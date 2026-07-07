@extends('layouts.portal')

@section('title', 'Dashboard')
@section('activeMenu', 'Dashboard')

@section('content')
<div class="w-full mx-auto p-1 sm:p-4 lg:p-6 space-y-6">

    <div class="bg-blue-900 rounded-xl px-6 py-6 sm:px-8 sm:py-7 relative overflow-hidden shadow-sm">
        <div class="relative z-10 max-w-md sm:max-w-xl">
            <h1 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                Selamat Datang, {{ explode(' ', auth()->user()->name)[0] }}!
            </h1>
            <p class="text-blue-200 text-xs sm:text-sm mt-1.5 leading-relaxed">
                @if (!auth()->user()->program_studi_id)
                    Pilih program studi kamu untuk mulai mengakses kelas dan materi kuliah.
                @else
                    Kamu punya {{ count($deadlines ?? []) }} tugas yang harus dikumpulkan. Tetap fokus dan pertahankan progres belajarmu.
                @endif
            </p>
        </div>
        <div class="absolute -right-6 -bottom-8 w-32 h-32 sm:w-36 sm:h-36 text-blue-850 opacity-40 pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path d="M12 14l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z"/>
            </svg>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg shadow-sm transition">
            {{ session('success') }}
        </div>
    @endif

    @if (!auth()->user()->program_studi_id && isset($prodiList))
        <div class="space-y-4">
            <div class="border-b border-gray-100 pb-2">
                <h2 class="text-lg font-bold text-gray-800">Pilih Program Studi</h2>
                <p class="text-xs text-gray-400 mt-0.5">Silakan bergabung dengan salah satu program studi di bawah ini.</p>
            </div>

            @if ($prodiList->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 p-12 text-center shadow-sm">
                    <p class="text-gray-400 text-sm">Belum ada program studi tersedia. Silakan hubungi pihak Admin.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach ($prodiList as $prodi)
                        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
                            <div>
                                <img src="https://picsum.photos/seed/prodi-{{ $prodi->id }}/400/200" alt="{{ $prodi->nama_prodi }}" class="w-full h-32 object-cover">
                                <div class="p-4 space-y-2">
                                    <span class="inline-block text-[10px] font-bold tracking-wider text-blue-900 bg-blue-50 px-2.5 py-1 rounded-md uppercase">
                                        {{ $prodi->jenjang }}
                                    </span>
                                    <h3 class="font-bold text-gray-800 text-base leading-snug">{{ $prodi->nama_prodi }}</h3>
                                </div>
                            </div>

                            <div class="p-4 pt-0">
                                <form method="POST" action="{{ route('mahasiswa.pilih-prodi', $prodi->id) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold py-2.5 rounded-xl transition shadow-sm">
                                        Gabung Program Studi
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-900 flex items-center justify-center shrink-0 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">Program Studi Aktif</p>
                    <p class="text-sm font-bold text-gray-800 mt-0.5">{{ auth()->user()->programStudi?->nama_prodi ?? '-' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('mahasiswa.keluar-prodi') }}" onsubmit="return confirm('Yakin keluar dari program studi ini? Semua kelas yang kamu ikuti akan ikut terhapus.')" class="w-full sm:w-auto">
                @csrf
                <button type="submit" class="w-full sm:w-auto text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-xl transition">
                    Keluar Program Studi
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">

                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-50 pb-1">
                        <h2 class="text-lg font-bold text-gray-800">Current Courses</h2>
                        <a href="{{ route('mahasiswa.kelas-saya') }}" class="text-xs font-bold text-blue-900 hover:text-blue-800 hover:underline flex items-center gap-1">View All →</a>
                    </div>

                    @if ($courses->isEmpty())
                        <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-8 flex flex-col items-center justify-center text-center h-[340px] shadow-sm">
                            <a href="{{ route('mahasiswa.jelajahi-kelas') }}" class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-gray-100 border border-gray-200 mb-3 transition shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                            <h3 class="font-bold text-gray-800 text-base">Tambah Mata Kuliah</h3>
                            <p class="text-gray-400 text-xs max-w-[240px] mt-1 leading-relaxed">Kamu belum mengambil atau terdaftar di kelas manapun. Klik tombol di atas untuk menjelajahi kelas.</p>
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
                                        <a href="{{ route('mahasiswa.kelas-detail', $kelas['id']) }}" class="w-full block text-center bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold py-2.5 rounded-xl transition shadow-sm">
                                            Masuk Kelas
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-gray-800 flex items-center gap-2 border-b border-gray-50 pb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                        Recent Announcements
                    </h2>

                    @if ($announcements->isEmpty())
                        <p class="text-sm text-gray-400">Belum ada pengumuman terbaru.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($announcements as $i => $item)
                                <div class="border-l-2 {{ $i === 0 ? 'border-blue-900' : 'border-gray-200' }} pl-4 py-0.5">
                                    <div class="flex items-center justify-between gap-4">
                                        <p class="text-sm font-bold text-gray-800">{{ $item->judul }}</p>
                                        <span class="text-[10px] text-gray-400 shrink-0">{{ $item->created_at?->diffForHumans() ?? 'Baru saja' }}</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">{{ Str::limit($item->isi ?? '', 120) }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="space-y-4">
                <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-50 pb-2">
                        <h2 class="text-base font-bold text-gray-800">Deadlines</h2>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>

                    @if ($deadlines->isEmpty())
                        <p class="text-sm text-gray-400 py-2">Tidak ada tugas dalam waktu dekat.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($deadlines as $tugas)
                                <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-xl transition duration-150">
                                    <div class="shrink-0 w-10 h-10 rounded-xl bg-red-50 text-red-700 flex flex-col items-center justify-center text-[10px] font-bold leading-none shadow-sm">
                                        <span class="text-base font-extrabold">{{ \Carbon\Carbon::parse($tugas->deadline)->format('d') }}</span>
                                        <span class="text-[8px] uppercase font-bold mt-0.5">{{ \Carbon\Carbon::parse($tugas->deadline)->format('M') }}</span>
                                    </div>
                                    <div class="overflow-hidden space-y-0.5">
                                        <p class="text-sm font-bold text-gray-800 truncate leading-snug">{{ $tugas->judul }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">{{ $tugas->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection