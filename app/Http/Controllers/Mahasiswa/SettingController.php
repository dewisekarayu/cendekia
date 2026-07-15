<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get stats for dashboard
        $totalKelas = $user->kelasDiikuti()->count();
        $nilaiAkhirList = $user->nilaiAkhir()->get();
        $rataRata = $nilaiAkhirList->avg('nilai_akhir');
        $announcements = collect([]);
        
        return view('mahasiswa.setting', compact(
            'user',
            'totalKelas',
            'nilaiAkhirList',
            'rataRata',
            'announcements'
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

        return redirect()->route('mahasiswa.setting')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Update user photo/avatar
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

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui',
            'foto_url' => asset('storage/' . $path),
        ]);
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

        return redirect()->route('mahasiswa.setting')
            ->with('success', 'Password berhasil diperbarui');
    }
}
