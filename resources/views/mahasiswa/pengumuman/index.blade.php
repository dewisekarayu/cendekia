@extends('layouts.portal')

@section('title', 'Pengumuman')
@section('activeMenu', 'Pengumuman')

@section('content')

    {{-- HEADER SECTION --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Pengumuman</h1>
            <p class="text-sm font-medium text-slate-400 dark:text-slate-500 mt-0.5">Informasi terbaru dari administrator kampus dan dosen pengajar kelas Anda.</p>
        </div>
    </div>

    {{-- LIST CARD PENGUMUMAN --}}
    <div class="space-y-4">
        @forelse($pengumuman as $p)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition duration-150">
                <div class="flex items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-2">
                        @if($p->untuk_semua || is_null($p->kelas_perkuliahan_id))
                            <span class="text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 border border-amber-200/40 dark:border-amber-900/30 text-amber-600 dark:text-amber-300 px-2.5 py-0.5 rounded-md">Penting / Global</span>
                        @endif
                        @if($p->kelasPerkuliahan)
                            <span class="text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/30 text-[#002B6B] dark:text-blue-300 px-2.5 py-0.5 rounded-md">
                                Kelas: {{ $p->kelasPerkuliahan->kode_kelas }}
                            </span>
                        @endif
                    </div>
                </div>

                <h2 class="text-base font-bold text-slate-800 dark:text-white tracking-tight">{{ $p->judul }}</h2>
                <div x-data="{ expanded: false }" class="mt-2 text-sm text-slate-600 dark:text-slate-300 leading-relaxed relative">
                    <div :class="expanded ? 'whitespace-pre-line' : 'line-clamp-2 whitespace-normal'" class="transition-all duration-200">
                        {{ $p->isi }}
                    </div>
                    @if(strlen($p->isi) > 200)
                        <button @click="expanded = !expanded" class="text-[#002B6B] dark:text-blue-400 font-bold mt-2 hover:underline text-xs flex items-center gap-1 focus:outline-none">
                            <span x-text="expanded ? 'Tampilkan lebih sedikit' : 'Baca selengkapnya'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" :class="expanded ? 'rotate-180' : ''" class="h-3 w-3 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    @endif
                </div>

                <div class="flex flex-wrap items-center justify-between gap-2 mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 text-[11px] font-medium text-slate-400 dark:text-slate-500">
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Oleh: <strong class="text-slate-600 dark:text-slate-300 font-semibold">{{ $p->pembuat->name ?? 'Administrator' }}</strong>
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400 dark:text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Tanggal Terbit: <strong class="text-slate-600 dark:text-slate-300 font-semibold">{{ $p->created_at->format('d M Y') }}</strong>
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700 py-12 text-center text-slate-400 dark:text-slate-500 text-sm font-semibold shadow-sm">
                Tidak ada pengumuman saat ini.
            </div>
        @endforelse

        <div class="mt-6">
            {{ $pengumuman->links() }}
        </div>
    </div>

@endsection
