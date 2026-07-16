# Navigation Flow Map - Cendekia LMS

## Complete Navigation & Flow Structure

### 1. AUTHENTICATION & MAIN NAVIGATION
```
Login → [Choose Role]
├── Admin Dashboard
├── Dosen Dashboard  
└── Mahasiswa Dashboard
```

All users access the **Portal Layout** (`resources/views/layouts/portal.blade.php`) with:
- **Sidebar Navigation** (role-based menu)
- **Help Center Link** (accessible from all pages)
- **Theme Settings** (integrated in settings pages)

---

## 2. ROLE-BASED FLOWS

### 2.1 MAHASISWA (Student) Flow

#### Main Navigation
```
Mahasiswa Dashboard (mahasiswa.dashboard)
├── My Classes (mahasiswa.kelas-saya)
│   └── Class Detail (mahasiswa.kelas-detail) [ID-based route]
│       ├── Contextual Help (with dismissible option)
│       ├── Tab Navigation:
│       │   ├── Semua (Overview)
│       │   ├── Materi (Materials)
│       │   ├── Tugas (Assignments)
│       │   ├── Absensi (Attendance)
│       │   └── Forum (Discussion)
│       └── Help Button → Help Center
├── Schedule (mahasiswa.jadwal.*)
├── Attendance (mahasiswa.absensi.*)
├── Gradebook (mahasiswa.gradebook)
└── Settings (mahasiswa.setting)
    ├── Theme Selector (Light/Dark/Auto)
    ├── Notifications
    └── Profile
```

#### Detailed Routes
- **Dashboard**: `/mahasiswa/dashboard`
- **My Classes**: `/mahasiswa/kelas-saya`
- **Class Detail**: `/mahasiswa/kelas/{id}` → Shows:
  - Contextual help based on page section
  - Material list with file access
  - Assignment list with submission status
  - Attendance session tracking
  - Forum discussions
- **Attendance Mark**: `/mahasiswa/absensi/kelas/{kelasId}/masuk` → POST attendance
- **Settings**: `/mahasiswa/setting`
  - Theme update: POST `/mahasiswa/setting/umum`
  - Notifications: POST `/mahasiswa/setting/notifikasi`
  - Profile photo: PUT `/mahasiswa/setting/foto`

---

### 2.2 DOSEN (Teacher) Flow

#### Main Navigation
```
Dosen Dashboard (dosen.dashboard)
├── My Classes (dosen.kelas-saya)
│   └── Class Detail (dosen.kelas-detail) [ID-based route]
│       ├── Contextual Help
│       ├── Tab Navigation:
│       │   ├── Beranda (Overview)
│       │   ├── Absensi (Attendance Management)
│       │   ├── Materi (Material Management)
│       │   ├── Tugas (Assignment Management)
│       │   ├── Forum (Discussions)
│       │   └── Penilaian (Grade Management)
│       ├── Quick Actions:
│       │   ├── Add Material
│       │   ├── Create Assignment
│       │   ├── Input Grades
│       │   └── Manage Attendance
│       └── Help Button → Help Center
├── Announcements (dosen.kelas-pengumuman.*)
├── Gradebook (dosen.gradebook)
├── Schedule (dosen.schedule)
└── Settings (dosen.setting)
    ├── Theme Selector
    ├── Notifications
    └── Language
```

#### Detailed Routes
- **Dashboard**: `/dosen/dashboard`
- **My Classes**: `/dosen/kelas-saya`
- **Class Detail**: `/dosen/kelas/{id}` → Shows:
  - Class description & quick action buttons
  - Learning outcomes
  - Course info sidebar
- **Material Management**: `/dosen/kelas/{id}/materi`
- **Assignment Management**: `/dosen/kelas/{id}/tugas`
- **Grade Input**: `/dosen/gradebook`
- **Settings**: `/dosen/setting`
  - Theme update: POST `/dosen/setting/umum`
  - Notifications: POST `/dosen/setting/notifikasi`

---

### 2.3 ADMIN Flow

#### Main Navigation
```
Admin Dashboard (admin.dashboard)
├── Programs (admin.program-studi)
├── Courses (admin.mata-kuliah)
├── Teachers (admin.dosen)
├── Students (admin.mahasiswa)
├── Classes (admin.kelas)
├── Users (admin.user)
├── Announcements (admin.pengumuman)
├── Attendance (admin.absensi.*)
├── Notifications (admin.notification-preferences.*)
├── Help Center Management (admin.help-center.*)
│   ├── Dashboard (admin.help-center.dashboard)
│   ├── Tickets (admin.help-center.tickets)
│   ├── Ticket Detail (admin.help-center.ticket-detail)
│   └── Update Status (PUT admin.help-center.update-status)
└── Settings
    └── Theme (via Portal Layout)
```

