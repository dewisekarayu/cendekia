<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan semua notifikasi pengguna dengan pagination.
     */
    public function index()
    {
        $notifikasis = auth()->user()->notifikasi()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifikasi.index', compact('notifikasis'));
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
