<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

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
     * Display settings page (Umum & Notifikasi)
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
     * Display profil page (Keamanan)
     */
    public function profil(Request $request)
    {
        $user = $request->user();

        $stats = $this->getStats($user);

        return view('mahasiswa.profil', array_merge(
            compact('user'),
            $stats
        ));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$request->user()->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->update($validated);

        return redirect()->route('mahasiswa.profil')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Update user photo/avatar
     * (versi form submit biasa — redirect balik ke halaman profil, bukan JSON)
     */
    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif'],
        ]);

        $user = $request->user();

        // Delete old photo if exists
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        // Store new photo
        $path = $request->file('foto')->store('avatars/mahasiswa', 'public');
        $user->update(['foto' => $path]);

        return redirect()->route('mahasiswa.profil')
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

        return redirect()->route('mahasiswa.profil')
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