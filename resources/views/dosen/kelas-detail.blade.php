@extends('layouts.portal')

@section('title', $kelas->mataKuliah->nama_mk ?? 'Detail Kelas')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <!-- Class Header Banner -->
    <div class="bg-blue-900 rounded-xl px-8 py-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                <span class="text-xs text-blue-200">Semester {{ $kelas->semester->nama_semester ?? '-' }}</span>
            </div>
            <h1 class="text-xl font-bold text-white">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h1>
            <p class="text-sm text-blue-200 mt-1">
                {{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }} • {{ $kelas->ruangan }}
            </p>
        </div>
        <a href="#" class="shrink-0 bg-white text-blue-900 text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-blue-50 transition text-center">
            Start Virtual Class
        </a>
    </div>

    <!-- Tabs -->
    @php
        $tabLinks = [
            'Beranda' => route('dosen.kelas-detail', $kelas->id),
            'Materi' => route('dosen.kelas-materi', $kelas->id),
            'Tugas & Proyek' => route('dosen.kelas-tugas', $kelas->id),
            'Forum Diskusi' => '#',
            'Peserta' => '#',
            'Penilaian' => '#',
        ];
        $activeTab = 'Beranda';
    @endphp
    <div class="flex items-center gap-6 border-b border-gray-200 mb-6 overflow-x-auto">
        @foreach ($tabLinks as $label => $url)
            <a href="{{ $url }}" class="pb-3 text-sm font-medium whitespace-nowrap transition
                {{ $label === $activeTab ? 'text-blue-900 border-b-2 border-blue-900' : 'text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Tab Content: Beranda -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <h2 class="text-base font-bold text-gray-800 mb-3">Deskripsi Mata Kuliah</h2>
                <p class="text-sm text-gray-600 leading-relaxed">
                    {{ $kelas->mataKuliah->deskripsi ?? 'Belum ada deskripsi untuk mata kuliah ini.' }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Capaian Pembelajaran</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex gap-2">
                            <span class="text-blue-900 mt-0.5">•</span>
                            Mampu menganalisis konsep dasar mata kuliah ini
                        </li>
                        <li class="flex gap-2">
                            <span class="text-blue-900 mt-0.5">•</span>
                            Mampu mengimplementasikan teori ke praktik
                        </li>
                    </ul>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-800 mb-3">Prasyarat</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex gap-2">
                            <span class="text-blue-900 mt-0.5">•</span>
                            Tidak ada prasyarat khusus
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Class Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Total Mahasiswa</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->mahasiswa->count() }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>SKS</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->mataKuliah->sks ?? 0 }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Program Studi</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->mataKuliah->programStudi->nama_prodi ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection