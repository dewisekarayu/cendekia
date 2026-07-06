@extends('layouts.portal')

@section('title', 'Tambah Program Studi')
@section('activeMenu', 'Program Studi')

@section('content')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Tambah Program Studi</h1>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-lg">
        <form action="{{ route('admin.program-studi.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Program Studi</label>
                <input type="text" name="kode_prodi" value="{{ old('kode_prodi') }}" class="w-full border-gray-300 rounded-lg text-sm" placeholder="TI">
                @error('kode_prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Program Studi</label>
                <input type="text" name="nama_prodi" value="{{ old('nama_prodi') }}" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Teknik Informatika">
                @error('nama_prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang</label>
                <select name="jenjang" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3</option>
                    <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1</option>
                    <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2</option>
                    <option value="S3" {{ old('jenjang') == 'S3' ? 'selected' : '' }}>S3</option>
                </select>
                @error('jenjang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white text-sm font-medium px-5 py-2 rounded-lg">Simpan</button>
                <a href="{{ route('admin.program-studi.index') }}" class="text-gray-500 text-sm font-medium px-5 py-2">Batal</a>
            </div>
        </form>
    </div>

@endsection