@extends('layouts.portal')

@section('title', 'Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')

{{-- HERO HEADER BANNER --}}
<div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-[#260c5a] via-[#3a1480] to-[#511da8] dark:from-slate-900 dark:via-indigo-950 dark:to-purple-950 px-6 py-7 sm:px-8 shadow-md relative text-white">
    <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
    <div class="pointer-events-none absolute -left-10 -bottom-10 h-36 w-36 rounded-full bg-purple-400/10 blur-xl"></div>
    
    <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0 flex-1">
            <div class="mb-3 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/20 bg-white/15 px-3 py-1 text-xs font-bold tracking-wide text-white backdrop-blur-sm shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 opacity-90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    {{ $kelas->mataKuliah?->kode_mk ?? '-' }}
                </span>
                <span class="rounded-lg border border-white/10 bg-black/20 px-2.5 py-1 text-xs font-medium text-purple-100 backdrop-blur-sm">
                    {{ $kelas->semester?->nama_semester ?? 'Semester Aktif' }}
                </span>
                <span class="rounded-lg border border-white/10 bg-black/20 px-2.5 py-1 text-xs font-medium text-purple-100 backdrop-blur-sm">
                    {{ $kelas->mataKuliah?->programStudi?->nama_prodi ?? 'Program Studi' }}
                </span>
            </div>

            <h1 class="text-2xl font-black text-white sm:text-3xl tracking-tight leading-tight">
                {{ $kelas->mataKuliah?->nama_mk ?? 'Detail Kelas' }}
            </h1>

            <div class="mt-3 flex flex-wrap items-center gap-y-2 gap-x-5 text-xs sm:text-sm text-purple-100/90 font-medium">
                <span class="inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $kelas->hari }}, {{ substr($kelas->jam_mulai,0,5) }} – {{ substr($kelas->jam_selesai,0,5) }} WIB
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    Ruang: {{ $kelas->ruangan ?? '-' }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    {{ $kelas->mataKuliah?->sks ?? 0 }} SKS
                </span>
            </div>
        </div>

        {{-- Right Side / Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 shrink-0">
            <div class="flex flex-col items-center justify-center rounded-xl bg-white/10 px-5 py-3 border border-white/20 backdrop-blur-sm">
                <span class="text-[10px] font-bold text-purple-200 uppercase tracking-wider mb-0.5">Mahasiswa</span>
                <span class="text-xl font-black text-white leading-none">{{ $kelas->mahasiswa->count() }}</span>
            </div>
        </div>
    </div>
</div>

{{-- TABS --}}
@php
    $tabLinks = [
        'Beranda'      => ['url' => route('dosen.kelas-detail', $kelas->id), 'active' => request()->routeIs('dosen.kelas-detail')],
        'Absensi'      => ['url' => route('dosen.absensi.index', $kelas->id),  'active' => request()->routeIs('dosen.absensi.*')],
        'Materi'       => ['url' => route('dosen.kelas-materi', $kelas->id), 'active' => request()->routeIs('dosen.kelas-materi')],
        'Tugas'        => ['url' => route('dosen.kelas-tugas', $kelas->id),  'active' => request()->routeIs('dosen.kelas-tugas')],
        'Forum'        => ['url' => route('dosen.kelas-forum', $kelas->id),  'active' => request()->routeIs('dosen.kelas-forum')],
        'Rekap Tugas'  => ['url' => route('dosen.kelas-tugas.rekap', $kelas->id), 'active' => request()->routeIs('dosen.kelas-tugas.rekap')],
    ];
@endphp

<div class="mb-4 flex items-center gap-1 overflow-x-auto rounded-xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-1 shadow-sm transition-colors duration-200">
    @foreach ($tabLinks as $label => $tab)
        <a href="{{ $tab['url'] }}" class="whitespace-nowrap rounded-lg px-3 py-1.5 text-xs font-bold transition
            {{ $tab['active']
                ? 'bg-[#321270] dark:bg-purple-650 text-white shadow-sm shadow-purple-900/20'
                : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="space-y-4 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-200/60 dark:border-slate-700/60">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2.5">
                <div class="p-1.5 bg-[#321270]/5 dark:bg-purple-500/10 text-[#321270] dark:text-purple-400 rounded-lg border border-[#321270]/10 dark:border-purple-500/20 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Presensi Manajemen Kelas</span>
            </h1>
            <p class="mt-1.5 text-xs text-slate-500 flex items-center flex-wrap gap-2">
                <span class="font-semibold text-[#321270] bg-[#321270]/5 px-2 py-0.5 rounded-md border border-[#321270]/10 text-xs">{{ $kelas->mataKuliah->nama_mk }}</span>
                <span class="text-slate-400">•</span>
                <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-mono text-[11px] font-bold border border-slate-200">{{ $kelas->kode_kelas }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            @if($absensiList->count() > 0)
            <a href="{{ route('dosen.absensi.export-all', $kelas->id) }}"
               class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-lg font-bold text-xs shadow-sm transition-all duration-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Export Semua (CSV)</span>
            </a>
            @endif
            <a href="{{ route('dosen.absensi.create', $kelas->id) }}"
               class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 bg-gradient-to-r from-[#321270] to-[#4a1fa8] hover:brightness-110 text-white rounded-lg font-bold text-xs shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.01]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buat Sesi Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-emerald-50 border border-emerald-200 rounded-lg p-3 flex items-start gap-2.5 shadow-sm">
            <div class="p-1 bg-emerald-100 text-emerald-700 rounded-md shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-xs font-bold text-emerald-900">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-start gap-2.5 shadow-sm">
            <div class="p-1 bg-amber-100 text-amber-700 rounded-md shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-xs font-bold text-amber-900">{{ session('warning') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-rose-50 border border-rose-200 rounded-lg p-3 flex items-start gap-2.5 shadow-sm">
            <div class="p-1 bg-rose-100 text-rose-700 rounded-md shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-xs font-bold text-rose-900">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200/70 dark:border-slate-700 shadow-sm p-3 flex items-center justify-between hover:border-[#321270]/40 transition-all duration-300">
            <div>
                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Total Seluruh Sesi</p>
                <p class="text-xl font-black text-slate-700 dark:text-white mt-0.5">{{ $statistics['total_sesi'] }}</p>
            </div>
            <div class="p-2 bg-[#321270]/5 dark:bg-purple-900/30 text-[#321270] dark:text-purple-400 rounded-lg border border-[#321270]/10 dark:border-purple-500/20 shadow-inner">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200/70 dark:border-slate-700 shadow-sm p-3 flex items-center justify-between hover:border-amber-300 transition-all duration-300">
            <div>
                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sesi Konsep (Draft)</p>
                <p class="text-xl font-black text-amber-600 dark:text-amber-400 mt-0.5">{{ $statistics['sesi_draft'] }}</p>
            </div>
            <div class="p-2 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg border border-amber-100 dark:border-amber-700/50 shadow-inner">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200/70 dark:border-slate-700 shadow-sm p-3 flex items-center justify-between hover:border-emerald-300 transition-all duration-300">
            <div>
                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sesi Aktif (Terbuka)</p>
                <p class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-0.5">{{ $statistics['sesi_buka'] }}</p>
            </div>
            <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg border border-emerald-100 dark:border-emerald-700/50 shadow-inner">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-lg border border-slate-200/70 dark:border-slate-700 shadow-sm p-3 flex items-center justify-between hover:border-rose-300 transition-all duration-300">
            <div>
                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Sesi Selesai (Tutup)</p>
                <p class="text-xl font-black text-rose-600 dark:text-rose-400 mt-0.5">{{ $statistics['sesi_tutup'] }}</p>
            </div>
            <div class="p-2 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-lg border border-rose-100 dark:border-rose-700/50 shadow-inner">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden transition-colors duration-200">
        <div class="bg-gradient-to-r from-slate-50 to-[#321270]/5 dark:from-slate-800 dark:to-purple-900/20 px-4 py-2.5 border-b border-slate-100 dark:border-slate-700 flex items-center gap-2">
            <span class="w-1.5 h-3 bg-[#321270] dark:bg-purple-500 rounded-full"></span>
            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Daftar Log Rekapitulasi Presensi</h2>
        </div>

        @if($absensiList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200/80 dark:border-slate-700">
                            <th class="px-5 py-3 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-[6%]">No</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-[16%]">Pertemuan</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-[16%]">Tanggal</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-[16%]">Alokasi Waktu</th>
                            <th class="px-5 py-3 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-[14%]">Status Akses</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-[20%]">Rasio Kehadiran</th>
                            <th class="px-5 py-3 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap w-[12%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800">
                        @foreach($absensiList as $absensi)
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/50 transition duration-150 group">
                                <td class="px-5 py-3 text-center font-mono font-bold text-slate-400 text-xs">
                                    {{ ($absensiList->currentPage() - 1) * $absensiList->perPage() + $loop->iteration }}
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[#321270]/5 border border-[#321270]/10 text-[#321270] font-bold text-[11px] group-hover:bg-[#321270] group-hover:text-white group-hover:border-transparent transition-all duration-300">
                                        Pertemuan {{ $absensi->pertemuan_ke }}
                                    </span>
                                </td>

                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 text-slate-700 font-semibold text-xs">
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-[#321270] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $absensi->tanggal->format('d M Y') }}
                                    </div>
                                </td>

                                <td class="px-5 py-3 whitespace-nowrap text-slate-600 font-medium text-xs">
                                    @if($absensi->jam_mulai && $absensi->jam_selesai)
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ substr($absensi->jam_mulai, 0, 5) }} - {{ substr($absensi->jam_selesai, 0, 5) }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">—</span>
                                    @endif
                                </td>

                                <td class="px-5 py-3 whitespace-nowrap text-center">
                                    <span @class([
                                        'inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-bold border shadow-sm',
                                        'bg-amber-50 text-amber-700 border-amber-200' => $absensi->isDraft(),
                                        'bg-emerald-50 text-emerald-700 border-emerald-200' => $absensi->isBuka(),
                                        'bg-rose-50 text-rose-700 border-rose-200' => $absensi->isTutup(),
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
                                </td>

                                <td class="px-5 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex gap-1">
                                            <div class="w-5 h-5 rounded-md bg-emerald-500 flex items-center justify-center text-white text-[9px] font-black border border-white shadow-sm" title="Hadir">
                                                {{ $absensi->hadir_count }}
                                            </div>
                                            <div class="w-5 h-5 rounded-md bg-slate-300 flex items-center justify-center text-slate-700 text-[9px] font-black border border-white shadow-sm" title="Belum Presensi/Absen">
                                                {{ max(0, $kelas->mahasiswa->count() - $absensi->hadir_count) }}
                                            </div>
                                        </div>
                                        <span class="text-slate-700 dark:text-slate-200 text-xs font-bold bg-slate-50 dark:bg-slate-900/50 px-1.5 py-0.5 border border-slate-200 dark:border-slate-700 rounded-md">
                                            {{ $absensi->hadir_count }}<span class="text-slate-400 font-normal">/</span>{{ $kelas->mahasiswa->count() }} <span class="text-[10px] text-slate-400 font-medium">Mhs</span>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-3 whitespace-nowrap text-center">
                                    <div class="flex justify-center relative" x-data="{ open: false }" :class="open ? 'z-50' : 'z-0'">
                                        <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-[11px] font-bold shadow-sm transition-all focus:outline-none" :class="{ 'ring-2 ring-[#321270]/20 bg-slate-50': open }">
                                            <span>Aksi</span>
                                            <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100" 
                                             x-transition:enter-start="transform opacity-0 scale-95" 
                                             x-transition:enter-end="transform opacity-100 scale-100" 
                                             x-transition:leave="transition ease-in duration-75" 
                                             x-transition:leave-start="transform opacity-100 scale-100" 
                                             x-transition:leave-end="transform opacity-0 scale-95" 
                                             class="absolute right-0 top-full mt-1.5 w-44 origin-top-right rounded-xl bg-white shadow-xl ring-1 ring-slate-900/5 focus:outline-none overflow-hidden text-left" 
                                             style="display: none;">
                                            
                                            <div class="py-1.5">
                                                <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" class="group flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-[#321270]/5 hover:text-[#321270] transition-colors">
                                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-[#321270]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat Detail
                                                </a>
                                                
                                                @if($absensi->isDraft())
                                                    <form action="{{ route('dosen.absensi.buka', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST" class="block w-full">
                                                        @csrf
                                                        <button type="submit" class="group flex w-full items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg>
                                                            Buka Sesi
                                                        </button>
                                                    </form>
                                                @elseif($absensi->isBuka())
                                                    <form action="{{ route('dosen.absensi.tutup', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST" class="block w-full">
                                                        @csrf
                                                        <button type="submit" class="group flex w-full items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Tutup Sesi
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <a href="{{ route('dosen.absensi.edit', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" class="group flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit Presensi
                                                </a>

                                                <a href="{{ route('dosen.absensi.export', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" class="group flex items-center gap-2.5 px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                                    <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Export CSV
                                                </a>
                                                
                                                <div class="border-t border-slate-100 my-1.5"></div>
                                                
                                                <form action="{{ route('dosen.absensi.destroy', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip sesi presensi beserta rekap riwayat pertemuan ini?');" class="block w-full">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="group flex w-full items-center gap-2.5 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                                        <svg class="w-4 h-4 text-rose-500 group-hover:text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus Sesi
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Footer: Show + Info + Pagination --}}
            <div class="flex items-center justify-between flex-wrap gap-3 px-5 py-3 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 rounded-b-xl">
                {{-- Kiri: Show + Keterangan --}}
                <div class="flex items-center gap-3 flex-wrap">
                    <form method="GET" action="{{ route('dosen.absensi.index', $kelas->id) }}" class="flex items-center gap-2">
                        @foreach(request()->except(['per_page', 'page']) as $k => $v)
                            @if(is_array($v))
                                @foreach($v as $item)
                                    <input type="hidden" name="{{ $k }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endif
                        @endforeach
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider whitespace-nowrap">Show:</span>
                        <select name="per_page" onchange="this.form.submit()"
                            class="w-[70px] h-8 px-2 text-sm font-semibold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#321270]/20 dark:focus:ring-purple-500/30">
                            <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
                            <option value="25" @selected(($perPage ?? 10) == 25)>25</option>
                            <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
                            <option value="100" @selected(($perPage ?? 10) == 100)>100</option>
                        </select>
                    </form>
                    <span class="text-[0.78rem] text-slate-400 whitespace-nowrap">
                        Menampilkan {{ $absensiList->firstItem() ?? 0 }}&ndash;{{ $absensiList->lastItem() ?? 0 }} dari {{ $absensiList->total() }} sesi
                    </span>
                </div>

                {{-- Kanan: Pagination --}}
                @if($absensiList->hasPages())
                    <div class="flex items-center">
                        {{ $absensiList->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

        @else
            <div class="text-center py-10 px-4 bg-white dark:bg-slate-800 rounded-b-xl">
                <div class="w-12 h-12 bg-[#321270]/5 dark:bg-purple-900/20 text-[#321270] dark:text-purple-400 rounded-xl border border-[#321270]/10 dark:border-purple-500/20 shadow-sm flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700 dark:text-slate-200">Belum Ada Sesi Presensi Terjadwal</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Seluruh modul log absensi pertemuan mata kuliah ini masih bernilai kosong.</p>
                <div class="mt-4">
                    <a href="{{ route('dosen.absensi.create', $kelas->id) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#321270] to-[#4a1fa8] hover:brightness-110 text-white rounded-lg font-bold text-xs shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.01]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buat Sesi Pertemuan Pertama</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection