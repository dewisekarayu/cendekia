# BAB III
# RINCIAN PELAKSANAAN

Bab ini berisi inti pembahasan laporan yang mengkaji landasan pendukung di bidang pengembangan perangkat lunak, ruang lingkup bidang kerja atau penempatan bagian PKL, uraian rincian tugas teknis yang dikerjakan secara berkala, manfaat yang diperoleh, hingga pelaksanaan proyek khusus yang mencakup perancangan aplikasi Cendekia (Learning Management System & Sistem Informasi Akademik) serta uraian permasalahan khusus yang dihadapi beserta solusi penyelesaiannya.

---

## 3.1 Landasan Teoritis

Dalam merancang dan membangun aplikasi Cendekia, diperlukan landasan teori dan pemahaman teknologi modern di bidang Rekayasa Perangkat Lunak (Software Engineering), arsitektur web, basis data, serta pola kolaborasi pengembangan perangkat lunak. Landasan teoritis yang mendasari pelaksanaan kegiatan Praktik Kerja Lapangan (PKL) ini meliputi:

### 1. Rekayasa Perangkat Lunak dan Metode Pengembangan
Pengembangan aplikasi Cendekia menerapkan prinsip Rekayasa Perangkat Lunak dengan pendekatan Iterative & Agile Development. Siklus pengembangan melibatkan tahapan:
- Analisis Kebutuhan (Requirements Analysis): Penggalian kebutuhan fungsional bagi tiga entitas pengguna utama (Admin, Dosen, dan Mahasiswa).
- Perancangan (System & Database Design): Pemodelan relasi entitas (Entity Relationship Diagram / ERD), perancangan antarmuka pengguna (UI/UX Wireframing), dan pemetaan rute aplikasi.
- Implementasi (Coding & Development): Penulisan kode program modular menggunakan framework berbasis PHP modern dan styling terstruktur.
- Pengujian (Testing & Quality Assurance): Pengujian fungsionalitas fitur (Black-box testing) serta validasi integritas basis data.

### 2. Arsitektur Model-View-Controller (MVC)
Aplikasi Cendekia dibangun menggunakan pola arsitektur MVC (Model-View-Controller) yang memisahkan logika aplikasi menjadi tiga komponen utama guna memastikan kode program terstruktur, mudah dirawat (maintainable), dan terukur (scalable):
- Model: Merepresentasikan struktur data, aturan bisnis (business logic), dan relasi data dengan bantuan Object-Relational Mapping (ORM) Eloquent.
- View: Menyajikan data dan antarmuka interaktif kepada pengguna akhir menggunakan Blade Templating Engine.
- Controller: Bertindak sebagai pengendali alur logika yang menerima permintaan (HTTP request), memproses data melalui Model, dan mengembalikan respon (HTTP response) ke View.

### 3. Framework Laravel
Framework PHP Laravel (versi 11/12 berbasis PHP 8.3+) dipilih sebagai fondasi utama pengembangan backend. Fitur-fitur Laravel yang dimanfaatkan secara intensif antara lain:
- Eloquent ORM: Mempermudah manipulasi dan relasi data antar tabel (seperti relasi One to Many, Many to Many antara mahasiswa, kelas, mata kuliah, dosen, dan tugas).
- Routing & Middleware: Pengelompokan rute (Route Groups) dan proteksi akses menggunakan Middleware otentikasi serta otorisasi berbasis peran pengguna (Role-based Access Control).
- Blade Templating Engine: Mendukung konsep template inheritance, komponen UI yang dapat digunakan kembali (reusable components), dan direktif kondisional yang bersih.
- Database Migrations & Seeders: Manajemen skema basis data secara terversi dan penyediaan data uji (dummy data) yang konsisten.

### 4. Database Relasional & Object Relational Mapping (ORM)
Basis data relasional (MySQL / MariaDB / SQLite) digunakan untuk mengelola data akademik yang saling terhubung secara terstruktur. Konsep yang diimplementasikan meliputi:
- Normalisasi Data: Mencegah redundansi dan anomali data (anomali penyisipan, pengubahan, dan penghapusan).
- Integritas Referensial (Foreign Key Constraints): Memastikan relasi data antar tabel (misalnya keterkaitan antara jadwal perkuliahan, kelas, dosen pengampu, dan program studi) tetap konsisten.

### 5. Frontend & Desain Responsif (Tailwind CSS & JavaScript)
Untuk menghadirkan antarmuka pengguna yang modern, ergonomis, dan intuitif:
- Tailwind CSS: Utility-first CSS framework digunakan untuk membangun tata letak yang konsisten, sistem desain (design system) dengan variabel warna yang terdefinisi, serta transisi visual yang halus.
- Responsive Mobile-First Design: Memastikan seluruh tata letak (tabel data, kalender akademik, modul materi, dan form tugas) dapat diakses dengan optimal baik pada layar monitor desktop maupun perangkat ponsel pintar (mobile).
- JavaScript & LocalStorage API: Digunakan untuk manipulasi DOM interaktif (modal, dropdown, filter data dinamis) serta penyimpanan preferensi antarmuka pengguna (seperti status Dark Theme).

### 6. Role-Based Access Control (RBAC) & Autentikasi
Keamanan sistem ditegakkan melalui pembatasan hak akses berbasis peran menggunakan paket Spatie Laravel Permission dan Laravel Breeze:
- Administrator: Hak akses penuh terhadap data master akademik, manajemen pengguna, log aktivitas, konfigurasi sistem, dan penanganan tiket keluhan.
- Dosen: Hak akses perkuliahan yang diampu, unggah materi, pembuatan tugas, penilaian (gradebook), presensi, kalender mengajar, dan asisten kecerdasan buatan (AI Assistant).
- Mahasiswa: Hak akses pengambilan mata kuliah (KRS), presensi mandiri, unduh materi, pengumpulan tugas, forum diskusi kelas, serta pemantauan kalender dan pengumuman.

### 7. Version Control System (Git & GitHub)
Sistem kendali versi Git dan platform kolaboratif GitHub dimanfaatkan untuk memantau riwayat perubahan kode sumber, manajemen cabang (branching), peninjauan kode (pull request), serta resolusi konflik penggabungan kode (merge conflicts) antar anggota tim pengembang.

---

## 3.2 Bidang Kerja atau Bagian Tempat PKL

Dalam pelaksanaan Praktik Kerja Lapangan (PKL) pada proyek pengembangan sistem Cendekia, pembagian bidang kerja dan tanggung jawab teknis masing-masing anggota tim disusun secara terkoordinasi sebagai berikut:

| No | Nama | Bidang Kerja / Bagian Tempat PKL | Uraian Fokus Tanggung Jawab |
|---|---|---|---|
| 1. | Dewi Sekar Ayu | Lead Fullstack Developer & DevOps / Core Logic Integration | Bertanggung jawab atas koordinasi arsitektur sistem secara menyeluruh, integrasi logika inti (core logic), konfigurasi lingkungan pengembangan dan kontainerisasi (Docker & PHP runtime), standarisasi mekanisme unggah berkas (materi dan tugas), optimalisasi responsivitas antarmuka mobile, perbaikan bugs routing/middleware, serta implementasi sistem Help Center & Support Ticket. |
| 2. | Marfa Triasa | Backend Developer & Database Administrator | Bertanggung jawab atas perancangan skema basis data (migrations & seeders), pemodelan relasi antar entitas, implementasi fitur CRUD master data akademik (Mahasiswa, Dosen, Fakultas, Program Studi, Mata Kuliah), optimasi kueri basis data pada modul penjadwalan (KelasJadwal), konfigurasi SEO dasar (sitemap & verifikasi web), serta pemeliharaan integritas basis data. |
| 3. | May Lusi Rahmawati | Frontend Developer & UI/UX Specialist | Bertanggung jawab atas penyusunan dan penyempurnaan antarmuka visual pengguna (UI/UX design), implementasi tema gelap (Dark Theme System) dan konsistensi palet warna per-role, modul ekspor dan impor data (PDF via DomPDF & Excel via Maatwebsite), perancangan antarmuka alur pengumpulan tugas mahasiswa, antarmuka kalender akademik terpadu, serta manajemen aset visual aplikasi. |

---

## 3.3 Tugas Umum

Tugas umum merupakan seluruh rangkaian kegiatan teknis dan non-teknis yang dikerjakan secara rutin dan berkala oleh peserta PKL selama masa pelaksanaan kerja praktik, meliputi:

1. Koordinasi dan Penyelarasan Harian (Daily Standup & Sync)  
   Melakukan evaluasi kemajuan harian, mendiskusikan backlog tugas yang sedang dikerjakan, mengidentifikasi hambatan teknis (blockers), dan membagi alur kerja agar tidak terjadi tumpang tindih dalam pengerjaan kode.

2. Analisis Kebutuhan Sistem & Pemodelan Data  
   Mengkaji alur proses bisnis akademik perguruan tinggi secara berkala, memvalidasi kebutuhan form input/output data, serta memastikan skema relasi tabel basis data tetap relevan dengan kebutuhan fitur yang terus berkembang.

3. Pengkodean dan Pemeliharaan Kode (Coding & Maintenance)  
   Menulis kode program yang bersih (Clean Code) sesuai standar konvensi PSR pada bahasa PHP, mengimplementasikan kontroler, model, komponen blade, serta styling responsif menggunakan Tailwind CSS.

4. Pengujian Fungsionalitas Mandiri (Manual Testing & Debugging)  
   Melakukan pengujian langsung terhadap setiap alur fitur yang baru diselesaikan, menguji skenario sukses maupun skenario gagal (misal: validasi ukuran file, data kosong, duplikasi kode mata kuliah), serta melacak pesan kesalahan melalui log aplikasi (storage/logs/laravel.log).

5. Manajemen Repositori & Sinkronisasi Kode Menggunakan Git  
   Melakukan pulling, pembuatan cabang fitur (feature branch), commit dengan pesan yang deskriptif, peninjauan kode (code review), penggabungan (merge), dan menyelesaikan konflik (conflict resolution) pada repositori kerja bersama di GitHub.

6. Penyusunan Dokumentasi Teknis dan Panduan Pengguna  
   Mencatat perubahan skema konfigurasi, membuat panduan operasional sistem (deployment checklist, peta alur navigasi / navigation flow map, dan ringkasan implementasi), serta memperbarui catatan log sistem.

---

## 3.4 Manfaat yang Diperoleh dari Pelaksanaan Praktik Kerja Lapangan

Pelaksanaan Praktik Kerja Lapangan ini memberikan kontribusi yang sangat signifikan bagi pengembangan kompetensi peserta, baik secara teknis (hard skills) maupun non-teknis (soft skills). Manfaat-manfaat tersebut dirinci sebagai berikut:

1. Meningkatnya kompetensi keahlian yang telah diperoleh di sekolah.  
   Teori pemrograman web, logika algoritma, dan perancangan basis data yang dipelajari di bangku sekolah berhasil diterapkan secara nyata dan mendalam ke dalam kerangka kerja industri skala penuh seperti Laravel, Tailwind CSS, dan Eloquent ORM.

2. Bertambahnya wawasan mengenai dunia kerja khususnya berupa pengalaman kerja secara langsung/nyata dalam rangka menanamkan iklim kerja positif yang berorientasi pada peduli mutu proses dan hasil kerja.  
   Peserta memperoleh pemahaman riil mengenai standar industri pengembangan perangkat lunak, di mana kualitas suatu aplikasi tidak hanya dinilai dari berfungsinya fitur, tetapi juga dari kebersihan struktur kode (clean code), keamanan akses (RBAC), keramahan pengguna (UI/UX usability), dan performa waktu muat aplikasi.

3. Meningkatnya kompetensi serta tertanamnya etos kerja yang tinggi sesuai prosedur Operasional Standar (POS) dan budaya kerja di dunia kerja.  
   Peserta terbiasa bekerja secara disiplin, mematuhi jadwal tenggat waktu (deadline), mengikuti alur version control yang baku, teliti dalam pengujian, serta bertanggung jawab penuh terhadap modul yang didelegasikan.

4. Memperkuat kemampuan produktif sesuai dengan konsentrasi keahlian yang dipelajari.  
   Keahlian praktis peserta dalam membangun arsitektur basis data relasional, mengolah logika backend, menyusun tata letak frontend yang responsif, hingga mengonfigurasi runtime aplikasi semakin matang dan siap diterapkan langsung dalam kebutuhan industri IT.

5. Mengembangkan kemampuan sesuai perkembangan dunia kerja dengan bimbingan/arahan pembimbing industri dan dapat berkontribusi kepada dunia kerja.  
   Peserta mampu mengeksplorasi dan mengadopsi teknologi mutakhir (seperti integrasi AI Assistant pada LMS, arsitektur Dark Theme berbasis prefers-color-scheme, dan pustaka pemrosesan dokumen otomatis) dengan arahan pembimbing industri, menghasilkan produk digital yang aplikatif dan bernilai guna tinggi.

6. Mengembangkan kemandirian belajar dan kemampuan kewirausahaan peserta PKL, dan peningkatan keahlian profesional sebagai bekal untuk meningkatkan taraf hidup dan pengembangan diri secara berkelanjutan.  
   Peserta mengasah ketangguhan mental dalam memecahkan masalah (problem solving) secara mandiri melalui literatur teknis, kemampuan berpikir kritis (critical thinking), serta kesadaran untuk senantiasa memperbarui keahlian secara berkesinambungan (life-long learning).

---

## 3.5 Implementasi Proyek (Tugas Khusus)

### 3.5.1 Deskripsi Proyek: Cendekia (LMS & Academic Information System)
Proyek tugas khusus yang dirancang dan diimplementasikan selama masa Praktik Kerja Lapangan adalah Cendekia, sebuah platform terpadu yang memadukan keunggulan Learning Management System (LMS) interaktif dan Sistem Informasi Akademik (SIAKAD) perguruan tinggi modern.

Aplikasi Cendekia dibangun untuk mengatasi kendala fragmentasi sistem akademik konvensional, di mana pengelolaan data induk kampus, materi kuliah, penugasan, presensi, dan penilaian sering kali terpisah pada platform yang berbeda. Cendekia menghadirkan ekosistem terpadu dengan 3 (tiga) peran pengguna utama:
1. Portal Administrator: Mengelola data struktur akademik (Fakultas, Program Studi, Tahun Akademik, Mata Kuliah), manajemen akun civitas akademika (Mahasiswa dan Dosen), penataan kelas perkuliahan, audit log aktivitas, pengaturan sistem, serta pengelolaan tiket keluhan pengguna melalui Help Center.
2. Portal Dosen: Mengelola kelas perkuliahan yang diampu, membagikan materi perkuliahan dalam berbagai format (PDF, dokumen), membuat dan mengelola penugasan kuliah, menilai hasil kerja mahasiswa melalui buku nilai terpadu (Gradebook), memantau kehadiran (Absensi) kelas dengan status buka/tutup sesi dan unduh rekap, memanfaatkan AI Assistant untuk pembuatan deskripsi instruksi soal/tugas, serta memantau kalender jadwal mengajar.
3. Portal Mahasiswa: Mengakses kelas perkuliahan yang diikuti, mengunduh dan melakukan pratinjau materi ajar secara daring, mengumpulkan tugas secara terstruktur (dengan riwayat pengumpulan dan status penilaian), melakukan presensi sesi kelas (termasuk verifikasi token QR), melihat kartu hasil studi/gradebook, serta mengakses Pusat Bantuan (Help Center).

### 3.5.2 Fitur Unggulan yang Diterapkan
- Sistem Tema Gelap (Dark Theme System) Terpadu: Dukungan tiga mode tampilan (Light, Dark, dan Auto/System preference) dengan palet warna khusus sesuai peran (Dark Blue untuk Admin, Dark Purple untuk Dosen, dan Dark Slate untuk Mahasiswa) yang tersimpan permanen di localStorage.
- Pusat Bantuan & Tiket Bantuan (Help Center & Support Ticket): Modul publik yang memuat daftar tanya jawab (FAQ) interaktif dengan pencarian real-time, panduan penggunaan sistem, serta pengajuan tiket bantuan kendala teknis yang langsung terhubung ke dashboard administrator.
- Modul Impor & Ekspor Data Massal: Fitur pengunduhan template, pratinjau data (preview validation), dan impor CSV/Excel untuk mempermudah pendaftaran ribuan data dosen dan mahasiswa secara instan, serta ekspor berkas rekap data ke format PDF (DomPDF) dan Excel (Maatwebsite).
- Asisten AI Dosen (AI Assistant): Modul cerdas untuk membantu tenaga pengajar menyusun deskripsi materi ajar, menghasilkan instruksi penugasan, dan menyusun draf soal evaluasi secara otomatis.
- Sistem Presensi & Gradebook Terpadu: Pencatatan kehadiran perkuliahan dengan fleksibilitas pembukaan/penutupan sesi dan perhitungan bobot nilai tugas yang tersinkronisasi otomatis dengan rekapitulasi penilaian akhir.

---

### 3.5.3 Permasalahan Khusus yang Dihadapi dan Solusi Penanganannya

