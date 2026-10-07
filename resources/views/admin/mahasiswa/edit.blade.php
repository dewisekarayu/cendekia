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

        <div class="table-card" style="max-width: 800px;">
            <form method="POST" action="{{ route('admin.mahasiswa.update', $mahasiswaMember->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div style="padding: 2rem;">
                    <!-- Foto -->
                    <div class="mb-4">
                        <label class="form-label">Foto Profil</label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img id="photoPreview"
                                src="{{ $mahasiswaMember->foto ? asset('storage/' . $mahasiswaMember->foto) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($mahasiswaMember->name) }}"
                                style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; background-color: #f1f5f9;" alt="Preview">
                            <div>
                                <input type="file" class="form-control" id="photoInput" name="foto" accept="image/*">
                                <small class="text-muted d-block mt-2">Format: JPG, PNG (Max 2MB). Kosongkan jika tidak ingin mengganti foto.</small>
                                @error('foto') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- NIM -->
                    <div class="mb-4">
                        <label class="form-label">NIM <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control @error('nim') is-invalid @enderror" name="nim" value="{{ old('nim', $mahasiswaMember->nip_nim) }}" placeholder="Masukkan NIM" required>
                        @error('nim') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Nama -->
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama', $mahasiswaMember->name) }}" placeholder="Masukkan nama lengkap" required>
                        @error('nama') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="form-label">Email <span style="color: #dc2626;">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $mahasiswaMember->email) }}" placeholder="Masukkan email" required>
                        @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Telepon -->
                    <div class="mb-4">
                        <label class="form-label">No. Telepon</label>
                        <input type="tel" class="form-control @error('telepon') is-invalid @enderror" name="telepon" value="{{ old('telepon', $mahasiswaMember->telepon) }}" placeholder="Masukkan nomor telepon">
                        @error('telepon') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Program Studi -->
                    <div class="mb-4">
                        <label class="form-label">Program Studi <span style="color: #dc2626;">*</span></label>
                        <select class="form-select @error('program_studi_id') is-invalid @enderror" name="program_studi_id">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudiList as $programStudi)
                            <option value="{{ $programStudi->id }}" {{ old('program_studi_id', $mahasiswaMember->program_studi_id) == $programStudi->id ? 'selected' : '' }}>
                                {{ $programStudi->nama_prodi }}
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
                                <input class="form-check-input" type="radio" name="status" id="statusAktif" value="aktif" {{ old('status', $mahasiswaMember->status) == 'aktif' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusAktif">Aktif</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusCuti" value="cuti" {{ old('status', $mahasiswaMember->status) == 'cuti' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusCuti">Cuti</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="non_aktif" {{ old('status', $mahasiswaMember->status) == 'non_aktif' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusNonAktif">Non-Aktif</label>
                            </div>
                        </div>
                        @error('status') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Buttons -->
                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
</x-admin-layout>