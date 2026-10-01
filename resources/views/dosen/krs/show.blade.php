@extends('layouts.portal')

@section('title', 'Detail KRS - ' . $mahasiswa->name)

@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('dosen.krs.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[#321270] transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Daftar Bimbingan
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 dark:text-white">Daftar Mata Kuliah Diambil</h3>
                <span class="text-xs font-bold px-2.5 py-1 rounded-md bg-gray-100 text-gray-600">Semester {{ $krs->semester }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/70 dark:bg-slate-900/30 text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">
                            <th class="px-5 py-3 text-left w-12">No</th>
                            <th class="px-5 py-3 text-left">Kode & Mata Kuliah</th>
                            <th class="px-5 py-3 text-center">SKS</th>
                            <th class="px-5 py-3 text-center">Semester MK</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                        @php $totalSks = 0; @endphp
                        @forelse($krsItems as $item)
                            @php $totalSks += $item->sks; @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                <td class="px-5 py-3.5 text-xs font-bold text-gray-400">{{ $loop->iteration }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $item->nama_mk }}</div>
                                    <div class="text-[11px] text-gray-500">{{ $item->kode_kelas }}</div>
                                </td>
                                <td class="px-5 py-3.5 text-center font-bold text-slate-700">{{ $item->sks }}</td>
                                <td class="px-5 py-3.5 text-center text-gray-500 text-xs">{{ $item->semester }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                    Belum ada mata kuliah yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-slate-900/50 font-bold border-t border-gray-200">
                        <tr>
                            <td colspan="2" class="px-5 py-3 text-right text-gray-600">Total SKS</td>
                            <td class="px-5 py-3 text-center text-slate-800 text-lg">{{ $totalSks }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="font-bold text-sm text-slate-800 mb-4">Informasi Mahasiswa</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-[#321270] flex items-center justify-center text-white text-lg font-bold">
                    {{ strtoupper(substr($mahasiswa->name, 0, 1)) }}
                </div>
                <div>
                    <h4 class="font-bold text-slate-800 text-sm">{{ $mahasiswa->name }}</h4>
                    <p class="text-xs text-gray-500">{{ $mahasiswa->nip_nim }}</p>
                </div>
            </div>
            
            <div class="pt-4 border-t border-gray-100">
                <h3 class="font-bold text-sm text-slate-800 mb-3">Status Persetujuan</h3>
                @if($krs->status == 'diajukan')
                    <div class="mb-4 p-3 rounded-lg bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold">
                        Menunggu Persetujuan Anda
                    </div>
                    <form action="{{ route('dosen.krs.approve', $krs->id) }}" method="POST" class="mb-2">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Catatan Pembimbing (Opsional)</label>
                            <textarea name="catatan" rows="2" class="w-full text-xs rounded-lg border border-gray-200 p-2 focus:ring-[#321270]/20"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition-colors">
                            Setujui KRS
                        </button>
                    </form>
                    <form action="{{ route('dosen.krs.reject', $krs->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-xs font-bold transition-colors">
                            Tolak / Minta Revisi
                        </button>
                    </form>
                @elseif($krs->status == 'disetujui')
                    <div class="mb-4 p-3 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-semibold">
                        KRS telah disetujui
                    </div>
                    @if($krs->catatan_pembimbing)
                        <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-600 italic">
                            "{{ $krs->catatan_pembimbing }}"
                        </div>
                    @endif
                @else
                    <div class="mb-4 p-3 rounded-lg bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold">
                        Status: {{ ucfirst($krs->status) }}
                    </div>
                    @if($krs->catatan_pembimbing)
                        <div class="p-3 bg-gray-50 rounded-lg text-xs text-gray-600 italic">
                            "{{ $krs->catatan_pembimbing }}"
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
