@extends('layouts.portal')

@section('title', 'Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                <a href="{{ route('mahasiswa.absensi.index') }}" class="hover:text-blue-600 transition">Daftar Kelas</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-slate-800 font-semibold truncate">Presensi</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-sky-500 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="truncate">Presensi Kelas</span>
            </h1>
            <p class="mt-2 text-slate-500 flex items-center gap-2 flex-wrap">
                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">{{ $kelas->kode_kelas }}</span>
                <span>{{ $kelas->mataKuliah->nama_mk }}</span>
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('mahasiswa.absensi.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span class="hidden sm:inline">Kembali</span>
            </a>
            <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-semibold transition shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="hidden sm:inline">Riwayat</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-emerald-900">Berhasil!</p>
                <p class="text-sm text-emerald-700 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-amber-900">Perhatian!</p>
                <p class="text-sm text-amber-700 mt-0.5">{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            @if($absensiAktif)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <!-- Header dengan Status -->
                    <div class="bg-gradient-to-r from-blue-600 via-sky-600 to-blue-700 px-6 sm:px-8 py-4 sm:py-5 text-white">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold">Sesi Presensi Aktif</h2>
                                    <p class="text-sky-100 text-sm mt-0.5">Silakan segera melakukan presensi</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-emerald-400/90 text-emerald-950 font-bold text-sm shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-800 mr-2 animate-pulse"></span>
                                Terbuka
                            </span>
                        </div>
                    </div>

                    <!-- Session Info -->
                    <div class="px-5 sm:px-6 py-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                <p class="text-blue-600 text-xs font-bold uppercase tracking-wide mb-2">Pertemuan</p>
                                <p class="text-lg font-black text-blue-700">{{ $absensiAktif->pertemuan_ke }}</p>
                            </div>
                            <div class="bg-sky-50 rounded-xl p-4 border border-sky-100">
                                <p class="text-sky-600 text-xs font-bold uppercase tracking-wide mb-2">Tanggal</p>
                                <p class="text-lg font-black text-sky-700">{{ $absensiAktif->tanggal->format('d M') }}</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                <p class="text-slate-500 text-xs font-bold uppercase tracking-wide mb-2">Mulai</p>
                                <p class="text-lg font-black text-slate-700">{{ $absensiAktif->jam_mulai }}</p>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                <p class="text-slate-500 text-xs font-bold uppercase tracking-wide mb-2">Selesai</p>
                                <p class="text-lg font-black text-slate-700">{{ $absensiAktif->jam_selesai }}</p>
                            </div>
                        </div>

                        <!-- Status Info -->
                        <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 mb-6">
                            @if($sudahAbsen)
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-emerald-900 text-lg">Anda Sudah Melakukan Presensi</p>
                                        <div class="flex items-center gap-4 mt-2 flex-wrap">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm text-slate-500">Status:</span>
                                                @php
                                                    $statusColor = match($sudahAbsen->status) {
                                                        'hadir' => 'bg-emerald-100 text-emerald-800',
                                                        'izin' => 'bg-blue-100 text-blue-800',
                                                        'sakit' => 'bg-amber-100 text-amber-800',
                                                        default => 'bg-slate-100 text-slate-700',
                                                    };
                                                @endphp
                                                <span class="px-3 py-1 rounded-full text-sm font-bold {{ $statusColor }}">
                                                    {{ $sudahAbsen->getStatusLabel() }}
                                                </span>
                                            </div>
                                            @if($sudahAbsen->waktu_absensi)
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span class="text-sm font-semibold text-slate-600">{{ $sudahAbsen->waktu_absensi->format('H:i:s') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center flex-shrink-0 shadow-sm animate-pulse">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-blue-900 text-lg">Sesi Masih Terbuka</p>
                                        <p class="text-sm text-blue-700/80 mt-0.5">Lakukan presensi sekarang jika Anda hadir di kelas</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Attendance Form -->
                        @if(!$sudahAbsen)
                            <form action="{{ route('mahasiswa.absensi.masuk', ['kelasId' => $kelas->id, 'absensiId' => $absensiAktif->id]) }}" method="POST" x-data="{ status: 'hadir' }" class="space-y-6">
                                @csrf

                                <div>
                                    <label class="block text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Pilih Status Kehadiran Anda
                                    </label>
                                    <div class="grid grid-cols-3 gap-3">
                                        <!-- Hadir Button -->
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" value="hadir" x-model="status" class="sr-only">
                                            <div class="relative p-3 sm:p-4 rounded-xl border-2 transition-all duration-200 shadow-sm text-center"
                                                 :class="status === 'hadir' ? 'border-emerald-500 bg-emerald-50 shadow-md' : 'border-slate-200 hover:border-emerald-300'">
                                                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors"
                                                     :class="status === 'hadir' ? 'bg-emerald-600' : 'bg-emerald-100'">
                                                    <svg class="w-6 h-6 transition-colors" :class="status === 'hadir' ? 'text-white' : 'text-emerald-600'" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm sm:text-base font-bold block" :class="status === 'hadir' ? 'text-emerald-700' : 'text-slate-800'">Hadir</span>
                                                <span class="text-xs mt-0.5 block" :class="status === 'hadir' ? 'text-emerald-600' : 'text-slate-500'">Saya hadir</span>
                                            </div>
                                        </label>

                                        <!-- Izin Button -->
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" value="izin" x-model="status" class="sr-only">
                                            <div class="relative p-3 sm:p-4 rounded-xl border-2 transition-all duration-200 shadow-sm text-center"
                                                 :class="status === 'izin' ? 'border-blue-500 bg-blue-50 shadow-md' : 'border-slate-200 hover:border-blue-300'">
                                                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors"
                                                     :class="status === 'izin' ? 'bg-blue-600' : 'bg-blue-100'">
                                                    <svg class="w-6 h-6 transition-colors" :class="status === 'izin' ? 'text-white' : 'text-blue-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm sm:text-base font-bold block" :class="status === 'izin' ? 'text-blue-700' : 'text-slate-800'">Izin</span>
                                                <span class="text-xs mt-0.5 block" :class="status === 'izin' ? 'text-blue-600' : 'text-slate-500'">Tidak hadir</span>
                                            </div>
                                        </label>

                                        <!-- Sakit Button -->
                                        <label class="cursor-pointer">
                                            <input type="radio" name="status" value="sakit" x-model="status" class="sr-only">
                                            <div class="relative p-3 sm:p-4 rounded-xl border-2 transition-all duration-200 shadow-sm text-center"
                                                 :class="status === 'sakit' ? 'border-amber-500 bg-amber-50 shadow-md' : 'border-slate-200 hover:border-amber-300'">
                                                <div class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-2 transition-colors"
                                                     :class="status === 'sakit' ? 'bg-amber-600' : 'bg-amber-100'">
                                                    <svg class="w-6 h-6 transition-colors" :class="status === 'sakit' ? 'text-white' : 'text-amber-600'" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2m0 2a8 8 0 100 16 8 8 0 000-16zm0 3a1 1 0 110 2 1 1 0 010-2zm0 7a1 1 0 100 2 1 1 0 000-2z"/>
                                                    </svg>
                                                </div>
                                                <span class="text-sm sm:text-base font-bold block" :class="status === 'sakit' ? 'text-amber-700' : 'text-slate-800'">Sakit</span>
                                                <span class="text-xs mt-0.5 block" :class="status === 'sakit' ? 'text-amber-600' : 'text-slate-500'">Tidak sehat</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Reason Field -->
                                <div x-show="status === 'izin' || status === 'sakit'" x-cloak class="animate-in fade-in duration-200">
                                    <label for="keterangan" class="block text-sm font-bold text-slate-800 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                        Keterangan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        id="keterangan"
                                        name="keterangan"
                                        rows="3"
                                        :required="status === 'izin' || status === 'sakit'"
                                        placeholder="Jelaskan alasan izin atau sakit Anda dengan singkat..."
                                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none shadow-sm @error('keterangan') border-red-500 @enderror">
                                    </textarea>
                                    @error('keterangan')
                                        <p class="mt-2 text-sm text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <button
                                    type="submit"
                                    class="w-full font-bold py-3 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center justify-center gap-2 text-sm sm:text-base text-white duration-200"
                                    :class="{
                                        'bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800': status === 'hadir',
                                        'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800': status === 'izin',
                                        'bg-gradient-to-r from-amber-600 to-amber-700 hover:from-amber-700 hover:to-amber-800': status === 'sakit',
                                    }">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="status === 'hadir' ? 'Presensi / Absen Masuk' : (status === 'izin' ? 'Kirim Izin' : 'Kirim Sakit')"></span>
                                </button>
                            </form>
                        @else
                            <div class="text-center py-6">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-slate-600 font-medium">Anda sudah melakukan presensi untuk sesi ini</p>
                                <p class="text-sm text-slate-400 mt-1">Terima kasih telah melakukan presensi tepat waktu</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-sky-50/60 rounded-2xl border-2 border-dashed border-sky-200 p-8 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 rounded-full bg-white border border-slate-200 flex items-center justify-center mx-auto mb-6 shadow-sm">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-800 mb-2">Tidak Ada Sesi Presensi Aktif</h3>
                    <p class="text-slate-600 mb-4">Sesi presensi belum dibuka oleh dosen untuk hari ini.</p>
                    <p class="text-sm text-slate-400">Silakan cek kembali nanti atau hubungi dosen Anda.</p>
                </div>
            @endif

            <!-- Class Info Card -->
            <div class="mt-6 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-slate-800">Informasi Kelas</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                            <p class="text-blue-600 text-xs font-bold uppercase tracking-wide mb-1">Pengajar</p>
                            <p class="font-bold text-slate-800 truncate" title="{{ $kelas->dosen->name }}">{{ $kelas->dosen->name }}</p>
                        </div>
                        <div class="bg-sky-50 rounded-xl p-4 border border-sky-100">
                            <p class="text-sky-600 text-xs font-bold uppercase tracking-wide mb-1">Ruangan</p>
                            <p class="font-bold text-slate-800">{{ $kelas->ruangan ?? '—' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wide mb-1">Hari</p>
                            <p class="font-bold text-slate-800">{{ $kelas->hari ?? '—' }}</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-4 border border-blue-100 md:col-span-2">
                            <p class="text-blue-600 text-xs font-bold uppercase tracking-wide mb-1">Jam Kuliah</p>
                            <p class="font-bold text-slate-800">{{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            @if($riwayatAbsensi->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition">
                    <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="font-bold text-slate-800">Presensi 5 Terakhir</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @foreach($riwayatAbsensi->take(5) as $absensi)
                            @php $attendance = $absensi->absensiMahasiswa->first(); @endphp
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 transition">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-800">Pertemuan {{ $absensi->pertemuan_ke }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">{{ $absensi->tanggal->format('d M Y') }}</p>
                                </div>
                                @if($attendance)
                                    <span @class([
                                        'inline-flex items-center px-3 py-1 rounded-lg text-xs font-bold flex-shrink-0 ml-2',
                                        'bg-emerald-100 text-emerald-800' => $attendance->status === 'hadir',
                                        'bg-blue-100 text-blue-800' => $attendance->status === 'izin',
                                        'bg-amber-100 text-amber-800' => $attendance->status === 'sakit',
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
            <div class="bg-blue-50 rounded-2xl border border-blue-100 p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-900 mb-2">Tips Presensi</h4>
                        <ul class="text-xs text-blue-800 space-y-1.5">
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span>Presensi hanya saat sesi terbuka</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span>Satu kali presensi per sesi</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span>Cantumkan alasan jika izin/sakit</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-600 font-bold">✓</span>
                                <span>Cek riwayat secara berkala</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h3 class="font-bold text-slate-800">Ringkasan</h3>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between p-2">
                        <span class="text-sm font-medium text-slate-500">Lihat Riwayat Lengkap</span>
                        <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}" class="text-blue-600 hover:text-blue-700 font-bold text-sm">→</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection