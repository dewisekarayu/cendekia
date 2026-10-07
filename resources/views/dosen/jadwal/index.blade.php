@extends('layouts.portal')

@section('title', 'Jadwal & Log Mengajar')
@section('activeMenu', 'Jadwal & Log Mengajar')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto py-2 sm:py-4"
     x-data="jadwalMengajarData()">

    {{-- HERO BANNER --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#260c5a] via-[#3a1480] to-[#511da8] dark:from-slate-900 dark:via-indigo-950 dark:to-purple-950 px-6 py-6 sm:px-8 sm:py-7 shadow-md text-white">
        <div class="pointer-events-none absolute -right-12 -top-12 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -left-10 -bottom-10 h-40 w-40 rounded-full bg-purple-400/10 blur-xl"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-3 py-1 text-xs font-semibold text-purple-200 backdrop-blur-sm mb-2 border border-white/10">
                    <svg class="w-3.5 h-3.5 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Portal Akademik Dosen &middot; Semester Aktif</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight flex items-center gap-2.5">
                    Jadwal & Log Mengajar
                </h1>
                <p class="mt-2 text-xs sm:text-sm text-purple-100/80 leading-relaxed">
                    Kelola jadwal perkuliahan mingguan, agenda mengajar harian, serta rekapitulasi sesi log presensi mahasiswa dalam satu halaman terpadu.
                </p>
            </div>
        </div>
    </div>

    {{-- STATISTIK OVERVIEW --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
        {{-- Total Kelas --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-[#321270]/30 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Total Kelas</p>
                    <p class="mt-1 text-2xl font-black text-slate-800 dark:text-white">{{ $totalKelas }}</p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Kelas Aktif Semester Ini</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Beban SKS --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-[#321270]/30 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Beban Mengajar</p>
                    <p class="mt-1 text-2xl font-black text-slate-800 dark:text-white">{{ $totalSKS }} <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">SKS</span></p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Akumulasi Kredit Mata Kuliah</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-950/50 dark:text-purple-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Total Mahasiswa --}}
        <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shadow-sm hover:border-[#321270]/30 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400">Total Mahasiswa</p>
                    <p class="mt-1 text-2xl font-black text-slate-800 dark:text-white">{{ $totalMahasiswa }}</p>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Peserta Didik Terdaftar</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- HIGHLIGHT AGENDA HARI INI (IF ANY) --}}
    @if($kelasHariIni->isNotEmpty())
        <div class="rounded-2xl border border-purple-200/80 dark:border-purple-800/40 bg-gradient-to-r from-purple-50/90 via-indigo-50/70 to-white dark:from-purple-950/40 dark:via-slate-800 dark:to-slate-800 p-4 sm:p-5 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-3 pb-3 border-b border-purple-100 dark:border-slate-700">
                <div class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#321270] text-white shadow-sm">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="rounded bg-[#321270] px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white">Hari Ini</span>
                            <span class="text-xs font-bold text-[#321270] dark:text-purple-300">{{ $todayName }}, {{ now()->translatedFormat('d F Y') }}</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">
                            Anda memiliki <strong>{{ $kelasHariIni->count() }} sesi kelas tatap muka</strong> yang terjadwal hari ini.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($kelasHariIni as $kelas)
                    <div class="rounded-xl border border-purple-100 dark:border-slate-700 bg-white/90 dark:bg-slate-900/90 p-3.5 shadow-sm flex flex-col justify-between hover:shadow-md transition">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <span class="rounded-md bg-purple-100 dark:bg-purple-950/80 px-2 py-0.5 text-[11px] font-bold text-[#321270] dark:text-purple-300">
                                    {{ $kelas->kode_kelas }}
                                </span>
                                <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}
                                </span>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-white line-clamp-1">{{ $kelas->mataKuliah->nama_mk }}</h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $kelas->ruangan ?: 'Ruang Belum Ditentukan' }} &middot; {{ $kelas->mahasiswa->count() }} Mhs
                            </p>
                        </div>
                        <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between gap-2">
                            <a href="{{ route('dosen.kelas-detail', $kelas->id) }}"
                               class="text-xs font-bold text-[#321270] dark:text-purple-300 hover:underline">
                                Detail Kelas &rarr;
                            </a>
                            <button type="button"
                                    @click="openLogForClass('{{ md5($kelas->kode_kelas) }}')"
                                    class="inline-flex items-center gap-1 rounded-lg bg-[#321270] hover:bg-[#260c5a] px-2.5 py-1 text-[11px] font-bold text-white transition shadow-sm">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <span>Buka Log</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- TAB SWITCHER & QUICK SEARCH BAR --}}
    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-2 sm:p-3 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            {{-- Tabs --}}
            <div class="flex items-center gap-1.5 p-1 bg-slate-100 dark:bg-slate-900 rounded-xl overflow-x-auto">
                <button type="button"
                        @click="switchTab('jadwal')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-bold transition whitespace-nowrap"
                        :class="activeTab === 'jadwal' ? 'bg-[#321270] text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Jadwal Mengajar</span>
                    <span class="rounded-full px-1.5 py-0.2 text-[10px] font-extrabold"
                          :class="activeTab === 'jadwal' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300'">
                        {{ $totalKelas }}
                    </span>
                </button>

                <button type="button"
                        @click="switchTab('log')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs font-bold transition whitespace-nowrap"
                        :class="activeTab === 'log' ? 'bg-[#321270] text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>Log Mengajar & Presensi</span>
                    <span class="rounded-full px-1.5 py-0.2 text-[10px] font-extrabold"
                          :class="activeTab === 'log' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-300'">
                        {{ $totalSesi }} Sesi
                    </span>
                </button>

                <button type="button"
                        @click="switchTab('semua')"
                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-bold transition whitespace-nowrap hidden sm:inline-flex"
                        :class="activeTab === 'semua' ? 'bg-[#321270] text-white shadow-sm' : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span>Tampilkan Keduanya</span>
                </button>
            </div>

            {{-- Filter Pencarian --}}
            <div class="relative w-full md:w-72">
                <input type="text"
                       x-model="searchQuery"
                       placeholder="Cari mata kuliah, kode, kelas..."
                       class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3.5 py-2 pl-9 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:border-[#321270] focus:ring-1 focus:ring-[#321270] transition" />
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="button" x-show="searchQuery" @click="searchQuery = ''" class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600 text-xs">
                    &times;
                </button>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- SECTION 1: JADWAL MENGAJAR (TIMETABLE BY DAY)            --}}
    {{-- ======================================================== --}}
    <section x-show="activeTab === 'jadwal' || activeTab === 'semua'" x-transition class="space-y-4">
        
        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    Jadwal Perkuliahan Mingguan
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Daftar kelas yang Anda ampu dikelompokkan berdasarkan hari perkuliahan.</p>
            </div>

            {{-- Day Pills Filter --}}
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1">
                <button type="button"
                        @click="selectedDay = 'Semua'"
                        class="px-3 py-1 rounded-lg text-xs font-bold transition whitespace-nowrap"
                        :class="selectedDay === 'Semua' ? 'bg-[#321270] text-white shadow-sm' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50'">
                    Semua Hari ({{ $totalKelas }})
                </button>
                @foreach($days as $day)
                    @php $countDay = $jadwalByDay[$day]->count(); @endphp
                    @if($countDay > 0)
                        <button type="button"
                                @click="selectedDay = '{{ $day }}'"
                                class="px-2.5 py-1 rounded-lg text-xs font-bold transition whitespace-nowrap flex items-center gap-1.5"
                                :class="selectedDay === '{{ $day }}' ? 'bg-[#321270] text-white shadow-sm' : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50'">
                            <span>{{ $day }}</span>
                            <span class="rounded-full px-1.5 py-0.2 text-[10px]"
                                  :class="selectedDay === '{{ $day }}' ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300'">
                                {{ $countDay }}
                            </span>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        @if($kelasPerkuliahan->isEmpty())
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 text-center shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-400 mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-700 dark:text-white">Belum Ada Jadwal Mengajar</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">Anda belum tercatat sebagai dosen pengampu pada kelas perkuliahan aktif di semester ini.</p>
            </div>
        @else
            {{-- KELAS PENGGANTI (RESCHEDULE) SECTION --}}
            @if(isset($reschedules) && $reschedules->isNotEmpty())
                <div class="mb-6 rounded-2xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-950/20 p-4 shadow-sm" x-show="selectedDay === 'Semua'">
                    <div class="flex items-center gap-2.5 mb-4">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600 dark:bg-blue-900/50 dark:text-blue-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-sm font-extrabold text-blue-800 dark:text-blue-300">Jadwal Kelas Pengganti</h3>
                            <p class="text-[11px] text-blue-700/80 dark:text-blue-400/80 mt-0.5">Daftar kelas reschedule yang akan datang.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach($reschedules as $reschedule)
                            @php
                                $kelas = $reschedule->kelasPerkuliahan;
                            @endphp
                            <div class="relative rounded-xl border border-blue-200 dark:border-blue-800 bg-white dark:bg-slate-900 p-4 shadow-sm hover:shadow-md transition group overflow-hidden">
                                <div class="absolute top-0 right-0 p-3">
                                    <span class="animate-pulse inline-flex items-center gap-1 rounded-md bg-indigo-100 dark:bg-indigo-900 px-2 py-0.5 text-[10px] font-bold text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        Kelas Pengganti
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 mb-2">
                                    <span class="rounded bg-indigo-100 dark:bg-indigo-900/50 px-2 py-1 text-xs font-bold text-indigo-700 dark:text-indigo-300">
                                        {{ \Carbon\Carbon::parse($reschedule->tanggal)->translatedFormat('l, d F Y') }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white line-clamp-1 pr-24">{{ $kelas->mataKuliah->nama_mk }}</h4>
                                <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-1 mb-2">Kode: {{ $kelas->kode_kelas }}</p>

                                <div class="space-y-1.5 border-t border-slate-100 dark:border-slate-800 pt-3">
                                    <p class="text-xs text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="font-medium text-blue-600 dark:text-blue-400">{{ substr($reschedule->jam_mulai, 0, 5) }} - {{ substr($reschedule->jam_selesai, 0, 5) }}</span>
                                    </p>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        {{ $reschedule->ruangan_pengganti }}
                                    </p>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 flex items-start gap-1.5 mt-2 bg-blue-50/50 dark:bg-blue-900/10 p-2 rounded-lg border border-blue-100 dark:border-blue-800/30">
                                        <svg class="w-3.5 h-3.5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span class="text-[11px] leading-tight text-blue-800 dark:text-blue-200">Alasan: {{ $reschedule->alasan_pengganti }}</span>
                                    </p>

                                    <div class="mt-4 pt-3 border-t border-blue-100 dark:border-blue-800/50 text-right">
                                        <form action="{{ route('dosen.jadwal.undo-reschedule', $reschedule->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal kelas pengganti ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-1.5 text-[11px] font-bold text-red-600 hover:bg-red-100 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/40 transition">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                                                Batal Reschedule
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="space-y-4">
                @foreach($days as $day)
                    @if($jadwalByDay[$day]->isNotEmpty())
                        <div x-show="selectedDay === 'Semua' || selectedDay === '{{ $day }}'" x-transition
                             class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
                            {{-- Day Header --}}
                            <div class="bg-slate-50 dark:bg-slate-900/60 px-5 py-3 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-[#321270] dark:bg-purple-400"></span>
                                    <h3 class="font-extrabold text-sm sm:text-base text-slate-800 dark:text-white">{{ $day }}</h3>
                                    @if(strtolower(trim($day)) === strtolower(trim($todayName)))
                                        <span class="rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 text-[10px] font-bold px-2 py-0.5">Hari Ini</span>
                                    @endif
                                </div>
                                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                    {{ $jadwalByDay[$day]->count() }} Kelas Terjadwal
                                </span>
                            </div>

                            {{-- Class list for this day --}}
                            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($jadwalByDay[$day] as $kelas)
                                    @php
                                        $courseSearchString = strtolower($kelas->mataKuliah->nama_mk . ' ' . $kelas->kode_kelas . ' ' . ($kelas->ruangan ?? '') . ' ' . ($kelas->programStudi->nama_prodi ?? ''));
                                    @endphp
                                    <div x-show="matchesSearch('{{ addslashes($courseSearchString) }}')"
                                         class="p-5 hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            {{-- Top metadata badges --}}
                                            <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                                <span class="rounded-md bg-[#321270]/10 text-[#321270] dark:bg-purple-950/80 dark:text-purple-300 text-xs font-bold px-2.5 py-0.5 border border-[#321270]/20">
                                                    Kelas {{ $kelas->kode_kelas }}
                                                </span>
                                                @if($kelas->mataKuliah->sks)
                                                    <span class="rounded-md bg-purple-50 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 text-xs font-bold px-2 py-0.5">
                                                        {{ $kelas->mataKuliah->sks }} SKS
                                                    </span>
                                                @endif
                                                <span class="rounded-md bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 text-xs font-bold px-2 py-0.5">
                                                    {{ $kelas->mahasiswa->count() }} / {{ $kelas->kuota_mahasiswa ?: 40 }} Mahasiswa
                                                </span>
                                                @if($kelas->absensi->count() > 0)
                                                    <span class="rounded-md bg-amber-50 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 text-xs font-bold px-2 py-0.5">
                                                        {{ $kelas->absensi->count() }} Pertemuan Tercatat
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Course Title --}}
                                            <h4 class="text-base font-extrabold text-slate-800 dark:text-white leading-tight">
                                                {{ $kelas->mataKuliah->nama_mk }}
                                                <span class="text-xs font-normal text-slate-400 dark:text-slate-500">({{ $kelas->mataKuliah->kode_mk }})</span>
                                            </h4>

                                            {{-- Details Row --}}
                                            <div class="mt-2 flex flex-wrap items-center gap-4 text-xs text-slate-600 dark:text-slate-300">
                                                {{-- Time --}}
                                                <div class="flex items-center gap-1.5 font-bold text-slate-700 dark:text-slate-200">
                                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    <span>{{ substr($kelas->jam_mulai, 0, 5) }} &ndash; {{ substr($kelas->jam_selesai, 0, 5) }} WIB</span>
                                                </div>

                                                {{-- Room --}}
                                                <div class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                    <span>{{ $kelas->ruangan ?: 'Ruang Belum Diatur' }}</span>
                                                </div>

                                                {{-- Prodi --}}
                                                @if($kelas->mataKuliah->programStudi)
                                                    <div class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z"/></svg>
                                                        <span>{{ $kelas->mataKuliah->programStudi->nama_prodi }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Team Teaching if any --}}
                                            @if($kelas->dosenPengampuTambahan && $kelas->dosenPengampuTambahan->isNotEmpty())
                                                <div class="mt-2 flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                                    <span class="font-semibold text-slate-600 dark:text-slate-300">Team Teaching:</span>
                                                    @foreach($kelas->dosenPengampuTambahan as $dosenTambahan)
                                                        <span class="rounded bg-slate-100 dark:bg-slate-700 px-2 py-0.5 text-[11px] font-medium text-slate-700 dark:text-slate-200">
                                                            {{ $dosenTambahan->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Reschedule Button --}}
                                        <div class="flex items-center gap-2 shrink-0 self-start mt-1 lg:mt-0">
                                            <button type="button"
                                                    @click="rescheduleKelasId = {{ $kelas->id }}; rescheduleNamaMk = '{{ addslashes($kelas->mataKuliah->nama_mk) }}'; rescheduleKodeKelas = '{{ addslashes($kelas->kode_kelas) }}'; showRescheduleModal = true"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-950/40 px-3 py-1.5 text-xs font-bold text-orange-700 dark:text-orange-300 hover:bg-orange-100 dark:hover:bg-orange-900/60 transition shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                <span>Reschedule</span>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </section>

    {{-- ======================================================== --}}
    {{-- SECTION 2: LOG MENGAJAR & PRESENSI (ACCORDION PER KELAS)  --}}
    {{-- ======================================================== --}}
    <section x-show="activeTab === 'log' || activeTab === 'semua'" x-transition class="space-y-4">
        
        {{-- Section Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-lg font-extrabold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                    Log Mengajar & Rekap Presensi
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Klik kelas untuk melihat daftar mata kuliah, rincian pertemuan, rasio kehadiran, serta kelola sesi secara langsung.
                </p>
            </div>
        </div>

        {{-- Filter Bulan Sesi Mengajar --}}
        @if(isset($availableMonths) && count($availableMonths) > 0)
            <div class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-3 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-700 dark:text-slate-300 shrink-0">
                    <span class="flex h-6 w-6 items-center justify-center rounded-md bg-purple-100 dark:bg-purple-950 text-[#321270] dark:text-purple-300">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    <span>Filter Rekap Per Bulan:</span>
                </div>

                <div class="flex items-center gap-2 w-full sm:w-64">
                    <select x-model="selectedMonth"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3.5 py-2 text-xs font-semibold text-slate-800 dark:text-white focus:border-[#321270] focus:ring-1 focus:ring-[#321270] transition">
                        <option value="Semua">Semua Bulan ({{ $totalSesi }})</option>
                        @foreach($availableMonths as $m)
                            <option value="{{ $m['key'] }}">{{ $m['label'] }} ({{ $m['count'] }})</option>
                        @endforeach
                    </select>

                    <button type="button" x-show="selectedMonth !== 'Semua'" @click="selectedMonth = 'Semua'"
                            class="shrink-0 text-xs font-bold text-slate-400 hover:text-slate-600 dark:hover:text-white" title="Reset filter">
                        &times;
                    </button>
                </div>
            </div>
        @endif

        @if($kelasList->isNotEmpty())
            <div class="space-y-3">
                @foreach($kelasList as $kelasGrup)
                    @php
                        $searchableClass = strtolower($kelasGrup->kode . ' ' . $kelasGrup->mata_kuliah->pluck('mataKuliah.nama_mk')->implode(' '));
                        $classMonths = $kelasGrup->mata_kuliah->flatMap->absensi
                            ->filter(fn($a) => !empty($a->tanggal))
                            ->map(fn($a) => \Carbon\Carbon::parse($a->tanggal)->format('m'))
                            ->unique()
                            ->implode(',');
                    @endphp
                    <div id="log-kelas-{{ $kelasGrup->id }}"
                         x-show="classMatchesMonth('{{ $classMonths }}') && matchesSearch('{{ addslashes($searchableClass) }}')"
                         class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden transition">
                        
                        {{-- Accordion Header Bar --}}
                        <div class="px-5 py-4 cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/40 transition flex items-center justify-between gap-4 select-none"
                             @click="openKelasId = openKelasId === '{{ $kelasGrup->id }}' ? null : '{{ $kelasGrup->id }}'">
                            
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl transition"
                                      :class="openKelasId === '{{ $kelasGrup->id }}' ? 'bg-[#321270] text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300'">
                                    <svg class="h-4 w-4 transition-transform duration-200"
                                         :class="openKelasId === '{{ $kelasGrup->id }}' ? 'rotate-90' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-extrabold text-base text-slate-800 dark:text-white">
                                            Kelas {{ $kelasGrup->kode }}
                                        </h3>
                                        <span class="rounded-full bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 text-[10px] font-bold px-2 py-0.5 border border-indigo-200/50">
                                            {{ $kelasGrup->jumlah_mata_kuliah }} Mata Kuliah
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs font-semibold text-slate-400 hidden sm:inline"
                                      x-text="openKelasId === '{{ $kelasGrup->id }}' ? 'Tutup Rincian' : 'Buka Rincian'">
                                </span>
                            </div>
                        </div>

                        {{-- Accordion Body (Courses within this class) --}}
                        <div x-show="openKelasId === '{{ $kelasGrup->id }}'" x-cloak x-transition
                             class="border-t border-slate-100 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40 p-4 sm:p-5 space-y-5">
                            
                            @foreach($kelasGrup->mata_kuliah as $kelasItem)
                                @php
                                    $itemMonths = $kelasItem->absensi
                                        ->filter(fn($a) => !empty($a->tanggal))
                                        ->map(fn($a) => \Carbon\Carbon::parse($a->tanggal)->format('m'))
                                        ->unique()
                                        ->implode(',');
                                @endphp
                                <div x-show="classMatchesMonth('{{ $itemMonths }}')"
                                     class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm overflow-hidden">
                                    {{-- Course Header within Class --}}
                                    <div class="bg-indigo-50/70 dark:bg-slate-800/80 px-4 py-3 border-b border-indigo-100/60 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-sm font-extrabold text-indigo-900 dark:text-white">
                                                    {{ $kelasItem->mataKuliah->nama_mk }}
                                                </h4>
                                                <span class="rounded bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 text-[10px] font-bold px-1.5 py-0.5">
                                                    {{ $kelasItem->mataKuliah->kode_mk }}
                                                </span>
                                            </div>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                {{ $kelasItem->mahasiswa->count() }} mahasiswa terdaftar &middot; Jadwal: {{ $kelasItem->hari }}, {{ substr($kelasItem->jam_mulai, 0, 5) }}-{{ substr($kelasItem->jam_selesai, 0, 5) }} &middot; Ruang: {{ $kelasItem->ruangan ?: '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Sessions Table --}}
                                    @if($kelasItem->absensi->isNotEmpty())
                                        <div class="overflow-x-auto">
                                            <table class="w-full min-w-[700px] border-collapse text-left">
                                                 <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                                                    <tr>
                                                        <th class="w-14 px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-slate-400">Pert.</th>
                                                        <th class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Tanggal & Waktu</th>
                                                        <th class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Status Sesi</th>
                                                        <th class="px-4 py-2.5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Rasio Kehadiran</th>
                                                        <th class="px-4 py-2.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-400 w-28">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800 text-xs">
                                                    @foreach($kelasItem->absensi as $absensi)
                                                        @php
                                                             $totalMhs = $kelasItem->mahasiswa->count();
                                                             $persen = $totalMhs > 0 ? round(($absensi->hadir_count / $totalMhs) * 100) : 0;
                                                             $monthKey = $absensi->tanggal ? $absensi->tanggal->format('m') : 'tanpa-tanggal';
                                                             $monthName = $absensi->tanggal ? $absensi->tanggal->translatedFormat('F') : '';
                                                        @endphp
                                                        <tr x-show="sessionMatchesMonth('{{ $monthKey }}', '{{ $monthName }}')"
                                                            class="hover:bg-slate-50/70 dark:hover:bg-slate-700/30 transition">
                                                            {{-- Pertemuan Ke --}}
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-50 dark:bg-indigo-950 font-extrabold text-[#321270] dark:text-purple-300 text-xs border border-indigo-200/50">
                                                                    {{ $absensi->pertemuan_ke }}
                                                                </span>
                                                            </td>

                                                            {{-- Tanggal & Waktu --}}
                                                            <td class="px-4 py-3">
                                                                <p class="font-bold text-slate-800 dark:text-white">
                                                                    {{ $absensi->tanggal?->translatedFormat('l, d M Y') ?? '-' }}
                                                                </p>
                                                                <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                                                    {{ $absensi->jam_mulai && $absensi->jam_selesai ? substr($absensi->jam_mulai, 0, 5).' - '.substr($absensi->jam_selesai, 0, 5).' WIB' : 'Waktu belum diatur' }}
                                                                </p>
                                                            </td>

                                                            {{-- Status Akses --}}
                                                            <td class="px-4 py-3">
                                                                @if($absensi->isDraft())
                                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800">
                                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                                        Draft (Konsep)
                                                                    </span>
                                                                @elseif($absensi->isBuka())
                                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800">
                                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                                        Dibuka (Aktif)
                                                                    </span>
                                                                @else
                                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600">
                                                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                                        Ditutup (Selesai)
                                                                    </span>
                                                                @endif
                                                            </td>

                                                            {{-- Rasio Kehadiran --}}
                                                            <td class="px-4 py-3">
                                                                <div class="flex items-center gap-2">
                                                                    <div class="h-2 w-20 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700">
                                                                        <div class="h-full rounded-full bg-emerald-500 transition-all duration-300" style="width: {{ $persen }}%"></div>
                                                                    </div>
                                                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-200">
                                                                        {{ $absensi->hadir_count }}/{{ $totalMhs }}
                                                                    </span>
                                                                    <span class="text-[10px] text-slate-400">({{ $persen }}%)</span>
                                                                </div>
                                                            </td>

                                                            {{-- Aksi: Hanya Tombol Lihat Saja --}}
                                                            <td class="px-4 py-3 text-right">
                                                                <div class="flex items-center justify-end">
                                                                    <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelasItem->id, 'absensiId' => $absensi->id]) }}"
                                                                       class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-700 px-3 py-1.5 text-xs font-bold text-slate-700 dark:text-white hover:bg-[#321270] hover:text-white hover:border-[#321270] dark:hover:bg-purple-900 transition shadow-sm group">
                                                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                                        <span>Lihat</span>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="px-5 py-8 text-center text-slate-400 dark:text-slate-500">
                                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                            <p class="text-xs font-semibold">Belum ada sesi pertemuan tercatat untuk mata kuliah ini.</p>
                                            <a href="{{ route('dosen.absensi.create', $kelasItem->id) }}" class="inline-block mt-2 text-xs font-bold text-[#321270] dark:text-purple-300 hover:underline">
                                                + Buat Sesi Presensi Pertama
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-8 text-center shadow-sm">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 dark:bg-slate-700 text-slate-400 mx-auto mb-3">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-700 dark:text-white">Belum Ada Data Log Mengajar</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">Anda belum memiliki kelas aktif yang terdaftar untuk pencatatan log mengajar.</p>
            </div>
        @endif
    </section>

    {{-- ======================================================== --}}
    {{-- MODAL: RESCHEDULE KELAS PENGGANTI                        --}}
    {{-- ======================================================== --}}
    <div x-show="showRescheduleModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         @keydown.escape.window="showRescheduleModal = false">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="showRescheduleModal = false"></div>

        {{-- Modal Content --}}
        <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-slate-800 shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden"
             @click.stop x-transition>
            {{-- Header --}}
            <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-white/20 text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </span>
                        <div>
                            <h3 class="text-base font-extrabold text-white">Jadwalkan Kelas Pengganti</h3>
                            <p class="text-xs text-white/80 mt-0.5" x-text="rescheduleNamaMk + ' (' + rescheduleKodeKelas + ')'"></p>
                        </div>
                    </div>
                    <button @click="showRescheduleModal = false" class="text-white/70 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Body --}}
            <form method="POST" action="{{ route('dosen.jadwal.reschedule') }}" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="kelas_perkuliahan_id" :value="rescheduleKelasId">
                <input type="hidden" name="nama_mk" :value="rescheduleNamaMk">
                <input type="hidden" name="kode_kelas" :value="rescheduleKodeKelas">

                {{-- Info Banner --}}
                @if ($errors->any())
                    <div class="rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 p-3 mb-4">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <h4 class="text-xs font-bold text-red-800 dark:text-red-200">Gagal menyimpan jadwal pengganti:</h4>
                                <ul class="mt-1 list-disc list-inside text-[11px] text-red-700 dark:text-red-300">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 p-3 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-amber-800 dark:text-amber-200 leading-relaxed">
                        Pengumuman otomatis akan dikirim ke seluruh mahasiswa di kelas ini saat Anda menyimpan jadwal pengganti.
                    </p>
                </div>

                {{-- Tanggal Pengganti --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tanggal Pengganti <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_pengganti" required min="{{ date('Y-m-d') }}" value="{{ old('tanggal_pengganti') }}"
                           class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-800 dark:text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition">
                </div>

                {{-- Jam Mulai & Selesai --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_mulai" required value="{{ old('jam_mulai') }}"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-800 dark:text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_selesai" required value="{{ old('jam_selesai') }}"
                               class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-800 dark:text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition">
                    </div>
                </div>

                {{-- Ruangan Pengganti --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Ruangan Pengganti <span class="text-red-500">*</span></label>
                    <select name="ruangan_pengganti" required
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-800 dark:text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition">
                        <option value="" disabled selected>-- Pilih Ruangan Pengganti --</option>
                        @foreach($availableRooms as $room)
                            <option value="{{ $room }}" {{ old('ruangan_pengganti') == $room ? 'selected' : '' }}>{{ $room }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Alasan --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Alasan Reschedule <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <textarea name="alasan_pengganti" rows="3" placeholder="Jelaskan alasan perubahan jadwal (opsional)..."
                              class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 px-3.5 py-2.5 text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition resize-none">{{ old('alasan_pengganti') }}</textarea>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="showRescheduleModal = false"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-orange-500 to-amber-500 text-white text-xs font-bold shadow-md hover:shadow-lg hover:from-orange-600 hover:to-amber-600 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan & Kirim Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function jadwalMengajarData() {
        return {
            activeTab: '{{ in_array($activeTab, ['jadwal', 'log', 'semua']) ? $activeTab : 'jadwal' }}',
            selectedDay: 'Semua',
            selectedMonth: 'Semua',
            searchQuery: '',
            openKelasId: @json($kelasList->isNotEmpty() ? $kelasList->first()->id : null),
            // Reschedule modal state
            showRescheduleModal: {{ $errors->any() ? 'true' : 'false' }},
            rescheduleKelasId: {{ old('kelas_perkuliahan_id') ?: 'null' }},
            rescheduleNamaMk: '{{ old('nama_mk', old('kelas_perkuliahan_id') ? 'Kelas Pengganti' : '') }}',
            rescheduleKodeKelas: '{{ old('kode_kelas', '') }}',
            switchTab(tab) {
                this.activeTab = tab;
                try {
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tab);
                    window.history.replaceState({}, '', url);
                } catch (e) {}
            },
            openLogForClass(kelasId) {
                this.switchTab('log');
                this.openKelasId = kelasId;
                this.$nextTick(function() {
                    const el = document.getElementById('log-kelas-' + kelasId);
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            },
            matchesSearch(text) {
                if (!this.searchQuery || !this.searchQuery.trim()) return true;
                return text.toLowerCase().indexOf(this.searchQuery.toLowerCase().trim()) !== -1;
            },
            sessionMatchesMonth(monthKey) {
                if (this.selectedMonth === 'Semua') return true;
                return this.selectedMonth === monthKey;
            },
            classMatchesMonth(monthsCsv) {
                if (this.selectedMonth === 'Semua') return true;
                if (!monthsCsv) return false;
                const months = monthsCsv.split(',').map(function(m) { return m.trim(); });
                return months.indexOf(this.selectedMonth) !== -1;
            }
        };
    }
</script>

@endsection