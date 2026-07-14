@extends('layouts.portal')

@section('title', 'Presensi')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4 bg-sky-50/40 rounded-2xl border border-sky-100 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-sky-100">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-3">
                <div class="p-2 bg-blue-100 text-blue-700 rounded-xl border border-blue-200 shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m0 0V3a2 2 0 00-2-2h-2a2 2 0 00-2 2v2z" />
                    </svg>
                </div>
                <span>Presensi Kelas Saya</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500">Kelola dan pantau persentase serta riwayat kehadiran Anda pada setiap kelas perkuliahan aktif</p>
        </div>
    </div>

    @if(session('success'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="p-1 bg-emerald-100 text-emerald-700 rounded-md flex-shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-bold text-emerald-900">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="p-1 bg-amber-100 text-amber-700 rounded-md flex-shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-bold text-amber-900">{{ session('warning') }}</p>
        </div>
    @endif

    @if($kelasList->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
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
                <div class="group bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg hover:border-blue-300 transition-all duration-300 hover:-translate-y-0.5">
                    <div class="h-12 bg-gradient-to-r from-blue-600 via-sky-500 to-blue-700 relative overflow-hidden">
                        <div class="absolute inset-0 opacity-15">
                            <svg class="w-24 h-24 absolute -right-4 -top-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative px-5 py-4 -mt-6 bg-white rounded-t-2xl">
                        <h3 class="text-base font-bold text-slate-800 mb-1 line-clamp-2 min-h-[3rem] group-hover:text-blue-600 transition duration-200">
                            {{ $kelas->mataKuliah->nama_mk }}
                        </h3>

                        <div class="flex items-center justify-between mb-3.5">
                            <span class="bg-slate-100 border border-slate-200 text-slate-600 font-mono text-xs font-bold px-2 py-0.5 rounded-md">
                                Kelas {{ $kelas->kode_kelas }}
                            </span>
                            @if($absensiAktif)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold shadow-sm">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Presensi Terbuka
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2.5 mb-4 pb-3.5 border-b border-slate-100">
                            <div class="w-7 h-7 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-slate-500 truncate" title="{{ $kelas->dosen->name }}">
                                {{ $kelas->dosen->name }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-sky-50 border border-sky-100 rounded-xl p-2.5 text-center transition duration-200 group-hover:bg-sky-100/70">
                                <p class="text-lg font-black text-blue-600">{{ $totalHadir }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Kehadiran</p>
                            </div>
                            <div class="bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-center transition duration-200 group-hover:bg-slate-100">
                                <p class="text-lg font-black text-slate-700">{{ $totalPertemuan }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Total Sesi</p>
                            </div>
                        </div>

                        @if($totalPertemuan > 0)
                            <div class="mb-5">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-xs font-bold text-slate-500">Persentase Kehadiran</span>
                                    <span class="text-xs font-black text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">{{ $attendancePercentage }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                    <div class="bg-gradient-to-r from-blue-500 to-sky-500 h-full rounded-full transition-all duration-500"
                                         style="width: {{ $attendancePercentage }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-2.5 pt-1">
                            <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}"
                               class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-bold transition text-xs shadow-sm hover:shadow-md">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Masuk Presensi</span>
                            </a>
                            <a href="{{ route('mahasiswa.absensi.show', $kelas->id) }}"
                               class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-bold transition text-xs shadow-sm">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Log Riwayat</span>
                            </a>
                            <a href="{{ route('mahasiswa.kelas-detail', $kelas->id) }}"
                                class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 text-slate-500 hover:text-blue-600 rounded-xl font-semibold transition text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                    <span>Kembali ke Detail Kelas</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex justify-center mt-6 pt-4 border-t border-slate-200">
            {{ $kelasList->links() }}
        </div>
    @else
        <div class="text-center py-10 px-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl border border-blue-100 shadow-inner flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-700">Belum Ada Kelas Terdaftar</h3>
            <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto mb-5">Akun Anda belum terdata mengambil kelas perkuliahan aktif di semester berjalan ini.</p>
            <a href="{{ route('mahasiswa.kelas-saya') }}"
               class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Lihat Modul Kelas Saya</span>
            </a>
        </div>
    @endif
</div>
@endsection