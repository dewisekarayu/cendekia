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

        $kelasList = $user->kelasDiampu()->with(['mataKuliah.programStudi', 'mahasiswa'])->get();

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
        $kelasHariIni = $kelasList->filter(fn ($k) => strcasecmp($k->hari, $todayName) === 0);

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