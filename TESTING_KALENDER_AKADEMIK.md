# Testing End-to-End: Fitur Manajemen Kalender Akademik

**Target:** Verifikasi bahwa sistem Kalender Akademik berfungsi dengan sempurna - admin dapat membuat agenda yang langsung terlihat di mahasiswa tanpa perubahan kode atau data manual.

---

## Prerequisites (Persiapan)

- [x] Database migration sudah dijalankan (`kalender_akademik` table tersedia)
- [x] Semester minimal 1 yang aktif sudah dibuat di database
- [x] Admin user dengan role 'admin' sudah terdaftar
- [x] Mahasiswa user dengan role 'mahasiswa' sudah terdaftar dan terdaftar di kelas
- [x] Laravel app sudah di-setup dan dev server berjalan

---

## Testing Checklist

### Phase 1: Akses & Navigasi

#### 1.1. Admin Dapat Akses Kalender Admin
- [ ] Login sebagai admin
- [ ] Navigasi ke `/admin/kalender-akademik` atau klik menu "Kalender Akademik"
- [ ] Halaman muncul dengan layout modern, kalender grid, filter kategori
- [ ] Sidebar menunjukkan "Agenda Hari Ini", "Agenda Mendatang", dan "Ringkasan"
- **Expected Result:** Halaman admin kalender muncul dengan sempurna, tidak ada error di console

#### 1.2. Mahasiswa Dapat Akses Kalender Mahasiswa  
- [ ] Login sebagai mahasiswa
- [ ] Navigasi ke `/mahasiswa/kalender-akademik` atau klik menu "Kalender"
- [ ] Halaman muncul dengan kalender grid, filter kategori, sidebar
- [ ] Tidak ada error di console
- **Expected Result:** Halaman mahasiswa kalender muncul tanpa error

#### 1.3. Routes Sudah Terdaftar
- [ ] Admin routes ada: `admin.kalender-akademik.*` (index, create, store, show, edit, update, destroy)
- [ ] Mahasiswa routes ada: `mahasiswa.kalender-akademik.index`
- **Expected Result:** Semua routes bekerja dan tidak 404

---

### Phase 2: CRUD Admin (Create, Read, Update, Delete)

#### 2.1. Create Agenda (Buat Agenda Baru)
- [ ] Klik tombol "Tambah Agenda" di halaman admin
- [ ] Form muncul dengan 4 sections: Informasi Dasar, Tanggal & Waktu, Kategori & Lokasi, Warna & Status
- [ ] Isi form dengan data:
  - Semester: pilih semester aktif
  - Judul: "Test UTS - Testing Kalender"
  - Deskripsi: "Ini adalah testing agenda untuk verifikasi sistem"
  - Tanggal Mulai: pilih tanggal besok
  - Tanggal Selesai: kosongkan (hanya 1 hari)
  - Jenis Kegiatan: "UTS"
  - Lokasi: "Ruang 301"
  - Warna: pilih Merah (testing ujian)
  - Publikasikan: checked (ON)
  - Waktu: unchecked "Sepanjang Hari", set waktu 08:00 - 10:00
- [ ] Klik "Buat Agenda"
- [ ] Redirect ke halaman index, muncul notifikasi sukses "Agenda berhasil dibuat"
- **Expected Result:** Agenda baru tersimpan di database, user kembali ke index dengan pesan sukses

#### 2.2. Read Agenda (Lihat Detail)
- [ ] Agenda yang baru dibuat muncul di kalender grid admin (sesuai tanggal)
- [ ] Badge warna merah muncul dengan judul "Test UTS - Testing Kalender"
- [ ] Klik agenda di kalender grid, modal muncul dengan detail lengkap
- [ ] Klik badge atau event lain untuk membuka modal detail (show page)
- [ ] Detail page menampilkan:
  - Judul, semester, jenis kegiatan, status, tanggal, waktu, lokasi, deskripsi
  - Info pembuat (admin name, email, created_at, updated_at)
  - Warna kategori
  - Riwayat aktivitas (log) - harus ada 1 entry "Dibuat"
- **Expected Result:** Semua detail tersimpan dan ditampilkan dengan benar

