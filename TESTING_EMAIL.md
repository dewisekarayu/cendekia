# Email Notification System - Testing Guide

## Setup untuk Development

### Option 1: Menggunakan Mailtrap (Recommended untuk Development)

1. **Buat akun Mailtrap** di https://mailtrap.io
2. **Dapatkan SMTP credentials** dari dashboard Mailtrap
3. **Update `.env`** dengan credentials Mailtrap:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@cendekia.local
   MAIL_FROM_NAME="Admin Cendekia"
   ```

4. **Setup Queue Driver** (sudah dikonfigurasi):
   ```
   QUEUE_CONNECTION=database
   ```

### Option 2: Menggunakan Mailpit (Local Testing)

1. **Install Mailpit** (memerlukan Go atau Docker):
   ```bash
   # Menggunakan Docker
   docker run -d --name mailpit -p 1025:1025 -p 8025:8025 axllent/mailpit
   ```

2. **Update `.env`**:
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=127.0.0.1
   MAIL_PORT=1025
   MAIL_FROM_ADDRESS=noreply@cendekia.local
   MAIL_FROM_NAME="Admin Cendekia"
   QUEUE_CONNECTION=database
   ```

3. **Akses Mailpit UI** di http://localhost:8025

## Menjalankan Queue Worker

Untuk memproses email yang ada di queue:

```bash
# Terminal 1: Jalankan queue worker
php artisan queue:work

# Terminal 2: Jalankan application
php artisan serve
```

Jangan gunakan `queue:work --daemon` untuk development karena bisa cause memory leak.

## Testing Notifications

### 1. Test Email Notifikasi Pengguna Baru

```bash
php artisan email:test user
```

### 2. Test semua email types

```bash
php artisan email:test materi
php artisan email:test tugas
php artisan email:test pengumuman
php artisan email:test nilai
php artisan email:test absensi
php artisan email:test pengumpulan
php artisan email:test pesan
```

### 3. Manual Testing via UI

1. **Registrasi user baru** - Akan trigger `notifyPenggunaBaru`
2. **Login sebagai dosen** → Upload materi - Akan trigger `notifyMateriBaru` ke semua mahasiswa kelas
3. **Login sebagai dosen** → Buat tugas - Akan trigger `notifyTugasBaru` ke semua mahasiswa
4. **Login sebagai mahasiswa** → Kumpulkan tugas - Akan trigger `notifyPengumpulanTugas` ke dosen
5. **Login sebagai dosen** → Beri nilai - Akan trigger `notifyNilaiBaru` ke mahasiswa
6. **Login sebagai dosen** → Buat pengumuman - Akan trigger `notifyPengumumanBaru` ke semua mahasiswa
7. **Login sebagai dosen** → Buka sesi presensi - Akan trigger `notifyAbsensiDibuka` ke semua mahasiswa

## Melihat Email Logs

### Via Mailtrap
- Akses dashboard Mailtrap untuk melihat semua email yang dikirim

### Via Mailpit
- Akses http://localhost:8025 untuk melihat UI dengan email history

### Via Laravel Logs
```bash
# Lihat log file
tail -f storage/logs/laravel.log

# Atau gunakan Telescope untuk UI yang lebih menarik
php artisan telescope:install
```

## Troubleshooting

### Email tidak terkirim
1. Pastikan queue worker berjalan: `php artisan queue:work`
2. Cek `.env` configuration untuk SMTP credentials
3. Lihat failed_jobs table: `php artisan queue:failed`

### Retry failed jobs
```bash
# Retry 1 job
php artisan queue:retry {job_id}

# Retry semua failed jobs
php artisan queue:retry all

# Flush semua failed jobs
php artisan queue:flush
```

### Clear queue
```bash
php artisan queue:flush
```

## Database Tables untuk Email Tracking

Tabel yang digunakan oleh queue system:
- `jobs` - Queue jobs yang pending
- `failed_jobs` - Jobs yang failed setelah retry attempts

Check status:
```bash
# Lihat pending jobs
SELECT COUNT(*) FROM jobs;

# Lihat failed jobs
SELECT COUNT(*) FROM failed_jobs;
```

## Performance Tips

1. **Batch email sending** - NotificationService sudah handle batch notifications
2. **Async processing** - Semua emails diproses via queue (non-blocking)
3. **Retry logic** - Max 3 retries dengan exponential backoff
4. **Logging** - Semua success/failure logs di `storage/logs/laravel.log`

## Production Checklist

- [ ] Update SMTP credentials dengan production email service (SendGrid, AWS SES, etc)
- [ ] Setup Redis atau alternative queue driver untuk production
- [ ] Configure queue worker dengan supervisor atau systemd
- [ ] Setup monitoring untuk failed jobs
- [ ] Configure email rate limiting jika perlu
- [ ] Test dengan volume tinggi (batch sending)
- [ ] Setup email bouncing/complaint handling
