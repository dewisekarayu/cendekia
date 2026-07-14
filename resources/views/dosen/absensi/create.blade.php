@extends('layouts.portal')

@section('title', 'Buat Sesi Presensi Baru')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200/60">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                <a href="{{ route('dosen.kelas-saya') }}" class="hover:text-blue-600 transition duration-200">Kelas Saya</a>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="hover:text-blue-600 transition duration-200">Presensi</a>
                <svg class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-slate-800 font-bold truncate">Buat Sesi Baru</span>
            </div>
            
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-800 flex items-center gap-3 mt-2">
                <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 shadow-sm flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m7 4v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9" />
                    </svg>
                </div>
                <span class="truncate">Buat Sesi Presensi Baru</span>
            </h1>
            
            <p class="mt-2 text-sm text-slate-500 flex items-center flex-wrap gap-2">
                <span class="font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100/70">{{ $kelas->kode_kelas }}</span>
                <span class="text-slate-400">•</span>
                <span class="font-medium text-slate-600">{{ $kelas->mataKuliah->nama_mk }}</span>
            </p>
        </div>
        
        <div class="flex items-center">
            <a href="{{ route('dosen.absensi.index', $kelas->id) }}" 
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl font-semibold text-sm shadow-sm hover:shadow transition-all duration-300">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="animate-in slide-in-from-top-2 duration-300 bg-rose-50 border border-rose-200 rounded-xl p-4 flex items-start gap-3 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-bold text-rose-900 text-sm">Terjadi Kesalahan Validasi Data!</p>
                <ul class="text-xs text-rose-700 mt-1.5 space-y-1 list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="bg-gradient-to-r from-slate-50 to-blue-50/30 px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Informasi Sesi Presensi</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Isi seluruh parameter detail sesi perkuliahan baru</p>
                    </div>
                </div>

                <form action="{{ route('dosen.absensi.store', $kelas->id) }}" method="POST" class="p-6 space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="pertemuan_ke" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <span>Pertemuan Ke</span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" id="pertemuan_ke" name="pertemuan_ke"
                                value="{{ old('pertemuan_ke', $nextPertemuan) }}"
                                min="1" max="99" required
                                class="w-full px-3.5 py-2.5 text-sm text-slate-700 rounded-xl border @error('pertemuan_ke') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-blue-500 @enderror focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 shadow-sm">
                            @error('pertemuan_ke')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <span>Tanggal Pelaksanaan</span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="tanggal" name="tanggal"
                                value="{{ old('tanggal', today()->format('Y-m-d')) }}"
                                required
                                class="w-full px-3.5 py-2.5 text-sm text-slate-700 rounded-xl border @error('tanggal') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-blue-500 @enderror focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 shadow-sm">
                            @error('tanggal')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="jam_mulai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <span>Jam Mulai Kuliah</span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <input type="time" id="jam_mulai" name="jam_mulai"
                                value="{{ old('jam_mulai', $kelas->jam_mulai ?? '') }}"
                                required
                                class="w-full px-3.5 py-2.5 text-sm text-slate-700 rounded-xl border @error('jam_mulai') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-blue-500 @enderror focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 shadow-sm">
                            @error('jam_mulai')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="jam_selesai" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                <span>Jam Selesai Kuliah</span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <input type="time" id="jam_selesai" name="jam_selesai"
                                value="{{ old('jam_selesai', $kelas->jam_selesai ?? '') }}"
                                required
                                class="w-full px-3.5 py-2.5 text-sm text-slate-700 rounded-xl border @error('jam_selesai') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-blue-500 @enderror focus:bg-white focus:ring-2 focus:ring-blue-500/20 outline-none transition duration-200 shadow-sm">
                            @error('jam_selesai')
                                <p class="mt-1 text-xs text-rose-600 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-slate-100 my-2"></div>

                    <div>
                        <label for="rangkuman" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Ringkasan Pokok Bahasan materi <span class="text-slate-400 font-medium lowercase">(opsional)</span>
                        </label>
                        <textarea id="rangkuman" name="rangkuman" rows="3"
                            placeholder="Tulis poin-poin materi utama yang akan dipaparkan..."
                            class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 hover:bg-white focus:bg-white rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 shadow-sm resize-none">{{ old('rangkuman') }}</textarea>
                    </div>

                    <div>
                        <label for="berita_acara" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Berita Acara Perkuliahan <span class="text-slate-400 font-medium lowercase">(opsional)</span>
                        </label>
                        <textarea id="berita_acara" name="berita_acara" rows="3"
                            placeholder="Tulis pokok kejadian, hambatan kelas, atau catatan berita acara..."
                            class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 hover:bg-white focus:bg-white rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 shadow-sm resize-none">{{ old('berita_acara') }}</textarea>
                    </div>

                    <div>
                        <label for="catatan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Catatan Tambahan Khusus <span class="text-slate-400 font-medium lowercase">(opsional)</span>
                        </label>
                        <textarea id="catatan" name="catatan" rows="2"
                            placeholder="Catatan pendelegasian tugas mandiri mahasiswa atau lainnya..."
                            class="w-full px-4 py-3 text-sm text-slate-700 bg-slate-50 hover:bg-white focus:bg-white rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition duration-200 shadow-sm resize-none">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="reset" 
                                class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-bold text-sm shadow-sm transition-all duration-300 hover:scale-[1.01]">
                            Reset Form
                        </button>
                        <button type="submit" 
                                class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all duration-300 hover:scale-[1.01]">
                            ✓ Buat Sesi Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-slate-200/80 p-5 shadow-sm hover:shadow-md transition duration-300">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 border border-sky-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm mb-2.5">Informasi Alur Presensi</h3>
                        <ul class="text-xs text-slate-600 space-y-2.5">
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 font-bold">•</span>
                                <span><strong>Status Awal Sesi:</strong> Sesi presensi yang baru dibuat akan berstatus <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 rounded text-[10px] font-bold">Draft</span>.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 font-bold">•</span>
                                <span><strong>Aktivasi QR / Manual:</strong> Buka tautan halaman detail absensi untuk mengaktifkan sesi ke mahasiswa.</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 font-bold">•</span>
                                <span><strong>Validasi Unik:</strong> Setiap nomor pertemuan dilarang bernilai ganda dalam satu kelas perkuliahan.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200/80 p-5">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                    <div class="p-1 text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-800 text-sm">Spesifikasi Detail Kelas</h3>
                </div>
                
                <div class="space-y-3.5">
                    <div>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Nama Mata Kuliah</p>
                        <p class="text-sm font-bold text-slate-700 mt-0.5">{{ $kelas->mataKuliah->nama_mk }}</p>
                    </div>
                    <div>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Kode Akses Kelas</p>
                        <p class="text-xs font-bold font-mono bg-slate-50 border border-slate-200 px-2 py-1 rounded-md text-slate-700 inline-block mt-1">{{ $kelas->kode_kelas }}</p>
                    </div>
                    <div class="pt-3 border-t border-slate-100">
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider mb-1">Total Mahasiswa Kelas</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-extrabold text-blue-600">{{ $kelas->mahasiswa->count() }}</p>
                            <p class="text-xs font-semibold text-slate-500">Orang Terdaftar</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Lokasi Ruangan</p>
                        <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $kelas->ruangan ?? '— (Online/Hybrid)' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-slate-50 to-blue-50/20 rounded-xl border border-slate-200/60 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 3.062v6.050a3.066 3.066 0 01-1.254 2.449l-5.848 4.991a3.066 3.066 0 01-4.009 0l-5.848-4.991a3.066 3.066 0 01-1.254-2.449v-6.05a3.066 3.066 0 012.812-3.062zM9 16a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-800 text-sm mb-1">Tips Efisiensi</h4>
                        <ul class="text-xs text-slate-600 space-y-1 list-inside list-disc">
                            <li>Sesuaikan jam dengan jadwal SIAKAD.</li>
                            <li>Tulis ringkasan agar materi tertata rapi.</li>
                            <li>Format otomatis mengikuti standar 24 jam.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection