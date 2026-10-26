<x-admin-layout>
    <div class="container-fluid">
        <div class="mb-4">
            <div>
                <h1 class="page-title mb-0">Tambah Mahasiswa Baru</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.mahasiswa.index') }}">Data Mahasiswa</a></li>
                        <li class="breadcrumb-item active">Tambah Mahasiswa</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="table-card" style="max-width: 800px;">
            <form method="POST" action="{{ route('admin.mahasiswa.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div style="padding: 2rem;">
                    <div class="mb-4">
                        <label class="form-label">Foto Profil</label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <x-user-avatar name="Mahasiswa" :size="80" id="photoPreview" />
                            <div>
                                <input type="file" class="form-control" id="photoInput" name="foto" accept="image/*">
                                <small class="text-muted d-block mt-2">Format gambar (Maksimal 10 MB).</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">NIM <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control @error('nim') is-invalid @enderror" name="nim" value="{{ old('nim') }}" placeholder="Masukkan NIM" required>
                        @error('nim')
                            <div class="invalid-feedback d-block text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama lengkap" required>
                        @error('nama')
                            <div class="invalid-feedback d-block text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Email <span style="color: #dc2626;">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required>
                        @error('email')
                            <div class="invalid-feedback d-block text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">No. Telepon</label>
                        <input type="tel" class="form-control" name="telepon" value="{{ old('telepon') }}" placeholder="Masukkan nomor telepon">
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Program Studi <span style="color: #dc2626;">*</span></label>
                        <select class="form-select @error('program_studi_id') is-invalid @enderror" name="program_studi_id">
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($programStudiList as $programStudi)
                                <option value="{{ $programStudi->id }}" {{ old('program_studi_id') == $programStudi->id ? 'selected' : '' }}>
                                    {{ $programStudi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_id')
                            <div class="invalid-feedback d-block text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

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
                                <input class="form-check-input" type="radio" name="status" id="statusCuti" value="cuti" {{ old('status') == 'cuti' ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusCuti">
                                    Cuti
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

                    <!-- Opsi Otomatisasi Kelas Perkuliahan -->
                    <div class="mb-4 p-3 rounded-3" style="background-color: #f8fafc; border: 1.5px solid #e2e8f0;">
                        <div class="form-check form-switch mb-1 d-flex align-items-center gap-2">
                            <input class="form-check-input" type="checkbox" role="switch" id="autoEnrollKelas" name="auto_enroll_kelas" value="1" {{ old('auto_enroll_kelas', '1') == '1' ? 'checked' : '' }} style="cursor: pointer; width: 2.4em; height: 1.25em;">
                            <label class="form-check-label fw-bold text-dark mb-0" for="autoEnrollKelas" style="cursor: pointer; font-size: 0.95rem;">
                                <i class="bi bi-magic text-primary me-1"></i> Daftarkan Otomatis ke Kelas Perkuliahan Sesuai Prodi
                            </label>
                        </div>
                        <p class="text-muted small mb-0 mt-2" style="font-size: 0.825rem; line-height: 1.5;">
                            Sistem akan otomatis mendistribusikan mahasiswa baru ke kelas-kelas perkuliahan aktif pada program studi yang dipilih (paket Semester 1) dengan pembagian kuota kelas paralel yang merata.
                        </p>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan
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
        // Photo preview
        document.getElementById('photoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('photoPreview').src = event.target.result;
                    document.getElementById('photoPreview').dataset.fallback = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-admin-layout>