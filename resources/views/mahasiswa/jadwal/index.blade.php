@extends('layouts.portal')

@section('title', 'Jadwal Kuliah')
@section('activeMenu', 'Jadwal')

@section('content')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Jadwal Kuliah Saya</h1>

    @if($kelasPerkuliahan->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-8 text-center">
            <div class="text-gray-400 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada jadwal kuliah</h3>
            <p class="text-gray-500 mb-6">Anda belum terdaftar pada kelas perkuliahan aktif.</p>
            <a href="{{ route('mahasiswa.kelas.index') }}" class="bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Lihat Daftar Kelas
            </a>
        </div>
    @else
        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-2 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Kelas</p>
                        <p class="text-xl font-bold text-gray-800">{{ $kelasPerkuliahan->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-center">
                    <div class="bg-green-100 p-2 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total SKS</p>
                        <p class="text-xl font-bold text-gray-800">{{ $totalSKS }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-2 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Semester Aktif</p>
                        <p class="text-xl font-bold text-gray-800">
                            @if($kelasPerkuliahan->first()->semester)
                                {{ $kelasPerkuliahan->first()->semester->nama_semester }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal per hari -->
        <div class="space-y-6">
            @foreach($days as $day)
                @if($jadwalByDay[$day]->isNotEmpty())
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-100">
                            <h3 class="text-lg font-bold text-gray-800">{{ $day }}</h3>
                        </div>
                        
                        <div class="divide-y divide-gray-100">
                            @foreach($jadwalByDay[$day] as $kelas)
                                <div class="px-6 py-4 hover:bg-gray-50 transition">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-base font-semibold text-gray-800">{{ $kelas->mataKuliah->nama_mk }}</h4>
                                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded">{{ $kelas->kode_kelas }}</span>
                                                @if($kelas->mataKuliah->sks)
                                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-0.5 rounded">{{ $kelas->mataKuliah->sks }} SKS</span>
                                                @endif
                                            </div>
                                            
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-2">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>{{ \Carbon\Carbon::parse($kelas->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($kelas->jam_selesai)->format('H:i') }}</span>
                                                </div>
                                                
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                    </svg>
                                                    <span>{{ $kelas->dosen->name }}</span>
                                                </div>
                                                
                                                @if($kelas->ruangan)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    <span>{{ $kelas->ruangan }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            
                                            <div class="text-xs text-gray-500">
                                                <span class="inline-flex items-center">
                                                    {{ $kelas->mataKuliah->programStudi->nama_prodi ?? '-' }} 
                                                    • {{ $kelas->tahun_akademik }} 
                                                    • Semester {{ $kelas->semester->nama_semester ?? '-' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="flex gap-2">
                                            <a href="{{ route('mahasiswa.kelas.show', $kelas->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                Detail Kelas
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Calendar View Link -->
        <div class="mt-8 text-center">
            <a href="{{ route('mahasiswa.jadwal.calendar') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Tampilkan dalam Kalender
            </a>
        </div>
    @endif

@endsection