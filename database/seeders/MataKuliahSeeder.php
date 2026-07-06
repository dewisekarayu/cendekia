<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $ti = ProgramStudi::where('kode_prodi', 'TI')->first();

        if (! $ti) {
            return;
        }

        MataKuliah::updateOrCreate(
            ['kode_mk' => 'CS-201'],
            [
                'program_studi_id' => $ti->id,
                'nama_mk' => 'Struktur Data & Algoritma',
                'sks' => 4,
                'semester_ke' => 3,
                'deskripsi' => 'Mata kuliah ini membahas pengorganisasian data dan teknik algoritma untuk manipulasi data secara efisien.',
            ]
        );

        MataKuliah::updateOrCreate(
            ['kode_mk' => 'SE-204'],
            [
                'program_studi_id' => $ti->id,
                'nama_mk' => 'Arsitektur Komputer',
                'sks' => 3,
                'semester_ke' => 3,
                'deskripsi' => 'Mempelajari arsitektur dan organisasi sistem komputer.',
            ]
        );

        MataKuliah::updateOrCreate(
            ['kode_mk' => 'MT-102'],
            [
                'program_studi_id' => $ti->id,
                'nama_mk' => 'Matematika Diskrit',
                'sks' => 3,
                'semester_ke' => 2,
                'deskripsi' => 'Dasar-dasar matematika diskrit untuk ilmu komputer.',
            ]
        );
    }
}