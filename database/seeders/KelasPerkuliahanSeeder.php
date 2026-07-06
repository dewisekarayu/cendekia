<?php

namespace Database\Seeders;

use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasPerkuliahanSeeder extends Seeder
{
    public function run(): void
    {
        $dosen = User::role('dosen')->first();
        $semester = Semester::where('is_active', true)->first();

        if (! $dosen || ! $semester) {
            return;
        }

        $mkList = MataKuliah::all();

        foreach ($mkList as $i => $mk) {
            KelasPerkuliahan::firstOrCreate(
                [
                    'mata_kuliah_id' => $mk->id,
                    'semester_id' => $semester->id,
                    'kode_kelas' => chr(65 + $i),
                ],
                [
                    'dosen_id' => $dosen->id,
                    'hari' => ['Senin', 'Selasa', 'Rabu'][$i % 3],
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '10:30',
                    'ruangan' => 'Lab Komputer ' . ($i + 1),
                    'is_active' => true,
                ]
            );
        }
    }
}