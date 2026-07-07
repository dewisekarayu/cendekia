<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
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

        <!-- Form Card -->
        <div class="table-card" style="max-width: 800px;">
            <form method="POST" action="{{ route('admin.mahasiswa.store') }}" enctype="multipart/form-data">
                @csrf
                
                <div style="padding: 2rem;">
                    <!-- Foto -->
                    <div class="mb-4">
                        <label class="form-label">Foto Profil</label>
                        <div style="display: flex; align-items: center; gap: 1rem;">
                            <img id="photoPreview" 
                                 src="https://api.dicebear.com/7.x/avataaars/svg?seed=newstudent" 
                                 style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;" alt="Preview">
                            <div>
                                <input type="file" class="form-control" id="photoInput" accept="image/*">
                                <small class="text-muted d-block mt-2">Format: JPG, PNG (Max 2MB)</small>
                            </div>
                        </div>
                    </div>

                    <!-- NIM -->
                    <div class="mb-4">
                        <label class="form-label">NIM <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control" name="nim" placeholder="Masukkan NIM" required>
                    </div>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label class="form-label">Nama Lengkap <span style="color: #dc2626;">*</span></label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap" required>
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

                    <!-- Semester -->
                    <div class="mb-4">
                        <label class="form-label">Semester <span style="color: #dc2626;">*</span></label>
                        <select class="form-select" name="semester" required>
                            <option value="">-- Pilih Semester --</option>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>
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
                                <input class="form-check-input" type="radio" name="status" id="statusCuti" value="cuti">
                                <label class="form-check-label" for="statusCuti">
                                    Cuti
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

                    <!-- Tanggal Lahir -->
                    <div class="mb-4">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" class="form-control" name="tanggal_lahir">
                    </div>

                    <!-- Buttons -->
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
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-admin-layout>
