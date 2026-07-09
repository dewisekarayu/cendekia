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

            <button class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 0.5rem; background-color: #0d6efd; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);" data-bs-toggle="modal" data-bs-target="#createPengumumanModal">
                <i class="bi bi-plus-lg"></i> Buat Pengumuman
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2 border-0 shadow-sm mb-4" style="border-radius: 10px;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        <div class="d-flex flex-column gap-3 mb-4">
            @forelse ($pengumuman as $item)
                <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 12px; border-left: 5px solid #e2e8f0 !important;">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            @if ($item->untuk_semua)
                                <span class="badge fw-bold px-2 py-1" style="background-color: #ECFDF5; color: #059669; font-size: 0.7rem; border-radius: 6px; letter-spacing: 0.5px;">
                                    <i class="bi bi-people-fill me-1"></i> UNTUK SEMUA
                                </span>
                            @else
                                <span class="badge fw-bold px-2 py-1" style="background-color: #F1F5F9; color: #64748b; font-size: 0.7rem; border-radius: 6px; letter-spacing: 0.5px;">
                                    <i class="bi bi-person-lines-fill me-1"></i> KELOMPOK TERTENTU
                                </span>
                            @endif
                            <span class="text-muted small"><i class="bi bi-calendar4-event me-1"></i> {{ $item->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-light border text-secondary p-1 px-2" style="border-radius: 6px;" data-bs-toggle="modal" data-bs-target="#editPengumumanModal{{ $item->id }}" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-light border text-danger p-1 px-2" style="border-radius: 6px;" data-bs-toggle="modal" data-bs-target="#deletePengumumanModal{{ $item->id }}" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #002B6B; font-size: 1.1rem;">{{ $item->judul }}</h5>
                    <p class="text-muted small mb-3" style="line-height: 1.6;">{{ Str::limit(strip_tags($item->isi), 220) }}</p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top" style="border-color: #f1f5f9 !important;">
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                {{ $item->pembuat ? strtoupper(Str::substr($item->pembuat->name, 0, 2)) : '?' }}
                            </div>
                            <span class="fw-semibold text-secondary small">{{ $item->pembuat->name ?? 'Pengguna tidak diketahui' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Edit --}}
                <div class="modal fade" id="editPengumumanModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 14px; border: none;">
                            <div class="modal-header px-4 pt-4 pb-2 border-0">
                                <div>
                                    <h5 class="modal-title fw-bold" style="color: #002B6B; font-size: 1.3rem;">Edit Pengumuman</h5>
                                    <p class="text-muted small mb-0">Perbarui informasi pengumuman ini.</p>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body px-4 py-3">
                                <form id="editForm{{ $item->id }}" action="{{ route('admin.pengumuman.update', $item) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="judul_edit_{{ $item->id }}" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Judul Pengumuman <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="judul_edit_{{ $item->id }}" name="judul" value="{{ old('judul', $item->judul) }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                                    </div>

                                    <div class="mb-3">
                                        <label for="isi_edit_{{ $item->id }}" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Isi Pengumuman <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="isi_edit_{{ $item->id }}" name="isi" rows="5" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">{{ old('isi', $item->isi) }}</textarea>
                                    </div>

                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="untuk_semua_edit_{{ $item->id }}" name="untuk_semua" value="1" {{ $item->untuk_semua ? 'checked' : '' }}>
                                        <label class="form-check-label small fw-semibold text-secondary" for="untuk_semua_edit_{{ $item->id }}">Tampilkan untuk semua pengguna</label>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer px-4 pb-4 pt-2 border-0 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                                <button type="submit" form="editForm{{ $item->id }}" class="btn btn-primary px-4 py-2 fw-semibold" style="background-color: #002B6B; border: none; border-radius: 8px;">Simpan Perubahan</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Hapus --}}
                <div class="modal fade" id="deletePengumumanModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content" style="border-radius: 14px; border: none;">
                            <div class="modal-body px-4 py-4 text-center">
                                <div class="d-flex align-items-center justify-content-center bg-danger bg-opacity-10 text-danger rounded-circle mx-auto mb-3" style="width: 56px; height: 56px;">
                                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: #002B6B;">Hapus Pengumuman?</h5>
                                <p class="text-muted small mb-4">Tindakan ini tidak dapat dibatalkan. "<strong>{{ $item->judul }}</strong>" akan dihapus secara permanen.</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                                    <form action="{{ route('admin.pengumuman.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold" style="border-radius: 8px;">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border-0 shadow-sm p-5 bg-white text-center" style="border-radius: 12px;">
                    <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-megaphone fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-1" style="color: #002B6B;">Belum Ada Pengumuman</h5>
                    <p class="text-muted small mb-0">Klik "Buat Pengumuman" untuk menerbitkan informasi pertama.</p>
                </div>
            @endforelse
        </div>

        @if ($pengumuman->hasPages())
            <div class="d-flex justify-content-center">
                {{ $pengumuman->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Buat Pengumuman --}}
    <div class="modal fade" id="createPengumumanModal" tabindex="-1" aria-labelledby="createPengumumanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px; border: none;">
                <div class="modal-header px-4 pt-4 pb-2 border-0">
                    <div>
                        <h5 class="modal-title fw-bold" id="createPengumumanModalLabel" style="color: #002B6B; font-size: 1.3rem;">Buat Pengumuman Baru</h5>
                        <p class="text-muted small mb-0">Buat dan terbitkan pengumuman untuk seluruh civitas akademika.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-4 py-3">
                    <form id="createPengumumanForm" action="{{ route('admin.pengumuman.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Masukkan judul pengumuman..." value="{{ old('judul') }}" required style="border-radius: 8px; padding: 0.65rem 0.75rem;">
                        </div>

                        <div class="mb-3">
                            <label for="isi" class="form-label fw-semibold small text-muted text-uppercase" style="letter-spacing: 0.5px;">Isi Pengumuman <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="isi" name="isi" rows="6" placeholder="Ketik isi pengumuman di sini..." required style="border-radius: 8px; padding: 0.65rem 0.75rem;">{{ old('isi') }}</textarea>
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="untuk_semua" name="untuk_semua" value="1" checked>
                            <label class="form-check-label small fw-semibold text-secondary" for="untuk_semua">Tampilkan untuk semua pengguna</label>
                        </div>
                    </form>
                </div>

                <div class="modal-footer px-4 pb-4 pt-2 border-0 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light border px-4 py-2 fw-semibold text-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                    <button type="submit" form="createPengumumanForm" class="btn btn-primary px-4 py-2 fw-semibold" style="background-color: #002B6B; border: none; border-radius: 8px;">Terbitkan Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>