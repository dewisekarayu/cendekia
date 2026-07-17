@extends('layouts.portal')
@section('title', $kelas->mataKuliah?->nama_mk ?? 'Detail Kelas')
@section('content')

@php
    $hariIni  = now()->locale('id')->dayName;
    $sekarang = now()->format('H:i:s');
    if ($kelas->hari === $hariIni) {
        if ($sekarang < $kelas->jam_mulai) {
            $statusLabel = 'Belum Mulai'; $statusClass = 'bg-amber-50 text-amber-600 border border-amber-200';
        } elseif ($sekarang <= $kelas->jam_selesai) {
            $statusLabel = 'Sedang Berlangsung'; $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
        } else {
            $statusLabel = 'Selesai Hari Ini'; $statusClass = 'bg-gray-100 text-gray-500 border border-gray-200';
        }
    } else {
        $statusLabel = null;
    }
@endphp

{{-- ===== HEADER BANNER ===== --}}
<div class="mb-5 overflow-hidden rounded-2xl bg-gradient-to-br from-[#002B6B] to-[#0044a8] p-6 sm:p-7 shadow-lg shadow-blue-950/15 relative">
    <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/5"></div>
    <div class="pointer-events-none absolute right-16 bottom-0 h-20 w-20 rounded-full bg-white/5"></div>
    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <div class="mb-2 flex flex-wrap items-center gap-2">
                <span class="rounded-lg bg-white/15 border border-white/20 px-2.5 py-1 text-xs font-bold text-white">
                    {{ $kelas->mataKuliah?->kode_mk ?? '-' }}
                </span>
                <span class="text-xs text-blue-200/80">Kelas {{ $kelas->kode_kelas }}</span>
                @if ($statusLabel)
                    <span class="rounded-full {{ $statusClass }} px-2.5 py-1 text-[10px] font-bold">{{ $statusLabel }}</span>
                @endif
            </div>
            <h1 class="text-xl font-extrabold leading-tight text-white sm:text-2xl">
                {{ $kelas->mataKuliah?->nama_mk ?? 'Detail Kelas' }}
            </h1>
            <p class="mt-1.5 text-sm text-blue-100/80">
                Bersama {{ $kelas->dosen?->name ?? 'Dosen pengampu' }}
            </p>
        </div>
        <a href="{{ route('mahasiswa.kelas-saya') }}"
           class="shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-xs font-semibold text-white hover:bg-white/20 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>
</div>

{{-- ===== INFO CARDS ===== --}}
<div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
    @php
        $infoCards = [
            ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Dosen', 'value' => $kelas->dosen?->name ?? '-', 'color' => 'text-blue-600 bg-blue-50'],
            ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => $kelas->hari, 'value' => substr($kelas->jam_mulai,0,5).' – '.substr($kelas->jam_selesai,0,5), 'color' => 'text-amber-600 bg-amber-50'],
            ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Ruangan', 'value' => $kelas->ruangan ?? '-', 'color' => 'text-rose-600 bg-rose-50'],
            ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'SKS', 'value' => ($kelas->mataKuliah?->sks ?? 0).' SKS', 'color' => 'text-violet-600 bg-violet-50'],
        ];
    @endphp
    @foreach ($infoCards as $card)
        <div class="flex items-center gap-3 rounded-2xl border border-slate-200/80 bg-white p-3.5 shadow-sm">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $card['color'] }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/></svg>
            </div>
            <div class="min-w-0">
                <p class="truncate text-xs font-semibold text-slate-800 leading-tight">{{ $card['value'] }}</p>
                <p class="text-[10px] text-gray-400">{{ $card['label'] }}</p>
            </div>
        </div>
    @endforeach
</div>

