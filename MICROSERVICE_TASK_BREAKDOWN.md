# 📋 Breakdown Tugas Arsitektur Berbasis Layanan (Microservice)

## 📌 Overview
Tugas ini adalah implementasi **Arsitektur Microservice** dengan 3 service utama yang saling berkomunikasi, ditambah middleware dan distributed logging.

---

## ✅ POIN 1.a: User Authentication Service (TUGAS ANDA)

### Deskripsi
Membangun service independen untuk autentikasi user dengan endpoint CRUD lengkap.

### Requirements
| Requirement | Status | File/Bukti |
|-------------|--------|-----------|
| **Endpoint: Register** | ✅ | `RegisteredUserController.php` (store method) |
| **Endpoint: Login** | ✅ | `AuthenticatedSessionController.php` (store method) |
| **Endpoint: User Profile (Read)** | ✅ | `ProfileController.php` (edit method) |
| **Endpoint: User Profile (Update)** | ✅ | `ProfileController.php` (update method) |
| **Endpoint: User CRUD** | ✅ | `UserController.php` (index, create, store, show, edit, update, destroy) |
| **Validasi Input** | ✅ | `LoginRequest.php`, `ProfileUpdateRequest.php`, rules di controller |
| **Error Handling** | ✅ | Try-catch blocks, validation exceptions, custom error responses |
| **Unit Test** | ✅ | `UserTest.php`, `LoginRequestTest.php`, `UserCrudTest.php`, `AuthenticationTest.php`, `RegistrationTest.php`, `ProfileTest.php` |

### API Endpoints yang Harus Ada
```
POST   /register                    - Registrasi user baru
POST   /login                       - Login user
POST   /logout                      - Logout user
GET    /profile                     - Get user profile
PATCH  /profile                     - Update user profile
DELETE /profile                     - Delete user account

// CRUD Admin
GET    /users                       - List semua users
POST   /users                       - Create user baru
GET    /users/{id}                  - Get user detail
PUT    /users/{id}                  - Update user
DELETE /users/{id}                  - Delete user
GET    /users/{id}/password/edit    - Show password change form
PUT    /users/{id}/password         - Update password
PUT    /users/{id}/toggle-status    - Toggle active/inactive status
```

### ✨ Checklist Kelengkapan (Anda)
- [x] Register dengan validasi email unique dan password strength
- [x] Login dengan rate limiting & remember me
- [x] User profile view & update
- [x] User CRUD dengan role-based access (admin only)
- [x] Validasi input di semua endpoint
- [x] Error handling dengan try-catch
- [x] Unit tests minimal 6 test cases per controller
- [x] Database migrations & seeders
- [x] Timestamps (created_at, updated_at)
- [x] Role & status fields

---

## 📦 POIN 1.b: Additional Service (1 Service, Bukan User)

### Deskripsi
Buat 1 service tambahan dengan domain bisnis yang relevan ke project PBL Anda (bukan user service).

### Contoh Service yang Relevan
Berdasarkan project PBL (Waste Management):
- **Waste Inventory Service** (Jenis Limbah, Penyimpanan Limbah)
- **Waste Report Service** (Laporan Harian, Laporan Hasil Pengelolaan)
- **Waste Processing Service** (Pengelolaan Limbah)
- **Vendor Service** (Vendor management)

### Requirements
| Requirement | Deskripsi |
|-------------|-----------|
| **CRUD** | Create, Read, Update, Delete resource domain bisnis |
| **Validasi Input** | Form request dengan rules validation |
| **Error Handling** | Exception handling & consistent error response |
| **Unit Test** | Minimal 1 test case per method (index, store, show, update, destroy) |

### Template API Endpoints
```
GET    /api/[resource]              - List semua items
POST   /api/[resource]              - Create item baru
GET    /api/[resource]/{id}         - Get detail item
PUT    /api/[resource]/{id}         - Update item
DELETE /api/[resource]/{id}         - Delete item
```

### Minimal Code Structure
```
app/Http/Controllers/[ResourceName]Controller.php
app/Http/Requests/[ResourceName]/Store[ResourceName]Request.php
app/Http/Requests/[ResourceName]/Update[ResourceName]Request.php
app/Models/[ResourceName].php
database/migrations/create_[resource_names]_table.php
tests/Feature/[ResourceName]Test.php
```

---

## 🔗 POIN 1.c: Orchestrator/Integration Service

### Deskripsi
Membuat service yang **mengkombinasikan data dari User Service (1.a) dan Service B (1.b)** dengan komunikasi HTTP/API.

### Requirements Detail

