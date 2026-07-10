<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\AbsensiMahasiswa;
use App\Models\AktivitasPengguna;
use App\Models\ForumDiskusi;
use App\Models\JadwalUjian;
use App\Models\KelasMahasiswa;
use App\Models\KelasPerkuliahan;
use App\Models\KomentarDiskusi;
use App\Models\Materi;
use App\Models\MataKuliah;
use App\Models\NilaiAkhir;
use App\Models\Notifikasi;
use App\Models\PengumpulanTugas;
use App\Models\Pengumuman;
use App\Models\ProgramStudi;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ComprehensiveAcademicSeeder extends Seeder
{
    private array $materiTopics = [
        'Orientasi Perkuliahan dan Kontrak Belajar',
        'Konsep Dasar dan Fondasi Teori',
        'Pengenalan Tools dan Environment Setup',
        'Fundamental Principles dan Best Practices',
        'Studi Kasus Industri dan Real-World Applications',
        'Hands-on Workshop dan Praktik Laboratorium',
        'Analisis Masalah dan Problem Solving',
        'Perancangan Solusi dan Implementation Strategy',
        'Review Tengah Semester (Mid-review)',
        'Pembaharuan dan Deep Dive Topics',
        'Advanced Concepts dan Optimization',
        'Integration dengan Sistem Existing',
        'Project Planning dan Execution',
        'Presentasi dan Demonstration Skills',
        'Review Akhir Semester dan Q&A',
        'Final Assessment dan Refleksi Pembelajaran',
    ];

    private array $forumTopics = [
        'Diskusi Materi Minggu Ini',
        'Bantuan Praktikum dan Lab Work',
        'Share Resources dan Best Practices',
        'Tanya Jawab Umum Mata Kuliah',
        'Kolaborasi dan Study Group',
        'Feedback dan Improvement Ideas',
    ];

    private array $pengumumanTemplates = [
        'Pengumuman Penting Terkait {topik}',
        'Update Jadwal dan Perubahan {topik}',
        'Pengingat Deadline Tugas {topik}',
        'Materi Baru Telah Diunggah untuk {topik}',
        'Kesempatan Ujian Susulan {topik}',
        'Hasil {topik} Telah Diterbitkan',
        'Partisipasi Aktif Diperlukan dalam {topik}',
        'Sumber Daya Tambahan untuk {topik}',
    ];

    private array $tugasTemplates = [
        'Quiz Pemahaman Materi Minggu {no}',
        'Tugas Praktik Implementasi {no}',
        'Studi Kasus dan Analisis {no}',
        'Report Mini Project {no}',
        'Presentasi Kelompok {no}',
        'Refleksi dan Jurnal Pembelajaran {no}',
        'Assignment Review dan Feedback {no}',
        'Praktikum Laboratorium {no}',
    ];

    public function run(): void
    {
        $kelasList = KelasPerkuliahan::with(['mataKuliah.programStudi', 'dosen'])->get();
        $mahasiswaByProdi = User::role('mahasiswa')->get()->groupBy('program_studi_id');
        $admin = User::role('admin')->first();
        $dosen = User::role('dosen')->get();

        // Academic calendar
        $semesterStart = Carbon::parse('2025-09-01');
        $semesterEnd = Carbon::parse('2026-01-31');

        foreach ($kelasList as $kelas) {
            $prodiId = $kelas->mataKuliah?->program_studi_id;
            $mahasiswaDiKelas = $mahasiswaByProdi->get($prodiId, collect());

            if ($mahasiswaDiKelas->isEmpty()) {
                continue;
            }

            // Distribute mahasiswa ke kelas
            $sectionLetter = substr($kelas->kode_kelas, -1);
            $sectionIndex = ord($sectionLetter) - ord('A');
            $itemsPerSection = ceil($mahasiswaDiKelas->count() / 3);
            $offset = $sectionIndex * $itemsPerSection;

            $peserta = $mahasiswaDiKelas->slice($offset, $itemsPerSection)->values();

            if ($peserta->isEmpty()) {
                $peserta = $mahasiswaDiKelas->take(25)->values();
            }

            // Enroll mahasiswa ke kelas
            $peserta->each(function (User $mahasiswa) use ($kelas, $semesterStart) {
                KelasMahasiswa::updateOrCreate(
                    [
                        'kelas_perkuliahan_id' => $kelas->id,
                        'mahasiswa_id' => $mahasiswa->id,
                    ],
                    ['tanggal_daftar' => $semesterStart->copy()->subDays(7)]
                );
            });

            // Seed akademik data
            $this->seedMateri($kelas, $semesterStart);
            $this->seedAbsensi($kelas, $peserta, $semesterStart);
            $tugasList = $this->seedTugas($kelas, $semesterStart);
            $this->seedPengumpulanTugas($kelas, $peserta, $tugasList);
            $this->seedNilaiAkhir($kelas, $peserta, $tugasList);
            $this->seedForum($kelas, $peserta);
            $this->seedPengumuman($kelas);
            $this->seedJadwalUjian($kelas, $semesterStart, $semesterEnd);
            $this->seedNotifikasi($kelas, $peserta);
            $this->seedAktivitas($kelas, $peserta, $semesterStart);
        }
    }

    private function seedMateri(KelasPerkuliahan $kelas, Carbon $startDate): void
    {
        foreach ($this->materiTopics as $index => $topic) {
            $pertemuan = $index + 1;
            $uploadDate = $startDate->copy()->addWeeks($index - 1)->addDays(rand(0, 3));

            foreach (['pdf', 'ppt', 'video'] as $fileType) {
                Materi::updateOrCreate(
                    [
                        'kelas_perkuliahan_id' => $kelas->id,
                        'pertemuan_ke' => $pertemuan,
                        'tipe_file' => $fileType,
                    ],
                    [
                        'judul' => $topic,
                        'deskripsi' => "Pertemuan $pertemuan - $topic\n\nDalam sesi ini, kami akan membahas:\n- Konsep dan teori dasar\n- Studi kasus praktis\n- Implementasi dan demo\n- Pertanyaan dan diskusi\n\nSilakan persiapkan bahan referensi tambahan untuk pembelajaran mandiri.",
                        'file_path' => "storage/materi/{$kelas->mataKuliah->kode_mk}/p" . str_pad((string)$pertemuan, 2, '0', STR_PAD_LEFT) . ".$fileType",
                        'created_at' => $uploadDate,
                        'updated_at' => $uploadDate,
                    ]
                );
            }
        }
    }

    private function seedAbsensi(KelasPerkuliahan $kelas, $peserta, Carbon $startDate): void
    {
        for ($pertemuan = 1; $pertemuan <= 16; $pertemuan++) {
            $tanggal = $startDate->copy()->addWeeks($pertemuan - 1);

            $absensi = Absensi::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'pertemuan_ke' => $pertemuan,
                ],
                [
                    'tanggal' => $tanggal,
                    'rangkuman' => "Pertemuan $pertemuan - Pembelajaran berlangsung sesuai RPS dengan diskusi interaktif, praktik hands-on, dan sesi tanya jawab.",
                    'berita_acara' => "Dosen membuka kelas, menyampaikan learning objectives, memandu pembelajaran, dan memberi tindak lanjut.",
                ]
            );

            // Generate attendance untuk setiap mahasiswa
            $peserta->each(function (User $mahasiswa, int $index) use ($absensi, $pertemuan) {
                // Realistic attendance distribution: 80% hadir, 10% izin/sakit, 10% alpha
                $random = ($index * 7 + $pertemuan * 11) % 100;
                
                if ($random < 80) {
                    $status = 'hadir';
                } elseif ($random < 90) {
                    $status = 'izin';
                } elseif ($random < 95) {
                    $status = 'sakit';
                } else {
                    $status = 'alpha';
                }

                AbsensiMahasiswa::updateOrCreate(
                    [
                        'absensi_id' => $absensi->id,
                        'mahasiswa_id' => $mahasiswa->id,
                    ],
                    ['status' => $status]
                );
            });
        }
    }

    private function seedTugas(KelasPerkuliahan $kelas, Carbon $startDate)
    {
        $tugasConfig = [
            ['Quiz Pemahaman Minggu 1-2', 2, 10, 'Quiz online tentang konsep dasar. Waktu: 45 menit, Total soal: 25.'],
            ['Praktik Implementasi Minggu 3-4', 4, 15, 'Buatkan implementasi sederhana dari materi. Format: Laporan + Code Repository.'],
            ['Studi Kasus Kelompok Minggu 5-7', 7, 20, 'Analisis studi kasus industri secara kelompok. Presentasi dan Laporan Analisis.'],
            ['Mini Project Implementation Minggu 8-11', 11, 25, 'Implementasi project dari nol sampai testing. Deliverable: Code, Testing Report, Demo.'],
            ['Review Materi UTS Minggu 6-7', 7, 10, 'Kerjakan soal review untuk persiapan UTS. Open book, 60 menit.'],
            ['Praktik Lanjutan Minggu 12-13', 13, 15, 'Advanced hands-on workshop. Output: Dokumentasi dan Code.'],
            ['Final Project Minggu 14-15', 15, 30, 'Project akhir semester dengan standar production-ready. Deliverable: Full Documentation dan Deployment.'],
            ['Review Akhir Semester Minggu 15', 15, 10, 'Latihan soal persiapan UAS. Open discussion allowed.'],
        ];

        return collect($tugasConfig)->map(function (array $config) use ($kelas, $startDate) {
            [$judul, $mingguDeadline, $bobot, $instruksi] = $config;
            $filePath = "storage/tugas/{$kelas->mataKuliah->kode_mk}/" . Str::slug($judul) . ".pdf";

            return Tugas::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'judul' => "$judul - {$kelas->mataKuliah->nama_mk}",
                ],
                [
                    'instruksi' => $instruksi . "\n\nNilai maksimal: $bobot poin. Pengumpulan melalui platform dengan format yang ditentukan.",
                    'file_lampiran' => $filePath,
                    'deadline' => $startDate->copy()->addWeeks($mingguDeadline)->setTime(23, 59),
                    'bobot_nilai' => $bobot,
                ]
            );
        });
    }

    private function seedPengumpulanTugas(KelasPerkuliahan $kelas, $peserta, $tugasList): void
    {
        $peserta->each(function (User $mahasiswa, int $mahasiswaIdx) use ($kelas, $tugasList) {
            foreach ($tugasList as $tugaIdx => $tugas) {
                // Realistic submission distribution
                $random = ($mahasiswaIdx * 13 + $tugaIdx * 7) % 100;
                
                // 85% submit on time, 10% submit late, 5% tidak submit
                if ($random < 85) {
                    $submitted = true;
                    $submitDate = $tugas->deadline->copy()->subDays(rand(0, 3))->addHours(rand(6, 22));
                } elseif ($random < 95) {
                    $submitted = true;
                    $submitDate = $tugas->deadline->copy()->addDays(rand(1, 4))->addHours(rand(6, 22));
                } else {
                    $submitted = false;
                    $submitDate = null;
                }

                if (!$submitted) {
                    continue;
                }

                // Generate nilai realistis: 60-100, dengan beberapa var terkait submission time dan student performance
                $baseNilai = 70 + (($mahasiswaIdx * 11 + $tugaIdx * 5) % 28);
                $isLate = $submitDate > $tugas->deadline;
                $penalty = $isLate ? rand(5, 15) : 0;
                $nilai = max(50, $baseNilai - $penalty);

                $feedback = $nilai >= 85 
                    ? 'Excellent work! Analisis mendalam dan implementasi rapi. Pertahankan kualitas ini.'
                    : ($nilai >= 75
                        ? 'Baik. Perlu lebih detail dalam analisis dan dokumentasi.'
                        : 'Sudah mengikuti instruksi, namun ada beberapa aspek yang perlu ditingkatkan.');

                PengumpulanTugas::updateOrCreate(
                    [
                        'tugas_id' => $tugas->id,
                        'mahasiswa_id' => $mahasiswa->id,
                    ],
                    [
                        'file_jawaban' => "storage/pengumpulan/{$mahasiswa->nip_nim}/{$tugas->id}.pdf",
                        'catatan' => "Submission {$mahasiswa->name}. Status: " . ($isLate ? 'TERLAMBAT' : 'TEPAT WAKTU'),
                        'waktu_kumpul' => $submitDate,
                        'nilai' => $nilai,
                        'feedback_dosen' => $feedback,
                        'status' => 'dinilai',
                    ]
                );
            }
        });
    }

    private function seedNilaiAkhir(KelasPerkuliahan $kelas, $peserta, $tugasList): void
    {
        $peserta->each(function (User $mahasiswa, int $index) use ($kelas, $tugasList) {
            // Hitung nilai dari pengumpulan tugas
            $pengumpulan = PengumpulanTugas::whereHas('tugas', fn ($q) => $q->where('kelas_perkuliahan_id', $kelas->id))
                ->where('mahasiswa_id', $mahasiswa->id)
                ->get();

            $nilaiTugas = $pengumpulan->isNotEmpty() ? $pengumpulan->avg('nilai') : 60;

            // Generate nilai kehadiran berdasarkan absensi
            $hadir = AbsensiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->whereHas('absensi', fn ($q) => $q->where('kelas_perkuliahan_id', $kelas->id))
                ->where('status', 'hadir')
                ->count();
            $totalAbsensi = AbsensiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->whereHas('absensi', fn ($q) => $q->where('kelas_perkuliahan_id', $kelas->id))
                ->count();
            $nilaiKehadiran = $hadir > 0 ? (($hadir / $totalAbsensi) * 100) : 0;

            // Generate nilai komponen lainnya
            $nilaiQuiz = 65 + (($index * 13) % 30);
            $nilaiProject = 70 + (($index * 17) % 28);
            $nilaiUts = 65 + (($index * 19) % 32);
            $nilaiUas = 68 + (($index * 23) % 30);

            // Hitung nilai akhir dengan weighted average
            $nilaiAkhir = (
                ($nilaiKehadiran * 0.10) +
                ($nilaiTugas * 0.20) +
                ($nilaiQuiz * 0.10) +
                ($nilaiProject * 0.20) +
                ($nilaiUts * 0.20) +
                ($nilaiUas * 0.20)
            );

            // Tentukan grade
            $grade = match (true) {
                $nilaiAkhir >= 85 => 'A',
                $nilaiAkhir >= 75 => 'B',
                $nilaiAkhir >= 65 => 'C',
                $nilaiAkhir >= 55 => 'D',
                default => 'E',
            };

            NilaiAkhir::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'mahasiswa_id' => $mahasiswa->id,
                ],
                [
                    'nilai_kehadiran' => round($nilaiKehadiran, 2),
                    'nilai_tugas' => round($nilaiTugas, 2),
                    'nilai_quiz' => round($nilaiQuiz, 2),
                    'nilai_project' => round($nilaiProject, 2),
                    'nilai_uts' => round($nilaiUts, 2),
                    'nilai_uas' => round($nilaiUas, 2),
                    'nilai_akhir' => round($nilaiAkhir, 2),
                    'grade' => $grade,
                    'catatan' => 'Nilai telah dievaluasi berdasarkan komponen penilaian yang telah ditentukan dalam RPS.',
                ]
            );
        });
    }

    private function seedForum(KelasPerkuliahan $kelas, $peserta): void
    {
        $dosen = $kelas->dosen;

        foreach ($this->forumTopics as $topicIdx => $topik) {
            // Forum dibuat oleh dosen
            $forum = ForumDiskusi::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'dibuat_oleh' => $dosen->id,
                    'judul' => "$topik - {$kelas->mataKuliah->nama_mk}",
                ],
                [
                    'isi' => "Topik diskusi: $topik\n\nSilakan bagikan pandangan, pertanyaan, atau pengalaman Anda terkait topik ini. Mari berdiskusi secara konstruktif dan saling belajar.",
                    'created_at' => now()->subDays(rand(0, 30)),
                ]
            );

            // Tambahkan komentar dari mahasiswa dan dosen
            $participantCount = min(rand(3, $peserta->count()), $peserta->count());
            for ($i = 0; $i < $participantCount; $i++) {
                $commenter = $i % 2 === 0 ? $dosen : $peserta[rand(0, $peserta->count() - 1)];

                KomentarDiskusi::updateOrCreate(
                    [
                        'forum_diskusi_id' => $forum->id,
                        'user_id' => $commenter->id,
                    ],
                    [
                        'isi' => $this->generateKomentar($commenter, $topik),
                        'created_at' => $forum->created_at->copy()->addHours(rand(1, 24) * ($i + 1)),
                    ]
                );
            }
        }
    }

    private function seedPengumuman(KelasPerkuliahan $kelas): void
    {
        $dosen = $kelas->dosen;
        $topics = ['Materi', 'Deadline Tugas', 'Jadwal Ujian', 'Nilai', 'Pengingat Penting', 'Update Sistem'];

        foreach ($topics as $idx => $topik) {
            $template = $this->pengumumanTemplates[$idx % count($this->pengumumanTemplates)];
            $judul = str_replace('{topik}', $topik, $template);

            Pengumuman::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'dibuat_oleh' => $dosen->id,
                    'judul' => $judul,
                ],
                [
                    'isi' => $this->generatePengumuman($topik, $kelas),
                    'untuk_semua' => false,
                    'created_at' => now()->subDays(rand(1, 45)),
                ]
            );
        }
    }

    private function seedJadwalUjian(KelasPerkuliahan $kelas, Carbon $start, Carbon $end): void
    {
        // UTS di minggu 8
        JadwalUjian::updateOrCreate(
            [
                'kelas_perkuliahan_id' => $kelas->id,
                'jenis_ujian' => 'UTS',
            ],
            [
                'tanggal_ujian' => $start->copy()->addWeeks(8)->format('Y-m-d'),
                'jam_mulai' => '09:00',
                'jam_selesai' => '11:00',
                'ruangan' => $kelas->ruangan,
                'catatan' => 'Ujian Tengah Semester - Materi minggu 1-7',
                'is_published' => true,
            ]
        );

        // UAS di minggu 16
        JadwalUjian::updateOrCreate(
            [
                'kelas_perkuliahan_id' => $kelas->id,
                'jenis_ujian' => 'UAS',
            ],
            [
                'tanggal_ujian' => $start->copy()->addWeeks(16)->format('Y-m-d'),
                'jam_mulai' => '09:00',
                'jam_selesai' => '11:00',
                'ruangan' => $kelas->ruangan,
                'catatan' => 'Ujian Akhir Semester - Materi minggu 1-16',
                'is_published' => true,
            ]
        );
    }

    private function seedNotifikasi(KelasPerkuliahan $kelas, $peserta): void
    {
        $notificationTypes = [
            'informasi' => 'Pengumuman baru di kelas Anda',
            'tugas' => 'Tugas baru telah diberikan',
            'ujian' => 'Jadwal ujian telah diumumkan',
            'nilai' => 'Nilai Anda telah diterbitkan',
            'presensi' => 'Presensi telah dicatat',
        ];

        foreach ($peserta as $mahasiswa) {
            foreach ($notificationTypes as $tipe => $judul) {
                Notifikasi::updateOrCreate(
                    [
                        'user_id' => $mahasiswa->id,
                        'kelas_perkuliahan_id' => $kelas->id,
                        'judul' => "$judul - {$kelas->mataKuliah->nama_mk}",
                    ],
                    [
                        'pesan' => "Ada update terbaru di kelas Anda. Silakan cek platform untuk informasi lengkap.",
                        'tipe' => $tipe,
                        'dibaca_pada' => null,
                        'created_at' => now()->subDays(rand(0, 30)),
                    ]
                );
            }
        }
    }

    private function seedAktivitas(KelasPerkuliahan $kelas, $peserta, Carbon $startDate): void
    {
        $activityTypes = ['login', 'view_materi', 'download_materi', 'submit_tugas', 'forum_post'];

        foreach ($peserta as $mahasiswa) {
            for ($i = 0; $i < rand(10, 30); $i++) {
                $type = $activityTypes[rand(0, count($activityTypes) - 1)];
                $terjadi = $startDate->copy()->addDays(rand(0, 120))->addHours(rand(6, 22));
                
                AktivitasPengguna::create([
                    'user_id' => $mahasiswa->id,
                    'kelas_perkuliahan_id' => $kelas->id,
                    'aksi' => $type,
                    'deskripsi' => $this->getActivityDescription($type, $kelas),
                    'ip_address' => '192.168.' . rand(1, 255) . '.' . rand(1, 255),
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                    'terjadi_pada' => $terjadi,
                ]);
            }
        }
    }

    private function generateKomentar(User $user, string $topik): string
    {
        $komentar = [
            'Topik yang sangat menarik, terima kasih sudah membuat diskusi ini.',
            'Saya setuju dengan pandangan di atas. Menambahkan bahwa...',
            'Ada pertanyaan, apakah ada referensi lebih lanjut tentang ' . $topik . '?',
            'Berdasarkan pengalaman saya, perspektif lain yang bisa ditambahkan adalah...',
            'Diskusi yang sangat berguna. Semoga bisa implementasi dalam project kami.',
            'Terima kasih atas insight-nya. Sangat membantu dalam memahami konsep ini.',
        ];
        return $komentar[array_rand($komentar)];
    }

    private function generatePengumuman(string $topik, KelasPerkuliahan $kelas): string
    {
        $pengumuman = [
            'Materi' => "Materi pembelajaran untuk minggu ini telah diunggah di sistem. Silakan pelajari dan siapkan pertanyaan untuk diskusi di kelas.",
            'Deadline Tugas' => "Pengingat: Deadline untuk tugas belum diunggah adalah 23:59 hari ini. Pastikan upload tepat waktu.",
            'Jadwal Ujian' => "Jadwal ujian telah ditentukan seperti yang ada di sistem. Silakan persiapkan diri dengan baik.",
            'Nilai' => "Nilai untuk tugas/ujian telah diterbitkan. Silakan cek portal akademik dan hubungi dosen jika ada pertanyaan.",
            'Pengingat Penting' => "Pengingat untuk aktif berpartisipasi di forum kelas. Partisipasi aktif akan mempengaruhi nilai.",
            'Update Sistem' => "Ada update pada sistem pembelajaran. Pastikan refresh browser untuk fitur terbaru.",
        ];
        
        return $pengumuman[$topik] ?? "Pengumuman terkait $topik di mata kuliah {$kelas->mataKuliah->nama_mk}.";
    }

    private function getActivityDescription(string $type, KelasPerkuliahan $kelas): string
    {
        $descriptions = [
            'login' => 'Login ke platform pembelajaran',
            'view_materi' => "Melihat materi {$kelas->mataKuliah->nama_mk}",
            'download_materi' => "Mengunduh file materi {$kelas->mataKuliah->nama_mk}",
            'submit_tugas' => "Mengumpulkan tugas untuk {$kelas->mataKuliah->nama_mk}",
            'forum_post' => "Memposting komentar di forum kelas",
        ];
        
        return $descriptions[$type] ?? 'Aktivitas di kelas';
    }
}
