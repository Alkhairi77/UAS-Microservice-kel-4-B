# 👥 REKOMENDASI PEMBAGIAN TASK KELOMPOK

**Project:** UAS Arsitektur Berbasis Layanan (Microservice)  
**Deadline:** [Sesuaikan dengan jadwal]  
**Jumlah Anggota:** 4 orang  

---

## 📌 RINGKASAN PEMBAGIAN TUGAS

| Anggota | Tugas Utama | Blocking | Priority | Est. Jam |
|---------|------------|----------|----------|----------|
| **Anda** | User Service (1.a) + Poin 1.d & 1.e di User Service | - | HIGH | 20 jam |
| **Anggota 2** | Service B (1.b) - Waste Type/Vendor | - | HIGH | 25 jam |
| **Anggota 3** | Orchestrator Service (1.c) | Service B ⬅️ | HIGH | 30 jam |
| **Anggota 4** | DevOps/Docs/Middleware (1.d, 1.e, 2) | - | MEDIUM | 25 jam |

---

## 👤 ANGGOTA 1: ANDA - User Service (Poin 1.a)

### Status Saat Ini
✅ **70% Selesai**
- ✅ Register, Login, Profile CRUD
- ✅ Validasi input & error handling
- ✅ Unit tests lengkap
- ⚠️ Belum: Correlation ID middleware
- ⚠️ Belum: Distributed logging

### Deliverables
- [ ] User Service fully functional dengan logging
- [ ] API endpoints documentation
- [ ] Unit tests (sudah ada, tinggal update untuk logging)
- [ ] Correlation ID middleware integration

### Timeline
**Week 1 (7 hari):**
- Day 1-2: Implementasi Correlation ID Middleware
- Day 2-3: Setup Custom Log Formatter & Context
- Day 3-4: Update semua controllers dengan logging
- Day 4-5: Update unit tests untuk verify correlation ID
- Day 5-7: Testing & documentation

**Est. 20 jam kerja**

### Subtasks
```
1. Create CorrelationIdMiddleware.php
   - Generate UUID jika tidak ada
   - Simpan ke request attributes
   - Attach ke response header
   - Set logging context

2. Create CustomFormatter.php
   - Extend JsonFormatter
   - Add service name, timestamp, environment

3. Update config/logging.php
   - Register custom formatter
   - Setup log channels

4. Update Controllers:
   - AuthenticatedSessionController (login, logout)
   - RegisteredUserController (register)
   - ProfileController (profile CRUD)
   - UserController (admin CRUD)
   - Add logging statements di setiap method

5. Create Unit Tests
   - tests/Feature/CorrelationIdTest.php
   - Test UUID generation
   - Test correlation ID preservation
   - Test logging context

6. Create Documentation
   - API endpoints list
   - Correlation ID flow explanation
   - How to use logging in other services
```

### Success Criteria
- [ ] Correlation ID di-generate & di-forward dengan baik
- [ ] Semua logging statements include correlation ID
- [ ] Unit tests pass (khusus correlation ID)
- [ ] Log format konsisten (JSON)
- [ ] API response include correlation ID
- [ ] Documentation clear & complete

---

## 👤 ANGGOTA 2: Service B - Waste Type/Vendor Service (Poin 1.b)

### Deskripsi
Membangun CRUD service untuk **Waste Type (Jenis Limbah)** atau **Vendor Service** dengan database yang sudah ada di project.

### Pilihan Resource
**Option A: Waste Type Service** (RECOMMENDED)
```
Table: jenis_limbahs
Fields: id, nama, deskripsi, satuan_default, kategori, created_at, updated_at
Endpoints:
  - GET /api/waste-types (list semua)
  - POST /api/waste-types (create)
  - GET /api/waste-types/{id} (detail)
  - PUT /api/waste-types/{id} (update)
  - DELETE /api/waste-types/{id} (delete)
```

**Option B: Vendor Service**
```
Table: vendors
Fields: id, nama, email, phone, address, kategori_layanan
Endpoints:
  - GET /api/vendors
  - POST /api/vendors
  - GET /api/vendors/{id}
  - PUT /api/vendors/{id}
  - DELETE /api/vendors/{id}
```

### Deliverables
- [ ] Service B repository (baru atau dalam folder)
- [ ] Database schema & migrations
- [ ] CRUD endpoints (5 endpoints)
- [ ] Form Request validations
- [ ] Error handling
- [ ] 5+ unit tests
- [ ] Correlation ID middleware integration
- [ ] Logging dengan context
- [ ] API documentation

### Timeline
**Week 1 (7 hari):**
- Day 1-2: Setup project structure & database
- Day 2-3: Implement CRUD controllers
- Day 3-4: Form requests & validations
- Day 4-5: Unit tests
- Day 5-6: Logging & correlation ID
- Day 6-7: Testing & documentation

**Est. 25 jam kerja**

### Subtasks
```
1. Project Setup
   - Create folder services/waste-type-service/
   - composer create-project laravel/laravel
   - Setup .env & database

2. Database
   - Create/verify migration untuk jenis_limbahs
   - Create model WasteType
   - Create factory untuk testing

3. API Controllers
   - WasteTypeController dengan methods: index, store, show, update, destroy
   - JSON response format
   - Proper HTTP status codes

4. Validations
   - StoreWasteTypeRequest.php
   - UpdateWasteTypeRequest.php
   - Rules: required, unique, max length, etc.

5. Error Handling
   - Try-catch di setiap method
   - Custom error responses
   - Logging for errors

6. Unit Tests
   - tests/Feature/WasteTypeTest.php
   - Test create, read, update, delete
   - Test validation errors
   - Test authorization (if applicable)
   - Min. 5 test cases

7. Correlation ID & Logging
   - Copy middleware dari User Service
   - Setup logging dengan context
   - Verify correlation ID di logs

8. Documentation
   - README.md dengan setup instructions
   - API endpoints list
   - Example requests/responses
```

### Success Criteria
- [ ] CRUD endpoints working
- [ ] Validasi input prevent invalid data
- [ ] Error handling return proper messages
- [ ] Unit tests pass (min. 5)
- [ ] Correlation ID included in logs
- [ ] API response format consistent
- [ ] Documentation clear

---

## 👤 ANGGOTA 3: Orchestrator Service (Poin 1.c)

### Deskripsi
Service yang mengintegrasikan **User Service + Service B** dengan:
- Call ke User Service (get user data)
- Call ke Service B (get waste type data)
- Combine & return integrated response
- Forward correlation ID & authorization token
- Error handling konsisten

### Deliverables
- [ ] Orchestrator Service repository
- [ ] Integration endpoints (minimal 2-3 endpoints)
- [ ] Token forwarding implementation
- [ ] Correlation ID forwarding
- [ ] Error handling untuk service failures
- [ ] 3+ integration unit tests
- [ ] Logging & distributed tracing
- [ ] Documentation

### Timeline
**Week 1-2 (14 hari):**
- Day 1-2: Setup project (depends on Service B ready)
- Day 2-4: Implement HTTP client calls
- Day 4-5: Token & correlation ID forwarding
- Day 5-7: Error handling layer
- Day 7-10: Unit tests
- Day 10-12: Logging & testing
- Day 12-14: Integration testing & docs

**Est. 30 jam kerja (blocking: wait for Service B)**

### Subtasks
```
1. Project Setup
   - Create services/orchestrator-service/
   - Install Laravel
   - Setup HTTP client configuration

2. Service Clients
   - UserServiceClient.php (HTTP wrapper untuk User Service)
     Methods: authenticate, getUser, getUserProfile
   - WasteServiceClient.php (HTTP wrapper untuk Service B)
     Methods: getWasteType, listWasteTypes

3. Integration Controller
   - IntegrationController.php dengan endpoints:
     a) GET /api/user-waste-summary
        - Call User Service → get authenticated user
        - Call Waste Service → get waste types
        - Combine data → return integrated response
     
     b) GET /api/user-waste-details/{userId}/{wasteTypeId}
        - Call User Service → get user detail
        - Call Waste Service → get waste detail
        - Combine & return

4. Token & Correlation ID Forwarding
   - Extract bearer token dari request
   - Extract/generate correlation ID
   - Forward ke both services via headers:
     Authorization: Bearer {token}
     X-Correlation-ID: {id}

5. Error Handling
   - Handle User Service errors (401, 404, 500)
   - Handle Waste Service errors (404, 500)
   - Return consistent error response
   - Log failures dengan correlation ID
   - Implement retry logic (optional)

6. Unit Tests
   - tests/Feature/IntegrationTest.php
   - Test successful call ke both services
   - Test error handling (service timeout)
   - Test token forwarding
   - Test correlation ID forwarding
   - Min. 3 test cases

7. Logging & Tracing
   - Log each service call
   - Log response status
   - Log combined data
   - Include correlation ID in all logs

8. Documentation
   - README & setup
   - API endpoints
   - Example flow diagram
   - How to call from client
   - Error scenarios
```

### Example Implementation
```php
// IntegrationController.php
public function userWasteSummary(Request $request)
{
    $correlationId = $request->attributes->get('correlation_id');
    $token = $request->bearerToken();

    try {
        // Call User Service
        $user = $this->userClient->getUser($token, $correlationId);
        
        // Call Waste Service
        $wasteTypes = $this->wasteClient->getWasteTypes($correlationId);
        
        // Combine
        $response = [
            'user' => $user,
            'waste_types' => $wasteTypes,
            'correlation_id' => $correlationId,
        ];

        return response()->json($response);
    } catch (\Exception $e) {
        logger()->error('Integration error', [
            'correlation_id' => $correlationId,
            'exception' => $e->getMessage(),
        ]);

        return response()->json([
            'error' => 'Service unavailable',
            'correlation_id' => $correlationId,
        ], 503);
    }
}
```

### Success Criteria
- [ ] Service successfully calls User Service
- [ ] Service successfully calls Service B
- [ ] Token forwarded correctly (no 401 errors)
- [ ] Correlation ID forwarded & logged
- [ ] Error handling prevents data loss
- [ ] Unit tests pass (min. 3)
- [ ] Distributed tracing visible in logs
- [ ] Documentation clear

---

## 👤 ANGGOTA 4: DevOps / Infrastructure / Documentation (Poin 1.d, 1.e, 2)

### Deskripsi
Menangani:
1. **Poin 1.d:** Middleware Correlation ID di semua service
2. **Poin 1.e:** Distributed Logging setup
3. **Poin 2:** GitHub repository & documentation

### Deliverables
- [ ] GitHub repository setup (organization/team)
- [ ] Middleware Correlation ID di User Service (dari Anggota 1)
- [ ] Middleware Correlation ID di Service B (dari Anggota 2)
- [ ] Middleware Correlation ID di Orchestrator (dari Anggota 3)
- [ ] Distributed logging configuration (konsisten di semua service)
- [ ] Comprehensive documentation (README, API, Architecture)
- [ ] Log samples showing correlation ID across services
- [ ] CI/CD pipeline (GitHub Actions - optional)
- [ ] Docker compose (optional)

### Timeline
**Week 1-3 (Paralel dengan development):**
- Day 1-2: GitHub setup & structure
- Day 2-3: Middleware Correlation ID documentation
- Day 3-5: Verify logging across services
- Day 5-7: Compile distributed logging proof
- Day 7-10: Write comprehensive documentation
- Day 10-14: Polish, CI/CD, Docker (optional)

**Est. 25 jam kerja**

### Subtasks
```
1. GitHub Repository Setup
   - Create organization/repository
   - Setup .gitignore
   - Create folder structure:
     services/
       ├── user-service/
       ├── waste-type-service/
       └── orchestrator-service/
     docs/
       ├── ARCHITECTURE.md
       ├── API.md
       ├── SETUP.md
       └── DISTRIBUTED_LOGGING.md
     .github/workflows/ (optional CI/CD)

2. Middleware Coordination
   - Ensure CorrelationIdMiddleware sama di semua service
   - Document how to integrate
   - Verify logging context konsisten

3. Distributed Logging Setup
   - Verify CustomFormatter di semua service
   - Setup log aggregation (optional: ELK stack, Loki, etc.)
   - Verify JSON log format di semua service
   - Test correlation ID flow

4. Documentation
   a) README.md (root level)
      - Project overview
      - Architecture diagram
      - Quick start guide
      - Team members

   b) MICROSERVICE_TASK_BREAKDOWN.md
      - Detailed explanation setiap poin
      - Responsibility matrix

   c) IMPLEMENTATION_GUIDE_USER_SERVICE.md
      - How to implement logging + correlation ID
      - Code examples

   d) API.md
      - All endpoints dari 3 services
      - Request/response examples
      - Authorization details

   e) SETUP.md
      - How to setup each service locally
      - Environment variables
      - Database migrations
      - How to run tests

   f) DISTRIBUTED_LOGGING.md
      - Logging strategy
      - Log format
      - Correlation ID explanation
      - Log samples (proof of tracing)

   g) ARCHITECTURE.md
      - System diagram
      - Service interactions
      - Data flow
      - Error handling flow

5. Distributed Logging Proof
   - Run request through all services
   - Capture logs showing:
     * Same correlation ID across services
     * User authentication
     * Service-to-service calls
     * Error scenarios
   - Compile as screenshot/text in DISTRIBUTED_LOGGING.md

6. CI/CD Pipeline (Optional)
   - GitHub Actions for automated testing
   - Run tests on push
   - Code quality checks

7. Docker Setup (Optional)
   - docker-compose.yml untuk run semua service
   - Environment setup
   - Database seeding
```

### Example GitHub Structure
```
UAS-Microservice/
├── README.md
├── MICROSERVICE_TASK_BREAKDOWN.md
├── IMPLEMENTATION_GUIDE_USER_SERVICE.md
├── docker-compose.yml (optional)
│
├── services/
│   ├── user-service/
│   │   ├── app/
│   │   ├── database/
│   │   ├── tests/
│   │   ├── .env.example
│   │   ├── composer.json
│   │   └── README.md
│   │
│   ├── waste-type-service/
│   │   ├── app/
│   │   ├── database/
│   │   ├── tests/
│   │   ├── .env.example
│   │   ├── composer.json
│   │   └── README.md
│   │
│   └── orchestrator-service/
│       ├── app/
│       ├── tests/
│       ├── .env.example
│       ├── composer.json
│       └── README.md
│
├── docs/
│   ├── API.md
│   ├── SETUP.md
│   ├── ARCHITECTURE.md
│   ├── DISTRIBUTED_LOGGING.md
│   └── images/ (diagrams)
│
└── .github/
    └── workflows/ (optional)
        └── tests.yml
```

### Success Criteria
- [ ] GitHub repository organized & documented
- [ ] All services have consistent middleware
- [ ] Logging format JSON & consistent
- [ ] Correlation ID visible in all service logs
- [ ] Documentation comprehensive & clear
- [ ] README helps team understand entire system
- [ ] API documentation complete
- [ ] Distributed logging proof included
- [ ] All team members can understand system from docs

---

## 📅 TIMELINE OVERVIEW

```
Week 1:
├── Anggota 1: Setup Correlation ID + Logging (User Service)
├── Anggota 2: Setup Service B (CRUD endpoints)
└── Anggota 4: GitHub setup + Documentation start

Week 2:
├── Anggota 1: Unit tests + Documentation
├── Anggota 2: Tests + Logging + Middleware
├── Anggota 3: Setup Orchestrator (after Service B ready)
└── Anggota 4: Middleware coordination + Logging verification

Week 3:
├── Anggota 1: Final polish + Review
├── Anggota 2: Final testing
├── Anggota 3: Integration testing
└── Anggota 4: Documentation finalization + Distributed tracing proof

Final:
├── Integration testing (all together)
├── User acceptance testing
├── Documentation review
└── Deployment/Submission
```

---

## 🔄 DEPENDENCIES & COORDINATION

```
Blocking Relationships:
├── Service B must be DONE before Anggota 3 starts Orchestrator
└── User Service logging must be DONE before Anggota 4 can verify middleware

Parallelizable:
├── Anggota 1: User Service improvements
├── Anggota 2: Service B development
└── Anggota 4: GitHub & documentation setup

Integration Points:
└── Week 2-3: All services integrated for testing
```

---

## ✅ WEEKLY SYNC CHECKLIST

### Week 1 Sync
- [ ] Anggota 1: Correlation ID middleware implemented & tested
- [ ] Anggota 2: Service B CRUD endpoints working
- [ ] Anggota 4: GitHub repo created & documented
- [ ] All: Agree on API response format

### Week 2 Sync
- [ ] Anggota 1: User Service logging complete
- [ ] Anggota 2: Service B tests passing
- [ ] Anggota 3: Orchestrator endpoints working
- [ ] Anggota 4: Verify middleware consistency
- [ ] All: Test end-to-end request flow

### Week 3 Sync
- [ ] All services: Correlation ID flowing correctly
- [ ] All services: Logging shows distributed trace
- [ ] All: Documentation complete
- [ ] All: Unit tests passing
- [ ] All: Ready for final submission

---

## 📞 COMMUNICATION

### Recommended Tools
- **GitHub Issues** - Track tasks & bugs
- **GitHub Discussions** - Ask questions
- **Slack/Discord** - Quick sync
- **Weekly Meetings** - Status updates

### Key Communication Points
1. API contract between services (what data to return)
2. Error response format (consistent across all)
3. Logging format (JSON with correlation ID)
4. Token format (Bearer token)
5. Correlation ID header name (X-Correlation-ID)

---

## 🎯 SUCCESS CRITERIA (OVERALL)

- [ ] All poin 1.a-e implemented
- [ ] GitHub repository organized
- [ ] All services have correlation ID middleware
- [ ] All services have distributed logging
- [ ] Distributed logging proof shows correlation ID across services
- [ ] Unit tests passing (min. 1 per service)
- [ ] Documentation comprehensive
- [ ] All team members understand system
- [ ] System deployed/ready for demo

---

**Status: READY FOR ASSIGNMENT** ✅

Setiap anggota siap mulai dengan deliverables yang jelas dan timeline yang realistis.

