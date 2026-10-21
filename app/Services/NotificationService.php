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
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Send materi notification to all enrolled students
     */
    public static function notifyMateriBaru(Materi $materi, User $dosen)
    {
        try {
            $kelas = $materi->kelasPerkuliahan;
            if (!$kelas) {
                return;
            }

            $mahasiswaList = $kelas->mahasiswa()->get();
            $namaMk = $kelas->mataKuliah->nama_mk ?? 'Kelas';

            // Notifikasi in-app & email HANYA untuk mahasiswa di kelas (bukan untuk dosen pembuat)
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
                try {
                    \App\Models\Notifikasi::create([
                        'user_id' => $mahasiswa->id,
                        'kelas_perkuliahan_id' => $materi->kelas_perkuliahan_id,
                        'judul' => 'Materi Baru: ' . Str::limit($materi->judul, 40),
                        'pesan' => 'Mata Kuliah: ' . $namaMk . '. ' . Str::limit(strip_tags($materi->deskripsi ?? ''), 80),
                        'tipe' => 'materi',
                        'url' => route('mahasiswa.materi.buka', ['kelas' => $materi->kelas_perkuliahan_id, 'materi' => $materi->id]),
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Failed to create in-app materi notif for user {$mahasiswa->id}: " . $e->getMessage());
                }

                try {
                    if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('materi_baru')) {
                        SendMateriBaru::dispatch($materi, $mahasiswa, $dosen);
                    }
                } catch (\Throwable $e) {
                    Log::warning("Skipped SendMateriBaru dispatch for user {$mahasiswa->id}: " . $e->getMessage());
                }
            }

            Log::info("Notified {$mahasiswaList->count()} students about new materi: {$materi->judul}");
        } catch (\Throwable $e) {
            Log::error('Error notifying materi: ' . $e->getMessage());
        }
    }

    /**
     * Send tugas notification to all enrolled students
     */
    public static function notifyTugasBaru(Tugas $tugas, User $dosen)
    {
        try {
            $kelas = $tugas->kelasPerkuliahan;
            if (!$kelas) {
                return;
            }

            $mahasiswaList = $kelas->mahasiswa()->get();
            $namaMk = $kelas->mataKuliah->nama_mk ?? 'Kelas';
            $deskripsiTugas = $tugas->instruksi ?? $tugas->deskripsi ?? '';
            $deadlineStr = $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-';

            // Notifikasi in-app & email HANYA untuk mahasiswa di kelas (bukan untuk dosen pembuat)
            foreach ($mahasiswaList as $mahasiswa) {
                try {
                    \App\Models\Notifikasi::create([
                        'user_id' => $mahasiswa->id,
                        'kelas_perkuliahan_id' => $tugas->kelas_perkuliahan_id,
                        'judul' => 'Tugas Baru: ' . Str::limit($tugas->judul, 40),
                        'pesan' => $namaMk . ' • Batas Waktu: ' . $deadlineStr . '. ' . Str::limit(strip_tags($deskripsiTugas), 75),
                        'tipe' => 'tugas',
                        'url' => route('mahasiswa.pengumpulan-tugas.show', $tugas->id),
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Failed to create in-app tugas notif for user {$mahasiswa->id}: " . $e->getMessage());
                }

                try {
                    if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('tugas_baru')) {
                        SendTugasBaru::dispatch($tugas, $mahasiswa, $dosen);
                    }
                } catch (\Throwable $e) {
                    Log::warning("Skipped SendTugasBaru dispatch for user {$mahasiswa->id}: " . $e->getMessage());
                }
            }

            Log::info("Notified {$mahasiswaList->count()} students about new tugas: {$tugas->judul}");
        } catch (\Throwable $e) {
            Log::error('Error notifying tugas: ' . $e->getMessage());
        }
    }

    /**
     * Send pengumuman notification
     * Handles both class-level announcements (by lecturer) and global/campus announcements (by admin)
     * Notify students when tugas deadline is extended or reopened
     */
    public static function notifyTugasUpdated(Tugas $tugas, User $dosen, string $keterangan = 'Tugas telah diperbarui.')
    {
        try {
            $kelas = $tugas->kelasPerkuliahan;
            if (!$kelas) return;

            $mahasiswaList = $kelas->mahasiswa()->get();
            $namaMk = $kelas->mataKuliah->nama_mk ?? 'Kelas';
            $deadlineStr = $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y, H:i') : '-';

            foreach ($mahasiswaList as $mahasiswa) {
                try {
                    \App\Models\Notifikasi::create([
                        'user_id' => $mahasiswa->id,
                        'kelas_perkuliahan_id' => $tugas->kelas_perkuliahan_id,
                        'judul' => 'Pembaruan Tugas: ' . Str::limit($tugas->judul, 35),
                        'pesan' => $namaMk . ' • ' . $keterangan . ' Batas waktu: ' . $deadlineStr . '.',
                        'tipe' => 'tugas',
                        'url' => route('mahasiswa.pengumpulan-tugas.show', $tugas->id),
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Failed to create tugas update notif for user {$mahasiswa->id}: " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error notifying tugas update: ' . $e->getMessage());
        }
    }

    /**
     * Send pengumuman notification to all enrolled students
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
            $kelas = $pengumuman->kelasPerkuliahan;
            $mahasiswaList = $kelas ? $kelas->mahasiswa()->get() : collect();
            $namaMk = $kelas->mataKuliah->nama_mk ?? 'Pengumuman Kelas';

            // Notifikasi in-app & email HANYA untuk mahasiswa di kelas (bukan untuk dosen pembuat)
            foreach ($mahasiswaList as $mahasiswa) {
                try {
                    \App\Models\Notifikasi::create([
                        'user_id' => $mahasiswa->id,
                        'kelas_perkuliahan_id' => $pengumuman->kelas_perkuliahan_id,
                        'judul' => 'Pengumuman Kelas: ' . Str::limit($pengumuman->judul, 40),
                        'pesan' => $namaMk . ' • ' . Str::limit(strip_tags($pengumuman->isi ?? ''), 85),
                        'tipe' => 'pengumuman',
                        'url' => route('mahasiswa.pengumuman.index'),
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Failed to create pengumuman notif for user {$mahasiswa->id}: " . $e->getMessage());
                }

                try {
                    if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('pengumuman_baru')) {
                        SendPengumumanBaru::dispatch($pengumuman, $mahasiswa, $dosen);
                    }
                } catch (\Throwable $e) {
                    Log::warning("Skipped SendPengumumanBaru for user {$mahasiswa->id}: " . $e->getMessage());
                }
            }

            Log::info("Notified {$mahasiswaList->count()} students about new class pengumuman");
        } catch (\Throwable $e) {
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
     * Broadcast pengumuman resmi dari Admin ke seluruh Civitas (Mahasiswa & Dosen)
     */
    public static function notifyPengumumanAdmin(Pengumuman $pengumuman, User $admin)
    {
        try {
            // Ambil semua mahasiswa & dosen aktif
            $recipients = User::role(['mahasiswa', 'dosen'])
                ->where('id', '!=', $admin->id)
                ->get(['id', 'email', 'name']);

            $ringkasan = Str::limit(strip_tags($pengumuman->isi ?? ''), 85);

            foreach ($recipients as $recipient) {
                try {
                    $url = $recipient->hasRole('dosen')
                        ? route('dosen.pengumuman.index')
                        : route('mahasiswa.pengumuman.index');

                    \App\Models\Notifikasi::create([
                        'user_id' => $recipient->id,
                        'kelas_perkuliahan_id' => null,
                        'judul' => 'Pengumuman Kampus: ' . Str::limit($pengumuman->judul, 40),
                        'pesan' => 'Pengumuman resmi dari Admin: ' . $ringkasan,
                        'tipe' => 'pengumuman',
                        'url' => $url,
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Failed to create admin pengumuman notif for user {$recipient->id}: " . $e->getMessage());
                }
            }

            Log::info("Admin broadcasted pengumuman to {$recipients->count()} civitas members");
        } catch (\Throwable $e) {
            Log::error('Error broadcasting admin pengumuman: ' . $e->getMessage());
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
            if (!$mahasiswa || !$tugas) {
                return;
            }

            $namaMk = $tugas->kelasPerkuliahan->mataKuliah->nama_mk ?? 'Kelas';
            $nilaiDisplay = ($submission->nilai !== null) ? $submission->nilai : '-';
            
            // Buat notifikasi in-app
            try {
                \App\Models\Notifikasi::create([
                    'user_id' => $mahasiswa->id,
                    'kelas_perkuliahan_id' => $tugas->kelas_perkuliahan_id,
                    'judul' => 'Nilai Diterbitkan: ' . Str::limit($tugas->judul, 40),
                    'pesan' => $namaMk . ' • Nilai tugas Anda: ' . $nilaiDisplay . '/100.' . ($submission->feedback_dosen ? ' Catatan Dosen: ' . Str::limit($submission->feedback_dosen, 40) : ''),
                    'tipe' => 'nilai',
                    'url' => route('mahasiswa.pengumpulan-tugas.show', $tugas->id),
                ]);
            } catch (\Throwable $e) {
                Log::error("Failed to create nilai notif for user {$mahasiswa->id}: " . $e->getMessage());
            }

            try {
                if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('nilai_baru')) {
                    SendNilaiBaru::dispatch($submission, $mahasiswa, $dosen);
                }
            } catch (\Throwable $e) {
                Log::warning("Skipped SendNilaiBaru for student {$mahasiswa->id}: " . $e->getMessage());
            }

            Log::info("Notified student about new nilai", ['mahasiswa_id' => $mahasiswa->id, 'tugas_id' => $tugas->id]);
        } catch (\Throwable $e) {
            Log::error('Error notifying nilai: ' . $e->getMessage());
        }
    }

    /**
     * Send absensi dibuka notification to all enrolled students
     */
    public static function notifyAbsensiDibuka(Absensi $absensi, User $dosen)
    {
        try {
            $kelas = $absensi->kelasPerkuliahan;
            if (!$kelas) return;

            $mahasiswaList = $kelas->mahasiswa()->get();
            $namaMk = $kelas->mataKuliah->nama_mk ?? 'Kelas';

            foreach ($mahasiswaList as $mahasiswa) {
                // Hindari duplikasi notifikasi presensi untuk pertemuan yang sama
                $sudahAda = \App\Models\Notifikasi::where('user_id', $mahasiswa->id)
                    ->where('kelas_perkuliahan_id', $absensi->kelas_perkuliahan_id)
                    ->where('tipe', 'presensi')
                    ->where('judul', 'like', "%Pertemuan {$absensi->pertemuan_ke}%")
                    ->whereDate('created_at', today())
                    ->exists();

                if ($sudahAda) {
                    continue;
                }

                // Buat notifikasi in-app
                try {
                    \App\Models\Notifikasi::create([
                        'user_id' => $mahasiswa->id,
                        'kelas_perkuliahan_id' => $absensi->kelas_perkuliahan_id,
                        'judul' => 'Presensi Dibuka: Pertemuan ' . $absensi->pertemuan_ke,
                        'pesan' => $namaMk . ' • Presensi pertemuan ke-' . $absensi->pertemuan_ke . ' telah dibuka. Segera lakukan presensi kehadiran Anda.',
                        'tipe' => 'presensi',
                        'url' => route('mahasiswa.absensi.index'),
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Failed to create absensi notif for user {$mahasiswa->id}: " . $e->getMessage());
                }

                try {
                    if (NotificationPreference::forUser($mahasiswa->id)->isEnabled('absensi_dibuka')) {
                        SendAbsensiDibuka::dispatch($absensi, $mahasiswa, $dosen);
                    }
                } catch (\Throwable $e) {
                    Log::warning("Skipped SendAbsensiDibuka for user {$mahasiswa->id}: " . $e->getMessage());
                }
            }

            Log::info("Notified students about absensi dibuka: {$namaMk} Pertemuan {$absensi->pertemuan_ke}");
        } catch (\Throwable $e) {
            Log::error('Error notifying absensi dibuka: ' . $e->getMessage());
        }
    }

    /**
     * Notify dosen about new submission from a student
     */
    public static function notifyPengumpulanTugas(PengumpulanTugas $submission, User $dosen)
    {
        try {
            $tugas = $submission->tugas;
            $mahasiswa = $submission->mahasiswa;
            $namaMk = $tugas->kelasPerkuliahan->mataKuliah->nama_mk ?? 'Kelas';

            // Buat notifikasi in-app untuk dosen
            try {
                \App\Models\Notifikasi::create([
                    'user_id' => $dosen->id,
                    'kelas_perkuliahan_id' => $tugas->kelas_perkuliahan_id,
                    'judul' => 'Tugas Dikumpulkan: ' . ($mahasiswa->name ?? 'Mahasiswa'),
                    'pesan' => ($mahasiswa->name ?? 'Mahasiswa') . ' telah mengumpulkan tugas "' . Str::limit($tugas->judul, 30) . '" di kelas ' . $namaMk . '.',
                    'tipe' => 'tugas',
                    'url' => route('dosen.tugas.detail', ['id' => $tugas->kelas_perkuliahan_id, 'tugasId' => $tugas->id]),
                ]);
            } catch (\Throwable $e) {
                Log::error("Failed to create pengumpulan notif for dosen {$dosen->id}: " . $e->getMessage());
            }

            try {
                if (NotificationPreference::forUser($dosen->id)->isEnabled('pengumpulan_tugas')) {
                    SendPengumpulanTugas::dispatch($submission, $dosen);
                }
            } catch (\Throwable $e) {
                Log::warning("Skipped SendPengumpulanTugas for dosen {$dosen->id}: " . $e->getMessage());
            }
        } catch (\Throwable $e) {
            Log::error('Error notifying pengumpulan tugas: ' . $e->getMessage());
        }
    }

    /**
     * Notify user about new pesan in forum
     */
    public static function notifyPesanBaru(ForumDiskusi $forum, User $recipient, User $sender)
    {
        try {
            // Buat notifikasi in-app
            try {
                \App\Models\Notifikasi::create([
                    'user_id' => $recipient->id,
                    'kelas_perkuliahan_id' => $forum->kelas_perkuliahan_id,
                    'judul' => 'Pesan Baru di Forum Diskusi',
                    'pesan' => ($sender->name ?? 'Pengguna') . ' mengirim pesan di forum: "' . Str::limit($forum->judul, 40) . '".',
                    'tipe' => 'informasi',
                    'url' => route('forum-diskusi.show', $forum->id),
                ]);
            } catch (\Throwable $e) {
                Log::error("Failed to create forum notif for user {$recipient->id}: " . $e->getMessage());
            }

            try {
                if (NotificationPreference::forUser($recipient->id)->isEnabled('pesan_baru')) {
                    SendPesanBaru::dispatch($forum, $recipient, $sender);
                }
            } catch (\Throwable $e) {
                Log::warning("Skipped SendPesanBaru for recipient {$recipient->id}: " . $e->getMessage());
            }
        } catch (\Throwable $e) {
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
            
            foreach ($admins as $admin) {
                try {
                    \App\Models\Notifikasi::create([
                        'user_id' => $admin->id,
                        'kelas_perkuliahan_id' => null,
                        'judul' => 'Pengguna Baru Terdaftar',
                        'pesan' => $user->name . ' (' . $user->email . ') telah terdaftar ke sistem sebagai ' . ucfirst($role) . '.',
                        'tipe' => 'informasi',
                        'url' => route('admin.user.index'),
                    ]);
                } catch (\Throwable $e) {
                    Log::error("Failed to create admin new user notif: " . $e->getMessage());
                }

                try {
                    if (NotificationPreference::forUser($admin->id)->isEnabled('pengguna_baru')) {
                        SendPenggunaBaru::dispatch($user, $role);
                    }
                } catch (\Throwable $e) {
                    Log::warning("Skipped SendPenggunaBaru for admin {$admin->id}: " . $e->getMessage());
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error notifying pengguna baru: ' . $e->getMessage());
        }
    }
}
