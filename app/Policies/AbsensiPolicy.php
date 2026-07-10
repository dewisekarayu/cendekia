<?php

namespace App\Policies;

use App\Models\Absensi;
use App\Models\User;

class AbsensiPolicy
{
    /**
     * Dosen pengampu kelas (atau admin) boleh melihat/mengelola sesi presensi.
     * Mahasiswa yang terdaftar di kelas boleh melihat sesi presensi kelasnya.
     */
    public function view(User $user, Absensi $absensi): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDosen()) {
            return $this->dosenMengampuKelas($user, $absensi);
        }

        if ($user->isMahasiswa()) {
            return $this->mahasiswaTerdaftarDiKelas($user, $absensi);
        }

        return false;
    }

    /**
     * Hanya mahasiswa yang terdaftar di kelas DAN sesi masih berstatus "buka"
     * yang boleh melakukan check-in presensi.
     */
    public function checkIn(User $user, Absensi $absensi): bool
    {
        if (!$user->isMahasiswa()) {
            return false;
        }

        return $this->mahasiswaTerdaftarDiKelas($user, $absensi) && $absensi->isBuka();
    }

    /**
     * Mahasiswa yang terdaftar di kelas boleh melihat riwayat presensinya sendiri.
     */
    public function viewHistory(User $user, Absensi $absensi): bool
    {
        if (!$user->isMahasiswa()) {
            return false;
        }

        return $this->mahasiswaTerdaftarDiKelas($user, $absensi);
    }

    /**
     * Dosen pengampu (utama atau team teaching) atau admin boleh mengelola
     * sesi presensi: membuat, membuka/menutup, mengedit kehadiran manual, menghapus.
     */
    public function manage(User $user, Absensi $absensi): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isDosen() && $this->dosenMengampuKelas($user, $absensi);
    }

    private function dosenMengampuKelas(User $user, Absensi $absensi): bool
    {
        $kelas = $absensi->kelasPerkuliahan;

        if (!$kelas) {
            return false;
        }

        if ($kelas->dosen_id === $user->id) {
            return true;
        }

        $dosenPengampu = $kelas->dosen_pengampu ?? [];

        return in_array($user->id, $dosenPengampu);
    }

    private function mahasiswaTerdaftarDiKelas(User $user, Absensi $absensi): bool
    {
        return $user->kelasDiikuti()
            ->where('kelas_perkuliahan.id', $absensi->kelas_perkuliahan_id)
            ->exists();
    }
}
