@extends('layouts.admin')

@section('title', 'Tambah Agenda Kalender Akademik')
@section('activeMenu', 'Kalender Akademik')

@section('content')
<style>
    /* ---------- Stepper ---------- */
    .step-dot {
        width: 2.4rem; height: 2.4rem; border-radius: 9999px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: .85rem;
        border: 2px solid #cbd5e1; color: #94a3b8; background: #fff;
        transition: all .2s ease; flex-shrink: 0;
    }
    .dark .step-dot { background: #1e293b; border-color: #475569; color: #64748b; }
    .step-dot.is-active { border-color: #002B6B; background: #002B6B; color: #fff; box-shadow: 0 0 0 4px rgba(0, 43, 107, .15); }
    .step-dot.is-done { border-color: #16a34a; background: #16a34a; color: #fff; }
    .step-line { flex: 1; height: 2px; background: #e2e8f0; margin: 0 .35rem; transition: background .2s ease; }
    .dark .step-line { background: #334155; }
    .step-line.is-done { background: #16a34a; }
    .step-label { font-size: .7rem; font-weight: 700; color: #94a3b8; margin-top: .35rem; text-align: center; }
    .step-label.is-active { color: #002B6B; }
    .dark .step-label.is-active { color: #93c5fd; }

    /* ---------- Wizard step ---------- */
    .wizard-step { display: none; }
    .wizard-step.is-current { display: block; animation: stepIn .25s ease; }
    @keyframes stepIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }
</style>

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

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
    <div class="lg:col-span-8 space-y-6">

    {{-- ============ STEPPER ============ --}}
    @php
        $wizardSteps = [
            ['Informasi', 'bi-card-text'],
            ['Waktu', 'bi-calendar-event'],
            ['Kategori', 'bi-tags'],
            ['Warna', 'bi-palette'],
        ];
    @endphp
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm p-4 sm:p-5">
        <div class="flex items-start">
            @foreach ($wizardSteps as $i => $s)
                <button type="button" class="wizard-nav flex flex-col items-center bg-transparent border-0 p-0" style="min-width: 2.4rem;"
                        data-goto="{{ $i + 1 }}" aria-label="Langkah {{ $i + 1 }}: {{ $s[0] }}">
                    <span class="step-dot" data-dot="{{ $i + 1 }}">{{ $i + 1 }}</span>
                    <span class="step-label hidden sm:block" data-label="{{ $i + 1 }}">{{ $s[0] }}</span>
                </button>
                @if (!$loop->last)
                    <div class="step-line" style="margin-top: 1.1rem;" data-line="{{ $i + 1 }}"></div>
                @endif
            @endforeach
        </div>
        <div class="sm:hidden text-center text-xs font-semibold text-slate-500 dark:text-slate-400 mt-3" id="stepCaption"></div>
    </div>

    {{-- ============ FORM ============ --}}
    <form action="{{ route('admin.kalender-akademik.store') }}" method="POST" id="formAgenda" novalidate>
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">

            {{-- ---------- STEP 1: Informasi Dasar ---------- --}}
            <div class="wizard-step" data-step="1">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                        <i class="bi bi-card-text text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white m-0">Informasi Dasar</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Pilih semester, lalu isi judul dan deskripsi kegiatan</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label for="semester_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Semester Akademik <span class="text-red-500">*</span>
                        </label>
                        <select name="semester_id" id="semester_id" class="form-select w-full" required onchange="updatePreview()">
                            <option value="">-- Pilih Semester --</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}"
                                        data-nama="{{ $sem->tahun_ajaran }} – {{ $sem->nama_semester }}"
                                        {{ (old('semester_id') ? old('semester_id') == $sem->id : $sem->is_active) ? 'selected' : '' }}>
                                    {{ $sem->tahun_ajaran }} – {{ $sem->nama_semester }} @if($sem->is_active) (Aktif) @endif
                                </option>
                            @endforeach
                        </select>
                        @error('semester_id')
                            <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="judul" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Judul Agenda <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" class="form-control w-full"
                               value="{{ old('judul') }}" placeholder="Contoh: Ujian Tengah Semester (UTS) Gasal"
                               required oninput="updatePreview()">
                        @error('judul')
                            <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Deskripsi & Petunjuk <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="form-control w-full"
                                  placeholder="Tuliskan petunjuk operasional, persyaratan, atau informasi terkait agenda ini..."
                                  oninput="updatePreview()">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ---------- STEP 2: Waktu & Tanggal ---------- --}}
            <div class="wizard-step" data-step="2">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                        <i class="bi bi-calendar-event text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white m-0">Waktu & Tanggal</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Kapan kegiatan ini dilaksanakan?</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="tanggal_mulai" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control w-full"
                                   value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required onchange="updatePreview()">
                            @error('tanggal_mulai')
                                <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_selesai" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                                Tanggal Selesai <span class="text-slate-400 font-normal lowercase">(kosongkan jika 1 hari)</span>
                            </label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control w-full"
                                   value="{{ old('tanggal_selesai') }}" onchange="updatePreview()">
                            @error('tanggal_selesai')
                                <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/80 dark:border-slate-700">
                        <label for="is_all_day" class="flex items-center justify-between gap-3 cursor-pointer select-none m-0">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center border border-amber-200/50 dark:border-amber-800/40 flex-shrink-0">
                                    <i class="bi bi-sun"></i>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white block">Kegiatan Sepanjang Hari (All Day)</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">Matikan jika kegiatan memiliki jam tertentu</span>
                                </div>
                            </div>
                            <input type="checkbox" name="is_all_day" id="is_all_day" value="1"
                                   {{ (old() ? old('is_all_day') : true) ? 'checked' : '' }}
                                   onchange="toggleWaktuInput(this); updatePreview();"
                                   class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer flex-shrink-0">
                        </label>
                    </div>

                    <div id="waktuContainer" class="hidden p-4 rounded-xl bg-slate-50/70 dark:bg-slate-900/40 border border-slate-200/80 dark:border-slate-700 space-y-3">
                        <div class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="bi bi-clock"></i> Jam Pelaksanaan
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="waktu_mulai" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jam Mulai</label>
                                <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control w-full"
                                       value="{{ old('waktu_mulai', '08:00') }}" onchange="updatePreview()">
                                @error('waktu_mulai')
                                    <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="waktu_selesai" class="block text-xs font-semibold text-slate-600 dark:text-slate-300 mb-1">Jam Selesai</label>
                                <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control w-full"
                                       value="{{ old('waktu_selesai', '16:00') }}" onchange="updatePreview()">
                                @error('waktu_selesai')
                                    <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ---------- STEP 3: Kategori & Lokasi ---------- --}}
            <div class="wizard-step" data-step="3">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                        <i class="bi bi-tags text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white m-0">Kategori & Lokasi</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Jenis agenda dan tempat penyelenggaraan</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="jenis_kegiatan" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider m-0">
                                Jenis Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <button type="button" onclick="toggleFormTambahJenis()" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 bg-blue-50 dark:bg-blue-950/60 hover:bg-blue-100 dark:hover:bg-blue-900/80 px-2.5 py-1 rounded-lg border border-blue-200/80 dark:border-blue-800/60 transition-all cursor-pointer">
                                <i class="bi bi-plus-lg text-xs"></i>
                                <span>Tambah Jenis</span>
                            </button>
                        </div>

                        <!-- Form Inline Tambah Jenis Kegiatan Baru -->
                        <div id="formTambahJenisContainer" class="hidden mb-3 p-3 bg-slate-50 dark:bg-slate-900/70 rounded-xl border border-slate-200 dark:border-slate-700 transition-all shadow-sm">
                            <div class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center justify-between">
                                <span class="flex items-center gap-1.5">
                                    <i class="bi bi-plus-circle-fill text-blue-600 dark:text-blue-400"></i>
                                    <span>Tambah Jenis Kegiatan Baru</span>
                                </span>
                                <button type="button" onclick="toggleFormTambahJenis()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-0.5 rounded transition-colors">
                                    <i class="bi bi-x-lg text-xs"></i>
                                </button>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="text" id="input_jenis_baru" class="form-control text-sm py-1.5 px-3 rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full" placeholder="Contoh: Seminar Nasional / Workshop" onkeydown="if(event.key === 'Enter'){ event.preventDefault(); simpanJenisKegiatanBaru(); }">
                                <button type="button" onclick="simpanJenisKegiatanBaru()" class="px-3.5 py-1.5 bg-[#002B6B] hover:bg-blue-900 text-white text-xs font-semibold rounded-lg shadow-sm transition-all flex items-center gap-1.5 shrink-0 cursor-pointer">
                                    <i class="bi bi-check-lg"></i>
                                    <span>Tambah</span>
                                </button>
                            </div>
                            <span id="error_jenis_baru" class="text-red-500 text-xs mt-1.5 hidden font-medium block"></span>
                        </div>

                        <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-select w-full" required onchange="updatePreview()">
                            <option value="">-- Pilih Jenis Kegiatan --</option>
                            @foreach($jenisKegiatanOptions as $key => $label)
                                <option value="{{ $key }}" {{ old('jenis_kegiatan') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('jenis_kegiatan')
                            <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Lokasi / Tempat <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <input type="text" name="lokasi" id="lokasi" class="form-control w-full"
                               value="{{ old('lokasi') }}" placeholder="Contoh: Gedung Rektorat Lt. 3 / Daring (Zoom)"
                               oninput="updatePreview()">
                        @error('lokasi')
                            <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="target_audience" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Ditampilkan Untuk <span class="text-red-500">*</span>
                        </label>
                        <select name="target_audience" id="target_audience" class="form-select w-full" required onchange="updatePreview()">
                            <option value="semua" {{ old('target_audience') == 'semua' ? 'selected' : '' }}>Semua (Dosen & Mahasiswa)</option>
                            <option value="dosen" {{ old('target_audience') == 'dosen' ? 'selected' : '' }}>Hanya Dosen</option>
                            <option value="mahasiswa" {{ old('target_audience') == 'mahasiswa' ? 'selected' : '' }}>Hanya Mahasiswa</option>
                        </select>
                        @error('target_audience')
                            <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="catatan" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Catatan Internal Admin <span class="text-slate-400 font-normal lowercase">(opsional, tidak tampil ke publik)</span>
                        </label>
                        <textarea name="catatan" id="catatan" rows="3" class="form-control w-full"
                                  placeholder="Catatan untuk koordinasi atau evaluasi antar administrator...">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ---------- STEP 4: Warna & Publikasi ---------- --}}
            <div class="wizard-step" data-step="4">
                <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                        <i class="bi bi-palette text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white m-0">Warna & Publikasi</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Warna badge di kalender dan status tayang</p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                            Warna Badge <span class="text-red-500">*</span>
                        </label>

                        <span class="text-xs text-slate-500 dark:text-slate-400 block mb-2 font-medium">Pilih preset cepat:</span>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($warnaPresets as $hex => $label)
                                @php
                                    $presetTextColor = in_array($hex, ['#F59E0B', '#EA580C', '#65A30D', '#16A34A', '#EAB308']) ? '#1e293b' : '#ffffff';
                                @endphp
                                <button type="button"
                                        class="color-preset-btn transition-transform active:scale-95 inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-bold shadow-sm border border-black/5"
                                        @style(['background-color: ' . $hex, 'color: ' . $presetTextColor])
                                        onclick="setColor('{{ $hex }}');"
                                        title="{{ $label }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white/70"></span>
                                    {{ str_replace('Warna ', '', $label) }}
                                </button>
                            @endforeach
                        </div>

                        <div class="inline-flex items-center gap-3 p-2 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-700">
                            <input type="color" name="warna" id="warna"
                                   class="rounded-lg cursor-pointer border-0 p-0"
                                   style="width: 44px; height: 44px; background: none;"
                                   value="{{ old('warna', '#002B6B') }}"
                                   oninput="updateWarnaLabel(); updatePreview();" required>
                            <div class="pr-2">
                                <div id="warnaLabel" class="font-bold font-mono text-sm" style="color: #002B6B;">#002B6B</div>
                                <small class="text-slate-400 text-xs block">Atau pilih warna sendiri</small>
                            </div>
                        </div>
                        @error('warna')
                            <span class="field-error text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/80 dark:border-slate-700">
                        <label for="is_published" class="flex items-start gap-3.5 cursor-pointer select-none m-0">
                            <input type="checkbox" name="is_published" id="is_published" value="1"
                                   {{ (old() ? old('is_published') : true) ? 'checked' : '' }}
                                   onchange="updatePreview()"
                                   class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer mt-0.5 flex-shrink-0">
                            <div>
                                <div class="text-sm font-bold text-slate-800 dark:text-white">Publikasikan Langsung ke Kalender Civitas</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                    Jika dicentang, agenda langsung terlihat di portal mahasiswa, dosen, dan dashboard akademik. Jika tidak, disimpan sebagai draft internal.
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ---------- Navigasi Wizard ---------- --}}
            <div class="flex items-center justify-between gap-3 pt-6 mt-6 border-t border-slate-100 dark:border-slate-700/60">
                <div>
                    <a href="{{ route('admin.kalender-akademik.index') }}" id="btnBatal"
                       class="btn btn-light border px-5 py-2.5 text-sm font-semibold rounded-xl text-slate-700 dark:text-slate-200 dark:bg-slate-700 dark:border-slate-600">
                        Batal
                    </a>
                    <button type="button" id="btnPrev"
                            class="btn btn-light border px-5 py-2.5 text-sm font-semibold rounded-xl text-slate-700 dark:text-slate-200 dark:bg-slate-700 dark:border-slate-600 hidden">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </button>
                </div>

                <div>
                    <button type="button" id="btnNext" class="btn btn-primary px-6 py-2.5 inline-flex items-center gap-2">
                        <span>Lanjut</span> <i class="bi bi-arrow-right"></i>
                    </button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary px-6 py-2.5 hidden items-center gap-2">
                        <i class="bi bi-calendar-check text-base"></i>
                        <span>Simpan Agenda</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    </div>{{-- /kolom form --}}

    {{-- ============ PRATINJAU (selalu di samping form) ============ --}}
    <div class="lg:col-span-4">
        <div class="lg:sticky lg:top-6 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100 dark:border-slate-700/60">
                    <div class="flex items-center gap-2">
                        <i class="bi bi-eye text-blue-600 dark:text-blue-400"></i>
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-700 dark:text-slate-300">Pratinjau Agenda</span>
                    </div>
                    <span class="badge-status" id="previewStatusBadge" style="background-color: #ecfdf5; color: #059669;">
                        <span class="status-dot"></span> <span id="previewStatusText">Publik</span>
                    </span>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 transition-all" id="previewCardBox" style="border-left-width: 6px; border-left-color: #002B6B; background-color: rgba(0, 43, 107, 0.03);">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span id="previewJenisBadge" class="text-[11px] font-bold px-2 py-0.5 rounded text-white" style="background-color: #002B6B;">
                            Umum / Kegiatan
                        </span>
                        <span id="previewSemester" class="text-[11px] text-slate-400 dark:text-slate-500 font-medium truncate max-w-[140px]">
                            Semester Aktif
                        </span>
                    </div>

                    <h3 id="previewJudul" class="text-sm font-bold text-slate-900 dark:text-white mb-2 leading-snug">
                        Judul Agenda Kegiatan
                    </h3>

                    <p id="previewDeskripsi" class="text-xs text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">
                        Deskripsi agenda akan muncul di sini sesuai teks yang Anda masukkan...
                    </p>

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

    </div>{{-- /grid --}}
</div>

@push('scripts')
<script>
    const TOTAL_STEPS = 4;
    const STEP_TITLES = ['Informasi Dasar', 'Waktu & Tanggal', 'Kategori & Lokasi', 'Warna & Publikasi'];
    let currentStep = 1;

    /* ---------------- Wizard ---------------- */
    function showStep(n) {
        currentStep = n;

        document.querySelectorAll('.wizard-step').forEach(el => {
            el.classList.toggle('is-current', parseInt(el.dataset.step, 10) === n);
        });

        for (let i = 1; i <= TOTAL_STEPS; i++) {
            const dot = document.querySelector(`[data-dot="${i}"]`);
            const label = document.querySelector(`[data-label="${i}"]`);
            dot.classList.toggle('is-active', i === n);
            dot.classList.toggle('is-done', i < n);
            dot.innerHTML = i < n ? '<i class="bi bi-check-lg"></i>' : i;
            if (label) label.classList.toggle('is-active', i === n);
            const line = document.querySelector(`[data-line="${i}"]`);
            if (line) line.classList.toggle('is-done', i < n);
        }

        document.getElementById('stepCaption').textContent = `Langkah ${n} dari ${TOTAL_STEPS} · ${STEP_TITLES[n - 1]}`;

        document.getElementById('btnBatal').classList.toggle('hidden', n !== 1);
        document.getElementById('btnPrev').classList.toggle('hidden', n === 1);
        document.getElementById('btnNext').classList.toggle('hidden', n === TOTAL_STEPS);

        const submitBtn = document.getElementById('btnSubmit');
        submitBtn.classList.toggle('hidden', n !== TOTAL_STEPS);
        submitBtn.classList.toggle('inline-flex', n === TOTAL_STEPS);

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(n) {
        checkRentang();
        const step = document.querySelector(`.wizard-step[data-step="${n}"]`);
        const fields = step.querySelectorAll('input, select, textarea');
        for (const f of fields) {
            if (!f.checkValidity()) {
                f.reportValidity();
                return false;
            }
        }
        return true;
    }

    function goTo(target) {
        if (target < currentStep) { showStep(target); return; }
        for (let i = currentStep; i < target; i++) {
            showStep(i);
            if (!validateStep(i)) return;
        }
        showStep(target);
    }

    // Validasi tambahan: tanggal & jam tidak boleh terbalik
    function checkRentang() {
        const mulai = document.getElementById('tanggal_mulai');
        const selesai = document.getElementById('tanggal_selesai');
        selesai.setCustomValidity(
            selesai.value && mulai.value && selesai.value < mulai.value
                ? 'Tanggal selesai tidak boleh sebelum tanggal mulai.' : ''
        );

        const wMulai = document.getElementById('waktu_mulai');
        const wSelesai = document.getElementById('waktu_selesai');
        const allDay = document.getElementById('is_all_day').checked;
        const sehari = !selesai.value || selesai.value === mulai.value;
        wSelesai.setCustomValidity(
            !allDay && sehari && wMulai.value && wSelesai.value && wSelesai.value <= wMulai.value
                ? 'Jam selesai harus setelah jam mulai.' : ''
        );
    }

    /* ---------------- Helper form ---------------- */
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

    /* ---------------- Tambah Jenis Kegiatan Dynamically ---------------- */
    function toggleFormTambahJenis() {
        const container = document.getElementById('formTambahJenisContainer');
        const input = document.getElementById('input_jenis_baru');
        const errorEl = document.getElementById('error_jenis_baru');
        
        if (errorEl) {
            errorEl.classList.add('hidden');
            errorEl.textContent = '';
        }
        
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            setTimeout(() => input.focus(), 100);
        } else {
            container.classList.add('hidden');
            input.value = '';
        }
    }

    function simpanJenisKegiatanBaru() {
        const input = document.getElementById('input_jenis_baru');
        const errorEl = document.getElementById('error_jenis_baru');
        const select = document.getElementById('jenis_kegiatan');
        
        const rawVal = input.value.trim();
        if (!rawVal) {
            errorEl.textContent = 'Nama jenis kegiatan tidak boleh kosong.';
            errorEl.classList.remove('hidden');
            input.focus();
            return;
        }

        const key = rawVal.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '') || rawVal.toLowerCase();
        
        let existingOpt = Array.from(select.options).find(opt => opt.value === key || opt.text.toLowerCase() === rawVal.toLowerCase());
        
        if (existingOpt) {
            select.value = existingOpt.value;
            errorEl.classList.add('hidden');
            toggleFormTambahJenis();
            updatePreview();
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: 'Jenis kegiatan sudah ada dan telah dipilih.',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
            return;
        }

        const option = document.createElement('option');
        option.value = key;
        option.text = rawVal;
        option.selected = true;

        select.appendChild(option);
        select.value = key;
        
        toggleFormTambahJenis();
        updatePreview();

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Jenis kegiatan "${rawVal}" berhasil ditambahkan.`,
                showConfirmButton: false,
                timer: 2500
            });
        }
    }

    function updatePreview() {
        checkRentang();

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

    /* ---------------- Init ---------------- */
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formAgenda');

        updateWarnaLabel();
        toggleWaktuInput(document.getElementById('is_all_day'));
        updatePreview();

        // Jika ada error dari server, langsung buka langkah yang bermasalah
        const firstErr = document.querySelector('.wizard-step .field-error');
        showStep(firstErr ? parseInt(firstErr.closest('.wizard-step').dataset.step, 10) : 1);

        document.getElementById('btnNext').addEventListener('click', () => goTo(currentStep + 1));
        document.getElementById('btnPrev').addEventListener('click', () => goTo(currentStep - 1));
        document.querySelectorAll('.wizard-nav').forEach(btn => {
            // Klik nomor langkah = pindah bebas, tanpa perlu mengisi dulu
            btn.addEventListener('click', () => showStep(parseInt(btn.dataset.goto, 10)));
        });

        // Tekan Enter = lanjut ke langkah berikutnya (bukan langsung submit)
        form.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA' && e.target.type !== 'submit') {
                e.preventDefault();
                if (currentStep < TOTAL_STEPS) goTo(currentStep + 1);
            }
        });

        // Validasi semua langkah sebelum kirim
        form.addEventListener('submit', function (e) {
            for (let i = 1; i <= TOTAL_STEPS; i++) {
                showStep(i);
                if (!validateStep(i)) { e.preventDefault(); return; }
            }
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true; // cegah klik ganda
        });
    });
</script>
@endpush

@endsection