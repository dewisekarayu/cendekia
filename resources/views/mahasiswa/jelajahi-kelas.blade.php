@extends('layouts.portal')

@section('title', 'Jelajahi Kelas')
@section('activeMenu', 'Jelajahi Kelas')

@section('content')

    <div class="mb-6">
        <h1 class="text-xl font-bold text-gray-800">Jelajahi Kelas</h1>
        <p class="text-sm text-gray-500 mt-1">
            Kelas yang tersedia untuk program studi {{ auth()->user()->programStudi->nama_prodi ?? '-' }}, semester ini.
        </p>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($kelasList->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-10 text-center shadow-sm">
            <p class="text-gray-500 text-sm">Tidak ada kelas baru yang tersedia. Kamu mungkin sudah bergabung ke semua kelas di prodimu.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($kelasList as $kelas)
                <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                    <span class="inline-block text-[10px] font-semibold tracking-wide text-blue-900 bg-blue-50 px-2 py-0.5 rounded mb-2">
                        {{ $kelas->mataKuliah->kode_mk ?? '-' }}
                    </span>
                    <h3 class="font-semibold text-gray-800">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $kelas->dosen->name ?? '-' }} • {{ $kelas->mataKuliah->sks ?? 0 }} SKS</p>

                    <div class="mt-3 text-xs text-gray-500 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}
                    </div>

                    <form method="POST" action="{{ route('mahasiswa.kelas.join', $kelas->id) }}" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full bg-blue-900 hover:bg-blue-800 text-white text-sm font-medium py-2 rounded-lg transition">
                            Daftar Kelas Ini
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

@endsection