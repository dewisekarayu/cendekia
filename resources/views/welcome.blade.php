<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cendekia') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-indigo-50/40">

        <!-- Navbar -->
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-900 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6l9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-blue-900">Cendekia</span>
                </a>

                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-600">
                    <a href="{{ route('dashboard') }}" class="text-blue-900 font-semibold border-b-2 border-blue-900 pb-1">Dashboard</a>
                    <a href="#" class="hover:text-blue-900 transition">Courses</a>
                    <a href="#" class="hover:text-blue-900 transition">Assignments</a>
                    <a href="#" class="hover:text-blue-900 transition">Calendar</a>
                </nav>

                <div class="flex items-center gap-4">
                    <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-900 hover:bg-blue-800 text-white text-sm font-medium px-5 py-2 rounded-md transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-900 hover:bg-blue-800 text-white text-sm font-medium px-5 py-2 rounded-md transition">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="max-w-2xl mx-auto px-6 py-14 flex flex-col items-center text-center gap-5">

            <!-- Badge -->
            <div class="inline-flex items-center gap-1.5 bg-blue-900 text-white text-xs font-medium px-3 py-1.5 rounded-full shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                </svg>
                Platform Edukasi Generasi Baru
            </div>

            <!-- Ilustrasi -->
            <div class="w-full max-w-lg">
                <img src="{{ asset('images/belajar.png') }}" alt="Ilustrasi Edukasi" class="w-full h-auto">
            </div>

            <!-- CTA Button -->
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 bg-blue-900 hover:bg-blue-800 text-white font-semibold px-8 py-3.5 rounded-lg shadow-lg shadow-blue-900/20 transition">
                Mulai Belajar Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </main>

    </body>
</html>