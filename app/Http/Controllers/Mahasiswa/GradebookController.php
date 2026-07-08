<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiAkhir;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class GradebookController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Nilai akhir per kelas (dari admin/dosen yang sudah input)
        $nilaiAkhirList = NilaiAkhir::with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.dosen'])
            ->where('mahasiswa_id', $user->id)
            ->orderByDesc('nilai_akhir')
            ->get();

        // Nilai per tugas yang sudah dinilai dosen
        $nilaiTugasList = PengumpulanTugas::with(['tugas.kelasPerkuliahan.mataKuliah'])
            ->where('mahasiswa_id', $user->id)
            ->whereNotNull('nilai')
            ->orderByDesc('waktu_kumpul')
            ->get();

        // Ringkasan statistik
        $rataRata   = $nilaiAkhirList->avg('nilai_akhir');
        $nilaiTertinggi = $nilaiAkhirList->max('nilai_akhir');
        $nilaiTerendah  = $nilaiAkhirList->min('nilai_akhir');
        $totalKelas     = $nilaiAkhirList->count();

        // Distribusi grade
        $gradeDistribusi = $nilaiAkhirList->groupBy('grade')
            ->map(fn ($g) => $g->count())
            ->toArray();

        return view('mahasiswa.gradebook', compact(
            'nilaiAkhirList',
            'nilaiTugasList',
            'rataRata',
            'nilaiTertinggi',
            'nilaiTerendah',
            'totalKelas',
            'gradeDistribusi'
        ));
    }
}
