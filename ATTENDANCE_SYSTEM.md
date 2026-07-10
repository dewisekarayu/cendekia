# Sistem Presensi (Attendance System)

Dokumentasi lengkap sistem presensi/absensi untuk aplikasi portal akademik.

## Ringkasan

Sistem presensi dirancang untuk memfasilitasi proses absensi mahasiswa dengan alur yang jelas:
1. **Dosen** membuat sesi presensi untuk kelas yang diampu
2. **Dosen** membuka sesi agar mahasiswa dapat melakukan presensi
3. **Mahasiswa** melihat sesi aktif dan melakukan check-in
4. **Dosen** menutup sesi setelah waktu berakhir
5. **Dosen** dapat melihat daftar kehadiran dan mengedit status manual
6. **Admin** dapat mengelola semua presensi di semua kelas

## Alur Sistem

### 1. Dosen Membuat Sesi Presensi

**Route:** `POST /dosen/kelas/{kelasId}/absensi`

**Endpoint:** `Dosen\AbsensiController@store`

**Deskripsi:**
- Dosen masuk ke kelas yang diampu
- Membuat sesi presensi baru dengan:
  - Pertemuan ke (1-16)
  - Tanggal
  - Jam mulai & jam selesai
  - Ringkasan materi (opsional)
  - Berita acara (opsional)
  - Catatan (opsional)
- Status awal: **draft**
- Hanya dosen pengampu kelas yang bisa membuat

**Authorization:** `AbsensiPolicy@manage`

### 2. Dosen Membuka Sesi

**Route:** `POST /dosen/kelas/{kelasId}/absensi/{absensiId}/buka`

**Endpoint:** `Dosen\AbsensiController@bukaSession`

**Deskripsi:**
- Mengubah status dari `draft` → `buka`
- Set `waktu_buka` ke waktu sekarang
- Mahasiswa dapat melakukan presensi setelah ini

**Authorization:** `AbsensiPolicy@manage`

### 3. Mahasiswa Melihat Sesi Aktif

**Route:** `GET /mahasiswa/absensi/kelas/{kelasId}/masuk`

**Endpoint:** `Mahasiswa\AbsensiController@kelasAbsensi`

**Deskripsi:**
- Mahasiswa melihat sesi presensi aktif untuk hari ini
- Menampilkan:
  - Detail sesi (pertemuan, tanggal, waktu)
  - Status presensi mahasiswa (jika sudah/belum absen)
  - Riwayat presensi 5 pertemuan terakhir
- Hanya mahasiswa terdaftar di kelas yang bisa melihat

**Authorization:** `kelasDiikutiMahasiswa()` check

### 4. Mahasiswa Melakukan Check-in

**Route:** `POST /mahasiswa/absensi/kelas/{kelasId}/masuk/{absensiId}`

**Endpoint:** `Mahasiswa\AbsensiController@absenMasuk`

**Deskripsi:**
- Membuat record `AbsensiMahasiswa` dengan:
  - Status: `hadir`
  - Waktu absensi: sekarang
- Validasi:
  - Mahasiswa terdaftar di kelas
  - Sesi masih dalam status `buka`
  - Mahasiswa belum presensi untuk sesi ini

**Authorization:** `AbsensiPolicy@checkIn`

**Response:** Redirect dengan success message

### 5. Dosen Melihat Daftar Kehadiran

**Route:** `GET /dosen/kelas/{kelasId}/absensi/{absensiId}`

**Endpoint:** `Dosen\AbsensiController@show`

**Deskripsi:**
- Menampilkan detail sesi presensi:
  - Info sesi (pertemuan, tanggal, waktu, status)
  - Statistik kehadiran (total, hadir, izin/sakit, alpha)
  - Tabel daftar mahasiswa dengan:
    - Nama & NIM
    - Status (Hadir/Izin/Sakit/Alpha)
    - Waktu absensi
    - Keterangan

**Authorization:** `AbsensiPolicy@view`

### 6. Dosen Mengedit Kehadiran Manual

**Route:** `GET /dosen/kelas/{kelasId}/absensi/{absensiId}/attendance` (form)

**Route:** `PUT /dosen/kelas/{kelasId}/absensi/{absensiId}/attendance` (submit)

**Endpoint:** `Dosen\AbsensiController@editAttendance` & `updateAttendance`

**Deskripsi:**
- Form untuk edit status kehadiran per mahasiswa
- Pilihan status:
  - Hadir
  - Izin
  - Sakit
  - Alpha (tidak hadir)
- Bisa tambah keterangan per mahasiswa
- Hanya dosen pengampu yang bisa edit

**Authorization:** `AbsensiPolicy@manage`

### 7. Dosen Menutup Sesi

**Route:** `POST /dosen/kelas/{kelasId}/absensi/{absensiId}/tutup`

**Endpoint:** `Dosen\AbsensiController@tutupSession`

**Deskripsi:**
- Mengubah status dari `buka` → `tutup`
- Set `waktu_tutup` ke waktu sekarang
- Mahasiswa tidak bisa presensi lagi
- Mahasiswa belum presensi otomatis dianggap **alpha** (tidak hadir)

**Authorization:** `AbsensiPolicy@manage`

### 8. Dosen Menghapus Sesi

**Route:** `DELETE /dosen/kelas/{kelasId}/absensi/{absensiId}`

**Endpoint:** `Dosen\AbsensiController@destroy`

**Deskripsi:**
- Menghapus sesi presensi beserta seluruh data kehadiran
- Hanya dosen pengampu yang bisa hapus

**Authorization:** `AbsensiPolicy@manage`

### 9. Mahasiswa Melihat Riwayat Presensi

**Route:** `GET /mahasiswa/absensi/{kelasId}`

**Endpoint:** `Mahasiswa\AbsensiController@show`

**Deskripsi:**
- Menampilkan riwayat presensi mahasiswa untuk satu kelas
- Statistik:
  - Total pertemuan
  - Hadir
  - Izin
  - Sakit
  - Alpha
- Tabel detail presensi per pertemuan
- Progress bar kehadiran

**Authorization:** `AbsensiPolicy@viewHistory`

## Database Schema

### Tabel `absensi`

```sql
- id (primary key)
- kelas_perkuliahan_id (foreign key)
- pertemuan_ke (1-16)
- tanggal (date)
- jam_mulai (time)
- jam_selesai (time)
- session_status (enum: draft, buka, tutup)
- rangkuman (text, nullable)
- berita_acara (text, nullable)
- catatan (text, nullable)
- waktu_buka (datetime, nullable)
- waktu_tutup (datetime, nullable)
- created_at, updated_at
```

### Tabel `absensi_mahasiswa`

```sql
- id (primary key)
- absensi_id (foreign key)
- mahasiswa_id (foreign key to users)
- status (enum: hadir, izin, sakit, alpha)
- waktu_absensi (datetime, nullable)
- keterangan (text, nullable)
- created_at, updated_at
```

## Model Relationships

### Absensi Model

```php
- belongsTo(KelasPerkuliahan)
- hasMany(AbsensiMahasiswa)

Scopes:
- buka() → session_status = 'buka'
- draft() → session_status = 'draft'
- tutup() → session_status = 'tutup'
- hariIni() → tanggal = today()

Methods:
- isBuka() : bool
- isDraft() : bool
- isTutup() : bool
- bukaSession() : bool
- tutupSession() : bool
- getStatusLabel() : string
```

### AbsensiMahasiswa Model

```php
- belongsTo(Absensi)
- belongsTo(User, 'mahasiswa_id')

Scopes:
- hadir() → status = 'hadir'
- izin() → status = 'izin'
- sakit() → status = 'sakit'
- alpha() → status = 'alpha'

Methods:
- isHadir() : bool
- isIzin() : bool
- isSakit() : bool
- isAlpha() : bool
- getStatusLabel() : string
```

## Authorization & Policies

### AbsensiPolicy

```php
public function view(User $user, Absensi $absensi) : bool
  - Admin: true
  - Dosen: check if mengampu kelas
  - Mahasiswa: check if terdaftar di kelas

public function checkIn(User $user, Absensi $absensi) : bool
  - Hanya mahasiswa terdaftar & sesi harus buka

public function viewHistory(User $user, Absensi $absensi) : bool
  - Hanya mahasiswa terdaftar di kelas

public function manage(User $user, Absensi $absensi) : bool
  - Admin: true
  - Dosen: check if mengampu kelas
```

## Views

### Dosen Views

- `resources/views/dosen/absensi/index.blade.php` - Daftar sesi presensi
- `resources/views/dosen/absensi/create.blade.php` - Form buat sesi
- `resources/views/dosen/absensi/show.blade.php` - Detail sesi & daftar kehadiran
- `resources/views/dosen/absensi/edit.blade.php` - Form edit sesi
- `resources/views/dosen/absensi/attendance.blade.php` - Form edit kehadiran manual

### Mahasiswa Views

- `resources/views/mahasiswa/absensi/index.blade.php` - Daftar kelas dengan presensi
- `resources/views/mahasiswa/absensi/kelas-absensi.blade.php` - Presensi saat ini
- `resources/views/mahasiswa/absensi/show.blade.php` - Riwayat presensi

### Admin Views

- `resources/views/admin/absensi/index.blade.php` - Daftar semua presensi
- `resources/views/admin/absensi/create.blade.php` - Form buat presensi
- `resources/views/admin/absensi/show.blade.php` - Detail presensi
- `resources/views/admin/absensi/edit.blade.php` - Form edit presensi
- `resources/views/admin/absensi/attendance.blade.php` - Form edit kehadiran

