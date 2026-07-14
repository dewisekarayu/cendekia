# 📧 Email Notification System - API Reference

## NotificationService Methods

Gunakan `App\Services\NotificationService` untuk trigger notifications.

### 1. notifyMateriBaru()

Kirim notifikasi ke semua mahasiswa saat dosen upload materi baru.

```php
use App\Services\NotificationService;
use App\Models\Materi;

NotificationService::notifyMateriBaru($materi, $dosen);
```

**Parameters:**
- `$materi` - Materi model instance
- `$dosen` - User model instance (the teacher)

**Example:**
```php
public function store(Request $request, $id)
{
    $kelas = KelasPerkuliahan::findOrFail($id);
    
    $materi = Materi::create([
        'kelas_perkuliahan_id' => $kelas->id,
        'judul' => 'Introduction to Programming',
        'deskripsi' => 'Basic concepts...',
        'pertemuan_ke' => 1,
    ]);
    
    // Notify all students in class
    NotificationService::notifyMateriBaru($materi, auth()->user());
    
    return redirect()->with('success', 'Materi added');
}
```

---

### 2. notifyTugasBaru()

Kirim notifikasi ke semua mahasiswa saat dosen membuat tugas.

```php
NotificationService::notifyTugasBaru($tugas, $dosen);
```

**Parameters:**
- `$tugas` - Tugas model instance
- `$dosen` - User model instance

**Example:**
```php
$tugas = Tugas::create([
    'kelas_perkuliahan_id' => $kelas->id,
    'judul_tugas' => 'Programming Assignment',
    'deadline_tugas' => now()->addDays(7),
    // ...
]);

NotificationService::notifyTugasBaru($tugas, auth()->user());
```

---

### 3. notifyPengumumanBaru()

Kirim notifikasi ke semua mahasiswa saat dosen posting pengumuman.

```php
NotificationService::notifyPengumumanBaru($pengumuman, $dosen);
```

**Parameters:**
- `$pengumuman` - Pengumuman model instance
- `$dosen` - User model instance

**Example:**
```php
$pengumuman = Pengumuman::create([
    'kelas_perkuliahan_id' => $kelas->id,
    'judul' => 'Class Cancelled Tomorrow',
    'isi' => 'Due to unforeseen circumstances...',
    'dibuat_oleh' => auth()->id(),
]);

NotificationService::notifyPengumumanBaru($pengumuman, auth()->user());
```

---

### 4. notifyNilaiBaru()

Kirim notifikasi ke mahasiswa saat dosen memberikan nilai.

```php
NotificationService::notifyNilaiBaru($pengumpulan, $dosen);
```

**Parameters:**
- `$pengumpulan` - PengumpulanTugas model instance
- `$dosen` - User model instance

**Example:**
```php
$pengumpulan->update([
    'nilai' => 85,
    'feedback_dosen' => 'Good work!',
    'status' => 'dinilai',
]);

NotificationService::notifyNilaiBaru($pengumpulan, auth()->user());
```

---

### 5. notifyAbsensiDibuka()

Kirim notifikasi ke semua mahasiswa saat dosen membuka sesi presensi.

```php
NotificationService::notifyAbsensiDibuka($absensi, $dosen);
```

**Parameters:**
- `$absensi` - Absensi model instance
- `$dosen` - User model instance

**Example:**
```php
$absensi->update(['session_status' => 'buka']);

NotificationService::notifyAbsensiDibuka($absensi, auth()->user());
```

---

### 6. notifyPengumpulanTugas()

Kirim notifikasi ke dosen saat mahasiswa mengumpulkan tugas.

```php
NotificationService::notifyPengumpulanTugas($pengumpulan, $dosen);
```

**Parameters:**
- `$pengumpulan` - PengumpulanTugas model instance
- `$dosen` - User model instance

**Example:**
```php
$pengumpulan = PengumpulanTugas::create([
    'tugas_id' => $tugas->id,
    'mahasiswa_id' => auth()->id(),
    'waktu_kumpul' => now(),
    'status' => 'dikumpul',
]);

$dosen = $tugas->kelasPerkuliahan->dosen;
NotificationService::notifyPengumpulanTugas($pengumpulan, $dosen);
```

---

### 7. notifyPesanBaru()

Kirim notifikasi saat ada pesan baru di forum.

```php
NotificationService::notifyPesanBaru($forum, $recipient, $sender);
```

**Parameters:**
- `$forum` - ForumDiskusi model instance
- `$recipient` - User model instance (penerima notif)
- `$sender` - User model instance (pengirim pesan)

**Example:**
```php
$forum = ForumDiskusi::create([
    'kelas_perkuliahan_id' => $kelas->id,
    'judul' => 'How to solve problem X?',
    'isi' => 'I have trouble with...',
    'dibuat_oleh' => auth()->id(),
]);

$dosen = $kelas->dosen;
NotificationService::notifyPesanBaru($forum, $dosen, auth()->user());
```

---

### 8. notifyPenggunaBaru()

Kirim notifikasi ke semua admin saat pengguna baru mendaftar.

```php
NotificationService::notifyPenggunaBaru($user, $role);
```

**Parameters:**
- `$user` - User model instance
- `$role` - String role ('mahasiswa', 'dosen', 'admin')

**Example:**
```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => Hash::make('password'),
]);

NotificationService::notifyPenggunaBaru($user, 'mahasiswa');
```

---

## Direct Job Dispatch