Selama proses perancangan, pengembangan, dan integrasi modul pada aplikasi Cendekia, tim pengembang menghadapi serangkaian kendala teknis dan arsitektural yang kompleks. Berikut adalah rincian permasalahan khusus yang dihadapi beserta solusi penanganannya:

#### 1. Permasalahan: Batasan Unggah Berkas Materi/Tugas dan Kerentanan Antarmuka Unggah pada Perangkat Mobile
- Deskripsi Permasalahan:  
  Pada modul materi dan pengumpulan tugas, berkas yang diunggah oleh dosen dan mahasiswa berupa dokumen PDF, presentasi, atau arsip tugas berukuran hingga beberapa puluh megabyte. Muncul dua kendala utama:
  1. Pengguna mengalami galat HTTP 413 (Payload Too Large) saat mengunggah berkas lebih dari 2 MB akibat konfigurasi bawaan lingkungan web server/PHP dan container Docker yang sangat terbatas (upload_max_filesize = 2M dan post_max_size = 8M).
  2. Elemen input berkas standar HTML (<input type="file">) tidak merespons sentuhan (tap) pada peramban seluler (smartphone) karena tertutup oleh lapisan pembungkus (wrapper div) yang memblokir penangkapan event klik.
- Solusi Penanganan:  
  - Pada sisi infrastruktur server, tim melakukan konfigurasi ulang pada berkas Dockerfile dan konfigurasi PHP-FPM dengan menaikkan direktif upload_max_filesize = 64M, post_max_size = 64M, serta meningkatkan memory_limit = 256M.
  - Pada sisi antarmuka Blade, tim merestrukturisasi elemen pembungkus unggahan dari elemen <div> menjadi elemen semantik <label for="file_input"> dengan posisi absolut yang tepat, sehingga area sentuh (touch target) pada layar ponsel pintar menjadi luas dan input file terbuka sempurna saat disentuh.

---

#### 2. Permasalahan: Terjadinya Kilatan Putih (Flash of Unstyled Content / Dark Mode Flash) dan Ketidakkonsistenan Kontras Warna Antar Peran
- Deskripsi Permasalahan:  
  Ketika pengguna memilih mode gelap (Dark Mode), setiap kali halaman dimuat ulang (reload) atau berpindah menu, terjadi efek kedipan putih yang menyilaukan (theme flash) selama sepersekian detik sebelum script CSS Tailwind mengeksekusi kelas dark. Selain itu, kontras teks dan warna latar belakang pada menu navigasi samping (sidebar) sering kali bertabrakan dan tidak memenuhi standar aksesibilitas WCAG, terutama saat pengguna beralih antara peran Admin, Dosen, dan Mahasiswa.
- Solusi Penanganan:  
  - Tim memindahkan logika pengecekan tema dari berkas JavaScript eksternal yang lambat ke dalam blok <script> sebaris (inline script) yang diletakkan di bagian paling atas <head> pada layout utama portal.blade.php. Script ini membaca kunci localStorage.getItem('theme') atau media kueri window.matchMedia('(prefers-color-scheme: dark)') secara sinkron sebelum proses rendering DOM dimulai, sehingga kelas dark langsung diterapkan pada elemen <html> tanpa ada jeda kilatan visual.
  - Tim menyusun arsitektur palet warna modular pada resources/css/app.css dengan menerapkan utility class Tailwind yang spesifik untuk masing-masing peran: #0f172a (Dark Blue) untuk Admin, #1e1b4b (Dark Purple) untuk Dosen, dan #1e293b (Dark Slate) untuk Mahasiswa, dengan teks primer #f8fafc yang memiliki rasio kontras tinggi dan nyaman di mata pengguna.

---

#### 3. Permasalahan: Konflik Penggabungan Repositori (Merge Conflicts) dan Kegagalan Eksekusi Database Seeder Relasional
- Deskripsi Permasalahan:  
  Kolaborasi aktif multi-developer pada repositori GitHub Cendekia sempat menimbulkan insiden merge conflict yang parah pada branch main. Beberapa file controller (seperti MahasiswaController.php) mengalami korupsi kode akibat kesalahan perintah git stash pop yang tertimpa secara tidak sengaja. Di saat bersamaan, perintah migrasi dan pengisian data uji (php artisan migrate:fresh --seed) mengalami kegagalan (foreign key constraint fails) karena urutan eksekusi seeder yang salah: tabel anak (program_studis dan kelas_perkuliahans) dijalankan sebelum tabel induk (semesters dan fakultas) selesai diisi datanya.
