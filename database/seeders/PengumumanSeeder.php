<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $dummyAnnouncements = [
            'Pengumuman Penting Terkait Materi' => 'Materi pembelajaran untuk minggu ini telah diunggah di sistem. Silakan pelajari dan siapkan pertanyaan untuk diskusi di kelas.',
            'Update Jadwal dan Perubahan Deadline Tugas' => 'Pengingat: Deadline untuk tugas belum diunggah adalah 23:59 hari ini. Pastikan upload tepat waktu.',
            'Pengingat Deadline Tugas Jadwal Ujian' => 'Jadwal ujian telah ditentukan seperti yang ada di sistem. Silakan persiapkan diri dengan baik.',
            'Materi Baru Telah Diunggah untuk Nilai' => 'Nilai untuk tugas/ujian telah diterbitkan. Silakan cek portal akademik dan hubungi dosen jika ada pertanyaan.',
            'Kesempatan Ujian Susulan Pengingat Penting' => 'Pengingat untuk aktif berpartisipasi di forum kelas. Partisipasi aktif akan mempengaruhi nilai.',
            'Hasil Update Sistem Telah Diterbitkan' => 'Ada update pada sistem pembelajaran. Pastikan refresh browser untuk fitur terbaru.',
            'Perubahan Jadwal Ujian Tengah Semester Ganjil 2023/2024' => 'Sehubungan dengan agenda akademik nasional, jadwal UTS akan disesuaikan. Mahasiswa diminta memeriksa jadwal terbaru melalui portal akademik.',
            'Pendaftaran Workshop Digital Literacy Batch 4' => 'Workshop literasi digital dibuka untuk 50 peserta pertama. Peserta akan mendapatkan e-certificate dan materi praktik.',
            'Layanan Perpustakaan Selama Masa Libur Nasional' => 'Layanan peminjaman online tetap dapat diakses selama libur nasional. Pengembalian buku menyesuaikan jadwal operasional kampus.',
        ];

        Pengumuman::where(function ($query) use ($dummyAnnouncements) {
            foreach ($dummyAnnouncements as $judul => $isi) {
                $query->orWhere(function ($query) use ($judul, $isi) {
                    $query->where('judul', $judul)
                        ->where('isi', $isi);
                });
            }
        })->delete();
    }
}
