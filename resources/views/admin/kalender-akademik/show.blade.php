@extends('layouts.portal')
@section('title', 'Detail Agenda: ' . $kalenderAkademik->judul)
@section('activeMenu', 'Kalender Akademik')
@section('content')

<div class="space-y-6 max-w-5xl mx-auto p-3 sm:p-4">
    {{-- Header --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-4">
        <a href="{{ route('admin.kalender-akademik.index') }}" class="hover:text-gray-900 dark:hover:text-white transition-colors">Kalender Akademik</a>
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        <span class="text-gray-900 dark:text-white font-medium">Detail Agenda</span>
    </div>

    {{-- Breadcrumb & Title --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $kalenderAkademik->judul }}</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Lihat detail lengkap dan riwayat perubahan agenda</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('admin.kalender-akademik.edit', $kalenderAkademik) }}" class="px-4 py-2.5 rounded-xl font-medium text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </a>
            <button @click="confirmDelete()"
                    class="px-4 py-2.5 rounded-xl font-medium text-sm text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors flex items-center gap-2 border border-red-200 dark:border-red-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Hapus
            </button>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Utama (Info) --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Info Card --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Informasi Agenda
                    </h3>
                </div>

                <div class="p-6 space-y-5">
                    {{-- Badges Status --}}
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold"
                              style="background-color: {{ $kalenderAkademik->warna }}20; color: {{ $kalenderAkademik->warna }}">
                            {{ $kalenderAkademik->jenis_kegiatan_label }}
                        </span>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold"
                              style="background-color: {{ $kalenderAkademik->status_badge_admin['bg'] }}; color: {{ $kalenderAkademik->status_badge_admin['color'] }}">
                            {{ $kalenderAkademik->status_badge_admin['label'] }}
                        </span>
                        <span class="px-3 py-1.5 rounded-full text-xs font-bold {{ $kalenderAkademik->is_published ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300' }}">
                            {{ $kalenderAkademik->is_published ? 'Dipublikasikan' : 'Draft' }}
                        </span>
                    </div>

                    {{-- Informasi Dasar --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1.5">Semester</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kalenderAkademik->semester->tahun_ajaran }} – {{ $kalenderAkademik->semester->nama_semester }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1.5">Lokasi</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-1">
                                @if($kalenderAkademik->lokasi)
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $kalenderAkademik->lokasi }}
                                @else
                                    <span class="text-gray-500">—</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Tanggal & Waktu --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-3 border-t border-gray-100 dark:border-slate-700">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1.5">Tanggal Mulai</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kalenderAkademik->tanggal_mulai->translatedFormat('l, d F Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1.5">Tanggal Selesai</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                @if($kalenderAkademik->tanggal_selesai && !$kalenderAkademik->tanggal_mulai->eq($kalenderAkademik->tanggal_selesai))
                                    {{ $kalenderAkademik->tanggal_selesai->translatedFormat('l, d F Y') }}
                                @else
                                    <span class="text-gray-500">Sama dengan tanggal mulai (1 hari)</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-1.5">Waktu Pelaksanaan</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @if($kalenderAkademik->is_all_day)
                                <span class="text-gray-500">Sepanjang Hari</span>
                            @else
                                {{ $kalenderAkademik->waktu_mulai }}
                                @if($kalenderAkademik->waktu_selesai)
                                    – {{ $kalenderAkademik->waktu_selesai }}
                                @endif
                            @endif
                        </p>
                    </div>

                    {{-- Deskripsi --}}
                    @if($kalenderAkademik->deskripsi)
                        <div class="pt-3 border-t border-gray-100 dark:border-slate-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-2.5">Deskripsi</p>
                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-slate-700/40 border border-gray-100 dark:border-slate-600">
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">{{ $kalenderAkademik->deskripsi }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Catatan Tambahan --}}
                    @if($kalenderAkademik->catatan)
                        <div class="pt-3 border-t border-gray-100 dark:border-slate-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase mb-2.5">Catatan Tambahan</p>
                            <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800">
                                <p class="text-sm text-amber-900 dark:text-amber-200 whitespace-pre-wrap leading-relaxed">{{ $kalenderAkademik->catatan }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Activity Log --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Riwayat Aktivitas
                    </h3>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                        {{ $kalenderAkademik->aktivitasLogs->count() }}
                    </span>
                </div>

                <div class="overflow-hidden">
                    @forelse($kalenderAkademik->aktivitasLogs->take(15) as $log)
                        <div class="p-4 border-b border-gray-100 dark:border-slate-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-xs font-bold text-white"
                                         style="background-color: {{ $log->event_color ?? '#6366F1' }}">
                                        @if($log->event === 'created')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.5H7a1 1 0 100 2h4v1a1 1 0 102 0v-1a1 1 0 100-2h-1V7z" clip-rule="evenodd"/></svg>
                                        @elseif($log->event === 'updated')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                        @elseif($log->event === 'deleted')
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="text-xs font-bold uppercase" style="color: {{ $log->event_color ?? '#6366F1' }}">{{ $log->event_label }}</span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $log->occurred_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $log->description }}</p>
                                        @if($log->user)
                                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                                                {{ $log->user->name }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                @if($log->old_values || $log->new_values)
                                    <button @click="showLogDetail(@json($log))"
                                            class="px-2.5 py-1.5 rounded-lg text-xs font-semibold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors flex-shrink-0">
                                        Detail
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-200 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada riwayat aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Info Pembuat --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                        Pembuat
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 text-white font-bold shadow-md">
                            {{ strtoupper(($kalenderAkademik->creator->name ?? 'S')[0]) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kalenderAkademik->creator?->name ?? '—' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $kalenderAkademik->creator?->email ?? '—' }}</p>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-slate-700 space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Dibuat</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kalenderAkademik->created_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Diperbarui</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kalenderAkademik->updated_at->translatedFormat('d F Y H:i') }}</p>
                        </div>
                    </div>

                    @if($kalenderAkademik->updater && $kalenderAkademik->updater->id !== $kalenderAkademik->creator->id)
                        <div class="pt-4 border-t border-gray-100 dark:border-slate-700">
                            <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-2">Diubah Oleh</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $kalenderAkademik->updater->name }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $kalenderAkademik->updater->email }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Warna Badge --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        Warna Kategori
                    </h3>
                    <div class="w-20 h-20 rounded-2xl shadow-md border-4 border-gray-100 dark:border-slate-600"
                         style="background-color: {{ $kalenderAkademik->warna }}"></div>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white mt-3">{{ $kalenderAkademik->warna }}</p>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Delete Modal (Alpine) --}}
<div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog">
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="deleteModalOpen = false"></div>
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-sm w-full z-10"
         x-show="deleteModalOpen"
         x-transition>
        <div class="p-6 border-b border-gray-100 dark:border-slate-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                Konfirmasi Hapus
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-sm text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin menghapus agenda <strong>{{ $kalenderAkademik->judul }}</strong>?</p>
            <p class="text-xs text-gray-500 dark:text-gray-400">Tindakan ini tidak dapat dibatalkan. Semua data terkait akan dihapus permanen.</p>
        </div>
        <div class="p-6 bg-gray-50 dark:bg-slate-700/30 flex gap-3">
            <button @click="deleteModalOpen = false" class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                Batal
            </button>
            <form action="{{ route('admin.kalender-akademik.destroy', $kalenderAkademik) }}" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full px-4 py-2.5 rounded-xl font-semibold text-sm text-white bg-red-600 hover:bg-red-700 transition-colors">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('show', () => ({
            deleteModalOpen: false,
            logDetailOpen: false,
            selectedLog: null,

            confirmDelete() {
                this.deleteModalOpen = true;
            },

            showLogDetail(log) {
                this.selectedLog = log;
                this.logDetailOpen = true;
            }
        }));
    });
</script>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<script>
    // Add Alpine to page if not already present
    if (window.Alpine === undefined) {
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('[x-show="deleteModalOpen"]')) {
                const btn = document.querySelector('[x-show="deleteModalOpen"]').closest('[x-data]');
                if (!btn) {
                    const div = document.querySelector('.space-y-6');
                    div.setAttribute('x-data', 'show()');
                }
            }
        });
    }
</script>
@endpush

@endsection
