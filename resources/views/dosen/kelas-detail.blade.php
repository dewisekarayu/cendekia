@extends('layouts.portal')
@section('title', $kelas->mataKuliah?->nama_mk ?? 'Detail Kelas')
@section('content')

<x-flash-message />

{{-- HEADER --}}
<div class="mb-5 overflow-hidden rounded-2xl bg-gradient-to-br from-[#321270] to-[#4a1fa8] dark:from-indigo-950 dark:to-purple-900 px-6 py-6 sm:px-8 shadow-lg relative">
    <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/5"></div>
    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <div class="mb-2 flex flex-wrap items-center gap-2">
                <span class="rounded-lg border border-white/20 bg-white/15 px-2.5 py-1 text-xs font-bold text-white">
                    {{ $kelas->mataKuliah?->kode_mk ?? '-' }}
                </span>
                <span class="text-xs text-purple-200/80">{{ $kelas->semester?->nama_semester ?? 'Semester Aktif' }}</span>
            </div>
            <h1 class="text-xl font-extrabold text-white sm:text-2xl">{{ $kelas->mataKuliah?->nama_mk ?? 'Detail Kelas' }}</h1>
            <p class="mt-1 text-sm text-purple-100/80">
                {{ $kelas->hari }}, {{ substr($kelas->jam_mulai,0,5) }}–{{ substr($kelas->jam_selesai,0,5) }} · {{ $kelas->ruangan ?? '-' }}
            </p>
        </div>
        <div class="shrink-0 flex flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-xl border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                {{ $kelas->mahasiswa->count() }} mahasiswa
            </span>
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
        'Penilaian'    => ['url' => route('dosen.gradebook', ['kelas_id' => $kelas->id]), 'active' => request()->routeIs('dosen.gradebook')],
    ];
@endphp

{{-- Help Center Contextual Help --}}
@if($contextualHelp ?? null)
    @include('help-center.contextual-help', array_merge($contextualHelp, ['dismissible' => true]))
@endif

<div class="mb-5 flex items-center gap-1 overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-1.5 shadow-sm transition-colors duration-200">
    @foreach ($tabLinks as $label => $tab)
        <a href="{{ $tab['url'] }}"
           class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-bold transition
               {{ $tab['active']
                   ? 'bg-[#321270] dark:bg-purple-650 text-white shadow-sm shadow-purple-900/20'
                   : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- CONTENT --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-5">

        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm transition-colors duration-200">
            <h2 class="mb-3 text-sm font-bold text-gray-800 dark:text-white">Deskripsi Mata Kuliah</h2>
            <p class="text-sm leading-relaxed text-gray-600 dark:text-slate-300">
                {{ $kelas->mataKuliah?->deskripsi ?? 'Belum ada deskripsi untuk mata kuliah ini.' }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm transition-colors duration-200">
                <h3 class="mb-3 text-sm font-bold text-gray-800 dark:text-white">Capaian Pembelajaran</h3>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-slate-300">
                    <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-[#321270] dark:bg-purple-500"></span>Mampu menganalisis konsep dasar mata kuliah ini</li>
                    <li class="flex gap-2"><span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-[#321270] dark:bg-purple-500"></span>Mampu mengimplementasikan teori ke praktik</li>
                </ul>
            </div>
            <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm transition-colors duration-200">
                <h3 class="mb-3 text-sm font-bold text-gray-800 dark:text-white">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('dosen.kelas-materi', $kelas->id) }}" class="flex items-center gap-2.5 rounded-xl border border-gray-100 dark:border-slate-700 p-3 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:border-[#321270] dark:hover:border-purple-500 hover:bg-purple-50/40 dark:hover:bg-purple-950/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#321270] dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Materi
                    </a>
                    <a href="{{ route('dosen.kelas-tugas', $kelas->id) }}" class="flex items-center gap-2.5 rounded-xl border border-gray-100 dark:border-slate-700 p-3 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:border-amber-400 dark:hover:border-amber-500 hover:bg-amber-50/40 dark:hover:bg-amber-950/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Buat Tugas Baru
                    </a>
                    <a href="{{ route('dosen.gradebook', ['kelas_id' => $kelas->id]) }}" class="flex items-center gap-2.5 rounded-xl border border-gray-100 dark:border-slate-700 p-3 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:border-emerald-400 dark:hover:border-emerald-500 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Input Nilai
                    </a>
                    <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="flex items-center gap-2.5 rounded-xl border border-gray-100 dark:border-slate-700 p-3 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/40 dark:hover:bg-blue-950/20 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Kelola Absensi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm h-fit transition-colors duration-200">
        <h3 class="mb-4 text-sm font-bold text-gray-800 dark:text-white">Info Kelas</h3>
        <div class="space-y-3 text-xs">
            @foreach ([
                ['label' => 'Mahasiswa', 'value' => $kelas->mahasiswa->count().' orang', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z'],
                ['label' => 'SKS', 'value' => ($kelas->mataKuliah?->sks ?? 0).' SKS', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['label' => 'Program Studi', 'value' => $kelas->mataKuliah?->programStudi?->nama_prodi ?? '-', 'icon' => 'M12 14l9-5-9-5-9 5 9 5z'],
                ['label' => 'Ruangan', 'value' => $kelas->ruangan ?? '-', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
            ] as $info)
                <div class="flex items-center gap-2.5">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#321270]/10 dark:bg-purple-950/40 text-[#321270] dark:text-purple-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $info['icon'] }}"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] text-gray-400 dark:text-slate-500">{{ $info['label'] }}</p>
                        <p class="font-bold text-slate-700 dark:text-slate-200 truncate">{{ $info['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
