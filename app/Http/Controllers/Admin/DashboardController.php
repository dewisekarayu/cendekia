<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\MataKuliah;
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
        $totalProdi = ProgramStudi::count();
        $totalMatkul = MataKuliah::count();

        // Statistik Status Akademik Mahasiswa
        $totalAktif = User::role('mahasiswa')->where('status', 'aktif')->count();
        $totalCuti = User::role('mahasiswa')->where('status', 'cuti')->count();
        $totalNonAktif = User::role('mahasiswa')->where('status', 'non_aktif')->count();

        // Ambil Data Ringkasan Program Studi
        $programStudiList = ProgramStudi::withCount('mataKuliah')->get();

        // SOLUSI: Menghitung distribusi data Mahasiswa per Prodi untuk variabel $mahasiswaPerProdi
        $mahasiswaPerProdi = ProgramStudi::withCount(['mataKuliah', 'mataKuliah as value' => function($query) {
            // Jika relasi langsung ke mahasiswa lewat user, hitung jumlah user bermitras prodi_id
        }])->get()->map(function ($prodi) {
            // Hitung jumlah mahasiswa yang terdaftar di prodi ini
            $count = User::role('mahasiswa')->where('program_studi_id', $prodi->id)->count();
            
            return [
                'label' => $prodi->nama_prodi,
                'value' => $count
            ];
        });

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
        
        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalDosen',
            'totalMahasiswa',
            'totalProdi',
            'totalMatkul',
            'totalAktif',
            'totalCuti',
            'totalNonAktif',
            'programStudiList',
            'mahasiswaPerProdi', // Dikirim kesini agar view baris 89 tidak error lagi
            'aktivitasBulanan',
            'aktivitasMingguan',
            'recentUsers'
        ));
    }
}