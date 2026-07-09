# Forum 403 Error Analysis - Root Cause & Solutions

## Problem Statement
Dosen users receive "403: User does not have the right roles" error when trying to:
- View forums (`/dosen/forums`)
- Send messages to forums (`/dosen/forums/{forum}/pesan`)

Data is fetched from database correctly, but authorization is denied.

---

## Authorization Flow (4-Layer Architecture)

### Layer 1: Route Middleware - `RoleMiddleware`
**Location:** `routes/web.php` → `Route::middleware(['auth', 'role:dosen'])`

**How it works:**
```php
// Middleware checks: user -> hasRole('dosen')
// Returns 403 if no match
```

**Potential Issues:**
- ❌ Role name case sensitivity: `'dosen'` vs `'DOSEN'` vs `'Dosen'`
- ❌ Spatie Permission requires exact case match in `roles` table
- ❌ If roles seeded as `'admin'`, `'dosen'`, `'mahasiswa'` but middleware checks differently

---

### Layer 2: Controller-Level Check
**Location:** `app/Http/Controllers/Dosen/ForumController.php`

**Code:**
```php
if (!$user || !$user->isDosen()) {
    abort(403, 'Anda tidak memiliki akses ke forum.');
}
```

**How `isDosen()` works (User.php):**
```php
public function isDosen(): bool
{
    return $this->hasRole('dosen');
}
```

**Potential Issues:**
- ❌ Depends on Spatie `hasRole()` - must be seeded correctly
- ❌ If user not assigned 'dosen' role → fails

---

### Layer 3: Policy Authorization - `ForumPolicy`
**Location:** `app/Policies/ForumPolicy.php` → `sendMessage()` & `view()`

**Code (sendMessage example):**
```php
public function sendMessage(User $user, ForumDiskusi $forum): bool
{
    if ($user->isDosen()) {
        // Check if Dosen teaches this class
        return $user->kelasDiampu()
            ->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)
            ->exists();
    }
}
```

**Potential Issues:**
- ❌ `kelasDiampu()` relationship might return no results if:
  - Database records not seeded properly
  - Foreign key mismatch: `dosen_id` in `kelas_perkuliahan` table
  - Table join using wrong columns
- ❌ SQL ambiguous column error already fixed (qualified names)

---

### Layer 4: Policy Check in Controller
**Location:** `app/Http/Controllers/Dosen/ForumController.php` → `kirimPesan()`

**Code:**
```php
if (!Gate::inspect('sendMessage', $forum)->allowed()) {
    abort(403, 'Anda tidak dapat mengirim pesan di forum ini.');
}
```

**Potential Issues:**
- ❌ Uses `Gate::inspect()` which calls `ForumPolicy::sendMessage()`
- ❌ If any of the above layers fail → 403 error

---

## Root Cause Identification

### Primary Issue: Multi-Point Failure
The error can occur at **ANY of the 4 layers**. Without detailed logging, we can't identify which one fails:

1. **Is the Dosen role assigned?** (Layer 1 & 2)
   - Check: `model_has_roles` table has user assigned 'dosen' role
   
2. **Is the Dosen teaching any classes?** (Layer 3)
   - Check: `kelas_perkuliahan` table has records with `dosen_id = user_id`
   
3. **Is there a forum in that class?** (Layer 3)
   - Check: `forum_diskusi` table has records with `kelas_perkuliahan_id` matching class

### Secondary Issues:
1. **Role case sensitivity mismatch**
   - RoleMiddleware checks: `'dosen'` (lowercase)
   - Database might store: `'Dosen'` or `'DOSEN'`
   - Spatie Permission is case-sensitive

2. **Missing fallback authorization**
   - `RoleMiddleware` only has one check: `hasRole()`
   - If Spatie Permission fails, no fallback
   - Previous logs show: `current: []` (empty role names array)

