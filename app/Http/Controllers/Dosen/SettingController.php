<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page for dosen.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $preferences = NotificationPreference::forUser($user->id);

        return view('dosen.setting', compact('user', 'preferences'));
    }

    /**
     * Update general settings (language and theme).
     */
    public function updateUmum(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'language' => ['required', 'string', 'in:id,en'],
            'theme' => ['required', 'string', 'in:light,dark,auto'],
        ]);

        $user->update([
            'language' => $validated['language'],
            'theme' => $validated['theme'],
        ]);

        session(['locale' => $validated['language']]);

        return back()->with('success', $validated['language'] === 'en' ? 'General settings updated successfully.' : 'Pengaturan umum berhasil diperbarui.');
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifikasi(Request $request)
    {
        $user = $request->user();
        $preferences = NotificationPreference::forUser($user->id);

        $preferences->update([
            'pengumpulan_tugas' => $request->has('pengumpulan_tugas'),
            'pesan_baru' => $request->has('pesan_baru'),
            'pengumuman_baru' => $request->has('pengumuman_baru'),
        ]);

        $lang = $user->language ?? 'id';
        return back()->with('success', $lang === 'en' ? 'Notification preferences updated successfully.' : 'Preferensi notifikasi berhasil diperbarui.');
    }
}
