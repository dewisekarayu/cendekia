@extends('layouts.portal')

@section('title', 'My Courses')
@section('activeMenu', 'My Courses')

@section('content')

    <div class="bg-blue-900 rounded-xl px-8 py-6 text-white mb-8 shadow-sm">
        <h1 class="text-2xl font-bold">Daftar Mata Kuliah</h1>
        <p class="text-blue-200 text-sm mt-1">Akses semua kelas perkuliahan aktif Anda di semester ini.</p>
    </div>

    <form method="GET" action="{{ route('mahasiswa.kelas-saya') }}" class="flex flex-col sm:flex-row gap-4 items-center justify-between mb-8">
        <div class="flex gap-4 w-full sm:w-auto">
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Semester</label>
                <select name="semester" class="bg-white border border-gray-200 rounded-lg text-sm px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-900">
                    <option value="">Semester Ganjil 2023/2024</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Fakultas</label>
                <select name="fakultas" class="bg-white border border-gray-200 rounded-lg text-sm px-3 py-2 text-gray-700 focus:outline-none focus:border-blue-900">
                    <option value="">Semua Fakultas</option>
                </select>
            </div>
        </div>
        <div class="w-full sm:w-72 self-end">
            <div class="relative">
                <input name="q" value="{{ request('q') }}" type="text" placeholder="Cari mata kuliah..." class="w-full bg-white border border-gray-200 rounded-lg text-sm pl-9 pr-10 py-2 text-gray-700 focus:outline-none focus:border-blue-900">
                <button type="submit" class="absolute left-2 top-2 text-gray-400 p-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </form>

    @if ($kelasList->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-12 text-center shadow-sm flex flex-col items-center justify-center min-h-[340px]">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 mb-4 border border-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-700 text-base">Belum Ada Kelas Terdaftar</h3>
            <p class="text-gray-400 text-xs max-w-sm mt-1 leading-relaxed">Anda belum dimasukkan ke kelas mana pun oleh Admin Akademik untuk semester ini. Silakan hubungi bagian administrasi prodi Anda.</p>
        </div>
    @else
        {{-- MENGUBAH PEMBUNGKUS MENJADI LIST BARIS (flex-col) --}}
        <div class="flex flex-col gap-4">
            @foreach ($kelasList as $kelas)
                <a href="{{ route('mahasiswa.kelas-detail', $kelas->id) }}" class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm hover:shadow-md hover:border-gray-200 transition duration-200 flex flex-col md:flex-row md:items-center justify-between gap-5 group text-left">
                    
                    {{-- Bagian Kiri: Info Utama Kelas --}}
                    <div class="flex-1 min-w-0 space-y-2.5">
                        <div class="flex items-center gap-2">
                            <span class="inline-block text-[10px] font-bold tracking-wide text-blue-900 bg-blue-50 px-2.5 py-1 rounded-md uppercase">
                                {{ $kelas->mataKuliah?->kode_mk ?? '-' }}
                            </span>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">
                                Aktif
                            </span>
                            <span class="text-[11px] font-bold text-gray-500 ml-2">
                                {{ $kelas->mataKuliah?->sks ?? '0' }} SKS
                            </span>
                        </div>

                        <h3 class="font-bold text-gray-800 text-base group-hover:text-blue-900 transition truncate">
                            {{ $kelas->mataKuliah?->nama_mk ?? '-' }}
                        </h3>
                        
                        <p class="text-xs text-gray-400 flex flex-wrap items-center gap-x-3 gap-y-1">
                            <span class="flex items-center gap-1">
                                🏢 Ruangan: <strong class="text-gray-600 font-semibold">{{ $kelas->ruangan ?? '-' }}</strong>
                            </span>
                            <span class="text-gray-300">|</span>
                            <span>{{ $kelas->mataKuliah?->programStudi?->nama_prodi ?? 'Umum' }}</span>
                        </p>
                    </div>

                    {{-- Bagian Tengah Kiri: Dosen Pengampu --}}
                    <div class="flex items-center gap-3 md:w-56 shrink-0 border-t md:border-t-0 pt-3 md:pt-0 border-gray-50">
                        <div class="w-8 h-8 rounded-full bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($kelas->dosen?->name ?? 'Dosen') }}&background=EBF4FF&color=1E3A8A" alt="Avatar">
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-[9px] text-gray-400 font-medium uppercase tracking-wider leading-none">Dosen Pengampu</p>
                            <p class="text-xs font-semibold text-gray-700 mt-1 truncate" title="{{ $kelas->dosen?->name ?? '-' }}">
                                {{ $kelas->dosen?->name ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Bagian Tengah Kanan: Jadwal & Progress Belajar --}}
                    <div class="md:w-52 shrink-0 space-y-2">
                        <div class="text-xs text-gray-600 flex items-center gap-1.5 font-medium">
                            📅 <span>{{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }} WIB</span>
                        </div>
                        <div>
                            <div class="flex justify-between items-center text-[10px] font-semibold text-gray-400 mb-0.5">
                                <span>Progress Kelas</span>
                                <span class="text-blue-900 font-bold">0%</span>
                            </div>
                            <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian Kanan: Tombol Aksi --}}
                    <div class="shrink-0 pt-2 md:pt-0">
                        <div class="w-full md:w-auto inline-flex items-center justify-center bg-blue-900 group-hover:bg-blue-800 text-white text-xs font-semibold px-5 py-2.5 rounded-xl transition shadow-sm gap-1">
                            <span>Masuk Kelas</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    @endif

@endsection