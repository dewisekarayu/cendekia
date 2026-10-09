<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\User;
use App\Services\EwsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request, EwsService $ews)
    {
        $dosen = $request->user();

        // 1. Semua kelas yang diampu dosen ini (pengampu utama maupun tambahan)
        $kelasList = KelasPerkuliahan::with('mataKuliah')
            ->where(function ($q) use ($dosen) {
                $q->where('dosen_id', $dosen->id)
                    ->orWhereHas('dosenPengampuTambahan', fn ($q2) => $q2->where('users.id', $dosen->id));
            })
            ->get();
        $kelasIds = $kelasList->pluck('id');

        $availableClasses = [];
        foreach ($kelasList as $k) {
            $namaMataKuliah = $k->mataKuliah?->nama_mk;
            $availableClasses[$k->id] = ($namaMataKuliah ? $namaMataKuliah . ' - ' : '') . $k->kode_kelas;
        }

        // 2. Semua mahasiswa di kelas-kelas tersebut
        $kelasMahasiswa = DB::table('kelas_mahasiswa')
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->get();

        $mahasiswaIds = $kelasMahasiswa->pluck('mahasiswa_id')->unique()->values();
        $students = User::whereIn('id', $mahasiswaIds)->get();

        // 3. Metrik EWS (batch, hanya kelas yang diikuti tiap mahasiswa)
        $metrics = $ews->metrics($mahasiswaIds, $kelasIds);

        $analytics = [];
        $atRiskCount = 0;
        $totalStudents = $students->count();

        $colors = [
            'High'   => 'bg-rose-100 text-rose-700 font-bold',
            'Medium' => 'bg-amber-100 text-amber-700',
            'Low'    => 'bg-emerald-100 text-emerald-700',
        ];

        foreach ($students as $mhs) {
            $mhsKelasIds = $kelasMahasiswa->where('mahasiswa_id', $mhs->id)->pluck('kelas_perkuliahan_id');
            $mhsKelasNames = $mhsKelasIds->map(fn ($id) => $availableClasses[$id] ?? null)->filter()->values()->all();

            $combined = $ews->combine(
                $mhsKelasIds->map(fn ($id) => $metrics[$mhs->id . ':' . $id] ?? null)->filter()->all()
            );
            $result = $ews->evaluate($combined);

            if ($result['risk_level'] === 'High') {
                $atRiskCount++;
            }

            $analytics[] = (object) array_merge($result, [
                'mahasiswa'    => $mhs,
                'kelas_string' => implode(', ', $mhsKelasNames),
                'kelas_array'  => $mhsKelasNames,
                'color'        => $colors[$result['risk_level']],
            ]);
        }

        // Urutkan berdasarkan tingkat risiko tertinggi
        usort($analytics, fn ($a, $b) => $b->risk_score <=> $a->risk_score);

        return view('dosen.analytics.index', compact('analytics', 'atRiskCount', 'totalStudents', 'availableClasses'));
    }
}
