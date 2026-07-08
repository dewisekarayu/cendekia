<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cendekia') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

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

            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(14px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.7s ease-out both;
            }
>>>>>>> 3ecbb9aa1ea688fe4e744016f1a5a2612a5c8395
        </style>
    </head>
    <body class="font-sans antialiased bg-[#f0f4fb] overflow-x-hidden">

        {{-- Header --}}
        <header class="anim-header fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100/50">
            <div class="max-w-7xl mx-auto px-6 sm:px-8 py-4 flex items-center justify-between">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2.5 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#002B6B] to-[#001a4d] flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow">
                        <img src="{{ asset('images/logo.png') }}" alt="Cendekia" class="w-6 h-6 object-contain">
                    </div>
                    <span class="text-xl font-black text-[#002B6B] tracking-tight hidden sm:inline">Cendekia</span>
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
                <div class="hidden md:flex flex-1 justify-end items-center pr-12">
                    <div id="nav-container" class="relative flex items-center gap-8 text-[15px] font-semibold text-gray-500 py-2">
                        <div id="nav-indicator" class="absolute bottom-0 h-0.5 bg-[#0f2c59] transition-all duration-300 ease-out"></div>

                        <a href="{{ route('dashboard') }}" class="nav-link text-[#0f2c59] font-bold pb-1">Dashboard</a>
>>>>>>> 3ecbb9aa1ea688fe4e744016f1a5a2612a5c8395
                    </div>
                </div>

                {{-- Auth Buttons --}}
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold text-[#002B6B] hover:bg-[#002B6B]/5 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold text-[#002B6B] hover:bg-[#002B6B]/5 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Masuk
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex sm:hidden items-center justify-center gap-1.5 px-3.5 py-2 rounded-lg text-sm font-semibold text-white bg-[#002B6B] hover:bg-[#001a4d] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </header>

<<<<<<< HEAD
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
=======
        <main class="max-w-4xl mx-auto px-6 py-10 flex flex-col items-center justify-center">
            <div class="w-full flex flex-col items-center">

                <div class="inline-flex items-center gap-1.5 bg-[#0f2c59] text-white text-[11px] font-medium px-4 py-1.5 rounded-full shadow-md animate-float mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    Platform Edukasi Generasi Baru
                </div>

                <div class="w-full flex flex-col items-center text-center mb-6 animate-fade-in-up">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-[#0f2c59] tracking-tight leading-tight">
                        Selamat Datang di <span class="text-[#0f2c59]">Cendekia</span> 👋
                    </h1>
                    <p class="mt-1 text-gray-500 text-base md:text-lg max-w-xl">
                        Tempat belajar yang terstruktur dan menyenangkan, dirancang untuk membantumu berkembang setiap hari.
                    </p>
                </div>

                <div class="w-full max-w-xl md:max-w-4xl -mt-15 animate-fade-in-up">
                    <img src="{{ asset('images/belajar2.png') }}" alt="Ilustrasi Edukasi" class="w-full h-auto object-contain">
                </div>

                <div class="w-full flex justify-center -mt-20 animate-fade-in-up">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-3 bg-[#0f2c59] hover:bg-[#0a1f3f] text-white font-semibold text-base px-8 py-4 rounded-xl shadow-xl shadow-blue-900/30 transition transform hover:-translate-y-0.5">
                        Mulai Belajar Sekarang
                        <svg xmlns="http://www.w3.org/2000/xl" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
>>>>>>> 3ecbb9aa1ea688fe4e744016f1a5a2612a5c8395
                        </svg>
                    </a>
                    <a href="#" class="w-full sm:w-auto flex items-center justify-center gap-2 border-2 border-gray-300 hover:border-[#002B6B] text-gray-700 hover:text-[#002B6B] font-bold py-4 px-8 rounded-xl transition-all duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Lihat Demo</span>
                    </a>
                </div>

                {{-- Features Grid --}}
                <div class="anim-in-3 grid md:grid-cols-2 gap-6 mb-16">
                    <div class="group bg-white/80 backdrop-blur border border-gray-200/50 rounded-2xl p-6 hover:shadow-xl hover:border-[#002B6B]/20 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-[#002B6B] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#002B6B] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Manajemen Kelas</h3>
                                <p class="text-sm text-gray-600">Kelola kelas dengan mudah, dari pengaturan hingga monitoring kehadiran mahasiswa.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white/80 backdrop-blur border border-gray-200/50 rounded-2xl p-6 hover:shadow-xl hover:border-[#002B6B]/20 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center group-hover:bg-[#002B6B] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#002B6B] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Upload Materi</h3>
                                <p class="text-sm text-gray-600">Bagikan materi pembelajaran dengan format beragam: PDF, video, dokumen, dan lainnya.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white/80 backdrop-blur border border-gray-200/50 rounded-2xl p-6 hover:shadow-xl hover:border-[#002B6B]/20 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-[#002B6B] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#002B6B] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Forum Diskusi</h3>
                                <p class="text-sm text-gray-600">Fasilitasi diskusi dan kolaborasi antara mahasiswa dan dosen secara real-time.</p>
                            </div>
                        </div>
                    </div>

                    <div class="group bg-white/80 backdrop-blur border border-gray-200/50 rounded-2xl p-6 hover:shadow-xl hover:border-[#002B6B]/20 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center group-hover:bg-[#002B6B] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#002B6B] group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 mb-2">Gradebook Nilai</h3>
                                <p class="text-sm text-gray-600">Kelola penilaian mahasiswa dengan sistem gradebook yang komprehensif dan transparan.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Illustration & Stats --}}
                <div class="anim-in-3 grid md:grid-cols-2 gap-12 items-center">
                    <div class="flex justify-center md:justify-end">
                        <img src="{{ asset('images/belajar1.png') }}" alt="Ilustrasi Cendekia" class="w-full max-w-md drop-shadow-2xl">
                    </div>
                    <div class="space-y-6">
                        <div class="bg-white/80 backdrop-blur border border-gray-200/50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-500 flex items-center justify-center text-white text-2xl font-bold">200+</div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Mahasiswa Aktif</p>
                                    <p class="text-xs text-gray-500">Terdaftar setiap semester</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/80 backdrop-blur border border-gray-200/50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-amber-400 to-amber-500 flex items-center justify-center text-white text-2xl font-bold">40+</div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Mata Kuliah</p>
                                    <p class="text-xs text-gray-500">Lintas program studi</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/80 backdrop-blur border border-gray-200/50 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-400 to-blue-500 flex items-center justify-center text-white text-2xl font-bold">50+</div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">Dosen Pengajar</p>
                                    <p class="text-xs text-gray-500">Berkualifikasi tinggi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- Footer --}}
        <footer class="border-t border-gray-200 bg-white/50 backdrop-blur py-8 text-center text-sm text-gray-500">
            <p>© {{ date('Y') }} <strong>Cendekia</strong> · Platform Akademik Digital · <a href="mailto:admin@cendekia.ac.id" class="text-[#002B6B] font-semibold hover:underline">Hubungi Kami</a></p>
        </footer>

        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const links = document.querySelectorAll(".nav-link");
                const indicator = document.getElementById("nav-indicator");

                if (!indicator) return;

                function moveIndicator(element) {
                    indicator.style.width = `${element.offsetWidth}px`;
                    indicator.style.left = `${element.offsetLeft}px`;
                }

                links.forEach(link => {
                    link.addEventListener("mouseenter", (e) => {
                        moveIndicator(e.target);
                    });
                });

                document.getElementById("nav-container")?.addEventListener("mouseleave", () => {
                    const firstLink = links[0];
                    if (firstLink) moveIndicator(firstLink);
                });
            });
        </script>
    </body>
</html>