#### 2.3. Edit Agenda (Ubah Agenda)
- [ ] Di halaman detail, klik tombol "Edit"
- [ ] Form edit terbuka dengan data pre-filled dari agenda
- [ ] Ubah deskripsi menjadi: "Testing edit - Verifikasi perubahan data"
- [ ] Ubah waktu selesai menjadi 11:00
- [ ] Klik "Perbarui Agenda"
- [ ] Redirect ke halaman index dengan notifikasi "Agenda berhasil diperbarui"
- [ ] Klik agenda lagi untuk lihat detail - perubahan sudah tersimpan
- [ ] Riwayat aktivitas menambah 1 entry baru "Diubah"
- **Expected Result:** Edit berhasil, data terupdate, audit trail tercatat

#### 2.4. Delete Agenda (Hapus Agenda)
- [ ] Buat agenda kedua untuk testing delete (judul: "Test Delete Agenda")
- [ ] Di halaman detail atau edit, klik tombol "Hapus"
- [ ] Modal konfirmasi muncul dengan warning "Tindakan ini tidak dapat dibatalkan"
- [ ] Klik "Hapus Permanen"
- [ ] Notifikasi sukses muncul "Agenda berhasil dihapus"
- [ ] Kembali ke index, agenda sudah hilang
- [ ] Riwayat aktivitas mencatat entry "Dihapus"
- **Expected Result:** Delete berhasil, data dihapus dari database, audit trail tercatat

---

### Phase 3: Sinkronisasi Admin → Mahasiswa

#### 3.1. Agenda Published Muncul di Mahasiswa
- [ ] Tetap di browser admin, buat 1 agenda lagi:
  - Judul: "Deadline Tugas Kelompok"
  - Jenis: "Deadline Tugas"
  - Tanggal: 2 hari dari sekarang
  - Status Publikasi: **CHECKED (ON)** ← PENTING
- [ ] Klik "Buat Agenda"
- [ ] Buka browser baru atau tab baru, login sebagai mahasiswa (user berbeda)
- [ ] Navigasi ke `/mahasiswa/kalender-akademik`
- [ ] **VERIFIKASI:** Agenda "Deadline Tugas Kelompok" muncul di kalender
  - Muncul di tanggal yang benar
  - Badge berwarna sesuai jenis kegiatan (orange untuk deadline)
  - Muncul di sidebar "Agenda Mendatang"
- **Expected Result:** Agenda muncul otomatis tanpa perlu refresh kode atau database

#### 3.2. Agenda Draft TIDAK Muncul di Mahasiswa
- [ ] Di browser admin, buat agenda baru:
  - Judul: "Test Draft Agenda"
  - Status Publikasi: **UNCHECKED (OFF)** ← PENTING
- [ ] Klik "Buat Agenda"
- [ ] Kembali ke browser mahasiswa, refresh halaman
- [ ] **VERIFIKASI:** Agenda "Test Draft Agenda" TIDAK muncul di kalender
- [ ] Hanya agenda yang published (status ON) yang terlihat di mahasiswa
- **Expected Result:** Draft agenda disembunyikan dari mahasiswa (privacy kontrol)

#### 3.3. Perubahan Agenda di Admin Langsung Terlihat di Mahasiswa
- [ ] Di browser admin, cari agenda "Deadline Tugas Kelompok" yang sudah dibuat
- [ ] Klik Edit, ubah:
  - Deskripsi: "Deadline tugas diperpanjang hingga jam 23:59"
  - Warna: ubah ke warna lain
- [ ] Klik "Perbarui"
- [ ] Di browser mahasiswa, refresh halaman atau tutup-buka modal
- [ ] **VERIFIKASI:** Perubahan sudah terlihat
  - Deskripsi terupdate di modal detail
  - Warna badge berubah sesuai
- **Expected Result:** Data real-time sinkron antara admin dan mahasiswa

---

### Phase 4: Fitur Tampilan (UI/UX)

#### 4.1. Kalender Admin - Grid & Filter
- [ ] Buat 5 agenda dengan jenis kegiatan berbeda (UTS, UAS, Libur, Deadline, Praktikum)
- [ ] Di admin kalender, klik tombol filter kategori
- [ ] Uncheck beberapa kategori (misal: UTS, Praktikum)
- [ ] Kalender grid update, hanya event dari kategori yang di-check tampil
- [ ] Klik "Semua" untuk menampilkan kembali semua kategori
- **Expected Result:** Filter kategori berfungsi real-time tanpa refresh halaman

#### 4.2. Kalender Mahasiswa - Grid & Events
- [ ] Login mahasiswa, lihat kalender
- [ ] Pilih bulan dengan banyak agenda (navigate prev/next month)
- [ ] Tanggal dengan 3+ agenda menampilkan badge "+N agenda"
- [ ] Klik "+N agenda", tampil list lengkap event di hari itu
- [ ] Event diurutkan ascending by waktu_mulai
- **Expected Result:** Multi-event handling sempurna, UI intuitif

#### 4.3. Sidebar Agenda Hari Ini, Mendatang, History
- [ ] Admin: sidebar "Agenda Hari Ini" menampilkan event hari ini (tanggal sesuai)
- [ ] Admin: sidebar "Agenda Mendatang" menampilkan 15 event terdekat
- [ ] Mahasiswa: sidebar "Agenda Hari Ini" menampilkan event hari ini
- [ ] Mahasiswa: sidebar "Agenda Mendatang" menampilkan event 60 hari ke depan (max 15)
- [ ] Klik event di sidebar untuk buka modal detail
- **Expected Result:** Sidebar berfungsi sempurna, sorting correct

#### 4.4. Modal Detail Event
- [ ] Buka modal event dari kalender atau sidebar
- [ ] Modal menampilkan:
  - Judul, kategori badge, waktu badge
  - Tanggal lengkap, waktu, lokasi
  - Deskripsi (jika ada)
  - Semester (untuk mahasiswa: opsional)
- [ ] Klik tombol X atau klik luar modal untuk close
- **Expected Result:** Modal user-friendly, semua info muncul dengan benar

---

### Phase 5: Validasi Data & Error Handling

#### 5.1. Validasi Form Create/Edit
- [ ] Coba submit form tanpa isi required field:
  - Judul kosong → error "Judul wajib diisi"
  - Semester tidak dipilih → error "Semester wajib dipilih"
  - Tanggal mulai kosong → error "Tanggal mulai wajib diisi"
  - Warna tidak dipilih → error "Warna wajib dipilih"
- [ ] Coba tanggal selesai < tanggal mulai → error "Tanggal selesai harus >= tanggal mulai"
- [ ] Coba waktu selesai < waktu mulai (jika tidak all-day) → error "Waktu selesai harus > waktu mulai"
- **Expected Result:** Validasi client & server berfungsi, user dapat dipandu dengan jelas

#### 5.2. Waktu Logic (All Day vs Specific Time)
- [ ] Buat agenda dengan "Sepanjang Hari" checked:
  - Field waktu mulai & selesai hidden/disabled
  - Submit → waktu_mulai & waktu_selesai jadi NULL di database
  - Tampilan: "Sepanjang Hari" badge muncul di detail
- [ ] Buat agenda dengan "Sepanjang Hari" unchecked:
  - Field waktu mulai & selesai visible & required
  - Input waktu 08:00 - 10:00
  - Submit → waktu_mulai='08:00', waktu_selesai='10:00' tersimpan
  - Tampilan: time badge "08:00 – 10:00" muncul
- **Expected Result:** All-day logic berfungsi sempurna, waktu stored & displayed correctly

#### 5.3. Multi-Day Event Handling
- [ ] Buat agenda tanggal mulai 15 Sep, tanggal selesai 17 Sep (3 hari)
- [ ] Di kalender admin & mahasiswa:
  - Event muncul di tanggal 15, 16, 17
  - Label yang sama di ketiga tanggal
  - Sorting tetap ascending by tanggal_mulai
- [ ] Detail event menampilkan range: "15 Sep 2024 – 17 Sep 2024"
- **Expected Result:** Multi-day event expanded di kalender grid correctly

---

