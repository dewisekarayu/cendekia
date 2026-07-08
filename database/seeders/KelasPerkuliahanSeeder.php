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
        $dosenList = User::role('dosen')->get()->values();
        $semester = Semester::where('is_active', true)->first();

        if ($dosenList->isEmpty() || ! $semester) {
            return;
        }

        $mkList = MataKuliah::all();
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamList = [
            ['08:00', '10:30'],
            ['10:30', '13:00'],
            ['13:00', '15:30'],
            ['15:30', '18:00'],
        ];

        foreach ($mkList as $i => $mk) {
            foreach (['A', 'B'] as $sectionIndex => $section) {
                $slot = $jamList[($i + $sectionIndex) % count($jamList)];

                KelasPerkuliahan::updateOrCreate(
                    [
                        'mata_kuliah_id' => $mk->id,
                        'semester_id' => $semester->id,
                        'kode_kelas' => $mk->kode_mk . '-' . $section,
                    ],
                    [
                        'dosen_id' => $dosenList[($i + $sectionIndex) % $dosenList->count()]->id,
                        'hari' => $hariList[($i + $sectionIndex) % count($hariList)],
                        'jam_mulai' => $slot[0],
                        'jam_selesai' => $slot[1],
                        'ruangan' => 'Lab FIK ' . str_pad((string) (($i % 8) + 1), 2, '0', STR_PAD_LEFT),
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
