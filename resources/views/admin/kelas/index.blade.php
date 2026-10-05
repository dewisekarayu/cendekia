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
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                        <th>MATA KULIAH & KELAS</th>
                        <th>DOSEN PENGAMPU</th>
                        <th>JADWAL & RUANGAN</th>
                        <th class="text-center">MAHASISWA</th>
                        <th>STATUS</th>
                        <th class="text-center" style="width: 120px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelasList as $kelas)
                        <tr>
                            <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                                {{ ($kelasList->currentPage() - 1) * $kelasList->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <div class="fw-bold text-slate-800 dark:text-white" style="font-size: 0.9rem;">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge-code" style="font-size: 0.725rem;">{{ $kelas->mataKuliah?->kode_mk ?? '-' }}</span>
                                    <span class="text-slate-400" style="font-size: 0.8rem;">Kelas {{ $kelas->kode_kelas }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-slate-700 dark:text-slate-300" style="font-size: 0.875rem;">
                                    {{ $kelas->dosen?->name ?? 'Belum Ditentukan' }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-slate-800 dark:text-slate-200" style="font-size: 0.85rem;">
                                    {{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}
                                </div>
                                <small class="text-slate-400"><i class="bi bi-geo-alt me-1"></i>{{ $kelas->ruangan }}</small>
                            </td>
                            <td class="text-center">
                                <span class="badge px-2.5 py-1.5 fw-bold" style="background-color: #eff6ff; color: #1d4ed8; border-radius: 0.5rem;">
                                    <i class="bi bi-people-fill me-1"></i> {{ $kelas->mahasiswa->count() }} Mhs
                                </span>
                            </td>
                            <td>
                                @if ($kelas->is_active)
                                    <span class="badge-status badge-status-aktif">
                                        <span class="status-dot"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="badge-status badge-status-nonaktif">
                                        <span class="status-dot"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons justify-content-center">
                                    <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="action-btn action-btn-edit" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus kelas ini? Semua data mahasiswa yang terdaftar akan ikut terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete" title="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="bi bi-calendar-check fs-1 text-slate-300 dark:text-slate-600 mb-2"></i>
                                    <p class="fw-semibold mb-0">Belum ada kelas perkuliahan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-4 py-3 border-top flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <form method="GET" action="{{ route('admin.kelas.index') }}" class="d-flex align-items-center gap-2">
                    @foreach(request()->except(['per_page', 'page']) as $k => $v)
                        @if(is_array($v))
                            @foreach($v as $item)
                                <input type="hidden" name="{{ $k }}[]" value="{{ $item }}">
                            @endforeach
                        @else
                            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                        @endif
                    @endforeach
                    <span class="text-xs fw-bold text-slate-500 uppercase tracking-wider text-nowrap">Show:</span>
                    <select name="per_page" class="form-select form-select-sm" style="width: 80px; border-radius: 0.5rem; height: 34px;" onchange="this.form.submit()">
                        <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
                        <option value="25" @selected(($perPage ?? 10) == 25)>25</option>
                        <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
                        <option value="100" @selected(($perPage ?? 10) == 100)>100</option>
                    </select>
                </form>
                <small class="text-muted">Menampilkan {{ $kelasList->firstItem() ?? 0 }}-{{ $kelasList->lastItem() ?? 0 }} dari {{ $kelasList->total() }} kelas</small>
            </div>
            @if($kelasList->hasPages())
                <div>
                    {{ $kelasList->links('pagination::bootstrap-5') }}
                </div>
            @endif
        <div>
            <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold px-4 py-2" style="background-color: #002B6B; border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                <i class="bi bi-plus-lg"></i>
                Buat Kelas Baru
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: white; overflow: hidden;">
    <div class="p-4 border-bottom bg-white">
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
