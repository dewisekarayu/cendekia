@extends('layouts.portal')

@section('title', 'Gradebook')
@section('activeMenu', 'Gradebook')

@section('content')

@php
    $gradeColors = [
        'A'  => ['bg' => 'bg-emerald-100 dark:bg-emerald-950/40',  'text' => 'text-emerald-700 dark:text-emerald-350',  'ring' => 'ring-emerald-250'],
        'B'  => ['bg' => 'bg-blue-100 dark:bg-blue-950/40',        'text' => 'text-blue-700 dark:text-blue-350',        'ring' => 'ring-blue-250'],
        'C'  => ['bg' => 'bg-amber-100 dark:bg-amber-950/40',      'text' => 'text-amber-700 dark:text-amber-350',      'ring' => 'ring-amber-250'],
        'D'  => ['bg' => 'bg-orange-100 dark:bg-orange-950/40',    'text' => 'text-orange-700 dark:text-orange-350',    'ring' => 'ring-orange-250'],
        'E'  => ['bg' => 'bg-red-100 dark:bg-red-950/40',          'text' => 'text-red-700 dark:text-red-350',          'ring' => 'ring-red-250'],
    ];
@endphp

{{-- ===== HEADER ===== --}}
<div class="mb-6 rounded-2xl bg-[#720c16] dark:bg-gradient-to-r dark:from-[#1b0305] dark:to-[#2f0509] px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-[#fecaca]/70">Penilaian</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">Pratinjau Tabel Interaktif</h1>
            @if ($kelas)
                <p class="mt-1 text-sm text-[#fee2e2]/70">
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



