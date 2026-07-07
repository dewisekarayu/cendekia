<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $announcements = collect();

        if ($user?->program_studi_id) {
            $kelasIds = $user->kelasDiikuti()->pluck('kelas_perkuliahan.id');

            $announcements = Pengumuman::where(function ($query) use ($kelasIds) {
                $query->whereIn('kelas_perkuliahan_id', $kelasIds)
                    ->orWhere('untuk_semua', true);
            })
                ->latest()
                ->take(5)
                ->get();
        }

        return view('mahasiswa.setting', compact('announcements'));
    }
}
