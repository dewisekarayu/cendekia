<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Cendekia</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        (function() {
            const theme = "{{ auth()->check() ? auth()->user()->theme : 'light' }}";
            if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Hide scrollbar on sidebar for clean modern look */
        aside {
            -ms-overflow-style: none !important; /* IE and Edge */
            scrollbar-width: none !important; /* Firefox */
        }
        aside::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        /* Dark Mode overrides for Student/Dosen portal */
        html.dark body {
            background-color: #0f172a !important; /* slate-900 */
            color: #cbd5e1 !important;
        }

        /* Ensure cards and text are white/slate-200 in dark mode */
        html.dark .text-slate-800,
        html.dark .text-gray-900,
        html.dark .text-slate-900,
        html.dark .text-gray-800,
        html.dark .text-slate-700,
        html.dark .text-slate-600 {
            color: #f8fafc !important;
        }

        html.dark .text-gray-600,
        html.dark .text-gray-500,
        html.dark .text-slate-500 {
            color: #cbd5e1 !important;
        }

        html.dark .bg-white {
            background-color: #1e293b !important; /* slate-800 */
        }

        html.dark .border-slate-100,
        html.dark .border-slate-200,
        html.dark .border-gray-200,
        html.dark .border-gray-150,
        html.dark .border-gray-100 {
            border-color: #334155 !important;
        }

        html.dark .bg-gray-50,
        html.dark .bg-slate-50 {
            background-color: #0f172a !important;
        }

        /* Stats numbers and dynamic background boxes in dark mode */
        html.dark .text-\[\#002B6B\],
        html.dark .text-indigo-900,
        html.dark .text-blue-900,
        html.dark .text-violet-900 {
            color: #60a5fa !important;
        }

        html.dark .text-\[\#321270\],
        html.dark .text-purple-900 {
            color: #a78bfa !important;
        }

        /* Standard Tailwind color backgrounds overrides */
        html.dark .bg-blue-50,
        html.dark .bg-indigo-50,
        html.dark .bg-sky-50,
        html.dark .bg-sky-50\/40,
        html.dark .bg-blue-50\/30,
        html.dark .bg-slate-50,
        html.dark .bg-emerald-50,
        html.dark .bg-amber-50,
        html.dark .bg-rose-50,
        html.dark .bg-violet-50 {
            background-color: #1e293b !important;
        }

        html.dark .bg-blue-100,
        html.dark .bg-indigo-100,
        html.dark .bg-sky-100,
        html.dark .bg-slate-100,
        html.dark .bg-emerald-100,
        html.dark .bg-amber-100,
        html.dark .bg-rose-100,
        html.dark .bg-violet-100 {
            background-color: #334155 !important;
        }

        /* Accent Text Colors in Dark Mode */
        html.dark .text-blue-700,
        html.dark .text-blue-600,
        html.dark .text-indigo-700,
        html.dark .text-indigo-600 {
            color: #93c5fd !important;
        }
        html.dark .text-emerald-700,
        html.dark .text-emerald-600 {
            color: #6ee7b7 !important;
        }
        html.dark .text-amber-700,
        html.dark .text-amber-600 {
            color: #fde047 !important;
        }
        html.dark .text-rose-700,
        html.dark .text-rose-600 {
            color: #fca5a5 !important;
        }
        html.dark .text-violet-700,
        html.dark .text-violet-600 {
            color: #c7d2fe !important;
        }
        html.dark .text-sky-700,
        html.dark .text-sky-600 {
            color: #7dd3fc !important;
        }

        /* Inputs and selects */
        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }

        /* Contextual Help Box Overrides */
        html.dark .bg-gradient-to-br.from-blue-50.to-indigo-50 {
            background-image: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%) !important;
            border-color: #312e81 !important;
        }
        html.dark .bg-gradient-to-br.from-blue-50.to-indigo-50 p,
        html.dark .bg-gradient-to-br.from-blue-50.to-indigo-50 span,
        html.dark .bg-gradient-to-br.from-blue-50.to-indigo-50 li {
            color: #cbd5e1 !important;
        }
        html.dark .bg-gradient-to-br.from-blue-50.to-indigo-50 a {
            color: #818cf8 !important;
        }

        /* Alert Box Gradients in Dark Mode */
        html.dark .bg-gradient-to-r.from-green-50.to-emerald-50 {
            background-image: linear-gradient(to right, #064e3b, #022c22) !important;
            border-color: #047857 !important;
        }
        html.dark .bg-gradient-to-r.from-yellow-50.to-amber-50 {
            background-image: linear-gradient(to right, #78350f, #451a03) !important;
            border-color: #b45309 !important;
        }
        html.dark .bg-gradient-to-r.from-green-50.to-emerald-50 p,
        html.dark .bg-gradient-to-r.from-green-50.to-emerald-50 span,
        html.dark .bg-gradient-to-r.from-yellow-50.to-amber-50 p,
        html.dark .bg-gradient-to-r.from-yellow-50.to-amber-50 span {
            color: #cbd5e1 !important;
        }
        html.dark .bg-gradient-to-r.from-green-50.to-emerald-50 .font-bold {
            color: #6ee7b7 !important;
        }
        html.dark .bg-gradient-to-r.from-yellow-50.to-amber-50 .font-bold {
            color: #fde047 !important;
        }

        /* Schedule Timetable and mobile list overrides for Dark Mode */
        html.dark .border-l-4[class*="bg-"] {
            background-color: #334155 !important;
        }
        html.dark .border-l-4.bg-blue-50\/80 {
            background-color: rgba(30, 58, 138, 0.3) !important;
        }
        html.dark .border-l-4.bg-violet-50\/80 {
            background-color: rgba(76, 29, 149, 0.3) !important;
        }
        html.dark .border-l-4.bg-emerald-50\/80 {
            background-color: rgba(6, 78, 59, 0.3) !important;
        }
        html.dark .border-l-4.bg-amber-50\/80 {
            background-color: rgba(120, 53, 15, 0.3) !important;
        }
        html.dark .border-l-4.bg-rose-50\/80 {
            background-color: rgba(136, 19, 55, 0.3) !important;
        }

        html.dark table,
        html.dark tr,
        html.dark th,
        html.dark td {
            border-color: #334155 !important;
        }
        html.dark th.bg-gray-50\/80,
        html.dark th[class*="bg-gray-50"] {
            background-color: #1e293b !important;
        }
        html.dark th span.text-gray-600 {
            color: #cbd5e1 !important;
        }
        html.dark .bg-gray-50 {
            background-color: #1e293b !important;
        }
        html.dark .divide-gray-50 > * {
            border-color: #334155 !important;
        }

        /* Forums Dark Mode Overrides */
        html.dark .forum-chat-wrap {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: none !important;
        }
        html.dark .forum-body {
            background: #0f172a !important;
        }
        html.dark .forum-input-bar {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        html.dark .forum-textarea {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        html.dark .forum-bubble.theirs {
            background-color: #334155 !important;
            color: #f8fafc !important;
            border-color: #475569 !important;
        }
        html.dark .forum-bubble.theirs .forum-bubble-text {
            color: #f8fafc !important;
        }
        html.dark .forum-bubble.theirs .forum-bubble-time {
            color: #cbd5e1 !important;
        }
        html.dark .forum-chip {
            background-color: #1e1b4b !important;
            color: #818cf8 !important;
        }
        html.dark .forum-date-chip {
            background-color: #334155 !important;
            color: #cbd5e1 !important;
        }

        /* SweetAlert override for dark mode */
        .swal2-popup {
            background-color: #1e293b !important;
            color: #f8fafc !important;
        }
        .swal2-title {
            color: #f8fafc !important;
        }
        .swal2-html-container {
            color: #cbd5e1 !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-slate-900 transition-colors duration-200">
    @php
        $user = auth()->user();
        $currentLocale = session('locale', $user->language ?? 'id');
        $hasDosen = $user && $user->hasRole('dosen');
        $hasAdmin = $user && $user->hasRole('admin');

        if ($hasDosen) {
            $sidebarBg = '#321270';
            $sidebarText = 'rgba(255,255,255,0.75)';
            $sidebarTitle = '#FFFFFF';
            $sidebarMuted = 'rgba(255,255,255,0.5)';
            $sidebarBorder = 'rgba(255,255,255,0.12)';
            $sidebarHover = 'rgba(255,255,255,0.08)';
            $activeBg = 'rgba(255,255,255,0.14)';
            $activeText = '#FFFFFF';
        } elseif ($hasAdmin) {
            $sidebarBg = '#002B6B';
            $sidebarText = 'rgba(255,255,255,0.75)';
            $sidebarTitle = '#FFFFFF';
            $sidebarMuted = 'rgba(255,255,255,0.5)';
            $sidebarBorder = 'rgba(255,255,255,0.12)';
            $sidebarHover = 'rgba(255,255,255,0.08)';
            $activeBg = 'rgba(255,255,255,0.14)';
            $activeText = '#FFFFFF';
        } else {
            $sidebarBg = '#F7F9FB';
            $sidebarText = '#4B5563';
            $sidebarTitle = '#1E3A8A';
            $sidebarMuted = '#9CA3AF';
            $sidebarBorder = '#F3F4F6';
            $sidebarHover = '#EEF2F7';
            $activeBg = '#1E3A8A';
            $activeText = '#FFFFFF';
        }
    @endphp

    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="min-h-screen flex overflow-x-hidden">

        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-gray-900/45 lg:hidden"
            aria-hidden="true"></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex h-screen w-72 max-w-[86vw] flex-col overflow-y-auto transition-transform duration-200 lg:w-64"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            style="background-color: {{ $sidebarBg }}; border-right: 1px solid {{ $sidebarBorder }};">

            <div class="px-6 py-4" style="border-bottom: 1px solid {{ $sidebarBorder }};">
                <div class="flex items-center justify-between gap-3">
                    <a href="/" class="flex min-w-0 items-center gap-2" style="gap: 0.5rem;">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Cendekia" class="" />
                        </div>
                        <div class="min-w-0">
                            <div class="text-base font-bold leading-tight truncate notranslate" translate="no" style="color: {{ $sidebarTitle }};">Cendekia</div>
                            <div class="text-[11px] leading-tight truncate" style="color: {{ $sidebarMuted }};">Academic Portal</div>
                        </div>
                    </a>

                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg lg:hidden"
                        style="color: {{ $sidebarText }};"
                        aria-label="Tutup menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                @php
                    if (auth()->user()->hasRole('dosen')) {
                        $menu = [
                            ['label' => 'Dashboard', 'route' => 'dosen.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Kelas Saya', 'route' => 'dosen.kelas-saya', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            [
                                'label' => 'Penilaian & EWS', 
                                'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                                'routes' => ['dosen.gradebook', 'dosen.analytics'],
                                'children' => [
                                    ['label' => 'Gradebook', 'route' => 'dosen.gradebook'],
                                    ['label' => 'Early Warning System', 'route' => 'dosen.analytics']
                                ]
                            ],
                            ['label' => 'Pengumuman', 'route' => 'dosen.kelas-pengumuman.index', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                            ['label' => 'Jadwal & Log Mengajar', 'route' => 'dosen.jadwal.index', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ['label' => 'Kalender Akademik', 'route' => 'dosen.kalender-akademik.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['label' => 'AI Assistant', 'route' => 'dosen.ai-assistant', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        ];
                    } elseif (auth()->user()->hasRole('admin')) {
                        $menu = [
                            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Data Dosen', 'route' => 'admin.dosen.index', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z'],
                            ['label' => 'Data Mahasiswa', 'route' => 'admin.mahasiswa.index', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z'],
                            ['label' => 'Fakultas', 'route' => 'admin.fakultas.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            ['label' => 'Program Studi', 'route' => 'admin.program-studi.index', 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z'],
                            ['label' => 'Kurikulum', 'route' => 'admin.kurikulum.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['label' => 'Tahun Akademik', 'route' => 'admin.tahun-akademik.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['label' => 'Kelas Perkuliahan', 'route' => 'admin.kelas.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            ['label' => 'Mata Kuliah', 'route' => 'admin.mata-kuliah.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['label' => 'Kalender Akademik', 'route' => 'admin.kalender-akademik.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ['label' => 'Pengumuman', 'route' => 'admin.pengumuman.index', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                        ];
                    } else {
                        if (auth()->user()->program_studi_id) {
                            $menu = [
                                ['label' => 'Dashboard', 'route' => 'mahasiswa.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['label' => 'My Courses', 'route' => 'mahasiswa.kelas-saya', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                ['label' => 'Gradebook', 'route' => 'mahasiswa.gradebook', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                                ['label' => 'Schedule', 'route' => 'mahasiswa.schedule', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['label' => 'Kalender Akademik', 'route' => 'mahasiswa.kalender-akademik.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ];
                        } else {
                            $menu = [
                                ['label' => 'Dashboard', 'route' => 'mahasiswa.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ];
                        }
                    }
                @endphp

                @foreach ($menu as $item)
                    @if(isset($item['children']))
                        @php
                            $isOpen = false;
                            if(isset($item['routes'])) {
                                foreach($item['routes'] as $r) {
                                    if(request()->routeIs($r)) {
                                        $isOpen = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div x-data="{ open: {{ $isOpen ? 'true' : 'false' }} }" class="space-y-1">
                            <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition cursor-pointer border-0 bg-transparent text-left sidebar-link" style="color: {{ $sidebarText }}; --hover-bg: {{ $sidebarHover }};" x-bind:style="open ? 'color: {{ $activeText }};' : 'color: {{ $sidebarText }};'">
                                <div class="flex items-center gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span>{{ $item['label'] }}</span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </button>
                            <div x-show="open" x-collapse class="pl-10 pr-2 py-1 space-y-1">
                                @foreach($item['children'] as $child)
                                    @php
                                        $isChildActive = request()->routeIs($child['route']);
                                    @endphp
                                    <a href="{{ route($child['route']) }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition text-decoration-none sidebar-link {{ $isChildActive ? 'sidebar-link-active' : '' }}" style="{{ $isChildActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @php
                            $activeLabel = View::yieldContent('activeMenu') ?: '';
                            $isActive = request()->routeIs($item['route']) ||
                                $item['label'] === $activeLabel ||
                                ($item['label'] === 'Jadwal & Log Mengajar' && in_array($activeLabel, ['Jadwal & Log Mengajar', 'Jadwal', 'Log Mengajar'])) ||
                                ($item['label'] === 'My Courses' && request()->routeIs(
                                    'mahasiswa.kelas-detail',
                                    'mahasiswa.jelajahi-kelas',
                                    'mahasiswa.materi.*',
                                    'mahasiswa.pengumpulan-tugas.*',
                                    'mahasiswa.kelas-forum*',
                                    'mahasiswa.absensi.*',
                                    'mahasiswa.krs.*'
                                )) ||
                                ($item['label'] === 'Gradebook' && request()->routeIs('mahasiswa.analytics')) ||
                                ($item['label'] === 'Schedule' && request()->routeIs('mahasiswa.jadwal.*'));
                        @endphp
                        <a
                            href="{{ $item['route'] === '#' ? '#' : route($item['route'], $item['param'] ?? []) }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $isActive ? 'sidebar-link-active' : '' }}"
                            style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach

                {{-- ===== BOTTOM SECTION: Settings, Help Center, Logout ===== --}}
                <div class="pt-4 mt-4 space-y-1" style="border-top: 1px solid {{ $sidebarBorder }};">

                    {{-- Profile / Settings per role --}}
                    @if (auth()->user()->hasRole('mahasiswa'))
                        @php $isSettingActive = request()->routeIs('mahasiswa.setting*', 'mahasiswa.profil'); @endphp
                        <a
                            href="{{ route('mahasiswa.setting') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $isSettingActive ? 'sidebar-link-active' : '' }}"
                            style="{{ $isSettingActive ? 'background-color: ' . $activeBg . '; color: ' . $activeText . ';' : 'color: ' . $sidebarText . ';' }} --hover-bg: {{ $sidebarHover }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                            Setting
                        </a>
                    @elseif (auth()->user()->hasRole('dosen'))
                        @php $isSettingActive = request()->routeIs('dosen.setting'); @endphp
                        <a
                            href="{{ route('dosen.setting') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $isSettingActive ? 'sidebar-link-active' : '' }}"
                            style="{{ $isSettingActive ? 'background-color: ' . $activeBg . '; color: ' . $activeText . ';' : 'color: ' . $sidebarText . ';' }} --hover-bg: {{ $sidebarHover }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                            Settings
                        </a>
                    @elseif (auth()->user()->hasRole('admin'))
                        @php $isPrefActive = request()->routeIs('admin.setting*') || request()->routeIs('admin.user.*') || request()->routeIs('profile.*'); @endphp
                        <a
                            href="{{ route('admin.setting') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $isPrefActive ? 'sidebar-link-active' : '' }}"
                            style="{{ $isPrefActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                            <span>Pengaturan</span>
                        </a>
                    @endif

                    {{-- ===== HELP CENTER LINK (FIXED) ===== --}}
                    @php $isHelpActive = request()->routeIs('help-center.*'); @endphp
                    <a
                        href="{{ route('help-center.index') }}"
                        class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $isHelpActive ? 'sidebar-link-active' : '' }}"
                        style="{{ $isHelpActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $currentLocale === 'en' ? 'Help Center' : 'Pusat Bantuan' }}
                    </a>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="sidebar-link w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition" style="color: {{ $sidebarText }}; --hover-bg: {{ $sidebarHover }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <div class="flex-1 min-w-0 w-full transition-all duration-200" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">

            <header class="sticky top-0 z-30 bg-white/95 dark:bg-slate-800/95 backdrop-blur-md border-b border-gray-200/80 dark:border-slate-700/80 px-3 py-2.5 sm:px-6 sm:py-3.5 flex items-center justify-between gap-3 transition-colors duration-200">

                <div class="flex items-center gap-2.5 sm:gap-3.5 min-w-0">
                    <button
                        type="button"
                        @click="sidebarOpen = !sidebarOpen"
                        class="inline-flex h-9 w-9 sm:h-10 sm:w-10 items-center justify-center rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/80 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition focus:outline-none shrink-0"
                        aria-label="Toggle menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <h1 class="text-sm sm:text-base font-bold text-slate-800 dark:text-white truncate">
                            @yield('title', 'Dashboard')
                        </h1>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-1.5 sm:gap-3 shrink-0">
                    @php
                        $targetLocale = $currentLocale === 'en' ? 'id' : 'en';
                    @endphp
                    {{-- Language Switcher (Clean, no broken Windows emoji flags) --}}
                    <a href="{{ route('switch-language', $targetLocale) }}" 
                       class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 dark:bg-slate-700/80 text-slate-700 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 transition text-decoration-none shadow-2xs notranslate"
                       translate="no"
                       onclick="if ('{{ $targetLocale }}' === 'id') { document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'; document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.' + window.location.hostname + ';'; }"
                       title="{{ $currentLocale === 'en' ? 'Ganti ke Bahasa Indonesia' : 'Switch to English' }}">
                        <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <span>{{ strtoupper($currentLocale) }}</span>
                    </a>

                    @php
                        if (auth()->check()) {
                            \App\Services\NotificationService::syncMissingGlobalPengumuman(auth()->user());
                        }
                        $recentNotifs = auth()->check() ? auth()->user()->notifikasi()->orderBy('created_at', 'desc')->get() : collect();
                        $recentNotifs = auth()->check() ? auth()->user()->notifikasi()->with(['kelasPerkuliahan.mataKuliah'])->orderBy('created_at', 'desc')->take(7)->get() : collect();
                        $unreadCount = auth()->check() ? auth()->user()->notifikasi()->whereNull('dibaca_pada')->count() : 0;
                    @endphp
                    <div class="relative" 
                         x-data="{ 
                            notifOpen: false, 
                            unreadCount: {{ $unreadCount }},
                            markAllReadError: '',
                            pollNotifications() {
                                fetch('{{ route('notifikasi.poll') }}', { headers: { 'Accept': 'application/json' } })
                                    .then(r => r.json())
                                    .then(data => {
                                        if (data && typeof data.unread_count !== 'undefined') {
                                            this.unreadCount = data.unread_count;
                                        }
                                    })
                                    .catch(() => {});
                            },
                            init() {
                                setInterval(() => this.pollNotifications(), 25000);
                            }
                         }">
                        {{-- Tombol Lonceng Proporsional & Elegan --}}
                        <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" 
                                type="button"
                                class="relative inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/80 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition focus:outline-none"
                                aria-label="Notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            
                            {{-- Badge Counter Unread di Sudut Kanan Atas Luar --}}
                            <span x-show="unreadCount > 0"
                                  x-text="unreadCount > 99 ? '99+' : unreadCount"
                                  class="bg-rose-600 text-white font-black rounded-full ring-2 ring-white dark:ring-slate-800 shadow-xs pointer-events-none"
                                  style="position: absolute !important; top: -4px !important; right: -4px !important; min-width: 18px !important; height: 18px !important; padding: 0 4px !important; font-size: 10px !important; line-height: 18px !important; text-align: center !important; z-index: 10 !important;">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        </button>

                        {{-- Dropdown Container Ala Google Classroom --}}
                        <div x-show="notifOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 mt-2.5 w-[calc(100vw-2rem)] max-w-sm sm:w-96 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 overflow-hidden z-50 text-left"
                             style="display: none;">
                            
                            {{-- Header Dropdown --}}
                            <div class="px-4 py-3 bg-slate-50/80 dark:bg-slate-800/80 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-800 dark:text-white m-0">Notifikasi Kelas</h3>
                                    <template x-if="unreadCount > 0">
                                        <span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-[11px] font-bold"
                                              x-text="unreadCount + ' Baru'">
                                            {{ $unreadCount }} Baru
                                        </span>
                                    </template>
                                </div>
                                <form action="{{ route('notifikasi.baca-semua') }}" method="POST" class="m-0 shrink-0" x-on:submit.prevent="window.markAllPortalNotificationsRead($event, $data)">
                                    @csrf
                                    <button type="submit" :disabled="unreadCount === 0"
                                        class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 hover:underline transition bg-transparent border-0 p-0 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50">
                                        Tandai semua dibaca
                                    </button>
                                </form>
                            </div>
                            <p x-show="markAllReadError" x-text="markAllReadError" role="alert"
                               class="px-4 py-2 text-xs font-medium text-red-600 dark:text-red-400"></p>

                            {{-- List Notifikasi --}}
                            <div class="max-h-[380px] overflow-y-auto divide-y divide-gray-50 dark:divide-slate-800/60">
                                @forelse($recentNotifs as $notif)
                                    @php 
                                        $isUnread = is_null($notif->dibaca_pada);
                                        $meta = $notif->getTypeMeta();
                                        $namaMk = $notif->kelasPerkuliahan->mataKuliah->nama_mk ?? null;
                                    @endphp
                                    <a href="{{ route('notifikasi.baca', $notif->id) }}"
                                       data-unread-notification="{{ $isUnread ? 'true' : 'false' }}"
                                       class="flex items-start gap-3 p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition group text-decoration-none {{ $isUnread ? 'bg-blue-50/40 dark:bg-slate-800/40' : '' }}">
                                        
                                        {{-- Icon Jenis Notifikasi --}}
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-xs {{ $meta['icon_bg'] }}">
                                            @if($notif->tipe === 'tugas')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                </svg>
                                            @elseif($notif->tipe === 'materi')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            @elseif($notif->tipe === 'presensi')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($notif->tipe === 'nilai')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($notif->tipe === 'pengumuman' || $notif->tipe === 'informasi')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            @endif
                                        </div>

                                        {{-- Konten Notifikasi Clean 2 Baris --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <h4 @if($isUnread) data-unread-title @endif class="text-xs font-bold {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300' }} truncate leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition m-0">
                                                    {{ $notif->judul }}
                                                </h4>
                                                <span class="text-[10px] text-gray-400 dark:text-slate-500 font-medium shrink-0">
                                                    {{ $notif->created_at->diffForHumans(null, true) }}
                                                </span>
                                            </div>

                                            <p @if($isUnread) data-unread-message @endif class="text-[11px] {{ $isUnread ? 'text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500' }} truncate mt-1 m-0">
                                                @if($namaMk)
                                                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $namaMk }}</span>
                                                    <span class="text-slate-300 dark:text-slate-600 mx-1">&bull;</span>
                                                @endif
                                                <span>{{ Str::limit($notif->pesan, 65) }}</span>
                                            </p>
                                        </div>

                                        {{-- Indikator Belum Dibaca (Titik Biru) --}}
                                        @if($isUnread)
                                            <span data-unread-indicator class="w-2 h-2 mt-1.5 shrink-0 rounded-full bg-blue-600 ring-2 ring-blue-100 dark:ring-blue-900/50"></span>
                                        @endif
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-400 mb-2.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300">Belum Ada Notifikasi</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">Aktivitas kelas Anda akan muncul di sini</p>
                                    </div>
                                @endforelse
                            </div>

                            {{-- Footer Dropdown --}}
                            <div class="p-2 bg-slate-50/80 dark:bg-slate-800/80 border-t border-gray-100 dark:border-slate-800">
                                <a href="{{ route('notifikasi.index') }}" 
                                   class="flex items-center justify-center gap-1.5 w-full py-2 text-center text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-slate-700/60 rounded-xl transition shadow-2xs">
                                    <span>Lihat Semua Notifikasi</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- User Profile Pill --}}
                    <div class="flex items-center gap-2 bg-slate-100 dark:bg-slate-700/80 rounded-xl p-1 sm:pr-3 shrink-0">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-[#002B6B] flex items-center justify-center text-white text-xs font-bold uppercase shadow-2xs overflow-hidden shrink-0">
                            @if (auth()->user()->foto)
                                <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto profil" class="w-full h-full object-cover">
                            @else
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            @endif
                        </div>
                        <span class="hidden max-w-[9rem] md:max-w-[12rem] truncate text-xs font-bold text-slate-700 dark:text-slate-200 sm:inline">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>

            <main class="w-full min-w-0 overflow-x-hidden p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast Notification -->
    <x-toast />

    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-link:hover {
            background-color: var(--hover-bg) !important;
        }

        aside a,
        aside a:hover,
        aside a:focus {
            text-decoration: none !important;
        }

        html.dark aside {
            background-color: #0f172a !important;
            border-right: 1px solid #1e293b !important;
        }
        html.dark aside .sidebar-link {
            color: #94a3b8 !important;
        }
        html.dark aside .sidebar-link:hover {
            background-color: #1e293b !important;
            color: #f8fafc !important;
        }
        html.dark aside .sidebar-link-active {
            background-color: #1e1b4b !important;
            color: #c084fc !important;
            border-left: 3px solid #a855f7 !important;
            padding-left: calc(0.75rem - 3px) !important;
        }
        html.dark aside .pt-4.mt-4 {
            border-top: 1px solid #1e293b !important;
        }
        html.dark aside div.px-6.py-5 {
            border-bottom: 1px solid #1e293b !important;
        }
        html.dark aside div.text-base {
            color: #f8fafc !important;
        }
        html.dark aside div.text-\[11px\] {
            color: #64748b !important;
        }
        html.dark .bg-white {
            background-color: #1e293b !important;
        }
        html.dark .border-gray-100,
        html.dark .border-slate-100,
        html.dark .border-slate-200,
        html.dark .border-slate-250\/70,
        html.dark .border-slate-200\/60,
        html.dark .border-slate-200\/80,
        html.dark .border-gray-200 {
            border-color: #334155 !important;
        }
        html.dark .bg-slate-50,
        html.dark .bg-gray-50,
        html.dark .bg-slate-50\/50,
        html.dark .bg-gray-50\/50,
        html.dark .bg-gray-50\/70,
        html.dark .bg-slate-50\/30,
        html.dark .bg-slate-100 {
            background-color: #0f172a !important;
            border-color: #334155 !important;
        }
        html.dark tbody {
            background-color: #1e293b !important;
        }
        html.dark tr.hover\:bg-gray-50\/50:hover,
        html.dark tr.hover\:bg-slate-50\/80:hover {
            background-color: #334155 !important;
        }
        html.dark .text-slate-800,
        html.dark .text-gray-800,
        html.dark .text-slate-700,
        html.dark .text-gray-700 {
            color: #f8fafc !important;
        }
        html.dark .text-slate-600,
        html.dark .text-gray-600 {
            color: #cbd5e1 !important;
        }
        html.dark .text-slate-500,
        html.dark .text-gray-500 {
            color: #94a3b8 !important;
        }
        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        html.dark input:focus,
        html.dark select:focus,
        html.dark textarea:focus {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border-color: #a855f7 !important;
            box-shadow: 0 0 0 2px rgba(168, 85, 247, 0.2) !important;
        }
        html.dark input:hover,
        html.dark select:hover,
        html.dark textarea:hover {
            border-color: #475569 !important;
        }
        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #475569 !important;
        }
        html.dark .bg-white.text-slate-700,
        html.dark .bg-white.text-gray-700,
        html.dark a.bg-white,
        html.dark button.bg-white {
            background-color: #1e293b !important;
            color: #cbd5e1 !important;
            border-color: #334155 !important;
        }
        html.dark .bg-white.text-slate-700:hover,
        html.dark a.bg-white:hover,
        html.dark button.bg-white:hover {
            background-color: #334155 !important;
            color: #f8fafc !important;
        }
        html.dark .hover\:bg-slate-50:hover,
        html.dark .hover\:bg-gray-50:hover {
            background-color: #334155 !important;
        }
        html.dark .bg-blue-50,
        html.dark .bg-blue-50\/80 {
            background-color: #1e1b4b !important;
            color: #a78bfa !important;
            border-color: #312e81 !important;
        }
        html.dark .bg-purple-50 {
            background-color: #3b0764 !important;
            color: #d8b4fe !important;
            border-color: #581c87 !important;
        }
        html.dark .bg-emerald-50 {
            background-color: #064e3b !important;
            color: #6ee7b7 !important;
            border-color: #065f46 !important;
        }
        html.dark .bg-amber-50 {
            background-color: #78350f !important;
            color: #fde047 !important;
            border-color: #92400e !important;
        }
        html.dark .bg-rose-50 {
            background-color: #4c0519 !important;
            color: #fda4af !important;
            border-color: #9f1239 !important;
        }
    </style>

    <script>
        window.markAllPortalNotificationsRead = async function (event, component) {
            const form = event.currentTarget;
            const button = form.querySelector('button');

            if (component.unreadCount === 0) return;
            component.markAllReadError = '';
            button.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error('Gagal memperbarui status notifikasi.');
                }

                await response.json();
                component.unreadCount = 0;

                document.querySelectorAll('[data-unread-notification=true]').forEach(notification => {
                    notification.classList.remove('bg-blue-50/40', 'dark:bg-slate-800/40');

                    const title = notification.querySelector('[data-unread-title]');
                    if (title) {
                        title.classList.replace('text-slate-900', 'text-slate-700');
                        title.classList.replace('dark:text-white', 'dark:text-slate-300');
                    }

                    const message = notification.querySelector('[data-unread-message]');
                    if (message) {
                        message.classList.replace('text-slate-600', 'text-slate-400');
                        message.classList.replace('dark:text-slate-300', 'dark:text-slate-500');
                    }

                    notification.querySelector('[data-unread-indicator]')?.remove();
                    notification.dataset.unreadNotification = 'false';
                });
            } catch (error) {
                console.error(error);
                component.markAllReadError = 'Gagal menandai notifikasi. Silakan coba lagi.';
                button.disabled = false;
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    @include('partials.translator')
</body>

</html>
