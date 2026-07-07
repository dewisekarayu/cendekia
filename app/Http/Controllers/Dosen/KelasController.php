<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Tugas;
use App\Models\Materi;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function kelasSaya(Request $request)
    {
        $search = trim($request->input('search', ''));

        $kelasQuery = $request->user()->kelasDiampu()->with(['mataKuliah.programStudi', 'mahasiswa']);

        if ($search !== '') {
            $kelasQuery->where(function ($query) use ($search) {
                $query->where('kode_kelas', 'like', "%{$search}%")
                    ->orWhereHas('mataKuliah', function ($subQuery) use ($search) {
                        $subQuery->where('nama_mk', 'like', "%{$search}%")
                            ->orWhere('kode_mk', 'like', "%{$search}%");
                    })
                    ->orWhereHas('mataKuliah.programStudi', function ($subQuery) use ($search) {
                        $subQuery->where('nama_prodi', 'like', "%{$search}%");
                    });
            });
        }

        $kelasList = $kelasQuery->get();

        return view('dosen.kelas-saya', compact('kelasList', 'search'));
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

    public function materi(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah.programStudi', 'mahasiswa'])
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($id);

        // also provide list of classes the dosen teaches for sidebar/search consistency
        $kelasList = $request->user()->kelasDiampu()->with(['mataKuliah.programStudi', 'mahasiswa'])->get();

        $materiList = Materi::where('kelas_perkuliahan_id', $kelas->id)
            ->orderBy('pertemuan_ke')
            ->get();

        return view('dosen.kelas-materi', compact('kelas', 'kelasList', 'materiList'));
    }
}