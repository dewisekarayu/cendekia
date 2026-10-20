<?php

namespace App\Services;

use App\Jobs\SendMateriBaru;
use App\Jobs\SendTugasBaru;
use App\Jobs\SendPengumumanBaru;
use App\Jobs\SendNilaiBaru;
use App\Jobs\SendAbsensiDibuka;
use App\Jobs\SendPengumpulanTugas;
use App\Jobs\SendPesanBaru;
use App\Jobs\SendPenggunaBaru;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\Pengumuman;
use App\Models\PengumpulanTugas;
use App\Models\Absensi;
use App\Models\ForumDiskusi;
use App\Models\User;
use App\Models\NotificationPreference;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send materi notification to all enrolled students
     */
    public static function notifyMateriBaru(Materi $materi, User $dosen)
    {
        try {
            $mahasiswaList = $materi->kelasPerkuliahan->mahasiswa()->get();
            $sentCount = 0;

            foreach ($mahasiswaList as $mahasiswa) {
                // Buat notifikasi in-app
                \App\Models\Notifikasi::create([
                    'user_id' => $mahasiswa->id,
                    'kelas_perkuliahan_id' => $materi->kelas_perkuliahan_id,
                    'judul' => 'Materi Baru: ' . \Illuminate\Support\Str::limit($materi->judul, 40),
                    'pesan' => 'Mata Kuliah: ' . ($materi->kelasPerkuliahan->mataKuliah->nama_mk ?? 'Kelas') . '. ' . \Illuminate\Support\Str::limit(strip_tags($materi->deskripsi ?? ''), 80),
                    'tipe' => 'informasi',
                    'url' => route('mahasiswa.materi.buka', ['kelas' => $materi->kelas_perkuliahan_id, 'materi' => $materi->id]),
                ]);

                if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('materi_baru')) {
                    SendMateriBaru::dispatch($materi, $mahasiswa, $dosen);
                    $sentCount++;
                } else {
                    Log::debug("Skipped SendMateriBaru for user {$mahasiswa->id} due to preference settings");
                }
            }

            Log::info("Notified {$sentCount}/{$mahasiswaList->count()} students about new materi");
        } catch (\Exception $e) {
            Log::error('Error notifying materi: ' . $e->getMessage());
        }
    }

    /**
     * Send tugas notification to all enrolled students
     */
    public static function notifyTugasBaru(Tugas $tugas, User $dosen)
    {
        try {
            $mahasiswaList = $tugas->kelasPerkuliahan->mahasiswa()->get();
            $sentCount = 0;

            foreach ($mahasiswaList as $mahasiswa) {
                // Buat notifikasi in-app
                \App\Models\Notifikasi::create([
                    'user_id' => $mahasiswa->id,
                    'kelas_perkuliahan_id' => $tugas->kelas_perkuliahan_id,
                    'judul' => 'Tugas Baru: ' . \Illuminate\Support\Str::limit($tugas->judul, 40),
                    'pesan' => 'Mata Kuliah: ' . ($tugas->kelasPerkuliahan->mataKuliah->nama_mk ?? 'Kelas') . '. ' . \Illuminate\Support\Str::limit(strip_tags($tugas->deskripsi ?? ''), 80),
                    'tipe' => 'tugas',
                    'url' => route('mahasiswa.pengumpulan-tugas.show', $tugas->id),
                ]);

                if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('tugas_baru')) {
                    SendTugasBaru::dispatch($tugas, $mahasiswa, $dosen);
                    $sentCount++;
                } else {
                    Log::debug("Skipped SendTugasBaru for user {$mahasiswa->id} due to preference settings");
                }
            }

            Log::info("Notified {$sentCount}/{$mahasiswaList->count()} students about new tugas");
        } catch (\Exception $e) {
            Log::error('Error notifying tugas: ' . $e->getMessage());
        }
    }

    /**
     * Send pengumuman notification
     * Handles both class-level announcements (by lecturer) and global/campus announcements (by admin)
     */
    public static function notifyPengumumanBaru(Pengumuman $pengumuman, User $sender)
    {
        try {
            // Case 1: Class-level announcement
            if ($pengumuman->kelas_perkuliahan_id && $pengumuman->kelasPerkuliahan) {
                $mahasiswaList = $pengumuman->kelasPerkuliahan->mahasiswa()->get();
                $sentCount = 0;

                foreach ($mahasiswaList as $mahasiswa) {
                    \App\Models\Notifikasi::create([
                        'user_id' => $mahasiswa->id,
                        'kelas_perkuliahan_id' => $pengumuman->kelas_perkuliahan_id,
                        'judul' => 'Pengumuman: ' . \Illuminate\Support\Str::limit($pengumuman->judul, 40),
                        'pesan' => 'Mata Kuliah: ' . ($pengumuman->kelasPerkuliahan->mataKuliah->nama_mk ?? 'Kelas') . '. ' . \Illuminate\Support\Str::limit(strip_tags($pengumuman->isi ?? ''), 80),
                        'tipe' => 'informasi',
                        'url' => route('mahasiswa.pengumuman.index'),
                    ]);

                    if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('pengumuman_baru')) {
                        SendPengumumanBaru::dispatch($pengumuman, $mahasiswa, $sender);
                        $sentCount++;
                    } else {
                        Log::debug("Skipped SendPengumumanBaru for user {$mahasiswa->id} due to preference settings");
                    }
                }

                Log::info("Notified {$sentCount}/{$mahasiswaList->count()} students about new class pengumuman");
                return;
            }

            // Case 2: Global / Admin announcement (no specific class)
            $judul = 'Pengumuman: ' . \Illuminate\Support\Str::limit($pengumuman->judul, 40);
            $pesan = \Illuminate\Support\Str::limit(strip_tags($pengumuman->isi ?? ''), 100);

            // Notify all Dosens (so it appears in the top bell notification)
            $dosens = User::role('dosen')->where('id', '!=', $sender->id)->get();
            $dosenSentCount = 0;
            foreach ($dosens as $dosen) {
                \App\Models\Notifikasi::create([
                    'user_id' => $dosen->id,
                    'kelas_perkuliahan_id' => null,
                    'judul' => $judul,
                    'pesan' => $pesan,
                    'tipe' => 'informasi',
                    'url' => route('dosen.kelas-pengumuman.index'),
                ]);

                if (NotificationPreference::forUser($dosen->id)->isEnabled('pengumuman_baru')) {
                    SendPengumumanBaru::dispatch($pengumuman, $dosen, $sender);
                    $dosenSentCount++;
                } else {
                    Log::debug("Skipped SendPengumumanBaru for dosen {$dosen->id} due to preference settings");
                }
            }

            // Notify all Mahasiswas (so it appears in the top bell notification)
            $mahasiswas = User::role('mahasiswa')->where('id', '!=', $sender->id)->get();
            $mhsSentCount = 0;
            foreach ($mahasiswas as $mhs) {
                \App\Models\Notifikasi::create([
                    'user_id' => $mhs->id,
                    'kelas_perkuliahan_id' => null,
                    'judul' => $judul,
                    'pesan' => $pesan,
                    'tipe' => 'informasi',
                    'url' => route('mahasiswa.pengumuman.index'),
                ]);

                if (NotificationPreference::forUser($mhs->id)->isEnabled('pengumuman_baru')) {
                    SendPengumumanBaru::dispatch($pengumuman, $mhs, $sender);
                    $mhsSentCount++;
                } else {
                    Log::debug("Skipped SendPengumumanBaru for mahasiswa {$mhs->id} due to preference settings");
                }
            }

            Log::info("Notified {$dosenSentCount}/{$dosens->count()} dosen and {$mhsSentCount}/{$mahasiswas->count()} mahasiswa about admin pengumuman");
        } catch (\Exception $e) {
            Log::error('Error notifying pengumuman: ' . $e->getMessage());
        }
    }

    /**
     * Ensure any existing global / admin announcements have corresponding notifications for the user
     */
    public static function syncMissingGlobalPengumuman(?User $user)
    {
        if (!$user) {
            return;
        }

        try {
            $isDosen = $user->hasRole('dosen');
            $isMahasiswa = $user->hasRole('mahasiswa');

            if (!$isDosen && !$isMahasiswa) {
                return;
            }

            $targetUrl = $isDosen ? route('dosen.kelas-pengumuman.index') : route('mahasiswa.pengumuman.index');

            // Get recent global / admin announcements
            $globalAnnouncements = Pengumuman::where(function ($q) {
                    $q->whereNull('kelas_perkuliahan_id')
                      ->orWhere('untuk_semua', true);
                })
                ->latest()
                ->take(10)
                ->get();

            if ($globalAnnouncements->isEmpty()) {
                return;
            }

            // Get existing announcement notification titles for this user
            $existingJuduls = \App\Models\Notifikasi::where('user_id', $user->id)
                ->where('url', $targetUrl)
                ->pluck('judul')
                ->toArray();

            foreach ($globalAnnouncements as $announcement) {
                // Don't notify the user who made the announcement
                if ($announcement->dibuat_oleh === $user->id) {
                    continue;
                }

                $judul = 'Pengumuman: ' . \Illuminate\Support\Str::limit($announcement->judul, 40);
                if (!in_array($judul, $existingJuduls)) {
                    \App\Models\Notifikasi::create([
                        'user_id' => $user->id,
                        'kelas_perkuliahan_id' => null,
                        'judul' => $judul,
                        'pesan' => \Illuminate\Support\Str::limit(strip_tags($announcement->isi ?? ''), 100),
                        'tipe' => 'informasi',
                        'url' => $targetUrl,
                        'created_at' => $announcement->created_at ?? now(),
                        'updated_at' => $announcement->updated_at ?? now(),
                    ]);
                    $existingJuduls[] = $judul;
                }
            }
        } catch (\Exception $e) {
            Log::error('Error syncing missing global pengumuman: ' . $e->getMessage());
        }
    }

    /**
     * Send nilai notification to student
     */
    public static function notifyNilaiBaru(PengumpulanTugas $submission, User $dosen)
    {
        try {
            $mahasiswa = $submission->mahasiswa;
            $tugas = $submission->tugas;
            
            // Buat notifikasi in-app
            \App\Models\Notifikasi::create([
                'user_id' => $mahasiswa->id,
                'kelas_perkuliahan_id' => $tugas->kelas_perkuliahan_id,
                'judul' => 'Nilai Diterbitkan: ' . \Illuminate\Support\Str::limit($tugas->judul, 40),
                'pesan' => 'Mata Kuliah: ' . ($tugas->kelasPerkuliahan->mataKuliah->nama_mk ?? 'Kelas') . '. Cek nilai Anda sekarang.',
                'tipe' => 'nilai',
                'url' => route('mahasiswa.pengumpulan-tugas.show', $tugas->id),
            ]);

            if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('nilai_baru')) {
                SendNilaiBaru::dispatch($submission, $mahasiswa, $dosen);
                Log::info("Notified student about new nilai", ['mahasiswa_id' => $mahasiswa->id]);
            } else {
                Log::debug("Skipped SendNilaiBaru for student {$mahasiswa->id} due to preference settings");
            }
        } catch (\Exception $e) {
            Log::error('Error notifying nilai: ' . $e->getMessage());
        }
    }

    /**
     * Send absensi dibuka notification
     */
    public static function notifyAbsensiDibuka(Absensi $absensi, User $dosen)
    {
        try {
            $mahasiswaList = $absensi->kelasPerkuliahan->mahasiswa()->get();
            $sentCount = 0;

            foreach ($mahasiswaList as $mahasiswa) {
                if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('absensi_dibuka')) {
                    SendAbsensiDibuka::dispatch($absensi, $mahasiswa, $dosen);
                    $sentCount++;
                } else {
                    Log::debug("Skipped SendAbsensiDibuka for user {$mahasiswa->id} due to preference settings");
                }
            }

            Log::info("Notified {$sentCount}/{$mahasiswaList->count()} students about absensi dibuka");
        } catch (\Exception $e) {
            Log::error('Error notifying absensi dibuka: ' . $e->getMessage());
        }
    }

    /**
     * Notify dosen about new submission
     */
    public static function notifyPengumpulanTugas(PengumpulanTugas $submission, User $dosen)
    {
        try {
            if (NotificationPreference::forUser($dosen->id)->isEnabled('pengumpulan_tugas')) {
                SendPengumpulanTugas::dispatch($submission, $dosen);
                Log::info("Notified dosen about new submission", ['dosen_id' => $dosen->id]);
            } else {
                Log::debug("Skipped SendPengumpulanTugas for dosen {$dosen->id} due to preference settings");
            }
        } catch (\Exception $e) {
            Log::error('Error notifying pengumpulan tugas: ' . $e->getMessage());
        }
    }

    /**
     * Notify user about new pesan in forum
     */
    public static function notifyPesanBaru(ForumDiskusi $forum, User $recipient, User $sender)
    {
        try {
            if (NotificationPreference::forUser($recipient->id)->isEnabled('pesan_baru')) {
                SendPesanBaru::dispatch($forum, $recipient, $sender);
                Log::info("Notified user about new pesan", ['recipient_id' => $recipient->id]);
            } else {
                Log::debug("Skipped SendPesanBaru for recipient {$recipient->id} due to preference settings");
            }
        } catch (\Exception $e) {
            Log::error('Error notifying pesan baru: ' . $e->getMessage());
        }
    }

    /**
     * Notify admin about new user registration
     */
    public static function notifyPenggunaBaru(User $user, string $role)
    {
        try {
            $admins = User::role('admin')->get();
            $sentCount = 0;
            
            foreach ($admins as $admin) {
                if (NotificationPreference::forUser($admin->id)->isEnabled('pengguna_baru')) {
                    SendPenggunaBaru::dispatch($user, $role);
                    $sentCount++;
                } else {
                    Log::debug("Skipped SendPenggunaBaru for admin {$admin->id} due to preference settings");
                }
            }

            Log::info("Notified {$sentCount}/{$admins->count()} admins about new user registration", ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Error notifying pengguna baru: ' . $e->getMessage());
        }
    }
}
