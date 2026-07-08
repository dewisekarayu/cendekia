@extends('layouts.portal')

@section('title', 'Gradebook')

@section('content')

@php
    $gradeColors = [
        'A'  => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'bar' => 'bg-emerald-500'],
        'AB' => ['bg' => 'bg-teal-100',    'text' => 'text-teal-700',    'border' => 'border-teal-200',    'bar' => 'bg-teal-500'],
        'B'  => ['bg' => 'bg-blue-100',    'text' => 'text-blue-700',    'border' => 'border-blue-200',    'bar' => 'bg-blue-500'],
        'BC' => ['bg' => 'bg-sky-100',     'text' => 'text-sky-700',     'border' => 'border-sky-200',     'bar' => 'bg-sky-500'],
        'C'  => ['bg' => 'bg-amber-100',   'text' => 'text-amber-700',   'border' => 'border-amber-200',   'bar' => 'bg-amber-500'],
        'D'  => ['bg' => 'bg-orange-100',  'text' => 'text-orange-700',  'border' => 'border-orange-200',  'bar' => 'bg-orange-500'],
        'E'  => ['bg' => 'bg-red-100',     'text' => 'text-red-700',     'border' => 'border-red-200',     'bar' => 'bg-red-500'],
    ];

    function gradeColor($grade, $key = 'bg') {
        global $gradeColors;
        return $gradeColors[$grade][$key] ?? ($key === 'bg' ? 'bg-gray-100' : ($key === 'text' ? 'text-gray-600' : 'border-gray-200'));
    }
@endphp

{{-- ===== HEADER ===== --}}
<div class="mb-6 rounded-2xl bg-[#002B6B] px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg shadow-blue-950/10">
    <div class="relative z-10">
        <p class="text-xs font-bold uppercase tracking-wide text-blue-200/70">Akademik</p>
        <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">Gradebook</h1>
        <p class="mt-1 text-sm text-blue-100/70">Rekap nilai akhir dan tugas dari semua kelas yang kamu ikuti.</p>
    </div>
    <div class="absolute -right-6 -top-6 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute right-20 -bottom-8 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>
</div>

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-2xl bg-white border border-slate-200/80 p-4 shadow-sm">
        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Rata-rata Nilai</p>
        <p class="mt-2 text-3xl font-extrabold text-[#002B6B]">
            {{ $rataRata ? number_format($rataRata, 1) : '-' }}
        </p>
        <p class="mt-1 text-xs text-gray-400">dari {{ $totalKelas }} kelas</p>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200/80 p-4 shadow-sm">
        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Nilai Tertinggi</p>
        <p class="mt-2 text-3xl font-extrabold text-emerald-600">
            {{ $nilaiTertinggi ? number_format($nilaiTertinggi, 1) : '-' }}
        </p>
        <p class="mt-1 text-xs text-gray-400">nilai terbaik kamu</p>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200/80 p-4 shadow-sm">
        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Nilai Terendah</p>
        <p class="mt-2 text-3xl font-extrabold text-amber-500">
            {{ $nilaiTerendah ? number_format($nilaiTerendah, 1) : '-' }}
        </p>
        <p class="mt-1 text-xs text-gray-400">perlu perhatian</p>
    </div>

    <div class="rounded-2xl bg-white border border-slate-200/80 p-4 shadow-sm">
        <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400">Tugas Dinilai</p>
        <p class="mt-2 text-3xl font-extrabold text-violet-600">{{ $nilaiTugasList->count() }}</p>
        <p class="mt-1 text-xs text-gray-400">tugas sudah ada nilainya</p>
    </div>
</div>

