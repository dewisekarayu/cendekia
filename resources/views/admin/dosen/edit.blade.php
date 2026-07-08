@extends('layouts.admin')

@section('title', 'Edit Data Dosen')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Cendekia</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.dosen.index') }}" class="text-decoration-none text-muted">Manajemen Dosen</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Edit Dosen</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Edit Data Dosen</h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">Perbarui data profil, kontak, serta penugasan homebase prodi dosen.</p>
    </div>

    <form action="{{ route('admin.dosen.update', $dosenMember->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-person-badge-fill text-primary" style="color: #002B6B !important;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Identitas & Profil Dosen</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Nama Lengkap & Gelar <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $dosenMember->name) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="nip_nim" class="form-label ...">NIP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nip_nim') is-invalid @enderror" id="nip_nim" name="nip_nim" value="{{ old('nip_nim', $dosenMember->nip_nim) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('nip_nim')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $dosenMember->email) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="kontak" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">No. Handphone / WhatsApp</label>
                            <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak" name="kontak" value="{{ old('kontak', $dosenMember->kontak ?? '+62 812-' . str_pad((string)$dosenMember->id, 4, '0', STR_PAD_LEFT) . '-' . str_pad((string)(($dosenMember->id * 73) % 10000), 4, '0', STR_PAD_LEFT)) }}" style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                            @error('kontak')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <div class="d-flex align-items-center gap-2 mb-4 pb-2 border-bottom">
                        <i class="bi bi-mortarboard-fill text-primary" style="color: #002B6B !important;"></i>
                        <h6 class="m-0 fw-bold text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Homebase & Penugasan</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="program_studi_id" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Program Studi Utama (Homebase)</label>
                            <select class="form-select @error('program_studi_id') is-invalid @enderror" id="program_studi_id" name="program_studi_id" style="border-radius: 8px; padding: 0.65rem 0.75rem; background-color: #F8FAFC;">
                                <option value="">Lintas Prodi / Global</option>
                                @foreach($prodiList ?? [] as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('program_studi_id', $dosenMember->program_studi_id) == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }}
                                </option>
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

                <div class="card border-0 shadow-sm p-4 mb-4 text-center text-white" style="border-radius: 12px; background: linear-gradient(135deg, #0f172a, #1e293b); position: relative; overflow: hidden;">
                    <div class="mb-3 position-relative d-inline-block mx-auto">
                        <img id="avatar_preview" src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($dosenMember->name) }}"
                            style="width: 90px; height: 90px; border-radius: 50%; border: 3px solid rgba(255,255,255,0.2); background-color: #f1f5f9;" alt="Avatar">
                    </div>

                    <h5 class="fw-bold m-0 tracking-wide text-truncate" id="preview_name">{{ $dosenMember->name }}</h5>
                    <p class="small text-white-50 text-truncate mb-0" id="preview_email" style="font-size: 0.8rem;">{{ $dosenMember->email }}</p>
                    <small class="badge mt-2 bg-white bg-opacity-10 py-1.5 px-3" id="preview_nidn" style="font-size: 0.75rem; font-weight: 500; border: 1px solid rgba(255,255,255,0.15);">
                        NIP: {{ $dosenMember->nip_nim }}
                    </small>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; background: white;">
                    <label class="form-label fw-semibold small text-muted text-uppercase d-block mb-3" style="letter-spacing: 0.5px;">Status Mengajar</label>

                    <div class="btn-group w-100 p-1 bg-light rounded-3" role="group" style="border: 1px solid #e2e8f0;">
                        <input type="radio" class="btn-check" name="status" id="status_aktif" value="aktif" {{ old('status', $dosenMember->status ?? 'aktif') == 'aktif' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary border-0 fw-semibold py-2 text-center" for="status_aktif" style="border-radius: 6px; font-size: 0.85rem;">Aktif</label>

                        <input type="radio" class="btn-check" name="status" id="status_nonaktif" value="non_aktif" {{ old('status', $dosenMember->status ?? 'aktif') == 'non_aktif' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary border-0 fw-semibold py-2 text-center" for="status_nonaktif" style="border-radius: 6px; font-size: 0.85rem;">Nonaktif</label>
                    </div>

                    <div class="mt-4 pt-3 border-top" style="font-size: 0.8rem; color: #64748b;">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Terdaftar sejak:</span>
                            <span class="fw-medium text-dark">{{ $dosenMember->created_at ? $dosenMember->created_at->format('d M Y') : '18 Agu 2025' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Pembaruan terakhir:</span>
                            <span class="fw-medium text-dark">Hari ini</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2 mt-4">
                    <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold d-flex align-items-center justify-content-center gap-2" style="background-color: #002B6B; border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                        <i class="bi bi-save"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.dosen.index') }}" class="btn btn-light border w-100 py-2.5 fw-semibold text-secondary" style="border-radius: 8px; background-color: white;">
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
    /* Radio toggle custom styles */
    .btn-check:checked+.btn-outline-primary {
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
    // Live text syncing to the card component layout
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const nidnInput = document.getElementById('nip_nim');
    
    const pName = document.getElementById('preview_name');
    const pEmail = document.getElementById('preview_email');
    const pNidn = document.getElementById('preview_nidn');
    const avatarImg = document.getElementById('avatar_preview');

    nameInput.addEventListener('input', (e) => {
        const val = e.target.value;
        pName.textContent = val || 'Nama Dosen';
        if (val.trim() !== "") {
            // Update avatar seed dynamically based on the name change
            avatarImg.src = `https://api.dicebear.com/7.x/avataaars/svg?seed=${encodeURIComponent(val)}`;
        }
    });

    emailInput.addEventListener('input', (e) => {
        pEmail.textContent = e.target.value || 'email@universitas.ac.id';
    });

    nidnInput.addEventListener('input', (e) => {
        pNidn.textContent = e.target.value ? `NIP: ${e.target.value}` : 'NIP: -';
    });
</script>
@endpush