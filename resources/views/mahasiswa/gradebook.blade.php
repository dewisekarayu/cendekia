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
<div class="space-y-6">

    {{-- TABLE: Nilai Seluruh Mata Pelajaran --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-100 px-6 py-4 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">Nilai Seluruh Mata Pelajaran</h2>
            <span class="text-xs text-gray-400 font-semibold">{{ $kelasList->count() }} Mata Pelajaran</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[10px] sm:text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="px-4 py-3.5 text-center">No</th>
                        <th class="px-4 py-3.5">Kode</th>
                        <th class="px-4 py-3.5">Mata Kuliah / Dosen</th>
                        <th class="px-3 py-3.5 text-center">Kehadiran<br><span class="text-[9px] text-gray-400 lowercase font-normal">(10%)</span></th>
                        <th class="px-3 py-3.5 text-center">Tugas<br><span class="text-[9px] text-gray-400 lowercase font-normal">(20%)</span></th>
                        <th class="px-3 py-3.5 text-center">Kuis<br><span class="text-[9px] text-gray-400 lowercase font-normal">(10%)</span></th>
                        <th class="px-3 py-3.5 text-center">Project<br><span class="text-[9px] text-gray-400 lowercase font-normal">(20%)</span></th>
                        <th class="px-3 py-3.5 text-center">UTS<br><span class="text-[9px] text-gray-400 lowercase font-normal">(20%)</span></th>
                        <th class="px-3 py-3.5 text-center">UAS<br><span class="text-[9px] text-gray-400 lowercase font-normal">(20%)</span></th>
                        <th class="px-4 py-3.5 text-center">Nilai Akhir</th>
                        <th class="px-4 py-3.5 text-center">Grade</th>
                        <th class="px-4 py-3.5">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @if ($kelasList->isEmpty())
                        <tr>
                            <td colspan="12" class="px-6 py-12 text-center text-gray-400">
                                <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-gray-300 mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                </div>
                                <h3 class="font-bold text-gray-600">Belum Mengikuti Kelas</h3>
                                <p class="mt-1 max-w-xs text-xs text-gray-400 leading-relaxed mx-auto">Kamu belum terdaftar di kelas perkuliahan manapun.</p>
                            </td>
                        </tr>
                    @else
                        @foreach ($kelasList as $index => $kelas)
                            @php
                                $nilai = $nilaiAkhirMap->get($kelas->id);
                                $grade = $nilai?->grade;
                                $gc = $grade ? ($gradeColors[$grade] ?? null) : null;
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-4 text-center font-medium text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 font-mono font-semibold text-slate-600">{{ $kelas->mataKuliah?->kode_mk ?? '-' }}</td>
                                <td class="px-4 py-4">
                                    <p class="font-bold text-slate-800 text-sm mb-0.5">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $kelas->dosen?->name ?? '-' }}</p>
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-600">
                                    {{ $nilai && $nilai->nilai_kehadiran !== null ? number_format($nilai->nilai_kehadiran, 0) : '-' }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-600">
                                    {{ $nilai && $nilai->nilai_tugas !== null ? number_format($nilai->nilai_tugas, 0) : '-' }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-600">
                                    {{ $nilai && $nilai->nilai_quiz !== null ? number_format($nilai->nilai_quiz, 0) : '-' }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-600">
                                    {{ $nilai && $nilai->nilai_project !== null ? number_format($nilai->nilai_project, 0) : '-' }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-600">
                                    {{ $nilai && $nilai->nilai_uts !== null ? number_format($nilai->nilai_uts, 0) : '-' }}
                                </td>
                                <td class="px-3 py-4 text-center font-semibold text-slate-600">
                                    {{ $nilai && $nilai->nilai_uas !== null ? number_format($nilai->nilai_uas, 0) : '-' }}
                                </td>
                                <td class="px-4 py-4 text-center font-bold text-slate-900 text-sm">
                                    {{ $nilai && $nilai->nilai_akhir !== null ? number_format($nilai->nilai_akhir, 1) : '-' }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if ($gc)
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $gc['bg'] }} {{ $gc['text'] }} {{ $gc['border'] }} border text-xs font-extrabold shadow-sm">
                                            {{ $grade }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2 py-1 rounded-md bg-gray-100 text-gray-500 border border-gray-200 text-[10px] font-semibold uppercase tracking-wide">
                                            Belum Dinilai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 max-w-[200px] truncate text-gray-500" title="{{ $nilai?->catatan ?? '' }}">
                                    {{ $nilai?->catatan ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    {{-- TWO COLUMN LAYOUT AT BOTTOM --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Total Kelas Diikuti</p>
                        <p class="text-xl font-extrabold text-[#002B6B] mt-1">{{ $totalKelas }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400">Rata-rata Nilai</p>
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
