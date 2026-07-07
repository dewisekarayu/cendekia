<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AbsensiMahasiswa;
use App\Models\KelasMahasiswa;
use App\Models\KelasPerkuliahan;
use App\Models\Materi;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function kelasSaya(Request $request)
    {
        $kelasList = $request->user()->kelasDiikuti()->with(['mataKuliah.programStudi', 'dosen'])->get();

        return view('mahasiswa.kelas-saya', compact('kelasList'));
    }

    public function show(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah.programStudi', 'dosen'])->findOrFail($id);

        $sudahGabung = KelasMahasiswa::where('kelas_perkuliahan_id', $kelas->id)
            ->where('mahasiswa_id', $request->user()->id)
            ->exists();

        abort_unless($sudahGabung, 403, 'Kamu belum bergabung ke kelas ini.');

        $materiList = Materi::where('kelas_perkuliahan_id', $kelas->id)
            ->orderBy('pertemuan_ke')
            ->get();

        $tugasList = Tugas::where('kelas_perkuliahan_id', $kelas->id)
            ->orderBy('deadline')
            ->get();

        $totalTugas = $tugasList->count();
        $submitted = PengumpulanTugas::whereIn('tugas_id', $tugasList->pluck('id'))
            ->where('mahasiswa_id', $request->user()->id)
            ->where('status', '!=', 'belum_dikumpulkan')
            ->count();
        $progress = $totalTugas > 0 ? round(($submitted / $totalTugas) * 100) : 0;

        $rekapAbsen = AbsensiMahasiswa::with('absensi')
            ->whereHas('absensi', fn ($q) => $q->where('kelas_perkuliahan_id', $kelas->id))
            ->where('mahasiswa_id', $request->user()->id)
            ->get()
            ->sortBy(fn ($item) => $item->absensi->pertemuan_ke)
            ->values();

        $totalHadir = $rekapAbsen->where('status', 'hadir')->count();
        $totalPertemuan = $rekapAbsen->count();

        return view('mahasiswa.kelas-detail', compact(
            'kelas', 'materiList', 'tugasList', 'rekapAbsen',
            'progress', 'totalHadir', 'totalPertemuan', 'submitted', 'totalTugas'
        ));
    }

    public function jelajahi(Request $request)
    {
        $user = $request->user();
        $joinedIds = $user->kelasDiikuti()->pluck('kelas_perkuliahan.id');

        $kelasList = KelasPerkuliahan::with(['mataKuliah.programStudi', 'dosen', 'semester'])
            ->whereHas('mataKuliah', fn ($q) => $q->where('program_studi_id', $user->program_studi_id))
            ->whereHas('semester', fn ($q) => $q->where('is_active', true))
            ->whereNotIn('id', $joinedIds)
            ->get();

        return view('mahasiswa.jelajahi-kelas', compact('kelasList'));
    }

    public function join(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::findOrFail($id);

        $sudahGabung = KelasMahasiswa::where('kelas_perkuliahan_id', $kelas->id)
            ->where('mahasiswa_id', $request->user()->id)
            ->exists();

        if (! $sudahGabung) {
            KelasMahasiswa::create([
                'kelas_perkuliahan_id' => $kelas->id,
                'mahasiswa_id' => $request->user()->id,
                'tanggal_daftar' => now(),
            ]);
        }

        return back()->with('success', 'Berhasil bergabung ke kelas!');
    }
}