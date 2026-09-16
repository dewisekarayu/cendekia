<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    /**
     * Display the admin settings page.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $preferences = NotificationPreference::forUser($user->id);

        $totalUsers = User::count();
        $totalAdmins = User::role('admin')->count();
        $totalDosen = User::role('dosen')->count();
        $totalMahasiswa = User::role('mahasiswa')->count();

        return view('admin.setting', compact(
            'user',
            'preferences',
            'totalUsers',
            'totalAdmins',
            'totalDosen',
            'totalMahasiswa'
        ));
    }

    /**
     * Update general settings (language and theme).
     */
    public function updateUmum(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'language' => ['required', 'string', 'in:id,en'],
            'theme'    => ['required', 'string', 'in:light,dark,auto'],
        ]);

        $user->update([
            'language' => $validated['language'],
            'theme'    => $validated['theme'],
        ]);

        session(['locale' => $validated['language']]);

        return back()->with('success', $validated['language'] === 'en' ? 'General settings updated successfully.' : 'Pengaturan umum dan tampilan berhasil disimpan.');
    }

    /**
     * Update admin profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'nip_nim' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'phone'   => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil administrator berhasil diperbarui.');
    }

    /**
     * Update admin password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Kata sandi administrator berhasil diperbarui.');
    }

    /**
     * Update admin notification preferences.
     */
    public function updateNotifikasi(Request $request)
    {
        $user = $request->user();
        $preferences = NotificationPreference::forUser($user->id);

        $preferences->update([
            'pengguna_baru'    => $request->boolean('pengguna_baru'),
            'pesan_baru'       => $request->boolean('pesan_baru'),
            'pengumuman_baru'  => $request->boolean('pengumuman_baru'),
            'pengumpulan_tugas'=> $request->boolean('pengumpulan_tugas'),
            'materi_baru'      => $request->boolean('materi_baru'),
            'tugas_baru'       => $request->boolean('tugas_baru'),
            'nilai_baru'       => $request->boolean('nilai_baru'),
            'absensi_dibuka'   => $request->boolean('absensi_dibuka'),
        ]);

        return back()->with('success', 'Preferensi notifikasi admin berhasil diperbarui.');
    }
}
