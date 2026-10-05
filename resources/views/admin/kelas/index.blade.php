@extends('layouts.admin')

@section('title', 'Manajemen Kelas Perkuliahan')

@section('content')
<div class="mb-4">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
        <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Akademik</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Kelas Perkuliahan</li>
        </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Kelas Perkuliahan & Team Teaching</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Kelola alokasi rombongan belajar, dosen pengampu, ruang, dan jadwal.</p>
        </div>

        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Buat Kelas Baru</span>
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #ecfdf5; color: #065f46;" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <span class="fw-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-card">
        <div class="p-4 border-bottom">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <div class="position-relative">
                        <input type="text" id="searchInput" class="form-control" placeholder="Cari Kode Kelas, Mata Kuliah, Dosen..." value="{{ $search ?? '' }}" style="border-radius: 8px; padding: 0.6rem 1rem 0.6rem 2.5rem; border-color: #e2e8f0; font-size: 0.9rem;">
                        <i class="bi bi-search position-absolute text-muted" style="left: 12px; top: 50%; transform: translateY(-50%);" id="searchIcon"></i>
                        <div class="spinner-border spinner-border-sm text-primary position-absolute d-none" role="status" style="left: 12px; top: 50%; transform: translateY(-50%); width: 1rem; height: 1rem;" id="searchSpinner"></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tableContainer">
            @include('admin.kelas.table', ['kelasList' => $kelasList])
        </div>
    </div>
@endsection

@push('styles')
<style>
    .badge-status {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .badge-status-aktif { background-color: rgba(34, 197, 94, 0.1); color: #16a34a; }
    .badge-status-nonaktif { background-color: rgba(100, 116, 139, 0.1); color: #64748b; }
    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
    }
    .action-buttons { display: flex; gap: 0.5rem; }
    .action-btn {
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px; border: none; transition: all 0.2s ease;
        text-decoration: none;
    }
    .action-btn-view { background-color: rgba(100, 116, 139, 0.1); color: #64748b; }
    .action-btn-view:hover { background-color: rgba(100, 116, 139, 0.2); color: #475569; }
    .action-btn-edit { background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .action-btn-edit:hover { background-color: rgba(59, 130, 246, 0.2); color: #2563eb; }
    .action-btn-delete { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .action-btn-delete:hover { background-color: rgba(239, 68, 68, 0.2); color: #dc2626; }
    
    .team-teaching-badge {
        font-size: 0.7rem;
        background-color: #f1f5f9;
        color: #475569;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        margin-left: 0.3rem;
        font-weight: 600;
        border: 1px solid #e2e8f0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const searchIcon = document.getElementById('searchIcon');
        const spinner = document.getElementById('searchSpinner');
        const tableContainer = document.getElementById('tableContainer');
        let debounceTimer;

        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    performSearch(1);
                }, 300);
            });
        }

        document.addEventListener('click', function (e) {
            const paginationLink = e.target.closest('.pagination a');
            if (paginationLink) {
                e.preventDefault();
                const url = new URL(paginationLink.href);
                const page = url.searchParams.get('page');
                performSearch(page);
            }
        });

        document.addEventListener('change', function (e) {
            if (e.target && e.target.id === 'perPageSelect') {
                performSearch(1);
            }
        });

        function performSearch(page = 1) {
            searchIcon.classList.add('d-none');
            spinner.classList.remove('d-none');

            const keyword = searchInput ? searchInput.value : '';
            const perPageEl = document.getElementById('perPageSelect');
            const perPage = perPageEl ? perPageEl.value : 10;
            const url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.set('search', keyword);
            url.searchParams.set('page', page);
            url.searchParams.set('per_page', perPage);
            url.searchParams.set('ajax', '1');

            fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    tableContainer.innerHTML = html;
                    spinner.classList.add('d-none');
                    searchIcon.classList.remove('d-none');
                    
                    const browserUrl = new URL(window.location.origin + window.location.pathname);
                    if(keyword) browserUrl.searchParams.set('search', keyword);
                    if(perPage) browserUrl.searchParams.set('per_page', perPage);
                    window.history.pushState({}, '', browserUrl);
                });
        }
    });
</script>
@endpush