3. **Incomplete data in controllers**
   - Controllers check `isDosen()` but only at abort level
   - No detailed logging to identify which policy check fails

---

## Solution Strategy

### Fix 1: Strengthen RoleMiddleware
- ✅ Already has fallback to helper methods (`isAdmin()`, `isDosen()`, `isMahasiswa()`)
- ✅ Already normalizes to lowercase
- ⚠️ Issue: If current roles are empty, both checks fail

**Root cause of empty roles:** Spatie Permission not synced correctly or roles not in database

### Fix 2: Enhance ForumPolicy Logging
- ⚠️ Currently no logging in policy checks
- Add detailed logging to identify which relationship fails

### Fix 3: Add Diagnostic Helper
- Create a method to test authorization at each layer
- Helps debug issues without looking at logs

### Fix 4: Ensure Database Data Integrity
- Verify roles seeded correctly (`admin`, `dosen`, `mahasiswa`)
- Verify Dosen users assigned 'dosen' role via `model_has_roles`
- Verify `kelas_perkuliahan` records have `dosen_id` set
- Verify `forum_diskusi` records have `kelas_perkuliahan_id` set

---

## Implementation Plan

### Step 1: Fix RoleMiddleware (Already Good, Enhance)
- Add more detailed error logging
- Include user ID, role names, required roles in error response

### Step 2: Enhanced ForumPolicy
- Add logging in `view()`, `sendMessage()`, `deleteMessage()`
- Log which condition fails (admin check, role check, relationship check)

### Step 3: Enhanced Controllers
- Add try-catch with detailed logging
- Log each step: user check, role check, policy check

### Step 4: Add Diagnostic Helper in User Model
- Add method: `canAccessForumDetailed()` that returns reason for denial

### Step 5: Data Integrity Check
- Create seeder test or artisan command to verify data

### Step 6: Test Both Roles
- Test Dosen forum access
- Test Mahasiswa forum access
- Ensure both work without alternating 403 errors

---

## Files to Modify

1. `app/Http/Middleware/RoleMiddleware.php` - Add detailed logging
2. `app/Policies/ForumPolicy.php` - Add logging in policy methods
3. `app/Http/Controllers/Dosen/ForumController.php` - Add logging + diagnostics
4. `app/Http/Controllers/Mahasiswa/ForumController.php` - Mirror Dosen fixes
5. `app/Models/User.php` - Add diagnostic helper method
6. `database/seeders/UserSeeder.php` - Verify data integrity (if needed)

---

## Expected Result

After fixes:
- ✅ Dosen can view forums from their classes
- ✅ Dosen can send messages to forums
- ✅ Dosen can delete their own/others' messages
- ✅ Mahasiswa can view forums from their enrolled classes
- ✅ Mahasiswa can send messages to forums
- ✅ Mahasiswa cannot delete messages (policy only allows Dosen + Admin)
- ✅ No 403 errors unless user genuinely lacks permission
- ✅ Clear error messages when access denied
- ✅ Detailed logging for debugging

---

## Testing Scenarios

### Test Case 1: Dosen Views Forums
1. Login as Dosen (NIP: 197900001)
2. Navigate to `/dosen/forums`
3. ✅ Should see list of forums from classes they teach

### Test Case 2: Dosen Sends Message
1. From forums list, select a forum
2. Type message and submit
3. ✅ Should see message posted

### Test Case 3: Mahasiswa Views Forums
1. Login as Mahasiswa (NIM: 202400001)
2. Navigate to `/mahasiswa/forums`
3. ✅ Should see list of forums from classes they're enrolled in

### Test Case 4: Mahasiswa Sends Message
1. From forums list, select a forum
2. Type message and submit
3. ✅ Should see message posted

### Test Case 5: No Alternating Errors
1. Run Test Case 1-2 (Dosen)
2. Logout and login as Mahasiswa
3. Run Test Case 3-4 (Mahasiswa)
4. ✅ Both should work without 403 errors