#### 1️⃣ Mengirim dan Menerima Correlation ID
**Apa itu Correlation ID?**
- Unique ID yang mengikuti request dari service ke service
- Membantu tracking request flow di logging terdistribusi
- Format: UUID atau unique string

**Implementasi:**
```
Request Flow:
Client → Service C → User Service
           ↓          ↓
           Correlation-ID: "uuid-xxxxx"
           (forward ke service lain)
```

**Cara Implementasi:**
- Generate correlation ID di service C
- Forward ke User Service melalui header: `X-Correlation-ID`
- Forward ke Service B melalui header: `X-Correlation-ID`
- Semua service log dengan correlation ID ini

#### 2️⃣ Meneruskan Authorization Token
**Konsep:**
- User login ke Service C
- Service C forward token ke User Service untuk validasi
- Service C forward token ke Service B jika perlu

**Implementasi:**
```php
// Di Service C, saat call User Service
$token = $request->bearerToken(); // Ambil dari request
$response = Http::withHeaders([
    'Authorization' => 'Bearer ' . $token,
    'X-Correlation-ID' => $correlationId
])->get('http://user-service/api/profile');
```

#### 3️⃣ Error Handling Konsisten untuk Kegagalan Service Lain
**Skenario:**
```
Service C call User Service → User Service error 500
Service C harus:
- Log error dengan correlation ID
- Return consistent error response ke client
- Jangan expose internal error detail
```

**Implementasi:**
```php
try {
    $response = Http::timeout(5)->get('http://user-service/api/data');
    
    if (!$response->successful()) {
        // Log dengan correlation ID
        logger()->error('User Service call failed', [
            'correlation_id' => $correlationId,
            'status' => $response->status(),
            'error' => $response->json()
        ]);
        
        return response()->json([
            'error' => 'External service unavailable',
            'correlation_id' => $correlationId
        ], 503);
    }
} catch (\Exception $e) {
    logger()->error('User Service call exception', [
        'correlation_id' => $correlationId,
        'exception' => $e->getMessage()
    ]);
    
    return response()->json([
        'error' => 'Service unavailable',
        'correlation_id' => $correlationId
    ], 503);
}
```

#### 4️⃣ Unit Test Minimal 1
- Test successful call ke user service + service B
- Test error handling saat service lain error
- Test correlation ID diteruskan dengan benar

### Contoh Use Case
**Laporan Limbah Terintegrasi:**
```
GET /api/integrated-report/{id}

Response harus kombinasi:
{
  "report_id": "xxx",
  "user": { 
    "id": "yyy",
    "name": "PT Maju Jaya"  // dari User Service
  },
  "waste_details": {
    "total": 100,
    "type": "Limbah B3"     // dari Service B
  },
  "correlation_id": "uuid-xxxxx"
}
```

---

## 🔄 POIN 1.d: Middleware Correlation ID

### Deskripsi
Buat middleware yang **secara otomatis menangani Correlation ID** di semua service.

### Fungsi Middleware
1. **Jika request sudah ada `X-Correlation-ID` header** → gunakan itu
2. **Jika tidak ada** → generate correlation ID baru
3. **Simpan ke request context** → bisa diakses di controller/service
4. **Attach ke response header** → client bisa tracking

### Implementasi Template
```php
// app/Http/Middleware/CorrelationIdMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CorrelationIdMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Cek jika ada correlation ID dari request
        $correlationId = $request->header('X-Correlation-ID') 
            ?? Str::uuid();

        // 2. Simpan di context (bisa diakses via request atau context)
        $request->attributes->set('correlation_id', $correlationId);
        
        // Optional: simpan ke logging context
        \Log::withContext(['correlation_id' => $correlationId]);

        // 3. Forward ke service lain
        $response = $next($request);

        // 4. Attach ke response header
        return $response->header('X-Correlation-ID', $correlationId);
    }
}
```

### Registrasi di Kernel
```php
// app/Http/Kernel.php
protected $middleware = [
    // ...
    \App\Http\Middleware\CorrelationIdMiddleware::class,
];
```

### Penggunaan di Controller
```php
public function someAction(Request $request)
{
    $correlationId = $request->attributes->get('correlation_id');
    
    // Log dengan correlation ID
    logger()->info('Action executed', [
        'correlation_id' => $correlationId
    ]);
}
```

---

## 📊 POIN 1.e: Distributed Logging

### Deskripsi
Implementasi logging yang konsisten di semua service dengan context yang sama.

### 3 Aspek Utama

#### 1️⃣ Log Context
Setiap log harus include:
```json
{
  "correlation_id": "uuid-xxxxx",
  "user_id": 123,
  "service": "user-service",
  "timestamp": "2025-12-15T10:30:00Z",
  "level": "INFO",
  "message": "User login successful",
  "request_id": "req-yyyyy"
}
```

#### 2️⃣ Logging Format Konsisten
**Format standar untuk semua service:**
```
[TIMESTAMP] [LEVEL] [SERVICE] [CORRELATION_ID] [MESSAGE] [CONTEXT]

Contoh:
[2025-12-15 10:30:00] INFO user-service correlation_id=uuid-xxxxx User login successful {user_id: 123, ip: 192.168.1.1}
```

#### 3️⃣ Proof of Distributed Tracing
**Berikan cuplikan log dari request yang melewati multiple services:**

Contoh skenario:
```
Request: Client → Service C → User Service → Service B

Logs yang diharapkan:

[Service C] INFO correlation_id=uuid-xxxxx Received request from client
[Service C] INFO correlation_id=uuid-xxxxx Calling User Service...
[User Service] INFO correlation_id=uuid-xxxxx Received request from Service C
[User Service] INFO correlation_id=uuid-xxxxx User authenticated
[User Service] INFO correlation_id=uuid-xxxxx Returning user data
[Service C] INFO correlation_id=uuid-xxxxx Received response from User Service
[Service C] INFO correlation_id=uuid-xxxxx Calling Service B...
[Service B] INFO correlation_id=uuid-xxxxx Received request from Service C
[Service B] INFO correlation_id=uuid-xxxxx Resource retrieved
[Service C] INFO correlation_id=uuid-xxxxx Integrating data from all services
[Service C] INFO correlation_id=uuid-xxxxx Response sent to client
```

### Implementasi Logging dengan Context
```php
// Di setiap service
\Log::withContext([
    'correlation_id' => $correlationId,
    'service' => 'user-service',
    'user_id' => auth()->id(),
    'request_path' => request()->path()
]);

// Atau gunakan custom log channel
logger()->info('Action executed', [
    'correlation_id' => $correlationId,
    'action' => 'user_login',
    'user_email' => $user->email
]);
```

### Konfigurasi Log
```php
// config/logging.php
'channels' => [
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'formatter' => \Monolog\Formatter\JsonFormatter::class,
    ],
]
```

---

## 🌐 POIN 2: GitHub Repository

### Struktur Repository
```
UAS-Microservice/
├── README.md (dokumentasi lengkap)
├── MICROSERVICE_TASK_BREAKDOWN.md (file ini)
├── docker-compose.yml (optional: untuk run semua service)
├── .github/workflows/ (CI/CD pipelines)
│
├── services/
│   ├── user-service/ (Poin 1.a)
│   ├── [service-b]/ (Poin 1.b)
│   └── orchestrator-service/ (Poin 1.c)
│
└── docs/
    ├── API.md (API documentation)
    ├── SETUP.md (installation guide)
    └── ARCHITECTURE.md (architecture diagram)
```

### README Harus Mencakup
- [ ] Deskripsi project
- [ ] Architecture diagram
- [ ] Setup instructions per service
- [ ] API endpoints documentation
- [ ] Correlation ID flow explanation
- [ ] Distributed logging proof
- [ ] Team members & task distribution
- [ ] How to run & test

---

## 👥 REKOMENDASI PEMBAGIAN TASK UNTUK KELOMPOK

Asumsi: kelompok 4 orang (sesuaikan dengan jumlah actual)

### 🎯 Anggota 1: User Authentication Service (ANDA - Selesai ✅)
**Status:** Sedang/selesai  
**Tugas:**
- [x] Implementasi login, register, profile, CRUD
- [x] Validasi input & error handling
- [x] Unit tests
- [ ] Setup API response format konsisten
- [ ] Tambah middleware untuk handle correlation ID (poin 1.d)
- [ ] Tambah logging dengan context (poin 1.e)
- [ ] Documentation API endpoints

**Timeline:** Sudah on track, tinggal refine logging & middleware

---

### 🎯 Anggota 2: Service B (Additional Service)
**Tugas:**
- [ ] Pilih domain (contoh: Waste Type Service, Vendor Service)
- [ ] Design database schema
- [ ] Implement CRUD endpoints
- [ ] Validasi input
- [ ] Error handling
- [ ] Unit tests (minimal 5 test cases)
- [ ] Implement correlation ID middleware (poin 1.d)
- [ ] Setup distributed logging (poin 1.e)

**Timeline:** ~1-2 minggu (depending pada complexity)

