@extends('layouts.portal')

@section('title', 'Riwayat Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Presensi
            </h1>
            <p class="mt-1 text-gray-500">
                {{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span>
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Presensi Sekarang
            </a>
            <a href="{{ route('mahasiswa.absensi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Pertemuan</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['totalPertemuan'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-200 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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

    <!-- Progress Bars -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Persentase Kehadiran</h3>
            <div class="space-y-4">
                @php
                    $totalPresensi = $stats['hadir'] + $stats['izin'] + $stats['sakit'] + $stats['alpha'];
                    $hairPct = $totalPresensi > 0 ? round($stats['hadir'] / $stats['totalPertemuan'] * 100) : 0;
                    $izinPct = $totalPresensi > 0 ? round(($stats['izin'] + $stats['sakit']) / $stats['totalPertemuan'] * 100) : 0;
                    $alphaPct = $totalPresensi > 0 ? round($stats['alpha'] / $stats['totalPertemuan'] * 100) : 0;
                @endphp

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            Hadir
                        </span>
                        <span class="text-sm font-bold text-green-600">{{ $hairPct }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-green-500 h-full rounded-full transition-all duration-500" style="width: {{ $hairPct }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                            Izin/Sakit
                        </span>
                        <span class="text-sm font-bold text-yellow-600">{{ $izinPct }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-yellow-500 h-full rounded-full transition-all duration-500" style="width: {{ $izinPct }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            Alpha
                        </span>
                        <span class="text-sm font-bold text-red-600">{{ $alphaPct }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-red-500 h-full rounded-full transition-all duration-500" style="width: {{ $alphaPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kelas</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Pengajar</span>
                    <span class="font-semibold text-gray-900">{{ $kelas->dosen->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Kode Kelas</span>
                    <span class="font-semibold text-gray-900">{{ $kelas->kode_kelas }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ruangan</span>
                    <span class="font-semibold text-gray-900">{{ $kelas->ruangan ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Hari</span>
                    <span class="font-semibold text-gray-900">{{ $kelas->hari ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Waktu</span>
                    <span class="font-semibold text-gray-900">{{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Riwayat Detail Presensi</h2>
        </div>

        @if($absensiList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Pertemuan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Waktu Absensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($absensiList as $absensi)
                            @php
                                $attendance = $absensi->absensiMahasiswa->first();
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
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">Pertemuan {{ $absensi->pertemuan_ke }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $absensi->tanggal->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $absensi->jam_mulai }} - {{ $absensi->jam_selesai }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $attendance && $attendance->waktu_absensi ? $attendance->waktu_absensi->format('H:i:s') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-100 flex justify-center">
                {{ $absensiList->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-500 font-medium">Belum ada data presensi untuk kelas ini.</p>
            </div>
        @endif
    </div>

    <!-- Legend -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="flex items-center gap-2 p-3 rounded-lg bg-green-50 border border-green-200">
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            <span class="text-sm font-medium text-green-700">Hadir</span>
        </div>
        <div class="flex items-center gap-2 p-3 rounded-lg bg-blue-50 border border-blue-200">
            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
            <span class="text-sm font-medium text-blue-700">Izin</span>
        </div>
        <div class="flex items-center gap-2 p-3 rounded-lg bg-yellow-50 border border-yellow-200">
            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
            <span class="text-sm font-medium text-yellow-700">Sakit</span>
        </div>
        <div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 border border-red-200">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            <span class="text-sm font-medium text-red-700">Alpha</span>
        </div>
    </div>
</div>
@endsection