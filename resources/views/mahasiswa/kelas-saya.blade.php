@extends('layouts.portal')

@section('title', 'My Courses')

@section('activeMenu', 'My Courses')

@section('content')

    {{-- Header --}}
    <div class="mb-6 rounded-2xl bg-gradient-to-br from-[#c0e6ff] via-[#d7efff] to-[#e8f5ff] border border-[#a3daf8]/80 dark:from-slate-900 dark:via-blue-950 dark:to-indigo-950 dark:border-blue-900/40 px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-sm">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-[#002B6B]/75 dark:text-blue-300/80">Semester Aktif</p>
                <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-[#002B6B] dark:text-white leading-tight">My Courses</h1>
                <p class="mt-1 text-sm text-[#003b8e]/80 dark:text-blue-200/80">{{ $kelasList->count() }} kelas terdaftar semester ini</p>
            </div>
        </div>
        {{-- decorative --}}
        <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/40 pointer-events-none"></div>
        <div class="absolute right-16 -bottom-10 w-28 h-28 rounded-full bg-white/30 pointer-events-none"></div>
    </div>

    {{-- Search bar --}}
    <div class="mb-6 flex items-center gap-3">
        <div class="relative flex-1 max-w-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input id="kelasSearch" type="text" placeholder="Cari mata kuliah..." autocomplete="off"
                   class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-9 pr-4 text-sm text-gray-700 shadow-sm focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200">
        </div>
        <span id="kelasCount" class="shrink-0 text-xs text-gray-400 font-medium">{{ $kelasList->count() }} kelas</span>
    </div>

    @if ($kelasList->isEmpty())
        <div class="flex min-h-[320px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white p-10 text-center shadow-sm">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-700">Belum Ada Kelas Terdaftar</h3>
            <p class="mt-1 max-w-xs text-xs text-gray-400 leading-relaxed">Kamu belum terdaftar di kelas manapun. Jelajahi kelas yang tersedia dan bergabung sekarang.</p>
            <a href="{{ route('mahasiswa.jelajahi-kelas') }}"
               class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#c0e6ff] px-5 py-2.5 text-sm font-bold text-[#002B6B] border border-[#002B6B]/20 shadow-sm hover:bg-[#a8daf8] transition">
                Jelajahi Kelas Sekarang
            </a>
        </div>
    @else
        @php
            $colors = [
                ['bg' => 'bg-[#c0e6ff]', 'light' => 'bg-sky-50',   'text' => 'text-[#002B6B]', 'bar' => 'bg-[#c0e6ff]', 'btn' => 'bg-[#c0e6ff] text-[#002B6B] border border-[#002B6B]/20 hover:bg-[#a8daf8]'],
                ['bg' => 'bg-blue-600',   'light' => 'bg-blue-50',  'text' => 'text-blue-700',   'bar' => 'bg-blue-600',   'btn' => 'bg-blue-600 text-white hover:bg-blue-700'],
                ['bg' => 'bg-sky-500',    'light' => 'bg-sky-50',   'text' => 'text-sky-700',    'bar' => 'bg-sky-500',    'btn' => 'bg-sky-500 text-white hover:bg-sky-600'],
                ['bg' => 'bg-indigo-600', 'light' => 'bg-indigo-50','text' => 'text-indigo-700', 'bar' => 'bg-indigo-600', 'btn' => 'bg-indigo-600 text-white hover:bg-indigo-700'],
                ['bg' => 'bg-cyan-600',   'light' => 'bg-cyan-50',  'text' => 'text-cyan-700',   'bar' => 'bg-cyan-600',   'btn' => 'bg-cyan-600 text-white hover:bg-cyan-700'],
                ['bg' => 'bg-blue-500',   'light' => 'bg-sky-100',  'text' => 'text-blue-800',   'bar' => 'bg-blue-500',   'btn' => 'bg-blue-500 text-white hover:bg-blue-600'],
            ];
        @endphp

        <div id="kelasGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($kelasList as $i => $kelas)
                @php $c = $colors[$i % count($colors)]; @endphp
                <a href="{{ route('mahasiswa.kelas-detail', $kelas->id) }}"
                   data-search="{{ strtolower($kelas->mataKuliah?->nama_mk ?? '') }} {{ strtolower($kelas->mataKuliah?->kode_mk ?? '') }} {{ strtolower($kelas->kode_kelas ?? '') }} {{ strtolower($kelas->mataKuliah?->programStudi?->nama_prodi ?? '') }} {{ strtolower($kelas->dosen?->name ?? '') }}"
                   class="kelas-card group flex flex-col rounded-2xl border border-slate-200/80 bg-white shadow-sm hover:shadow-lg hover:shadow-blue-950/8 hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">

                    {{-- Color bar top --}}
                    <div class="h-1.5 w-full {{ $c['bg'] }}"></div>

                    <div class="flex flex-col flex-1 p-4">
                        {{-- 1. PRODI --}}
                        <div class="flex items-center justify-between gap-2 mb-3">
                            <span class="inline-block max-w-[75%] truncate rounded-md {{ $c['light'] }} {{ $c['text'] }} px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide">
                                {{ $kelas->mataKuliah?->programStudi?->nama_prodi ?? 'Umum' }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-blue-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 inline-block"></span>
                                Aktif
                            </span>
                        </div>

                        {{-- 3. MATA KULIAH --}}
                        <div class="mt-3 rounded-lg bg-gray-50 px-3 py-2.5">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400">Mata Kuliah</p>
                            <p class="mt-0.5 text-sm font-semibold text-slate-700 leading-snug line-clamp-2">
                                {{ $kelas->mataKuliah?->nama_mk ?? '-' }}
                            </p>
                            <p class="mt-0.5 text-[11px] text-gray-400">
                                {{ $kelas->mataKuliah?->kode_mk ?? '-' }} &bull; {{ $kelas->mataKuliah?->sks ?? 0 }} SKS
                            </p>
                        </div>

                        {{-- Meta info --}}
                        <div class="mt-3 space-y-1.5 text-[11px] text-gray-500">
                            <div class="flex items-center gap-1.5 truncate">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate">{{ $kelas->dosen?->name ?? '-' }}</span>
                            </div>
                        </div>

                        {{-- CTA --}}
                        <div class="mt-3">
                            <span class="flex w-full items-center justify-center gap-1.5 rounded-xl {{ $c['btn'] ?? ($c['bg'] . ' text-white') }} py-2 text-xs font-bold shadow-sm transition">
                                Masuk Kelas
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Empty search state --}}
        <div id="kelasEmpty" class="hidden mt-6 rounded-xl border border-gray-100 bg-white p-10 text-center shadow-sm">
            <p class="text-sm text-gray-400">Tidak ada kelas yang cocok dengan pencarianmu.</p>
        </div>
    @endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('kelasSearch');
    const grid  = document.getElementById('kelasGrid');
    const empty = document.getElementById('kelasEmpty');
    const count = document.getElementById('kelasCount');
    const isEnglish = document.documentElement.lang === 'en';
    if (!input || !grid) return;

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        const cards = grid.querySelectorAll('.kelas-card');
        let visible = 0;
        cards.forEach(card => {
            const match = !q || (card.dataset.search || '').includes(q);
            card.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        if (empty) empty.classList.toggle('hidden', visible > 0);
        if (count) {
            const classLabel = isEnglish
                ? (visible === 1 ? 'class' : 'classes')
                : 'kelas';
            count.textContent = visible + ' ' + classLabel;
        }
    });
});
</script>
@endpush
