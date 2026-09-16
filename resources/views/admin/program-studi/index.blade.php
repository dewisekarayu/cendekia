@extends('layouts.admin')

@section('title', 'Manajemen Program Studi')

@section('content')
<div class="container-fluid px-0">
    {{-- Header Page --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">Manajemen Program Studi</h1>
            <p class="text-muted mb-2" style="font-size: 0.875rem;">Kelola seluruh data program studi dan jenjang akademik di lingkungan kampus.</p>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><span class="text-slate-500">Master Data</span></li>
                    <li class="breadcrumb-item active" aria-current="page">Program Studi</li>
                </ol>
            </nav>
        </div>

        <a href="{{ route('admin.program-studi.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Program Studi</span>
        </a>
    </div>

    {{-- Flash Alert --}}
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #ecfdf5; color: #065f46;" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <span class="fw-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Table Card Container --}}
    <div class="table-card">
        <div class="p-3.5 sm:p-4 border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="position-relative flex-grow-1" style="max-width: 480px;">
                    <input type="text" id="liveSearchProdi" class="form-control ps-4" value="{{ $search ?? '' }}" placeholder="Cari Kode atau Nama Program Studi..." style="height: 44px; padding-right: 2.75rem;" autocomplete="off">
                    <div id="searchSpinner" class="spinner-border spinner-border-sm text-secondary d-none" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);" role="status"></div>
                    <i id="searchIcon" class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem;"></i>
                </div>
            </div>
        </div>

        {{-- DOM Target Penggantian Tabel Otomatis --}}
        <div id="tableContainer">
            @include('admin.program-studi.table')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('liveSearchProdi');
        const tableContainer = document.getElementById('tableContainer');
        const searchIcon = document.getElementById('searchIcon');
        const spinner = document.getElementById('searchSpinner');
        let typingTimer;

        searchInput.addEventListener('input', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(performSearch, 350);
        });

        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'perPageSelect') {
                performSearch(1);
            }
        });

        function performSearch(page = 1) {
            searchIcon.classList.add('d-none');
            spinner.classList.remove('d-none');

            const keyword = searchInput.value;
            const perPageEl = document.getElementById('perPageSelect');
            const perPage = perPageEl ? perPageEl.value : 10;
            const url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.set('search', keyword);
            url.searchParams.set('page', page);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('ajax', '1');

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    tableContainer.innerHTML = html;
                    spinner.classList.add('d-none');
                    searchIcon.classList.remove('d-none');
                    
                    const browserUrl = new URL(window.location.origin + window.location.pathname);
                    if(keyword) browserUrl.searchParams.set('search', keyword);
                    if(perPage) browserUrl.searchParams.set('per_page', perPage);
                    window.history.pushState({}, '', browserUrl);
                })
                .catch(error => {
                    console.error('Gagal memuat data:', error);
                    spinner.classList.add('d-none');
                    searchIcon.classList.remove('d-none');
                });
        }

        // Intercept pagination links
        document.addEventListener('click', function (e) {
            const link = e.target.closest('#tableContainer .pagination a');
            if (link) {
                e.preventDefault();
                const urlParams = new URLSearchParams(link.getAttribute('href').split('?')[1]);
                const page = urlParams.get('page') || 1;
                performSearch(page);
            }
        });
    });
</script>
@endpush