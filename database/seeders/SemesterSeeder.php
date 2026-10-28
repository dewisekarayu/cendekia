<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = [
            // 2024/2025
            [
                'nama_semester' => 'Ganjil 2024/2025',
                'jenis' => 'Ganjil',
                'tahun_ajaran' => '2024/2025',
                'tanggal_mulai' => '2024-08-14',
                'tanggal_selesai' => '2025-01-31',
                'is_active' => false,
            ],
            [
                'nama_semester' => 'Genap 2024/2025',
                'jenis' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'tanggal_mulai' => '2025-02-19',
                'tanggal_selesai' => '2025-07-12',
                'is_active' => false,
            ],
            // 2025/2026
            [
                'nama_semester' => 'Ganjil 2025/2026',
                'jenis' => 'Ganjil',
                'tahun_ajaran' => '2025/2026',
                'tanggal_mulai' => '2025-08-12',
                'tanggal_selesai' => '2026-01-31',
                'is_active' => false,
            ],
            [
                'nama_semester' => 'Genap 2025/2026',
                'jenis' => 'Genap',
                'tahun_ajaran' => '2025/2026',
                'tanggal_mulai' => '2026-02-17',
                'tanggal_selesai' => '2026-07-11',
                'is_active' => false,
            ],
            // 2026/2027
            [
                'nama_semester' => 'Ganjil 2026/2027',
                'jenis' => 'Ganjil',
                'tahun_ajaran' => '2026/2027',
                'tanggal_mulai' => '2026-09-01',
                'tanggal_selesai' => '2027-01-31',
                'is_active' => false,
            ],
            [
                'nama_semester' => 'Genap 2026/2027',
                'jenis' => 'Genap',
                'tahun_ajaran' => '2026/2027',
                'tanggal_mulai' => '2027-02-16',
                'tanggal_selesai' => '2027-07-10',
                'is_active' => true,
            ],
            // 2027/2027 (Current active)
            [
                'nama_semester' => 'Ganjil 2027/2028',
                'jenis' => 'Ganjil',
                'tahun_ajaran' => '2027/2028',
                'tanggal_mulai' => '2027-09-01',
                'tanggal_selesai' => '2028-01-31',
                'is_active' => false,
            ],
            [
                'nama_semester' => 'Genap 2028/2029',
                'jenis' => 'Genap',
                'tahun_ajaran' => '2028/2029',
                'tanggal_mulai' => '2028-02-15',
                'tanggal_selesai' => '2028-07-09',
                'is_active' => false,
            ],
        ];

        // Deactivate all first before seeding so exactly 1 is active
        Semester::query()->update(['is_active' => false]);

        foreach ($semesters as $semesterData) {
            Semester::updateOrCreate(
                ['nama_semester' => $semesterData['nama_semester']],
                $semesterData
            );
        }
    }
}