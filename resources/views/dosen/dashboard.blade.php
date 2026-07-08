@extends('layouts.portal')
@section('title', 'Dashboard')
@section('activeMenu', 'Dashboard')
@section('content')

@php
    $firstName = explode(' ', auth()->user()->name)[0];
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $topColors = ['bg-blue-500','bg-violet-500','bg-emerald-500','bg-amber-500'];
@endphp

{{-- HERO --}}
<div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-[#321270] via-[#4a1fa8] to-[#5a2cc9] px-6 py-7 sm:px-8 shadow-lg shadow-purple-950/25">
    <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
    <div class="pointer-events-none absolute right-20 -bottom-10 h-32 w-32 rounded-full bg-white/5"></div>
    <div class="relative z-10 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-purple-200/80">{{ $greeting }}, Dosen</p>
            <h1 class="mt-2 text-2xl font-extrabold text-white leading-tight">{{ $firstName }} 👋</h1>
            <p class="mt-2 max-w-md text-sm leading-relaxed text-purple-100/80">
                Kamu mengampu <span class="font-bold text-white">{{ $kelasList->count() }} kelas</span> semester ini
                @if ($tugasPerluDinilai > 0)
                    dengan <span class="font-bold text-amber-300">{{ $tugasPerluDinilai }} tugas</span> menunggu penilaian.
                @else
                    . Semua tugas sudah dinilai. 🎉
                @endif
            </p>
        </div>
        <div class="grid grid-cols-3 gap-3 sm:w-72 shrink-0">
            <div class="rounded-xl border border-white/15 bg-white/10 p-3 text-center">
                <p class="text-lg font-extrabold text-white">{{ $kelasList->count() }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wide text-purple-200/70 mt-0.5">Kelas</p>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-3 text-center">
                <p class="text-lg font-extrabold {{ $tugasPerluDinilai > 0 ? 'text-amber-300' : 'text-white' }}">{{ $tugasPerluDinilai }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wide text-purple-200/70 mt-0.5">Dinilai</p>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-3 text-center">
                <p class="text-lg font-extrabold text-white">{{ $totalMahasiswa }}</p>
                <p class="text-[10px] font-semibold uppercase tracking-wide text-purple-200/70 mt-0.5">Mahasiswa</p>
            </div>
        </div>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
    @foreach ([
        ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Kelas Diampu', 'value' => $kelasList->count(), 'color' => 'bg-[#321270]/10 text-[#321270]'],
        ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Perlu Dinilai', 'value' => $tugasPerluDinilai, 'color' => 'bg-amber-50 text-amber-600'],
        ['icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z', 'label' => 'Total Mahasiswa', 'value' => $totalMahasiswa, 'color' => 'bg-emerald-50 text-emerald-600'],
    ] as $stat)
        <div class="flex items-center gap-4 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $stat['color'] }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">{{ $stat['label'] }}</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-0.5">{{ $stat['value'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- MY CLASSES --}}
<div class="mb-6">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-base font-bold text-slate-800">Kelas Saya</h2>
        <a href="{{ route('dosen.kelas-saya') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#321270] hover:underline">
            Lihat Semua <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if ($kelasList->isEmpty())
        <div class="flex min-h-[180px] items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white p-8 text-center text-sm text-gray-400 shadow-sm">
            Belum ada kelas yang diampu. Kelas akan muncul setelah ditambahkan Admin.
        </div>
    @else
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($kelasList->take(6) as $i => $kelas)
                <div class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg">
                    <div class="h-1.5 {{ $topColors[$i % count($topColors)] }}"></div>
                    <div class="flex flex-1 flex-col p-5">
                        <div class="mb-2 flex items-start justify-between gap-2">
                            <span class="rounded-lg bg-[#321270]/8 px-2.5 py-1 text-[10px] font-bold text-[#321270]">
                                {{ $kelas->mataKuliah?->programStudi?->kode_prodi ?? 'MK' }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-slate-800 leading-snug line-clamp-2 group-hover:text-[#321270] transition">
                            {{ $kelas->mataKuliah?->nama_mk ?? '-' }}
                        </h3>
                        <p class="mt-0.5 text-[11px] text-gray-400">{{ $kelas->kode_kelas }} · {{ $kelas->mataKuliah?->sks ?? 0 }} SKS</p>

                        <div class="mt-3 space-y-1.5 text-xs text-gray-500">
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $kelas->hari }}, {{ substr($kelas->jam_mulai,0,5) }}
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                {{ $kelas->mahasiswa->count() }} mahasiswa
                            </div>
                        </div>

                        <div class="mt-auto pt-4">
                            <a href="{{ route('dosen.kelas-detail', $kelas->id) }}"
                               class="block w-full rounded-xl bg-[#321270] py-2.5 text-center text-xs font-bold text-white hover:bg-[#321270]/90 transition">
                                Kelola Kelas
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- RECENT SUBMISSIONS --}}
<div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
        <h2 class="text-sm font-bold text-slate-800">Pengumpulan Terbaru</h2>
        @if ($submissions->isNotEmpty())
            <a href="{{ route('dosen.gradebook') }}" class="text-xs font-semibold text-[#321270] hover:underline">Buka Gradebook →</a>
        @endif
    </div>

    @if ($submissions->isEmpty())
        <div class="py-12 text-center text-sm text-gray-400">Belum ada tugas yang dikumpulkan.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[560px]">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70 text-[11px] font-bold uppercase tracking-wide text-gray-400">
                        <th class="px-5 py-3 text-left">Mahasiswa</th>
                        <th class="px-5 py-3 text-left">Tugas</th>
                        <th class="px-5 py-3 text-left">Waktu</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($submissions as $item)
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-purple-50/20 transition">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#321270] text-xs font-bold text-white">
                                        {{ strtoupper(substr($item->mahasiswa?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-800 truncate max-w-[120px]">{{ $item->mahasiswa?->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-semibold text-[#321270] truncate max-w-[160px]">{{ $item->tugas?->judul ?? '-' }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $item->tugas?->kelasPerkuliahan?->mataKuliah?->nama_mk ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-500">{{ $item->waktu_kumpul?->diffForHumans() ?? '-' }}</td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('dosen.gradebook', ['kelas_id' => $item->tugas?->kelas_perkuliahan_id]) }}"
                                   class="inline-flex items-center rounded-lg bg-[#321270]/10 px-3 py-1.5 text-xs font-semibold text-[#321270] hover:bg-[#321270]/20 transition">
                                    Nilai
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
