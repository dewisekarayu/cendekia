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
        $dosen = User::where('email', 'dosen@cendekia.test')->first();
        $semester = Semester::where('is_active', true)->first();

        $mkList = MataKuliah::all();

        foreach ($mkList as $i => $mk) {
            KelasPerkuliahan::create([
                'mata_kuliah_id' => $mk->id,
                'dosen_id' => $dosen->id,
                'semester_id' => $semester->id,
                'kode_kelas' => chr(65 + $i), // A, B, C
                'hari' => ['Senin', 'Selasa', 'Rabu'][$i % 3],
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:30',
                'ruangan' => 'Lab Komputer ' . ($i + 1),
                'is_active' => true,
            ]);
        }
    }
}