# Dokumentasi Sistem Presensi

## Ringkasan Fitur

Sistem Presensi Cendekia adalah sistem manajemen kehadiran untuk perkuliahan dengan alur lengkap dari pembuatan sesi hingga pelaporan statistik kehadiran.

### Alur Proses

#### 1. **Dosen: Membuat & Mengelola Sesi Presensi**

**Flow:**
1. Dosen masuk ke menu "Presensi Kelas" → Pilih Kelas
2. Klik "Buat Sesi Baru"
3. Isi detail:
   - Pertemuan ke (1-16)
   - Tanggal
   - Jam Mulai & Jam Selesai
   - Ringkasan Materi (opsional)
   - Berita Acara (opsional)
   - Catatan (opsional)
4. Status default: **Draft**
5. Sesi berhasil dibuat, dosen dapat:
   - **Buka Sesi**: Mengubah status ke "Buka" agar mahasiswa dapat presensi
   - **Edit**: Mengubah detail sesi (hanya saat Draft)
   - **Edit Kehadiran Manual**: Menambah/mengubah status mahasiswa
   - **Tutup Sesi**: Mengubah status ke "Tutup" (presensi selesai)
   - **Hapus**: Menghapus sesi dan semua datanya

**Halaman Dosen:**
- **Index** (`/dosen/kelas/{id}/absensi`): List semua sesi dengan statistik
- **Create** (`/dosen/kelas/{id}/absensi/create`): Form buat sesi baru
- **Show** (`/dosen/kelas/{id}/absensi/{id}`): Detail sesi + tabel kehadiran
- **Edit** (`/dosen/kelas/{id}/absensi/{id}/edit`): Form edit sesi
- **Attendance** (`/dosen/kelas/{id}/absensi/{id}/attendance`): Edit manual kehadiran

---

#### 2. **Mahasiswa: Melakukan Presensi**

**Flow:**
1. Mahasiswa masuk ke menu "Presensi"
2. Pilih Kelas yang diambil
3. Jika ada sesi aktif (status "Buka"), form presensi muncul otomatis
4. Pilih status:
   - **Hadir** (default)
   - **Izin** (wajib isi keterangan)
   - **Sakit** (wajib isi keterangan)
5. Klik tombol presensi
6. Sistem validasi:
   - Sesi harus masih dibuka (status = "buka")
   - Tanggal sesi harus hari ini
   - Mahasiswa belum pernah presensi untuk sesi ini (unique constraint)
7. Presensi berhasil dicatat, mahasiswa dapat:
   - Lihat status yang baru disubmit
   - Lihat riwayat & statistik kehadiran

**Halaman Mahasiswa:**
- **Index** (`/mahasiswa/absensi`): List kelas dengan ringkasan kehadiran
- **Kelas Absensi** (`/mahasiswa/absensi/kelas/{id}/masuk`): Form & info sesi aktif
- **Show** (`/mahasiswa/absensi/{id}`): Riwayat detail + statistik

---

## Database Structure

### Tabel: `absensi`
```sql
- id (PK)
- kelas_perkuliahan_id (FK) - Relasi ke kelas
- pertemuan_ke (INT) - Nomor pertemuan (1-16)
- tanggal (DATE) - Tanggal pertemuan
- jam_mulai (TIME) - Jam mulai perkuliahan
- jam_selesai (TIME) - Jam selesai perkuliahan
- session_status (ENUM: 'draft', 'buka', 'tutup') - Status sesi
- rangkuman (TEXT) - Ringkasan materi
- berita_acara (TEXT) - Berita acara
- catatan (TEXT) - Catatan tambahan
- waktu_buka (TIMESTAMP) - Waktu sesi dibuka
- waktu_tutup (TIMESTAMP) - Waktu sesi ditutup
- created_at, updated_at (TIMESTAMP)
- Unique Index: (kelas_perkuliahan_id, pertemuan_ke)
```

### Tabel: `absensi_mahasiswa`
```sql
- id (PK)
- absensi_id (FK) - Relasi ke sesi absensi
- mahasiswa_id (FK) - Relasi ke user (mahasiswa)
- status (ENUM: 'hadir', 'izin', 'sakit', 'alpha') - Status kehadiran
- waktu_absensi (TIMESTAMP) - Waktu mahasiswa presensi
- keterangan (VARCHAR 255) - Alasan izin/sakit
- created_at, updated_at (TIMESTAMP)
- Unique Index: (absensi_id, mahasiswa_id)
```

---

## Models & Relationships

### Model: `Absensi`
```php
namespace App\Models;

class Absensi extends Model {
    // Relationships
    public function kelasPerkuliahan() { }
    public function absensiMahasiswa() { }

    // Methods
    public function isBuka(): bool { }
    public function isTutup(): bool { }
    public function isDraft(): bool { }
    public function bukaSession(): bool { }
    public function tutupSession(): bool { }
    public function getDurasi(): ?int { } // Durasi dalam menit

    // Scopes
    public function scopeBuka($query)
    public function scopeDraft($query)
    public function scopeTutup($query)
    public function scopeHariIni($query)
}
```

### Model: `AbsensiMahasiswa`
```php
namespace App\Models;

class AbsensiMahasiswa extends Model {
    // Relationships
    public function absensi() { }
    public function mahasiswa() { }

    // Methods
    public function isHadir(): bool { }
    public function isIzin(): bool { }
    public function isSakit(): bool { }
    public function isAlpha(): bool { }

    // Scopes
    public function scopeHadir($query)
    public function scopeIzin($query)
    public function scopeSakit($query)
    public function scopeAlpha($query)
}
```

---

## Controllers

### `DosenAbsensiController`

| Method | Route | Fungsi |
|--------|-------|--------|
| `index` | GET `/dosen/kelas/{id}/absensi` | List sesi presensi |
| `create` | GET `/dosen/kelas/{id}/absensi/create` | Form buat sesi |
| `store` | POST `/dosen/kelas/{id}/absensi` | Simpan sesi baru |
| `show` | GET `/dosen/kelas/{id}/absensi/{id}` | Detail sesi + daftar mahasiswa |
| `edit` | GET `/dosen/kelas/{id}/absensi/{id}/edit` | Form edit sesi |
| `update` | PUT `/dosen/kelas/{id}/absensi/{id}` | Update sesi |
| `bukaSession` | POST `/dosen/kelas/{id}/absensi/{id}/buka` | Buka sesi |
| `tutupSession` | POST `/dosen/kelas/{id}/absensi/{id}/tutup` | Tutup sesi |
| `editAttendance` | GET `/dosen/kelas/{id}/absensi/{id}/attendance` | Form edit kehadiran manual |
| `updateAttendance` | PUT `/dosen/kelas/{id}/absensi/{id}/attendance` | Update kehadiran manual |
| `destroy` | DELETE `/dosen/kelas/{id}/absensi/{id}` | Hapus sesi |
| `export` | GET `/dosen/kelas/{id}/absensi/{id}/export` | Export PDF (coming soon) |

### `MahasiswaAbsensiController`

| Method | Route | Fungsi |
|--------|-------|--------|
| `index` | GET `/mahasiswa/absensi` | List kelas + statistik |
| `kelasAbsensi` | GET `/mahasiswa/absensi/kelas/{id}/masuk` | Form presensi & info sesi |
| `absenMasuk` | POST `/mahasiswa/absensi/kelas/{id}/masuk/{id}` | Submit presensi |
| `show` | GET `/mahasiswa/absensi/{id}` | Riwayat & statistik detail |

---

## Authorization Policy

### `AbsensiPolicy`

**Permissions:**
- **view**: Admin, Dosen pengampu, Mahasiswa terdaftar kelas
- **checkIn**: Mahasiswa terdaftar + sesi status "buka"
- **viewHistory**: Mahasiswa terdaftar kelas
- **manage**: Admin, Dosen pengampu

