<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationPreferenceController extends Controller
{
    /**
     * Display notification preferences for all users
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $role = $request->input('role', '');

        $usersQuery = User::query();

        if ($search) {
            $usersQuery->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('nip_nim', 'like', "%{$search}%");
        }

        if ($role && in_array($role, ['admin', 'dosen', 'mahasiswa'])) {
            $usersQuery->role($role);
        }

        $users = $usersQuery->with('notificationPreferences')
            ->paginate(20);

        $notificationTypes = [
            'materi_baru' => '📚 Materi Baru',
            'tugas_baru' => '📝 Tugas Baru',
            'pengumuman_baru' => '📢 Pengumuman Baru',
            'nilai_baru' => '✅ Nilai Baru',
            'absensi_dibuka' => '⏰ Presensi Dibuka',
            'pengumpulan_tugas' => '📬 Pengumpulan Tugas',
            'pesan_baru' => '💬 Pesan Baru',
            'pengguna_baru' => '👤 Pengguna Baru',
        ];

        return view('admin.notification-preferences.index', compact(
            'users',
            'search',
            'role',
            'notificationTypes'
        ));
    }

    /**
     * Show preferences for a specific user
     */
    public function show(User $user)
    {
        $preferences = NotificationPreference::forUser($user->id);

        $notificationTypes = [
            'materi_baru' => [
                'name' => '📚 Materi Baru',
                'description' => 'Notifikasi saat dosen mengunggah materi pembelajaran baru',
                'audience' => 'Mahasiswa'
            ],
            'tugas_baru' => [
                'name' => '📝 Tugas Baru',
                'description' => 'Notifikasi saat dosen membuat tugas baru',
                'audience' => 'Mahasiswa'
            ],
            'pengumuman_baru' => [
                'name' => '📢 Pengumuman Baru',
                'description' => 'Notifikasi saat dosen membuat pengumuman kelas',
                'audience' => 'Mahasiswa'
            ],
            'nilai_baru' => [
                'name' => '✅ Nilai Baru',
                'description' => 'Notifikasi saat dosen memberikan nilai tugas',
                'audience' => 'Mahasiswa'
            ],
            'absensi_dibuka' => [
                'name' => '⏰ Presensi Dibuka',
                'description' => 'Notifikasi saat sesi presensi dibuka',
                'audience' => 'Mahasiswa'
            ],
            'pengumpulan_tugas' => [
                'name' => '📬 Pengumpulan Tugas',
                'description' => 'Notifikasi saat mahasiswa mengumpulkan tugas (untuk dosen)',
                'audience' => 'Dosen'
            ],
            'pesan_baru' => [
                'name' => '💬 Pesan Baru',
                'description' => 'Notifikasi saat ada pesan baru di forum diskusi',
                'audience' => 'Mahasiswa & Dosen'
            ],
            'pengguna_baru' => [
                'name' => '👤 Pengguna Baru',
                'description' => 'Notifikasi saat pengguna baru mendaftar',
                'audience' => 'Admin'
            ],
        ];

        return view('admin.notification-preferences.show', compact(
            'user',
            'preferences',
            'notificationTypes'
        ));
    }

    /**
     * Update user preferences
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'materi_baru' => 'nullable|boolean',
            'tugas_baru' => 'nullable|boolean',
            'pengumuman_baru' => 'nullable|boolean',
            'nilai_baru' => 'nullable|boolean',
            'absensi_dibuka' => 'nullable|boolean',
            'pengumpulan_tugas' => 'nullable|boolean',
            'pesan_baru' => 'nullable|boolean',
            'pengguna_baru' => 'nullable|boolean',
        ]);

        $preferences = NotificationPreference::forUser($user->id);

        $preferences->update([
            'materi_baru' => $request->boolean('materi_baru'),
            'tugas_baru' => $request->boolean('tugas_baru'),
            'pengumuman_baru' => $request->boolean('pengumuman_baru'),
            'nilai_baru' => $request->boolean('nilai_baru'),
            'absensi_dibuka' => $request->boolean('absensi_dibuka'),
            'pengumpulan_tugas' => $request->boolean('pengumpulan_tugas'),
            'pesan_baru' => $request->boolean('pesan_baru'),
            'pengguna_baru' => $request->boolean('pengguna_baru'),
        ]);

        return redirect()
            ->route('admin.notification-preferences.show', $user)
            ->with('success', 'Preferensi notifikasi berhasil diperbarui.');
    }

    /**
     * Reset all preferences for a user to default (all enabled)
     */
    public function reset(User $user)
    {
        $preferences = NotificationPreference::forUser($user->id);
        $preferences->update([
            'materi_baru' => true,
            'tugas_baru' => true,
            'pengumuman_baru' => true,
            'nilai_baru' => true,
            'absensi_dibuka' => true,
            'pengumpulan_tugas' => true,
            'pesan_baru' => true,
            'pengguna_baru' => true,
        ]);

        return redirect()
            ->route('admin.notification-preferences.show', $user)
            ->with('success', 'Preferensi notifikasi direset ke default.');
    }

    /**
     * Disable all notifications for a user
     */
    public function disableAll(User $user)
    {
        $preferences = NotificationPreference::forUser($user->id);
        $preferences->update([
            'materi_baru' => false,
            'tugas_baru' => false,
            'pengumuman_baru' => false,
            'nilai_baru' => false,
            'absensi_dibuka' => false,
            'pengumpulan_tugas' => false,
            'pesan_baru' => false,
            'pengguna_baru' => false,
        ]);

        return redirect()
            ->route('admin.notification-preferences.show', $user)
            ->with('success', 'Semua notifikasi berhasil dinonaktifkan.');
    }

    /**
     * Enable all notifications for a user
     */
    public function enableAll(User $user)
    {
        $preferences = NotificationPreference::forUser($user->id);
        $preferences->update([
            'materi_baru' => true,
            'tugas_baru' => true,
            'pengumuman_baru' => true,
            'nilai_baru' => true,
            'absensi_dibuka' => true,
            'pengumpulan_tugas' => true,
            'pesan_baru' => true,
            'pengguna_baru' => true,
        ]);

        return redirect()
            ->route('admin.notification-preferences.show', $user)
            ->with('success', 'Semua notifikasi berhasil diaktifkan.');
    }
}
