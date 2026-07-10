@extends('layouts.portal')

@section('title', 'Detail Presensi - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Detail Presensi
            </h1>
            <p class="mt-1 text-gray-500">
                {{ $absensi->kelasPerkuliahan->mataKuliah->nama_mk }} • {{ $absensi->kelasPerkuliahan->kode_kelas }} • Pertemuan {{ $absensi->pertemuan_ke }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.absensi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('admin.absensi.edit', $absensi->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
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
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">{{ $absensi->getStatusLabel() }}</span>
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
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-4">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Total Mahasiswa</p>
                            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['total'] }}</p>
                        </div>
                        <svg class="w-8 h-8 text-blue-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646zM12 14H8m4 0h4m-4 0c-3.728 0-7-1.493-7-4.5" />
                        </svg>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 p-4">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Hadir</p>
                            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['hadir'] }}</p>
                        </div>
                        <svg class="w-8 h-8 text-green-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200 p-4">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Izin/Sakit</p>
                            <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['izin'] + $stats['sakit'] }}</p>
                        </div>
                        <svg class="w-8 h-8 text-yellow-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z" />
                        </svg>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-4">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Alpha</p>
                            <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['alpha'] }}</p>
                        </div>
                        <svg class="w-8 h-8 text-red-400 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Student Attendance Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-blue-600 px-6 py-4 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646zM12 14H8m4 0h4m-4 0c-3.728 0-7-1.493-7-4.5" />
                        </svg>
                        <h2 class="text-lg font-semibold">Daftar Kehadiran Mahasiswa</h2>
                    </div>
                    <span class="px-3 py-1 rounded-full bg-white bg-opacity-20 text-sm font-semibold">{{ $absensi->kelasPerkuliahan->mahasiswa->count() }} Mahasiswa</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Nama Mahasiswa</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">NIM</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Waktu Absensi</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($absensi->kelasPerkuliahan->mahasiswa as $index => $mahasiswa)
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
                                <tr class="hover:bg-gradient-to-r hover:from-purple-50 hover:to-blue-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 text-purple-700 font-bold text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                                {{ substr($mahasiswa->name, 0, 1) }}
                                            </div>
                                            <span class="font-semibold text-gray-900">{{ $mahasiswa->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $mahasiswa->nim ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $statusClass }}">
                                            @if($attendance?->status === 'hadir')
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            @elseif($attendance?->status === 'izin')
                                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                            @elseif($attendance?->status === 'sakit')
                                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                            @endif
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if($attendance && $attendance->waktu_absensi)
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $attendance->waktu_absensi->format('H:i:s') }}
                                            </div>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        @if($attendance && $attendance->keterangan)
                                            <span class="inline-block truncate" title="{{ $attendance->keterangan }}">{{ $attendance->keterangan }}</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646zM12 14H8m4 0h4m-4 0c-3.728 0-7-1.493-7-4.5" />
                                        </svg>
                                        <p class="text-gray-500 font-medium">Tidak ada mahasiswa terdaftar di kelas ini.</p>
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
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Info Kelas
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Mata Kuliah</p>
                        <p class="font-semibold text-gray-900">{{ $absensi->kelasPerkuliahan->mataKuliah->nama_mk }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Kode Kelas</p>
                        <p class="font-semibold text-gray-900">{{ $absensi->kelasPerkuliahan->kode_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Dosen Pengampu</p>
                        <p class="font-semibold text-gray-900">{{ $absensi->kelasPerkuliahan->dosen->name }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Aksi Cepat
                </h3>
                <a href="{{ route('admin.absensi.attendance', $absensi->id) }}" 
                    class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium py-3 rounded-lg transition shadow-lg hover:shadow-xl text-center text-sm">
                    Edit Kehadiran Manual
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
