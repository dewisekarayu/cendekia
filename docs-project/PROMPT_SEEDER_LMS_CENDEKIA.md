# PROMPT LENGKAP SEEDER DATABASE LMS CENDEKIA (1 SEMESTER COMPACT & REALISTIS)

File ini berisi **Prompt Ultra-Lengkap & Terstruktur** yang siap Anda copy-paste dan berikan langsung ke teman Anda atau di-input ke AI (ChatGPT / Claude / Cursor / Copilot).

---

## 📋 PROMPT LENGKAP (TINGGAL COPY SEMUA KOTAK DI BAWAH INI)

```text
Buatkan sistem dummy data seeder yang komprehensif, konsisten, dan saling terhubung (valid relational database) untuk aplikasi Learning Management System (LMS) Cendekia berbasis Laravel.

Semua data dibuat fokus untuk 1 SEMESTER AKTIF (Ganjil 2026/2027: September 2026 s/d Januari 2027), tidak berlebihan agar eksekusi cepat dan ringan, namun seluruh tabel, field, dan relasi terisi lengkap tanpa nilai null/kosong. Gunakan mekanisme idempotent (updateOrCreate/firstOrCreate) agar aman dijalankan berulang kali tanpa duplicate error.

======================================================================
1. FAKULTAS (Tepat 2 Fakultas)
======================================================================
Buat tepat 2 data pada tabel `fakultas`:
1. Kode: 'FK' | Nama: 'Fakultas Kedokteran'
2. Kode: 'FT' | Nama: 'Fakultas Teknik'
Keduanya terhubung ke ID semester aktif.

======================================================================
2. PROGRAM STUDI (Tepat 10 Program Studi, 5 per Fakultas)
======================================================================
Buat tepat 10 prodi pada tabel `program_studi` lengkap dengan akreditasi ('A') dan status (1):
- Fakultas Kedokteran (FK):
  1. Kode: 'PD'  | Nama: 'Pendidikan Dokter' | Jenjang: 'S1' | fakultas_id: FK
  2. Kode: 'KG'  | Nama: 'Kedokteran Gigi'   | Jenjang: 'S1' | fakultas_id: FK
  3. Kode: 'FAR' | Nama: 'Farmasi'           | Jenjang: 'S1' | fakultas_id: FK
  4. Kode: 'KEP' | Nama: 'Keperawatan'       | Jenjang: 'S1' | fakultas_id: FK
  5. Kode: 'KEB' | Nama: 'Kebidanan'         | Jenjang: 'S1' | fakultas_id: FK
- Fakultas Teknik (FT):
  6. Kode: 'TI'  | Nama: 'Teknik Informatika'| Jenjang: 'S1' | fakultas_id: FT
  7. Kode: 'TS'  | Nama: 'Teknik Sipil'      | Jenjang: 'S1' | fakultas_id: FT
  8. Kode: 'TE'  | Nama: 'Teknik Elektro'    | Jenjang: 'S1' | fakultas_id: FT
  9. Kode: 'TIN' | Nama: 'Teknik Industri'   | Jenjang: 'S1' | fakultas_id: FT
  10. Kode: 'SI' | Nama: 'Sistem Informasi'  | Jenjang: 'S1' | fakultas_id: FT

======================================================================
3. MATA KULIAH (6 Mata Kuliah per Prodi = Total 60 Mata Kuliah)
======================================================================
Buat 4 mata kuliah inti yang sesuai dengan bidang masing-masing prodi dan 2 mata kuliah umum (Pendidikan Pancasila dan Bahasa Inggris Akademik) pada tabel `mata_kuliah`.
Semua mata kuliah memiliki semester kurikulum, kode unik, jumlah SKS, dan deskripsi. Total beban semester harus wajar (sekitar 16-20 SKS) dan setiap prodi memiliki 6 mata kuliah aktif.
- PD : PD-101 Anatomi dan Fisiologi Manusia (4), PD-102 Histologi dan Biologi Sel (3), PD-103 Biokimia Kedokteran (3), PD-104 Keterampilan Klinis Dasar (2)
- KG : KG-101 Anatomi Gigi dan Mulut (4), KG-102 Biomaterial Kedokteran Gigi (3), KG-103 Histologi dan Embriologi Oral (3), KG-104 Ilmu Kedokteran Gigi Pencegahan (2)
- FAR: FAR-101 Farmakologi Dasar (4), FAR-102 Kimia Farmasi (3), FAR-103 Farmasetika Dasar (3), FAR-104 Farmakognosi (2)
- KEP: KEP-101 Fundamental Keperawatan (4), KEP-102 Komunikasi Terapeutik Keperawatan (3), KEP-103 Anatomi dan Fisiologi (3), KEP-104 Kebutuhan Dasar Manusia (2)
- KEB: KEB-101 Konsep Kebidanan dan Etikolegal (4), KEB-102 Asuhan Kebidanan Masa Nifas (3), KEB-103 Anatomi dan Fisiologi Reproduksi (3), KEB-104 Kesehatan Ibu dan Anak (2)
- TI : IF-201 Algoritma dan Pemrograman (4), IF-202 Basis Data (3), IF-203 Matematika Diskrit (3), IF-204 Sistem Operasi (3)
- TS : TS-101 Mekanika Rekayasa (4), TS-102 Bahan Konstruksi Bangunan (3), TS-103 Matematika Rekayasa (3), TS-104 Gambar Teknik dan CAD (3)
- TE : TE-101 Rangkaian Listrik (4), TE-102 Elektronika Dasar (3), TE-103 Matematika Teknik Elektro (3), TE-104 Pengukuran dan Instrumentasi (3)
- TIN: TIN-101 Pengantar Teknik Industri (3), TIN-102 Analisis & Perancangan Kerja (4), TIN-103 Statistika Industri (3), TIN-104 Ergonomi Industri (3)
- SI : SI-201 Analisis dan Desain Sistem Informasi (4), SI-202 Manajemen Basis Data Enterprise (3), SI-203 Proses Bisnis dan Sistem Informasi (3), SI-204 Pemrograman untuk Sistem Informasi (3)
- Tambahkan MKU berkode unik per prodi: MKU1-{KODE_PRODI} (Pendidikan Pancasila, 2 SKS) dan MKU2-{KODE_PRODI} (Bahasa Inggris Akademik, 2 SKS).

======================================================================
4. KELAS PERKULIAHAN & JADWAL (Tepat 120 Kelas, 12 per Prodi)
======================================================================
Buat tepat 12 kelas per prodi (2 seksi paralel untuk setiap 6 mata kuliah), total 120 data pada tabel `kelas_perkuliahan`.
- Setiap mata kuliah memiliki dua seksi yang masing-masing terhubung ke mata kuliah, prodi, semester aktif, dan dosen pengampu yang sesuai.
- Kode kelas per prodi berurutan dari `{PREFIX}-1` sampai `{PREFIX}-12` (TI menggunakan prefix `IF`).
- Atribut Kelas: kuota_mahasiswa: 30, status_kelas: 'aktif', is_active: true, bobot_tugas: 30, bobot_uts: 30, bobot_uas: 40.
- Tabel relasi `kelas_jadwals`: Setiap kelas memiliki dua jadwal reguler per minggu pada hari Senin-Jumat, jam mulai-selesai, dan ruang yang jelas. Jadwal dosen dan ruang tidak boleh bentrok.

======================================================================
5. AKUN USER & ROLES (Admin, Dosen, Mahasiswa Lengkap)
======================================================================
Semua akun user wajib mengisi field:
[nip_nim, name, email, password, program_studi_id, status: 'aktif', telepon, theme: 'light', language: 'id', email_verified_at].

A. 1 AKUN ADMIN
   - NIP/NIM: 'ADM0001'
   - Nama: 'Admin Cendekia'
   - Email: 'admin@cendekia.ac.id'
   - Password: Hash::make('admin123')
   - Role: 'admin'

B. 20 AKUN DOSEN (2 Dosen per Program Studi)
   - NIDN: Format '1980000001' s/d '1980000020'
   - Nama: Dosen dengan gelar akademik kredibel (Prof./Dr./dr., Sp.A / M.T)
   - Email: [slug]@dosen.cendekia.ac.id
   - Password: Hash::make('password123')
   - Role: 'dosen'
      - Relasi: Menjadi dosen pengampu beberapa mata kuliah dan kelas pada prodi bersangkutan serta dosen wali. Setiap dosen mengampu beberapa kelas, bukan hanya satu.

   C. 300 AKUN MAHASISWA (30 Mahasiswa per Prodi, Mengambil 6 Mata Kuliah)
   - Setiap kelas berisi TEPAT 15 mahasiswa.
   - Setiap seksi berisi tepat 15 mahasiswa dan kuotanya 30.
   - Setiap mahasiswa terdaftar pada keenam mata kuliah prodinya (enam kelas, satu seksi untuk setiap mata kuliah).
   - Total keseluruhan mahasiswa unik di sistem: 300 orang; total pendaftaran kelas: 1.800.
   - NIM urut: '20240001' s/d '20240300'.
   - Nama: Variasi nama Indonesia wajar (kombinasi nama depan dan belakang yang realistis).
   - Email: format resmi [nama.nim]@student.cendekia.ac.id.
   - Password: Hash::make('password123').
   - Role: 'mahasiswa'.
   - WAJIB menyertakan 3 akun email khusus berikut di kelas PD-1 (Pendidikan Dokter):
     1. NIM: 20240001 | Nama: 'Dewi Sekar Ayu'   | Email: 'dewisekarayu56@gmail.com' | Telepon: '081234567801'
     2. NIM: 20240002 | Nama: 'Maylusi Rahmawati'| Email: 'maylusi431@gmail.com'    | Telepon: '081234567802'
     3. NIM: 20240003 | Nama: 'Marfa Triasa'     | Email: 'marfatriasa@gmail.com'   | Telepon: '081234567803'
   - Relasi Kelas (`kelas_mahasiswa`): Setiap mahasiswa terhubung ke tepat 6 kelas dari prodi dan semester yang sama, mencakup enam mata kuliah berbeda.

======================================================================
6. DATA AKADEMIK 1 SEMESTER (Lengkap, Proporsional & Tidak Berat)
======================================================================
Untuk setiap kelas dari 120 kelas, isi aktivitas pembelajaran selama 1 semester aktif:

1. MATERI PERKULIAHAN (`materi`):
   - Buat materi untuk pertemuan 1 s/d 16 (16 topik perkuliahan).
   - Tipe file: PDF, PPT, Video.
   - File path terstruktur: 'storage/materi/{kode_mk}/p{pertemuan}.{ext}'.
   - Dosen pembuat/pengampu terhubung via pivot `dosen_materi`.

2. ABSENSI PERKULIAHAN (`absensi` & `absensi_mahasiswa`):
   - Buat 16 sesi absensi per kelas (pertemuan 1 s/d 16).
   - Lengkapi dengan tanggal pertemuan, rangkuman, dan berita acara.
   - Tabel `absensi_mahasiswa`: Catat kehadiran seluruh 15 mahasiswa per pertemuan dengan proporsi realistis: 80% hadir, 10% izin/sakit, 10% alpha.

3. TUGAS KULIAH (`tugas` & `pengumpulan_tugas`):
   - Buat tugas representatif dalam 1 semester (kuis, praktik, studi kasus, proyek, dan review ujian).
   - Lengkapi instruksi, bobot nilai (15%-30%), dan deadline pengumpulan.
   - Mahasiswa mengumpulkan tugas (`pengumpulan_tugas`) dengan nilai (rentang 65-98), catatan/feedback dosen, waktu kumpul, dan status 'dinilai'.

4. NILAI AKHIR SEMESTER (`nilai_akhir`):
   - Setiap pendaftaran mahasiswa pada kelas memiliki rekap nilai akhir:
     - nilai_kehadiran (dihitung otomatis dari riwayat absensi)
     - nilai_tugas (rata-rata tugas)
     - nilai_quiz
     - nilai_project
     - nilai_uts (rentang 70-95)
     - nilai_uas (rentang 70-95)
     - nilai_akhir (pembobotan: 10% kehadiran + 20% tugas + 10% quiz + 20% project + 20% UTS + 20% UAS)
     - grade: 'A', 'B', 'C' sesuai skala nilai standar kampus.

5. FORUM DISKUSI KELAS (`forum_diskusi` & `komentar_diskusi`):
   - Buat 3 topik forum per kelas (Orientasi Perkuliahan, Pembahasan Praktikum, Diskusi Project).
   - Lengkapi komentar interaktif bolak-balik antara dosen dan mahasiswa kelas.

6. JADWAL UJIAN (`jadwal_ujian`):
   - Buat jadwal UTS (minggu ke-8) dan UAS (minggu ke-16) per kelas, lengkap dengan ruang dan jam ujian.

======================================================================
7. STRUKTUR SEEDER & URUTAN EKSEKUSI (DatabaseSeeder)
======================================================================
Susun seeder dengan urutan pemanggilan yang rapi dan logis:
1. RoleSeeder (admin, dosen, mahasiswa)
2. SemesterSeeder (Ganjil & Genap)
3. ProgramStudiSeeder (2 Fakultas, 10 Prodi)
4. EnhancedMataKuliahSeeder (60 Mata Kuliah)
5. EnhancedUserSeeder (Admin, 20 Dosen, 300 Mahasiswa)
6. EnhancedKelasPerkuliahanSeeder (120 Kelas & Jadwal)
7. ComprehensiveAcademicSeeder (Materi, Absensi, Tugas, Nilai, Forum, Ujian 1 Semester)
8. KalenderAkademikSeeder (Kegiatan kalender akademik)

Pastikan setelah menjalankan `php artisan db:seed`, query verifikasi menghasilkan:
- Fakultas: 2
- Program Studi: 10
- Mata Kuliah: 60 (6 per Program Studi)
- Kelas: 120 (12 per Program Studi; 2 seksi per mata kuliah)
- Mahasiswa: 300 unik (masing-masing mengambil 6 mata kuliah; 1.800 pendaftaran kelas)
- Dosen: 20
- Seluruh akun login bisa digunakan tanpa error integrity constraint.
```

