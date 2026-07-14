# 📋 Panduan Lengkap Sistem Presensi Cendekia

## 🎯 Ringkasan Fitur

Sistem presensi Cendekia adalah platform manajemen kehadiran modern untuk institusi pendidikan dengan fitur:

- ✅ **Untuk Dosen:** Buat sesi presensi, buka/tutup sesi, edit manual kehadiran, lihat statistik
- ✅ **Untuk Mahasiswa:** Presensi dengan status (Hadir/Izin/Sakit), riwayat presensi, statistik kehadiran
- ✅ **Notifikasi Email:** Otomatis notifikasi ke mahasiswa saat sesi dibuka
- ✅ **UI Modern:** Interface responsive dengan Tailwind CSS dan Alpine.js
- ✅ **Keamanan:** Authorization policy, role-based access control
- ✅ **Data Export:** Export presensi ke Excel/CSV

---

## 👨‍🏫 Panduan Penggunaan untuk DOSEN

### 1. Menu Presensi

#### Akses Menu
- Login sebagai Dosen
- Di sidebar, pilih **Kelas** → pilih kelas yang diampuh
- Klik tab **Presensi** atau menu **Presensi Kelas**

#### Dashboard Presensi
Menampilkan:
- **Statistik:** Total sesi, Draft, Aktif, Ditutup
- **Tabel Daftar Sesi:** Semua sesi presensi untuk kelas
- **Tombol Aksi:** Lihat, Buka, Tutup, Edit, Hapus per sesi

---

### 2. Membuat Sesi Presensi Baru

#### Langkah Demi Langkah

**Step 1:** Klik tombol **"Buat Sesi Baru"** (warna biru di dashboard)

**Step 2:** Isi form berikut:
```
┌─ Informasi Sesi ─────────────────────────┐
│                                          │
│ Pertemuan Ke*    : 1 (wajib diisi)       │
│ Tanggal*         : 2026-07-14 (hari ini) │
│ Jam Mulai*       : 08:00 (format 24jam) │
│ Jam Selesai*     : 10:00 (format 24jam) │
│                                          │
│ Ringkasan Materi : [opsional]            │
│ Berita Acara     : [opsional]            │
│ Catatan Tambahan : [opsional]            │
│                                          │
│ [RESET]        [BUAT SESI PRESENSI]     │
└──────────────────────────────────────────┘
```

**Step 3:** Klik **"Buat Sesi Presensi"**

**Status Awal:** Sesi dibuat dengan status **DRAFT** (belum aktif)

---

### 3. Membuka Sesi Presensi

#### Di Dashboard
1. Lihat tabel daftar sesi
2. Cari sesi dengan status "Draft"
3. Klik tombol **"Buka"** (warna hijau)
4. Konfirmasi pop-up

**Hasil:**
- Status berubah menjadi **BUKA** (lampu hijau berkedip)
- Email notifikasi otomatis dikirim ke semua mahasiswa
- Mahasiswa dapat membuka menu Presensi dan mulai presensi

#### Di Halaman Detail
1. Klik tombol **"Lihat"** untuk masuk ke detail sesi
2. Di sidebar sebelah kanan, klik **"Buka Sesi Presensi"** (warna hijau)
3. Konfirmasi

---

### 4. Menutup Sesi Presensi

#### Saat Waktu Habis
1. Di dashboard atau detail sesi
2. Klik tombol **"Tutup"** (warna kuning)
3. Konfirmasi pop-up

**Hasil:**
- Status berubah menjadi **TUTUP** (warna merah)
- Mahasiswa tidak bisa presensi lagi
- Data presensi menjadi final

---

### 5. Melihat Detail Sesi & Daftar Kehadiran

#### Buka Halaman Detail
1. Di dashboard, klik tombol **"Lihat"** pada sesi yang ingin dilihat
2. Atau akses langsung: `/dosen/kelas/{id}/absensi/{id}`

#### Informasi yang Ditampilkan

**Kartu Informasi Sesi:**
- Status sesi (Draft/Buka/Tutup)
- Pertemuan ke berapa
- Tanggal dan waktu
- Ringkasan materi, berita acara, catatan

**Statistik Kehadiran (4 Kartu):**
- 🔵 **Total Mahasiswa:** 45 orang
- 🟢 **Hadir:** 40 orang
- 🟡 **Izin/Sakit:** 4 orang  
- 🔴 **Alpha:** 1 orang

**Chart Statistik:**
- Progress bar persentase Hadir/Izin/Alpha
- Visual pie chart summary

