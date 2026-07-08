<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiAkhir;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load('programStudi');

        // Pengumuman terbaru
        $kelasIds = $user->kelasDiikuti()->pluck('kelas_perkuliahan.id');
        $announcements = Pengumuman::where(function ($q) use ($kelasIds) {
                $q->whereIn('kelas_perkuliahan_id', $kelasIds)->orWhere('untuk_semua', true);
            })
            ->latest()
            ->take(6)
            ->get();

        // Statistik akademik
        $totalKelas    = $user->kelasDiikuti()->count();
        $nilaiAkhirList = NilaiAkhir::where('mahasiswa_id', $user->id)->get();
        $rataRata      = $nilaiAkhirList->avg('nilai_akhir');

        return view('mahasiswa.setting', compact(
            'user',
            'announcements',
            'totalKelas',
            'nilaiAkhirList',
            'rataRata'
        ));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'name.required'  => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.unique'   => 'Email sudah digunakan akun lain.',
        ]);

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.min'                      => 'Password baru minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }
}
