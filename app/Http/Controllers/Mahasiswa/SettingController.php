<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use App\Models\NilaiAkhir;
use App\Models\NotificationPreference;

class SettingController extends Controller
{
    /**
     * Shared stats used by both the Profil and Setting pages
     * so the summary cards stay identical on both.
     */
    protected function getStats($user): array
    {
        $totalKelas = $user->kelasDiikuti()->count();
        $nilaiAkhirList = NilaiAkhir::where('mahasiswa_id', $user->id)->get();
        $rataRata = $nilaiAkhirList->avg('nilai_akhir');
        $announcements = collect([]);

        return compact('totalKelas', 'nilaiAkhirList', 'rataRata', 'announcements');
    }

    /**
     * Display settings page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $preferences = NotificationPreference::forUser($user->id);

        $stats = $this->getStats($user);

        return view('mahasiswa.setting', array_merge(
            compact('user', 'preferences'),
            $stats
        ));
    }

    /**
     * Redirect the legacy profile page to its tab in Settings.
     */
    public function profil(Request $request)
    {
        return redirect()->route('mahasiswa.setting', ['tab' => 'profil']);
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->user()->id)],
            'telepon' => ['nullable', 'string', 'max:20'],
            'nip_nim' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nip_nim')->ignore($request->user()->id)],
        ]);

        $request->user()->update($validated);

        return redirect()->route('mahasiswa.setting', ['tab' => 'profil'])
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Update user photo/avatar
     * (versi form submit biasa — redirect balik ke halaman profil, bukan JSON)
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'max:10240', 'mimes:jpeg,png,jpg,gif'],
        ]);

        $user = $request->user();
        $fotoLama = $user->foto;

        $path = $request->file('foto')->store('foto-profil', 'public');
        if ($path === false) {
            return back()->withErrors(['foto' => 'Foto profil gagal disimpan. Silakan coba lagi.']);
        }

        if (!$user->update(['foto' => $path])) {
            Storage::disk('public')->delete($path);

            return back()->withErrors(['foto' => 'Foto profil gagal diperbarui. Silakan coba lagi.']);
        }

        if ($fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()->route('mahasiswa.setting', ['tab' => 'profil'])
            ->with('success', 'Foto profil berhasil diperbarui');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('mahasiswa.setting', ['tab' => 'profil'])
            ->with('success', 'Password berhasil diperbarui');
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
        if ($validated['language'] === 'en') {
            @setcookie('googtrans', '/id/en', time() + (86400 * 365), '/');
            $_COOKIE['googtrans'] = '/id/en';
        } else {
            @setcookie('googtrans', '', time() - 3600, '/');
            $host = $request->getHost();
            if ($host && $host !== 'localhost') {
                @setcookie('googtrans', '', time() - 3600, '/', '.' . $host);
            }
            unset($_COOKIE['googtrans']);
        }

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
            'tugas_baru' => $request->has('tugas_baru'),
            'materi_baru' => $request->has('materi_baru'),
            'pengumuman_baru' => $request->has('pengumuman_baru'),
            'nilai_baru' => $request->has('nilai_baru'),
            'absensi_dibuka' => $request->has('absensi_dibuka'),
            'pesan_baru' => $request->has('pesan_baru'),
        ]);

        $lang = $user->language ?? 'id';
        return back()->with('success', $lang === 'en' ? 'Notification preferences updated successfully.' : 'Preferensi notifikasi berhasil diperbarui.');
    }
}