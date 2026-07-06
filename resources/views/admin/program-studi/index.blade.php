<x-admin-layout>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="mb-4">
                    <h1 class="page-title mb-0">Manajemen Program Studi</h1>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                            <li class="breadcrumb-item active">Program Studi</li>
                        </ol>
                    </nav>
                </div>

                <!-- Filter -->
                <div class="table-card">
                    <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div style="position: relative;">
                                    <input type="text" class="form-control" placeholder="Cari data...">
                                    <i class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>KODE PRODI</th>
                                    <th>NAMA PROGRAM STUDI</th>
                                    <th>JENJANG PENDIDIKAN</th>
                                    <th>AKREDITASI</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>IF101</strong></td>
                                    <td>Teknik Informatika</td>
                                    <td>S1 - Sarjana</td>
                                    <td>Unggul</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn action-btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>SI102</strong></td>
                                    <td>Sistem Informasi</td>
                                    <td>S1 - Sarjana</td>
                                    <td>A</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn action-btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>DKV103</strong></td>
                                    <td>Desain Komunikasi Visual</td>
                                    <td>S1 - Sarjana</td>
                                    <td>B</td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn action-btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                                            <button class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                            <button class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <nav aria-label="Page navigation" style="padding: 1.5rem;">
                        <ul class="pagination mb-0 justify-content-center">
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Sidebar - Edit Form -->
            <div class="col-lg-4">
                <div class="table-card">
                    <div class="table-card-header">
                        <h5>Edit Program Studi</h5>
                    </div>
                    <form style="padding: 1.5rem;">
                        <div class="mb-3">
                            <label class="form-label">Kode Program Studi</label>
                            <input type="text" class="form-control" value="IF101" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Program Studi</label>
                            <input type="text" class="form-control" value="Teknik Informatika">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenjang Pendidikan</label>
                            <select class="form-select">
                                <option selected>S1 - Sarjana</option>
                                <option>D3 - Diploma</option>
                                <option>S2 - Magister</option>
                                <option>S3 - Doktor</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi Singkat</label>
                            <textarea class="form-control" rows="3">Program Studi Teknik Informatika fokus pada pengembangan sistem perangkat, komunikasi ketertarikan kemampuan mata kuliah jadi lebih dulu ketahui dengan matang sebelum menambahkan ke mana kuliah baru.</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Akreditasi</label>
                            <select class="form-select">
                                <option>-- Pilih Akreditasi --</option>
                                <option selected>Unggul</option>
                                <option>A</option>
                                <option>B</option>
                                <option>C</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kapasitas</label>
                            <input type="number" class="form-control" value="120" placeholder="Masukkan jumlah mahasiswa">
                        </div>

                        <div class="mb-3">
                            <h6 style="margin-bottom: 1rem; font-weight: 600;">Status Aktif</h6>
                            <div class="form-check form-switch" style="padding-left: 0;">
                                <input class="form-check-input" type="checkbox" id="statusSwitch" checked style="margin-right: 0.75rem;">
                                <label class="form-check-label" for="statusSwitch">
                                    <span style="display: inline-block; width: 10px; height: 10px; background-color: #10b981; border-radius: 50%; margin-right: 0.5rem;"></span>
                                    Aktif
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <button type="button" class="btn btn-outline-secondary">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
