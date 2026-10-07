@extends('layouts.portal')

@section('title', 'Log Mengajar')
@section('activeMenu', 'Log Mengajar')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 py-4 sm:py-6" x-data="{ openId: null }">
    <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#321270]/10 text-[#321270]">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div><p class="text-xs font-bold uppercase tracking-wider text-[#321270]">Log Mengajar</p><h1 class="mt-1 text-xl font-extrabold text-slate-800 sm:text-2xl">Daftar Mata Pelajaran</h1><p class="mt-1 text-sm text-slate-500">Klik mata pelajaran untuk membuka rincian pertemuan di bawahnya.</p></div>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        @if($mataKuliahList->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] border-collapse">
                    <thead class="border-b border-slate-200 bg-slate-50"><tr>
                        <th class="w-20 px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">No</th>
                        <th class="px-5 py-3 text-left text-[11px] font-bold uppercase tracking-wider text-slate-500">Mata Pelajaran</th>
                        <th class="w-44 px-5 py-3 text-center text-[11px] font-bold uppercase tracking-wider text-slate-500">Jumlah Kelas</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($mataKuliahList as $mataKuliah)
                            <tr class="group cursor-pointer transition hover:bg-[#321270]/[0.03]" @click="openId = openId === {{ $mataKuliah->id }} ? null : {{ $mataKuliah->id }}" :aria-expanded="openId === {{ $mataKuliah->id }}">
                                <td class="px-5 py-4 text-center text-sm font-semibold text-slate-400">{{ $loop->iteration }}</td>
                                <td class="px-5 py-4"><div class="flex items-center gap-3"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100 text-slate-500 transition group-hover:bg-[#321270] group-hover:text-white"><svg class="h-4 w-4 transition" :class="openId === {{ $mataKuliah->id }} ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg></span><div><p class="font-bold text-slate-700 transition group-hover:text-[#321270]">{{ $mataKuliah->nama }}</p><p class="mt-0.5 text-xs font-medium text-slate-400">{{ $mataKuliah->kode }}</p></div></div></td>
                                <td class="px-5 py-4 text-center"><span class="inline-flex items-center rounded-full bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-700">{{ $mataKuliah->jumlah_kelas }} Kelas</span></td>
                            </tr>
                            <tr x-show="openId === {{ $mataKuliah->id }}" x-cloak x-transition>
                                <td colspan="3" class="bg-slate-50/70 p-4 sm:p-5">
                                    <div class="space-y-4 border-l-2 border-[#321270] pl-4 sm:pl-5">
                                        @foreach($mataKuliah->kelas as $kelas)
                                            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                                                <div class="flex items-center justify-between border-b border-slate-100 bg-indigo-50/60 px-4 py-3">
                                                    <div><p class="text-sm font-bold text-indigo-800">Kelas {{ $kelas->kode_kelas }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $kelas->mahasiswa->count() }} mahasiswa terdaftar</p></div>
                                                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-slate-600 ring-1 ring-slate-200">{{ $kelas->absensi->count() }} Pertemuan</span>
                                                </div>
                                                @if($kelas->absensi->isNotEmpty())
                                                    <div class="overflow-x-auto"><table class="w-full min-w-[850px] border-collapse">
                                                        <thead class="border-b border-slate-100 bg-slate-50"><tr>
                                                            <th class="w-14 px-4 py-3 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500">No</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Kelas</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Pertemuan</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Tanggal</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Alokasi Waktu</th><th class="px-4 py-3 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500">Rasio Kehadiran</th><th class="px-4 py-3 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500">Aksi</th>
                                                        </tr></thead>
                                                        <tbody class="divide-y divide-slate-100">
                                                            @foreach($kelas->absensi as $absensi)
                                                                @php $totalMahasiswa = $kelas->mahasiswa->count(); $persentaseHadir = $totalMahasiswa ? round(($absensi->hadir_count / $totalMahasiswa) * 100) : 0; @endphp
                                                                <tr class="transition hover:bg-slate-50"><td class="px-4 py-3 text-center text-xs font-semibold text-slate-400">{{ $loop->iteration }}</td><td class="px-4 py-3"><span class="rounded-md bg-indigo-50 px-2 py-1 text-xs font-bold text-indigo-700">{{ $kelas->kode_kelas }}</span></td><td class="px-4 py-3 text-sm font-bold text-slate-700">Pertemuan {{ $absensi->pertemuan_ke }}</td><td class="px-4 py-3 text-sm text-slate-600">{{ $absensi->tanggal?->translatedFormat('d M Y') ?? '-' }}</td><td class="px-4 py-3 text-sm text-slate-600">{{ $absensi->jam_mulai && $absensi->jam_selesai ? substr($absensi->jam_mulai, 0, 5).' - '.substr($absensi->jam_selesai, 0, 5) : '-' }}</td><td class="px-4 py-3"><div class="flex items-center gap-2"><div class="h-1.5 w-16 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-emerald-500" style="width: {{ $persentaseHadir }}%"></div></div><span class="text-xs font-bold text-slate-700">{{ $absensi->hadir_count }}/{{ $totalMahasiswa }}</span></div></td><td class="px-4 py-3 text-right"><a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" class="inline-flex items-center gap-1 rounded-lg border border-[#321270]/20 bg-[#321270]/5 px-2.5 py-1.5 text-xs font-bold text-[#321270] transition hover:bg-[#321270] hover:text-white"><svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>Lihat</a></td></tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table></div>
                                                @else
                                                    <p class="px-4 py-7 text-center text-sm text-slate-500">Belum ada sesi pertemuan untuk kelas ini.</p>
                                                @endif
                                            </section>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-16 text-center"><p class="font-bold text-slate-700">Belum ada mata pelajaran</p><p class="mt-1 text-sm text-slate-500">Anda belum tercatat sebagai pengampu pada kelas mana pun.</p></div>
        @endif
    </section>
</div>
@endsection
