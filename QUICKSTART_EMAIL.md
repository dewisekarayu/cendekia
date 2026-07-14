# 🚀 Email Notification System - Quick Start

## 5 Menit Setup untuk Development

### Step 1: Setup Mailtrap (2 menit)

1. Buka https://mailtrap.io
2. Sign up gratis (atau login jika sudah punya)
3. Dari dashboard → Inbox → Integrations → Laravel
4. Copy credentials:
   ```
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=xxx
   MAIL_PASSWORD=xxx
   ```

### Step 2: Update .env (1 menit)

```bash
# Di project root, update .env:
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username_dari_mailtrap
MAIL_PASSWORD=your_password_dari_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@cendekia.local
MAIL_FROM_NAME="Admin Cendekia"

QUEUE_CONNECTION=database
```

### Step 3: Run Queue Worker (1 menit)

```bash
# Terminal 1 - Queue Worker
cd d:\laragon\www\cendekia
php artisan queue:work

# Output yang diharapkan:
# Processing jobs from the [default] queue
```

### Step 4: Jalankan App (1 menit)

```bash
# Terminal 2 - Application Server
cd d:\laragon\www\cendekia
php artisan serve

# http://localhost:8000
```

## 🧪 Test Email

### Test via Command

```bash
# Test email pengguna baru
php artisan email:test user

# Output:
# Testing user email notification...
# ✓ Email user queued successfully!
# Run 'php artisan queue:work' to process the queue.
```

### Test via UI

1. **Register pengguna baru** → Kirim email notifikasi ke admin
2. **Login Dosen** → Upload materi → Email ke semua mahasiswa di kelas
3. **Login Dosen** → Buat tugas → Email ke semua mahasiswa
4. **Login Mahasiswa** → Kumpulkan tugas → Email ke dosen
5. **Login Dosen** → Beri nilai → Email ke mahasiswa
6. **Login Dosen** → Buat pengumuman → Email ke semua mahasiswa
7. **Login Dosen** → Buka presensi → Email ke semua mahasiswa

### Lihat Email di Mailtrap

1. Buka https://mailtrap.io
2. Dashboard → Inbox
3. Klik email untuk lihat full template
4. Check content, formatting, dan links

## 📊 Admin Panel

**URL**: `http://localhost:8000/admin/notification-preferences`

### Features:
- ✅ Cari users (nama, email, NIM/NIP)
- ✅ Filter by role (Admin, Dosen, Mahasiswa)
- ✅ View notification status per user
- ✅ Edit preferences individually
- ✅ Bulk actions (Reset, Enable All, Disable All)

### Akses:
- Login sebagai **admin**
- Pergi ke dashboard
- Cari menu "Notification Preferences" atau akses langsung URL di atas

## 🔄 Queue Monitoring

### Check Pending Jobs

```bash
# PowerShell
# Lihat berapa jobs pending
(Invoke-SqlCmd -Query "SELECT COUNT(*) as pending FROM jobs" -ServerInstance localhost -Database cendekia).pending
```

### Check Failed Jobs

```bash
# Lihat jobs yang failed
php artisan queue:failed

# Retry semua failed jobs
php artisan queue:retry all

# Flush semua jobs
php artisan queue:flush
```

## 📧 Email Types Quick Reference

| Type | Trigger | Recipients | Key Info |
|------|---------|-----------|----------|
| 📚 Materi Baru | Dosen upload materi | All students in class | Includes materi title, description |
| 📝 Tugas Baru | Dosen create task | All students in class | Includes deadline |
| 📢 Pengumuman | Dosen post announcement | All students in class | Full announcement text |
| ✅ Nilai Baru | Dosen grade task | Student who submitted | Score + feedback |
| ⏰ Presensi Dibuka | Dosen opens attendance | All students in class | Meeting #, date, time |
| 📬 Pengumpulan Tugas | Student submit task | Task lecturer | Student name, submission time |
| 💬 Pesan Baru | Forum post | Relevant users | Message preview |
| 👤 Pengguna Baru | User registers | Admins | User role, registration time |

## 🐛 Troubleshooting

### Email tidak terkirim?

1. **Check queue worker** - Pastikan terminal dengan `php artisan queue:work` jalan
2. **Check .env** - Verify MAIL_* credentials benar
3. **Check logs** - `tail -f storage/logs/laravel.log`
4. **Check failed jobs** - `php artisan queue:failed`

### Connection timeout?

```bash
# Mailtrap bisa lambat, increase timeout
# Edit .env:
MAIL_TIMEOUT=10  # seconds
```

### Email ke Mailtrap tapi tidak terlihat?

1. Refresh Mailtrap dashboard
2. Check spam folder
3. Verify email address correct di .env

## 🔐 Production Setup

Untuk production:

```env
# Use production SMTP service (SendGrid, AWS SES, etc)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net  # example
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxxx

# Use Redis queue untuk better performance
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Setup supervisor untuk queue worker
# atau systemd service
```

## 📚 Full Documentation

Lihat file-file ini untuk detail lengkap:
- `EMAIL_NOTIFICATION_SYSTEM.md` - Comprehensive documentation
- `TESTING_EMAIL.md` - Advanced testing guide
- Code comments di `app/Services/NotificationService.php`

## ✅ Checklist

- [ ] Update .env dengan Mailtrap credentials
- [ ] Run `php artisan migrate`
- [ ] Start queue worker: `php artisan queue:work`
- [ ] Start app: `php artisan serve`
- [ ] Test dengan `php artisan email:test user`
- [ ] Check Mailtrap inbox untuk verify email
- [ ] Test manual dari UI
- [ ] Check admin panel at `/admin/notification-preferences`

## 🎉 Done!

System siap untuk:
- ✅ Send emails otomatis saat events
- ✅ Manage preferences via admin panel
- ✅ Test dengan Mailtrap
- ✅ Deploy ke production

**Butuh help?** Check logs atau dokumentasi lengkap di EMAIL_NOTIFICATION_SYSTEM.md

Enjoy! 🚀
