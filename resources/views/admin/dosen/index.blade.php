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
            <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 0.5rem; background-color: #002B6B; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                <i class="bi bi-plus-circle"></i> Tambah Dosen
            </a>
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