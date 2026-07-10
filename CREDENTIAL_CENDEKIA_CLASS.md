# 📚 Kelas Cendekia - Informasi Login

## 👨‍🏫 Dosen
**Nama:** Prof Ahmad Subagjo
**Email:** ahmad.subagjo@example.com
**Password:** password123
**Role:** Dosen
**NIP:** NIP123456789

## 👩‍🎓 Mahasiswa

### Mahasiswa 1
**Nama:** Dewi Sekar
**Email:** dewi.sekar@example.com
**Password:** password123
**Role:** Mahasiswa
**NIM:** NIM001

### Mahasiswa 2
**Nama:** May Lusi
**Email:** may.lusi@example.com
**Password:** password123
**Role:** Mahasiswa
**NIM:** NIM002

## 📖 Informasi Kelas

**Nama Mata Kuliah:** Cendekia
**Kode Kelas:** CEN101-A
**Kode Mata Kuliah:** CEN101
**Program Studi:** Teknik Informatika (TIF)
**SKS:** 3
**Semester:** 1 (Ganjil)
**Tahun Akademik:** 2026/2027

**Jadwal Kelas:**
- **Hari:** Senin
- **Jam:** 09:00 - 11:00
- **Ruangan:** Ruang 101
- **Kuota:** 50 mahasiswa
- **Status:** Aktif

## ✅ Fitur yang Tersedia

### Untuk Dosen (Prof Ahmad Subagjo):
1. ✓ Kelola materi perkuliahan
2. ✓ Buat dan kelola tugas
3. ✓ **Buat dan catat absensi mahasiswa**
4. ✓ Input nilai dan gradebook
5. ✓ Forum diskusi
6. ✓ Pengumuman kelas
7. ✓ Lihat jadwal mengajar

### Untuk Mahasiswa (Dewi Sekar & May Lusi):
1. ✓ Lihat jadwal kuliah
2. ✓ Akses materi perkuliahan
3. ✓ Lihat tugas dan deadline
4. ✓ **Lihat riwayat absensi**
5. ✓ Lihat nilai dan gradebook
6. ✓ Berpartisipasi di forum diskusi
7. ✓ Lihat pengumuman kelas

## 🔗 URL Akses

**Login:** http://localhost/cendekia/login

**Dashboard Dosen:** http://localhost/cendekia/dosen/dashboard
**Dashboard Mahasiswa:** http://localhost/cendekia/mahasiswa/dashboard

**Manajemen Absensi (Dosen):** http://localhost/cendekia/dosen/kelas/{kelasId}/absensi
**Riwayat Absensi (Mahasiswa):** http://localhost/cendekia/mahasiswa/absensi

## 📝 Catatan Penting

- Database sudah terintegrasi dengan sistem absensi lengkap
- Data Dewi Sekar dan May Lusi sudah terdaftar di kelas Cendekia
- Prof Ahmad Subagjo adalah dosen pengampu kelas ini
- Semua user sudah aktif dan siap digunakan
- Password default: **password123** (gunakan password yang kuat untuk production)

## 🚀 Cara Menggunakan Fitur Absensi

### Sebagai Dosen:
1. Login dengan akun Prof Ahmad Subagjo
2. Klik "Kelola Absensi" di halaman kelas
3. Klik "Buat Absensi Baru"
4. Isi pertemuan ke berapa dan tanggal
5. Hubungkan dengan materi (opsional)
6. Simpan absensi
7. Sistem otomatis membuat record untuk semua mahasiswa
8. Klik "Presensi" untuk mencatat kehadiran
9. Pilih status untuk setiap mahasiswa (Hadir, Izin, Sakit, Alpha)
10. Simpan kehadiran

### Sebagai Mahasiswa:
1. Login dengan akun Dewi Sekar atau May Lusi
2. Klik menu "Absensi" atau akses /mahasiswa/absensi
3. Lihat daftar kelas dengan persentase kehadiran
4. Klik "Lihat Detail" untuk melihat detail absensi per kelas
5. Lihat riwayat absensi per pertemuan
6. Lihat statistik kehadiran: Hadir, Izin, Sakit, Alpha
