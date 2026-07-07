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
                        <label class="form-label">Foto Profil</label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img id="photoPreview" 
                                 src="https://api.dicebear.com/7.x/avataaars/svg?seed=newteacher" 
                                 style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;" alt="Preview">
                            <div>
                                <input type="file" class="form-control" id="photoInput" accept="image/*">
                                <small class="text-muted d-block mt-2">Format: JPG, PNG (Max 2MB)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <!-- NIDN -->
                    <div class="mb-4">
                        <label class="form-label">NIDN <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control" name="nidn" placeholder="Masukkan NIDN" required>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label class="form-label">Email <span style="color: #dc2626;">*</span></label>
                        <input type="email" class="form-control" name="email" placeholder="Masukkan email" required>
                    </div>

                    <!-- No. Telepon -->
                    <div class="mb-4">
                        <label class="form-label">No. Telepon</label>
                        <input type="tel" class="form-control" name="telepon" placeholder="Masukkan nomor telepon">
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
                                <input class="form-check-input" type="radio" name="status" id="statusAktif" value="aktif" checked>
                                <label class="form-check-label" for="statusAktif">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" id="statusNonAktif" value="non-aktif">
                                <label class="form-check-label" for="statusNonAktif">
                                    Non-Aktif
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label class="form-label">Alamat</label>
                        <textarea class="form-control" name="alamat" placeholder="Masukkan alamat" rows="3"></textarea>
                    </div>

                    <!-- Kualifikasi Pendidikan -->
                    <div class="mb-4">
                        <label class="form-label">Kualifikasi Pendidikan</label>
                        <select class="form-select" name="kualifikasi">
                            <option value="">-- Pilih Kualifikasi --</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
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
                    document.getElementById('photoPreview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-admin-layout>