**Tabel Daftar Mahasiswa:**
Kolom: No, Nama, NIM, Status, Waktu Absensi, Keterangan

| No | Nama | NIM | Status | Waktu | Ket |
|----|------|-----|--------|-------|-----|
| 1 | Ahmad | 001 | Hadir | 08:05 | — |
| 2 | Budi | 002 | Sakit | — | Demam |
| 3 | Citra | 003 | Alpha | — | — |

---

### 6. Edit Manual Kehadiran

#### Akses Menu
1. Di halaman detail sesi, klik **"Edit Kehadiran Manual"** (tombol biru di sidebar)
2. Atau dari dashboard sesi, klik **"Edit"** → menu dropdown

#### Halaman Edit Kehadiran
Menampilkan form untuk setiap mahasiswa:

```
┌─ Mahasiswa #1: Ahmad ──────────────────┐
│                                        │
│ Status:     [Hadir ▼]                  │
│ Keterangan: [textbox]                  │
│ Waktu:      [datetime picker]          │
│                                        │
│ [SIMPAN PERUBAHAN]                     │
└────────────────────────────────────────┘
```

**Gunakan untuk:**
- Mengubah status jika mahasiswa pre sensi terlambat
- Menambah keterangan izin/sakit yang belum ada
- Memperbaiki data jika ada kesalahan

---

### 7. Mengedit Informasi Sesi

#### Akses Menu
1. Di halaman detail sesi
2. Di dashboard, klik **"Edit"** pada sesi
3. Atau dari detail sesi, klik dropdown **"Aksi"** → **"Edit Presensi"**

#### Form Edit
Form sama seperti saat membuat sesi:
- Pertemuan, Tanggal, Jam Mulai, Jam Selesai
- Ringkasan, Berita Acara, Catatan

**Catatan:** Tidak bisa mengubah status sesi di sini. Gunakan tombol Buka/Tutup.

---

### 8. Menghapus Sesi Presensi

#### Langkah Demi Langkah
1. Di dashboard presensi
2. Klik tombol **"Hapus"** (warna merah) pada sesi yang ingin dihapus
3. Konfirmasi pop-up: "Yakin ingin menghapus sesi ini?"
4. Klik **"Hapus"** untuk konfirmasi

⚠️ **Peringatan:** Penghapusan bersifat permanen dan tidak bisa dibatalkan!

---

### 9. Export Daftar Kehadiran

#### Akses Menu
1. Di halaman detail sesi
2. Klik dropdown **"Aksi"** → **"Export"**
3. File akan diunduh otomatis

#### Format Export
- Format: Excel (.xlsx) atau CSV
- Kolom: No, Nama, NIM, Status, Waktu Absensi, Keterangan
- Sudah terformat dengan warna per status

---

## 👨‍🎓 Panduan Penggunaan untuk MAHASISWA

### 1. Menu Presensi

#### Akses Menu
- Login sebagai Mahasiswa
- Di sidebar, pilih **Presensi**
- Atau akses langsung: `/mahasiswa/absensi`

#### Dashboard Presensi
Menampilkan:
- **Kartu Kelas:** Semua kelas yang terdaftar
- Per kartu menampilkan:
  - Nama mata kuliah
  - Nama dosen pengajar
  - Persentase kehadiran
  - Tombol "Presensi" (hijau) dan "Riwayat" (abu-abu)

---

### 2. Melakukan Presensi

#### Langkah Demi Langkah

**Step 1:** Di dashboard presensi atau detail kelas
- Klik tombol **"Presensi"** (warna hijau)

**Step 2:** Masuk halaman presensi kelas
```
┌─ Sesi Presensi Aktif ──────────────────┐
│                                        │
│ 🟢 Sesi Presensi Aktif                 │
│                                        │
│ Pertemuan: 5                           │
│ Tanggal:   14 Jul                      │
│ Mulai:     08:00  Selesai: 10:00       │
│                                        │
│ ✓ Anda Sudah Presensi                  │
│   Status: HADIR                        │
│   Waktu: 08:05:32                      │
│                                        │
└────────────────────────────────────────┘
```

**Step 3:** Pilih status kehadiran (3 pilihan dengan radio button)
```
┌────────────┬────────────┬────────────┐
│  ✓ HADIR   │  ⬜ IZIN   │ ⬜ SAKIT   │
├────────────┼────────────┼────────────┤
│  Standar   │  Ada kes.  │  Sedang    │
│  kehadiran │  penting   │  sakit     │
└────────────┴────────────┴────────────┘
```

**Step 4:** Jika pilih IZIN atau SAKIT, wajib isi alasan
```
┌─ Keterangan ──────────────────────────┐
│                                       │
│ Jelaskan alasan izin/sakit:           │
│ [textbox multibaris]                  │
│ Contoh: Ada keperluan keluarga        │
│                                       │
└───────────────────────────────────────┘
```

**Step 5:** Klik tombol **"PRESENSI / ABSEN MASUK"** (warna sesuai status)
- Tombol hijau jika "Hadir"
- Tombol biru jika "Izin"
- Tombol kuning jika "Sakit"

**Hasil:**
- Halaman refresh
- Tampil pesan sukses: "Presensi berhasil dicatat"
- Status berubah menjadi "Anda Sudah Presensi"
- Waktu absensi ditampilkan

---

### 3. Jika Tidak Ada Sesi Aktif

```
┌─────────────────────────────────────┐
│   ⏰ Tidak Ada Sesi Presensi Aktif   │
│                                     │
│ Sesi presensi belum dibuka oleh      │
│ dosen untuk hari ini.                │
│ Silakan cek kembali nanti.           │
└─────────────────────────────────────┘
```

**Solusi:** Tunggu dosen membuka sesi presensi

---

### 4. Melihat Riwayat Presensi

#### Akses Menu
1. Di dashboard presensi, klik tombol **"Riwayat"** (warna abu-abu)
2. Atau klik tab **"Riwayat Presensi"** di halaman detail kelas

#### Informasi yang Ditampilkan

**Statistik (4 Kartu):**
- 📅 Total Pertemuan: 10
- ✅ Hadir: 9
- ⚠️ Izin/Sakit: 1
- ❌ Alpha: 0

**Grafik Persentase:**
- Progress bar Hadir: 90%
- Progress bar Izin/Sakit: 10%
- Progress bar Alpha: 0%

**Tabel Riwayat Detail:**

| Pertemuan | Tanggal | Waktu | Status | Waktu Absensi |
|-----------|---------|-------|--------|---------------|
| 1 | 30 Jun 2026 | 08:00-10:00 | Hadir | 08:05:10 |
| 2 | 07 Jul 2026 | 08:00-10:00 | Hadir | 08:03:45 |
| 3 | 14 Jul 2026 | 08:00-10:00 | Sakit | — |
| 4 | 21 Jul 2026 | 08:00-10:00 | Alpha | — |

---

### 5. Keterangan Status Kehadiran

| Status | Arti | Simbol | Warna |
|--------|------|--------|-------|
| **Hadir** | Siswa hadir di kelas | ✓ | 🟢 Hijau |
| **Izin** | Ada keperluan, tidak bisa hadir | 📄 | 🔵 Biru |
| **Sakit** | Sedang tidak sehat | 🏥 | 🟡 Kuning |
| **Alpha** | Absen tanpa keterangan | ✗ | 🔴 Merah |

---

### 6. Tips Presensi

✓ **Presensi hanya saat sesi dibuka** - Tunggu notifikasi email atau cek menu
✓ **Satu kali presensi per sesi** - Tidak bisa presensi dua kali
✓ **Status default: Hadir** - Jika tidak ada keterangan, status otomatis "Hadir"
✓ **Cek riwayat berkala** - Pastikan presensi sudah tercatat dengan benar
✓ **Jika ada kendala** - Hubungi dosen atau admin

---

## 📧 Notifikasi Email

### Kapan Email Dikirim?

Email otomatis dikirim ke semua mahasiswa di kelas saat **dosen membuka sesi presensi**.

### Isi Email

```
Dari: noreply@cendekia.local
Ke: mahasiswa@gmail.com
Subjek: ⏰ Sesi Presensi Dibuka - Pertemuan 5

Konten:
─────────────────────────────────────────

Halo Muhammad Ridho Pratama,

Sesi presensi untuk kelas "Algoritma dan Pemrograman (IF-201-A)" 
telah dibuka!

📋 Detail Sesi:
- Pengajar: Prof. Dr. Ahmad Subagjo, M.Kom
- Pertemuan: Ke-5
- Tanggal: 14 Juli 2026
- Waktu: 08:00 - 10:00
- Lokasi: Ruang 205

Silakan masuk ke aplikasi Cendekia dan lakukan presensi sebelum 
sesi ditutup oleh dosen.

🔗 Buka Aplikasi:
https://cendekia.local/mahasiswa/absensi

Jangan lupa:
✓ Presensi sebelum sesi ditutup
✓ Pilih status yang sesuai (Hadir/Izin/Sakit)
✓ Jika izin/sakit, jelaskan alasannya

────────────────────────────────────────

Salam,
Sistem Cendekia
```

