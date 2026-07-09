@extends('layouts.admin')

@section('title', 'Tambah Program Studi')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Cendekia</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.program-studi.index') }}" class="text-decoration-none text-muted">Program Studi</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Tambah Program Studi</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Tambah Program Studi</h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Daftarkan entitas program studi baru ke dalam sistem akademik.</p>
    </div>

    <form action="{{ route('admin.program-studi.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-plus-circle-fill text-primary" style="color: #002B6B !important;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Formulir Program Studi Baru</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="kode_prodi" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Kode Prodi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_prodi') is-invalid @enderror" id="kode_prodi" name="kode_prodi" value="{{ old('kode_prodi') }}" placeholder="Contoh: IF101" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('kode_prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="nama_prodi" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nama Program Studi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_prodi') is-invalid @enderror" id="nama_prodi" name="nama_prodi" value="{{ old('nama_prodi') }}" placeholder="Contoh: Teknik Informatika" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('nama_prodi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="jenjang" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Jenjang Pendidikan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenjang') is-invalid @enderror" id="jenjang" name="jenjang" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                                <option value="" disabled selected>Pilih Jenjang</option>
                                <option value="D3" {{ old('jenjang') == 'D3' ? 'selected' : '' }}>D3 - Ahli Madya</option>
                                <option value="D4" {{ old('jenjang') == 'D4' ? 'selected' : '' }}>D4 - Sarjana Terapan</option>
                                <option value="S1" {{ old('jenjang') == 'S1' ? 'selected' : '' }}>S1 - Sarjana</option>
                                <option value="S2" {{ old('jenjang') == 'S2' ? 'selected' : '' }}>S2 - Magister</option>
                            </select>
                            @error('jenjang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="akreditasi" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Akreditasi</label>
                            <select class="form-select @error('akreditasi') is-invalid @enderror" id="akreditasi" name="akreditasi" style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                                <option value="Unggul" {{ old('akreditasi') == 'Unggul' ? 'selected' : '' }}>Unggul</option>
                                <option value="A" {{ old('akreditasi') == 'A' ? 'selected' : '' }}>A</option>
                                <option value="B" {{ old('akreditasi') == 'B' ? 'selected' : '' }}>B</option>
                                <option value="Baik" {{ old('akreditasi', 'Baik') == 'Baik' ? 'selected' : '' }}>Baik</option>
                            </select>
                            @error('akreditasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-3 mb-4 text-white" style="border-radius: 12px; background: linear-gradient(135deg, #0f172a, #1e293b); min-height: 140px; position: relative; overflow: hidden;">
                    <div class="position-absolute end-0 top-0 translate-middle-y opacity-10" style="font-size: 6rem; pointer-events: none; transform: rotate(-15deg);">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div class="text-start mb-2">
                        <span class="badge bg-white bg-opacity-20 text-uppercase tracking-wider fw-bold px-2 py-1" style="font-size: 0.65rem; border-radius: 4px;">PREVIEW</span>
                    </div>
                    <h4 class="fw-bold m-0 tracking-wide text-start text-truncate" id="preview_kode">PRODI</h4>
                    <p class="small text-start text-white-50 text-truncate mb-3" id="preview_nama">Nama Program Studi</p>
                    
                    <div class="d-flex justify-content-between align-items-center bg-white bg-opacity-10 px-2 py-1.5 rounded-2" style="font-size: 0.75rem;">
                        <span>Jenjang: <strong id="preview_jenjang">-</strong></span>
                        <span>Akreditasi: <strong id="preview_akreditasi">Baik</strong></span>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <label class="form-label fw-semibold small text-muted text-uppercase d-block mb-3" style="letter-spacing: 0.5px;">Status Departemen</label>
                    
                    <div class="btn-group w-100 p-1 bg-light rounded-3" role="group" style="border: 1px solid #e2e8f0;">
                        <input type="radio" class="btn-check" name="status" id="status_aktif" value="1" checked>
                        <label class="btn btn-outline-primary border-0 fw-semibold py-2 text-center" for="status_aktif" style="border-radius: 6px; font-size: 0.85rem;">Aktif</label>

                        <input type="radio" class="btn-check" name="status" id="status_nonaktif" value="0">
                        <label class="btn btn-outline-primary border-0 fw-semibold py-2 text-center" for="status_nonaktif" style="border-radius: 6px; font-size: 0.85rem;">Nonaktif</label>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2" style="background-color: #002B6B; border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                        <i class="bi bi-check-lg"></i> Simpan Departemen
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
    .btn-outline-primary {
        color: #64748b;
        background-color: transparent;
    }
    .btn-outline-primary:hover {
        background-color: rgba(0, 43, 107, 0.05);
        color: #002B6B;
    }
</style>
@endpush

@push('scripts')
<script>
    // Live Binding Preview Element Sync
    const kodi = document.getElementById('kode_prodi');
    const nama = document.getElementById('nama_prodi');
    const jenj = document.getElementById('jenjang');
    const akre = document.getElementById('akreditasi');

    const pk = document.getElementById('preview_kode');
    const pn = document.getElementById('preview_nama');
    const pj = document.getElementById('preview_jenjang');
    const pa = document.getElementById('preview_akreditasi');

    kodi.addEventListener('input', (e) => { pk.textContent = e.target.value.toUpperCase() || 'PRODI'; });
    nama.addEventListener('input', (e) => { pn.textContent = e.target.value || 'Nama Program Studi'; });
    jenj.addEventListener('change', (e) => { pj.textContent = e.target.value || '-'; });
    akre.addEventListener('change', (e) => { pa.textContent = e.target.value || 'Baik'; });
</script>
@endpush