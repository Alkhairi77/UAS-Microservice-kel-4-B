# ✅ CHECKLIST IMPLEMENTASI UNTUK ANDA (ANGGOTA 1)

**Task:** Melengkapi User Service dengan Correlation ID & Distributed Logging  
**Estimated Time:** 20 jam = 3-4 hari intensive  
**Status:** Ready to start

---

## 📋 PRE-IMPLEMENTATION CHECKLIST

### ✅ Current State Verification
- [ ] Clone latest project code
- [ ] Verify `composer install` works
- [ ] Verify database migration `php artisan migrate`
- [ ] Verify unit tests run `php artisan test`
- [ ] Verify current logs working `tail -f storage/logs/laravel.log`

---

## 🔧 PHASE 1: MIDDLEWARE IMPLEMENTATION (2 hours)

### Step 1: Create Correlation ID Middleware
```bash
# Run in terminal
php artisan make:middleware CorrelationIdMiddleware
```

File: `app/Http/Middleware/CorrelationIdMiddleware.php`

**Checklist:**
- [ ] File created
- [ ] Copy code dari: `IMPLEMENTATION_GUIDE_USER_SERVICE.md` (Section 1)
- [ ] Verify: import statements correct
- [ ] Verify: namespace correct

**Test:**
```bash
# Verify syntax
php artisan tinker
> new App\Http\Middleware\CorrelationIdMiddleware()
# Should not error
```

### Step 2: Register Middleware in Kernel
Edit: `app/Http/Kernel.php`

**Checklist:**
- [ ] Open file
- [ ] Find: `protected $middleware = [...]`
- [ ] Add: `\App\Http\Middleware\CorrelationIdMiddleware::class,`
- [ ] Save file

**Test:**
```bash
php artisan route:list
# Middleware should load without error
```

---

## 🔧 PHASE 2: LOGGING SETUP (2 hours)

### Step 1: Create Custom Formatter
```bash
# Create directory
mkdir -p app/Logging

# Create file manually or via editor
```

File: `app/Logging/CustomFormatter.php`

**Checklist:**
- [ ] File created in `app/Logging/`
- [ ] Copy code dari: `IMPLEMENTATION_GUIDE_USER_SERVICE.md` (Section 2)
- [ ] Verify: class extends JsonFormatter
- [ ] Verify: format() method implemented

### Step 2: Update Logging Config
Edit: `config/logging.php`

**Checklist:**
- [ ] Open file
- [ ] Find: `'single' => [...]` channel
- [ ] Update: `'formatter' => \App\Logging\CustomFormatter::class,`
- [ ] Verify: other channels config untouched

**Test:**
```bash
php artisan config:cache
# Should not error
```

### Step 3: Update .env
Edit: `.env`

**Checklist:**
- [ ] Add/update: `APP_SERVICE_NAME=user-service`
- [ ] Verify: LOG_CHANNEL=single (or stack)

---

## 🔧 PHASE 3: CONTROLLER LOGGING (4 hours)

### Step 1: Update AuthenticatedSessionController
File: `app/Http/Controllers/Auth/AuthenticatedSessionController.php`

**Checklist:**
- [ ] Open file
- [ ] In `store()` method:
  - [ ] Add: `$correlationId = $request->attributes->get('correlation_id');` at start
  - [ ] Add: `\Log::info('User login attempt', [...])` before authenticate
  - [ ] Add: `\Log::info('User login successful', [...])` after success
  - [ ] Add: `\Log::error('Login error', [...])` in catch block
- [ ] In `destroy()` method:
  - [ ] Add: `\Log::info('User logout', [...])` before logout

**Copy template from:** `IMPLEMENTATION_GUIDE_USER_SERVICE.md` (Section 3)

**Verify:**
- [ ] All log statements include correlation_id
- [ ] Log levels appropriate (info, warning, error)

### Step 2: Update RegisteredUserController
File: `app/Http/Controllers/Auth/RegisteredUserController.php`

**Checklist:**
- [ ] In `store()` method:
  - [ ] Add: `$correlationId = $request->attributes->get('correlation_id');`
  - [ ] Add: `\Log::info('User registration attempt', [...])`
  - [ ] Add: `\Log::info('User registered successfully', [...])`

**Copy template from:** `IMPLEMENTATION_GUIDE_USER_SERVICE.md`

### Step 3: Update ProfileController
File: `app/Http/Controllers/ProfileController.php`

**Checklist:**
- [ ] In `update()` method:
  - [ ] Add: `$correlationId = $request->attributes->get('correlation_id');`
  - [ ] Add: `\Log::info('User profile update', [...])`
  - [ ] Add: `\Log::info('User profile updated', [...])`
- [ ] In `destroy()` method:
  - [ ] Add: `$correlationId = $request->attributes->get('correlation_id');`
  - [ ] Add: `\Log::info('User deletion attempt', [...])`

### Step 4: Update UserController
File: `app/Http/Controllers/UserController.php`

**Checklist:**
- [ ] In `index()` method:
  - [ ] Add: `$correlationId = request()->attributes->get('correlation_id');`
  - [ ] Add: `\Log::info('Fetching users list', [...])`
