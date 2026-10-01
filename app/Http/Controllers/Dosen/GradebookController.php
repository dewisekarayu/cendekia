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

        $perPage = (int) $request->input('per_page', $request->input('show', 10));
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Nilai akhir mahasiswa di kelas ini, diurutkan terbaik di atas
        $students = $kelas
            ? NilaiAkhir::with('mahasiswa')
                ->where('kelas_perkuliahan_id', $kelas->id)
                ->orderByDesc('nilai_akhir')
                ->paginate($perPage)
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
            'totalStudents',
            'perPage'
        ));
    }

    public function updateBobot(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas_perkuliahan,id',
            'bobot_tugas' => 'required|integer|min:0|max:100',
            'bobot_uts' => 'required|integer|min:0|max:100',
            'bobot_uas' => 'required|integer|min:0|max:100',
        ]);

        $totalBobot = $request->bobot_tugas + $request->bobot_uts + $request->bobot_uas;
        if ($totalBobot !== 100) {
            return back()->with('error', 'Total bobot harus bernilai 100%. Saat ini: ' . $totalBobot . '%');
        }

        $kelas = KelasPerkuliahan::where('id', $request->kelas_id)
            ->where(function ($query) use ($request) {
                $query->where('dosen_id', $request->user()->id)
                    ->orWhereJsonContains('dosen_pengampu', $request->user()->id);
            })->firstOrFail();

        $kelas->update([
            'bobot_tugas' => $request->bobot_tugas,
            'bobot_uts' => $request->bobot_uts,
            'bobot_uas' => $request->bobot_uas,
        ]);

        return back()->with('success', 'Bobot penilaian berhasil diperbarui.');
    }
}
