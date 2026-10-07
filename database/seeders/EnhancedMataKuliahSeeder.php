<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class EnhancedMataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $specificCourses = [
            'TI' => [
                ['IF-201', 'Algoritma dan Pemrograman', 4, 1, 'Mata kuliah dasar yang membahas konsep algoritma, struktur data dasar.'],
                ['IF-202', 'Basis Data', 3, 2, 'Mata kuliah yang mempelajari desain basis data relasional.'],
                ['IF-203', 'Pemrograman Web', 3, 2, 'Mata kuliah yang mencakup pengembangan web frontend dan backend.'],
            ],
            'SI' => [
                ['SI-201', 'Analisis dan Desain Sistem Informasi', 4, 1, 'Mata kuliah yang mengajarkan metodologi analisis dan desain sistem informasi.'],
                ['SI-202', 'Manajemen Basis Data', 3, 2, 'Pengelolaan database untuk mendukung sistem informasi enterprise.'],
                ['SI-203', 'Sistem Informasi Manajemen', 3, 2, 'Studi tentang peran sistem informasi dalam manajemen organisasi.'],
            ],
            'HI' => [
                ['HI-101', 'Pengantar Ilmu Hubungan Internasional', 3, 1, 'Konsep dasar HI.'],
                ['HI-102', 'Sejarah Diplomasi', 3, 2, 'Sejarah diplomasi dunia.'],
            ],
            'IH' => [
                ['IH-101', 'Pengantar Ilmu Hukum', 4, 1, 'Konsep dasar hukum di Indonesia.'],
                ['IH-102', 'Hukum Perdata', 4, 2, 'Dasar-dasar hukum perdata.'],
            ],
            'PSI' => [
                ['PS-101', 'Psikologi Umum', 3, 1, 'Pengantar psikologi umum.'],
                ['PS-102', 'Psikologi Perkembangan', 3, 2, 'Tahap perkembangan manusia.'],
            ],
        ];

        $allProdis = ProgramStudi::all();

        foreach ($allProdis as $prodi) {
            $kodeProdi = $prodi->kode_prodi;

            if (isset($specificCourses[$kodeProdi])) {
                foreach ($specificCourses[$kodeProdi] as [$kodeMk, $namaMk, $sks, $semesterKe, $deskripsi]) {
                    MataKuliah::updateOrCreate(
                        ['kode_mk' => $kodeMk],
                        [
                            'program_studi_id' => $prodi->id,
                            'nama_mk' => $namaMk,
                            'sks' => $sks,
                            'semester_ke' => $semesterKe,
                            'deskripsi' => $deskripsi,
                        ]
                    );
                }
            } else {
                // Generate generic courses for other prodis
                for ($i = 1; $i <= 3; $i++) {
                    MataKuliah::updateOrCreate(
                        ['kode_mk' => $kodeProdi . '-10' . $i],
                        [
                            'program_studi_id' => $prodi->id,
                            'nama_mk' => 'Mata Kuliah Dasar ' . $prodi->nama_prodi . ' ' . $i,
                            'sks' => 3,
                            'semester_ke' => $i,
                            'deskripsi' => 'Mata kuliah dasar untuk program studi ' . $prodi->nama_prodi,
                        ]
                    );
                }
            }
        }
    }
}
