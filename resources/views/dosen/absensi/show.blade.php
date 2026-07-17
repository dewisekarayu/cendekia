@extends('layouts.portal')

@section('title', 'Detail Sesi Presensi - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-4 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-200/60">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">
                <a href="{{ route('dosen.kelas-saya') }}" class="hover:text-[#321270] transition duration-200">Kelas Saya</a>
                <svg class="w-3 h-3 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="hover:text-[#321270] transition duration-200">Presensi</a>
                <svg class="w-3 h-3 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-slate-800 font-bold">Pertemuan {{ $absensi->pertemuan_ke }}</span>
            </div>

            <h1 class="text-lg sm:text-xl font-bold text-slate-800 flex items-center gap-2.5 mt-1.5">
                <div class="w-9 h-9 rounded-lg bg-[#321270]/5 text-[#321270] border border-[#321270]/10 shadow-sm flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <span>Detail Ringkasan Sesi Presensi</span>
            </h1>
        </div>
        <div class="hidden sm:block shrink-0">
            <a href="{{ route('dosen.absensi.index', $kelas->id) }}"
            class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-[#321270] hover:bg-[#3d1690] text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-slate-200/80 p-5 shadow-sm">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <div class="space-y-1">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Nomor Pertemuan</p>
                <p class="text-lg font-black text-slate-700">Pertemuan {{ $absensi->pertemuan_ke }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Kegiatan</p>
                <p class="text-lg font-black text-slate-700">{{ $absensi->tanggal->format('d M Y') }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Alokasi Waktu Sesi</p>
                <p class="text-sm font-bold text-slate-700 bg-slate-50 border border-slate-200 px-2 py-1 rounded-lg inline-block font-mono">
                    {{ substr($absensi->jam_mulai, 0, 5) }} - {{ substr($absensi->jam_selesai, 0, 5) }}
                </p>
            </div>
            <div class="space-y-1">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Status Akses Sesi</p>
                <div>
                    <span @class([
                        'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border shadow-sm mt-0.5',
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
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg border border-slate-200/70 shadow-sm p-3 flex items-center justify-between hover:border-emerald-300 transition-all duration-300">
            <div class="space-y-0.5">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Hadir (Present)</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-xl font-black text-slate-700">{{ $stats['hadir'] }}</p>
                    <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">
                        {{ $stats['total'] > 0 ? round(($stats['hadir']/$stats['total'])*100) : 0 }}%
                    </span>
                </div>
            </div>
            <span class="w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-emerald-100 flex-shrink-0"></span>
        </div>

        <div class="bg-white rounded-lg border border-slate-200/70 shadow-sm p-3 flex items-center justify-between hover:border-sky-300 transition-all duration-300">
            <div class="space-y-0.5">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Izin (Permitted)</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-xl font-black text-slate-700">{{ $stats['izin'] }}</p>
                    <span class="text-[11px] font-bold text-sky-600 bg-sky-50 px-1.5 py-0.5 rounded border border-sky-100">
                        {{ $stats['total'] > 0 ? round(($stats['izin']/$stats['total'])*100) : 0 }}%
                    </span>
                </div>
            </div>
            <span class="w-3 h-3 rounded-full bg-sky-500 ring-4 ring-sky-100 flex-shrink-0"></span>
        </div>

        <div class="bg-white rounded-lg border border-slate-200/70 shadow-sm p-3 flex items-center justify-between hover:border-amber-300 transition-all duration-300">
            <div class="space-y-0.5">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Sakit (Sick Leave)</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-xl font-black text-slate-700">{{ $stats['sakit'] }}</p>
                    <span class="text-[11px] font-bold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-100">
                        {{ $stats['total'] > 0 ? round(($stats['sakit']/$stats['total'])*100) : 0 }}%
                    </span>
                </div>
            </div>
            <span class="w-3 h-3 rounded-full bg-amber-500 ring-4 ring-amber-100 flex-shrink-0"></span>
        </div>

        <div class="bg-white rounded-lg border border-slate-200/70 shadow-sm p-3 flex items-center justify-between hover:border-rose-300 transition-all duration-300">
            <div class="space-y-0.5">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Alpha (Absent)</p>
                <div class="flex items-baseline gap-2">
                    <p class="text-xl font-black text-slate-700">{{ $stats['alpha'] }}</p>
                    <span class="text-[11px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">
                        {{ $stats['total'] > 0 ? round(($stats['alpha']/$stats['total'])*100) : 0 }}%
                    </span>
                </div>
            </div>
            <span class="w-3 h-3 rounded-full bg-rose-500 ring-4 ring-rose-100 flex-shrink-0"></span>
        </div>
    </div>

    @if(session('success'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-emerald-50 border border-emerald-200 rounded-lg p-3 flex items-start gap-2.5 shadow-sm">
            <div class="p-1 bg-emerald-100 text-emerald-700 rounded-md flex-shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-xs font-bold text-emerald-900">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="animate-in slide-in-from-top-2 duration-300 bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-start gap-2.5 shadow-sm">
            <div class="p-1 bg-amber-100 text-amber-700 rounded-md flex-shrink-0">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            </div>
            <p class="text-xs font-bold text-amber-900">{{ session('warning') }}</p>
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-start gap-2 p-3.5 bg-white rounded-lg border border-slate-200/80 shadow-sm">
        @if($absensi->isDraft())
            <form action="{{ route('dosen.absensi.buka', [$kelas->id, $absensi->id]) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white border border-emerald-200 rounded-lg text-[11px] font-bold shadow-sm transition-all">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <span>Buka Akses Sesi</span>
                </button>
            </form>
        @endif

        @if($absensi->isBuka())
            <form action="{{ route('dosen.absensi.tutup', [$kelas->id, $absensi->id]) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 hover:bg-amber-600 text-amber-800 hover:text-white border border-amber-200 rounded-lg text-[11px] font-bold shadow-sm transition-all"
                        onclick="return confirm('Apakah Anda yakin ingin menutup akses gerbang presensi mandiri mahasiswa pada sesi ini?')">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>Tutup Akses Sesi</span>
                </button>
            </form>
        @endif

        <a href="{{ route('dosen.absensi.edit', [$kelas->id, $absensi->id]) }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-250/70 rounded-lg text-[11px] font-bold shadow-sm transition-all">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span>Edit Sesi</span>
        </a>

        <a href="{{ route('dosen.absensi.attendance', [$kelas->id, $absensi->id]) }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#321270]/5 hover:bg-[#321270] text-[#321270] hover:text-white border border-[#321270]/20 rounded-lg text-[11px] font-bold shadow-sm transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m7 4v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9" />
            </svg>
            <span>Koreksi Manual</span>
        </a>

        <div class="relative w-full sm:w-56">
            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
            <input type="text" id="cariMahasiswa" placeholder="Cari nama / NIM..."
                   class="w-full pl-9 pr-3 py-2 text-xs text-slate-700 rounded-lg border border-slate-200 bg-slate-50 focus:bg-white focus:border-[#321270] focus:ring-2 focus:ring-[#321270]/20 outline-none transition duration-200">
        </div>

        <div class="flex-1 text-right">
            <form action="{{ route('dosen.absensi.destroy', [$kelas->id, $absensi->id]) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 hover:bg-rose-650 text-rose-600 hover:text-white border border-rose-100 rounded-lg text-[11px] font-bold shadow-sm transition-all"
                        onclick="return confirm('Apakah Anda yakin ingin menghapus arsip sesi presensi beserta rekap riwayat pertemuan ini? Data tidak dapat dikembalikan.')">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span>Hapus Sesi</span>
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-50 to-[#321270]/5 px-4 py-2.5 border-b border-slate-100 flex items-center gap-2">
            <span class="w-1.5 h-3 bg-[#321270] rounded-full"></span>
            <h2 class="text-sm font-bold text-slate-800">Daftar Rekap Absensi Mahasiswa</h2>
            <span id="jumlahHasil" class="ml-auto text-[11px] font-semibold text-slate-400"></span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200/80">
                        <th class="px-4 py-2.5 text-center text-[11px] font-bold text-slate-500 uppercase tracking-wider w-14">No</th>
                        <th class="px-4 py-2.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-36">NIM</th>
                        <th class="px-4 py-2.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider min-w-[200px]">Nama Lengkap</th>
                        <th class="px-4 py-2.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-32">Status</th>
                        <th class="px-4 py-2.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider w-28">Waktu Log</th>
                        <th class="px-4 py-2.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider min-w-[220px]">Keterangan Catatan</th>
                    </tr>
                </thead>
                <tbody id="tabelMahasiswaBody" class="divide-y divide-slate-100 bg-white">
                    @forelse($kelas->mahasiswa as $index => $mahasiswa)
                        @php
                            $attendance = $hadirMap[$mahasiswa->id] ?? null;
                            $statusColor = match($attendance?->status ?? 'alpha') {
                                'hadir' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'izin' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'sakit' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'alpha' => 'bg-rose-50 text-rose-700 border-rose-200',
                            };
                            $statusLabel = match($attendance?->status ?? 'alpha') {
                                'hadir' => 'Hadir',
                                'izin' => 'Izin',
                                'sakit' => 'Sakit',
                                'alpha' => 'Alpha',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition duration-150 group" data-search="{{ strtolower($mahasiswa->name . ' ' . ($mahasiswa->nim ?? '')) }}">
                            <td class="px-4 py-2.5 text-center text-xs font-bold text-slate-400 group-hover:text-slate-700 transition">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-xs font-semibold text-slate-500">
                                {{ $mahasiswa->nim ?? '-' }}
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-[#321270]/5 border border-[#321270]/10 flex items-center justify-center text-[11px] font-bold text-[#321270] group-hover:bg-[#321270] group-hover:text-white transition-all duration-300">
                                        {{ strtoupper(substr($mahasiswa->name, 0, 2)) }}
                                    </div>
                                    <div class="font-bold text-slate-700 group-hover:text-[#321270] transition text-sm">
                                        {{ $mahasiswa->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold border shadow-sm {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 whitespace-nowrap text-xs font-medium text-slate-600 font-mono">
                                {{ $attendance?->waktu_absensi?->format('H:i') ?? '—' }}
                            </td>
                            <td class="px-4 py-2.5 text-xs text-slate-500 italic">
                                {{ $attendance?->keterangan ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-400 font-medium">
                                <div class="w-11 h-11 bg-slate-50 text-slate-400 rounded-lg border border-slate-200/60 flex items-center justify-center mx-auto mb-2.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <span class="text-xs">Tidak ada data mahasiswa terdaftar di dalam kelas ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <p id="tidakDitemukan" class="hidden px-6 py-10 text-center text-slate-400 text-xs font-medium">
                Tidak ada mahasiswa yang cocok dengan pencarian.
            </p>
        </div>
    </div>
</div>

<script>
    (function () {
        const input = document.getElementById('cariMahasiswa');
        const rows = Array.from(document.querySelectorAll('#tabelMahasiswaBody tr[data-search]'));
        const emptyMsg = document.getElementById('tidakDitemukan');
        const counter = document.getElementById('jumlahHasil');
        if (!input || rows.length === 0) return;

        input.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            let visible = 0;
            rows.forEach(function (row) {
                const match = row.dataset.search.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            emptyMsg.classList.toggle('hidden', visible !== 0 || q === '');
            counter.textContent = q === '' ? '' : visible + ' dari ' + rows.length + ' mahasiswa';
        });
    })();
</script>
@endsection