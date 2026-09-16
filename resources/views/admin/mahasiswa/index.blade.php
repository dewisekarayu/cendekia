<x-admin-layout>
    <div class="container-fluid px-0">
        <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="page-title mb-1">Manajemen Mahasiswa</h1>
                <p class="text-muted mb-2" style="font-size: 0.875rem;">Kelola seluruh database mahasiswa, status akademik aktif/cuti, dan program studi terdaftar.</p>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><span class="text-slate-500">Master Data</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Mahasiswa</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah Mahasiswa</span>
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #ecfdf5; color: #065f46;" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <span class="fw-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- 4 Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-sm-6">
                <x-admin.stat-card
                    icon="people-fill"
                    color="blue"
                    title="Total Mahasiswa"
                    :value="number_format($totalMahasiswa, 0, ',', '.')">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-sm-6">
                <x-admin.stat-card
                    icon="check-circle-fill"
                    color="green"
                    title="Mahasiswa Aktif"
                    :value="number_format($totalAktif, 0, ',', '.')">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-sm-6">
                <x-admin.stat-card
                    icon="pause-circle-fill"
                    color="amber"
                    title="Cuti Akademik"
                    :value="number_format($totalCuti, 0, ',', '.')">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-sm-6">
                <x-admin.stat-card
                    icon="x-circle-fill"
                    color="red"
                    title="Non-Aktif"
                    :value="number_format($totalNonAktif, 0, ',', '.')">
                </x-admin.stat-card>
            </div>
        </div>

        <div class="table-card">
            <form id="filterForm" method="GET" action="{{ route('admin.mahasiswa.index') }}" class="p-3.5 sm:p-4 border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex flex-wrap gap-2 flex-grow-1">
                        <div class="position-relative flex-grow-1" style="min-width: 220px; max-width: 360px;">
                            <input type="text" id="searchInput" name="search" value="{{ request('search') }}" class="form-control ps-4" placeholder="Cari Nama atau NIM..." style="height: 42px; padding-right: 2.5rem;" autocomplete="off">
                            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3 text-slate-400" style="font-size: 0.9rem;"></i>
                        </div>

                        <select id="prodiSelect" name="program_studi_id" class="form-select" style="width: auto; min-width: 180px; height: 42px;">
                            <option value="">Semua Program Studi</option>
                            @foreach ($programStudiList as $prodi)
                                <option value="{{ $prodi->id }}" {{ (string) request('program_studi_id') === (string) $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                            @endforeach
                        </select>

                        <select id="statusSelect" name="status" class="form-select" style="width: auto; min-width: 140px; height: 42px;">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="cuti" {{ request('status') === 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="non_aktif" {{ request('status') === 'non_aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light border d-flex align-items-center gap-2 px-3.5" style="border-radius: 0.75rem; height: 42px;" title="Reset Filter">
                            <i class="bi bi-arrow-clockwise"></i>
                            <span class="d-none d-sm-inline">Reset</span>
                        </a>
                    </div>
                </div>
            </form>

            <div id="tableContainer">
                @include('admin.mahasiswa.table')
            </div>
        </div>

        <!-- Modal Import CSV Mahasiswa -->
        <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold" id="importModalLabel">📥 Impor Data Mahasiswa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="alert alert-info border-0 mb-3" style="background-color: #f0f7ff; color: #002B6B;">
                            <h6 class="fw-bold mb-2" style="font-size: 0.9rem;"><i class="bi bi-info-circle-fill me-1"></i> SOP Impor Mahasiswa:</h6>
                            <ol class="mb-2 text-muted" style="font-size: 0.85rem; padding-left: 1.2rem;">
                                <li class="mb-1">Pastikan file Anda berekstensi <strong>.csv</strong>.</li>
                                <li class="mb-1">Baris paling atas (judul kolom) akan diabaikan oleh sistem.</li>
                                <li class="mb-1">Urutan 4 kolom wajib dari kiri ke kanan: <br><strong class="text-dark">Nama Lengkap &rarr; NIM &rarr; Email &rarr; ID Program Studi</strong></li>
                                <li>Password otomatis (*default*) disetel sama persis dengan angka NIM.</li>
                            </ol>
                            <a href="data:text/csv;charset=utf-8,Nama Lengkap,NIM,Email,ID Program Studi%0ABudi Santoso,20240001,budi@student.cendekia.ac.id,1" download="template_mahasiswa.csv" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center mt-2" style="font-size: 0.8rem; font-weight: 600;">
                                <i class="bi bi-download me-2"></i> Download Template CSV (Sudah Ada Isinya)
                            </a>
                        </div>
                        <input type="file" class="form-control" name="file_csv" accept=".csv, .txt" required>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary px-4" style="background-color: #002B6B; border: none;">Mulai Impor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const prodiSelect = document.getElementById('prodiSelect');
            const statusSelect = document.getElementById('statusSelect');
            const tableContainer = document.getElementById('tableContainer');
            
            let delayTimer;

            function fetchMahasiswa(page = 1) {
                const search = searchInput.value;
                const prodi = prodiSelect.value;
                const status = statusSelect.value;
                const perPageEl = document.getElementById('perPageSelect');
                const perPage = perPageEl ? perPageEl.value : 10;

                // Bangun parameter URL untuk request AJAX
                let url = `{{ route('admin.mahasiswa.index') }}?ajax=1&page=${page}&per_page=${perPage}&search=${encodeURIComponent(search)}&program_studi_id=${prodi}&status=${status}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                    })
                    .catch(error => console.error('Gagal memuat data mahasiswa:', error));
            }

            // Real-time Search Keyboard (dengan debounce 400ms)
            searchInput.addEventListener('input', function () {
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    fetchMahasiswa();
                }, 400);
            });

            // Ganti Filter Dropdown langsung panggil fungsi pencarian
            prodiSelect.addEventListener('change', () => fetchMahasiswa());
            statusSelect.addEventListener('change', () => fetchMahasiswa());

            // Handle perPage change via delegation
            document.addEventListener('change', function (e) {
                if (e.target && e.target.id === 'perPageSelect') {
                    fetchMahasiswa(1);
                }
            });

            // Intercept link pagination bawaan agar berjalan via AJAX
            document.addEventListener('click', function (e) {
                const paginationLink = e.target.closest('#tableContainer .pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    const urlParams = new URLSearchParams(paginationLink.getAttribute('href').split('?')[1]);
                    const page = urlParams.get('page') || 1;
                    fetchMahasiswa(page);
                }
            });
        });
    </script>
</x-admin-layout>