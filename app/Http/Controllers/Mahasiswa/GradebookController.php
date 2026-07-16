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

        // 1. Ambil seluruh nilai akhir yang sudah diinput untuk mahasiswa ini (termasuk semester sebelumnya)
        $nilaiAkhirList = NilaiAkhir::with(['kelasPerkuliahan.mataKuliah.programStudi', 'kelasPerkuliahan.dosen'])
            ->where('mahasiswa_id', $user->id)
            ->get();
        $nilaiAkhirMap = $nilaiAkhirList->keyBy('kelas_perkuliahan_id');

        // 2. Ambil kelas yang saat ini diikuti oleh mahasiswa (kelas aktif)
        $kelasListEnrolled = $user->kelasDiikuti()
            ->with(['mataKuliah.programStudi', 'dosen'])
            ->get();

        // 3. Gabungkan keduanya secara unik agar mahasiswa bisa melihat seluruh mata kuliah yang pernah/sedang diikuti
        $allKelas = collect();

        foreach ($kelasListEnrolled as $kelas) {
            $allKelas->put($kelas->id, $kelas);
        }

        foreach ($nilaiAkhirList as $nilai) {
            if ($nilai->kelasPerkuliahan && !$allKelas->has($nilai->kelas_perkuliahan_id)) {
                $allKelas->put($nilai->kelas_perkuliahan_id, $nilai->kelasPerkuliahan);
            }
        }

        $kelasList = $allKelas->values();

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
        $totalKelas     = $kelasList->count();

        // Distribusi grade
        $gradeDistribusi = $nilaiAkhirList->groupBy('grade')
            ->map(fn ($g) => $g->count())
            ->toArray();

        return view('mahasiswa.gradebook', compact(
            'kelasList',
            'nilaiAkhirMap',
            'nilaiTugasList',
            'rataRata',
            'nilaiTertinggi',
            'nilaiTerendah',
            'totalKelas',
            'gradeDistribusi'
        ));
    }
}
