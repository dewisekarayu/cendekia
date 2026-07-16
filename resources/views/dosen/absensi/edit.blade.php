@extends('layouts.portal')

@section('title', 'Edit Presensi - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/60">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-xl border border-blue-100 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <span>Edit Konfigurasi Presensi</span>
            </h1>
            <p class="mt-2 text-sm text-slate-500 flex items-center flex-wrap gap-2">
                <span class="font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100/70">{{ $kelas->mataKuliah->nama_mk }}</span>
                <span class="text-slate-400">•</span>
                <span class="font-medium text-slate-600">Kelas {{ $kelas->kode_kelas }}</span>
                <span class="text-slate-400">•</span>
                <span class="bg-slate-100 text-slate-700 px-2.5 py-1 rounded-md font-medium">Pertemuan {{ $absensi->pertemuan_ke }}</span>
            </p>
        </div>
        <div class="flex items-center">
            <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm shadow-sm hover:shadow transition-all duration-300">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    <form action="{{ route('dosen.absensi.update', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden transition-all duration-300 hover:shadow-md">
            <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                <span class="w-2 h-4 bg-blue-500 rounded-full"></span>
                <h2 class="text-base font-bold text-slate-800">Detail Informasi Parameter Presensi</h2>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="pertemuan_ke" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <span>Pertemuan Ke</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" id="pertemuan_ke" name="pertemuan_ke" 
                            value="{{ old('pertemuan_ke', $absensi->pertemuan_ke) }}" 
                            min="1" max="16" required
                            class="w-full px-3.5 py-2.5 text-sm text-slate-700 rounded-xl border @error('pertemuan_ke') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-blue-500 @enderror focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 shadow-sm">
                        @error('pertemuan_ke')
                            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <span>Tanggal Pelaksanaan</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="tanggal" name="tanggal" 
                            value="{{ old('tanggal', $absensi->tanggal->format('Y-m-d')) }}" 
                            required
                            class="w-full px-3.5 py-2.5 text-sm text-slate-700 rounded-xl border @error('tanggal') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-blue-500 @enderror focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 shadow-sm">
                        @error('tanggal')
                            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="jam_mulai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <span>Jam Mulai Kuliah</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" id="jam_mulai" name="jam_mulai" 
                            value="{{ old('jam_mulai', $absensi->jam_mulai) }}" 
                            required
                            class="w-full px-3.5 py-2.5 text-sm text-slate-700 rounded-xl border @error('jam_mulai') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-blue-500 @enderror focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 shadow-sm">
                        @error('jam_mulai')
                            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jam_selesai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1">
                            <span>Jam Selesai Kuliah</span>
                            <span class="text-rose-500">*</span>
                        </label>
                        <input type="time" id="jam_selesai" name="jam_selesai" 
                            value="{{ old('jam_selesai', $absensi->jam_selesai) }}" 
                            required
                            class="w-full px-3.5 py-2.5 text-sm text-slate-700 rounded-xl border @error('jam_selesai') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-blue-500 @enderror focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 shadow-sm">
                        @error('jam_selesai')
                            <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="session_status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1">
                        <span>Status Akses Sesi Absensi</span>
                        <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <select id="session_status" name="session_status" required
                            class="w-full pl-3.5 pr-10 py-2.5 text-sm font-semibold text-slate-700 bg-slate-50 focus:bg-white rounded-xl border @error('session_status') border-rose-300 focus:border-rose-500 @else border-slate-200 focus:border-blue-500 @enderror focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 appearance-none shadow-sm cursor-pointer">
                            <option value="draft" class="text-amber-600 font-semibold" {{ old('session_status', $absensi->session_status) === 'draft' ? 'selected' : '' }}>◴ Draft (Sesi Belum Diaktifkan / Mahasiswa Dikunci)</option>
                            <option value="buka" class="text-emerald-600 font-semibold" {{ old('session_status', $absensi->session_status) === 'buka' ? 'selected' : '' }}>✓ Dibuka (Sesi Aktif / Mahasiswa Dapat Melakukan Presensi)</option>
                            <option value="tutup" class="text-rose-600 font-semibold" {{ old('session_status', $absensi->session_status) === 'tutup' ? 'selected' : '' }}>✗ Ditutup (Sesi Selesai / Sistem Presensi Dikunci Semuanya)</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3.5 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @error('session_status')
                        <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 my-2"></div>

                <div>
                    <label for="rangkuman" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Ringkasan Pokok Bahasan materi <span class="text-slate-400 font-medium lowercase">(opsional)</span>
                    </label>
                    <textarea id="rangkuman" name="rangkuman" rows="3"
                        placeholder="Tulis ringkasan pokok materi perkuliahan..."
                        class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 hover:bg-white focus:bg-white rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 shadow-sm resize-none">{{ old('rangkuman', $absensi->rangkuman) }}</textarea>
                </div>

                <div>
                    <label for="berita_acara" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Berita Acara Jalannya Kelas <span class="text-slate-400 font-medium lowercase">(opsional)</span>
                    </label>
                    <textarea id="berita_acara" name="berita_acara" rows="3"
                        placeholder="Tulis hambatan, penyimpangan, atau kejadian khusus perkuliahan..."
                        class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 hover:bg-white focus:bg-white rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 shadow-sm resize-none">{{ old('berita_acara', $absensi->berita_acara) }}</textarea>
                </div>

                <div>
                    <label for="catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Catatan Internal Tambahan Dosen <span class="text-slate-400 font-medium lowercase">(opsional)</span>
                    </label>
                    <textarea id="catatan" name="catatan" rows="2"
                        placeholder="Catatan pendelegasian tugas tambahan mahasiswa atau lainnya..."
                        class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 hover:bg-white focus:bg-white rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 shadow-sm resize-none">{{ old('catatan', $absensi->catatan) }}</textarea>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-slate-100">
                    <button type="reset" 
                            class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm shadow-sm transition-all duration-300 hover:scale-[1.01]">
                        Reset Form
                    </button>
                    <button type="submit" 
                            class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.01]">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-xl border border-rose-150 p-6 shadow-sm transition duration-300 hover:border-rose-300">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-2 h-4 bg-rose-500 rounded-full"></span>
            <h3 class="text-base font-extrabold text-rose-900">Zona Bahaya (Danger Zone)</h3>
        </div>
        <p class="text-sm text-slate-500 mb-4 leading-relaxed">
            Menghapus data sesi presensi pertemuan ini akan melenyapkan <span class="font-bold text-rose-600">seluruh rekapitulasi kehadiran mahasiswa</span> yang telah terarsip masuk ke database secara permanen. Tindakan sistem ini tidak dapat dikembalikan.
        </p>
        <form action="{{ route('dosen.absensi.destroy', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" 
              method="POST" 
              onsubmit="return confirm('Apakah Anda benar-benar yakin ingin menghapus data presensi pertemuan ini beserta seluruh riwayat rekap mahasiswa di dalamnya?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 font-bold text-sm rounded-xl shadow-sm transition-all duration-300 hover:scale-[1.01] outline-none">
                Hapus Permanen Sesi Presensi
            </button>
        </form>
    </div>
</div>
@endsection