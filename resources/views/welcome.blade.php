<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="google-site-verification" content="eoYc0OSKXoeQHJDCd5KNcR2WUtiX0btMBZYjC60GU30" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cendekia') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/logo.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @keyframes float {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-6px); }
            }
            .animate-float { animation: float 3s ease-in-out infinite; }

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(14px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up { animation: fadeInUp 0.7s ease-out both; }

            @keyframes floatSoft {
                0%, 100% { transform: translateY(0); }
                50% { transform: translateY(-7px); }
            }
            .animate-float-card {
                animation: floatSoft 2.6s ease-in-out infinite;
            }

            @media (prefers-reduced-motion: reduce) {
                .animate-float, .animate-fade-in-up, .animate-float-card { animation: none; }
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-[#f6f8fd] overflow-x-hidden min-h-[100svh] flex flex-col">

        {{-- ===== HEADER ===== --}}
        <header class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-8 py-3 sm:py-4 flex items-center justify-between gap-3">
                <a href="/" class="flex min-w-0 items-center gap-2">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 shrink-0 rounded-full bg-white flex items-center justify-center relative shadow-sm">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo Cendekia" class="w-full h-full object-contain">
                    </div>
                    <span class="text-xl sm:text-2xl font-black text-[#0f2c59] tracking-tight truncate">Cendekia</span>
                </a>

                <div class="hidden md:flex flex-1 justify-end items-center pr-12">
                    <div id="nav-container" class="relative flex items-center gap-8 text-[15px] font-semibold text-gray-500 py-2">
                        <div id="nav-indicator" class="absolute bottom-0 h-0.5 bg-[#0f2c59] transition-all duration-300 ease-out"></div>
                        <a href="{{ route('dashboard') }}" class="nav-link text-[#0f2c59] font-bold pb-1">Dashboard</a>
                    </div>
                </div>

                <div class="flex shrink-0 items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-[#0a1f3f] text-white text-xs sm:text-sm font-bold md:font-semibold px-4 sm:px-6 py-2 sm:py-2.5 rounded-md transition shadow-sm">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-blue-600 hover:bg-[#0a1f3f] text-white text-xs sm:text-sm font-semibold px-4 sm:px-6 py-2 sm:py-2.5 rounded-md transition shadow-sm">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        {{-- ===== HERO (satu layar) ===== --}}
        <main class="flex-1 w-full max-w-4xl mx-auto px-4 sm:px-6 py-5 sm:py-8 flex flex-col items-center justify-center">

            <div class="inline-flex items-center gap-1.5 bg-blue-600 text-white text-[10px] sm:text-[11px] font-medium px-3.5 sm:px-4 py-1.5 rounded-full shadow-md animate-float mb-3 sm:mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                Platform Edukasi Generasi Baru
            </div>

            <div class="w-full flex flex-col items-center text-center animate-fade-in-up">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#0f2c59] tracking-tight leading-tight">
                    Selamat Datang di <span class="text-blue-600">Cendekia</span> 👋
                </h1>
                <p class="mt-2 sm:mt-1 text-gray-500 text-sm sm:text-base md:text-lg max-w-xl">
                    Tempat belajar yang terstruktur dan menyenangkan, dirancang untuk membantumu berkembang setiap hari.
                </p>
            </div>

            {{-- Tinggi gambar dibatasi di mobile supaya semua muat satu layar --}}
            <div class="w-full max-w-4xl mt-4 md:-mt-2 flex justify-center animate-fade-in-up">
                <img src="{{ asset('images/belajar2.png') }}" alt="Ilustrasi Edukasi"
                     class="w-auto max-w-full h-auto max-h-[30svh] sm:max-h-[36svh] md:max-h-none md:w-full object-contain">
            </div>

            {{-- Tombol: mobile di bawah gambar, desktop sedikit menimpa gambar --}}
            <div class="w-full flex justify-center mt-4 md:-mt-16 animate-fade-in-up">
                @auth
                    <a href="{{ route('dashboard') }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-3 bg-blue-600 hover:bg-[#0a1f3f] text-white font-semibold text-sm sm:text-base px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl shadow-xl shadow-blue-900/30 transition transform sm:hover:-translate-y-0.5 active:scale-[0.98]">
                        Buka Dashboard
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex w-full sm:w-auto items-center justify-center gap-3 bg-blue-600 hover:bg-[#0a1f3f] text-white font-semibold text-sm sm:text-base px-6 sm:px-8 py-3.5 sm:py-4 rounded-xl shadow-xl shadow-blue-900/30 transition transform sm:hover:-translate-y-0.5 active:scale-[0.98]">
                        Mulai Belajar Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                @endauth
            </div>

            {{-- Fitur ringkas (1 baris, kecil) --}}
            @php
                $fitur = [
                    ['label' => 'Materi', 'color' => 'bg-blue-50 text-blue-600',
                     'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                    ['label' => 'Tugas', 'color' => 'bg-rose-50 text-rose-600',
                     'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                    ['label' => 'Presensi', 'color' => 'bg-emerald-50 text-emerald-600',
                     'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['label' => 'Nilai', 'color' => 'bg-violet-50 text-violet-600',
                     'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ];
            @endphp
            <div class="mt-5 sm:mt-6 grid w-full max-w-md grid-cols-4 gap-2 sm:gap-3">
                @foreach ($fitur as $f)
                    <div class="animate-float-card flex flex-col items-center gap-1.5 rounded-xl border border-slate-200/80 bg-white py-2.5 shadow-sm transition-shadow hover:shadow-lg hover:shadow-blue-900/10"
                         style="animation-delay: {{ $loop->index * 0.35 }}s">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg {{ $f['color'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}"/></svg>
                        </div>
                        <span class="text-[10px] sm:text-xs font-bold text-slate-600">{{ $f['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </main>

        {{-- ===== FOOTER ===== --}}
        <footer class="py-3 text-center text-[11px] text-gray-400">
            &copy; {{ date('Y') }} Cendekia Academic Portal
        </footer>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const links = document.querySelectorAll(".nav-link");
                const indicator = document.getElementById("nav-indicator");
                const container = document.getElementById("nav-container");

                if (!indicator || !container) return;

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

                container.addEventListener("mouseleave", () => {
                    const currentActive = document.querySelector(".nav-link.text-\\[\\#0f2c59\\]");
                    if (currentActive) {
                        moveIndicator(currentActive);
                    }
                });
            });
        </script>
    </body>
</html>