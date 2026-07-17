@extends('layouts.portal')

@section('title', 'Presensi Kelas - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4">

    {{-- ===== HEADER BANNER ===== --}}
    <div class="mb-5 overflow-hidden rounded-2xl bg-gradient-to-br from-[#002B6B] to-[#0044a8] dark:from-indigo-950 dark:to-purple-900 p-6 sm:p-7 shadow-lg shadow-blue-950/15 relative transition-all">
        <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute right-16 bottom-0 h-20 w-20 rounded-full bg-white/5"></div>
        <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="min-w-0">
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <span class="rounded-lg bg-white/15 border border-white/20 px-2.5 py-1 text-xs font-bold text-white">
                        {{ $kelas->mataKuliah?->kode_mk ?? '-' }}
                    </span>
                    <span class="text-xs text-blue-200/80">Kelas {{ $kelas->kode_kelas }}</span>
                </div>
                <h1 class="text-xl font-extrabold leading-tight text-white sm:text-2xl">
                    {{ $kelas->mataKuliah?->nama_mk ?? 'Detail Kelas' }}
                </h1>
                <p class="mt-1.5 text-sm text-blue-100/80">
                    Bersama {{ $kelas->dosen?->name ?? 'Dosen pengampu' }}
                </p>
            </div>
            <a href="{{ route('mahasiswa.kelas-saya') }}"
               class="shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-white/20 bg-white/10 px-3 py-2 text-xs font-semibold text-white hover:bg-white/20 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- ===== INFO CARDS ===== --}}
    <div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
        @php
            $infoCards = [
                ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label' => 'Dosen', 'value' => $kelas->dosen?->name ?? '-', 'color' => 'text-blue-600 bg-blue-50 dark:text-purple-400 dark:bg-purple-950/40'],
                ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => $kelas->hari, 'value' => substr($kelas->jam_mulai,0,5).' – '.substr($kelas->jam_selesai,0,5), 'color' => 'text-sky-600 bg-sky-50 dark:text-amber-400 dark:bg-amber-950/40'],
                ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Ruangan', 'value' => $kelas->ruangan ?? '-', 'color' => 'text-indigo-600 bg-indigo-50 dark:text-rose-400 dark:bg-rose-950/40'],
                ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'SKS', 'value' => ($kelas->mataKuliah?->sks ?? 0).' SKS', 'color' => 'text-blue-700 bg-blue-50 dark:text-violet-400 dark:bg-purple-950/35'],
            ];
        @endphp
        @foreach ($infoCards as $card)
            <div class="flex items-center gap-3 rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-3.5 shadow-sm transition-colors duration-200">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $card['color'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}"/></svg>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-xs font-bold text-slate-800 dark:text-slate-200 leading-tight">{{ $card['value'] }}</p>
                    <p class="text-[10px] text-gray-400 dark:text-gray-550">{{ $card['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== TAB BUTTONS ===== --}}
    <div class="flex gap-2 overflow-x-auto pb-1">
        @php
            $tabLinks = [
                'semua'   => route('mahasiswa.kelas-detail', $kelas->id) . '?tab=semua',
                'materi'  => route('mahasiswa.kelas-detail', $kelas->id) . '?tab=materi',
                'tugas'   => route('mahasiswa.kelas-detail', $kelas->id) . '?tab=tugas',
                'absensi' => route('mahasiswa.absensi.kelas', $kelas->id),
                'forum'   => route('mahasiswa.kelas-forum', $kelas->id),
            ];
        @endphp
        @foreach (['semua' => 'Semua', 'materi' => 'Materi', 'tugas' => 'Tugas', 'absensi' => 'Absensi', 'forum' => 'Forum'] as $key => $label)
            <a href="{{ $tabLinks[$key] }}"
               class="whitespace-nowrap rounded-full px-5 py-2 text-xs font-bold transition-all duration-200
                   {{ $key === 'absensi'
                       ? 'bg-[#002B6B] dark:bg-purple-650 text-white shadow-sm shadow-blue-900/20 shadow-purple-900/20'
                       : 'bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 hover:border-[#002B6B] dark:hover:border-purple-500 hover:text-[#002B6B] dark:hover:text-purple-400' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <a href="{{ route('mahasiswa.kelas-detail', $kelas->id) }}?tab=absensi"
        class="inline-flex items-center gap-1.5 mb-4 text-sm font-medium text-slate-500 hover:text-[#002B6B] transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Detail Kelas
    </a>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- LEFT SIDE -->
        <div class="lg:col-span-2 space-y-4">

            @if($absensiAktif)
                <!-- Banner Sesi Presensi Aktif -->
                <div class="bg-gradient-to-r from-[#002B6B] to-blue-600 rounded-2xl p-5 text-white shadow-md shadow-blue-200 relative overflow-hidden">
                    <div class="pointer-events-none absolute -right-6 -top-6 h-28 w-28 rounded-full bg-white/10"></div>
                    <div class="flex items-start justify-between relative z-10">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/25">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-extrabold tracking-tight">Sesi Presensi Aktif</h2>
                                <p class="text-blue-100 text-xs mt-0.5">Silakan segera melakukan presensi</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/25 backdrop-blur-md border border-white/30 text-white tracking-wide">
                            ● Terbuka
                        </span>
                    </div>
                </div>

                <!-- Detail Grid Info Sesi -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-blue-50/60 rounded-xl p-4 border border-blue-100/70 shadow-sm">
                        <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-1">Pertemuan</p>
                        <p class="text-2xl font-black text-blue-800 font-mono">{{ $absensiAktif->pertemuan_ke }}</p>
                    </div>
                    <div class="bg-sky-50/60 rounded-xl p-4 border border-sky-100/70 shadow-sm">
                        <p class="text-[11px] font-bold text-sky-500 uppercase tracking-wider mb-1">Tanggal</p>
                        <p class="text-lg font-black text-sky-800 font-mono">{{ $absensiAktif->tanggal->format('d M') }}</p>
                    </div>
                    <div class="bg-indigo-50/60 rounded-xl p-4 border border-indigo-100/70 shadow-sm">
                        <p class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider mb-1">Mulai</p>
                        <p class="text-base font-black text-indigo-800 font-mono">{{ $absensiAktif->jam_mulai }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 shadow-sm">
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Selesai</p>
                        <p class="text-base font-black text-slate-800 font-mono">{{ $absensiAktif->jam_selesai }}</p>
                    </div>
                </div>

                @if($sudahAbsen)
                    <!-- Status: Sudah Absen -->
                    <div class="bg-white border border-blue-100 rounded-2xl p-6 shadow-sm flex flex-col items-center justify-center text-center space-y-4">
                        <div class="w-12 h-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center shadow-inner">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-black text-slate-800">Anda Sudah Melakukan Presensi</h3>
                            <p class="text-xs font-semibold text-slate-500 mt-1 flex items-center justify-center gap-3 flex-wrap">
                                @php
                                    $statusBadge = match($sudahAbsen->status) {
                                        'hadir' => 'bg-blue-100 text-blue-800',
                                        'izin' => 'bg-sky-100 text-sky-800',
                                        'sakit' => 'bg-amber-100 text-amber-800',
                                        default => 'bg-slate-100 text-slate-700',
                                    };
                                @endphp
                                <span>Status: <strong class="{{ $statusBadge }} px-2 py-0.5 rounded-md font-bold uppercase tracking-wide text-[11px]">{{ $sudahAbsen->getStatusLabel() }}</strong></span>
                                @if($sudahAbsen->waktu_absensi)
                                    <span class="text-slate-300">|</span>
                                    <span class="flex items-center gap-1 font-mono text-slate-600">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $sudahAbsen->waktu_absensi->format('H:i') }} WIB
                                    </span>
                                @endif
                            </p>
                        </div>
                        <div class="w-full max-w-md border-t border-slate-100 pt-3">
                            <p class="text-xs text-slate-400 font-medium">Anda sudah melakukan presensi untuk sesi ini</p>
                        </div>
                    </div>
                @else
                    <!-- Sesi Masih Terbuka info -->
                    <div class="flex items-center gap-3 rounded-2xl bg-blue-50/60 border border-blue-100 p-4">
                        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-blue-900">Sesi Masih Terbuka</p>
                            <p class="text-xs text-blue-500 font-medium">Lakukan presensi sekarang jika Anda hadir di kelas</p>
                        </div>
                    </div>

                    <!-- Form Pilih Status & Submit -->
                    <div x-data="{ status: 'hadir' }" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-5">
                        <form action="{{ route('mahasiswa.absensi.masuk', ['kelasId' => $kelas->id, 'absensiId' => $absensiAktif->id]) }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="status" :value="status">

                            <div>
                                <p class="flex items-center gap-1.5 text-sm font-bold text-slate-700 mb-3">
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Pilih Status Kehadiran Anda
                                </p>

                                <div class="grid grid-cols-3 gap-3">
                                    <button type="button" @click="status = 'hadir'"
                                            :class="status === 'hadir' ? 'border-blue-500 bg-blue-50 ring-2 ring-blue-200' : 'border-slate-200 bg-white hover:border-blue-200'"
                                            class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 py-5 px-2 transition">
                                        <div class="w-9 h-9 rounded-full bg-[#002B6B] flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-800">Hadir</span>
                                        <span class="text-[11px] text-slate-400">Saya hadir</span>
                                    </button>

                                    <button type="button" @click="status = 'izin'"
                                            :class="status === 'izin' ? 'border-sky-400 bg-sky-50 ring-2 ring-sky-200' : 'border-slate-200 bg-white hover:border-sky-200'"
                                            class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 py-5 px-2 transition">
                                        <div class="w-9 h-9 rounded-full bg-sky-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m2 7H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V17a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-800">Izin</span>
                                        <span class="text-[11px] text-slate-400">Tidak hadir</span>
                                    </button>

                                    <button type="button" @click="status = 'sakit'"
                                            :class="status === 'sakit' ? 'border-amber-400 bg-amber-50 ring-2 ring-amber-200' : 'border-slate-200 bg-white hover:border-amber-200'"
                                            class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 py-5 px-2 transition">
                                        <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-800">Sakit</span>
                                        <span class="text-[11px] text-slate-400">Tidak sehat</span>
                                    </button>
                                </div>
                            </div>

                            <div x-show="status !== 'hadir'" x-cloak x-transition class="space-y-2">
                                <label for="keterangan" class="block text-xs font-bold text-slate-500 uppercase tracking-wider">Alasan / Keterangan</label>
                                <textarea id="keterangan" name="keterangan" rows="3"
                                          :required="status !== 'hadir'"
                                          placeholder="Tulis alasan berhalangan Anda..."
                                          class="w-full px-3 py-2 border border-slate-200 bg-slate-50 text-slate-800 rounded-xl focus:outline-none focus:border-blue-400 focus:ring-1 focus:ring-blue-400 text-sm resize-none"></textarea>
                            </div>

                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2.5 py-4 px-6 rounded-2xl bg-gradient-to-r from-[#002B6B] to-blue-600 hover:from-blue-900 hover:to-blue-700 text-white font-bold text-sm shadow-lg shadow-blue-500/10 hover:shadow-xl transition-all duration-300 hover:scale-[1.01] active:scale-[0.99]">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Presensi / Absen Masuk</span>
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <!-- Tidak Ada Sesi Aktif -->
                <div class="bg-slate-50/70 rounded-2xl border-2 border-dashed border-slate-200 p-10 text-center shadow-sm">
                    <div class="w-14 h-14 rounded-full bg-white border border-slate-200 flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak Ada Sesi Presensi Aktif</h3>
                    <p class="text-sm text-slate-500">Sesi presensi mandiri belum dibuka oleh dosen untuk hari ini.</p>
                </div>
            @endif

            <!-- Informasi Kelas -->
            <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="text-sm font-black text-slate-800">Informasi Kelas</h3>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="rounded-xl p-4 bg-gradient-to-br from-blue-50 to-blue-50/40 border border-blue-100/70">
                        <p class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">Pengajar</p>
                        <p class="text-sm font-black text-blue-900 truncate">{{ $kelas->dosen?->name ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4 bg-gradient-to-br from-sky-50 to-sky-50/40 border border-sky-100/70">
                        <p class="text-[10px] font-bold text-sky-500 uppercase tracking-wider mb-1">Ruangan</p>
                        <p class="text-sm font-black text-sky-900 truncate">{{ $kelas->ruangan ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4 bg-gradient-to-br from-indigo-50 to-indigo-50/40 border border-indigo-100/70">
                        <p class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider mb-1">Hari</p>
                        <p class="text-sm font-black text-indigo-900 truncate">{{ $kelas->hari ?? '-' }}</p>
                    </div>
                    <div class="rounded-xl p-4 bg-gradient-to-br from-blue-50 to-blue-50/40 border border-blue-100/70 sm:col-span-3">
                        <p class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">Jam Kuliah</p>
                        <p class="text-sm font-black text-blue-900">{{ substr($kelas->jam_mulai,0,5) }} - {{ substr($kelas->jam_selesai,0,5) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE: Sidebar -->
        <div class="space-y-4">

            @if($riwayatAbsensi->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="bg-blue-50/60 px-4 py-3.5 border-b border-blue-100 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="text-xs font-black text-blue-900 uppercase tracking-wider">Presensi 5 Terakhir</h3>
                    </div>

                    <div class="p-4 space-y-2.5">
                        @foreach($riwayatAbsensi->take(5) as $absensi)
                            @php
                                $attendance = $absensi->absensiMahasiswa->first();
                                $rowStatus = $attendance?->status ?? 'alpha';
                                $rowStyle = match($rowStatus) {
                                    'hadir' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-100', 'badge' => 'bg-blue-100 text-blue-700'],
                                    'izin'  => ['bg' => 'bg-sky-50', 'border' => 'border-sky-100', 'badge' => 'bg-sky-100 text-sky-700'],
                                    'sakit' => ['bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'badge' => 'bg-amber-100 text-amber-700'],
                                    default => ['bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'badge' => 'bg-slate-200 text-slate-700'],
                                };
                                $rowLabel = $attendance?->getStatusLabel() ?? 'Alpha';
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-xl {{ $rowStyle['bg'] }} border {{ $rowStyle['border'] }} hover:brightness-95 transition">
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-800">Pertemuan {{ $absensi->pertemuan_ke }}</p>
                                    <p class="text-[11px] font-medium text-slate-400 mt-0.5 font-mono">{{ $absensi->tanggal->format('d M Y') }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg text-[11px] font-black tracking-wide uppercase {{ $rowStyle['badge'] }} flex-shrink-0 ml-2">{{ $rowLabel }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Tips Presensi -->
            <div class="bg-gradient-to-br from-sky-50 to-blue-50 rounded-2xl border border-blue-100 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-blue-900 text-sm mb-2">Tips Presensi</h4>
                        <ul class="text-xs text-blue-800/90 space-y-1.5">
                            <li class="flex items-start gap-1.5"><span class="text-blue-500 font-bold">✓</span> Presensi hanya saat sesi terbuka</li>
                            <li class="flex items-start gap-1.5"><span class="text-blue-500 font-bold">✓</span> Satu kali presensi per sesi</li>
                            <li class="flex items-start gap-1.5"><span class="text-blue-500 font-bold">✓</span> Cantumkan alasan jika izin/sakit</li>
                            <li class="flex items-start gap-1.5"><span class="text-blue-500 font-bold">✓</span> Cek riwayat secara berkala</li>
                        </ul>
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