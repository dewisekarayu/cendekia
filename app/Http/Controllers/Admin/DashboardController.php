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
<<<<<<< HEAD
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
=======
    public function index()
    {
        // PERBAIKAN: Menggunakan method role() dari package Role agar tidak memanggil kolom 'role' secara langsung
        $totalDosen = User::role('dosen')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();
        
        $totalProgramStudi = ProgramStudi::count();
        $totalMataKuliah = MataKuliah::count();
        $totalKelasAktif = KelasPerkuliahan::where('is_active', true)->count();
        // $totalAktivitas = AktivitasPengguna::whereDate('terjadi_pada', today())->count();
        // $rataNilaiAkhir = round((float) NilaiAkhir::avg('nilai_akhir'), 2);
        
        // $totalPresensi = Presensi::count();
        // $persentaseHadir = $totalPresensi > 0
        //     ? round((Presensi::where('status', 'hadir')->count() / $totalPresensi) * 100, 2)
        //     : 0;

        // Memetakan jumlah mata kuliah per program studi
        $prodiList = ProgramStudi::withCount('mataKuliah')->get()->pluck('mata_kuliah_count', 'nama');

        // PERBAIKAN: Menghitung mahasiswa per prodi yang disesuaikan dengan sistem relasi role
        // $mahasiswaPerProdi = ProgramStudi::withCount(['users as mahasiswa_count' => function ($query) {
        //     $query->role('mahasiswa'); // Menggunakan filter role yang benar
        // }])->get()->pluck('mahasiswa_count', 'nama');

        $recentUsers = User::latest()->take(5)->get();
        
        // $aktivitasBulanan = AktivitasPengguna::selectRaw('MONTH(terjadi_pada) as bulan, COUNT(*) as total')
        //     ->whereYear('terjadi_pada', now()->year)
        //     ->groupBy('bulan')
        //     ->orderBy('bulan')
        //     ->get()
        //     ->map(fn ($row) => [
        //         'label' => date('M', mktime(0, 0, 0, $row->bulan, 1)), 
        //         'value' => (int) $row->total
        //     ]);

        $aktivitasMingguan = collect(range(0, 6))->map(function ($offset) {
            $date = now()->startOfWeek()->addDays($offset);
>>>>>>> 3ecbb9aa1ea688fe4e744016f1a5a2612a5c8395

            return [
                'label' => $date->translatedFormat('D'),
                // 'value' => AktivitasPengguna::whereDate('terjadi_pada', $date)->count(),
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
            // 'totalAktivitas',
            // 'rataNilaiAkhir',
            // 'persentaseHadir',
            'prodiList',
<<<<<<< HEAD
            'mahasiswaPerProdi',
=======
            // 'mahasiswaPerProdi',
>>>>>>> 3ecbb9aa1ea688fe4e744016f1a5a2612a5c8395
            'recentUsers',
            // 'aktivitasBulanan',
            'aktivitasMingguan'
        ));
    }
}