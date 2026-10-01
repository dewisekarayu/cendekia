<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ProgramStudi;

class AnalyticsController extends Controller
{
    public function index()
    {
        // 1. Basic Stats
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalDosen = User::role('dosen')->count();
        $totalProdi = ProgramStudi::count();

        // 2. Fetch all mahasiswa to calculate global EWS
        $mahasiswaList = User::role('mahasiswa')->with('programStudi')->get();
        
        $globalHighRisk = 0;
        $globalMediumRisk = 0;
        $globalLowRisk = 0;
        
        $prodiRiskMap = [];
        $topAtRiskStudents = [];

        foreach ($mahasiswaList as $mhs) {
            $riskScore = 0;
            
            // Attendance Calculation
            $totalSesi = DB::table('absensi_mahasiswa')
                ->join('absensi', 'absensi_mahasiswa.absensi_id', '=', 'absensi.id')
                ->where('absensi_mahasiswa.mahasiswa_id', $mhs->id)
                ->count();

            $hadirSesi = DB::table('absensi_mahasiswa')
                ->join('absensi', 'absensi_mahasiswa.absensi_id', '=', 'absensi.id')
                ->where('absensi_mahasiswa.mahasiswa_id', $mhs->id)
                ->where('absensi_mahasiswa.status', 'hadir')
                ->count();

            $attendanceRate = $totalSesi > 0 ? round(($hadirSesi / $totalSesi) * 100) : 100;

            // Score Calculation
            $tugasDinilai = DB::table('pengumpulan_tugas')
                ->where('mahasiswa_id', $mhs->id)
                ->whereNotNull('nilai')
                ->get();
                
            $avgScore = $tugasDinilai->count() > 0 ? round($tugasDinilai->avg('nilai')) : 0;

            // Missed Assignments Calculation (simplified for global: just tasks not submitted in classes they take)
            $kelasIds = DB::table('kelas_mahasiswa')->where('mahasiswa_id', $mhs->id)->pluck('kelas_perkuliahan_id');
            $totalTugas = DB::table('tugas')->whereIn('kelas_perkuliahan_id', $kelasIds)->count();
            $tugasDikerjakan = DB::table('pengumpulan_tugas')->where('mahasiswa_id', $mhs->id)->count();
            $missedAssignments = max(0, $totalTugas - $tugasDikerjakan);

            // Calculate Risk Score
            if ($attendanceRate < 75) $riskScore += 40;
            if ($avgScore > 0 && $avgScore < 60) $riskScore += 40;
            if ($missedAssignments >= 2) $riskScore += 20;

            // Classify
            if ($riskScore >= 70) {
                $globalHighRisk++;
            } elseif ($riskScore >= 40) {
                $globalMediumRisk++;
            } else {
                $globalLowRisk++;
            }

            // Aggregate by Prodi
            $prodiName = $mhs->programStudi ? $mhs->programStudi->nama_prodi : 'Tanpa Prodi';
            if (!isset($prodiRiskMap[$prodiName])) {
                $prodiRiskMap[$prodiName] = [
                    'high' => 0,
                    'medium' => 0,
                    'low' => 0,
                    'total' => 0
                ];
            }
            
            $prodiRiskMap[$prodiName]['total']++;
            if ($riskScore >= 70) {
                $prodiRiskMap[$prodiName]['high']++;
            } elseif ($riskScore >= 40) {
                $prodiRiskMap[$prodiName]['medium']++;
            } else {
                $prodiRiskMap[$prodiName]['low']++;
            }

            // Keep track for Top At Risk
            if ($riskScore >= 40) {
                $topAtRiskStudents[] = (object) [
                    'mahasiswa' => $mhs,
                    'risk_score' => $riskScore,
                    'attendance_rate' => $attendanceRate,
                    'avg_score' => $avgScore,
                    'missed' => $missedAssignments,
                    'prodi' => $prodiName
                ];
            }
        }

        // Sort Top 10 At Risk
        usort($topAtRiskStudents, function($a, $b) {
            return $b->risk_score <=> $a->risk_score;
        });
        $topAtRiskStudents = array_slice($topAtRiskStudents, 0, 10);

        return view('admin.analytics.index', compact(
            'totalMahasiswa', 
            'totalDosen', 
            'totalProdi',
            'globalHighRisk',
            'globalMediumRisk',
            'globalLowRisk',
            'prodiRiskMap',
            'topAtRiskStudents'
        ));
    }
}
