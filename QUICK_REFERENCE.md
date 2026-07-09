# Forum Authorization - Quick Reference Guide

## Login Credentials (for testing)

### Admin
- **NIP:** ADM0001
- **Password:** admin123
- **Access:** All forums

### Dosen (Example)
- **NIP:** 1979000001
- **Password:** dosen123
- **Name:** Prof. Dr. Ahmad Subagjo, M.Kom
- **Access:** Forums from classes teaching

### Mahasiswa (Example)
- **NIM:** 2024000001
- **Password:** mahasiswa123
- **Name:** Alya Prameswari
- **Access:** Forums from enrolled classes

---

## Quick URLs

```
Welcome/Login:        http://localhost/
Dosen Dashboard:      http://localhost/dosen/dashboard
Dosen Forums:         http://localhost/dosen/forums
Mahasiswa Dashboard:  http://localhost/mahasiswa/dashboard
Mahasiswa Forums:     http://localhost/mahasiswa/forums
Admin Dashboard:      http://localhost/admin/dashboard
```

---

## Authorization Layers (4 Layers)

```
1. Route Middleware   → Check hasRole('dosen')
2. Controller         → Check isDosen()
3. Policy             → Check kelasDiampu().where(forum.kelas_id).exists()
4. Policy            → Check sendMessage() permission
```

---

## Verify Authorization (via Tinker)

```php
php artisan tinker

# Get user
$user = User::find(2);  # or User::where('nip_nim', '1979000001')->first()

# Quick authorization check
$user->isDosen();  # true/false

# Get classes
$user->kelasDiampu()->count();  # How many classes teaching

# Get forums
$forums = ForumDiskusi::whereIn('kelas_perkuliahan_id', $user->kelasDiampu()->pluck('id'))->get();

# Detailed diagnostic
$forum = $forums->first();
$user->canAccessForumDetailed($forum);
# Returns: ['can_access' => true/false, 'reason' => '...', 'debug_info' => [...]]

# Direct policy check
(new ForumPolicy())->sendMessage($user, $forum);  # true/false
```

---

## Check Database

```bash
# List all users and roles
mysql -u root cendekia << EOF
SELECT u.id, u.nip_nim, u.name, GROUP_CONCAT(r.name) as roles
FROM users u
LEFT JOIN model_has_roles mhr ON u.id = mhr.model_id
LEFT JOIN roles r ON mhr.role_id = r.id
GROUP BY u.id
LIMIT 10;
EOF

# List Dosen and their classes
mysql -u root cendekia << EOF
SELECT u.id, u.name, k.id as kelas_id, k.kode_kelas, mk.nama_mk
FROM users u
JOIN kelas_perkuliahan k ON u.id = k.dosen_id
JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
LIMIT 10;
EOF

# List forums
mysql -u root cendekia << EOF
SELECT fd.id, fd.judul, kp.kode_kelas, u.name as dosen
FROM forum_diskusi fd
JOIN kelas_perkuliahan kp ON fd.kelas_perkuliahan_id = kp.id
JOIN users u ON kp.dosen_id = u.id
LIMIT 10;
EOF
```

---

## Check Logs

```bash
# Watch logs in real-time
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log

# Filter for forum errors
grep -i forum storage/logs/laravel-*.log

# Filter for 403 errors
grep "403" storage/logs/laravel-*.log

# Find all ForumPolicy logs
grep "ForumPolicy" storage/logs/laravel-*.log

# Find all RoleMiddleware logs
grep "RoleMiddleware" storage/logs/laravel-*.log

# Full search with context
grep -A 5 -B 5 "forum_id.*5" storage/logs/laravel-*.log
```

---

## Common Issues & Solutions

### Issue: 403 Error on /dosen/forums
**Diagnosis:**
```php
$dosen = User::find(2);
$dosen->isDosen();              # Should be true
$dosen->kelasDiampu()->count(); # Should be > 0
```
**If isDosen() is false:** User not assigned 'dosen' role
**If count is 0:** No classes assigned to this Dosen

### Issue: Forums not showing
**Diagnosis:**
```php
$dosen = User::find(2);
$classIds = $dosen->kelasDiampu()->pluck('id');
ForumDiskusi::whereIn('kelas_perkuliahan_id', $classIds)->count();
```
**If count is 0:** No forums exist for these classes

### Issue: Cannot send message
**Diagnosis:**
```php
$user = User::find(2);
$forum = ForumDiskusi::find(1);
(new ForumPolicy())->sendMessage($user, $forum);
```
**If false:** Check if Dosen teaches that class or Mahasiswa enrolled

### Issue: Alternating 403 errors
**Solution:** This is now FIXED - comprehensive logging shows root cause in logs

---

## Database Schema (Forum-Related)

```
users
├─ id (PK)
├─ nip_nim
├─ name
├─ roles (via model_has_roles)
└─ relationships:
   ├─ kelasDiampu() → kelas_perkuliahan (dosen_id)
   └─ kelasDiikuti() → kelas_perkuliahan (via kelas_mahasiswa)

kelas_perkuliahan
├─ id (PK)
├─ dosen_id (FK → users)
├─ kode_kelas
└─ relationships:
   ├─ dosen → users
   ├─ mahasiswa → users (via kelas_mahasiswa)
   └─ forum → forum_diskusi

forum_diskusi
├─ id (PK)
├─ kelas_perkuliahan_id (FK)
├─ judul
├─ isi
└─ relationships:
   ├─ kelasPerkuliahan → kelas_perkuliahan
   └─ komentar → komentar_diskusi

komentar_diskusi
├─ id (PK)
├─ forum_diskusi_id (FK)
├─ user_id (FK)
└─ relationships:
   ├─ forum → forum_diskusi
   └─ user → users
```

---

## Authorization Flow Diagram

```
Dosen Request to /dosen/forums
    ↓
RoleMiddleware: hasRole('dosen') or isDosen() → [403 if fail]
    ↓
DosenForumController::index
    - Verify $user exists → [403 if null]
    - Verify $user->isDosen() → [403 if fail]
    - Get $user->kelasDiampu() → SQL query
    - Get ForumDiskusi from those classes
    - Verify active forum with Gate → [403 if fail]
    ↓
Return view('dosen.forums')
```

---

## Logging Reference

### Log Levels Used
- **DEBUG:** Successful authorization (frequent)
- **INFO:** Successful message creation (frequent)
- **WARNING:** Authorization denied (important to check)
- **ERROR:** Exception during message creation (critical)

### Log Entry Structure
```json
{
  "user_id": 2,
  "forum_id": 1,
  "forum_kelas_id": 1,
  "user_roles": ["dosen"],
  "classes_taught": [1, 2],
  "reason": "dosen_teaches_class",
  "method": "sendMessage"
}
```

---

## Test Commands

```bash
# Run all tests
php artisan test

# Run authorization tests
php artisan test --filter=auth

# Run forum tests
php artisan test --filter=forum

# Generate test database
php artisan migrate:fresh --seed
```

---

## File Locations

### Core Application
- RoleMiddleware: `app/Http/Middleware/RoleMiddleware.php`
- ForumPolicy: `app/Policies/ForumPolicy.php`
- Dosen Controller: `app/Http/Controllers/Dosen/ForumController.php`
- Mahasiswa Controller: `app/Http/Controllers/Mahasiswa/ForumController.php`
- User Model: `app/Models/User.php`

### Routes
- Forum Routes: `routes/web.php` (lines with 'forums')

### Views
- Dosen Forums: `resources/views/dosen/forums.blade.php`
- Mahasiswa Forums: `resources/views/mahasiswa/forums.blade.php`

### Logs
- Daily Logs: `storage/logs/laravel-YYYY-MM-DD.log`

### Documentation
- Root Cause: `FORUM_403_ANALYSIS.md`
- Full Guide: `FORUM_FIX_DOCUMENTATION.md`
- Summary: `IMPLEMENTATION_SUMMARY.md`
- DB Verification: `verify_permissions.php`

---

## Helpful Commands

```bash
# Artisan commands
php artisan tinker
php artisan migrate:fresh --seed
php artisan cache:clear
php artisan config:clear

# Database
mysql -u root cendekia
php artisan db:show

# Logs
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log
php artisan tail

# Composer
composer install
composer update
composer dump-autoload
```

---

## Performance Optimization

If experiencing slow forum loading:

1. **Check indexes:**
   ```sql
   ALTER TABLE kelas_perkuliahan ADD INDEX idx_dosen_id (dosen_id);
   ALTER TABLE kelas_mahasiswa ADD INDEX idx_mahasiswa_id (mahasiswa_id);
   ALTER TABLE forum_diskusi ADD INDEX idx_kelas_id (kelas_perkuliahan_id);
   ```

2. **Enable query caching:** 
   ```php
   // In .env
   CACHE_DRIVER=redis
   ```

3. **Monitor slow queries:**
   ```sql
   SET GLOBAL slow_query_log = 'ON';
   SET GLOBAL long_query_time = 0.1;
   ```

---

## Security Checklist

- [x] Authorization checks at each layer
- [x] SQL injection prevented (parameterized queries)
- [x] Cross-role access prevented
- [x] Logging doesn't expose passwords
- [x] Error messages don't reveal system details
- [x] CSRF protection enabled
- [x] Input validation in forms

---

## Quick Diagnostic Script

```php
// Copy to a file and run: php filename.php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

$user = \App\Models\User::find(2);
$forum = \App\Models\ForumDiskusi::first();

echo "User: {$user->name}\n";
echo "Is Dosen: " . ($user->isDosen() ? 'YES' : 'NO') . "\n";
echo "Classes: " . $user->kelasDiampu()->count() . "\n";
echo "Forum: {$forum->judul}\n";
echo "Access: " . ($user->canAccessForum($forum) ? 'YES' : 'NO') . "\n";
echo "\nDetailed:\n";
dd($user->canAccessForumDetailed($forum));
```

---

## Resources

- Full Documentation: `FORUM_FIX_DOCUMENTATION.md`
- Root Cause Analysis: `FORUM_403_ANALYSIS.md`
- Implementation Details: `IMPLEMENTATION_SUMMARY.md`
- Laravel Policies: https://laravel.com/docs/authorization
- Spatie Permission: https://spatie.be/docs/laravel-permission/v6

---

**Last Updated:** July 8, 2026
**Status:** ✅ Production Ready
**Version:** 1.0