- Solusi Penanganan:  
  - Tim memanfaatkan riwayat Git Reflog dan riwayat komit (git log) untuk memulihkan berkas MahasiswaController.php ke versi stabil terakhir yang bersih. Diterapkan aturan alur kerja Git yang ketat: setiap pengembang wajib melakukan git pull --rebase dari branch utama sebelum mengajukan perubahan.
  - Pada struktur basis data, urutan pemanggilan seeder pada DatabaseSeeder.php ditata ulang secara kronologis sesuai hierarki relasi data: dimulai dari data referensi dasar (FakultasSeeder, TahunAkademikSeeder, SemesterSeeder), dilanjutkan dengan data menengah (ProgramStudiSeeder, MataKuliahSeeder), dan diakhiri dengan data transaksi (UserSeeder, DosenSeeder, MahasiswaSeeder, dan KelasJadwalSeeder).

---

#### 4. Permasalahan: Galat Kueri Eloquent Akibat Perubahan Struktur Kolom Jadwal Kuliah dan Pemeriksaan Bentrok Ruangan
- Deskripsi Permasalahan:  
  Pada rancangan awal, data hari perkuliahan disimpan langsung dalam kolom hari pada tabel kelas_perkuliahans. Namun seiring berkembangnya kebutuhan bahwa satu kelas dapat memiliki jadwal lebih dari satu sesi per minggu, kolom hari dihapus dari tabel induk dan dipindahkan ke tabel relasional baru bernama kelas_jadwals. Akibatnya, modul JadwalController mengalami galat pengecualian kueri (SQL Query Exception: Column 'hari' not found) saat menjalankan fitur pencarian ruangan kosong (available rooms) dan penapisan jadwal mengajar dosen.
- Solusi Penanganan:  
  - Melakukan refaktorisasi kueri Eloquent secara menyeluruh pada JadwalController dan model terkait. Kueri langsung ke kolom hari diubah menjadi kueri relasional memanfaatkan metode whereHas('jadwals', function($q) { ... }) yang mengaitkan model KelasPerkuliahan dengan KelasJadwal.
  - Logika verifikasi ruangan yang tersedia (available rooms) diperbaiki agar memvalidasi bentrok waktu berdasarkan gabungan parameter hari, jam_mulai, dan jam_selesai pada relasi jadwal aktif, sehingga informasi ketersediaan ruangan menjadi akurat dan bebas galat.

---

#### 5. Permasalahan: Validasi Modal Form Tambah Data yang Menutup Otomatis dan Kurangnya Umpan Balik Error yang Jelas
- Deskripsi Permasalahan:  
  Pada antarmuka dosen dan admin, form penambahan materi kuliah, tugas baru, dan master data dirancang dalam bentuk jendela sembul (modal popup). Saat pengguna mengisi form dengan data yang tidak valid (misal: format berkas tidak didukung atau kolom wajib yang terlewat), halaman memuat ulang (redirect back), namun modal secara otomatis tertutup. Pengguna menjadi bingung karena tidak mengetahui apakah data berhasil disimpan atau ditolak, serta pesan error validasi bawaan Laravel tidak terlihat karena tersembunyi di dalam modal yang tertutup.
- Solusi Penanganan:  
  - Mengimplementasikan skrip JavaScript otomatis pada komponen modal: skrip mendeteksi keberadaan variabel $errors->any() dari Laravel. Jika ditemukan pesan galat validasi, skrip secara otomatis memicu pembukaan kembali modal yang bersangkutan (auto open modal on validation error).
  - Menghubungkan seluruh galat validasi ke komponen notifikasi mengambang (Toast Notification) berbasis Tailwind CSS yang muncul di sudut layar secara mencolok, menampilkan daftar kesalahan input dengan jelas sehingga pengguna dapat segera memperbaiki data tanpa kehilangan konteks halaman.

---

### 3.5.4 Kesimpulan Pelaksanaan Proyek
Pengerjaan proyek aplikasi Cendekia (LMS & Academic Information System) telah berhasil diselesaikan dengan status 100% fungsional dan siap produksi. Seluruh tantangan teknis mulai dari pengelolaan data relasional kompleks, standarisasi antarmuka multi-role responsif, arsitektur tema modern, hingga penanganan berkas dan validasi sistem berhasil diselesaikan dengan baik melalui kerja sama tim yang solid, penerapan pola desain perangkat lunak yang tepat, dan bimbingan industri yang terarah.
