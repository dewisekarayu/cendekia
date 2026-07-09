<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasPengguna;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\KelasPerkuliahan;
use App\Models\User;
use App\Models\NilaiAkhir;
use App\Models\AbsensiMahasiswa;
use Illuminate\Http\Request;
use Exception;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data statistik utama menggunakan Helper Spatie Role Checking
        $totalDosen = User::role('dosen')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalProgramStudi = ProgramStudi::count();
        $totalMataKuliah = MataKuliah::count();
        $uptime = '99.9';

        // Menggunakan optional data dari versi kedua
        $totalKelasAktif = KelasPerkuliahan::where('is_active', true)->count();
        $rataNilaiAkhir = class_exists(NilaiAkhir::class) ? round((float) NilaiAkhir::avg('nilai_akhir'), 2) : 0;
        $totalPresensi = class_exists(AbsensiMahasiswa::class) ? AbsensiMahasiswa::count() : 0;
        $persentaseHadir = $totalPresensi > 0 && class_exists(AbsensiMahasiswa::class)
            ? round((AbsensiMahasiswa::where('status', 'hadir')->count() / $totalPresensi) * 100, 1)
            : 0;

        $prodiList = ProgramStudi::withCount('mataKuliah')->get();
        $recentUsers = User::latest()->take(5)->get();

        // 2. Ambil data grafik aktivitas dengan proteksi try-catch & data fallback (dummy) jika kosong
        try {
            $totalAktivitas = AktivitasPengguna::whereDate('terjadi_pada', today())->count();
            if ($totalAktivitas === 0) { 
                $totalAktivitas = 150; 
            }

            // Grafik bulanan terintegrasi
            $aktivitasBulanan = collect(range(1, 12))->map(function ($month) {
                $count = AktivitasPengguna::whereMonth('terjadi_pada', $month)
                    ->whereYear('terjadi_pada', now()->year)
                    ->count();
                $dummyValues = [1 => 320, 450, 410, 520, 380, 610, 470, 500, 430, 560, 590, 400];
                return [
                    'label' => now()->month($month)->translatedFormat('M'), 
                    'value' => $count > 0 ? $count : $dummyValues[$month]
                ];
            })->toArray();

            // Grafik mingguan terintegrasi
            $aktivitasMingguan = collect(range(0, 6))->map(function ($day) {
                $date = now()->startOfWeek()->addDays($day);
                $count = AktivitasPengguna::whereDate('terjadi_pada', $date)->count();
                $dummyValues = [120, 150, 135, 170, 190, 90, 60];
                return [
                    'label' => $date->translatedFormat('D'), 
                    'value' => $count > 0 ? $count : $dummyValues[$day]
                ];
            })->toArray();

        } catch (Exception $e) {
            // Fallback total aman jika tabel database mengalami kendala/belum migrate
            $totalAktivitas = 150;
            $aktivitasBulanan = [
                ['label' => 'Jan', 'value' => 320], ['label' => 'Feb', 'value' => 450], ['label' => 'Mar', 'value' => 410],
                ['label' => 'Apr', 'value' => 520], ['label' => 'Mei', 'value' => 380], ['label' => 'Jun', 'value' => 610],
                ['label' => 'Jul', 'value' => 470], ['label' => 'Agu', 'value' => 500], ['label' => 'Sep', 'value' => 430],
                ['label' => 'Okt', 'value' => 560], ['label' => 'Nov', 'value' => 590], ['label' => 'Des', 'value' => 400],
            ];
            $aktivitasMingguan = [
                ['label' => 'Sen', 'value' => 120], ['label' => 'Sel', 'value' => 150], ['label' => 'Rab', 'value' => 135],
                ['label' => 'Kam', 'value' => 170], ['label' => 'Jum', 'value' => 190], ['label' => 'Sab', 'value' => 90],
                ['label' => 'Min', 'value' => 60],
            ];
        }

        // 3. Mapping data statistik Mahasiswa per Program Studi
        $mahasiswaPerProdi = ProgramStudi::all()->map(function ($prodi) {
            return [
                'label' => $prodi->nama_prodi ?? $prodi->nama ?? $prodi->kode_prodi,
                'value' => User::role('mahasiswa')->where('program_studi_id', $prodi->id)->count(),
            ];
        })->toArray();

        // 4. Return menuju View Admin Dashboard
        return view('admin.dashboard', compact(
            'totalDosen', 
            'totalMahasiswa', 
            'totalProgramStudi', 
            'totalMataKuliah',
            'totalKelasAktif', 
            'rataNilaiAkhir', 
            'totalAktivitas', 
            'persentaseHadir', 
            'uptime', 
            'prodiList', 
            'mahasiswaPerProdi', 
            'aktivitasBulanan', 
            'aktivitasMingguan', 
            'recentUsers'
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