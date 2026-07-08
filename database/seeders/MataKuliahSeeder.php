<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            'TI' => [
                ['IF-201', 'Struktur Data dan Algoritma', 4, 3],
                ['IF-203', 'Pemrograman Web', 3, 3],
                ['IF-205', 'Basis Data Lanjut', 3, 3],
                ['IF-207', 'Jaringan Komputer', 3, 3],
                ['IF-209', 'Rekayasa Perangkat Lunak', 3, 3],
            ],
            'SI' => [
                ['SI-201', 'Analisis Proses Bisnis', 3, 3],
                ['SI-203', 'Manajemen Basis Data', 3, 3],
                ['SI-205', 'Sistem Informasi Manajemen', 3, 3],
                ['SI-207', 'Enterprise Architecture', 3, 3],
                ['SI-209', 'Audit Sistem Informasi', 3, 3],
            ],
            'RPL' => [
                ['RPL-201', 'Desain UI/UX Produk Digital', 3, 3],
                ['RPL-203', 'Pemrograman Berorientasi Objek', 4, 3],
                ['RPL-205', 'Software Testing', 3, 3],
                ['RPL-207', 'DevOps Dasar', 3, 3],
                ['RPL-209', 'Manajemen Proyek Perangkat Lunak', 3, 3],
            ],
            'DS' => [
                ['DS-201', 'Statistika Komputasi', 3, 3],
                ['DS-203', 'Data Mining', 3, 3],
                ['DS-205', 'Machine Learning Dasar', 4, 3],
                ['DS-207', 'Visualisasi Data', 3, 3],
                ['DS-209', 'Etika dan Tata Kelola Data', 2, 3],
            ],
        ];

        foreach ($courses as $kodeProdi => $items) {
            $prodi = ProgramStudi::where('kode_prodi', $kodeProdi)->first();

            if (! $prodi) {
                continue;
            }

            foreach ($items as [$kodeMk, $namaMk, $sks, $semesterKe]) {
                MataKuliah::updateOrCreate(
                    ['kode_mk' => $kodeMk],
                    [
                        'program_studi_id' => $prodi->id,
                        'nama_mk' => $namaMk,
                        'sks' => $sks,
                        'semester_ke' => $semesterKe,
                        'deskripsi' => 'Mata kuliah Fakultas Ilmu Komputer yang membahas ' . strtolower($namaMk) . ' melalui teori, praktik laboratorium, studi kasus, dan proyek akhir semester.',
                    ]
                );
            }
        }
    }
}
