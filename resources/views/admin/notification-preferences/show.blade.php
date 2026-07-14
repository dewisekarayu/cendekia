@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.notification-preferences.index') }}" class="text-blue-600 hover:text-blue-900 font-medium mb-4 inline-block">
            ← Kembali ke Daftar
        </a>
        <h1 class="text-3xl font-bold text-gray-900">📧 Preferensi Notifikasi</h1>
        <p class="mt-2 text-gray-600">Kelola preferensi email untuk {{ $user->name }}</p>
    </div>

    <!-- User Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Nama</p>
                <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Peran</p>
                <p class="text-lg font-semibold text-gray-900">
                    @foreach($user->getRoleNames() as $role)
                        <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded text-sm mr-1">
                            {{ ucfirst($role) }}
                        </span>
                    @endforeach
                </p>
            </div>
        </div>
    </div>

    <!-- Preferences Form -->
    <form method="POST" action="{{ route('admin.notification-preferences.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Jenis-Jenis Notifikasi</h2>

            <div class="space-y-4">
                @foreach($notificationTypes as $key => $info)
                <div class="flex items-start p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="flex items-center h-6">
                        <input 
                            type="checkbox" 
                            name="{{ $key }}" 
                            id="{{ $key }}"
                            value="1"
                            @checked($preferences && $preferences->isEnabled($key))
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        >
                    </div>
                    <label for="{{ $key }}" class="ml-4 flex-1 cursor-pointer">
                        <p class="text-base font-medium text-gray-900">{{ $info['name'] }}</p>
                        <p class="text-sm text-gray-600">{{ $info['description'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">👥 {{ $info['audience'] }}</p>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        ✓ Simpan Perubahan
                    </button>
                    <form method="POST" action="{{ route('admin.notification-preferences.reset', $user) }}" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-medium" 
                            onclick="return confirm('Apakah Anda yakin ingin mereset ke default?')">
                            🔄 Reset ke Default
                        </button>
                    </form>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <form method="POST" action="{{ route('admin.notification-preferences.disableAll', $user) }}" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium"
                            onclick="return confirm('Nonaktifkan semua notifikasi?')">
                            ✗ Nonaktifkan Semua
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.notification-preferences.enableAll', $user) }}" style="display: inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            ✓ Aktifkan Semua
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </form>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-blue-900 mb-2">💡 Informasi</h3>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Notifikasi yang dinonaktifkan tidak akan dikirim ke email pengguna</li>
            <li>• Perubahan berlaku segera setelah disimpan</li>
            <li>• Pengguna dapat mengubah preferensi mereka sendiri di pengaturan akun</li>
        </ul>
    </div>
</div>
@endsection
