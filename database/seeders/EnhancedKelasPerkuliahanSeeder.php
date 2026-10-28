<?php

namespace Database\Seeders;

use App\Models\KelasJadwal;
use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class EnhancedKelasPerkuliahanSeeder extends Seeder
{
    private const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

    private const TIME_SLOTS = [
        ['08:00', '09:40'],
        ['10:00', '11:40'],
        ['13:00', '14:40'],
        ['15:00', '16:40'],
        ['17:00', '18:40'],
    ];

    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        if (! $semester) {
            throw new RuntimeException('Seeder kelas membutuhkan satu semester aktif. Jalankan SemesterSeeder terlebih dahulu.');
        }

        $prodiPrefixMap = [
            'PD' => 'PD',
            'KG' => 'KG',
            'FAR' => 'FAR',
            'KEP' => 'KEP',
            'KEB' => 'KEB',
            'TI' => 'IF',
            'TS' => 'TS',
            'TE' => 'TE',
            'TIN' => 'TIN',
            'SI' => 'SI',
        ];

        $prodis = ProgramStudi::whereIn('kode_prodi', array_keys($prodiPrefixMap))
            ->orderBy('kode_prodi')
            ->get();
        if ($prodis->count() !== count($prodiPrefixMap)) {
            throw new RuntimeException('Seeder kelas membutuhkan 10 program studi. Jalankan ProgramStudiSeeder terlebih dahulu.');
        }

        foreach ($prodis as $prodi) {
            $prefix = $prodiPrefixMap[$prodi->kode_prodi];
            $firstCourseNumber = in_array($prodi->kode_prodi, ['TI', 'SI'], true) ? 201 : 101;
            $expectedCourseCodes = array_map(
                fn (int $number) => $prefix.'-'.$number,
                range($firstCourseNumber, $firstCourseNumber + 3)
            );
            $expectedCourseCodes = array_merge($expectedCourseCodes, [
                'MKU1-'.$prodi->kode_prodi,
                'MKU2-'.$prodi->kode_prodi,
            ]);
            $courses = MataKuliah::where('program_studi_id', $prodi->id)
                ->where('semester_ke', 1)
                ->whereIn('kode_mk', $expectedCourseCodes)
                ->orderBy('kode_mk')
                ->get()
                ->values();
            $lecturers = User::role('dosen')
                ->where('program_studi_id', $prodi->id)
                ->whereBetween('nip_nim', ['1980000001', '1980000020'])
                ->orderBy('id')
                ->take(2)
                ->get()
                ->values();
            $students = User::role('mahasiswa')
                ->where('program_studi_id', $prodi->id)
                ->whereBetween('nip_nim', ['20240001', '20240300'])
                ->orderBy('nip_nim')
                ->get()
                ->values();

            if ($courses->count() !== 6) {
                throw new RuntimeException("Program studi {$prodi->kode_prodi} harus memiliki 6 mata kuliah semester 1 sebelum kelas dibuat.");
            }
            if ($lecturers->count() !== 2) {
                throw new RuntimeException("Program studi {$prodi->kode_prodi} harus memiliki 2 dosen untuk pembagian pengampu.");
            }
            if ($students->count() !== 30) {
                throw new RuntimeException("Program studi {$prodi->kode_prodi} harus memiliki 30 mahasiswa agar dapat dibagi menjadi dua seksi.");
            }

            foreach ($courses as $courseIndex => $course) {
                $lecturer = $lecturers[$courseIndex % $lecturers->count()];

                foreach ([0, 1] as $sectionIndex) {
                    $classNumber = ($courseIndex * 2) + $sectionIndex + 1;
                    $classCode = $prefix.'-'.$classNumber;
                    $classSlot = ($courseIndex * 2) + $sectionIndex;
                    $building = in_array($prodi->kode_prodi, ['PD', 'KG', 'FAR', 'KEP', 'KEB'], true)
                        ? 'Gedung FK'
                        : 'Gedung FT';

                    $class = KelasPerkuliahan::updateOrCreate(
                        [
                            'kode_kelas' => $classCode,
                            'program_studi_id' => $prodi->id,
                            'semester_id' => $semester->id,
                        ],
                        [
                            'mata_kuliah_id' => $course->id,
                            'dosen_id' => $lecturer->id,
                            'kuota_mahasiswa' => 30,
                            'status_kelas' => 'aktif',
                            'is_active' => true,
                            'bobot_tugas' => 30,
                            'bobot_uts' => 30,
                            'bobot_uas' => 40,
                        ]
                    );

                    $class->dosenPengampuTambahan()->sync([]);
                    KelasJadwal::where('kelas_perkuliahan_id', $class->id)->delete();
                    foreach ($this->scheduleFor($classSlot, $building, $prefix, $classNumber) as $schedule) {
                        KelasJadwal::create([
                            'kelas_perkuliahan_id' => $class->id,
                            ...$schedule,
                        ]);
                    }

                    $studentBatch = $students->slice($sectionIndex * 15, 15);
                    $class->mahasiswa()->sync(
                        $studentBatch->mapWithKeys(fn (User $student) => [
                            $student->id => ['tanggal_daftar' => now()->subMonths(2)->toDateString()],
                        ])->all()
                    );
                }
            }
        }
    }

    /**
     * Give each class two non-overlapping weekly sessions across five weekdays.
     *
     * @return array<int, array{hari: string, jam_mulai: string, jam_selesai: string, ruangan: string}>
     */
    private function scheduleFor(int $classSlot, string $building, string $prefix, int $classNumber): array
    {
        return collect([$classSlot, $classSlot + 12])
            ->map(function (int $slot) use ($building, $prefix, $classNumber): array {
                $day = self::DAYS[intdiv($slot, count(self::TIME_SLOTS))];
                [$start, $end] = self::TIME_SLOTS[$slot % count(self::TIME_SLOTS)];

                return [
                    'hari' => $day,
                    'jam_mulai' => $start,
                    'jam_selesai' => $end,
                    'ruangan' => $building.' '.$prefix.'-'.str_pad((string) $classNumber, 3, '0', STR_PAD_LEFT),
                ];
            })
            ->all();
    }
}
