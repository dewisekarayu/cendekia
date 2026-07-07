<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasPengguna;
use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\NilaiAkhir;
use App\Models\Presensi;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
        $totalProgramStudi = ProgramStudi::count();
        $totalMataKuliah = MataKuliah::count();
        $totalKelasAktif = KelasPerkuliahan::where('is_active', true)->count();
        $totalAktivitas = AktivitasPengguna::whereDate('terjadi_pada', today())->count();
        $rataNilaiAkhir = round((float) NilaiAkhir::avg('nilai_akhir'), 2);
        $totalPresensi = Presensi::count();
        $persentaseHadir = $totalPresensi > 0
            ? round((Presensi::where('status', 'hadir')->count() / $totalPresensi) * 100, 2)
            : 0;

        $prodiList = ProgramStudi::withCount('mataKuliah')->get();

            ->pluck('total', 'program_studi_id');

        $recentUsers = User::latest()->take(5)->get();
        $aktivitasBulanan = AktivitasPengguna::selectRaw('MONTH(terjadi_pada) as bulan, COUNT(*) as total')
            ->whereYear('terjadi_pada', now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->map(fn ($row) => ['label' => date('M', mktime(0, 0, 0, $row->bulan, 1)), 'value' => (int) $row->total]);

        $aktivitasMingguan = collect(range(0, 6))->map(function ($offset) {
            $date = now()->startOfWeek()->addDays($offset);

            return [
                'label' => $date->translatedFormat('D'),
                'value' => AktivitasPengguna::whereDate('terjadi_pada', $date)->count(),
            ];
        });

        return view('admin.dashboard', compact(
            'totalDosen',
            'totalMahasiswa',
            'totalProgramStudi',
            'totalMataKuliah',
            'totalKelasAktif',
            'totalAktivitas',
            'rataNilaiAkhir',
            'persentaseHadir',
            'prodiList',
            'mahasiswaPerProdi',
            'recentUsers'
            'recentUsers',
            'aktivitasBulanan',
            'aktivitasMingguan'
        ));
    }
}
