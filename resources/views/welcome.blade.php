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

                <div class="hidden md:flex flex-1 justify-end items-center pr-12">
                    <div id="nav-container" class="relative flex items-center gap-8 text-[15px] font-semibold text-gray-500 py-2">
                        <div id="nav-indicator" class="absolute bottom-0 h-0.5 bg-[#0f2c59] transition-all duration-300 ease-out"></div>
                        
                        <a href="{{ route('dashboard') }}" class="nav-link text-[#0f2c59] font-bold pb-1">Dashboard</a>
                        <a href="#" class="nav-link hover:text-[#0f2c59] transition pb-1">Courses</a>
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
                        <a href="{{ route('dashboard') }}" class="bg-[#0f2c59] hover:bg-[#0a1f3f] text-white text-sm font-semibold px-6 py-2.5 rounded-md transition shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-[#0f2c59] hover:bg-[#0a1f3f] text-white text-sm font-semibold px-6 py-2.5 rounded-md transition shadow-sm">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <main class="max-w-4xl mx-auto px-6 py-12 flex flex-col items-center justify-center">
            <div class="relative w-full flex flex-col items-center">
                
                <div class="absolute top-[10%] z-10 inline-flex items-center gap-1.5 bg-[#0f2c59] text-white text-[11px] font-medium px-4 py-1.5 rounded-full shadow-md animate-float">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Platform Edukasi Generasi Baru
                </div>

                <div class="w-full max-w-2xl">
                    <img src="{{ asset('images/belajar1.png') }}" alt="Ilustrasi Edukasi" class="w-full h-auto object-contain">
                </div>

                <div class="absolute bottom-[2%] z-10 w-full flex justify-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-3 bg-[#0f2c59] hover:bg-[#0a1f3f] text-white font-semibold text-base px-8 py-4 rounded-xl shadow-xl shadow-blue-900/30 transition transform hover:-translate-y-0.5">
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