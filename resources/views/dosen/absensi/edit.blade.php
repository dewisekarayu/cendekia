@extends('layouts.portal')

@section('title', 'Edit Presensi - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Presensi
            </h1>
            <p class="mt-1 text-gray-500">
                {{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span> • Pertemuan {{ $absensi->pertemuan_ke }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('dosen.absensi.update', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Informasi Presensi</h2>
            </div>

            <div class="p-6 space-y-6">
                <!-- Pertemuan -->
                <div>
                    <label for="pertemuan_ke" class="block text-sm font-semibold text-gray-700 mb-2">
                        Pertemuan Ke <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="pertemuan_ke" name="pertemuan_ke" 
                        value="{{ old('pertemuan_ke', $absensi->pertemuan_ke) }}" 
                        min="1" max="16" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('pertemuan_ke') border-red-500 @enderror">
                    @error('pertemuan_ke')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal" name="tanggal" 
                        value="{{ old('tanggal', $absensi->tanggal->format('Y-m-d')) }}" 
                        required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('tanggal') border-red-500 @enderror">
                    @error('tanggal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Waktu -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jam Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="jam_mulai" name="jam_mulai" 
                            value="{{ old('jam_mulai', $absensi->jam_mulai) }}" 
                            required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('jam_mulai') border-red-500 @enderror">
                        @error('jam_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="jam_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jam Selesai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="jam_selesai" name="jam_selesai" 
                            value="{{ old('jam_selesai', $absensi->jam_selesai) }}" 
                            required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('jam_selesai') border-red-500 @enderror">
                        @error('jam_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status Sesi -->
                <div>
                    <label for="session_status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Status Sesi <span class="text-red-500">*</span>
                    </label>
                    <select id="session_status" name="session_status" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition appearance-none @error('session_status') border-red-500 @enderror">
                        <option value="draft" {{ old('session_status', $absensi->session_status) === 'draft' ? 'selected' : '' }}>Draft (Belum dibuka)</option>
                        <option value="buka" {{ old('session_status', $absensi->session_status) === 'buka' ? 'selected' : '' }}>Dibuka (Mahasiswa bisa presensi)</option>
                        <option value="tutup" {{ old('session_status', $absensi->session_status) === 'tutup' ? 'selected' : '' }}>Ditutup (Selesai)</option>
                    </select>
                    @error('session_status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rangkuman -->
                <div>
                    <label for="rangkuman" class="block text-sm font-semibold text-gray-700 mb-2">
                        Ringkasan Materi <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea id="rangkuman" name="rangkuman" rows="3"
                        placeholder="Tulis ringkasan materi..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none">{{ old('rangkuman', $absensi->rangkuman) }}</textarea>
                </div>

                <!-- Berita Acara -->
                <div>
                    <label for="berita_acara" class="block text-sm font-semibold text-gray-700 mb-2">
                        Berita Acara <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea id="berita_acara" name="berita_acara" rows="3"
                        placeholder="Catatan penting..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none">{{ old('berita_acara', $absensi->berita_acara) }}</textarea>
                </div>

                <!-- Catatan -->
                <div>
                    <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan Tambahan <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea id="catatan" name="catatan" rows="2"
                        placeholder="Catatan lainnya..."
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none">{{ old('catatan', $absensi->catatan) }}</textarea>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="reset" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition">
                        Reset
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Delete Section -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
        <h3 class="text-lg font-semibold text-red-900 mb-4">Zona Bahaya</h3>
        <p class="text-red-800 mb-4">Menghapus presensi akan menghapus semua data kehadiran yang tercatat. Tindakan ini tidak dapat dibatalkan.</p>
        <form action="{{ route('dosen.absensi.destroy', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus presensi ini beserta semua datanya?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                Hapus Presensi Ini
            </button>
        </form>
    </div>
</div>
@endsection