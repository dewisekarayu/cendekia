@extends('layouts.admin')

@section('title', 'Mata Kuliah')

@section('content')
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
            <h1 class="page-title mb-1">Mata Kuliah</h1>
            <p class="text-muted mb-2" style="font-size: 0.9rem;">Kelola seluruh data mata kuliah dan relasinya.</p>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master Data</li>
                    <li class="breadcrumb-item active">Mata Kuliah</li>
                </ol>
            </nav>
        </div>

        <a href="{{ route('admin.mata-kuliah.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="white-space:nowrap;">
            <i class="bi bi-plus-circle"></i> Tambah Mata Kuliah
        </a>
    </div>

    <div class="table-card p-4">
        <div class="table-card-header">
            <h5 class="m-0">Daftar Mata Kuliah</h5>
            <div class="d-flex gap-2 align-items-center">
                {{-- placeholder (filter/search bila dibutuhkan) --}}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="min-width: 160px;">Kode MK</th>
                        <th>Nama MK</th>
                        <th style="min-width: 170px;">Program Studi</th>
                        <th style="min-width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mataKuliah as $mk)
                        <tr>
                            <td>{{ $mk->kode_mk }}</td>
                            <td>{{ $mk->nama_mk }}</td>
                            <td>
                                {{ $mk->programStudi->nama_prodi ?? '-' }}
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.mata-kuliah.edit', $mk->id) }}" class="action-btn action-btn-edit" title="Edit" aria-label="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>

                                    <form action="{{ route('admin.mata-kuliah.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Yakin hapus mata kuliah ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn action-btn-delete border-0" title="Hapus" aria-label="Hapus">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Belum ada data mata kuliah.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            {{ $mataKuliah->links() }}
        </div>
    </div>
@endsection

