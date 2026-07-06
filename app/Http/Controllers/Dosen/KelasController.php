<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Tugas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function kelasSaya(Request $request)
    {
        $kelasList = $request->user()->kelasDiampu()->with(['mataKuliah.programStudi', 'mahasiswa'])->get();

        return view('dosen.kelas-saya', compact('kelasList'));
    }

    public function show(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah.programStudi', 'mahasiswa'])
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($id);

        return view('dosen.kelas-detail', compact('kelas'));
    }

    public function tugas(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah', 'mahasiswa'])
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($id);

        $tugasList = Tugas::where('kelas_perkuliahan_id', $kelas->id)
            ->withCount([
                'pengumpulan as submitted_count' => fn ($q) => $q->where('status', '!=', 'belum_dikumpulkan'),
            ])
            ->latest()
            ->get();

        return view('dosen.kelas-tugas', compact('kelas', 'tugasList'));
    }
}