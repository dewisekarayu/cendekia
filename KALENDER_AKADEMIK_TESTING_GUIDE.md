# 🧪 Testing Guide - Kalender Akademik Admin

## Quick Start

### 1. Navigate to Admin Kalender Akademik
```
URL: http://localhost/admin/kalender-akademik
```

### 2. Click "Tambah Agenda" Button
- ✅ Button visible at top-right of page
- ✅ Blue gradient styling (from-blue-500 to-indigo-600)
- ✅ Plus icon next to text

### 3. Fill in the Form

#### Tab 1: Informasi Dasar
- **Semester**: Select "2024/2025 – Ganjil (Aktif)" or any active semester
- **Judul Agenda**: Type "UTS Semester Ganjil 2024"
- **Deskripsi**: Type "Ujian Tengah Semester untuk semua program studi"

#### Tab 2: Tanggal & Waktu
- **Tanggal Mulai**: Select date (e.g., 2024-12-02)
- **Tanggal Selesai**: Leave empty (single day) or select different date for multi-day
- **Sepanjang Hari**: Check this box (recommended)
- If unchecked, fill Waktu Mulai (08:00) and Waktu Selesai (10:00)

#### Tab 3: Kategori & Lokasi
- **Jenis Kegiatan**: Select "uts" or any category
- **Lokasi**: Type "Aula Besar Gedung A" (optional)
- **Catatan Tambahan**: Type any admin notes (optional)

#### Tab 4: Warna & Status
- **Warna Agenda**: Click color box or use preset buttons
- **Publikasikan Agenda**: Toggle ON (so mahasiswa can see it)

### 4. Submit Form
- Click "Buat Agenda" button
- Should redirect to index
- ✅ Green success message appears: "Agenda kalender akademik berhasil dibuat"
- ✅ New event appears in calendar

---

## Test Cases

### Test 1: Create Single-Day Event (All-day)
**Expected Result:** ✅
1. Fill form as above
2. Check "Sepanjang Hari" ✓
3. Leave Waktu fields empty
4. Submit
5. Event shows in calendar for that day

### Test 2: Create Multi-Day Event
**Expected Result:** ✅
1. Fill form
2. Check "Sepanjang Hari" ✓
3. **Tanggal Mulai:** 2024-12-02
4. **Tanggal Selesai:** 2024-12-04
5. Submit
6. Event appears on 2024-12-02, 2024-12-03, 2024-12-04

### Test 3: Create Event with Specific Time
**Expected Result:** ✅
1. Fill form
2. Uncheck "Sepanjang Hari" ⬜
3. **Waktu Mulai:** 08:00
4. **Waktu Selesai:** 10:00
5. Submit
6. Event shows with time badge "08:00 - 10:00"

### Test 4: Edit Agenda
**Expected Result:** ✅
1. Go back to calendar index
2. Click on an event in calendar
3. Click "Edit" button (or go to detail then edit)
4. Change title to "UTS Semester Ganjil (UPDATED)"
5. Change date
6. Submit
7. Redirect to index with message "Agenda kalender akademik berhasil diperbarui"
8. Calendar reflects changes

### Test 5: Delete Agenda
**Expected Result:** ✅
1. Go to event detail
2. Click red "Hapus" button
3. Confirmation modal appears with warning
4. Click "Hapus" in modal
5. Redirect to index with message "Agenda kalender akademik berhasil dihapus"
6. Event no longer in calendar

### Test 6: View Agenda in Admin Calendar
**Expected Result:** ✅
1. Index page shows calendar grid
2. Created event appears as colored badge on correct date
3. Can click badge to open detail modal
4. Modal shows full event info

### Test 7: View Sidebar Information
**Expected Result:** ✅
1. Index page right sidebar shows:
   - **Agenda Hari Ini** (if event is today)
   - **Agenda Mendatang** (events for next 30 days)
   - **Riwayat Agenda** (past events)
   - **Ringkasan Semester** (statistics)

### Test 8: View Activity Log
**Expected Result:** ✅
1. Go to event detail page
2. Scroll to "Riwayat Aktivitas" section
3. Shows:
   - Created by: [User Name]
   - Timestamp: [Date/Time]
   - Description: "Membuat agenda kalender akademik: [Title]"
4. If edited, shows update entry with old/new values

### Test 9: Filter by Semester
**Expected Result:** ✅
1. Index page, find semester selector
2. Select different semester
3. Calendar reloads with events for that semester
4. URL updates with `?semester_id=X`

### Test 10: Filter by Category
**Expected Result:** ✅
1. Index page, find category filter buttons
2. Click category name (e.g., "UTS")
3. Calendar shows only events for that category
4. Button highlights with different opacity

### Test 11: Success Message Display
**Expected Result:** ✅
1. After create/update/delete
2. Green alert appears at top of index page
3. Alert shows appropriate message
4. Alert auto-dismisses or can be closed

### Test 12: Responsive Design
**Expected Result:** ✅
- Desktop (1200px+): Full layout with sidebar
- Tablet (768px): Adjusted grid
- Mobile (320px): Stacked layout, buttons full-width

### Test 13: Mahasiswa Can See Published Agenda
**Expected Result:** ✅
1. Create agenda with "Publikasikan Agenda" ON
2. Login as mahasiswa
3. Go to Kalender Akademik
4. Event appears in mahasiswa's calendar
5. Mahasiswa can view but NOT edit/delete

