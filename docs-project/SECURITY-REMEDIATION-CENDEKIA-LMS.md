# Laporan Perbaikan Keamanan Backend: LMS Cendekia

Dikerjakan oleh: Raditya Rai Zeeshan  
Tanggal: 25 November 2026  
Referensi audit: [SECURITY-AUDIT-CENDEKIA-LMS.md](./SECURITY-AUDIT-CENDEKIA-LMS.md)

## Ringkasan Pengerjaan

Raditya Rai Zeeshan menyelesaikan seluruh 13 temuan keamanan backend LMS Cendekia yang tercatat pada laporan audit sebelumnya. Perbaikan mencakup 3 temuan kritis, 5 temuan medium, dan 5 temuan informasional di lapisan konfigurasi, rute, middleware, controller, dan model Laravel.

Setiap temuan dipisahkan menjadi commit tersendiri pada branch `main` (commit `233af30` sampai `98c4415`) agar tim dapat meninjau atau membatalkan tiap perubahan secara terisolasi. Setelah seluruh perubahan masuk, Raditya menjalankan pengujian regresi dan linting pada mode `config:cache` untuk memastikan aplikasi tetap berjalan normal.

## Perbandingan Keamanan Sebelum dan Sesudah Perbaikan

| Area | Kondisi Sebelumnya | Kondisi Sekarang |
|---|---|---|
| Kredensial & template `.env` | `ROUTE_API_KEY` berisi key aktif, `APP_DEBUG=true`, dan `SESSION_ENCRYPT=false` di `.env.example`. | `ROUTE_API_KEY` diganti placeholder, `APP_DEBUG=false`, dan `SESSION_ENCRYPT=true` di `.env.example`. |
| Endpoint API publik | Tiga rute dummy (`/api/users`, `/api/students`, `/api/teachers`) terbuka tanpa autentikasi. | Ketiga rute dummy dihapus dari `routes/api.php` dan mengembalikan HTTP 404. |
| Upload lampiran tiket | `HelpCenterController::storeTicket()` hanya membatasi ukuran 5 MB tanpa filter tipe file. | Validasi memeriksa MIME aktual dan ekstensi file (`pdf`, `jpg`, `jpeg`, `png`, `doc`, `docx`, `txt`). |
| Pembatasan permintaan | Endpoint scan QR absensi dan form tiket tamu tidak memiliki batas permintaan. | Scan QR dibatasi 10 permintaan/menit (`throttle:10,1`) dan tiket tamu 5 permintaan/menit (`throttle:5,1`). |
| Token QR absensi | `Absensi::bukaSession()` membuat token 40 karakter dan membiarkannya aktif di database setelah sesi ditutup. | `bukaSession()` memakai `bin2hex(random_bytes(32))` (64 karakter hex) dan `tutupSession()` mereset `qr_token` menjadi `null`. |
| Otorisasi tugas mahasiswa | `PengumpulantugasController::show()` dan `store()` mengizinkan mahasiswa mengakses tugas kelas lain lewat ID tugas. | Helper `ensureEnrolled()` memblokir akses dengan HTTP 403 apabila mahasiswa tidak tercatat di `kelas_mahasiswa`. |
| Kebocoran info internal | `RoleMiddleware` mengirim daftar role dan `user_id` di respons 403; `User::canAccessForumDetailed()` mengembalikan array `debug_info`. | Respons 403 hanya berisi `message`; `canAccessForumDetailed()` mengembalikan `bool` dan menulis log ke `Log::debug()`. |
| Konfigurasi & middleware | `isAdminOnline()` membaca `env()` saat runtime; ada dua middleware role (`RoleMiddleware` dan `EnsureUserHasRole`); `AiAssistantController` masih menyimpan method `exec()` lama. | Nilai dibaca lewat `config('app.admin_online_minutes', 5)`; middleware role disatukan ke `RoleMiddleware`; method `exec()` lama dihapus. |

## Rincian 13 Temuan yang Diselesaikan

### Temuan Kritis

#### C-01: API Key di `.env.example` (Commit `233af30`)
Sebelumnya, baris 71 di `.env.example` menyimpan nilai `ROUTE_API_KEY=sk-b0d39d13c3b5948b-tgr1rk-d4646932` secara terbuka. Raditya menggantinya dengan nilai placeholder `ROUTE_API_KEY=your-ai-router-key-here` dan memastikan `.env` serta `.env.production` tetap tercantum di `.gitignore` tanpa status tracked di Git.

#### C-02: Endpoint API Publik Tanpa Autentikasi (Commit `7b819b7`)
Sebelumnya, `routes/api.php` memiliki tiga rute terbuka (`/users`, `/students`, dan `/teachers`) di luar middleware autentikasi, disertai spasi kosong sebelum tag pembuka PHP. Karena ketiganya hanya berisi respons status dummy dan tidak dipanggil bagian aplikasi mana pun, Raditya menghapus ketiga rute tersebut beserta whitespace awal file.

