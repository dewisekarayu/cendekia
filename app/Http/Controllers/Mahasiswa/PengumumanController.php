<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Tampilkan daftar pengumuman global dan kelas.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Ambil ID kelas yang diikuti mahasiswa
        $kelasIds = \App\Models\KelasMahasiswa::where('mahasiswa_id', $user->id)
            ->pluck('kelas_perkuliahan_id')
            ->toArray();

        // Ambil pengumuman (Global + Kelas yang diikuti)
        $pengumuman = Pengumuman::with(['pembuat', 'kelasPerkuliahan'])
            ->where(function ($query) use ($kelasIds) {
                $query->whereNull('kelas_perkuliahan_id')
                      ->orWhereIn('kelas_perkuliahan_id', $kelasIds);
            })
            ->latest()
            ->paginate(10);

        return view('mahasiswa.pengumuman.index', compact('pengumuman'));
    }
}
