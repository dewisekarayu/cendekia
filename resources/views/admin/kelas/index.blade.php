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

        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100 w-md-auto" style="min-width: fit-content;">
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

    <div class="card border-0 shadow-sm mb-4">
        
        <!-- Filters -->
        <div class="card-header bg-white p-3 p-md-4 border-bottom">
            <form id="filterForm" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-semibold mb-1">Pencarian</label>
                    <input type="text" id="searchInput" name="search" class="form-control form-control-sm" placeholder="Kode Kelas, Matkul..." value="{{ $search ?? '' }}">
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold mb-1">Program Studi</label>
                    <select name="program_studi_id" id="prodiFilter" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($prodis as $p)
                            <option value="{{ $p->id }}" {{ request('program_studi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label small fw-semibold mb-1">Dosen</label>
                    <select name="dosen_id" id="dosenFilter" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ request('dosen_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-2 mt-2 mt-md-0">
                    <button type="button" class="btn btn-primary btn-sm w-100 text-nowrap" id="applyFilterBtn">Terapkan Filter</button>
                </div>
            </form>
        </div>

        <div class="card-body p-4 bg-light">
            <div class="d-flex flex-column gap-3">
                @forelse($groupedByDosen as $dosenId => $data)
                    <div class="card border-0 shadow-sm" x-data="{ open: false }">
                        <div class="card-header bg-white border-bottom-0 p-3" @click="open = !open" style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold text-dark">
                                    <i class="bi bi-person-fill me-2 text-primary"></i>{{ $data['dosen']->name }} 
                                    <span class="badge bg-light text-primary border ms-2" style="font-size: 0.8rem;">{{ count($data['kelas']) }} Kelas</span>
                                </h5>
                                <i class="bi text-muted fs-5" :class="open ? 'bi-chevron-up' : 'bi-chevron-down'"></i>
                            </div>
                        </div>
                        <div x-show="open" x-transition.opacity class="card-body p-0 border-top">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 bg-white">
                                    <thead class="table-light text-muted small text-uppercase" style="letter-spacing: 0.5px;">
                                        <tr>
                                            <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                                            <th>MATA KULIAH & KELAS</th>
                                            <th>PERAN DOSEN</th>
                                            <th>JADWAL & RUANG</th>
                                            <th class="text-center">MAHASISWA</th>
                                            <th>STATUS</th>
                                            <th class="text-center" style="width: 120px;">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data['kelas'] as $kelas)
                                            <tr>
                                                <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-slate-800" style="font-size: 0.95rem;">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</div>
                                                    <div class="d-flex align-items-center gap-2 mt-1">
                                                        <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">{{ $kelas->mataKuliah?->kode_mk ?? '-' }}</span>
                                                        <span class="text-primary fw-semibold" style="font-size: 0.8rem;">Kelas {{ $kelas->kode_kelas }}</span>
                                                    </div>
                                                    <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                                        Prodi: {{ $kelas->programStudi?->nama_prodi ?? '-' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($kelas->dosen_id == $dosenId)
                                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="bi bi-star-fill me-1 small"></i>Dosen Utama</span>
                                                    @else
                                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"><i class="bi bi-people-fill me-1 small"></i>Team Teaching</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($kelas->jadwals && $kelas->jadwals->count() > 0)
                                                        @foreach($kelas->jadwals as $jadwal)
                                                            <div class="mb-1 pb-1 border-bottom border-light">
                                                                <div class="fw-semibold text-slate-800" style="font-size: 0.85rem;">
                                                                    {{ $jadwal->hari }}, {{ substr($jadwal->jam_mulai, 0, 5) }} - {{ substr($jadwal->jam_selesai, 0, 5) }}
                                                                </div>
                                                                <div class="text-muted" style="font-size: 0.8rem;">
                                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $jadwal->ruangan ?: 'TBA' }}
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted small">Belum ada jadwal</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 fw-bold">
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
                    <div class="text-center py-5 text-muted bg-white rounded border border-dashed">
                        <i class="bi bi-inbox fs-1 mb-3 d-block"></i>
                        Belum ada data kelas yang dapat ditampilkan.
                    </div>
                @endforelse
            </div>
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
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const filterForm = document.getElementById('filterForm');
        const applyFilterBtn = document.getElementById('applyFilterBtn');

        function performSearch() {
            const url = new URL(window.location.origin + window.location.pathname);
            
            if (searchInput) url.searchParams.set('search', searchInput.value);
            if (filterForm) {
                const formData = new FormData(filterForm);
                for (const [key, value] of formData.entries()) {
                    if (value) url.searchParams.set(key, value);
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
