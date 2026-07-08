@extends('layouts.portal')
@section('title', 'Jelajahi Kelas')
@section('content')

<x-flash-message />

<div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-500 px-6 py-6 sm:px-8 shadow-lg shadow-emerald-900/15 relative">
    <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/10"></div>
    <div class="relative z-10">
        <p class="text-xs font-bold uppercase tracking-widest text-emerald-100/80">Bergabung Kelas Baru</p>
        <h1 class="mt-1 text-xl font-extrabold text-white sm:text-2xl">Jelajahi Kelas</h1>
        <p class="mt-1 text-sm text-emerald-100/80">
            Kelas aktif untuk
            <span class="font-semibold text-white">{{ auth()->user()->programStudi?->nama_prodi ?? '-' }}</span>
            semester ini.
        </p>
    </div>
</div>

@if ($kelasList->isEmpty())
    <div class="flex min-h-[280px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white p-10 text-center shadow-sm">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
        </div>
        <h3 class="font-bold text-gray-700">Tidak Ada Kelas Tersedia</h3>
        <p class="mt-1 max-w-sm text-xs text-gray-400 leading-relaxed">
            Kamu mungkin sudah bergabung ke semua kelas aktif di prodimu, atau belum ada kelas baru untuk semester ini.
        </p>
        <a href="{{ route('mahasiswa.kelas-saya') }}" class="mt-5 inline-flex items-center gap-1.5 rounded-xl bg-[#002B6B] px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition">
            Lihat Kelas Saya
        </a>
    </div>
@else
    @php
        $colors = [
            ['top' => 'bg-blue-500',   'badge' => 'bg-blue-50 text-blue-700',   'btn' => 'bg-blue-600 hover:bg-blue-700'],
            ['top' => 'bg-emerald-500','badge' => 'bg-emerald-50 text-emerald-700','btn' => 'bg-emerald-600 hover:bg-emerald-700'],
            ['top' => 'bg-violet-500', 'badge' => 'bg-violet-50 text-violet-700', 'btn' => 'bg-violet-600 hover:bg-violet-700'],
            ['top' => 'bg-amber-500',  'badge' => 'bg-amber-50 text-amber-700',  'btn' => 'bg-amber-600 hover:bg-amber-700'],
            ['top' => 'bg-rose-500',   'badge' => 'bg-rose-50 text-rose-700',    'btn' => 'bg-rose-600 hover:bg-rose-700'],
            ['top' => 'bg-cyan-500',   'badge' => 'bg-cyan-50 text-cyan-700',    'btn' => 'bg-cyan-600 hover:bg-cyan-700'],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($kelasList as $i => $kelas)
            @php $c = $colors[$i % count($colors)]; @endphp
            <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-900/8">
                <div class="h-1.5 w-full {{ $c['top'] }}"></div>
                <div class="flex flex-1 flex-col p-5">
                    <div class="mb-3 flex items-start justify-between gap-2">
                        <span class="rounded-lg {{ $c['badge'] }} px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide">
                            {{ $kelas->mataKuliah?->kode_mk ?? '-' }}
                        </span>
                        <span class="shrink-0 text-[10px] font-semibold text-gray-500">{{ $kelas->mataKuliah?->sks ?? 0 }} SKS</span>
                    </div>

                    <h3 class="text-sm font-bold text-slate-800 leading-snug line-clamp-2 min-h-[40px]">
                        {{ $kelas->mataKuliah?->nama_mk ?? '-' }}
                    </h3>

                    <div class="mt-3 space-y-2 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="truncate">{{ $kelas->dosen?->name ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $kelas->hari }}, {{ substr($kelas->jam_mulai,0,5) }} – {{ substr($kelas->jam_selesai,0,5) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $kelas->ruangan ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="mt-auto pt-4">
                        <form method="POST" action="{{ route('mahasiswa.kelas.join', $kelas->id) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full rounded-xl {{ $c['btn'] }} py-2.5 text-xs font-bold text-white shadow-sm transition active:scale-95">
                                + Daftar Kelas Ini
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection
