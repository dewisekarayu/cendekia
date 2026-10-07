<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $dosen = $request->user();

        // 1. Dapatkan semua kelas yang diampu Dosen ini
        $kelasList = KelasPerkuliahan::with('mataKuliah')->where('dosen_id', $dosen->id)->get();
        $kelasIds = $kelasList->pluck('id');
        $kelasMap = $kelasList->keyBy('id');

        $availableClasses = [];
        foreach ($kelasList as $k) {
            $nama = $k->mataKuliah->nama_mata_kuliah . ' - ' . $k->kode_kelas;
            $availableClasses[$k->id] = $nama;
        }

        // 2. Dapatkan semua mahasiswa di kelas-kelas tersebut
        $kelasMahasiswa = DB::table('kelas_mahasiswa')
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->get();

        $mahasiswaIds = $kelasMahasiswa->pluck('mahasiswa_id')->unique();
        $students = User::whereIn('id', $mahasiswaIds)->get();

        $analytics = [];
        $atRiskCount = 0;
        $totalStudents = count($students);

        foreach ($students as $mhs) {
            $mhsKelasIds = $kelasMahasiswa->where('mahasiswa_id', $mhs->id)->pluck('kelas_perkuliahan_id');
            $mhsKelasNames = [];
            foreach ($mhsKelasIds as $cId) {
                if (isset($availableClasses[$cId])) {
                    $mhsKelasNames[] = $availableClasses[$cId];
                }
            }
            $kelasString = implode(', ', $mhsKelasNames);

            // A. Cek Kehadiran (Attendance) di semua kelas dosen ini
            $totalSesi = DB::table('absensi_mahasiswa')
                ->join('absensi', 'absensi_mahasiswa.absensi_id', '=', 'absensi.id')
                ->whereIn('absensi.kelas_perkuliahan_id', $kelasIds)
                ->where('absensi_mahasiswa.mahasiswa_id', $mhs->id)
                ->count();

            $hadirSesi = DB::table('absensi_mahasiswa')
                ->join('absensi', 'absensi_mahasiswa.absensi_id', '=', 'absensi.id')
                ->whereIn('absensi.kelas_perkuliahan_id', $kelasIds)
                ->where('absensi_mahasiswa.mahasiswa_id', $mhs->id)
                ->where('absensi_mahasiswa.status', 'hadir')
                ->count();

            $attendanceRate = $totalSesi > 0 ? round(($hadirSesi / $totalSesi) * 100) : 100;

            // B. Cek Nilai Tugas (Assignment Scores)
            $tugasDikumpulkan = DB::table('pengumpulan_tugas')
                ->join('tugas', 'pengumpulan_tugas.tugas_id', '=', 'tugas.id')
                ->whereIn('tugas.kelas_perkuliahan_id', $kelasIds)
                ->where('pengumpulan_tugas.mahasiswa_id', $mhs->id)
                ->whereNotNull('pengumpulan_tugas.nilai')
                ->get();

            $avgScore = 0;
            if ($tugasDikumpulkan->count() > 0) {
                $avgScore = round($tugasDikumpulkan->avg('nilai'));
            }

            // C. AI Risk Prediction Logic (Simulated EWS)
            $riskScore = 0;
            $reasons = [];

            if ($attendanceRate < 75) {
                $riskScore += 40;
                $reasons[] = "Kehadiran rendah ($attendanceRate%)";
            }
            if ($avgScore > 0 && $avgScore < 60) {
                $riskScore += 40;
                $reasons[] = "Rata-rata nilai tugas kurang ($avgScore)";
            }

            // Dapatkan jumlah tugas yang belum dikerjakan sama sekali (terlewat)
            $totalTugas = DB::table('tugas')->whereIn('kelas_perkuliahan_id', $kelasIds)->count();
            $tugasDikerjakan = DB::table('pengumpulan_tugas')
                ->join('tugas', 'pengumpulan_tugas.tugas_id', '=', 'tugas.id')
                ->whereIn('tugas.kelas_perkuliahan_id', $kelasIds)
                ->where('pengumpulan_tugas.mahasiswa_id', $mhs->id)
                ->count();

            $missedAssignments = $totalTugas - $tugasDikerjakan;
            if ($missedAssignments >= 2) {
                $riskScore += 20;
                $reasons[] = "Melewatkan $missedAssignments tugas";
            }

            // Kategori Risiko
            $riskLevel = 'Low';
            $color = 'bg-emerald-100 text-emerald-700';
            if ($riskScore >= 70) {
                $riskLevel = 'High';
                $color = 'bg-rose-100 text-rose-700 font-bold';
                $atRiskCount++;
            } elseif ($riskScore >= 40) {
                $riskLevel = 'Medium';
                $color = 'bg-amber-100 text-amber-700';
            }

            $analytics[] = (object) [
                'mahasiswa' => $mhs,
                'kelas_string' => $kelasString,
                'kelas_array' => $mhsKelasNames,
                'attendance_rate' => $attendanceRate,
                'avg_score' => $avgScore,
                'missed_assignments' => $missedAssignments,
                'risk_score' => $riskScore,
                'risk_level' => $riskLevel,
                'color' => $color,
                'reasons' => $reasons,
            ];
        }

        // Urutkan berdasarkan tingkat risiko tertinggi
        usort($analytics, function ($a, $b) {
            return $b->risk_score <=> $a->risk_score;
        });

        return view('dosen.analytics.index', compact('analytics', 'atRiskCount', 'totalStudents', 'availableClasses'));
    }
}
