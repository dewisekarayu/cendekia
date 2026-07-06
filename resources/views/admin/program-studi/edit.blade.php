@extends('layouts.portal')

@section('title', 'Edit Program Studi')
@section('activeMenu', 'Program Studi')

@section('content')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Edit Program Studi</h1>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-lg">
        <form action="{{ route('admin.program-studi.update', $prodi->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Program Studi</label>
                <input type="text" name="kode_prodi" value="{{ old('kode_prodi', $prodi->kode_prodi) }}" class="w-full border-gray-300 rounded-lg text-sm">
                @error('kode_prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Program Studi</label>
                <input type="text" name="nama_prodi" value="{{ old('nama_prodi', $prodi->nama_prodi) }}" class="w-full border-gray-300 rounded-lg text-sm">
                @error('nama_prodi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang</label>
                <select name="jenjang" class="w-full border-gray-300 rounded-lg text-sm">
                    @foreach (['D3', 'S1', 'S2', 'S3'] as $jenjang)
                        <option value="{{ $jenjang }}" {{ old('jenjang', $prodi->jenjang) == $jenjang ? 'selected' : '' }}>{{ $jenjang }}</option>
                    @endforeach
                </select>
                @error('jenjang') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white text-sm font-medium px-5 py-2 rounded-lg">Update</button>
                <a href="{{ route('admin.program-studi.index') }}" class="text-gray-500 text-sm font-medium px-5 py-2">Batal</a>
            </div>
        </form>
    </div>

@endsection