### Jika Email Tidak Masuk

**Solusi:**
1. Cek folder **Spam/Junk** email Anda
2. Hubungi admin jika email valid tapi tidak terima
3. Gunakan menu Presensi manual di aplikasi

---

## 🔐 Keamanan & Otorisasi

### Dosen

✅ **Dapat membuat sesi** hanya untuk kelas yang diampu
✅ **Dapat membuka/tutup** sesi sendiri
✅ **Dapat edit manual** kehadiran mahasiswa di kelasnya
✅ **Tidak dapat** melihat presensi di kelas yang tidak diampu

### Mahasiswa

✅ **Dapat presensi** hanya untuk kelas yang terdaftar
✅ **Dapat melihat** riwayat presensi pribadi
✅ **Tidak dapat** melihat presensi mahasiswa lain
✅ **Tidak dapat** mengubah presensi setelah sesi ditutup

### Admin

✅ **Dapat mengakses semua** fitur presensi
✅ **Dapat membuat sesi** untuk semua kelas
✅ **Dapat melihat** presensi di semua kelas

---

## 📊 Database Schema

### Tabel: `absensi`
```sql
id                  INTEGER PRIMARY KEY
kelas_perkuliahan_id INTEGER FOREIGN KEY
pertemuan_ke        TINYINT UNIQUE (per kelas)
tanggal             DATE
jam_mulai           VARCHAR (5) -- Format: HH:MM
jam_selesai         VARCHAR (5) -- Format: HH:MM
session_status      ENUM(draft, buka, tutup)
rangkuman           LONGTEXT
berita_acara        LONGTEXT
catatan             LONGTEXT
waktu_buka          DATETIME
waktu_tutup         DATETIME
created_at          TIMESTAMP
updated_at          TIMESTAMP
```

### Tabel: `absensi_mahasiswa`
```sql
id                  INTEGER PRIMARY KEY
absensi_id          INTEGER FOREIGN KEY
mahasiswa_id        INTEGER FOREIGN KEY
status              ENUM(hadir, izin, sakit, alpha)
waktu_absensi       DATETIME (NULL jika alpha)
keterangan          TEXT
created_at          TIMESTAMP
updated_at          TIMESTAMP
UNIQUE (absensi_id, mahasiswa_id)
```

---

## 🛠️ Troubleshooting

### ❌ "Sesi presensi tidak muncul"

**Kemungkinan:**
- Dosen belum membuka sesi
- Anda tidak terdaftar di kelas
- Sesi sudah ditutup

**Solusi:**
- Hubungi dosen untuk membuka sesi
- Pastikan sudah terdaftar di kelas
- Cek riwayat presensi

### ❌ "Tidak bisa presensi, tombol disabled"

**Kemungkinan:**
- Sesi belum dibuka dosen
- Sesi sudah ditutup
- Sudah presensi untuk sesi ini

**Solusi:**
- Tunggu dosen membuka sesi
- Presensi sebelum sesi ditutup
- Hanya bisa presensi 1x per sesi

### ❌ "Email notifikasi tidak terima"

**Kemungkinan:**
- Email setting belum dikonfigurasi
- Email masuk ke folder spam
- Email address tidak valid

**Solusi:**
- Hubungi admin untuk verifikasi email
- Cek folder spam/junk
- Update email di profil Anda

### ❌ "Sistem error/500"

**Solusi:**
1. Refresh halaman
2. Clear browser cache
3. Login ulang
4. Hubungi admin jika masih error
5. Cek server logs: `tail -f storage/logs/laravel.log`

---

## 📞 Support

**Hub ungi Admin Jika:**
- Sistem error atau tidak bisa akses
- Email belum dikonfigurasi
- Ada bug atau fitur tidak berfungsi
- Perlu reset atau setup ulang

**Contact:**
- Email: admin@cendekia.local
- WhatsApp: [Hubungi admin]
- Office: Ruang IT Lantai 2

---

## 📚 Referensi

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [MySQL](https://dev.mysql.com/doc)

---

**Versi:** 2.0
**Last Updated:** 14 Juli 2026
**Status:** Production Ready ✅

