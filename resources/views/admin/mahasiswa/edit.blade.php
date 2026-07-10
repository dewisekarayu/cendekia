<x-admin-layout>
    <div class="container-fluid">
        <div class="mb-4">
            <div>
                <h1 class="page-title mb-0">Edit Data Mahasiswa</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mahasiswa.index') }}">Data Mahasiswa</a></li>
                        <li class="breadcrumb-item active">Edit Mahasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="table-card" style="max-width: 800px; background: white; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.05);">
            <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswaMember->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="padding: 2rem;">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Foto Profil</label>
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <img id="photoPreview"
                                src="{{ $mahasiswaMember->foto ? asset('storage/' . $mahasiswaMember->foto) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($mahasiswaMember->name) }}"
                                style="width: 85px; height: 85px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; background-color: #f1f5f9;" alt="Preview">
                            <div class="flex-1">
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" id="photoInput" name="foto" accept="image/*">
                                <small class="text-muted d-block mt-2">Format: JPG, PNG (Max 2MB). Kosongkan jika tidak ingin mengganti foto profil.</small>
                                @error('foto') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">NIM <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nim') is-invalid @enderror" name="nim" value="{{ old('nim', $mahasiswaMember->nip_nim) }}" placeholder="Masukkan NIM" required style="border-radius: 8px; padding: 0.6rem 0.8rem;">
                            @error('nim') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $mahasiswaMember->name) }}" placeholder="Masukkan nama lengkap" required style="border-radius: 8px; padding: 0.6rem 0.8rem;">
                            @error('nama') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $mahasiswaMember->email) }}" placeholder="Masukkan email" required style="border-radius: 8px; padding: 0.6rem 0.8rem;">
                            @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">No. Telepon</label>
                            <input type="tel" class="form-control @error('telepon') is-invalid @enderror" name="telepon" value="{{ old('telepon', $mahasiswaMember->telepon) }}" placeholder="Masukkan nomor telepon" style="border-radius: 8px; padding: 0.6rem 0.8rem;">
                            @error('telepon') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Program Studi <span class="text-danger">*</span></label>
                        <select class="form-select @error('program_studi_id') is-invalid @enderror" name="program_studi_id" style="border-radius: 8px; padding: 0.6rem 0.8rem;">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudiList as $programStudi)
                                <option value="{{ $programStudi->id }}" {{ old('program_studi_id', $mahasiswaMember->program_studi_id) == $programStudi->id ? 'selected' : '' }}>
                                    {{ $programStudi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_id') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase d-block mb-2">Status</label>
                        <div class="d-flex gap-4 bg-light p-3 rounded-3 border border-slate-100">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusAktif" value="aktif" {{ old('status', $mahasiswaMember->status) == 'aktif' ? 'checked' : '' }}>
                                <label class="form-check-label font-medium text-slate-700" for="statusAktif">Aktif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusCuti" value="cuti" {{ old('status', $mahasiswaMember->status) == 'cuti' ? 'checked' : '' }}>
                                <label class="form-check-label font-medium text-slate-700" for="statusCuti">Cuti</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="non_aktif" {{ old('status', $mahasiswaMember->status) == 'non_aktif' ? 'checked' : '' }}>
                                <label class="form-check-label font-medium text-slate-700" for="statusNonAktif">Non-Aktif</label>
                            </div>
                        </div>
                        @error('status') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <div class="d-flex gap-2.5 mt-5">
                        <button type="submit" class="btn btn-primary px-4 py-2 font-medium d-flex align-items-center gap-2" style="background-color: #002B6B; border: none; border-radius: 8px;">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-secondary px-4 py-2 font-medium" style="border-radius: 8px;">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('photoPreview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    @endpush
</x-admin-layout>