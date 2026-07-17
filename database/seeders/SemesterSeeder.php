<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $semesters = [
            // 2023/2024
            [
                'nama_semester' => 'Ganjil 2023/2024',
                'jenis' => 'Ganjil',
                'tahun_ajaran' => '2023/2024',
                'tanggal_mulai' => '2023-08-14',
                'tanggal_selesai' => '2024-01-31',
                'is_active' => false,
            ],
            [
                'nama_semester' => 'Genap 2023/2024',
                'jenis' => 'Genap',
                'tahun_ajaran' => '2023/2024',
                'tanggal_mulai' => '2024-02-19',
                'tanggal_selesai' => '2024-07-12',
                'is_active' => false,
            ],
            // 2024/2025
            [
                'nama_semester' => 'Ganjil 2024/2025',
                'jenis' => 'Ganjil',
                'tahun_ajaran' => '2024/2025',
                'tanggal_mulai' => '2024-08-12',
                'tanggal_selesai' => '2025-01-31',
                'is_active' => false,
            ],
            [
                'nama_semester' => 'Genap 2024/2025',
                'jenis' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'tanggal_mulai' => '2025-02-17',
                'tanggal_selesai' => '2025-07-11',
                'is_active' => false,
            ],
            // 2025/2026 (Current active)
            [
                'nama_semester' => 'Ganjil 2025/2026',
                'jenis' => 'Ganjil',
                'tahun_ajaran' => '2025/2026',
                'tanggal_mulai' => '2025-09-01',
                'tanggal_selesai' => '2026-01-31',
                'is_active' => true,
            ],
            [
                'nama_semester' => 'Genap 2025/2026',
                'jenis' => 'Genap',
                'tahun_ajaran' => '2025/2026',
                'tanggal_mulai' => '2026-02-16',
                'tanggal_selesai' => '2026-07-10',
                'is_active' => false,
            ],
        ];

        foreach ($semesters as $semesterData) {
            Semester::updateOrCreate(
                ['nama_semester' => $semesterData['nama_semester']],
                $semesterData
            );
        }
    }
}