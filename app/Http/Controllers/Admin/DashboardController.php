<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDosen = User::role('dosen')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalProgramStudi = ProgramStudi::count();
        $totalMataKuliah = MataKuliah::count();
        $totalKelasAktif = KelasPerkuliahan::where('is_active', true)->count();

        $prodiList = ProgramStudi::withCount('mataKuliah')->get();

        // Hitung jumlah mahasiswa per prodi secara terpisah (lebih akurat)
        $mahasiswaPerProdi = User::role('mahasiswa')
            ->whereNotNull('program_studi_id')
            ->selectRaw('program_studi_id, count(*) as total')
            ->groupBy('program_studi_id')
            ->pluck('total', 'program_studi_id');

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalDosen',
            'totalMahasiswa',
            'totalProgramStudi',
            'totalMataKuliah',
            'totalKelasAktif',
            'prodiList',
            'mahasiswaPerProdi',
            'recentUsers'
        ));
    }
}
