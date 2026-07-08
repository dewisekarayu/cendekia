<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1'],
            ['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1'],
            ['kode_prodi' => 'RPL', 'nama_prodi' => 'Rekayasa Perangkat Lunak', 'jenjang' => 'S1'],
            ['kode_prodi' => 'DS', 'nama_prodi' => 'Sains Data', 'jenjang' => 'S1'],
        ] as $prodi) {
            ProgramStudi::updateOrCreate(
                ['kode_prodi' => $prodi['kode_prodi']],
                $prodi
            );
        }
    }
}
