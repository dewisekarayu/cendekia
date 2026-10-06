@extends('layouts.portal')

@section('title', 'Gradebook')

@section('content')

@php
    $gradeColors = [
        'A'  => ['bg' => 'bg-emerald-100 dark:bg-emerald-950/40',  'text' => 'text-emerald-700 dark:text-emerald-350',  'ring' => 'ring-emerald-250'],
        'AB' => ['bg' => 'bg-teal-100 dark:bg-teal-950/40',        'text' => 'text-teal-700 dark:text-teal-350',        'ring' => 'ring-teal-250'],
        'B'  => ['bg' => 'bg-blue-100 dark:bg-blue-950/40',        'text' => 'text-blue-700 dark:text-blue-350',        'ring' => 'ring-blue-250'],
        'BC' => ['bg' => 'bg-sky-100 dark:bg-sky-950/40',          'text' => 'text-sky-700 dark:text-sky-350',          'ring' => 'ring-sky-250'],
        'C'  => ['bg' => 'bg-amber-100 dark:bg-amber-950/40',      'text' => 'text-amber-700 dark:text-amber-350',      'ring' => 'ring-amber-250'],
        'D'  => ['bg' => 'bg-orange-100 dark:bg-orange-950/40',    'text' => 'text-orange-700 dark:text-orange-350',    'ring' => 'ring-orange-250'],
        'E'  => ['bg' => 'bg-red-100 dark:bg-red-950/40',          'text' => 'text-red-700 dark:text-red-350',          'ring' => 'ring-red-250'],
    ];
@endphp

{{-- ===== HEADER ===== --}}
<div class="mb-6 rounded-2xl bg-[#321270] dark:bg-gradient-to-r dark:from-indigo-950 dark:to-purple-900 px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-purple-200/70">Penilaian</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">Gradebook</h1>
            @if ($kelas)
                <p class="mt-1 text-sm text-purple-100/70">
                    {{ $kelas->mataKuliah?->nama_mk ?? '-' }} &middot; {{ $kelas->kode_kelas }}
                </p>
            @endif
        </div>
        @if ($kelas)
            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-1.5 rounded-xl bg-white/15 border border-white/20 px-3 py-1.5 text-xs font-semibold text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/>
                    </svg>
                    {{ $totalStudents }} mahasiswa
                </span>
            </div>
        @endif
    </div>
    <div class="absolute -right-6 -top-6 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
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
        'Grade Akhir'  => ['url' => route('dosen.gradebook', ['kelas_id' => $kelas->id]), 'active' => request()->routeIs('dosen.gradebook')],
    ];
@endphp
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

{{-- ===== CLASS SELECTOR ===== --}}
@if ($kelasList->isNotEmpty())
    <div class="mb-5 flex flex-wrap gap-2">
        @foreach ($kelasList as $k)
            <a href="{{ route('dosen.gradebook', ['kelas_id' => $k->id]) }}"
               class="inline-flex items-center gap-1.5 rounded-xl border px-3.5 py-2 text-xs font-bold transition
                   {{ $kelas && $kelas->id === $k->id
                       ? 'bg-[#321270] dark:bg-[#6c2bd9] text-white border-[#321270] dark:border-purple-600 shadow-sm shadow-purple-900/20'
                       : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-[#321270] dark:hover:border-purple-500 hover:text-[#321270] dark:hover:text-purple-400' }}">
                {{ $k->mataKuliah?->kode_mk ?? '-' }}
                <span class="hidden sm:inline opacity-70">&mdash; {{ Str::limit($k->mataKuliah?->nama_mk ?? '-', 22) }}</span>
            </a>
        @endforeach
    </div>
@endif

@if (!$kelas)
    <div class="flex min-h-[300px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-10 text-center shadow-sm transition-colors duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <h3 class="font-bold text-gray-550 dark:text-slate-400">Belum Ada Kelas</h3>
        <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">Pilih kelas dari selector di atas untuk melihat gradebook.</p>
    </div>

@else

    {{-- ===== STAT CARDS ===== --}}
    @php
        $nilaiArr  = $students instanceof \Illuminate\Pagination\LengthAwarePaginator
            ? $students->getCollection()
            : collect($students);
        $avg       = $nilaiArr->avg('nilai_akhir');
        $highest   = $nilaiArr->max('nilai_akhir');
        $lowest    = $nilaiArr->min('nilai_akhir');
        $gradeACount = $nilaiArr->whereIn('grade', ['A','AB'])->count();
        $gradeDist = $nilaiArr->groupBy('grade')->map->count();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-4 shadow-sm transition-colors duration-200">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">Rata-rata</p>
            <p class="mt-2 text-3xl font-extrabold text-[#321270] dark:text-white">{{ $avg ? number_format($avg, 1) : '-' }}</p>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">nilai akhir kelas</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-4 shadow-sm transition-colors duration-200">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">Tertinggi</p>
            <p class="mt-2 text-3xl font-extrabold text-emerald-600 dark:text-emerald-450">{{ $highest ? number_format($highest, 1) : '-' }}</p>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">nilai terbaik</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-4 shadow-sm transition-colors duration-200">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">Terendah</p>
            <p class="mt-2 text-3xl font-extrabold text-amber-500 dark:text-amber-450">{{ $lowest ? number_format($lowest, 1) : '-' }}</p>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">perlu perhatian</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-4 shadow-sm transition-colors duration-200">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">Nilai A/AB</p>
            <p class="mt-2 text-3xl font-extrabold text-violet-600 dark:text-purple-400">{{ $gradeACount }}</p>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">mahasiswa berprestasi</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- TABLE (3 cols) --}}
        <div class="lg:col-span-3">
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-200">

                {{-- Table toolbar --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <div class="relative flex-1 max-w-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input id="studentSearch" type="text" placeholder="Cari nama / NIM..."
                               class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 pl-8 pr-3 py-2 text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:border-[#321270] dark:focus:border-purple-500 focus:ring-1 focus:ring-[#321270]/20">
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 dark:text-slate-500 font-medium">
                            {{ $students instanceof \Illuminate\Pagination\LengthAwarePaginator ? $students->total() : count($students) }} mahasiswa
                        </span>
                        <form action="{{ route('dosen.gradebook.sync-absensi') }}" method="POST">
                            @csrf
                            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                            <button type="submit" class="inline-flex items-center gap-1 rounded-xl bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 text-[#321270] dark:text-purple-400 border border-purple-100 dark:border-purple-800 px-3 py-1.5 text-xs font-bold transition">
                                <i class="bi bi-arrow-repeat"></i> Sync Kehadiran
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[640px]">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-slate-700 bg-gray-50/70 dark:bg-slate-900/30 text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">
                                <th class="px-3 py-3 text-center w-12">NO</th>
                                <th class="px-5 py-3 text-left">Mahasiswa</th>
                                <th class="px-4 py-3 text-center">Hadir</th>
                                <th class="px-4 py-3 text-center">Tugas</th>
                                <th class="px-4 py-3 text-center">Quiz</th>
                                <th class="px-4 py-3 text-center">Project</th>
                                <th class="px-4 py-3 text-center">UTS</th>
                                <th class="px-4 py-3 text-center">UAS</th>
                                <th class="px-4 py-3 text-center">Akhir</th>
                                <th class="px-4 py-3 text-center">Grade</th>
                                <th class="px-3 py-3 text-center w-14"><i class="bi bi-three-dots"></i></th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody" class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            @forelse ($students as $s)
                                @php
                                    $g  = $s->grade ?? 'E';
                                    $gc = $gradeColors[$g] ?? $gradeColors['E'];
                                @endphp
                                <tr class="student-row hover:bg-purple-50/30 dark:hover:bg-purple-900/10 transition"
                                    data-search="{{ strtolower($s->name ?? '') }} {{ strtolower($s->nip_nim ?? '') }}">
                                    <td class="px-3 py-3.5 text-center font-mono font-bold text-gray-400 text-xs">
                                        {{ ($students instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($students->currentPage() - 1) * $students->perPage() : 0) + $loop->iteration }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-[#321270] dark:bg-purple-950 flex items-center justify-center text-white dark:text-purple-300 text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($s->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-800 dark:text-white text-xs truncate">{{ $s->name ?? '-' }}</p>
                                                <p class="text-[10px] text-gray-400 dark:text-slate-500">{{ $s->nip_nim ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ $s->nilai_kehadiran !== null ? number_format($s->nilai_kehadiran, 0) : '-' }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ $s->nilai_tugas !== null ? number_format($s->nilai_tugas, 0) : '-' }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ $s->nilai_quiz !== null ? number_format($s->nilai_quiz, 0) : '-' }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ $s->nilai_project !== null ? number_format($s->nilai_project, 0) : '-' }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ $s->nilai_uts !== null ? number_format($s->nilai_uts, 0) : '-' }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ $s->nilai_uas !== null ? number_format($s->nilai_uas, 0) : '-' }}</td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="font-black text-sm text-slate-800 dark:text-white">{{ $s->nilai_akhir !== null ? number_format($s->nilai_akhir, 1) : '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex items-center justify-center w-9 h-7 rounded-lg text-xs font-extrabold {{ $gc['bg'] }} {{ $gc['text'] }}">
                                            {{ $g }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3.5 text-center">
                                        <button type="button" class="btn-edit-nilai text-[#321270] hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300" 
                                            data-bs-toggle="modal" data-bs-target="#editNilaiModal"
                                            data-id="{{ $s->mahasiswa_id }}" 
                                            data-nama="{{ $s->name }}"
                                            data-nim="{{ $s->nip_nim }}"
                                            data-hadir="{{ $s->nilai_kehadiran }}"
                                            data-tugas="{{ $s->nilai_tugas }}"
                                            data-quiz="{{ $s->nilai_quiz }}"
                                            data-project="{{ $s->nilai_project }}"
                                            data-uts="{{ $s->nilai_uts }}"
                                            data-uas="{{ $s->nilai_uas }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="py-12 text-center text-sm text-gray-400 dark:text-slate-500">
                                        Belum ada data nilai akhir untuk kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination & Footer Toolbar --}}
                <div class="px-5 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/30 flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3 flex-wrap">
                        <form method="GET" action="{{ route('dosen.gradebook') }}" class="flex items-center gap-1.5">
                            @foreach(request()->except(['per_page', 'page']) as $k => $v)
                                @if(is_array($v))
                                    @foreach($v as $item)
                                        <input type="hidden" name="{{ $k }}[]" value="{{ $item }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                @endif
                            @endforeach
                            <span class="text-xs text-gray-500 dark:text-slate-400 font-bold uppercase tracking-wider">Show:</span>
                            <select name="per_page" class="rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 py-1 text-xs text-gray-700 dark:text-gray-300 font-semibold focus:outline-none shadow-sm" onchange="this.form.submit()">
                                <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
                                <option value="25" @selected(($perPage ?? 10) == 25)>25</option>
                                <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
                                <option value="100" @selected(($perPage ?? 10) == 100)>100</option>
                            </select>
                        </form>
                        <span class="text-xs text-gray-500 dark:text-slate-400">
                            Menampilkan {{ $students instanceof \Illuminate\Pagination\LengthAwarePaginator ? $students->firstItem() : 1 }}-{{ $students instanceof \Illuminate\Pagination\LengthAwarePaginator ? $students->lastItem() : count($students) }} dari {{ $students instanceof \Illuminate\Pagination\LengthAwarePaginator ? $students->total() : count($students) }} mahasiswa
                        </span>
                    </div>
                    @if ($students instanceof \Illuminate\Pagination\LengthAwarePaginator && $students->hasPages())
                        <div>
                            {{ $students->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SIDEBAR: Grade distribution --}}
        <div class="space-y-5">
            {{-- PENGATURAN BOBOT NILAI --}}
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-5 shadow-sm transition-colors duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Pengaturan Bobot</h3>
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-[#321270]/10 text-[#321270] dark:bg-purple-900/30 dark:text-purple-300">
                        Total 100%
                    </span>
                </div>
                
                <form action="{{ route('dosen.gradebook.update-bobot') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center justify-between gap-3">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Tugas & Praktek</label>
                            <div class="relative w-20">
                                <input type="number" name="bobot_tugas" value="{{ old('bobot_tugas', $kelas->bobot_tugas ?? 30) }}" min="0" max="100" class="w-full text-right rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 py-1.5 pl-2 pr-6 text-xs font-bold text-gray-800 dark:text-white focus:outline-none focus:border-[#321270] dark:focus:border-purple-500">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between gap-3">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Ujian Tengah Semester</label>
                            <div class="relative w-20">
                                <input type="number" name="bobot_uts" value="{{ old('bobot_uts', $kelas->bobot_uts ?? 30) }}" min="0" max="100" class="w-full text-right rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 py-1.5 pl-2 pr-6 text-xs font-bold text-gray-800 dark:text-white focus:outline-none focus:border-[#321270] dark:focus:border-purple-500">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between gap-3">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-300">Ujian Akhir Semester</label>
                            <div class="relative w-20">
                                <input type="number" name="bobot_uas" value="{{ old('bobot_uas', $kelas->bobot_uas ?? 40) }}" min="0" max="100" class="w-full text-right rounded-lg border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900 py-1.5 pl-2 pr-6 text-xs font-bold text-gray-800 dark:text-white focus:outline-none focus:border-[#321270] dark:focus:border-purple-500">
                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">%</span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full py-2 bg-[#321270] hover:bg-purple-900 dark:bg-purple-600 dark:hover:bg-purple-500 text-white rounded-xl text-xs font-bold transition-colors shadow-sm">
                        Simpan Bobot
                    </button>
                </form>
            </div>

            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-5 shadow-sm transition-colors duration-200">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">Distribusi Grade</h3>

                @if ($gradeDist->isEmpty())
                    <p class="text-xs text-gray-400 dark:text-slate-500 text-center py-4">Belum ada data.</p>
                @else
                    <div class="space-y-2.5">
                        @foreach (['A','AB','B','BC','C','D','E'] as $g)
                            @php
                                $cnt = $gradeDist[$g] ?? 0;
                                $gc2 = $gradeColors[$g] ?? $gradeColors['E'];
                                $pct = $totalStudents > 0 ? ($cnt / $totalStudents) * 100 : 0;
                            @endphp
                            <div class="flex items-center gap-2.5">
                                <span class="w-9 h-7 rounded-lg {{ $gc2['bg'] }} {{ $gc2['text'] }} flex items-center justify-center text-[11px] font-extrabold shrink-0">
                                    {{ $g }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="h-2 rounded-full bg-gray-100 dark:bg-slate-900 overflow-hidden">
                                        <div class="h-full rounded-full transition-all
                                            {{ $g === 'A' || $g === 'AB' ? 'bg-emerald-500' :
                                               ($g === 'B' || $g === 'BC' ? 'bg-blue-500' :
                                               ($g === 'C' ? 'bg-amber-500' :
                                               ($g === 'D' ? 'bg-orange-500' : 'bg-red-500'))) }}"
                                             style="width: {{ $pct }}%">
                                        </div>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-gray-600 dark:text-slate-300 w-5 text-right shrink-0">{{ $cnt }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-slate-700 text-center">
                        <p class="text-[10px] text-gray-400 dark:text-slate-500 uppercase font-bold tracking-wide">Lulus (≥C)</p>
                        @php
                            $lulus = ($gradeDist['A'] ?? 0) + ($gradeDist['AB'] ?? 0) + ($gradeDist['B'] ?? 0)
                                   + ($gradeDist['BC'] ?? 0) + ($gradeDist['C'] ?? 0);
                            $lulusPct = $totalStudents > 0 ? round($lulus / $totalStudents * 100) : 0;
                        @endphp
                        <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-450 mt-1">{{ $lulusPct }}%</p>
                        <p class="text-xs text-gray-400 dark:text-slate-500">{{ $lulus }} dari {{ $totalStudents }} mahasiswa</p>
                    </div>
                @endif
            </div>

            {{-- Kelas Info --}}
            <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-5 shadow-sm space-y-3 transition-colors duration-200">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white">Info Kelas</h3>
                <div class="space-y-2 text-xs text-gray-600 dark:text-slate-300">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 dark:text-slate-500">Kode Kelas</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $kelas->kode_kelas }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 dark:text-slate-500">Hari/Jam</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 dark:text-slate-500">Ruangan</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200 text-right">{{ $kelas->ruangan ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-gray-400 dark:text-slate-500">SKS</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-200">{{ $kelas->mataKuliah?->sks ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endif

{{-- MODAL EDIT NILAI (Tailwind) --}}
<div id="editNilaiModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background overlay -->
    <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity backdrop-blur-sm"></div>

    <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100 dark:border-slate-700">
            <div class="border-b border-gray-100 dark:border-slate-700 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white text-lg" id="modal-title">Input / Edit Nilai</h3>
                <button type="button" class="btn-close-modal text-gray-400 hover:text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('dosen.gradebook.update-nilai') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelas?->id }}">
                <input type="hidden" name="mahasiswa_id" id="edit_mahasiswa_id">
                
                <div class="px-6 py-5">
                    <p class="text-sm font-bold text-[#321270] dark:text-purple-400 mb-4" id="edit_nama_mhs"></p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">Kehadiran (0-100)</label>
                            <input type="number" name="nilai_kehadiran" id="edit_kehadiran" min="0" max="100" step="any" class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-2 text-sm focus:border-[#321270] focus:ring-[#321270]/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">Tugas (0-100)</label>
                            <input type="number" name="nilai_tugas" id="edit_tugas" min="0" max="100" step="any" class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-2 text-sm focus:border-[#321270] focus:ring-[#321270]/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">Quiz (0-100)</label>
                            <input type="number" name="nilai_quiz" id="edit_quiz" min="0" max="100" step="any" class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-2 text-sm focus:border-[#321270] focus:ring-[#321270]/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">Project (0-100)</label>
                            <input type="number" name="nilai_project" id="edit_project" min="0" max="100" step="any" class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-2 text-sm focus:border-[#321270] focus:ring-[#321270]/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">UTS (0-100)</label>
                            <input type="number" name="nilai_uts" id="edit_uts" min="0" max="100" step="any" class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-2 text-sm focus:border-[#321270] focus:ring-[#321270]/20">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1">UAS (0-100)</label>
                            <input type="number" name="nilai_uas" id="edit_uas" min="0" max="100" step="any" class="w-full rounded-xl border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-700 px-3 py-2 text-sm focus:border-[#321270] focus:ring-[#321270]/20">
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-100 dark:border-slate-700 px-6 py-4 flex justify-end gap-3 bg-gray-50 dark:bg-slate-800/50 rounded-b-2xl">
                    <button type="button" class="btn-close-modal rounded-xl px-4 py-2 text-sm font-bold bg-white dark:bg-slate-700 border border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-600">Batal</button>
                    <button type="submit" class="rounded-xl px-4 py-2 text-sm font-bold text-white bg-[#321270] hover:bg-[#250d54]">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('studentSearch');
    if (!input) return;
    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('.student-row').forEach(row => {
            const text = (row.dataset.search || '').toLowerCase();
            row.classList.toggle('hidden', q !== '' && !text.includes(q));
        });
    });

    // Populate Edit Nilai Modal and Show
    document.querySelectorAll('.btn-edit-nilai').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('edit_mahasiswa_id').value = this.dataset.id;
            document.getElementById('edit_nama_mhs').textContent = this.dataset.nama + ' (' + this.dataset.nim + ')';
            document.getElementById('edit_kehadiran').value = this.dataset.hadir !== '' ? this.dataset.hadir : 0;
            document.getElementById('edit_tugas').value = this.dataset.tugas !== '' ? this.dataset.tugas : 0;
            document.getElementById('edit_quiz').value = this.dataset.quiz !== '' ? this.dataset.quiz : 0;
            document.getElementById('edit_project').value = this.dataset.project !== '' ? this.dataset.project : 0;
            document.getElementById('edit_uts').value = this.dataset.uts !== '' ? this.dataset.uts : 0;
            document.getElementById('edit_uas').value = this.dataset.uas !== '' ? this.dataset.uas : 0;
            
            document.getElementById('editNilaiModal').classList.remove('hidden');
        });
    });

    // Close Modal
    document.querySelectorAll('.btn-close-modal').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('editNilaiModal').classList.add('hidden');
        });
    });
});
</script>
@endpush