### Phase 6: Performance & Edge Cases

#### 6.1. Large Dataset (Stress Test)
- [ ] Buat 50+ agenda untuk berbagai tanggal
- [ ] Kalender admin: load time masih < 2 detik
- [ ] Kalender mahasiswa: load time masih < 2 detik
- [ ] Filter kategori tetap responsif
- [ ] Navigate month tetap smooth
- **Expected Result:** Performance acceptable untuk production

#### 6.2. Semester Filtering
- [ ] Buat agenda untuk semester A dan semester B
- [ ] Di admin, select semester filter:
  - Pilih semester A → hanya agenda semester A tampil
  - Pilih semester B → hanya agenda semester B tampil
  - Pilih "Semua Semester" → semua agenda tampil
- [ ] Di mahasiswa, select semester filter:
  - Behavior sama seperti admin
- **Expected Result:** Semester filter works correctly

#### 6.3. Timezone & Date Accuracy
- [ ] Pastikan tanggal sesuai dengan timezone server (verifikasi di database)
- [ ] Buat agenda untuk berbagai tanggal (awal bulan, akhir bulan, boundary)
- [ ] Verifikasi kalender menampilkan tanggal yang benar
- **Expected Result:** Date handling correct, no timezone issues

---

### Phase 7: Accessibility & Dark Mode

#### 7.1. Dark Mode Support
- [ ] Toggle dark mode di browser/aplikasi
- [ ] Verifikasi kedua halaman (admin & mahasiswa):
  - Text readable (contrast sufficient)
  - Background colors appropriate
  - Badge colors visible
  - Modal colors correct
- **Expected Result:** Dark mode works perfectly, no readability issues

#### 7.2. Responsive Design
- [ ] Test pada device sizes:
  - Desktop (1920px)
  - Tablet (768px)
  - Mobile (375px)
- [ ] Kalender grid responsive
- [ ] Sidebar stacks correctly on mobile
- [ ] Form inputs remain accessible
- **Expected Result:** Responsive design works, usable on all devices

---

### Phase 8: Audit Trail & Activity Log

#### 8.1. Activity Log Recorded
- [ ] Di detail event admin, lihat "Riwayat Aktivitas" section
- [ ] Untuk event yang baru dibuat: harus ada minimal 2 entries
  - "Dibuat agenda..." dengan waktu created_at
  - "Diubah agenda..." dengan waktu updated_at (jika sudah di-edit)
- [ ] Kolom menampilkan: Waktu, Aksi (badge), Oleh (nama user), Keterangan
- [ ] Klik "Detail" button untuk lihat old_values & new_values
- **Expected Result:** Audit trail lengkap dan akurat

#### 8.2. User Attribution
- [ ] Buat agenda sebagai admin-A
- [ ] Edit agenda sebagai admin-B
- [ ] Di detail event:
  - "Pembuat" menunjukkan admin-A
  - "Diubah oleh" menunjukkan admin-B
  - Timestamps menunjukkan waktu yang berbeda
- **Expected Result:** User attribution correct untuk audit compliance

---

## Kesimpulan & Sign-Off

**Tanggal Testing:** `____________________`  
**Tester Name:** `____________________`  
**Status:** [ ] PASS [ ] FAIL

**Summary:**
- Total test cases: 40+
- Passed: `_____`
- Failed: `_____`
- Issues found: `_____`

**Critical Issues Found:**
(List any critical bugs that block functionality)

**Minor Issues Found:**
(List any UI/UX or non-critical issues)

**Approved By:** `____________________`  
**Date:** `____________________`

---

## Post-Testing Checklist

- [ ] All critical issues resolved
- [ ] All minor issues documented & scheduled for fix
- [ ] Code cleanup done (no console.log debugging)
- [ ] Documentation updated
- [ ] Ready for production deployment

---

## Notes & Recommendations

- Sistem sudah production-ready jika semua test cases PASS
- Untuk high-traffic usage, consider adding caching untuk queries event by date
- Pertimbangkan import/export agenda (CSV/Excel) untuk admin bulk operations
- Saran: Tambahkan email notification ketika agenda dibuat/diubah

