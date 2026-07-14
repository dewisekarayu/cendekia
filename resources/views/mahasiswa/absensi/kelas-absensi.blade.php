@extends('layouts.portal')

@section('title', 'Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4">
    <!-- Header with Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-2">
                <a href="{{ route('mahasiswa.absensi.index') }}" class="hover:text-blue-600 dark:hover:text-purple-400 transition">Daftar Kelas</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-slate-800 dark:text-slate-200 font-semibold truncate">Presensi</span>
            </div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-sky-500 dark:from-purple-900 dark:to-indigo-850 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span class="truncate">Presensi Kelas</span>
            </h1>
            <p class="mt-2 text-slate-500 dark:text-slate-400 flex items-center gap-2 flex-wrap">
                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 dark:bg-purple-950/50 text-blue-700 dark:text-purple-400 text-sm font-semibold">{{ $kelas->kode_kelas }}</span>
                <span>{{ $kelas->mataKuliah->nama_mk }}</span>
            </p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('mahasiswa.absensi.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-semibold transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#321270] hover:bg-[#230c50] dark:bg-purple-650 dark:hover:bg-purple-700 text-white rounded-xl font-semibold transition shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Riwayat Lengkap</span>
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/30 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-emerald-900 dark:text-emerald-300">Berhasil!</p>
                <p class="text-sm text-emerald-700 dark:text-emerald-450 mt-0.5">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/30 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/50 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-amber-600 dark:text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-amber-900 dark:text-amber-300">Perhatian!</p>
                <p class="text-sm text-amber-700 dark:text-amber-450 mt-0.5">{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            @if($absensiAktif)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                    <!-- Header dengan Status -->
                    <div class="bg-gradient-to-r from-blue-600 via-sky-600 to-blue-700 dark:from-purple-900 dark:to-indigo-850 px-6 sm:px-8 py-4 sm:py-5 text-white">
                        <div class="flex items-center justify-between gap-4 flex-wrap">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-white/15 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-bold">Sesi Presensi Aktif</h2>
                                    <p class="text-sky-100 dark:text-purple-200 text-sm mt-0.5">Silakan lakukan absensi kehadiran hari ini</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-emerald-400/90 dark:bg-emerald-900/80 text-emerald-950 dark:text-emerald-350 font-bold text-sm shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-800 dark:bg-emerald-400 mr-2 animate-pulse"></span>
                                Terbuka
                            </span>
                        </div>
                    </div>

                    <!-- Session Info -->
                    <div class="px-5 sm:px-6 py-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-blue-50 dark:bg-slate-900/50 rounded-xl p-4 border border-blue-100 dark:border-slate-700">
                                <p class="text-blue-600 dark:text-purple-400 text-xs font-bold uppercase tracking-wide mb-2">Pertemuan</p>
                                <p class="text-lg font-black text-blue-700 dark:text-purple-300">Pertemuan {{ $absensiAktif->pertemuan_ke }}</p>
                            </div>
                            <div class="bg-sky-50 dark:bg-slate-900/50 rounded-xl p-4 border border-sky-100 dark:border-slate-700">
                                <p class="text-sky-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wide mb-2">Tanggal</p>
                                <p class="text-lg font-black text-sky-700 dark:text-indigo-300">{{ $absensiAktif->tanggal->format('d M') }}</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-4 border border-slate-200 dark:border-slate-700/60">
                                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wide mb-2">Mulai</p>
                                <p class="text-lg font-black text-slate-700 dark:text-slate-350">{{ $absensiAktif->jam_mulai }}</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-4 border border-slate-200 dark:border-slate-700/60">
                                <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wide mb-2">Selesai</p>
                                <p class="text-lg font-black text-slate-700 dark:text-slate-350">{{ $absensiAktif->jam_selesai }}</p>
                            </div>
                        </div>

                        <!-- Status & Check-in Logic -->
                        @if($sudahAbsen)
                            <div class="bg-slate-50 dark:bg-slate-900/50 rounded-xl p-5 border border-slate-200 dark:border-slate-700 text-center">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-950/40 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-450" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-slate-800 dark:text-white text-lg">Kehadiran Anda Berhasil Dicatat</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Terima kasih telah melakukan presensi secara mandiri</p>
                                
                                <div class="flex items-center justify-center gap-6 mt-4 flex-wrap text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="text-slate-400">Status Kehadiran:</span>
                                        @php
                                            $statusColor = match($sudahAbsen->status) {
                                                'hadir' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-400 border border-emerald-900/30',
                                                'izin' => 'bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-400 border border-blue-900/30',
                                                'sakit' => 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-400 border border-amber-900/30',
                                                default => 'bg-slate-100 text-slate-700 dark:bg-slate-900 dark:text-slate-400',
                                            };
                                        @endphp
                                        <span class="px-3 py-0.5 rounded-full font-bold {{ $statusColor }}">
                                            {{ $sudahAbsen->getStatusLabel() }}
                                        </span>
                                    </div>
                                    @if($sudahAbsen->waktu_absensi)
                                        <div class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-semibold">{{ $sudahAbsen->waktu_absensi->format('H:i') }} WIB</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Single-click Instant Check-in & Leave Form -->
                            <div x-data="{ openPermit: false, status: 'hadir' }" class="space-y-4">
                                <!-- Form Absen Mandiri Instan -->
                                <form action="{{ route('mahasiswa.absensi.masuk', ['kelasId' => $kelas->id, 'absensiId' => $absensiAktif->id]) }}" method="POST">
                                    @csrf
                                    <!-- Default status is Hadir for self check-in -->
                                    <input type="hidden" name="status" value="hadir">
                                    
                                    <button type="submit" 
                                            class="w-full flex items-center justify-center gap-3 py-4 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-base shadow-lg shadow-emerald-500/10 hover:shadow-xl transition-all duration-300 hover:scale-[1.01] active:scale-[0.99]">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Lakukan Presensi Hadir (Check-in Sekarang)</span>
                                    </button>
                                </form>

                                <!-- Sakit / Izin Toggle Link -->
                                <div class="text-center">
                                    <button type="button" @click="openPermit = !openPermit" 
                                            class="text-xs sm:text-sm font-semibold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-purple-400 underline transition">
                                        Saya Berhalangan Hadir (Ingin Mengajukan Sakit / Izin)
                                    </button>
                                </div>

                                <!-- Form Sakit / Izin Form (Toggled) -->
                                <form x-show="openPermit" x-cloak x-transition
                                      action="{{ route('mahasiswa.absensi.masuk', ['kelasId' => $kelas->id, 'absensiId' => $absensiAktif->id]) }}" 
                                      method="POST" 
                                      class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 space-y-4 animate-in fade-in slide-in-from-top-2 duration-300">
                                    @csrf
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Pilih Status Berhalangan</label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="status" value="sakit" x-model="status" class="accent-purple-500">
                                                <span class="text-sm font-bold text-slate-700 dark:text-slate-350">Sakit (Sick)</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="status" value="izin" x-model="status" class="accent-purple-500">
                                                <span class="text-sm font-bold text-slate-700 dark:text-slate-350">Izin (Permitted)</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="keterangan" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Alasan / Keterangan</label>
                                        <textarea id="keterangan" name="keterangan" rows="3" required
                                                  placeholder="Tulis alasan berhalangan Anda..."
                                                  class="w-full px-3 py-2 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-xl focus:outline-none focus:border-purple-500 dark:focus:border-purple-400 focus:ring-1 focus:ring-purple-500 dark:focus:ring-purple-400 text-sm resize-none"></textarea>
                                    </div>

                                    <button type="submit" 
                                            class="w-full py-2.5 px-4 bg-purple-600 hover:bg-purple-700 text-white text-sm font-bold rounded-xl shadow transition duration-200">
                                        Kirim Pengajuan Absensi
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-sky-50/50 dark:bg-slate-900/50 rounded-2xl border-2 border-dashed border-sky-200 dark:border-slate-700 p-8 text-center shadow-sm transition">
                    <div class="w-14 h-14 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-1">Tidak Ada Sesi Presensi Aktif</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Sesi presensi mandiri belum dibuka oleh dosen untuk hari ini.</p>
                </div>
            @endif

            <!-- Class Info Card -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                <div class="bg-slate-50 dark:bg-slate-900/30 px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Informasi Kelas Perkuliahan</h3>
                </div>
                <div class="px-6 py-5">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <div class="bg-blue-50 dark:bg-slate-900/50 rounded-xl p-4 border border-blue-100/50 dark:border-slate-700">
                            <p class="text-blue-600 dark:text-purple-400 text-xs font-bold uppercase tracking-wide mb-1">Dosen Pengampu</p>
                            <p class="font-bold text-slate-800 dark:text-slate-200 truncate" title="{{ $kelas->dosen->name }}">{{ $kelas->dosen->name }}</p>
                        </div>
                        <div class="bg-sky-50 dark:bg-slate-900/50 rounded-xl p-4 border border-sky-100/50 dark:border-slate-700">
                            <p class="text-sky-600 dark:text-indigo-400 text-xs font-bold uppercase tracking-wide mb-1">Ruangan</p>
                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $kelas->ruangan ?? '—' }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-900/30 rounded-xl p-4 border border-slate-200 dark:border-slate-700/60">
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-wide mb-1">Hari</p>
                            <p class="font-bold text-slate-800 dark:text-slate-200">{{ $kelas->hari ?? '—' }}</p>
                        </div>
                        <div class="bg-blue-50 dark:bg-slate-900/50 rounded-xl p-4 border border-blue-100/50 dark:border-slate-700 md:col-span-2">
                            <p class="text-blue-600 dark:text-purple-400 text-xs font-bold uppercase tracking-wide mb-1">Jam Kuliah</p>
                            <p class="font-bold text-slate-800 dark:text-slate-200 font-mono">{{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            @if($riwayatAbsensi->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors">
                    <div class="bg-blue-50/50 dark:bg-slate-900/30 px-6 py-4 border-b border-blue-100 dark:border-slate-700 flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="font-bold text-slate-800 dark:text-white text-sm">Riwayat 5 Pertemuan Terakhir</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        @foreach($riwayatAbsensi->take(5) as $absensi)
                            @php $attendance = $absensi->absensiMahasiswa->first(); @endphp
                            <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 dark:bg-slate-900/40 border border-slate-100 dark:border-slate-700/50 hover:bg-slate-100 dark:hover:bg-slate-700/30 transition">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 dark:text-slate-200">Pertemuan {{ $absensi->pertemuan_ke }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $absensi->tanggal->format('d M Y') }}</p>
                                </div>
                                @if($attendance)
                                    <span @class([
                                        'inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold flex-shrink-0 ml-2',
                                        'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-400 dark:border-emerald-900/20' => $attendance->status === 'hadir',
                                        'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950 dark:text-blue-400 dark:border-blue-900/20' => $attendance->status === 'izin',
                                        'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950 dark:text-amber-400 dark:border-amber-900/20' => $attendance->status === 'sakit',
                                        'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950 dark:text-rose-400 dark:border-rose-900/20' => $attendance->status === 'alpha',
                                    ])>
                                        {{ $attendance->getStatusLabel() }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950 dark:text-rose-400 dark:border-rose-900/20 flex-shrink-0 ml-2">Alpha</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Tips -->
            <div class="bg-blue-50/50 dark:bg-purple-950/20 rounded-2xl border border-blue-100/50 dark:border-purple-900/30 p-5 shadow-sm hover:shadow-md transition">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-purple-900/50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-700 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-blue-900 dark:text-purple-300 text-sm mb-2">Ketentuan Presensi</h4>
                        <ul class="text-xs text-blue-800 dark:text-purple-400 space-y-1.5 list-disc list-inside">
                            <li>Lakukan check-in saat rentang jam kuliah berlangsung.</li>
                            <li>Toleransi keterlambatan mandiri diatur oleh dosen pengampu.</li>
                            <li>Jika izin/sakit, lampirkan alasan yang jelas untuk verifikasi dosen.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection