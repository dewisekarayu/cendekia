@extends('layouts.admin')

@section('title', 'Tambah Kurikulum')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Cendekia</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.kurikulum.index') }}" class="text-decoration-none text-muted">Kurikulum</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Tambah Kurikulum</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Tambah Kurikulum</h1>
    </div>

    <form action="{{ route('admin.kurikulum.store') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; max-width: 800px;">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="program_studi_id" class="form-label fw-semibold small text-muted">Program Studi <span class="text-danger">*</span></label>
                    <select class="form-select @error('program_studi_id') is-invalid @enderror" id="program_studi_id" name="program_studi_id" required>
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($programStudiList as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                    @error('program_studi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="nama_kurikulum" class="form-label fw-semibold small text-muted">Nama Kurikulum <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_kurikulum') is-invalid @enderror" id="nama_kurikulum" name="nama_kurikulum" value="{{ old('nama_kurikulum') }}" placeholder="Contoh: Kurikulum MBKM 2024" required>
                    @error('nama_kurikulum') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label for="tahun_mulai" class="form-label fw-semibold small text-muted">Tahun Mulai <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('tahun_mulai') is-invalid @enderror" id="tahun_mulai" name="tahun_mulai" value="{{ old('tahun_mulai') }}" placeholder="Contoh: 2024" required>
                    @error('tahun_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Status Aktif</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">Simpan Kurikulum</button>
                <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-light border px-4">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
