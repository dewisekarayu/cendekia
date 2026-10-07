@extends('layouts.portal')

@section('title', 'Gradebook')
@section('activeMenu', 'Gradebook')

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
        <p class="text-xs font-bold uppercase tracking-wide text-blue-200/70">Akademik & EWS</p>
        <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">Gradebook & Self-Analytics</h1>
        <p class="mt-1 text-sm text-blue-100/70">Rekap nilai akhir, tugas, dan pantauan performa akademik Anda secara mandiri (EWS).</p>
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
</div>

    {{-- ===== SELF-ANALYTICS (EWS) ===== --}}
    <div class="mt-8">
        <h2 class="text-xl font-extrabold text-slate-800 mb-4">Self-Analytics & Prediksi Risiko (EWS)</h2>
        
        {{-- Global Status Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $globalAttendance < 75 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Rata-rata Kehadiran</p>
                    <h3 class="text-xl font-black text-slate-800">{{ $globalAttendance }}%</h3>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $globalAvgScore < 60 ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Rata-rata Nilai Tugas</p>
                    <h3 class="text-xl font-black text-slate-800">{{ $globalAvgScore ?: '-' }}</h3>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5 flex items-center gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $globalMissed > 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-400 uppercase">Tugas Terlewat</p>
                    <h3 class="text-xl font-black text-slate-800">{{ $globalMissed }} <span class="text-sm font-normal text-gray-500">tugas</span></h3>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-bold text-slate-800">Prediksi Risiko Per Kelas</h3>
            </div>
            
            <div class="divide-y divide-gray-100">
                @forelse($analyticsPerClass as $data)
                    <div class="p-5 hover:bg-slate-50/50 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="font-extrabold text-slate-800 text-base">{{ $data->kelas->mataKuliah->nama_mk }}</h4>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $data->status_color }}">
                                    {{ $data->status_label }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500">{{ $data->kelas->kode_kelas }} &middot; Dosen: {{ $data->kelas->dosen->name }}</p>
                            
                            @if(count($data->reasons) > 0)
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($data->reasons as $reason)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-rose-50 text-rose-600 text-[10px] font-semibold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                            {{ $reason }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex items-center gap-6 md:w-auto w-full md:border-l border-gray-200 md:pl-6">
                            <div class="text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Kehadiran</p>
                                <p class="font-black text-lg {{ $data->attendance_rate < 75 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $data->attendance_rate }}%</p>
                            </div>
                            <div class="text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Rata Nilai</p>
                                <p class="font-black text-lg {{ $data->avg_score < 60 ? 'text-amber-600' : 'text-slate-700' }}">{{ $data->avg_score ?: '-' }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-gray-500">
                        <p>Anda belum terdaftar di kelas manapun semester ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
