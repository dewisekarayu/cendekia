@extends('layouts.portal')

@section('title', 'Presensi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m0 0V3a2 2 0 00-2-2h-2a2 2 0 00-2 2v2z" />
            </svg>
            Presensi Kelas
        </h1>
        <p class="mt-2 text-gray-600">Kelola dan pantau presensi Anda untuk setiap kelas yang diambil</p>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="font-semibold text-green-900">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            <p class="font-semibold text-yellow-900">{{ session('warning') }}</p>
        </div>
    @endif

    <!-- Classes Grid -->
    @if($kelasList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kelasList as $kelas)
                @php
                    $totalPertemuan = \App\Models\Absensi::where('kelas_perkuliahan_id', $kelas->id)->count();
                    $totalHadir = \App\Models\AbsensiMahasiswa::whereIn('absensi_id',
                        \App\Models\Absensi::where('kelas_perkuliahan_id', $kelas->id)->pluck('id')
                    )->where('mahasiswa_id', auth()->id())
                    ->where('status', 'hadir')
                    ->count();
                    $absensiAktif = \App\Models\Absensi::where('kelas_perkuliahan_id', $kelas->id)
                        ->where('session_status', 'buka')
                        ->whereDate('tanggal', today())
                        ->first();
                    $attendancePercentage = $totalPertemuan > 0 ? round($totalHadir / $totalPertemuan * 100) : 0;
                @endphp
                <div class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <div class="h-20 bg-gradient-to-r from-purple-500 via-purple-600 to-blue-600 relative overflow-hidden">
                        <div class="absolute inset-0 opacity-20">
                            <svg class="w-32 h-32 absolute -right-8 -top-8" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative px-5 py-4 -mt-10">
                        <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-2">{{ $kelas->mataKuliah->nama_mk }}</h3>
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-semibold text-gray-600">Kelas {{ $kelas->kode_kelas }}</p>
                            @if($absensiAktif)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    Aktif
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 mb-4 pb-4 border-b border-gray-100">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-600">{{ $kelas->dosen->name }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $totalHadir }}</p>
                                <p class="text-xs text-gray-600 mt-1">Hadir</p>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-3 text-center">
                                <p class="text-2xl font-bold text-gray-600">{{ $totalPertemuan }}</p>
                                <p class="text-xs text-gray-600 mt-1">Total</p>
                            </div>
                        </div>

                        @if($totalPertemuan > 0)
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-semibold text-gray-700">Tingkat Kehadiran</span>
                                    <span class="text-xs font-bold text-purple-600">{{ $attendancePercentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-gradient-to-r from-purple-500 to-blue-600 h-full rounded-full transition-all duration-500"
                                        style="width: {{ $attendancePercentage }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg font-semibold transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Presensi
                            </a>
                            <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}"
                                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center mt-8">
            {{ $kelasList->links() }}
        </div>
    @else
        <div class="bg-gradient-to-br from-gray-50 to-blue-50 rounded-2xl border border-gray-200 p-12 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Data Presensi</h3>
            <p class="text-gray-600 mb-6">Anda belum terdaftar di kelas manapun. Daftarkan diri Anda ke kelas untuk memulai presensi.</p>
            <a href="{{ route('mahasiswa.kelas-saya') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Lihat Kelas Saya
            </a>
        </div>
    @endif
</div>
@endsection
