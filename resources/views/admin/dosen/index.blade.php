<x-admin-layout>
    <div class="container-fluid px-0">
        {{-- Flash Message Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #ecfdf5; color: #065f46;" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <span class="fw-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="page-title mb-1">Manajemen Dosen</h1>
                <p class="text-muted mb-2" style="font-size: 0.875rem;">Kelola seluruh profil dosen, nomor induk (NIDN), dan status penugasan program studi.</p>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><span class="text-slate-500">Master Data</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Dosen</li>
                    </ol>
                </nav>
            </div>
            <!-- Tambah Dosen Button & Import CSV -->
            <div class="d-flex gap-2">
                <a href="{{ route('admin.dosen.import.view') }}" class="btn btn-outline-success d-flex align-items-center">
                    <i class="bi bi-file-earmark-excel me-2"></i> Impor CSV
                </a>
                <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="background-color: #002B6B; border: none;">
                    <i class="bi bi-person-plus-fill"></i> Tambah Dosen
                </a>
            </div>
        </div>

        <div class="table-card">
            {{-- Bagian Form Filter & Pencarian Aktif --}}
            <div class="p-3.5 sm:p-4 border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
                <div class="row g-3 align-items-center">
                    {{-- Input Pencarian Otomatis (Live Search) --}}
                    <div class="col-md-7">
                        <div class="position-relative">
                            <input type="text" id="liveSearchInput" name="search" class="form-control ps-4" value="{{ $search ?? '' }}" placeholder="Cari Nama, NIDN, atau Email Dosen..." style="height: 44px; padding-right: 2.75rem;" autocomplete="off">
                            <div id="searchSpinner" class="spinner-border spinner-border-sm text-secondary d-none" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);" role="status"></div>
                            <i id="searchIcon" class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem;"></i>
                        </div>
                    </div>
                    
                    {{-- Dropdown Filter Program Studi --}}
                    <div class="col-md-5 d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
                        <form method="GET" action="{{ route('admin.dosen.index') }}" id="filterForm" class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0">
                            <input type="hidden" name="search" id="hiddenSearchInput" value="{{ $search ?? '' }}">
                            
                            <select name="program_studi_id" id="prodiSelect" class="form-select" style="height: 44px; min-width: 180px;">
                                <option value="">Semua Program Studi</option>
                                @foreach ($programStudiList as $prodi)
                                    <option value="{{ $prodi->id }}" {{ ($prodiFilter ?? '') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            
                            @if(($search ?? '') || ($prodiFilter ?? '') || request('per_page'))
                                <a href="{{ route('admin.dosen.index') }}" class="btn btn-light border d-flex align-items-center justify-content-center px-3" style="border-radius: 0.75rem; height: 44px;" title="Reset Filter">
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
        const doneTypingInterval = 350;

        searchInput.addEventListener('keyup', function () {
            clearTimeout(typingTimer);
            hiddenSearchInput.value = searchInput.value;
            typingTimer = setTimeout(performSearch, doneTypingInterval);
        });

        searchInput.addEventListener('keydown', function () {
            clearTimeout(typingTimer);
        });

        prodiSelect.addEventListener('change', performSearch);

        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'perPageSelect') {
                performSearch(1);
            }
        });

        function performSearch(page = 1) {
            searchIcon.classList.add('d-none');
            spinner.classList.remove('d-none');

            const searchValue = searchInput.value;
            const prodiValue = prodiSelect.value;
            const perPageEl = document.getElementById('perPageSelect');
            const perPageValue = perPageEl ? perPageEl.value : 10;

            const url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.set('search', searchValue);
            url.searchParams.set('page', page);
            url.searchParams.set('per_page', perPageValue);
            if (prodiValue) {
                url.searchParams.set('program_studi_id', prodiValue);
            }
            url.searchParams.set('ajax', '1');

            fetch(url)
                .then(response => response.text())
                .then(data => {
                    tableContainer.innerHTML = data;
                    spinner.classList.add('d-none');
                    searchIcon.classList.remove('d-none');

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

        // Intercept pagination clicks
        document.addEventListener('click', function (e) {
            const paginationLink = e.target.closest('#tableContainer .pagination a');
            if (paginationLink) {
                e.preventDefault();
                const urlParams = new URLSearchParams(paginationLink.getAttribute('href').split('?')[1]);
                const page = urlParams.get('page') || 1;
                performSearch(page);
            }
        });
    });
</script>