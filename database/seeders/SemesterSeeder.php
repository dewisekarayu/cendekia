<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        Semester::updateOrCreate(
            ['nama_semester' => 'Ganjil 2025/2026'],
            [
                'jenis' => 'Ganjil',
                'tahun_ajaran' => '2025/2026',
                'tanggal_mulai' => '2025-09-01',
                'tanggal_selesai' => '2026-01-31',
                'is_active' => true,
            ]
        );
    }
}