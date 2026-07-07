@extends('layouts.portal')

@section('title', 'Dashboard')
@section('activeMenu', 'Dashboard')

@section('content')

    <!-- Welcome Banner -->
    <div class="bg-blue-900 rounded-xl px-8 py-6 relative overflow-hidden mb-8">
        <div class="relative z-10">
            <h1 class="text-xl font-bold text-white">Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
            <p class="text-blue-200 text-sm mt-1 max-w-md">
                Kamu punya {{ $deadlines->count() }} tugas yang harus dikumpulkan. Tetap fokus dan pertahankan progres belajarmu.
            </p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-4 -bottom-6 w-32 h-32 text-blue-800 opacity-60" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
            <path d="M12 14l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z"/>
        </svg>
    </div>

    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (!auth()->user()->program_studi_id)
        <div class="bg-amber-50 border border-amber-200 text-amber-700 text-sm px-4 py-3 rounded-lg mb-6">
            Program studi kamu belum ditentukan. Hubungi Admin akademik untuk penempatan program studi.
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Courses + Announcements -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Current Courses -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">Current Courses</h2>
                    <a href="{{ route('mahasiswa.kelas-saya') }}" class="text-sm font-medium text-blue-900 hover:underline">View All →</a>
                </div>

                @if ($courses->isEmpty())
                    <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-8 flex flex-col items-center justify-center text-center h-[240px] shadow-sm">
                        <a href="{{ route('mahasiswa.jelajahi-kelas') }}" class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 border border-gray-200 mb-3 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        <h3 class="font-bold text-gray-800 text-base">Belum Ada Kelas</h3>
                        <p class="text-gray-400 text-xs max-w-[240px] mt-1 leading-relaxed">Kamu belum terdaftar di kelas manapun. Klik tombol di atas untuk menjelajahi kelas.</p>
                        <a href="{{ route('mahasiswa.jelajahi-kelas') }}" class="mt-4 text-xs font-semibold text-blue-900 bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition">
                            Jelajahi Kelas Baru →
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($courses as $kelas)
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm flex flex-col justify-between min-h-[340px]">
                                <div class="p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-900 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                            </svg>
                                        </div>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">
                                            Aktif
                                        </span>
                                    </div>

                                    <h3 class="font-bold text-gray-800 text-base leading-snug min-h-[44px] line-clamp-2">
                                        {{ $kelas['title'] }}
                                    </h3>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $kelas['tag'] }}
                                    </p>

                                    <div class="mt-4 flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full bg-gray-100 overflow-hidden shrink-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($kelas['dosen']) }}&background=EBF4FF&color=1E3A8A" alt="Avatar">
                                        </div>
                                        <div class="overflow-hidden">
                                            <p class="text-[9px] text-gray-400 font-medium uppercase tracking-wider leading-none">Dosen Pengampu</p>
                                            <p class="text-xs font-semibold text-gray-700 mt-1 truncate">{{ $kelas['dosen'] }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <div class="flex justify-between items-center text-[11px] font-semibold text-gray-500 mb-1">
                                            <span>Course Progress</span>
                                            <span class="text-blue-900 font-bold">{{ $kelas['progress'] }}%</span>
                                        </div>
                                        <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-600 rounded-full" style="width: {{ $kelas['progress'] }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="px-5 pb-5 pt-3 border-t border-gray-50 bg-gray-50/50">
                                    <a href="{{ route('mahasiswa.kelas-detail', $kelas['id']) }}" class="w-full block text-center bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold py-2 rounded-lg transition shadow-sm">
                                        Masuk Kelas
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Announcements -->
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <h2 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                    </svg>
                    Recent Announcements
                </h2>

                @if ($announcements->isEmpty())
                    <p class="text-sm text-gray-400">Belum ada pengumuman.</p>
                @else
                    <div class="space-y-4">
                        @foreach ($announcements as $i => $item)
                            <div class="border-l-2 {{ $i === 0 ? 'border-blue-900' : 'border-gray-200' }} pl-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-800">{{ $item->judul }}</p>
                                    <span class="text-xs text-gray-400 shrink-0 ml-2">{{ $item->created_at?->diffForHumans() ?? 'Baru saja' }}</span>
                                </div>
                                <p class="text-sm text-gray-500 mt-0.5">{{ Str::limit($item->isi ?? '', 100) }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Deadlines -->
        <div>
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-800">Deadlines</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>

                @if ($deadlines->isEmpty())
                    <p class="text-sm text-gray-400">Tidak ada tugas mendatang.</p>
                @else
                    <div class="space-y-5">
                        @foreach ($deadlines as $tugas)
                            <div class="flex gap-3">
                                <div class="shrink-0 w-10 h-10 rounded-lg bg-red-50 text-red-700 flex flex-col items-center justify-center text-[10px] font-bold leading-none">
                                    <span class="text-base">{{ \Carbon\Carbon::parse($tugas->deadline)->format('d') }}</span>
                                    <span class="text-[9px] uppercase mt-0.5">{{ \Carbon\Carbon::parse($tugas->deadline)->format('M') }}</span>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $tugas->judul }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $tugas->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection