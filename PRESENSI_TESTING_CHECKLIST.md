# PRESENSI SYSTEM - TESTING CHECKLIST

## Project Overview
Sistem Presensi Terintegrasi untuk LMS Cendekia dengan alur:
1. **Dosen** membuat sesi presensi → membuka sesi → menutup sesi
2. **Mahasiswa** melihat sesi aktif di kelas → melakukan presensi (Hadir/Izin/Sakit)
3. **Dosen** melihat daftar hadir + status → menutup sesi
4. **Mahasiswa** melihat riwayat dan statistik presensi

---

## 🔴 WORKFLOW TESTING CHECKLIST

### ✅ 1. DOSEN - Create Attendance Session
**Route:** `dosen.absensi.create` (GET/POST)
**File:** `dosen/absensi/create.blade.php`

- [ ] Dosen login & akses kelas yang diampu
- [ ] Tombol "Buat Presensi" muncul di kelas
- [ ] Form terbuka dengan:
  - [ ] Pertemuan ke (auto-increment dari pertemuan sebelumnya)
  - [ ] Tanggal (date picker)
  - [ ] Jam Mulai (time input)
  - [ ] Jam Selesai (time input)
  - [ ] Rangkuman (textarea)
  - [ ] Berita Acara (textarea)
  - [ ] Catatan (textarea)
  - [ ] Sidebar dengan info kelas, total mahasiswa, quick tips
- [ ] Submit form → redirect ke detail presensi dengan status "DRAFT"
- [ ] Validasi error handling (tanggal kosong, jam tidak valid, pertemuan duplikat)
- [ ] UI modern dengan gradient, shadow, responsive design

### ✅ 2. DOSEN - View Attendance Sessions List
**Route:** `dosen.absensi.index`
**File:** `dosen/absensi/index.blade.php` (create if not exists)

- [ ] Dosen melihat list semua presensi untuk 1 kelas
- [ ] Setiap sesi menampilkan:
  - [ ] Pertemuan ke
  - [ ] Tanggal & Waktu
  - [ ] Status (DRAFT/BUKA/TUTUP) dengan badge color
  - [ ] Jumlah hadir / total mahasiswa
  - [ ] Action buttons (Lihat, Edit, Hapus)
- [ ] Pagination if > 10 sessions
- [ ] Statistics card: total sesi, draft, buka, tutup
- [ ] UI modern dengan gradient backgrounds, icons, shadows

### ✅ 3. DOSEN - Open Attendance Session
**Route:** `dosen.absensi.buka` (POST)
**File:** `dosen/absensi/show.blade.php`

- [ ] Dosen click "BUKA SESI" button pada detail presensi (status DRAFT)
- [ ] Confirm modal atau inline confirmation
- [ ] Session status berubah dari DRAFT → BUKA
- [ ] Notifikasi email dikirim ke semua mahasiswa (if NotificationService integrated)
- [ ] Success message: "Sesi presensi telah dibuka. Mahasiswa dapat mulai melakukan presensi."
- [ ] UI shows real-time session status with colored badge

### ✅ 4. DOSEN - View Attendance Details & Student List
**Route:** `dosen.absensi.show`
**File:** `dosen/absensi/show.blade.php`

- [ ] Dosen melihat detail 1 sesi presensi dengan:
  - [ ] Breadcrumb: Dashboard > Kelas > Presensi > Detail
  - [ ] Session info card (pertemuan, tanggal, waktu, status)
  - [ ] Statistics cards: Hadir | Izin | Sakit | Alpha (dengan count & colors)
  - [ ] Student attendance table dengan kolom:
    - [ ] No. Urut
    - [ ] Foto/Avatar (gradient initials)
    - [ ] Nama Mahasiswa
    - [ ] NIM
    - [ ] Status (Hadir/Izin/Sakit/Alpha) dengan colored badge
    - [ ] Waktu Absensi (jika ada)
    - [ ] Keterangan (if Izin/Sakit)
  - [ ] Sidebar dengan:
    - [ ] Quick action buttons: Buka/Tutup Sesi, Edit, Hapus, Export
    - [ ] Session timeline (pertemuan sebelumnya dengan status)
    - [ ] Tips card
- [ ] Hover effects pada student rows
- [ ] Dropdown/modal untuk Edit & Delete actions
- [ ] All UI using Tailwind with modern design

### ✅ 5. DOSEN - Edit Attendance Session
**Route:** `dosen.absensi.edit` / `dosen.absensi.update` (GET/PUT)
**File:** `dosen/absensi/edit.blade.php`

- [ ] Dosen click "Edit" pada detail presensi
- [ ] Form pre-filled dengan data existing:
  - [ ] Pertemuan ke
  - [ ] Tanggal
  - [ ] Jam Mulai & Selesai
  - [ ] Status dropdown (DRAFT/BUKA/TUTUP)
  - [ ] Rangkuman, Berita Acara, Catatan
- [ ] Submit update → redirect ke show dengan success message
- [ ] Validasi: No duplicate pertemuan ke untuk kelas yang sama
- [ ] UI consistent dengan create form

### ✅ 6. DOSEN - Edit Individual Student Attendance (Manual Correction)
**Route:** `dosen.absensi.attendance` / `dosen.absensi.updateAttendance` (GET/PUT)
**File:** `dosen/absensi/attendance.blade.php`

- [ ] Dosen click "Koreksi Kehadiran" atau edit icon pada student list
- [ ] Form menampilkan list semua mahasiswa kelas dengan:
  - [ ] Status dropdown per mahasiswa (Hadir/Izin/Sakit/Alpha)
  - [ ] Keterangan textarea (for Izin/Sakit)
  - [ ] Current status indicator
- [ ] Submit → update AbsensiMahasiswa records
- [ ] Success message & redirect ke detail presensi
- [ ] Validasi: only registered students in this class

### ✅ 7. DOSEN - Close Attendance Session
**Route:** `dosen.absensi.tutup` (POST)
**File:** `dosen/absensi/show.blade.php`

- [ ] Dosen click "TUTUP SESI" button pada detail presensi (status BUKA)
- [ ] Confirm modal: "Setelah ditutup, mahasiswa tidak dapat lagi melakukan presensi. Lanjutkan?"
- [ ] Session status berubah dari BUKA → TUTUP
- [ ] Mahasiswa yang belum presensi otomatis dihitung sebagai ALPHA (calculated at query time, not stored)
- [ ] Success message: "Sesi presensi telah ditutup."
- [ ] UI shows TUTUP status with red/closed badge

### ✅ 8. DOSEN - Delete Attendance Session
**Route:** `dosen.absensi.destroy` (DELETE)
**File:** `dosen/absensi/show.blade.php`

- [ ] Dosen click "Hapus" button
- [ ] Confirm modal: "Semua data presensi untuk sesi ini akan dihapus. Yakin?"
- [ ] DELETE absensiMahasiswa records & absensi record
- [ ] Redirect to attendance list with success message
- [ ] Validasi: only the teaching dosen can delete

---

## 🔵 WORKFLOW TESTING CHECKLIST

### ✅ 9. MAHASISWA - View Attendance Entry Page (Per Kelas)
**Route:** `mahasiswa.absensi.kelas` (GET)
**File:** `mahasiswa/absensi/kelas-absensi.blade.php`

- [ ] Mahasiswa login & access kelas yang diikuti
- [ ] Klik menu "Presensi" atau tab Presensi di kelas detail
- [ ] Page menampilkan:
  - [ ] Breadcrumb: Dashboard > Kelas > Presensi
  - [ ] Alert: "Sesi presensi sedang dibuka" (jika ada active session hari ini)
  - [ ] Session info card (pertemuan, tanggal, waktu, status "DIBUKA")
  - [ ] **Attendance form dengan 3 large buttons:**
    - [ ] Hadir (green/emerald button)
    - [ ] Izin (blue button)
    - [ ] Sakit (amber/yellow button)
  - [ ] Conditional textarea "Keterangan" (shows only for Izin/Sakit)
  - [ ] Submit button (dynamic color based on selected status)
  - [ ] Class info card (mata kuliah, dosen, ruangan)
  - [ ] Recent attendance history sidebar (last 5 sessions)
  - [ ] Tips card dengan info penting
- [ ] All UI modern with gradients, shadows, animations, responsive

### ✅ 10. MAHASISWA - Submit Attendance (Check-In)
**Route:** `mahasiswa.absensi.masuk` (POST)
**File:** `mahasiswa/absensi/kelas-absensi.blade.php` → form submission

- [ ] Mahasiswa select status (Hadir/Izin/Sakit)
- [ ] If Izin/Sakit: keterangan field required
- [ ] If Hadir: keterangan optional
- [ ] Click submit button
- [ ] Database: Create AbsensiMahasiswa record dengan:
  - [ ] absensi_id, mahasiswa_id, status, keterangan, waktu_absensi
  - [ ] Lock untuk prevent double submission (DB transaction)
- [ ] Success message: "Presensi berhasil dicatat. Status Anda: [STATUS]."
- [ ] Form cleared or redirected
- [ ] If already submitted: warning "Anda sudah melakukan presensi untuk sesi ini."
- [ ] If session closed: warning "Sesi presensi tidak dapat diakses (sudah ditutup atau bukan sesi hari ini)."

### ✅ 11. MAHASISWA - View Attendance History & Statistics
**Route:** `mahasiswa.absensi.show` (GET)
**File:** `mahasiswa/absensi/show.blade.php`

- [ ] Mahasiswa click "Riwayat Presensi" atau access `/mahasiswa/absensi/{kelasId}`
- [ ] Page menampilkan:
  - [ ] Breadcrumb: Dashboard > Kelas > Riwayat Presensi
  - [ ] **Statistics Cards:**
    - [ ] Total Pertemuan (count of all absensi for this class)
    - [ ] Hadir (count with % & progress bar)
    - [ ] Izin (count with % & progress bar)
    - [ ] Sakit (count with % & progress bar)
    - [ ] Alpha (count with % & progress bar = total - submitted)
  - [ ] Progress bars with gradient colors:
    - [ ] Hadir: Green gradient
    - [ ] Izin: Blue gradient
    - [ ] Sakit: Amber gradient
    - [ ] Alpha: Red gradient
  - [ ] Class info card: mata kuliah, dosen, ruangan, kode kelas
  - [ ] **Attendance history table** (paginated):
    - [ ] Pertemuan # (with gradient circle)
    - [ ] Tanggal & Waktu
    - [ ] Status (colored badge: green/hadir, blue/izin, amber/sakit, red/alpha)
    - [ ] Keterangan (if any)
    - [ ] Empty state if no records: "Belum ada data presensi untuk kelas ini."
  - [ ] Legend card: color codes untuk setiap status
- [ ] All UI modern with gradients, shadows, animations

### ✅ 12. MAHASISWA - Authorization & Access Control
- [ ] Mahasiswa can ONLY access kelas yang terdaftar (kelasDiikuti)
- [ ] Mahasiswa CANNOT access presensi dari kelas lain (404 or redirect)
- [ ] Mahasiswa CANNOT submit presensi jika:
  - [ ] Session status ≠ "BUKA"
  - [ ] Session tanggal ≠ today
  - [ ] Sudah submit untuk session ini (double submission check)
- [ ] Mahasiswa CANNOT edit/delete presensi

---

## 🟢 AUTHORIZATION & SECURITY TESTING

### ✅ 13. Authorization: Dosen Management Rights
- [ ] Only the teaching dosen (dosen_id or dosen_pengampu) can:
  - [ ] Create attendance session
  - [ ] Open/Close session
  - [ ] Edit session
  - [ ] Delete session
  - [ ] View student list
  - [ ] Manually correct attendance
- [ ] Other dosens (not teaching this class) get 403 Forbidden
- [ ] Admin cannot access dosen attendance management (for now)

### ✅ 14. Authorization: Mahasiswa Check-In Rights
- [ ] Only registered mahasiswa in this class can:
  - [ ] View this class's attendance entry page
  - [ ] View attendance history for this class
  - [ ] Submit attendance if session is BUKA
- [ ] Mahasiswa from other classes get 404 or redirect
- [ ] Not-enrolled mahasiswa get 404

### ✅ 15. Double Submission Prevention
- [ ] Use DB transaction + lockForUpdate in `absenMasuk` method
- [ ] Second attempt returns warning message
- [ ] No duplicate AbsensiMahasiswa records created

### ✅ 16. Session Status Flow Validation
- [ ] Session can only move: DRAFT → BUKA → TUTUP
- [ ] Cannot go backwards (TUTUP → BUKA not allowed)
- [ ] Cannot open already-opened session
- [ ] Cannot close already-closed session

---

## 🟡 EDGE CASES & ERROR HANDLING

