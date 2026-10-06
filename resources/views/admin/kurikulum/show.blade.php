@extends('layouts.admin')

@section('title', 'Detail Kurikulum')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Cendekia</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.kurikulum.index') }}" class="text-decoration-none text-muted">Kurikulum</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Detail Kurikulum</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Detail Kurikulum: {{ $kurikulum->nama_kurikulum }}</h1>
            <a href="{{ route('admin.kurikulum.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; max-width: 800px;">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th style="width: 200px;" class="text-muted">Nama Kurikulum</th>
                    <td class="fw-bold">{{ $kurikulum->nama_kurikulum }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Tahun Mulai Berlaku</th>
                    <td>{{ $kurikulum->tahun_mulai }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Program Studi</th>
                    <td>{{ $kurikulum->programStudi ? $kurikulum->programStudi->nama_prodi : '-' }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Status</th>
                    <td>
                        @if($kurikulum->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Aktif</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">Nonaktif</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-muted">Tanggal Dibuat</th>
                    <td>{{ $kurikulum->created_at->format('d M Y H:i') }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('admin.kurikulum.edit', $kurikulum->id) }}" class="btn btn-primary px-4"><i class="bi bi-pencil"></i> Edit Kurikulum</a>
        </div>
    </div>
</div>
@endsection