{{-- ===== MAIN CONTENT ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: Nilai Akhir Per Kelas --}}
    <div class="lg:col-span-2 space-y-4">

        <div class="flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Nilai Akhir Per Kelas</h2>
            @if ($nilaiAkhirList->isNotEmpty())
                <span class="text-xs text-gray-400">{{ $nilaiAkhirList->count() }} mata kuliah</span>
            @endif
        </div>

        @if ($nilaiAkhirList->isEmpty())
            <div class="flex min-h-[220px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white p-8 text-center shadow-sm">
                <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <h3 class="font-bold text-gray-600">Belum Ada Nilai Akhir</h3>
                <p class="mt-1 max-w-xs text-xs text-gray-400 leading-relaxed">Nilai akhir akan muncul setelah dosen atau admin menginput penilaian untuk kelas ini.</p>
            </div>
        @else
            @foreach ($nilaiAkhirList as $nilai)
                @php
                    $grade  = $nilai->grade ?? 'E';
                    $gc     = $gradeColors[$grade] ?? $gradeColors['E'];
                    $persen = min(100, (float) $nilai->nilai_akhir);

                    $komponen = [
                        ['label' => 'Kehadiran', 'nilai' => $nilai->nilai_kehadiran, 'bobot' => 10, 'color' => 'bg-blue-400'],
                        ['label' => 'Tugas',     'nilai' => $nilai->nilai_tugas,     'bobot' => 20, 'color' => 'bg-violet-400'],
                        ['label' => 'Quiz',      'nilai' => $nilai->nilai_quiz,      'bobot' => 10, 'color' => 'bg-amber-400'],
                        ['label' => 'Project',   'nilai' => $nilai->nilai_project,   'bobot' => 20, 'color' => 'bg-emerald-400'],
                        ['label' => 'UTS',       'nilai' => $nilai->nilai_uts,       'bobot' => 20, 'color' => 'bg-cyan-400'],
                        ['label' => 'UAS',       'nilai' => $nilai->nilai_uas,       'bobot' => 20, 'color' => 'bg-rose-400'],
                    ];
                @endphp

                <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
                    {{-- Top bar warna grade --}}
                    <div class="h-1 w-full {{ $gc['bar'] }}"></div>

                    <div class="p-5">
                        {{-- Header row --}}
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400 mb-0.5">
                                    {{ $nilai->kelasPerkuliahan?->mataKuliah?->kode_mk ?? '-' }}
                                </p>
                                <h3 class="font-bold text-slate-800 text-sm leading-snug">
                                    {{ $nilai->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}
                                </h3>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    {{ $nilai->kelasPerkuliahan?->dosen?->name ?? '-' }}
                                </p>
                            </div>

                            {{-- Grade badge --}}
                            <div class="shrink-0 flex flex-col items-center justify-center rounded-2xl {{ $gc['bg'] }} {{ $gc['border'] }} border px-4 py-2 min-w-[64px]">
                                <span class="text-2xl font-extrabold {{ $gc['text'] }} leading-none">{{ $grade }}</span>
                                <span class="text-[10px] font-semibold {{ $gc['text'] }} mt-0.5 opacity-70">
                                    {{ number_format($nilai->nilai_akhir, 1) }}
                                </span>
                            </div>
                        </div>

                        {{-- Progress bar nilai akhir --}}
                        <div class="mb-4">
                            <div class="flex items-center justify-between text-[11px] font-semibold text-gray-500 mb-1.5">
                                <span>Nilai Akhir</span>
                                <span class="{{ $gc['text'] }} font-bold">{{ number_format($nilai->nilai_akhir, 2) }} / 100</span>
                            </div>
                            <div class="h-2 w-full rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $gc['bar'] }} transition-all duration-500"
                                     style="width: {{ $persen }}%"></div>
                            </div>
                        </div>

                        {{-- Komponen nilai --}}
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-2">
                            @foreach ($komponen as $k)
                                <div class="rounded-xl bg-gray-50 border border-gray-100 px-2 py-2 text-center">
                                    <p class="text-[9px] font-bold uppercase tracking-wide text-gray-400 leading-tight">{{ $k['label'] }}</p>
                                    <p class="mt-1 text-sm font-extrabold text-slate-700">{{ number_format($k['nilai'], 0) }}</p>
                                    <p class="text-[9px] text-gray-400 font-medium">bobot {{ $k['bobot'] }}%</p>
                                </div>
                            @endforeach
                        </div>

                        @if ($nilai->catatan)
                            <div class="mt-3 flex items-start gap-2 rounded-xl bg-amber-50 border border-amber-100 px-3 py-2.5 text-xs text-amber-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>{{ $nilai->catatan }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- RIGHT: Distribusi Grade + Nilai Tugas --}}
    <div class="space-y-5">

        {{-- Grade Distribution --}}
        <div class="rounded-2xl bg-white border border-slate-200/80 p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 mb-4">Distribusi Grade</h3>

            @if (empty($gradeDistribusi))
                <p class="text-xs text-gray-400 py-4 text-center">Belum ada data grade.</p>
            @else
                <div class="space-y-2.5">
                    @foreach (['A','AB','B','BC','C','D','E'] as $g)
                        @php
                            $count = $gradeDistribusi[$g] ?? 0;
                            $gc2   = $gradeColors[$g] ?? $gradeColors['E'];
                            $pct   = $totalKelas > 0 ? ($count / $totalKelas) * 100 : 0;
                        @endphp
                        @if ($count > 0)
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-lg {{ $gc2['bg'] }} {{ $gc2['text'] }} flex items-center justify-center text-xs font-extrabold shrink-0">
                                    {{ $g }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                        <div class="h-full rounded-full {{ $gc2['bar'] }}" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-gray-600 w-4 text-right">{{ $count }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- Summary --}}
                <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-2 gap-3 text-center">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Total Kelas</p>
                        <p class="text-xl font-extrabold text-[#002B6B] mt-1">{{ $totalKelas }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Rata-rata</p>
                        <p class="text-xl font-extrabold text-emerald-600 mt-1">
                            {{ $rataRata ? number_format($rataRata, 1) : '-' }}
                        </p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Nilai Tugas Terbaru --}}
        <div class="rounded-2xl bg-white border border-slate-200/80 p-5 shadow-sm">
            <h3 class="text-sm font-bold text-slate-800 mb-4">Nilai Tugas Terbaru</h3>

            @if ($nilaiTugasList->isEmpty())
                <div class="py-6 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto text-gray-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-xs text-gray-400">Belum ada tugas yang dinilai.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($nilaiTugasList->take(8) as $tugas)
                        @php
                            $n = (float) $tugas->nilai;
                            $tugasGc = $n >= 85 ? $gradeColors['A']
                                : ($n >= 75 ? $gradeColors['B']
                                : ($n >= 65 ? $gradeColors['C']
                                : $gradeColors['E']));
                        @endphp
                        <div class="flex items-center gap-3">
                            {{-- Score badge --}}
                            <div class="shrink-0 w-10 h-10 rounded-xl {{ $tugasGc['bg'] }} {{ $tugasGc['border'] }} border flex items-center justify-center">
                                <span class="text-sm font-extrabold {{ $tugasGc['text'] }}">{{ (int) $n }}</span>
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-700 truncate">
                                    {{ $tugas->tugas?->judul ?? 'Tugas' }}
                                </p>
                                <p class="text-[10px] text-gray-400 truncate mt-0.5">
                                    {{ $tugas->tugas?->kelasPerkuliahan?->mataKuliah?->nama_mk ?? '-' }}
                                </p>
                            </div>

                            {{-- Mini progress --}}
                            <div class="shrink-0 w-16">
                                <div class="h-1.5 w-full rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-full {{ $tugasGc['bar'] }}" style="width: {{ min(100, $n) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($nilaiTugasList->count() > 8)
                    <p class="mt-3 text-center text-xs text-gray-400">
                        + {{ $nilaiTugasList->count() - 8 }} tugas lainnya
                    </p>
                @endif
            @endif
        </div>

    </div>
</div>

@endsection
