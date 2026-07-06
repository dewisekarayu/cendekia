<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-0">Manajemen Dosen</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item active">Data Dosen</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Dosen
            </a>
        </div>

        <!-- Filter Section -->
        <div class="table-card">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="position: relative;">
                            <input type="text" class="form-control" placeholder="Cari Nama / NIDN...">
                            <i class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <select class="form-select">
                            <option>Semua Program Studi</option>
                            <option>Teknik Informatika</option>
                            <option>Sistem Informasi</option>
                        </select>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="px-4 pt-3 pb-2 text-muted">
                <small>Menampilkan {{ $dosen->firstItem() }}-{{ $dosen->lastItem() }} dari {{ $dosen->total() }} data dosen</small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>FOTO & NAMA</th>
                            <th>NIDN</th>
                            <th>PROGRAM STUDI</th>
                            <th>KONTAK</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dosen as $item)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($item->name) }}" 
                                             style="width: 40px; height: 40px; border-radius: 50%;" alt="{{ $item->name }}">
                                        <div>
                                            <div style="font-weight: 600;">{{ $item->name }}</div>
                                            <small style="color: #9ca3af;">{{ $item->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ str_pad((string) $item->id, 8, '0', STR_PAD_LEFT) }}</td>
                                <td><span class="badge" style="background-color: #dbeafe; color: #0284c7;">Program Studi</span></td>
                                <td>+62-8{{ $item->id }}{{ $item->id % 10 }}</td>
                                <td><span class="badge badge-status badge-aktif">Aktif</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn action-btn-view" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="action-btn action-btn-edit" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="action-btn action-btn-delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data dosen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.5rem;">
                {{ $dosen->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
