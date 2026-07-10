@extends('layouts.admin')

@section('title', 'Manajemen Program Studi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="page-title mb-2">Manajemen Program Studi</h1>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: #002B6B; font-weight: 600; text-decoration: none;">Dashboard</a></li>
                    <li class="breadcrumb-item"><span style="color: #334155;">Master Data</span></li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #6b7280;">Program Studi</li>
                </ol>
            </nav>
        </div>

        <a href="{{ route('admin.program-studi.create') }}" class="btn btn-primary px-4 py-2 d-flex align-items-center gap-2" style="border-radius: 0.5rem; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
            <i class="bi bi-plus"></i>
            Tambah Program Studi
        </a>
    </div>

    <div class="table-card" style="margin-top: 0; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <div style="padding: 1.5rem; border-bottom: 1px solid rgba(0, 43, 107, 0.08);">
            <div style="position: relative; max-width: 620px;">
                <input type="text" id="liveSearchProdi" class="form-control" value="{{ $search ?? '' }}" placeholder="Cari Kode atau Nama Program Studi..." style="height: 46px; padding-right: 3rem;" autocomplete="off">
                <div id="searchSpinner" class="spinner-border spinner-border-sm text-secondary d-none" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);" role="status"></div>
                <i id="searchIcon" class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem;"></i>
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
            typingTimer = setTimeout(function () {
                searchIcon.classList.add('d-none');
                spinner.classList.remove('d-none');

                const keyword = searchInput.value;
                const url = new URL(window.location.href);
                url.searchParams.set('search', keyword);
                url.searchParams.set('ajax', '1');

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        spinner.classList.add('d-none');
                        searchIcon.classList.remove('d-none');
                        
                        const browserUrl = new URL(window.location.href);
                        keyword ? browserUrl.searchParams.set('search', keyword) : browserUrl.searchParams.delete('search');
                        window.history.pushState({}, '', browserUrl);
                    });
            }, 350);
        });

        // Intercept pagination links
        document.addEventListener('click', function (e) {
            const link = e.target.closest('#tableContainer .pagination a');
            if (link) {
                e.preventDefault();
                const targetUrl = new URL(link.getAttribute('href'));
                targetUrl.searchParams.set('ajax', '1');

                fetch(targetUrl)
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        targetUrl.searchParams.delete('ajax');
                        window.history.pushState({}, '', targetUrl);
                    });
            }
        });
    });
</script>
@endpush