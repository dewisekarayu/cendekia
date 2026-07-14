@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">📧 Manajemen Preferensi Notifikasi Email</h1>
        <p class="mt-2 text-gray-600">Kelola preferensi email untuk semua pengguna di sistem Cendekia</p>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form method="GET" action="{{ route('admin.notification-preferences.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Pengguna</label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search" 
                        placeholder="Nama, email, atau NIM/NIP..."
                        value="{{ $search }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Filter Peran</label>
                    <select name="role" id="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Peran</option>
                        <option value="admin" @selected($role === 'admin')>Admin</option>
                        <option value="dosen" @selected($role === 'dosen')>Dosen</option>
                        <option value="mahasiswa" @selected($role === 'mahasiswa')>Mahasiswa</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        🔍 Cari
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if($users->count() > 0)
    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Pengguna</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Peran</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Status Notifikasi</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $user->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $user->email }}
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @foreach($user->getRoleNames() as $role)
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium mr-1">
                                {{ ucfirst($role) }}
                            </span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @php
                            $prefs = $user->notificationPreferences;
                            if (!$prefs) {
                                echo '<span class="text-gray-500">Tidak dikonfigurasi</span>';
                            } else {
                                $enabledCount = collect([
                                    $prefs->materi_baru,
                                    $prefs->tugas_baru,
                                    $prefs->pengumuman_baru,
                                    $prefs->nilai_baru,
                                    $prefs->absensi_dibuka,
                                    $prefs->pengumpulan_tugas,
                                    $prefs->pesan_baru,
                                    $prefs->pengguna_baru,
                                ])->filter()->count();
                                $totalCount = 8;
                                
                                if ($enabledCount === $totalCount) {
                                    echo '<span class="inline-block px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-medium">✓ Semua Aktif (' . $enabledCount . '/8)</span>';
                                } elseif ($enabledCount === 0) {
                                    echo '<span class="inline-block px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">✗ Semua Nonaktif</span>';
                                } else {
                                    echo '<span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">⚠ Sebagian Aktif (' . $enabledCount . '/8)</span>';
                                }
                            }
                        @endphp
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.notification-preferences.show', $user) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            ⚙️ Kelola
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $users->links() }}
    </div>
    @else
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
        <p class="text-yellow-800">Tidak ada pengguna yang ditemukan.</p>
    </div>
    @endif
</div>
@endsection
