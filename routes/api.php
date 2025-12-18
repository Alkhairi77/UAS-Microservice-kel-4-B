<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'service' => 'User Service',
        'timestamp' => now()
    ]);
});

// API Authentication Routes (Public - no middleware)
Route::post('/login', function (Request $request) {
        $correlationId = $request->header('X-Correlation-ID') ?? 'N/A';

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        Log::info('User Service - Login attempt', [
            'correlation_id' => $correlationId,
            'email' => $credentials['email'],
        ]);

        // Attempt login
        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
            $user = \Illuminate\Support\Facades\Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;

            Log::info('User Service - Login successful', [
                'correlation_id' => $correlationId,
                'user_id' => $user->id,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ],
                'correlation_id' => $correlationId
            ], 200);
        }

        Log::warning('User Service - Login failed', [
            'correlation_id' => $correlationId,
            'email' => $credentials['email'],
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials',
            'correlation_id' => $correlationId
        ], 401);
    })->name('api.login');

// API Register
Route::post('/register', function (Request $request) {
        $correlationId = $request->header('X-Correlation-ID') ?? 'N/A';

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        Log::info('User Service - Register attempt', [
            'correlation_id' => $correlationId,
            'email' => $validated['email'],
        ]);

        try {
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => 'perusahaan'
            ]);

            $token = $user->createToken('api-token')->plainTextToken;

            Log::info('User Service - Registration successful', [
                'correlation_id' => $correlationId,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ],
                'correlation_id' => $correlationId
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
                'correlation_id' => $correlationId
            ], 422);
        }
    })->name('api.register');

// Protected API Routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Get current user profile
    Route::get('/profile', function (Request $request) {
        $correlationId = $request->header('X-Correlation-ID') ?? 'N/A';

        Log::info('User Service - Profile requested', [
            'correlation_id' => $correlationId,
            'user_id' => $request->user()->id ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully',
            'data' => $request->user(),
            'correlation_id' => $correlationId
        ], 200);
    })->name('api.profile');

    // Logout
    Route::post('/logout', function (Request $request) {
        $correlationId = $request->header('X-Correlation-ID') ?? 'N/A';

        $request->user()->tokens()->delete();

        Log::info('User Service - Logout', [
            'correlation_id' => $correlationId,
            'user_id' => $request->user()->id ?? null,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
            'correlation_id' => $correlationId
        ], 200);
    })->name('api.logout');

    // User CRUD API endpoints
    // Get all users
    Route::get('/users', function (Request $request) {
        try {
            $query = \App\Models\User::query();

            // Filter by role
            if ($request->has('role')) {
                $query->where('role', $request->role);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Search by name or email
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            $perPage = $request->input('per_page', 15);
            $users = $query->latest()->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'last_page' => $users->lastPage(),
                ],
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage(),
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 500);
        }
    })->name('api.users.index');

    // Get specific user
    Route::get('/users/{id}', function (Request $request, $id) {
        try {
            $user = \App\Models\User::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => $user,
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage(),
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 500);
        }
    })->name('api.users.show');

    // Create new user
    Route::post('/users', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,perusahaan',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            $user = \App\Models\User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status'] ?? 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user,
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user',
                'error' => $e->getMessage(),
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 500);
        }
    })->name('api.users.store');

    // Update user
    Route::put('/users/{id}', function (Request $request, $id) {
        try {
            $user = \App\Models\User::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|min:6',
                'role' => 'required|in:admin,perusahaan',
                'status' => 'nullable|in:active,inactive',
            ]);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'status' => $validated['status'] ?? $user->status,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = bcrypt($validated['password']);
            }

            $user->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => $user->fresh(),
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage(),
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 500);
        }
    })->name('api.users.update');

    // Delete user
    Route::delete('/users/{id}', function (Request $request, $id) {
        try {
            $user = \App\Models\User::findOrFail($id);

            // Prevent self-deletion
            if ($user->id === $request->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete your own account',
                    'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage(),
                'correlation_id' => $request->header('X-Correlation-ID') ?? 'N/A'
            ], 500);
        }
    })->name('api.users.destroy');
});

?>