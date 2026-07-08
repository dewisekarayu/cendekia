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
<div class="relative mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-[#002B6B] via-[#003d99] to-[#0052cc] px-6 py-6 sm:px-8 sm:py-8 shadow-lg shadow-blue-950/20">
    {{-- decorative circles --}}
    <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
    <div class="pointer-events-none absolute -bottom-12 right-24 h-36 w-36 rounded-full bg-white/5"></div>
    <div class="pointer-events-none absolute bottom-4 right-6 h-16 w-16 rounded-full bg-white/10"></div>

    <div class="relative z-10 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-blue-200/80">{{ $greeting }}, Mahasiswa</p>
            <h1 class="mt-2 text-2xl font-extrabold leading-tight text-white sm:text-3xl">{{ $firstName }} 👋</h1>
            <p class="mt-2 max-w-lg text-sm leading-relaxed text-blue-100/80">
                @if ($deadlines->count() > 0)
                    Kamu punya <span class="font-bold text-white">{{ $deadlines->count() }} tugas mendatang</span>. Jangan sampai terlewat ya!
                @else
                    Tidak ada deadline mendesak. Tetap semangat belajar hari ini!
                @endif
            </p>
        </div>

        <div class="grid grid-cols-3 gap-3 lg:w-80 shrink-0">
            <div class="rounded-xl border border-white/15 bg-white/10 p-3 text-center backdrop-blur-sm">
                <p class="text-lg font-extrabold text-white">{{ $courses->count() }}</p>
                <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-200/70">Kelas</p>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-3 text-center backdrop-blur-sm">
                <p class="text-lg font-extrabold text-white">{{ $deadlines->count() }}</p>
                <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-200/70">Deadline</p>
            </div>
            <div class="rounded-xl border border-white/15 bg-white/10 p-3 text-center backdrop-blur-sm">
                <p class="text-lg font-extrabold text-white">{{ $announcements->count() }}</p>
                <p class="mt-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-200/70">Pengumuman</p>
            </div>
        </div>
    </div>
</div>

@if (!auth()->user()->program_studi_id)
    <div class="mb-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700 shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <span>Program studi kamu belum ditentukan. Hubungi Admin akademik untuk penempatan program studi.</span>
    </div>
@endif

