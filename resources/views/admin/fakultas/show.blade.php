@extends('layouts.admin')

@section('title', 'Detail Fakultas')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Cendekia</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.fakultas.index') }}" class="text-decoration-none text-muted">Fakultas</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Detail Fakultas</li>
            </ol>
        </nav>
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Detail Fakultas: {{ $fakulta->nama_fakultas }}</h1>
            <a href="{{ route('admin.fakultas.index') }}" class="btn btn-light border"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px; max-width: 800px;">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <th style="width: 200px;" class="text-muted">Kode Fakultas</th>
                    <td class="fw-bold">{{ $fakulta->kode_fakultas }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Nama Fakultas</th>
                    <td>{{ $fakulta->nama_fakultas }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Tanggal Dibuat</th>
                    <td>{{ $fakulta->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th class="text-muted">Terakhir Diperbarui</th>
                    <td>{{ $fakulta->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </tbody>
        </table>
        
        <div class="d-flex gap-2 mt-4 pt-3 border-top">
            <a href="{{ route('admin.fakultas.edit', $fakulta->id) }}" class="btn btn-primary px-4"><i class="bi bi-pencil"></i> Edit Fakultas</a>
        </div>
    </div>
</div>
@endsection
