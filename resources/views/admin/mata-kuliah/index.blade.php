<x-admin-layout>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <div class="mb-4">
                    <h1 class="page-title mb-0">Manajemen Mata Kuliah</h1>
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                            <li class="breadcrumb-item active">Mata Kuliah</li>
                        </ol>
                    </nav>
                </div>

                <!-- Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <x-admin.stat-card 
                            icon="book-fill" 
                            color="blue" 
                            title="Total Mata Kuliah" 
                            value="142">
                        </x-admin.stat-card>
                    </div>
                    <div class="col-md-6">
                        <x-admin.stat-card 
                            icon="check-circle-fill" 
                            color="green" 
                            title="Aktif" 
                            value="128">
                        </x-admin.stat-card>
                    </div>
                </div>

                <!-- Filter -->
                <div class="table-card mb-4">
                    <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div style="position: relative;">
                                    <input type="text" class="form-control" placeholder="Cari nama Mata Kuliah...">
                                    <i class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex gap-2">
                                <select class="form-select">
                                    <option>Semua Semester</option>
                                    <option>Semester 1</option>
                                    <option>Semester 2</option>
                                </select>
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
                        <table class="table table-hover">
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
                                        <td>{{ $mk->dosen?->name ?? 'Belum ditentukan' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="#" class="action-btn action-btn-view" title="Lihat"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route('admin.mata-kuliah.edit', $mk->id) }}" class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                                                <form method="POST" action="#" style="display:inline-block;">
                                                    @csrf
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
                    <div style="display: flex; gap: 1rem;">
                        <i class="bi bi-info-circle" style="color: #f59e0b; font-size: 1.25rem; flex-shrink: 0;"></i>
                        <div>
                            <strong style="color: #92400e;">Catatan Akademik</strong>
                            <p style="margin: 0.5rem 0 0 0; color: #78350f; font-size: 0.9rem;">Penggalian pada situs Mata Kuliah tidak membuktikan sistem perangkat tuntas, kecerdasan buatan, lalu bermacam solusi ketertarikan kemampuan mata kuliah jadi lebih dulu ketahui dengan matang sebelum menambahkan ke mana kuliah baru.</p>
                        </div>
                    </div>
                </div>
            </div>

            </div>
        </div>
    </div>
</x-admin-layout>