{{-- ===== MAIN CONTENT ===== --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3" x-data="{ tab: 'semua' }" x-init="tab = new URLSearchParams(window.location.search).get('tab') || 'semua'">

    {{-- Content Area --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Help Center Contextual Help --}}
        @if($contextualHelp ?? null)
            @include('help-center.contextual-help', array_merge($contextualHelp, ['dismissible' => true]))
        @endif

        {{-- Tab buttons (Alpine, tanpa reload halaman) --}}
        <div class="flex gap-2 overflow-x-auto pb-1 items-center">
            @php
                $tabs = [
                    'semua'   => ['label' => 'Semua', 'icon' => 'M4 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6z'],
                    'materi'  => ['label' => 'Materi', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    'tugas'   => ['label' => 'Tugas', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'absensi' => ['label' => 'Absensi', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    'forum'   => ['label' => 'Forum', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                ];
            @endphp

            @foreach ($tabs as $key => $t)
                <button @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}'
                            ? 'bg-[#002B6B] text-white shadow-sm shadow-blue-900/20'
                            : 'bg-white border border-gray-200 text-gray-600 hover:border-[#002B6B] hover:text-[#002B6B]'"
                        class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-semibold transition inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/>
                    </svg>
                    {{ $t['label'] }}
                </button>
            @endforeach

            <!-- <div class="flex gap-2 ml-auto items-center">
                <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}" class="whitespace-nowrap rounded-full px-4 py-2 text-xs font-semibold bg-emerald-500 text-white hover:bg-emerald-600 transition inline-flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Pencet Absen
                </a>
            </div> -->
        </div>

        {{-- MATERI --}}
        <div x-show="tab === 'semua' || tab === 'materi'" class="space-y-3">
            @forelse ($materiList as $materi)
                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[#002B6B]">
                        @php
                            $icons = ['pdf' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z', 'mp4' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z'];
                            $iconPath = $icons[$materi->tipe_file ?? ''] ?? 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';
                        @endphp
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="mb-1 flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold text-slate-600">Pertemuan {{ $materi->pertemuan_ke }}</span>
                            @if ($materi->tipe_file)
                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold uppercase text-blue-600">{{ $materi->tipe_file }}</span>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-slate-800">{{ $materi->judul }}</p>
                        <p class="mt-0.5 text-xs leading-relaxed text-slate-500">
                        {{ $materi->deskripsi ? Str::limit($materi->deskripsi, 110) : ($materi->files->isNotEmpty() ? 'File tersedia.' : 'File belum diunggah.') }}
                    </p>
                    </div>
                    @if ($materi->files->isNotEmpty())
                        <a href="{{ route('mahasiswa.materi.buka', [$kelas->id, $materi->id]) }}"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-[#002B6B] px-4 py-2.5 text-xs font-bold text-white hover:bg-blue-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Buka ({{ $materi->files->count() }})
                        </a>
                    @else
                        <span class="inline-flex shrink-0 items-center justify-center rounded-xl bg-slate-100 px-4 py-2.5 text-xs font-bold text-slate-400">Belum Ada</span>
                    @endif
                </div>
            @empty
                <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-8 text-center text-sm text-gray-400" x-show="tab === 'materi'">
                    Belum ada materi untuk kelas ini.
                </div>
            @endforelse
        </div>

        {{-- TUGAS --}}
        <div x-show="tab === 'semua' || tab === 'tugas'" class="space-y-3">
            @forelse ($tugasList as $tugas)
                @php
                    $dl = \Carbon\Carbon::parse($tugas->deadline);
                    $overdue = $dl->isPast();
                    $daysLeft = (int) floor(now()->diffInDays($dl, false));

                    // pengumpulan milik mahasiswa yang login (eager-load dari controller)
                    $pengumpulanSaya = $tugas->pengumpulanTugas
                        ->firstWhere('status', '!=', \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL);

                    if ($pengumpulanSaya && $pengumpulanSaya->status === \App\Models\PengumpulanTugas::STATUS_DINILAI) {
                        $tugasBtnLabel = 'Lihat Nilai';
                        $tugasBtnClass = 'bg-emerald-600 hover:bg-emerald-700';
                        $tugasBtnIcon  = 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                    } elseif ($pengumpulanSaya) {
                        $tugasBtnLabel = 'Lihat Pengumpulan';
                        $tugasBtnClass = 'bg-slate-600 hover:bg-slate-700';
                        $tugasBtnIcon  = 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z';
                    } else {
                        $tugasBtnLabel = 'Kerjakan';
                        $tugasBtnClass = 'bg-[#002B6B] hover:bg-blue-800';
                        $tugasBtnIcon  = 'M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14';
                    }
                @endphp
                <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $overdue && !$pengumpulanSaya ? 'bg-red-50 text-red-500' : 'bg-amber-50 text-amber-600' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $tugas->judul }}</p>
                        <p class="mt-0.5 text-xs text-gray-400">
                            Deadline: {{ $dl->format('d M Y, H:i') }}
                            @if ($pengumpulanSaya)
                                <span class="ml-1 font-semibold text-emerald-600">· Sudah dikumpulkan</span>
                            @elseif ($overdue)
                                <span class="ml-1 font-semibold text-red-500">· Sudah lewat</span>
                            @elseif ($daysLeft <= 2)
                                <span class="ml-1 font-semibold text-amber-600">· {{ $daysLeft === 0 ? 'Hari ini' : $daysLeft.' hari lagi' }}</span>
                            @endif
                        </p>
                    </div>

                    <div class="flex shrink-0 items-center gap-2">
                        @if ($tugas->bobot_nilai)
                            <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600">{{ $tugas->bobot_nilai }}%</span>
                        @endif

                        @if ($pengumpulanSaya?->is_graded)
                            <span class="rounded-lg bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">{{ $pengumpulanSaya->nilai }}/100</span>
                        @endif

                        <a href="{{ route('mahasiswa.pengumpulan-tugas.show', $tugas->id) }}"
                           class="inline-flex items-center gap-1.5 rounded-xl {{ $tugasBtnClass }} px-4 py-2.5 text-xs font-bold text-white transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tugasBtnIcon }}"/></svg>
                            {{ $tugasBtnLabel }}
                        </a>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-8 text-center text-sm text-gray-400" x-show="tab === 'tugas'">
                    Belum ada tugas untuk kelas ini.
                </div>
            @endforelse
        </div>

        {{-- ABSENSI --}}
        <div x-show="tab === 'semua' || tab === 'absensi'" class="space-y-3">
            @forelse ($rekapAbsen as $item)
                @php
                    $attendance = $item->absensiMahasiswa->first();
                    $status = $attendance?->status;
                    if (!$status) {
                        if ($item->isBuka()) {
                            $status = 'terbuka';
                        } elseif ($item->isDraft()) {
                            $status = 'draft';
                        } else {
                            $status = 'alpha';
                        }
                    }

                    $absenMap = [
                        'hadir'    => ['label' => 'Hadir',         'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/30', 'icon' => 'M5 13l4 4L19 7'],
                        'izin'     => ['label' => 'Izin',           'class' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/40 dark:text-blue-400 dark:border-blue-900/30',           'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'sakit'    => ['label' => 'Sakit',          'class' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/30',      'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'alpha'    => ['label' => 'Tidak Hadir',    'class' => 'bg-red-50 text-red-700 border-red-200 dark:bg-rose-950/40 dark:text-rose-400 dark:border-rose-900/30',              'icon' => 'M6 18L18 6M6 6l12 12'],
                        'terbuka'  => ['label' => 'Terbuka',        'class' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/40 dark:text-indigo-400 dark:border-indigo-900/30',  'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        'draft'    => ['label' => 'Belum Dimulai',  'class' => 'bg-slate-50 text-slate-500 border-slate-200 dark:bg-slate-900/40 dark:text-slate-400 dark:border-slate-800',        'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ];
                    $ab = $absenMap[$status] ?? $absenMap['alpha'];
                @endphp
                <div class="flex items-center gap-3 rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm transition-colors duration-200">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border {{ $ab['class'] }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $ab['icon'] }}"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-250">Pertemuan {{ $item->pertemuan_ke }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-450">{{ $item->tanggal->format('d M Y') }}
                            @if ($item->rangkuman)
                                · {{ Str::limit($item->rangkuman, 80) }}
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($status === 'terbuka')
                            <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}" class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white hover:text-white text-decoration-none transition shadow-sm">
                                Absen Masuk
                            </a>
                        @endif
                        <span class="shrink-0 rounded-full border px-2.5 py-1 text-[10px] font-bold {{ $ab['class'] }}">{{ $ab['label'] }}</span>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border-2 border-dashed border-gray-200 bg-white p-8 text-center text-sm text-gray-400" x-show="tab === 'absensi'">
                    Belum ada data absensi.
                </div>
            @endforelse
        </div>

        {{-- FORUM --}}
        <div x-show="tab === 'forum'" class="space-y-4">
            <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm text-center">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-violet-50 text-violet-600 mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Forum Diskusi</h3>
                <p class="text-sm text-gray-500 mb-5">Buka forum diskusi untuk chat dengan dosen dan teman sekelas</p>
                <a href="{{ route('mahasiswa.kelas-forum', $kelas->id) }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-[#002B6B] px-6 py-3 text-sm font-semibold text-white hover:bg-blue-800 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Buka Forum
                </a>
            </div>
        </div>
    </div>

    {{-- Sidebar Container --}}
    <div class="sticky top-24 space-y-4">
        {{-- Progress Kelas Card --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <h3 class="mb-4 text-sm font-bold text-slate-800">Progress Kelas</h3>

            {{-- Penyelesaian --}}
            <div class="mb-1.5 flex items-center justify-between text-xs font-semibold">
                <span class="text-gray-500">Penyelesaian</span>
                <span class="font-bold text-[#002B6B]">{{ $progress }}%</span>
            </div>
            <div class="mb-5 h-2 w-full overflow-hidden rounded-full bg-gray-100">
                <div class="h-full rounded-full bg-[#002B6B] transition-all" style="width: {{ $progress }}%"></div>
            </div>

            {{-- Kehadiran --}}
            <div class="mb-1.5 flex items-center justify-between text-xs font-semibold">
                <span class="text-gray-500">Kehadiran</span>
                <span class="font-bold text-emerald-600">{{ $totalHadir }}/{{ $totalPertemuan }}</span>
            </div>
            <div class="mb-5 h-2 w-full overflow-hidden rounded-full bg-gray-100">
                @php
                    $persenHadir = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100) : 0;
                @endphp
                <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ $persenHadir }}%"></div>
            </div>

            {{-- Detail Informasi --}}
            <div class="space-y-2.5 border-t border-gray-100 pt-4">
                @foreach ([
                    ['label' => 'Tugas Selesai', 'value' => $submitted.'/'.$totalTugas], 
                    ['label' => 'Total Materi', 'value' => $materiList->count().' modul'], 
                    ['label' => 'Total Pertemuan', 'value' => $totalPertemuan.' kali']
                ] as $row)
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-gray-500">{{ $row['label'] }}</span>
                        <span class="font-semibold text-gray-800">{{ $row['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ringkasan Card --}}
        <div class="bg-gradient-to-br from-[#002B6B]/5 to-blue-50 rounded-2xl border border-blue-100 p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 rounded-lg bg-[#002B6B] flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14"/>
                    </svg>
                </div>
                <h3 class="font-bold text-blue-900 text-sm">Ringkasan</h3>
            </div>
            <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}" class="flex items-center justify-between text-sm font-bold text-blue-700 hover:text-blue-900 transition-colors duration-200 group">
                <span>Lihat Riwayat Lengkap</span>
                <svg class="w-4 h-4 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>
@endsection