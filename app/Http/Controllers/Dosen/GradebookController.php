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

        // Semua mahasiswa yang terdaftar di kelas ini, gabung dengan nilai_akhir (jika ada)
        $students = $kelas
            ? $kelas->mahasiswa()
                ->leftJoin('nilai_akhir', function($join) use ($kelas) {
                    $join->on('users.id', '=', 'nilai_akhir.mahasiswa_id')
                         ->where('nilai_akhir.kelas_perkuliahan_id', '=', $kelas->id);
                })
                ->select('users.id as mahasiswa_id', 'users.name', 'users.nip_nim', 
                         'nilai_akhir.nilai_kehadiran', 'nilai_akhir.nilai_tugas', 
                         'nilai_akhir.nilai_quiz', 'nilai_akhir.nilai_project', 
                         'nilai_akhir.nilai_uts', 'nilai_akhir.nilai_uas', 
                         'nilai_akhir.nilai_akhir', 'nilai_akhir.grade')
                ->orderByDesc('nilai_akhir.nilai_akhir')
                ->orderBy('users.name')
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
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($request) {
                        $q->where('users.id', $request->user()->id);
                    });
            })->firstOrFail();

        $kelas->update([
            'bobot_tugas' => $request->bobot_tugas,
            'bobot_uts' => $request->bobot_uts,
            'bobot_uas' => $request->bobot_uas,
        ]);

        return back()->with('success', 'Bobot penilaian berhasil diperbarui.');
    }

    public function updateNilai(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas_perkuliahan,id',
            'mahasiswa_id' => 'required|exists:users,id',
            'nilai_kehadiran' => 'nullable|numeric|min:0|max:100',
            'nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'nilai_quiz' => 'nullable|numeric|min:0|max:100',
            'nilai_project' => 'nullable|numeric|min:0|max:100',
            'nilai_uts' => 'nullable|numeric|min:0|max:100',
            'nilai_uas' => 'nullable|numeric|min:0|max:100',
        ]);

        $kelas = KelasPerkuliahan::where('id', $request->kelas_id)
            ->where(function ($query) use ($request) {
                $query->where('dosen_id', $request->user()->id)
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($request) {
                        $q->where('users.id', $request->user()->id);
                    });
            })->firstOrFail();

        // Calculate final grade
        $kehadiran = $request->nilai_kehadiran ?? 0;
        $tugas = $request->nilai_tugas ?? 0;
        $quiz = $request->nilai_quiz ?? 0;
        $project = $request->nilai_project ?? 0;
        $uts = $request->nilai_uts ?? 0;
        $uas = $request->nilai_uas ?? 0;

        // Determine weights. If class has custom weights (tugas, uts, uas), use them. 
        // Otherwise fallback to default
        $bTugas = $kelas->bobot_tugas ?? 20;
        $bUts = $kelas->bobot_uts ?? 30;
        $bUas = $kelas->bobot_uas ?? 50;

        // Since the requirement usually includes quiz, project, dll, we use a custom formula
        // Default formula if total is 100
        $nilai_akhir = round(
            ($kehadiran * 0.10) +
            ($tugas * 0.20) +
            ($quiz * 0.10) +
            ($project * 0.20) +
            ($uts * 0.20) +
            ($uas * 0.20),
            2
        );

        // Calculate Grade
        $grade = match (true) {
            $nilai_akhir >= 85 => 'A',
            $nilai_akhir >= 80 => 'AB',
            $nilai_akhir >= 75 => 'B',
            $nilai_akhir >= 70 => 'BC',
            $nilai_akhir >= 65 => 'C',
            $nilai_akhir >= 55 => 'D',
            default => 'E',
        };

        NilaiAkhir::updateOrCreate(
            [
                'kelas_perkuliahan_id' => $kelas->id,
                'mahasiswa_id' => $request->mahasiswa_id,
            ],
            [
                'nilai_kehadiran' => $kehadiran,
                'nilai_tugas' => $tugas,
                'nilai_quiz' => $quiz,
                'nilai_project' => $project,
                'nilai_uts' => $uts,
                'nilai_uas' => $uas,
                'nilai_akhir' => $nilai_akhir,
                'grade' => $grade,
            ]
        );

        return back()->with('success', 'Nilai mahasiswa berhasil disimpan.');
    }

    public function syncAbsensi(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas_perkuliahan,id',
        ]);

        $kelas = KelasPerkuliahan::with(['absensi.absensiMahasiswa', 'mahasiswa'])->findOrFail($request->kelas_id);
        $totalPertemuan = $kelas->absensi->count();

        if ($totalPertemuan === 0) {
            return back()->with('error', 'Belum ada data pertemuan/absensi di kelas ini.');
        }

        foreach ($kelas->mahasiswa as $mhs) {
            $hadirCount = 0;
            
            foreach ($kelas->absensi as $absensi) {
                $status = $absensi->absensiMahasiswa->where('mahasiswa_id', $mhs->id)->first()?->status;
                if ($status === 'hadir') {
                    $hadirCount++;
                }
            }
            
            $nilaiHadir = ($hadirCount / $totalPertemuan) * 100;

            $nilaiAkhirRecord = NilaiAkhir::firstOrNew([
                'kelas_perkuliahan_id' => $kelas->id,
                'mahasiswa_id' => $mhs->id,
            ]);

            $nilaiAkhirRecord->nilai_kehadiran = $nilaiHadir;
            
            // Recalculate Nilai Akhir if it exists
            $tugas = $nilaiAkhirRecord->nilai_tugas ?? 0;
            $quiz = $nilaiAkhirRecord->nilai_quiz ?? 0;
            $project = $nilaiAkhirRecord->nilai_project ?? 0;
            $uts = $nilaiAkhirRecord->nilai_uts ?? 0;
            $uas = $nilaiAkhirRecord->nilai_uas ?? 0;
            
            // Average of tugas/quiz/project
            $avgTugas = ($tugas + $quiz + $project) / 3;
            
            $wTugas = ($kelas->bobot_tugas ?? 20) / 100;
            $wUts = ($kelas->bobot_uts ?? 30) / 100;
            $wUas = ($kelas->bobot_uas ?? 50) / 100;

            // Optional: Include attendance in calculation if you want, but for now stick to original formula
            $nilai_akhir = ($avgTugas * $wTugas) + ($uts * $wUts) + ($uas * $wUas);

            $grade = match (true) {
                $nilai_akhir >= 85 => 'A',
                $nilai_akhir >= 80 => 'AB',
                $nilai_akhir >= 75 => 'B',
                $nilai_akhir >= 70 => 'BC',
                $nilai_akhir >= 65 => 'C',
                $nilai_akhir >= 55 => 'D',
                default => 'E',
            };

            $nilaiAkhirRecord->nilai_akhir = $nilai_akhir;
            $nilaiAkhirRecord->grade = $grade;
            $nilaiAkhirRecord->save();
        }

        return back()->with('success', 'Nilai kehadiran berhasil disinkronkan dari data absensi!');
    }
}
