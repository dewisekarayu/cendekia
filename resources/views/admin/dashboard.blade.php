@extends('layouts.portal')

@section('title', 'Dashboard Admin')
@section('activeMenu', 'Dashboard')

@section('content')
    <div class="mb-4">
        <h1 class="text-xl font-bold text-gray-800">Dashboard Admin</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="text-sm text-gray-500">Total Mahasiswa</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalMahasiswa ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="text-sm text-gray-500">Total Dosen</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalDosen ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="text-sm text-gray-500">Total Mata Kuliah</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalMataKuliah ?? 0 }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <div class="text-sm text-gray-500">Total Program Studi</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalProgramStudi ?? 0 }}</div>
        </div>
    </div>
@endsection

