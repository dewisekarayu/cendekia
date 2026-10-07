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

        $sort = request('sort', 'nama_asc');

        if ($kelas) {
            $query = $kelas->mahasiswa()
                ->leftJoin('nilai_akhir', function($join) use ($kelas) {
                    $join->on('users.id', '=', 'nilai_akhir.mahasiswa_id')
                         ->where('nilai_akhir.kelas_perkuliahan_id', '=', $kelas->id);
                })
                ->select('users.id as mahasiswa_id', 'users.name', 'users.nip_nim', 
                         'nilai_akhir.nilai_kehadiran', 'nilai_akhir.nilai_tugas', 
                         'nilai_akhir.nilai_quiz', 'nilai_akhir.nilai_project', 
                         'nilai_akhir.nilai_uts', 'nilai_akhir.nilai_uas', 
                         'nilai_akhir.nilai_akhir', 'nilai_akhir.grade');
            
            if ($sort === 'nilai_desc') {
                $query->orderByDesc('nilai_akhir.nilai_akhir')->orderBy('users.name');
            } elseif ($sort === 'nilai_asc') {
                $query->orderBy('nilai_akhir.nilai_akhir')->orderBy('users.name');
            } else {
                $query->orderBy('users.name');
            }
            
            $students = $query->paginate($perPage)->withQueryString();
        } else {
            $students = collect();
        }

        // Jumlah total mahasiswa di kelas (termasuk yang belum punya nilai akhir)
        $totalStudents = $kelas
            ? KelasPerkuliahan::withCount('mahasiswa')->find($kelas->id)?->mahasiswa_count ?? 0
            : 0;

        return view('dosen.gradebook', compact(
            'kelasList',
            'kelas',
            'students',
            'totalStudents',
            'perPage',
            'sort'
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

        // Hitung ulang nilai akhir semua mahasiswa dengan bobot baru
        $kelas->refresh();
        NilaiAkhir::where('kelas_perkuliahan_id', $kelas->id)->get()
            ->each(function (NilaiAkhir $record) use ($kelas) {
                [$record->nilai_akhir, $record->grade] = $this->hitungNilai(
                    $kelas,
                    $record->nilai_tugas ?? 0,
                    $record->nilai_uts ?? 0,
                    $record->nilai_uas ?? 0
                );
                $record->save();
            });

        return back()->with('success', 'Bobot penilaian berhasil diperbarui dan nilai akhir telah dihitung ulang.');
    }

    /**
     * Rumus tunggal nilai akhir & grade berdasarkan bobot kelas.
     */
    protected function hitungNilai(KelasPerkuliahan $kelas, $tugas, $uts, $uas): array
    {
        $nilai = round(
            ($tugas * ($kelas->bobot_tugas ?? 30) / 100)
            + ($uts * ($kelas->bobot_uts ?? 30) / 100)
            + ($uas * ($kelas->bobot_uas ?? 40) / 100),
            2
        );

        $grade = match (true) {
            $nilai >= 90 => 'A',
            $nilai >= 80 => 'B',
            $nilai >= 70 => 'C',
            $nilai >= 60 => 'D',
            default => 'E',
        };

        return [$nilai, $grade];
    }

    public function updateNilai(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas_perkuliahan,id',
            'students' => 'required|array',
            'students.*.mahasiswa_id' => 'required|exists:users,id',
            'students.*.nilai_kehadiran' => 'nullable|numeric|min:0|max:100',
            'students.*.nilai_tugas' => 'nullable|numeric|min:0|max:100',
            'students.*.nilai_uts' => 'nullable|numeric|min:0|max:100',
            'students.*.nilai_uas' => 'nullable|numeric|min:0|max:100',
        ]);

        $kelas = KelasPerkuliahan::where('id', $request->kelas_id)
            ->where(function ($query) use ($request) {
                $query->where('dosen_id', $request->user()->id)
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($request) {
                        $q->where('users.id', $request->user()->id);
                    });
            })->firstOrFail();

        foreach ($request->students as $studentData) {
            $kehadiran = $studentData['nilai_kehadiran'] ?? 0;
            $tugas = $studentData['nilai_tugas'] ?? 0;
            $uts = $studentData['nilai_uts'] ?? 0;
            $uas = $studentData['nilai_uas'] ?? 0;

            [$nilai_akhir, $grade] = $this->hitungNilai($kelas, $tugas, $uts, $uas);

            NilaiAkhir::updateOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'mahasiswa_id' => $studentData['mahasiswa_id'],
                ],
                [
                    'nilai_kehadiran' => $kehadiran,
                    'nilai_tugas' => $tugas,
                    'nilai_quiz' => 0,
                    'nilai_project' => 0,
                    'nilai_uts' => $uts,
                    'nilai_uas' => $uas,
                    'nilai_akhir' => $nilai_akhir,
                    'grade' => $grade,
                ]
            );
        }

        return back()->with('success', 'Nilai seluruh mahasiswa berhasil disimpan.');
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
            
            [$nilai_akhir, $grade] = $this->hitungNilai(
                $kelas,
                $nilaiAkhirRecord->nilai_tugas ?? 0,
                $nilaiAkhirRecord->nilai_uts ?? 0,
                $nilaiAkhirRecord->nilai_uas ?? 0
            );

            $nilaiAkhirRecord->nilai_akhir = $nilai_akhir;
            $nilaiAkhirRecord->grade = $grade;
            $nilaiAkhirRecord->save();
        }

        return back()->with('success', 'Nilai kehadiran berhasil disinkronkan dari data absensi!');
    }
}
