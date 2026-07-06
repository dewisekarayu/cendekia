<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Models\PengumpulanTugas;
use App\Models\ProgramStudi;
use App\Models\Tugas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->program_studi_id) {
            return view('mahasiswa.dashboard', [
                'needProdi' => true,
                'prodiList' => ProgramStudi::all(),
                'courses' => collect(),
                'deadlines' => collect(),
                'announcements' => collect(),
            ]);
        }

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

        return view('mahasiswa.dashboard', [
            'needProdi' => false,
            'courses' => $courses,
            'deadlines' => $deadlines,
            'announcements' => $announcements,
        ]);
    }

    public function pilihProdi(Request $request, $id)
    {
        $prodi = ProgramStudi::findOrFail($id);
        $request->user()->update(['program_studi_id' => $prodi->id]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Berhasil bergabung ke program studi ' . $prodi->nama_prodi . '!');
    }

    public function keluarProdi(Request $request)
    {
        $user = $request->user();

        // Otomatis keluar dari semua kelas yang diikuti,
        // karena kelas-kelas itu terikat ke prodi yang akan ditinggalkan
        $user->kelasDiikuti()->detach();

        $user->update(['program_studi_id' => null]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Kamu telah keluar dari program studi. Semua kelas yang diikuti juga ikut dihapus.');
    }
}