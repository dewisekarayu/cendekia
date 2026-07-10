@extends('layouts.portal')

@section('title', 'Buat Sesi Presensi Baru')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Buat Sesi Presensi Baru
            </h1>
            <p class="mt-1 text-gray-500">{{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span></p>
        </div>
        <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <p class="font-semibold text-red-900 mb-2">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside text-sm text-red-800 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900">Informasi Sesi</h2>
                </div>

                <form action="{{ route('dosen.absensi.store', $kelas->id) }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="pertemuan_ke" class="block text-sm font-semibold text-gray-700 mb-2">
                                Pertemuan Ke <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="pertemuan_ke" name="pertemuan_ke"
                                value="{{ old('pertemuan_ke', $nextPertemuan) }}"
                                min="1" max="16" required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('pertemuan_ke') border-red-500 @enderror">
                            @error('pertemuan_ke')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal"
                                value="{{ old('tanggal', today()->format('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('tanggal') border-red-500 @enderror">
                            @error('tanggal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="jam_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                                Jam Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="time" id="jam_mulai" name="jam_mulai"
                                value="{{ old('jam_mulai', $kelas->jam_mulai ?? '') }}"
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
                                value="{{ old('jam_selesai', $kelas->jam_selesai ?? '') }}"
                                required
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('jam_selesai') border-red-500 @enderror">
                            @error('jam_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="rangkuman" class="block text-sm font-semibold text-gray-700 mb-2">
                            Ringkasan Materi <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea id="rangkuman" name="rangkuman" rows="3"
                            placeholder="Tulis ringkasan materi yang akan diajarkan..."
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none">{{ old('rangkuman') }}</textarea>
                    </div>

                    <div>
                        <label for="berita_acara" class="block text-sm font-semibold text-gray-700 mb-2">
                            Berita Acara <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea id="berita_acara" name="berita_acara" rows="3"
                            placeholder="Catatan penting atau berita acara pertemuan..."
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none">{{ old('berita_acara') }}</textarea>
                    </div>

                    <div>
                        <label for="catatan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Catatan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea id="catatan" name="catatan" rows="2"
                            placeholder="Catatan atau keterangan lainnya..."
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="reset" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition">
                            Reset
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                            Buat Sesi Presensi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-100 p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                    </svg>
                    <div>
                        <h3 class="font-semibold text-blue-900 mb-2">Informasi Penting</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li><strong>Status Awal:</strong> Sesi dibuat dengan status <span class="inline-block bg-yellow-200 text-yellow-900 px-2 py-0.5 rounded text-xs font-semibold">Draft</span></li>
                            <li><strong>Langkah Berikutnya:</strong> Buka sesi di halaman detail agar mahasiswa dapat presensi</li>
                            <li><strong>Pertemuan:</strong> Nomor urut unik per kelas, tidak boleh sama</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m6 2a2 2 0 11-4 0m-6 0a2 2 0 11-4 0m10.5-1h.01m-6.02 0h.01M12 12h4.01M16 20H4a2 2 0 01-2-2V6a2 2 0 012-2h16a2 2 0 012 2v12a2 2 0 01-2 2z" />
                    </svg>
                    Detail Kelas
                </h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Mata Kuliah</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->mataKuliah->nama_mk }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Kode Kelas</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->kode_kelas }}</p>
                    </div>
                    <div class="pt-2 border-t border-gray-200">
                        <p class="text-gray-500 mb-1">Total Mahasiswa</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $kelas->mahasiswa()->count() }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Ruangan</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->ruangan ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