Jika ingin lebih kontrol, bisa langsung dispatch Job:

```php
use App\Jobs\SendMateriBaru;

SendMateriBaru::dispatch($materi, $mahasiswa, $dosen);
```

### Semua Job Classes

```php
// Materi Baru
\App\Jobs\SendMateriBaru::dispatch($materi, $mahasiswa, $dosen);

// Tugas Baru
\App\Jobs\SendTugasBaru::dispatch($tugas, $mahasiswa, $dosen);

// Pengumuman Baru
\App\Jobs\SendPengumumanBaru::dispatch($pengumuman, $mahasiswa, $dosen);

// Nilai Baru
\App\Jobs\SendNilaiBaru::dispatch($pengumpulan, $mahasiswa, $dosen);

// Absensi Dibuka
\App\Jobs\SendAbsensiDibuka::dispatch($absensi, $mahasiswa, $dosen);

// Pengumpulan Tugas
\App\Jobs\SendPengumpulanTugas::dispatch($pengumpulan, $dosen);

// Pesan Baru
\App\Jobs\SendPesanBaru::dispatch($forum, $recipient, $sender);

// Pengguna Baru
\App\Jobs\SendPenggunaBaru::dispatch($user, $role);
```

---

## Mailables (Direct Mail Class)

Jika ingin mengirim email tanpa queue:

```php
use App\Mail\MateriBaru;
use Illuminate\Support\Facades\Mail;

// Send immediately (blocking)
Mail::send(new MateriBaru($materi, $mahasiswa, $dosen));

// Queue the email
Mail::queue(new MateriBaru($materi, $mahasiswa, $dosen));
```

---

## NotificationPreference Model

Manage user email preferences:

```php
use App\Models\NotificationPreference;

// Get or create for user
$prefs = NotificationPreference::forUser(auth()->id());

// Check if specific notification is enabled
if ($prefs->isEnabled('materi_baru')) {
    // Send email
}

// Get all preferences
$allPrefs = auth()->user()->notificationPreferences;

// Update preferences
auth()->user()->notificationPreferences->update([
    'materi_baru' => false,
    'tugas_baru' => true,
    'pengumuman_baru' => true,
]);
```

---

## Respecting User Preferences

Sebelum mengirim email, check preferensi user:

```php
use App\Models\NotificationPreference;
use App\Services\NotificationService;

// In your controller or service
$recipients = $kelas->kelasMahasiswa()->get()->pluck('mahasiswa');

foreach ($recipients as $mahasiswa) {
    $prefs = NotificationPreference::forUser($mahasiswa->id);
    
    if ($prefs->isEnabled('materi_baru')) {
        \App\Jobs\SendMateriBaru::dispatch($materi, $mahasiswa, $dosen);
    }
}
```

**Note**: NotificationService sudah handle ini otomatis di implementation.

---

## Admin Panel Controller

API endpoints untuk admin panel:

```php
use App\Http\Controllers\Admin\NotificationPreferenceController;

// List all users with preferences
// GET /admin/notification-preferences

// Show preferences for user
// GET /admin/notification-preferences/{user}

// Update preferences
// PUT /admin/notification-preferences/{user}

// Reset to default (all enabled)
// PUT /admin/notification-preferences/{user}/reset

// Disable all
// PUT /admin/notification-preferences/{user}/disable-all

// Enable all
// PUT /admin/notification-preferences/{user}/enable-all
```

---

## Model Relationships

```php
// User -> NotificationPreference (one-to-one)
$user->notificationPreferences();

// NotificationPreference -> User (reverse)
$preference->user();

// Check if enabled
$preference->isEnabled('materi_baru');  // returns boolean
```

---

## Event Listeners (Optional)

Bisa menggunakan Laravel events untuk cleaner code:

```php
// In Materi model
use Illuminate\Database\Eloquent\Events\Created;

class Materi extends Model
{
    protected static function booted()
    {
        static::created(function ($model) {
            NotificationService::notifyMateriBaru(
                $model,
                auth()->user()
            );
        });
    }
}

// Now just do:
$materi = Materi::create([...]);  // Email sent automatically!
```

---

## Testing

```php
// Test sending email
php artisan email:test user

// Test specific type
php artisan email:test materi
php artisan email:test tugas
```

---

## Queue Management

```bash
# Check pending jobs
SELECT COUNT(*) FROM jobs;

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry {job-id}
php artisan queue:retry all

# Flush all jobs
php artisan queue:flush
```

---

## Environment Variables

```env
# SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@cendekia.local
MAIL_FROM_NAME="Admin Cendekia"

# Queue Configuration
QUEUE_CONNECTION=database
```

---

## Complete Example: Custom Notification

```php
<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\KelasPerkuliahan;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function store(Request $request, $kelasId)
    {
        // Validate
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'pertemuan_ke' => 'required|integer|min:1|max:16',
        ]);

        // Get kelas
        $kelas = KelasPerkuliahan::findOrFail($kelasId);

        // Create materi
        $materi = Materi::create([
            'kelas_perkuliahan_id' => $kelas->id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'],
            'pertemuan_ke' => $validated['pertemuan_ke'],
        ]);

        // Send notification
        NotificationService::notifyMateriBaru($materi, auth()->user());

        return redirect()
            ->route('dosen.kelas-materi', $kelas->id)
            ->with('success', 'Materi berhasil ditambahkan & email terkirim!');
    }
}
```

---

**For more details, see EMAIL_NOTIFICATION_SYSTEM.md**
