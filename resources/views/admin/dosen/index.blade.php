<x-admin-layout>
    <div class="container-fluid py-3">
        {{-- Flash Message Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 8px;">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
            <!-- Tambah Dosen Button & Import CSV -->
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#importDosenModal">
                    <i class="bi bi-file-earmark-excel me-2"></i> Impor CSV
                </button>
                <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="background-color: #002B6B; border: none;">
                    <i class="bi bi-person-plus-fill"></i> Tambah Dosen
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden; background: white;">
            {{-- Bagian Form Filter & Pencarian Aktif --}}
            <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <div class="row g-3">
                    {{-- Input Pencarian Otomatis (Live Search) --}}
                    <div class="col-md-6">
                        <div style="position: relative;">
                            <input type="text" id="liveSearchInput" name="search" class="form-control" value="{{ $search ?? '' }}" placeholder="Cari Nama / NIDN / Email..." style="border-radius: 8px; padding: 0.6rem 2.5rem 0.6rem 1rem;" autocomplete="off">
                            
                            {{-- Spinner Loading kecil saat mengetik --}}
                            <div id="searchSpinner" class="spinner-border spinner-border-sm text-secondary d-none" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);" role="status"></div>
                            <i id="searchIcon" class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
                        </div>
                    </div>
                    
                    {{-- Dropdown Filter Program Studi --}}
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.dosen.index') }}" id="filterForm" class="d-flex gap-2">
                            {{-- Input hidden untuk tetap membawa value search yang sedang diketik ketika prodi diubah --}}
                            <input type="hidden" name="search" id="hiddenSearchInput" value="{{ $search ?? '' }}">
                            
                            <select name="program_studi_id" id="prodiSelect" class="form-select" style="border-radius: 8px;">
                                <option value="">Semua Program Studi</option>
                                @foreach ($programStudiList as $prodi)
                                    <option value="{{ $prodi->id }}" {{ ($prodiFilter ?? '') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <button type="submit" class="btn btn-outline-secondary d-flex align-items-center gap-2" style="border-radius: 8px; white-space: nowrap; color: #475569;">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            
                            @if(($search ?? '') || ($prodiFilter ?? ''))
                                <a href="{{ route('admin.dosen.index') }}" class="btn btn-light border d-flex align-items-center justify-content-center" style="border-radius: 8px;" title="Reset Filter">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Container Utama Tabel (Akan di-refresh otomatis oleh JavaScript) --}}
            <div id="tableContainer">
                @include('admin.dosen.table')
            </div>
        </div>
    </div>

    <!-- Modal Import CSV Dosen -->
    <div class="modal fade" id="importDosenModal" tabindex="-1" aria-labelledby="importDosenModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="importDosenModalLabel">📥 Impor Data Dosen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                        <div class="alert alert-info border-0 mb-3" style="background-color: #f0f7ff; color: #002B6B;">
                            <h6 class="fw-bold mb-2" style="font-size: 0.9rem;"><i class="bi bi-info-circle-fill me-1"></i> SOP Impor Dosen:</h6>
                            <ol class="mb-2 text-muted" style="font-size: 0.85rem; padding-left: 1.2rem;">
                                <li class="mb-1">Pastikan file Anda berekstensi <strong>.csv</strong>.</li>
                                <li class="mb-1">Baris paling atas (judul kolom) akan diabaikan oleh sistem.</li>
                                <li class="mb-1">Urutan 4 kolom wajib dari kiri ke kanan: <br><strong class="text-dark">Nama Lengkap &rarr; NIP &rarr; Email &rarr; ID Program Studi</strong></li>
                                <li>Password otomatis (*default*) disetel sama persis dengan angka NIP.</li>
                            </ol>
                            <a href="data:text/csv;charset=utf-8,Nama Lengkap,NIP,Email,ID Program Studi%0AProf. Budi,19790001,budi@dosen.cendekia.ac.id,1" download="template_dosen.csv" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center mt-2" style="font-size: 0.8rem; font-weight: 600;">
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
</x-admin-layout>

{{-- JavaScript Ajax Live Search --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('liveSearchInput');
        const hiddenSearchInput = document.getElementById('hiddenSearchInput');
        const prodiSelect = document.getElementById('prodiSelect');
        const tableContainer = document.getElementById('tableContainer');
        const searchIcon = document.getElementById('searchIcon');
        const spinner = document.getElementById('searchSpinner');
        
        let typingTimer;
        const doneTypingInterval = 350; // Jeda waktu tunggu setelah ketikan terakhir (350 milidetik)

        searchInput.addEventListener('keyup', function () {
            clearTimeout(typingTimer);
            
            // Salin teks ke input tersembunyi agar form filter prodi tetap sinkron
            hiddenSearchInput.value = searchInput.value;

            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        searchInput.addEventListener('keydown', function () {
            clearTimeout(typingTimer);
        });

        function performSearch() {
            // Tampilkan animasi loading spinner menggantikan icon kaca pembesar
            searchIcon.classList.add('d-none');
            spinner.classList.remove('d-none');

            const searchValue = searchInput.value;
            const prodiValue = prodiSelect.value;

            // Susun URL query string secara dinamis
            const url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.set('search', searchValue);
            if (prodiValue) {
                url.searchParams.set('program_studi_id', prodiValue);
            }
            url.searchParams.set('ajax', '1'); // Penanda request backend via AJAX

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    // Masukkan potongan HTML tabel baru ke dalam container
                    tableContainer.innerHTML = data;

                    // Sembunyikan loading spinner kembali
                    spinner.classList.add('d-none');
                    searchIcon.classList.remove('d-none');

                    // Ubah URL browser tanpa reload halaman agar link pencarian bisa dibagikan/di-bookmark
                    const browserUrl = new URL(url);
                    browserUrl.searchParams.delete('ajax');
                    window.history.pushState({}, '', browserUrl);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    spinner.classList.add('d-none');
                    searchIcon.classList.remove('d-none');
                });
        }
    });
</script>