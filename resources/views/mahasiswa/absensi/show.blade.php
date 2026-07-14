@extends('layouts.portal')

@section('title', 'Presensi Kelas - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-sm text-blue-500 mb-2">
                <a href="{{ route('mahasiswa.absensi.index') }}" class="hover:text-blue-700 transition">Daftar Kelas</a>
                <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-blue-900 font-semibold truncate">Presensi</span>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Icon Presensi Kelas (Full Biru) -->
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm shadow-blue-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-blue-950 tracking-tight">Presensi Kelas</h1>
            </div>
            
            <p class="mt-2 text-blue-700/80 flex items-center gap-2 flex-wrap text-sm">
                <span class="inline-block px-2.5 py-0.5 rounded-lg bg-blue-100 text-blue-800 font-bold font-mono">{{ $kelas->kode_kelas }}</span>
                <span class="font-medium text-blue-900">{{ $kelas->mataKuliah->nama_mk }}</span>
            </p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
            <a href="{{ route('mahasiswa.absensi.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 rounded-xl font-bold text-sm transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('mahasiswa.absensi.riwayat', $kelas->id) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm transition shadow-md shadow-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Riwayat</span>
            </a>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT SIDE: Main Session and Status Check-In -->
        <div class="lg:col-span-2 space-y-4">
            
            <!-- Banner Sesi Presensi Aktif (DIUBAH DARI HIJAU KE GRADASI BIRU) -->
            <div class="bg-gradient-to-r from-blue-600 to-sky-500 rounded-2xl p-5 text-white shadow-md shadow-blue-100 relative overflow-hidden">
                <div class="flex items-start justify-between relative z-10">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/20">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-extrabold tracking-tight">Sesi Presensi Aktif</h2>
                            <p class="text-sky-100 text-xs mt-0.5">Silakan segera melakukan konfirmasi presensi</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-white/20 backdrop-blur-md border border-white/30 text-white tracking-wide">
                        ● Terbuka
                    </span>
                </div>
            </div>

            <!-- Detail Grid Info Sesi (DIUBAH UNTUK VARIASI BIRU SAJA) -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="bg-blue-50/60 rounded-xl p-4 border border-blue-100/70 shadow-sm">
                    <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-1">Pertemuan</p>
                    <p class="text-2xl font-black text-blue-800 font-mono">{{ $sesiAktif->pertemuan_ke ?? '26' }}</p>
                </div>
                <div class="bg-sky-50/60 rounded-xl p-4 border border-sky-100/70 shadow-sm">
                    <p class="text-[11px] font-bold text-sky-600 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="text-lg font-black text-sky-800 font-mono">{{ isset($sesiAktif) ? $sesiAktif->tanggal->format('d M') : '14 Jul' }}</p>
                </div>
                <div class="bg-indigo-50/50 rounded-xl p-4 border border-indigo-100/60 shadow-sm">
                    <p class="text-[11px] font-bold text-indigo-500 uppercase tracking-wider mb-1">Mulai</p>
                    <p class="text-base font-black text-indigo-800 font-mono">{{ $sesiAktif->jam_mulai ?? '07:30:00' }}</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 shadow-sm">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Selesai</p>
                    <p class="text-base font-black text-slate-800 font-mono">{{ $sesiAktif->jam_selesai ?? '21:10:00' }}</p>
                </div>
            </div>

            <!-- Card Status Konfirmasi Anda (DIUBAH MENJADI KOMBINASI BIRU ELEGAN) -->
            <div class="bg-white border border-blue-100 rounded-2xl p-6 shadow-sm flex flex-col items-center justify-center text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center shadow-inner">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                
                <div>
                    <h3 class="text-base font-black text-blue-950">Anda Sudah Melakukan Presensi</h3>
                    <p class="text-xs font-semibold text-blue-500 mt-1 flex items-center justify-center gap-3">
                        <span>Status: <strong class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-md font-bold uppercase tracking-wide text-[11px]">Izin</strong></span>
                        <span class="text-slate-300">|</span>
                        <span class="flex items-center gap-1 font-mono text-slate-600">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            05:29:42
                        </span>
                    </p>
                </div>

                <div class="w-full max-w-md border-t border-blue-50 pt-3">
                    <p class="text-xs text-blue-400 font-medium">Anda sudah melakukan presensi untuk sesi ini</p>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE: Sidebar Presensi 5 Terakhir -->
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
                <!-- Header Sidebar -->
                <div class="bg-blue-50/70 px-4 py-3.5 border-b border-blue-100 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xs font-black text-blue-900 uppercase tracking-wider">Presensi 5 Terakhir</h3>
                </div>

                <!-- List Item Riwayat Terakhir (MERAH & HIJAU DIGANTI JADI BIRU/SLATE) -->
                <div class="p-4 space-y-3">
                    
                    <!-- Contoh Alpha -> Diganti BG Slate / Dark Blue Muted -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/60 hover:bg-slate-100/50 transition">
                        <div>
                            <p class="text-sm font-bold text-blue-950">Pertemuan 23</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5 font-mono">14 Jul 2026</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-black tracking-wide uppercase bg-slate-200 text-slate-700">Alpha</span>
                    </div>

                    <!-- Contoh Alpha -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/60 hover:bg-slate-100/50 transition">
                        <div>
                            <p class="text-sm font-bold text-blue-950">Pertemuan 24</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5 font-mono">14 Jul 2026</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-black tracking-wide uppercase bg-slate-200 text-slate-700">Alpha</span>
                    </div>

                    <!-- Contoh Hadir -> Diganti BG Sky Blue Cerah -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-sky-50 border border-sky-100 hover:bg-sky-100/50 transition">
                        <div>
                            <p class="text-sm font-bold text-blue-950">Pertemuan 25</p>
                            <p class="text-[11px] font-medium text-sky-500/80 mt-0.5 font-mono">14 Jul 2026</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-black tracking-wide uppercase bg-sky-100 text-sky-800">Hadir</span>
                    </div>

                    <!-- Contoh Izin -> Diganti BG Indigo / Royal Blue -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-blue-50 border border-blue-100 hover:bg-blue-100/50 transition">
                        <div>
                            <p class="text-sm font-bold text-blue-950">Pertemuan 26</p>
                            <p class="text-[11px] font-medium text-blue-500 mt-0.5 font-mono">14 Jul 2026</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-black tracking-wide uppercase bg-blue-100 text-blue-800">Izin</span>
                    </div>

                    <!-- Contoh Alpha -->
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200/60 hover:bg-slate-100/50 transition">
                        <div>
                            <p class="text-sm font-bold text-blue-950">Pertemuan 17</p>
                            <p class="text-[11px] font-medium text-slate-400 mt-0.5 font-mono">10 Jul 2026</p>
                        </div>
                        <span class="px-2.5 py-1 rounded-lg text-[11px] font-black tracking-wide uppercase bg-slate-200 text-slate-700">Alpha</span>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection