@extends('layouts.admin')

@section('title', 'Detail Tahun Akademik')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.tahun-akademik.index') }}" class="text-decoration-none text-muted">Tahun Akademik</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Detail Semester</li>
            </ol>
        </nav>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Detail Tahun Akademik</h1>
                <p class="text-muted mb-0" style="font-size: 0.9rem;">Informasi lengkap terkait periode akademik.</p>
            </div>
            <div>
                <a href="{{ route('admin.tahun-akademik.edit', $tahun_akademik->id) }}" class="btn btn-primary d-flex align-items-center gap-2 fw-semibold px-4 py-2" style="background-color: #002B6B; border: none; border-radius: 8px;">
                    <i class="bi bi-pencil-square"></i>
                    Edit Data
                </a>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-body p-0">
            <table class="table table-borderless mb-0">
                <tbody>
                    <tr class="border-bottom">
                        <th class="ps-4 py-3 text-muted fw-semibold" style="width: 250px;">Nama Semester</th>
                        <td class="py-3 fw-bold text-dark">{{ $tahun_akademik->nama_semester }}</td>
                    </tr>
                    <tr class="border-bottom">
                        <th class="ps-4 py-3 text-muted fw-semibold" style="">Tahun Ajaran</th>
                        <td class="py-3">{{ $tahun_akademik->tahun_ajaran }}</td>
                    </tr>
                    <tr class="border-bottom">
                        <th class="ps-4 py-3 text-muted fw-semibold" style="">Jenis Semester</th>
                        <td class="py-3">
                            <span class="badge {{ $tahun_akademik->jenis == 'Ganjil' ? 'bg-primary' : 'bg-info' }} bg-opacity-10 text-{{ $tahun_akademik->jenis == 'Ganjil' ? 'primary' : 'info' }} px-3 py-2 rounded-pill fw-semibold">
                                {{ $tahun_akademik->jenis }}
                            </span>
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <th class="ps-4 py-3 text-muted fw-semibold" style="">Tanggal Pelaksanaan</th>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-calendar-event text-primary"></i>
                                <span>{{ $tahun_akademik->tanggal_mulai->format('d M Y') }} &mdash; {{ $tahun_akademik->tanggal_selesai->format('d M Y') }}</span>
                            </div>
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <th class="ps-4 py-3 text-muted fw-semibold" style="">Status</th>
                        <td class="py-3">
                            @if($tahun_akademik->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold d-inline-flex align-items-center gap-2">
                                    <span style="width: 6px; height: 6px; background-color: currentColor; border-radius: 50%; display: inline-block;"></span>
                                    Semester Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill fw-semibold d-inline-flex align-items-center gap-2">
                                    <span style="width: 6px; height: 6px; background-color: currentColor; border-radius: 50%; display: inline-block;"></span>
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
