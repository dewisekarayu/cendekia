@extends('layouts.portal')

@section('title', 'Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-4 sm:p-6 bg-slate-50/50 rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Presensi Manajemen Kelas</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500 flex items-center flex-wrap gap-2">
                <span class="font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100/70">{{ $kelas->mataKuliah->nama_mk }}</span>
                <span class="text-slate-400">•</span>
                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md font-mono text-xs font-bold border border-slate-200">{{ $kelas->kode_kelas }}</span>
            </p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('dosen.kelas-detail', $kelas->id) }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm shadow-sm hover:shadow transition-all duration-300">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('dosen.absensi.create', $kelas->id) }}" 
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.01]">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Buat Sesi Baru</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="p-1 bg-emerald-100 text-emerald-700 rounded-md">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-bold text-emerald-900">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="p-1 bg-amber-100 text-amber-700 rounded-md">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-bold text-amber-900">{{ session('warning') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="p-1 bg-rose-100 text-rose-700 rounded-md">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-sm font-bold text-rose-900">{{ session('error') }}</p>
        </div>
    @endif

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200/70 shadow-sm p-4 flex items-center justify-between hover:border-blue-300 hover:scale-[1.02] transition-all duration-300">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Seluruh Sesi</p>
                <p class="text-2xl font-black text-slate-700 mt-0.5">{{ $statistics['total_sesi'] }}</p>
            </div>
            <div class="p-2.5 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 shadow-inner">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/70 shadow-sm p-4 flex items-center justify-between hover:border-amber-300 hover:scale-[1.02] transition-all duration-300">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sesi Konsep (Draft)</p>
                <p class="text-2xl font-black text-amber-600 mt-0.5">{{ $statistics['sesi_draft'] }}</p>
            </div>
            <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl border border-amber-100 shadow-inner">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/70 shadow-sm p-4 flex items-center justify-between hover:border-emerald-300 hover:scale-[1.02] transition-all duration-300">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sesi Aktif (Terbuka)</p>
                <p class="text-2xl font-black text-emerald-600 mt-0.5">{{ $statistics['sesi_buka'] }}</p>
            </div>
            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 shadow-inner">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200/70 shadow-sm p-4 flex items-center justify-between hover:border-rose-300 hover:scale-[1.02] transition-all duration-300">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Sesi Selesai (Tutup)</p>
                <p class="text-2xl font-black text-rose-600 mt-0.5">{{ $statistics['sesi_tutup'] }}</p>
            </div>
            <div class="p-2.5 bg-rose-50 text-rose-600 rounded-xl border border-rose-100 shadow-inner">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <span class="w-2 h-4 bg-blue-500 rounded-full"></span>
            <h2 class="text-base font-bold text-slate-800">Daftar Log Rekapitulasi Presensi</h2>
        </div>

        @if($absensiList->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80">
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-48">Pertemuan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-40">Tanggal</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-48">Alokasi Waktu</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-36">Status Akses</th>
                            <th class="px-6 py-3.5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-52">Rasio Kehadiran</th>
                            <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-500 uppercase tracking-wider min-w-[280px]">Panel Opsi Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($absensiList as $absensi)
                            <tr class="hover:bg-slate-50/80 transition duration-150 group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-blue-50/80 border border-blue-100 text-blue-700 font-bold text-xs group-hover:bg-blue-600 group-hover:text-white group-hover:border-transparent transition-all duration-300">
                                        Pertemuan {{ $absensi->pertemuan_ke }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-slate-700 font-semibold text-sm">
                                        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $absensi->tanggal->format('d M Y') }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600 font-medium text-sm">
                                    @if($absensi->jam_mulai && $absensi->jam_selesai)
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span>{{ substr($absensi->jam_mulai, 0, 5) }} - {{ substr($absensi->jam_selesai, 0, 5) }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-400 italic">—</span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span @class([
                                        'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold border shadow-sm',
                                        'bg-amber-50 text-amber-700 border-amber-200' => $absensi->isDraft(),
                                        'bg-emerald-50 text-emerald-700 border-emerald-200' => $absensi->isBuka(),
                                        'bg-rose-50 text-rose-700 border-rose-200' => $absensi->isTutup(),
                                    ])>
                                        @if($absensi->isDraft())
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        @elseif($absensi->isBuka())
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        @endif
                                        {{ $absensi->getStatusLabel() }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex -space-x-1.5">
                                            <div class="w-6 h-6 rounded-md bg-emerald-500 flex items-center justify-center text-white text-[10px] font-black border border-white shadow-sm" title="Hadir">
                                                {{ $absensi->hadir_count }}
                                            </div>
                                            <div class="w-6 h-6 rounded-md bg-slate-300 flex items-center justify-center text-slate-700 text-[10px] font-black border border-white shadow-sm" title="Belum Presensi/Absen">
                                                {{ max(0, $kelas->mahasiswa->count() - $absensi->hadir_count) }}
                                            </div>
                                        </div>
                                        <span class="text-slate-700 text-sm font-bold bg-slate-50 px-2 py-0.5 border border-slate-200 rounded-md">
                                            {{ $absensi->hadir_count }}<span class="text-slate-400 font-normal">/</span>{{ $kelas->mahasiswa->count() }} <span class="text-[11px] text-slate-400 font-medium">Mhs</span>
                                        </span>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}"
                                           class="inline-flex items-center gap-1 px-3 py-2 bg-white hover:bg-slate-50 text-blue-600 border border-slate-250/70 rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.02]">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Lihat</span>
                                        </a>
                                        
                                        @if($absensi->isDraft())
                                            <form action="{{ route('dosen.absensi.buka', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.02]">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                    </svg>
                                                    <span>Buka</span>
                                                </button>
                                            </form>
                                        @elseif($absensi->isBuka())
                                            <form action="{{ route('dosen.absensi.tutup', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-amber-50 hover:bg-amber-600 text-amber-800 hover:text-white border border-amber-200 rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.02]">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    <span>Tutup</span>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('dosen.absensi.edit', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}"
                                           class="inline-flex items-center gap-1 px-3 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-250/70 rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.02]">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>
                                        
                                        <form action="{{ route('dosen.absensi.destroy', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip sesi presensi beserta rekap riwayat pertemuan ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 bg-rose-50 hover:bg-rose-650 text-rose-600 hover:text-white border border-rose-100 rounded-xl text-xs font-bold shadow-sm transition-all hover:scale-[1.02]">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="flex items-center justify-center py-5 border-t border-slate-100 bg-slate-50/50">
                <div class="px-4">
                    {{ $absensiList->links() }}
                </div>
            </div>
        @else
            <div class="text-center py-16 px-4 bg-white rounded-b-xl">
                <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-2xl border border-blue-100 shadow-sm flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-700">Belum Ada Sesi Presensi Terjadwal</h3>
                <p class="text-sm text-slate-400 mt-1 max-w-sm mx-auto">Seluruh modul log absensi pertemuan mata kuliah ini masih bernilai kosong.</p>
                <div class="mt-5">
                    <a href="{{ route('dosen.absensi.create', $kelas->id) }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.01]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Buat Sesi Pertemuan Pertama</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection