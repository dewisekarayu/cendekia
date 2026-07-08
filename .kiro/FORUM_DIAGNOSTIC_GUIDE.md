# Forum Authorization - Diagnostic & Troubleshooting Guide

## 🔍 Quick Diagnosis Commands

### 1. Check User Roles in Database
```bash
# Connect to your database and run:
SELECT id, name, email FROM users WHERE email = 'user@example.com';
SELECT user_id, role_id FROM model_has_roles WHERE user_id = 123;
SELECT id, name FROM roles;
```

### 2. Check if User Has Role Assignment
```php
// In Laravel tinker:
$user = User::find(123);
$user->getRoleNames();        // Array of role names
$user->isDosen();              // Check Dosen role
$user->isMahasiswa();          // Check Mahasiswa role
$user->isAdmin();              // Check Admin role
$user->hasRole('dosen');       // Direct Spatie check
```

### 3. Check Forum Access
```php
$user = User::find(123);
$forum = ForumDiskusi::find(456);

// Check all authorization layers
$can_view = Gate::inspect('view', $forum)->allowed();
$can_send = Gate::inspect('sendMessage', $forum)->allowed();
$can_delete = Gate::inspect('deleteMessage', $forum)->allowed();

// Check policy directly
$policy = new ForumPolicy();
$policy->sendMessage($user, $forum);
```

---

## 🐛 Common Issues & Solutions

### Issue 1: "User does not have the required role(s)"

**Symptom**: 
- 403 error at middleware level
- Error message: `{"message":"User does not have the required role(s)."}`

**Diagnosis**:
```php
$user = Auth::user();
echo "User ID: " . $user->id . "\n";
echo "Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";
echo "isDosen: " . ($user->isDosen() ? 'true' : 'false') . "\n";

// Check if roles table is empty
echo "Total roles in DB: " . Role::count() . "\n";
echo "Dosen role exists: " . (Role::where('name', 'dosen')->exists() ? 'yes' : 'no') . "\n";
```

**Solutions**:
1. **Ensure roles exist in database**:
   ```php
   // Run this in tinker or migration
   foreach(['admin', 'dosen', 'mahasiswa'] as $role) {
       Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
   }
   ```

2. **Assign role to user**:
   ```php
   $user = User::find(123);
   $user->assignRole('dosen');  // or 'mahasiswa', 'admin'
   $user->save();
   ```

3. **Verify role case** (should be lowercase):
   ```php
   // Check in database
   SELECT * FROM roles WHERE name IN ('dosen', 'mahasiswa', 'admin');
   
   // In Laravel - should all be lowercase
   Role::all()->pluck('name');  // Should be: ['admin', 'dosen', 'mahasiswa']
   ```

---

### Issue 2: "Anda tidak dapat mengirim pesan di forum ini"

**Symptom**: 
- Passed middleware but failed at policy level
- 403 error when sending message

**Diagnosis**:
```php
$user = Auth::user();
$forum = ForumDiskusi::find(456);

// Step 1: Check user role
echo "User role: " . $user->getPrimaryRole() . "\n";

// Step 2: Check forum's class
echo "Forum class ID: " . $forum->kelas_perkuliahan_id . "\n";

// Step 3: Check if Dosen teaches this class
if ($user->isDosen()) {
    $teaches = $user->kelasDiampu()
        ->where('id', $forum->kelas_perkuliahan_id)
        ->exists();
    echo "Dosen teaches class: " . ($teaches ? 'yes' : 'no') . "\n";
    
    // If no, which classes does Dosen teach?
    echo "Classes taught: ";
    print_r($user->kelasDiampu()->pluck('id')->toArray());
}

// Step 4: Check if Mahasiswa enrolled in this class
if ($user->isMahasiswa()) {
    $enrolled = $user->kelasDiikuti()
        ->where('id', $forum->kelas_perkuliahan_id)
        ->exists();
    echo "Mahasiswa enrolled: " . ($enrolled ? 'yes' : 'no') . "\n";
    
    // If no, which classes is Mahasiswa enrolled?
    echo "Classes enrolled: ";
    print_r($user->kelasDiikuti()->pluck('id')->toArray());
}
```

**Solutions**:
1. **For Dosen** - Assign to teach the class:
   ```php
   $kelas = KelasPerkuliahan::find(456);
   $kelas->dosen_id = 123;  // Dosen user ID
   $kelas->save();
   ```

2. **For Mahasiswa** - Enroll in the class:
   ```php
   $user = User::find(123);
   $user->kelasDiikuti()->attach(456);  // 456 = class ID
   
   // Verify
   $user->kelasDiikuti()->pluck('id');
   ```

---

### Issue 3: Alternating Errors Between Dosen & Mahasiswa

**Symptom**:
- Dosen can access forum
- Then Mahasiswa gets error
- Then Dosen works again
- (Alternating failures)

**Diagnosis**:
```php
// This usually means multiple issues:

// 1. Check if routes are correctly middleware'ed
// In routes/web.php, ensure:
Route::middleware(['auth', 'role:dosen'])->group(...);
Route::middleware(['auth', 'role:mahasiswa'])->group(...);

// 2. Check if user has BOTH roles
$user = User::find(123);
echo "Roles: " . implode(', ', $user->getRoleNames()->toArray()) . "\n";

// 3. Check database for role assignments
SELECT * FROM model_has_roles WHERE user_id = 123;
```

**Solutions**:
1. **Ensure routes use correct middleware**:
   ```php
   // Correct: One route group per role
   Route::middleware(['auth', 'role:dosen'])->group(function () {
       Route::get('/dosen/forums', ...);
   });
   
   Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
       Route::get('/mahasiswa/forums', ...);
   });
   ```

2. **Clean up role assignments**:
   ```php
   // If user has both roles, remove conflicting ones
   $user = User::find(123);
   $user->removeRole('mahasiswa');  // Keep only 'dosen'
   
   // Or verify which role is primary
   echo "Primary: " . $user->getPrimaryRole() . "\n";
   ```

---

### Issue 4: Forum List is Empty

**Symptom**:
- Forum page loads (no 403)
- But no forums displayed

**Diagnosis**:
```php
$user = Auth::user();

// Step 1: Check classes
if ($user->isDosen()) {
    $classes = $user->kelasDiampu();
    echo "Classes taught: " . $classes->count() . "\n";
} else if ($user->isMahasiswa()) {
    $classes = $user->kelasDiikuti();
    echo "Classes enrolled: " . $classes->count() . "\n";
}

// Step 2: Check forums in those classes
$class_ids = $classes->pluck('id');
$forums = ForumDiskusi::whereIn('kelas_perkuliahan_id', $class_ids)->get();
echo "Forums count: " . $forums->count() . "\n";

// Step 3: Check all forums in system
echo "Total forums in DB: " . ForumDiskusi::count() . "\n";
```

**Solutions**:
1. **Assign class to Dosen**:
   ```php
   $kelas = KelasPerkuliahan::find(456);
   $kelas->dosen_id = 123;
   $kelas->save();
   ```

2. **Enroll Mahasiswa in class**:
   ```php
   $mahasiswa = User::find(123);
   $mahasiswa->kelasDiikuti()->attach(456);
   ```

3. **Create forum for class**:
   ```php
   ForumDiskusi::create([
       'kelas_perkuliahan_id' => 456,
       'dibuat_oleh' => 123,  // Optional
       'judul' => 'Forum Diskusi',
       'isi' => 'Selamat datang di forum diskusi kelas ini',
   ]);
   ```

---

## 📊 Debug Checklist

Use this when troubleshooting forum issues:

```
[ ] User is authenticated
    [ ] User ID: ___
    [ ] Email: ___
    
[ ] User has role
    [ ] Roles: ___
    [ ] isDosen(): Yes / No
    [ ] isMahasiswa(): Yes / No
    [ ] isAdmin(): Yes / No
    
[ ] User has class assignment
    [ ] Dosen classes: ___
    [ ] Mahasiswa classes: ___
    
[ ] Forum exists
    [ ] Forum ID: ___
    [ ] Forum class ID: ___
    [ ] Forum title: ___
    
[ ] Authorization passes
    [ ] Middleware pass: Yes / No
    [ ] Policy pass: Yes / No
    [ ] Both pass: Yes / No
    
[ ] Database integrity
    [ ] Roles table has data: Yes / No
    [ ] model_has_roles populated: Yes / No
    [ ] Class assignments correct: Yes / No
```

---

## 🔧 Common Fixes (Copy-Paste)

### Fix 1: Recreate All Roles
```php
// Run in tinker: php artisan tinker
DB::table('roles')->truncate();
DB::table('model_has_roles')->truncate();

foreach(['admin', 'dosen', 'mahasiswa'] as $role) {
    Role::create(['name' => $role, 'guard_name' => 'web']);
}

echo "Roles created successfully";
```

### Fix 2: Assign Role to User
```php
// Run in tinker
$user = User::find(123);  // Replace with actual user ID
$user->assignRole('dosen');  // or 'mahasiswa'

// Verify
echo $user->getRoleNames();  // Should show the role
```

### Fix 3: Enroll Mahasiswa in All Classes
```php
// For all mahasiswa
Mahasiswa::with('kelasDiikuti')->get()->each(function($user) {
    if ($user->kelasDiikuti()->count() === 0) {
        $all_classes = KelasPerkuliahan::pluck('id');
        $user->kelasDiikuti()->attach($all_classes);
        echo "Enrolled user {$user->id}\n";
    }
});
```

### Fix 4: Verify Middleware Configuration
```php
// In bootstrap/app.php, should have:
$middleware->alias([
    'role' => \App\Http\Middleware\RoleMiddleware::class,
]);

// In routes/web.php, usage should be:
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/forums', ...);
});
```

---

## 🧪 Complete Test Scenario

```php
// This test scenario checks everything
function test_forum_access() {
    // Create test user with Dosen role
    $dosen = User::factory()->create(['email' => 'dosen@test.com']);
    $dosen->assignRole('dosen');
    
    // Create a class for this Dosen
    $kelas = KelasPerkuliahan::factory()->create(['dosen_id' => $dosen->id]);
    
    // Create a forum for this class
    $forum = ForumDiskusi::factory()->create(['kelas_perkuliahan_id' => $kelas->id]);
    
    // Test 1: Middleware should pass
    $this->actingAs($dosen)
        ->get('/dosen/forums')
        ->assertStatus(200);
    
    // Test 2: Should see the forum
    $this->actingAs($dosen)
        ->get('/dosen/forums')
        ->assertSee($forum->judul);
    
    // Test 3: Should be able to send message
    $this->actingAs($dosen)
        ->post(route('dosen.forum.pesan', $forum), [
            'isi' => 'Test message'
        ])
        ->assertRedirect();
    
    // Verify message was created
    $this->assertDatabaseHas('komentar_diskusi', [
        'forum_diskusi_id' => $forum->id,
        'user_id' => $dosen->id,
        'isi' => 'Test message'
    ]);
    
    echo "✅ All tests passed!";
}
```

---

## 📞 When to Contact Support

If you've checked everything above and still have issues:

1. **Check the logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Look for specific error message**

3. **Provide**:
   - User ID with issue
   - Role(s) assigned to user
   - Forum ID user is trying to access
   - Screenshot of error
   - Relevant log entries

---

## 🚀 Prevention Tips

1. **Always verify roles exist before assigning**:
   ```php
   Role::firstOrCreate(['name' => 'dosen', 'guard_name' => 'web']);
   ```

2. **Use database seeders for initial setup**:
   ```bash
   php artisan db:seed
   ```

3. **Monitor logs for authorization failures**:
   ```bash
   grep -i "forbidden\|403\|unauthorized" storage/logs/laravel.log
   ```

4. **Test with multiple users**:
   - 1 Admin user
   - 1 Dosen user
   - 1 Mahasiswa user
   - 1 User without proper role

5. **Document your class assignments**:
   - Which Dosen teaches which class?
   - Which Mahasiswa enrolled in which class?
