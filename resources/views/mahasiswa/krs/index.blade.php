@extends('layouts.portal')

@section('title', 'Kartu Rencana Studi (KRS)')

@section('content')
<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-blue-200/70">Akademik</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">Kartu Rencana Studi</h1>
            <p class="mt-2 text-sm text-blue-100/70">Pilih mata kuliah yang akan diambil pada semester ini dan ajukan ke Dosen PA.</p>
        </div>
        
        <div class="flex items-center gap-2">
            @php
                $statusClass = 'bg-gray-100 text-gray-700';
                $statusLabel = 'Draft';
                if ($krs->status == 'diajukan') {
                    $statusClass = 'bg-blue-100 text-blue-700';
                    $statusLabel = 'Menunggu Persetujuan';
                } elseif ($krs->status == 'disetujui') {
                    $statusClass = 'bg-emerald-100 text-emerald-700';
                    $statusLabel = 'Disetujui Dosen PA';
                } elseif ($krs->status == 'ditolak') {
                    $statusClass = 'bg-rose-100 text-rose-700';
                    $statusLabel = 'Perlu Revisi / Ditolak';
                }
            @endphp
            <div class="px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm {{ $statusClass }}">
                Status: {{ $statusLabel }}
            </div>
        </div>
    </div>
    <div class="absolute -right-6 -top-6 w-36 h-36 rounded-full bg-white/10 pointer-events-none"></div>
</div>

@if(session('success'))
    <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm font-semibold">
        {{ session('error') }}
    </div>
@endif

@if($krs->status == 'ditolak' && $krs->catatan_pembimbing)
    <div class="mb-4 p-4 rounded-xl bg-rose-50 border border-rose-200">
        <h4 class="text-sm font-bold text-rose-800 mb-1">Catatan dari Dosen PA:</h4>
        <p class="text-sm text-rose-700 italic">"{{ $krs->catatan_pembimbing }}"</p>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- BAGIAN KIRI: Mata Kuliah Terpilih --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white">Mata Kuliah Diambil</h3>
                <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-gray-100 text-gray-600">Total SKS: {{ $totalSks }}</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/70 dark:bg-slate-900/30 text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">
                            <th class="px-5 py-3 text-left w-12">No</th>
                            <th class="px-5 py-3 text-left">Kode & Mata Kuliah</th>
                            <th class="px-5 py-3 text-center">SKS</th>
                            @if($krs->status == 'draft' || $krs->status == 'ditolak')
                                <th class="px-5 py-3 text-right">Aksi</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                        @forelse($krsItems as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                <td class="px-5 py-3.5 text-xs font-bold text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $item->nama_mk }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $item->kode_kelas }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-center font-bold text-slate-700">{{ $item->sks }}</td>
                                @if($krs->status == 'draft' || $krs->status == 'ditolak')
                                    <td class="px-5 py-3.5 text-right">
                                        <form action="{{ route('mahasiswa.krs.hapus', ['id' => $krs->id, 'kelas_id' => $item->kelas_perkuliahan_id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus dari KRS">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ ($krs->status == 'draft' || $krs->status == 'ditolak') ? '4' : '3' }}" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                    Belum ada mata kuliah yang Anda pilih. Silakan pilih dari daftar di samping.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(($krs->status == 'draft' || $krs->status == 'ditolak') && count($krsItems) > 0)
                <div class="p-5 border-t border-gray-100 bg-gray-50">
                    <form action="{{ route('mahasiswa.krs.ajukan', $krs->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-sm font-bold shadow-sm transition-colors" onclick="return confirm('Apakah Anda yakin ingin mengajukan KRS? Setelah diajukan, Anda tidak dapat mengubahnya hingga diperiksa Dosen PA.')">
                            Ajukan KRS ke Dosen PA
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- BAGIAN KANAN: Pilihan Mata Kuliah --}}
    <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="font-bold text-sm text-slate-800 mb-4">Kelas Tersedia</h3>
            
            @if($krs->status == 'draft' || $krs->status == 'ditolak')
                <div class="space-y-3">
                    @forelse($tersedia as $kelas)
                        <div class="p-3 border border-gray-100 rounded-xl hover:border-blue-200 hover:bg-blue-50/30 transition-colors">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-xs">{{ $kelas->mataKuliah->nama_mk }}</h4>
                                    <p class="text-[10px] text-gray-500">{{ $kelas->kode_kelas }} &middot; SKS: {{ $kelas->mataKuliah->sks }}</p>
                                </div>
                            </div>
                            <form action="{{ route('mahasiswa.krs.tambah', $krs->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
                                <button type="submit" class="w-full py-1.5 bg-white border border-gray-200 hover:border-blue-300 hover:text-blue-600 rounded-lg text-xs font-bold text-gray-700 transition-colors shadow-sm">
                                    + Ambil Kelas
                                </button>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 text-center py-4">Semua kelas yang tersedia sudah Anda ambil.</p>
                    @endforelse
                </div>
            @else
                <div class="text-center py-6">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-600 mb-1">KRS Terkunci</p>
                    <p class="text-xs text-gray-500">Anda tidak dapat menambah atau menghapus mata kuliah saat KRS sedang diajukan atau telah disetujui.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
