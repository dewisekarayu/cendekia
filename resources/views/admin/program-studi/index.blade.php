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

    <div class="table-card" style="margin-top: 0;">
        <div style="padding: 1.5rem; border-bottom: 1px solid rgba(0, 43, 107, 0.08);">
            <div style="position: relative; max-width: 620px;">
                <input type="text" class="form-control" placeholder="Cari data..." style="height: 46px; padding-right: 3rem;">
                <i class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.1rem;"></i>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>KODE PRODI</th>
                        <th>NAMA PROGRAM STUDI</th>
                        <th>JENJANG PENDIDIKAN</th>
                        <th>AKREDITASI</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prodiList as $prodi)
                        @php
                            $akreditasi = match ($prodi->kode_prodi) {
                                'TI' => 'Unggul',
                                'SI' => 'A',
                                'DKV' => 'B',
                                default => 'Baik',
                            };
                            $kodeTampil = match ($prodi->kode_prodi) {
                                'TI' => 'IF101',
                                'SI' => 'SI102',
                                'DKV' => 'DKV103',
                                default => $prodi->kode_prodi,
                            };
                        @endphp
                        <tr>
                            <td style="font-weight: 700; color: #002B6B;">{{ $kodeTampil }}</td>
                            <td style="font-weight: 600;">{{ $prodi->nama_prodi }}</td>
                            <td>{{ $prodi->jenjang }} - {{ $prodi->jenjang === 'S1' ? 'Sarjana' : 'Program ' . $prodi->jenjang }}</td>
                            <td>{{ $akreditasi }}</td>
                            <td>
                                <div class="action-buttons justify-content-center">
                                    <button type="button" class="action-btn action-btn-view" title="Lihat"><i class="bi bi-eye"></i></button>
                                    <form method="POST" action="{{ route('admin.program-studi.destroy', $prodi->id) }}" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">Belum ada data program studi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(0, 43, 107, 0.08);">
            {{ $prodiList->links() }}
        </div>
    </div>
</div>
@endsection
