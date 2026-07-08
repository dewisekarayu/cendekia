<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Cendekia') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50%       { transform: translateY(-12px) rotate(2deg); }
        }
        @keyframes float-med {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-8px); }
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .float-slow { animation: float-slow 6s ease-in-out infinite; }
        .float-med  { animation: float-med  4s ease-in-out infinite; }
        .anim-in    { animation: fade-in-up .55s ease both; }
        .anim-in-1  { animation: fade-in-up .55s .1s ease both; }
        .anim-in-2  { animation: fade-in-up .55s .2s ease both; }
        .anim-in-3  { animation: fade-in-up .55s .3s ease both; }
        .anim-in-4  { animation: fade-in-up .55s .4s ease both; }
    </style>
</head>
<body class="font-sans antialiased bg-[#f0f4fb]">

<div class="min-h-screen flex">

    {{-- ===== LEFT PANEL (Branding) - hidden on mobile ===== --}}
    <div class="hidden lg:flex lg:w-[52%] xl:w-[55%] flex-col relative overflow-hidden
                bg-gradient-to-br from-[#001a4d] via-[#002B6B] to-[#003d99]">

        {{-- Decorative circles --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute top-1/3 -right-16 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute -bottom-20 left-1/4 w-80 h-80 rounded-full bg-white/5 pointer-events-none"></div>

        {{-- Dot grid pattern --}}
        <div class="absolute inset-0 pointer-events-none opacity-[0.06]"
             style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px);
                    background-size: 32px 32px;"></div>

        {{-- Content --}}
        <div class="relative z-10 flex flex-col h-full px-12 py-10">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Cendekia" class="w-7 h-7 object-contain">
                </div>
                <span class="text-xl font-black text-white tracking-tight">Cendekia</span>
            </div>

            {{-- Main content --}}
            <div class="flex-1 flex flex-col justify-center">

                {{-- Floating card badges --}}
                <div class="relative mb-8">
                    {{-- Stats card 1 --}}
                    <div class="float-slow absolute -top-2 right-8 z-10 flex items-center gap-3 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-sm px-4 py-3 shadow-xl">
                        <div class="w-9 h-9 rounded-xl bg-emerald-400/20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">200+ Mahasiswa</p>
                            <p class="text-[10px] text-white/60">Aktif Semester Ini</p>
                        </div>
                    </div>

                    {{-- Stats card 2 --}}
                    <div class="float-med absolute bottom-0 left-0 z-10 flex items-center gap-3 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-sm px-4 py-3 shadow-xl">
                        <div class="w-9 h-9 rounded-xl bg-amber-400/20 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">40+ Mata Kuliah</p>
                            <p class="text-[10px] text-white/60">Lintas Program Studi</p>
                        </div>
                    </div>

                    {{-- Illustration --}}
                    <div class="flex justify-center px-8 pt-8 pb-16">
                        <img src="{{ asset('images/belajar1.png') }}" alt="Ilustrasi Cendekia"
                             class="w-full max-w-sm drop-shadow-2xl">
                    </div>
                </div>

                {{-- Headline --}}
                <div class="mt-4">
                    <h1 class="text-3xl xl:text-4xl font-black text-white leading-tight">
                        Belajar Lebih Cerdas,<br>
                        <span class="text-blue-300">Bersama Cendekia.</span>
                    </h1>
                    <p class="mt-4 text-sm leading-relaxed text-blue-100/70 max-w-sm">
                        Platform LMS modern untuk mahasiswa, dosen, dan akademik. Kelola kelas, materi, tugas, dan nilai dari satu dasbor terintegrasi.
                    </p>
                </div>

                {{-- Feature pills --}}
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach (['Manajemen Kelas', 'Upload Materi', 'Forum Diskusi', 'Gradebook Nilai', 'Jadwal Kuliah'] as $f)
                        <span class="inline-flex items-center gap-1.5 rounded-xl border border-white/15 bg-white/8 px-3 py-1.5 text-[11px] font-semibold text-white/80">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $f }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- Footer --}}
            <div class="pt-6 border-t border-white/10">
                <p class="text-[11px] text-white/40">© {{ date('Y') }} Cendekia · Platform Akademik Digital</p>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT PANEL (Form) ===== --}}
    <div class="flex-1 flex flex-col justify-center items-center px-6 py-10 sm:px-10 lg:px-16 xl:px-20 bg-white min-h-screen">

        {{-- Mobile logo (only visible on small screens) --}}
        <div class="lg:hidden mb-8 flex flex-col items-center">
            <div class="w-12 h-12 rounded-2xl bg-[#002B6B] flex items-center justify-center shadow-lg shadow-blue-900/30 mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Cendekia" class="w-7 h-7 object-contain">
            </div>
            <h1 class="text-xl font-black text-[#002B6B]">Cendekia</h1>
            <p class="text-xs text-gray-400 mt-0.5">Platform Akademik Digital</p>
        </div>

        {{-- Form slot --}}
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>

        {{-- Bottom link --}}
        <p class="mt-8 text-[11px] text-gray-400 text-center">
            Butuh bantuan? Hubungi
            <a href="mailto:admin@cendekia.ac.id" class="text-[#002B6B] font-semibold hover:underline">admin@cendekia.ac.id</a>
        </p>
    </div>

</div>
</body>
</html>
