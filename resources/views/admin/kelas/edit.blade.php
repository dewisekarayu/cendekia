@extends('layouts.portal')

@section('title', 'Edit Kelas')
@section('activeMenu', 'Kelas Perkuliahan')

@section('content')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Edit Kelas</h1>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Kuliah</label>
                <select name="mata_kuliah_id" class="w-full border-gray-300 rounded-lg text-sm">
                    @foreach ($mataKuliahList as $mk)
                        <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $kelas->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }} ({{ $mk->programStudi?->nama_prodi }})
                        </option>
                    @endforeach
                </select>
                @error('mata_kuliah_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dosen Pengampu</label>
                <select name="dosen_id" class="w-full border-gray-300 rounded-lg text-sm">
                    @foreach ($dosenList as $dosen)
                        <option value="{{ $dosen->id }}" {{ old('dosen_id', $kelas->dosen_id) == $dosen->id ? 'selected' : '' }}>
                            {{ $dosen->name }}
                        </option>
                    @endforeach
                </select>
                @error('dosen_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
                <select name="semester_id" class="w-full border-gray-300 rounded-lg text-sm">
                    @foreach ($semesterList as $semester)
                        <option value="{{ $semester->id }}" {{ old('semester_id', $kelas->semester_id) == $semester->id ? 'selected' : '' }}>
                            {{ $semester->nama_semester }}
                        </option>
                    @endforeach
                </select>
                @error('semester_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Kelas</label>
                <input type="text" name="kode_kelas" value="{{ old('kode_kelas', $kelas->kode_kelas) }}" class="w-full border-gray-300 rounded-lg text-sm">
                @error('kode_kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                    <select name="hari" class="w-full border-gray-300 rounded-lg text-sm">
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari', $kelas->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                    @error('hari') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                    <input type="time" name="jam_mulai" value="{{ old('jam_mulai', substr($kelas->jam_mulai, 0, 5)) }}" class="w-full border-gray-300 rounded-lg text-sm">
                    @error('jam_mulai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                    <input type="time" name="jam_selesai" value="{{ old('jam_selesai', substr($kelas->jam_selesai, 0, 5)) }}" class="w-full border-gray-300 rounded-lg text-sm">
                    @error('jam_selesai') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ruangan</label>
                <input type="text" name="ruangan" value="{{ old('ruangan', $kelas->ruangan) }}" class="w-full border-gray-300 rounded-lg text-sm">
                @error('ruangan') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $kelas->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-900">
                <label for="is_active" class="text-sm text-gray-700">Kelas aktif</label>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white text-sm font-medium px-5 py-2 rounded-lg">Update</button>
                <a href="{{ route('admin.kelas.index') }}" class="text-gray-500 text-sm font-medium px-5 py-2">Batal</a>
            </div>
        </form>
    </div>

@endsection