@extends('layouts.admin')

@section('title', 'Kelas Perkuliahan')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">Kelas Perkuliahan</h1>
            <p class="text-muted mb-2" style="font-size: 0.875rem;">Kelola alokasi rombongan belajar, dosen pengampu, ruang, dan jadwal kelas aktif.</p>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><span class="text-slate-500">Akademik</span></li>
                    <li class="breadcrumb-item active" aria-current="page">Kelas Perkuliahan</li>
                </ol>
            </nav>
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
        </div>
    </div>
</div>
@endsection
