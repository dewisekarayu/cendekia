<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::role('admin')->first();

        if (! $admin) {
            return;
        }

        $items = [
            [
                'judul' => 'Perubahan Jadwal Ujian Tengah Semester Ganjil 2023/2024',
                'isi' => 'Sehubungan dengan agenda akademik nasional, jadwal UTS akan disesuaikan. Mahasiswa diminta memeriksa jadwal terbaru melalui portal akademik.',
            ],
            [
                'judul' => 'Pendaftaran Workshop Digital Literacy Batch 4',
                'isi' => 'Workshop literasi digital dibuka untuk 50 peserta pertama. Peserta akan mendapatkan e-certificate dan materi praktik.',
            ],
            [
                'judul' => 'Layanan Perpustakaan Selama Masa Libur Nasional',
                'isi' => 'Layanan peminjaman online tetap dapat diakses selama libur nasional. Pengembalian buku menyesuaikan jadwal operasional kampus.',
            ],
        ];

        foreach ($items as $item) {
            Pengumuman::updateOrCreate(
                ['judul' => $item['judul']],
                [
                    'dibuat_oleh' => $admin->id,
                    'isi' => $item['isi'],
                    'untuk_semua' => true,
                ]
            );
        }
    }
}
