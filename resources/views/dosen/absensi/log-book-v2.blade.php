@extends('layouts.portal')

@section('title', 'Log Mengajar')
@section('activeMenu', 'Log Mengajar')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 py-4 sm:py-6">
    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-2 text-[#321270]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <span class="text-xs font-bold uppercase tracking-wider">Log Mengajar</span>
                </div>
                <h1 class="mt-2 text-xl font-extrabold text-slate-800 sm:text-2xl">{{ $mataKuliahTerpilih ? $mataKuliahTerpilih->nama : 'Daftar Mata Pelajaran' }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $mataKuliahTerpilih ? 'Rincian pertemuan pada seluruh kelas yang Anda ampu.' : 'Pilih mata pelajaran untuk melihat rincian pertemuan dan kehadiran.' }}</p>
            </div>
            @if($mataKuliahTerpilih)
                <a href="{{ route('dosen.log-book') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-slate-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>Kembali
                </a>
            @endif
        </div>
    </section>

    @if($mataKuliahTerpilih)
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50 px-5 py-4 sm:px-6">
                <span class="rounded-md bg-[#321270]/10 px-2.5 py-1 text-xs font-bold text-[#321270]">{{ $mataKuliahTerpilih->kode }}</span>
                <span class="ml-2 text-sm font-bold text-slate-700">Rincian Log Mengajar</span>
            </div>
            @if($absensiList->count())
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[880px] border-collapse">
                        <thead class="border-b border-slate-200 bg-slate-50"><tr>
                            <th class="w-16 px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Kelas</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Pertemuan</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Tanggal</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Alokasi Waktu</th>
                            <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Rasio Kehadiran</th>
                            <th class="px-5 py-3 text-right text-[11px] font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                        </tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($absensiList as $absensi)
                                @php
                                    $totalMahasiswa = $absensi->kelasPerkuliahan->mahasiswa->count();
                                    $persentaseHadir = $totalMahasiswa ? round(($absensi->hadir_count / $totalMahasiswa) * 100) : 0;
                                @endphp
                                <tr class="transition hover:bg-slate-50/80">
                                    <td class="px-5 py-4 text-center text-sm font-semibold text-slate-400">{{ ($absensiList->currentPage() - 1) * $absensiList->perPage() + $loop->iteration }}</td>
                                    <td class="px-5 py-4"><span class="rounded-lg bg-indigo-50 px-2.5 py-1.5 text-xs font-bold text-indigo-700">{{ $absensi->kelasPerkuliahan->kode_kelas }}</span></td>
                                    <td class="px-5 py-4 text-sm font-bold text-slate-700">Pertemuan {{ $absensi->pertemuan_ke }}</td>
                                    <td class="px-5 py-4 text-sm font-medium text-slate-600">{{ $absensi->tanggal?->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td class="px-5 py-4 text-sm font-medium text-slate-600">{{ $absensi->jam_mulai && $absensi->jam_selesai ? substr($absensi->jam_mulai, 0, 5).' - '.substr($absensi->jam_selesai, 0, 5) : '-' }}</td>
                                    <td class="px-5 py-4"><div class="flex items-center gap-2.5"><div class="h-2 w-20 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-500" style="width: {{ $persentaseHadir }}%"></div></div><span class="text-sm font-bold text-slate-700">{{ $absensi->hadir_count }}/{{ $totalMahasiswa }}</span></div></td>
                                    <td class="px-5 py-4 text-right"><a href="{{ route('dosen.absensi.show', ['kelasId' => $absensi->kelas_perkuliahan_id, 'absensiId' => $absensi->id]) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-[#321270]/20 bg-[#321270]/5 px-3 py-2 text-xs font-bold text-[#321270] transition hover:bg-[#321270] hover:text-white"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>Lihat</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="border-t border-slate-100 px-5 py-4 sm:px-6">{{ $absensiList->links() }}</div>
            @else
                <div class="px-6 py-16 text-center text-sm text-slate-500">Belum ada sesi pertemuan untuk mata pelajaran ini.</div>
            @endif
        </section>
    @else
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            @if($mataKuliahList->isNotEmpty())
                <div class="overflow-x-auto"><table class="w-full min-w-[580px] border-collapse">
                    <thead class="border-b border-slate-200 bg-slate-50"><tr><th class="w-20 px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th><th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Mata Pelajaran</th><th class="w-40 px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">Jumlah Kelas</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($mataKuliahList as $mataKuliah)
                            <tr class="group transition hover:bg-[#321270]/[0.03]">
                                <td class="px-5 py-4 text-center text-sm font-semibold text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4"><a href="{{ route('dosen.log-book', ['mata_kuliah_id' => $mataKuliah->id]) }}" class="block"><p class="font-bold text-slate-700 transition group-hover:text-[#321270]">{{ $mataKuliah->nama }}</p><p class="mt-0.5 text-xs font-medium text-slate-400">{{ $mataKuliah->kode }}</p></a></td>
                                <td class="px-5 py-4 text-center"><a href="{{ route('dosen.log-book', ['mata_kuliah_id' => $mataKuliah->id]) }}" class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700 transition hover:bg-indigo-100">{{ $mataKuliah->jumlah_kelas }} Kelas<svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table></div>
            @else
                <div class="px-6 py-16 text-center"><p class="font-bold text-slate-700">Belum ada mata pelajaran</p><p class="mt-1 text-sm text-slate-500">Anda belum tercatat sebagai pengampu pada kelas mana pun.</p></div>
            @endif
        </section>
    @endif
</div>
@endsection
