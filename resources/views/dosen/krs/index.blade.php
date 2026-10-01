@extends('layouts.portal')

@section('title', 'Bimbingan KRS')

@section('content')
<div class="mb-6 rounded-2xl bg-[#321270] dark:bg-gradient-to-r dark:from-indigo-950 dark:to-purple-900 px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg">
    <div class="relative z-10">
        <p class="text-xs font-bold uppercase tracking-wide text-purple-200/70">Dosen Pembimbing Akademik</p>
        <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">Bimbingan KRS Mahasiswa</h1>
        <p class="mt-2 text-sm text-purple-100/70">Kelola dan setujui Kartu Rencana Studi (KRS) mahasiswa bimbingan Anda.</p>
    </div>
    <div class="absolute -right-6 -top-6 w-36 h-36 rounded-full bg-white/5 pointer-events-none"></div>
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

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden">
    <div class="p-5 border-b border-gray-100 dark:border-slate-700">
        <h3 class="font-bold text-slate-800 dark:text-white">Daftar Mahasiswa Bimbingan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/70 dark:bg-slate-900/30 text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">
                    <th class="px-5 py-3 text-left w-12">No</th>
                    <th class="px-5 py-3 text-left">Mahasiswa</th>
                    <th class="px-5 py-3 text-center">Semester</th>
                    <th class="px-5 py-3 text-center">Status KRS</th>
                    <th class="px-5 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                @forelse($mahasiswaBimbingan as $mhs)
                    @php
                        $krs = $mhs->krs->first();
                        $statusClass = 'bg-gray-100 text-gray-600';
                        $statusLabel = 'Belum Mengisi';
                        
                        if ($krs) {
                            if ($krs->status == 'diajukan') {
                                $statusClass = 'bg-blue-100 text-blue-700';
                                $statusLabel = 'Menunggu Persetujuan';
                            } elseif ($krs->status == 'disetujui') {
                                $statusClass = 'bg-emerald-100 text-emerald-700';
                                $statusLabel = 'Disetujui';
                            } elseif ($krs->status == 'ditolak') {
                                $statusClass = 'bg-rose-100 text-rose-700';
                                $statusLabel = 'Ditolak/Revisi';
                            } elseif ($krs->status == 'draft') {
                                $statusClass = 'bg-slate-100 text-slate-700';
                                $statusLabel = 'Draft';
                            }
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                        <td class="px-5 py-4 text-xs font-bold text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $mhs->name }}</div>
                            <div class="text-[11px] text-gray-500">{{ $mhs->nip_nim }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="text-xs font-semibold text-slate-600 dark:text-slate-300">
                                {{ $krs ? $krs->semester : '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            @if($krs)
                                <a href="{{ route('dosen.krs.show', $krs->id) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    Lihat KRS
                                </a>
                            @else
                                <span class="text-[11px] text-gray-400 italic">Belum ada KRS</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                            Anda belum memiliki mahasiswa bimbingan akademik.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
