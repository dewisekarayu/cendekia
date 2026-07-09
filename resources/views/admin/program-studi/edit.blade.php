@extends('layouts.admin')

@section('title', 'Edit Program Studi')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Cendekia</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.program-studi.index') }}" class="text-decoration-none text-muted">Program Studi</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Edit Program Studi</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Edit Program Studi</h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Perbarui data departemen program studi akademik.</p>
    </div>

    <form action="{{ route('admin.program-studi.update', $prodi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-pencil-square text-primary" style="color: #002B6B !important;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Formulir Perubahan Data</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="kode_prodi" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Kode Prodi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_prodi') is-invalid @enderror" id="kode_prodi" name="kode_prodi" value="{{ old('kode_prodi', $prodi->kode_prodi) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                            @error('kode_prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="nama_prodi" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nama Program Studi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_prodi') is-invalid @enderror" id="nama_prodi" name="nama_prodi" value="{{ old('nama_prodi', $prodi->nama_prodi) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                            @error('nama_prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jenjang" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Jenjang Pendidikan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenjang') is-invalid @enderror" id="jenjang" name="jenjang" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                                @foreach (['D3', 'D4', 'S1', 'S2'] as $jenj)
                                    <option value="{{ $jenj }}" {{ old('jenjang', $prodi->jenjang) === $jenj ? 'selected' : '' }}>{{ $jenj }}</option>
                                @endforeach
                            </select>
                            @error('jenjang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="akreditasi" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Akreditasi <span class="text-danger">*</span></label>
                            <select class="form-select" id="akreditasi" name="akreditasi" style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                                @foreach (['Unggul', 'A', 'B', 'Baik'] as $akre)
                                    <option value="{{ $akre }}" {{ old('akreditasi', $prodi->akreditasi) === $akre ? 'selected' : '' }}>{{ $akre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <label class="form-label fw-semibold small text-muted text-uppercase d-block mb-3" style="letter-spacing: 0.5px;">Status Departemen</label>
                    <div class="btn-group w-100 p-1 bg-light rounded-3" role="group" style="border: 1px solid #e2e8f0;">
                        <input type="radio" class="btn-check" name="status" id="status_aktif" value="1" {{ old('status', $prodi->status) == 1 ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary border-0 fw-semibold py-2 text-center" for="status_aktif" style="border-radius: 6px; font-size: 0.85rem;">Aktif</label>

                        <input type="radio" class="btn-check" name="status" id="status_nonaktif" value="0" {{ old('status', $prodi->status) == 0 ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary border-0 fw-semibold py-2 text-center" for="status_nonaktif" style="border-radius: 6px; font-size: 0.85rem;">Nonaktif</label>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2" style="background-color: #002B6B; border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                        <i class="bi bi-save"></i> Perbarui Data
                    </button>
                    <a href="{{ route('admin.program-studi.index') }}" class="btn btn-light border w-100 py-2.5 fw-semibold text-secondary" style="border-radius: 8px; background-color: white;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .btn-check:checked + .btn-outline-primary {
        background-color: #002B6B !important;
        color: white !important;
        box-shadow: 0 2px 6px rgba(0, 43, 107, 0.2);
    }
    .btn-outline-primary { color: #64748b; background-color: transparent; }
    .btn-outline-primary:hover { background-color: rgba(0, 43, 107, 0.05); color: #002B6B; }
</style>
@endpush