- [ ] In `store()` method:
  - [ ] Add correlation ID logging (attempt + success/error)
- [ ] In `update()` method:
  - [ ] Add correlation ID logging
- [ ] In `destroy()` method:
  - [ ] Add correlation ID logging
  - [ ] Add warning logs for prevented deletions

**Copy template from:** `IMPLEMENTATION_GUIDE_USER_SERVICE.md` (Section 3)

**Test after each controller:**
```bash
php artisan tinker
> Log::info('Test', ['test' => 'message']);
> exit
# Check: storage/logs/laravel.log
# Should see JSON formatted log with timestamp
```

---

## 🔧 PHASE 4: CREATE RESPONSE HELPER (1 hour)

### Optional but Recommended: API Response Helper
File: `app/Http/Responses/ApiResponse.php`

**Checklist:**
- [ ] Create file in `app/Http/Responses/`
- [ ] Copy code from: `IMPLEMENTATION_GUIDE_USER_SERVICE.md` (Section 4)
- [ ] Verify: methods for success, error, paginated responses

**Usage in controllers (optional update):**
```php
// Instead of: return response()->json($data);
// Use: return ApiResponse::success($data, 'User created', 201);
```

---

## 🧪 PHASE 5: UNIT TESTS (3 hours)

### Step 1: Create Correlation ID Test File
```bash
php artisan make:test CorrelationIdTest --feature
```

**Checklist:**
- [ ] File created: `tests/Feature/CorrelationIdTest.php`
- [ ] Copy code from: `IMPLEMENTATION_GUIDE_USER_SERVICE.md` (Section 5)
- [ ] Verify: all test methods present

### Step 2: Run Tests
```bash
# Run specific test file
php artisan test tests/Feature/CorrelationIdTest.php

# Expected output: All tests PASS ✓
```

**Checklist:**
- [ ] Test: correlation_id_is_generated_when_not_provided ✓
- [ ] Test: correlation_id_is_preserved_from_request ✓
- [ ] Test: correlation_id_is_included_in_authenticated_response ✓
- [ ] Test: correlation_id_persists_across_redirects ✓

### Step 3: Update Existing Tests (if needed)
```bash
# Run all tests to verify nothing broke
php artisan test
```

**Checklist:**
- [ ] All existing tests still pass
- [ ] No new failures introduced

---

## 📊 PHASE 6: VERIFICATION & TESTING (4 hours)

### Step 1: Manual Testing with Browser/Postman

#### Test 1: Login without Correlation ID
```
POST http://localhost:8000/login
Headers: 
  Content-Type: application/json
Body:
{
  "email": "admin@example.com",
  "password": "password"
}

Expected Response Headers:
  X-Correlation-ID: <some-uuid> ✓

Expected in logs:
  "correlation_id": "<uuid>" ✓
```

**Checklist:**
- [ ] Response includes X-Correlation-ID header
- [ ] Login successful
- [ ] No errors in logs

#### Test 2: Login WITH Correlation ID
```
POST http://localhost:8000/login
Headers: 
  X-Correlation-ID: my-test-123
  Content-Type: application/json
Body:
{
  "email": "admin@example.com",
  "password": "password"
}

Expected:
  Response X-Correlation-ID = my-test-123 ✓
  Logs show correlation_id: my-test-123 ✓
```

**Checklist:**
- [ ] Same correlation ID returned in response
- [ ] Logs show same correlation ID
- [ ] Login successful

#### Test 3: Check Log Format
```bash
# Terminal
tail -f storage/logs/laravel.log | grep "my-test-123"

# Expected output (JSON formatted with correlation_id)
{"timestamp":"...","level":"INFO","service":"user-service",
"correlation_id":"my-test-123","message":"User login attempt",...}
```

**Checklist:**
- [ ] Log format is JSON ✓
- [ ] Includes correlation_id field ✓
- [ ] Includes service name ✓
- [ ] Includes timestamp ✓

### Step 2: Test Logging in Different Scenarios

**Test register endpoint:**
```bash
POST http://localhost:8000/register
{
  "name": "Test User",
  "email": "test@example.com",
  "password": "Password123",
  "password_confirmation": "Password123"
}
```

**Checklist:**
- [ ] Logs show correlation_id
- [ ] Logs show email & action

**Test CRUD endpoints:**
```bash
GET http://localhost:8000/users
# Should log with correlation_id

POST http://localhost:8000/users
# Should log with correlation_id

PUT http://localhost:8000/users/1
# Should log with correlation_id
```

**Checklist:**
- [ ] All endpoints log with correlation_id
- [ ] Log messages make sense

### Step 3: Verify Log File
```bash
# Check log file size
ls -lh storage/logs/laravel.log

# View recent logs
tail -20 storage/logs/laravel.log

# Search logs by correlation ID
grep "uuid-123" storage/logs/laravel.log
# Should find all log entries with same UUID
```

**Checklist:**
- [ ] Logs file exists & has size > 0
- [ ] Logs formatted as JSON
- [ ] Multiple logs with same correlation_id visible

---

