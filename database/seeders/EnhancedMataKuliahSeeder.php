<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use RuntimeException;

class EnhancedMataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $coursesByProgram = [
            'PD' => [
                ['PD-101', 'Anatomi dan Fisiologi Manusia', 4, 'Struktur tubuh dan fungsi organ sebagai dasar ilmu kedokteran.'],
                ['PD-102', 'Histologi dan Biologi Sel', 3, 'Struktur mikroskopis sel, jaringan, dan organ manusia.'],
                ['PD-103', 'Biokimia Kedokteran', 3, 'Proses biokimia tubuh dan kaitannya dengan fungsi normal serta gangguan kesehatan.'],
                ['PD-104', 'Keterampilan Klinis Dasar', 2, 'Komunikasi klinis, keselamatan pasien, pemeriksaan dasar, dan etika profesi.'],
            ],
            'KG' => [
                ['KG-101', 'Anatomi Gigi dan Mulut', 4, 'Morfologi, struktur, dan fungsi gigi serta rongga mulut.'],
                ['KG-102', 'Biomaterial Kedokteran Gigi', 3, 'Sifat dan pemilihan material yang digunakan dalam kedokteran gigi.'],
                ['KG-103', 'Histologi dan Embriologi Oral', 3, 'Perkembangan dan struktur mikroskopis jaringan rongga mulut.'],
                ['KG-104', 'Ilmu Kedokteran Gigi Pencegahan', 2, 'Pencegahan penyakit gigi dan mulut serta edukasi kesehatan masyarakat.'],
            ],
            'FAR' => [
                ['FAR-101', 'Farmakologi Dasar', 4, 'Mekanisme kerja obat dalam tubuh dan efek terapinya.'],
                ['FAR-102', 'Kimia Farmasi', 3, 'Analisis senyawa kimia obat dan dasar sintesis bahan obat.'],
                ['FAR-103', 'Farmasetika Dasar', 3, 'Bentuk sediaan, perhitungan dosis, dan dasar peracikan obat.'],
                ['FAR-104', 'Farmakognosi', 2, 'Bahan alam sebagai sumber obat, identifikasi, dan pengendalian mutunya.'],
            ],
            'KEP' => [
                ['KEP-101', 'Fundamental Keperawatan', 4, 'Konsep dasar profesi keperawatan, etika, dan asuhan keperawatan.'],
                ['KEP-102', 'Komunikasi Terapeutik Keperawatan', 3, 'Teknik komunikasi efektif dengan pasien dan keluarga.'],
                ['KEP-103', 'Anatomi dan Fisiologi', 3, 'Struktur dan fungsi sistem tubuh sebagai dasar pemberian asuhan keperawatan.'],
                ['KEP-104', 'Kebutuhan Dasar Manusia', 2, 'Pengkajian dan pemenuhan kebutuhan dasar manusia dalam berbagai kondisi kesehatan.'],
            ],
            'KEB' => [
                ['KEB-101', 'Konsep Kebidanan dan Etikolegal', 4, 'Fondasi profesi bidan, etika pelayanan, dan aspek hukum kebidanan.'],
                ['KEB-102', 'Asuhan Kebidanan Masa Nifas', 3, 'Asuhan bagi ibu dan bayi pada masa setelah persalinan.'],
                ['KEB-103', 'Anatomi dan Fisiologi Reproduksi', 3, 'Struktur dan fungsi sistem reproduksi sebagai dasar pelayanan kebidanan.'],
                ['KEB-104', 'Kesehatan Ibu dan Anak', 2, 'Pelayanan promotif dan preventif bagi ibu, bayi, dan anak.'],
            ],
            'TI' => [
                ['IF-201', 'Algoritma dan Pemrograman', 4, 'Algoritma, logika pemrograman, dan struktur data dasar.'],
                ['IF-202', 'Basis Data', 3, 'Pemodelan data, normalisasi, SQL, dan basis data relasional.'],
                ['IF-203', 'Matematika Diskrit', 3, 'Logika, himpunan, relasi, kombinatorika, dan graf untuk komputasi.'],
                ['IF-204', 'Sistem Operasi', 3, 'Proses, memori, sistem berkas, dan pengelolaan sumber daya komputer.'],
            ],
            'TS' => [
                ['TS-101', 'Mekanika Rekayasa', 4, 'Analisis statika dan mekanika struktur bangunan sipil.'],
                ['TS-102', 'Bahan Konstruksi Bangunan', 3, 'Karakteristik dan pengujian material beton, baja, dan kayu.'],
                ['TS-103', 'Matematika Rekayasa', 3, 'Matematika terapan untuk pemodelan masalah teknik sipil.'],
                ['TS-104', 'Gambar Teknik dan CAD', 3, 'Gambar teknik, interpretasi gambar, dan pemodelan dasar berbantuan komputer.'],
            ],
            'TE' => [
                ['TE-101', 'Rangkaian Listrik', 4, 'Analisis rangkaian listrik arus searah dan bolak-balik.'],
                ['TE-102', 'Elektronika Dasar', 3, 'Prinsip kerja komponen semikonduktor, dioda, dan transistor.'],
                ['TE-103', 'Matematika Teknik Elektro', 3, 'Matematika terapan untuk analisis sistem dan rangkaian listrik.'],
                ['TE-104', 'Pengukuran dan Instrumentasi', 3, 'Pengukuran besaran listrik, penggunaan instrumen, dan analisis ketidakpastian.'],
            ],
            'TIN' => [
                ['TIN-101', 'Pengantar Teknik Industri', 3, 'Sistem terintegrasi, perancangan kerja, dan ergonomi.'],
                ['TIN-102', 'Analisis dan Perancangan Kerja', 4, 'Pengukuran waktu kerja, tata letak fasilitas, dan efisiensi sistem.'],
                ['TIN-103', 'Statistika Industri', 3, 'Statistika untuk analisis kualitas, proses, dan keputusan industri.'],
                ['TIN-104', 'Ergonomi Industri', 3, 'Perancangan sistem kerja yang aman, nyaman, dan sesuai kemampuan manusia.'],
            ],
            'SI' => [
                ['SI-201', 'Analisis dan Desain Sistem Informasi', 4, 'Metodologi analisis kebutuhan dan desain sistem informasi.'],
                ['SI-202', 'Manajemen Basis Data Enterprise', 3, 'Pengelolaan basis data untuk mendukung sistem informasi organisasi.'],
                ['SI-203', 'Proses Bisnis dan Sistem Informasi', 3, 'Analisis proses bisnis dan pemetaan kebutuhan informasi organisasi.'],
                ['SI-204', 'Pemrograman untuk Sistem Informasi', 3, 'Dasar pemrograman untuk membangun solusi sistem informasi.'],
            ],
        ];

        $generalCourses = [
            ['MKU1', 'Pendidikan Pancasila', 2, 'Pemahaman dan penerapan nilai Pancasila dalam kehidupan bermasyarakat dan bernegara.'],
            ['MKU2', 'Bahasa Inggris Akademik', 2, 'Keterampilan bahasa Inggris untuk membaca, menulis, dan presentasi akademik.'],
        ];

        $programs = ProgramStudi::whereIn('kode_prodi', array_keys($coursesByProgram))->get()->keyBy('kode_prodi');
        if ($programs->count() !== count($coursesByProgram)) {
            throw new RuntimeException('Seeder mata kuliah membutuhkan 10 program studi. Jalankan ProgramStudiSeeder terlebih dahulu.');
        }

        foreach ($coursesByProgram as $programCode => $courses) {
            $program = $programs->get($programCode);
            foreach ($courses as [$code, $name, $credits, $description]) {
                MataKuliah::updateOrCreate(
                    ['kode_mk' => $code],
                    [
                        'program_studi_id' => $program->id,
                        'nama_mk' => $name,
                        'sks' => $credits,
                        'semester_ke' => 1,
                        'deskripsi' => $description,
                    ]
                );
            }

            foreach ($generalCourses as [$generalCode, $name, $credits, $description]) {
                MataKuliah::updateOrCreate(
                    ['kode_mk' => $generalCode.'-'.$programCode],
                    [
                        'program_studi_id' => $program->id,
                        'nama_mk' => $name,
                        'sks' => $credits,
                        'semester_ke' => 1,
                        'deskripsi' => $description,
                    ]
                );
            }
        }
    }
}
