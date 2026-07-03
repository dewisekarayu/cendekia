@extends('layouts.portal')

@section('title', 'My Courses')
@section('activeMenu', 'My Courses')

@section('content')

    <!-- Header Section -->
    <div class="bg-blue-900 rounded-xl px-8 py-6 text-white mb-8">
        <h1 class="text-2xl font-bold">Daftar Mata Kuliah</h1>
        <p class="text-blue-200 text-sm mt-1">Kelola dan akses semua mata kuliah Anda di semester ini.</p>
    </div>

    <!-- Filter & Search Bar -->
    <div class="flex flex-col sm:flex-row gap-4 items-center justify-between mb-8">
        <div class="flex gap-4 w-full sm:w-auto">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Semester</label>
                <select class="bg-white border border-gray-200 rounded-lg text-sm px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-900">
                    <option>Semester Ganjil 2023/2024</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Fakultas</label>
                <select class="bg-white border border-gray-200 rounded-lg text-sm px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-900">
                    <option>Semua Fakultas</option>
                </select>
            </div>
        </div>
        <div class="w-full sm:w-72 self-end">
            <div class="relative">
                <input type="text" placeholder="Cari mata kuliah..." class="w-full bg-white border border-gray-200 rounded-lg text-sm pl-9 pr-4 py-2 text-gray-700 focus:outline-none focus:border-blue-900">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Grid Main Container -->
    @if ($kelasList->isEmpty())
        <!-- TAMPILAN KOSONG: Benar-benar kosong / hanya info teks minimalis tanpa card action -->
        <div class="bg-white rounded-xl border border-gray-100 p-12 text-center shadow-sm flex flex-col items-center justify-center min-h-[340px]">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-4 border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-700 text-base">Belum Ada Kelas</h3>
            <p class="text-gray-400 text-xs max-w-sm mt-1 leading-relaxed">Halaman kelas saya masih kosong karena kamu belum bergabung ke kelas mana pun semester ini. Silakan hubungi admin atau ambil KRS terlebih dahulu.</p>
        </div>
    @else
        <!-- GRID KETIKA SUDAH ADA KELAS YANG DIIKUTI -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($kelasList as $kelas)
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden shadow-sm flex flex-col justify-between min-h-[340px]">
                    <div class="p-5">
                        <!-- Header Card: Icon & Status Tag -->
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

                        <!-- Info Mata Kuliah -->
                        <h3 class="font-bold text-gray-800 text-base leading-snug min-h-[44px] line-clamp-2">
                            {{ $kelas->mataKuliah?->nama_mk ?? '-' }}
                        </h3>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $kelas->mataKuliah?->kode_mk ?? 'CS-' . $kelas->id }} • {{ $kelas->mataKuliah?->programStudi?->nama_prodi ?? 'Umum' }}
                        </p>

                        <!-- Dosen Pengampu -->
                        <div class="mt-4 flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-gray-100 overflow-hidden shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($kelas->dosen?->name ?? 'Dosen') }}&background=EBF4FF&color=1E3A8A" alt="Avatar">
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-[9px] text-gray-400 font-medium uppercase tracking-wider leading-none">Dosen Pengampu</p>
                                <p class="text-xs font-semibold text-gray-700 mt-1 truncate">{{ $kelas->dosen?->name ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Progress Belajar -->
                        <div class="mt-5">
                            <div class="flex justify-between items-center text-[11px] font-semibold text-gray-500 mb-1">
                                <span>Progress Belajar</span>
                                <span class="text-blue-900 font-bold">0%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Card / Action Button -->
                    <div class="px-5 pb-5 pt-3 border-t border-gray-50 bg-gray-50/50">
                        <div class="flex items-center justify-between text-xs text-gray-400 font-medium mb-3">
                            <span>{{ $kelas->mataKuliah?->sks ?? '3' }} SKS • {{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }}</span>
                        </div>
                        <a href="{{ route('mahasiswa.kelas-detail', $kelas->id) }}" class="w-full block text-center bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold py-2 rounded-lg transition shadow-sm">
                            Masuk Kelas
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection