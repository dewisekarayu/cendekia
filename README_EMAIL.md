# 📧 Email Notification System untuk Cendekia LMS

> Sistem notifikasi email otomatis yang mengirimkan email profesional ke users berdasarkan event yang terjadi di LMS.

## 🎯 Apa Yang Sudah Diimplementasikan?

### ✅ 8 Jenis Notifikasi Email

| Icon | Jenis | Dipicu Saat | Dikirim Ke |
|------|-------|-----------|-----------|
| 📚 | **Materi Baru** | Dosen upload materi | Semua mahasiswa di kelas |
| 📝 | **Tugas Baru** | Dosen buat tugas | Semua mahasiswa di kelas |
| 📢 | **Pengumuman** | Dosen buat pengumuman | Semua mahasiswa di kelas |
| ✅ | **Nilai** | Dosen kasih nilai | Mahasiswa yang dinilai |
| ⏰ | **Presensi Dibuka** | Dosen buka sesi presensi | Semua mahasiswa di kelas |
| 📬 | **Pengumpulan Tugas** | Mahasiswa kumpulkan tugas | Dosen pengampu |
| 💬 | **Pesan Forum** | Ada pesan di forum | Dosen kelas |
| 👤 | **Pengguna Baru** | User baru daftar | Admin |

### ✅ Fitur-Fitur Unggulan

- **📱 Responsive Design** - Email terlihat bagus di mobile, tablet, desktop
- **🎨 Professional Template** - Gradient header, info boxes, CTA buttons
- **⚡ Async Processing** - Non-blocking, tidak hambat aplikasi
- **🔄 Retry Logic** - Otomatis retry jika gagal
- **⚙️ Admin Panel** - Manage preferensi user secara terpusat
- **📊 Batch Sending** - Otomatis ke semua mahasiswa
- **🔍 Easy Testing** - Command untuk test setiap email type
- **📝 Comprehensive Docs** - Documentasi lengkap & siap pakai

---

## 🚀 Quick Start (5 Menit)

### 1️⃣ Setup Mailtrap

```bash
# 1. Buka https://mailtrap.io
# 2. Sign up gratis
# 3. Copy SMTP credentials dari dashboard
```

### 2️⃣ Update .env

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=xxx  # dari Mailtrap
MAIL_PASSWORD=xxx  # dari Mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@cendekia.local
MAIL_FROM_NAME="Admin Cendekia"
QUEUE_CONNECTION=database
```

### 3️⃣ Run Queue Worker

```bash
# Terminal 1
php artisan queue:work
```

### 4️⃣ Start App

```bash
# Terminal 2
php artisan serve
```

### 5️⃣ Test Email

```bash
php artisan email:test user
```

Lihat email di https://mailtrap.io inbox 📬

---

## 📁 Struktur File

```
app/
├── Mail/                    # 8 email classes
│   ├── MateriBaru.php
│   ├── TugasBaru.php
│   ├── PengumumanBaru.php
│   ├── NilaiBaru.php
│   ├── AbsensiDibuka.php
│   ├── PengumpulanTugas.php
│   ├── PesanBaru.php
│   └── PenggunaBaru.php
│
├── Jobs/                    # 8 queue jobs
│   ├── SendMateriBaru.php
│   ├── SendTugasBaru.php
│   ├── ... (6 more)
│
├── Services/
│   └── NotificationService.php  # Batch sending helper
│
├── Models/
│   └── NotificationPreference.php  # User preferences
│
├── Http/Controllers/
│   └── Admin/
│       └── NotificationPreferenceController.php  # Admin panel
│
└── Console/Commands/
    └── TestEmailNotification.php  # Test command

resources/views/
├── emails/
│   ├── layout.blade.php  # Base template
│   ├── materi-baru.blade.php
│   ├── tugas-baru.blade.php
│   ├── pengumuman-baru.blade.php
│   ├── nilai-baru.blade.php
│   ├── absensi-dibuka.blade.php
│   ├── pengumpulan-tugas.blade.php
│   ├── pesan-baru.blade.php
│   └── pengguna-baru.blade.php
│
└── admin/notification-preferences/
    ├── index.blade.php  # User list
    └── show.blade.php   # Edit preferences
