<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #002B6B; font-weight: 600; text-decoration: none;">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #6b7280;">Pengumuman</li>
                    </ol>
                </nav>
                <h1 class="page-title mb-1" style="font-size: 1.75rem; font-weight: 700; color: #002B6B;">Manajemen Pengumuman</h1>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Buat dan kelola informasi penting untuk seluruh civitas akademika.</p>
            </div>
            <button class="btn btn-primary px-4 py-2" style="border-radius: 0.5rem; background-color: #002B6B; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);" data-bs-toggle="modal" data-bs-target="#createAnnouncementModal">
                <i class="bi bi-plus-circle me-2"></i> Create New Announcement
            </button>
        </div>

        <!-- Total Stats Announcement Card -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card" style="background: white; border-radius: 1rem; border: none; padding: 1.5rem; display: flex; flex-direction: column; box-shadow: 0 4px 20px rgba(0, 43, 107, 0.05);">
                    <div class="stat-card-top d-flex justify-content-between align-items-center mb-2">
                        <span class="label" style="font-size: 0.75rem; font-weight: 700; color: #8A94A6; text-transform: uppercase;">TOTAL POST</span>
                        <span class="stat-card-badge up">
                            <i class="bi bi-arrow-up-short"></i> +12%
                        </span>
                    </div>
                    <div class="number" style="font-size: 2rem; font-weight: 700; color: #002B6B;">{{ number_format($pengumuman->total() ?: 3, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Filter & Feed Area -->
        <div class="table-card" style="background: transparent; box-shadow: none; border: none; margin-top: 1rem;">
            <!-- Tabs Filter -->
            <div class="mb-4">
                <div class="d-flex gap-2">
                    <button class="btn px-4 py-2" style="background-color: #002B6B; color: white; border-radius: 2rem; font-weight: 600; font-size: 0.85rem; border: none;">Semua</button>
                    <button class="btn px-4 py-2" style="background-color: white; color: #64748b; border-radius: 2rem; font-weight: 600; font-size: 0.85rem; border: 1px solid #e2e8f0;">Penting</button>
                    <button class="btn px-4 py-2" style="background-color: white; color: #64748b; border-radius: 2rem; font-weight: 600; font-size: 0.85rem; border: 1px solid #e2e8f0;">Akademik</button>
                    <button class="btn px-4 py-2" style="background-color: white; color: #64748b; border-radius: 2rem; font-weight: 600; font-size: 0.85rem; border: 1px solid #e2e8f0;">Event</button>
                </div>
            </div>

            <!-- Feed List -->
            <div class="d-flex flex-column gap-3 mb-4">
                <!-- Post 1 -->
                <div class="feed-item" style="background: white; border-radius: 1rem; padding: 1.75rem; box-shadow: 0 4px 20px rgba(0, 43, 107, 0.05); position: relative; border-left: 5px solid #ef4444;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge" style="background-color: #FEE2E2; color: #ef4444; font-size: 0.75rem; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 0.5rem; margin-right: 0.75rem; text-transform: uppercase;">HIGH PRIORITY</span>
                            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 500;"><i class="bi bi-calendar4-event me-1"></i> 13 Okt 2023, 09:00</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown" style="box-shadow: none;">
                                <i class="bi bi-three-dots-vertical" style="font-size: 1.25rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i> Hapus</a></li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="mb-2" style="font-weight: 700; color: #002B6B; font-size: 1.15rem;">Perubahan Jadwal Ujian Tengah Semester Ganjil 2023/2024</h5>
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.25rem;">Sehubungan dengan adanya agenda nasional, maka seluruh jadwal UTS yang semula dijadwalkan pada tanggal 20 Oktober akan diundur ke tanggal berikutnya sesuai pengumuman resmi...</p>
                    <div class="d-flex justify-content-between align-items-center pt-3" style="border-top: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed=biroakademik" style="width: 28px; height: 28px; border-radius: 50%;" alt="Author">
                            <span style="font-weight: 600; color: #475569; font-size: 0.85rem;">Biro Akademik</span>
                        </div>
                        <div style="color: #94a3b8; font-size: 0.85rem;">
                            <i class="bi bi-paperclip me-1"></i> Jadwal_Revisi.pdf
                        </div>
                    </div>
                </div>

                <!-- Post 2 -->
                <div class="feed-item" style="background: white; border-radius: 1rem; padding: 1.75rem; box-shadow: 0 4px 20px rgba(0, 43, 107, 0.05); position: relative; border-left: 5px solid #f97316;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge" style="background-color: #FFF3EB; color: #f97316; font-size: 0.75rem; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 0.5rem; margin-right: 0.75rem; text-transform: uppercase;">MEDIUM PRIORITY</span>
                            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 500;"><i class="bi bi-calendar4-event me-1"></i> 10 Okt 2023, 14:20</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown" style="box-shadow: none;">
                                <i class="bi bi-three-dots-vertical" style="font-size: 1.25rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i> Hapus</a></li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="mb-2" style="font-weight: 700; color: #002B6B; font-size: 1.15rem;">Pendaftaran Workshop Digital Literacy Batch 4</h5>
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.25rem;">Dibuka pendaftaran workshop literasi digital untuk batch ke-4. Kuota terbatas hanya untuk 50 peserta pertama. Dapatkan benefit e-certificate dan free snacks...</p>
                    <div class="d-flex justify-content-between align-items-center pt-3" style="border-top: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed=itsupport" style="width: 28px; height: 28px; border-radius: 50%;" alt="Author">
                            <span style="font-weight: 600; color: #475569; font-size: 0.85rem;">Unit IT Support</span>
                        </div>
                        <div style="color: #94a3b8; font-size: 0.85rem;">
                            <i class="bi bi-paperclip me-1"></i> Poster.png
                        </div>
                    </div>
                </div>

                <!-- Post 3 -->
                <div class="feed-item" style="background: white; border-radius: 1rem; padding: 1.75rem; box-shadow: 0 4px 20px rgba(0, 43, 107, 0.05); position: relative; border-left: 5px solid #002B6B;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="badge" style="background-color: #E6EEFF; color: #002B6B; font-size: 0.75rem; font-weight: 700; padding: 0.35rem 0.75rem; border-radius: 0.5rem; margin-right: 0.75rem; text-transform: uppercase;">LOW PRIORITY</span>
                            <span style="color: #94a3b8; font-size: 0.85rem; font-weight: 500;"><i class="bi bi-calendar4-event me-1"></i> 08 Okt 2023, 11:15</span>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-link text-muted p-0" data-bs-toggle="dropdown" style="box-shadow: none;">
                                <i class="bi bi-three-dots-vertical" style="font-size: 1.25rem;"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i> Hapus</a></li>
                            </ul>
                        </div>
                    </div>
                    <h5 class="mb-2" style="font-weight: 700; color: #002B6B; font-size: 1.15rem;">Layanan Perpustakaan Selama Masa Libur Nasional</h5>
                    <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.25rem;">Informasi operasional layanan perpustakaan pusat selama masa cuti bersama dan libur nasional. Layanan peminjaman online tetap dapat diakses...</p>
                    <div class="d-flex justify-content-between align-items-center pt-3" style="border-top: 1px solid #f1f5f9;">
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://api.dicebear.com/7.x/identicon/svg?seed=perpus" style="width: 28px; height: 28px; border-radius: 50%;" alt="Author">
                            <span style="font-weight: 600; color: #475569; font-size: 0.85rem;">Layanan Perpustakaan</span>
                        </div>
                        <div style="color: #94a3b8; font-size: 0.85rem;">
                            <span class="text-muted">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Load More Button -->
            <div class="text-center pb-4">
                <button class="btn btn-outline-secondary px-5 py-2.5" style="border-radius: 0.5rem; border-color: #cbd5e1; font-weight: 600; color: #475569; background-color: white;">
                    Muat Lebih Banyak
                </button>
            </div>
        </div>
    </div>

    <!-- Create Announcement Modal (High Fidelity mockup from Screen 5) -->
    <div class="modal fade" id="createAnnouncementModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 1rem; border: none; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
                <div class="modal-header px-4 py-3" style="background-color: #F8FAFC; border-bottom: 1px solid #e2e8f0;">
                    <div>
                        <h5 class="modal-title" style="font-weight: 700; color: #002B6B;">Buat Pengumuman Baru</h5>
                        <small class="text-muted">Buat dan terbitkan pengumuman untuk seluruh civitas akademika.</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body px-4 py-4" style="max-height: 75vh; overflow-y: auto;">
                    <form id="announcementForm" method="POST" action="{{ route('admin.pengumuman.store') }}">
                        @csrf
                        <!-- Judul -->
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Judul Pengumuman</label>
                            <input type="text" name="judul" class="form-control" placeholder="Masukkan judul pengumuman..." style="border-radius: 0.5rem; border-color: #cbd5e1; padding: 0.65rem 1rem;" required>
                        </div>

                        <!-- Kategori -->
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Kategori</label>
                            <select class="form-select" style="border-radius: 0.5rem; border-color: #cbd5e1; padding: 0.65rem 1rem;">
                                <option selected>Pilih Kategori</option>
                                <option>Akademik</option>
                                <option>Kegiatan</option>
                                <option>Event</option>
                                <option>Umum</option>
                            </select>
                        </div>

                        <!-- Tingkat Prioritas (Mockup Screen 5 style) -->
                        <div class="mb-3">
                            <label class="form-label d-block" style="font-weight: 600; color: #334155; margin-bottom: 0.5rem;">Tingkat Prioritas</label>
                            <div class="d-flex gap-4">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="radio" name="priorityRadio" id="priorityLow" checked style="margin-right: 0.5rem;">
                                    <label class="form-check-label" for="priorityLow" style="color: #002B6B; font-weight: 600; font-size: 0.9rem;">
                                        Low Priority
                                    </label>
                                </div>
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="radio" name="priorityRadio" id="priorityMedium" style="margin-right: 0.5rem;">
                                    <label class="form-check-label" for="priorityMedium" style="color: #f97316; font-weight: 600; font-size: 0.9rem;">
                                        Medium Priority
                                    </label>
                                </div>
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="radio" name="priorityRadio" id="priorityHigh" style="margin-right: 0.5rem;">
                                    <label class="form-check-label" for="priorityHigh" style="color: #ef4444; font-weight: 600; font-size: 0.9rem;">
                                        High Priority <i class="bi bi-exclamation-triangle-fill text-danger ms-1"></i>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Rich text toolbar mock & textarea -->
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Isi Pengumuman</label>
                            <div class="editor-container" style="border: 1px solid #cbd5e1; border-radius: 0.5rem; overflow: hidden;">
                                <!-- Toolbar -->
                                <div class="editor-toolbar d-flex align-items-center gap-3 px-3 py-2" style="background-color: #F8FAFC; border-bottom: 1px solid #cbd5e1; font-size: 0.95rem; color: #64748b;">
                                    <i class="bi bi-type-bold cursor-pointer" title="Bold"></i>
                                    <i class="bi bi-type-italic cursor-pointer" title="Italic"></i>
                                    <i class="bi bi-type-underline cursor-pointer" title="Underline"></i>
                                    <span style="color: #cbd5e1;">|</span>
                                    <i class="bi bi-justify-left cursor-pointer" title="Align Left"></i>
                                    <i class="bi bi-justify cursor-pointer" title="Align Center"></i>
                                    <i class="bi bi-link-45deg cursor-pointer" title="Insert Link"></i>
                                    <i class="bi bi-image cursor-pointer" title="Insert Image"></i>
                                </div>
                                <textarea name="isi" class="form-control border-0" rows="5" placeholder="Ketik isi pengumuman di sini..." style="border-radius: 0; padding: 1rem; outline: none; box-shadow: none; font-size: 0.95rem;" required></textarea>
                            </div>
                        </div>

                        <!-- Target Audiens -->
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Target Audiens</label>
                            <div class="d-flex gap-4">
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="audienceAll" name="untuk_semua" value="1" checked style="margin-right: 0.5rem;">
                                    <label class="form-check-label" for="audienceAll" style="font-weight: 500; font-size: 0.9rem; color: #475569;">Semua</label>
                                </div>
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="audienceDosen" style="margin-right: 0.5rem;">
                                    <label class="form-check-label" for="audienceDosen" style="font-weight: 500; font-size: 0.9rem; color: #475569;">Dosen</label>
                                </div>
                                <div class="form-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" id="audienceMahasiswa" checked style="margin-right: 0.5rem;">
                                    <label class="form-check-label" for="audienceMahasiswa" style="font-weight: 500; font-size: 0.9rem; color: #475569;">Mahasiswa</label>
                                </div>
                            </div>
                        </div>

                        <!-- Drag and Drop Lampiran -->
                        <div class="mb-2">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Lampiran (Opsional)</label>
                            <div class="drag-drop-area text-center py-4 px-3" style="border: 2px dashed #cbd5e1; border-radius: 0.5rem; background-color: #F8FAFC; cursor: pointer;">
                                <i class="bi bi-cloud-upload-fill" style="font-size: 2rem; color: #002B6B; display: block; margin-bottom: 0.5rem;"></i>
                                <span style="font-size: 0.9rem; font-weight: 600; color: #475569; display: block;">Pilih untuk upload atau drag and drop</span>
                                <small class="text-muted" style="font-size: 0.75rem;">PDF, DOCX, JPG, PNG (Max 10MB)</small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer px-4 py-3" style="background-color: #F8FAFC; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 0.5rem;">
                    <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal" style="border: 1px solid #cbd5e1; border-radius: 0.5rem; font-weight: 600; color: #475569; background-color: white;">Batal</button>
                    <button type="submit" form="announcementForm" class="btn btn-primary px-4 py-2" style="background-color: #002B6B; border: none; border-radius: 0.5rem; font-weight: 600; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">Terbitkan Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
