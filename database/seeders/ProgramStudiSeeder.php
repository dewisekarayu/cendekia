<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $fakultasData = [
            [
                'kode_fakultas' => 'FK',
                'nama_fakultas' => 'Fakultas Kedokteran',
                'prodi' => [
                    ['kode_prodi' => 'PD', 'nama_prodi' => 'Pendidikan Dokter', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'KG', 'nama_prodi' => 'Kedokteran Gigi', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'FAR', 'nama_prodi' => 'Farmasi', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'KEP', 'nama_prodi' => 'Keperawatan', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'KEB', 'nama_prodi' => 'Kebidanan', 'jenjang' => 'S1'],
                ]
            ],
            [
                'kode_fakultas' => 'FT',
                'nama_fakultas' => 'Fakultas Teknik',
                'prodi' => [
                    ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'TS', 'nama_prodi' => 'Teknik Sipil', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'TE', 'nama_prodi' => 'Teknik Elektro', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'TIN', 'nama_prodi' => 'Teknik Industri', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1'],
                ]
            ],
        ];

        // Ensure semester is available (fetch active semester)
        $semester = \App\Models\Semester::where('is_active', true)->first();

        $activeFakultasIds = [];
        $activeProdiCodes = [];

        foreach ($fakultasData as $fakultas) {
            $createdFakultas = \App\Models\Fakultas::updateOrCreate(
                ['kode_fakultas' => $fakultas['kode_fakultas']],
                [
                    'nama_fakultas' => $fakultas['nama_fakultas'],
                    'semester_id' => $semester ? $semester->id : null,
                ]
            );
            $activeFakultasIds[] = $createdFakultas->id;

            foreach ($fakultas['prodi'] as $prodi) {
                ProgramStudi::updateOrCreate(
                    ['kode_prodi' => $prodi['kode_prodi']],
                    [
                        'nama_prodi' => $prodi['nama_prodi'],
                        'jenjang' => $prodi['jenjang'],
                        'fakultas_id' => $createdFakultas->id,
                        'akreditasi' => 'A',
                        'status' => 1,
                    ]
                );
                $activeProdiCodes[] = $prodi['kode_prodi'];
            }
        }

        // Clean up obsolete prodi not in active list
        ProgramStudi::whereNotIn('kode_prodi', $activeProdiCodes)->delete();
        // Clean up obsolete fakultas not in active list
        \App\Models\Fakultas::whereNotIn('id', $activeFakultasIds)->delete();
    }
}
