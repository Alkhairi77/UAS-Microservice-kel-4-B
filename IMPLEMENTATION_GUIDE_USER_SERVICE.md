# 🔧 Rekomendasi Implementasi untuk Poin 1.a (User Service)

Berdasarkan review code yang sudah ada, berikut adalah rekomendasi improvement untuk **melengkapi poin 1.d (Correlation ID) dan 1.e (Distributed Logging)** di User Service Anda.

---

## ✅ Status Saat Ini: USER SERVICE

**Yang sudah OK:**
- ✅ Register dengan validasi
- ✅ Login dengan rate limiting
- ✅ User profile CRUD
- ✅ Error handling dengan try-catch
- ✅ Unit tests lengkap
- ✅ Database migrations

**Yang perlu ditambah:**
- ⚠️ Middleware Correlation ID
- ⚠️ Distributed logging dengan context
- ⚠️ API response format yang konsisten untuk integrate dengan Service C

---

## 1️⃣ Implementasi Correlation ID Middleware

### Step 1: Create Middleware
Buat file: `app/Http/Middleware/CorrelationIdMiddleware.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CorrelationIdMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. Cek apakah request sudah memiliki correlation ID
        $correlationId = $request->header('X-Correlation-ID') 
            ?? Str::uuid()->toString();

        // 2. Simpan ke request attributes (bisa diakses di controller)
        $request->attributes->set('correlation_id', $correlationId);

        // 3. Simpan ke logging context (otomatis di-attach ke semua log)
        \Log::withContext([
            'correlation_id' => $correlationId,
            'service' => 'user-service',
            'request_path' => $request->path(),
            'request_method' => $request->method(),
        ]);

        // 4. Proses request
        $response = $next($request);

        // 5. Attach correlation ID ke response header
        return $response->header('X-Correlation-ID', $correlationId);
    }
}
```

### Step 2: Register Middleware di Kernel

Edit `app/Http/Kernel.php`:

```php
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // ... middleware yang sudah ada
        \App\Http\Middleware\CorrelationIdMiddleware::class, // ← Tambah baris ini
    ];

    // ... rest of the code
}
```

---

## 2️⃣ Implementasi Distributed Logging

### Step 1: Create Custom Log Formatter

Buat file: `app/Logging/CustomFormatter.php`

```php
<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

class CustomFormatter extends JsonFormatter
{
    /**
     * Format the given log record.
     */
    public function format(LogRecord $record): string
    {
        $record->extra = array_merge($record->extra, [
            'service' => config('app.service_name', 'user-service'),
            'timestamp' => $record->datetime->format('Y-m-d H:i:s.u'),
            'environment' => config('app.env'),
        ]);

        return parent::format($record);
    }
}
```

### Step 2: Update Logging Configuration

Edit `config/logging.php`:

```php
<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'formatter' => \App\Logging\CustomFormatter::class, // ← Pakai custom formatter
            'formatter_with' => [
                'includeStacktraces' => true,
                'maxDepth' => 3,
            ],
        ],

        // Optional: Channel khusus untuk microservice logs
        'microservice' => [
            'driver' => 'single',
            'path' => storage_path('logs/microservice.log'),
            'level' => 'info',
            'formatter' => \App\Logging\CustomFormatter::class,
        ],
    ],
];
```

### Step 3: Add Service Name di Config

Edit `.env`:

```
APP_SERVICE_NAME=user-service
```

Atau update di `config/app.php`:

```php
'service_name' => env('APP_SERVICE_NAME', 'user-service'),
```

---

## 3️⃣ Update Controllers dengan Distributed Logging

