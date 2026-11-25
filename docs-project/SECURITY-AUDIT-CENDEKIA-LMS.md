# Security Audit: LMS Cendekia Backend

> Diperiksa oleh: Raditya Rai Zeeshan
> Tanggal: 24 November 2026
> Scope: Backend Laravel — routes, controllers, middleware, models, API

---

## Ringkasan Eksekutif

Backend Cendekia dibangun di atas Laravel dengan autentikasi Breeze, role management Spatie Permission, dan Sanctum untuk API mobile. Secara keseluruhan, lapisan autentikasi dan otorisasi berbasis role sudah berjalan. Namun ada beberapa titik yang perlu diperkuat sebelum masuk ke lingkungan produksi publik.

Temuan dikelompokkan menjadi tiga tingkat: **Kritis**, **Medium**, dan **Informasional**.

---

## Temuan Kritis

### C-01: API Key Bocor di `.env.example`

**File:** [`/.env.example` baris 71](file:///D:/Raditya%20Dokumen/cendekia/.env.example#L71)

```
ROUTE_API_KEY=sk-b0d39d13c3b5948b-tgr1rk-d4646932
```

`.env.example` di-commit ke repositori dan berisi API key yang terlihat seperti nilai nyata — bukan placeholder kosong. Jika key ini aktif di production, siapa saja yang membaca repositori bisa memakainya untuk memanggil layanan AI router.

**Perbaikan:**

1. Ganti nilai di `.env.example` menjadi placeholder: `ROUTE_API_KEY=your-ai-router-key-here`
2. Rotasi key ini di dashboard 9Router sekarang, karena riwayat Git menyimpan nilai lama.
3. Pastikan `.env` dan `.env.production` masuk `.gitignore` dan tidak pernah ter-commit.

---

### C-02: Tiga Endpoint API Publik Tanpa Auth

**File:** [`/routes/api.php` baris 30-40](file:///D:/Raditya%20Dokumen/cendekia/routes/api.php#L30-L40)

```php
Route::get('/users', function () {
    return response()->json(['message' => 'API Users aktif']);
});

Route::get('/students', function () {
    return response()->json(['message' => 'API Students aktif']);
});

Route::get('/teachers', function () {
    return response()->json(['message' => 'API Teachers aktif']);
});
```

Tiga route ini terbuka tanpa middleware `auth:sanctum`. Saat ini hanya mengembalikan pesan status, tapi mereka mendokumentasikan eksistensi endpoint. Kalau suatu saat route-route ini diisi logika nyata tanpa ingat menambahkan auth, hasilnya jadi lubang terbuka.

**Perbaikan:** Pindahkan ketiga route ke dalam blok `middleware('auth:sanctum')` yang sudah ada, atau hapus jika memang tidak dipakai.

---

### C-03: Tiket Bantuan Bisa Dikirim Tanpa Login + Attachment Tanpa Validasi Tipe File

**File:** [`/routes/web.php` baris 443](file:///D:/Raditya%20Dokumen/cendekia/routes/web.php#L443), [`/app/Http/Controllers/HelpCenterController.php` baris 463-485](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Controllers/HelpCenterController.php#L463-L485)

Route `/help-center/ticket` (POST) tidak memerlukan auth. Ini memang disengaja agar tamu bisa melapor — tapi validasi attachment-nya terlalu longgar:

```php
'attachment' => ['nullable', 'file', 'max:5120'],
```

Tidak ada validasi `mimes:` untuk attachment tiket. Penyerang bisa mengunggah file PHP atau SVG yang kemudian disimpan ke `storage/app/public/help-tickets`. Jika direktori itu dapat diakses langsung via web (symlink `public/storage`), file bisa dieksekusi di server shared hosting.

**Perbaikan:**

```php
'attachment' => ['nullable', 'file', 'max:5120', 'mimes:pdf,jpg,jpeg,png,doc,docx,txt'],
```

Tambahkan juga validasi mime-type aktual (bukan hanya ekstensi) menggunakan `Illuminate\Validation\Rules\File::types(...)` jika tersedia.

---

## Temuan Medium

### M-01: `RoleMiddleware` Bocorkan Daftar Role User di Body Respons 403

**File:** [`/app/Http/Middleware/RoleMiddleware.php` baris 82-87](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Middleware/RoleMiddleware.php#L82-L87)

```php
return response()->json([
    'message'  => 'User does not have the required role(s).',
    'required' => $requiredRoles,
    'current'  => $user->getRoleNames()->toArray(),
    'user_id'  => $user->id,
], 403);
```

Respons 403 ini menyertakan `current` (daftar role user saat ini) dan `user_id`. Informasi ini tidak perlu dikirim ke klien — cukup pesan "forbidden". Data role yang terbaca membantu penyerang memetakan privilege akun yang sudah berhasil mereka akses (misalnya setelah credential stuffing).

**Perbaikan:**

```php
return response()->json([
    'message' => 'Akses ditolak.',
], 403);
```

Simpan detail lengkap di `Log::warning(...)` saja — tidak perlu dikembalikan ke body respons.

---

### M-02: `show()` Mahasiswa Tidak Memvalidasi Kepemilikan Tugas terhadap Kelas yang Diikuti

**File:** [`/app/Http/Controllers/Mahasiswa/PengumpulantugasController.php` baris 20-33](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Controllers/Mahasiswa/PengumpulantugasController.php#L20-L33)

```php
public function show(Tugas $tugas)
{
    $mahasiswaId = Auth::id();
    $pengumpulan = PengumpulanTugas::with(['tugas', 'files'])
        ->where('tugas_id', $tugas->id)
        ->where('mahasiswa_id', $mahasiswaId)
        ->where('status', '!=', PengumpulanTugas::STATUS_BELUM_DIKUMPUL)
        ->first();
    $tugas->load('kelasPerkuliahan.mataKuliah');

    return view('mahasiswa.pengumpulan-tugas', [
        'tugas'       => $tugas,
        'pengumpulan' => $pengumpulan,
    ]);
}
```

Method `show()` menerima `$tugas` via route model binding tanpa memeriksa apakah mahasiswa yang login terdaftar di kelas tempat tugas itu berada. Seorang mahasiswa yang mengetahui ID tugas dari kelas lain bisa mengakses halaman detail tugas tersebut (judul, instruksi, lampiran soal), meski tidak terdaftar di kelas itu.

Berbeda dengan `store()` dan `destroy()` yang sudah membatasi via `PengumpulanTugas`, method `show()` belum punya guard ini.

**Perbaikan:**

```php
public function show(Tugas $tugas)
{
    $mahasiswaId = Auth::id();

    // Pastikan mahasiswa terdaftar di kelas tempat tugas ini berada
    $enrolled = KelasMahasiswa::where('kelas_perkuliahan_id', $tugas->kelas_perkuliahan_id)
        ->where('mahasiswa_id', $mahasiswaId)
        ->exists();

    abort_unless($enrolled, 403, 'Kamu tidak terdaftar di kelas tugas ini.');

    // ... sisa logika
}
```

---

### M-03: `extractTextFromFile()` Mempakai `exec()` dengan Path yang Dibangun dari Variabel

**File:** [`/app/Http/Controllers/Dosen/AiAssistantController.php` baris 467-470](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Controllers/Dosen/AiAssistantController.php#L467-L470)

```php
$escapedPath = escapeshellarg($fullPath);
$output = [];
$returnVar = 0;
exec("pdftotext -q -enc UTF-8 {$escapedPath} -", $output, $returnVar);
```

Fungsi ini ada di method private yang menerima `$fullPath` dari file yang sudah diunggah. `escapeshellarg` memang melindungi dari shell injection, jadi ini bukan command injection. Namun ada dua catatan:

1. Method ini adalah fungsi lama (tidak dipanggil dari `extractDocumentText()` yang baru). Fungsi baru sudah menggunakan `Symfony\Process` yang lebih aman. Fungsi lama ini sebaiknya dihapus untuk mengurangi attack surface.
2. Eksistensi dua metode ekstraksi PDF yang berbeda (satu via `exec()`, satu via `Symfony\Process`) membingungkan dan meningkatkan kemungkinan maintenance error di masa depan.

**Perbaikan:** Hapus method `extractTextFromFile()` dan `extractTextFromPdfStream()` karena keduanya sudah digantikan oleh `extractDocumentText()` yang lebih baik.

---

### M-04: QR Scan Absensi Tidak Membatasi Percobaan (Rate Limiting)

**File:** [`/app/Http/Controllers/Mahasiswa/AbsensiController.php` baris 194-253](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Controllers/Mahasiswa/AbsensiController.php#L194-L253)

Route `GET /mahasiswa/absensi/scan/{token}` dapat dipukul berulang kali oleh siapa pun yang sudah login sebagai mahasiswa. Token QR yang pendek atau berstruktur lemah bisa dienumerate.

Saat ini tidak ada throttling di level route untuk endpoint ini. Jika token QR menggunakan format yang dapat ditebak, penyerang bisa brute-force token dan melakukan absensi palsu.

**Perbaikan:**

1. Tambahkan `throttle:10,1` di route scan QR.
2. Pastikan `qr_token` di tabel `absensi` menggunakan minimal 128 bit entropi (mis. `Str::random(64)` atau `bin2hex(random_bytes(32))`).
3. Invalidasi token setelah sesi ditutup (sudah ada logika `checkAndAutoClose()` — pastikan token di-null setelah tutup).

---

### M-05: `SESSION_ENCRYPT=false` di `.env.example`

**File:** [`/.env.example` baris 33](file:///D:/Raditya%20Dokumen/cendekia/.env.example#L33)

```
SESSION_ENCRYPT=false
```

Enkripsi sesi dinonaktifkan. Data sesi Laravel (termasuk flash messages, CSRF state) tersimpan di tabel database tanpa enkripsi tambahan. Jika seseorang mendapat akses baca ke tabel `sessions` (mis. via SQL injection di titik lain), mereka bisa membaca data sesi.

**Perbaikan:** Set `SESSION_ENCRYPT=true` di production. Laravel akan mengenkripsi payload sesi menggunakan `APP_KEY`.

---

## Temuan Informasional

### I-01: `APP_DEBUG=true` di `.env.example`

**File:** [`/.env.example` baris 4](file:///D:/Raditya%20Dokumen/cendekia/.env.example#L4)

Debug mode aktif di `.env.example`. Jika developer lupa mengubah ini sebelum deploy, stack trace lengkap (termasuk path file, variabel, dan nilai environment) akan tampil ke pengguna saat ada error.

**Perbaikan:** Ubah nilai default ke `APP_DEBUG=false` di `.env.example`. Tim dev bisa mengubahnya secara lokal sendiri.

---

### I-02: `canAccessForumDetailed()` Mengembalikan Data Debug Internal

**File:** [`/app/Models/User.php` baris 257-317](file:///D:/Raditya%20Dokumen/cendekia/app/Models/User.php#L257-L317)

```php
'debug_info' => [
    'classes_teaching' => $classesTeaching,
    'classes_enrolled' => $classesEnrolled,
    'user_roles'       => $this->getRoleNames()->toArray(),
],
```

Method `canAccessForumDetailed()` mengembalikan array berisi informasi debugging — daftar kelas yang diajar/diikuti dan role user. Jika metode ini dipanggil dan outputnya dikirim langsung ke respons HTTP (misalnya dalam API atau view JSON), data internal ini terekspos ke klien.

Saat ini method ini tampaknya dipakai untuk logging internal, tapi tidak ada guard untuk memastikan outputnya tidak muncul di response produksi.

**Perbaikan:** Pisahkan logika debug ke `Log::debug()` dan buat method yang hanya mengembalikan `bool` untuk kebutuhan kontrol akses.

---

### I-03: `storeTicket()` Menerima Tiket dari Tamu Tanpa Rate Limiting

**File:** [`/app/Http/Controllers/HelpCenterController.php` baris 463](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Controllers/HelpCenterController.php#L463)

Endpoint `POST /help-center/ticket` tidak memerlukan auth dan tidak ada `throttle` middleware. Siapa pun bisa mengirim ribuan tiket secara otomatis, membanjiri tabel `tickets` dan inbox notifikasi admin.

**Perbaikan:** Tambahkan `throttle:5,1` per IP untuk route ini.

---

### I-04: `isAdminOnline()` Mempakai `env()` Langsung di Runtime

**File:** [`/app/Http/Controllers/HelpCenterController.php` baris 187](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Controllers/HelpCenterController.php#L187)

```php
$minutes = (int) env('ADMIN_ONLINE_MINUTES', 5);
```

Memanggil `env()` langsung di runtime (bukan di konfigurasi) tidak akan bekerja setelah `php artisan config:cache` dijalankan — sebuah langkah wajib di production.

**Perbaikan:** Pindahkan ke `config/app.php` atau file config khusus, lalu akses via `config('app.admin_online_minutes', 5)`.

---

### I-05: Dua Middleware Role yang Tumpang Tindih

**Files:**
- [`/app/Http/Middleware/RoleMiddleware.php`](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Middleware/RoleMiddleware.php)
- [`/app/Http/Middleware/EnsureUserHasRole.php`](file:///D:/Raditya%20Dokumen/cendekia/app/Http/Middleware/EnsureUserHasRole.php)

Ada dua middleware yang melakukan hal serupa. `RoleMiddleware` dipakai di routes web (`role:admin`, `role:dosen`, `role:mahasiswa`). `EnsureUserHasRole` juga ada tapi tidak terlihat dipakai di routes utama. Keberadaan dua middleware role yang berbeda meningkatkan risiko inkonsistensi di masa depan — developer mungkin memakai yang salah.

**Perbaikan:** Konsolidasikan ke satu middleware. Periksa mana yang lebih banyak dipakai di bootstrap/kernel, lalu hapus yang lain.

---

## Hal yang Sudah Baik

Beberapa pola keamanan di codebase ini sudah tepat:

**Ownership check konsisten di controller Dosen.** Setiap query di `DosenKelasController` selalu menggunakan `->where('dosen_id', $request->user()->id)->findOrFail()`, memastikan dosen hanya bisa mengakses kelas miliknya sendiri.

**Transaction + `lockForUpdate()` di absensi.** `AbsensiController::absenMasuk()` dan `scanQr()` sudah memakai DB transaction dengan pessimistic lock untuk mencegah race condition double-submit absensi.

**Validasi file upload yang spesifik.** Controller tugas dan materi sudah memvalidasi `mimes:` dan `max:` untuk file upload — tidak ada wildcard `*` yang berbahaya.

**ProfilePhotoController sudah hardened.** Validasi regex untuk filename, pengecekan mime-type aktual, dan header `X-Content-Type-Options: nosniff` sudah ada.

**Password di-hash via casting.** Model User sudah menggunakan `'password' => 'hashed'` di method `casts()` — password tidak pernah tersimpan plain text.

**Mahasiswa tidak bisa akses tugas kelas lain saat upload.** `store()` di `PengumpulantugasController` mengikat pengumpulan ke `Auth::id()` via `updateOrCreate`, bukan dari input request.

---

## Prioritas Perbaikan

| Prioritas | ID | Deskripsi |
|---|---|---|
| Segera | C-01 | Rotasi API key yang bocor di `.env.example` |
| Segera | C-03 | Tambahkan `mimes:` validation untuk attachment tiket |
| Minggu ini | C-02 | Pindahkan API endpoint publik ke dalam auth middleware |
| Minggu ini | M-01 | Hapus data role dari respons 403 |
| Minggu ini | M-02 | Tambahkan enrollment check di `PengumpulantugasController::show()` |
| Sprint berikutnya | M-03 | Hapus method `extractTextFromFile()` lama |
| Sprint berikutnya | M-04 | Tambahkan throttle di QR scan + periksa entropi token |
| Sprint berikutnya | M-05 | Aktifkan `SESSION_ENCRYPT=true` |
| Kapanpun | I-01 | Ubah `APP_DEBUG=false` di `.env.example` |
| Kapanpun | I-02 | Pisahkan debug info dari method akses kontrol |
| Kapanpun | I-03 | Tambahkan throttle di endpoint tiket tamu |
| Kapanpun | I-04 | Ganti `env()` dengan `config()` di `isAdminOnline()` |
| Kapanpun | I-05 | Konsolidasikan dua middleware role |
