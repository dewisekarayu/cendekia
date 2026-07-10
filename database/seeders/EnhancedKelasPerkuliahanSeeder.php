<?php

namespace Database\Seeders;

use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnhancedKelasPerkuliahanSeeder extends Seeder
{
    public function run(): void
    {
        $dosenList = User::role('dosen')->get()->values();
        $semester = Semester::where('is_active', true)->first();

        if ($dosenList->isEmpty() || !$semester) {
            return;
        }

        $mkList = MataKuliah::all();
        
        // Jadwal: Senin-Jumat, dengan 4 slot waktu
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamList = [
            ['07:30', '09:10'],
            ['09:20', '11:00'],
            ['13:00', '14:40'],
            ['14:50', '16:30'],
        ];

        $ruanganList = [
            'Lab FIK 01', 'Lab FIK 02', 'Lab FIK 03', 'Lab FIK 04',
            'Lab FIK 05', 'Lab FIK 06', 'Lab FIK 07', 'Lab FIK 08',
            'Kelas A101', 'Kelas A102', 'Kelas A103', 'Kelas A104',
        ];

        $jadwalUsed = [];

        foreach ($mkList as $i => $mk) {
            // Create 2-3 kelas per mata kuliah (A, B, sometimes C)
            $numSections = ($i % 3) === 2 ? 3 : 2;

            for ($sectionIdx = 0; $sectionIdx < $numSections; $sectionIdx++) {
                $section = chr(65 + $sectionIdx); // A, B, C
                
                // Find a dosen for this kelas
                $dosenIdx = ($i + $sectionIdx) % $dosenList->count();
                $dosen = $dosenList[$dosenIdx];
                
                // Find a schedule that doesn't conflict
                $jadwalIndex = ($i * 3 + $sectionIdx) % (count($hariList) * count($jamList));
                $hari = $hariList[$jadwalIndex % count($hariList)];
                $jamIndex = (int)($jadwalIndex / count($hariList)) % count($jamList);
                $jam = $jamList[$jamIndex];
                
                // Try to find non-conflicting schedule
                $attempts = 0;
                while (isset($jadwalUsed[$dosen->id][$hari][$jamIndex]) && $attempts < 20) {
                    $jamIndex = ($jamIndex + 1) % count($jamList);
                    $attempts++;
                }
                
                if (!isset($jadwalUsed[$dosen->id])) {
                    $jadwalUsed[$dosen->id] = [];
                }
                if (!isset($jadwalUsed[$dosen->id][$hari])) {
                    $jadwalUsed[$dosen->id][$hari] = [];
                }
                $jadwalUsed[$dosen->id][$hari][$jamIndex] = true;

                $jam = $jamList[$jamIndex];
                $ruangan = $ruanganList[($i + $sectionIdx) % count($ruanganList)];

                KelasPerkuliahan::updateOrCreate(
                    [
                        'mata_kuliah_id' => $mk->id,
                        'semester_id' => $semester->id,
                        'kode_kelas' => $mk->kode_mk . '-' . $section,
                    ],
                    [
                        'dosen_id' => $dosen->id,
                        'hari' => $hari,
                        'jam_mulai' => $jam[0],
                        'jam_selesai' => $jam[1],
                        'ruangan' => $ruangan,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
