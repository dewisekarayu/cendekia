# Walkthrough - Implementasi Pengaturan Dosen & Perbaikan Mode Gelap (Dark Mode)

Saya telah berhasil merancang, membuat, dan mengintegrasikan preferensi pengaturan untuk dosen, serta melakukan desain ulang tampilan antarmuka agar selaras, premium, dan tidak clashing (bentrok) di mode gelap.

## Ringkasan Perubahan

### 1. Database & Model
* **Migrasi Database (`2026_07_14_140000_add_theme_and_language_to_users_table.php`)**: Menambahkan kolom `theme` (default `'light'`) dan `language` (default `'id'`) pada tabel `users`.
* **Model User (`app/Models/User.php`)**: Menambahkan `theme` dan `language` ke dalam array `$fillable` agar dapat disimpan secara massal.

### 2. Logika Backend & Routing
* **SettingController (`app/Http/Controllers/Dosen/SettingController.php`)**: Controller baru untuk dosen yang mengelola:
  * Pengambilan data pengaturan dan preferensi notifikasi saat ini (`index`).
  * Penyimpanan pengaturan umum seperti bahasa (`language`) dan tema (`theme`).
  * Penyimpanan preferensi notifikasi (`pengumpulan_tugas`, `pesan_baru`, `pengumuman_baru`).
* **Rute Web (`routes/web.php`)**: Mengganti closure statis untuk `/dosen/setting` dengan controller action dan menambahkan endpoint POST untuk penyimpanan data.
* **LocaleMiddleware (`app/Http/Middleware/LocaleMiddleware.php`)**:
  * Mengambil preferensi bahasa pengguna (atau session locale) dan mengatur bahasa aplikasi secara dinamis pada setiap request.
  * **SISTEM TERJEMAHAN DINAMIS DOSEN**: Menambahkan filter pasca-render (post-rendering translations) pada response HTML untuk rute `dosen*`.
  * **PROSESOR TERJEMAHAN SKEMA REGEX**: Untuk menghindari penggantian kata-kata di dalam URL path (seperti kata `kelas`, `tugas`, atau `absensi` dalam `<a href="...">` atau `<form action="...">`) yang menyebabkan error 404 Not Found, middleware memisahkan tag HTML dengan text nodes menggunakan regex:
    ```php
    $pattern = '/(<[^>]+>)|([^<]+)/';
    ```
    Hanya text nodes di luar tag yang diterjemahkan menggunakan `lang/en_translations.php` (untuk English) atau `lang/id_translations.php` (untuk Indonesian). Ini mencegah perubahan URL secara total dan menjaga navigasi tetap 100% aman dan bekerja.

### 3. Tampilan Antarmuka (UI) & Penyesuaian Mode Gelap (Dark Mode) Premium
* **Tata Letak Portal (`resources/views/layouts/portal.blade.php`)**:
  * Menambahkan script inline di `<head>` untuk membaca preferensi tema (`light`, `dark`, atau `auto` mengikuti sistem) dan mengaplikasikan class `dark` pada elemen root `<html>` secara instan.
  * Menambahkan class Tailwind `dark:bg-slate-900`, `dark:bg-slate-800` pada komponen tata letak utama.
  * Memperbaiki class warna invalid `slate-750` menjadi `slate-700` pada kontainer profil pengguna agar warna terintegrasi secara halus.
  * **SINKRONISASI SIDEBAR MODE GELAP**: Merancang ulang background sidebar untuk mode gelap menggunakan warna yang **sama persis** dengan warna gelap latar belakang utama (`#0f172a` dengan border `#1e293b`), sehingga terlihat menyatu dengan sempurna.
  * **HIGHLIGHT AKTIF SIDEBAR**: Rute menu yang sedang diakses (seperti Dashboard, Profil, Pengaturan) kini memiliki indikator samping violet yang menyala (`border-left: 3px solid #a855f7`) dengan background kontainer ungu tua transparan (`#1e1b4b`) dan teks ungu muda (`#c084fc`) untuk estetika premium.
  * **OVERLAY UNIVERSAL MODE GELAP**: Menambahkan rule CSS helper global pada layout untuk secara otomatis menyesuaikan warna box `.bg-white`, pembatas border, table headers, form inputs, dropdown select, textarea, dan badge-badge berwarna khas agar otomatis menyesuaikan dengan skema warna mode gelap premium secara instan. Termasuk menetralkan warna input saat fokus (`focus:bg-white`) agar tetap gelap dengan outline violet menyala.
* **Halaman Dashboard Dosen (`resources/views/dosen/dashboard.blade.php`)**:
  * Menerapkan class `dark:bg-slate-800`, `dark:border-slate-700`, dan `dark:text-white` pada semua ringkasan statistik (Stats Summary Cards), sehingga tidak ada lagi kotak putih yang mencolok (clashing) ketika mode gelap diaktifkan.
  * Menyelaraskan kartu daftar kelas perkuliahan ("Kelas Mengajar Saya") dengan background gelap yang serasi, mempermudah keterbacaan teks, serta menyesuaikan kontras warna badge kelas.
  * Merapikan tabel "Pengumpulan Tugas Terbaru" dengan border gelap, background header tabel (`dark:bg-slate-900/30`), teks yang jelas, serta mempercantik tombol tindakan "Buka Evaluasi" (`dark:bg-purple-950/40`, `dark:text-purple-300`).
* **Halaman Profil Dosen (`resources/views/dosen/profil.blade.php`)**:
  * Menghapus warna putih polos yang mencolok dan menggantinya dengan penyesuaian mode gelap yang indah pada kartu statistik atas (bergabung, kelas diampu, dll.), kartu foto profil, list pengumuman, serta formulir tab.
  * Menyelaraskan warna tab indicator (`dark:bg-purple-500`) dan pewarnaan tab button saat aktif/tidak aktif.
  * Memperbaiki elemen input form dan border agar nyaman dibaca dan tidak merusak estetika mode gelap.
* **Halaman Forum Diskusi (`resources/views/dosen/forums.blade.php`) & Pengumuman (`resources/views/dosen/kelas-pengumuman.blade.php`)**:
  * Menyelaraskan tab navigasi bar ("Announcements" & "Forums") agar responsive terhadap dark mode menggunakan class tailwind terpadu (`dark:bg-slate-800`, `dark:border-slate-700`).
  * Memperbaiki layout bubble chat di forums (`html.dark .forum-bubble`) agar memiliki background indigo-950 (`#312e81`) untuk pengirim dan slate-700 (`#334155`) untuk penerima dengan kontras teks yang sangat jelas (`#f8fafc`).
  * Memperbaiki style input textarea pesan forum, placeholder, and action buttons.
  * Menyesuaikan form isian, drop-down, checklists, modal box wrapper, dan tombol terbit pengumuman baru untuk dark mode.
* **Halaman Kelas Detail (`kelas-detail.blade.php`), Kelas Materi (`kelas-materi.blade.php` & `materi/buka.blade.php`), & Kelas Presensi (`absensi/...`)**:
  * Menghapus warna terang clashing pada detail materi, breadcrumbs link, daftar file, serta kotak unduhan materi.
  * **SINKRONISASI TAMPILAN PRESENSI**: Menghilangkan pembungkus luar abu-abu ganda (`bg-slate-50/50` dengan border dan padding tebal) pada seluruh halaman presensi (`index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`, `attendance.blade.php`) agar layout menjadi transparan, bersih, serasi, dan sejajar dengan margin halaman portal lainnya.
  * Menyelaraskan tabel rekap absensi pertemuan mahasiswa, kartu statistik absensi (hadir, absen, sakit, izin), tombol status buka/tutup absensi, serta form rekap pertemuan baru agar nyaman dibaca dan tidak merusak estetika mode gelap.
* **Halaman Pengaturan Dosen (`resources/views/dosen/setting.blade.php`)**:
  * Menghilangkan pilihan **Zona Waktu** dan **Ukuran Font**.
  * Menyisakan pilihan **Bahasa** dengan opsi: Bahasa Indonesia (`id`) dan English (`en`).
  * Mengintegrasikan radio button **Tampilan** (Terang, Gelap, Otomatis) dengan menambahkan highlight aktif visual dinamis melalui fungsi JavaScript `highlightThemeCards()`. Pilihan tema yang aktif akan langsung memiliki border ungu tebal dan background ungu transparan yang elegan.
  * Menhubungkan semua form dengan rute backend, menyertakan token `@csrf`, serta memetakan preferensi notifikasi langsung ke kolom database `notification_preferences`.
  * Memperbaiki styling checkbox dan select dropdown untuk dark mode dengan border `slate-700` dan background input `slate-900` untuk kontras yang seimbang.
  * Menambahkan lokalisasi teks (dua bahasa) menggunakan variabel PHP berdasarkan locale saat ini.
  * Menambahkan notifikasi sukses yang elegan menggunakan library `SweetAlert2` yang sudah tersedia di layout.

### 4. Perbaikan Bug Tambahan
* **KelasController (`app/Http/Controllers/Dosen/KelasController.php`)**: Menyelesaikan konflik merge Git yang sebelumnya menyebabkan kesalahan parser PHP/Laravel pada metode `submissions` dan `simpanNilai`.

---

## Verifikasi & Pengujian
1. **Pemeriksaan Rute**: Perintah `php artisan route:list` berjalan sukses dan rute settings telah terdaftar dengan benar.
2. **Kesesuaian Kode**: Menambahkan pemanggilan `NotificationService::notifyNilaiBaru` yang terlewat pada implementasi penilaian agar mahasiswa menerima email saat diberi nilai.
3. **Penyelesaian Git & Push**:
   * Melakukan `git pull` dan menyelesaikan konflik penggabungan pada `kelas-tugas.blade.php`, `kelas-pengumuman.blade.php`, dan `forums.blade.php` (menyesuaikan perubahan letak tab forum ke kelas masing-masing dari maylusirahmawati).
   * Berhasil mempublikasikan/push seluruh commit ke remote branch `origin main` di GitHub.
