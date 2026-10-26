@extends('layouts.portal')
@section('title', 'Dashboard')
@section('activeMenu', 'Dashboard')
@section('content')

@php
    $firstName = explode(' ', auth()->user()->name)[0];
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
@endphp

<x-flash-message />

{{-- ===== HERO BANNER ===== --}}
<div class="relative mb-5 sm:mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-[#c0e6ff] via-[#d7efff] to-[#e8f5ff] dark:from-slate-900 dark:via-blue-950 dark:to-indigo-950 border border-[#a3daf8]/80 dark:border-blue-900/40 px-5 py-6 sm:px-8 sm:py-8 shadow-sm text-[#002B6B] dark:text-white">
    {{-- decorative soft ambient blurs --}}
    <div class="pointer-events-none absolute -right-12 -top-12 h-60 w-60 rounded-full bg-white/40 dark:bg-blue-300/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-10 right-28 h-48 w-48 rounded-full bg-white/40 dark:bg-indigo-300/10 blur-2xl"></div>

    <div class="relative z-10 flex flex-col gap-5 sm:gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="min-w-0 flex-1">
            <p class="text-[11px] sm:text-xs font-bold uppercase tracking-widest text-[#002B6B]/75 dark:text-blue-200/90">{{ $greeting }}, Mahasiswa</p>
            <h1 class="mt-1.5 sm:mt-2 text-2xl font-extrabold leading-tight text-[#002B6B] dark:text-white break-words sm:text-3xl">{{ $firstName }}</h1>
            <p class="mt-2 max-w-lg text-xs sm:text-sm leading-relaxed text-[#002B6B]/80 dark:text-blue-100/90">
                @if ($deadlines->count() > 0)
                    Kamu punya <span class="font-bold text-[#002B6B] dark:text-white underline decoration-blue-400/50 underline-offset-2">{{ $deadlines->count() }} tugas mendatang</span>. Jangan sampai terlewat ya!
                @else
                    Tidak ada deadline mendesak hari ini. Tetap semangat menggapai prestasi terbaik!
                @endif
            </p>
        </div>

        <div class="grid w-full grid-cols-3 gap-2.5 sm:gap-3.5 sm:max-w-md lg:w-80 lg:max-w-none shrink-0">
            <div class="min-w-0 rounded-2xl border border-[#002B6B]/15 dark:border-white/20 bg-white/80 dark:bg-white/10 p-3 text-center backdrop-blur-md shadow-2xs">
                <p class="text-lg sm:text-xl font-extrabold text-[#002B6B] dark:text-white">{{ $courses->count() }}</p>
                <p class="mt-0.5 truncate text-[10px] font-bold uppercase tracking-wide text-[#002B6B]/70 dark:text-blue-200/80">Kelas</p>
            </div>
            <div class="min-w-0 rounded-2xl border border-[#002B6B]/15 dark:border-white/20 bg-white/80 dark:bg-white/10 p-3 text-center backdrop-blur-md shadow-2xs">
                <p class="text-lg sm:text-xl font-extrabold text-[#002B6B] dark:text-white">{{ $deadlines->count() }}</p>
                <p class="mt-0.5 truncate text-[10px] font-bold uppercase tracking-wide text-[#002B6B]/70 dark:text-blue-200/80">Deadline</p>
            </div>
            <div class="min-w-0 rounded-2xl border border-[#002B6B]/15 dark:border-white/20 bg-white/80 dark:bg-white/10 p-3 text-center backdrop-blur-md shadow-2xs">
                <p class="text-lg sm:text-xl font-extrabold text-[#002B6B] dark:text-white">{{ $announcements->count() }}</p>
                <p class="mt-0.5 truncate text-[10px] font-bold uppercase tracking-wide text-[#002B6B]/70 dark:text-blue-200/80">Info</p>
            </div>
        </div>
    </div>
</div>

@if (!auth()->user()->program_studi_id)
    <div class="mb-5 flex items-start gap-3 rounded-2xl border border-amber-200 dark:border-amber-900/60 bg-amber-50 dark:bg-amber-950/30 px-4 py-3.5 text-xs sm:text-sm text-amber-800 dark:text-amber-300 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span>Program studi kamu belum ditentukan. Hubungi Admin akademik untuk penempatan program studi.</span>
    </div>
@endif

