@extends('layouts.admin')

@section('title', 'Edit Tahun Akademik')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tahun-akademik.index') }}" class="text-decoration-none text-muted">Tahun Akademik</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Edit Semester</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Edit Tahun Akademik</h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Perbarui data periode semester akademik.</p>
    </div>

    <form action="{{ route('admin.tahun-akademik.update', $tahun_akademik->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-calendar-event-fill text-primary" style="color: #002B6B !important;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Informasi Semester</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nama_semester" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nama Semester <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_semester') is-invalid @enderror" id="nama_semester" name="nama_semester" value="{{ old('nama_semester', $tahun_akademik->nama_semester) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                            @error('nama_semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tahun_ajaran" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tahun Ajaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('tahun_ajaran') is-invalid @enderror" id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', $tahun_akademik->tahun_ajaran) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                            @error('tahun_ajaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jenis" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Jenis Semester <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis') is-invalid @enderror" id="jenis" name="jenis" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                                <option value="Ganjil" {{ old('jenis', $tahun_akademik->jenis) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                <option value="Genap" {{ old('jenis', $tahun_akademik->jenis) == 'Genap' ? 'selected' : '' }}>Genap</option>
                            </select>
                            @error('jenis')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-clock-history text-primary" style="color: #002B6B !important;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Periode Pelaksanaan</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tanggal_mulai" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai', $tahun_akademik->tanggal_mulai ? $tahun_akademik->tanggal_mulai->format('Y-m-d') : '') }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="tanggal_selesai" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai', $tahun_akademik->tanggal_selesai ? $tahun_akademik->tanggal_selesai->format('Y-m-d') : '') }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px;">
                    <label class="form-label fw-semibold small text-muted text-uppercase d-block mb-3" style="letter-spacing: 0.5px;">Status Semester</label>
                    <div class="form-check form-switch fs-5 mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $tahun_akademik->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label ms-2 fs-6 mt-1 text-dark" for="is_active">Jadikan Semester Aktif</label>
                    </div>
                    <small class="text-muted" style="font-size: 0.8rem;">Jika dicentang, semester yang sedang aktif saat ini akan otomatis dinonaktifkan.</small>
                </div>

                <div class="d-flex flex-column gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2" style="background-color: #002B6B; border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                        <i class="bi bi-save"></i> Perbarui Data
                    </button>
                    <a href="{{ route('admin.tahun-akademik.index') }}" class="btn btn-light border w-100 py-2.5 fw-semibold text-secondary" style="border-radius: 8px;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
