<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div>
                <h1 class="page-title mb-0">Tambah Dosen Baru</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.dosen.index') }}">Data Dosen</a></li>
                        <li class="breadcrumb-item active">Tambah Dosen</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Form Card -->
        <div class="table-card" style="max-width: 800px;">
            <form method="POST" action="{{ route('admin.dosen.store') }}" enctype="multipart/form-data">
                @csrf

                <div style="padding: 2rem;">
                    <!-- Foto -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Foto Profil</label>
                        <div style="display: flex; align-items: center; gap: 1.5rem;">
                            <x-user-avatar name="Dosen" :size="85" id="photoPreview" />
                            <div class="flex-1">
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" id="photoInput" name="foto" accept="image/*">
                                <small class="text-muted d-block mt-2">Format gambar (Maksimal 10 MB). Opsional.</small>
                                @error('foto') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                        @error('name') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- NIP (dipakai sebagai identitas login) -->
                    <div class="mb-4">
                        <label class="form-label">NIP <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control" name="nip_nim" value="{{ old('nip_nim') }}" placeholder="Masukkan NIP" required>
                        @error('nip_nim') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="form-label">Email <span style="color: #dc2626;">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
                        @error('email') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- No. Telepon / Kontak -->
                    <div class="mb-4">
                        <label class="form-label">No. Telepon / Kontak</label>
                        <input type="tel" class="form-control @error('telepon') is-invalid @enderror" name="telepon" value="{{ old('telepon') }}" placeholder="Contoh: 081234567890">
                        <small class="text-muted d-block mt-1">Nomor kontak aktif dosen (WhatsApp/Telepon). Opsional.</small>
                        @error('telepon') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                    </div>

                    <!-- Program Studi -->
                    <div class="mb-4">
                        <label class="form-label">Program Studi <span style="color: #dc2626;">*</span></label>
                        <select class="form-select" name="program_studi_id">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudiList as $programStudi)
                            <option value="{{ $programStudi->id }}">{{ $programStudi->nama_prodi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusAktif" value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusAktif">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="non_aktif" {{ old('status') == 'non_aktif' ? 'checked' : '' }}>
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
                            <i class="bi bi-check-circle"></i> Simpan
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
                    const preview = document.getElementById('photoPreview');
                    preview.src = event.target.result;
                    preview.dataset.fallback = preview.src;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-admin-layout>