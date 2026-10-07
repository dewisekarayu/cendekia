<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Services\EwsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request, EwsService $ews)
    {
        $mahasiswa = $request->user();

        // 1. Semua kelas yang diikuti mahasiswa ini
        $kelasIds = DB::table('kelas_mahasiswa')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->pluck('kelas_perkuliahan_id');

        $kelasAktif = KelasPerkuliahan::with(['mataKuliah', 'dosen'])
            ->whereIn('id', $kelasIds)
            ->get();

        $metrics = $ews->metrics(collect([$mahasiswa->id]), $kelasIds);

        $statusMap = [
            'High'   => ['Kritis (Bahaya)', 'bg-rose-100 text-rose-700 border-rose-200 font-bold'],
            'Medium' => ['Waspada', 'bg-amber-100 text-amber-700 border-amber-200 font-bold'],
            'Low'    => ['Aman', 'bg-emerald-100 text-emerald-700 border-emerald-200'],
        ];

        $analyticsPerClass = [];
        $allMetrics = [];

        foreach ($kelasAktif as $kelas) {
            $m = $metrics[$mahasiswa->id . ':' . $kelas->id] ?? $ews->combine([]);
            $allMetrics[] = $m;
            $r = $ews->evaluate($m);
            [$label, $color] = $statusMap[$r['risk_level']];

            $analyticsPerClass[] = (object) array_merge($r, [
                'kelas'        => $kelas,
                'status_label' => $label,
                'status_color' => $color,
            ]);
        }

        // Urutkan kelas dari yang paling berisiko
        usort($analyticsPerClass, fn ($a, $b) => $b->risk_score <=> $a->risk_score);

        // Status global
        $global = $ews->evaluate($ews->combine($allMetrics));
        $globalAttendance = $global['attendance_rate'];
        $globalAvgScore = $global['avg_score'];
        $globalMissed = $global['missed_assignments'];
        $totalClasses = $kelasAktif->count();

        return view('mahasiswa.analytics.index', compact(
            'analyticsPerClass',
            'globalAttendance',
            'globalAvgScore',
            'globalMissed',
            'totalClasses'
        ));
    }
}
