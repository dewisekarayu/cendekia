@extends('layouts.admin')

@section('title', 'Detail Agenda: ' . $kalenderAkademik->judul)
@section('activeMenu', 'Kalender Akademik')

@section('content')
<div class="space-y-6" x-data="{ deleteModalOpen: false }">
    
    {{-- Page Header & Breadcrumb --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-[#002B6B] dark:text-blue-400 border border-blue-100 dark:border-blue-800/60">
                    <i class="bi bi-calendar-event text-xs"></i>
                    Informasi Detail Agenda
                </span>
            </div>
            <h1 class="page-title mb-1 text-2xl sm:text-3xl font-extrabold text-[#002B6B] dark:text-white">
                {{ $kalenderAkademik->judul }}
            </h1>
            <nav style="--bs-breadcrumb-divider: '›';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.kalender-akademik.index') }}">Kalender Akademik</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Agenda</li>
                </ol>
            </nav>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.kalender-akademik.index') }}" 
               class="btn btn-light border bg-white dark:bg-slate-800 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 d-inline-flex align-items-center gap-2 px-3.5 py-2 text-sm font-semibold shadow-sm"
               style="border-radius: 0.75rem;">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
            <a href="{{ route('admin.kalender-akademik.edit', $kalenderAkademik) }}" 
               class="btn btn-primary d-inline-flex align-items-center gap-2 px-3.5 py-2 text-sm font-semibold shadow-sm">
                <i class="bi bi-pencil-square"></i>
                <span>Edit Agenda</span>
            </a>
            <button type="button" @click="deleteModalOpen = true"
                    class="btn btn-danger bg-red-600 hover:bg-red-700 border-0 text-white d-inline-flex align-items-center gap-2 px-3.5 py-2 text-sm font-semibold shadow-sm"
                    style="border-radius: 0.75rem;">
                <i class="bi bi-trash"></i>
                <span>Hapus</span>
            </button>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left Column: Agenda Information (8 Cols) --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Main Info Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white m-0 flex items-center gap-2">
                        <i class="bi bi-info-circle text-blue-600 dark:text-blue-400"></i>
                        Spesifikasi Kegiatan
                    </h2>
                    <div class="flex items-center gap-2">
                        <span class="badge-status" style="background-color: {{ $kalenderAkademik->is_published ? '#ecfdf5' : '#f1f5f9' }}; color: {{ $kalenderAkademik->is_published ? '#059669' : '#64748b' }};">
                            <span class="status-dot" style="background-color: {{ $kalenderAkademik->is_published ? '#10b981' : '#94a3b8' }};"></span>
                            {{ $kalenderAkademik->is_published ? 'Publik' : 'Draft' }}
                        </span>
                    </div>
                </div>

                <div class="p-5 sm:p-7 space-y-6">
                    
                    {{-- Badges Strip --}}
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold text-white shadow-sm"
                              style="background-color: {{ $kalenderAkademik->warna }};">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            {{ $kalenderAkademik->jenis_kegiatan_label }}
                        </span>
                        <span class="badge-code">
                            {{ $kalenderAkademik->semester->tahun_ajaran }} – {{ $kalenderAkademik->semester->nama_semester }}
                        </span>
                    </div>

                    {{-- Data Matrix --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-700/60">
                        <div>
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Tanggal Pelaksanaan</span>
                            <div class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <i class="bi bi-calendar-event text-blue-600 dark:text-blue-400"></i>
                                <span>
                                    {{ $kalenderAkademik->tanggal_mulai->translatedFormat('d F Y') }}
                                    @if($kalenderAkademik->tanggal_selesai && !$kalenderAkademik->tanggal_mulai->eq($kalenderAkademik->tanggal_selesai))
                                        – {{ $kalenderAkademik->tanggal_selesai->translatedFormat('d F Y') }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div>
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Waktu / Jam</span>
                            <div class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <i class="bi bi-clock text-blue-600 dark:text-blue-400"></i>
                                <span>
                                    @if($kalenderAkademik->is_all_day)
                                        Sepanjang Hari (All Day)
                                    @else
                                        {{ substr($kalenderAkademik->waktu_mulai, 0, 5) }}
                                        @if($kalenderAkademik->waktu_selesai)
                                            – {{ substr($kalenderAkademik->waktu_selesai, 0, 5) }}
                                        @endif
                                        WIB
                                    @endif
                                </span>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-1">Lokasi / Ruangan</span>
                            <div class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                <i class="bi bi-geo-alt text-red-500"></i>
                                <span>{{ $kalenderAkademik->lokasi ?: 'Tidak ada keterangan lokasi (Daring/Tentatif)' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    @if($kalenderAkademik->deskripsi)
                        <div>
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider block mb-2">Deskripsi & Petunjuk</span>
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/70 dark:border-slate-700 text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-wrap">
                                {{ $kalenderAkademik->deskripsi }}
                            </div>
                        </div>
                    @endif

                    {{-- Catatan Internal --}}
                    @if($kalenderAkademik->catatan)
                        <div>
                            <span class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider block mb-2">Catatan Khusus Administrator</span>
                            <div class="p-4 rounded-xl bg-amber-50/70 dark:bg-amber-950/30 border border-amber-200/70 dark:border-amber-800/50 text-sm text-amber-900 dark:text-amber-200 leading-relaxed whitespace-pre-wrap">
                                {{ $kalenderAkademik->catatan }}
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Activity Logs --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-900 dark:text-white m-0 flex items-center gap-2">
                        <i class="bi bi-clock-history text-blue-600 dark:text-blue-400"></i>
                        Riwayat Aktivitas & Perubahan
                    </h2>
                    <span class="badge-code">{{ $kalenderAkademik->aktivitasLogs->count() }} log</span>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-700/60">
                    @forelse($kalenderAkademik->aktivitasLogs as $log)
                        <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 text-white font-bold text-xs"
                                 style="background-color: {{ $log->event_color ?? '#002B6B' }};">
                                <i class="bi bi-activity"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-bold uppercase tracking-wider" style="color: {{ $log->event_color ?? '#002B6B' }};">
                                        {{ $log->event_label }}
                                    </span>
                                    <span class="text-xs text-slate-400">·</span>
                                    <span class="text-xs text-slate-400">{{ $log->occurred_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5 mb-1">{{ $log->description }}</p>
                                @if($log->user)
                                    <span class="text-[11px] text-slate-400 flex items-center gap-1">
                                        <i class="bi bi-person"></i> Oleh: {{ $log->user->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400 text-sm">
                            Belum ada riwayat perubahan tercatat
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Right Column: Metadata & Details (4 Cols) --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Meta Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm p-5 space-y-4">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider m-0">
                    Informasi Entri
                </h3>

                <div class="space-y-3 pt-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Dibuat Oleh:</span>
                        <span class="font-bold text-slate-800 dark:text-white">{{ $kalenderAkademik->creator?->name ?? 'Administrator' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Waktu Dibuat:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $kalenderAkademik->created_at->translatedFormat('d F Y, H:i') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-slate-400">Terakhir Diperbarui:</span>
                        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $kalenderAkademik->updated_at->translatedFormat('d F Y, H:i') }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60">
                    <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider block mb-2">Penanda Warna Kalender</span>
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-100 dark:border-slate-700">
                        <div class="w-8 h-8 rounded-lg shadow-sm border border-black/10" style="background-color: {{ $kalenderAkademik->warna }};"></div>
                        <div>
                            <div class="font-bold font-mono text-xs text-slate-800 dark:text-white">{{ $kalenderAkademik->warna }}</div>
                            <span class="text-[11px] text-slate-400">HEX Code</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- Delete Modal --}}
    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="deleteModalOpen = false"></div>
        <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full z-10 p-6 space-y-4 border border-slate-200 dark:border-slate-700"
             x-show="deleteModalOpen"
             x-transition>
            <div class="w-12 h-12 rounded-2xl bg-red-50 dark:bg-red-950/50 text-red-600 dark:text-red-400 flex items-center justify-center text-2xl mx-auto">
                <i class="bi bi-trash"></i>
            </div>
            <div class="text-center">
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-1">Hapus Agenda Ini?</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Agenda <strong>"{{ $kalenderAkademik->judul }}"</strong> akan dihapus permanen dari kalender akademik seluruh civitas.
                </p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="deleteModalOpen = false"
                        class="btn btn-light border flex-1 py-2 text-sm font-semibold rounded-xl text-slate-700 dark:text-slate-200">
                    Batal
                </button>
                <form action="{{ route('admin.kalender-akademik.destroy', $kalenderAkademik) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full py-2 text-sm font-semibold rounded-xl bg-red-600 hover:bg-red-700 text-white border-0 shadow-sm">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
