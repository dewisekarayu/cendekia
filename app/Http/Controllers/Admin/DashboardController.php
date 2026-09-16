<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\MataKuliah;
use App\Models\AktivitasPengguna;
use App\Models\AbsensiMahasiswa;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan Halaman Utama Dashboard Admin
     */
    public function index()
    {
        // Statistik Utama (Stat Cards)
        $totalDosen = User::role('dosen')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalProgramStudi = ProgramStudi::count();
        $totalProdi = $totalProgramStudi;
        $totalMataKuliah = MataKuliah::count();
        $totalMatkul = $totalMataKuliah;

        // Statistik Status Akademik Mahasiswa
        $totalAktif = User::role('mahasiswa')->where('status', 'aktif')->count();
        $totalCuti = User::role('mahasiswa')->where('status', 'cuti')->count();
        $totalNonAktif = User::role('mahasiswa')->where('status', 'non_aktif')->count();

        // Ambil Data Ringkasan Program Studi
        $programStudiList = ProgramStudi::withCount('mataKuliah')->get();

        // Menghitung distribusi data Mahasiswa per Prodi untuk $mahasiswaPerProdi
        $mahasiswaPerProdi = ProgramStudi::all()->map(function ($prodi) {
            $count = User::role('mahasiswa')->where('program_studi_id', $prodi->id)->count();
            return [
                'label' => $prodi->nama_prodi,
                'value' => $count,
            ];
        });

<<<<<<< HEAD
        // Inisialisasi variabel mockup chart tambahan agar dashboard tidak komplain undefined
        $aktivitasBulanan = [
            ['label' => 'Jan', 'value' => rand(500, 1000)],
            ['label' => 'Feb', 'value' => rand(500, 1000)],
            ['label' => 'Mar', 'value' => rand(500, 1000)],
            ['label' => 'Apr', 'value' => rand(500, 1000)],
            ['label' => 'Mei', 'value' => rand(500, 1000)],
            ['label' => 'Jun', 'value' => rand(500, 1000)],
        ];
        
        $aktivitasMingguan = [
            ['label' => 'Senin', 'value' => rand(100, 300)],
            ['label' => 'Selasa', 'value' => rand(100, 300)],
            ['label' => 'Rabu', 'value' => rand(100, 300)],
            ['label' => 'Kamis', 'value' => rand(100, 300)],
            ['label' => 'Jumat', 'value' => rand(100, 300)],
            ['label' => 'Sabtu', 'value' => rand(20, 80)],
            ['label' => 'Minggu', 'value' => rand(10, 50)],
        ];
        
=======
        // Mahasiswa yang belum memiliki asosiasi prodi
        $unassignedMahasiswa = User::role('mahasiswa')->whereNull('program_studi_id')->count();
        if ($unassignedMahasiswa > 0) {
            $mahasiswaPerProdi->push([
                'label' => 'Belum Ditentukan',
                'value' => $unassignedMahasiswa,
            ]);
        }

        // Hitung aktivitas bulanan (Tahun Berjalan)
        $bulanList = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $currentYear = now()->year;
        $aktivitasBulanan = [];
        $totalAktivitas = 0;

        foreach ($bulanList as $bulanNum => $bulanLabel) {
            $c1 = class_exists(AktivitasPengguna::class) ? AktivitasPengguna::whereYear('created_at', $currentYear)->whereMonth('created_at', $bulanNum)->count() : 0;
            $c2 = class_exists(AbsensiMahasiswa::class) ? AbsensiMahasiswa::whereYear('created_at', $currentYear)->whereMonth('created_at', $bulanNum)->count() : 0;
            $c3 = class_exists(PengumpulanTugas::class) ? PengumpulanTugas::whereYear('created_at', $currentYear)->whereMonth('created_at', $bulanNum)->count() : 0;

            $totalMonthly = $c1 + $c2 + $c3;
            $totalAktivitas += $totalMonthly;

            $aktivitasBulanan[] = [
                'label' => $bulanLabel,
                'value' => $totalMonthly,
            ];
        }

        // Hitung aktivitas mingguan (7 hari terakhir)
        $hariIndo = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $aktivitasMingguan = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayName = $hariIndo[$date->format('l')] ?? $date->format('D');
            
            $c1 = class_exists(AktivitasPengguna::class) ? AktivitasPengguna::whereDate('created_at', $date->toDateString())->count() : 0;
            $c2 = class_exists(AbsensiMahasiswa::class) ? AbsensiMahasiswa::whereDate('created_at', $date->toDateString())->count() : 0;
            $c3 = class_exists(PengumpulanTugas::class) ? PengumpulanTugas::whereDate('created_at', $date->toDateString())->count() : 0;

            $dailyTotal = $c1 + $c2 + $c3;

            $aktivitasMingguan[] = [
                'label' => $dayName,
                'value' => $dailyTotal,
            ];
        }

>>>>>>> a3574bb (feat: perbarui ui dosen, admin, tata letak tabel, pagination, dan dropdown serta mempefeat: perbarui ui dosen, admin, tata let)
        $recentUsers = User::latest()->take(5)->get();
        $uptime = '99.9';

        return view('admin.dashboard', compact(
            'totalDosen',
            'totalMahasiswa',
            'totalProdi',
            'totalProgramStudi',
            'totalMatkul',
            'totalMataKuliah',
            'totalAktif',
            'totalCuti',
            'totalNonAktif',
            'totalAktivitas',
            'programStudiList',
            'mahasiswaPerProdi',
            'aktivitasBulanan',
            'aktivitasMingguan',
            'recentUsers',
            'uptime'
        ));
    }
}