{{-- ===== MAIN GRID (2/3 Left + 1/3 Right on lg screen) ===== --}}
<div class="grid grid-cols-1 gap-5 sm:gap-6 lg:grid-cols-3">

    {{-- LEFT: Courses + Announcements --}}
    <div class="space-y-5 sm:space-y-6 lg:col-span-2 min-w-0">

        {{-- Kelas Aktif --}}
        <div>
            <div class="mb-3.5 flex items-center justify-between gap-3">
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Kelas Aktif</h2>
                <a href="{{ route('mahasiswa.kelas-saya') }}" class="inline-flex shrink-0 items-center gap-1 text-xs font-bold text-[#002B6B] dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($courses->isEmpty())
                <div class="flex min-h-[220px] sm:min-h-[240px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 sm:p-8 text-center shadow-sm">
                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 dark:bg-slate-700 border border-gray-100 dark:border-slate-600 text-gray-300 dark:text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-700 dark:text-slate-200">Belum Ada Kelas</h3>
                    <p class="mt-1 max-w-xs text-xs text-gray-400 dark:text-slate-400 leading-relaxed">Kamu belum terdaftar di kelas manapun. Silakan tunggu Admin akademik untuk memasukkanmu ke dalam kelas.</p>
                </div>
            @else
                @php
                    $cardColors = [
                        ['top' => 'bg-[#c0e6ff]', 'icon_bg' => 'bg-blue-50 dark:bg-blue-950/60', 'icon_text' => 'text-[#002B6B] dark:text-blue-300', 'bar' => 'bg-sky-400 dark:bg-blue-500', 'btn' => 'bg-[#c0e6ff] text-[#002B6B] border border-[#002B6B]/20 hover:bg-[#a8daf8]'],
                        ['top' => 'bg-sky-300',    'icon_bg' => 'bg-sky-50 dark:bg-sky-950/60',   'icon_text' => 'text-sky-700 dark:text-sky-300',   'bar' => 'bg-sky-500 dark:bg-sky-500',   'btn' => 'bg-sky-50 text-sky-800 border border-sky-200 hover:bg-sky-100'],
                        ['top' => 'bg-blue-300',   'icon_bg' => 'bg-blue-50 dark:bg-blue-950/60', 'icon_text' => 'text-blue-700 dark:text-blue-300', 'bar' => 'bg-blue-500 dark:bg-blue-500', 'btn' => 'bg-blue-50 text-blue-800 border border-blue-200 hover:bg-blue-100'],
                        ['top' => 'bg-cyan-300',   'icon_bg' => 'bg-cyan-50 dark:bg-cyan-950/60', 'icon_text' => 'text-cyan-700 dark:text-cyan-300', 'bar' => 'bg-cyan-500 dark:bg-cyan-500', 'btn' => 'bg-cyan-50 text-cyan-800 border border-cyan-200 hover:bg-cyan-100'],
                    ];
                @endphp
                <div class="grid grid-cols-1 gap-3.5 sm:gap-4 sm:grid-cols-2">
                    @foreach ($courses as $i => $kelas)
                        @php $cc = $cardColors[$i % count($cardColors)]; @endphp
                        <div class="group flex min-w-0 flex-col overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/8">
                            <div class="h-1.5 w-full {{ $cc['top'] }}"></div>
                            <div class="flex flex-1 flex-col p-4 sm:p-5">
                                <div class="mb-3 flex items-start justify-between gap-2">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $cc['icon_bg'] }} {{ $cc['icon_text'] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 dark:bg-blue-950/40 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-blue-700 dark:text-blue-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-blue-500 inline-block"></span>
                                        Aktif
                                    </span>
                                </div>

                                <h3 class="min-h-[2.5rem] line-clamp-2 text-sm font-bold leading-snug text-slate-800 dark:text-white group-hover:text-[#002B6B] dark:group-hover:text-blue-400 transition" title="{{ $kelas['title'] }}">
                                    {{ $kelas['title'] }}
                                </h3>
                                <p class="mt-0.5 truncate text-[11px] font-medium text-gray-400 dark:text-slate-400">{{ $kelas['tag'] }}</p>

                                <div class="mt-3.5 flex min-w-0 items-center gap-2">
                                    <div class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/60 text-[#002B6B] dark:text-blue-300 text-[10px] font-bold">
                                        {{ strtoupper(substr($kelas['dosen'] ?? 'D', 0, 1)) }}
                                    </div>
                                    <p class="truncate text-xs font-semibold text-gray-600 dark:text-slate-300">{{ $kelas['dosen'] }}</p>
                                </div>

                                <div class="mt-4 flex-1">
                                    <div class="mb-1.5 flex items-center justify-between text-[11px] font-semibold">
                                        <span class="text-gray-400 dark:text-slate-400">Progress</span>
                                        <span class="{{ $cc['icon_text'] }} font-bold">{{ $kelas['progress'] }}%</span>
                                    </div>
                                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-slate-700">
                                        <div class="h-full rounded-full {{ $cc['bar'] }} transition-all duration-500" style="width: {{ $kelas['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 pb-4 sm:px-5 sm:pb-5">
                                <a href="{{ route('mahasiswa.kelas-detail', $kelas['id']) }}"
                                   class="block w-full rounded-xl {{ $cc['btn'] }} py-2.5 text-center text-xs font-bold shadow-2xs hover:opacity-90 active:scale-[0.99] transition">
                                    Masuk Kelas →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pengumuman Terbaru --}}
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-4 sm:p-5 shadow-sm transition-colors duration-200">
            <div class="mb-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex min-w-0 items-center gap-2">
                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-[#c0e6ff] text-[#002B6B]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white">Pengumuman Terbaru</h2>
                </div>
                <a href="{{ route('mahasiswa.pengumuman.index') }}" class="self-start sm:self-auto shrink-0 rounded-lg border border-[#002B6B]/20 bg-[#c0e6ff]/50 dark:border-blue-400/30 px-3 py-1.5 text-[11px] font-bold text-[#002B6B] dark:text-blue-300 transition hover:bg-[#c0e6ff] dark:hover:bg-blue-400/10">
                    Tampilkan Semua Pengumuman
                </a>
            </div>

            @if ($announcements->isEmpty())
                <div class="py-6 text-center text-xs text-gray-400 dark:text-slate-500">Belum ada pengumuman terbaru.</div>
            @else
                <div class="space-y-3.5">
                    @foreach ($announcements as $i => $item)
                        <div class="flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/30 transition">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $i === 0 ? 'bg-[#002B6B] dark:bg-blue-400' : 'bg-gray-300 dark:bg-slate-600' }}"></div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-baseline justify-between gap-2">
                                    <p class="min-w-0 line-clamp-1 text-sm font-semibold text-slate-800 dark:text-slate-200 break-words">{{ $item->judul }}</p>
                                    <span class="shrink-0 whitespace-nowrap text-[11px] text-gray-400 dark:text-slate-500">{{ $item->created_at?->diffForHumans(null, true, true) }}</span>
                                </div>
                                <p class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-gray-500 dark:text-slate-400 break-words">{{ Str::limit($item->isi ?? '', 120) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- RIGHT: Upcoming Deadlines (Sticky on Desktop) --}}
    <div class="min-w-0">
        <div class="lg:sticky lg:top-24 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-4 sm:p-5 shadow-sm transition-colors duration-200">
            <div class="mb-3.5 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800 dark:text-white">Upcoming Deadlines</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-500 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>

            @if ($deadlines->isEmpty())
                <div class="py-7 text-center">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-200">Semua Beres!</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-slate-400">Tidak ada tugas mendatang.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100 dark:divide-slate-700/60">
                    @foreach ($deadlines as $tugas)
                        @php
                            $deadline = \Carbon\Carbon::parse($tugas->deadline);
                            $daysLeft = (int) floor(now()->diffInDays($deadline, false));
                            $isUrgent = $daysLeft <= 2;
                        @endphp
                        <a href="{{ route('mahasiswa.pengumpulan-tugas.show', $tugas->id) }}"
                           class="flex min-w-0 items-start gap-3 py-3 px-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/40 transition group">
                            <div class="shrink-0 flex h-11 w-11 flex-col items-center justify-center rounded-xl {{ $isUrgent ? 'bg-rose-50 dark:bg-rose-950/40 border border-rose-200/60 dark:border-rose-800 text-rose-700 dark:text-rose-300' : 'bg-slate-50 dark:bg-slate-900 border border-slate-200/60 dark:border-slate-700 text-slate-700 dark:text-slate-300' }}">
                                <span class="text-base font-extrabold leading-none">{{ $deadline->format('d') }}</span>
                                <span class="text-[9px] font-bold uppercase mt-0.5">{{ $deadline->format('M') }}</span>
                            </div>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <p class="truncate text-sm font-semibold text-slate-800 dark:text-slate-200 group-hover:text-[#002B6B] dark:group-hover:text-blue-400 transition" title="{{ $tugas->judul }}">{{ $tugas->judul }}</p>
                                <p class="mt-0.5 truncate text-[11px] text-gray-400 dark:text-slate-400">{{ $tugas->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}</p>
                                @if ($isUrgent)
                                    <span class="mt-1 inline-block rounded text-[10px] font-bold text-rose-600 dark:text-rose-400">
                                        {{ $daysLeft === 0 ? 'Hari ini!' : $daysLeft . ' hari lagi' }}
                                    </span>
                                @else
                                    <span class="mt-1 inline-block rounded text-[10px] font-semibold text-slate-400 dark:text-slate-500">
                                        {{ $deadline->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <a href="{{ route('mahasiswa.kelas-saya') }}" class="mt-4 flex w-full items-center justify-center gap-1.5 rounded-xl border border-gray-200 dark:border-slate-700 py-2.5 text-xs font-bold text-gray-600 dark:text-slate-300 hover:border-[#002B6B] hover:text-[#002B6B] dark:hover:border-blue-400 dark:hover:text-blue-300 transition">
                    Lihat semua kelas
                </a>
            @endif
        </div>
    </div>

</div>
@endsection