<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-0">Manajemen Mahasiswa</h1>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                        <li class="breadcrumb-item active">Data Mahasiswa</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
            </a>
        </div>

        <!-- Stats Cards Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="people-fill" 
                    color="blue" 
                    title="Total Mahasiswa" 
                    value="{{ number_format($mahasiswa->total(), 0, ',', '.') }}">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="check-circle-fill" 
                    color="green" 
                    title="Mahasiswa Aktif" 
                    value="{{ number_format($mahasiswa->total(), 0, ',', '.') }}">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="pencil-square" 
                    color="yellow" 
                    title="Cuti Akademik" 
                    value="0">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="x-circle-fill" 
                    color="red" 
                    title="Non Aktif" 
                    value="0">
                </x-admin.stat-card>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="table-card">
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="position: relative;">
                            <input type="text" class="form-control" placeholder="Cari Nama / NIM...">
                            <i class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <select class="form-select">
                            <option>Semua Prodis</option>
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
                <small>Menampilkan {{ $mahasiswa->firstItem() }}-{{ $mahasiswa->lastItem() }} dari {{ $mahasiswa->total() }} data mahasiswa</small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>NAMA LENGKAP</th>
                            <th>PROGRAM STUDI</th>
                            <th>SEMESTER</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $item)
                            <tr>
                                <td>{{ str_pad((string) $item->id, 8, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($item->name) }}" 
                                             style="width: 32px; height: 32px; border-radius: 50%;" alt="{{ $item->name }}">
                                        <div>
                                            <div style="font-weight: 600;">{{ $item->name }}</div>
                                            <small style="color: #9ca3af;">{{ $item->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Program Studi</td>
                                <td>{{ $item->id % 8 + 1 }}</td>
                                <td><span class="badge badge-status badge-aktif">AKTIF</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn action-btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                                        <button class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.5rem;">
                {{ $mahasiswa->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
