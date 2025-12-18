# 🗺️ VISUAL QUICK REFERENCE - MICROSERVICE ARCHITECTURE

---

## 📐 SYSTEM ARCHITECTURE

```
┌─────────────┐
│   CLIENT    │
└──────┬──────┘
       │ 1. POST /login
       │    X-Correlation-ID: auto-generated
       ▼
┌──────────────────────────────────────────────┐
│          ORCHESTRATOR SERVICE                 │
│  (Poin 1.c - Anggota 3)                      │
├──────────────────────────────────────────────┤
│ • GET /api/user-waste-summary                │
│ • Combines data from 2 services              │
│ • Forwards token & correlation ID            │
│ • Error handling untuk service failures      │
└──────┬──────────────────┬─────────────────────┘
       │                  │
       │ 2. Forward       │ 3. Forward
       │    Bearer token  │    Bearer token
       │    & Correlation │    & Correlation
       │    ID            │    ID
       ▼                  ▼
┌─────────────────┐   ┌──────────────────┐
│  USER SERVICE   │   │  SERVICE B       │
│ (Poin 1.a)      │   │ (Poin 1.b)       │
│ Anggota 1 ✅    │   │ Anggota 2        │
├─────────────────┤   ├──────────────────┤
│ • POST /register│   │ • CRUD /waste-   │
│ • POST /login   │   │   types          │
│ • GET /profile  │   │ • Validations    │
│ • CRUD /users   │   │ • Error handling │
│ • Correlation   │   │ • Logging        │
│   ID middleware │   │ • Tests          │
│ • Logging       │   │ • Correlation ID │
│ • Tests ✅      │   │   middleware     │
└────────┬────────┘   └────────┬─────────┘
         │                     │
         │ 4. Auth response     │ 5. Data response
         │    with user data    │    with correlation ID
         │    & correlation ID  │
         └──────────┬───────────┘
                    │
                    ▼
           ┌────────────────┐
           │  LOGGING LAYER │
           │ (Poin 1.e)     │
           │ Anggota 4      │
           ├────────────────┤
           │ • JSON format  │
           │ • Correlation  │
           │   ID tracking  │
           │ • Service name │
           │ • User context │
           │ • Timestamp    │
           └────────────────┘
```

---

## 🔄 REQUEST FLOW WITH CORRELATION ID

```
Timeline: Request → Response

CLIENT
├─ 1. POST /api/user-waste-summary
│  └─ Headers: Authorization: Bearer token
│             (no X-Correlation-ID → server generates)
│
▼
ORCHESTRATOR SERVICE
├─ 2. [CorrelationIdMiddleware] Generate: X-Correlation-ID = uuid-123
├─ 3. Log: {"correlation_id": "uuid-123", "action": "received_request"}
├─ 4. Extract: bearer token from Authorization header
├─ 5. Call User Service:
│    POST /api/users/me
│    Headers: Authorization: Bearer token
│             X-Correlation-ID: uuid-123 ← FORWARD
│
▼ (parallel)
USER SERVICE
├─ 6. [CorrelationIdMiddleware] Receive: uuid-123 (preserve)
├─ 7. Log: {"correlation_id": "uuid-123", "action": "user_authenticated"}
├─ 8. Return: User data with correlation_id
│
ORCHESTRATOR (continued)
├─ 9. Call Service B:
│    GET /api/waste-types
│    Headers: X-Correlation-ID: uuid-123 ← FORWARD
│
▼
SERVICE B
├─ 10. [CorrelationIdMiddleware] Receive: uuid-123 (preserve)
├─ 11. Log: {"correlation_id": "uuid-123", "action": "list_waste_types"}
├─ 12. Return: Waste types with correlation_id
│
ORCHESTRATOR (combine)
├─ 13. Combine data from both services
├─ 14. Log: {"correlation_id": "uuid-123", "action": "data_combined"}
├─ 15. Response with X-Correlation-ID: uuid-123 header
│
▼
CLIENT
└─ Receives: 200 OK
   Header: X-Correlation-ID: uuid-123
   Body: {user, waste_types, correlation_id}
```

---

## 📊 DISTRIBUTED LOGGING TRACE

```
SAME Correlation ID Across All Services = PROOF OF DISTRIBUTED LOGGING

Logs Timeline:

[10:30:00.100] USER-SERVICE    correlation_id=uuid-123 User login attempt
[10:30:00.150] USER-SERVICE    correlation_id=uuid-123 User authenticated
[10:30:00.160] ORCHESTRATOR    correlation_id=uuid-123 Calling User Service...
[10:30:00.200] ORCHESTRATOR    correlation_id=uuid-123 User Service response received
[10:30:00.210] ORCHESTRATOR    correlation_id=uuid-123 Calling Service B...
[10:30:00.250] SERVICE-B       correlation_id=uuid-123 Fetching waste types
[10:30:00.300] SERVICE-B       correlation_id=uuid-123 Waste types retrieved
[10:30:00.310] ORCHESTRATOR    correlation_id=uuid-123 Combining data
[10:30:00.320] ORCHESTRATOR    correlation_id=uuid-123 Response prepared
[10:30:00.330] ORCHESTRATOR    correlation_id=uuid-123 Request completed

^ SAME uuid-123 throughout = can trace entire request path!
```

