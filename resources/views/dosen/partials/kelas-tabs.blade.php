@php
    $isEn = app()->getLocale() === 'en';
    $tabLinks = [
        [
            'key'    => 'home',
            'label'  => $isEn ? 'Home' : 'Beranda',
            'url'    => route('dosen.kelas-detail', $kelas->id),
            'active' => request()->routeIs('dosen.kelas-detail'),
            'icon'   => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        ],
        [
            'key'    => 'absensi',
            'label'  => $isEn ? 'Attendance' : 'Absensi',
            'url'    => route('dosen.absensi.index', $kelas->id),
            'active' => request()->routeIs('dosen.absensi.*'),
            'icon'   => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        ],
        [
            'key'    => 'materi',
            'label'  => $isEn ? 'Materials' : 'Materi',
            'url'    => route('dosen.kelas-materi', $kelas->id),
            'active' => request()->routeIs('dosen.kelas-materi*') || request()->routeIs('dosen.materi.*'),
            'icon'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
        ],
        [
            'key'    => 'tugas',
            'label'  => $isEn ? 'Assignments' : 'Tugas',
            'url'    => route('dosen.kelas-tugas', $kelas->id),
            'active' => (request()->routeIs('dosen.kelas-tugas') || request()->routeIs('dosen.tugas.*')) && !request()->routeIs('dosen.kelas-tugas.rekap*'),
            'icon'   => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        ],
        [
            'key'    => 'forum',
            'label'  => $isEn ? 'Forum' : 'Forum',
            'url'    => route('dosen.kelas-forum', $kelas->id),
            'active' => request()->routeIs('dosen.kelas-forum*'),
            'icon'   => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
        ],
        [
            'key'    => 'rekap',
            'label'  => $isEn ? 'Assignment Recap' : 'Rekap Tugas',
            'url'    => route('dosen.kelas-tugas.rekap', $kelas->id),
            'active' => request()->routeIs('dosen.kelas-tugas.rekap*'),
            'icon'   => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        ],
    ];
@endphp

<div class="mb-5 sm:mb-6 flex items-center gap-1 sm:gap-1.5 overflow-x-auto scrollbar-none snap-x rounded-2xl border border-slate-200/80 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-1.5 shadow-sm transition-colors duration-200">
    @foreach ($tabLinks as $tab)
        <a href="{{ $tab['url'] }}"
           class="snap-start inline-flex shrink-0 items-center gap-2 whitespace-nowrap rounded-xl px-3.5 sm:px-4 py-2 text-xs font-bold transition-all duration-150 text-decoration-none
               {{ $tab['active']
                   ? 'bg-[#321270] dark:bg-purple-700 text-white shadow-sm shadow-purple-900/30'
                   : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/70 hover:text-slate-900 dark:hover:text-white' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 {{ $tab['active'] ? 'text-purple-200' : 'text-slate-400 dark:text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/>
            </svg>
            <span>{{ $tab['label'] }}</span>
        </a>
    @endforeach
</div>
