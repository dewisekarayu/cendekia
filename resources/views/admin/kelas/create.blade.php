@extends('layouts.admin')

@section('title', 'Tambah Kelas Perkuliahan')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}" class="text-decoration-none text-muted">Kelas Perkuliahan</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Tambah Baru</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Tambah Kelas Perkuliahan</h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Daftarkan kelas baru, alokasikan dosen, ruang, dan jadwal.</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Periksa kembali isian form:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.kelas.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <!-- Data Akademik -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-journal-bookmark-fill" style="color: #002B6B;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Data Akademik & Kurikulum</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="semester_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Semester <span class="text-danger">*</span></label>
                            <select name="semester_id" id="semester_id" class="form-select @error('semester_id') is-invalid @enderror" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                                <option value="">-- Pilih Semester --</option>
                                @foreach ($semesterList as $semester)
                                    <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                                        {{ $semester->nama_semester }} {{ $semester->is_active ? '(Aktif)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('semester_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="program_studi_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Program Studi <span class="text-danger">*</span></label>
                            <select name="program_studi_id" id="program_studi_id" class="form-select @error('program_studi_id') is-invalid @enderror" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;" onchange="updateMataKuliah(this.value)">
                                <option value="">-- Pilih Program Studi --</option>
                                @foreach ($programStudiList as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('program_studi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="mata_kuliah_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Mata Kuliah <span class="text-danger">*</span></label>
                            <select name="mata_kuliah_id" id="mata_kuliah_id" class="form-select @error('mata_kuliah_id') is-invalid @enderror" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                                <option value="">-- Pilih Prodi Terlebih Dahulu --</option>
                                {{-- Diisi via JavaScript --}}
                            </select>
                            @error('mata_kuliah_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="kode_kelas" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Kode Kelas <span class="text-danger">*</span></label>
                            <input type="text" name="kode_kelas" id="kode_kelas" value="{{ old('kode_kelas') }}" class="form-control @error('kode_kelas') is-invalid @enderror" placeholder="Contoh: A, B, atau Reg-1" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('kode_kelas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Pengampu -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-people-fill" style="color: #002B6B;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Dosen Pengampu & Team Teaching</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="dosen_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Dosen Utama (Koordinator) <span class="text-danger">*</span></label>
                            <select name="dosen_id" id="dosen_id" class="form-select @error('dosen_id') is-invalid @enderror" required style="border-radius: 8px; background-color: #F8FAFC;">
                                <option value="">-- Pilih Dosen Utama --</option>
                                @foreach ($dosenList as $dosen)
                                    <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
                                        {{ $dosen->name }} ({{ $dosen->nip_nim }})
                                    </option>
                                @endforeach
                            </select>
                            @error('dosen_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="dosen_pengampu" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Dosen Tambahan (Team Teaching) <span class="text-muted fw-normal text-lowercase">opsional</span></label>
                            <select name="dosen_pengampu[]" id="dosen_pengampu" class="form-select @error('dosen_pengampu') is-invalid @enderror" multiple="multiple" style="border-radius: 8px; background-color: #F8FAFC;">
                                @foreach ($dosenList as $dosen)
                                    <option value="{{ $dosen->id }}" {{ in_array($dosen->id, old('dosen_pengampu', [])) ? 'selected' : '' }}>
                                        {{ $dosen->name }} ({{ $dosen->nip_nim }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">Pilih beberapa dosen jika kelas ini menggunakan sistem team teaching. Dosen utama otomatis tidak bisa dipilih lagi di sini.</small>
                            @error('dosen_pengampu') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Waktu & Tempat -->
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-clock-fill" style="color: #002B6B;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Waktu & Ruang</h6>
                    </div>

                    <div class="mb-3">
                        <label for="hari" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Hari <span class="text-danger">*</span></label>
                        <select name="hari" id="hari" class="form-select @error('hari') is-invalid @enderror" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            <option value="">-- Pilih Hari --</option>
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $hari)
                                <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                            @endforeach
                        </select>
                        @error('hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="jam_mulai" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" id="jam_mulai" value="{{ old('jam_mulai') }}" class="form-control @error('jam_mulai') is-invalid @enderror" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6">
                            <label for="jam_selesai" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" id="jam_selesai" value="{{ old('jam_selesai') }}" class="form-control @error('jam_selesai') is-invalid @enderror" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="ruangan" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Ruangan / Lab</label>
                        <input type="text" name="ruangan" id="ruangan" value="{{ old('ruangan') }}" class="form-control @error('ruangan') is-invalid @enderror" placeholder="Cth: R.301 / Lab Komputer" style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                        @error('ruangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kuota_mahasiswa" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Kuota Mahasiswa <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="kuota_mahasiswa" id="kuota_mahasiswa" value="{{ old('kuota_mahasiswa', 40) }}" class="form-control @error('kuota_mahasiswa') is-invalid @enderror" required min="1" max="200" style="background-color: #F8FAFC;">
                            <span class="input-group-text" style="background-color: #e2e8f0;">Orang</span>
                        </div>
                        @error('kuota_mahasiswa') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold d-flex align-items-center justify-content-center gap-2" style="background-color: #002B6B; border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                        <i class="bi bi-save"></i> Buat Kelas
                    </button>
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-light border w-100 py-2 fw-semibold text-secondary" style="border-radius: 8px; background-color: white;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- CSS dimasukkan di sini supaya tetap termuat walau layout tidak punya @stack('styles') --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 8px;
        background-color: #F8FAFC;
        border-color: #dee2e6;
        font-size: 0.95rem;
    }
    .select2-container--bootstrap-5 .select2-selection--single {
        min-height: 44px;
        padding: 0.55rem 0.75rem;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple {
        min-height: 44px;
        padding: 0.25rem 0.5rem;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: #002B6B;
        box-shadow: 0 0 0 0.2rem rgba(0, 43, 107, 0.12);
    }

    /* Tag dosen terpilih (team teaching) */
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
        display: inline-flex;
        align-items: center;
        margin: 2px 4px 2px 0;
        padding: 3px 10px 3px 6px;
        background-color: #E8EEF9;
        border: 1px solid #C7D4EE;
        border-radius: 6px;
        font-size: 0.82rem;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice .select2-selection__choice__display,
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice .select2-selection__choice__display * {
        color: #002B6B !important;
        padding-left: 0.35rem;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        position: static;
        order: -1;
        margin: 0;
        color: #002B6B !important;
        opacity: .7;
    }
    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        opacity: 1;
        background: transparent;
    }

    /* Dropdown hasil */
    .select2-container--bootstrap-5 .select2-results__option--highlighted {
        background-color: #002B6B !important;
        color: #fff !important;
    }
    .select2-container--bootstrap-5 .select2-results__option--selected {
        background-color: #E8EEF9;
        color: #002B6B;
    }
    .select2-container--bootstrap-5 .select2-results__option[aria-disabled="true"] {
        color: #adb5bd;
    }

    /* Pesan error validasi tetap tampil setelah container select2 */
    .select2-container + .invalid-feedback { display: block; }
</style>

{{-- Pakai jQuery milik layout kalau sudah ada, kalau belum baru dimuat --}}
<script>window.jQuery || document.write('<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"><\/script>')</script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const mkList = @json($mataKuliahList);
    const oldMkId = "{{ old('mata_kuliah_id') }}";

    jQuery(function ($) {
        $('#dosen_id').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: '-- Pilih Dosen Utama --',
            allowClear: true
        });

        $('#dosen_pengampu').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih dosen tambahan (opsional)',
            closeOnSelect: false
        });

        // Dosen utama tidak boleh sekaligus menjadi dosen tambahan
        function syncTeam() {
            const main = $('#dosen_id').val();
            $('#dosen_pengampu option').each(function () {
                const same = main && this.value === main;
                $(this).prop('disabled', !!same);
                if (same) this.selected = false;
            });
            $('#dosen_pengampu').trigger('change.select2');
        }
        $('#dosen_id').on('change', syncTeam);
        syncTeam();

        const initialProdiId = document.getElementById('program_studi_id').value;
        if (initialProdiId) updateMataKuliah(initialProdiId, oldMkId);
    });

    function updateMataKuliah(prodiId, selectedMk = null) {
        const mkSelect = document.getElementById('mata_kuliah_id');
        mkSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';

        if (!prodiId) {
            mkSelect.innerHTML = '<option value="">-- Pilih Prodi Terlebih Dahulu --</option>';
            return;
        }

        const filteredMk = mkList.filter(mk => mk.program_studi_id == prodiId);

        if (filteredMk.length === 0) {
            mkSelect.innerHTML = '<option value="">Belum ada mata kuliah di prodi ini</option>';
            return;
        }

        filteredMk.forEach(mk => {
            const option = document.createElement('option');
            option.value = mk.id;
            option.textContent = `[${mk.kode_mk}] ${mk.nama_mk} (Smt ${mk.semester})`;
            if (selectedMk && selectedMk == mk.id) option.selected = true;
            mkSelect.appendChild(option);
        });
    }
</script>
@endpush