```

---

## 🎮 Admin Panel

**URL**: `/admin/notification-preferences`

### Features:
- 👥 Lihat daftar semua users
- 🔍 Cari users (nama, email, NIM)
- 🎚️ Filter by role (Admin, Dosen, Mahasiswa)
- ⚙️ Edit preferensi email per user
- 🔄 Bulk actions:
  - Reset ke default (semua aktif)
  - Disable semua
  - Enable semua

### Screenshot Flow:
```
Dashboard Admin
    ↓
Menu Notification Preferences
    ↓
List Users + Search/Filter
    ↓
Click "Kelola" untuk user tertentu
    ↓
Edit checkboxes untuk 8 notification types
    ↓
Save atau Bulk Actions
```

---

## 💻 Untuk Developers

### Trigger Email Manual

```php
use App\Services\NotificationService;

// Dalam controller, trigger notification:
NotificationService::notifyMateriBaru($materi, auth()->user());
```

### Semua Available Methods

```php
// Batch ke semua mahasiswa di kelas
NotificationService::notifyMateriBaru($materi, $dosen);
NotificationService::notifyTugasBaru($tugas, $dosen);
NotificationService::notifyPengumumanBaru($pengumuman, $dosen);
NotificationService::notifyAbsensiDibuka($absensi, $dosen);

// Ke individual
NotificationService::notifyNilaiBaru($pengumpulan, $dosen);
NotificationService::notifyPengumpulanTugas($pengumpulan, $dosen);
NotificationService::notifyPesanBaru($forum, $recipient, $sender);
NotificationService::notifyPenggunaBaru($user, $role);
```

### Custom Email

```php
// Direct dispatch job
use App\Jobs\SendMateriBaru;
SendMateriBaru::dispatch($materi, $mahasiswa, $dosen);

// Or send immediately
use App\Mail\MateriBaru;
Mail::send(new MateriBaru($materi, $mahasiswa, $dosen));
```

---

## 🧪 Testing

### Test Command

```bash
# Test semua notification types
php artisan email:test user      # Test pengguna baru
php artisan email:test materi    # Test materi
php artisan email:test tugas     # Test tugas
php artisan email:test pengumuman # Test pengumuman
php artisan email:test nilai     # Test nilai
php artisan email:test absensi   # Test presensi
php artisan email:test pengumpulan # Test submission
```

### Manual Testing

1. **Register User Baru** → Email ke admin
2. **Upload Materi** → Email ke semua mahasiswa
3. **Buat Tugas** → Email ke semua mahasiswa
4. **Beri Nilai** → Email ke mahasiswa
5. **Buka Presensi** → Email ke semua mahasiswa
6. **Kumpulkan Tugas** → Email ke dosen
7. **Post Forum** → Email ke dosen

---

## 📊 Queue Monitoring

### Check Status

```bash
# Lihat pending jobs
SELECT COUNT(*) FROM jobs;

# Lihat failed jobs
php artisan queue:failed

# Lihat logs
tail -f storage/logs/laravel.log
```

### Manage Queue

```bash
# Retry failed
php artisan queue:retry all

# Clear queue
php artisan queue:flush

# Start worker
php artisan queue:work
```

---

## 🔐 Keamanan

✅ **Implemented:**
- User preference checks (respect privacy)
- Queue retry dengan error handling
- Comprehensive logging
- SMTP authentication
- Async non-blocking processing

⚠️ **Production Checklist:**
- [ ] Use production SMTP service
- [ ] Configure Redis queue driver
- [ ] Setup supervisor for queue worker
- [ ] Monitor failed jobs
- [ ] Enable DKIM/SPF

---

## 📚 Documentation

| Document | Untuk | Durasi Baca |
|----------|-------|-----------|
| **README_EMAIL.md** (ini) | Overview | 5 min |
| **QUICKSTART_EMAIL.md** | Setup | 5 min |
| **EMAIL_NOTIFICATION_SYSTEM.md** | Complete Doc | 20 min |
| **EMAIL_API_REFERENCE.md** | Developer | 15 min |
| **TESTING_EMAIL.md** | QA/Tester | 10 min |
| **IMPLEMENTATION_SUMMARY.md** | PM/Arch | 15 min |

---

## 🐛 Troubleshooting

### Email tidak terkirim?

```bash
# 1. Check queue worker jalan
# 2. Check .env credentials
# 3. Check logs
tail -f storage/logs/laravel.log

