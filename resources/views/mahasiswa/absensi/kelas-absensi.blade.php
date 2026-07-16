@extends('layouts.portal')

@section('title', 'Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400 mb-2">
                <a href="{{ route('mahasiswa.absensi.index') }}" class="hover:text-gray-900 dark:hover:text-white transition">Daftar Kelas</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-900 font-medium truncate">Presensi</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3 mt-2">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="truncate">Presensi Kelas</span>
            </h1>
            <p class="mt-2 text-gray-600 dark:text-slate-400 flex items-center gap-2">
                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">{{ $kelas->kode_kelas }}</span>
                <span>{{ $kelas->mataKuliah->nama_mk }}</span>
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('mahasiswa.absensi.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 rounded-xl font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="hidden sm:inline">Kembali</span>
            </a>
            <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-medium transition shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="hidden sm:inline">Riwayat</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-4 flex items-start gap-3 shadow-lg">
            <div class="w-10 h-10 rounded-full bg-green-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-green-900">Berhasil!</p>
                <p class="text-sm text-green-700 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-gradient-to-r from-yellow-50 to-amber-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3 shadow-lg">
            <div class="w-10 h-10 rounded-full bg-yellow-200 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-yellow-900">Perhatian!</p>
                <p class="text-sm text-yellow-700 mt-0.5">{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            @if($absensiAktif)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-gray-100 dark:border-slate-700 overflow-hidden">
                    <!-- Header dengan Status -->
                    <div class="bg-gradient-to-r from-emerald-500 via-green-500 to-teal-600 px-5 sm:px-6 py-4 sm:py-5 text-white">
                        <div class="flex items-center justify-between gap-3 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white bg-opacity-20 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-base sm:text-lg font-bold leading-tight">Sesi Presensi Aktif</h2>
                                    <p class="text-green-100 text-xs mt-0.5">Silakan segera melakukan presensi</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white bg-opacity-20 text-white font-bold text-xs animate-pulse">
                                <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5"></span>
                                Terbuka
                            </span>
                        </div>
                    </div>

                    <!-- Session Info -->
                    <div class="px-5 sm:px-6 py-5">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-3 border border-blue-200">
                                <p class="text-blue-600 text-[10px] font-bold uppercase tracking-wide mb-1">Pertemuan</p>
                                <p class="text-xl font-black text-blue-700">{{ $absensiAktif->pertemuan_ke }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-3 border border-purple-200">
                                <p class="text-purple-600 text-[10px] font-bold uppercase tracking-wide mb-1">Tanggal</p>
                                <p class="text-base font-black text-purple-700">{{ $absensiAktif->tanggal->format('d M') }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-3 border border-orange-200">
                                <p class="text-orange-600 text-[10px] font-bold uppercase tracking-wide mb-1">Mulai</p>
                                <p class="text-base font-black text-orange-700">{{ $absensiAktif->jam_mulai }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-3 border border-red-200">
                                <p class="text-red-600 text-[10px] font-bold uppercase tracking-wide mb-1">Selesai</p>
                                <p class="text-base font-black text-red-700">{{ $absensiAktif->jam_selesai }}</p>
                            </div>
                        </div>

                        <!-- Status Info -->
                        <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200 mb-5">
                            @if($sudahAbsen)
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-green-900 dark:text-green-300 text-sm sm:text-base">Anda Sudah Melakukan Presensi</p>
                                        <div class="flex items-center gap-3 mt-1.5 flex-wrap">
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-xs text-gray-600 dark:text-slate-400">Status:</span>
                                                @php
                                                    $statusColor = match($sudahAbsen->status) {
                                                        'hadir' => 'bg-green-100 text-green-800',
                                                        'izin' => 'bg-blue-100 text-blue-800',
                                                        'sakit' => 'bg-yellow-100 text-yellow-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                @endphp
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $statusColor }}">
                                                    {{ $sudahAbsen->getStatusLabel() }}
                                                </span>
                                            </div>
                                            @if($sudahAbsen->waktu_absensi)
                                                <div class="flex items-center gap-1.5">
                                                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-xs font-semibold text-gray-700">{{ $sudahAbsen->waktu_absensi->format('H:i:s') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-sm animate-pulse">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-indigo-900 dark:text-indigo-300 text-sm sm:text-base">Sesi Masih Terbuka</p>
                                        <p class="text-xs text-indigo-700 dark:text-indigo-400 mt-0.5">Lakukan presensi sekarang jika Anda hadir di kelas</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Attendance Form -->
                        @if(!$sudahAbsen)
                            <form action="{{ route('mahasiswa.absensi.masuk', ['kelasId' => $kelas->id, 'absensiId' => $absensiAktif->id]) }}" method="POST" x-data="{ status: 'hadir' }" class="space-y-5">
                                @csrf

                                <div>
                                    <label class="block text-xs font-bold text-gray-900 dark:text-slate-200 mb-3 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pilih Status Kehadiran Anda
                                    </label>
                                    <div class="grid grid-cols-3 gap-2.5">
                                        <!-- Hadir Button -->
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="status" value="hadir" x-model="status" class="peer sr-only">
                                            <div class="relative p-3 rounded-xl border-2 border-gray-200 dark:border-slate-600 peer-checked:border-green-500 peer-checked:bg-gradient-to-b peer-checked:from-green-50 peer-checked:to-emerald-50 hover:border-green-300 transition-all duration-200 shadow-sm">
                                                <div class="text-center">
                                                    <div class="w-9 h-9 rounded-full bg-green-100 dark:bg-green-900/40 group-hover:bg-green-200 peer-checked:bg-green-600 flex items-center justify-center mx-auto mb-1.5 transition-colors">
                                                        <svg class="w-5 h-5 text-green-600 peer-checked:text-white transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-xs sm:text-sm font-bold text-gray-800 dark:text-slate-200 peer-checked:text-green-700 transition-colors block">Hadir</span>
                                                    <span class="text-[10px] text-gray-500 dark:text-slate-400 peer-checked:text-green-600 mt-0.5 block">Saya hadir</span>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- Izin Button -->
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="status" value="izin" x-model="status" class="peer sr-only">
                                            <div class="relative p-3 rounded-xl border-2 border-gray-200 dark:border-slate-600 peer-checked:border-blue-500 peer-checked:bg-gradient-to-b peer-checked:from-blue-50 peer-checked:to-indigo-50 hover:border-blue-300 transition-all duration-200 shadow-sm">
                                                <div class="text-center">
                                                    <div class="w-9 h-9 rounded-full bg-blue-100 dark:bg-blue-900/40 group-hover:bg-blue-200 peer-checked:bg-blue-600 flex items-center justify-center mx-auto mb-1.5 transition-colors">
                                                        <svg class="w-5 h-5 text-blue-600 peer-checked:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-xs sm:text-sm font-bold text-gray-800 dark:text-slate-200 peer-checked:text-blue-700 transition-colors block">Izin</span>
                                                    <span class="text-[10px] text-gray-500 dark:text-slate-400 peer-checked:text-blue-600 mt-0.5 block">Tidak hadir</span>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- Sakit Button -->
                                        <label class="cursor-pointer group">
                                            <input type="radio" name="status" value="sakit" x-model="status" class="peer sr-only">
                                            <div class="relative p-3 rounded-xl border-2 border-gray-200 dark:border-slate-600 peer-checked:border-yellow-500 peer-checked:bg-gradient-to-b peer-checked:from-yellow-50 peer-checked:to-amber-50 hover:border-yellow-300 transition-all duration-200 shadow-sm">
                                                <div class="text-center">
                                                    <div class="w-9 h-9 rounded-full bg-yellow-100 dark:bg-yellow-900/40 group-hover:bg-yellow-200 peer-checked:bg-yellow-600 flex items-center justify-center mx-auto mb-1.5 transition-colors">
                                                        <svg class="w-5 h-5 text-yellow-600 peer-checked:text-white transition-colors" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2m0 2a8 8 0 100 16 8 8 0 000-16zm0 3a1 1 0 110 2 1 1 0 010-2zm0 7a1 1 0 100 2 1 1 0 000-2z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-xs sm:text-sm font-bold text-gray-800 dark:text-slate-200 peer-checked:text-yellow-700 transition-colors block">Sakit</span>
                                                    <span class="text-[10px] text-gray-500 dark:text-slate-400 peer-checked:text-yellow-600 mt-0.5 block">Tidak sehat</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Reason Field -->
                                <div x-show="status === 'izin' || status === 'sakit'" x-cloak class="animate-in fade-in duration-200">
                                    <label for="keterangan" class="block text-xs font-bold text-gray-900 dark:text-slate-200 mb-1.5 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                        Keterangan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        id="keterangan"
                                        name="keterangan"
                                        rows="2"
                                        :required="status === 'izin' || status === 'sakit'"
                                        placeholder="Jelaskan alasan izin atau sakit Anda dengan singkat..."
                                         class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none shadow-sm @error('keterangan') border-red-500 @enderror">
                                    </textarea>
                                    @error('keterangan')
                                        <p class="mt-1.5 text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <button
                                    type="submit"
                                    class="w-full font-bold py-3 px-5 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm sm:text-base text-white duration-200"
                                    :class="{
                                        'bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 hover:from-green-700 hover:via-emerald-700 hover:to-teal-700': status === 'hadir',
                                        'bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700': status === 'izin',
                                        'bg-gradient-to-r from-yellow-600 via-amber-600 to-orange-600 hover:from-yellow-700 hover:via-amber-700 hover:to-orange-700': status === 'sakit',
                                    }">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="status === 'hadir' ? '✓ PRESENSI / ABSEN MASUK' : (status === 'izin' ? '📋 KIRIM IZIN' : '🏥 KIRIM SAKIT')"></span>
                                </button>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/40 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-gray-600 dark:text-slate-400 font-medium text-sm">Anda sudah melakukan presensi untuk sesi ini</p>
                                <p class="text-xs text-gray-500 dark:text-slate-500 mt-1">Terima kasih telah melakukan presensi tepat waktu</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-br from-gray-50 to-blue-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl border-2 border-dashed border-gray-300 dark:border-slate-600 p-8 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-full bg-gray-200 dark:bg-slate-600 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-2">Tidak Ada Sesi Presensi Aktif</h3>
                    <p class="text-gray-600 dark:text-slate-400 mb-3 text-sm">Sesi presensi belum dibuka oleh dosen untuk hari ini.</p>
                    <p class="text-xs text-gray-500 dark:text-slate-500">Silakan cek kembali nanti atau hubungi dosen Anda.</p>
                </div>
            @endif

            <!-- Class Info Card -->
            <div class="mt-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition">
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-800 px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Informasi Kelas</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                        <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 dark:from-indigo-950/50 dark:to-slate-800 rounded-xl p-4 border border-indigo-200 dark:border-indigo-800">
                            <p class="text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wide mb-1">Pengajar</p>
                            <p class="font-bold text-gray-900 dark:text-slate-200 truncate" title="{{ $kelas->dosen->name }}">{{ $kelas->dosen->name }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-cyan-50 to-cyan-100 dark:from-cyan-950/50 dark:to-slate-800 rounded-xl p-4 border border-cyan-200 dark:border-cyan-800">
                            <p class="text-cyan-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wide mb-1">Ruangan</p>
                            <p class="font-bold text-gray-900 dark:text-slate-200">{{ $kelas->ruangan ?? '—' }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-lime-50 to-lime-100 dark:from-lime-950/50 dark:to-slate-800 rounded-xl p-4 border border-lime-200 dark:border-lime-800">
                            <p class="text-lime-600 dark:text-lime-400 text-xs font-bold uppercase tracking-wide mb-1">Hari</p>
                            <p class="font-bold text-gray-900 dark:text-slate-200">{{ $kelas->hari ?? '—' }}</p>
                        </div>
                        <div class="bg-gradient-to-br from-pink-50 to-pink-100 dark:from-pink-950/50 dark:to-slate-800 rounded-xl p-4 border border-pink-200 dark:border-pink-800 md:col-span-2">
                            <p class="text-pink-600 dark:text-pink-400 text-xs font-bold uppercase tracking-wide mb-1">Jam Kuliah</p>
                            <p class="font-bold text-gray-900 dark:text-slate-200">{{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            @if($riwayatAbsensi->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden hover:shadow-md transition">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/50 dark:to-indigo-950/50 px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="font-bold text-gray-900 dark:text-white">Presensi 5 Terakhir</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @foreach($riwayatAbsensi->take(5) as $absensi)
                            @php $attendance = $absensi->absensiMahasiswa->first(); @endphp
                            <div class="flex items-center justify-between p-3 rounded-xl bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-700 hover:from-slate-100 hover:to-slate-200 dark:hover:from-slate-600 dark:hover:to-slate-600 transition">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-900 dark:text-slate-200">Pertemuan {{ $absensi->pertemuan_ke }}</p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">{{ $absensi->tanggal->format('d M Y') }}</p>
                                </div>
                                @if($attendance)
                                    <span @class([
                                        'inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold flex-shrink-0 ml-2',
                                        'bg-green-100 text-green-800' => $attendance->status === 'hadir',
                                        'bg-blue-100 text-blue-800' => $attendance->status === 'izin',
                                        'bg-yellow-100 text-yellow-800' => $attendance->status === 'sakit',
                                        'bg-red-100 text-red-800' => $attendance->status === 'alpha',
                                    ])>
                                        {{ $attendance->getStatusLabel() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-800 flex-shrink-0 ml-2">Alpha</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Tips -->
            <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-indigo-950/50 dark:via-purple-950/50 dark:to-pink-950/50 rounded-2xl border border-indigo-200 dark:border-indigo-800 p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-700 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-indigo-900 dark:text-indigo-300 mb-2">Tips Presensi</h4>
                        <ul class="text-xs text-indigo-800 dark:text-indigo-300 space-y-1.5">
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span>Presensi hanya saat sesi terbuka</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span>Satu kali presensi per sesi</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span>Cantumkan alasan jika izin/sakit</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-green-600 font-bold">✓</span>
                                <span>Cek riwayat secara berkala</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-950/50 dark:to-teal-950/50 px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="font-bold text-gray-900 dark:text-white">Ringkasan</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between p-2">
                        <span class="text-sm font-medium text-gray-600 dark:text-slate-400">Lihat Riwayat Lengkap</span>
                        <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}" class="text-indigo-600 hover:text-indigo-700 font-bold text-sm">→</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================
   DARK MODE — Presensi Kelas (Absen Mahasiswa)
   ============================================ */

/* --- Main Cards --- */
html.dark .presensi-card,
html.dark .bg-white.rounded-2xl.shadow-md,
html.dark .bg-white.rounded-2xl.shadow-sm {
    background-color: #1e293b !important;
    border-color: #334155 !important;
}

/* --- Gradient Header (Sesi Presensi Aktif) --- */
html.dark .bg-gradient-to-r.from-emerald-500 {
    background-image: linear-gradient(to right, #047857, #059669, #0d9488) !important;
}

/* --- Session Info Cards (Pertemuan, Tanggal, Mulai, Selesai) --- */
html.dark .bg-gradient-to-br.from-blue-50.to-blue-100 {
    background-image: linear-gradient(135deg, #1e1b4b, #1e293b) !important;
    border-color: #312e81 !important;
}
html.dark .bg-gradient-to-br.from-blue-50.to-blue-100 .text-blue-600,
html.dark .bg-gradient-to-br.from-blue-50.to-blue-100 .text-blue-700 {
    color: #93c5fd !important;
}

html.dark .bg-gradient-to-br.from-purple-50.to-purple-100 {
    background-image: linear-gradient(135deg, #3b0764, #1e293b) !important;
    border-color: #581c87 !important;
}
html.dark .bg-gradient-to-br.from-purple-50.to-purple-100 .text-purple-600,
html.dark .bg-gradient-to-br.from-purple-50.to-purple-100 .text-purple-700 {
    color: #d8b4fe !important;
}

html.dark .bg-gradient-to-br.from-orange-50.to-orange-100 {
    background-image: linear-gradient(135deg, #431407, #1e293b) !important;
    border-color: #9a3412 !important;
}
html.dark .bg-gradient-to-br.from-orange-50.to-orange-100 .text-orange-600,
html.dark .bg-gradient-to-br.from-orange-50.to-orange-100 .text-orange-700 {
    color: #fdba74 !important;
}

html.dark .bg-gradient-to-br.from-red-50.to-red-100 {
    background-image: linear-gradient(135deg, #450a0a, #1e293b) !important;
    border-color: #991b1b !important;
}
html.dark .bg-gradient-to-br.from-red-50.to-red-100 .text-red-600,
html.dark .bg-gradient-to-br.from-red-50.to-red-100 .text-red-700 {
    color: #fca5a5 !important;
}

/* --- Status Info Box (Sesi Masih Terbuka / Sudah Presensi) --- */
html.dark .bg-gradient-to-r.from-slate-50.to-slate-100 {
    background-image: linear-gradient(to right, #1e293b, #1e293b) !important;
    border-color: #334155 !important;
}
html.dark .text-green-900 { color: #6ee7b7 !important; }
html.dark .text-green-700 { color: #6ee7b7 !important; }
html.dark .text-indigo-900 { color: #a5b4fc !important; }
html.dark .text-indigo-700 { color: #a5b4fc !important; }

/* --- Radio Button Attendance Options --- */
html.dark .border-gray-200.peer-checked\:border-green-500,
html.dark .border-gray-200.peer-checked\:border-blue-500,
html.dark .border-gray-200.peer-checked\:border-yellow-500 {
    border-color: #475569 !important;
}
html.dark .peer-checked\:bg-gradient-to-b.peer-checked\:from-green-50.peer-checked\:to-emerald-50 {
    background-image: linear-gradient(to bottom, #064e3b, #022c22) !important;
}
html.dark .peer-checked\:bg-gradient-to-b.peer-checked\:from-blue-50.peer-checked\:to-indigo-50 {
    background-image: linear-gradient(to bottom, #1e1b4b, #0f172a) !important;
}
html.dark .peer-checked\:bg-gradient-to-b.peer-checked\:from-yellow-50.peer-checked\:to-amber-50 {
    background-image: linear-gradient(to bottom, #78350f, #451a03) !important;
}
html.dark .peer-checked\:text-green-700 { color: #6ee7b7 !important; }
html.dark .peer-checked\:text-blue-700 { color: #93c5fd !important; }
html.dark .peer-checked\:text-yellow-700 { color: #fde047 !important; }

/* --- Empty State (Tidak Ada Sesi Aktif) --- */
html.dark .bg-gradient-to-br.from-gray-50.to-blue-50 {
    background-image: linear-gradient(135deg, #1e293b, #0f172a) !important;
    border-color: #475569 !important;
}
html.dark .bg-gray-200 {
    background-color: #334155 !important;
}

/* --- Class Info Card --- */
html.dark .bg-gradient-to-r.from-slate-50.to-slate-100.px-6 {
    background-image: linear-gradient(to right, #1e293b, #1e293b) !important;
    border-color: #334155 !important;
}

/* --- Class Info Sub-Cards --- */
html.dark .bg-gradient-to-br.from-indigo-50.to-indigo-100 {
    background-image: linear-gradient(135deg, #1e1b4b, #1e293b) !important;
    border-color: #312e81 !important;
}
html.dark .bg-gradient-to-br.from-indigo-50.to-indigo-100 .text-indigo-600 {
    color: #a5b4fc !important;
}
html.dark .bg-gradient-to-br.from-indigo-50.to-indigo-100 .text-gray-900 {
    color: #f1f5f9 !important;
}

html.dark .bg-gradient-to-br.from-cyan-50.to-cyan-100 {
    background-image: linear-gradient(135deg, #083344, #1e293b) !important;
    border-color: #0e7490 !important;
}
html.dark .bg-gradient-to-br.from-cyan-50.to-cyan-100 .text-cyan-600 {
    color: #67e8f9 !important;
}
html.dark .bg-gradient-to-br.from-cyan-50.to-cyan-100 .text-gray-900 {
    color: #f1f5f9 !important;
}

html.dark .bg-gradient-to-br.from-lime-50.to-lime-100 {
    background-image: linear-gradient(135deg, #1a2e05, #1e293b) !important;
    border-color: #4d7c0f !important;
}
html.dark .bg-gradient-to-br.from-lime-50.to-lime-100 .text-lime-600 {
    color: #bef264 !important;
}
html.dark .bg-gradient-to-br.from-lime-50.to-lime-100 .text-gray-900 {
    color: #f1f5f9 !important;
}

html.dark .bg-gradient-to-br.from-pink-50.to-pink-100 {
    background-image: linear-gradient(135deg, #500724, #1e293b) !important;
    border-color: #9d174d !important;
}
html.dark .bg-gradient-to-br.from-pink-50.to-pink-100 .text-pink-600 {
    color: #f9a8d4 !important;
}
html.dark .bg-gradient-to-br.from-pink-50.to-pink-100 .text-gray-900 {
    color: #f1f5f9 !important;
}

/* --- History Sidebar Card --- */
html.dark .bg-gradient-to-r.from-blue-50.to-indigo-50 {
    background-image: linear-gradient(to right, #1e1b4b, #1e293b) !important;
    border-color: #312e81 !important;
}
html.dark .bg-gradient-to-r.from-blue-50.to-indigo-50 .text-gray-900 {
    color: #f1f5f9 !important;
}

/* --- History Items --- */
html.dark .bg-gradient-to-r.from-slate-50.to-slate-100.hover\:from-slate-100.hover\:to-slate-200 {
    background-image: linear-gradient(to right, #1e293b, #1e293b) !important;
    border-color: #334155 !important;
}
html.dark .bg-gradient-to-r.from-slate-50.to-slate-100.hover\:from-slate-100.hover\:to-slate-200:hover {
    background-image: linear-gradient(to right, #334155, #334155) !important;
}

/* --- Status Badges (Hadir, Izin, Sakit, Alpha) --- */
html.dark .bg-green-100.text-green-800 {
    background-color: rgba(6, 78, 59, 0.5) !important;
    color: #6ee7b7 !important;
}
html.dark .bg-blue-100.text-blue-800 {
    background-color: rgba(30, 27, 75, 0.5) !important;
    color: #93c5fd !important;
}
html.dark .bg-yellow-100.text-yellow-800 {
    background-color: rgba(120, 53, 15, 0.5) !important;
    color: #fde047 !important;
}
html.dark .bg-red-100.text-red-800 {
    background-color: rgba(69, 10, 10, 0.5) !important;
    color: #fca5a5 !important;
}

/* --- Tips Card --- */
html.dark .bg-gradient-to-br.from-indigo-50.via-purple-50.to-pink-50 {
    background-image: linear-gradient(135deg, #1e1b4b, #3b0764, #500724) !important;
    border-color: #4c1d95 !important;
}
html.dark .bg-gradient-to-br.from-indigo-50.via-purple-50.to-pink-50 .text-indigo-900 {
    color: #c7d2fe !important;
}
html.dark .bg-gradient-to-br.from-indigo-50.via-purple-50.to-pink-50 .text-indigo-800 {
    color: #c7d2fe !important;
}
html.dark .bg-indigo-200 {
    background-color: #312e81 !important;
}
html.dark .text-indigo-700 {
    color: #a5b4fc !important;
}

/* --- Ringkasan Sidebar --- */
html.dark .bg-gradient-to-r.from-emerald-50.to-teal-50 {
    background-image: linear-gradient(to right, #022c22, #0f172a) !important;
    border-color: #047857 !important;
}
html.dark .bg-gradient-to-r.from-emerald-50.to-teal-50 .text-gray-900 {
    color: #f1f5f9 !important;
}

/* --- Alert Success/Warning Messages --- */
html.dark .bg-gradient-to-r.from-green-50.to-emerald-50.border {
    background-image: linear-gradient(to right, #064e3b, #022c22) !important;
    border-color: #047857 !important;
}
html.dark .bg-gradient-to-r.from-green-50.to-emerald-50 .text-green-900 {
    color: #6ee7b7 !important;
}
html.dark .bg-gradient-to-r.from-green-50.to-emerald-50 .text-green-700 {
    color: #6ee7b7 !important;
}

html.dark .bg-gradient-to-r.from-yellow-50.to-amber-50.border {
    background-image: linear-gradient(to right, #78350f, #451a03) !important;
    border-color: #b45309 !important;
}
html.dark .bg-gradient-to-r.from-yellow-50.to-amber-50 .text-yellow-900 {
    color: #fde047 !important;
}
html.dark .bg-gradient-to-r.from-yellow-50.to-amber-50 .text-yellow-700 {
    color: #fde047 !important;
}
</style>
@endsection