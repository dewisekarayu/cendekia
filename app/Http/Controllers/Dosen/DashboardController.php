<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $kelasList = $user->kelasDiampu()->with(['mataKuliah.programStudi', 'mahasiswa', 'jadwals'])->get();

        $tugasPerluDinilai = PengumpulanTugas::whereHas('tugas.kelasPerkuliahan', fn ($q) => $q->where('dosen_id', $user->id))
            ->where('status', 'dikumpulkan')
            ->count();

        $totalMahasiswa = $kelasList->sum(fn ($kelas) => $kelas->mahasiswa->count());

        $submissions = PengumpulanTugas::with(['mahasiswa', 'tugas.kelasPerkuliahan.mataKuliah'])
            ->whereHas('tugas.kelasPerkuliahan', fn ($q) => $q->where('dosen_id', $user->id))
            ->latest('waktu_kumpul')
            ->take(5)
            ->get();

        $hariIndo = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ];
        $todayName = $hariIndo[now()->format('l')] ?? 'Senin';
        $kelasHariIni = $kelasList->filter(function ($k) use ($todayName) {
            foreach ($k->jadwals as $jadwal) {
                if (strcasecmp($jadwal->hari, $todayName) === 0) {
                    // Update property to matched jadwal so view displays correct time
                    $k->jam_mulai = $jadwal->jam_mulai;
                    $k->jam_selesai = $jadwal->jam_selesai;
                    $k->ruangan = $jadwal->ruangan;
                    return true;
                }
            }
            return false;
        });

        return view('dosen.dashboard', compact(
            'kelasList',
            'tugasPerluDinilai',
            'totalMahasiswa',
            'submissions',
            'kelasHariIni',
            'todayName'
        ));
    }
}