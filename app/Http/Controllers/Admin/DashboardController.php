<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasPengguna;
use App\Models\AbsensiMahasiswa;
use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\NilaiAkhir;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalProgramStudi = ProgramStudi::count();
        $totalMataKuliah = MataKuliah::count();
        $totalKelasAktif = KelasPerkuliahan::where('is_active', true)->count();
        $rataNilaiAkhir = round((float) NilaiAkhir::avg('nilai_akhir'), 2);
        $totalAktivitas = AktivitasPengguna::whereDate('terjadi_pada', today())->count();
        $totalPresensi = AbsensiMahasiswa::count();
        $persentaseHadir = $totalPresensi > 0
            ? round((AbsensiMahasiswa::where('status', 'hadir')->count() / $totalPresensi) * 100, 1)
            : 0;

        $prodiList = ProgramStudi::withCount('mataKuliah')->get();
        $recentUsers = User::latest()->take(5)->get();
        $aktivitasBulanan = collect(range(1, 12))->map(fn ($month) => [
            'label' => now()->month($month)->translatedFormat('M'),
            'value' => AktivitasPengguna::whereMonth('terjadi_pada', $month)->count(),
        ]);
        $aktivitasMingguan = collect(range(0, 6))->map(function ($day) {
            $date = now()->startOfWeek()->addDays($day);

            return [
                'label' => $date->translatedFormat('D'),
                'value' => AktivitasPengguna::whereDate('terjadi_pada', $date)->count(),
            ];
        });

        // Pastikan variabel yang dipakai di view tersedia (hindari undefined variable)
        $totalDosen = User::whereHas('roles', function ($q) {
            $q->where('name', 'dosen');
        })->count();

        $totalMahasiswa = User::whereHas('roles', function ($q) {
            $q->where('name', 'mahasiswa');
        })->count();

        $mahasiswaPerProdi = ProgramStudi::withCount(['mataKuliah'])
            ->get()
            ->map(fn ($prodi) => [
                'label' => $prodi->kode_prodi,
                'value' => User::role('mahasiswa')->where('program_studi_id', $prodi->id)->count(),
            ]);

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
            'recentUsers',
            'aktivitasBulanan',
            'aktivitasMingguan'
        ));
    }
}
