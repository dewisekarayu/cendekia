<x-admin-layout>
    <div class="container-fluid py-3">
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #6b7280; text-decoration: none;">Cendekia</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Pengumuman</li>
                    </ol>
                </nav>
                <h1 class="page-title mb-1" style="font-size: 1.75rem; font-weight: 700; color: #002B6B;">Manajemen Pengumuman</h1>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Buat dan kelola informasi penting untuk seluruh civitas akademika.</p>
            </div>
            
            <button class="btn btn-primary px-4 py-2.5 d-flex align-items-center gap-2" style="border-radius: 0.5rem; background-color: #0d6efd; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                <i class="bi bi-plus-lg"></i> Create New Announcement
            </button>
        </div>

        <div class="row mb-4">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center gap-3" style="border-radius: 12px; background: white;">
                    <div class="p-2 text-primary bg-primary bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-megaphone fs-4"></i>
                    </div>
                    <div>
                        <small class="text-muted text-uppercase fw-bold d-block" style="font-size: 0.7rem; letter-spacing: 0.5px;">TOTAL POST</small>
                        <div class="d-flex align-items-baseline gap-2">
                            <h3 class="fw-bold m-0" style="color: #002B6B;">128</h3>
                            <span class="text-success small fw-semibold" style="font-size: 0.8rem;"><i class="bi bi-arrow-up-short"></i> +12%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <div class="d-flex flex-wrap gap-2 mb-4">
                <button class="btn px-4 py-2 text-white" style="background-color: #0d6efd; border-radius: 2rem; font-weight: 600; font-size: 0.85rem; border: none;">Semua</button>
                <button class="btn px-4 py-2 text-secondary bg-white border" style="border-radius: 2rem; font-weight: 600; font-size: 0.85rem;">Penting</button>
                <button class="btn px-4 py-2 text-secondary bg-white border" style="border-radius: 2rem; font-weight: 600; font-size: 0.85rem;">Akademik</button>
                <button class="btn px-4 py-2 text-secondary bg-white border" style="border-radius: 2rem; font-weight: 600; font-size: 0.85rem;">Event</button>
            </div>

            <div class="d-flex flex-column gap-3 mb-4">
                
                <div class="card border-0 shadow-sm p-4 bg-white container-feed-item" style="border-radius: 12px; border-left: 5px solid #ef4444 !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="badge fw-bold px-2.5 py-1.5" style="background-color: #FEE2E2; color: #ef4444; font-size: 0.7rem; border-radius: 6px; letter-spacing: 0.5px;"><i class="bi bi-exclamation-triangle-fill me-1"></i> HIGH PRIORITY</span>
                            <span class="text-muted small"><i class="bi bi-calendar4-event me-1"></i> 12 Okt 2023, 09:00</span>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-light border text-secondary p-1 px-2" style="border-radius: 6px;"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-light border text-secondary p-1 px-2" style="border-radius: 6px;"><i class="bi bi-three-dots-vertical"></i></button>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #002B6B; font-size: 1.1rem;">Perubahan Jadwal Ujian Tengah Semester Ganjil 2023/2024</h5>
                    <p class="text-muted small mb-3" style="line-height: 1.6;">Sehubungan dengan adanya agenda nasional, maka seluruh jadwal UTS yang semula dijadwalkan pada tanggal 20 Oktober akan diundur ke tanggal berikutnya sesuai pengumuman resmi...</p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #f1f5f9 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">BA</div>
                            <span class="fw-semibold text-secondary small">Biro Akademik</span>
                        </div>
                        <span class="text-muted small"><i class="bi bi-paperclip me-1"></i> Jadwal_Revisi.pdf</span>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 bg-white container-feed-item" style="border-radius: 12px; border-left: 5px solid #f97316 !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="badge fw-bold px-2.5 py-1.5" style="background-color: #FFF3EB; color: #f97316; font-size: 0.7rem; border-radius: 6px; letter-spacing: 0.5px;">MEDIUM PRIORITY</span>
                            <span class="text-muted small"><i class="bi bi-calendar4-event me-1"></i> 10 Okt 2023, 14:20</span>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-light border text-secondary p-1 px-2" style="border-radius: 6px;"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-light border text-secondary p-1 px-2" style="border-radius: 6px;"><i class="bi bi-three-dots-vertical"></i></button>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #002B6B; font-size: 1.1rem;">Pendaftaran Workshop Digital Literacy Batch 4</h5>
                    <p class="text-muted small mb-3" style="line-height: 1.6;">Dibuka pendaftaran workshop literasi digital untuk batch ke-4. Kuota terbatas hanya untuk 50 peserta pertama. Dapatkan benefit e-certificate dan free snacks...</p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #f1f5f9 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-info d-flex align-items-center justify-content-center text-white fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">IT</div>
                            <span class="fw-semibold text-secondary small">Unit IT Support</span>
                        </div>
                        <span class="text-muted small"><i class="bi bi-paperclip me-1"></i> Poster.png</span>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 bg-white container-feed-item" style="border-radius: 12px; border-left: 5px solid #64748b !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <span class="badge fw-bold px-2.5 py-1.5" style="background-color: #F1F5F9; color: #64748b; font-size: 0.7rem; border-radius: 6px; letter-spacing: 0.5px;">LOW PRIORITY</span>
                            <span class="text-muted small"><i class="bi bi-calendar4-event me-1"></i> 08 Okt 2023, 11:15</span>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-light border text-secondary p-1 px-2" style="border-radius: 6px;"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-light border text-secondary p-1 px-2" style="border-radius: 6px;"><i class="bi bi-three-dots-vertical"></i></button>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #002B6B; font-size: 1.1rem;">Layanan Perpustakaan Selama Masa Libur Nasional</h5>
                    <p class="text-muted small mb-3" style="line-height: 1.6;">Informasi operasional layanan perpustakaan pusat selama masa cuti bersama dan libur nasional. Layanan peminjaman online tetap dapat diakses...</p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #f1f5f9 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-warning d-flex align-items-center justify-content-center text-dark fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">LP</div>
                            <span class="fw-semibold text-secondary small">Layanan Perpustakaan</span>
                        </div>
                        <span class="text-muted small">-</span>
                    </div>
                </div>

            </div>

            <div class="text-center pt-2">
                <button class="btn btn-light border px-5 py-2 fw-semibold text-secondary shadow-sm" style="border-radius: 8px; background-color: white;">
                    Muat Lebih Banyak
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createAnnouncementModal" tabindex="-1" aria-labelledby="createAnnouncementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px; border: none;">
                <div class="modal-header px-4 pt-4 pb-2 border-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="createAnnouncementModalLabel" style="color: #002B6B; font-size: 1.3rem;">Buat Pengumuman Baru</h5>
                        <p class="text-muted small mb-0">Buat dan terbitkan pengumuman untuk seluruh civitas akademika.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body px-4 py-3">
                    <form id="announcementForm" action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul pengumuman..." required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="kategori" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select" id="kategori" name="kategori" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Penting">Penting</option>
                                    <option value="Event">Event</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="prioritas" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Tingkat Prioritas <span class="text-danger">*</span></label>
                                <select class="form-select" id="prioritas" name="prioritas" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                                    <option value="Low">Low Priority</option>
                                    <option value="Medium" selected>Medium Priority</option>
                                    <option value="High">High Priority</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Isi Pengumuman <span class="text-danger">*</span></label>
                            <div class="border rounded-3 overflow-hidden">
                                <div class="d-flex align-items-center gap-3 px-3 py-2 bg-light border-bottom text-muted" style="font-size: 0.9rem;">
                                    <i class="bi bi-type-bold cursor-pointer"></i>
                                    <i class="bi bi-type-italic cursor-pointer"></i>
                                    <i class="bi bi-type-underline cursor-pointer"></i>
                                    <span class="text-black-50">|</span>
                                    <i class="bi bi-list-ul cursor-pointer"></i>
                                    <i class="bi bi-list-ol cursor-pointer"></i>
                                    <span class="text-black-50">|</span>
                                    <i class="bi bi-link-45deg cursor-pointer"></i>
                                    <i class="bi bi-image cursor-pointer"></i>
                                </div>
                                <textarea class="form-control border-0" id="isi" name="isi" rows="5" placeholder="Ketik isi pengumuman di sini..." style="border-radius: 0; box-shadow: none;" required></textarea>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Lampiran <span class="text-muted">(Opsional)</span></label>
                            <div class="border-2 border-dashed rounded-3 p-4 text-center bg-light position-relative" style="cursor: pointer; border-color: #cbd5e1 !important;">
                                <input type="file" name="lampiran" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;">
                                <i class="bi bi-cloud-arrow-up text-primary fs-2 mb-2 d-block"></i>
                                <span class="fw-semibold d-block small text-secondary">Pilih untuk upload atau drag and drop</span>
                                <small class="text-muted" style="font-size: 0.75rem;">PDF, DOCX, JPG, PNG (Max 10MB)</small>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" form="announcementForm" class="btn btn-primary px-4 py-2 fw-semibold" style="background-color: #002B6B; border: none; border-radius: 8px;">Terbitkan Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>