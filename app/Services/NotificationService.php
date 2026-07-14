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

            foreach ($mahasiswaList as $mahasiswa) {
                SendMateriBaru::dispatch($materi, $mahasiswa, $dosen);
            }

            Log::info("Notified {$mahasiswaList->count()} students about new materi");
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

            foreach ($mahasiswaList as $mahasiswa) {
                SendTugasBaru::dispatch($tugas, $mahasiswa, $dosen);
            }

            Log::info("Notified {$mahasiswaList->count()} students about new tugas");
        } catch (\Exception $e) {
            Log::error('Error notifying tugas: ' . $e->getMessage());
        }
    }

    /**
     * Send pengumuman notification
     */
    public static function notifyPengumumanBaru(Pengumuman $pengumuman, User $dosen)
    {
        try {
            $mahasiswaList = $pengumuman->kelasPerkuliahan->mahasiswa()->get();

            foreach ($mahasiswaList as $mahasiswa) {
                SendPengumumanBaru::dispatch($pengumuman, $mahasiswa, $dosen);
            }

            Log::info("Notified {$mahasiswaList->count()} students about new pengumuman");
        } catch (\Exception $e) {
            Log::error('Error notifying pengumuman: ' . $e->getMessage());
        }
    }

    /**
     * Send nilai notification to student
     */
    public static function notifyNilaiBaru(PengumpulanTugas $submission, User $dosen)
    {
        try {
            SendNilaiBaru::dispatch($submission, $submission->mahasiswa, $dosen);
            Log::info("Notified student about new nilai", ['mahasiswa_id' => $submission->mahasiswa_id]);
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
            $mahasiswaList = $absensi->kelasPerkuliahan
                ->mahasiswa()
                ->get();

            foreach ($mahasiswaList as $mahasiswa) {
                SendAbsensiDibuka::dispatch($absensi, $mahasiswa, $dosen);
            }

            Log::info("Notified {$mahasiswaList->count()} students about absensi dibuka");
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
            SendPengumpulanTugas::dispatch($submission, $dosen);
            Log::info("Notified dosen about new submission", ['dosen_id' => $dosen->id]);
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
            SendPesanBaru::dispatch($forum, $recipient, $sender);
            Log::info("Notified user about new pesan", ['recipient_id' => $recipient->id]);
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
            
            foreach ($admins as $admin) {
                SendPenggunaBaru::dispatch($user, $role);
            }

            Log::info("Notified admins about new user registration", ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Error notifying pengguna baru: ' . $e->getMessage());
        }
    }
}