### Test 14: Mahasiswa Cannot See Draft Agenda
**Expected Result:** ✅
1. Create agenda with "Publikasikan Agenda" OFF
2. Login as mahasiswa
3. Go to Kalender Akademik
4. Draft event does NOT appear

### Test 15: Validation Errors
**Expected Result:** ✅
1. Try to submit empty form
2. Error messages appear:
   - "Semester harus dipilih"
   - "Judul Agenda harus diisi"
   - "Tanggal Mulai harus dipilih"
   - etc.
3. Form stays on same page (no redirect)
4. Old input values preserved for correction

---

## Manual Verification

### Form Fields
- [ ] Semester dropdown populated
- [ ] Judul input accepts text
- [ ] Deskripsi textarea works
- [ ] Tanggal Mulai date picker works
- [ ] Tanggal Selesai date picker works
- [ ] Sepanjang Hari toggle shows/hides time inputs
- [ ] Waktu Mulai time picker works
- [ ] Waktu Selesai time picker works
- [ ] Jenis Kegiatan dropdown has 20+ options
- [ ] Lokasi input accepts text
- [ ] Catatan Tambahan textarea works
- [ ] Warna color picker works
- [ ] Preset warna buttons set color
- [ ] Publikasikan Agenda toggle works

### Calendar Display
- [ ] Calendar grid shows 7 days per week
- [ ] Month navigation works (prev/next)
- [ ] "Hari Ini" button goes to current month
- [ ] Event badges display with correct color
- [ ] Event badges show full text (truncate if long)
- [ ] Multiple events per day show count
- [ ] Multi-day events span correctly

### Buttons & Links
- [ ] "Tambah Agenda" button visible
- [ ] "Tambah Agenda" button clickable
- [ ] "Buat Agenda" submit button works
- [ ] "Perbarui Agenda" submit button works
- [ ] "Edit" button links to edit page
- [ ] "Hapus" button opens confirmation
- [ ] "Batal" buttons redirect to index

### Messages
- [ ] Success message on create
- [ ] Success message on update
- [ ] Success message on delete
- [ ] Error messages for validation
- [ ] No console errors (F12 → Console)

---

## Browser Testing

- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

---

## Performance Checks

- [ ] Index loads < 2 seconds
- [ ] Create form loads < 1 second
- [ ] Edit form loads < 1 second
- [ ] Form submit < 1 second
- [ ] Calendar renders smoothly
- [ ] No layout shifts after load

---

## Database Verification

```sql
-- Check table exists
SELECT * FROM information_schema.TABLES 
WHERE TABLE_NAME = 'kalender_akademik' 
AND TABLE_SCHEMA = DATABASE();

-- Check data was saved
SELECT COUNT(*) FROM kalender_akademik;

-- Check activity log
SELECT * FROM kalender_aktivitas_log 
ORDER BY occurred_at DESC 
LIMIT 10;

-- Check relationships
SELECT k.*, s.tahun_ajaran, u.name 
FROM kalender_akademik k
LEFT JOIN semesters s ON k.semester_id = s.id
LEFT JOIN users u ON k.created_by = u.id
LIMIT 5;
```

---

## Troubleshooting

### Issue: "Tambah Agenda" button not showing
**Solution:**
1. Check if logged in as admin: `auth()->check()` && `auth()->user()->isAdmin()`
2. Verify route exists: `route('admin.kalender-akademik.create')`
3. Clear browser cache: Ctrl+Shift+Delete
4. Hard refresh page: Ctrl+Shift+R

### Issue: Form won't submit
**Solution:**
1. Check browser console (F12) for JavaScript errors
2. Verify form has `@csrf` token
3. Check POST route is correct
4. Verify user is authenticated

### Issue: Data not showing in calendar
**Solution:**
1. Check if agenda is `is_published = true`
2. Check if dates are in current month view
3. Try navigating to different month and back
4. Check database: `SELECT * FROM kalender_akademik;`

### Issue: Multi-day event not showing on all days
**Solution:**
1. Check `tanggal_selesai` is set and >= `tanggal_mulai`
2. Verify dates are within current month view
3. Check controller loop logic in `index()` method
4. Verify `eventsByDate` array is populated correctly

---

## Success Indicators ✅

All tests should pass:
- [x] "Tambah Agenda" button visible and clickable
- [x] Create form displays and submits
- [x] Edit form pre-populates and updates
- [x] Delete works with confirmation
- [x] Calendar displays events correctly
- [x] Sidebar shows today/upcoming/history
- [x] Activity log tracks all changes
- [x] Success messages display
- [x] Data persists in database
- [x] Mahasiswa sees published events
- [x] Responsive on all devices
- [x] No console errors

---

## Support

For issues or questions, check:
1. **KALENDER_AKADEMIK_FIX_SUMMARY.md** - Full documentation
2. **Controller:** `app/Http/Controllers/Admin/KalenderAkademikController.php`
3. **Views:** `resources/views/admin/kalender-akademik/`
4. **Model:** `app/Models/KalenderAkademik.php`
5. **Routes:** `routes/web.php` (search "kalender-akademik")

---

**Last Updated:** 17 Juli 2026  
**Status:** ✅ READY FOR PRODUCTION TESTING
