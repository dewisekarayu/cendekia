@extends('layouts.portal')

@section('title', 'Semua Notifikasi')
@section('activeMenu', 'Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Semua Notifikasi</h1>
            <p class="text-sm font-medium text-slate-400 dark:text-slate-500 mt-0.5">Pantau semua pembaruan dan informasi penting.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            @if ($isMahasiswa)
                <div class="flex flex-col sm:flex-row gap-2">
                    <form action="{{ route('notifikasi.index') }}" method="GET" class="flex items-center gap-2">
                        <input type="hidden" name="lihat_pengumuman" id="lihatPengumumanFilter" value="{{ request()->boolean('lihat_pengumuman') ? '1' : '' }}">
                        <label for="tanggalNotifikasi" class="sr-only">Filter notifikasi berdasarkan tanggal</label>
                        <input
                            id="tanggalNotifikasi"
                            type="date"
                            name="tanggal"
                            value="{{ $selectedDate }}"
                            max="{{ now()->toDateString() }}"
                            class="px-3 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm text-gray-700 dark:text-gray-300 focus:border-[#002B6B] focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10"
                        >
                        <button type="submit" class="px-4 py-2 bg-[#002B6B] rounded-xl text-sm font-bold text-white hover:bg-blue-800 transition shadow-sm">
                            Filter
                        </button>
                    </form>
                    <button
                        id="togglePengumuman"
                        type="button"
                        aria-expanded="{{ request()->boolean('lihat_pengumuman') ? 'true' : 'false' }}"
                        aria-controls="daftarPengumuman"
                        class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/80 transition shadow-sm text-center"
                    >
                        {{ request()->boolean('lihat_pengumuman') ? 'Sembunyikan Pengumuman' : 'Tampilkan Semua Pengumuman' }}
                    </button>
                </div>
            @endif
            <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/80 transition shadow-sm">
                    Tandai Semua Dibaca
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700 shadow-sm overflow-hidden">
        @forelse($notifikasis as $notif)
            @php $isUnread = is_null($notif->dibaca_pada); @endphp
            <a href="{{ route('notifikasi.baca', $notif->id) }}" class="flex items-start gap-4 p-5 border-b border-gray-100 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition {{ $isUnread ? 'bg-blue-50/30 dark:bg-slate-700/20' : '' }}">
                <div class="flex-shrink-0 mt-1">
                    @if($isUnread)
                        <div class="w-3 h-3 bg-blue-500 rounded-full shadow-sm ring-4 ring-blue-100 dark:ring-slate-700"></div>
                    @else
                        <div class="w-3 h-3 bg-gray-300 dark:bg-slate-600 rounded-full"></div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold {{ $isUnread ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300' }} mb-1">
                        {{ $notif->judul }}
                    </p>
                    <p class="text-sm {{ $isUnread ? 'text-gray-600 dark:text-gray-400' : 'text-gray-500 dark:text-gray-500/80' }} leading-relaxed">
                        {{ $notif->pesan }}
                    </p>
                    <div class="mt-3 flex items-center gap-3 text-xs font-medium {{ $isUnread ? 'text-gray-500 dark:text-gray-400' : 'text-gray-400 dark:text-gray-500' }}">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                        <span>&bull;</span>
                        <span>{{ $notif->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 dark:text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-600 transition">
                        <svg class="w-4 h-4 transform -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-800 border-2 border-dashed border-gray-200 dark:border-slate-700 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                </div>
                <h3 class="text-base font-bold text-gray-800 dark:text-white mb-1">
                    {{ $isMahasiswa ? 'Tidak Ada Notifikasi pada Tanggal Ini' : 'Belum Ada Notifikasi' }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $isMahasiswa ? 'Pilih tanggal lain untuk melihat notifikasi sebelumnya.' : 'Anda akan melihat notifikasi di sini ketika ada aktivitas baru.' }}
                </p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $notifikasis->links() }}
    </div>

    @if ($isMahasiswa)
        <section id="daftarPengumuman" class="{{ request()->boolean('lihat_pengumuman') ? '' : 'hidden' }} space-y-4" aria-label="Semua pengumuman">
            <h2 class="text-lg font-black text-slate-800 dark:text-white">Semua Pengumuman</h2>
            @forelse ($pengumuman as $item)
                <article class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700 p-5 shadow-sm">
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        @if ($item->untuk_semua || is_null($item->kelas_perkuliahan_id))
                            <span class="text-[10px] font-bold bg-amber-50 dark:bg-amber-950/40 border border-amber-200/40 dark:border-amber-900/30 text-amber-600 dark:text-amber-300 px-2.5 py-0.5 rounded-md">Penting / Global</span>
                        @endif
                        @if ($item->kelasPerkuliahan)
                            <span class="text-[10px] font-bold bg-purple-50 dark:bg-purple-950/40 border border-purple-100 dark:border-purple-900/30 text-[#002B6B] dark:text-blue-300 px-2.5 py-0.5 rounded-md">
                                Kelas: {{ $item->kelasPerkuliahan->kode_kelas }}
                            </span>
                        @endif
                    </div>
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white">{{ $item->judul }}</h3>
                    <p class="mt-2 whitespace-pre-line text-sm leading-relaxed text-slate-600 dark:text-slate-300">{{ $item->isi }}</p>
                    <div class="mt-3 flex flex-wrap items-center justify-between gap-2 border-t border-slate-100 dark:border-slate-700 pt-3 text-xs text-slate-400 dark:text-slate-500">
                        <span>Oleh: {{ $item->pembuat->name ?? 'Administrator' }}</span>
                        <span>{{ $item->created_at?->format('d M Y') }}</span>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 text-center text-sm text-slate-500">
                    Tidak ada pengumuman saat ini.
                </div>
            @endforelse

            @if ($pengumuman->hasPages())
                <div>
                    {{ $pengumuman->appends([
                        'tanggal' => $selectedDate,
                        'lihat_pengumuman' => 1,
                    ])->links() }}
                </div>
            @endif
        </section>
    @endif
</div>

@if ($isMahasiswa)
    @push('scripts')
        <script>
            document.getElementById('togglePengumuman').addEventListener('click', function () {
                const section = document.getElementById('daftarPengumuman');
                const isVisible = !section.classList.contains('hidden');
                const params = new URLSearchParams(window.location.search);

                section.classList.toggle('hidden', isVisible);
                this.setAttribute('aria-expanded', String(!isVisible));
                this.textContent = isVisible ? 'Tampilkan Semua Pengumuman' : 'Sembunyikan Pengumuman';
                document.getElementById('lihatPengumumanFilter').value = isVisible ? '' : '1';

                if (isVisible) {
                    params.delete('lihat_pengumuman');
                } else {
                    params.set('lihat_pengumuman', '1');
                }

                const query = params.toString();
                window.history.replaceState(null, '', window.location.pathname + (query ? '?' + query : ''));
            });
        </script>
    @endpush
@endif
@endsection