{{-- ===== CLASS SELECTOR ===== --}}
@if ($kelasList->isNotEmpty())
    <div class="mb-5 flex flex-wrap gap-2">
        @foreach ($kelasList as $k)
            <a href="{{ route('dosen.gradebook', ['kelas_id' => $k->id]) }}"
               class="inline-flex items-center gap-1.5 rounded-xl border px-3.5 py-2 text-xs font-bold transition
                   {{ $kelas && $kelas->id === $k->id
                       ? 'bg-[#720c16] dark:bg-[#9a1622] text-white border-[#720c16] dark:border-[#720c16] shadow-sm shadow-[#2f0509]/20'
                       : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-[#720c16] dark:hover:border-[#a92a35] hover:text-[#720c16] dark:hover:text-[#f87171]' }}">
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
        
        $gradedStudents = $nilaiArr->filter(fn($s) => $s->nilai_akhir !== null);
        $avg       = $gradedStudents->isNotEmpty() ? $gradedStudents->avg('nilai_akhir') : null;
        $highest   = $gradedStudents->isNotEmpty() ? $gradedStudents->max('nilai_akhir') : null;
        $lowest    = $gradedStudents->isNotEmpty() ? $gradedStudents->min('nilai_akhir') : null;
        $highestGrade = $gradedStudents->isNotEmpty() ? ($gradedStudents->sortByDesc('nilai_akhir')->first()->grade ?? '-') : '-';
        $lowestGrade  = $gradedStudents->isNotEmpty() ? ($gradedStudents->sortBy('nilai_akhir')->first()->grade ?? '-') : '-';
        $gradeACount  = $gradedStudents->where('grade', 'A')->count();
        $gradeDist    = $gradedStudents->groupBy('grade')->map->count();
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-4 shadow-sm transition-colors duration-200">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">Rata-rata</p>
            <p class="mt-2 text-3xl font-extrabold text-[#720c16] dark:text-white">{{ $avg !== null ? number_format($avg, 1) : '-' }}</p>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">nilai akhir kelas</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-4 shadow-sm transition-colors duration-200">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">Tertinggi</p>
            <div class="mt-2 flex items-center gap-2">
                <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-450">{{ $highest !== null ? number_format($highest, 1) : '-' }}</p>
                @if($highestGrade !== '-')
                    <span class="inline-flex items-center justify-center w-8 h-6 rounded-lg text-xs font-extrabold {{ $gradeColors[$highestGrade]['bg'] ?? '' }} {{ $gradeColors[$highestGrade]['text'] ?? '' }}">{{ $highestGrade }}</span>
                @endif
            </div>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">nilai terbaik</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-4 shadow-sm transition-colors duration-200">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">Terendah</p>
            <div class="mt-2 flex items-center gap-2">
                <p class="text-3xl font-extrabold text-amber-500 dark:text-amber-450">{{ $lowest !== null ? number_format($lowest, 1) : '-' }}</p>
                @if($lowestGrade !== '-')
                    <span class="inline-flex items-center justify-center w-8 h-6 rounded-lg text-xs font-extrabold {{ $gradeColors[$lowestGrade]['bg'] ?? '' }} {{ $gradeColors[$lowestGrade]['text'] ?? '' }}">{{ $lowestGrade }}</span>
                @endif
            </div>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">perlu perhatian</p>
        </div>
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-4 shadow-sm transition-colors duration-200">
            <p class="text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">Nilai A</p>
            <p class="mt-2 text-3xl font-extrabold text-[#720c16] dark:text-[#f87171]">{{ $gradeACount }}</p>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">mahasiswa berprestasi</p>
        </div>
    </div>

    {{-- ===== TABLE SECTION (FULL WIDTH) ===== --}}
    <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-200 mb-6">

        {{-- Table toolbar --}}
        <form action="{{ route('dosen.gradebook.sync-absensi') }}" method="POST" id="form-sync-absensi">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        </form>

        <form action="{{ route('dosen.gradebook.update-nilai') }}" method="POST" id="form-update-nilai">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                <div class="flex items-center gap-2 flex-wrap">
                    <div class="relative flex-1 min-w-[180px] max-w-[240px]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input id="studentSearch" type="text" placeholder="Cari nama / NIM..."
                               class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 pl-8 pr-3 py-1.5 text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:border-[#720c16] dark:focus:border-[#a92a35] focus:ring-1 focus:ring-[#720c16]/20">
                    </div>
                    <select id="gradeFilter" class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 px-3 py-1.5 text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:border-[#720c16] focus:ring-1 focus:ring-[#720c16]/20">
                        <option value="">Semua Grade</option>
                        <option value="A">Grade A</option>
                        <option value="B">Grade B</option>
                        <option value="C">Grade C</option>
                        <option value="D">Grade D</option>
                        <option value="E">Grade E</option>
                    </select>
                    <select name="sort" onchange="window.location.href='?kelas_id={{ $kelas->id }}&per_page={{ $perPage }}&sort='+this.value" class="rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 px-3 py-1.5 text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:border-[#720c16] focus:ring-1 focus:ring-[#720c16]/20">
                        <option value="nama_asc" @selected(($sort ?? '') == 'nama_asc')>Urut: Nama (A-Z)</option>
                        <option value="nilai_desc" @selected(($sort ?? '') == 'nilai_desc')>Urut: Nilai Tertinggi</option>
                        <option value="nilai_asc" @selected(($sort ?? '') == 'nilai_asc')>Urut: Nilai Terendah</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" form="form-sync-absensi" class="inline-flex items-center gap-1.5 rounded-xl bg-[#fdf2f2] hover:bg-[#fee2e2] dark:bg-[#2f0509]/30 dark:hover:bg-[#2f0509]/50 text-[#720c16] dark:text-[#f87171] border border-[#fee2e2] dark:border-[#45070d] px-3 py-1.5 text-xs font-bold transition">
                        <i class="bi bi-arrow-repeat"></i> Sync Kehadiran
                    </button>
                    <button type="button" id="btnToggleEdit" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-gray-800 dark:text-white px-4 py-1.5 text-xs font-bold transition shadow-sm">
                        <i class="bi bi-pencil-square"></i> Edit Nilai
                    </button>
                    <button type="submit" id="btnSimpanNilai" class="hidden inline-flex items-center gap-1.5 rounded-xl bg-[#720c16] hover:bg-[#2f0509] dark:bg-[#720c16] dark:hover:bg-[#a92a35] text-white px-4 py-1.5 text-xs font-bold transition shadow-sm">
                        <i class="bi bi-check2-circle"></i> Simpan Nilai
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-slate-700 bg-gray-50/70 dark:bg-slate-900/30 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500">
                            <th class="px-3 py-3 text-center w-12">NO</th>
                            <th class="px-5 py-3 text-left min-w-[200px]">Mahasiswa</th>
                            <th class="px-3 py-3 text-center w-24">Hadir</th>
                            <th class="px-3 py-3 text-center w-24">Tugas</th>
                            <th class="px-3 py-3 text-center w-24">UTS</th>
                            <th class="px-3 py-3 text-center w-24">UAS</th>
                            <th class="px-3 py-3 text-center w-24">Akhir</th>
                            <th class="px-3 py-3 text-center w-20">Grade</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50">
                        @forelse ($students as $index => $s)
                            @php
                                $g  = $s->grade;
                                $gc = $g ? ($gradeColors[$g] ?? $gradeColors['E']) : null;
                            @endphp
                            <tr class="student-row transition-colors"
                                data-search="{{ strtolower($s->name ?? '') }} {{ strtolower($s->nip_nim ?? '') }}"
                                data-grade="{{ $g ?? '' }}">
                                <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-400 text-xs">
                                    {{ ($students instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($students->currentPage() - 1) * $students->perPage() : 0) + $loop->iteration }}
                                </td>
                                <td class="px-5 py-2.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-full bg-[#720c16] dark:bg-[#1b0305] flex items-center justify-center text-white dark:text-[#fca5a5] text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($s->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-slate-800 dark:text-white text-xs truncate">{{ $s->name ?? '-' }}</p>
                                            <p class="text-[10px] text-gray-400 dark:text-slate-500">{{ $s->nip_nim ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <input type="hidden" name="students[{{ $index }}][mahasiswa_id]" value="{{ $s->mahasiswa_id }}">
                                    <input type="number" step="any" name="students[{{ $index }}][nilai_kehadiran]"
                                           value="{{ $s->nilai_kehadiran !== null ? (float) $s->nilai_kehadiran : '' }}"
                                           placeholder="-"
                                           class="grade-input grade-hadir w-16 sm:w-20 text-center rounded-lg border border-transparent bg-transparent dark:bg-transparent px-1.5 py-1 text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-[#720c16] dark:focus:border-[#a92a35] focus:ring-1 focus:ring-[#720c16]/20 transition-all placeholder:text-gray-300 dark:placeholder:text-slate-600"
                                           readonly min="0" max="100">
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <input type="number" step="any" name="students[{{ $index }}][nilai_tugas]"
                                           value="{{ $s->nilai_tugas !== null ? (float) $s->nilai_tugas : '' }}"
                                           placeholder="-"
                                           class="grade-input grade-tugas w-16 sm:w-20 text-center rounded-lg border border-transparent bg-transparent dark:bg-transparent px-1.5 py-1 text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-[#720c16] dark:focus:border-[#a92a35] focus:ring-1 focus:ring-[#720c16]/20 transition-all placeholder:text-gray-300 dark:placeholder:text-slate-600"
                                           readonly min="0" max="100">
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <input type="number" step="any" name="students[{{ $index }}][nilai_uts]"
                                           value="{{ $s->nilai_uts !== null ? (float) $s->nilai_uts : '' }}"
                                           placeholder="-"
                                           class="grade-input grade-uts w-16 sm:w-20 text-center rounded-lg border border-transparent bg-transparent dark:bg-transparent px-1.5 py-1 text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-[#720c16] dark:focus:border-[#a92a35] focus:ring-1 focus:ring-[#720c16]/20 transition-all placeholder:text-gray-300 dark:placeholder:text-slate-600"
                                           readonly min="0" max="100">
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <input type="number" step="any" name="students[{{ $index }}][nilai_uas]"
                                           value="{{ $s->nilai_uas !== null ? (float) $s->nilai_uas : '' }}"
                                           placeholder="-"
                                           class="grade-input grade-uas w-16 sm:w-20 text-center rounded-lg border border-transparent bg-transparent dark:bg-transparent px-1.5 py-1 text-xs font-semibold text-slate-800 dark:text-white focus:outline-none focus:border-[#720c16] dark:focus:border-[#a92a35] focus:ring-1 focus:ring-[#720c16]/20 transition-all placeholder:text-gray-300 dark:placeholder:text-slate-600"
                                           readonly min="0" max="100">
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="nilai-akhir-val font-black text-sm text-slate-800 dark:text-white">{{ $s->nilai_akhir !== null ? number_format($s->nilai_akhir, 1) : '-' }}</span>
                                </td>
                                <td class="px-3 py-2.5 text-center">
                                    <span class="grade-badge inline-flex items-center justify-center w-9 h-7 rounded-lg text-xs font-extrabold {{ $gc ? ($gc['bg'].' '.$gc['text']) : 'bg-gray-100 text-gray-400 dark:bg-slate-700 dark:text-slate-400' }}">
                                        {{ $g ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-sm text-gray-400 dark:text-slate-500">
                                    Belum ada data nilai akhir untuk kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

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

    {{-- ===== BOTTOM SECTION: BOBOT & DISTRIBUSI (2 COLS) ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- PENGATURAN BOBOT NILAI --}}
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-5 sm:p-6 shadow-sm transition-colors duration-200">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">Pengaturan Bobot Penilaian</h3>
                    <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-0.5">Komponen penilaian kelulusan kelas</p>
                </div>
                <span id="bobot-total" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-[#720c16]/10 text-[#720c16] dark:bg-[#2f0509]/30 dark:text-[#fca5a5]">
                    Total {{ ($kelas->bobot_tugas ?? 30) + ($kelas->bobot_uts ?? 30) + ($kelas->bobot_uas ?? 40) }}%
                </span>
            </div>
            
            <form action="{{ route('dosen.gradebook.update-bobot') }}" method="POST">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                
                <div class="space-y-3.5 mb-5">
                    <div class="flex items-center justify-between gap-4 p-2 rounded-xl bg-gray-50/60 dark:bg-slate-900/40 border border-gray-100 dark:border-slate-700/50">
                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-200">Tugas &amp; Praktek</label>
                        <div class="relative w-28 shrink-0">
                            <input type="number" name="bobot_tugas" value="{{ old('bobot_tugas', $kelas->bobot_tugas ?? 30) }}" min="0" max="100" class="w-full text-right rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 py-1.5 pl-2 pr-7 text-xs font-bold text-gray-800 dark:text-white focus:outline-none focus:border-[#720c16] dark:focus:border-[#a92a35] shadow-sm">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 pointer-events-none">%</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between gap-4 p-2 rounded-xl bg-gray-50/60 dark:bg-slate-900/40 border border-gray-100 dark:border-slate-700/50">
                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-200">Ujian Tengah Semester (UTS)</label>
                        <div class="relative w-28 shrink-0">
                            <input type="number" name="bobot_uts" value="{{ old('bobot_uts', $kelas->bobot_uts ?? 30) }}" min="0" max="100" class="w-full text-right rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 py-1.5 pl-2 pr-7 text-xs font-bold text-gray-800 dark:text-white focus:outline-none focus:border-[#720c16] dark:focus:border-[#a92a35] shadow-sm">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 pointer-events-none">%</span>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between gap-4 p-2 rounded-xl bg-gray-50/60 dark:bg-slate-900/40 border border-gray-100 dark:border-slate-700/50">
                        <label class="text-xs font-semibold text-gray-700 dark:text-gray-200">Ujian Akhir Semester (UAS)</label>
                        <div class="relative w-28 shrink-0">
                            <input type="number" name="bobot_uas" value="{{ old('bobot_uas', $kelas->bobot_uas ?? 40) }}" min="0" max="100" class="w-full text-right rounded-lg border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 py-1.5 pl-2 pr-7 text-xs font-bold text-gray-800 dark:text-white focus:outline-none focus:border-[#720c16] dark:focus:border-[#a92a35] shadow-sm">
                            <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 pointer-events-none">%</span>
                        </div>
                    </div>
                </div>
                
                <button id="bobot-submit" type="submit" class="w-full py-2.5 bg-[#720c16] hover:bg-[#2f0509] dark:bg-[#720c16] dark:hover:bg-[#a92a35] text-white rounded-xl text-xs font-bold transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="bi bi-save me-1"></i> Simpan Bobot
                </button>
            </form>
            <script>
                (function () {
                    const names = ['bobot_tugas', 'bobot_uts', 'bobot_uas'];
                    const inputs = names.map(n => document.querySelector(`input[name="${n}"]`));
                    const badge = document.getElementById('bobot-total');
                    const btn = document.getElementById('bobot-submit');
                    function update() {
                        const total = inputs.reduce((s, i) => s + (parseInt(i ? i.value : 0) || 0), 0);
                        const ok = total === 100;
                        if (badge) {
                            badge.textContent = 'Total ' + total + '%';
                            badge.classList.toggle('!bg-red-100', !ok);
                            badge.classList.toggle('!text-red-600', !ok);
                        }
                        if (btn) btn.disabled = !ok;
                    }
                    inputs.forEach(i => i && i.addEventListener('input', update));
                    update();
                })();
            </script>
        </div>

        {{-- DISTRIBUSI GRADE --}}
        <div class="rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 p-5 sm:p-6 shadow-sm transition-colors duration-200">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-1">Distribusi Grade</h3>
            <p class="text-[11px] text-gray-400 dark:text-slate-500 mb-4">Sebaran nilai dari mahasiswa yang telah dinilai</p>

            @if ($gradedStudents->isEmpty())
                <div class="py-8 text-center text-xs text-gray-400 dark:text-slate-500">
                    <i class="bi bi-bar-chart text-2xl mb-1 block opacity-40"></i>
                    Belum ada nilai akhir yang dihitung.
                </div>
            @else
                <div class="space-y-2.5">
                    @foreach (['A','B','C','D','E'] as $g)
                        @php
                            $cnt = $gradeDist[$g] ?? 0;
                            $gc2 = $gradeColors[$g] ?? $gradeColors['E'];
                            $pct = $gradedStudents->count() > 0 ? ($cnt / $gradedStudents->count()) * 100 : 0;
                        @endphp
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-7 rounded-lg {{ $gc2['bg'] }} {{ $gc2['text'] }} flex items-center justify-center text-[11px] font-extrabold shrink-0">
                                {{ $g }}
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="h-2 rounded-full bg-gray-100 dark:bg-slate-900 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-300
                                        {{ $g === 'A' ? 'bg-emerald-500' :
                                           ($g === 'B' ? 'bg-blue-500' :
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

                <div class="mt-5 pt-4 border-t border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 dark:text-slate-500 uppercase font-bold tracking-wide">Tingkat Kelulusan (≥ C)</p>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">
                            @php
                                $lulus = ($gradeDist['A'] ?? 0) + ($gradeDist['B'] ?? 0) + ($gradeDist['C'] ?? 0);
                                $lulusPct = $gradedStudents->count() > 0 ? round($lulus / $gradedStudents->count() * 100) : 0;
                            @endphp
                            {{ $lulus }} dari {{ $gradedStudents->count() }} mahasiswa ternilai
                        </p>
                    </div>
                    <p class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-450">{{ $lulusPct }}%</p>
                </div>
            @endif
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('studentSearch');
    const gradeFilter = document.getElementById('gradeFilter');
    
    function filterTable() {
        const q = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const g = gradeFilter ? gradeFilter.value : '';
        
        document.querySelectorAll('.student-row').forEach(row => {
            const text = (row.dataset.search || '').toLowerCase();
            const rowGrade = row.dataset.grade || '';
            
            const matchSearch = q === '' || text.includes(q);
            const matchGrade = g === '' || rowGrade === g;
            
            row.classList.toggle('hidden', !(matchSearch && matchGrade));
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterTable);
    if (gradeFilter) gradeFilter.addEventListener('change', filterTable);

    const updateForm = document.getElementById('form-update-nilai');
    const stateKey = `gradebook-ui:${window.location.pathname}${window.location.search}`;
    const savedState = sessionStorage.getItem(stateKey);

    if (updateForm) {
        updateForm.addEventListener('submit', function () {
            sessionStorage.setItem(stateKey, JSON.stringify({
                search: searchInput?.value ?? '',
                grade: gradeFilter?.value ?? '',
                scrollY: window.scrollY,
            }));
        });
    }

    if (savedState) {
        sessionStorage.removeItem(stateKey);
        const state = JSON.parse(savedState);
        if (searchInput) searchInput.value = state.search;
        if (gradeFilter) gradeFilter.value = state.grade;
        filterTable();
        requestAnimationFrame(() => window.scrollTo(0, state.scrollY));
    }

    // Live grade calculation
    const bobotTugas = {{ (int) ($kelas->bobot_tugas ?? 30) }};
    const bobotUts = {{ (int) ($kelas->bobot_uts ?? 30) }};
    const bobotUas = {{ (int) ($kelas->bobot_uas ?? 40) }};

    function calculateRowGrade(row) {
        const inputTugas = row.querySelector('.grade-tugas');
        const inputUts = row.querySelector('.grade-uts');
        const inputUas = row.querySelector('.grade-uas');
        const akhirSpan = row.querySelector('.nilai-akhir-val');
        const gradeBadge = row.querySelector('.grade-badge');

        if (!inputTugas || !inputUts || !inputUas || !akhirSpan || !gradeBadge) return;

        const vTugas = inputTugas.value.trim();
        const vUts = inputUts.value.trim();
        const vUas = inputUas.value.trim();

        if (vTugas === '' && vUts === '' && vUas === '') {
            akhirSpan.textContent = '-';
            gradeBadge.textContent = '-';
            gradeBadge.className = 'grade-badge inline-flex items-center justify-center w-9 h-7 rounded-lg text-xs font-extrabold bg-gray-100 text-gray-400 dark:bg-slate-700 dark:text-slate-400';
            row.dataset.grade = '';
            return;
        }

        let totalBobotTerisi = 0;
        let totalNilai = 0;

        if (vTugas !== '') {
            totalNilai += (parseFloat(vTugas) || 0) * bobotTugas;
            totalBobotTerisi += bobotTugas;
        }
        if (vUts !== '') {
            totalNilai += (parseFloat(vUts) || 0) * bobotUts;
            totalBobotTerisi += bobotUts;
        }
        if (vUas !== '') {
            totalNilai += (parseFloat(vUas) || 0) * bobotUas;
            totalBobotTerisi += bobotUas;
        }

        if (totalBobotTerisi <= 0) {
            akhirSpan.textContent = '-';
            gradeBadge.textContent = '-';
            gradeBadge.className = 'grade-badge inline-flex items-center justify-center w-9 h-7 rounded-lg text-xs font-extrabold bg-gray-100 text-gray-400 dark:bg-slate-700 dark:text-slate-400';
            row.dataset.grade = '';
            return;
        }

        const akhir = Math.round((totalNilai / totalBobotTerisi) * 10) / 10;
        akhirSpan.textContent = akhir.toFixed(1);

        let g = 'E';
        let bg = 'bg-red-100 dark:bg-red-950/40';
        let text = 'text-red-700 dark:text-red-350';

        if (akhir >= 90) {
            g = 'A'; bg = 'bg-emerald-100 dark:bg-emerald-950/40'; text = 'text-emerald-700 dark:text-emerald-350';
        } else if (akhir >= 80) {
            g = 'B'; bg = 'bg-blue-100 dark:bg-blue-950/40'; text = 'text-blue-700 dark:text-blue-350';
        } else if (akhir >= 70) {
            g = 'C'; bg = 'bg-amber-100 dark:bg-amber-950/40'; text = 'text-amber-700 dark:text-amber-350';
        } else if (akhir >= 60) {
            g = 'D'; bg = 'bg-orange-100 dark:bg-orange-950/40'; text = 'text-orange-700 dark:text-orange-350';
        }

        gradeBadge.textContent = g;
        gradeBadge.className = `grade-badge inline-flex items-center justify-center w-9 h-7 rounded-lg text-xs font-extrabold ${bg} ${text}`;
        row.dataset.grade = g;
    }

    document.querySelectorAll('.student-row').forEach(row => {
        row.querySelectorAll('.grade-input').forEach(input => {
            input.addEventListener('input', () => calculateRowGrade(row));
        });
    });

    // Toggle Edit Mode
    const btnToggleEdit = document.getElementById('btnToggleEdit');
    const btnSimpanNilai = document.getElementById('btnSimpanNilai');
    const gradeInputs = document.querySelectorAll('.grade-input');

    if(btnToggleEdit) {
        btnToggleEdit.addEventListener('click', function() {
            const isEditing = !btnSimpanNilai.classList.contains('hidden');
            if(!isEditing) {
                // Masuk mode edit
                btnSimpanNilai.classList.remove('hidden');
                btnToggleEdit.innerHTML = '<i class="bi bi-x-circle"></i> Batal Edit';
                btnToggleEdit.classList.replace('bg-gray-100', 'bg-red-50');
                btnToggleEdit.classList.replace('text-gray-800', 'text-red-600');
                btnToggleEdit.classList.replace('dark:bg-slate-700', 'dark:bg-red-900/30');
                btnToggleEdit.classList.replace('dark:text-white', 'dark:text-red-400');
                
                gradeInputs.forEach(input => {
                    input.removeAttribute('readonly');
                    input.classList.remove('border-transparent', 'bg-transparent', 'dark:bg-transparent');
                    input.classList.add('border-gray-300', 'dark:border-slate-600', 'bg-white', 'dark:bg-slate-700', 'shadow-xs');
                });
            } else {
                // Batal edit
                btnSimpanNilai.classList.add('hidden');
                btnToggleEdit.innerHTML = '<i class="bi bi-pencil-square"></i> Edit Nilai';
                btnToggleEdit.classList.replace('bg-red-50', 'bg-gray-100');
                btnToggleEdit.classList.replace('text-red-600', 'text-gray-800');
                btnToggleEdit.classList.replace('dark:bg-red-900/30', 'dark:bg-slate-700');
                btnToggleEdit.classList.replace('dark:text-red-400', 'dark:text-white');
                
                gradeInputs.forEach(input => {
                    input.setAttribute('readonly', true);
                    input.classList.add('border-transparent', 'bg-transparent', 'dark:bg-transparent');
                    input.classList.remove('border-gray-300', 'dark:border-slate-600', 'bg-white', 'dark:bg-slate-700', 'shadow-xs');
                    input.value = input.defaultValue;
                });

                document.querySelectorAll('.student-row').forEach(row => calculateRowGrade(row));
            }
        });
    }
});
</script>
@endpush
