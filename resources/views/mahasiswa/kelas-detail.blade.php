@extends('layouts.portal')

@section('title', $kelas->mataKuliah->nama_mk ?? 'Detail Kelas')
@section('activeMenu', 'My Courses')

@section('content')

    <div class="bg-blue-900 rounded-xl px-8 py-6 mb-6">
        <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah?->kode_mk ?? '-' }}</span>
        <h1 class="text-xl font-bold text-white mt-2">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</h1>
        <p class="text-sm text-blue-200 mt-1">{{ $kelas->dosen?->name ?? '-' }} • {{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
        <p class="text-sm text-gray-600">{{ $kelas->mataKuliah?->deskripsi ?? 'Belum ada deskripsi untuk mata kuliah ini.' }}</p>
    </div>

@endsection