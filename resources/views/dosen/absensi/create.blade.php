@extends('layouts.portal')

@section('title', 'Buat Sesi Presensi Baru')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

<style>
    .font-display { font-family: 'Fraunces', serif; font-optical-sizing: auto; }
    .font-mono-tix { font-family: 'JetBrains Mono', monospace; }
    .rail-dot { box-shadow: 0 0 0 4px #FBF9F6; }
    .rail-line { background-image: repeating-linear-gradient(to bottom, #321270 0, #321270 4px, transparent 4px, transparent 9px); }
</style>

<div class="max-w-7xl mx-auto" style="font-family: 'Inter', sans-serif;">

    {{-- HEADER: STAMP + TITLE --}}
    <div class="flex flex-row items-center gap-3 sm:gap-5 pb-5 sm:pb-6 mb-5 sm:mb-6 border-b-2 border-dashed border-slate-200">
        <div class="shrink-0 relative">
            <div class="w-14 h-14 sm:w-[72px] sm:h-[72px] rounded-full border-2 border-[#321270] flex flex-col items-center justify-center rotate-[-4deg] bg-[#321270]/[0.03]">
                <span class="text-[6px] sm:text-[8px] font-bold text-[#321270]/70 uppercase tracking-wider -mb-0.5">Sesi ke</span>
                <span class="font-display text-lg sm:text-2xl font-semibold text-[#321270] leading-none">{{ $nextPertemuan }}</span>
            </div>
        </div>
        <div class="min-w-0 flex-1">
            <h1 class="font-display text-lg sm:text-[30px] font-semibold text-[#1E1B2E] leading-tight truncate">
                Buat Sesi Presensi Baru
            </h1>
            <p class="mt-1 sm:mt-1.5 text-xs sm:text-sm text-slate-500 flex items-center flex-wrap gap-1.5 sm:gap-2">
                <span class="font-mono-tix font-bold text-[#321270] bg-[#321270]/[0.06] px-1.5 sm:px-2 py-0.5 rounded border border-[#321270]/10 text-[10px] sm:text-xs">{{ $kelas->kode_kelas }}</span>
                <span class="italic font-display text-slate-400 text-xs sm:text-sm truncate">{{ $kelas->mataKuliah->nama_mk }}</span>
            </p>
            <a href="{{ route('dosen.absensi.index', $kelas->id) }}"
               class="sm:hidden inline-flex items-center gap-1 mt-1.5 text-[#321270] font-bold text-[11px] uppercase tracking-wider">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.6" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
        <div class="hidden sm:block shrink-0">
            <a href="{{ route('dosen.absensi.index', $kelas->id) }}"
            class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 bg-[#321270] hover:bg-[#3d1690] text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-all duration-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.4" d="M15 19l-7-7 7-7" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 rounded-r-lg p-4 flex items-start gap-3 shadow-sm">
            <svg class="w-4.5 h-4.5 text-rose-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
            <div class="flex-1 min-w-0">
                <p class="font-display font-semibold text-rose-900 text-sm">Beberapa bagian formulir perlu diperbaiki</p>
                <ul class="text-xs text-rose-700 mt-1.5 space-y-0.5 list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[1fr_300px] gap-6">

        {{-- FORM WITH STEP RAIL --}}
        <form action="{{ route('dosen.absensi.store', $kelas->id) }}" method="POST">
            @csrf

            {{-- STEP 1: JADWAL --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center shrink-0">
                    <div class="w-8 h-8 rounded-full bg-[#321270] text-white font-display font-semibold text-sm flex items-center justify-center rail-dot">1</div>
                    <div class="w-px flex-1 rail-line mt-1 mb-1"></div>
                </div>
                <div class="flex-1 min-w-0 pb-6">
                    <h2 class="font-display text-lg font-semibold text-[#1E1B2E] mb-0.5">Jadwal Pertemuan</h2>
                    <p class="text-xs text-slate-500 mb-4">Nomor urut, tanggal, dan rentang waktu perkuliahan</p>

                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="pertemuan_ke" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                    Pertemuan Ke <span class="text-rose-500">*</span>
                                </label>
                                <input type="number" id="pertemuan_ke" name="pertemuan_ke"
                                    value="{{ old('pertemuan_ke', $nextPertemuan) }}"
                                    min="1" max="99" required
                                    class="w-full px-3.5 py-2.5 text-sm font-mono-tix font-bold text-[#1E1B2E] rounded-xl border-2 @error('pertemuan_ke') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-[#321270] @enderror focus:bg-white outline-none transition duration-200">
                                @error('pertemuan_ke')
                                    <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                    Tanggal Pelaksanaan <span class="text-rose-500">*</span>
                                </label>
                                <input type="date" id="tanggal" name="tanggal"
                                    value="{{ old('tanggal', today()->format('Y-m-d')) }}"
                                    required
                                    class="w-full px-3.5 py-2.5 text-sm text-[#1E1B2E] rounded-xl border-2 @error('tanggal') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-[#321270] @enderror focus:bg-white outline-none transition duration-200">
                                @error('tanggal')
                                    <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="jam_mulai" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                    Jam Mulai Kuliah <span class="text-rose-500">*</span>
                                </label>
                                <input type="time" id="jam_mulai" name="jam_mulai"
                                    value="{{ old('jam_mulai', $kelas->jam_mulai ?? '') }}"
                                    required
                                    class="w-full px-3.5 py-2.5 text-sm font-mono-tix text-[#1E1B2E] rounded-xl border-2 @error('jam_mulai') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-[#321270] @enderror focus:bg-white outline-none transition duration-200">
                                @error('jam_mulai')
                                    <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="jam_selesai" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                    Jam Selesai Kuliah <span class="text-rose-500">*</span>
                                </label>
                                <input type="time" id="jam_selesai" name="jam_selesai"
                                    value="{{ old('jam_selesai', $kelas->jam_selesai ?? '') }}"
                                    required
                                    class="w-full px-3.5 py-2.5 text-sm font-mono-tix text-[#1E1B2E] rounded-xl border-2 @error('jam_selesai') border-rose-300 bg-rose-50/30 focus:border-rose-500 @else border-slate-200 bg-slate-50 focus:border-[#321270] @enderror focus:bg-white outline-none transition duration-200">
                                @error('jam_selesai')
                                    <p class="mt-1 text-[11px] text-rose-600 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2: DOKUMENTASI --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center shrink-0">
                    <div class="w-8 h-8 rounded-full bg-[#321270] text-white font-display font-semibold text-sm flex items-center justify-center rail-dot">2</div>
                    <div class="w-px flex-1 rail-line mt-1 mb-1"></div>
                </div>
                <div class="flex-1 min-w-0 pb-6">
                    <h2 class="font-display text-lg font-semibold text-[#1E1B2E] mb-0.5">Dokumentasi Sesi</h2>
                    <p class="text-xs text-slate-500 mb-4">Ringkasan materi dan catatan perkuliahan — semua kolom opsional</p>

                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
                        <div>
                            <label for="rangkuman" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                Ringkasan Pokok Bahasan Materi
                            </label>
                            <textarea id="rangkuman" name="rangkuman" rows="3"
                                placeholder="Tulis poin-poin materi utama yang akan dipaparkan..."
                                class="w-full px-3.5 py-2.5 text-sm text-[#1E1B2E] bg-slate-50 hover:bg-white focus:bg-white rounded-xl border-2 border-slate-200 focus:border-[#321270] outline-none transition duration-200 resize-none">{{ old('rangkuman') }}</textarea>
                        </div>

                        <div>
                            <label for="berita_acara" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                Berita Acara Perkuliahan
                            </label>
                            <textarea id="berita_acara" name="berita_acara" rows="3"
                                placeholder="Tulis pokok kejadian, hambatan kelas, atau catatan berita acara..."
                                class="w-full px-3.5 py-2.5 text-sm text-[#1E1B2E] bg-slate-50 hover:bg-white focus:bg-white rounded-xl border-2 border-slate-200 focus:border-[#321270] outline-none transition duration-200 resize-none">{{ old('berita_acara') }}</textarea>
                        </div>

                        <div>
                            <label for="catatan" class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">
                                Catatan Tambahan Khusus
                            </label>
                            <textarea id="catatan" name="catatan" rows="2"
                                placeholder="Catatan pendelegasian tugas mandiri mahasiswa atau lainnya..."
                                class="w-full px-3.5 py-2.5 text-sm text-[#1E1B2E] bg-slate-50 hover:bg-white focus:bg-white rounded-xl border-2 border-slate-200 focus:border-[#321270] outline-none transition duration-200 resize-none">{{ old('catatan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 3 (final marker, no line after) --}}
            <div class="flex gap-4">
                <div class="flex flex-col items-center shrink-0">
                    <div class="w-8 h-8 rounded-full bg-[#321270] text-white flex items-center justify-center rail-dot">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-[#321270]/[0.04] border border-[#321270]/10 rounded-2xl p-4">
                        <p class="text-xs text-slate-500">Sesi akan tersimpan berstatus <span class="font-bold text-amber-700">Draft</span> — aktifkan nanti dari halaman detail.</p>
                        <div class="flex items-center gap-2.5 w-full sm:w-auto">
                            <button type="reset"
                                    class="flex-1 sm:flex-none px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded-xl font-bold text-xs shadow-sm transition-all duration-200">
                                Reset
                            </button>
                            <button type="submit"
                                    class="flex-1 sm:flex-none px-6 py-2.5 bg-[#321270] hover:bg-[#3d1690] text-white rounded-xl font-bold text-xs shadow-md hover:shadow-lg transition-all duration-200">
                                Buat Sesi Baru →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- SIDEBAR: INDEX CARD STYLE --}}
        <div class="space-y-5">

            {{-- alur presensi --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h3 class="font-display font-semibold text-[#1E1B2E] text-base mb-3 pb-2.5 border-b border-dashed border-slate-200">Alur Presensi</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2.5">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-[#321270] shrink-0"></span>
                        <span class="text-xs text-slate-600 leading-relaxed"><strong class="text-[#1E1B2E]">Status awal:</strong> sesi baru berstatus <span class="inline-block bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 rounded text-[10px] font-bold align-middle">Draft</span></span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-[#321270] shrink-0"></span>
                        <span class="text-xs text-slate-600 leading-relaxed"><strong class="text-[#1E1B2E]">Aktivasi:</strong> buka halaman detail absensi untuk mengaktifkan sesi ke mahasiswa</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-[#321270] shrink-0"></span>
                        <span class="text-xs text-slate-600 leading-relaxed"><strong class="text-[#1E1B2E]">Validasi unik:</strong> nomor pertemuan tidak boleh ganda dalam satu kelas</span>
                    </li>
                </ul>
            </div>

            {{-- kelas record card --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="bg-[#321270] px-5 py-3">
                    <p class="text-[10px] font-bold text-white/60 uppercase tracking-[0.15em]">Kartu Kelas</p>
                    <p class="font-display text-white font-semibold text-base truncate">{{ $kelas->mataKuliah->nama_mk }}</p>
                </div>
                <div class="p-5 space-y-3.5">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Kode Akses</span>
                        <span class="font-mono-tix font-bold text-[#321270] bg-[#321270]/[0.06] px-2 py-0.5 rounded border border-[#321270]/10">{{ $kelas->kode_kelas }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Ruangan</span>
                        <span class="font-semibold text-slate-700">{{ $kelas->ruangan ?? 'Online/Hybrid' }}</span>
                    </div>
                    <div class="pt-3 border-t border-dashed border-slate-200 flex items-center justify-between">
                        <span class="text-slate-400 font-semibold uppercase tracking-wider text-[10px]">Mahasiswa Terdaftar</span>
                        <span class="font-display text-2xl font-semibold text-[#321270]">{{ $kelas->mahasiswa->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- tips --}}
            <div class="rounded-2xl border border-dashed border-[#321270]/25 p-5">
                <p class="text-[10px] font-bold text-[#321270] uppercase tracking-[0.15em] mb-2">Catatan Kaki</p>
                <ul class="text-xs text-slate-600 space-y-1.5">
                    <li>· Sesuaikan jam dengan jadwal SIAKAD</li>
                    <li>· Ringkasan yang rapi memudahkan rekap akhir semester</li>
                    <li>· Format waktu mengikuti standar 24 jam</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection