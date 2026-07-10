@extends('layouts.portal')

@section('title', 'Detail Sesi Presensi - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Detail Sesi Presensi
            </h1>
            <p class="mt-1 text-gray-500">
                {{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span> • Pertemuan {{ $absensi->pertemuan_ke }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <div class="relative group">
                <button type="button" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                    Aksi
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-10 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition">
                    <a href="{{ route('dosen.absensi.edit', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" class="block px-4 py-2 text-sm text-purple-600 hover:bg-purple-50 transition">Edit Presensi</a>
                    @if($absensi->isDraft())
                        <form action="{{ route('dosen.absensi.buka', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-green-50 transition">Buka Sesi Presensi</button>
                        </form>
                    @elseif($absensi->isBuka())
                        <form action="{{ route('dosen.absensi.tutup', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-yellow-600 hover:bg-yellow-50 transition">Tutup Sesi Presensi</button>
                        </form>
                    @endif
                    <a href="{{ route('dosen.absensi.attendance', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" class="block px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 transition">Edit Kehadiran Manual</a>
                    <form action="{{ route('dosen.absensi.destroy', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus sesi ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Hapus Sesi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <p class="font-semibold text-green-900">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <p class="font-semibold text-yellow-900">{{ session('warning') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Session Info -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Session Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Sesi Presensi</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-4 gap-4">
                        <div class="text-center">
                            <p class="text-gray-600 text-sm font-medium mb-2">Status Sesi</p>
                            @php
                                $statusClass = match($absensi->session_status) {
                                    'draft' => 'bg-yellow-100 text-yellow-800',
                                    'buka' => 'bg-green-100 text-green-800',
                                    'tutup' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                                $statusLabel = match($absensi->session_status) {
                                    'draft' => 'Draft',
                                    'buka' => 'Dibuka',
                                    'tutup' => 'Ditutup',
                                    default => 'Unknown',
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-600 text-sm font-medium mb-2">Pertemuan</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $absensi->pertemuan_ke }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-600 text-sm font-medium mb-2">Tanggal</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $absensi->tanggal->format('d M Y') }}</p>
                        </div>
                        <div class="text-center">
                            <p class="text-gray-600 text-sm font-medium mb-2">Waktu</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $absensi->jam_mulai }} - {{ $absensi->jam_selesai }}</p>
                        </div>
                    </div>

                    <!-- Session Details -->
                    @if($absensi->rangkuman || $absensi->berita_acara || $absensi->catatan)
                        <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if($absensi->rangkuman)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Ringkasan Materi</h3>
                                    <p class="text-sm text-gray-600">{{ $absensi->rangkuman }}</p>
                                </div>
                            @endif
                            @if($absensi->berita_acara)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Berita Acara</h3>
                                    <p class="text-sm text-gray-600">{{ $absensi->berita_acara }}</p>
                                </div>
                            @endif
                            @if($absensi->catatan)
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Catatan</h3>
                                    <p class="text-sm text-gray-600">{{ $absensi->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Mahasiswa</p>
                            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['total'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-blue-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646zM12 14H8m4 0h4m-4 0c-3.728 0-7-1.493-7-4.5" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Hadir</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['hadir'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-green-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Izin/Sakit</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['izin'] + $stats['sakit'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-yellow-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Alpha</p>
                            <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['alpha'] }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-red-200 flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance Progress -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Statistik Kehadiran</h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @php
                            $categories = [
                                ['label' => 'Hadir', 'count' => $stats['hadir'], 'color' => 'green', 'percentage' => $stats['total'] > 0 ? round($stats['hadir'] / $stats['total'] * 100) : 0],
                                ['label' => 'Izin/Sakit', 'count' => $stats['izin'] + $stats['sakit'], 'color' => 'yellow', 'percentage' => $stats['total'] > 0 ? round(($stats['izin'] + $stats['sakit']) / $stats['total'] * 100) : 0],
                                ['label' => 'Alpha', 'count' => $stats['alpha'], 'color' => 'red', 'percentage' => $stats['total'] > 0 ? round($stats['alpha'] / $stats['total'] * 100) : 0],
                            ];
                        @endphp
                        
                        @foreach($categories as $category)
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                        <span class="w-3 h-3 rounded-full bg-{{ $category['color'] }}-500"></span>
                                        {{ $category['label'] }}
                                    </span>
                                    <span class="text-sm font-bold text-gray-900">{{ $category['count'] }} ({{ $category['percentage'] }}%)</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-{{ $category['color'] }}-500 h-full rounded-full transition-all duration-500" 
                                        style="width: {{ $category['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Student Attendance Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Kehadiran Mahasiswa</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Mahasiswa</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">NIM</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Waktu Absensi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($kelas->mahasiswa as $index => $mahasiswa)
                                @php
                                    $attendance = $hadirMap[$mahasiswa->id] ?? null;
                                    $statusClass = match($attendance->status ?? 'alpha') {
                                        'hadir' => 'bg-green-100 text-green-800',
                                        'izin' => 'bg-blue-100 text-blue-800',
                                        'sakit' => 'bg-yellow-100 text-yellow-800',
                                        'alpha' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                    $statusLabel = match($attendance->status ?? 'alpha') {
                                        'hadir' => 'Hadir',
                                        'izin' => 'Izin',
                                        'sakit' => 'Sakit',
                                        'alpha' => 'Alpha',
                                        default => '-',
                                    };
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-semibold text-gray-900">{{ $mahasiswa->name }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $mahasiswa->nim ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $attendance && $attendance->waktu_absensi ? $attendance->waktu_absensi->format('H:i:s') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        {{ $attendance && $attendance->keterangan ? $attendance->keterangan : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        Tidak ada mahasiswa terdaftar di kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Aksi Cepat
                </h3>
                <div class="space-y-2">
                    @if($absensi->isDraft())
                        <form action="{{ route('dosen.absensi.buka', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium py-3 rounded-lg transition shadow-lg hover:shadow-xl">
                                Buka Sesi Presensi
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 text-center mt-1">Mahasiswa dapat melakukan presensi setelah sesi dibuka</p>
                    @elseif($absensi->isBuka())
                        <form action="{{ route('dosen.absensi.tutup', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-gradient-to-r from-yellow-600 to-amber-600 hover:from-yellow-700 hover:to-amber-700 text-white font-medium py-3 rounded-lg transition shadow-lg hover:shadow-xl">
                                Tutup Sesi Presensi
                            </button>
                        </form>
                        <p class="text-xs text-gray-500 text-center mt-1">Mahasiswa tidak dapat presensi setelah sesi ditutup</p>
                    @endif
                    
                    <a href="{{ route('dosen.absensi.attendance', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" 
                        class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium py-3 rounded-lg transition shadow-lg hover:shadow-xl text-center">
                        Edit Kehadiran Manual
                    </a>
                </div>
            </div>

            <!-- Session Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Timeline Sesi
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Dibuat</span>
                        <span class="font-semibold text-gray-900">{{ $absensi->created_at->format('d M H:i') }}</span>
                    </div>
                    @if($absensi->waktu_buka)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Dibuka</span>
                            <span class="font-semibold text-green-600">{{ $absensi->waktu_buka->format('d M H:i') }}</span>
                        </div>
                    @endif
                    @if($absensi->waktu_tutup)
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">Ditutup</span>
                            <span class="font-semibold text-red-600">{{ $absensi->waktu_tutup->format('d M H:i') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tips Card -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 mb-2">Tips Penggunaan</h4>
                        <ul class="text-xs text-blue-800 space-y-1">
                            <li>✓ Pastikan sesi dibuka agar mahasiswa dapat presensi</li>
                            <li>✓ Edit manual jika ada ketidaksempurnaan</li>
                            <li>✓ Tutup sesi setelah waktu selesai</li>
                            <li>✓ Periksa statistik untuk tracking kehadiran</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection