<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengumuman;
use App\Models\KelasMahasiswa;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan semua notifikasi pengguna dengan pagination.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $isMahasiswa = $user->hasRole('mahasiswa');
        $selectedDate = null;

        $query = $user->notifikasi();

        if ($isMahasiswa) {
            $validated = $request->validate([
                'tanggal' => ['sometimes', 'required', 'date_format:Y-m-d'],
            ]);

            $selectedDate = $validated['tanggal'] ?? now()->toDateString();
            $query->whereDate('created_at', $selectedDate);
        }

        $notifikasis = $query
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $pengumuman = null;
        if ($isMahasiswa) {
            $kelasIds = KelasMahasiswa::where('mahasiswa_id', $user->id)
                ->pluck('kelas_perkuliahan_id');

            $pengumuman = Pengumuman::with(['pembuat', 'kelasPerkuliahan'])
                ->where(function ($announcementQuery) use ($kelasIds) {
                    $announcementQuery->whereNull('kelas_perkuliahan_id')
                        ->orWhereIn('kelas_perkuliahan_id', $kelasIds);
                })
                ->latest()
                ->paginate(5, ['*'], 'pengumuman_page')
                ->withQueryString();
        }

        return view('notifikasi.index', compact(
            'notifikasis',
            'isMahasiswa',
            'selectedDate',
            'pengumuman'
        ));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca dan redirect ke URL-nya.
     */
    public function baca(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id == auth()->id()) {
            $notifikasi->update(['dibaca_pada' => now()]);
            $notifikasi->save();
        }

        $url = $notifikasi->url;
        if (empty($url) || $url == '#') {
            $url = url()->previous();
        }
        
        return redirect($url);
    }

    /**
     * Tandai semua notifikasi pengguna saat ini sebagai dibaca.
     */
    public function bacaSemua()
    {
        auth()->user()->notifikasi()
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
