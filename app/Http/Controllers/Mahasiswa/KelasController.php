<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\KelasMahasiswa;
use App\Models\KelasPerkuliahan;
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

        return view('mahasiswa.kelas-detail', compact('kelas'));
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