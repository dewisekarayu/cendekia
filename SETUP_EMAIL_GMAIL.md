# Panduan Setup Email Notifications dengan Gmail

## 🎯 Tujuan
Mengkonfigurasi sistem presensi agar mengirim notifikasi email ke Gmail mahasiswa secara otomatis.

---

## 📋 Langkah 1: Setup Google Account (Admin/Dosen)

### Opsi A: Menggunakan Gmail App Password (RECOMMENDED - Lebih Aman)

#### 1.1 Enable 2-Factor Authentication
1. Buka https://myaccount.google.com
2. Klik **Security** di sidebar kiri
3. Scroll ke "2-Step Verification"
4. Klik **Get Started**
5. Follow instruksi verifikasi
6. **Centang "Confirm"** untuk enable 2FA

#### 1.2 Buat App Password
1. Buka https://myaccount.google.com/apppasswords
2. Di dropdown **Select the app**, pilih **Mail**
3. Di dropdown **Select the device**, pilih **Windows Computer** (atau device Anda)
4. Klik **Generate**
5. **Copy password** yang muncul (16 karakter)
   - Contoh: `abcd efgh ijkl mnop`

---

## 🔧 Langkah 2: Update `.env` File

Buka file `d:\laragon\www\cendekia\.env` dan ubah bagian MAIL:

```ini
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_16_digit_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="Admin Cendekia"

# Queue Configuration
QUEUE_CONNECTION=database
```

### Contoh Configuration:
```ini
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=admin.cendekia@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin.cendekia@gmail.com"
MAIL_FROM_NAME="Sistem Presensi Cendekia"
```

---

## ✅ Langkah 3: Verify Configuration

Jalankan test command untuk memastikan konfigurasi benar:

```bash
php artisan test:absensi --clean
php artisan test:absensi
```

Seharusnya:
- ✅ 10 jobs queued
- ✅ Notifications dispatched

---

## 📤 Langkah 4: Process Queue

### Opsi A: Manual Test (untuk development)
```bash
php artisan queue:work --stop-when-empty
```

**Output yang diharapkan:**
```
2026-07-14 03:07:46 App\Jobs\SendAbsensiDibuka . 55.05ms DONE
2026-07-14 03:07:46 App\Jobs\SendAbsensiDibuka . 55.05ms DONE
...
```

Lihat email masuk ke Gmail mahasiswa dalam beberapa detik! ✅

### Opsi B: Auto Background Process (untuk production)

#### Windows - Menggunakan Task Scheduler

1. Buka **Task Scheduler**
2. Klik **Create Basic Task**
3. Isi:
   - **Name**: Cendekia Queue Worker
   - **Description**: Process email notifications queue
4. Trigger: **Daily** jam 6 pagi
5. Action: **Start a program**
   - Program: `php.exe` 
   - Path: `d:\laragon\bin\php\php8.1.0-Win32-x64-library-vs16\php.exe`
   - Arguments: `d:\laragon\www\cendekia\artisan queue:work --stop-when-empty --max-jobs=100`
6. **Finish**

#### Linux/Mac - Menggunakan Supervisor

1. Install Supervisor
2. Buat file `/etc/supervisor/conf.d/cendekia-queue.conf`:

```ini
[program:cendekia-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/cendekia/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=/var/log/cendekia-queue.log
```

3. Restart Supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start cendekia-queue:*
```

---

## 🧪 Langkah 5: Test Email

### Test 1: Cek Queue
```bash
php artisan queue:failed
```

Jika ada failed jobs, ada masalah dengan konfigurasi.

### Test 2: Manual Send
Jalankan di tinker:

```bash
php artisan tinker
>>> Mail::to('mahasiswa@gmail.com')->send(new \App\Mail\AbsensiDibuka(...))
```

### Test 3: Check Logs
```bash
tail -f storage/logs/laravel.log | grep -i "email\|absensi"
```

---

## 🔒 Troubleshooting

### ❌ Error: "SMTP Error: Could not connect to SMTP host"
**Solusi:**
- Pastikan MAIL_HOST: `smtp.gmail.com` (bukan mail.gmail.com)
- Pastikan MAIL_PORT: `587` (bukan 25 atau 465)
- Cek koneksi internet

### ❌ Error: "Authentication failed"
**Solusi:**
- Copy-paste app password ulang (jangan manual ketik)
- Pastikan tidak ada space di awal/akhir password
- Pastikan 2FA sudah enabled di Google Account
- Coba generate app password baru

### ❌ Error: "Connection timed out"
**Solusi:**
- Cek firewall, pastikan port 587 allowed
- Coba dengan port 465 + `MAIL_ENCRYPTION=ssl`
- Coba dari network lain

### ❌ Email not received tapi no error
**Solusi:**
- Check **Spam/Junk** folder
- Pastikan email mahasiswa benar di database
- Check user.email di database: `SELECT id, name, email FROM users LIMIT 10`
- Cek logs: `php artisan queue:failed`

### ❌ Jobs stuck in queue
**Solusi:**
```bash
# Clear failed jobs
php artisan queue:flush

# Restart queue worker
php artisan queue:work --stop-when-empty

# Or clear & retry
php artisan queue:retry all
```

---

## 📊 Monitoring

### Cek Status Queue
```bash
php artisan queue:info
```

### Cek Failed Jobs
```bash
php artisan queue:failed
php artisan queue:retry {id}
```

### Cek Logs Real-time
```bash
tail -f storage/logs/laravel.log
```

---

## 🎯 Checklist

- [ ] 2FA enabled di Google Account
- [ ] App password generated & copied
- [ ] `.env` file updated dengan credentials
- [ ] `MAIL_MAILER=smtp` (bukan log)
- [ ] `MAIL_HOST=smtp.gmail.com`
- [ ] `MAIL_PORT=587`
- [ ] `MAIL_ENCRYPTION=tls`
- [ ] Test dengan `php artisan test:absensi`
- [ ] Process queue dengan `php artisan queue:work`
- [ ] Email diterima di Gmail mahasiswa ✅

---

## 📧 Email Content

Email yang diterima mahasiswa akan berisi:

```
Subject: ⏰ Sesi Presensi Dibuka - Pertemuan 1

Konten:
- Nama pengajar
- Mata kuliah
- Kelas
- Tanggal & waktu
- Link untuk buka menu presensi
- Instruksi singkat
```

---

## 🚀 Production Deployment

Untuk production server, pastikan:

1. **Queue Worker Always Running**
   - Gunakan Supervisor (Linux)
   - Gunakan Task Scheduler (Windows)
   - Gunakan systemd (Linux)

2. **Email Rate Limiting**
   - Jangan kirim >100 emails per menit
   - Gunakan `--delay=1000` untuk throttling

3. **Error Handling**
   - Monitor failed jobs regularly
   - Setup alerts untuk failed jobs
   - Log all email activities

4. **Security**
   - Jangan commit app password ke git
   - Gunakan `.env.example` tanpa credentials
   - Rotate app password setiap 3 bulan

---

## 📝 References

- [Laravel Mail Documentation](https://laravel.com/docs/11.x/mail)
- [Laravel Queue Documentation](https://laravel.com/docs/11.x/queues)
- [Gmail SMTP Settings](https://support.google.com/mail/answer/7126229)
- [Google App Passwords](https://support.google.com/accounts/answer/185833)

---

**Need Help?**
- Check logs: `storage/logs/laravel.log`
- Run diagnostics: `php artisan mail:test admin@cendekia.local`
- Contact: admin@cendekia.local
