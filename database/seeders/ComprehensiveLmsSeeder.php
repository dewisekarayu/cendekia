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
use App\Models\NilaiAkhir;
use App\Models\Notifikasi;
use App\Models\PengumpulanTugas;
use App\Models\Pengumuman;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ComprehensiveLmsSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = KelasPerkuliahan::with(['mataKuliah.programStudi', 'dosen'])->get();
        $mahasiswaByProdi = User::role('mahasiswa')->get()->groupBy('program_studi_id');
        $admin = User::role('admin')->first();
        $startDate = Carbon::parse('2025-09-01');

        $kelasList->each(function (KelasPerkuliahan $kelas, int $kelasIndex) use ($mahasiswaByProdi, $admin, $startDate) {
            $prodiId = $kelas->mataKuliah?->program_studi_id;
            $pool = $mahasiswaByProdi->get($prodiId, collect())->values();

            if ($pool->isEmpty()) {
                return;
            }

            $sectionOffset = str_ends_with($kelas->kode_kelas, '-B') ? 25 : 0;
            $peserta = $pool->slice($sectionOffset, 25)->values();

            if ($peserta->isEmpty()) {
                $peserta = $pool->take(25)->values();
            }

            $peserta->each(function (User $mahasiswa, int $index) use ($kelas, $startDate) {
                KelasMahasiswa::updateOrCreate(
                    [
                        'kelas_perkuliahan_id' => $kelas->id,
                        'mahasiswa_id' => $mahasiswa->id,
                    ],
                    ['tanggal_daftar' => $startDate->copy()->subDays(7)->addHours($index)]
                );
            });

            $this->seedMateri($kelas, $startDate);
            $this->seedAbsensi($kelas, $peserta, $startDate);
            $tugasList = $this->seedTugas($kelas, $startDate);
            $this->seedPengumpulanDanNilai($kelas, $peserta, $tugasList);
            $this->seedForum($kelas, $peserta, $kelasIndex);
            $this->seedPengumuman($kelas, $admin ?: $kelas->dosen, $startDate);
            $this->seedJadwalUjian($kelas, $startDate);
            $this->seedNotifikasi($kelas, $peserta);
            $this->seedAktivitas($kelas, $peserta, $startDate);
        });
    }

    private function seedMateri(KelasPerkuliahan $kelas, Carbon $startDate): void
    {
        $topics = [
            'Orientasi Perkuliahan dan Kontrak Belajar',
            'Konsep Dasar ' . $kelas->mataKuliah->nama_mk,
            'Terminologi dan Lingkup Implementasi',
            'Praktik Laboratorium Terarah',
            'Studi Kasus Industri',
            'Analisis Masalah dan Kebutuhan',
            'Perancangan Solusi',
            'Review Materi Pra-UTS',
            'Pendalaman Konsep Setelah UTS',
            'Praktik Kolaboratif',
            'Evaluasi Kualitas Solusi',
            'Integrasi dengan Sistem Nyata',
            'Mini Project',
            'Presentasi Progres Project',
            'Review UAS dan Refleksi',
            'Final Project dan Portofolio',
        ];

        foreach ($topics as $index => $topic) {
            $pertemuan = $index + 1;

            foreach (['pdf', 'ppt', 'video'] as $type) {
                Materi::updateOrCreate(
                    [
                        'kelas_perkuliahan_id' => $kelas->id,
                        'pertemuan_ke' => $pertemuan,
                        'tipe_file' => $type,
                    ],
                    [
                        'judul' => $topic,
                        'deskripsi' => 'Pertemuan ' . $pertemuan . ' membahas ' . strtolower($topic) . ' pada mata kuliah ' . $kelas->mataKuliah->nama_mk . '. Ringkasan mencakup tujuan belajar, contoh praktik, referensi bacaan, dan tindak lanjut mandiri.',
                        'file_path' => 'dummy/materi/' . strtolower($kelas->mataKuliah->kode_mk) . '/p' . str_pad((string) $pertemuan, 2, '0', STR_PAD_LEFT) . '.' . $type,
                    ]
                );
            }
        }
    }

    private function seedAbsensi(KelasPerkuliahan $kelas, $peserta, Carbon $startDate): void
    {
        for ($pertemuan = 1; $pertemuan <= 16; $pertemuan++) {
            $absensi = Absensi::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'pertemuan_ke' => $pertemuan,
                ],
                [
                    'tanggal' => $startDate->copy()->addWeeks($pertemuan - 1),
                    'rangkuman' => 'Pembelajaran pertemuan ' . $pertemuan . ' berjalan sesuai RPS dengan diskusi, praktik, dan tanya jawab.',
                    'berita_acara' => 'Dosen membuka kelas, menyampaikan materi, memandu latihan, lalu memberi tindak lanjut.',
                ]
            );

            $peserta->each(function (User $mahasiswa, int $index) use ($absensi, $pertemuan) {
                $score = ($index + $pertemuan) % 20;
                $status = match (true) {
                    $score === 0 => 'alpha',
                    $score <= 2 => 'izin',
                    $score <= 4 => 'sakit',
                    default => 'hadir',
                };

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
        $items = [
            ['Analisis Konsep Dasar', 4, 15],
            ['Quiz Terstruktur Pra-UTS', 7, 10],
            ['Project Mini Implementasi', 11, 25],
            ['Laporan Final Project', 15, 30],
        ];

        return collect($items)->map(function (array $item) use ($kelas, $startDate) {
            [$judul, $week, $bobot] = $item;

            return Tugas::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'judul' => $judul . ' - ' . $kelas->mataKuliah->nama_mk,
                ],
                [
                    'instruksi' => 'Kerjakan ' . strtolower($judul) . ' berdasarkan materi kelas, sertakan referensi, bukti praktik, dan refleksi pembelajaran.',
                    'file_lampiran' => 'dummy/tugas/' . strtolower($kelas->mataKuliah->kode_mk) . '-' . str_replace(' ', '-', strtolower($judul)) . '.pdf',
                    'deadline' => $startDate->copy()->addWeeks($week)->setTime(23, 59),
                    'bobot_nilai' => $bobot,
                ]
            );
        });
    }

    private function seedPengumpulanDanNilai(KelasPerkuliahan $kelas, $peserta, $tugasList): void
    {
        $peserta->each(function (User $mahasiswa, int $index) use ($kelas, $tugasList) {
            $nilaiTugas = [];

            foreach ($tugasList as $taskIndex => $tugas) {
                $base = 68 + (($index * 7 + $taskIndex * 5) % 28);
                $late = ($index + $taskIndex) % 13 === 0;
                $nilai = max(50, $base - ($late ? 8 : 0));

                PengumpulanTugas::updateOrCreate(
                    [
                        'tugas_id' => $tugas->id,
                        'mahasiswa_id' => $mahasiswa->id,
                    ],
                    [
                        'file_jawaban' => 'dummy/pengumpulan/' . $mahasiswa->nip_nim . '/' . $tugas->id . '.pdf',
                        'catatan' => 'Jawaban sudah mencakup analisis, praktik, dan kesimpulan singkat.',
                        'waktu_kumpul' => $tugas->deadline->copy()->subDays(($index + $taskIndex) % 4)->addHours($index % 6),
                        'nilai' => $nilai,
                        'feedback_dosen' => $nilai >= 85 ? 'Analisis kuat dan bukti praktik rapi.' : 'Sudah baik, perlu memperjelas argumen dan dokumentasi.',
                        'status' => 'dinilai',
                    ]
                );

                $nilaiTugas[] = $nilai;
            }

            $hadir = AbsensiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->whereHas('absensi', fn ($query) => $query->where('kelas_perkuliahan_id', $kelas->id))
                ->where('status', 'hadir')
                ->count();
            $nilaiKehadiran = round(($hadir / 16) * 100, 2);
            $rataTugas = round(array_sum($nilaiTugas) / count($nilaiTugas), 2);
            $nilaiQuiz = min(100, $rataTugas + (($index % 7) - 3));
            $nilaiProject = $nilaiTugas[2] ?? $rataTugas;
            $nilaiUts = min(100, 65 + (($index * 3) % 31));
            $nilaiUas = min(100, 66 + (($index * 5) % 30));
            $nilaiAkhir = round(($nilaiKehadiran * 0.10) + ($rataTugas * 0.20) + ($nilaiQuiz * 0.10) + ($nilaiProject * 0.20) + ($nilaiUts * 0.20) + ($nilaiUas * 0.20), 2);

            NilaiAkhir::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'mahasiswa_id' => $mahasiswa->id,
                ],
                [
                    'nilai_kehadiran' => $nilaiKehadiran,
                    'nilai_tugas' => $rataTugas,
                    'nilai_quiz' => $nilaiQuiz,
                    'nilai_project' => $nilaiProject,
                    'nilai_uts' => $nilaiUts,
                    'nilai_uas' => $nilaiUas,
                    'nilai_akhir' => $nilaiAkhir,
                    'grade' => $this->gradeFor($nilaiAkhir),
                    'catatan' => $nilaiAkhir >= 80 ? 'Kinerja konsisten sepanjang semester.' : 'Perlu menjaga ritme belajar dan memperkuat latihan mandiri.',
                ]
            );
        });
    }

    private function seedForum(KelasPerkuliahan $kelas, $peserta, int $kelasIndex): void
    {
        foreach (['Diskusi Studi Kasus Minggu 5', 'Tanya Jawab Project Akhir', 'Review Materi UAS'] as $index => $judul) {
            $creator = $index === 0 ? $kelas->dosen : $peserta[($kelasIndex + $index) % $peserta->count()];
            $forum = ForumDiskusi::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'judul' => $judul . ' - ' . $kelas->mataKuliah->kode_mk,
                ],
                [
                    'dibuat_oleh' => $creator->id,
                    'isi' => 'Silakan berbagi temuan, pertanyaan, dan contoh implementasi yang relevan dengan ' . $kelas->mataKuliah->nama_mk . '.',
                ]
            );

            for ($i = 0; $i < 5; $i++) {
                KomentarDiskusi::updateOrCreate(
                    [
                        'forum_diskusi_id' => $forum->id,
                        'user_id' => $peserta[($i + $index) % $peserta->count()]->id,
                    ],
                    ['isi' => 'Menurut saya poin pentingnya adalah menghubungkan teori dengan latihan praktikum agar solusi lebih mudah diuji.']
                );
            }
        }
    }

    private function seedPengumuman(KelasPerkuliahan $kelas, User $pembuat, Carbon $startDate): void
    {
        foreach ([
            ['Materi baru sudah tersedia', 1],
            ['Pengingat deadline tugas analisis', 4],
            ['Jadwal UTS dan tata tertib', 8],
            ['Brief project akhir semester', 12],
            ['Rekap nilai akhir dapat dicek', 16],
        ] as [$judul, $week]) {
            Pengumuman::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'judul' => $judul . ' - ' . $kelas->mataKuliah->kode_mk,
                ],
                [
                    'dibuat_oleh' => $pembuat->id,
                    'isi' => $judul . ' untuk kelas ' . $kelas->kode_kelas . '. Mahasiswa diminta membaca detail dan menindaklanjuti sesuai arahan dosen.',
                    'untuk_semua' => false,
                    'created_at' => $startDate->copy()->addWeeks($week),
                ]
            );
        }
    }

    private function seedJadwalUjian(KelasPerkuliahan $kelas, Carbon $startDate): void
    {
        foreach ([['UTS', 8], ['UAS', 16]] as [$jenis, $week]) {
            JadwalUjian::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'jenis_ujian' => $jenis,
                ],
                [
                    'tanggal_ujian' => $startDate->copy()->addWeeks($week)->toDateString(),
                    'jam_mulai' => $kelas->jam_mulai,
                    'jam_selesai' => $kelas->jam_selesai,
                    'ruangan' => $kelas->ruangan,
                    'catatan' => $jenis . ' dilaksanakan tertib dengan soal studi kasus dan analisis.',
                    'is_published' => true,
                ]
            );
        }
    }

    private function seedNotifikasi(KelasPerkuliahan $kelas, $peserta): void
    {
        $peserta->take(8)->each(function (User $mahasiswa, int $index) use ($kelas) {
            Notifikasi::updateOrCreate(
                [
                    'user_id' => $mahasiswa->id,
                    'kelas_perkuliahan_id' => $kelas->id,
                    'judul' => 'Aktivitas terbaru ' . $kelas->mataKuliah->kode_mk,
                ],
                [
                    'pesan' => 'Ada pembaruan materi, tugas, atau nilai pada kelas ' . $kelas->kode_kelas . '.',
                    'tipe' => ['informasi', 'tugas', 'nilai', 'ujian'][$index % 4],
                    'url' => '/mahasiswa/kelas/' . $kelas->id,
                    'dibaca_pada' => $index % 3 === 0 ? now()->subDays($index) : null,
                ]
            );
        });
    }

    private function seedAktivitas(KelasPerkuliahan $kelas, $peserta, Carbon $startDate): void
    {
        $actions = [
            'login' => 'Masuk ke portal Cendekia',
            'buka_dashboard' => 'Membuka dashboard mahasiswa',
            'buka_kelas' => 'Membuka kelas ' . $kelas->kode_kelas,
            'unduh_materi' => 'Mengunduh materi perkuliahan',
            'tonton_video' => 'Menonton video pembelajaran',
            'kumpul_tugas' => 'Mengumpulkan tugas semester',
            'lihat_gradebook' => 'Melihat rekap nilai',
            'balas_forum' => 'Membalas forum diskusi kelas',
        ];

        $peserta->take(12)->each(function (User $mahasiswa, int $index) use ($kelas, $startDate, $actions) {
            foreach ($actions as $aksi => $deskripsi) {
                AktivitasPengguna::updateOrCreate(
                    [
                        'user_id' => $mahasiswa->id,
                        'kelas_perkuliahan_id' => $kelas->id,
                        'aksi' => $aksi,
                        'terjadi_pada' => $startDate->copy()->addDays(($index * 3) + strlen($aksi))->setTime(8 + ($index % 9), ($index * 7) % 60),
                    ],
                    [
                        'deskripsi' => $deskripsi,
                        'ip_address' => '192.168.' . ($index % 50) . '.' . (20 + $index),
                        'user_agent' => ['Chrome Windows', 'Firefox Windows', 'Edge Windows', 'Safari iOS'][$index % 4],
                    ]
                );
            }
        });
    }

    private function gradeFor(float $nilai): string
    {
        return match (true) {
            $nilai >= 85 => 'A',
            $nilai >= 80 => 'AB',
            $nilai >= 75 => 'B',
            $nilai >= 70 => 'BC',
            $nilai >= 65 => 'C',
            $nilai >= 55 => 'D',
            default => 'E',
        };
    }
}
