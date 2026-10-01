<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\KelasPerkuliahan;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = $request->user();

        // 1. Dapatkan semua kelas yang diikuti Mahasiswa ini
        $kelasIds = DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->pluck('kelas_perkuliahan_id');

        $kelasAktif = KelasPerkuliahan::with(['mataKuliah', 'dosen'])
            ->whereIn('id', $kelasIds)
            ->get();

        $analyticsPerClass = [];
        $globalRiskScore = 0;
        $totalClasses = count($kelasAktif);

        // Agregasi Global
        $globalTotalSesi = 0;
        $globalHadirSesi = 0;
        $globalTotalTugas = 0;
        $globalTugasDikerjakan = 0;
        $globalTotalNilai = 0;
        $globalJumlahNilai = 0;

        foreach ($kelasAktif as $kelas) {
            // A. Kehadiran di Kelas Ini
            $totalSesi = DB::table('absensi')
                ->where('kelas_perkuliahan_id', $kelas->id)
                ->count();

            $hadirSesi = DB::table('absensi_mahasiswa')
                ->join('absensi', 'absensi_mahasiswa.absensi_id', '=', 'absensi.id')
                ->where('absensi.kelas_perkuliahan_id', $kelas->id)
                ->where('absensi_mahasiswa.mahasiswa_id', $mahasiswa->id)
                ->where('absensi_mahasiswa.status', 'hadir')
                ->count();

            $attendanceRate = $totalSesi > 0 ? round(($hadirSesi / $totalSesi) * 100) : 100;

            // Tambah ke Global
            $globalTotalSesi += $totalSesi;
            $globalHadirSesi += $hadirSesi;

            // B. Tugas di Kelas Ini
            $totalTugas = DB::table('tugas')->where('kelas_perkuliahan_id', $kelas->id)->count();
            
            $tugasDikumpulkan = DB::table('pengumpulan_tugas')
                ->join('tugas', 'pengumpulan_tugas.tugas_id', '=', 'tugas.id')
                ->where('tugas.kelas_perkuliahan_id', $kelas->id)
                ->where('pengumpulan_tugas.mahasiswa_id', $mahasiswa->id)
                ->get();

            $tugasDikerjakan = $tugasDikumpulkan->count();
            $missedAssignments = max(0, $totalTugas - $tugasDikerjakan);
            
            $avgScore = 0;
            $tugasDinilai = $tugasDikumpulkan->whereNotNull('nilai');
            if ($tugasDinilai->count() > 0) {
                $avgScore = round($tugasDinilai->avg('nilai'));
                $globalTotalNilai += $tugasDinilai->sum('nilai');
                $globalJumlahNilai += $tugasDinilai->count();
            }

            // Tambah ke Global
            $globalTotalTugas += $totalTugas;
            $globalTugasDikerjakan += $tugasDikerjakan;

            // C. Risk Prediksi Per Kelas
            $riskScore = 0;
            $reasons = [];

            if ($attendanceRate < 75) {
                $riskScore += 40;
                $reasons[] = "Kehadiran sangat rendah ($attendanceRate%)";
            } elseif ($attendanceRate < 85) {
                $riskScore += 20;
                $reasons[] = "Kehadiran perlu ditingkatkan ($attendanceRate%)";
            }

            if ($avgScore > 0 && $avgScore < 60) {
                $riskScore += 40;
                $reasons[] = "Rata-rata tugas rendah ($avgScore)";
            } elseif ($avgScore > 0 && $avgScore < 70) {
                $riskScore += 20;
                $reasons[] = "Rata-rata tugas batas bawah ($avgScore)";
            }

            if ($missedAssignments >= 2) {
                $riskScore += 30;
                $reasons[] = "Tidak mengerjakan $missedAssignments tugas";
            } elseif ($missedAssignments == 1) {
                $riskScore += 10;
                $reasons[] = "Tidak mengerjakan 1 tugas";
            }

            // Status Kelas
            $statusColor = 'bg-emerald-100 text-emerald-700 border-emerald-200';
            $statusLabel = 'Aman';
            if ($riskScore >= 70) {
                $statusColor = 'bg-rose-100 text-rose-700 border-rose-200 font-bold';
                $statusLabel = 'Kritis (Bahaya)';
            } elseif ($riskScore >= 40) {
                $statusColor = 'bg-amber-100 text-amber-700 border-amber-200 font-bold';
                $statusLabel = 'Waspada';
            }

            $analyticsPerClass[] = (object) [
                'kelas' => $kelas,
                'attendance_rate' => $attendanceRate,
                'avg_score' => $avgScore,
                'missed_assignments' => $missedAssignments,
                'reasons' => $reasons,
                'status_color' => $statusColor,
                'status_label' => $statusLabel,
                'risk_score' => $riskScore
            ];
        }

        // Urutkan kelas dari yang paling berisiko
        usort($analyticsPerClass, function ($a, $b) {
            return $b->risk_score <=> $a->risk_score;
        });

        // Hitung Global Status
        $globalAttendance = $globalTotalSesi > 0 ? round(($globalHadirSesi / $globalTotalSesi) * 100) : 100;
        $globalAvgScore = $globalJumlahNilai > 0 ? round($globalTotalNilai / $globalJumlahNilai) : 0;
        $globalMissed = max(0, $globalTotalTugas - $globalTugasDikerjakan);

        return view('mahasiswa.analytics.index', compact(
            'analyticsPerClass', 
            'globalAttendance', 
            'globalAvgScore', 
            'globalMissed',
            'totalClasses'
        ));
    }
}
