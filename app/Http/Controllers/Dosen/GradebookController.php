<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\NilaiAkhir;
use Illuminate\Http\Request;

class GradebookController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Semua kelas yang diampu dosen ini
        $kelasList = $user->kelasDiampu()
            ->with(['mataKuliah', 'semester'])
            ->get();

        // Kelas yang sedang aktif (dari query string atau default ke yang pertama)
        $kelas = $kelasList->firstWhere('id', (int) $request->query('kelas_id'))
            ?? $kelasList->first();

        // Nilai akhir mahasiswa di kelas ini, diurutkan terbaik di atas
        $students = $kelas
            ? NilaiAkhir::with('mahasiswa')
                ->where('kelas_perkuliahan_id', $kelas->id)
                ->orderByDesc('nilai_akhir')
                ->paginate(20)
                ->withQueryString()
            : collect();

        // Jumlah total mahasiswa di kelas (termasuk yang belum punya nilai akhir)
        $totalStudents = $kelas
            ? KelasPerkuliahan::withCount('mahasiswa')->find($kelas->id)?->mahasiswa_count ?? 0
            : 0;

        return view('dosen.gradebook', compact(
            'kelasList',
            'kelas',
            'students',
            'totalStudents'
        ));
    }
}