### Update AuthenticatedSessionController

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $correlationId = $request->attributes->get('correlation_id');

        // Log attempt
        \Log::info('User login attempt', [
            'correlation_id' => $correlationId,
            'email' => $request->email,
            'ip' => $request->ip(),
        ]);

        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = Auth::user();
            $user->updateLastLogin();

            // Log success
            \Log::info('User login successful', [
                'correlation_id' => $correlationId,
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
            ]);

            // Redirect berdasarkan role
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isPerusahaan()) {
                if (!$user->hasValidPerusahaan()) {
                    \Log::warning('User perusahaan missing company profile', [
                        'correlation_id' => $correlationId,
                        'user_id' => $user->id,
                    ]);
                    return redirect()->route('perusahaan.create')
                        ->with('info', 'Silakan lengkapi profil perusahaan Anda terlebih dahulu.');
                }
                return redirect()->intended(route('perusahaan.dashboard'));
            } else {
                Auth::logout();
                \Log::warning('Invalid user role', [
                    'correlation_id' => $correlationId,
                    'user_id' => $user->id,
                    'role' => $user->role,
                ]);
                return redirect()->route('login')
                    ->with('error', 'Role pengguna tidak valid.');
            }
        } catch (\Exception $e) {
            Auth::logout();
            Session::invalidate();
            Session::regenerateToken();

            // Log error
            \Log::error('Login error', [
                'correlation_id' => $correlationId,
                'exception' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat login: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        $correlationId = $request->attributes->get('correlation_id');
        $user = Auth::user();

        \Log::info('User logout', [
            'correlation_id' => $correlationId,
            'user_id' => $user?->id,
            'user_email' => $user?->email,
        ]);

        Auth::guard('web')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return redirect('/');
    }
}
```

### Update UserController (untuk CRUD)

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $correlationId = request()->attributes->get('correlation_id');

        \Log::info('Fetching users list', [
            'correlation_id' => $correlationId,
        ]);

        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $correlationId = $request->attributes->get('correlation_id');

        \Log::info('Creating new user', [
            'correlation_id' => $correlationId,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'role' => ['required', 'string', Rule::in(['admin', 'perusahaan'])],
                'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
                'notes' => ['nullable', 'string'],
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status'],
                'notes' => $validated['notes'],
            ]);

            \Log::info('User created successfully', [
                'correlation_id' => $correlationId,
                'user_id' => $user->id,
                'user_email' => $user->email,
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil dibuat.');
        } catch (\Exception $e) {
            \Log::error('User creation failed', [
                'correlation_id' => $correlationId,
                'exception' => $e->getMessage(),
            ]);

            return redirect()->route('users.create')
                ->with('error', 'Gagal membuat user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, User $user)
    {
        $correlationId = $request->attributes->get('correlation_id');

        \Log::info('Updating user', [
            'correlation_id' => $correlationId,
            'user_id' => $user->id,
        ]);

        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'role' => ['required', 'string', Rule::in(['admin', 'perusahaan'])],
                'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
                'notes' => ['nullable', 'string'],
            ]);

            $user->update($validated);

            \Log::info('User updated successfully', [
                'correlation_id' => $correlationId,
                'user_id' => $user->id,
            ]);

            return redirect()->route('users.index')
                ->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            \Log::error('User update failed', [
                'correlation_id' => $correlationId,
                'user_id' => $user->id,
                'exception' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function destroy(User $user)
    {
        $correlationId = request()->attributes->get('correlation_id');

        \Log::info('Deleting user', [
            'correlation_id' => $correlationId,
            'user_id' => $user->id,
        ]);

        if ($user->id === auth()->id()) {
            \Log::warning('User attempted self-deletion', [
                'correlation_id' => $correlationId,
                'user_id' => $user->id,
            ]);

            return redirect()->route('users.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        if ($user->status !== 'inactive') {
            \Log::warning('Attempted to delete active user', [
                'correlation_id' => $correlationId,
                'user_id' => $user->id,
                'status' => $user->status,
            ]);

            return redirect()->route('users.index')
                ->with('error', 'User hanya dapat dihapus jika statusnya non-aktif.');
        }

        $user->delete();

        \Log::info('User deleted successfully', [
            'correlation_id' => $correlationId,
            'deleted_user_id' => $user->id,
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
```

---

## 4️⃣ Create API Response Helper (untuk Integration dengan Service C)

Buat file: `app/Http/Responses/ApiResponse.php`

```php
<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success($data = null, $message = 'Success', $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($correlationId = request()->attributes->get('correlation_id')) {
            $response['correlation_id'] = $correlationId;
        }

        return response()->json($response, $code);
    }

    public static function error($message = 'Error', $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        if ($correlationId = request()->attributes->get('correlation_id')) {
            $response['correlation_id'] = $correlationId;
        }

        return response()->json($response, $code);
    }

    public static function paginated($data, $message = 'Success'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'count' => $data->count(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
            ],
        ];

        if ($correlationId = request()->attributes->get('correlation_id')) {
            $response['correlation_id'] = $correlationId;
        }

        return response()->json($response);
    }
}
```

---

## 5️⃣ Unit Test untuk Correlation ID & Logging

Buat file: `tests/Feature/CorrelationIdTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CorrelationIdTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test correlation ID is generated when not provided
     */
    public function test_correlation_id_is_generated_when_not_provided(): void
    {
        $response = $this->get('/login');

        $this->assertTrue(
            Str::isUuid($response->header('X-Correlation-ID')),
            'Correlation ID should be a valid UUID'
        );
    }

    /**
     * Test correlation ID is preserved from request
     */
    public function test_correlation_id_is_preserved_from_request(): void
    {
        $correlationId = 'test-correlation-id-12345';

        $response = $this->withHeaders([
            'X-Correlation-ID' => $correlationId,
        ])->get('/login');

        $this->assertEquals(
            $correlationId,
            $response->header('X-Correlation-ID'),
            'Correlation ID from request should be preserved in response'
        );
    }

    /**
     * Test correlation ID is included in login response
     */
    public function test_correlation_id_is_included_in_authenticated_response(): void
    {
        $user = User::factory()->create();
        $correlationId = 'test-id-' . Str::uuid();

        $response = $this->withHeaders([
            'X-Correlation-ID' => $correlationId,
        ])->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertEquals(
            $correlationId,
            $response->header('X-Correlation-ID')
        );
    }

    /**
     * Test correlation ID persists across redirects
     */
    public function test_correlation_id_persists_across_redirects(): void
    {
        $correlationId = 'test-' . Str::uuid();

        $response = $this->withHeaders([
            'X-Correlation-ID' => $correlationId,
        ])->get('/logout');

        $this->assertEquals(
            $correlationId,
            $response->header('X-Correlation-ID')
        );
    }
}
```

---

## 6️⃣ Proof of Distributed Logging

Saat request masuk ke User Service dengan correlation ID:

### Log Output Example:

```json
{
  "message": "User login attempt",
  "context": {
    "correlation_id": "550e8400-e29b-41d4-a716-446655440000",
    "email": "user@example.com",
    "ip": "192.168.1.100",
    "service": "user-service",
    "request_path": "/login",
    "request_method": "POST"
  },
  "level": "INFO",
  "timestamp": "2025-12-15 10:30:15.234567"
}

{
  "message": "User login successful",
  "context": {
    "correlation_id": "550e8400-e29b-41d4-a716-446655440000",
    "user_id": 1,
    "user_email": "user@example.com",
    "user_role": "perusahaan",
    "service": "user-service"
  },
  "level": "INFO",
  "timestamp": "2025-12-15 10:30:15.456789"
}
```

---

## ✅ Checklist Implementasi

- [ ] Create `app/Http/Middleware/CorrelationIdMiddleware.php`
- [ ] Register middleware di `app/Http/Kernel.php`
- [ ] Create `app/Logging/CustomFormatter.php`
- [ ] Update `config/logging.php` dengan custom formatter
- [ ] Add `APP_SERVICE_NAME` di `.env`
- [ ] Update all controllers dengan logging statements
- [ ] Create `app/Http/Responses/ApiResponse.php`
- [ ] Add correlation ID unit tests
- [ ] Test logs with `tail -f storage/logs/laravel.log`
- [ ] Verify correlation ID flow dengan HTTP request (gunakan Postman)

---

## 🧪 Testing Correlation ID

### Dengan Postman

1. **Request tanpa Correlation ID:**
```
GET http://localhost:8000/login
Response Headers: X-Correlation-ID: 550e8400-e29b-41d4-a716-446655440000
```

2. **Request dengan Correlation ID:**
```
GET http://localhost:8000/login
Headers: X-Correlation-ID: my-custom-trace-id-123
Response Headers: X-Correlation-ID: my-custom-trace-id-123
```

3. **Check logs:**
```bash
tail -f storage/logs/laravel.log | grep "my-custom-trace-id-123"
```

---

## 📊 Expected Log Output (Distributed Tracing)

Saat request dari Service C ke User Service:

```
Service C sends:
POST /api/users
Headers: X-Correlation-ID: trace-abc123
         Authorization: Bearer token-xyz

User Service logs:
[2025-12-15 10:30:00] INFO trace-abc123 Received request from Service C
[2025-12-15 10:30:00] INFO trace-abc123 User authenticated
[2025-12-15 10:30:00] INFO trace-abc123 Returning user profile

Service C logs:
[2025-12-15 10:30:00] INFO trace-abc123 Calling User Service...
[2025-12-15 10:30:01] INFO trace-abc123 User Service response received
[2025-12-15 10:30:01] INFO trace-abc123 Integrating data from services
[2025-12-15 10:30:01] INFO trace-abc123 Response sent to client
```

---

**Status:** Siap untuk implementasi! 🚀