---

## 👥 TASK MATRIX (WHO DOES WHAT)

```
┌──────────────┬───────────────────────┬────────────┬──────────────┐
│ Anggota      │ Main Task             │ Depends On │ Deliverable  │
├──────────────┼───────────────────────┼────────────┼──────────────┤
│ 1 (ANDA) ✅  │ User Service          │ -          │ Service +    │
│              │ + Poin 1.d, 1.e       │            │ Tests        │
│              │ (20 hrs)              │            │              │
├──────────────┼───────────────────────┼────────────┼──────────────┤
│ 2           │ Service B              │ -          │ Service +    │
│              │ (Waste Type/Vendor)   │            │ Tests        │
│              │ (25 hrs)              │            │              │
├──────────────┼───────────────────────┼────────────┼──────────────┤
│ 3           │ Orchestrator Service   │ Service B  │ Service +    │
│              │ (30 hrs, starts Day 4 │ ⬅─         │ Tests        │
│              │ after Service B ready)│            │              │
├──────────────┼───────────────────────┼────────────┼──────────────┤
│ 4           │ DevOps/Docs/Infra     │ -          │ GitHub +     │
│              │ • Middleware           │ (parallel) │ Docs +       │
│              │ • Logging              │            │ Proof        │
│              │ • GitHub               │            │              │
│              │ • Documentation        │            │              │
│              │ (25 hrs)              │            │              │
└──────────────┴───────────────────────┴────────────┴──────────────┘

Critical Path: Anggota 2 → Anggota 3 (Service B must be done first)
Parallel Work: Anggota 1 & 4 can start immediately

Total Estimated: 100 hours (3-4 weeks for 1 person full-time)
With 4 people: 3-4 weeks (distributed load)
```

---

## ✅ POIN 1.a STATUS (USER SERVICE - ANDA)

```
Current Status: 80% DONE ✅
┌──────────────────────────────────────────────┐
│ ✅ Register                                  │
│ ✅ Login (with rate limiting)                │
│ ✅ User Profile (view & update)              │
│ ✅ User CRUD (admin)                         │
│ ✅ Input Validation                          │
│ ✅ Error Handling                            │
│ ✅ Unit Tests (6+ test cases)                │
├──────────────────────────────────────────────┤
│ ⚠️  Correlation ID Middleware (TODO)         │
│ ⚠️  Distributed Logging (TODO)               │
│ ⚠️  API Response Format Consistency (TODO)   │
└──────────────────────────────────────────────┘

Remaining Work (4-5 days):
1. Create CorrelationIdMiddleware.php (2 hours)
2. Setup Custom Log Formatter (2 hours)
3. Update all controllers with logging (4 hours)
4. Create/update unit tests (3 hours)
5. Test & verify logs (2 hours)
6. Documentation (2 hours)

Total: ~15 hours = 2-3 days intensive work
```

---

## 🔑 KEY FILES TO CREATE/MODIFY (ANDA)

```
Create New:
├── app/Http/Middleware/CorrelationIdMiddleware.php
├── app/Logging/CustomFormatter.php
├── app/Http/Responses/ApiResponse.php (helper)
└── tests/Feature/CorrelationIdTest.php

Modify Existing:
├── app/Http/Kernel.php (register middleware)
├── config/logging.php (setup custom formatter)
├── config/app.php (add service_name)
├── app/Http/Controllers/Auth/AuthenticatedSessionController.php (add logs)
├── app/Http/Controllers/Auth/RegisteredUserController.php (add logs)
├── app/Http/Controllers/ProfileController.php (add logs)
├── app/Http/Controllers/UserController.php (add logs)
└── .env (add APP_SERVICE_NAME)
```

---

## 📋 POIN 1.b TEMPLATE (SERVICE B - ANGGOTA 2)

```
Service B: Waste Type Service (Example)
├── Controllers
│   └── WasteTypeController.php
│       ├── index() → GET /api/waste-types
│       ├── store() → POST /api/waste-types
│       ├── show() → GET /api/waste-types/{id}
│       ├── update() → PUT /api/waste-types/{id}
│       └── destroy() → DELETE /api/waste-types/{id}
│
├── Requests
│   ├── StoreWasteTypeRequest.php
│   └── UpdateWasteTypeRequest.php
│
├── Models
│   └── WasteType.php
│
├── Database
│   ├── migrations/create_waste_types_table.php
│   └── factories/WasteTypeFactory.php
│
└── Tests
    └── Feature/WasteTypeTest.php (min 5 tests)
```

---

## 📡 POIN 1.c TEMPLATE (ORCHESTRATOR - ANGGOTA 3)

```
Orchestrator Service
├── Controllers
│   └── IntegrationController.php
│       ├── userWasteSummary() → GET /api/user-waste-summary
│       └── userWasteDetail() → GET /api/user-waste/{userId}/{wasteId}
│
├── Services
│   ├── UserServiceClient.php (HTTP wrapper)
│   │   ├── getUser(token, correlationId)
│   │   └── getUserProfile(token, correlationId)
│   │
│   └── WasteServiceClient.php (HTTP wrapper)
│       ├── getWasteType(id, correlationId)
│       └── listWasteTypes(correlationId)
│
└── Tests
    └── Feature/IntegrationTest.php (min 3 tests)
```

---

## 🏗️ POIN 1.d: MIDDLEWARE CHECKLIST

```
Correlation ID Middleware Requirements:
┌─────────────────────────────────────┐
│ ✅ Generate UUID if not provided    │
│ ✅ Preserve existing correlation ID │
│ ✅ Attach to request attributes     │
│ ✅ Attach to response header        │
│ ✅ Set logging context              │
│ ✅ Forward to other services        │
│ ✅ Implement in ALL 3 services      │
└─────────────────────────────────────┘

Implementation Checklist per Service:
Service 1 (User):      [✅ Anggota 1 do]
Service 2 (Waste):     [⬜ Anggota 2 do]
Service 3 (Orchestor): [⬜ Anggota 3 do]
Verify consistency:    [⬜ Anggota 4 verify]
```

---

## 📊 POIN 1.e: LOGGING FORMAT STANDARD

```
REQUIRED LOG FORMAT (JSON):

{
  "timestamp": "2025-12-15T10:30:00.123456Z",
  "level": "INFO",
  "service": "user-service",
  "correlation_id": "550e8400-e29b-41d4-a716-446655440000",
  "message": "User authenticated successfully",
  "context": {
    "user_id": 5,
    "user_email": "user@example.com",
    "action": "login",
    "ip_address": "192.168.1.100",
    "duration_ms": 145
  }
}

LOGGING LOCATIONS:
✅ User Service    - All auth & CRUD operations
✅ Service B       - All CRUD operations
✅ Orchestrator    - Service calls, data combination
✅ Middleware      - Request received/processed
```

---

## 📚 GITHUB REPOSITORY STRUCTURE

```
UAS-Microservice/
│
├── README.md ⬅─ Start here!
├── EXECUTIVE_SUMMARY.md ⬅─ This one
├── MICROSERVICE_TASK_BREAKDOWN.md ⬅─ Detailed explanation
├── IMPLEMENTATION_GUIDE_USER_SERVICE.md ⬅─ Code examples
├── TEAM_TASK_DISTRIBUTION.md ⬅─ Who does what
│
├── services/
│   ├── user-service/
│   │   ├── app/
│   │   ├── database/
│   │   ├── tests/
│   │   └── README.md
│   │
│   ├── waste-type-service/
│   │   ├── app/
│   │   ├── database/
│   │   ├── tests/
│   │   └── README.md
│   │
│   └── orchestrator-service/
│       ├── app/
│       ├── tests/
│       └── README.md
│
├── docs/
│   ├── API.md ⬅─ All endpoints documented
│   ├── SETUP.md ⬅─ How to setup locally
│   ├── ARCHITECTURE.md ⬅─ System design
│   ├── DISTRIBUTED_LOGGING.md ⬅─ Proof of tracing
│   └── images/
│       └── architecture-diagram.png
│
└── .github/
    └── workflows/ (optional)
        └── tests.yml
```

---

## ⏰ TIMELINE AT A GLANCE

```
Week 1: Foundation
├─ Mon-Tue: Anggota 1 setup correlation ID
├─ Mon-Tue: Anggota 2 start Service B
├─ Mon-Fri: Anggota 4 setup GitHub
└─ Wed-Fri: Anggota 1 add logging

Week 2: Development
├─ Anggota 1: Unit tests + finalize
├─ Anggota 2: CRUD + tests + logging
├─ Anggota 3: Start Orchestrator (after B ready)
└─ Anggota 4: Coordinate + documentation

Week 3: Integration
├─ Anggota 1-3: Fix issues + integration tests
├─ Anggota 4: Compile distributed logging proof
├─ All: Documentation review
└─ All: Preparation for submission
```

---

## ✨ ACCEPTANCE CRITERIA (FINAL CHECK)

```
User Service:
✅ Endpoints working with logging
✅ Correlation ID in response headers
✅ Logs show correlation ID (JSON format)
✅ Unit tests passing (6+)
✅ API documented

Service B:
✅ CRUD endpoints working
✅ Validation prevents bad data
✅ Error messages helpful
✅ Unit tests passing (5+)
✅ Logging includes correlation ID

Orchestrator:
✅ Endpoints combine data from 2 services
✅ Token forwarding working
✅ Correlation ID forwarded
✅ Error handling for service failures
✅ Unit tests passing (3+)

Infrastructure:
✅ Middleware consistent across services
✅ Logging format JSON + consistent
✅ Correlation ID visible in all logs
✅ Distributed tracing proof (sample logs)

Docs & GitHub:
✅ Repository organized
✅ README helpful
✅ API endpoints documented
✅ Setup guide complete
✅ Architecture clear
✅ Team members documented
```

---

**Ready to present to team? Print this page! 📄**

