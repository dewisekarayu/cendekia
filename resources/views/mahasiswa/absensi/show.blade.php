@extends('layouts.portal')

@section('title', 'Riwayat Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4">

    {{-- ===== HEADER SECTION ===== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-sm text-blue-500 mb-2 flex-wrap">
                <a href="{{ route('mahasiswa.absensi.index') }}" class="hover:text-blue-700 transition">Presensi</a>
                <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}" class="hover:text-blue-700 transition truncate max-w-[160px] sm:max-w-none">{{ $kelas->mataKuliah->nama_mk }}</a>
                <svg class="w-4 h-4 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-blue-900 font-semibold">Riwayat</span>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm shadow-blue-200">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-blue-950 tracking-tight">Riwayat Presensi</h1>
            </div>

            <p class="mt-2 text-blue-700/80 flex items-center gap-2 flex-wrap text-sm">
                <span class="inline-block px-2.5 py-0.5 rounded-lg bg-blue-100 text-blue-800 font-bold font-mono">{{ $kelas->kode_kelas }}</span>
                <span class="font-medium text-blue-900">{{ $kelas->mataKuliah->nama_mk }}</span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-500">{{ $kelas->dosen?->name ?? '-' }}</span>
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white hover:bg-blue-50 text-blue-700 border border-blue-200 rounded-xl font-bold text-sm transition shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                <span>Kembali</span>
            </a>
            <a href="{{ route('mahasiswa.kelas-detail', $kelas->id) }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm transition shadow-md shadow-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span>Detail Kelas</span>
            </a>
        </div>
    </div>

    {{-- ===== RINGKASAN STATISTIK ===== --}}
    @php
        $persentase = $stats['totalPertemuan'] > 0
            ? round($stats['hadir'] / $stats['totalPertemuan'] * 100)
            : 0;
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-sm">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Total Sesi</p>
            <p class="text-2xl font-black text-slate-800 font-mono">{{ $stats['totalPertemuan'] }}</p>
        </div>
        <div class="bg-blue-50/60 rounded-2xl border border-blue-100 p-4 shadow-sm">
            <p class="text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-1">Hadir</p>
            <p class="text-2xl font-black text-blue-800 font-mono">{{ $stats['hadir'] }}</p>
        </div>
        <div class="bg-sky-50/60 rounded-2xl border border-sky-100 p-4 shadow-sm">
            <p class="text-[11px] font-bold text-sky-600 uppercase tracking-wider mb-1">Izin</p>
            <p class="text-2xl font-black text-sky-800 font-mono">{{ $stats['izin'] }}</p>
        </div>
        <div class="bg-amber-50/60 rounded-2xl border border-amber-100 p-4 shadow-sm">
            <p class="text-[11px] font-bold text-amber-500 uppercase tracking-wider mb-1">Sakit</p>
            <p class="text-2xl font-black text-amber-800 font-mono">{{ $stats['sakit'] }}</p>
        </div>
        <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4 shadow-sm col-span-2 sm:col-span-1">
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">Alpha</p>
            <p class="text-2xl font-black text-slate-700 font-mono">{{ $stats['alpha'] }}</p>
        </div>
    </div>

    {{-- ===== PROGRESS PERSENTASE KEHADIRAN ===== --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-bold text-slate-700">Persentase Kehadiran</span>
            <span class="text-sm font-black text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-lg border border-blue-100">{{ $persentase }}%</span>
        </div>
        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden shadow-inner">
            <div class="bg-gradient-to-r from-blue-500 to-sky-500 h-full rounded-full transition-all duration-500"
                 style="width: {{ $persentase }}%"></div>
        </div>
        @if($persentase < 75)
            <p class="mt-2 text-xs font-semibold text-amber-600 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 4.5c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                Kehadiran Anda di bawah 75%, perhatikan syarat minimum kehadiran ujian.
            </p>
        @endif
    </div>

    {{-- ===== LIST RIWAYAT PERTEMUAN (PAGINATED) ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-slate-50 px-5 py-3.5 border-b border-slate-200 flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
            <h3 class="text-xs font-black text-slate-700 uppercase tracking-wider">Semua Riwayat Pertemuan</h3>
        </div>

        @if($absensiList->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($absensiList as $absensi)
                    @php
                        $attendance = $absensi->absensiMahasiswa->first();
                        $rowStatus = $attendance?->status ?? 'alpha';

                        $rowStyle = match($rowStatus) {
                            'hadir' => ['icon' => 'bg-blue-50 text-blue-600', 'badge' => 'bg-blue-100 text-blue-700', 'path' => 'M5 13l4 4L19 7'],
                            'izin'  => ['icon' => 'bg-sky-50 text-sky-600', 'badge' => 'bg-sky-100 text-sky-700', 'path' => 'M9 12h6m2 7H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V17a2 2 0 01-2 2z'],
                            'sakit' => ['icon' => 'bg-amber-50 text-amber-600', 'badge' => 'bg-amber-100 text-amber-700', 'path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            default => ['icon' => 'bg-slate-100 text-slate-500', 'badge' => 'bg-slate-200 text-slate-700', 'path' => 'M6 18L18 6M6 6l12 12'],
                        };
                        $rowLabel = $attendance?->getStatusLabel() ?? 'Alpha';
                    @endphp
                    <div class="flex items-center justify-between gap-3 px-5 py-4 hover:bg-slate-50/70 transition">
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-9 h-9 rounded-xl {{ $rowStyle['icon'] }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="{{ $rowStyle['path'] }}"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800">Pertemuan {{ $absensi->pertemuan_ke }}</p>
                                <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $absensi->tanggal->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-shrink-0">
                            @if($attendance?->waktu_absensi)
                                <span class="hidden sm:flex items-center gap-1 text-xs text-slate-400 font-mono">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $attendance->waktu_absensi->format('H:i') }}
                                </span>
                            @endif
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-black tracking-wide uppercase {{ $rowStyle['badge'] }}">
                                {{ $rowLabel }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-5 py-4 border-t border-slate-100">
                {{ $absensiList->links() }}
            </div>
        @else
            <div class="text-center py-12 px-4">
                <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl border border-slate-200 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Riwayat Presensi</h3>
                <p class="text-xs text-slate-400 mt-1">Sesi presensi untuk kelas ini belum pernah dibuka.</p>
            </div>
        @endif
    </div>

</div>
@endsection