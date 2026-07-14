@extends('layouts.portal')

@section('title', 'Riwayat Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a href="{{ route('mahasiswa.absensi.index') }}" class="hover:text-blue-600 transition">Daftar Kelas</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-slate-800 font-semibold truncate">Riwayat Presensi</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="truncate">Riwayat Presensi</span>
            </h1>
            <p class="mt-2 text-slate-500 flex items-center gap-2 flex-wrap">
                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">{{ $kelas->kode_kelas }}</span>
                <span>{{ $kelas->mataKuliah->nama_mk }}</span>
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="hidden sm:inline">Presensi Sekarang</span>
            </a>
            <a href="{{ route('mahasiswa.absensi.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="hidden sm:inline">Kembali</span>
            </a>
        </div>
    </div>

    <!-- Main Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 text-xs font-bold uppercase tracking-wide mb-1">Total Pertemuan</p>
                    <p class="text-2xl sm:text-lg font-black text-blue-700">{{ $stats['totalPertemuan'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-blue-100 flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-emerald-50 rounded-2xl border border-emerald-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-600 text-xs font-bold uppercase tracking-wide mb-1">Hadir</p>
                    <p class="text-2xl sm:text-lg font-black text-emerald-700">{{ $stats['hadir'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-emerald-100 flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-amber-50 rounded-2xl border border-amber-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-amber-600 text-xs font-bold uppercase tracking-wide mb-1">Izin/Sakit</p>
                    <p class="text-2xl sm:text-lg font-black text-amber-700">{{ $stats['izin'] + $stats['sakit'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-amber-100 flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-red-50 rounded-2xl border border-red-100 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 text-xs font-bold uppercase tracking-wide mb-1">Alpha</p>
                    <p class="text-2xl sm:text-lg font-black text-red-700">{{ $stats['alpha'] }}</p>
                </div>
                <div class="w-11 h-11 rounded-lg bg-red-100 flex items-center justify-center shadow-sm">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Percentage Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-blue-50 px-4 sm:px-5 py-3 border-b border-blue-100 flex items-center gap-2.5">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800">Persentase Kehadiran</h3>
            </div>
            <div class="p-4 sm:p-5">
                <div class="space-y-4">
                    @php
                        $totalPresensi = $stats['hadir'] + $stats['izin'] + $stats['sakit'] + $stats['alpha'];
                        $hairPct = $totalPresensi > 0 ? round($stats['hadir'] / $stats['totalPertemuan'] * 100) : 0;
                        $izinPct = $totalPresensi > 0 ? round(($stats['izin'] + $stats['sakit']) / $stats['totalPertemuan'] * 100) : 0;
                        $alphaPct = $totalPresensi > 0 ? round($stats['alpha'] / $stats['totalPertemuan'] * 100) : 0;
                    @endphp

                    <!-- Hadir Progress -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                <span class="text-sm font-semibold text-slate-700">Hadir</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-base font-bold text-emerald-600">{{ $hairPct }}%</span>
                                <span class="text-xs font-medium text-slate-400">({{ $stats['hadir'] }}/{{ $stats['totalPertemuan'] }})</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full transition-all duration-700" style="width: {{ $hairPct }}%"></div>
                        </div>
                    </div>

                    <!-- Izin/Sakit Progress -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                <span class="text-sm font-semibold text-slate-700">Izin/Sakit</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-base font-bold text-amber-600">{{ $izinPct }}%</span>
                                <span class="text-xs font-medium text-slate-400">({{ $stats['izin'] + $stats['sakit'] }}/{{ $stats['totalPertemuan'] }})</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-amber-500 h-full rounded-full transition-all duration-700" style="width: {{ $izinPct }}%"></div>
                        </div>
                    </div>

                    <!-- Alpha Progress -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                                <span class="text-sm font-semibold text-slate-700">Alpha</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <span class="text-base font-bold text-red-600">{{ $alphaPct }}%</span>
                                <span class="text-xs font-medium text-slate-400">({{ $stats['alpha'] }}/{{ $stats['totalPertemuan'] }})</span>
                            </div>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-red-500 h-full rounded-full transition-all duration-700" style="width: {{ $alphaPct }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Info & Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-blue-50 px-4 sm:px-5 py-3 border-b border-blue-100 flex items-center gap-2.5">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-base font-bold text-slate-800">Informasi Kelas</h3>
            </div>
            <div class="p-4 sm:p-5">
                <div class="space-y-3">
                    <div>
                        <p class="text-slate-500 text-xs font-medium mb-1">Pengajar</p>
                        <p class="text-sm font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-7 h-7 rounded-full bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ substr($kelas->dosen->name, 0, 1) }}
                            </span>
                            <span class="truncate">{{ $kelas->dosen->name }}</span>
                        </p>
                    </div>
                    <hr class="border-slate-100">
                    <div>
                        <p class="text-slate-500 text-xs font-medium mb-1">Kode Kelas</p>
                        <p class="text-sm font-bold text-slate-800 font-mono bg-slate-100 px-2.5 py-1 rounded-lg inline-block">{{ $kelas->kode_kelas }}</p>
                    </div>
                    <hr class="border-slate-100">
                    <div>
                        <p class="text-slate-500 text-xs font-medium mb-1">Ruangan</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $kelas->ruangan ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 text-xs font-medium mb-1">Hari/Waktu</p>
                        <p class="text-sm font-semibold text-slate-800">{{ $kelas->hari ?? '—' }} • {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance History Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 sm:px-6 py-4 text-white flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h2 class="text-lg font-bold">Riwayat Detail Presensi</h2>
                    <p class="text-sky-100 text-sm mt-0.5">Daftar lengkap kehadiran per pertemuan</p>
                </div>
            </div>
        </div>

        @if($absensiList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Pertemuan</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu Absensi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($absensiList as $absensi)
                            @php
                                $attendance = $absensi->absensiMahasiswa->first();
                                $statusClass = match($attendance->status ?? 'alpha') {
                                    'hadir' => 'bg-emerald-100 text-emerald-800',
                                    'izin' => 'bg-blue-100 text-blue-800',
                                    'sakit' => 'bg-amber-100 text-amber-800',
                                    'alpha' => 'bg-red-100 text-red-800',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                                $statusDot = match($attendance->status ?? 'alpha') {
                                    'hadir' => 'bg-emerald-500',
                                    'izin' => 'bg-blue-500',
                                    'sakit' => 'bg-amber-500',
                                    default => 'bg-red-500',
                                };
                                $statusLabel = match($attendance->status ?? 'alpha') {
                                    'hadir' => 'Hadir',
                                    'izin' => 'Izin',
                                    'sakit' => 'Sakit',
                                    'alpha' => 'Alpha',
                                    default => '-',
                                };
                            @endphp
                            <tr class="hover:bg-blue-50/60 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-700 font-bold text-sm border border-blue-100">
                                        {{ $absensi->pertemuan_ke }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-800">{{ $absensi->tanggal->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-sm text-slate-500">{{ $absensi->jam_mulai }} - {{ $absensi->jam_selesai }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold {{ $statusClass }}">
                                        <span class="w-2 h-2 rounded-full {{ $statusDot }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($attendance && $attendance->waktu_absensi)
                                        <div class="flex items-center gap-2 text-slate-700 font-semibold">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {{ $attendance->waktu_absensi->format('H:i:s') }}
                                        </div>
                                    @else
                                        <span class="text-slate-300 font-medium">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-200 flex justify-center bg-slate-50">
                {{ $absensiList->links() }}
            </div>
        @else
            <div class="text-center py-10">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-slate-500 font-medium text-lg">Belum ada data presensi untuk kelas ini.</p>
                <p class="text-slate-400 text-sm mt-1">Riwayat presensi akan muncul setelah dosen membuat sesi</p>
            </div>
        @endif
    </div>

    <!-- Legend -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-100 shadow-sm hover:shadow-md transition">
            <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 shadow-sm"></span>
            <span class="text-sm font-semibold text-emerald-700">Hadir</span>
        </div>
        <div class="flex items-center gap-3 p-4 rounded-xl bg-blue-50 border border-blue-100 shadow-sm hover:shadow-md transition">
            <span class="w-3.5 h-3.5 rounded-full bg-blue-500 shadow-sm"></span>
            <span class="text-sm font-semibold text-blue-700">Izin</span>
        </div>
        <div class="flex items-center gap-3 p-4 rounded-xl bg-amber-50 border border-amber-100 shadow-sm hover:shadow-md transition">
            <span class="w-3.5 h-3.5 rounded-full bg-amber-500 shadow-sm"></span>
            <span class="text-sm font-semibold text-amber-700">Sakit</span>
        </div>
        <div class="flex items-center gap-3 p-4 rounded-xl bg-red-50 border border-red-100 shadow-sm hover:shadow-md transition">
            <span class="w-3.5 h-3.5 rounded-full bg-red-500 shadow-sm"></span>
            <span class="text-sm font-semibold text-red-700">Alpha</span>
        </div>
    </div>
</div>
@endsection