<?php

namespace Database\Factories;

use App\Models\KalenderAkademik;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class KalenderAkademikFactory extends Factory
{
    protected $model = KalenderAkademik::class;

    public function definition(): array
    {
        $jenisKegiatan = [
            'uts' => ['label' => 'UTS', 'warna' => '#DC2626', 'icon' => 'bi-file-earmark-text'],
            'uas' => ['label' => 'UAS', 'warna' => '#DC2626', 'icon' => 'bi-file-earmark-text'],
            'libur_nasional' => ['label' => 'Libur Nasional', 'warna' => '#16A34A', 'icon' => 'bi-calendar-x'],
            'libur_akademik' => ['label' => 'Libur Akademik', 'warna' => '#16A34A', 'icon' => 'bi-calendar-x'],
            'deadline_tugas' => ['label' => 'Deadline Tugas', 'warna' => '#EA580C', 'icon' => 'bi-clock-history'],
            'deadline_skripsi' => ['label' => 'Deadline Skripsi', 'warna' => '#EA580C', 'icon' => 'bi-mortarboard'],
            'pengumuman_nilai' => ['label' => 'Pengumuman Nilai', 'warna' => '#9333EA', 'icon' => 'bi-award'],
            'praktikum' => ['label' => 'Praktikum', 'warna' => '#65A30D', 'icon' => 'bi-beaker'],
            'wisuda' => ['label' => 'Wisuda', 'warna' => '#E11D48', 'icon' => 'bi-mortarboard-fill'],
            'orientasi_mahasiswa_baru' => ['label' => 'Orientasi Mahasiswa Baru', 'warna' => '#0891B2', 'icon' => 'bi-people'],
            'pembayaran_ukt' => ['label' => 'Pembayaran UKT', 'warna' => '#F59E0B', 'icon' => 'bi-credit-card'],
            'pengisian_krs' => ['label' => 'Pengisian KRS', 'warna' => '#0891B2', 'icon' => 'bi-journal-plus'],
            'pengisian_khs' => ['label' => 'Pengisian KHS', 'warna' => '#0891B2', 'icon' => 'bi-journal-check'],
            'cuti_akademik' => ['label' => 'Cuti Akademik', 'warna' => '#F59E0B', 'icon' => 'bi-calendar-minus'],
            'seminar' => ['label' => 'Seminar', 'warna' => '#9333EA', 'icon' => 'bi-mic'],
            'presentasi_proyek' => ['label' => 'Presentasi Proyek', 'warna' => '#65A30D', 'icon' => 'bi-diagram-3'],
            'sidang' => ['label' => 'Sidang', 'warna' => '#E11D48', 'icon' => 'bi-people'],
            'workshop' => ['label' => 'Workshop', 'warna' => '#0891B2', 'icon' => 'bi-tools'],
            'pengumuman_akademik' => ['label' => 'Pengumuman Akademik', 'warna' => '#9333EA', 'icon' => 'bi-megaphone'],
            'lainnya' => ['label' => 'Lainnya', 'warna' => '#002B6B', 'icon' => 'bi-calendar-event'],
        ];

        $jenis = array_rand($jenisKegiatan);
        $jenisData = $jenisKegiatan[$jenis];

        $semester = Semester::inRandomOrder()->first();
        if (!$semester) {
            $semester = Semester::factory()->create();
        }

        $startDate = $this->faker->dateTimeBetween($semester->tanggal_mulai, $semester->tanggal_selesai);
        $endDate = (clone $startDate)->modify('+' . rand(0, 7) . ' days');

        $isAllDay = $this->faker->boolean(70);
        $waktuMulai = !$isAllDay ? $this->faker->time('H:i') : null;
        $waktuSelesai = !$isAllDay && $waktuMulai ? Carbon::parse($waktuMulai)->addHours(rand(1, 4))->format('H:i') : null;

        $titles = [
            'uts' => ['UTS Semester Ganjil', 'UTS Semester Genap', 'Ujian Tengah Semester'],
            'uas' => ['UAS Semester Ganjil', 'UAS Semester Genap', 'Ujian Akhir Semester'],
            'libur_nasional' => ['Hari Kemerdekaan', 'Hari Raya Idul Fitri', 'Hari Raya Idul Adha', 'Tahun Baru Masehi', 'Hari Buruh Internasional', 'Hari Pancasila', 'Isra Mi\'raj', 'Hari Waisak', 'Hari Natal'],
            'libur_akademik' => ['Cuti Semester Ganjil', 'Cuti Semester Genap', 'Libur Akhir Tahun Akademik'],
            'deadline_tugas' => ['Deadline Tugas Pemrograman', 'Deadline Tugas Kalkulus', 'Deadline Laporan Praktikum', 'Deadline Makalah Akhir'],
            'deadline_skripsi' => ['Batas Akhir Pengumpulan Skripsi', 'Batas Revisi Skripsi', 'Deadline Upload Skripsi ke Repository'],
            'pengumuman_nilai' => ['Pengumuman Nilai UTS', 'Pengumuman Nilai UAS', 'Pengumuman Nilai Akhir Semester'],
            'praktikum' => ['Praktikum Pemrograman Web', 'Praktikum Jaringan Komputer', 'Praktikum Basis Data', 'Praktikum Algoritma'],
            'wisuda' => ['Wisuda Periode Januari', 'Wisuda Periode Agustus', 'Prosesi Wisuda Tahap I', 'Prosesi Wisuda Tahap II'],
            'orientasi_mahasiswa_baru' => ['OSPEK Mahasiswa Baru', 'Orientasi Akademik', 'Pengenalan Kampus', 'Pendaftaran Mahasiswa Baru'],
            'pembayaran_ukt' => ['Pembayaran UKT Semester Ganjil', 'Pembayaran UKT Semester Genap', 'Cicilan UKT Tahap 1', 'Cicilan UKT Tahap 2'],
            'pengisian_krs' => ['Pengisian KRS Online', 'Perubahan KRS', 'Penutupan KRS'],
            'pengisian_khs' => ['Pengisian KHS Semester Ganjil', 'Pengisian KHS Semester Genap'],
            'cuti_akademik' => ['Cuti Akademik Semester Ganjil', 'Cuti Akademik Semester Genap'],
            'seminar' => ['Seminar Nasional Informatika', 'Seminar Proposal Skripsi', 'Seminar Hasil Skripsi', 'Seminar Teknologi Terkini'],
            'presentasi_proyek' => ['Presentasi Proyek Akhir', 'Demo Day Capstone Project', 'Presentasi Kelompok'],
            'sidang' => ['Sidang Skripsi Mahasiswa', 'Sidang Tesis', 'Sidang Proposal'],
            'workshop' => ['Workshop Machine Learning', 'Workshop Mobile Development', 'Workshop Cyber Security', 'Workshop Data Science'],
            'pengumuman_akademik' => ['Pengumuman Jadwal Ujian', 'Pengumuman Hasil Seleksi', 'Pengumuman Beasiswa'],
            'lainnya' => ['Rapat Koordinasi Dosen', 'Audit Mutu Internal', 'Kunjungan Industri', 'Kegiatan Kemahasiswaan'],
        ];

        $title = $this->faker->randomElement($titles[$jenis] ?? ['Kegiatan Akademik']);

        $descriptions = [
            'uts' => 'Ujian Tengah Semester untuk seluruh mata kuliah wajib dan pilihan. Mohon hadir tepat waktu sesuai jadwal yang telah ditentukan.',
            'uas' => 'Ujian Akhir Semester menutup semester akademik. Persiapkan diri dengan baik dan bawa kartu ujian serta KTM.',
            'libur_nasional' => 'Libur nasional sesuai dengan keputusan pemerintah. Semua aktivitas akademik ditutup.',
            'libur_akademik' => 'Libur akademik antar semester. Gunakan waktu ini untuk istirahat dan persiapan semester berikutnya.',
            'deadline_tugas' => 'Batas akhir pengumpulan tugas. Pastikan mengumpulkan tepat waktu untuk menghindari penalti keterlambatan.',
            'deadline_skripsi' => 'Batas akhir pengumpulan dokumen skripsi. Pastikan semua revisi telah disetujui pembimbing.',
            'pengumuman_nilai' => 'Nilai akan dipublikasikan melalui sistem akademik. Cek secara berkala untuk melihat hasil.',
            'praktikum' => 'Praktikum wajib hadir. Bawa alat tulis, laporan praktikum sebelumnya, dan pakaian laboratorium sesuai standar.',
            'wisuda' => 'Prosesi wisuda untuk mahasiswa yang telah memenuhi syarat kelulusan. Undangan akan dikirim via email.',
            'orientasi_mahasiswa_baru' => 'Acara orientasi bagi mahasiswa baru untuk mengenal kampus, sistem akademik, dan fasilitas.',
            'pembayaran_ukt' => 'Wajib dibayar sebelum batas waktu untuk menghindari blokir KRS dan layanan akademik lainnya.',
            'pengisian_krs' => 'Isi KRS sesuai dengan bimbingan dosen wali. Maksimal 24 SKS per semester.',
            'pengisian_khs' => 'Isi KHS untuk merekam nilai mata kuliah yang telah diambil semester ini.',
            'cuti_akademik' => 'Mahasiswa yang mengajukan cuti akademik wajib mengisi formulir dan mendapatkan persetujuan dosen wali.',
            'seminar' => 'Seminar terbuka untuk umum. Mahasiswa diharapkan hadir untuk memperluas wawasan.',
            'presentasi_proyek' => 'Presentasi proyek akhir/kelompok. Siapkan slide presentasi maksimal 15 menit.',
            'sidang' => 'Sidang skripsi/tesis. Mahasiswa wajib hadir 30 menit sebelum jadwal dengan bawa 3 eksemplar skripsi.',
            'workshop' => 'Workshop praktis dengan pembicara ahli. Pendaftaran dibuka 1 minggu sebelum pelaksanaan.',
            'pengumuman_akademik' => 'Informasi penting seputar jadwal, kebijakan baru, dan pengumuman resmi kampus.',
            'lainnya' => 'Kegiatan rutin atau khusus yang mendukung proses pembelajaran dan pengembangan mahasiswa.',
        ];

        return [
            'semester_id' => $semester->id,
            'judul' => $title,
            'deskripsi' => $descriptions[$jenis] ?? 'Kegiatan akademik rutin semester ini.',
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'jenis_kegiatan' => $jenis,
            'warna' => $jenisData['warna'],
            'is_published' => $this->faker->boolean(90),
            'is_all_day' => $isAllDay,
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'lokasi' => $this->faker->optional(0.7)->randomElement([
                'Ruang 301, Gedung A', 'Ruang 402, Gedung B', 'Lab Komputer 1', 'Lab Komputer 2',
                'Aula Utama', 'Ruang Rapat Dekan', 'Zoom Meeting', 'Google Meet',
                'Lapangan Kampus', 'Perpustakaan Lt. 2', 'Gedung Serba Guna', 'Online (LMS)'
            ]),
            'created_by' => User::whereHas('roles', fn($q) => $q->where('name', 'admin'))->inRandomOrder()->first()?->id,
            'updated_by' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn() => ['is_published' => true]);
    }

    public function draft(): static
    {
        return $this->state(fn() => ['is_published' => false]);
    }

    public function uts(): static
    {
        return $this->state(fn() => [
            'jenis_kegiatan' => 'uts',
            'warna' => '#DC2626',
            'judul' => 'UTS Semester ' . $this->faker->randomElement(['Ganjil', 'Genap']),
        ]);
    }

    public function uas(): static
    {
        return $this->state(fn() => [
            'jenis_kegiatan' => 'uas',
            'warna' => '#DC2626',
            'judul' => 'UAS Semester ' . $this->faker->randomElement(['Ganjil', 'Genap']),
        ]);
    }

    public function liburNasional(): static
    {
        return $this->state(fn() => [
            'jenis_kegiatan' => 'libur_nasional',
            'warna' => '#16A34A',
            'is_all_day' => true,
        ]);
    }

    public function deadlineTugas(): static
    {
        return $this->state(fn() => [
            'jenis_kegiatan' => 'deadline_tugas',
            'warna' => '#EA580C',
            'judul' => 'Deadline ' . $this->faker->randomElement(['Tugas Pemrograman', 'Laporan Praktikum', 'Makalah', 'Studi Kasus']),
        ]);
    }
}