<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - Cendekia</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

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
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-slate-900 transition-colors duration-200">
    @php
        $user = auth()->user();
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
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:-translate-x-full'"
            style="background-color: {{ $sidebarBg }}; border-right: 1px solid {{ $sidebarBorder }};">

            <div class="px-6 py-5" style="border-bottom: 1px solid {{ $sidebarBorder }};">
                <div class="flex items-center justify-between gap-3">
                    <a href="/" class="flex min-w-0 items-center gap-2">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Cendekia" class="" />
                        </div>
                        <div class="min-w-0">
                            <div class="text-base font-bold leading-tight truncate" style="color: {{ $sidebarTitle }};">Cendekia</div>
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
                            ['label' => 'My Classes', 'route' => 'dosen.kelas-saya', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['label' => 'Announcements', 'route' => 'dosen.kelas-pengumuman.index', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                            ['label' => 'Schedule', 'route' => 'dosen.schedule', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ];
                    } elseif (auth()->user()->hasRole('admin')) {
                        $menu = [
                            ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['label' => 'Data Dosen', 'route' => 'admin.dosen.index', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z'],
                            ['label' => 'Data Mahasiswa', 'route' => 'admin.mahasiswa.index', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z'],
                            ['label' => 'Program Studi', 'route' => 'admin.program-studi.index', 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z'],
                            ['label' => 'Mata Kuliah', 'route' => 'admin.mata-kuliah.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['label' => 'Pengumuman', 'route' => 'admin.pengumuman.index', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 4v-4z'],
                            ['label' => 'Laporan', 'route' => '#', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ];
                    } else {
                        if (auth()->user()->program_studi_id) {
                            $menu = [
                                ['label' => 'Dashboard', 'route' => 'mahasiswa.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['label' => 'My Courses', 'route' => 'mahasiswa.kelas-saya', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                ['label' => 'Gradebook', 'route' => 'mahasiswa.gradebook', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                                ['label' => 'Schedule', 'route' => 'mahasiswa.schedule', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                            ];
                        } else {
                            $menu = [
                                ['label' => 'Dashboard', 'route' => 'mahasiswa.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ];
                        }
                    }
                @endphp

                @foreach ($menu as $item)
                    @php
                        $activeLabel = View::yieldContent('activeMenu') ?: '';
                        $isActive = $item['label'] === $activeLabel;
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
                @endforeach

                {{-- ===== BOTTOM SECTION: Settings, Help Center, Logout ===== --}}
                <div class="pt-4 mt-4 space-y-1" style="border-top: 1px solid {{ $sidebarBorder }};">

                    {{-- Profile / Settings per role --}}
                    @if (auth()->user()->hasRole('mahasiswa'))
                        @php $isSettingActive = request()->routeIs('mahasiswa.setting'); @endphp
                        <a
                            href="{{ route('mahasiswa.setting') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $isSettingActive ? 'sidebar-link-active' : '' }}"
                            style="{{ $isSettingActive ? 'background-color: ' . $activeBg . '; color: ' . $activeText . ';' : 'color: ' . $sidebarText . ';' }} --hover-bg: {{ $sidebarHover }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            </svg>
                            Profile
                        </a>
                    @elseif (auth()->user()->hasRole('dosen'))
                        @php $isProfilActive = request()->routeIs('dosen.profil.index'); @endphp
                        <a
                            href="{{ route('dosen.profil.index') }}"
                            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition {{ $isProfilActive ? 'sidebar-link-active' : '' }}"
                            style="{{ $isProfilActive ? 'background-color: ' . $activeBg . '; color: ' . $activeText . ';' : 'color: ' . $sidebarText . ';' }} --hover-bg: {{ $sidebarHover }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19a4 4 0 00-8 0m4-4a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            Profile
                        </a>

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
                        Help Center
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

            <header class="sticky top-0 z-30 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 px-4 py-3 sm:px-6 lg:px-8 lg:py-4 flex items-center justify-between gap-3 transition-colors duration-200">

                <button
                    type="button"
                    @click="sidebarOpen = !sidebarOpen"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition"
                    aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1 lg:hidden">
                    <p class="truncate text-sm font-bold text-gray-800 dark:text-white">@yield('title', 'Dashboard')</p>
                </div>

                <div class="hidden lg:block flex-1"></div>

                <div class="flex items-center justify-end gap-2 sm:gap-4">
                    <button class="relative w-9 h-9 rounded-full border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="min-w-0 flex items-center gap-2 bg-gray-50 dark:bg-slate-700 rounded-full pl-1 pr-2 sm:pr-4 py-1">
                        <div class="w-7 h-7 rounded-full bg-[#002B6B] flex items-center justify-center text-white text-xs font-semibold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <span class="hidden max-w-[11rem] truncate text-sm font-medium text-gray-700 dark:text-gray-200 sm:inline">{{ auth()->user()->name }}</span>
                    </div>
                </div>
            </header>

            <main class="w-full min-w-0 overflow-x-hidden p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }

        .sidebar-link:hover {
            background-color: var(--hover-bg) !important;
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>