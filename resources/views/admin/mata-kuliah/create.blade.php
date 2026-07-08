@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')

@section('content')
    <!-- Header Section -->
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Master Data</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.mata-kuliah.index') }}" class="text-decoration-none text-muted">Mata Kuliah</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Mata Kuliah</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1">Tambah Mata Kuliah</h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Tambahkan data mata kuliah baru ke dalam sistem kurikulum.</p>
    </div>

    <!-- Form Card -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; max-width: 800px;">
        <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
            <i class="bi bi-plus-circle-fill text-primary fs-5"></i>
            <h5 class="m-0 fw-bold">Formulir Mata Kuliah Baru</h5>
        </div>

        <form action="{{ route('admin.mata-kuliah.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <!-- Kode MK -->
                <div class="col-md-4">
                    <label for="kode_mk" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">
                        Kode MK <span class="text-danger">*</span>
                    </label>

                    <div class="input-group">
                        @php
                            $prefix = ['CS', 'SE', 'MT', 'IF', 'TI', 'SI'];
                            $kodeMk = $prefix[array_rand($prefix)] . '-' . rand(100,999);
                        @endphp

                        <input
                            type="text"
                            class="form-control"
                            name="kode_mk"
                            value="{{ old('kode_mk', $kodeMk) }}"
                            readonly
                        >

                        @error('kode_mk')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>


                <!-- Nama MK -->
                <div class="col-md-8">
                    <label for="nama_mk" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nama Mata Kuliah <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_mk') is-invalid @enderror" id="nama_mk" name="nama_mk" value="{{ old('nama_mk') }}" placeholder="Contoh: Algoritma & Struktur Data" required style="border-radius: 8px; padding: 0.6rem 0.75rem;">
                    @error('nama_mk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- SKS -->
                <div class="col-md-6">
                    <label for="sks" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Jumlah SKS <span class="text-danger">*</span></label>
                    <select class="form-select @error('sks') is-invalid @enderror" id="sks" name="sks" required style="border-radius: 8px; padding: 0.6rem 0.75rem;">
                        <option value="" disabled selected>Pilih SKS</option>
                        @for ($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}" {{ old('sks') == $i ? 'selected' : '' }}>{{ $i }} SKS</option>
                        @endfor
                    </select>
                    @error('sks')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Semester -->
                <div class="col-md-6">
                    <label for="semester" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Semester <span class="text-danger">*</span></label>
                    <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester" required style="border-radius: 8px; padding: 0.6rem 0.75rem;">
                        <option value="" disabled selected>Pilih Semester</option>
                        @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" {{ old('semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                        @endfor
                    </select>
                    @error('semester')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Program Studi -->
                <div class="col-md-12">
                    <label for="program_studi_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Program Studi <span class="text-danger">*</span></label>
                    <select class="form-select @error('program_studi_id') is-invalid @enderror" id="program_studi_id" name="program_studi_id" required style="border-radius: 8px; padding: 0.6rem 0.75rem;">
                        <option value="" disabled selected>Pilih Program Studi</option>
                        @foreach($programStudis ?? [] as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                    @error('program_studi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Dosen Pengampu -->
                <div class="col-md-12">
                    <label for="dosen_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Dosen Pengampu <span class="text-muted">(Opsional)</span></label>
                    <select class="form-select @error('dosen_id') is-invalid @enderror" id="dosen_id" name="dosen_id" style="border-radius: 8px; padding: 0.6rem 0.75rem;">
                        <option value="" selected>Belum ditentukan / Pilih Dosen Pengampu</option>
                        @foreach($dosens ?? [] as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>{{ $dosen->nama }}</option>
                        @endforeach
                    </select>
                    @error('dosen_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('admin.mata-kuliah.index') }}" class="btn btn-light border px-4 py-2 fw-semibold text-secondary" style="border-radius: 8px;">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold" style="background-color: #0d6efd; border-radius: 8px;">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
@endsection