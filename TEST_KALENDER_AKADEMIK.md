# Test Plan: Kalender Akademik Mahasiswa

## Overview
Complete testing suite untuk fitur Kalender Akademik Mahasiswa. Meliputi:
- Calendar navigation dan filtering
- Event display dan sorting
- Responsive design
- Modal functionality
- Data accuracy

---

## Test Cases

### 1. Page Load & Initial State
- [ ] Page loads without errors
- [ ] Calendar displays current month/year
- [ ] All 20 category filters show in horizontal scroll
- [ ] All filters start as checked (blue)
- [ ] Events from seeded data appear in calendar
- [ ] Stats sidebar shows correct totals
- [ ] Today's date highlighted with blue background/border
- [ ] Weekend dates have rose/pink background
- [ ] Other month dates have gray background

**Expected Result**: Page loads fully with 142 events across 4 semesters visible on calendar grid

---

### 2. Calendar Navigation
#### Month Navigation
- [ ] Prev button changes month backward
- [ ] Next button changes month forward
- [ ] Year increments when going from Dec to Jan
- [ ] Year decrements when going from Jan to Dec
- [ ] URL updates with month/year params when navigating
- [ ] "Hari Ini" button jumps to current month/year
- [ ] Current month/year displays correctly in header

**Expected Result**: Month navigation works smoothly, URL changes correctly, "Hari Ini" jumps to today

#### Semester Filter
- [ ] Dropdown shows all semesters
- [ ] Selecting "Semua Semester" shows events from all semesters
- [ ] Selecting specific semester filters events to that semester only
- [ ] URL updates when semester changes
- [ ] Page reloads with filtered events

**Expected Result**: Semester dropdown filters work, events match selected semester

---

### 3. Category Filtering
#### Filter Toggle
- [ ] Unchecking filter hides events in that category
- [ ] Checking filter shows events in that category again
- [ ] Multiple filters can be unchecked/checked independently
- [ ] Filter changes apply instantly (no page reload)
- [ ] Selected filters turn blue, deselected turn gray
- [ ] Filter state persists across month/year changes

**Expected Result**: Category filtering works instantly, visual feedback correct

#### Stats Update
- [ ] Total Agenda count changes when filters change
- [ ] UTS & UAS count reflects only exams
- [ ] Libur & Cuti count reflects only holidays/leaves
- [ ] Deadline Tugas count reflects only deadlines
- [ ] Praktikum & Acara count reflects other events
- [ ] All stats update reactively

**Expected Result**: Stats always reflect filtered events accurately

---

### 4. Event Display
#### Calendar Grid Events
- [ ] Events show colored dot (matching category color)
- [ ] Event title displays (truncated if long)
- [ ] Event time shows (HH:MM format, if timed)
- [ ] Days with >2 events show "+N lagi" indicator
- [ ] Clicking "+N lagi" doesn't crash (future enhancement)
- [ ] All-day events display without time

**Expected Result**: Events display correctly with proper formatting

#### Today's Events Section
- [ ] Shows only events for today
- [ ] Events sorted (upcoming first, then past)
- [ ] Shows category badge (colored)
- [ ] Shows event title
- [ ] Shows time (if timed)
- [ ] Shows location icon (if has location)
- [ ] Shows description preview (line-clamp-2)
- [ ] No events message appears if none today

**Expected Result**: Today's section shows correct events in correct order

#### Upcoming Events Sidebar
- [ ] Shows 15 upcoming events (next 60 days)
- [ ] Events sorted by date ascending
- [ ] Shows category badge
- [ ] Shows event title
- [ ] Shows date (calendar icon)
- [ ] Shows time
- [ ] Shows location (if has location)
- [ ] "No upcoming events" message when none

**Expected Result**: Upcoming sidebar shows correct events in order

---

### 5. Event Detail Modal
#### Modal Display
- [ ] Clicking event opens modal
- [ ] Modal displays centered on screen
- [ ] Modal has semi-transparent backdrop (bg-black/50)
- [ ] Close button (X) appears in top right
- [ ] ESC key closes modal
- [ ] Modal body scrollable if content overflows

**Expected Result**: Modal opens and closes correctly

#### Modal Content
- [ ] Title shows in modal header (colored box with icon)
- [ ] Category badge shows below title
- [ ] "Sepanjang Hari" badge shows for all-day events
- [ ] Time badge shows for timed events
- [ ] Date range displays with calendar icon
- [ ] Date range shows "hingga" for multi-day events
- [ ] Time shows (if timed, not all-day)
- [ ] Location shows (if exists) with map icon
- [ ] Description shows with "Deskripsi" label
- [ ] Semester info shows with tahun_ajaran + nama_semester

**Expected Result**: Modal displays all event information correctly

#### Modal Styling
- [ ] Color scheme matches event category
- [ ] Text readable in light/dark mode
- [ ] Icons display correctly
- [ ] Padding/spacing consistent
- [ ] Long text doesn't overflow
- [ ] Modal responsive on mobile (max-h-[90vh])

**Expected Result**: Modal is visually polished and responsive

---

### 6. Event Sorting
#### Calendar Grid Sorting
- [ ] Events on same day sorted by time (ascending)
- [ ] All-day events appear first
- [ ] Timed events follow (by waktu_mulai)

**Expected Result**: Events sorted correctly on calendar grid

#### Today's Events Sorting
- [ ] Upcoming events (not started) appear first
- [ ] Past events (started) appear after
- [ ] Within each group, sorted by time ascending

**Expected Result**: Today's events prioritize upcoming

#### Multi-Day Events
- [ ] Events spanning multiple days appear on all days
- [ ] Event content (judul, warna, etc.) consistent across all dates
- [ ] Count includes event on all days in range

**Expected Result**: Multi-day events expand correctly to all dates

---

### 7. Responsive Design
#### Mobile (< 640px)
- [ ] Header font smaller, icon smaller
- [ ] Filter section: categories horizontal scroll with shortened labels
- [ ] Calendar cells: min-h-[80px], responsive padding
- [ ] Today's Events: responsive text sizes, padding compact
- [ ] Sidebar: stacks below calendar on very small screens
- [ ] Modal: padding reduced, responsive font sizes
- [ ] All buttons/controls accessible on small screens

**Expected Result**: Layout works well on mobile, no overflow/wrapping issues

#### Tablet (640px - 1024px)
- [ ] Layout balanced between mobile and desktop
- [ ] Calendar cells: medium height (90px range)
- [ ] Sidebar visible alongside calendar
- [ ] All text readable at default zoom

**Expected Result**: Tablet view responsive and readable

#### Desktop (> 1024px)
- [ ] Full layout with 3-col calendar + 1-col sidebar
- [ ] Comfortable padding/spacing
- [ ] Font sizes appropriate
- [ ] Sticky sidebar as scrolling

**Expected Result**: Desktop view polished and comfortable

---

### 8. Browser Console
- [ ] No errors in console
- [ ] Debug logs show event counts correctly
- [ ] No undefined variables
- [ ] No CSS warnings

**Expected Result**: Clean console, no errors

---

### 9. Dark Mode
- [ ] Colors visible in dark mode
- [ ] Text readable (contrast OK)
- [ ] Icons display
- [ ] Modal works in dark mode
- [ ] Badges readable

**Expected Result**: Dark mode works well

---

### 10. Data Accuracy
#### Event Counts
- [ ] 142 total events across all semesters
- [ ] Semester filter shows correct subset
- [ ] Category filter shows correct subset
- [ ] Combined filters (semester + categories) accurate

**Expected Result**: Event counts always match expectations

#### Date Logic
- [ ] Multi-day events span all dates correctly
- [ ] Date range query limits to current month display
- [ ] Today's events query accurate for current date
- [ ] Upcoming events query gets next 60 days
- [ ] History query gets past events

**Expected Result**: All date queries accurate

#### Event Fields
- [ ] All events have: judul, warna, jenis_kegiatan, tanggal_mulai
- [ ] All events should have: jenis_kegiatan_label (accessor)
- [ ] Timed events have: waktu_mulai, waktu_selesai, waktu_formatted
- [ ] All-day events: is_all_day = true
- [ ] Optional fields (lokasi, deskripsi, semester) display when present

**Expected Result**: Event data complete and accurate

---

## Performance Notes
- Events load from single query per month (efficient)
- Alpine.js filtering client-side (instant feedback)
- No page reloads for category filtering
- Modal uses DOM manipulation (not Ajax)

---

## Known Limitations (Acceptable)
- "+N lagi" indicator doesn't expand inline (by design, modal shows all)
- Category filter labels shortened on mobile (space constraint)
- Sidebar sticky only on desktop
- Modal backdrop doesn't prevent scroll (acceptable UX)

---

## Pass Criteria
✅ ALL test cases pass  
✅ No console errors  
✅ Responsive on mobile/tablet/desktop  
✅ Dark mode works  
✅ Performance acceptable  

---

## Test Date
Document created: 2026-07-15  
Last tested: [FILL IN]  
Tester: [FILL IN]  
Status: [FILL IN - PASS/FAIL]