#### Detailed Routes
- **Dashboard**: `/admin/dashboard`
- **Help Center Management**: `/admin/help-center/*`
  - View tickets: GET `/admin/help-center/tickets`
  - Ticket detail: GET `/admin/help-center/tickets/{id}`
  - Update status: PUT `/admin/help-center/tickets/{id}`
  - Close ticket: POST `/admin/help-center/tickets/{id}/close`

---

## 3. HELP CENTER FLOW (All Roles)

### Public & Authenticated Access
```
Help Center (help-center.*)
├── Main Page (help-center.index) → /help-center
│   ├── Search FAQ
│   ├── FAQ Accordion
│   └── Support Ticket Form (modal)
├── FAQ Search (help-center.search-faq) → /help-center/search-faq
├── FAQ Detail (help-center.faq-detail) → /help-center/faq/{faq}
├── Guides (help-center.guides) → /help-center/guides
├── Guide Detail (help-center.guide-detail) → /help-center/guides/{id}
└── Submit Ticket (help-center.store-ticket) → POST /help-center/ticket
```

### Components
- **Contextual Help** (`help-center.contextual-help`):
  - Displayed in class detail pages
  - Dismissible alert box with tips and links
  - Data provided by `HelpCenterHelper::getContextualHelp()`
  
- **FAQ System**:
  - Searchable accordion interface
  - Categories: login, tugas, absensi, forum, etc.
  - 26+ pre-loaded FAQ items
  
- **Support Tickets**:
  - Form submission in modal
  - Status tracking
  - Admin dashboard for management

---

## 4. SETTINGS & THEME INTEGRATION

### Theme Selector (All Roles)
- **Light Mode**: Light gray background, dark text
- **Dark Mode**: Dark blue/purple backgrounds, light text
- **Auto Mode**: Follows system preferences

### Color Scheme by Role (Dark Mode)
| Role | Sidebar Color | Active Item | Text Color |
|------|---------------|-------------|-----------|
| Admin | `#0f172a` (Dark Blue) | `#60a5fa` | `#f8fafc` |
| Dosen | `#1e1b4b` (Dark Purple) | `#c4b5fd` | `#f8fafc` |
| Mahasiswa | `#1e293b` (Slate) | `#60a5fa` | `#f8fafc` |

### Settings Routes
- **Mahasiswa**: `/mahasiswa/setting` + POST `/mahasiswa/setting/umum`
- **Dosen**: `/dosen/setting` + POST `/dosen/setting/umum`
- **Admin**: Theme via Portal Layout

---

## 5. ROUTE STRUCTURE & NAMING CONVENTIONS

### Route Groups & Middleware
```
[Public]
- Authentication routes (login, register, etc.)
- Help Center routes (help-center.*)

[Authenticated - Mahasiswa]
- Route group: middleware(['auth', 'role:mahasiswa'])
- Prefix: /mahasiswa
- Name prefix: mahasiswa.

[Authenticated - Dosen]
- Route group: middleware(['auth', 'role:dosen'])
- Prefix: /dosen
- Name prefix: dosen.

[Authenticated - Admin]
- Route group: middleware(['auth', 'role:admin'])
- Prefix: /admin
- Name prefix: admin.
```

### Naming Patterns
- **Index**: `{role}.{resource}.index` → GET `/{role}/{resource}`
- **Show**: `{role}.{resource}.show` → GET `/{role}/{resource}/{id}`
- **Create**: `{role}.{resource}.create` → GET `/{role}/{resource}/create`
- **Store**: `{role}.{resource}.store` → POST `/{role}/{resource}`
- **Edit**: `{role}.{resource}.edit` → GET `/{role}/{resource}/{id}/edit`
- **Update**: `{role}.{resource}.update` → PUT `/{role}/{resource}/{id}`
- **Destroy**: `{role}.{resource}.destroy` → DELETE `/{role}/{resource}/{id}`

---

## 6. KEY CONTROLLERS & DATA FLOW

### Help Center Integration
- **Controller**: `App\Http\Controllers\HelpCenterController`
- **Helper**: `App\Helpers\HelpCenterHelper`
- **Methods**:
  - `getContextualHelp($context, $action)` - Returns help data for views
  - `index()` - Main help page
  - `faqPage()` - FAQ listing page
  - `faqDetail()` - Individual FAQ detail
  - `storeTicket()` - Save support ticket

### Class Detail Controllers
- **Mahasiswa**: `App\Http\Controllers\Mahasiswa\KelasController@show()`
  - Passes: `$kelas`, `$materiList`, `$tugasList`, `$contextualHelp`, etc.
- **Dosen**: `App\Http\Controllers\Dosen\KelasController@show()`
  - Passes: `$kelas`, `$contextualHelp`

---

## 7. VERIFICATION CHECKLIST

✅ **Routing**
- All role-based routes properly registered
- Help Center routes accessible
- Setting routes with theme update endpoints

✅ **Navigation**
- Sidebar menu appears for all authenticated users
- Help Center link in sidebar
- Settings links in sidebar

✅ **Help Integration**
- Contextual help displays in class detail pages
- Dismissible help alerts working
- Help Center search and FAQ functional

✅ **Theme System**
- Theme selector (3-card UI) in settings
- Live preview working
- localStorage persistence
- System theme listener for auto mode
- Dark mode colors applied correctly by role

✅ **Flow Connections**
- Dashboard → Class Detail → Help Center
- Class Detail → Tabs → Settings
- Settings → Theme Selection → Sidebar Updates
- All dark mode components styled correctly

---

## 8. ACCESSIBILITY & DARK MODE

### Dark Mode Implementation
- **Tailwind Dark Mode**: Enabled via `dark:` utilities
- **JS Toggle**: Theme selector sets `dark` class on `<html>`
- **Persistence**: localStorage + server-side user.theme column
- **System Preference**: Auto mode listens to `prefers-color-scheme`

### CSS Architecture
- **Global Utilities**: `resources/css/app.css`
- **@layer components**: Button, card, input styles
- **@layer utilities**: Dark mode color utilities
- **Inline dark:** Dark mode specific classes on elements

---

## 9. FILE STRUCTURE

```
resources/views/
├── layouts/
│   └── portal.blade.php          # Main layout with sidebar
├── help-center/
│   ├── index.blade.php           # Main help page
│   ├── faq.blade.php             # FAQ page
│   ├── contextual-help.blade.php # Help component
│   └── faq-detail.blade.php      # Individual FAQ
├── mahasiswa/
│   ├── kelas-detail.blade.php    # Student class detail
│   ├── setting.blade.php         # Theme & settings selector
│   ├── dashboard.blade.php
│   └── ...
├── dosen/
│   ├── kelas-detail.blade.php    # Teacher class detail
│   ├── setting.blade.php
│   ├── dashboard.blade.php
│   └── ...
└── admin/
    ├── dashboard.blade.php
    └── ...

app/Http/Controllers/
├── HelpCenterController.php      # Help center logic
├── Mahasiswa/
│   ├── KelasController.php       # Shows class + contextual help
│   └── SettingController.php     # Theme management
├── Dosen/
│   ├── KelasController.php
│   └── SettingController.php
└── Admin/
    └── HelpCenterController.php  # Admin ticket management

app/Helpers/
└── HelpCenterHelper.php          # Context-based help data

routes/
└── web.php                       # All route definitions
```

---

## 10. USER JOURNEY EXAMPLES

### Example 1: Mahasiswa Accessing Help
1. Opens browser → Cendekia LMS
2. Authenticates as Mahasiswa
3. Sees Dashboard with Sidebar
4. Clicks "Help Center" link in sidebar
5. Lands on Help Center main page
6. Searches FAQ or fills support ticket
7. Returns to dashboard via back button or sidebar

### Example 2: Mahasiswa with Class Help
1. Navigates to "My Classes"
2. Clicks on a specific class
3. Sees **Contextual Help** box explaining tabs
4. Tabs include: Semua, Materi, Tugas, Absensi, Forum
5. Help box has link to full Help Center
6. User can dismiss help box with X button
7. Returns to previous class list with back button

### Example 3: Theme Selection Flow
1. User in Dashboard
2. Clicks "Settings" in sidebar
3. Navigates to Setting page
4. Sees 3-card Theme Selector (Light/Dark/Auto)
5. Clicks "Dark" card
6. **Live Preview**: Page immediately turns dark
7. Clicks "Save"
8. Theme persists across sessions
9. Sidebar colors change based on role + theme

---

## Summary

The Cendekia LMS navigation is **clean, hierarchical, and well-connected**:
- ✅ All flows properly routed
- ✅ Help system integrated contextually
- ✅ Theme system persistent and responsive
- ✅ Sidebar navigation accessible from all pages
- ✅ Role-based access control enforced
- ✅ Dark mode properly implemented across all UI

**Status**: Production Ready ✨
