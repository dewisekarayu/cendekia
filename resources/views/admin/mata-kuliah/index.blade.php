<x-admin-layout>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12">

                <!-- Header -->
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                    <div>
                        <h1 class="page-title mb-1">Manajemen Mata Kuliah</h1>
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">Kelola seluruh kurikulum dan distribusi dosen pengampu.</p>
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Mata Kuliah</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('admin.mata-kuliah.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="background-color:#002B6B; border-color:#002B6B; white-space:nowrap;">
                        <i class="bi bi-plus-lg"></i> Tambah Mata Kuliah
                    </a>
                </div>

                <!-- Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <x-admin.stat-card
                            icon="book-fill"
                            color="blue"
                            title="Total Mata Kuliah"
                            value="{{ $mataKuliah->total() ?? 142 }}">
                        </x-admin.stat-card>
                    </div>
                    <div class="col-6 col-md-3">
                        <x-admin.stat-card
                            icon="check-circle-fill"
                            color="green"
                            title="Aktif"
                            value="128">
                        </x-admin.stat-card>
                    </div>
                    <div class="col-6 col-md-3">
                        <x-admin.stat-card
                            icon="pause-circle-fill"
                            color="teal"
                            title="Nonaktif"
                            value="12">
                        </x-admin.stat-card>
                    </div>
                    <div class="col-6 col-md-3">
                        <x-admin.stat-card
                            icon="exclamation-circle-fill"
                            color="red"
                            title="Belum Ada Dosen"
                            value="6">
                        </x-admin.stat-card>
                    </div>
                </div>

                <!-- Filter -->
                <div class="table-card mb-4">
                    <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <div style="position: relative;">
                                    <input type="text" class="form-control" placeholder="Cari Kode atau Nama Mata Kuliah...">
                                    <i class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <select class="form-select">
                                    <option>Semua Semester</option>
                                    <option>Semester 1</option>
                                    <option>Semester 2</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select class="form-select">
                                    <option>Semua Program Studi</option>
                                    <option>Teknik Informatika</option>
                                    <option>Sistem Informasi</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>KODE MK</th>
                                    <th>NAMA MATA KULIAH</th>
                                    <th>SKS</th>
                                    <th>SEMESTER</th>
                                    <th>DOSEN PENGAMPU</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($mataKuliah as $mk)
                                    <tr>
                                        <td><strong>{{ $mk->kode_mk }}</strong></td>
                                        <td>{{ $mk->nama_mk }}</td>
                                        <td>{{ $mk->sks }}</td>
                                        <td>{{ $mk->semester_ke }}</td>
                                        <td>{{ $mk->kelasPerkuliahan->first()?->dosen?->name ?? 'Belum ditentukan' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="#" class="action-btn action-btn-view" title="Lihat"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('admin.mata-kuliah.edit', $mk->id) }}" class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                                <form method="POST" action="{{ route('admin.mata-kuliah.destroy', $mk->id) }}" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada data mata kuliah.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <nav aria-label="Page navigation" style="padding: 1.5rem;">
                        {{ $mataKuliah->links() }}
                    </nav>
                </div>

                <!-- Academic Notes -->
                <div style="background: #fef9e7; border-left: 4px solid #f59e0b; padding: 1.5rem; border-radius: 0.5rem;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                        <div style="display: flex; gap: 1rem;">
                            <i class="bi bi-info-circle" style="color: #f59e0b; font-size: 1.25rem; flex-shrink: 0;"></i>
                            <div>
                                <strong style="color: #92400e;">Catatan Akademik</strong>
                                <p style="margin: 0.5rem 0 0 0; color: #78350f; font-size: 0.9rem;">
                                    Perubahan pada data Mata Kuliah akan otomatis disinkronkan dengan modul Kalender Akademik dan Penjadwalan. Pastikan Dosen Pengampu telah terverifikasi sebelum menetapkannya ke mata kuliah aktif.
                                </p>
                            </div>
                        </div>
                        <a href="#" style="color: #92400e; font-weight: 600; font-size: 0.85rem; white-space: nowrap; text-decoration: underline;">Pelajari Lebih Lanjut</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>