**Rekomendasi Resource:**
- Waste Type / Kategori Limbah Service
- Database: `jenis_limbahs`, `kategori_artikel`
- Endpoints: CRUD jenis limbah dengan validasi & categorization

---

### 🎯 Anggota 3: Orchestrator Service (Integration)
**Tugas:**
- [ ] Setup service baru
- [ ] Implement API yang call User Service + Service B
- [ ] Implement correlation ID handling (mengirim & menerima)
- [ ] Implement token forwarding
- [ ] Implement error handling konsisten (poin 1.c)
- [ ] Unit tests integration (minimal 3-4 test cases)
- [ ] Setup distributed logging

**Timeline:** ~2-3 minggu (blocking: menunggu service B selesai)

**Contoh Use Case:**
```
GET /api/integrated-report/{report_id}

1. Call User Service → get user data
2. Call Service B → get waste data
3. Combine → return integrated data
4. Log everything dengan correlation ID
```

---

### 🎯 Anggota 4: DevOps / Infrastructure / Documentation
**Tugas:**
- [ ] Setup middleware Correlation ID di seluruh service (poin 1.d)
- [ ] Setup distributed logging system (poin 1.e)
- [ ] Setup GitHub repository & CI/CD
- [ ] Create comprehensive documentation
- [ ] Create architecture diagram
- [ ] Setup docker-compose untuk run semua service
- [ ] Testing & QA semua service
- [ ] Proof of distributed tracing (compile log examples)

**Timeline:** Paralel dengan development (kontinyu refinement)

---

## 📋 Checklist Per Poin Tugas

### Poin 1.a (USER SERVICE) ✅ DONE
- [x] Register endpoint dengan validasi
- [x] Login endpoint dengan rate limiting
- [x] User profile endpoints (view & update)
- [x] User CRUD (admin only)
- [x] Input validation
- [x] Error handling
- [x] Unit tests
- [ ] ⚠️ TODO: Tambah correlation ID support
- [ ] ⚠️ TODO: Tambah distributed logging

### Poin 1.b (SERVICE B) ⏳ IN PROGRESS
- [ ] Design & implement CRUD
- [ ] Validasi input
- [ ] Error handling
- [ ] Unit tests
- [ ] Correlation ID support
- [ ] Distributed logging

### Poin 1.c (ORCHESTRATOR) ⏳ IN PROGRESS
- [ ] Setup service
- [ ] Call User Service
- [ ] Call Service B
- [ ] Correlation ID forwarding
- [ ] Token forwarding
- [ ] Error handling
- [ ] Unit tests

### Poin 1.d (MIDDLEWARE) ⏳ IN PROGRESS
- [ ] Create middleware di setiap service
- [ ] Generate/forward correlation ID
- [ ] Attach ke response
- [ ] Register di Kernel

### Poin 1.e (DISTRIBUTED LOGGING) ⏳ IN PROGRESS
- [ ] Setup log context
- [ ] Consistent log format
- [ ] Proof of tracing (log samples)

### Poin 2 (GITHUB) ⏳ IN PROGRESS
- [ ] Repository setup
- [ ] README
- [ ] API documentation
- [ ] Architecture docs
- [ ] Team distribution docs

---

## ⚡ Quick Summary

| Poin | Nama | Owner | Blocking | Priority |
|------|------|-------|----------|----------|
| 1.a | User Service | Anda | - | HIGH ✅ |
| 1.b | Service B | Anggota 2 | - | HIGH |
| 1.c | Orchestrator | Anggota 3 | 1.b ⬅️ | HIGH |
| 1.d | Correlation ID Middleware | Anggota 4 | - | MEDIUM |
| 1.e | Distributed Logging | Anggota 4 | 1.d | MEDIUM |
| 2 | GitHub & Docs | Anggota 4 | All | MEDIUM |

---

## 🚀 Recommended Workflow

1. **Week 1:** 
   - Anda: Refine User Service (add logging + middleware)
   - Anggota 2: Build Service B
   - Anggota 4: Setup GitHub & infrastructure

2. **Week 2:**
   - Anggota 3: Start Orchestrator Service
   - Anggota 4: Implement middleware & logging di semua service

3. **Week 3:**
   - Integration testing
   - Documentation & proof of distributed tracing
   - Final polish & deployment

---

## 📚 Reference Links
- [Laravel Sanctum (API Token)](https://laravel.com/docs/sanctum)
- [Laravel HTTP Client](https://laravel.com/docs/http-client)
- [Distributed Tracing Concepts](https://opentelemetry.io/docs/concepts/)
- [Correlation ID Best Practices](https://www.baeldung.com/correlation-id)

