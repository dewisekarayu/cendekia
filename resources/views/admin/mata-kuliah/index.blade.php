@extends('layouts.admin')

@section('title', 'Manajemen Mata Kuliah')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">Manajemen Mata Kuliah</h1>
            <p class="text-muted mb-2" style="font-size: 0.875rem;">Kelola seluruh katalog kurikulum, kode mata kuliah, dan keterhubungan program studi.</p>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><span class="text-slate-500">Master Data</span></li>
                    <li class="breadcrumb-item active" aria-current="page">Mata Kuliah</li>
                </ol>
            </nav>
        </div>

        <a href="{{ route('admin.mata-kuliah.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Mata Kuliah</span>
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

    <div class="table-card">
        <div class="p-3.5 sm:p-4 border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                {{-- Kotak Input Live Search Mata Kuliah --}}
                <div class="position-relative flex-grow-1" style="max-width: 480px;">
                    <input type="text" id="liveSearchMK" class="form-control ps-4" value="{{ $search ?? '' }}" placeholder="Cari Kode atau Nama MK..." style="height: 44px; padding-right: 2.75rem;" autocomplete="off">
                    <div id="searchSpinner" class="spinner-border spinner-border-sm text-secondary d-none" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);" role="status"></div>
                    <i id="searchIcon" class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem;"></i>
                </div>
            </div>
        </div>

        {{-- Container Utama Hasil Render AJAX (Tabel Parsial) --}}
        <div id="tableContainer">
            @include('admin.mata-kuliah.table')
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('liveSearchMK');
            const tableContainer = document.getElementById('tableContainer');
            const searchIcon = document.getElementById('searchIcon');
            const spinner = document.getElementById('searchSpinner');
            let typingTimer;
            const doneTypingInterval = 350;

            searchInput.addEventListener('input', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(performSearch, doneTypingInterval);
            });

            document.addEventListener('change', function (e) {
                if (e.target && e.target.id === 'perPageSelect') {
                    performSearch(1);
                }
            });

            function performSearch(page = 1) {
                if (searchIcon) searchIcon.classList.add('d-none');
                if (spinner) spinner.classList.remove('d-none');

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
                        if (spinner) spinner.classList.add('d-none');
                        if (searchIcon) searchIcon.classList.remove('d-none');

                        const browserUrl = new URL(window.location.origin + window.location.pathname);
                        if(keyword) browserUrl.searchParams.set('search', keyword);
                        if(perPage) browserUrl.searchParams.set('per_page', perPage);
                        window.history.pushState({}, '', browserUrl);
                    })
                    .catch(error => {
                        console.error('Gagal memuat data:', error);
                        if (spinner) spinner.classList.add('d-none');
                        if (searchIcon) searchIcon.classList.remove('d-none');
                    });
            }

            // Intercept klik pagination
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
@endsection