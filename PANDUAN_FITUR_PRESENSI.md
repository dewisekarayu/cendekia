# Panduan Fitur Presensi - Sistem Cendekia

## 📋 Daftar Isi
1. [Quick Start](#quick-start)
2. [Untuk Dosen](#untuk-dosen)
3. [Untuk Mahasiswa](#untuk-mahasiswa)
4. [Fitur-Fitur](#fitur-fitur)
5. [FAQ & Troubleshooting](#faq--troubleshooting)
6. [Testing](#testing)

---

## 🚀 Quick Start

### Akses Menu Presensi

**Untuk Dosen:**
- Dashboard → Menu Sidebar → Pilih Kelas → Presensi
- Atau: `/dosen/kelas/{id}/absensi`

**Untuk Mahasiswa:**
- Dashboard → Menu Sidebar → Presensi
- Atau: `/mahasiswa/absensi`

---

## 👨‍🏫 Untuk Dosen

### 1. Membuat Sesi Presensi Baru

**Langkah:**
1. Buka menu **Presensi Kelas**
2. Pilih kelas yang ingin dikelola
3. Klik tombol **"Buat Sesi Baru"** (hijau)
4. Isi form:
   - **Pertemuan Ke**: Nomor urut pertemuan (1-16)
   - **Tanggal**: Tanggal pertemuan
   - **Jam Mulai**: Waktu dimulai (format: HH:MM)
   - **Jam Selesai**: Waktu selesai (format: HH:MM)
   - **Ringkasan Materi** (opsional): Apa yang diajarkan
   - **Berita Acara** (opsional): Catatan penting
   - **Catatan Tambahan** (opsional): Info lainnya
5. Klik **"Buat Sesi Presensi"**

**Status Awal**: Draft (belum aktif)

### 2. Membuka Sesi Presensi

**Kapan**: Buka sesi 5-10 menit sebelum kelas dimulai

**Langkah:**
1. Di halaman list presensi, lihat sesi dengan status "Draft"
2. Klik tombol **"Lihat"** (biru)
3. Halaman detail sesi muncul
4. Di bagian sidebar kanan, klik **"Buka Sesi Presensi"** (hijau)
5. Konfirmasi, sesi berubah status menjadi **"Dibuka"**

**Yang Terjadi:**
- Mahasiswa bisa mulai presensi
- Email notifikasi dikirim ke semua mahasiswa di kelas
- Sesi hanya aktif untuk hari ini

### 3. Melihat Daftar Kehadiran

**Langkah:**
1. Di halaman detail sesi, scroll ke bawah
2. Lihat tabel **"Daftar Kehadiran Mahasiswa"**
3. Tabel menampilkan:
   - Nama mahasiswa
   - NIM
   - Status (Hadir/Izin/Sakit/Alpha)
   - Waktu absensi
   - Keterangan

**Statistik di atas tabel:**
- Total mahasiswa
- Jumlah hadir
- Jumlah izin/sakit
- Jumlah alpha (tidak hadir)

### 4. Edit Kehadiran Manual

**Gunakan Jika:**
- Ada kesalahan input
- Mahasiswa hadir tapi lupa presensi
- Perlu koreksi status

**Langkah:**
1. Di halaman detail sesi
2. Klik tombol **"Edit Kehadiran Manual"** (biru)
3. Halaman edit muncul dengan form
4. Untuk setiap mahasiswa:
   - **Status**: Pilih Hadir/Izin/Sakit/Alpha
   - **Keterangan**: Isi alasan jika izin/sakit (opsional)
5. Klik **"Simpan Perubahan"**

### 5. Menutup Sesi Presensi

**Kapan**: Tutup setelah waktu kelas selesai

**Langkah:**
1. Di halaman detail sesi
2. Klik tombol **"Tutup Sesi Presensi"** (kuning)
3. Status berubah menjadi **"Ditutup"**
4. Mahasiswa tidak bisa lagi presensi

### 6. Edit Sesi

**Gunakan Jika**: Perlu ubah data sesi (hanya saat Draft)

**Langkah:**
1. Di halaman list presensi
2. Klik tombol **"Edit"** (abu-abu) pada sesi draft
3. Form edit muncul
4. Ubah data yang diperlukan
5. Klik **"Simpan Perubahan"**

### 7. Hapus Sesi

⚠️ **Hati-hati**: Menghapus sesi akan menghapus semua data presensi

**Langkah:**
1. Di halaman list presensi
2. Klik tombol **"Hapus"** (merah)
3. Konfirmasi dengan klik OK
4. Sesi dan semua datanya dihapus

### 8. Melihat Statistik

Di halaman list presensi, lihat 4 kartu statistik di atas:
- **Total Sesi**: Jumlah sesi yang sudah dibuat
- **Draft**: Sesi yang belum dibuka
- **Aktif**: Sesi yang sedang dibuka
- **Ditutup**: Sesi yang sudah ditutup

---

## 👨‍🎓 Untuk Mahasiswa

### 1. Melihat Daftar Kelas

**Langkah:**
1. Buka menu **Presensi**
2. Halaman menampilkan kartu setiap kelas
3. Setiap kartu menunjukkan:
   - Nama mata kuliah
   - Kode kelas
   - Nama dosen
   - Status sesi hari ini (jika ada)
   - Statistik kehadiran (hadir/total)
   - Progress bar kehadiran

### 2. Melakukan Presensi

**Persyaratan:**
- Sesi presensi harus dibuka (status "Dibuka")
- Sesi harus untuk hari ini
- Belum pernah presensi untuk sesi ini

**Langkah:**
1. Klik tombol **"Presensi"** (ungu) pada kartu kelas
2. Halaman presensi kelas terbuka
3. Jika ada sesi aktif, form presensi muncul
4. Pilih status kehadiran:
   - **Hadir**: Anda hadir (default)
   - **Izin**: Anda izin (harus isi alasan)
   - **Sakit**: Anda sakit (harus isi alasan)
5. Jika pilih Izin/Sakit, isi kolom keterangan
6. Klik tombol **"PRESENSI / ABSEN MASUK"**
7. ✅ Presensi berhasil dicatat

**Setelah Presensi:**
- Halaman menampilkan konfirmasi
- Status presensi yang baru tercatat
- Waktu presensi

### 3. Melihat Riwayat Presensi

**Langkah:**
1. Di halaman presensi kelas
2. Klik tombol **"Riwayat"** (biru) di kanan atas
3. Halaman riwayat terbuka dengan:
   - **Statistik**: Total pertemuan, hadir, izin, sakit, alpha
   - **Progress Bar**: Visualisasi kehadiran
   - **Tabel Detail**: Riwayat per pertemuan
   - **Persentase**: Tingkat kehadiran Anda

**Informasi di Tabel:**
- Pertemuan ke
- Tanggal
- Waktu
- Status
- Waktu absensi

### 4. Tips Presensi

✅ **DO:**
- Presensi saat sesi dibuka
- Isi keterangan jika izin/sakit
- Monitor riwayat kehadiran Anda
- Hubungi dosen jika ada masalah

❌ **DON'T:**
- Jangan presensi 2x untuk sesi yang sama (tidak bisa)
- Jangan presensi saat sesi sudah ditutup
- Jangan submit tanpa pilih status

---

## ✨ Fitur-Fitur

### Status Kehadiran

| Status | Warna | Keterangan |
|--------|-------|-----------|
| **Hadir** | 🟢 Hijau | Mahasiswa hadir |
| **Izin** | 🔵 Biru | Tidak hadir karena izin (ada alasan) |
| **Sakit** | 🟡 Kuning | Tidak hadir karena sakit (ada bukti?) |
| **Alpha** | 🔴 Merah | Tidak hadir tanpa keterangan |

### Status Sesi

| Status | Warna | Arti |
|--------|-------|------|
| **Draft** | 🟠 Oranye | Dibuat tapi belum dibuka |
| **Dibuka** | 🟢 Hijau | Aktif, mahasiswa bisa presensi |
| **Ditutup** | 🔴 Merah | Selesai, presensi tidak bisa dilakukan |

### Validasi & Keamanan

✅ Hanya dosen pengampu yang bisa manage sesi
✅ Hanya mahasiswa terdaftar yang bisa presensi
✅ Unique constraint: 1 mahasiswa = 1 presensi per sesi
✅ Lock untuk prevent double submit
✅ Email notification saat sesi dibuka
✅ Audit trail (created_at, updated_at)

---

## ❓ FAQ & Troubleshooting

### Q: Saya (dosen) tidak bisa membuka sesi presensi
**A**: Pastikan:
- Sesi status "Draft"
- Tanggal sesi belum lewat
- Anda adalah dosen pengampu kelas

### Q: Saya (mahasiswa) tidak bisa presensi
**A**: Pastikan:
- Anda terdaftar di kelas tersebut
- Sesi status "Dibuka" (lihat indikator hijau)
- Sesi untuk hari ini
- Anda belum pernah presensi sesi ini
- Jika pilih Izin/Sakit, isi kolom keterangan

### Q: Cara ubah status presensi yang sudah tercatat?
**A**:
- Mahasiswa tidak bisa mengubah sendiri (tidak ada tombol edit)
- Hubungi dosen untuk koreksi
- Dosen bisa edit di halaman "Edit Kehadiran Manual"

### Q: Berapa persentase kehadiran yang diperlukan?
**A**: Tergantung kebijakan institusi (biasanya 80%). 
- Lihat di riwayat presensi Anda
- Hubungi dosen/akademik untuk info resmi

### Q: Apa itu "Alpha"?
**A**: Alpha = Tidak hadir tanpa keterangan. 
- Status ini otomatis untuk mahasiswa yang belum presensi saat sesi ditutup

### Q: Bisa export presensi ke Excel/PDF?
**A**: Fitur export sedang dalam tahap development. Coming soon!

### Q: Email notifikasi tidak terima?
**A**: Periksa:
- Folder Spam
- Email setting di Notification Preferences
- Hubungi admin sistem

---

## 🧪 Testing

### Jalankan Test Sistem

```bash
php artisan test:absensi
```

**Output Test:**
- Create session (Draft)
- Open session
- Student check-in (Hadir)
- Attendance statistics
- Multiple sessions
- Close session
- Authorization checks

### Clean Test Data

```bash
php artisan test:absensi --clean
```

---

## 📞 Support & Bantuan

**Untuk Dosen:**
- Hubungi: Admin Sistem / Tim IT
- Email: admin@cendekia.local
- Dokumentasi: `/SISTEM_PRESENSI.md`

**Untuk Mahasiswa:**
- Hubungi Dosen masing-masing kelas
- Hubungi: Bagian Akademik
- Hubungi: Admin Sistem (jika teknis)

---

## 📝 Changelog

### Version 1.0 (July 14, 2026)
- ✅ Session management (Draft → Buka → Tutup)
- ✅ Student check-in dengan status (Hadir/Izin/Sakit/Alpha)
- ✅ Manual edit kehadiran oleh dosen
- ✅ Attendance statistics & reporting
- ✅ Email notifications
- ✅ Authorization policies
- ✅ Modern UI with Tailwind CSS
- ✅ Mobile responsive
- ✅ Helper functions & utilities
- ✅ Console commands untuk testing

---

**Last Updated**: July 14, 2026  
**Status**: Production Ready ✅
