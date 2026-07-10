@extends('layouts.admin')

@section('title', 'Edit Mata Kuliah')

@section('content')
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Master Data</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.mata-kuliah.index') }}" class="text-decoration-none text-muted">Mata Kuliah</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Edit Mata Kuliah</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Edit Mata Kuliah</h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Perbarui detail informasi untuk mata kuliah {{ $mk->nama_mk }}.</p>
    </div>

    <form action="{{ route('admin.mata-kuliah.update', $mk->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-info-circle-fill text-primary" style="color: #002B6B !important;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Informasi Dasar</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="kode_mk" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Kode Mata Kuliah</label>
                            <input type="text" class="form-control @error('kode_mk') is-invalid @enderror" id="kode_mk" name="kode_mk" value="{{ old('kode_mk', $mk->kode_mk) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('kode_mk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nama_mk" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nama Mata Kuliah</label>
                            <input type="text" class="form-control @error('nama_mk') is-invalid @enderror" id="nama_mk" name="nama_mk" value="{{ old('nama_mk', $mk->nama_mk) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('nama_mk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sks" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Bobot SKS</label>
                            <select class="form-select @error('sks') is-invalid @enderror" id="sks" name="sks" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                                @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ old('sks', $mk->sks) == $i ? 'selected' : '' }}>{{ $i }} SKS</option>
                                @endfor
                            </select>
                            @error('sks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="semester_ke" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Semester</label>
                            <select class="form-select @error('semester_ke') is-invalid @enderror" id="semester_ke" name="semester_ke" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('semester_ke', $mk->semester_ke) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                                @endfor
                            </select>
                            @error('semester_ke')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="deskripsi" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Deskripsi Mata Kuliah</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4" style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;" placeholder="Jelaskan deskripsi ringkas program kuliah di sini...">{{ old('deskripsi', $mk->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-diagram-3-fill text-primary" style="color: #002B6B !important;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kurikulum</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="program_studi_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Program Studi</label>
                            <select class="form-select @error('program_studi_id') is-invalid @enderror" id="program_studi_id" name="program_studi_id" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                                <option value="" disabled>Pilih Program Studi</option>
                                @foreach($prodiList as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('program_studi_id', $mk->program_studi_id) == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                                @endforeach
                            </select>
                            @error('program_studi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-3 mb-4 text-white" style="border-radius: 12px; background: linear-gradient(135deg, #1e293b, #0f172a); min-height: 110px; position: relative; overflow: hidden;">
                    <div class="position-absolute end-0 top-0 translate-middle-y opacity-10" style="font-size: 6rem; pointer-events: none; transform: rotate(-15deg);">
                        <i class="bi bi-book-half"></i>
                    </div>
                    <div class="text-start mb-2">
                        <span class="badge bg-white bg-opacity-20 text-uppercase tracking-wider fw-bold px-2 py-1" style="font-size: 0.65rem; border-radius: 4px;">PREVIEW</span>
                    </div>
                    <h4 class="fw-bold m-0 tracking-wide text-start text-truncate" id="preview_code">{{ $mk->kode_mk }}</h4>
                    <p class="small text-start text-white-50 text-truncate mb-0" id="preview_title">{{ $mk->nama_mk }}</p>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div style="font-size: 0.8rem; color: #64748b;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Dibuat pada:</span>
                            <span class="fw-medium text-dark">{{ $mk->created_at ? $mk->created_at->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Terakhir diubah:</span>
                            <span class="fw-medium text-dark">{{ $mk->updated_at ? $mk->updated_at->format('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2" style="background-color: #002B6B; border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.mata-kuliah.index') }}" class="btn btn-light border w-100 py-2.5 fw-semibold text-secondary" style="border-radius: 8px; background-color: white;">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const codeInput = document.getElementById('kode_mk');
    const titleInput = document.getElementById('nama_mk');
    const previewCode = document.getElementById('preview_code');
    const previewTitle = document.getElementById('preview_title');

    if(codeInput && previewCode) {
        codeInput.addEventListener('input', (e) => { previewCode.textContent = e.target.value.toUpperCase() || 'KODE MK'; });
    }
    if(titleInput && previewTitle) {
        titleInput.addEventListener('input', (e) => { previewTitle.textContent = e.target.value || 'Nama Mata Kuliah'; });
    }
</script>
@endpush