#### C-03: Validasi Lampiran Tiket Bantuan (Commit `7a0762e`)
Sebelumnya, `HelpCenterController::storeTicket()` hanya memakai aturan `['nullable', 'file', 'max:5120']`, sehingga file PHP atau SVG bisa tersimpan ke `storage/app/public/help-tickets`. Raditya menggantinya dengan `File::types(['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'txt'])->extensions(['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx', 'txt'])->max(5120)` untuk memeriksa isi MIME aktual sekaligus ekstensi file.

### Temuan Medium

#### M-01: Kebocoran Data Role di Respons 403 (Commit `7cd5e1f`)
Sebelumnya, `RoleMiddleware` mengembalikan field `required`, `current`, dan `user_id` pada body JSON saat menolak akses (HTTP 403). Raditya menyederhanakan respons menjadi `['message' => 'Akses ditolak.']`, sementara rincian role dan user tetap dicatat pada `Log::warning()` di server.

#### M-02: Validasi Kepesertaan Kelas pada Tugas Mahasiswa (Commit `3173683`)
Sebelumnya, `Mahasiswa\PengumpulantugasController::show()` dan `store()` tidak memeriksa apakah mahasiswa terdaftar di kelas milik tugas tersebut. Raditya menambahkan helper `ensureEnrolled(Tugas $tugas, int $mahasiswaId)` yang memverifikasi tabel `kelas_mahasiswa` dan mengembalikan HTTP 403 bagi mahasiswa di luar kelas.

#### M-03: Penghapusan Method Lama Berbasis `exec()` (Commit `9ffd23b`)
Sebelumnya, `Dosen\AiAssistantController` masih menyimpan method `extractTextFromFile()` (yang memanggil `exec("pdftotext ...")`) dan `extractTextFromPdfStream()`, meski fitur aktif sudah memakai `extractDocumentText()` berbasis `Symfony\Process`. Raditya menghapus kedua method lama tersebut untuk memperkecil permukaan serangan dan menghilangkan duplikasi kode.

#### M-04: Pembatasan dan Lifecycle Token QR Absensi (Commit `e29acfb`)
Sebelumnya, rute `mahasiswa.absensi.scan` tidak dibatasi laju permintaannya, `qr_token` dibuat memakai `Str::random(40)`, dan token lama tetap tersimpan setelah sesi ditutup. Raditya menambahkan `throttle:10,1` pada rute scan di `routes/web.php`, mengganti generator token di `Absensi::bukaSession()` menjadi `bin2hex(random_bytes(32))` (64 karakter hex), serta mereset `qr_token` menjadi `null` pada `Absensi::tutupSession()`.

#### M-05: Enkripsi Payload Sesi di `.env.example` (Commit `c527e58`)
Sebelumnya, `.env.example` mengatur `SESSION_ENCRYPT=false` sehingga isi sesi di database tersimpan tanpa enkripsi tambahan. Raditya mengubah nilai default tersebut menjadi `SESSION_ENCRYPT=true`.

### Temuan Informasional

#### I-01: Debug Mode Default di `.env.example` (Commit `af0185a`)
Sebelumnya, `.env.example` menyetel `APP_DEBUG=true`, yang berisiko menampilkan stack trace ke pengguna bila lupa diubah saat deploy. Raditya mengubah default template tersebut menjadi `APP_DEBUG=false`.

#### I-02: Pemisahan Debug Info pada Kontrol Akses Forum (Commit `f861ac3`)
Sebelumnya, `User::canAccessForumDetailed()` mengembalikan array berisi `debug_info` (daftar kelas ajar, daftar kelas yang diikuti, dan role user). Raditya mengubah method tersebut agar mendelegasikan pemeriksaan ke `canAccessForum()`, mencatat informasi diagnostik ke `Log::debug()`, dan hanya mengembalikan nilai `bool`.

#### I-03: Rate Limiting Pengiriman Tiket Tamu (Commit `44de39e`)
Sebelumnya, `POST /help-center/ticket` dapat dipanggil tanpa batas oleh tamu tanpa login. Raditya menambahkan middleware `throttle:5,1` pada rute `help-center.store-ticket` di `routes/web.php`.

#### I-04: Pembacaan `ADMIN_ONLINE_MINUTES` via Config (Commit `623652d`)
Sebelumnya, `HelpCenterController::isAdminOnline()` memanggil `env('ADMIN_ONLINE_MINUTES', 5)` langsung saat runtime, yang akan bernilai `null` ketika `php artisan config:cache` aktif. Raditya mendaftarkan opsi `admin_online_minutes` di `config/app.php` dan mengubah `isAdminOnline()` agar memanggil `config('app.admin_online_minutes', 5)`.

