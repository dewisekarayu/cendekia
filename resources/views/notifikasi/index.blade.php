@extends(auth()->user()->hasRole('admin') ? 'layouts.admin' : 'layouts.portal')

@section('title', 'Semua Notifikasi')
@section('activeMenu', 'Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight flex items-center gap-2.5">
                <span>Notifikasi</span>
                @if(($counts['semua'] ?? 0) > 0)
                    <span class="px-2.5 py-0.5 rounded-full bg-rose-500 text-white text-xs font-black shadow-xs">
                        {{ $counts['semua'] }} Baru
                    </span>
                @endif
            </h1>
            <p class="text-sm font-medium text-slate-400 dark:text-slate-500 mt-0.5">
                Pantau seluruh tugas, materi, absensi, nilai, dan pengumuman dari setiap kelas Anda.
            </p>
        </div>

        <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:flex-wrap sm:items-center">
            <form action="{{ route('notifikasi.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="hidden" name="tipe" value="{{ $selectedTipe ?? 'semua' }}">
                <input
                    id="tanggalNotifikasi"
                    type="date"
                    name="tanggal"
                    value="{{ $selectedDate ?? '' }}"
                    max="{{ now()->toDateString() }}"
                    class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs text-gray-700 dark:text-gray-300 focus:border-blue-500 focus:outline-none"
                    aria-label="Filter tanggal"
                >
                <button type="submit" class="px-3 py-1.5 bg-blue-600 rounded-xl text-xs font-bold text-white hover:bg-blue-700 transition shadow-xs">
                    Filter
                </button>
                @if($selectedDate)
                    <a href="{{ route('notifikasi.index', ['tipe' => $selectedTipe ?? 'semua']) }}" class="px-2.5 py-1.5 bg-gray-100 dark:bg-slate-700 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 hover:bg-gray-200 transition">
                        Reset
                    </a>
                @endif
            </form>

            <form action="{{ route('notifikasi.baca-semua') }}" method="POST" class="m-0 shrink-0">
                @csrf
                <button type="submit"
                    @disabled(($counts['semua'] ?? 0) === 0)
                    class="px-3.5 py-1.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-xs flex items-center gap-1.5 disabled:cursor-not-allowed disabled:opacity-50">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    <span>Tandai Semua Dibaca</span>
                </button>
            </form>
        </div>
    </div>

    {{-- Tabs Filter Ala Google Classroom --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        @php
            $tabItems = [
                'semua' => ['label' => 'Semua', 'icon' => 'bell', 'count' => $counts['semua'] ?? 0],
                'tugas' => ['label' => 'Tugas', 'icon' => 'clipboard', 'count' => $counts['tugas'] ?? 0],
                'materi' => ['label' => 'Materi', 'icon' => 'book', 'count' => $counts['materi'] ?? 0],
                'pengumuman' => ['label' => 'Pengumuman', 'icon' => 'megaphone', 'count' => $counts['pengumuman'] ?? 0],
                'presensi' => ['label' => 'Presensi', 'icon' => 'clock', 'count' => $counts['presensi'] ?? 0],
                'nilai' => ['label' => 'Nilai', 'icon' => 'award', 'count' => $counts['nilai'] ?? 0],
            ];
        @endphp

        @foreach($tabItems as $tKey => $tab)
            @php $isActive = ($selectedTipe ?? 'semua') === $tKey; @endphp
            <a href="{{ route('notifikasi.index', array_merge(request()->query(), ['tipe' => $tKey])) }}" 
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0 {{ $isActive ? 'bg-[#002B6B] text-white shadow-sm' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/60' }}">
                <span>{{ $tab['label'] }}</span>
                @if($tab['count'] > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ $isActive ? 'bg-white text-blue-900' : 'bg-rose-500 text-white' }}">
                        {{ $tab['count'] }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Daftar Notifikasi --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/70 dark:border-slate-700 shadow-sm overflow-hidden divide-y divide-gray-100 dark:divide-slate-700/60">
        @forelse($notifikasis as $notif)
            @php 
                $isUnread = is_null($notif->dibaca_pada);
                $meta = $notif->getTypeMeta();
                $namaMk = $notif->kelasPerkuliahan->mataKuliah->nama_mk ?? null;
            @endphp
            <a href="{{ route('notifikasi.baca', $notif->id) }}" 
               class="flex items-start gap-4 p-4 sm:p-5 hover:bg-slate-50/80 dark:hover:bg-slate-700/40 transition group {{ $isUnread ? 'bg-blue-50/30 dark:bg-slate-700/20' : '' }}">
                
                {{-- Icon Jenis Notifikasi --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-xs {{ $meta['icon_bg'] }}">
                    @if($notif->tipe === 'tugas')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    @elseif($notif->tipe === 'materi')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    @elseif($notif->tipe === 'presensi')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($notif->tipe === 'nilai')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @elseif($notif->tipe === 'pengumuman' || $notif->tipe === 'informasi')
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    @endif
                </div>

                {{-- Konten Utama Clean --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-3 mb-1">
                        <div class="flex items-center gap-2 min-w-0">
                            @if($namaMk)
                                <span class="inline-flex items-center text-xs font-bold text-blue-600 dark:text-blue-400 truncate">
                                    {{ $namaMk }}
                                </span>
                            @else
                                <span class="inline-flex items-center text-xs font-bold text-slate-500 dark:text-slate-400">
                                    Sistem Akademik
                                </span>
                            @endif
                        </div>

                        <span class="text-xs text-slate-400 dark:text-slate-500 font-medium shrink-0">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>

                    <h3 class="text-sm sm:text-[15px] font-bold {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300' }} group-hover:text-blue-600 dark:group-hover:text-blue-400 transition leading-snug m-0">
                        {{ $notif->judul }}
                    </h3>

                    <p class="text-xs {{ $isUnread ? 'text-slate-600 dark:text-slate-300' : 'text-slate-500 dark:text-slate-400' }} leading-relaxed mt-1 m-0">
                        {{ $notif->pesan }}
                    </p>
                </div>

                {{-- Status & Arahkan Panah --}}
                <div class="flex items-center gap-2 shrink-0 self-center">
                    @if($isUnread)
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 ring-4 ring-blue-100 dark:ring-blue-900/40"></span>
                    @endif
                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700/60 flex items-center justify-center text-slate-400 group-hover:text-blue-600 group-hover:bg-blue-50 dark:group-hover:bg-slate-700 transition">
                        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-slate-50 dark:bg-slate-700/50 text-slate-400 mb-3">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white mb-1">
                    {{ $selectedDate ? 'Tidak Ada Notifikasi pada Tanggal Ini' : 'Belum Ada Notifikasi' }}
                </h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">
                    {{ $selectedDate ? 'Pilih tanggal lain atau bersihkan filter untuk melihat semua notifikasi.' : 'Semua aktivitas kelas, tugas, nilai, materi, dan absensi Anda akan otomatis masuk ke sini.' }}
                </p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $notifikasis->links() }}
    </div>
</div>
@endsection
