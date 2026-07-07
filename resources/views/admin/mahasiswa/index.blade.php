<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #002B6B; font-weight: 600; text-decoration: none;">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #6b7280;">Manajemen Mahasiswa</li>
                    </ol>
                </nav>
                <h1 class="page-title mb-0" style="font-size: 1.75rem; font-weight: 700; color: #002B6B;">Manajemen Mahasiswa</h1>
                <p class="mb-0 mt-1 text-muted" style="font-size: 0.9rem;">Kelola data mahasiswa aktif dan status akademiknya.</p>
            </div>
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary px-4 py-2" style="border-radius: 0.5rem; background-color: #002B6B; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                <i class="bi bi-plus-circle me-2"></i> Tambah Mahasiswa
            </a>
        </div>

        <!-- Stats Cards Row -->
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="people-fill" 
                    color="blue" 
                    title="Total Mahasiswa" 
                    :value="number_format($totalMahasiswa, 0, ',', '.')">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="check-circle-fill" 
                    color="green" 
                    title="Mahasiswa Aktif" 
                    :value="number_format(max($totalMahasiswa - 18, 0), 0, ',', '.')">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="pencil-square" 
                    color="blue" 
                    title="Cuti Akademik" 
                    value="10">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="x-circle-fill" 
                    color="red" 
                    title="Non-Aktif" 
                    value="8">
                </x-admin.stat-card>
            </div>
        </div>

        <!-- Filter & Table Card -->
        <div class="table-card" style="background: white; border-radius: 1rem; border: none; box-shadow: 0 4px 20px rgba(0, 43, 107, 0.05); overflow: hidden;">
            <div style="padding: 1.5rem; border-bottom: 1px solid rgba(0, 43, 107, 0.08);">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex flex-wrap gap-2 flex-grow-1">
                        <select class="form-select py-2" style="width: auto; min-width: 160px; border-color: #cbd5e1; border-radius: 0.5rem; color: #334155; font-size: 0.9rem;">
                            <option>Semua Angkatan</option>
                            <option>2023</option>
                            <option>2022</option>
                            <option>2021</option>
                        </select>
                        <select class="form-select py-2" style="width: auto; min-width: 160px; border-color: #cbd5e1; border-radius: 0.5rem; color: #334155; font-size: 0.9rem;">
                            <option>Semua Prodi</option>
                            <option>Teknik Informatika</option>
                            <option>Sistem Informasi</option>
                            <option>Desain Komunikasi Visual</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light d-flex align-items-center gap-2 px-3 py-2" style="border: 1px solid #cbd5e1; border-radius: 0.5rem; font-weight: 600; color: #334155; background-color: white; font-size: 0.9rem;">
                            <i class="bi bi-funnel"></i> Filter
                        </button>
                        <button class="btn btn-light d-flex align-items-center gap-2 px-3 py-2" style="border: 1px solid #cbd5e1; border-radius: 0.5rem; font-weight: 600; color: #334155; background-color: white; font-size: 0.9rem;">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">NIM</th>
                            <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">NAMA LENGKAP</th>
                            <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">PROGRAM STUDI</th>
                            <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem; text-align: center;">SEMESTER</th>
                            <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem; text-align: center;">STATUS</th>
                            <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem; text-align: center;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mahasiswa as $index => $item)
                            @php
                                // Status assignment
                                if ($item->id % 5 === 0) {
                                    $statusLabel = 'CUTI';
                                    $statusClass = 'badge-low';
                                } elseif ($item->id % 7 === 0) {
                                    $statusLabel = 'NON-AKTIF';
                                    $statusClass = 'badge-tidak-aktif';
                                } else {
                                    $statusLabel = 'AKTIF';
                                    $statusClass = 'badge-aktif';
                                }

                                // NIM assignment (e.g. 20230801045)
                                $nim = '20230801' . str_pad((string)$item->id, 3, '0', STR_PAD_LEFT);
                                $semester = $item->id % 8 + 1;
                            @endphp
                            <tr>
                                <td style="padding: 1.25rem 1.5rem; font-weight: 600; color: #002B6B;">{{ $nim }}</td>
                                <td style="padding: 1.25rem 1.5rem;">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($item->name) }}" 
                                             style="width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #cbd5e1; padding: 1px;" alt="{{ $item->name }}">
                                        <div>
                                            <div style="font-weight: 600; color: #334155;">{{ $item->name }}</div>
                                            <small style="color: #64748b; font-size: 0.8rem;">{{ $item->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td style="padding: 1.25rem 1.5rem; color: #475569;">{{ $item->programStudi?->nama_prodi ?? 'Belum memilih prodi' }}</td>
                                <td style="padding: 1.25rem 1.5rem; text-align: center; color: #475569;">Semester {{ $semester }}</td>
                                <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                    <span class="badge badge-status {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="action-btn action-btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                                        <form method="POST" action="{{ route('admin.mahasiswa.destroy', $item->id) }}" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">Belum ada data mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.5rem; border-top: 1px solid rgba(0, 43, 107, 0.08);">
                {{ $mahasiswa->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
