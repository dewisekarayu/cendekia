# 📅 ALUR INPUT JADWAL DI SISTEM LMS CENDEKIA

## 🎯 PRINSIP DATABASE YANG BENAR

Dalam arsitektur basis data sistem LMS/Portal Akademik yang baik, **Admin TIDAK mengatur jadwal langsung di tabel Mahasiswa atau Mata Kuliah**. Sebaliknya, jadwal diatur di **Tabel Relasi Tengah (Bridge/Pivot Table)** yang bernama **"Kelas Perkuliahan"** (juga dikenal sebagai "Course Offering" atau "Class Schedule").

---

## 📊 MENGAPA STRUKTUR INI?

### ❌ SALAH - Jadwal di Tabel Mata Kuliah

```
Tabel Mata Kuliah:
┌─────────────────────────────────────────┐
│ ID │ Kode | Nama MK | SKS | Hari | Jam │
├─────────────────────────────────────────┤
│ 1  │ MK01 │ Web... │ 3  │ Sel  │ 10:30
│ 1  │ MK01 │ Web... │ 3  │ Kam  │ 14:00  ← DUPLIKASI DATA!
└─────────────────────────────────────────┘
```

**Masalah:**
- Data statis (Kode, Nama, SKS) menjadi duplikat berkali-kali
- Setiap kelas/jadwal yang berbeda membuat record baru dengan data yang sama
- Tidak efisien dan sulit untuk update

### ❌ SALAH - Jadwal di Tabel Mahasiswa

```
Tabel Mahasiswa:
┌──────────────────────────────────────────┐
│ NIM │ Nama │ Hari | Jam  | Ruangan | MK  │
├──────────────────────────────────────────┤
│ 001 │ Alya │ Sel  │ 10:30│ Lab 1  │ MK01│
│ 001 │ Alya │ Kam  │ 14:00│ Lab 2  │ MK02│  ← REPEATING COLUMN!
│ 002 │ Budi │ Sel  │ 10:30│ Lab 1  │ MK01│  ← DUPLIKASI DATA!
└──────────────────────────────────────────┘
```

**Masalah:**
- Satu mahasiswa bisa ambil 5-8 MK, maka recordnya berlipat ganda
- Database membengkak tidak terkendali
- Anomali data (update anomaly, delete anomaly)
- Tidak normal form (bukan 3NF)

### ✅ BENAR - Jadwal di Tabel Kelas Perkuliahan

```
Tabel Mata Kuliah (Master - Statis):
┌──────────────────────────────────┐
│ ID │ Kode  │ Nama MK         │ SKS │
├──────────────────────────────────┤
│ 1  │ IF201 │ Pemrograman Web │ 3   │
│ 2  │ IF202 │ Basis Data      │ 3   │
└──────────────────────────────────┘

Tabel Kelas Perkuliahan (Bridge - Jadwal):
┌───────────────────────────────────────────────────────────┐
│ ID │ MK_ID │ Dosen_ID │ Kode | Hari │ Jam   │ Ruangan    │
├───────────────────────────────────────────────────────────┤
│ 1  │ 1     │ 5        │ IF201-A │ Sel  │ 10:30 │ Lab FIK 01 │
│ 2  │ 1     │ 6        │ IF201-B │ Kam  │ 14:00 │ Lab FIK 02 │
│ 3  │ 2     │ 7        │ IF202-A │ Sel  │ 13:00 │ Lab FIK 03 │
└───────────────────────────────────────────────────────────┘

Tabel Mahasiswa (Master - Personal Data):
┌─────────────────────────────────────┐
│ ID │ NIM │ Nama │ Email │ Program... │
├─────────────────────────────────────┤
│ 10 │ 001 │ Alya │ alya@│ TI        │
│ 11 │ 002 │ Budi │ budi@│ SI        │
└─────────────────────────────────────┘

Tabel Kelas_Mahasiswa (Enrollment - Relasi):
┌──────────────────────────────────┐
│ ID │ Kelas_ID │ Mahasiswa_ID │
├──────────────────────────────────┤
│ 1  │ 1        │ 10           │  ← Alya ambil IF201-A
│ 2  │ 1        │ 11           │  ← Budi ambil IF201-A
│ 3  │ 2        │ 10           │  ← Alya ambil IF201-B
└──────────────────────────────────┘
```

**Keuntungan:**
✅ Data master (MK) tidak redundan
✅ Data personal (Mahasiswa) tetap clean
✅ Jadwal terpisah di Kelas Perkuliahan
✅ Mahasiswa bisa ambil banyak kelas
✅ Satu kelas bisa punya banyak mahasiswa
✅ Mudah update & maintain
✅ Normal form (3NF)

---

## 🛠️ ALUR INPUT JADWAL DI ADMIN CENDEKIA

### STEP 1: Admin Masuk Menu "Kelas Perkuliahan"

Sidebar Admin → **Kelas Perkuliahan**

```
Dashboard
Program Studi
Mata Kuliah
├─► KELAS PERKULIAHAN ◄─── ADMIN DIMULAI DARI SINI!
Data Dosen
Data Mahasiswa
Pengumuman
Laporan
```

### STEP 2: Admin Klik Tombol "Buat Kelas Baru"

**URL:** `/admin/kelas/create`

### STEP 3: Admin Mengisi Form Berikut

#### Form Input Kelas Perkuliahan

```
┌─────────────────────────────────────────────┐
│    FORM BUAT KELAS PERKULIAHAN BARU         │
├─────────────────────────────────────────────┤
│                                             │
│ 1. PILIH MATA KULIAH                        │
│    [Dropdown]                               │
│    ├─ IF201 - Pemrograman Web (3 SKS)       │
│    ├─ IF202 - Basis Data (3 SKS)            │
│    └─ IF203 - Jaringan Komputer (3 SKS)     │
│                                             │
│ 2. PILIH DOSEN PENGAMPU                     │
│    [Dropdown]                               │
│    ├─ Ahmad Subagjo, S.Kom., M.T            │
│    ├─ Nadia Kurniasari, S.Kom., M.Kom       │
│    └─ Rizal Pratama, S.Kom., M.T            │
│                                             │
│ 3. PILIH SEMESTER                           │
│    [Dropdown]                               │
│    ├─ Ganjil 2025/2026 (Aktif)              │
│    └─ Genap 2025/2026                       │
│                                             │
│ 4. INPUT KODE KELAS                         │
│    [Text Input] ___________                 │
│    Contoh: IF201-A, IF201-B, IF201-C        │
│                                             │
│ 5. PILIH HARI MENGAJAR                      │
│    [Dropdown]                               │
│    ├─ Senin ├─ Rabu ├─ Jumat                │
│    ├─ Selasa├─ Kamis├─ Sabtu                │
│    └─ (Tidak ada bentrok jadwal dosen)      │
│                                             │
│ 6. INPUT JAM MULAI                          │
│    [Time Picker] 10:30                      │
│    Slot tersedia:                           │
│    ├─ 07:30 - 09:10                         │
│    ├─ 09:20 - 11:00                         │
│    ├─ 13:00 - 14:40                         │
│    └─ 14:50 - 16:30                         │
│                                             │
│ 7. INPUT JAM SELESAI                        │
│    [Time Picker] 11:00                      │
│    (HARUS lebih besar dari Jam Mulai)       │
│                                             │
│ 8. INPUT RUANGAN                            │
│    [Text Input] Lab FIK 01                  │
│    Contoh: Lab FIK 01, Kelas A101, etc      │
│                                             │
│ 9. CENTANG KELAS AKTIF                      │
│    [Checkbox] ☑ Kelas aktif                 │
│                                             │
│    [SIMPAN]  [BATAL]                        │
└─────────────────────────────────────────────┘
```

### STEP 4: Sistem Membuat Kelas & Jadwal

**Setelah diklik SIMPAN, sistem:**

