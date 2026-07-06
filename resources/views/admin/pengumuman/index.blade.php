<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-0">Manajemen Pengumuman</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Konten</a></li>
                        <li class="breadcrumb-item active">Pengumuman</li>
                    </ol>
                </nav>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                <i class="bi bi-plus-circle"></i> Create New Announcement
            </button>
        </div>

        <!-- Filter Section -->
        <div class="table-card">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div style="position: relative;">
                            <input type="text" class="form-control" placeholder="Cari pengumuman...">
                            <i class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <select class="form-select">
                            <option>Semua</option>
                            <option>Pending</option>
                            <option>Published</option>
                            <option>Archived</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <small class="text-muted">TOTAL POST: 128 • 12/5</small>
                </div>
            </div>

            <div style="padding: 1.5rem;">
                <!-- Post 1 -->
                <div style="padding: 1.5rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge badge-status badge-high" style="margin-right: 0.5rem;">HIGH PRIORITY</span>
                            <h6 class="d-inline" style="color: #1f2937;">Perubahan Jadwal Ujian Tengah Semester 2023/2024</h6>
                        </div>
                        <div class="action-buttons">
                            <button class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <p style="color: #4b5563; margin-bottom: 1rem;">Perubahan jadwal ujian telah dilakukan, mohon perhatikan untuk mengikuti jadwal yang baru sesuai dengan pengumuman ini.</p>
                    <div style="display: flex; gap: 2rem; font-size: 0.85rem; color: #9ca3af;">
                        <span><i class="bi bi-person"></i> Sita Azimovah</span>
                        <span><i class="bi bi-clock"></i> Dipost pada 01 Oct 2023, 10:20</span>
                    </div>
                </div>

                <!-- Post 2 -->
                <div style="padding: 1.5rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge badge-status badge-medium" style="margin-right: 0.5rem;">MEDIUM PRIORITY</span>
                            <h6 class="d-inline" style="color: #1f2937;">Pendaftaran Workshop Digital Literacy Batch 4</h6>
                        </div>
                        <div class="action-buttons">
                            <button class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <p style="color: #4b5563; margin-bottom: 1rem;">Dibuka pendaftaran workshop digital literacy untuk batch 4. Silakan daftarkan diri anda melalui link formulir pendaftaran.</p>
                    <div style="display: flex; gap: 2rem; font-size: 0.85rem; color: #9ca3af;">
                        <span><i class="bi bi-person"></i> Dini B Suguri</span>
                        <span><i class="bi bi-clock"></i> Dipost pada 30 Oct 2023, 14:34</span>
                    </div>
                </div>

                <!-- Post 3 -->
                <div style="padding: 1.5rem; border: 1px solid #e5e7eb; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge badge-status badge-low" style="margin-right: 0.5rem;">LOW PRIORITY</span>
                            <h6 class="d-inline" style="color: #1f2937;">Layanan Perpustakaan Selama Masa Libur Nasional</h6>
                        </div>
                        <div class="action-buttons">
                            <button class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <p style="color: #4b5563; margin-bottom: 1rem;">Informasi mengenai jam operasional perpustakaan pusat selama masa libur nasional sampai dengan menjelang perkuliahan dimulai kembali.</p>
                    <div style="display: flex; gap: 2rem; font-size: 0.85rem; color: #9ca3af;">
                        <span><i class="bi bi-person"></i> Layanan Perpustakaan</span>
                        <span><i class="bi bi-clock"></i> Dipost pada 28 Oct 2023, 15:54</span>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="text-center">
                    <button class="btn btn-outline-secondary">
                        Muat Lebih Banyak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Announcement Modal -->
    <div class="modal fade" id="createAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buat Pengumuman Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Judul Pengumuman</label>
                            <input type="text" class="form-control" placeholder="Masukkan judul...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Isi Pengumuman</label>
                            <textarea class="form-control" rows="4" placeholder="Masukkan konten pengumuman..."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prioritas</label>
                            <select class="form-select">
                                <option selected>Pilih Prioritas</option>
                                <option value="low">Low Priority</option>
                                <option value="medium">Medium Priority</option>
                                <option value="high">High Priority</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Target Audiens</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="targetAll" checked>
                                <label class="form-check-label" for="targetAll">Semua Pengguna</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="targetDosen">
                                <label class="form-check-label" for="targetDosen">Dosen</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="targetMahasiswa">
                                <label class="form-check-label" for="targetMahasiswa">Mahasiswa</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary">Publikasikan</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