## 📚 PHASE 7: DOCUMENTATION (2 hours)

### Step 1: Document API Endpoints
Create: `docs/API_USER_SERVICE.md` (or similar)

**Document:**
- [ ] POST /register
  - [ ] Request format
  - [ ] Response format with correlation_id
  - [ ] Error responses
- [ ] POST /login
  - [ ] Request format
  - [ ] Response format with correlation_id
  - [ ] Example with correlation ID
- [ ] GET /profile
  - [ ] Request format
  - [ ] Response format
- [ ] Other CRUD endpoints

**Example:**
```markdown
## POST /register

Register new user.

### Request
```json
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Response (201 Created)
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {...},
  "correlation_id": "550e8400-..."
}
```

### Headers
```
X-Correlation-ID: 550e8400-...
```
```

### Step 2: Document Correlation ID Usage
Create: `docs/CORRELATION_ID_GUIDE.md`

**Document:**
- [ ] What is Correlation ID
- [ ] Why we use it
- [ ] How to use it in requests
- [ ] How to see it in logs
- [ ] Example flow diagram

### Step 3: Document Logging
Create: `docs/LOGGING_GUIDE.md`

**Document:**
- [ ] Log format (JSON)
- [ ] Fields in each log
- [ ] How to read logs
- [ ] Correlation ID in logs
- [ ] Example log entries

---

## ✅ FINAL CHECKLIST (BEFORE SUBMISSION)

### Code Quality
- [ ] No PHP syntax errors
  ```bash
  php artisan tinker
  > exit
  ```

- [ ] Middleware loads correctly
  ```bash
  php artisan route:list
  ```

- [ ] All tests pass
  ```bash
  php artisan test
  ```

### Functionality
- [ ] Correlation ID generated when missing ✓
- [ ] Correlation ID preserved from request ✓
- [ ] Correlation ID in response headers ✓
- [ ] Correlation ID in all logs ✓
- [ ] Log format is JSON ✓
- [ ] All existing features still work ✓

### Testing
- [ ] Unit tests for correlation ID (4 tests) ✓
- [ ] Manual testing in browser/Postman ✓
- [ ] Log verification done ✓

### Documentation
- [ ] API endpoints documented ✓
- [ ] Correlation ID usage documented ✓
- [ ] Logging guide created ✓
- [ ] README updated ✓

### Integration Ready
- [ ] Code ready for Orchestrator Service to call ✓
- [ ] Error responses consistent ✓
- [ ] Correlation ID properly forwarded ✓
- [ ] Logging shows correlation ID ✓

---

## 🚀 SUBMISSION CHECKLIST

Before sending to team/instructor:

```bash
# 1. Run tests
php artisan test

# 2. Check logs format
tail -5 storage/logs/laravel.log

# 3. Commit code
git add .
git commit -m "Add correlation ID & distributed logging"
git push origin autentikasi

# 4. Verify documentation
ls -la docs/

# 5. Create summary of changes
# List all new/modified files in commit message
```

**Checklist:**
- [ ] All tests passing (no failures)
- [ ] Logs formatted correctly
- [ ] Code committed to git
- [ ] Documentation complete
- [ ] Ready for peer review

---

## 🎯 SUCCESS CRITERIA

You're done when:
1. ✅ Correlation ID middleware working
2. ✅ All controllers log with correlation ID
3. ✅ Logs formatted as JSON
4. ✅ Unit tests passing
5. ✅ Manual testing verified
6. ✅ Documentation complete
7. ✅ Ready for Orchestrator Service to integrate

---

## 📞 TROUBLESHOOTING

### Issue: Logs not showing correlation_id
**Solution:** 
- Check middleware registered in Kernel.php
- Verify Log::withContext called in middleware
- Check log channel configured with custom formatter

### Issue: Correlation ID not in response header
**Solution:**
- Check middleware returns response->header()
- Verify middleware is executing (add dd() to test)

### Issue: JSON logs not formatted properly
**Solution:**
- Check CustomFormatter class created correctly
- Verify config/logging.php points to correct formatter
- Run: php artisan config:cache

### Issue: Tests failing
**Solution:**
- Run: php artisan migrate:refresh --seed
- Ensure test database exists
- Check test syntax matches Laravel testing format

---

## 📖 REFERENCE DOCUMENTS

Read in order:
1. **QUICK_REFERENCE.md** - Visual overview (2 min)
2. **EXECUTIVE_SUMMARY.md** - Detailed explanation (10 min)
3. **IMPLEMENTATION_GUIDE_USER_SERVICE.md** - Code examples & steps (15 min)
4. **This checklist** - Step by step execution (ongoing)

---

## ⏰ ESTIMATED TIMELINE

- **Day 1:** Phases 1-2 (Middleware + Logging setup) - 4 hours
- **Day 2:** Phase 3 (Controller logging) - 4 hours  
- **Day 3:** Phases 4-5 (Response helper + Tests) - 4 hours
- **Day 4:** Phases 6-7 (Verification + Docs) - 4 hours

**Total: ~16 hours = 2 days intensive + 1 day for review**

---

**Ready to start? Pick Phase 1 and begin! 🚀**

