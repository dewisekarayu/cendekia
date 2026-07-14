@extends('layouts.portal')

@section('title', 'Detail Sesi Presensi - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/60 dark:border-slate-700/60">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">
                <a href="{{ route('dosen.kelas-saya') }}" class="hover:text-blue-600 dark:hover:text-purple-400 transition duration-200">Kelas Saya</a>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="hover:text-blue-600 dark:hover:text-purple-400 transition duration-200">Presensi</a>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-slate-800 dark:text-slate-200 font-bold">Pertemuan {{ $absensi->pertemuan_ke }}</span>
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 dark:text-white flex items-center gap-3 mt-2">
                <div class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-purple-950/40 text-blue-600 dark:text-purple-400 border border-blue-100 dark:border-purple-900/30 shadow-sm flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Detail & Rekap Presensi</span>
            </h1>
        </div>
        <div class="flex items-center">
            <a href="{{ route('dosen.absensi.index', $kelas->id) }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 rounded-xl font-semibold text-sm shadow-sm hover:shadow transition-all duration-300">
                <svg class="w-4 h-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <!-- Alert / Flash Messages -->
    @if(session('success'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/30 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="p-1 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 rounded-md flex-shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-bold text-emerald-900 dark:text-emerald-300">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/30 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="p-1 bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300 rounded-md flex-shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-bold text-amber-900 dark:text-amber-300">{{ session('warning') }}</p>
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/80 dark:border-slate-700 p-6 shadow-sm transition-colors duration-200">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nomor Pertemuan</p>
                <p class="text-xl font-black text-slate-700 dark:text-white">Pertemuan {{ $absensi->pertemuan_ke }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Tanggal Kegiatan</p>
                <p class="text-xl font-black text-slate-700 dark:text-white">{{ $absensi->tanggal->format('d M Y') }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Alokasi Waktu Sesi</p>
                <p class="text-base font-bold text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 px-2.5 py-1 rounded-lg inline-block font-mono">
                    {{ substr($absensi->jam_mulai, 0, 5) }} - {{ substr($absensi->jam_selesai, 0, 5) }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Status Akses Sesi</p>
                <div>
                    <span @class([
                        'inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border shadow-sm mt-0.5',
                        'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-900/30' => $absensi->isDraft(),
                        'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-900/30' => $absensi->isBuka(),
                        'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-900/30' => $absensi->isTutup(),
                    ])>
                        @if($absensi->isDraft())
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        @elseif($absensi->isBuka())
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        @else
                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        @endif
                        {{ $absensi->getStatusLabel() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/70 dark:border-slate-700 shadow-sm p-4 flex items-center justify-between hover:border-emerald-300 dark:hover:border-emerald-800 hover:scale-[1.02] transition-all duration-300">
            <div class="space-y-0.5">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Hadir</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-slate-700 dark:text-white">{{ $stats['hadir'] }}</p>
                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-1.5 py-0.5 rounded border border-emerald-100 dark:border-emerald-900/20">
                        {{ $stats['total'] > 0 ? round(($stats['hadir']/$stats['total'])*100) : 0 }}%
                    </span>
                </div>
            </div>
            <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 ring-4 ring-emerald-100 dark:ring-emerald-950/50 flex-shrink-0"></span>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/70 dark:border-slate-700 shadow-sm p-4 flex items-center justify-between hover:border-sky-300 dark:hover:border-sky-800 hover:scale-[1.02] transition-all duration-300">
            <div class="space-y-0.5">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Izin</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-slate-700 dark:text-white">{{ $stats['izin'] }}</p>
                    <span class="text-xs font-bold text-sky-600 dark:text-sky-400 bg-sky-50 dark:bg-sky-950/30 px-1.5 py-0.5 rounded border border-sky-100 dark:border-sky-900/20">
                        {{ $stats['total'] > 0 ? round(($stats['izin']/$stats['total'])*100) : 0 }}%
                    </span>
                </div>
            </div>
            <span class="w-3.5 h-3.5 rounded-full bg-sky-500 ring-4 ring-sky-100 dark:ring-sky-950/50 flex-shrink-0"></span>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/70 dark:border-slate-700 shadow-sm p-4 flex items-center justify-between hover:border-amber-300 dark:hover:border-amber-800 hover:scale-[1.02] transition-all duration-300">
            <div class="space-y-0.5">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sakit</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-slate-700 dark:text-white">{{ $stats['sakit'] }}</p>
                    <span class="text-xs font-bold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/30 px-1.5 py-0.5 rounded border border-amber-100 dark:border-amber-900/20">
                        {{ $stats['total'] > 0 ? round(($stats['sakit']/$stats['total'])*100) : 0 }}%
                    </span>
                </div>
            </div>
            <span class="w-3.5 h-3.5 rounded-full bg-amber-500 ring-4 ring-amber-100 dark:ring-amber-950/50 flex-shrink-0"></span>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200/70 dark:border-slate-700 shadow-sm p-4 flex items-center justify-between hover:border-rose-300 dark:hover:border-rose-800 hover:scale-[1.02] transition-all duration-300">
            <div class="space-y-0.5">
                <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Alpha</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-2xl font-black text-slate-700 dark:text-white">{{ $stats['alpha'] }}</p>
                    <span class="text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/30 px-1.5 py-0.5 rounded border border-rose-100 dark:border-rose-900/20">
                        {{ $stats['total'] > 0 ? round(($stats['alpha']/$stats['total'])*100) : 0 }}%
                    </span>
                </div>
            </div>
            <span class="w-3.5 h-3.5 rounded-full bg-rose-500 ring-4 ring-rose-100 dark:ring-rose-950/50 flex-shrink-0"></span>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="flex flex-wrap items-center justify-start gap-2.5 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200/80 dark:border-slate-700 shadow-sm animate-in fade-in-50 duration-200">
        @if($absensi->isDraft())
            <form action="{{ route('dosen.absensi.buka', [$kelas->id, $absensi->id]) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 dark:bg-emerald-950/30 hover:bg-emerald-600 text-emerald-700 dark:text-emerald-400 hover:text-white border border-emerald-250 dark:border-emerald-900/40 rounded-xl text-xs font-bold shadow-sm transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Buka Akses Sesi</span>
                </button>
            </form>
        @endif

        @if($absensi->isBuka())
            <form action="{{ route('dosen.absensi.tutup', [$kelas->id, $absensi->id]) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-50 dark:bg-amber-950/30 hover:bg-amber-600 text-amber-800 dark:text-amber-400 hover:text-white border border-amber-250 dark:border-amber-900/40 rounded-xl text-xs font-bold shadow-sm transition-all duration-200" 
                        onclick="return confirm('Apakah Anda yakin ingin menutup akses gerbang presensi mandiri mahasiswa pada sesi ini?')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Tutup Akses Sesi</span>
                </button>
            </form>
        @endif

        <a href="{{ route('dosen.absensi.edit', [$kelas->id, $absensi->id]) }}" 
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-slate-700 hover:bg-slate-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 border border-slate-250 dark:border-slate-650 rounded-xl text-xs font-bold shadow-sm transition-all duration-200">
            <svg class="w-4 h-4 text-slate-400 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>Edit Detail Sesi</span>
        </a>

        <div class="flex-1 text-right">
            <form action="{{ route('dosen.absensi.destroy', [$kelas->id, $absensi->id]) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 dark:bg-rose-950/30 hover:bg-rose-600 text-rose-600 dark:text-rose-450 hover:text-white border border-rose-100 dark:border-rose-900/40 rounded-xl text-xs font-bold shadow-sm transition-all duration-200" 
                        onclick="return confirm('Apakah Anda yakin ingin menghapus arsip sesi presensi beserta rekap riwayat pertemuan ini? Data tidak dapat dikembalikan.')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus Sesi</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Attendance Form & Table -->
    <form action="{{ route('dosen.absensi.updateAttendance', [$kelas->id, $absensi->id]) }}" method="POST" class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden transition-colors duration-200">
        @csrf
        @method('PUT')

        <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 dark:from-slate-700/50 dark:to-slate-700/30 px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="w-2 h-4 bg-purple-500 rounded-full"></span>
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Rekap & Koreksi Kehadiran Mahasiswa</h2>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500 dark:text-slate-400">Petunjuk: H = Hadir, S = Sakit, I = Izin, A = Alpha</span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200/80 dark:border-slate-700">
                        <th class="px-5 py-3.5 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-16">No</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-36">NIM</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[200px]">Nama Lengkap</th>
                        <th class="px-6 py-3.5 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider w-48">Status Kehadiran</th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider min-w-[240px]">Catatan / Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                    @forelse($kelas->mahasiswa as $index => $mahasiswa)
                        @php
                            $attendance = $hadirMap[$mahasiswa->id] ?? null;
                            $currentStatus = $attendance?->status ?? 'alpha';
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition duration-150 group">
                            <td class="px-5 py-4 text-center text-sm font-bold text-slate-400 dark:text-slate-500 group-hover:text-slate-700 dark:group-hover:text-slate-300 transition">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-500 dark:text-slate-400 tracking-medium">
                                {{ $mahasiswa->nim ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-purple-950/40 border border-blue-100 dark:border-purple-900/30 flex items-center justify-center text-xs font-bold text-blue-600 dark:text-purple-400 group-hover:bg-blue-600 dark:group-hover:bg-purple-600 group-hover:text-white dark:group-hover:text-white transition-all duration-300">
                                        {{ strtoupper(substr($mahasiswa->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-700 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-purple-400 transition">
                                            {{ $mahasiswa->name }}
                                        </div>
                                        @if($attendance?->waktu_absensi)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 border border-purple-100/50 dark:border-purple-900/30 mt-0.5">
                                                Absen Mandiri ({{ $attendance->waktu_absensi->format('H:i') }})
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-1">
                                    <!-- Hadir -->
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance[{{ $mahasiswa->id }}]" value="hadir" {{ $currentStatus === 'hadir' ? 'checked' : '' }} class="sr-only peer">
                                        <div class="px-3 py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 dark:peer-checked:bg-emerald-600 dark:peer-checked:border-emerald-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                            H
                                        </div>
                                    </label>
                                    <!-- Sakit -->
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance[{{ $mahasiswa->id }}]" value="sakit" {{ $currentStatus === 'sakit' ? 'checked' : '' }} class="sr-only peer">
                                        <div class="px-3 py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 dark:peer-checked:bg-amber-600 dark:peer-checked:border-amber-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                            S
                                        </div>
                                    </label>
                                    <!-- Izin -->
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance[{{ $mahasiswa->id }}]" value="izin" {{ $currentStatus === 'izin' ? 'checked' : '' }} class="sr-only peer">
                                        <div class="px-3 py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 peer-checked:bg-sky-500 peer-checked:text-white peer-checked:border-sky-500 dark:peer-checked:bg-sky-600 dark:peer-checked:border-sky-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                            I
                                        </div>
                                    </label>
                                    <!-- Alpha -->
                                    <label class="cursor-pointer">
                                        <input type="radio" name="attendance[{{ $mahasiswa->id }}]" value="alpha" {{ $currentStatus === 'alpha' ? 'checked' : '' }} class="sr-only peer">
                                        <div class="px-3 py-1.5 text-xs font-bold rounded-lg border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 peer-checked:bg-rose-500 peer-checked:text-white peer-checked:border-rose-500 dark:peer-checked:bg-rose-600 dark:peer-checked:border-rose-600 hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                            A
                                        </div>
                                    </label>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="text" name="keterangan[{{ $mahasiswa->id }}]" value="{{ $attendance?->keterangan ?? '' }}" placeholder="Alasan/catatan..." class="w-full max-w-xs px-3 py-1.5 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:outline-none focus:border-purple-500 dark:focus:border-purple-400 focus:ring-1 focus:ring-purple-500 dark:focus:ring-purple-400">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 font-medium">
                                <div class="w-12 h-12 bg-slate-50 dark:bg-slate-900/50 text-slate-400 dark:text-slate-500 rounded-xl border border-slate-200/60 dark:border-slate-700 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span>Tidak ada data mahasiswa terdaftar di dalam kelas ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/40 border-t border-slate-100 dark:border-slate-700 flex items-center justify-end gap-3 transition-colors">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm shadow-md transition-all duration-300">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                </svg>
                <span>Simpan Rekap Presensi</span>
            </button>
        </div>
    </form>
</div>
@endsection