### ✅ 17. Multiple Attendance Sessions Same Day
- [ ] Dosen can create multiple sessions for different pertemuan on same date
- [ ] Each session is independent
- [ ] Mahasiswa sees only the BUKA session today (latest if multiple)
- [ ] Query: `whereDate('tanggal', today())->latest('created_at')->first()`

### ✅ 18. Alpha Status Calculation
- [ ] Alpha = total mahasiswa - submitted attendance
- [ ] Calculated at query time, not stored as record
- [ ] Auto-calculated when session closed
- [ ] When exporting/viewing: `$stats['alpha'] = $kelas->mahasiswa->count() - $absensi->absensiMahasiswa->count()`

### ✅ 19. Empty Sessions
- [ ] Dosen can create session but no mahasiswa submits
- [ ] All students show as Alpha
- [ ] Page displays correctly

### ✅ 20. Session Past Closing Time
- [ ] If session tanggal is today but NOW > jam_selesai:
  - [ ] Display as "Session ended" or "No longer accepting attendance"
  - [ ] OR allow submission if status=BUKA? (Define business logic)
  - [ ] Current impl: allows if status=BUKA regardless of time
- [ ] Mahasiswa cannot submit if tanggal ≠ today (regardless of status)

### ✅ 21. Partial Attendance Data
- [ ] Dosen closes session with some students as Alpha
- [ ] Page displays correctly without errors
- [ ] Statistics accurate

### ✅ 22. Large Class (100+ students)
- [ ] Pagination works (10 per page in show method)
- [ ] Table renders without lag
- [ ] Performance acceptable

---

## 📱 UI/UX TESTING

### ✅ 23. Responsive Design
**Desktop (1920px):** All elements visible, well-spaced
**Tablet (768px):** Sidebar collapses/hides, main content full width
**Mobile (375px):** 
- [ ] Buttons stack vertically
- [ ] Tables scroll horizontally or collapse to card view
- [ ] Form inputs properly sized
- [ ] Status buttons remain large and touchable

### ✅ 24. Color Coding Consistency
- [ ] Hadir: Green/Emerald (consistent across all views)
- [ ] Izin: Blue (consistent)
- [ ] Sakit: Amber/Yellow (consistent)
- [ ] Alpha: Red (consistent)
- [ ] DRAFT badge: Gray
- [ ] BUKA badge: Green/glowing
- [ ] TUTUP badge: Red/closed

### ✅ 25. Interactive Elements
- [ ] Buttons hover effects
- [ ] Forms focus states
- [ ] Dropdown menus functional
- [ ] Modals open/close smoothly
- [ ] Animations smooth (not jarring)
- [ ] Page transitions smooth

### ✅ 26. Accessibility
- [ ] Proper contrast ratios (WCAG AA)
- [ ] Form labels associated with inputs
- [ ] Error messages clear and visible
- [ ] Keyboard navigation works
- [ ] Screen reader friendly (ARIA labels if needed)

### ✅ 27. Error Messages
- [ ] Clear, non-technical language
- [ ] Helpful suggestion for resolution
- [ ] Properly displayed (not hidden)
- [ ] Animated/highlighted for visibility

### ✅ 28. Success Messages
- [ ] Clear confirmation of action
- [ ] Visible for 5+ seconds
- [ ] Auto-dismiss or manual close

---

## 🔧 DATA INTEGRITY TESTING

### ✅ 29. Database Consistency
- [ ] absensi table has all required columns:
  - [ ] id, kelas_perkuliahan_id, pertemuan_ke, tanggal, jam_mulai, jam_selesai
  - [ ] session_status (draft/buka/tutup), rangkuman, berita_acara, catatan
  - [ ] created_at, updated_at
- [ ] absensi_mahasiswa table:
  - [ ] id, absensi_id, mahasiswa_id, status (hadir/izin/sakit)
  - [ ] keterangan, waktu_absensi, created_at, updated_at
- [ ] Foreign keys working (cascade delete)
- [ ] Timestamps auto-updated

### ✅ 30. Notification Service Integration (Optional)
- [ ] When session opened: NotificationService::notifyAbsensiDibuka() called
- [ ] Email sent to all enrolled students
- [ ] Email contains: kelas, pertemuan, session deadline?
- [ ] Fallback if email fails (graceful error)

### ✅ 31. Pagination & Query Performance
- [ ] Attendance list pagination (10 per page)
- [ ] N+1 queries avoided (use eager loading with()):
  - [ ] `with(['mataKuliah', 'dosen', 'mahasiswa'])`
  - [ ] `with(['absensiMahasiswa.mahasiswa'])`
- [ ] Load time < 1 second for typical classes

---

## 📋 WORKFLOW SCENARIOS TO TEST

### Scenario A: Happy Path - Complete Workflow
1. Dosen creates session (DRAFT)
2. Dosen opens session (BUKA) → email notification sent
3. Mahasiswa sees active session
4. Mahasiswa submits Hadir
5. Mahasiswa 2 submits Izin (dengan keterangan)
6. Mahasiswa 3 submits Sakit (dengan keterangan)
7. Mahasiswa 4 doesn't submit (will be Alpha)
8. Dosen views attendance list: 1 Hadir, 1 Izin, 1 Sakit, 1 Alpha
9. Dosen closes session (TUTUP)
10. Mahasiswa views riwayat: statistics updated
11. ✅ All steps successful

### Scenario B: Dosen Corrects Attendance
1. Session BUKA, mahasiswa submit attendance
2. Dosen realizes a mahasiswa should be Sakit (was marked Hadir by mistake)
3. Dosen clicks "Koreksi Kehadiran"
4. Dosen updates mahasiswa status to Sakit + keterangan
5. Dosen closes session
6. Mahasiswa views riwayat: status corrected
7. ✅ Correction works

### Scenario C: Session Never Opened
1. Dosen creates session (DRAFT)
2. Dosen decides not to open it
3. Dosen deletes session
4. Mahasiswa never sees this session
5. ✅ Delete works, no orphaned data

### Scenario D: Multiple Sessions Same Class
1. Dosen creates Session 1 (Pertemuan 1) - BUKA
2. Dosen creates Session 2 (Pertemuan 2) - DRAFT
3. Mahasiswa sees only Session 1 as active (latest BUKA today)
4. Mahasiswa submits for Session 1
5. Dosen opens Session 2
6. Mahasiswa sees Session 2 as new active
7. Mahasiswa submits for Session 2
8. Both sessions have attendance records
9. ✅ Multiple sessions work independently

### Scenario E: Security - Unauthorized Access
1. Mahasiswa A tries to access kelas B (not enrolled)
2. ✅ 404 or redirect
3. Mahasiswa A tries to access dosen.absensi routes
4. ✅ 403 or redirect (role:dosen middleware)
5. Dosen A tries to manage attendance for Dosen B's class
6. ✅ 403 Forbidden (authorization policy)

---

## 📝 TESTING EXECUTION LOG

Use this section to log actual test results:

| Test # | Status | Notes | Tester | Date |
|--------|--------|-------|--------|------|
| 1      | ⏳     |       |        |      |
| 2      | ⏳     |       |        |      |
| ...    | ⏳     |       |        |      |

---

## 🐛 KNOWN ISSUES & FIXES

(To be updated during testing)

---

## ✨ COMPLETED TASKS

- [x] Created dosen/absensi/create.blade.php (Modern UI)
- [x] Created dosen/absensi/show.blade.php (Modern UI + student list)
- [x] Created mahasiswa/absensi/kelas-absensi.blade.php (Modern attendance entry UI)
- [x] Created mahasiswa/absensi/show.blade.php (Statistics + history view)
- [x] Controllers (Dosen & Mahasiswa AbsensiController) - fully implemented
- [x] Models (Absensi & AbsensiMahasiswa) - with relationships & scopes
- [x] Routes - all presensi routes configured
- [x] Authorization policies - dosen & mahasiswa access control
- [x] Notifications - email when session opened (via NotificationService)

---

## 🚀 NEXT STEPS (POST-TESTING)

1. If all tests pass: Mark Task #5 as COMPLETE
2. Create index view (dosen/absensi/index.blade.php) if not exists
3. Consider: attendance/edit.blade.php layout improvements
4. Consider: attendance/attendance.blade.php (manual correction) UI improvements
5. Performance optimization: caching, query optimization
6. Add export PDF feature
7. Add attendance analytics/reports dashboard

---

**Generated:** July 10, 2026
**Version:** 1.0
**Status:** Ready for Testing
