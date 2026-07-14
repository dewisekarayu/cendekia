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
        'Materi'       => ['url' => route('dosen.kelas-materi', $kelas->id), 'active' => request()->routeIs('dosen.kelas-materi')],
        'Tugas'        => ['url' => route('dosen.kelas-tugas', $kelas->id),  'active' => request()->routeIs('dosen.kelas-tugas')],
        'Absensi'      => ['url' => route('dosen.absensi.index', $kelas->id),  'active' => request()->routeIs('dosen.absensi.*')],
        'Penilaian'    => ['url' => route('dosen.gradebook', ['kelas_id' => $kelas->id]), 'active' => request()->routeIs('dosen.gradebook')],
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
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 px-5 py-4 border-b border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <div class="relative flex-1 max-w-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input id="studentSearch" type="text" placeholder="Cari nama / NIM..."
                               class="w-full rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 pl-8 pr-3 py-2 text-xs text-gray-800 dark:text-gray-100 focus:outline-none focus:border-[#321270] dark:focus:border-purple-500 focus:ring-1 focus:ring-[#321270]/20">
                    </div>
                    <span class="text-xs text-gray-400 dark:text-slate-500">
                        {{ $students->total() }} mahasiswa terdaftar
                    </span>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[640px]">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-slate-700 bg-gray-50/70 dark:bg-slate-900/30 text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">
                                <th class="px-5 py-3 text-left">Mahasiswa</th>
                                <th class="px-4 py-3 text-center">Hadir</th>
                                <th class="px-4 py-3 text-center">Tugas</th>
                                <th class="px-4 py-3 text-center">Quiz</th>
                                <th class="px-4 py-3 text-center">Project</th>
                                <th class="px-4 py-3 text-center">UTS</th>
                                <th class="px-4 py-3 text-center">UAS</th>
                                <th class="px-4 py-3 text-center">Akhir</th>
                                <th class="px-4 py-3 text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody" class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            @forelse ($students as $s)
                                @php
                                    $g  = $s->grade ?? 'E';
                                    $gc = $gradeColors[$g] ?? $gradeColors['E'];
                                @endphp
                                <tr class="student-row hover:bg-purple-50/30 dark:hover:bg-purple-900/10 transition"
                                    data-search="{{ strtolower($s->mahasiswa?->name ?? '') }} {{ strtolower($s->mahasiswa?->nip_nim ?? '') }}">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-8 h-8 rounded-full bg-[#321270] dark:bg-purple-950 flex items-center justify-center text-white dark:text-purple-300 text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($s->mahasiswa?->name ?? '?', 0, 1)) }}
                                            </div>
                                            <div class="min-w-0">
                                                <p class="font-bold text-slate-800 dark:text-white text-xs truncate">{{ $s->mahasiswa?->name ?? '-' }}</p>
                                                <p class="text-[10px] text-gray-400 dark:text-slate-500">{{ $s->mahasiswa?->nip_nim ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ number_format($s->nilai_kehadiran, 0) }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ number_format($s->nilai_tugas, 0) }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ number_format($s->nilai_quiz, 0) }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ number_format($s->nilai_project, 0) }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ number_format($s->nilai_uts, 0) }}</td>
                                    <td class="px-4 py-3.5 text-center text-xs font-medium text-gray-700 dark:text-slate-300">{{ number_format($s->nilai_uas, 0) }}</td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="font-black text-sm text-slate-800 dark:text-white">{{ number_format($s->nilai_akhir, 1) }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex items-center justify-center w-9 h-7 rounded-lg text-xs font-extrabold {{ $gc['bg'] }} {{ $gc['text'] }}">
                                            {{ $g }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 text-center text-sm text-gray-400 dark:text-slate-500">
                                        Belum ada data nilai akhir untuk kelas ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($students instanceof \Illuminate\Pagination\LengthAwarePaginator && $students->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/30">
                        {{ $students->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- SIDEBAR: Grade distribution --}}
        <div class="space-y-5">
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
});
</script>
@endpush
