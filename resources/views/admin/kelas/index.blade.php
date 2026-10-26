@extends('layouts.admin')

@section('title', 'Manajemen Kelas Perkuliahan')

@section('content')
<style>
    /* Button Buat Kelas Baru (Compact, Solid Navy & Responsive) */
    .btn-action-wrapper {
        width: auto;
    }
    .btn-buat-kelas {
        height: 42px !important;
        padding: 0 1.25rem !important;
        background-color: #047857 !important;
        color: #ffffff !important;
        border-radius: 0.75rem !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        border: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        text-decoration: none !important;
        white-space: nowrap !important;
        width: auto !important;
        min-width: 160px !important;
        box-shadow: 0 4px 12px rgba(4, 120, 87, 0.2) !important;
        transition: all 0.2s ease !important;
    }
    .btn-buat-kelas:hover {
        background-color: #065f46 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(4, 120, 87, 0.3) !important;
    }
    @media (max-width: 575.98px) {
        .btn-action-wrapper {
            width: 100% !important;
        }
        .btn-buat-kelas {
            width: 100% !important;
        }
    }

    /* Status Badges */
    .badge-status {
        padding: 0.35rem 0.75rem;
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

    /* Action Buttons */
    .action-buttons { display: flex; gap: 0.4rem; }
    .action-btn {
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 8px; border: none; transition: all 0.2s ease;
        text-decoration: none;
        font-size: 0.85rem;
    }
    .action-btn-view { background-color: rgba(100, 116, 139, 0.1); color: #64748b; }
    .action-btn-view:hover { background-color: rgba(100, 116, 139, 0.2); color: #475569; }
    .action-btn-edit { background-color: rgba(4, 120, 87, 0.1); color: #047857; }
    .action-btn-edit:hover { background-color: rgba(4, 120, 87, 0.2); color: #065f46; }
    .action-btn-delete { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .action-btn-delete:hover { background-color: rgba(239, 68, 68, 0.2); color: #dc2626; }
</style>

<div class="mb-4">
    <!-- Breadcrumb -->
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Akademik</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #047857; font-weight: 600;">Kelas Perkuliahan</li>
        </ol>
    </nav>

    <!-- Header & Action Button (Sleek & Fully Responsive) -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h1 class="page-title h3 fw-bold mb-1" style="color: #064e3b;">Kelas Perkuliahan & Team Teaching</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">Kelola alokasi rombongan belajar, dosen pengampu, ruang, dan jadwal.</p>
        </div>

        <div class="btn-action-wrapper">
            <a href="{{ route('admin.kelas.create') }}" 
               class="btn btn-primary btn-buat-kelas">
                <i class="bi bi-plus-lg text-white"></i>
                <span class="text-white">Buat Kelas Baru</span>
            </a>
        </div>
    </div>

    <!-- Alert Success -->
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #ecfdf5; color: #065f46;" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <span class="fw-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 1rem; overflow: hidden; background: #ffffff;">
        <!-- Filters (Clean & Responsive) -->
        <div class="card-header bg-white p-3 p-md-4 border-bottom">
            <form id="filterForm" class="row g-2.5 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-slate-700 mb-1.5" style="font-size: 0.8rem;">Pencarian</label>
                    <div class="position-relative">
                        <input type="text" 
                               id="searchInput" 
                               name="search" 
                               class="form-control" 
                               placeholder="Cari Kode Kelas, Matkul..." 
                               value="{{ $search ?? '' }}" 
                               style="height: 42px; border-radius: 0.75rem; padding-left: 40px !important; font-size: 0.875rem;">
                        <span class="position-absolute top-50 translate-middle-y d-flex align-items-center justify-content-center text-muted" style="left: 13px; pointer-events: none;">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold text-slate-700 mb-1.5" style="font-size: 0.8rem;">Program Studi</label>
                    <select name="program_studi_id" id="prodiFilter" class="form-select" style="height: 42px; border-radius: 0.75rem; font-size: 0.875rem;">
                        <option value="">Semua Program Studi</option>
                        @foreach($prodis as $p)
                            <option value="{{ $p->id }}" {{ request('program_studi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-semibold text-slate-700 mb-1.5" style="font-size: 0.8rem;">Dosen Pengampu</label>
                    <select name="dosen_id" id="dosenFilter" class="form-select" style="height: 42px; border-radius: 0.75rem; font-size: 0.875rem;">
                        <option value="">Semua Dosen</option>
                        @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ request('dosen_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3 d-flex gap-2">
                    <button type="button" 
                            class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-1.5 flex-grow-1 text-nowrap shadow-xs" 
                            id="applyFilterBtn" 
                            style="height: 42px; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600; background-color: #047857; border: none;">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span>Terapkan</span>
                    </button>
                    @if(request('search') || request('program_studi_id') || request('dosen_id'))
                        <a href="{{ route('admin.kelas.index') }}" 
                           class="btn btn-light border d-inline-flex align-items-center justify-content-center gap-1.5 text-slate-600 px-3 shadow-xs text-decoration-none" 
                           style="height: 42px; border-radius: 0.75rem; font-size: 0.875rem; font-weight: 600;" 
                           title="Reset Filter">
                            <svg class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Reset</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Class List Grouped by Lecturer -->
        <div class="card-body p-3 p-md-4 bg-light">
            <div class="d-flex flex-column gap-3">
                @forelse($groupedByDosen as $dosenId => $data)
                    <div class="card border-0 shadow-sm" x-data="{ open: false }" style="border-radius: 0.85rem; overflow: hidden;">
                        <div class="card-header bg-white border-bottom-0 p-3 p-md-3.5 d-flex justify-content-between align-items-center" 
                             @click="open = !open" 
                             style="cursor: pointer; transition: background-color 0.15s ease;">
                            <div class="d-flex align-items-center gap-2.5">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-primary" style="width: 36px; height: 36px; background: #eff6ff;">
                                    <i class="bi bi-person-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">
                                        {{ $data['dosen']->name }} 
                                        <span class="badge bg-light text-primary border ms-2 rounded-pill px-2.5 py-1" style="font-size: 0.75rem; font-weight: 600;">
                                            {{ count($data['kelas']) }} Kelas
                                        </span>
                                    </h6>
                                </div>
                            </div>
                            <div class="text-muted d-flex align-items-center gap-2">
                                <span class="small d-none d-sm-inline" style="font-size: 0.8rem;" x-text="open ? 'Tutup Detail' : 'Buka Detail'"></span>
                                <div class="rounded-circle d-flex align-items-center justify-content-center border" style="width: 28px; height: 28px; background: #f8fafc;">
                                    <i class="bi" :class="open ? 'bi-chevron-up text-primary' : 'bi-chevron-down text-muted'" style="font-size: 0.75rem;"></i>
                                </div>
                            </div>
                        </div>

                        <div x-show="open" x-transition.opacity class="card-body p-0 border-top">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 bg-white">
                                    <thead class="table-light text-muted small text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                                        <tr>
                                            <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                                            <th>MATA KULIAH & KELAS</th>
                                            <th>PERAN DOSEN</th>
                                            <th>JADWAL & RUANG</th>
                                            <th class="text-center">MAHASISWA</th>
                                            <th>STATUS</th>
                                            <th class="text-center" style="width: 130px;">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['kelas'] as $kelas)
                                            <tr>
                                                <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold" style="font-size: 0.85rem;">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-slate-800" style="font-size: 0.9rem;">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</div>
                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                        <span class="badge bg-light text-dark border px-2 py-0.5 rounded" style="font-size: 0.75rem;">{{ $kelas->mataKuliah?->kode_mk ?? '-' }}</span>
                                                        <span class="text-primary fw-semibold" style="font-size: 0.8rem;">Kelas {{ $kelas->kode_kelas }}</span>
                                                    </div>
                                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                        Prodi: {{ $kelas->programStudi?->nama_prodi ?? '-' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($kelas->dosen_id == $dosenId)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                                            <i class="bi bi-star-fill me-1 small"></i>Dosen Utama
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 0.75rem;">
                                                            <i class="bi bi-people-fill me-1 small"></i>Team Teaching
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kelas->jadwals && $kelas->jadwals->count() > 0)
                                                        @foreach($kelas->jadwals as $jadwal)
                                                            <div class="mb-1 pb-1 border-bottom border-light">
                                                                <div class="fw-semibold text-slate-800" style="font-size: 0.825rem;">
                                                                    {{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                                                </div>
                                                                <div class="text-muted" style="font-size: 0.775rem;">
                                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $jadwal->ruangan ?: 'TBA' }}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">Belum ada jadwal</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2.5 py-1.5 fw-bold rounded-pill" style="font-size: 0.8rem;">
                                                        <i class="bi bi-people-fill me-1"></i> {{ $kelas->mahasiswa->count() }} / {{ $kelas->kuota_mahasiswa }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($kelas->is_active && $kelas->status_kelas == 'aktif')
                                                        <span class="badge-status badge-status-aktif">
                                                            <span class="status-dot"></span> Aktif
                                                        </span>
                                                    @else
                                                        <span class="badge-status badge-status-nonaktif">
                                                            <span class="status-dot"></span> {{ ucfirst($kelas->status_kelas) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="action-buttons justify-content-center">
                                                        <a href="{{ route('admin.kelas.mahasiswa', $kelas->id) }}" class="action-btn action-btn-view" title="Kelola Mahasiswa">
                                                            <i class="bi bi-people-fill"></i>
                                                        </a>
                                                        <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="action-btn action-btn-edit" title="Edit">
                                                            <i class="bi bi-pencil-fill"></i>
                                                        </a>
                                                        <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" style="display:inline-block;" data-confirm="Yakin hapus kelas ini? Semua data mahasiswa yang terdaftar akan ikut terhapus.">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="action-btn action-btn-delete" title="Hapus">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted bg-white rounded border border-dashed" style="border-radius: 0.85rem;">
                        <i class="bi bi-inbox fs-1 mb-2 d-block text-slate-300"></i>
                        <span class="fw-semibold">Belum ada data kelas yang dapat ditampilkan.</span>
                        <p class="small text-muted mt-1">Coba sesuaikan kata kunci pencarian atau filter program studi/dosen.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const filterForm = document.getElementById('filterForm');
        const applyFilterBtn = document.getElementById('applyFilterBtn');

        function performSearch() {
            const url = new URL(window.location.origin + window.location.pathname);
            
            if (searchInput && searchInput.value.trim() !== '') {
                url.searchParams.set('search', searchInput.value.trim());
            }
            if (filterForm) {
                const formData = new FormData(filterForm);
                for (const [key, value] of formData.entries()) {
                    if (value && key !== 'search') {
                        url.searchParams.set(key, value);
                    }
                }
            }
            
            window.location.href = url.toString();
        }

        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', () => performSearch());
        }

        if (searchInput) {
            searchInput.addEventListener('keyup', function (e) {
                if(e.key === 'Enter') performSearch();
            });
        }
    });
</script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
