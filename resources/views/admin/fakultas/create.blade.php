@extends('layouts.admin')

@section('title', 'Tambah Fakultas')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Cendekia</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.fakultas.index') }}" class="text-decoration-none text-muted">Fakultas</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Tambah Fakultas</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Tambah Fakultas</h1>
    </div>

    <form action="{{ route('admin.fakultas.store') }}" method="POST">
        @csrf
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white; max-width: 800px;">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="kode_fakultas" class="form-label fw-semibold small text-muted">Kode Fakultas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('kode_fakultas') is-invalid @enderror" id="kode_fakultas" name="kode_fakultas" value="{{ old('kode_fakultas') }}" placeholder="Contoh: FT" required>
                    @error('kode_fakultas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8">
                    <label for="nama_fakultas" class="form-label fw-semibold small text-muted">Nama Fakultas <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_fakultas') is-invalid @enderror" id="nama_fakultas" name="nama_fakultas" value="{{ old('nama_fakultas') }}" placeholder="Contoh: Fakultas Teknik" required>
                    @error('nama_fakultas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">Simpan Fakultas</button>
                <a href="{{ route('admin.fakultas.index') }}" class="btn btn-light border px-4">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