### Integration in Other Views

- `resources/views/mahasiswa/kelas-detail.blade.php`
  - Tab "Absensi" menampilkan riwayat presensi
  - Tombol "Pencet Absen" link ke kelasAbsensi
  - Sidebar menampilkan progress kehadiran

## Controllers

### Dosen\AbsensiController

| Method | Route | Action |
|--------|-------|--------|
| index | GET /dosen/kelas/{kelasId}/absensi | Daftar sesi |
| create | GET /dosen/kelas/{kelasId}/absensi/create | Form buat |
| store | POST /dosen/kelas/{kelasId}/absensi | Simpan sesi |
| show | GET /dosen/kelas/{kelasId}/absensi/{absensiId} | Detail & attendance |
| edit | GET /dosen/kelas/{kelasId}/absensi/{absensiId}/edit | Form edit |
| update | PUT /dosen/kelas/{kelasId}/absensi/{absensiId} | Simpan perubahan |
| bukaSession | POST /dosen/kelas/{kelasId}/absensi/{absensiId}/buka | Buka sesi |
| tutupSession | POST /dosen/kelas/{kelasId}/absensi/{absensiId}/tutup | Tutup sesi |
| editAttendance | GET /dosen/kelas/{kelasId}/absensi/{absensiId}/attendance | Form edit kehadiran |
| updateAttendance | PUT /dosen/kelas/{kelasId}/absensi/{absensiId}/attendance | Simpan kehadiran |
| destroy | DELETE /dosen/kelas/{kelasId}/absensi/{absensiId} | Hapus sesi |
| export | GET /dosen/kelas/{kelasId}/absensi/{absensiId}/export | Export PDF (placeholder) |

### Mahasiswa\AbsensiController

| Method | Route | Action |
|--------|-------|--------|
| index | GET /mahasiswa/absensi | Daftar kelas |
| kelasAbsensi | GET /mahasiswa/absensi/kelas/{kelasId}/masuk | Presensi saat ini |
| absenMasuk | POST /mahasiswa/absensi/kelas/{kelasId}/masuk/{absensiId} | Check-in |
| show | GET /mahasiswa/absensi/{kelasId} | Riwayat |

### Admin\AbsensiController

Sama dengan Dosen, tapi tanpa filter `kelasId` (bisa lihat semua kelas).

## UI/UX Features

### Tailwind CSS Styling

- **Gradient backgrounds** untuk header & stat cards
- **Color-coded status badges** (draft=yellow, buka=green, tutup=red)
- **Animated pulse** untuk status aktif
- **Smooth transitions** & hover effects
- **Responsive grid layouts** untuk mobile
- **Avatar circles** dengan inisial nama
- **Progress bars** untuk kehadiran
- **Icons** untuk semua aksi

### Status Indicators

- **Draft** (kuning): Sesi belum dibuka
- **Dibuka** (hijau): Sesi aktif, mahasiswa bisa presensi
- **Ditutup** (merah): Sesi selesai, tidak bisa presensi
- **Hadir** (hijau): Mahasiswa hadir
- **Izin** (biru): Mahasiswa izin
- **Sakit** (kuning): Mahasiswa sakit
- **Alpha** (merah): Mahasiswa tidak hadir

## Testing Checklist

- [x] Syntax errors: All PHP files pass `php -l` check
- [x] Routes: All routes defined in `routes/web.php`
- [x] Controllers: All methods implemented with proper authorization
- [x] Models: Relationships & scopes working correctly
- [x] Policies: Authorization checks on every sensitive operation
- [x] Views: All templates styled with Tailwind
- [x] Integration: Presensi tab in kelas-detail
- [x] Database: Migrations for `absensi` & `absensi_mahasiswa` tables

## Future Enhancements

1. **Export PDF** - Dosen dapat export laporan kehadiran
2. **QR Code** - Check-in via QR code
3. **Geolocation** - Validasi lokasi saat presensi
4. **Notifications** - Email/push saat sesi dibuka
5. **Analytics** - Dashboard analytics kehadiran per mahasiswa/kelas
6. **Bulk Import** - Import presensi dari file Excel
7. **Mobile App** - Native mobile app untuk presensi
8. **Late Submission** - Toleransi keterlambatan absen

## Notes

- Mahasiswa yang tidak presensi saat sesi tutup otomatis "alpha"
- Hanya dosen pengampu kelas (utama atau team teaching) bisa manage presensi
- Admin bisa manage semua presensi di semua kelas
- Pertemuan tidak boleh duplikat per kelas
- Presensi dapat diedit kapan saja selama sesi masih ada

---

**Last Updated:** July 10, 2026
**Version:** 1.0
**Status:** Production Ready
