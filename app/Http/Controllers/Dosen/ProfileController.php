<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Throwable;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('dosen.setting', ['tab' => 'profil']);
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

        return redirect()->route('dosen.setting', ['tab' => 'profil'])
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => ['required', 'image', 'max:10240'],
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu.',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.max'      => 'Ukuran foto maksimal 10MB.',
        ]);

        $user = $request->user();
        $fotoLama = $user->foto;

        try {
            $path = $request->file('foto')->store('foto-profil', 'public');
            if (!$path || !Storage::disk('public')->exists($path)) {
                throw new \RuntimeException('Uploaded profile photo was not written to public storage.');
            }

            if (!$user->update(['foto' => $path])) {
                Storage::disk('public')->delete($path);
                throw new \RuntimeException('Profile photo path was not saved to the user record.');
            }
        } catch (Throwable $exception) {
            Log::error('Unable to update lecturer profile photo.', [
                'user_id' => $user->id,
                'exception' => $exception,
            ]);

            return redirect()->route('dosen.setting', ['tab' => 'profil'])
                ->withErrors(['foto' => 'Foto gagal disimpan. Pastikan penyimpanan tersedia dan coba lagi.']);
        }

        if ($fotoLama) {
            try {
                Storage::disk('public')->delete($fotoLama);
            } catch (Throwable $exception) {
                Log::warning('Unable to remove previous lecturer profile photo.', [
                    'user_id' => $user->id,
                    'exception' => $exception,
                ]);
            }
        }

        return redirect()->route('dosen.setting', ['tab' => 'profil'])
            ->with('success', 'Foto profil berhasil diperbarui.');
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

        return redirect()->route('dosen.setting', ['tab' => 'profil'])
            ->with('success', 'Password berhasil diperbarui.');
    }
}