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
                'kode_fakultas' => 'FT',
                'nama_fakultas' => 'Fakultas Teknik',
                'prodi' => [
                    ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'TE', 'nama_prodi' => 'Teknik Elektro', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'TM', 'nama_prodi' => 'Teknik Mesin', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'TS', 'nama_prodi' => 'Teknik Sipil', 'jenjang' => 'S1'],
                ]
            ],
            [
                'kode_fakultas' => 'FEB',
                'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis',
                'prodi' => [
                    ['kode_prodi' => 'MJ', 'nama_prodi' => 'Manajemen', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'AK', 'nama_prodi' => 'Akuntansi', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'EP', 'nama_prodi' => 'Ekonomi Pembangunan', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'bisnis', 'nama_prodi' => 'Bisnis Digital', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'eksya', 'nama_prodi' => 'Ekonomi Syariah', 'jenjang' => 'S1'],
                ]
            ],
            [
                'kode_fakultas' => 'FISIP',
                'nama_fakultas' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'prodi' => [
                    ['kode_prodi' => 'HI', 'nama_prodi' => 'Hubungan Internasional', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'IK', 'nama_prodi' => 'Ilmu Komunikasi', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'AP', 'nama_prodi' => 'Administrasi Publik', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'SOS', 'nama_prodi' => 'Sosiologi', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'IP', 'nama_prodi' => 'Ilmu Pemerintahan', 'jenjang' => 'S1'],
                ]
            ],
            [
                'kode_fakultas' => 'FH',
                'nama_fakultas' => 'Fakultas Hukum',
                'prodi' => [
                    ['kode_prodi' => 'IH', 'nama_prodi' => 'Ilmu Hukum', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'HTN', 'nama_prodi' => 'Hukum Tata Negara', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'HP', 'nama_prodi' => 'Hukum Pidana', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'HPD', 'nama_prodi' => 'Hukum Perdata', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'HI_HUKUM', 'nama_prodi' => 'Hukum Internasional', 'jenjang' => 'S1'],
                ]
            ],
            [
                'kode_fakultas' => 'FPsi',
                'nama_fakultas' => 'Fakultas Psikologi',
                'prodi' => [
                    ['kode_prodi' => 'PSI', 'nama_prodi' => 'Psikologi', 'jenjang' => 'S1'],
                    ['kode_prodi' => 'PK', 'nama_prodi' => 'Psikologi Klinis', 'jenjang' => 'S2'],
                    ['kode_prodi' => 'PIO', 'nama_prodi' => 'Psikologi Industri & Organisasi', 'jenjang' => 'S2'],
                    ['kode_prodi' => 'PP', 'nama_prodi' => 'Psikologi Pendidikan', 'jenjang' => 'S2'],
                    ['kode_prodi' => 'PS', 'nama_prodi' => 'Psikologi Sosial', 'jenjang' => 'S2'],
                ]
            ],
        ];

        // Ensure semester is available (might need to fetch active semester)
        $semester = \App\Models\Semester::where('is_active', true)->first();

        foreach ($fakultasData as $fakultas) {
            $createdFakultas = \App\Models\Fakultas::updateOrCreate(
                ['kode_fakultas' => $fakultas['kode_fakultas']],
                [
                    'nama_fakultas' => $fakultas['nama_fakultas'],
                    'semester_id' => $semester ? $semester->id : null,
                ]
            );

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
            }
        }
    }
}
