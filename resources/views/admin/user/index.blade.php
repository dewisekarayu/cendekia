@extends('layouts.admin')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">Kelola Akun Pengguna</h1>
            <p class="text-muted mb-2" style="font-size: 0.875rem;">Kelola hak akses, kredensial login, dan akun civitas akademika Cendekia.</p>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><span class="text-slate-500">Pengaturan</span></li>
                    <li class="breadcrumb-item active" aria-current="page">Kelola Pengguna</li>
                </ol>
            </nav>
        </div>

        <a href="{{ route('admin.user.create', ['role' => $role]) }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah {{ ucfirst($role) }}</span>
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

    @if (session('error'))
        <div class="alert alert-danger d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem;" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <span class="fw-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Tab Filter Role --}}
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
        <div class="d-flex gap-2 flex-wrap">
            @foreach (['admin' => 'Administrator', 'dosen' => 'Dosen Pengajar', 'mahasiswa' => 'Mahasiswa'] as $r => $label)
                <a href="{{ route('admin.user.index', ['role' => $r, 'per_page' => request('per_page', 10)]) }}"
                    class="btn btn-sm d-flex align-items-center gap-2 text-decoration-none px-3.5 py-2 font-bold"
                    style="border-radius: 0.75rem; font-size: 0.825rem; {{ $role === $r ? 'background-color: #002B6B; color: white; border: 1px solid #002B6B; box-shadow: 0 4px 10px rgba(0,43,107,0.15);' : 'background-color: white; color: #475569; border: 1px solid #e2e8f0;' }}">
                    @if($r === 'admin')
                        <i class="bi bi-shield-check"></i>
                    @elseif($r === 'dosen')
                        <i class="bi bi-person-badge"></i>
                    @else
                        <i class="bi bi-mortarboard"></i>
                    @endif
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                        <th style="width: 140px;">NIP / NIM</th>
                        <th>NAMA LENGKAP</th>
                        <th>EMAIL</th>
                        @if ($role === 'mahasiswa')
                            <th>PROGRAM STUDI</th>
                        @endif
                        <th class="text-center" style="width: 120px;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($userList as $user)
                        <tr>
                            <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                                {{ ($userList->currentPage() - 1) * $userList->perPage() + $loop->iteration }}
                            </td>
                            <td>
                                <span class="badge-code">{{ $user->nip_nim }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-slate-800 dark:text-white" style="font-size: 0.9rem;">{{ $user->name }}</div>
                            </td>
                            <td>
                                <span class="text-slate-500" style="font-size: 0.85rem;">{{ $user->email }}</span>
                            </td>
                            @if ($role === 'mahasiswa')
                                <td>
                                    <span class="badge-akreditasi">
                                        {{ $user->programStudi?->nama_prodi ?? '-' }}
                                    </span>
                                </td>
                            @endif
                            <td>
                                <div class="action-buttons justify-content-center">
                                    <a href="{{ route('admin.user.edit', $user->id) }}" class="action-btn action-btn-edit" title="Edit">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
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
                            <td colspan="{{ $role === 'mahasiswa' ? '6' : '5' }}" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                                    <i class="bi bi-people fs-1 text-slate-300 dark:text-slate-600 mb-2"></i>
                                    <p class="fw-semibold mb-0">Belum ada data akun {{ $role }}.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-4 py-3 border-t border-slate-100 dark:border-slate-700/60 gap-3">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <form method="GET" action="{{ route('admin.user.index') }}" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="role" value="{{ $role }}">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider text-nowrap">Show:</span>
                    <select name="per_page" class="form-select form-select-sm" style="width: 80px; border-radius: 0.5rem; height: 34px;" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </form>
                <small class="text-muted">Menampilkan {{ $userList->firstItem() ?? 0 }}-{{ $userList->lastItem() ?? 0 }} dari {{ $userList->total() }} data</small>
            </div>
            @if($userList->hasPages())
                <div>
                    {{ $userList->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection