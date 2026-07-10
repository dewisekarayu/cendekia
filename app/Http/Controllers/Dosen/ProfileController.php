<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Pengumuman terbaru dari kelas-kelas yang diampu dosen ini
        $kelasIds = $user->kelasDiampu()->pluck('kelas_perkuliahan.id');

        $announcements = Pengumuman::whereIn('kelas_perkuliahan_id', $kelasIds)
            ->latest()
            ->take(6)
            ->get();

        // Statistik akademik dosen
        $totalKelas = $user->kelasDiampu()->count();

        return view('dosen.profil', compact(
            'user',
            'announcements',
            'totalKelas'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telepon' => ['nullable', 'string', 'max:20'],
            'nip_nim' => ['nullable', 'string', 'max:50', Rule::unique('users', 'nip_nim')->ignore($user->id)],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan akun lain.',
            'nip_nim.unique' => 'NID sudah digunakan akun lain.',
        ]);

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'max:2048'],
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu.',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        $user = $request->user();

        // Hapus foto lama kalau ada
        if ($user->foto) {
            Storage::disk('public')->delete($user->foto);
        }

        $path = $request->file('foto')->store('foto-profil', 'public');

        $user->update(['foto' => $path]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.min'                       => 'Password baru minimal 8 karakter.',
            'password.confirmed'                 => 'Konfirmasi password tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}