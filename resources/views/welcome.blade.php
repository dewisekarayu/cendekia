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

        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-6px); }
            }
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
<<<<<<< HEAD
            @keyframes fade-in-up {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            @keyframes slide-in-down {
                from { opacity: 0; transform: translateY(-20px); }
                to   { opacity: 1; transform: translateY(0); }
            }
            .float-slow { animation: float-slow 6s ease-in-out infinite; }
            .float-med  { animation: float-med  4s ease-in-out infinite; }
            .anim-in    { animation: fade-in-up .55s ease both; }
            .anim-in-1  { animation: fade-in-up .55s .1s ease both; }
            .anim-in-2  { animation: fade-in-up .55s .2s ease both; }
            .anim-in-3  { animation: fade-in-up .55s .3s ease both; }
            .anim-header { animation: slide-in-down .6s ease both; }
=======
>>>>>>> 0de0cef02f3af816f9dfab402c227ef6e21844ab

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(14px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.7s ease-out both;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#f6f8fd]">

        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-8 py-4 flex items-center justify-between">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-9 h-9 rounded-full bg-[#fffff] flex items-center justify-center relative shadow-sm">
                        <img src="{{ asset('images/logo.png') }}" alt="Ilustrasi Edukasi" class="">
                    </div>
                    <span class="text-2xl font-black text-[#0f2c59] tracking-tight">Cendekia</span>
                </a>

<<<<<<< HEAD
                {{-- Nav Links (hidden on mobile) --}}
                <div class="hidden md:flex flex-1 justify-center items-center">
                    <div id="nav-container" class="relative flex items-center gap-8 text-sm font-semibold text-gray-500">
                        <div id="nav-indicator" class="absolute -bottom-1 h-0.5 bg-[#002B6B] transition-all duration-300 ease-out rounded-full"></div>
                        
                        <a href="#" class="nav-link hover:text-[#002B6B] transition pb-0.5">Fitur</a>
                        <a href="#" class="nav-link hover:text-[#002B6B] transition pb-0.5">Tentang</a>
                        <a href="#" class="nav-link hover:text-[#002B6B] transition pb-0.5">Kontak</a>
=======
>>>>>>> 0de0cef02f3af816f9dfab402c227ef6e21844ab
                <div class="hidden md:flex flex-1 justify-end items-center pr-12">
                    <div id="nav-container" class="relative flex items-center gap-8 text-[15px] font-semibold text-gray-500 py-2">
                        <div id="nav-indicator" class="absolute bottom-0 h-0.5 bg-[#0f2c59] transition-all duration-300 ease-out"></div>

                        <a href="{{ route('dashboard') }}" class="nav-link text-[#0f2c59] font-bold pb-1">Dashboard</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button class="text-gray-400 hover:text-gray-600 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>

                    <div class="h-6 w-px bg-gray-200 mx-1"></div>

                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-[#0a1f3f] text-white text-sm font-black md:font-semibold px-6 py-2.5 rounded-md transition shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-[#0a1f3f] text-white text-sm font-semibold px-6 py-2.5 rounded-md transition shadow-sm">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="pt-24 pb-12 px-6 min-h-screen flex flex-col items-center justify-center relative overflow-hidden">

            {{-- Decorative background elements --}}
            <div class="absolute top-10 left-5 w-96 h-96 bg-[#002B6B]/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-20 right-5 w-96 h-96 bg-blue-400/5 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-6xl mx-auto">

                {{-- Badge --}}
                <div class="anim-in flex justify-center mb-8">
                    <div class="float-slow inline-flex items-center gap-2 bg-gradient-to-r from-emerald-50 to-blue-50 border border-emerald-200/50 rounded-full px-4 py-2 shadow-sm">
                        <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-semibold text-[#002B6B]">Platform Edukasi Generasi Baru</span>
                    </div>
                </div>

                {{-- Main Heading --}}
                <div class="anim-in-1 text-center mb-12">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-gray-900 leading-tight mb-6">
                        Belajar Lebih <span class="bg-gradient-to-r from-[#002B6B] to-blue-600 bg-clip-text text-transparent">Cerdas</span>,
                        <br>
                        Bersama <span class="bg-gradient-to-r from-blue-600 to-[#002B6B] bg-clip-text text-transparent">Cendekia.</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Platform LMS modern untuk mahasiswa, dosen, dan akademik. Kelola kelas, materi, tugas, dan nilai dari satu dasbor terintegrasi.
                    </p>
                </div>

                {{-- CTA Buttons --}}
                <div class="anim-in-2 flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                    <a href="{{ route('login') }}" class="group w-full sm:w-auto flex items-center justify-center gap-2 bg-gradient-to-r from-[#002B6B] to-blue-700 hover:shadow-2xl hover:shadow-blue-900/30 text-white font-bold py-4 px-8 rounded-xl transition-all duration-300 transform hover:scale-105">
                        <span>Mulai Belajar Sekarang</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
        <main class="max-w-4xl mx-auto px-6 py-10 flex flex-col items-center justify-center">
            <div class="w-full flex flex-col items-center">

                <div class="inline-flex items-center gap-1.5 bg-blue-600 text-white text-[11px] font-medium px-4 py-1.5 rounded-full shadow-md animate-float mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Platform Edukasi Generasi Baru
                </div>

                <div class="w-full flex flex-col items-center text-center mb-6 animate-fade-in-up">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[#0f2c59] tracking-tight leading-tight">
                        Selamat Datang di <span class="text-blue-600">Cendekia</span> 👋
                    </h1>
                    <p class="mt-1 text-gray-500 text-base md:text-lg max-w-xl">
                        Tempat belajar yang terstruktur dan menyenangkan, dirancang untuk membantumu berkembang setiap hari.
                    </p>
                </div>

                <div class="w-full max-w-xl md:max-w-4xl -mt-15 animate-fade-in-up">
                    <img src="{{ asset('images/belajar2.png') }}" alt="Ilustrasi Edukasi" class="w-full h-auto object-contain">
                </div>

                <div class="w-full flex justify-center -mt-20 animate-fade-in-up">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-3 bg-blue-600 hover:bg-[#0a1f3f] text-white font-semibold text-base px-8 py-4 rounded-xl shadow-xl shadow-blue-900/30 transition transform hover:-translate-y-0.5">
                        Mulai Belajar Sekarang
                        <svg xmlns="http://www.w3.org/2000/xl" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </main>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const links = document.querySelectorAll(".nav-link");
                const indicator = document.getElementById("nav-indicator");

                function moveIndicator(element) {
                    indicator.style.width = `${element.offsetWidth}px`;
                    indicator.style.left = `${element.offsetLeft}px`;
                }

                const activeLink = document.querySelector(".nav-link.text-\\[\\#0f2c59\\]");
                if (activeLink) {
                    moveIndicator(activeLink);
                }

                links.forEach(link => {
                    link.addEventListener("mouseenter", (e) => {
                        moveIndicator(e.target);
                    });

                    link.addEventListener("click", (e) => {
                        links.forEach(l => {
                            l.classList.remove("text-[#0f2c59]", "font-bold");
                            l.classList.add("text-gray-500");
                        });
                        e.target.classList.add("text-[#0f2c59]", "font-bold");
                        e.target.classList.remove("text-gray-500");
                        moveIndicator(e.target);
                    });
                });

                document.getElementById("nav-container").addEventListener("mouseleave", () => {
                    const currentActive = document.querySelector(".nav-link.text-\\[\\#0f2c59\\]");
                    if (currentActive) {
                        moveIndicator(currentActive);
                    }
                });
            });
        </script>
    </body>
</html>