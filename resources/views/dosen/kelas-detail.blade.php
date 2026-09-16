@extends('layouts.portal')
@section('title', ($kelas->mataKuliah?->nama_mk ?? 'Detail Kelas') . ' - Dosen')
@section('activeMenu', 'Kelas Saya')

@section('content')

<x-flash-message />

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
                @if($absensiAktif)
                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-400/30 bg-emerald-500/25 px-2.5 py-1 text-xs font-bold text-emerald-200 animate-pulse">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Presensi Terbuka
                    </span>
                @endif
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Ruang: {{ $kelas->ruangan ?? 'Online / Menyesuaikan' }}
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    {{ $kelas->mataKuliah?->sks ?? 0 }} SKS
                </span>
            </div>
        </div>

        {{-- Top Right Metrics --}}
        <div class="flex items-center gap-3 shrink-0">
            <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-center backdrop-blur-md min-w-[100px]">
                <p class="text-xs font-bold uppercase tracking-wider text-purple-200">Mahasiswa</p>
                <p class="text-2xl font-black text-white mt-0.5">{{ $kelas->mahasiswa->count() }}</p>
            </div>
            <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-center backdrop-blur-md min-w-[90px]">
                <p class="text-xs font-bold uppercase tracking-wider text-purple-200">SKS</p>
                <p class="text-2xl font-black text-white mt-0.5">{{ $kelas->mataKuliah?->sks ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

{{-- NAVIGATION TABS --}}
@php
    $tabLinks = [
        'Beranda'      => ['url' => route('dosen.kelas-detail', $kelas->id), 'active' => request()->routeIs('dosen.kelas-detail'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        'Absensi'      => ['url' => route('dosen.absensi.index', $kelas->id),  'active' => request()->routeIs('dosen.absensi.*'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
        'Materi'       => ['url' => route('dosen.kelas-materi', $kelas->id), 'active' => request()->routeIs('dosen.kelas-materi'), 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
        'Tugas'        => ['url' => route('dosen.kelas-tugas', $kelas->id),  'active' => request()->routeIs('dosen.kelas-tugas'), 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
        'Forum'        => ['url' => route('dosen.kelas-forum', $kelas->id),  'active' => request()->routeIs('dosen.kelas-forum'), 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        'Penilaian'    => ['url' => route('dosen.kelas-tugas.rekap', $kelas->id), 'active' => request()->routeIs('dosen.kelas-tugas.rekap') || request()->routeIs('dosen.gradebook'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
    ];
@endphp

<div class="mb-6 flex items-center gap-1.5 overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-1.5 shadow-sm transition-colors duration-200">
    @foreach ($tabLinks as $label => $tab)
        <a href="{{ $tab['url'] }}"
           class="inline-flex items-center gap-2 whitespace-nowrap rounded-xl px-4 py-2.5 text-xs font-bold transition duration-150
               {{ $tab['active']
                   ? 'bg-[#321270] dark:bg-purple-700 text-white shadow-sm shadow-purple-900/25'
                   : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/80 hover:text-slate-900 dark:hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 {{ $tab['active'] ? 'text-purple-200' : 'text-slate-400 dark:text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/>
            </svg>
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- MAIN CONTENT GRID --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    
    {{-- LEFT COLUMN (2/3) --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- 4 KEY METRICS ROW --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
            <a href="{{ route('dosen.kelas-materi', $kelas->id) }}" class="group rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-purple-300 dark:hover:border-purple-600 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Materi</span>
                    <div class="h-8 w-8 rounded-xl bg-purple-50 dark:bg-purple-950/50 text-[#321270] dark:text-purple-300 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-800 dark:text-white mt-2">{{ $totalMateri }}</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 group-hover:text-[#321270] dark:group-hover:text-purple-400 font-medium">Buka materi &rarr;</p>
            </a>

            <a href="{{ route('dosen.kelas-tugas', $kelas->id) }}" class="group rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-amber-300 dark:hover:border-amber-600 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Total Tugas</span>
                    <div class="h-8 w-8 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-800 dark:text-white mt-2">{{ $totalTugas }}</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 group-hover:text-amber-600 dark:group-hover:text-amber-400 font-medium">Lihat tugas &rarr;</p>
            </a>

            <a href="{{ route('dosen.kelas-tugas.rekap', $kelas->id) }}" class="group rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-rose-300 dark:hover:border-rose-600 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Perlu Dinilai</span>
                    <div class="h-8 w-8 rounded-xl {{ $tugasPerluDinilai > 0 ? 'bg-rose-50 text-rose-600 dark:bg-rose-950/50 dark:text-rose-400' : 'bg-slate-50 text-slate-400 dark:bg-slate-900/50 dark:text-slate-500' }} flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-black {{ $tugasPerluDinilai > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-800 dark:text-white' }} mt-2">{{ $tugasPerluDinilai }}</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 group-hover:text-rose-600 dark:group-hover:text-rose-400 font-medium">Input nilai &rarr;</p>
            </a>

            <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="group rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-emerald-300 dark:hover:border-emerald-600 hover:shadow-md transition">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Sesi Presensi</span>
                    <div class="h-8 w-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                </div>
                <p class="text-2xl font-black text-slate-800 dark:text-white mt-2">{{ $totalAbsensi }}</p>
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 font-medium">Kelola presensi &rarr;</p>
            </a>
        </div>

        {{-- DESKRIPSI & CPMK CARD --}}
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition-colors duration-200">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/60">
                <div class="flex items-center gap-2.5">
                    <div class="h-8 w-8 rounded-xl bg-purple-100 dark:bg-purple-950/50 text-[#321270] dark:text-purple-300 flex items-center justify-center font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800 dark:text-white">Deskripsi & Capaian Pembelajaran</h2>
                        <p class="text-xs text-slate-400 dark:text-slate-500">Rencana dan sasaran pembelajaran mata kuliah ini</p>
                    </div>
                </div>
            </div>

            <div class="space-y-5">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2">Deskripsi Mata Kuliah</h3>
                    <p class="text-sm leading-relaxed text-slate-700 dark:text-slate-300 bg-slate-50/70 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700/50">
                        {{ $kelas->mataKuliah?->deskripsi ?? 'Mata kuliah ini dirancang untuk memberikan pemahaman menyeluruh tentang konsep, teori, dan implementasi praktis sesuai dengan kurikulum program studi.' }}
                    </p>
                </div>

                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-2.5">Capaian Pembelajaran Mata Kuliah (CPMK)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                        <div class="flex items-start gap-3 rounded-xl border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/40 p-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs font-bold">✓</span>
                            <p class="text-xs font-medium text-slate-700 dark:text-slate-300 leading-snug">Mampu menguasai konsep teoritis dan fundamental keilmuan secara komprehensif.</p>
                        </div>
                        <div class="flex items-start gap-3 rounded-xl border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-900/40 p-3">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 text-xs font-bold">✓</span>
                            <p class="text-xs font-medium text-slate-700 dark:text-slate-300 leading-snug">Mampu mengimplementasikan studi kasus dan pemecahan masalah praktis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- AKTIVITAS TERKINI (MATERI & TUGAS) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- Materi Terbaru --}}
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3.5">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-purple-600"></span>
                        Materi Terbaru
                    </h3>
                    <a href="{{ route('dosen.kelas-materi', $kelas->id) }}" class="text-xs font-semibold text-[#321270] dark:text-purple-400 hover:underline">Semua</a>
                </div>

                @if($recentMateri->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach($recentMateri as $mat)
                            <div class="flex items-center justify-between rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/60 dark:bg-slate-900/40 p-3 hover:border-purple-200 transition">
                                <div class="min-w-0 flex-1 pr-2">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $mat->judul }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Pertemuan {{ $mat->pertemuan ?? '-' }} · {{ $mat->created_at?->diffForHumans() }}</p>
                                </div>
                                <span class="rounded-lg bg-purple-50 dark:bg-purple-950/40 px-2 py-1 text-[10px] font-bold text-purple-700 dark:text-purple-300 shrink-0">
                                    {{ $mat->kategori ?? 'Dokumen' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-700 p-6 text-center">
                        <p class="text-xs text-slate-400 dark:text-slate-500 mb-2">Belum ada materi diunggah</p>
                        <a href="{{ route('dosen.kelas-materi', $kelas->id) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-purple-50 dark:bg-purple-950/50 px-3 py-1.5 text-xs font-bold text-[#321270] dark:text-purple-300 hover:bg-purple-100 transition">
                            + Unggah Materi
                        </a>
                    </div>
                @endif
            </div>

            {{-- Tugas Terbaru --}}
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3.5">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                        Tugas Perkuliahan
                    </h3>
                    <a href="{{ route('dosen.kelas-tugas', $kelas->id) }}" class="text-xs font-semibold text-amber-600 dark:text-amber-400 hover:underline">Semua</a>
                </div>

                @if($recentTugas->isNotEmpty())
                    <div class="space-y-2.5">
                        @foreach($recentTugas as $tug)
                            <div class="flex items-center justify-between rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/60 dark:bg-slate-900/40 p-3 hover:border-amber-200 transition">
                                <div class="min-w-0 flex-1 pr-2">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $tug->judul }}</p>
                                    <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">Deadline: {{ $tug->deadline ? \Carbon\Carbon::parse($tug->deadline)->format('d M H:i') : '-' }}</p>
                                </div>
                                <span class="rounded-lg bg-amber-50 dark:bg-amber-950/40 px-2 py-1 text-[10px] font-bold text-amber-700 dark:text-amber-300 shrink-0">
                                    {{ $tug->bobot_nilai ?? 100 }} Poin
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-700 p-6 text-center">
                        <p class="text-xs text-slate-400 dark:text-slate-500 mb-2">Belum ada tugas dibuat</p>
                        <a href="{{ route('dosen.kelas-tugas', $kelas->id) }}" class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/50 px-3 py-1.5 text-xs font-bold text-amber-700 dark:text-amber-300 hover:bg-amber-100 transition">
                            + Buat Tugas
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- MAHASISWA TERDAFTAR PREVIEW --}}
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100 dark:border-slate-700/60">
                <div>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Mahasiswa Terdaftar</h3>
                    <p class="text-xs text-slate-400 dark:text-slate-500">Total {{ $kelas->mahasiswa->count() }} mahasiswa terdaftar dalam kelas ini</p>
                </div>
                <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#321270] dark:text-purple-400 hover:underline">
                    Lihat Presensi
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if($kelas->mahasiswa->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($kelas->mahasiswa->take(6) as $mhs)
                        <div class="flex items-center gap-3 rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40 p-2.5">
                            <div class="h-8 w-8 shrink-0 rounded-full bg-[#321270]/10 dark:bg-purple-950 text-[#321270] dark:text-purple-300 flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($mhs->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $mhs->name }}</p>
                                <p class="text-[10px] text-slate-400 dark:text-slate-500">{{ $mhs->nip_nim ?? $mhs->email }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($kelas->mahasiswa->count() > 6)
                    <p class="mt-3 text-center text-xs text-slate-400 dark:text-slate-500 font-medium">
                        + {{ $kelas->mahasiswa->count() - 6 }} mahasiswa lainnya
                    </p>
                @endif
            @else
                <p class="text-center text-xs text-slate-400 dark:text-slate-500 py-4">Belum ada mahasiswa yang terdaftar di kelas ini.</p>
            @endif
        </div>

    </div>

    {{-- RIGHT COLUMN (1/3) --}}
    <div class="space-y-6">

        {{-- QUICK ACTIONS CARD --}}
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
            <h3 class="mb-3 text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#321270] dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Aksi Cepat Dosen
            </h3>
            <div class="space-y-2.5">
                <a href="{{ route('dosen.kelas-materi', $kelas->id) }}" class="flex items-center gap-3 rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40 p-3 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-purple-400 dark:hover:border-purple-500 hover:bg-purple-50/50 dark:hover:bg-purple-950/30 transition group">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-purple-100 dark:bg-purple-950/60 text-[#321270] dark:text-purple-300 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 dark:text-white leading-tight">Tambah Materi Baru</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">Upload slide, modul, link</p>
                    </div>
                </a>

                <a href="{{ route('dosen.kelas-tugas', $kelas->id) }}" class="flex items-center gap-3 rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40 p-3 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-amber-400 dark:hover:border-amber-500 hover:bg-amber-50/50 dark:hover:bg-amber-950/30 transition group">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 dark:text-white leading-tight">Buat Tugas / Kuis</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">Tentukan deadline & bobot</p>
                    </div>
                </a>

                <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="flex items-center gap-3 rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40 p-3 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-950/30 transition group">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 dark:text-white leading-tight">Buka Sesi Presensi</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">Presensi pertemuan hari ini</p>
                    </div>
                </a>

                <a href="{{ route('dosen.kelas-tugas.rekap', $kelas->id) }}" class="flex items-center gap-3 rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40 p-3 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-emerald-400 dark:hover:border-emerald-500 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/30 transition group">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 dark:text-white leading-tight">Input & Rekap Nilai</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">Penilaian tugas & gradebook</p>
                    </div>
                </a>

                <a href="{{ route('dosen.kelas-forum', $kelas->id) }}" class="flex items-center gap-3 rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40 p-3 text-xs font-bold text-slate-700 dark:text-slate-200 hover:border-indigo-400 dark:hover:border-indigo-500 hover:bg-indigo-50/50 dark:hover:bg-indigo-950/30 transition group">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 group-hover:scale-105 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-800 dark:text-white leading-tight">Forum Diskusi Kelas</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-normal">Diskusi bersama mahasiswa</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- INFO KELAS CARD --}}
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
            <h3 class="mb-4 text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#321270] dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Informasi Kelas
            </h3>
            <div class="space-y-3.5">
                @foreach ([
                    ['label' => 'Kode Kelas', 'value' => $kelas->kode_kelas ?? $kelas->mataKuliah?->kode_mk, 'icon' => 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14'],
                    ['label' => 'Program Studi', 'value' => $kelas->mataKuliah?->programStudi?->nama_prodi ?? '-', 'icon' => 'M12 14l9-5-9-5-9 5 9 5z'],
                    ['label' => 'Beban SKS', 'value' => ($kelas->mataKuliah?->sks ?? 0).' SKS', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['label' => 'Jadwal Perkuliahan', 'value' => $kelas->hari . ', ' . substr($kelas->jam_mulai,0,5) . ' - ' . substr($kelas->jam_selesai,0,5), 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Ruangan Kuliah', 'value' => $kelas->ruangan ?? 'Online / Menyesuaikan', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['label' => 'Kapasitas Kuota', 'value' => ($kelas->kuota_mahasiswa ?? 40) . ' Kursi (' . $kelas->mahasiswa->count() . ' Terisi)', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z'],
                ] as $info)
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-purple-50 dark:bg-purple-950/50 text-[#321270] dark:text-purple-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $info['icon'] }}"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">{{ $info['label'] }}</p>
                            <p class="font-bold text-slate-800 dark:text-slate-200 text-xs truncate mt-0.5">{{ $info['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- AI ASSISTANT PROMPT WIDGET --}}
        <div class="rounded-2xl border border-purple-200/60 dark:border-purple-800/40 bg-gradient-to-br from-purple-50/60 to-indigo-50/40 dark:from-purple-950/30 dark:to-slate-900 p-5 shadow-sm">
            <div class="flex items-center gap-2.5 mb-2">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#321270] text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </span>
                <p class="text-xs font-bold text-slate-800 dark:text-white">Cendekia AI Copilot</p>
            </div>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-3">
                Butuh bantuan menyusun silabus, kisi-kisi soal, atau rangkuman materi untuk kelas ini?
            </p>
            <a href="{{ route('dosen.ai-assistant') }}" class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-[#321270] dark:bg-purple-700 px-3 py-2 text-xs font-bold text-white hover:bg-[#250d54] dark:hover:bg-purple-600 transition shadow-sm">
                Tanya AI Assistant &rarr;
            </a>
        </div>

    </div>

</div>

@endsection
