@component('mail::message')
# ⏰ Sesi Presensi Dibuka

Halo **{{ $mahasiswa->name }}**,

Dosen Anda **{{ $dosen->name }}** telah membuka sesi presensi untuk kelas.

## 📋 Detail Sesi Presensi

- **Mata Kuliah**: {{ $kelas->mataKuliah->nama_mk }}
- **Kelas**: {{ $kelas->kode_kelas }}
- **Pertemuan Ke**: {{ $absensi->pertemuan_ke }}
- **Tanggal**: {{ $absensi->tanggal->format('d F Y') }}
- **Waktu**: {{ $absensi->jam_mulai }} - {{ $absensi->jam_selesai }}

@if($absensi->rangkuman)
## 📝 Ringkasan Materi

{{ $absensi->rangkuman }}
@endif

## 🎯 Apa yang Harus Anda Lakukan

Silakan lakukan presensi melalui portal Cendekia:
1. Masuk ke dashboard Cendekia
2. Buka menu **Presensi**
3. Pilih kelas **{{ $kelas->mataKuliah->nama_mk }}**
4. Pilih status kehadiran Anda (Hadir/Izin/Sakit)
5. Klik tombol **Presensi**

## ⚠️ Penting

- Sesi presensi hanya dapat diisi saat **dibuka oleh dosen**
- Anda hanya dapat presensi **sekali per sesi**
- Jika Anda tidak hadir, pastikan mengisi izin atau sakit dengan alasan yang jelas

---

@component('mail::button', ['url' => route('mahasiswa.absensi.kelas', ['kelasId' => $kelas->id])])
Buka Menu Presensi
@endcomponent

Terima kasih,  
**{{ config('app.name') }} - Sistem Informasi Akademik**

---

*Email ini otomatis dikirim sistem. Jangan balas email ini.*
@endcomponent