{{-- ===== MAIN GRID ===== --}}
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    {{-- LEFT: Courses + Announcements --}}
    <div class="space-y-6 lg:col-span-2 min-w-0">

        {{-- Kelas Aktif --}}
        <div>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">Kelas Aktif</h2>
                <a href="{{ route('mahasiswa.kelas-saya') }}" class="inline-flex items-center gap-1 text-xs font-semibold text-[#002B6B] hover:text-blue-700 transition">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @if ($courses->isEmpty())
                <div class="flex min-h-[240px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white p-8 text-center shadow-sm">
                    <div class="mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-700">Belum Ada Kelas</h3>
                    <p class="mt-1 max-w-xs text-xs text-gray-400 leading-relaxed">Kamu belum terdaftar di kelas manapun. Mulai jelajahi kelas aktif sekarang.</p>
                    <a href="{{ route('mahasiswa.jelajahi-kelas') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-[#002B6B] px-4 py-2 text-xs font-semibold text-white hover:bg-blue-800 transition">
                        Jelajahi Kelas
                    </a>
                </div>
            @else
                @php
                    $cardColors = [
                        ['top' => 'bg-blue-500', 'icon_bg' => 'bg-blue-50', 'icon_text' => 'text-blue-600', 'bar' => 'bg-blue-500'],
                        ['top' => 'bg-violet-500', 'icon_bg' => 'bg-violet-50', 'icon_text' => 'text-violet-600', 'bar' => 'bg-violet-500'],
                        ['top' => 'bg-emerald-500', 'icon_bg' => 'bg-emerald-50', 'icon_text' => 'text-emerald-600', 'bar' => 'bg-emerald-500'],
                        ['top' => 'bg-amber-500', 'icon_bg' => 'bg-amber-50', 'icon_text' => 'text-amber-600', 'bar' => 'bg-amber-500'],
                    ];
                @endphp
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @foreach ($courses as $i => $kelas)
                        @php $cc = $cardColors[$i % count($cardColors)]; @endphp
                        <div class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/8">
                            <div class="h-1.5 w-full {{ $cc['top'] }}"></div>
                            <div class="flex flex-1 flex-col p-5">
                                <div class="mb-3 flex items-start justify-between gap-2">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $cc['icon_bg'] }} {{ $cc['icon_text'] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-emerald-600">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                        Aktif
                                    </span>
                                </div>

                                <h3 class="min-h-[40px] line-clamp-2 text-sm font-bold leading-snug text-slate-800 group-hover:text-[#002B6B] transition" title="{{ $kelas['title'] }}">
                                    {{ $kelas['title'] }}
                                </h3>
                                <p class="mt-0.5 truncate text-[11px] font-medium text-gray-400">{{ $kelas['tag'] }}</p>

                                <div class="mt-3 flex items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($kelas['dosen']) }}&background=EEF4FF&color=002B6B&size=56" class="h-6 w-6 rounded-full border border-gray-200" alt="">
                                    <p class="truncate text-xs font-semibold text-gray-500">{{ $kelas['dosen'] }}</p>
                                </div>

                                <div class="mt-4 flex-1">
                                    <div class="mb-1.5 flex items-center justify-between text-[11px] font-semibold">
                                        <span class="text-gray-400">Progress</span>
                                        <span class="{{ $cc['icon_text'] }} font-bold">{{ $kelas['progress'] }}%</span>
                                    </div>
                                    <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100">
                                        <div class="h-full rounded-full {{ $cc['bar'] }} transition-all duration-500" style="width: {{ $kelas['progress'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="px-5 pb-5">
                                <a href="{{ route('mahasiswa.kelas-detail', $kelas['id']) }}"
                                   class="block w-full rounded-xl {{ $cc['top'] }} py-2.5 text-center text-xs font-bold text-white shadow-sm hover:opacity-90 transition">
                                    Masuk Kelas →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Pengumuman --}}
        <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center gap-2">
                <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-[#002B6B]/10 text-[#002B6B]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <h2 class="text-base font-bold text-slate-800">Pengumuman Terbaru</h2>
            </div>

            @if ($announcements->isEmpty())
                <div class="py-6 text-center text-sm text-gray-400">Belum ada pengumuman terbaru.</div>
            @else
                <div class="space-y-4">
                    @foreach ($announcements as $i => $item)
                        <div class="flex gap-3">
                            <div class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $i === 0 ? 'bg-[#002B6B]' : 'bg-gray-300' }}"></div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-baseline justify-between gap-2">
                                    <p class="line-clamp-1 text-sm font-semibold text-gray-800">{{ $item->judul }}</p>
                                    <span class="shrink-0 text-[11px] text-gray-400">{{ $item->created_at?->diffForHumans(null, true, true) }}</span>
                                </div>
                                <p class="mt-0.5 line-clamp-2 text-xs leading-relaxed text-gray-500">{{ Str::limit($item->isi ?? '', 120) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- RIGHT: Deadlines --}}
    <div class="min-w-0">
        <div class="sticky top-24 rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-800">Upcoming Deadlines</h2>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>

            @if ($deadlines->isEmpty())
                <div class="py-8 text-center">
                    <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-gray-600">Semua Beres!</p>
                    <p class="mt-1 text-xs text-gray-400">Tidak ada tugas mendatang.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($deadlines as $tugas)
                        @php
                            $deadline = \Carbon\Carbon::parse($tugas->deadline);
                            $daysLeft = now()->diffInDays($deadline, false);
                            $isUrgent = $daysLeft <= 2;
                        @endphp
                        <div class="flex items-start gap-3 rounded-xl p-2 -mx-2 hover:bg-gray-50 transition">
                            <div class="shrink-0 flex h-11 w-10 flex-col items-center justify-center rounded-xl {{ $isUrgent ? 'bg-rose-50 border border-rose-100 text-rose-700' : 'bg-slate-50 border border-slate-100 text-slate-600' }}">
                                <span class="text-base font-extrabold leading-none">{{ $deadline->format('d') }}</span>
                                <span class="text-[9px] font-bold uppercase mt-0.5">{{ $deadline->format('M') }}</span>
                            </div>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <p class="truncate text-sm font-semibold text-gray-800" title="{{ $tugas->judul }}">{{ $tugas->judul }}</p>
                                <p class="mt-0.5 truncate text-[11px] text-gray-400">{{ $tugas->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}</p>
                                @if ($isUrgent)
                                    <span class="mt-1 inline-block rounded text-[10px] font-bold text-rose-600">
                                        {{ $daysLeft === 0 ? 'Hari ini!' : $daysLeft . ' hari lagi' }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('mahasiswa.kelas-saya') }}" class="mt-4 flex w-full items-center justify-center gap-1.5 rounded-xl border border-gray-200 py-2 text-xs font-semibold text-gray-500 hover:border-[#002B6B] hover:text-[#002B6B] transition">
                    Lihat semua kelas
                </a>
            @endif
        </div>
    </div>

</div>
@endsection
