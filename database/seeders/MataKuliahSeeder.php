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

        MataKuliah::create([
            'program_studi_id' => $ti->id,
            'kode_mk' => 'CS-201',
            'nama_mk' => 'Struktur Data & Algoritma',
            'sks' => 4,
            'semester_ke' => 3,
            'deskripsi' => 'Mata kuliah ini membahas pengorganisasian data dan teknik algoritma untuk manipulasi data secara efisien.',
        ]);

        MataKuliah::create([
            'program_studi_id' => $ti->id,
            'kode_mk' => 'SE-204',
            'nama_mk' => 'Arsitektur Komputer',
            'sks' => 3,
            'semester_ke' => 3,
            'deskripsi' => 'Mempelajari arsitektur dan organisasi sistem komputer.',
        ]);

        MataKuliah::create([
            'program_studi_id' => $ti->id,
            'kode_mk' => 'MT-102',
            'nama_mk' => 'Matematika Diskrit',
            'sks' => 3,
            'semester_ke' => 2,
            'deskripsi' => 'Dasar-dasar matematika diskrit untuk ilmu komputer.',
        ]);
    }
}