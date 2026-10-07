@extends('layouts.portal')

@section('title', 'Gradebook')
@section('activeMenu', 'Gradebook')

@section('content')

@php
    $gradeColors = [
        'A'  => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
        'AB' => ['bg' => 'bg-teal-100',    'text' => 'text-teal-700',    'border' => 'border-teal-200'],
        'B'  => ['bg' => 'bg-blue-100',    'text' => 'text-blue-700',    'border' => 'border-blue-200'],
        'BC' => ['bg' => 'bg-sky-100',     'text' => 'text-sky-700',     'border' => 'border-sky-200'],
        'C'  => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'border' => 'border-amber-200'],
        'D'  => ['bg' => 'bg-orange-100',  'text' => 'text-orange-700',  'border' => 'border-orange-200'],
        'E'  => ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'border' => 'border-red-200'],
    ];

    // Format angka, '-' jika kosong
    $fmt = fn($v, $d = 0) => ($v !== null && $v !== '') ? number_format((float) $v, $d) : '-';

    // Warna angka berdasarkan nilai
    $scoreClass = function ($v) {
        if ($v === null || $v === '') return 'text-slate-300';
        $v = (float) $v;
        return $v < 70 ? 'text-rose-600' : ($v < 80 ? 'text-amber-600' : 'text-slate-700');
    };

    // Status EWS -> key (kritis / waspada / aman)
    $statusKey = function ($d) {
        $l = strtolower($d->status_label ?? '');
        if (str_contains($l, 'kritis')) return 'kritis';
        if (str_contains($l, 'waspada')) return 'waspada';
        return 'aman';
    };

    $ewsItems = collect($analyticsPerClass)
        ->map(function ($d) use ($statusKey) { $d->_key = $statusKey($d); return $d; })
        ->sortBy(fn($d) => ['kritis' => 0, 'waspada' => 1, 'aman' => 2][$d->_key])
        ->values();

    $cntKritis  = $ewsItems->where('_key', 'kritis')->count();
    $cntWaspada = $ewsItems->where('_key', 'waspada')->count();
    $cntAman    = $ewsItems->where('_key', 'aman')->count();

    $reasonStyle = [
        'kritis'  => 'bg-rose-50 text-rose-600',
        'waspada' => 'bg-amber-50 text-amber-700',
        'aman'    => 'bg-slate-100 text-slate-500',
    ];
    $cardAccent = [
        'kritis'  => 'border-l-rose-500',
        'waspada' => 'border-l-amber-400',
        'aman'    => 'border-l-emerald-400',
    ];
@endphp

<style>[x-cloak]{display:none!important}</style>

{{-- ===== HEADER ===== --}}
<div class="mb-4 sm:mb-6 rounded-2xl bg-[#002B6B] px-4 py-4 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg shadow-blue-950/10">
    <div class="relative z-10">
        <p class="text-[11px] sm:text-xs font-bold uppercase tracking-wide text-blue-200/70">Akademik &amp; EWS</p>
        <h1 class="mt-1 text-lg sm:text-2xl font-extrabold text-white">Gradebook &amp; Self-Analytics</h1>
        <p class="mt-1 text-xs sm:text-sm text-blue-100/70">Rekap nilai akhir, tugas, dan pantauan performa akademik Anda secara mandiri (EWS).</p>
    </div>
    <div class="absolute -right-6 -top-6 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute right-20 -bottom-8 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
    <div class="rounded-2xl bg-white border border-slate-200/80 p-3.5 sm:p-4 shadow-sm">
        <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wide text-gray-400">Rata-rata Nilai</p>
        <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-extrabold text-[#002B6B]">{{ $rataRata ? number_format($rataRata, 1) : '-' }}</p>
        <p class="mt-1 text-[11px] sm:text-xs text-gray-400">dari {{ $totalKelas }} kelas</p>
    </div>
    <div class="rounded-2xl bg-white border border-slate-200/80 p-3.5 sm:p-4 shadow-sm">
        <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wide text-gray-400">Nilai Tertinggi</p>
        <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-extrabold text-emerald-600">{{ $nilaiTertinggi ? number_format($nilaiTertinggi, 1) : '-' }}</p>
        <p class="mt-1 text-[11px] sm:text-xs text-gray-400">nilai terbaik kamu</p>
    </div>
    <div class="rounded-2xl bg-white border border-slate-200/80 p-3.5 sm:p-4 shadow-sm">
        <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wide text-gray-400">Nilai Terendah</p>
        <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-extrabold text-amber-500">{{ $nilaiTerendah ? number_format($nilaiTerendah, 1) : '-' }}</p>
        <p class="mt-1 text-[11px] sm:text-xs text-gray-400">perlu perhatian</p>
    </div>
    <div class="rounded-2xl bg-white border border-slate-200/80 p-3.5 sm:p-4 shadow-sm">
        <p class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wide text-gray-400">Tugas Dinilai</p>
        <p class="mt-1.5 sm:mt-2 text-2xl sm:text-3xl font-extrabold text-violet-600">{{ $nilaiTugasList->count() }}</p>
        <p class="mt-1 text-[11px] sm:text-xs text-gray-400">tugas sudah ada nilainya</p>
    </div>
</div>

{{-- ===== NILAI SELURUH MATA PELAJARAN ===== --}}
<div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
    <div class="border-b border-slate-100 px-4 sm:px-6 py-3.5 sm:py-4 flex items-center justify-between gap-3">
        <h2 class="text-sm sm:text-base font-bold text-slate-800">Nilai Seluruh Mata Pelajaran</h2>
        <span class="shrink-0 text-xs text-gray-400 font-semibold">{{ $kelasList->count() }} Mata Pelajaran</span>
    </div>

    @if ($kelasList->isEmpty())
        <div class="px-6 py-12 text-center text-gray-400">
            <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-gray-300 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-600">Belum Mengikuti Kelas</h3>
            <p class="mt-1 max-w-xs text-xs text-gray-400 leading-relaxed mx-auto">Kamu belum terdaftar di kelas perkuliahan manapun.</p>
        </div>
    @else

        {{-- ===== DESKTOP: TABEL ===== --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[820px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-4 py-3.5 text-center w-12">No</th>
                        <th class="px-4 py-3.5">Mata Kuliah / Dosen</th>
                        <th class="px-3 py-3.5 text-center">Hadir<br><span class="text-[9px] text-gray-400 normal-case font-normal">10%</span></th>
                        <th class="px-3 py-3.5 text-center">Tugas<br><span class="text-[9px] text-gray-400 normal-case font-normal">20%</span></th>
                        <th class="px-3 py-3.5 text-center">Kuis<br><span class="text-[9px] text-gray-400 normal-case font-normal">10%</span></th>
                        <th class="px-3 py-3.5 text-center">Project<br><span class="text-[9px] text-gray-400 normal-case font-normal">20%</span></th>
                        <th class="px-3 py-3.5 text-center">UTS<br><span class="text-[9px] text-gray-400 normal-case font-normal">20%</span></th>
                        <th class="px-3 py-3.5 text-center">UAS<br><span class="text-[9px] text-gray-400 normal-case font-normal">20%</span></th>
                        <th class="px-4 py-3.5 text-center">Nilai Akhir</th>
                        <th class="px-4 py-3.5 text-center">Grade</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @foreach ($kelasList as $index => $kelas)
                        @php
                            $nilai = $nilaiAkhirMap->get($kelas->id);
                            $grade = $nilai?->grade;
                            $gc = $grade ? ($gradeColors[$grade] ?? null) : null;
                            $belum = !$nilai || $nilai->nilai_akhir === null;
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition-colors {{ $belum ? 'bg-slate-50/40' : '' }}">
                            <td class="px-4 py-3.5 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    <p class="font-bold text-slate-800 text-sm">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</p>
                                    @if ($nilai?->catatan)
                                        <span class="text-slate-300 hover:text-slate-500 cursor-help" title="{{ $nilai->catatan }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-0.5 text-[11px] text-gray-400">
                                    <span class="font-mono font-semibold text-slate-500">{{ $kelas->mataKuliah?->kode_mk ?? '-' }}</span>
                                    @if ($kelas->kode_kelas) &middot; Kelas {{ $kelas->kode_kelas }} @endif
                                    &middot; {{ $kelas->dosen?->name ?? '-' }}
                                </p>
                            </td>
                            <td class="px-3 py-3.5 text-center font-semibold {{ $scoreClass($nilai?->nilai_kehadiran) }}">{{ $fmt($nilai?->nilai_kehadiran) }}</td>
                            <td class="px-3 py-3.5 text-center font-semibold {{ $scoreClass($nilai?->nilai_tugas) }}">{{ $fmt($nilai?->nilai_tugas) }}</td>
                            <td class="px-3 py-3.5 text-center font-semibold {{ $scoreClass($nilai?->nilai_quiz) }}">{{ $fmt($nilai?->nilai_quiz) }}</td>
                            <td class="px-3 py-3.5 text-center font-semibold {{ $scoreClass($nilai?->nilai_project) }}">{{ $fmt($nilai?->nilai_project) }}</td>
                            <td class="px-3 py-3.5 text-center font-semibold {{ $scoreClass($nilai?->nilai_uts) }}">{{ $fmt($nilai?->nilai_uts) }}</td>
                            <td class="px-3 py-3.5 text-center font-semibold {{ $scoreClass($nilai?->nilai_uas) }}">{{ $fmt($nilai?->nilai_uas) }}</td>
                            <td class="px-4 py-3.5 text-center font-extrabold text-sm {{ $belum ? 'text-slate-300' : 'text-slate-900' }}">{{ $fmt($nilai?->nilai_akhir, 1) }}</td>
                            <td class="px-4 py-3.5 text-center">
                                @if ($gc)
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $gc['bg'] }} {{ $gc['text'] }} {{ $gc['border'] }} border text-xs font-extrabold shadow-sm">{{ $grade }}</span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2 py-1 rounded-md bg-gray-100 text-gray-500 border border-gray-200 text-[10px] font-semibold uppercase tracking-wide whitespace-nowrap">Belum Dinilai</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ===== MOBILE: KARTU ===== --}}
        <div class="md:hidden divide-y divide-slate-100">
            @foreach ($kelasList as $index => $kelas)
                @php
                    $nilai = $nilaiAkhirMap->get($kelas->id);
                    $grade = $nilai?->grade;
                    $gc = $grade ? ($gradeColors[$grade] ?? null) : null;
                    $belum = !$nilai || $nilai->nilai_akhir === null;
                    $komponen = [
                        'Hadir'   => $nilai?->nilai_kehadiran,
                        'Tugas'   => $nilai?->nilai_tugas,
                        'Kuis'    => $nilai?->nilai_quiz,
                        'Project' => $nilai?->nilai_project,
                        'UTS'     => $nilai?->nilai_uts,
                        'UAS'     => $nilai?->nilai_uas,
                    ];
                @endphp
                <div class="p-4 {{ $belum ? 'bg-slate-50/50' : '' }}">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-slate-800 leading-snug break-words">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</p>
                            <p class="mt-0.5 text-[11px] text-gray-400 break-words">
                                <span class="font-mono font-semibold text-slate-500">{{ $kelas->mataKuliah?->kode_mk ?? '-' }}</span>
                                @if ($kelas->kode_kelas) &middot; Kelas {{ $kelas->kode_kelas }} @endif
                            </p>
                            <p class="text-[11px] text-gray-400 break-words">{{ $kelas->dosen?->name ?? '-' }}</p>
                        </div>
                        <div class="shrink-0 flex items-center gap-2">
                            @if ($gc)
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $gc['bg'] }} {{ $gc['text'] }} {{ $gc['border'] }} border text-xs font-extrabold">{{ $grade }}</span>
                            @endif
                            <div class="text-right">
                                <p class="text-[9px] font-bold uppercase tracking-wide text-gray-400">Nilai Akhir</p>
                                <p class="text-xl font-black leading-tight {{ $belum ? 'text-slate-300' : 'text-slate-900' }}">{{ $fmt($nilai?->nilai_akhir, 1) }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($belum)
                        <p class="mt-3 inline-flex rounded-md bg-gray-100 border border-gray-200 px-2 py-1 text-[10px] font-semibold uppercase tracking-wide text-gray-500">Belum Dinilai</p>
                    @else
                        <div class="mt-3 grid grid-cols-6 gap-1.5">
                            @foreach ($komponen as $label => $val)
                                <div class="rounded-lg bg-slate-50 border border-slate-100 py-1.5 text-center">
                                    <p class="text-[9px] font-semibold text-gray-400">{{ $label }}</p>
                                    <p class="text-xs font-bold {{ $scoreClass($val) }}">{{ $fmt($val) }}</p>
                                </div>
                            @endforeach
                        </div>
                        @if ($nilai?->catatan)
                            <p class="mt-2 text-[11px] leading-snug text-gray-400">{{ $nilai->catatan }}</p>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ===== SELF-ANALYTICS (EWS) ===== --}}
<div class="mt-6 sm:mt-8" x-data="{ filter: 'all', showAman: false }">
    <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 mb-3 sm:mb-4">Self-Analytics &amp; Prediksi Risiko (EWS)</h2>

    {{-- Global Status Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-4 sm:mb-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center {{ $globalAttendance < 75 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase">Rata-rata Kehadiran</p>
                    <h3 class="text-xl font-black text-slate-800">{{ $globalAttendance }}%</h3>
                </div>
            </div>
            <div class="mt-3 h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                <div class="h-full rounded-full {{ $globalAttendance < 75 ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ min(100, max(0, (float) $globalAttendance)) }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex items-center gap-3.5">
            <div class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center {{ $globalAvgScore < 60 ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase">Rata-rata Nilai Tugas</p>
                <h3 class="text-xl font-black text-slate-800">{{ $globalAvgScore ?: '-' }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-5 flex items-center gap-3.5">
            <div class="w-11 h-11 shrink-0 rounded-full flex items-center justify-center {{ $globalMissed > 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase">Tugas Terlewat</p>
                <h3 class="text-xl font-black text-slate-800">{{ $globalMissed }} <span class="text-sm font-normal text-gray-500">tugas</span></h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-gray-100 bg-slate-50/50 space-y-3">
            <h3 class="font-bold text-slate-800 text-sm sm:text-base">Prediksi Risiko Per Kelas</h3>

            {{-- Filter chips --}}
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="filter = 'all'"
                        :class="filter === 'all' ? 'bg-[#002B6B] text-white border-[#002B6B]' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'"
                        class="rounded-full border px-3 py-1.5 text-xs font-bold transition">
                    Semua <span class="opacity-70">{{ $ewsItems->count() }}</span>
                </button>
                <button type="button" @click="filter = 'kritis'"
                        :class="filter === 'kritis' ? 'bg-rose-600 text-white border-rose-600' : 'bg-white text-rose-600 border-rose-200 hover:border-rose-300'"
                        class="rounded-full border px-3 py-1.5 text-xs font-bold transition">
                    Kritis <span class="opacity-70">{{ $cntKritis }}</span>
                </button>
                <button type="button" @click="filter = 'waspada'"
                        :class="filter === 'waspada' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-amber-700 border-amber-200 hover:border-amber-300'"
                        class="rounded-full border px-3 py-1.5 text-xs font-bold transition">
                    Waspada <span class="opacity-70">{{ $cntWaspada }}</span>
                </button>
                <button type="button" @click="filter = 'aman'"
                        :class="filter === 'aman' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-emerald-700 border-emerald-200 hover:border-emerald-300'"
                        class="rounded-full border px-3 py-1.5 text-xs font-bold transition">
                    Aman <span class="opacity-70">{{ $cntAman }}</span>
                </button>
            </div>
        </div>

        <div class="p-3 sm:p-5 grid grid-cols-1 lg:grid-cols-2 gap-3 sm:gap-4">
            @forelse ($ewsItems as $data)
                @php
                    $key = $data->_key;
                    $rate = min(100, max(0, (float) $data->attendance_rate));
                @endphp
                <div x-show="filter === 'all' ? ('{{ $key }}' !== 'aman' || showAman) : filter === '{{ $key }}'"
                     x-transition.opacity
                     @if ($key === 'aman') x-cloak @endif
                     class="min-w-0 rounded-xl border border-slate-200 border-l-4 {{ $cardAccent[$key] }} bg-white p-4 hover:shadow-sm transition">

                    <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                        <h4 class="font-extrabold text-slate-800 text-sm sm:text-base break-words">{{ $data->kelas->mataKuliah->nama_mk }}</h4>
                        <span class="shrink-0 px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $data->status_color }}">{{ $data->status_label }}</span>
                    </div>
                    <p class="mt-0.5 text-[11px] sm:text-xs text-gray-500 break-words">{{ $data->kelas->kode_kelas }} &middot; Dosen: {{ $data->kelas->dosen->name }}</p>

                    {{-- Metrik --}}
                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <div>
                            <div class="flex items-baseline justify-between">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Kehadiran</p>
                                <p class="font-black text-sm {{ $data->attendance_rate < 75 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $data->attendance_rate }}%</p>
                            </div>
                            <div class="mt-1.5 h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $data->attendance_rate < 75 ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ $rate }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex items-baseline justify-between">
                                <p class="text-[10px] font-bold text-gray-400 uppercase">Rata Nilai</p>
                                <p class="font-black text-sm {{ $data->avg_score && $data->avg_score < 60 ? 'text-amber-600' : 'text-slate-700' }}">{{ $data->avg_score ?: '-' }}</p>
                            </div>
                            <div class="mt-1.5 h-1.5 w-full rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $data->avg_score && $data->avg_score < 60 ? 'bg-amber-500' : 'bg-blue-500' }}" style="width: {{ min(100, max(0, (float) ($data->avg_score ?: 0))) }}%"></div>
                            </div>
                        </div>
                    </div>

                    @if (count($data->reasons) > 0)
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($data->reasons as $reason)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded {{ $reasonStyle[$key] }} text-[10px] font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $reason }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="lg:col-span-2 p-10 text-center text-gray-500">
                    <p>Anda belum terdaftar di kelas manapun semester ini.</p>
                </div>
            @endforelse
        </div>

        {{-- Toggle kelas aman --}}
        @if ($cntAman > 0)
            <div x-show="filter === 'all'" class="border-t border-gray-100 bg-slate-50/50 px-4 py-3 text-center">
                <button type="button" @click="showAman = !showAman"
                        class="text-xs font-bold text-[#002B6B] hover:underline">
                    <span x-show="!showAman">Tampilkan {{ $cntAman }} kelas aman</span>
                    <span x-show="showAman" x-cloak>Sembunyikan kelas aman</span>
                </button>
            </div>
        @endif
    </div>
</div>

@endsection