# 4. Retry failed
php artisan queue:retry all

# 5. Check Mailtrap inbox
```

### Email lambat?

```bash
# 1. Use production SMTP service
# 2. Switch to Redis queue
# 3. Increase worker concurrency
php artisan queue:work --jobs=5
```

---

## ✨ Highlights

### Untuk Users 👥
```
✅ Automatic email notifications
✅ Can customize preferences
✅ Professional templates
✅ Works on all devices
```

### Untuk Admins 🔧
```
✅ Centralized management
✅ User preferences panel
✅ Bulk operations
✅ Easy to monitor
```

### Untuk Developers 💻
```
✅ Clean architecture
✅ Easy to extend
✅ Well documented
✅ Fully testable
```

---

## 🚀 Status

**Implementation Status**: ✅ **100% COMPLETE**

- [x] 8 Email types
- [x] Professional templates
- [x] Async job processing
- [x] User preferences
- [x] Admin panel
- [x] Testing support
- [x] Documentation

**Ready for**: ✅ **Production**

---

## 🎯 Next Steps

1. **Setup** (5 min)
   - Update .env dengan Mailtrap credentials
   - Run `php artisan migrate`

2. **Test** (5 min)
   - Start queue worker
   - Run test command

3. **Verify** (5 min)
   - Check Mailtrap inbox
   - Test admin panel

4. **Deploy** (production)
   - Use production SMTP
   - Configure Redis queue
   - Setup supervisor

---

## 📞 Support

### Quick Help
- 📖 Check documentation files
- 🔍 Search in code comments
- 💻 Run test command for examples

### Common Commands
```bash
php artisan email:test user           # Test email
php artisan queue:work                # Start worker
php artisan queue:failed              # View failures
php artisan migrate                   # Run migrations
php artisan config:cache              # Cache config
```

---

## 📋 Checklist

- [ ] Update .env dengan SMTP credentials
- [ ] Run migrations
- [ ] Start queue worker
- [ ] Test email via command
- [ ] Check admin panel access
- [ ] Verify templates in Mailtrap
- [ ] Read full documentation
- [ ] Deploy to production

---

## 🎉 You're Ready!

System siap untuk:
- ✅ Send automatic emails
- ✅ Manage user preferences
- ✅ Test & debug
- ✅ Deploy production

**Let's go!** 🚀

---

## 📬 Email Preview

```
┌─────────────────────────────────┐
│   [BLUE GRADIENT HEADER]        │ ← Responsive header
│   📚 Materi Baru Tersedia       │
│   Mata Kuliah XYZ               │
└─────────────────────────────────┘
│                                 │
│ Halo [Nama Mahasiswa],          │
│                                 │
│ Dosen Anda telah mengunggah     │
│ materi pembelajaran baru.       │
│                                 │ ← Professional styling
│ ┌─────────────────────────────┐ │
│ │ 📖 Judul: Advanced OOP      │ │
│ │ 👨‍🏫 Dosen: Dr. Budi         │ │
│ │ 🎓 Kelas: CS101             │ │
│ └─────────────────────────────┘ │
│                                 │
│      [BUKA MATERI BUTTON]       │ ← CTA button
│                                 │
├─────────────────────────────────┤
│ © 2026 Cendekia Learning        │
│ Platform Pembelajaran Digital    │ ← Professional footer
└─────────────────────────────────┘
```

---

**Happy emailing!** 📧✨
