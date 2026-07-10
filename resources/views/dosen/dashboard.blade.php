@extends('layouts.portal')

@section('title', 'Dashboard Dosen')
@section('activeMenu', 'Dashboard')

@section('content')

@php
    $firstName = explode(' ', auth()->user()->name)[0];
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
@endphp

<div class="space-y-6">
    {{-- HERO SECTION --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#321270] via-[#5a2cc9] to-[#7c3aed] px-8 py-10 shadow-lg">
        <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -left-10 -bottom-10 h-48 w-48 rounded-full bg-white/5 blur-3xl"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <p class="text-sm font-bold uppercase tracking-widest text-purple-200">{{ $greeting }}, Dosen</p>
                    <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                        Prof. {{ $firstName }} 👋
                    </h1>
                    <p class="mt-3 max-w-lg text-base leading-relaxed text-purple-100">
                        Anda mengampu <span class="font-bold text-white">{{ $kelasList->count() }} kelas</span> aktif 
                        @if ($tugasPerluDinilai > 0)
                            dengan <span class="font-bold text-amber-200">{{ $tugasPerluDinilai }} tugas</span> menunggu penilaian.
                        @else
                            dan semua tugas sudah dinilai. Sempurna! 🎉
                        @endif
                    </p>
                </div>
                
                <div class="grid grid-cols-3 gap-3 w-full sm:w-96 shrink-0">
                    <div class="rounded-2xl border border-white/20 bg-white/10 backdrop-blur-xl p-4 text-center">
                        <p class="text-3xl font-extrabold text-white">{{ $kelasList->count() }}</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-purple-100 mt-1.5">Kelas Aktif</p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 backdrop-blur-xl p-4 text-center">
                        <p class="text-3xl font-extrabold {{ $tugasPerluDinilai > 0 ? 'text-amber-300' : 'text-white' }}">{{ $tugasPerluDinilai }}</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-purple-100 mt-1.5">Perlu Dinilai</p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 backdrop-blur-xl p-4 text-center">
                        <p class="text-3xl font-extrabold text-white">{{ $totalMahasiswa }}</p>
                        <p class="text-xs font-bold uppercase tracking-wider text-purple-100 mt-1.5">Mahasiswa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
        <div class="rounded-2xl border border-slate-200/80 bg-gradient-to-br from-blue-50 to-blue-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide">Total Kelas</p>
                    <p class="text-4xl font-black text-blue-900 mt-2">{{ $kelasList->count() }}</p>
                </div>
                <div class="rounded-full bg-blue-500/20 p-3">
                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-gradient-to-br from-amber-50 to-amber-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-amber-600 uppercase tracking-wide">Perlu Dinilai</p>
                    <p class="text-4xl font-black {{ $tugasPerluDinilai > 0 ? 'text-amber-900' : 'text-amber-600' }} mt-2">{{ $tugasPerluDinilai }}</p>
                </div>
                <div class="rounded-full {{ $tugasPerluDinilai > 0 ? 'bg-amber-500/20' : 'bg-amber-200/20' }} p-3">
                    <svg class="h-8 w-8 {{ $tugasPerluDinilai > 0 ? 'text-amber-600' : 'text-amber-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-gradient-to-br from-emerald-50 to-emerald-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wide">Mahasiswa</p>
                    <p class="text-4xl font-black text-emerald-900 mt-2">{{ $totalMahasiswa }}</p>
                </div>
                <div class="rounded-full bg-emerald-500/20 p-3">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200/80 bg-gradient-to-br from-violet-50 to-violet-100 p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-violet-600 uppercase tracking-wide">Rata-rata Siswa</p>
                    <p class="text-4xl font-black text-violet-900 mt-2">{{ $kelasList->count() > 0 ? round($totalMahasiswa / $kelasList->count(), 0) : 0 }}</p>
                </div>
                <div class="rounded-full bg-violet-500/20 p-3">
                    <svg class="h-8 w-8 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- KELAS MENGAJAR --}}
    <div>
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">📚 Kelas Mengajar Saya</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola pembelajaran dan interaksi dengan mahasiswa</p>
            </div>
            @if ($kelasList->count() > 3)
                <a href="{{ route('dosen.kelas-saya') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#321270] hover:text-[#4a1fa8] transition">
                    Lihat Semua →
                </a>
            @endif
        </div>

        @if ($kelasList->isEmpty())
            <div class="flex min-h-[240px] flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-slate-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-base font-bold text-slate-500">Belum ada kelas perkuliahan aktif.</p>
                <p class="text-sm text-slate-400 mt-1">Hubungi administrator untuk penugasan kelas.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($kelasList->take(6) as $i => $kelas)
                    <a href="{{ route('dosen.kelas-detail', $kelas->id) }}" class="group relative overflow-hidden rounded-3xl border border-slate-200/70 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-[#321270] to-[#7c3aed]"></div>
                        <div class="p-6">
                            <div class="mb-4 flex items-start justify-between">
                                <div class="flex-1">
                                    <span class="inline-block rounded-full bg-[#321270]/10 px-3 py-1 text-xs font-bold text-[#321270] mb-2">
                                        {{ $kelas->mataKuliah?->sks ?? 0 }} SKS
                                    </span>
                                    <h3 class="text-base font-bold text-slate-800 leading-tight group-hover:text-[#321270] transition-colors line-clamp-2">
                                        {{ $kelas->mataKuliah?->nama_mk ?? '-' }}
                                    </h3>
                                    <p class="text-sm font-semibold text-[#321270] mt-1">{{ $kelas->kode_kelas }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-100 rounded-full text-xs font-bold text-emerald-700">
                                    <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            </div>

                            <div class="space-y-3 mb-5 pb-5 border-t border-b border-slate-100">
                                <div class="flex items-center gap-3 pt-4">
                                    <div class="rounded-lg bg-blue-100 p-2">
                                        <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-500 uppercase">Jadwal</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="rounded-lg bg-emerald-100 p-2">
                                        <svg class="h-4 w-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-slate-500 uppercase">Peserta</p>
                                        <p class="text-sm font-bold text-slate-800">{{ $kelas->mahasiswa->count() }} Mahasiswa</p>
                                    </div>
                                </div>
                            </div>

                            <button class="w-full rounded-xl bg-[#321270] py-2.5 text-center text-xs font-bold text-white transition-all duration-200 group-hover:bg-[#250d54] group-hover:shadow-lg">
                                Kelola Kelas →
                            </button>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</div>

@endsection
