<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasPengguna;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Exception;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDosen = User::whereHas('roles', function ($q) {
            $q->where('name', 'dosen');
        })->count();

        $totalMahasiswa = User::whereHas('roles', function ($q) {
            $q->where('name', 'mahasiswa');
        })->count();
        
        $totalProgramStudi = ProgramStudi::count();
        $totalMataKuliah = MataKuliah::count();
        // $totalKelasAktif = KelasPerkuliahan::where('is_active', true)->count();
        // $rataNilaiAkhir = round((float) NilaiAkhir::avg('nilai_akhir'), 2);
        // $totalAktivitas = AktivitasPengguna::whereDate('terjadi_pada', today())->count();
        // $totalPresensi = AbsensiMahasiswa::count();
        // $persentaseHadir = $totalPresensi > 0
        //     ? round((AbsensiMahasiswa::where('status', 'hadir')->count() / $totalPresensi) * 100, 1)
        //     : 0;

        $prodiList = ProgramStudi::withCount('mataKuliah')->get();
        $recentUsers = User::latest()->take(5)->get();
        
        $aktivitasMingguan = collect(range(0, 6))->map(function ($day) {
            $date = now()->startOfWeek()->addDays($day);
            return [
                'label' => $date->translatedFormat('D'),
                'value' => rand(2, 12), // Data aktivitas ringan (2-12 aktivitas per hari)
            ];
        });

        $aktivitasBulanan = collect(range(0, 6))->map(function ($month) {
            // Mulai dari Januari tahun ini
            $date = now()->startOfYear()->addMonths($month);
            // Batasi hanya sampai bulan berjalan jika belum sampai Juli
            if ($date->month > now()->month) {
                $date = now()->startOfMonth();
            }
            return [
                'label' => $date->translatedFormat('M'),
                'value' => rand(5, 25), // Data aktivitas yang ringan (5-25 aktivitas per bulan)
            ];
        });

        $mahasiswaPerProdi = ProgramStudi::withCount(['mataKuliah'])
            ->get()
            ->map(fn ($prodi) => [
                'label' => $prodi->kode_prodi,
                'value' => User::whereHas('roles', function ($q) {
                    $q->where('name', 'mahasiswa');
                })->where('program_studi_id', $prodi->id)->count(),
            ]);

        return view('admin.dashboard', compact(
            'totalDosen',
            'totalMahasiswa',
            'totalProgramStudi',
            'totalMataKuliah',
            // 'totalKelasAktif',
            // 'rataNilaiAkhir',
            // 'totalAktivitas',
            // 'persentaseHadir',
            'prodiList',
            'mahasiswaPerProdi',
            'recentUsers',
            'aktivitasMingguan',
            'aktivitasBulanan'
        ));
    }
}