---

## 🔍 HASIL RINGKASAN DATA DATABASE CENDEKIA

| Komponen | Jumlah | Keterangan |
|---|---|---|
| **Fakultas** | **2** | Kedokteran (FK), Teknik (FT) |
| **Program Studi** | **10** | 5 FK (PD, KG, FAR, KEP, KEB), 5 FT (TI, TS, TE, TIN, SI) |
| **Mata Kuliah** | **60** | 6 Mata Kuliah per Program Studi (4 inti + 2 umum) |
| **Kelas Perkuliahan** | **120** | 2 seksi untuk setiap mata kuliah; 12 kelas per Prodi |
| **Mahasiswa** | **300** | 30 mahasiswa per Prodi; tiap mahasiswa mengambil 6 mata kuliah |
| **Pendaftaran Kelas** | **1.800** | 15 mahasiswa di setiap seksi, terdaftar pada 6 mata kuliah |
| **Dosen** | **20** | 2 Dosen per Prodi; masing-masing mengampu beberapa kelas |
| **Admin** | **1** | NIP `ADM0001` (admin@cendekia.ac.id) |
| **Data Akademik** | **1 Semester** | Materi, absensi, tugas bernilai, nilai akhir A/B/C, forum di setiap kelas |

---

## 🔑 AKSES LOGIN TESTING

- **Admin:** `ADM0001` / `admin123`
- **Mahasiswa Khusus 1:** `dewisekarayu56@gmail.com` (Dewi Sekar Ayu - NIM: 20240001) / `password123`
- **Mahasiswa Khusus 2:** `maylusi431@gmail.com` (Maylusi Rahmawati - NIM: 20240002) / `password123`
- **Mahasiswa Khusus 3:** `marfatriasa@gmail.com` (Marfa Triasa - NIM: 20240003) / `password123`
- **Dosen:** `1980000001` s/d `1980000020` / `password123`
