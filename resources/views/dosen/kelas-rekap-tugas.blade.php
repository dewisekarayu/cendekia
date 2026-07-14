@extends('layouts.portal')

@section('title', 'Rekap Nilai Tugas')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <div class="bg-[#321270] rounded-xl px-8 py-6 mb-6">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
        </div>
        <h1 class="text-xl font-bold text-white">Rekap Nilai Tugas — {{ $kelas->kode_kelas }}</h1>
        <p class="text-sm text-blue-200 mt-1">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</p>
    </div>

    <x-flash-message />

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('dosen.kelas-tugas', $kelas->id) }}" class="text-sm text-gray-500 hover:text-gray-800">&larr; Kembali ke Daftar Tugas</a>
        <p class="text-sm text-gray-500">{{ $tugasList->count() }} tugas &middot; {{ $mahasiswaList->count() }} mahasiswa</p>
    </div>

    @if ($tugasList->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-10 text-center text-sm text-gray-400">
            Belum ada tugas yang dibuat di kelas ini.
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[720px]">
                    <thead>
                        <tr class="text-left text-gray-400 text-xs border-b border-gray-100 bg-gray-50/70">
                            <th class="px-5 py-3 font-medium sticky left-0 bg-gray-50/70 z-10 min-w-[180px]">Mahasiswa</th>
                            @foreach ($tugasList as $t)
                                <th class="px-4 py-3 font-medium text-center min-w-[110px]">
                                    <a href="{{ route('dosen.tugas.submissions', [$kelas->id, $t->id]) }}" class="hover:text-[#321270] hover:underline">
                                        {{ Str::limit($t->judul, 18) }}
                                    </a>
                                    <p class="text-[10px] text-gray-300 font-normal mt-0.5">Bobot {{ $t->bobot_nilai }}%</p>
                                </th>
                            @endforeach
                            <th class="px-4 py-3 font-medium text-center min-w-[90px] bg-purple-50/50">Rata-rata</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswaList as $mhs)
                            <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50">
                                <td class="px-5 py-3 sticky left-0 bg-white z-10">
                                    <p class="font-medium text-gray-800">{{ $mhs->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $mhs->nim ?? $mhs->email }}</p>
                                </td>
                                @foreach ($tugasList as $t)
                                    @php
                                        $p = $matrix[$mhs->id][$t->id] ?? null;
                                    @endphp
                                    <td class="px-4 py-3 text-center">
                                        @if (!$p || $p->status === \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL)
                                            <span class="text-[11px] text-gray-300 italic">Blm kumpul</span>
                                        @elseif ($p->status === \App\Models\PengumpulanTugas::STATUS_DINILAI)
                                            <span class="font-semibold text-gray-800">{{ $p->nilai }}</span>
                                        @else
                                            <span class="text-[11px] text-amber-600 font-medium">Menunggu</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-center bg-purple-50/30">
                                    @if ($rataRata[$mhs->id] !== null)
                                        <span class="font-bold text-[#321270]">{{ $rataRata[$mhs->id] }}</span>
                                    @else
                                        <span class="text-[11px] text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $tugasList->count() + 2 }}" class="text-center text-gray-400 py-10 text-sm">
                                    Belum ada mahasiswa di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-xs text-gray-400 mt-3">
            Klik nama tugas di header tabel untuk membuka halaman penilaian tugas tersebut. Kolom "Rata-rata" dihitung tertimbang berdasarkan bobot tiap tugas yang sudah dinilai.
        </p>
    @endif

@endsection