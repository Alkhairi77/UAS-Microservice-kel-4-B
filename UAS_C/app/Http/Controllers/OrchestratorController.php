<?php

namespace App\Http\Controllers;

use App\Services\JenisLimbahServiceClient;
use App\Services\UserServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrchestratorController extends Controller
{
    protected UserServiceClient $userService;
    protected JenisLimbahServiceClient $jenisLimbahService;

    public function __construct(
        UserServiceClient $userService,
        JenisLimbahServiceClient $jenisLimbahService
    ) {
        $this->userService = $userService;
        $this->jenisLimbahService = $jenisLimbahService;
    }

    /**
     * Login user and get their profile + available jenis limbah.
     */
    public function loginWithLimbahData(Request $request)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Orchestrator - Login with Limbah Data', [
            'correlation_id' => $correlationId,
            'email' => $request->email,
        ]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'correlation_id' => $correlationId,
            ], 422);
        }

        try {
            // Step 1: Login to User Service
            $loginResult = $this->userService->login(
                $request->email,
                $request->password,
                $correlationId
            );

            if (!$loginResult['success']) {
                Log::warning('Orchestrator - Login failed', [
                    'correlation_id' => $correlationId,
                    'error' => $loginResult['error'],
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Login failed',
                    'error' => $loginResult['error'],
                    'correlation_id' => $correlationId,
                ], $loginResult['status']);
            }

            $token = $loginResult['data']['token'] ?? null;
            $userData = $loginResult['data']['user']
                ?? ($loginResult['data']['data'] ?? null);

            // Step 2: Get profile from User Service (if token available)
            if ($token) {
                $profileResult = $this->userService->getProfile($token, $correlationId);
                if ($profileResult['success']) {
                    $userData = $profileResult['data']['user']
                        ?? ($profileResult['data']['data'] ?? $userData);
                }
            }

            // Step 3: Get Jenis Limbah list (first 10 items)
            $limbahResult = $this->jenisLimbahService->getJenisLimbahs(
                ['per_page' => 10, 'status' => 'aktif'],
                $correlationId
            );

            $limbahData = [];
            if ($limbahResult['success']) {
                $limbahData = $limbahResult['data']['data'] ?? [];
            }

            Log::info('Orchestrator - Login with Limbah Data successful', [
                'correlation_id' => $correlationId,
                'user_id' => $userData['id'] ?? null,
                'limbah_count' => count($limbahData),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login successful with limbah data',
                'data' => [
                    'user' => $userData,
                    'token' => $token,
                    'jenis_limbah' => $limbahData,
                ],
                'correlation_id' => $correlationId,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Orchestrator - Error in loginWithLimbahData', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Orchestrator error',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }

    /**
     * Register new user and get available jenis limbah.
     */
    public function registerWithLimbahData(Request $request)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Orchestrator - Register with Limbah Data', [
            'correlation_id' => $correlationId,
            'email' => $request->email,
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
                'correlation_id' => $correlationId,
            ], 422);
        }

        try {
            // Step 1: Register to User Service
            $registerResult = $this->userService->register(
                $request->only(['name', 'email', 'password', 'password_confirmation']),
                $correlationId
            );

            if (!$registerResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration failed',
                    'error' => $registerResult['error'],
                    'correlation_id' => $correlationId,
                ], $registerResult['status']);
            }

            // Step 2: Get Jenis Limbah list
            $limbahResult = $this->jenisLimbahService->getJenisLimbahs(
                ['per_page' => 10, 'status' => 'aktif'],
                $correlationId
            );

            $limbahData = [];
            if ($limbahResult['success']) {
                $limbahData = $limbahResult['data']['data'] ?? [];
            }

            Log::info('Orchestrator - Register with Limbah Data successful', [
                'correlation_id' => $correlationId,
                'limbah_count' => count($limbahData),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful with limbah data',
                'data' => [
                    'user' => $registerResult['data']['user'] ?? null,
                    'jenis_limbah' => $limbahData,
                ],
                'correlation_id' => $correlationId,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Orchestrator - Error in registerWithLimbahData', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Orchestrator error',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }

    /**
     * Get user profile with their jenis limbah data.
     */
    public function getUserWithLimbah(Request $request)
    {
        $correlationId = $request->attributes->get('correlation_id');
        $token = $request->bearerToken();

        Log::info('Orchestrator - Get User with Limbah', [
            'correlation_id' => $correlationId,
        ]);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Authorization token required',
                'correlation_id' => $correlationId,
            ], 401);
        }

        try {
            // Step 1: Get user profile
            $profileResult = $this->userService->getProfile($token, $correlationId);

            if (!$profileResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get profile',
                    'error' => $profileResult['error'],
                    'correlation_id' => $correlationId,
                ], $profileResult['status']);
            }

            // Step 2: Get jenis limbah
            $limbahResult = $this->jenisLimbahService->getJenisLimbahs(
                ['status' => 'aktif'],
                $correlationId
            );

            return response()->json([
                'success' => true,
                'message' => 'User data with limbah retrieved successfully',
                'data' => [
                    'user' => $profileResult['data']['user']
                        ?? ($profileResult['data']['data'] ?? null),
                    'jenis_limbah' => $limbahResult['data']['data'] ?? [],
                ],
                'correlation_id' => $correlationId,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Orchestrator - Error in getUserWithLimbah', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Orchestrator error',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }

    /**
     * Get jenis limbah by category with logged user info.
     */
    public function getLimbahByCategory(Request $request, string $kategori)
    {
        $correlationId = $request->attributes->get('correlation_id');

        Log::info('Orchestrator - Get Limbah by Category', [
            'correlation_id' => $correlationId,
            'kategori' => $kategori,
        ]);

        try {
            // Get jenis limbah by category
            $limbahResult = $this->jenisLimbahService->getJenisLimbahs(
                ['kategori' => $kategori, 'status' => 'aktif'],
                $correlationId
            );

            if (!$limbahResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to get jenis limbah',
                    'error' => $limbahResult['error'],
                    'correlation_id' => $correlationId,
                ], $limbahResult['status']);
            }

            return response()->json([
                'success' => true,
                'message' => "Jenis limbah for category {$kategori} retrieved successfully",
                'data' => $limbahResult['data']['data'] ?? [],
                'correlation_id' => $correlationId,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Orchestrator - Error in getLimbahByCategory', [
                'correlation_id' => $correlationId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Orchestrator error',
                'error' => $e->getMessage(),
                'correlation_id' => $correlationId,
            ], 500);
        }
    }
}
