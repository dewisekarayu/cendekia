@extends('layouts.portal')

@section('title', 'Tambah Mata Kuliah')
@section('activeMenu', 'Mata Kuliah')

@section('content')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Tambah Mata Kuliah</h1>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-xl">
        <form action="{{ route('admin.mata-kuliah.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                <select name="program_studi_id" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach ($prodiList as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
                @error('program_studi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Mata Kuliah</label>
                <input type="text" name="kode_mk" value="{{ old('kode_mk') }}" class="w-full border-gray-300 rounded-lg text-sm" placeholder="CS-201">
                @error('kode_mk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Kuliah</label>
                <input type="text" name="nama_mk" value="{{ old('nama_mk') }}" class="w-full border-gray-300 rounded-lg text-sm" placeholder="Struktur Data & Algoritma">
                @error('nama_mk') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKS</label>
                    <input type="number" name="sks" value="{{ old('sks', 3) }}" min="1" max="6" class="w-full border-gray-300 rounded-lg text-sm">
                    @error('sks') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester Ke</label>
                    <input type="number" name="semester_ke" value="{{ old('semester_ke', 1) }}" min="1" max="8" class="w-full border-gray-300 rounded-lg text-sm">
                    @error('semester_ke') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="3" class="w-full border-gray-300 rounded-lg text-sm">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white text-sm font-medium px-5 py-2 rounded-lg">Simpan</button>
                <a href="{{ route('admin.mata-kuliah.index') }}" class="text-gray-500 text-sm font-medium px-5 py-2">Batal</a>
            </div>
        </form>
    </div>

@endsection