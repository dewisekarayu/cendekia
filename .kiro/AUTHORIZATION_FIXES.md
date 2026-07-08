# Authorization System Fixes - Complete Implementation Guide

## Problem Summary
- Error 403: "User does not have the right roles" when Dosen tries to send forum message
- Error: "Call to undefined method authorize()" when Mahasiswa tries to send forum message
- Role checking inconsistent across application
- Forum access control not properly validated

## Root Causes
1. **Controller Base Class Issue**: Missing `AuthorizesRequests` trait required for policy authorization
2. **Spatie RoleMiddleware Strictness**: Original middleware too strict, causing false 403s
3. **Missing Relationships**: User model had `kelasDiampu` and `kelasDiikuti` but not properly validated
4. **Inconsistent Authorization Patterns**: Mix of manual checks, policies, and middleware

## Solutions Implemented

### 1. Fixed Controller Base Class
**File**: `app/Http/Controllers/Controller.php`

```php
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
```

**Why**: Adds required traits for authorization and validation to work properly

### 2. Simplified Forum Controllers
**Files**: 
- `app/Http/Controllers/Mahasiswa/ForumController.php`
- `app/Http/Controllers/Dosen/ForumController.php`

**Changed from**: Policy-based authorization with `$this->authorize('sendMessage', $forum)`
**Changed to**: Manual authorization checks using relationships

```php
// Mahasiswa Controller
public function kirimPesan(Request $request, ForumDiskusi $forum)
{
    $user = $request->user();
    
    // Check if mahasiswa is enrolled in the class
    $terdaftar = $user->kelasDiikuti()
        ->where('kelas_perkuliahan_id', $forum->kelas_perkuliahan_id)
        ->exists();

    if (!$terdaftar) {
        abort(403, 'Anda tidak terdaftar di kelas ini.');
    }
    // ... create message
}

// Dosen Controller
public function kirimPesan(Request $request, ForumDiskusi $forum)
{
    $user = $request->user();
    
    // Check if dosen is teaching the class
    $mengampu = $user->kelasDiampu()
        ->where('id', $forum->kelas_perkuliahan_id)
        ->exists();

    if (!$mengampu) {
        abort(403, 'Anda tidak mengampu kelas ini.');
    }
    // ... create message
}
```

**Why**: Direct relationship checks are more reliable than policy dispatching, especially for complex relationships

### 3. Custom RoleMiddleware
**File**: `app/Http/Middleware/RoleMiddleware.php`

Replaces Spatie's strict RoleMiddleware with a simpler version:

```php
public function handle(Request $request, Closure $next, string ...$roles): Response
{
    $user = $request->user();
    
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    
    // Check if user has any of the required roles
    foreach ($roles as $role) {
        if ($user->hasRole($role)) {
            return $next($request);
        }
    }
    
    return response()->json(['message' => 'User does not have the required role(s).'], 403);
}
```

**Registered in**: `bootstrap/app.php`

```php
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);
```

**Why**: Simpler logic, consistent behavior, better error messages

### 4. User Model Helper Methods
**File**: `app/Models/User.php`

Added convenience methods:

```php
public function isAdmin(): bool { return $this->hasRole('admin'); }
public function isDosen(): bool { return $this->hasRole('dosen'); }
public function isMahasiswa(): bool { return $this->hasRole('mahasiswa'); }
public function getPrimaryRole(): string { /* returns primary role */ }
public function getDashboardRoute(): string { /* returns dashboard route */ }
```

**Why**: Centralized role checking, null-safe methods

### 5. Dashboard Route Update
**File**: `routes/web.php`

Uses new User helper methods:

```php
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) {
        abort(401);
    }
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isDosen()) {
        return redirect()->route('dosen.dashboard');
    } elseif ($user->isMahasiswa()) {
        return redirect()->route('mahasiswa.dashboard');
    }
    
    abort(403, 'Role tidak dikenali');
})->middleware(['auth', 'verified'])->name('dashboard');
```

### 6. Portal Layout Null-Safety
**File**: `resources/views/layouts/portal.blade.php`

```php
@php
    $user = auth()->user();
    $isAdmin = $user ? $user->hasRole('admin') : false;
    $isDosen = $user ? $user->hasRole('dosen') : false;
    $isMahasiswa = $user ? $user->hasRole('mahasiswa') : false;
@endphp
```

**Why**: Prevents null reference errors when user not authenticated

### 7. Cache Clearing
Execute commands to clear stale cache:

```bash
php artisan cache:clear
php artisan config:clear
```

## Forum Access Control Flow

### Mahasiswa Forum Access
1. Route middleware checks: `role:mahasiswa`
2. Controller method `kirimPesan()` receives `ForumDiskusi $forum`
3. Manual check: Is mahasiswa enrolled in the class?
   ```
   $user->kelasDiikuti()->where('kelas_perkuliahan_id', $forum->kelas_perkuliahan_id)->exists()
   ```
4. If yes: Create message, update forum timestamp, redirect
5. If no: Abort 403

### Dosen Forum Access
1. Route middleware checks: `role:dosen`
2. Controller method `kirimPesan()` receives `ForumDiskusi $forum`
3. Manual check: Is dosen teaching this class?
   ```
   $user->kelasDiampu()->where('id', $forum->kelas_perkuliahan_id)->exists()
   ```
4. If yes: Create message, update forum timestamp, redirect
5. If no: Abort 403

## Files Modified

| File | Change | Reason |
|------|--------|--------|
| `app/Http/Controllers/Controller.php` | Added traits | Enable authorization methods |
| `app/Http/Controllers/Mahasiswa/ForumController.php` | Simplified auth | Manual relationship checks |
| `app/Http/Controllers/Dosen/ForumController.php` | Simplified auth | Manual relationship checks |
| `app/Http/Middleware/RoleMiddleware.php` | Created | Replace strict Spatie middleware |
| `app/Models/User.php` | Added helpers | Centralized role methods |
| `routes/web.php` | Updated dashboard | Use helper methods |
| `resources/views/layouts/portal.blade.php` | Null-safe checks | Prevent null errors |
| `bootstrap/app.php` | Updated middleware alias | Use custom RoleMiddleware |

## Files Removed/Deprecated

- `app/Providers/AuthServiceProvider.php` - Not needed for simplified approach
- `app/Policies/ForumPolicy.php` - Replaced with manual checks
- `app/Http/Middleware/EnsureUserHasRole.php` - Replaced with RoleMiddleware
- `app/Services/RoleService.php` - Replaced with User model methods

## Testing Checklist

- [ ] Mahasiswa can login and view forum
- [ ] Mahasiswa can send message in forum of enrolled class
- [ ] Mahasiswa gets 403 when trying to message forum of unenrolled class
- [ ] Dosen can login and view forum
- [ ] Dosen can send message in forum of taught class
- [ ] Dosen gets 403 when trying to message forum of untaught class
- [ ] Admin can access all dashboards
- [ ] Dashboard redirect works for all roles
- [ ] No null reference errors in portal layout
- [ ] Forum list shows correct forums per role

## Performance Considerations

- Manual relationship checks are fast (single query per check)
- No policy dispatching overhead
- Spatie role caching still works for `hasRole()` calls
- Clear cache after user role changes

## Security Notes

- All endpoints protected by `auth` middleware first
- Then checked by `role:` middleware for role validation
- Finally checked by controller logic for resource ownership
- 403 responses don't leak information about role status

## Future Improvements

1. Can migrate back to Policies when needed
2. Can add permission-based checks if needed
3. Can implement caching layer for forum access checks
4. Can add audit logging for authorization checks
