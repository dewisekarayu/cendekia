@extends('layouts.portal')

@section('title', 'Rekap Nilai Tugas')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <div class="bg-[#321270] dark:bg-gradient-to-r dark:from-indigo-950 dark:to-purple-900 rounded-xl px-8 py-6 mb-6 shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
        </div>
        <h1 class="text-xl font-bold text-white">Rekap Nilai Tugas — {{ $kelas->kode_kelas }}</h1>
        <p class="text-sm text-blue-200 dark:text-purple-250 mt-1">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</p>
    </div>

    <x-flash-message />

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('dosen.kelas-tugas', $kelas->id) }}" class="text-sm text-gray-550 dark:text-slate-400 hover:text-gray-800 dark:hover:text-white">&larr; Kembali ke Daftar Tugas</a>
        <p class="text-sm text-gray-500 dark:text-slate-400">{{ $tugasList->count() }} tugas &middot; {{ $mahasiswaList->count() }} mahasiswa</p>
    </div>

    @if ($tugasList->isEmpty())
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm p-10 text-center text-sm text-gray-400 dark:text-slate-500 transition-colors duration-200">
            Belum ada tugas yang dibuat di kelas ini.
        </div>
    @else
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[720px]">
                    <thead>
                        <tr class="text-left text-gray-400 dark:text-slate-500 text-xs border-b border-gray-100 dark:border-slate-700 bg-gray-50/70 dark:bg-slate-900/30">
                            <th class="px-5 py-3 font-medium sticky left-0 bg-gray-50/70 dark:bg-slate-900/90 backdrop-blur-sm z-10 min-w-[180px]">Mahasiswa</th>
                            @foreach ($tugasList as $t)
                                <th class="px-4 py-3 font-medium text-center min-w-[110px]">
                                    <a href="{{ route('dosen.tugas.submissions', [$kelas->id, $t->id]) }}" class="hover:text-[#321270] dark:hover:text-purple-400 hover:underline">
                                        {{ Str::limit($t->judul, 18) }}
                                    </a>
                                    <p class="text-[10px] text-gray-300 dark:text-slate-550 font-normal mt-0.5">Bobot {{ $t->bobot_nilai }}%</p>
                                </th>
                            @endforeach
                            <th class="px-4 py-3 font-medium text-center min-w-[90px] bg-purple-50/50 dark:bg-purple-950/40">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                        @forelse ($mahasiswaList as $mhs)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-900/20 transition duration-150">
                                <td class="px-5 py-3 sticky left-0 bg-white dark:bg-slate-800 z-10 transition-colors duration-200">
                                    <p class="font-bold text-gray-800 dark:text-white">{{ $mhs->name }}</p>
                                    <p class="text-xs text-gray-400 dark:text-slate-500">{{ $mhs->nim ?? $mhs->email }}</p>
                                </td>
                                @foreach ($tugasList as $t)
                                    @php
                                        $p = $matrix[$mhs->id][$t->id] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        @if (!$p || $p->status === \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL)
                                            <span class="text-[11px] text-gray-300 dark:text-slate-600 italic">Blm kumpul</span>
                                        @elseif ($p->status === \App\Models\PengumpulanTugas::STATUS_DINILAI)
                                            <span class="font-bold text-gray-850 dark:text-slate-200">{{ $p->nilai }}</span>
                                        @else
                                            <span class="text-[11px] text-amber-600 dark:text-amber-400 font-semibold">Menunggu</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center bg-purple-50/30 dark:bg-purple-950/20">
                                    @if ($rataRata[$mhs->id] !== null)
                                        <span class="font-black text-[#321270] dark:text-purple-300">{{ $rataRata[$mhs->id] }}</span>
                                    @else
                                        <span class="text-[11px] text-gray-300 dark:text-slate-650">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $tugasList->count() + 2 }}" class="text-center text-gray-400 dark:text-slate-500 py-10 text-sm">
                                    Belum ada mahasiswa di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-xs text-gray-400 dark:text-slate-500 mt-3">
            Klik nama tugas di header tabel untuk membuka halaman penilaian tugas tersebut. Kolom "Rata-rata" dihitung tertimbang berdasarkan bobot tiap tugas yang sudah dinilai.
        </p>
    @endif

@endsection