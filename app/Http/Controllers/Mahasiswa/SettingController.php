<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiAkhir;
use App\Models\Pengumuman;
use App\Models\NotificationPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function profil(Request $request)
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

        return view('mahasiswa.profil', compact(
            'user',
            'announcements',
            'totalKelas',
            'nilaiAkhirList',
            'rataRata'
        ));
    }

    public function setting(Request $request)
    {
        $user = $request->user()->load('programStudi');
        $preferences = NotificationPreference::forUser($user->id);

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
            'preferences',
            'announcements',
            'totalKelas',
            'nilaiAkhirList',
            'rataRata'
        ));
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
            'password.min'                      => 'Password baru minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi password tidak cocok.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
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
            'materi_baru' => $request->has('materi_baru'),
            'tugas_baru' => $request->has('tugas_baru'),
            'pengumuman_baru' => $request->has('pengumuman_baru'),
            'nilai_baru' => $request->has('nilai_baru'),
            'absensi_dibuka' => $request->has('absensi_dibuka'),
            'pesan_baru' => $request->has('pesan_baru'),
        ]);

        $lang = $user->language ?? 'id';
        return back()->with('success', $lang === 'en' ? 'Notification preferences updated successfully.' : 'Preferensi notifikasi berhasil diperbarui.');
    }
}
