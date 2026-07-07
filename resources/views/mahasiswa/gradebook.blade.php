@extends('layouts.portal')

@section('title', 'Gradebook')
@section('activeMenu', 'Gradebook')

@section('content')

    <div class="bg-blue-900 rounded-xl px-8 py-6 text-white mb-8">
        <h1 class="text-2xl font-bold">Gradebook</h1>
        <p class="text-blue-200 text-sm mt-1">Rekap nilai tugas dari semua kelas yang kamu ikuti.</p>
    </div>

    @if ($nilaiList->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-10 text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
            </svg>
            <p class="text-gray-500 text-sm">Belum ada nilai yang tersedia. Nilai akan muncul di sini setelah dosen menilai tugasmu.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">Mata Kuliah</th>
                        <th class="px-5 py-3 font-medium">Tugas</th>
                        <th class="px-5 py-3 font-medium text-right">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nilaiList as $item)
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="px-5 py-3 text-gray-700">{{ $item->tugas->kelasPerkuliahan->mataKuliah->nama_mk ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $item->tugas->judul ?? '-' }}</td>
                            <td class="px-5 py-3 text-right font-semibold text-blue-900">{{ $item->nilai }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection