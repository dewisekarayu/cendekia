@extends('layouts.portal')

@section('title', 'Schedule')
@section('activeMenu', 'Schedule')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Jadwal Kuliah</h1>
        <p class="text-sm text-gray-500 mt-1">Jadwal dari semua kelas yang kamu ikuti minggu ini.</p>
    </div>

    @if ($kelasList->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-10 text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-500 text-sm">Belum ada jadwal. Gabung ke kelas dulu untuk melihat jadwalmu.</p>
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
            @foreach ($kelasList as $kelas)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $kelas->ruangan }}</p>
                    </div>
                    <span class="text-sm text-gray-500">{{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}</span>
                </div>
            @endforeach
        </div>
    @endif

@endsection