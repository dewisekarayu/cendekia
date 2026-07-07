@extends('layouts.portal')

@section('title', 'Tambah Pengguna')
@section('activeMenu', 'Dosen & Mahasiswa')

@section('content')

    <h1 class="text-xl font-bold text-gray-800 mb-6">Tambah Pengguna Baru</h1>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 max-w-xl">
        <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" id="role-select" class="w-full border-gray-300 rounded-lg text-sm">
                    @foreach (['admin', 'dosen', 'mahasiswa'] as $r)
                        <option value="{{ $r }}" {{ old('role', $role) == $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                    @endforeach
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">NIP / NIM</label>
                <input type="text" name="nip_nim" value="{{ old('nip_nim') }}" class="w-full border-gray-300 rounded-lg text-sm" placeholder="20240000123">
                @error('nip_nim') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-lg text-sm">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-lg text-sm">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border-gray-300 rounded-lg text-sm">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div id="prodi-field">
                <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi (khusus Mahasiswa)</label>
                <select name="program_studi_id" class="w-full border-gray-300 rounded-lg text-sm">
                    <option value="">-- Tidak ada --</option>
                    @foreach ($prodiList as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
                @error('program_studi_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white text-sm font-medium px-5 py-2 rounded-lg">Simpan</button>
                <a href="{{ route('admin.user.index') }}" class="text-gray-500 text-sm font-medium px-5 py-2">Batal</a>
            </div>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role-select');
        const prodiField = document.getElementById('prodi-field');

        function toggleProdiField() {
            prodiField.style.display = roleSelect.value === 'mahasiswa' ? 'block' : 'none';
        }

        roleSelect.addEventListener('change', toggleProdiField);
        toggleProdiField();
    </script>

@endsection