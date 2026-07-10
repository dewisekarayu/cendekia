<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div>
                <h1 class="page-title mb-0">Edit Data Dosen</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.dosen.index') }}">Data Dosen</a></li>
                        <li class="breadcrumb-item active">Edit Dosen</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Form Card -->
        <div class="table-card" style="max-width: 800px;">
            <form method="POST" action="{{ route('admin.dosen.update', $dosenMember->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="padding: 2rem;">
                    <!-- Foto -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Foto Profil</label>
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <img id="photoPreview"
                                src="{{ $dosenMember->foto ? asset('storage/' . $dosenMember->foto) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($dosenMember->name) }}"
                                style="width: 85px; height: 85px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; background-color: #f1f5f9;" alt="Preview">
                            <div class="flex-1">
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" id="photoInput" name="foto" accept="image/*">
                                <small class="text-muted d-block mt-2">Format: JPG, PNG (Max 2MB). Kosongkan jika tidak ingin mengganti foto profil.</small>
                                @error('foto') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $dosenMember->name) }}" placeholder="Masukkan nama lengkap" required>
                        @error('name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- NIP (dipakai sebagai identitas login) -->
                    <div class="mb-4">
                        <label class="form-label">NIP <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control @error('nip_nim') is-invalid @enderror" name="nip_nim" value="{{ old('nip_nim', $dosenMember->nip_nim) }}" placeholder="Masukkan NIP" required>
                        @error('nip_nim') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="form-label">Email <span style="color: #dc2626;">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $dosenMember->email) }}" placeholder="Masukkan email" required>
                        @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Program Studi -->
                    <div class="mb-4">
                        <label class="form-label">Program Studi <span style="color: #dc2626;">*</span></label>
                        <select class="form-select @error('program_studi_id') is-invalid @enderror" name="program_studi_id">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($prodiList as $prodi)
                            <option value="{{ $prodi->id }}" {{ old('program_studi_id', $dosenMember->program_studi_id) == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                            @endforeach
                        </select>
                        @error('program_studi_id') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusAktif" value="aktif" {{ old('status', $dosenMember->status ?? 'aktif') == 'aktif' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusAktif">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="non_aktif" {{ old('status', $dosenMember->status ?? 'aktif') == 'non_aktif' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusNonAktif">
                                    Non-Aktif
                                </label>
                            </div>
                        </div>
                        @error('status') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Update
                        </button>
                        <a href="{{ route('admin.dosen.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Photo preview
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
</x-admin-layout>