---

## Views

### Dosen Views
```
resources/views/dosen/absensi/
├── index.blade.php          # List sesi + statistik
├── create.blade.php         # Form buat sesi
├── edit.blade.php           # Form edit sesi
├── show.blade.php           # Detail sesi + tabel kehadiran
└── attendance.blade.php     # Edit manual kehadiran
```

### Mahasiswa Views
```
resources/views/mahasiswa/absensi/
├── index.blade.php          # List kelas + kehadiran
├── kelas-absensi.blade.php  # Form presensi + info
└── show.blade.php           # Riwayat + statistik
```

---

## Fitur-Fitur Utama

### 1. **Session Status Management**
- **Draft**: Sesi baru, dosen bisa edit/delete sebelum dibuka
- **Buka**: Sesi aktif, mahasiswa bisa presensi
- **Tutup**: Sesi selesai, tidak bisa presensi lagi

### 2. **Status Kehadiran**
- **Hadir**: Mahasiswa hadir
- **Izin**: Mahasiswa izin (perlu keterangan)
- **Sakit**: Mahasiswa sakit (perlu keterangan)
- **Alpha**: Mahasiswa tidak hadir & tidak ada keterangan

### 3. **Statistik & Monitoring**
- Total mahasiswa per kelas
- Jumlah hadir/izin/sakit/alpha per sesi
- Persentase kehadiran per mahasiswa
- Timeline sesi (dibuat, dibuka, ditutup)
- Riwayat detail presensi per mahasiswa

### 4. **Validation & Security**
- Unique constraint: 1 mahasiswa = 1 presensi per sesi
- Unique constraint: 1 pertemuan = 1 sesi per kelas
- Lock for update untuk prevent double submit
- Authorization policy untuk akses control
- Timestamp untuk audit trail

### 5. **Email Notifications** (Optional)
- Notifikasi ke mahasiswa saat sesi dibuka
- Setting per-user di notification preferences

---

## Best Practices & Tips

### Untuk Dosen:
1. **Buat sesi terlebih dahulu**: Buat sesi dengan status Draft sebelum jam mulai
2. **Buka tepat waktu**: Buka sesi 5-10 menit sebelum kelas mulai
3. **Tutup tepat waktu**: Tutup sesi setelah waktu kelas selesai
4. **Edit manual jika perlu**: Koreksi data kehadiran di halaman Edit Kehadiran Manual
5. **Monitor statistik**: Cek persentase kehadiran per pertemuan

### Untuk Mahasiswa:
1. **Presensi saat sesi aktif**: Hanya bisa presensi saat sesi dibuka
2. **Hanya 1x per sesi**: Tidak bisa presensi 2x untuk sesi yang sama
3. **Isi keterangan untuk izin/sakit**: Wajib menjelaskan alasan
4. **Cek riwayat**: Monitor kehadiran di halaman Riwayat Presensi
5. **Komunikasi dengan dosen**: Jika ada masalah, hubungi dosen

---

## Troubleshooting

### Error: "Sesi presensi sudah dibuka"
- Sesi sudah dalam status "Buka", tidak bisa dibuka lagi

### Error: "Anda sudah melakukan presensi untuk sesi ini"
- Mahasiswa sudah presensi di sesi yang sama, tidak bisa presensi 2x

### Error: "Sesi presensi ini tidak dapat diakses"
- Sesi tidak dibuka atau bukan untuk hari ini

### Error: "Keterangan wajib diisi"
- Saat memilih status Izin/Sakit, harus isi keterangan

---

## Development Notes

- **Framework**: Laravel 11
- **Database**: MySQL 8
- **Frontend**: Blade + Tailwind CSS
- **Authentication**: Laravel Sanctum/Session
- **Authorization**: Laravel Policies
- **Validation**: Laravel Form Requests
- **Notifications**: Optional (via Mail/Database)

---

**Version**: 1.0  
**Last Updated**: July 14, 2026  
**Status**: Production Ready
