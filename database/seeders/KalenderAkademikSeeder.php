<?php

namespace Database\Seeders;

use App\Models\KalenderAkademik;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class KalenderAkademikSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->first();
        $semesters = Semester::orderBy('tahun_ajaran')->orderBy('jenis')->get();

        if ($semesters->isEmpty()) {
            $this->command->warn('No semesters found. Please run SemesterSeeder first.');
            return;
        }

        // Define comprehensive academic calendar for 2 academic years (4 semesters)
        $academicEvents = [
            // ==========================================
            // SEMESTER 1: 2023/2024 GANJIL (Agustus 2023 - Januari 2024)
            // ==========================================
            [
                'semester_key' => '2023/2024-Ganjil',
                'events' => [
                    // Awal Semester
                    ['judul' => 'Awal Semester Ganjil 2023/2024', 'tanggal_mulai' => '2023-08-14', 'tanggal_selesai' => '2023-08-14', 'jenis' => 'lainnya', 'warna' => '#002B6B', 'deskripsi' => 'Mulai efektifnya perkuliahan Semester Ganjil Tahun Akademik 2023/2024', 'lokasi' => 'Seluruh Kampus'],
                    ['judul' => 'Orientasi Mahasiswa Baru (OSPEK)', 'tanggal_mulai' => '2023-08-07', 'tanggal_selesai' => '2023-08-11', 'jenis' => 'orientasi_mahasiswa_baru', 'warna' => '#0891B2', 'deskripsi' => 'Program orientasi studi dan pengenalan kampus bagi mahasiswa baru angkatan 2023', 'lokasi' => 'Aula Utama & Gedung Serba Guna'],

                    // Pengisian KRS
                    ['judul' => 'Pengisian KRS Online', 'tanggal_mulai' => '2023-08-14', 'tanggal_selesai' => '2023-08-25', 'jenis' => 'pengisian_krs', 'warna' => '#0891B2', 'deskripsi' => 'Periode pengisian Kartu Rencana Studi (KRS) melalui sistem akademik online', 'lokasi' => 'Online (Sistem Akademik)'],
                    ['judul' => 'Perubahan KRS', 'tanggal_mulai' => '2023-08-28', 'tanggal_selesai' => '2023-09-08', 'jenis' => 'pengisian_krs', 'warna' => '#0891B2', 'deskripsi' => 'Periode perubahan KRS (tambah/hapus mata kuliah) dengan persetujuan dosen wali', 'lokasi' => 'Online (Sistem Akademik)'],
                    ['judul' => 'Penutupan KRS', 'tanggal_mulai' => '2023-09-08', 'tanggal_selesai' => '2023-09-08', 'jenis' => 'pengisian_krs', 'warna' => '#EA580C', 'deskripsi' => 'Batas akhir pengisian dan perubahan KRS Semester Ganjil 2023/2024', 'lokasi' => 'Online (Sistem Akademik)'],

                    // Libur Nasional
                    ['judul' => 'Hari Kemerdekaan RI', 'tanggal_mulai' => '2023-08-17', 'tanggal_selesai' => '2023-08-17', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Hari Kemerdekaan Republik Indonesia ke-78', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Maulid Nabi Muhammad SAW', 'tanggal_mulai' => '2023-09-27', 'tanggal_selesai' => '2023-09-27', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Maulid Nabi Muhammad SAW', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Hari Pancasila', 'tanggal_mulai' => '2023-10-01', 'tanggal_selesai' => '2023-10-01', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Hari Lahir Pancasila', 'lokasi' => 'Seluruh Indonesia'],

                    // Pembayaran UKT
                    ['judul' => 'Pembayaran UKT Semester Ganjil - Tahap 1', 'tanggal_mulai' => '2023-07-01', 'tanggal_selesai' => '2023-08-31', 'jenis' => 'pembayaran_ukt', 'warna' => '#F59E0B', 'deskripsi' => 'Periode pembayaran UKT Semester Ganjil 2023/2024 tahap pertama', 'lokasi' => 'Bank/Bayar Online'],
                    ['judul' => 'Pembayaran UKT Semester Ganjil - Tahap 2 (Denda)', 'tanggal_mulai' => '2023-09-01', 'tanggal_selesai' => '2023-10-31', 'jenis' => 'pembayaran_ukt', 'warna' => '#EA580C', 'deskripsi' => 'Periode pembayaran UKT dengan denda keterlambatan', 'lokasi' => 'Bank/Bayar Online'],

                    // Praktikum & Kegiatan Rutin
                    ['judul' => 'Praktikum Pemrograman Dasar', 'tanggal_mulai' => '2023-08-21', 'tanggal_selesai' => '2023-08-25', 'jenis' => 'praktikum', 'warna' => '#65A30D', 'deskripsi' => 'Minggu pertama praktikum pemrograman dasar untuk mahasiswa semester 1', 'lokasi' => 'Lab Komputer 1 & 2'],
                    ['judul' => 'Praktikum Jaringan Komputer', 'tanggal_mulai' => '2023-09-04', 'tanggal_selesai' => '2023-09-08', 'jenis' => 'praktikum', 'warna' => '#65A30D', 'deskripsi' => 'Praktikum konfigurasi jaringan untuk mahasiswa semester 3', 'lokasi' => 'Lab Jaringan'],
                    ['judul' => 'Praktikum Basis Data', 'tanggal_mulai' => '2023-09-18', 'tanggal_selesai' => '2023-09-22', 'jenis' => 'praktikum', 'warna' => '#65A30D', 'deskripsi' => 'Praktikum SQL dan desain database untuk mahasiswa semester 3', 'lokasi' => 'Lab Komputer 3'],

                    // Deadline Tugas
                    ['judul' => 'Deadline Tugas Pemrograman Web Modul 1', 'tanggal_mulai' => '2023-09-15', 'tanggal_selesai' => '2023-09-15', 'jenis' => 'deadline_tugas', 'warna' => '#EA580C', 'deskripsi' => 'Pengumpulan tugas pertama mata kuliah Pemrograman Web', 'lokasi' => 'LMS / Google Classroom'],
                    ['judul' => 'Deadline Laporan Praktikum Minggu 1-4', 'tanggal_mulai' => '2023-10-06', 'tanggal_selesai' => '2023-10-06', 'jenis' => 'deadline_tugas', 'warna' => '#EA580C', 'deskripsi' => 'Pengumpulan laporan praktikum minggu 1-4', 'lokasi' => 'LMS / Dosen Pengampu'],

                    // UTS
                    ['judul' => 'UTS Semester Ganjil 2023/2024', 'tanggal_mulai' => '2023-10-16', 'tanggal_selesai' => '2023-10-27', 'jenis' => 'uts', 'warna' => '#DC2626', 'deskripsi' => 'Ujian Tengah Semester untuk seluruh mata kuliah. Jadwal detail per kelas di LMS.', 'lokasi' => 'Ruang Kelas & Aula Utama'],

                    // Seminar & Workshop
                    ['judul' => 'Seminar Nasional Teknologi Informasi', 'tanggal_mulai' => '2023-11-10', 'tanggal_selesai' => '2023-11-11', 'jenis' => 'seminar', 'warna' => '#9333EA', 'deskripsi' => 'Seminar nasional bertema "AI dan Masa Depan Pendidikan" dengan pembicara dari industri', 'lokasi' => 'Aula Utama & Zoom'],
                    ['judul' => 'Workshop Mobile Development Flutter', 'tanggal_mulai' => '2023-11-18', 'tanggal_selesai' => '2023-11-18', 'jenis' => 'workshop', 'warna' => '#0891B2', 'deskripsi' => 'Workshop hands-on pengembangan aplikasi mobile dengan Flutter', 'lokasi' => 'Lab Komputer 1'],

                    // Libur Akademik
                    ['judul' => 'Cuti Semester Ganjil / Libur Akhir Tahun', 'tanggal_mulai' => '2023-12-23', 'tanggal_selesai' => '2024-01-07', 'jenis' => 'libur_akademik', 'warna' => '#16A34A', 'deskripsi' => 'Libur semester ganjil dan libur Natal serta Tahun Baru', 'lokasi' => 'Seluruh Kampus'],

                    // UAS
                    ['judul' => 'UAS Semester Ganjil 2023/2024', 'tanggal_mulai' => '2024-01-15', 'tanggal_selesai' => '2024-01-26', 'jenis' => 'uas', 'warna' => '#DC2626', 'deskripsi' => 'Ujian Akhir Semester untuk seluruh mata kuliah. Persiapkan kartu ujian dan KTM.', 'lokasi' => 'Ruang Kelas & Aula Utama'],

                    // Pengumuman Nilai & KHS
                    ['judul' => 'Pengumuman Nilai UAS Semester Ganjil', 'tanggal_mulai' => '2024-02-05', 'tanggal_selesai' => '2024-02-09', 'jenis' => 'pengumuman_nilai', 'warna' => '#9333EA', 'deskripsi' => 'Publikasi nilai UAS dan nilai akhir semester melalui sistem akademik', 'lokasi' => 'Online (Sistem Akademik)'],
                    ['judul' => 'Pengisian KHS Semester Ganjil', 'tanggal_mulai' => '2024-02-12', 'tanggal_selesai' => '2024-02-23', 'jenis' => 'pengisian_khs', 'warna' => '#0891B2', 'deskripsi' => 'Periode pengisian Kartu Hasil Studi (KHS) Semester Ganjil 2023/2024', 'lokasi' => 'Online (Sistem Akademik)'],
                ]
            ],

            // ==========================================
            // SEMESTER 2: 2023/2024 GENAP (Februari 2024 - Juli 2024)
            // ==========================================
            [
                'semester_key' => '2023/2024-Genap',
                'events' => [
                    // Awal Semester
                    ['judul' => 'Awal Semester Genap 2023/2024', 'tanggal_mulai' => '2024-02-19', 'tanggal_selesai' => '2024-02-19', 'jenis' => 'lainnya', 'warna' => '#002B6B', 'deskripsi' => 'Mulai efektifnya perkuliahan Semester Genap Tahun Akademik 2023/2024', 'lokasi' => 'Seluruh Kampus'],

                    // KRS Genap
                    ['judul' => 'Pengisian KRS Online Semester Genap', 'tanggal_mulai' => '2024-02-19', 'tanggal_selesai' => '2024-03-01', 'jenis' => 'pengisian_krs', 'warna' => '#0891B2', 'deskripsi' => 'Periode pengisian Kartu Rencana Studi (KRS) Semester Genap', 'lokasi' => 'Online (Sistem Akademik)'],
                    ['judul' => 'Perubahan KRS Semester Genap', 'tanggal_mulai' => '2024-03-04', 'tanggal_selesai' => '2024-03-15', 'jenis' => 'pengisian_krs', 'warna' => '#0891B2', 'deskripsi' => 'Periode perubahan KRS (tambah/hapus mata kuliah)', 'lokasi' => 'Online (Sistem Akademik)'],

                    // Libur Nasional Semester Genap
                    ['judul' => 'Hari Raya Nyepi', 'tanggal_mulai' => '2024-03-11', 'tanggal_selesai' => '2024-03-11', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Hari Suci Nyepi Tahun Baru Saka', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Hari Paskah', 'tanggal_mulai' => '2024-03-31', 'tanggal_selesai' => '2024-03-31', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Paskah', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Hari Buruh Internasional', 'tanggal_mulai' => '2024-05-01', 'tanggal_selesai' => '2024-05-01', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Hari Buruh Internasional', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Hari Waisak', 'tanggal_mulai' => '2024-05-23', 'tanggal_selesai' => '2024-05-23', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Hari Raya Waisak Tri Suci', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Kenaikan Isa Al-Masih', 'tanggal_mulai' => '2024-05-09', 'tanggal_selesai' => '2024-05-09', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Kenaikan Isa Al-Masih', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Hari Pancasila', 'tanggal_mulai' => '2024-06-01', 'tanggal_selesai' => '2024-06-01', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Hari Lahir Pancasila', 'lokasi' => 'Seluruh Indonesia'],

                    // Idul Fitri 2024 (Perkiraan)
                    ['judul' => 'Hari Raya Idul Fitri 1445 H', 'tanggal_mulai' => '2024-04-10', 'tanggal_selesai' => '2024-04-11', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Libur Lebaran Idul Fitri 1445 H / 2024 M', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Cuti Bersama Idul Fitri', 'tanggal_mulai' => '2024-04-08', 'tanggal_selesai' => '2024-04-12', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Cuti bersama menjelang Idul Fitri', 'lokasi' => 'Seluruh Indonesia'],

                    // Idul Adha 2024 (Perkiraan)
                    ['judul' => 'Hari Raya Idul Adha 1445 H', 'tanggal_mulai' => '2024-06-17', 'tanggal_selesai' => '2024-06-17', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Idul Adha 1445 H', 'lokasi' => 'Seluruh Indonesia'],

                    // Pembayaran UKT Genap
                    ['judul' => 'Pembayaran UKT Semester Genap - Tahap 1', 'tanggal_mulai' => '2024-01-01', 'tanggal_selesai' => '2024-02-29', 'jenis' => 'pembayaran_ukt', 'warna' => '#F59E0B', 'deskripsi' => 'Pembayaran UKT Semester Genap 2023/2024 tahap pertama', 'lokasi' => 'Bank/Bayar Online'],
                    ['judul' => 'Pembayaran UKT Semester Genap - Tahap 2 (Denda)', 'tanggal_mulai' => '2024-03-01', 'tanggal_selesai' => '2024-04-30', 'jenis' => 'pembayaran_ukt', 'warna' => '#EA580C', 'deskripsi' => 'Pembayaran UKT dengan denda keterlambatan', 'lokasi' => 'Bank/Bayar Online'],

                    // Praktikum & Kegiatan Rutin
                    ['judul' => 'Praktikum Algoritma & Struktur Data', 'tanggal_mulai' => '2024-02-26', 'tanggal_selesai' => '2024-03-01', 'jenis' => 'praktikum', 'warna' => '#65A30D', 'deskripsi' => 'Praktikum algoritma untuk mahasiswa semester 2', 'lokasi' => 'Lab Komputer 2'],
                    ['judul' => 'Praktikum Pemrograman Web Lanjut', 'tanggal_mulai' => '2024-03-11', 'tanggal_selesai' => '2024-03-15', 'jenis' => 'praktikum', 'warna' => '#65A30D', 'deskripsi' => 'Praktikum framework Laravel/React untuk semester 4', 'lokasi' => 'Lab Komputer 1'],
                    ['judul' => 'Praktikum Kecerdasan Buatan', 'tanggal_mulai' => '2024-04-08', 'tanggal_selesai' => '2024-04-12', 'jenis' => 'praktikum', 'warna' => '#65A30D', 'deskripsi' => 'Praktikum Machine Learning untuk semester 6', 'lokasi' => 'Lab AI'],

                    // Deadline Tugas
                    ['judul' => 'Deadline Proyek Tengah Semester', 'tanggal_mulai' => '2024-04-19', 'tanggal_selesai' => '2024-04-19', 'jenis' => 'deadline_tugas', 'warna' => '#EA580C', 'deskripsi' => 'Pengumpulan proyek tengah semester mata kuliah Proyek Integratif', 'lokasi' => 'LMS / GitHub Classroom'],
                    ['judul' => 'Deadline Skripsi Periode April', 'tanggal_mulai' => '2024-04-26', 'tanggal_selesai' => '2024-04-26', 'jenis' => 'deadline_skripsi', 'warna' => '#EA580C', 'deskripsi' => 'Batas akhir pengumpulan skripsi untuk wisuda periode April', 'lokasi' => 'Sistem Repository Kampus'],

                    // UTS Genap
                    ['judul' => 'UTS Semester Genap 2023/2024', 'tanggal_mulai' => '2024-04-22', 'tanggal_selesai' => '2024-05-03', 'jenis' => 'uts', 'warna' => '#DC2626', 'deskripsi' => 'Ujian Tengah Semester Genap. Jadwal detail per kelas di LMS.', 'lokasi' => 'Ruang Kelas & Aula Utama'],

                    // Seminar & Workshop
                    ['judul' => 'Seminar Proposal Skripsi Periode Mei', 'tanggal_mulai' => '2024-05-13', 'tanggal_selesai' => '2024-05-17', 'jenis' => 'seminar', 'warna' => '#9333EA', 'deskripsi' => 'Seminar proposal skripsi mahasiswa semester 8', 'lokasi' => 'Ruang Rapat Prodi & Online'],
                    ['judul' => 'Workshop Data Science & Visualization', 'tanggal_mulai' => '2024-05-25', 'tanggal_selesai' => '2024-05-25', 'jenis' => 'workshop', 'warna' => '#0891B2', 'deskripsi' => 'Workshop visualisasi data dengan Python (Matplotlib, Seaborn, Plotly)', 'lokasi' => 'Lab Komputer 3'],
                    ['judul' => 'Seminar Nasional Cyber Security', 'tanggal_mulai' => '2024-06-07', 'tanggal_selesai' => '2024-06-08', 'jenis' => 'seminar', 'warna' => '#9333EA', 'deskripsi' => 'Seminar keamanan siber dengan tema "Melindungi Aset Digital di Era AI"', 'lokasi' => 'Aula Utama'],

                    // Presentasi Proyek
                    ['judul' => 'Presentasi Proyek Akhir Capstone', 'tanggal_mulai' => '2024-06-10', 'tanggal_selesai' => '2024-06-14', 'jenis' => 'presentasi_proyek', 'warna' => '#65A30D', 'deskripsi' => 'Presentasi proyek capstone mahasiswa semester 8', 'lokasi' => 'Ruang Seminar Prodi'],

                    // Sidang
                    ['judul' => 'Sidang Skripsi Periode Juni', 'tanggal_mulai' => '2024-06-17', 'tanggal_selesai' => '2024-06-28', 'jenis' => 'sidang', 'warna' => '#E11D48', 'deskripsi' => 'Sidang skripsi mahasiswa untuk wisuda periode Agustus', 'lokasi' => 'Ruang Sidang Prodi'],

                    // Libur Akademik
                    ['judul' => 'Cuti Semester Genap / Libur Antara Semester', 'tanggal_mulai' => '2024-07-13', 'tanggal_selesai' => '2024-08-11', 'jenis' => 'libur_akademik', 'warna' => '#16A34A', 'deskripsi' => 'Libur semester genap dan persiapan semester ganjil baru', 'lokasi' => 'Seluruh Kampus'],

                    // UAS Genap
                    ['judul' => 'UAS Semester Genap 2023/2024', 'tanggal_mulai' => '2024-07-01', 'tanggal_selesai' => '2024-07-12', 'jenis' => 'uas', 'warna' => '#DC2626', 'deskripsi' => 'Ujian Akhir Semester Genap. Persiapkan kartu ujian dan KTM.', 'lokasi' => 'Ruang Kelas & Aula Utama'],

                    // Pengumuman Nilai & KHS
                    ['judul' => 'Pengumuman Nilai UAS Semester Genap', 'tanggal_mulai' => '2024-07-22', 'tanggal_selesai' => '2024-07-26', 'jenis' => 'pengumuman_nilai', 'warna' => '#9333EA', 'deskripsi' => 'Publikasi nilai UAS dan nilai akhir Semester Genap 2023/2024', 'lokasi' => 'Online (Sistem Akademik)'],
                    ['judul' => 'Pengisian KHS Semester Genap', 'tanggal_mulai' => '2024-07-29', 'tanggal_selesai' => '2024-08-09', 'jenis' => 'pengisian_khs', 'warna' => '#0891B2', 'deskripsi' => 'Periode pengisian Kartu Hasil Studi (KHS) Semester Genap', 'lokasi' => 'Online (Sistem Akademik)'],

                    // Wisuda
                    ['judul' => 'Wisuda Periode Agustus 2024', 'tanggal_mulai' => '2024-08-24', 'tanggal_selesai' => '2024-08-24', 'jenis' => 'wisuda', 'warna' => '#E11D48', 'deskripsi' => 'Prosesi wisuda untuk lulusan periode Agustus 2024', 'lokasi' => 'Gedung Serbaguna / Aula Utama'],
                ]
            ],

            // ==========================================
            // SEMESTER 3: 2024/2025 GANJIL (Agustus 2024 - Januari 2025)
            // ==========================================
            [
                'semester_key' => '2024/2025-Ganjil',
                'events' => [
                    ['judul' => 'Awal Semester Ganjil 2024/2025', 'tanggal_mulai' => '2024-08-12', 'tanggal_selesai' => '2024-08-12', 'jenis' => 'lainnya', 'warna' => '#002B6B', 'deskripsi' => 'Mulai efektifnya perkuliahan Semester Ganjil TA 2024/2025', 'lokasi' => 'Seluruh Kampus'],
                    ['judul' => 'OSPEK Mahasiswa Baru Angkatan 2024', 'tanggal_mulai' => '2024-08-05', 'tanggal_selesai' => '2024-08-09', 'jenis' => 'orientasi_mahasiswa_baru', 'warna' => '#0891B2', 'deskripsi' => 'Orientasi studi dan pengenalan kampus bagi mahasiswa baru angkatan 2024', 'lokasi' => 'Aula Utama & Gedung Serba Guna'],

                    ['judul' => 'Pengisian KRS Online Semester Ganjil', 'tanggal_mulai' => '2024-08-12', 'tanggal_selesai' => '2024-08-23', 'jenis' => 'pengisian_krs', 'warna' => '#0891B2', 'deskripsi' => 'Periode pengisian KRS Semester Ganjil 2024/2025', 'lokasi' => 'Online (Sistem Akademik)'],
                    ['judul' => 'Perubahan KRS Semester Ganjil', 'tanggal_mulai' => '2024-08-26', 'tanggal_selesai' => '2024-09-06', 'jenis' => 'pengisian_krs', 'warna' => '#0891B2', 'deskripsi' => 'Periode perubahan KRS dengan persetujuan dosen wali', 'lokasi' => 'Online (Sistem Akademik)'],

                    ['judul' => 'Hari Kemerdekaan RI ke-79', 'tanggal_mulai' => '2024-08-17', 'tanggal_selesai' => '2024-08-17', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Kemerdekaan Indonesia ke-79', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Maulid Nabi Muhammad SAW', 'tanggal_mulai' => '2024-09-16', 'tanggal_selesai' => '2024-09-16', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Maulid Nabi', 'lokasi' => 'Seluruh Indonesia'],

                    ['judul' => 'Pembayaran UKT Semester Ganjil 2024/2025 - Tahap 1', 'tanggal_mulai' => '2024-07-01', 'tanggal_selesai' => '2024-08-31', 'jenis' => 'pembayaran_ukt', 'warna' => '#F59E0B', 'deskripsi' => 'Pembayaran UKT Semester Ganjil TA 2024/2025 tahap pertama', 'lokasi' => 'Bank/Bayar Online'],

                    ['judul' => 'Praktikum Pemrograman Dasar (Angkatan 2024)', 'tanggal_mulai' => '2024-08-19', 'tanggal_selesai' => '2024-08-23', 'jenis' => 'praktikum', 'warna' => '#65A30D', 'deskripsi' => 'Minggu pertama praktikum untuk mahasiswa baru', 'lokasi' => 'Lab Komputer 1 & 2'],

                    ['judul' => 'Deadline Tugas Algoritma Minggu 1-3', 'tanggal_mulai' => '2024-09-13', 'tanggal_selesai' => '2024-09-13', 'jenis' => 'deadline_tugas', 'warna' => '#EA580C', 'deskripsi' => 'Pengumpulan tugas algoritma minggu 1-3', 'lokasi' => 'LMS'],

                    ['judul' => 'UTS Semester Ganjil 2024/2025', 'tanggal_mulai' => '2024-10-14', 'tanggal_selesai' => '2024-10-25', 'jenis' => 'uts', 'warna' => '#DC2626', 'deskripsi' => 'Ujian Tengah Semester Ganjil TA 2024/2025', 'lokasi' => 'Ruang Kelas & Aula Utama'],

                    ['judul' => 'Seminar Nasional AI & Education', 'tanggal_mulai' => '2024-11-08', 'tanggal_selesai' => '2024-11-09', 'jenis' => 'seminar', 'warna' => '#9333EA', 'deskripsi' => 'Seminar "AI dalam Pendidikan Tinggi: Peluang & Tantangan"', 'lokasi' => 'Aula Utama & Zoom'],

                    ['judul' => 'Workshop Cloud Computing (AWS)', 'tanggal_mulai' => '2024-11-16', 'tanggal_selesai' => '2024-11-16', 'jenis' => 'workshop', 'warna' => '#0891B2', 'deskripsi' => 'Workshop fundamental AWS Cloud Practitioner', 'lokasi' => 'Lab Komputer 1'],

                    ['judul' => 'Cuti Semester Ganjil / Libur Natal & Tahun Baru', 'tanggal_mulai' => '2024-12-21', 'tanggal_selesai' => '2025-01-05', 'jenis' => 'libur_akademik', 'warna' => '#16A34A', 'deskripsi' => 'Libur semester ganjil, Natal, dan Tahun Baru 2025', 'lokasi' => 'Seluruh Kampus'],

                    ['judul' => 'Hari Natal', 'tanggal_mulai' => '2024-12-25', 'tanggal_selesai' => '2024-12-25', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Hari Natal', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Tahun Baru Masehi 2025', 'tanggal_mulai' => '2025-01-01', 'tanggal_selesai' => '2025-01-01', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Tahun Baru 2025', 'lokasi' => 'Seluruh Indonesia'],

                    ['judul' => 'UAS Semester Ganjil 2024/2025', 'tanggal_mulai' => '2025-01-13', 'tanggal_selesai' => '2025-01-24', 'jenis' => 'uas', 'warna' => '#DC2626', 'deskripsi' => 'Ujian Akhir Semester Ganjil TA 2024/2025', 'lokasi' => 'Ruang Kelas & Aula Utama'],

                    ['judul' => 'Pengumuman Nilai Semester Ganjil', 'tanggal_mulai' => '2025-02-03', 'tanggal_selesai' => '2025-02-07', 'jenis' => 'pengumuman_nilai', 'warna' => '#9333EA', 'deskripsi' => 'Publikasi nilai UAS dan nilai akhir Semester Ganjil 2024/2025', 'lokasi' => 'Online (Sistem Akademik)'],
                    ['judul' => 'Pengisian KHS Semester Ganjil 2024/2025', 'tanggal_mulai' => '2025-02-10', 'tanggal_selesai' => '2025-02-21', 'jenis' => 'pengisian_khs', 'warna' => '#0891B2', 'deskripsi' => 'Periode pengisian KHS Semester Ganjil TA 2024/2025', 'lokasi' => 'Online (Sistem Akademik)'],
                ]
            ],

            // ==========================================
            // SEMESTER 4: 2024/2025 GENAP (Februari 2025 - Juli 2025)
            // ==========================================
            [
                'semester_key' => '2024/2025-Genap',
                'events' => [
                    ['judul' => 'Awal Semester Genap 2024/2025', 'tanggal_mulai' => '2025-02-17', 'tanggal_selesai' => '2025-02-17', 'jenis' => 'lainnya', 'warna' => '#002B6B', 'deskripsi' => 'Mulai efektifnya perkuliahan Semester Genap TA 2024/2025', 'lokasi' => 'Seluruh Kampus'],

                    ['judul' => 'Pengisian KRS Semester Genap', 'tanggal_mulai' => '2025-02-17', 'tanggal_selesai' => '2025-02-28', 'jenis' => 'pengisian_krs', 'warna' => '#0891B2', 'deskripsi' => 'Periode pengisian KRS Semester Genap 2024/2025', 'lokasi' => 'Online (Sistem Akademik)'],

                    ['judul' => 'Hari Raya Nyepi 2025', 'tanggal_mulai' => '2025-03-29', 'tanggal_selesai' => '2025-03-29', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Hari Suci Nyepi Tahun Baru Saka 1947', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Idul Fitri 1446 H (Perkiraan)', 'tanggal_mulai' => '2025-03-30', 'tanggal_selesai' => '2025-03-31', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perkiraan Hari Raya Idul Fitri 1446 H', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Cuti Bersama Idul Fitri', 'tanggal_mulai' => '2025-03-24', 'tanggal_selesai' => '2025-04-04', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Cuti bersama menjelang Idul Fitri', 'lokasi' => 'Seluruh Indonesia'],

                    ['judul' => 'Hari Paskah 2025', 'tanggal_mulai' => '2025-04-20', 'tanggal_selesai' => '2025-04-20', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Paskah', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Hari Buruh Internasional', 'tanggal_mulai' => '2025-05-01', 'tanggal_selesai' => '2025-05-01', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Hari Buruh', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Kenaikan Isa Al-Masih', 'tanggal_mulai' => '2025-05-29', 'tanggal_selesai' => '2025-05-29', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Kenaikan Isa Al-Masih', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Hari Waisak 2025', 'tanggal_mulai' => '2025-05-12', 'tanggal_selesai' => '2025-05-12', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Waisak Tri Suci', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Hari Pancasila', 'tanggal_mulai' => '2025-06-01', 'tanggal_selesai' => '2025-06-01', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perayaan Hari Lahir Pancasila', 'lokasi' => 'Seluruh Indonesia'],
                    ['judul' => 'Idul Adha 1446 H (Perkiraan)', 'tanggal_mulai' => '2025-06-06', 'tanggal_selesai' => '2025-06-06', 'jenis' => 'libur_nasional', 'warna' => '#16A34A', 'deskripsi' => 'Perkiraan Idul Adha 1446 H', 'lokasi' => 'Seluruh Indonesia'],

                    ['judul' => 'Pembayaran UKT Semester Genap 2024/2025', 'tanggal_mulai' => '2025-01-01', 'tanggal_selesai' => '2025-02-28', 'jenis' => 'pembayaran_ukt', 'warna' => '#F59E0B', 'deskripsi' => 'Pembayaran UKT Semester Genap tahap 1', 'lokasi' => 'Bank/Bayar Online'],

                    ['judul' => 'Praktikum Struktur Data & Algoritma', 'tanggal_mulai' => '2025-02-24', 'tanggal_selesai' => '2025-02-28', 'jenis' => 'praktikum', 'warna' => '#65A30D', 'deskripsi' => 'Praktikum struktur data untuk semester 2', 'lokasi' => 'Lab Komputer 2'],

                    ['judul' => 'UTS Semester Genap 2024/2025', 'tanggal_mulai' => '2025-04-21', 'tanggal_selesai' => '2025-05-02', 'jenis' => 'uts', 'warna' => '#DC2626', 'deskripsi' => 'Ujian Tengah Semester Genap TA 2024/2025', 'lokasi' => 'Ruang Kelas & Aula Utama'],

                    ['judul' => 'Seminar Proposal Skripsi Periode Mei', 'tanggal_mulai' => '2025-05-12', 'tanggal_selesai' => '2025-05-16', 'jenis' => 'seminar', 'warna' => '#9333EA', 'deskripsi' => 'Seminar proposal skripsi mahasiswa semester 8', 'lokasi' => 'Ruang Seminar Prodi'],

                    ['judul' => 'Workshop DevOps & CI/CD', 'tanggal_mulai' => '2025-05-24', 'tanggal_selesai' => '2025-05-24', 'jenis' => 'workshop', 'warna' => '#0891B2', 'deskripsi' => 'Workshop praktis DevOps dengan GitLab CI/CD dan Docker', 'lokasi' => 'Lab Komputer 3'],

                    ['judul' => 'Sidang Skripsi Periode Juni 2025', 'tanggal_mulai' => '2025-06-16', 'tanggal_selesai' => '2025-06-27', 'jenis' => 'sidang', 'warna' => '#E11D48', 'deskripsi' => 'Sidang skripsi untuk wisuda periode Agustus 2025', 'lokasi' => 'Ruang Sidang Prodi'],

                    ['judul' => 'Presentasi Capstone Project', 'tanggal_mulai' => '2025-06-09', 'tanggal_selesai' => '2025-06-13', 'jenis' => 'presentasi_proyek', 'warna' => '#65A30D', 'deskripsi' => 'Presentasi proyek capstone mahasiswa semester 8', 'lokasi' => 'Ruang Seminar Prodi'],

                    ['judul' => 'Cuti Semester Genap / Libur Antara Semester', 'tanggal_mulai' => '2025-07-12', 'tanggal_selesai' => '2025-08-10', 'jenis' => 'libur_akademik', 'warna' => '#16A34A', 'deskripsi' => 'Libur semester genap dan persiapan semester ganjil 2025/2026', 'lokasi' => 'Seluruh Kampus'],

                    ['judul' => 'UAS Semester Genap 2024/2025', 'tanggal_mulai' => '2025-06-30', 'tanggal_selesai' => '2025-07-11', 'jenis' => 'uas', 'warna' => '#DC2626', 'deskripsi' => 'Ujian Akhir Semester Genap TA 2024/2025', 'lokasi' => 'Ruang Kelas & Aula Utama'],

                    ['judul' => 'Pengumuman Nilai Semester Genap', 'tanggal_mulai' => '2025-07-21', 'tanggal_selesai' => '2025-07-25', 'jenis' => 'pengumuman_nilai', 'warna' => '#9333EA', 'deskripsi' => 'Publikasi nilai akhir Semester Genap 2024/2025', 'lokasi' => 'Online (Sistem Akademik)'],

                    ['judul' => 'Wisuda Periode Agustus 2025', 'tanggal_mulai' => '2025-08-23', 'tanggal_selesai' => '2025-08-23', 'jenis' => 'wisuda', 'warna' => '#E11D48', 'deskripsi' => 'Prosesi wisuda lulusan periode Agustus 2025', 'lokasi' => 'Gedung Serbaguna'],
                ]
            ],
        ];

        // Process and insert events
        $totalCreated = 0;
        $totalSkipped = 0;

        foreach ($academicEvents as $semesterData) {
            // Parse semester key: e.g., "2023/2024-Ganjil"
            $keyParts = explode('-', $semesterData['semester_key']);
            $tahunAjaran = $keyParts[0]; // e.g., "2023/2024"
            $semesterType = $keyParts[1] ?? ''; // e.g., "Ganjil" or "Genap"

            // Map to actual semester fields
            $jenisMap = [
                'Ganjil' => 'Ganjil',
                'Genap' => 'Genap',
            ];
            $jenis = $jenisMap[$semesterType] ?? $semesterType;

            // Find matching semester
            $semester = $semesters->first(function ($s) use ($tahunAjaran, $jenis) {
                return str_contains($s->tahun_ajaran, $tahunAjaran) &&
                       strtolower($s->jenis) === strtolower($jenis);
            });

            if (!$semester) {
                // Fallback: try to find any semester within the date range of events
                $firstEventDate = Carbon::parse($semesterData['events'][0]['tanggal_mulai'] ?? now());
                $semester = $semesters->first(function ($s) use ($firstEventDate) {
                    return $firstEventDate->between($s->tanggal_mulai, $s->tanggal_selesai);
                });
            }

            if (!$semester) {
                $this->command->warn("Semester not found for: {$semesterData['semester_key']} (tahun: $tahunAjaran, jenis: $jenis)");
                continue;
            }

            $this->command->info("Seeding events for: {$semester->tahun_ajaran} - {$semester->jenis} (ID: {$semester->id})");

            foreach ($semesterData['events'] as $eventData) {
                $exists = KalenderAkademik::where('semester_id', $semester->id)
                    ->where('judul', $eventData['judul'])
                    ->where('tanggal_mulai', $eventData['tanggal_mulai'])
                    ->exists();

                if ($exists) {
                    $totalSkipped++;
                    continue;
                }

                KalenderAkademik::create([
                    'semester_id' => $semester->id,
                    'judul' => $eventData['judul'],
                    'deskripsi' => $eventData['deskripsi'],
                    'tanggal_mulai' => $eventData['tanggal_mulai'],
                    'tanggal_selesai' => $eventData['tanggal_selesai'],
                    'jenis_kegiatan' => $eventData['jenis'],
                    'warna' => $eventData['warna'],
                    'is_published' => true,
                    'is_all_day' => true,
                    'waktu_mulai' => null,
                    'waktu_selesai' => null,
                    'lokasi' => $eventData['lokasi'] ?? null,
                    'created_by' => $admin?->id,
                    'updated_by' => null,
                ]);

                $totalCreated++;

                // Log activity
                \App\Models\KalenderAktivitasLog::create([
                    'user_id' => $admin?->id,
                    'user_type' => 'App\Models\User',
                    'event' => 'created',
                    'kalender_akademik_id' => null, // Will be set after create, but we're seeding
                    'kalender_akademik_judul' => $eventData['judul'],
                    'old_values' => null,
                    'new_values' => $eventData,
                    'description' => "Seeding agenda kalender akademik: {$eventData['judul']}",
                    'ip_address' => '127.0.0.1',
                    'user_agent' => 'Seeder',
                    'occurred_at' => now(),
                ]);
            }
        }

        // Also create some recurring events using factory for additional data
        $additionalEvents = 50;
        for ($i = 0; $i < $additionalEvents; $i++) {
            KalenderAkademik::factory()->published()->create([
                'semester_id' => $semesters->random()->id,
                'created_by' => $admin?->id,
            ]);
            $totalCreated++;
        }

        $this->command->info("Kalender Akademik Seeder completed: {$totalCreated} created, {$totalSkipped} skipped (already exist).");
    }
}