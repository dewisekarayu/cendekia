<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\MataKuliah;
use App\Models\AktivitasPengguna;
use App\Models\AbsensiMahasiswa;
use App\Models\PengumpulanTugas;
use Carbon\Carbon;
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

        $isEn = app()->getLocale() === 'en' || session('locale') === 'en';

        // Mahasiswa yang belum memiliki asosiasi prodi
        $unassignedMahasiswa = User::role('mahasiswa')->whereNull('program_studi_id')->count();
        if ($unassignedMahasiswa > 0) {
            $mahasiswaPerProdi->push([
                'label' => $isEn ? 'Unassigned' : 'Belum Ditentukan',
                'value' => $unassignedMahasiswa,
            ]);
        }

        // Use the current year when it has data; otherwise show the latest year with activity.
        $bulanList = $isEn ? [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ] : [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $currentYear = now()->year;
        $latestActivityDate = collect([
            class_exists(AktivitasPengguna::class) ? AktivitasPengguna::max('terjadi_pada') : null,
            class_exists(AbsensiMahasiswa::class) ? AbsensiMahasiswa::max('waktu_absensi') : null,
            class_exists(PengumpulanTugas::class) ? PengumpulanTugas::max('waktu_kumpul') : null,
        ])->filter()->max();
        $latestActivityDate = $latestActivityDate ? Carbon::parse($latestActivityDate) : null;

        $currentYearActivityCount =
            AktivitasPengguna::whereYear('terjadi_pada', $currentYear)->count()
            + AbsensiMahasiswa::whereYear('waktu_absensi', $currentYear)->count()
            + PengumpulanTugas::whereYear('waktu_kumpul', $currentYear)->count();
        $activityYear = $currentYearActivityCount > 0
            ? $currentYear
            : ($latestActivityDate?->year ?? $currentYear);
        $activityPeriodLabel = $activityYear === $currentYear
            ? ($isEn ? "Activity in {$activityYear}" : "Aktivitas tahun {$activityYear}")
            : ($isEn ? "Latest activity year: {$activityYear}" : "Periode aktivitas terakhir: {$activityYear}");
        $aktivitasBulanan = [];
        $totalAktivitas = 0;

        foreach ($bulanList as $bulanNum => $bulanLabel) {
            $c1 = AktivitasPengguna::whereYear('terjadi_pada', $activityYear)->whereMonth('terjadi_pada', $bulanNum)->count();
            $c2 = AbsensiMahasiswa::whereYear('waktu_absensi', $activityYear)->whereMonth('waktu_absensi', $bulanNum)->count();
            $c3 = PengumpulanTugas::whereYear('waktu_kumpul', $activityYear)->whereMonth('waktu_kumpul', $bulanNum)->count();

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

        $weekReferenceDate = now();
        $currentWeekActivityCount =
            AktivitasPengguna::whereBetween('terjadi_pada', [$weekReferenceDate->copy()->startOfDay()->subDays(6), $weekReferenceDate->copy()->endOfDay()])->count()
            + AbsensiMahasiswa::whereBetween('waktu_absensi', [$weekReferenceDate->copy()->startOfDay()->subDays(6), $weekReferenceDate->copy()->endOfDay()])->count()
            + PengumpulanTugas::whereBetween('waktu_kumpul', [$weekReferenceDate->copy()->startOfDay()->subDays(6), $weekReferenceDate->copy()->endOfDay()])->count();

        if ($currentWeekActivityCount === 0 && $latestActivityDate) {
            $weekReferenceDate = $latestActivityDate->copy();
        }

        $aktivitasMingguan = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $weekReferenceDate->copy()->subDays($i);
            $dayName = $isEn ? $date->format('l') : ($hariIndo[$date->format('l')] ?? $date->format('D'));
            
            $c1 = AktivitasPengguna::whereDate('terjadi_pada', $date->toDateString())->count();
            $c2 = AbsensiMahasiswa::whereDate('waktu_absensi', $date->toDateString())->count();
            $c3 = PengumpulanTugas::whereDate('waktu_kumpul', $date->toDateString())->count();

            $dailyTotal = $c1 + $c2 + $c3;

            $aktivitasMingguan[] = [
                'label' => $dayName,
                'value' => $dailyTotal,
            ];
        }


        $recentUsers = User::latest()->take(5)->get();
        $recentMahasiswa = User::role('mahasiswa')
            ->with('programStudi')
            ->latest()
            ->take(5)
            ->get();
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
            'activityPeriodLabel',
            'programStudiList',
            'mahasiswaPerProdi',
            'aktivitasBulanan',
            'aktivitasMingguan',
            'recentUsers',
            'recentMahasiswa',
            'uptime'
        ));
    }
}