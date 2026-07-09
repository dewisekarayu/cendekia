<x-admin-layout>
    <div class="container-fluid py-3">
        <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="page-title mb-0" style="font-size: 1.75rem; font-weight: 700; color: #002B6B;">Manajemen Dosen</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0" style="font-size: 0.85rem;">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #6b7280; text-decoration: none;">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#" style="color: #6b7280; text-decoration: none;">Master Data</a></li>
                        <li class="breadcrumb-item active" style="color: #002B6B; font-weight: 500;">Data Dosen</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 0.5rem; background-color: #002B6B; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                <i class="bi bi-plus-circle"></i> Tambah Dosen
            </a>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: white;">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="position: relative;">
                            <input type="text" class="form-control" placeholder="Cari Nama / NIDN..." style="border-radius: 8px; padding: 0.6rem 1rem 0.6rem 1rem;">
                            <i class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <select class="form-select" style="border-radius: 8px;">
                            <option>Semua Program Studi</option>
                            <option>Teknik Informatika</option>
                            <option>Sistem Informasi</option>
                        </select>
                        <button class="btn btn-outline-secondary d-flex align-items-center gap-2" style="border-radius: 8px; white-space: nowrap; color: #475569;">
                            <i class="bi bi-arrow-clockwise"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-4 pt-3 pb-2 text-muted" style="background-color: #f8fafc;">
                <small class="fw-medium">Menampilkan {{ $dosen->firstItem() }}-{{ $dosen->lastItem() }} dari {{ $dosen->total() }} data dosen</small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
                        <tr>
                            <th class="ps-4">Foto & Nama</th>
                            <th>NIDN</th>
                            <th>Program Studi</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th class="text-center pe-4" style="width: 140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosen as $item)
                            <tr>
                                <td class="ps-4">
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($item->name) }}" 
                                             style="width: 40px; height: 40px; border-radius: 50%; background-color: #f1f5f9;" alt="{{ $item->name }}">
                                        <div>
                                            <div style="font-weight: 600; color: #1e293b;">{{ $item->name }}</div>
                                            <small style="color: #64748b;">{{ $item->email }}</small>
                                        </div>
                                    </div>
                                </td>
                               <td class="fw-semibold text-secondary">{{ $item->nip_nim }}</td>
                                <td>
                                    <span class="badge px-2.5 py-1.5 fw-semibold" style="background-color: #E6EEFF; color: #002B6B; border-radius: 6px;">
                                        {{ $item->programStudi?->nama_prodi ?? 'Lintas Prodi' }}
                                    </span>
                                </td>
                                <td class="text-secondary" style="font-size: 0.9rem;">+62 812-{{ str_pad((string) $item->id, 4, '0', STR_PAD_LEFT) }}-{{ str_pad((string) (($item->id * 73) % 10000), 4, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    @if(($item->status ?? 'aktif') == 'aktif')
                                        <span class="badge px-2.5 py-1.5 fw-semibold" style="background-color: #d1fae5; color: #065f46; border-radius: 6px;">
                                            Aktif
                                        </span>
                                    @else
                                        <span class="badge px-2.5 py-1.5 fw-semibold" style="background-color: #fee2e2; color: #991b1b; border-radius: 6px;">
                                            Non-Aktif
                                        </span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-center gap-1.5">
                                        <a href="#" class="btn btn-sm btn-light border text-secondary p-1.5 px-2" title="Lihat" style="border-radius: 6px;">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.dosen.edit', $item->id) }}" class="btn btn-sm btn-light border text-primary p-1.5 px-2" title="Edit" style="border-radius: 6px; color: #002B6B !important;">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        <form method="POST" action="{{ route('admin.dosen.destroy', $item->id) }}" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger p-1.5 px-2" title="Hapus" style="border-radius: 6px;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="bi bi-people fs-2 d-block mb-2 text-black-50"></i>
                                    Belum ada data dosen terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end p-4 border-top">
                {{ $dosen->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>