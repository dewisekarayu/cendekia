@extends('layouts.admin')

@section('title', 'Tambah Agenda Kalender Akademik')
@section('activeMenu', 'Kalender Akademik')

@section('content')
<div class="space-y-6">
    
    {{-- Page Header & Breadcrumbs --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-[#002B6B] dark:text-blue-400 border border-blue-100 dark:border-blue-800/60">
                    <i class="bi bi-calendar-plus text-xs"></i>
                    Manajemen Kalender
                </span>
            </div>
            <h1 class="page-title mb-1 text-2xl sm:text-3xl font-extrabold text-[#002B6B] dark:text-white">
                Tambah Agenda Akademik
            </h1>
            <nav style="--bs-breadcrumb-divider: '›';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.kalender-akademik.index') }}">Kalender Akademik</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah Agenda</li>
                </ol>
            </nav>
        </div>

        <div>
            <a href="{{ route('admin.kalender-akademik.index') }}" 
               class="btn btn-light border bg-white dark:bg-slate-800 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 d-inline-flex align-items-center gap-2 px-3.5 py-2 text-sm font-semibold shadow-sm"
               style="border-radius: 0.75rem;">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Kalender</span>
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 shadow-sm flex items-start gap-3" role="alert">
            <div class="w-8 h-8 rounded-xl bg-red-100 dark:bg-red-900/60 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="flex-1 text-sm">
                <strong class="font-bold block mb-1">Terdapat beberapa data yang perlu diperiksa kembali:</strong>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Main Form Container --}}
    <form action="{{ route('admin.kalender-akademik.store') }}" method="POST" id="formAgenda">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Form Inputs (Left 8 Cols) --}}
            <div class="lg:col-span-8 space-y-6">
                
                {{-- SECTION 1: Informasi Kegiatan --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 font-extrabold text-sm flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                            1
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white m-0">Informasi Dasar Kegiatan</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Pilih semester akademik dan masukkan judul serta rincian kegiatan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        {{-- Semester & Judul --}}
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                            <div class="sm:col-span-5">
                                <label for="semester_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    Semester Akademik <span class="text-red-500">*</span>
                                </label>
                                <select name="semester_id" id="semester_id" class="form-select w-full" required onchange="updatePreview()">
                                    <option value="">-- Pilih Semester --</option>
                                    @foreach($semesters as $sem)
                                        <option value="{{ $sem->id }}" 
                                                data-nama="{{ $sem->tahun_ajaran }} – {{ $sem->nama_semester }}"
                                                {{ old('semester_id') == $sem->id ? 'selected' : ($sem->is_active ? 'selected' : '') }}>
                                            {{ $sem->tahun_ajaran }} – {{ $sem->nama_semester }} @if($sem->is_active) (Aktif) @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('semester_id')
                                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="sm:col-span-7">
                                <label for="judul" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    Judul Agenda Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="judul" id="judul" class="form-control w-full"
                                       value="{{ old('judul') }}" placeholder="Contoh: Ujian Tengah Semester (UTS) Gasal" 
                                       required oninput="updatePreview()">
                                @error('judul')
                                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="deskripsi" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Deskripsi & Petunjuk Lengkap
                            </label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control w-full"
                                      placeholder="Tuliskan petunjuk operasional, persyaratan, atau informasi terkait agenda ini..."
                                      oninput="updatePreview()">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: Jadwal & Waktu Pelaksanaan --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 font-extrabold text-sm flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                            2
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white m-0">Waktu & Tanggal Pelaksanaan</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Tentukan durasi tanggal kegiatan dan konfigurasi jam pelaksanaan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Tanggal Mulai --}}
                            <div>
                                <label for="tanggal_mulai" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control w-full"
                                           value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required onchange="updatePreview()">
                                </div>
                                @error('tanggal_mulai')
                                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Tanggal Selesai --}}
                            <div>
                                <label for="tanggal_selesai" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    Tanggal Selesai <span class="text-slate-400 font-normal lowercase">(opsional jika 1 hari)</span>
                                </label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control w-full"
                                       value="{{ old('tanggal_selesai') }}" onchange="updatePreview()">
                                @error('tanggal_selesai')
                                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Card Toggle Sepanjang Hari --}}
                        <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/80 dark:border-slate-700">
                            <label class="flex items-center justify-between cursor-pointer select-none m-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-200/50 dark:border-amber-800/40">
                                        <i class="bi bi-sun"></i>
                                    </div>
                                    <div>
                                        <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white block">Kegiatan Sepanjang Hari (All Day)</span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400">Tidak membutuhkan batasan jam tertentu</span>
                                    </div>
                                </div>
                                <div class="relative inline-flex items-center">
                                    <input type="checkbox" name="is_all_day" id="is_all_day" value="1" 
                                           {{ old('is_all_day', true) ? 'checked' : '' }} 
                                           onchange="toggleWaktuInput(this); updatePreview();"
                                           class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer">
                                </div>
                            </label>
                        </div>

                        {{-- Waktu Container --}}
                        <div id="waktuContainer" class="hidden p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/80 dark:border-slate-700 space-y-3">
                            <div class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="bi bi-clock"></i> Batasan Jam Pelaksanaan
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="waktu_mulai" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jam Mulai</label>
                                    <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control w-full"
                                           value="{{ old('waktu_mulai', '08:00') }}" onchange="updatePreview()">
                                    @error('waktu_mulai')
                                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div>
                                    <label for="waktu_selesai" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jam Selesai</label>
                                    <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control w-full"
                                           value="{{ old('waktu_selesai', '16:00') }}" onchange="updatePreview()">
                                    @error('waktu_selesai')
                                        <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: Kategori & Lokasi --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 font-extrabold text-sm flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                            3
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white m-0">Kategori & Lokasi Kegiatan</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Klasifikasi jenis agenda dan tempat penyelenggaraan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Jenis Kegiatan --}}
                            <div>
                                <label for="jenis_kegiatan" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    Jenis Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-select w-full" required onchange="updatePreview()">
                                    <option value="">-- Pilih Jenis Kegiatan --</option>
                                    @foreach($jenisKegiatanOptions as $key => $label)
                                        <option value="{{ $key }}" {{ old('jenis_kegiatan') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jenis_kegiatan')
                                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Lokasi --}}
                            <div>
                                <label for="lokasi" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                    Lokasi / Tempat Pelaksanaan
                                </label>
                                <div class="relative">
                                    <input type="text" name="lokasi" id="lokasi" class="form-control w-full"
                                           value="{{ old('lokasi') }}" placeholder="Contoh: Gedung Rektorat Lt. 3 / Daring (Zoom)"
                                           oninput="updatePreview()">
                                </div>
                                @error('lokasi')
                                    <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        {{-- Catatan Khusus Admin --}}
                        <div>
                            <label for="catatan" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Catatan Khusus Internal Admin
                            </label>
                            <textarea name="catatan" id="catatan" rows="2" class="form-control w-full"
                                      placeholder="Tuliskan catatan internal jika ada kebutuhan koordinasi atau evaluasi antar administrator...">{{ old('catatan') }}</textarea>
                            @error('catatan')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- SECTION 4: Warna & Publikasi --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">
                    <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 font-extrabold text-sm flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                            4
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-900 dark:text-white m-0">Warna Indikator & Visibilitas</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Skema warna badge kalender dan pengaturan publikasi</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        {{-- Custom Color & Presets --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Skema Warna Badge <span class="text-red-500">*</span>
                            </label>

                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                {{-- Main Color Input --}}
                                <div class="flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700 flex-shrink-0">
                                    <input type="color" name="warna" id="warna" 
                                           class="rounded-lg cursor-pointer border-0 p-0" 
                                           style="width: 44px; height: 44px; background: none;"
                                           value="{{ old('warna', '#002B6B') }}" 
                                           oninput="updateWarnaLabel(); updatePreview();" required>
                                    <div class="pr-2">
                                        <div id="warnaLabel" class="font-bold font-mono text-sm" style="color: #002B6B;">#002B6B</div>
                                        <small class="text-slate-400 text-xs block">Warna Utama</small>
                                    </div>
                                </div>

                                {{-- Quick Presets --}}
                                <div class="flex-1">
                                    <span class="text-xs text-slate-500 dark:text-slate-400 block mb-1.5 font-medium">Pilihan Preset Cepat:</span>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($warnaPresets as $hex => $label)
                                            <button type="button" 
                                                    class="color-preset-btn transition-transform active:scale-95 inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-bold shadow-sm border border-black/5"
                                                    style="background-color: {{ $hex }}; color: {{ in_array($hex, ['#F59E0B', '#EA580C', '#65A30D', '#16A34A', '#EAB308']) ? '#1e293b' : '#ffffff' }};"
                                                    onclick="setColor('{{ $hex }}');"
                                                    title="{{ $label }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>
                                                {{ str_replace('Warna ', '', $label) }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @error('warna')
                                <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Publikasi Toggle Card --}}
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/80 dark:border-slate-700">
                            <label class="flex items-start gap-3.5 cursor-pointer select-none m-0">
                                <input type="checkbox" name="is_published" id="is_published" value="1" 
                                       {{ old('is_published', true) ? 'checked' : '' }} 
                                       onchange="updatePreview()"
                                       class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer mt-0.5">
                                <div>
                                    <div class="text-sm font-bold text-slate-800 dark:text-white">Publikasikan Langsung ke Kalender Civitas</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                        Jika dicentang, agenda ini akan otomatis terlihat pada portal mahasiswa, dosen, dan dashboard akademik. Jika tidak, akan disimpan sebagai draft internal.
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="p-4 sm:p-6 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ route('admin.kalender-akademik.index') }}"
                       class="btn btn-light border w-full sm:w-auto px-5 py-2.5 text-sm font-semibold rounded-xl text-slate-700 dark:text-slate-200 dark:bg-slate-700 dark:border-slate-600">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary w-full sm:w-auto px-6 py-2.5 flex items-center justify-center gap-2">
                        <i class="bi bi-calendar-check text-base"></i>
                        <span>Simpan Agenda Akademik</span>
                    </button>
                </div>

            </div>

            {{-- Live Preview & Sidebar Helper (Right 4 Cols) --}}
            <div class="lg:col-span-4 space-y-6">
                
                {{-- Live Preview Card --}}
                <div class="sticky top-6 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5">
                        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-700/60">
                            <div class="flex items-center gap-2">
                                <i class="bi bi-eye text-blue-600 dark:text-blue-400"></i>
                                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300">
                                    Pratinjau Agenda
                                </span>
                            </div>
                            <span class="badge-status" id="previewStatusBadge" style="background-color: #ecfdf5; color: #059669;">
                                <span class="status-dot"></span> <span id="previewStatusText">Publik</span>
                            </span>
                        </div>

                        {{-- Preview Event Card UI --}}
                        <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 transition-all" id="previewCardBox" style="border-left-width: 6px; border-left-color: #002B6B; background-color: rgba(0, 43, 107, 0.03);">
                            
                            {{-- Category Badge & Semester --}}
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <span id="previewJenisBadge" class="text-[11px] font-bold px-2 py-0.5 rounded text-white" style="background-color: #002B6B;">
                                    Umum / Kegiatan
                                </span>
                                <span id="previewSemester" class="text-[11px] text-slate-400 dark:text-slate-500 font-medium truncate max-w-[140px]">
                                    Semester Aktif
                                </span>
                            </div>

                            {{-- Title --}}
                            <h3 id="previewJudul" class="text-sm font-bold text-slate-900 dark:text-white mb-2 leading-snug">
                                Judul Agenda Kegiatan
                            </h3>

                            {{-- Description --}}
                            <p id="previewDeskripsi" class="text-xs text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">
                                Deskripsi agenda akan muncul di sini sesuai teks yang Anda masukkan...
                            </p>

                            {{-- Metadata Info --}}
                            <div class="space-y-1.5 text-xs text-slate-600 dark:text-slate-300 pt-2 border-t border-slate-100 dark:border-slate-700/60">
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-calendar3 text-slate-400"></i>
                                    <span id="previewTanggal" class="font-medium">Hari Ini</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="bi bi-clock text-slate-400"></i>
                                    <span id="previewWaktu" class="font-medium">Sepanjang Hari</span>
                                </div>
                                <div class="flex items-center gap-2" id="previewLokasiWrapper">
                                    <i class="bi bi-geo-alt text-slate-400"></i>
                                    <span id="previewLokasi" class="font-medium text-slate-500 italic">Lokasi belum ditentukan</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 bg-blue-50/50 dark:bg-blue-950/30 rounded-xl border border-blue-100/60 dark:border-blue-900/40 text-xs text-slate-600 dark:text-slate-300">
                            <div class="font-bold text-[#002B6B] dark:text-blue-400 mb-1 flex items-center gap-1.5">
                                <i class="bi bi-lightbulb"></i> Tips Kalender Akademik:
                            </div>
                            <ul class="list-disc list-inside space-y-1 text-[11px] text-slate-500 dark:text-slate-400">
                                <li>Pilih warna kontras untuk kegiatan penting seperti UTS / UAS.</li>
                                <li>Kegiatan libur nasional dapat menggunakan warna merah / jingga.</li>
                                <li>Agenda yang dipublikasikan langsung tampil pada portal dosen & mahasiswa.</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

@push('scripts')
<script>
    function setColor(hex) {
        document.getElementById('warna').value = hex;
        updateWarnaLabel();
        updatePreview();
    }

    function updateWarnaLabel() {
        const color = document.getElementById('warna').value;
        const label = document.getElementById('warnaLabel');
        label.textContent = color.toUpperCase();
        label.style.color = color;
    }

    function toggleWaktuInput(checkbox) {
        const container = document.getElementById('waktuContainer');
        const mulai = document.getElementById('waktu_mulai');
        const selesai = document.getElementById('waktu_selesai');
        
        if (checkbox.checked) {
            container.classList.add('hidden');
            if (mulai) mulai.required = false;
            if (selesai) selesai.required = false;
        } else {
            container.classList.remove('hidden');
            if (mulai) mulai.required = true;
        }
    }

    function formatTanggalIndo(dateStr) {
        if (!dateStr) return '';
        const parts = dateStr.split('-');
        if (parts.length !== 3) return dateStr;
        const year = parts[0];
        const monthIndex = parseInt(parts[1], 10) - 1;
        const day = parseInt(parts[2], 10);
        
        const bulanIndo = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        return `${day} ${bulanIndo[monthIndex]} ${year}`;
    }

    function updatePreview() {
        // Judul
        const judulVal = document.getElementById('judul').value.trim();
        document.getElementById('previewJudul').textContent = judulVal || 'Judul Agenda Kegiatan';

        // Deskripsi
        const deskripsiVal = document.getElementById('deskripsi').value.trim();
        document.getElementById('previewDeskripsi').textContent = deskripsiVal || 'Deskripsi agenda akan muncul di sini sesuai teks yang Anda masukkan...';

        // Semester
        const semesterEl = document.getElementById('semester_id');
        const selectedSemOption = semesterEl.options[semesterEl.selectedIndex];
        document.getElementById('previewSemester').textContent = (selectedSemOption && selectedSemOption.value) ? selectedSemOption.getAttribute('data-nama') || selectedSemOption.text : 'Semester Aktif';

        // Tanggal
        const tglMulai = document.getElementById('tanggal_mulai').value;
        const tglSelesai = document.getElementById('tanggal_selesai').value;
        
        let formattedTgl = 'Hari Ini';
        if (tglMulai && tglSelesai && tglMulai !== tglSelesai) {
            formattedTgl = `${formatTanggalIndo(tglMulai)} - ${formatTanggalIndo(tglSelesai)}`;
        } else if (tglMulai) {
            formattedTgl = formatTanggalIndo(tglMulai);
        }
        document.getElementById('previewTanggal').textContent = formattedTgl;

        // Waktu
        const isAllDay = document.getElementById('is_all_day').checked;
        if (isAllDay) {
            document.getElementById('previewWaktu').textContent = 'Sepanjang Hari';
        } else {
            const wMulai = document.getElementById('waktu_mulai').value || '--:--';
            const wSelesai = document.getElementById('waktu_selesai').value;
            document.getElementById('previewWaktu').textContent = wSelesai ? `${wMulai} - ${wSelesai} WIB` : `Mulai ${wMulai} WIB`;
        }

        // Lokasi
        const lokasiVal = document.getElementById('lokasi').value.trim();
        const lokasiEl = document.getElementById('previewLokasi');
        if (lokasiVal) {
            lokasiEl.textContent = lokasiVal;
            lokasiEl.classList.remove('text-slate-400', 'italic');
        } else {
            lokasiEl.textContent = 'Lokasi belum ditentukan';
            lokasiEl.classList.add('text-slate-400', 'italic');
        }

        // Jenis Kegiatan
        const jenisEl = document.getElementById('jenis_kegiatan');
        const selectedJenis = jenisEl.options[jenisEl.selectedIndex];
        document.getElementById('previewJenisBadge').textContent = (selectedJenis && selectedJenis.value) ? selectedJenis.text : 'Umum / Kegiatan';

        // Warna
        const colorVal = document.getElementById('warna').value;
        const previewCard = document.getElementById('previewCardBox');
        const previewJenisBadge = document.getElementById('previewJenisBadge');
        
        previewCard.style.borderLeftColor = colorVal;
        previewJenisBadge.style.backgroundColor = colorVal;
        
        // Cek kecerahan warna untuk text badge
        let r = 0, g = 0, b = 0;
        if (colorVal.length === 7) {
            r = parseInt(colorVal.substr(1, 2), 16);
            g = parseInt(colorVal.substr(3, 2), 16);
            b = parseInt(colorVal.substr(5, 2), 16);
        }
        const brightness = (r * 299 + g * 587 + b * 114) / 1000;
        previewJenisBadge.style.color = brightness > 155 ? '#1e293b' : '#ffffff';

        // Publikasi
        const isPub = document.getElementById('is_published').checked;
        const statusBadge = document.getElementById('previewStatusBadge');
        const statusText = document.getElementById('previewStatusText');
        if (isPub) {
            statusBadge.style.backgroundColor = '#ecfdf5';
            statusBadge.style.color = '#059669';
            statusText.textContent = 'Publik';
        } else {
            statusBadge.style.backgroundColor = '#f1f5f9';
            statusBadge.style.color = '#64748b';
            statusText.textContent = 'Draft Internal';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateWarnaLabel();
        toggleWaktuInput(document.getElementById('is_all_day'));
        updatePreview();
    });
</script>
@endpush

@endsection