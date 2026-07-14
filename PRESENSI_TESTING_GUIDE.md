# PRESENSI SYSTEM - TESTING EXECUTION GUIDE

## Prerequisites

### Test Users Setup
You should have test users created in your database:

```
ADMIN User:
- Email: admin@cendekia.test
- Password: password
- Role: admin

DOSEN User:
- Email: dosen1@cendekia.test
- Password: password
- Role: dosen
- Teaching: Kelas A (Matematika), Kelas B (Fisika)

MAHASISWA Users:
- Email: mahasiswa1@cendekia.test → Enrolled in Kelas A
- Email: mahasiswa2@cendekia.test → Enrolled in Kelas A
- Email: mahasiswa3@cendekia.test → Enrolled in Kelas A
- Email: mahasiswa4@cendekia.test → Enrolled in Kelas A
- Email: mahasiswa5@cendekia.test → Enrolled in Kelas B
```

### Database Setup
```bash
php artisan migrate
php artisan db:seed # if you have seeders
# OR manually create users and classes
```

### Start Development Server
```bash
php artisan serve
# Application will be available at http://localhost:8000
```

---

## QUICK TEST SCENARIOS

### 🟢 SCENARIO 1: Complete Attendance Workflow (Happy Path)

**Duration:** ~10 minutes  
**Users Needed:** 1 Dosen, 4 Mahasiswa

#### Step 1: Dosen Creates Attendance Session
1. Login as `dosen1@cendekia.test`
2. Navigate to "Dashboard" → "Kelas Saya" → Select "Kelas A (Matematika)"
3. Look for "Presensi" section or button
4. Click "Buat Presensi Baru" or "+" button
5. **Expected:** Form opens with:
   - [ ] Pertemuan ke (auto-filled to next meeting number)
   - [ ] Date picker (tanggal)
   - [ ] Time inputs (jam mulai, jam selesai)
   - [ ] Textareas (rangkuman, berita acara, catatan)
   - [ ] Sidebar with class info
6. **Fill Form:**
   - Pertemuan ke: `1`
   - Tanggal: Today's date
   - Jam Mulai: `08:00`
   - Jam Selesai: `10:00`
   - Rangkuman: "Kuliah tentang integral"
   - Berita Acara: "Tidak ada"
   - Catatan: "-"
7. Click **"Buat Presensi"** button
8. **Expected Result:**
   - [ ] Form submits successfully
   - [ ] Success message: "Sesi presensi berhasil dibuat dengan status Draft..."
   - [ ] Redirect to attendance detail page
   - [ ] Session shows status "DRAFT" (gray badge)
   - [ ] Page displays: session info, empty student list, sidebar with action buttons

#### Step 2: Dosen Opens Attendance Session
9. On the attendance detail page, click **"BUKA SESI"** button in sidebar or dropdown
10. **Expected:** Confirmation modal appears
11. Click **"Lanjutkan"** or **"BUKA SESI"**
12. **Expected Result:**
    - [ ] Success message: "Sesi presensi telah dibuka..."
    - [ ] Session status changes to "BUKA" (green badge)
    - [ ] Email notification sent to all 4 mahasiswa (check email or logs)
    - [ ] Page refreshes to show new status

#### Step 3: Mahasiswa 1 Performs Attendance (Hadir)
13. **Logout dosen, Login as `mahasiswa1@cendekia.test`**
14. Navigate to Dashboard → Kelas Saya → Select "Kelas A"
15. Look for "Presensi" section or tab
16. **Expected:** Alert shows "Sesi presensi sedang dibuka hari ini"
17. Page displays:
    - [ ] Active session info card
    - [ ] 3 large buttons: Hadir (green), Izin (blue), Sakit (amber)
    - [ ] Submit button
18. **Select "Hadir"** and click submit
19. **Expected Result:**
    - [ ] Success message: "Presensi berhasil dicatat. Status Anda: Hadir."
    - [ ] Form clears or redirects to kelas-absensi page
    - [ ] Page updates to show: "Anda sudah melakukan presensi"

#### Step 4: Mahasiswa 2 Performs Attendance (Izin with Reason)
20. **Logout mahasiswa1, Login as `mahasiswa2@cendekia.test`**
21. Navigate to same class presensi page
22. **Select "Izin"** button
23. **Expected:** Keterangan textarea appears (conditionally shown)
24. **Fill keterangan:** "Ada rapat akademik"
25. Click **"Kirim Presensi"** or submit button
26. **Expected Result:**
    - [ ] Success message: "Presensi berhasil dicatat. Status Anda: Izin."
    - [ ] Keterangan saved in database

#### Step 5: Mahasiswa 3 Performs Attendance (Sakit with Reason)
27. **Logout mahasiswa2, Login as `mahasiswa3@cendekia.test`**
28. Navigate to same class presensi page
29. **Select "Sakit"** button
30. **Expected:** Keterangan textarea appears
31. **Fill keterangan:** "Demam tinggi, akan ke dokter"
32. Click **"Kirim Presensi"** or submit button
33. **Expected Result:**
    - [ ] Success message: "Presensi berhasil dicatat. Status Anda: Sakit."

#### Step 6: Mahasiswa 4 Doesn't Submit (Will be Alpha)
34. **Logout mahasiswa3, Login as `mahasiswa4@cendekia.test`**
35. Navigate to class presensi page
36. Do NOT submit attendance
37. **Logout**

#### Step 7: Dosen Views Attendance List
38. **Login as `dosen1@cendekia.test`**
39. Navigate back to Kelas A → Presensi → Session detail
40. **Expected:** Student attendance table shows:
    - [ ] Mahasiswa 1: Status "Hadir" (green badge), waktu_absensi timestamp
    - [ ] Mahasiswa 2: Status "Izin" (blue badge), keterangan "Ada rapat akademik"
    - [ ] Mahasiswa 3: Status "Sakit" (amber badge), keterangan "Demam tinggi..."
    - [ ] Mahasiswa 4: Status "Alpha" (red badge), no timestamp
    - [ ] Statistics cards: 1 Hadir | 1 Izin | 1 Sakit | 1 Alpha
41. **Hover effects** work on table rows
42. Table displays properly on desktop/tablet/mobile

#### Step 8: Dosen Closes Attendance Session
43. Click **"TUTUP SESI"** button in sidebar or dropdown
44. **Expected:** Confirmation modal: "Setelah ditutup, mahasiswa tidak dapat lagi melakukan presensi. Lanjutkan?"
45. Click **"Tutup Sesi"** or **"Lanjutkan"**
46. **Expected Result:**
    - [ ] Success message: "Sesi presensi telah ditutup."
    - [ ] Session status changes to "TUTUP" (red badge)
    - [ ] Buttons "Buka Sesi" disabled/hidden

#### Step 9: Mahasiswa Views Attendance History & Stats
47. **Login as `mahasiswa1@cendekia.test`**
48. Navigate to Dashboard → Presensi → Select Kelas A
49. OR Navigate directly to `/mahasiswa/absensi/{kelasId}`
50. **Expected:** Statistics page displays:
    - [ ] Header: Kelas A, Mata Kuliah name, Dosen name
    - [ ] Statistics cards with:
        - Total Pertemuan: 1
        - Hadir: 1 (100%) with green progress bar
        - Izin: 1 (25%) with blue progress bar
        - Sakit: 1 (25%) with amber progress bar
        - Alpha: 1 (25%) with red progress bar
    - [ ] Attendance history table:
        - Pertemuan 1 | Today | Hadir | — (no keterangan for hadir)
    - [ ] Color-coded badges match across all pages

#### Step 10: Verify No More Attendance After Session Closed
51. Try to submit attendance again on same session
52. **Expected:** Warning message: "Anda sudah melakukan presensi untuk sesi ini." OR "Sesi presensi tidak dapat diakses (sudah ditutup...)"

✅ **SCENARIO 1 COMPLETE** - All workflow steps successful

---

### 🔵 SCENARIO 2: Dosen Corrects Attendance Manually

**Duration:** ~5 minutes  
**Users Needed:** 1 Dosen

#### Step 1: Open Attendance Detail
1. **Login as dosen1**
2. Navigate to attendance session (from Scenario 1)
3. On detail page, click **"Koreksi Kehadiran"** button or icon next to a student

#### Step 2: Edit Student Status
4. Form shows all students with current status
5. Change Mahasiswa 1 from "Hadir" to "Izin"
6. Add keterangan: "Telat karena macet"
7. Click **"Simpan"** or submit

#### Step 3: Verify Correction
8. **Expected:** Success message
9. Student list refreshes showing:
   - [ ] Mahasiswa 1 now shows "Izin" with new keterangan
   - [ ] Statistics updated: 0 Hadir, 2 Izin, 1 Sakit, 1 Alpha

✅ **SCENARIO 2 COMPLETE**

---

### 🟡 SCENARIO 3: Authorization & Security Tests

**Duration:** ~5 minutes

#### Test 1: Mahasiswa Cannot Access Other Class Presensi
1. **Login as mahasiswa1** (enrolled in Kelas A only)
2. Try to access `/mahasiswa/absensi/kelas/999` (nonexistent/wrong class)
3. **Expected:** 404 error or redirect to dashboard

#### Test 2: Dosen Cannot Manage Other Dosen's Presensi
1. **Create another dosen user** (dosen2) who teaches different class
2. **Login as dosen2**
3. Try to access `/dosen/kelas/[kelasA_id]/absensi/[sessionId]` (from dosen1's class)
4. **Expected:** 403 Forbidden error

#### Test 3: Mahasiswa Cannot Submit Twice
1. **Login as mahasiswa1**
2. Try to submit attendance again (manually craft form OR hack form if UI prevents)
3. **Expected:** Database transaction prevents duplicate, warning message shown

#### Test 4: Cannot Submit to Closed Session
1. **Login as mahasiswa1**
2. Try to POST to `/mahasiswa/absensi/kelas/{kelasId}/masuk/{absensiId}` (closed session)
3. **Expected:** Warning: "Sesi presensi tidak dapat diakses (sudah ditutup...)"

✅ **SCENARIO 3 COMPLETE**

---

### 🟢 SCENARIO 4: UI Responsiveness Check

**Duration:** ~5 minutes

#### Desktop (1920px - Full Browser)
1. **Login as dosen1**
2. Navigate to attendance detail page
3. **Check:**
   - [ ] All elements visible and well-spaced
   - [ ] Student table displays all columns properly
   - [ ] Sidebar visible on right
   - [ ] Buttons and forms easily clickable
   - [ ] No horizontal scrolling needed

#### Tablet (768px - iPad/Tab size)
4. Open same page in browser **Developer Tools** (F12) → **Device Emulation** → Select iPad
5. **Check:**
   - [ ] Sidebar collapses or moves below main content
   - [ ] Main content area expands to full width
   - [ ] Student table remains readable (may need horizontal scroll for many columns)
   - [ ] Buttons remain touchable (min 44x44px)
   - [ ] No layout breaks

#### Mobile (375px - iPhone size)
6. Select iPhone in device emulation
7. **Check:**
   - [ ] All sections stack vertically
   - [ ] Table converts to card view OR allows horizontal scroll
   - [ ] Large buttons remain easily clickable
   - [ ] Form inputs properly sized
   - [ ] No text is cut off
   - [ ] Modals display properly (not cut off)

✅ **SCENARIO 4 COMPLETE**

---

### 🔴 SCENARIO 5: Edge Cases

**Duration:** ~10 minutes

#### Edge Case 1: Empty Class (No Mahasiswa)
1. Create a new class with dosen1 teaching but 0 mahasiswa
2. Dosen creates attendance session
3. Dosen opens session
4. Dosen views attendance detail
5. **Expected:**
   - [ ] Student table shows "Tidak ada mahasiswa dalam kelas ini" or similar
   - [ ] Statistics: 0 Hadir, 0 Izin, 0 Sakit, 0 Alpha (or all 0)
   - [ ] No errors

#### Edge Case 2: Large Class (50+ students)
1. Create class with 50+ mahasiswa
2. Dosen opens session
3. Multiple mahasiswa submit attendance
4. Dosen views detail page
5. **Expected:**
   - [ ] Pagination works (shows 10/page with next/prev)
   - [ ] Page loads without lag
   - [ ] Statistics calculated correctly

#### Edge Case 3: Pertemuan Counter
1. Create 5 attendance sessions for same class
2. Verify pertemuan_ke is 1, 2, 3, 4, 5
3. Create new session → should auto-populate pertemuan_ke as 6
4. **Expected:** No gaps in pertemuan numbers

#### Edge Case 4: Date Edge Cases
1. Try to create session with tanggal in the past (e.g., yesterday)
2. **Expected:** Should allow (or restrict if business rule says so)
3. Try to create session with tanggal in far future
4. **Expected:** Should allow
5. Try to submit attendance for session not today
6. **Expected:** Warning "Sesi presensi tidak dapat diakses (bukan sesi hari ini)"

✅ **SCENARIO 5 COMPLETE**

---

## DETAILED TESTING STEPS BY FEATURE

### Feature 1: Create Attendance Session

**Route:** `dosen.absensi.create` & `dosen.absensi.store`  
**Files:** `dosen/absensi/create.blade.php`, `DosenAbsensiController@create`, `DosenAbsensiController@store`

```
✅ Check: Form displays correctly
   - Navigate to /dosen/kelas/{kelasId}/absensi/create
   - Verify all fields visible

✅ Check: Form validation
   - Submit empty form → see validation errors
   - Submit with invalid time format → validation error
   - Submit with duplicate pertemuan → "Sesi presensi untuk pertemuan ke-X sudah ada"

✅ Check: Database insert
   - After successful submit, check database:
   $ php artisan tinker
   >>> \App\Models\Absensi::latest()->first();
   - Verify: kelas_perkuliahan_id, pertemuan_ke, tanggal, jam_mulai, jam_selesai, session_status='draft'

✅ Check: UI Modern Design
   - Breadcrumb visible and correct
   - Error alerts display with proper styling
   - Form inputs have focus states and shadows
   - Sidebar shows class info
   - Gradient backgrounds visible
```

### Feature 2: Open Attendance Session

**Route:** `dosen.absensi.buka` (POST)  
**File:** `DosenAbsensiController@bukaSession`

```
✅ Check: Session status changes
   Database before: session_status = 'draft'
   After POST /dosen/kelas/{kelasId}/absensi/{absensiId}/buka
   Database after: session_status = 'buka', waktu_buka = NOW()

✅ Check: Email notification
   - Check if NotificationService::notifyAbsensiDibuka() called
   - Monitor /storage/logs/laravel.log for mail events (or use MailHog if configured)
   - All mahasiswa in class should receive notification

✅ Check: UI feedback
   - Session badge changes from gray "DRAFT" to green "DIBUKA"
   - "Buka Sesi" button becomes disabled
   - "Tutup Sesi" button becomes enabled
```

### Feature 3: Mahasiswa Performs Attendance

**Route:** `mahasiswa.absensi.absenMasuk` (POST)  
**File:** `mahasiswa/absensi/kelas-absensi.blade.php`, `MahasiswaAbsensiController@absenMasuk`

```
✅ Check: Form submission
   - Select status (Hadir/Izin/Sakit)
   - If Izin/Sakit: fill keterangan
   - Submit form
   - Success message displays

✅ Check: Database insert
   $ php artisan tinker
   >>> \App\Models\AbsensiMahasiswa::latest()->first();
   - Verify: absensi_id, mahasiswa_id, status, keterangan, waktu_absensi

✅ Check: Double submission prevention
   - Try to submit again
   - Expected: Warning message, no duplicate record

✅ Check: Authorization
   - Only enrolled mahasiswa can submit to this class
   - Not enrolled mahasiswa gets 404

✅ Check: Session status requirement
   - Try to submit to DRAFT session → warning
   - Try to submit to TUTUP session → warning
   - Only BUKA sessions accept submission
```

### Feature 4: View Attendance History & Stats

**Route:** `mahasiswa.absensi.show`  
**File:** `mahasiswa/absensi/show.blade.php`, `MahasiswaAbsensiController@show`

```
✅ Check: Statistics calculation
   - Verify counts: hadir, izin, sakit, alpha
   - Alpha = total pertemuan - submitted attendance
   - Percentages calculated correctly
   - Progress bars show correct width (percentage-based)

✅ Check: Data display
   - All attendance records listed
   - Status badges color-coded correctly
   - Keterangan displays when present
   - Timestamps formatted correctly

✅ Check: Pagination
   - If > 10 records, pagination shows
   - Next/Prev buttons work

✅ Check: Empty state
   - If class has no attendance sessions, show helpful message
```

### Feature 5: Dosen Views Student List & Stats

**Route:** `dosen.absensi.show`  
**File:** `dosen/absensi/show.blade.php`, `DosenAbsensiController@show`

```
✅ Check: Statistics accuracy
   - Total = count of all mahasiswa in class
   - Hadir = count where status='hadir'
   - Izin = count where status='izin'
   - Sakit = count where status='sakit'
   - Alpha = total - (hadir+izin+sakit)

✅ Check: Student table display
   - All students show in table
   - Status badges correct colors
   - Waktu_absensi shows for submitted students, blank for alpha
   - Keterangan shows when present

✅ Check: Action buttons
   - "Koreksi Kehadiran" button opens form
   - "Edit" button opens edit form
   - "Hapus" button shows confirmation
   - "Export" button (may show "coming soon")
```

---

## COMMON TESTING MISTAKES TO AVOID

❌ **DON'T:** Test in production database (use test database)  
✅ **DO:** Use separate database or reset between tests

❌ **DON'T:** Clear browser cache during testing (may hide bugs)  
✅ **DO:** Keep cache and test actual user experience

❌ **DON'T:** Only test happy path  
✅ **DO:** Test error cases, edge cases, security

❌ **DON'T:** Test on one browser/device only  
✅ **DO:** Test Chrome, Firefox, Safari, mobile

❌ **DON'T:** Skip authorization tests  
✅ **DO:** Verify access control thoroughly

---

## DEBUGGING & TROUBLESHOOTING

### Issue: Session doesn't show active attendance entry page

**Diagnosis:**
```bash
# Check database
php artisan tinker
>>> \App\Models\Absensi::where('session_status','buka')->whereDate('tanggal', today())->get()
# Should return active session(s)
```

**Solution:**
- Verify session status is 'buka'
- Verify tanggal is today
- Check if mahasiswa is enrolled in class

### Issue: Double submission allowed

**Diagnosis:**
```bash
# Check for duplicate records
>>> \App\Models\AbsensiMahasiswa::where('absensi_id', $absensiId)->where('mahasiswa_id', $mahasiswaId)->count()
# Should be 1 or 0, never > 1
```

**Solution:**
- Verify `DB::transaction()` and `lockForUpdate()` in controller
- Check database transaction isolation level

### Issue: Statistics not matching

**Diagnosis:**
```bash
# Manual calculation
>>> $total = \App\Models\KelasPerkuliahan::find($kelasId)->mahasiswa->count();
>>> $submitted = \App\Models\AbsensiMahasiswa::where('absensi_id', $absensiId)->count();
>>> $alpha = $total - $submitted;
```

**Solution:**
- Query method should calculate alpha as count difference
- Not stored as 'alpha' in database

---

## SIGN-OFF CHECKLIST

Use this checklist to confirm all testing complete:

- [ ] Scenario 1 (Happy Path): ✅ PASS
- [ ] Scenario 2 (Dosen Correction): ✅ PASS
- [ ] Scenario 3 (Authorization): ✅ PASS
- [ ] Scenario 4 (Responsiveness): ✅ PASS
- [ ] Scenario 5 (Edge Cases): ✅ PASS
- [ ] All Features Tested (5 main features): ✅ PASS
- [ ] No UI Layout Breaks: ✅ PASS
- [ ] Database Consistency: ✅ PASS
- [ ] Error Handling: ✅ PASS
- [ ] Performance Acceptable (< 1 sec): ✅ PASS
- [ ] Mobile/Tablet/Desktop Responsive: ✅ PASS

**Overall Status:** ✅ **READY FOR PRODUCTION**

---

## POST-TESTING NEXT STEPS

If all tests pass:

1. ✅ Mark Task #5 "Test and verify all attendance workflows" as **COMPLETE**
2. Celebrate! 🎉 Full presensi system with modern UI is ready
3. Consider enhancements:
   - Add export to PDF feature
   - Add analytics dashboard
   - Add SMS notifications
   - Add QR code attendance
   - Add attendance API for mobile app
   - Performance optimization (caching)

---

**Testing Guide Version:** 1.0  
**Created:** July 10, 2026  
**Status:** Ready for execution