1. ✅ Validasi semua field
2. ✅ Cek duplikasi jadwal dosen
3. ✅ Cek duplikasi kode kelas untuk MK tersebut
4. ✅ Simpan record ke tabel `kelas_perkuliahan`
5. ✅ Set status `is_active = true`
6. ✅ Redirect ke halaman list kelas

### STEP 5: Data Tersimpan di Database

```sql
INSERT INTO kelas_perkuliahan 
(mata_kuliah_id, dosen_id, semester_id, kode_kelas, hari, jam_mulai, jam_selesai, ruangan, is_active)
VALUES 
(1, 5, 1, 'IF201-A', 'Selasa', '10:30:00', '11:00:00', 'Lab FIK 01', 1);

Result:
┌────┬───────────────┬──────────┬──────────────┬──────────┬─────────┬──────────┐
│ ID │ mata_kuliah_id│ dosen_id │ kode_kelas   │ hari     │ jam_mulai
```

---

## 📌 SETELAH KELAS DIBUAT

### Bagaimana Jadwal Muncul di Mahasiswa?

Setelah Admin membuat **Kelas Perkuliahan** lengkap dengan jadwal, barulah mahasiswa bisa:

1. **Enroll ke Kelas** (melalui KRS/Kontrak Studi)
   - Mahasiswa memilih kelas mana saja yang ingin diambil
   - Sistem membuat record di tabel `kelas_mahasiswa`

2. **Lihat Schedule** (halaman mahasiswa/schedule)
   - Query: "Tampilkan semua kelas yang diikuti Mahasiswa X"
   - Ambil Hari, Jam, Ruangan dari tabel `kelas_perkuliahan`
   - Tampilkan di halaman Schedule

### Contoh Query Mahasiswa Lihat Jadwal

```sql
SELECT 
  k.kode_kelas,
  mk.nama_mk,
  k.hari,
  k.jam_mulai,
  k.jam_selesai,
  k.ruangan,
  d.name as dosen_pengampu
FROM kelas_perkuliahan k
JOIN mata_kuliah mk ON k.mata_kuliah_id = mk.id
JOIN users d ON k.dosen_id = d.id
JOIN kelas_mahasiswa km ON k.id = km.kelas_perkuliahan_id
WHERE km.mahasiswa_id = 10  -- ID Mahasiswa Alya
ORDER BY 
  CASE WHEN k.hari = 'Senin' THEN 1
       WHEN k.hari = 'Selasa' THEN 2
       WHEN k.hari = 'Rabu' THEN 3
       WHEN k.hari = 'Kamis' THEN 4
       WHEN k.hari = 'Jumat' THEN 5
       WHEN k.hari = 'Sabtu' THEN 6
  END,
  k.jam_mulai;
```

**Result untuk Mahasiswa Alya:**
```
┌────────────┬──────────────────┬────────┬──────────┬──────────┬─────────┬──────────────────┐
│ kode_kelas │ nama_mk           │ hari   │ jam_mul  │ jam_selesai│ruangan │ dosen_pengampu   │
├────────────┼──────────────────┼────────┼──────────┼──────────┼─────────┼──────────────────┤
│ IF201-A    │ Pemrograman Web   │ Selasa │ 10:30   │ 11:00   │ Lab 01 │ Ahmad Subagjo     │
│ IF202-A    │ Basis Data        │ Selasa │ 13:00   │ 14:40   │ Lab 02 │ Nadia Kurniasari  │
│ IF201-B    │ Pemrograman Web   │ Kamis  │ 14:00   │ 15:30   │ Lab 03 │ Rizal Pratama     │
└────────────┴──────────────────┴────────┴──────────┴──────────┴─────────┴──────────────────┘
```

---

## 🎓 RINGKASAN ALUR YANG BENAR

```
┌──────────────────────────────────────────────────────────────────┐
│                                                                  │
│  1. ADMIN INPUT DI MENU "KELAS PERKULIAHAN"                      │
│     ├─ Pilih MK (Pemrograman Web)                                │
│     ├─ Pilih Dosen (Ahmad Subagjo)                               │
│     ├─ Input Hari (Selasa)                                       │
│     ├─ Input Jam (10:30 - 11:00)                                 │
│     ├─ Input Ruangan (Lab FIK 01)                                │
│     └─ SIMPAN ✓                                                  │
│                                                                  │
│  2. SISTEM SIMPAN KE TABEL "kelas_perkuliahan"                   │
│     ├─ Record: IF201-A, Selasa, 10:30-11:00, Ahmad Subagjo      │
│     └─ is_active = TRUE                                          │
│                                                                  │
│  3. MAHASISWA ENROLL KE KELAS (dari KRS/Pendaftaran)             │
│     ├─ Alya: Daftar IF201-A                                      │
│     ├─ Budi: Daftar IF201-A                                      │
│     └─ Sistem simpan ke "kelas_mahasiswa"                        │
│                                                                  │
│  4. MAHASISWA LIHAT SCHEDULE (halaman Schedule)                  │
│     ├─ Query: "Kelas apa yang diikuti Alya?"                     │
│     ├─ Ambil data dari tabel kelas_perkuliahan                   │
│     └─ Tampilkan: Selasa 10:30 - Lab FIK 01 (Ahmad Subagjo)      │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

---

## ⚠️ YANG TIDAK DILAKUKAN

### ❌ Admin TIDAK input jadwal di sini:
- ✗ Menu "Data Mahasiswa" (untuk personal data saja)
- ✗ Menu "Mata Kuliah" (untuk master data saja)
- ✗ Halaman Edit Mahasiswa (untuk ubah nama, email, dll)

### ✅ Admin HANYA input jadwal di sini:
- ✓ Menu **"Kelas Perkuliahan"** (CREATE/UPDATE jadwal di sini)
- ✓ Form tambah/edit kelas (dengan semua field: MK, Dosen, Jadwal, Ruangan)

---

## 🔗 RELASI DATABASE YANG DIGUNAKAN

```
Tabel: program_studi
Tabel: mata_kuliah (FK: program_studi_id)
Tabel: semester
Tabel: kelas_perkuliahan (FK: mata_kuliah_id, dosen_id, semester_id)
         ↓
Tabel: kelas_mahasiswa (FK: kelas_perkuliahan_id, mahasiswa_id)
         ↓
Tabel: users (mahasiswa & dosen)
```

---

## 📝 DOKUMENTASI FILE

**Controller:** `app/Http/Controllers/Admin/KelasController.php`
- `create()` - Tampilkan form buat kelas
- `store()` - Proses simpan kelas baru
- `edit()` - Tampilkan form edit kelas
- `update()` - Proses update kelas
- `destroy()` - Hapus kelas

**Views:**
- `resources/views/admin/kelas/create.blade.php` - Form tambah
- `resources/views/admin/kelas/edit.blade.php` - Form edit
- `resources/views/admin/kelas/index.blade.php` - List kelas

**Routes:**
- `GET /admin/kelas` - List kelas (admin.kelas.index)
- `GET /admin/kelas/create` - Form buat (admin.kelas.create)
- `POST /admin/kelas` - Proses buat (admin.kelas.store)
- `GET /admin/kelas/{kelas}/edit` - Form edit (admin.kelas.edit)
- `PUT /admin/kelas/{kelas}` - Proses update (admin.kelas.update)
- `DELETE /admin/kelas/{kelas}` - Hapus (admin.kelas.destroy)

---

**✅ Alur input jadwal di sistem LMS Cendekia sudah benar dan mengikuti best practice database design!**


---

## 👥 TEAM TEACHING - MULTIPLE DOSEN PER MATERI

### Background

Dalam sistem LMS Cendekia, satu materi dapat diajarkan oleh multiple dosen (team teaching). Ini memberikan fleksibilitas untuk:

1. **Co-teaching:** Dua dosen mengajar materi yang sama dengan perspektif berbeda
2. **Specialist Input:** Dosen khusus mengisi bagian tertentu dari materi
3. **Peer Review:** Dosen lain melakukan validasi dan review materi
4. **Workload Distribution:** Membagi beban kerja pengajaran antar dosen

### Struktur Database

**Many-to-Many Relationship:**
```
users (dosen)
   ↓
dosen_materi (pivot table)
   ↓
materi
```

**Tabel `dosen_materi`:**
```sql
CREATE TABLE dosen_materi (
  id BIGINT PRIMARY KEY,
  materi_id BIGINT (FK to materi),
  dosen_id BIGINT (FK to users),
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE KEY (materi_id, dosen_id)  -- Cegah duplikasi
);
```

### Model Relationships

**Model Materi:**
```php
public function dosen()
{
    return $this->belongsToMany(User::class, 'dosen_materi', 'materi_id', 'dosen_id')
               ->withTimestamps();
}
```

**Model User (Dosen):**
```php
public function materi()
{
    return $this->belongsToMany(Materi::class, 'dosen_materi', 'dosen_id', 'materi_id')
               ->withTimestamps();
}
```

### Seeding Distribution

Dalam `ComprehensiveAcademicSeeder`, setiap materi di-assign dengan:
- **1-2 dosen per materi** (random)
- **Dosen dipilih dari dosen yang mengajar MK yang sama**
- **Distribution yang merata di semua materi**

**Result:**
```
Total Materi: 4,464
Total Dosen-Materi Relations: 6,700
Rata-rata Dosen per Materi: 1.5

Breakdown:
- 2,228 materi (49.9%) dengan 1 dosen
- 2,236 materi (50.1%) dengan 2 dosen (team teaching)
```

### Usage Examples

**Query: Cari semua dosen yang mengajar materi tertentu**
```php
$materi = Materi::find($materiId);
$dosenPengajar = $materi->dosen; // Collection of User models
```

**Query: Cari semua materi yang diajarkan dosen tertentu**
```php
$dosen = User::find($dosenId);
$materiYangDiajar = $dosen->materi; // Collection of Materi models
```

**Attach dosen ke materi (tambah relasi):**
```php
$materi->dosen()->attach($dosenId);
// atau sync jika sudah ada:
$materi->dosen()->sync([$dosenId1, $dosenId2]);
```

**Detach dosen dari materi (hapus relasi):**
```php
$materi->dosen()->detach($dosenId);
```

### Display di Views

**Tampilkan dosen pengajar di halaman materi:**
```blade
@foreach($materi->dosen as $dosen)
    <div class="badge badge-primary">{{ $dosen->name }}</div>
@endforeach
```

**Tampilkan di list materi (untuk dosen melihat materi yang diajarkan):**
```blade
@foreach($mahasiswa->kelasDiikuti as $kelas)
    @foreach($kelas->materis as $materi)
        <tr>
            <td>{{ $materi->judul }}</td>
            <td>
                @foreach($materi->dosen as $d)
                    <span class="badge">{{ $d->name }}</span>
                @endforeach
            </td>
        </tr>
    @endforeach
@endforeach
```

### Best Practices

1. **Jangan biarkan materi tanpa dosen**
   - Selalu assign minimal 1 dosen ke setiap materi

2. **Gunakan sync() untuk update multiple dosen**
   ```php
   $materi->dosen()->sync([$dosenA, $dosenB]); // Replace semua
   ```

3. **Cek relasi sebelum menampilkan**
   ```php
   @if($materi->dosen->isNotEmpty())
       Pengajar: {{ $materi->dosen->pluck('name')->join(', ') }}
   @endif
   ```

4. **Performance: Eager load relasi**
   ```php
   // BAIK - Menghindari N+1 query
   $materis = Materi::with('dosen')->get();
   
   // BURUK - N+1 query problem
   $materis = Materi::get();
   foreach ($materis as $m) {
       echo $m->dosen; // Query per materi!
   }
   ```

### Feature Roadmap

- [ ] Admin dashboard untuk manage dosen-materi assignments
- [ ] Form create/edit materi dengan multiple dosen selector
- [ ] Report: Workload distribution antar dosen
- [ ] Notification ketika materi di-assign ke dosen
- [ ] Validation: Cegah dosen dari prodi berbeda di-assign

---

✅ **Team Teaching Feature: COMPLETE**
