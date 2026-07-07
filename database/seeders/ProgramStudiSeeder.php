<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        ProgramStudi::updateOrCreate(
            ['kode_prodi' => 'TI'],
            [
                'nama_prodi' => 'Teknik Informatika',
                'jenjang' => 'S1',
            ]
        );

        ProgramStudi::updateOrCreate(
            ['kode_prodi' => 'SI'],
            [
                'nama_prodi' => 'Sistem Informasi',
                'jenjang' => 'S1',
            ]
        );

        ProgramStudi::updateOrCreate(
            ['kode_prodi' => 'DKV'],
            [
                'nama_prodi' => 'Desain Komunikasi Visual',
                'jenjang' => 'S1',
            ]
        );
    }
}