#### I-05: Konsolidasi Middleware Role (Commit `98c4415`)
Sebelumnya, `bootstrap/app.php` mendaftarkan dua alias middleware role (`role` untuk `RoleMiddleware` dan `ensure.role` untuk `EnsureUserHasRole`). Karena seluruh rute memakai `role` dan `EnsureUserHasRole` bergantung pada `app/Services/RoleService.php` yang memiliki error sintaks serta tidak dipakai, Raditya menghapus alias `ensure.role`, file `EnsureUserHasRole.php`, dan `RoleService.php`.

Selain 13 perbaikan di atas, Raditya juga memperbaiki kepatuhan PSR-4 pada `MateriController.php` dan penamaan file `HelpTicket.php` (commit `70aee6a`) agar seluruh kelas termuat oleh Composer autoload.

## Hasil Verifikasi Akhir

Pengujian ulang dilakukan pada 25 November 2026 dengan kondisi `php artisan config:cache` aktif dan database MySQL `web_cendekia` (perubahan data uji di-rollback dalam transaksi):

1. **Linting sintaks PHP:** 147 file PHP di `app/`, `routes/`, dan `config/` diperiksa menggunakan `php -l` dengan hasil 0 error.
2. **Resolusi rute:** `php artisan route:list --except-vendor` memuat 270 rute tanpa kegagalan controller atau middleware.
3. **Verifikasi 13 temuan:**
   - Rute `/api/users`, `/api/students`, dan `/api/teachers` mengembalikan HTTP 404.
   - Unggahan `shell.php`, `xss.svg`, `shell.jpg` berisi kode PHP, dan PDF 6 MB ditolak dengan HTTP 422, sedangkan file `.pdf` dan `.txt` valid lolos validasi.
   - `RoleMiddleware` mengembalikan HTTP 403 dengan satu key `message`.
   - Mahasiswa di luar kelas menerima HTTP 403 saat memanggil `PengumpulantugasController::show()` maupun `store()`, sedangkan mahasiswa terdaftar dapat membuka `show()`.
   - `AiAssistantController` tidak lagi memiliki `extractTextFromFile` dan `extractTextFromPdfStream`.
   - Scan QR ke-11 dalam 1 menit mengembalikan HTTP 429; token QR baru sepanjang 64 karakter hex berubah menjadi `null` setelah sesi ditutup.
   - Permintaan tiket bantuan ke-6 dalam 1 menit mengembalikan HTTP 429.
   - `User::canAccessForumDetailed()` mengembalikan tipe `boolean`, `config('app.admin_online_minutes')` terbaca `5` saat cache konfigurasi aktif, dan hanya alias `role` yang terdaftar di router HTTP.
4. **Smoke test portal:** Halaman `/`, `/login`, `/help-center/admin-status`, dashboard `admin`, dashboard `dosen`, dan dashboard `mahasiswa` mengembalikan HTTP 200; akses mahasiswa ke `/admin/dashboard` ditolak dengan HTTP 403.

## Catatan Temuan Tambahan di Luar Lingkup Audit

Selama smoke test akhir, muncul dua kondisi di luar 13 poin audit yang perlu diperhatikan sebelum rilis produksi:

1. **Halaman guest `/help-center`:** Mengembalikan HTTP 500 ketika dibuka tanpa login karena `resources/views/layouts/portal.blade.php` baris 410 memanggil `auth()->user()->hasRole('dosen')` tanpa memeriksa apakah pengguna sudah login.
2. **Paket `laravel/sanctum` belum terpasang:** Di `composer.json` dan `config/auth.php`, guard `sanctum` belum tersedia, sehingga endpoint mobile API pada `routes/api.php` yang memakai `auth:sanctum` (`api/user`, `api/logout`, `api/mahasiswa/dashboard`, `api/dosen/dashboard`) akan menghasilkan HTTP 500 jika dipanggil sebelum Sanctum dipasang.

## Kesimpulan

Seluruh 13 temuan keamanan backend pada audit awal telah ditutup di kode sumber dan terverifikasi lewat uji regresi serta linting penuh. Celah paling berbahaya pada template konfigurasi, upload lampiran tiket, rute API dummy, IDOR tugas mahasiswa, kebocoran informasi di respons 403, dan token/rate limit QR absensi sudah memiliki proteksi aktif di controller, model, dan middleware.

Untuk menyelesaikan mitigasi `C-01` secara tuntas sebelum server produksi dibuka ke publik, pemilik repositori masih perlu melakukan satu langkah manual di luar kode: mencabut dan mengganti API key `sk-b0d39d13c3b5948b-tgr1rk-d4646932` pada dashboard layanan 9Router, serta membersihkan riwayat commit Git apabila repositori pernah diakses secara publik.
