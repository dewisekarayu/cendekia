# PRESENSI SYSTEM - QUICK START GUIDE

## 🚀 Get Started in 5 Minutes

### For DOSEN (Teacher)

**1. Create Attendance Session**
```
Dashboard → Kelas Saya → Select Kelas → Presensi Tab
→ "Buat Presensi Baru" → Fill Form → Submit
```
Status: **DRAFT** (green button says "DRAFT")

**2. Open Session for Attendance**
```
Session Detail → Click "BUKA SESI" button
→ Confirm → Session is now BUKA (green badge)
```
Mahasiswa will now see attendance entry page

**3. View Student Attendance**
```
Session Detail page shows:
- Student table with status badges (Hadir/Izin/Sakit/Alpha)
- Statistics cards (count for each status)
- Timestamps when students submitted
```

**4. Close Session**
```
Session Detail → Click "TUTUP SESI" button
→ Confirm → Session is now TUTUP (red badge)
```
Students can no longer submit attendance

**5. Manual Correction (Optional)**
```
Session Detail → Click "Koreksi Kehadiran"
→ Change student status → Save
```

---

### For MAHASISWA (Student)

**1. View Active Attendance**
```
Dashboard → Kelas Saya → Select Kelas → Presensi Tab
→ See active session (green alert)
```

**2. Submit Attendance**
```
Click large button: Hadir / Izin / Sakit
If Izin/Sakit: add reason in "Keterangan" field
Click "Kirim Presensi"
```
Success! You've recorded your attendance

**3. View Your History**
```
Dashboard → Presensi → Select Kelas
→ See attendance history with statistics
```
Shows: Total Sessions | Hadir % | Izin % | Sakit % | Alpha %

---

## 🎨 UI COLOR GUIDE

| Status | Color | Meaning |
|--------|-------|---------|
| **Hadir** | 🟢 Green | Present |
| **Izin** | 🔵 Blue | Excused absence |
| **Sakit** | 🟡 Amber | Sick leave |
| **Alpha** | 🔴 Red | Absent (no submission) |

---

## 📊 WORKFLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────┐
│                    DOSEN (Teacher)                       │
├─────────────────────────────────────────────────────────┤
│                                                           │
│  1. CREATE SESSION      2. OPEN SESSION                 │
│     (DRAFT status)         (BUKA status)                │
│          ↓                      ↓                        │
│     [Form]          [Email sent to mahasiswa]           │
│                                                           │
│  3. MAHASISWA SUBMIT ATTENDANCE (via separate flow)     │
│          ↓                                               │
│  4. VIEW STUDENT LIST & STATISTICS                       │
│     [Hadir | Izin | Sakit | Alpha counts]              │
│          ↓                                               │
│  5. OPTIONAL: MANUAL CORRECTION                         │
│     [Change individual student status]                  │
│          ↓                                               │
│  6. CLOSE SESSION (TUTUP status)                        │
│     [Students can no longer submit]                     │
│                                                           │
└─────────────────────────────────────────────────────────┘

        ┌──────────────────────────────┐
        │   MAHASISWA (Student)        │
        ├──────────────────────────────┤
        │                              │
        │  1. OPEN CLASS PRESENSI PAGE  │
        │     [See active session]     │
        │          ↓                   │
        │  2. SELECT STATUS            │
        │     [Hadir/Izin/Sakit]      │
        │     [Add reason if needed]   │
        │          ↓                   │
        │  3. SUBMIT ATTENDANCE        │
        │     [Success message]        │
        │          ↓                   │
        │  4. VIEW HISTORY & STATS     │
        │     [Riwayat Presensi]       │
        │     [Percentage progress]    │
        │                              │
        └──────────────────────────────┘
```

---

## ✅ QUICK CHECKLIST

### Before First Use
- [ ] Database migrations executed (`php artisan migrate`)
- [ ] Users created (dosen, mahasiswa)
- [ ] Mahasiswa enrolled in kelas
- [ ] Server running (`php artisan serve`)

### First Session (Dosen)
- [ ] Create attendance session
- [ ] Fill all required fields (pertemuan, tanggal, waktu)
- [ ] Open session
- [ ] Ask mahasiswa to check and submit

### First Attendance (Mahasiswa)
- [ ] Log in and go to kelas
- [ ] Click Presensi tab
- [ ] Click status button (Hadir/Izin/Sakit)
- [ ] Add reason if Izin/Sakit
- [ ] Click submit

### Verification (Dosen)
- [ ] View session detail page
- [ ] See all submitted attendances
- [ ] Check statistics are correct
- [ ] Close session when done

---

## 🔧 COMMON ACTIONS

### View All Sessions for a Class
```
Dosen → Kelas Saya → Select Kelas → Presensi Tab
Shows list of all sessions with dates and statuses
```

### Edit Existing Session
```
Session Detail → Click "Edit" button
Modify: pertemuan, tanggal, waktu, descriptions
Save
```

### Delete Session (With Confirmation)
```
Session Detail → Click "Hapus" button
Confirm deletion
All attendance records will be deleted
```

### Correct Single Student Status
```
Session Detail → Click "Koreksi Kehadiran"
Select new status from dropdown
Add reason if needed
Save
```

### Download Attendance Report (Future)
```
Session Detail → Click "Export" button
(Feature coming soon)
```

---

## 🆘 TROUBLESHOOTING

### "Sesi presensi tidak dapat diakses"
**Cause:** Session is TUTUP or not today's date  
**Fix:** Ask dosen to reopen session (if needed)

### "Anda sudah melakukan presensi untuk sesi ini"
**Cause:** You already submitted attendance  
**Fix:** You can't submit twice. Done! ✅

### "Sesi presensi sedang dibuka" (Alert, not error)
**Cause:** Normal - session is active  
**Fix:** Select status and submit attendance

### Session doesn't appear for mahasiswa
**Cause:** Session status is not BUKA or not today's date  
**Fix:** Dosen needs to open session (click "BUKA SESI")

### Wrong student in attendance list
**Cause:** May not be enrolled in class  
**Fix:** Check kelas enrollment list

---

## 📱 MOBILE TIPS

- Use portrait orientation for best view
- Tap status buttons carefully (they're large!)
- Scroll down to see full form if needed
- All features work on mobile

---

## 💡 BEST PRACTICES

### For Dosen
✅ Create session at start of class  
✅ Open session immediately after creating  
✅ Give students 5-10 minutes to submit  
✅ Close session when time is up  
✅ Review attendance list before closing  
✅ Correct errors manually if needed  

### For Mahasiswa
✅ Submit immediately when session opens  
✅ Use correct reason for Izin/Sakit  
✅ Check your attendance history regularly  
✅ Contact dosen if there's an error  

---

## 📞 STILL NEED HELP?

1. **Read detailed guides:**
   - PRESENSI_TESTING_GUIDE.md (step-by-step instructions)
   - PRESENSI_IMPLEMENTATION_SUMMARY.md (full documentation)

2. **Check specific section:**
   - PRESENSI_TESTING_CHECKLIST.md (what should happen)

3. **Contact support with:**
   - Clear description of the issue
   - Steps to reproduce
   - Screenshots if possible

---

## 🎉 YOU'RE READY!

The Presensi System is ready to use. Start with the Quick Checklist above and refer to detailed guides as needed.

Happy teaching & learning! 🚀

---

**Version:** 1.0  
**Created:** July 10, 2026  
**Updated:** July 10, 2026
