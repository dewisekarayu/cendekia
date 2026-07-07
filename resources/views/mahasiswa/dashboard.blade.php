@extends('layouts.portal')

@section('title', 'Dashboard')
@section('activeMenu', 'Dashboard')

@section('content')

    <!-- Welcome Banner -->
    <div class="bg-[#002B6B] rounded-xl px-6 py-6 sm:px-8 sm:py-7 relative overflow-hidden mb-6 shadow-sm">
        <div class="relative z-10">
            <h1 class="text-lg sm:text-xl font-bold text-white">Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
            <p class="text-blue-100/80 text-xs sm:text-sm mt-1 max-w-md leading-relaxed">
                Kamu punya <span class="text-white font-semibold">{{ $deadlines->count() }} tugas</span> yang harus dikumpulkan. Tetap fokus dan pertahankan progres belajarmu.
            </p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-4 -bottom-6 w-28 h-28 sm:w-32 sm:h-32 text-white/10" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
            <path d="M12 14l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z"/>
        </svg>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (!auth()->user()->program_studi_id)
        <div class="bg-amber-50 border border-amber-200 text-amber-700 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>Program studi kamu belum ditentukan. Hubungi Admin akademik untuk penempatan program studi.</span>
        </div>
    @endif

    <!-- Main Grid Dashboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Courses & Announcements -->
        <div class="lg:col-span-2 space-y-6 min-w-0">

            <!-- Current Courses Section -->
            <div>
                <div class="flex items-center justify-between mb-4 gap-2">
                    <h2 class="text-base sm:text-lg font-bold text-gray-800 tracking-tight">Current Courses</h2>
                    <a href="{{ route('mahasiswa.kelas-saya') }}" class="text-xs sm:text-sm font-semibold text-[#002B6B] hover:text-blue-800 transition flex items-center gap-1 shrink-0">
                        View All <span>→</span>
                    </a>
                </div>

                @if ($courses->isEmpty())
                    <div class="bg-white rounded-xl border border-gray-200/60 border-dashed p-8 flex flex-col items-center justify-center text-center h-[260px] shadow-sm">
                        <a href="{{ route('mahasiswa.jelajahi-kelas') }}" class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 border border-gray-200/80 mb-3 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        <h3 class="font-bold text-gray-800 text-base">Belum Ada Kelas</h3>
                        <p class="text-gray-400 text-xs max-w-[240px] mt-1 leading-relaxed">Kamu belum terdaftar di kelas manapun. Klik tombol di bawah untuk menjelajahi kelas aktif.</p>
                        <a href="{{ route('mahasiswa.jelajahi-kelas') }}" class="mt-4 text-xs font-semibold text-[#002B6B] bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition">
                            Jelajahi Kelas Baru →
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($courses as $kelas)
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm flex flex-col justify-between hover:shadow-md transition duration-200">
                                <div class="p-5">
                                    <div class="flex items-center justify-between mb-3.5">
                                        <div class="w-9 h-9 rounded-lg bg-blue-50 text-[#002B6B] flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">
                                            Aktif
                                        </span>
                                    </div>

                                    <h3 class="font-bold text-gray-800 text-sm sm:text-base leading-snug line-clamp-2 min-h-[40px]" title="{{ $kelas['title'] }}">
                                        {{ $kelas['title'] }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-0.5 truncate">
                                        {{ $kelas['tag'] }}
                                    </p>

                                    <!-- Lecturer Info -->
                                    <div class="mt-4 flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-gray-100 overflow-hidden shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($kelas['dosen']) }}&background=EEF2F7&color=002B6B" alt="Avatar">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[9px] text-gray-400 font-medium uppercase tracking-wider leading-none">Dosen Pengampu</p>
                                            <p class="text-xs font-semibold text-gray-600 mt-1 truncate">{{ $kelas['dosen'] }}</p>
                                        </div>
                                    </div>

                                    <!-- Progress Tracker -->
                                    <div class="mt-5">
                                        <div class="flex justify-between items-center text-[11px] font-semibold text-gray-500 mb-1.5">
                                            <span>Course Progress</span>
                                            <span class="text-[#002B6B] font-bold">{{ $kelas['progress'] }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-600 rounded-full transition-all duration-300" style="width: {{ $kelas['progress'] }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 pb-5 pt-2">
                                    <a href="{{ route('mahasiswa.kelas-detail', $kelas['id']) }}" class="w-full block text-center bg-[#002B6B] hover:bg-blue-800 text-white text-xs sm:text-sm font-semibold py-2.5 rounded-lg transition shadow-sm">
                                        Masuk Kelas
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Announcements Section -->
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#002B6B]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Recent Announcements
                </h2>

                @if ($announcements->isEmpty())
                    <p class="text-xs sm:text-sm text-gray-400 py-2">Belum ada pengumuman terbaru saat ini.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($announcements as $i => $item)
                            <div class="border-l-2 {{ $i === 0 ? 'border-[#002B6B]' : 'border-gray-200' }} pl-4 transition-all">
                                <div class="flex items-baseline justify-between gap-2">
                                    <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $item->judul }}</p>
                                    <span class="text-[11px] text-gray-400 shrink-0 ml-2">{{ $item->created_at?->diffForHumans() ?? 'Baru saja' }}</span>
                                </div>
                                <p class="text-xs sm:text-sm text-gray-500 mt-0.5 line-clamp-2 leading-relaxed">{{ Str::limit($item->isi ?? '', 120) }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Deadlines Sidebar -->
        <div class="min-w-0">
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm sticky top-24">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-800 tracking-tight">Deadlines</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                @if ($deadlines->isEmpty())
                    <div class="text-center py-6">
                        <p class="text-xs sm:text-sm text-gray-400">Yaiy! Tidak ada tugas mendatang.</p>
                    </div>
                @else
                    <div class="space-y-4.5">
                        @foreach ($deadlines as $tugas)
                            <div class="flex items-center gap-3 p-2 -mx-2 rounded-lg hover:bg-gray-50/80 transition">
                                <div class="shrink-0 w-10 h-10 rounded-lg bg-rose-50 text-rose-700 flex flex-col items-center justify-center text-[10px] font-bold leading-none border border-rose-100">
                                    <span class="text-sm font-extrabold">{{ \Carbon\Carbon::parse($tugas->deadline)->format('d') }}</span>
                                    <span class="text-[8px] uppercase font-bold mt-0.5">{{ \Carbon\Carbon::parse($tugas->deadline)->format('M') }}</span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs sm:text-sm font-semibold text-gray-800 truncate" title="{{ $tugas->judul }}">{{ $tugas->judul }}</p>
                                    <p class="text-[11px] text-gray-400 truncate mt-0.5">{{ $tugas->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection