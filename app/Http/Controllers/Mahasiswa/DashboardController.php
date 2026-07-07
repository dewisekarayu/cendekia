<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $kelasDiikuti = $user->kelasDiikuti()->with(['mataKuliah.programStudi', 'dosen'])->get();

        $courses = $kelasDiikuti->map(function ($kelas) use ($user) {
            $totalTugas = Tugas::where('kelas_perkuliahan_id', $kelas->id)->count();
            $submitted = PengumpulanTugas::whereHas('tugas', fn ($q) => $q->where('kelas_perkuliahan_id', $kelas->id))
                ->where('mahasiswa_id', $user->id)
                ->where('status', '!=', 'belum_dikumpulkan')
                ->count();

            return [
                'id' => $kelas->id,
                'tag' => strtoupper($kelas->mataKuliah?->programStudi?->nama_prodi ?? 'UMUM'),
                'title' => $kelas->mataKuliah?->nama_mk ?? '-',
                'dosen' => $kelas->dosen?->name ?? '-',
                'progress' => $totalTugas > 0 ? round(($submitted / $totalTugas) * 100) : 0,
            ];
        });

        $kelasIds = $kelasDiikuti->pluck('id');

        $deadlines = Tugas::whereIn('kelas_perkuliahan_id', $kelasIds)
            ->where('deadline', '>=', now())
            ->with('kelasPerkuliahan.mataKuliah')
            ->orderBy('deadline')
            ->take(3)
            ->get();

        $announcements = Pengumuman::where(function ($q) use ($kelasIds) {
                $q->whereIn('kelas_perkuliahan_id', $kelasIds)->orWhere('untuk_semua', true);
            })
            ->latest()
            ->take(2)
            ->get();

        return view('mahasiswa.dashboard', compact('courses', 'deadlines', 'announcements'));
    }
}