@extends('layouts.admin')

@section('title', 'Manajemen Fakultas')

@section('content')
<div class="container-fluid px-0">
    {{-- Header Page --}}
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="page-title mb-1">Manajemen Fakultas</h1>
            <p class="text-muted mb-2" style="font-size: 0.875rem;">Kelola seluruh data fakultas dan program studi di lingkungan kampus.</p>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><span class="text-slate-500">Data Akademik</span></li>
                    <li class="breadcrumb-item active" aria-current="page">Fakultas</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white border-bottom py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex w-100 align-items-center gap-3">
                <form action="{{ route('admin.fakultas.index') }}" method="GET" class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2 w-100">
                    <label for="semester_id" class="fw-semibold text-muted mb-0" style="font-size: 0.9rem; white-space: nowrap;">Tahun Akademik:</label>
                    <select name="semester_id" id="semester_id" class="form-select form-select-sm border-0 bg-light fw-bold w-100" style="min-width: 200px; color: #002B6B;" onchange="this.form.submit()">
                        @foreach($semesters as $sem)
                            <option value="{{ $sem->id }}" {{ ($selectedSemester && $selectedSemester->id == $sem->id) ? 'selected' : '' }}>
                                {{ $sem->tahun_ajaran }} - {{ $sem->jenis }} {{ $sem->is_active ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div>
                <a href="{{ route('admin.fakultas.create') }}" class="btn btn-sm btn-primary shadow-sm" style="background-color: #002B6B; border: none;">
                    <i class="bi bi-plus-lg"></i> Tambah Fakultas
                </a>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if($selectedSemester && $selectedSemester->fakultas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Kode Fakultas</th>
                                <th class="px-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">Fakultas</th>
                                <th class="px-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 0.75rem; text-align: center;">Total Prodi</th>
                                <th class="px-4 py-3 text-uppercase text-muted fw-bold" style="font-size: 0.75rem; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ expandedId: null }">
                            @foreach($selectedSemester->fakultas as $fakultas)
                                <tr class="align-middle" :class="expandedId === {{ $fakultas->id }} ? 'bg-slate-50 border-primary' : ''" style="transition: all 0.2s; border-bottom: 1px solid #f1f5f9;">
                                    <td class="px-4 py-4">
                                        <button @click="expandedId = expandedId === {{ $fakultas->id }} ? null : {{ $fakultas->id }}" 
                                                class="btn btn-sm text-white d-inline-flex align-items-center gap-2 rounded-pill shadow-sm border-0"
                                                :class="expandedId === {{ $fakultas->id }} ? 'bg-primary' : 'bg-indigo-600'" 
                                                style="font-weight: 600; padding: 0.4rem 1rem; font-size: 0.875rem;">
                                            <i class="bi bi-chevron-right transition-transform" :class="expandedId === {{ $fakultas->id }} ? 'rotate-90' : ''" style="font-size: 0.75rem;"></i>
                                            {{ $fakultas->kode_fakultas }}
                                        </button>
                                    </td>
                                    <td class="px-4 py-4 fw-bold text-dark" style="font-size: 0.95rem;">{{ $fakultas->nama_fakultas }}</td>
                                    <td class="px-4 py-4 text-center">
                                        <span class="badge bg-indigo-50 text-indigo-700 border border-indigo-100 rounded-pill px-3 py-2" style="font-weight: 600;">
                                            <i class="bi bi-diagram-2 me-1"></i> {{ $fakultas->programStudi->count() }} Prodi
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <a href="{{ route('admin.fakultas.edit', $fakultas->id) }}" class="btn btn-sm btn-light border text-primary" title="Edit Fakultas">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    </td>
                                </tr>
                                
                                <!-- Child Row: Program Studi -->
                                <tr x-show="expandedId === {{ $fakultas->id }}" x-transition.opacity.duration.300ms style="display: none;">
                                    <td colspan="4" class="p-0 border-0">
                                        <div class="bg-slate-50 border-bottom border-start border-primary border-4 py-3 py-md-4 px-3 px-md-5 shadow-inner">
                                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
                                                <div>
                                                    <h6 class="mb-1 fw-bold text-primary"><i class="bi bi-diagram-3-fill"></i> Daftar Program Studi di {{ $fakultas->nama_fakultas }}</h6>
                                                </div>
                                                <a href="{{ route('admin.program-studi.create', ['fakultas_id' => $fakultas->id]) }}" class="btn btn-sm btn-outline-primary text-nowrap">
                                                    <i class="bi bi-plus-lg"></i> Tambah Prodi
                                                </a>
                                            </div>
                                            
                                            <div class="bg-white rounded-3 shadow-sm border border-slate-200 overflow-hidden">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-hover align-middle mb-0">
                                                        <thead style="background-color: #f8fafc;">
                                                            <tr>
                                                                <th class="px-4 py-2 text-uppercase text-muted" style="font-size: 0.7rem;">Kode</th>
                                                                <th class="px-4 py-2 text-uppercase text-muted" style="font-size: 0.7rem;">Nama Prodi</th>
                                                                <th class="px-4 py-2 text-uppercase text-muted" style="font-size: 0.7rem;">Jenjang</th>
                                                                <th class="px-4 py-2 text-uppercase text-muted" style="font-size: 0.7rem;">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($fakultas->programStudi as $prodi)
                                                            <tr>
                                                                <td class="px-4 py-2"><span class="badge bg-slate-100 text-slate-700 border">{{ $prodi->kode_prodi }}</span></td>
                                                                <td class="px-4 py-2 fw-semibold">{{ $prodi->nama_prodi }}</td>
                                                                <td class="px-4 py-2"><span class="badge bg-indigo-50 text-indigo-700 border">{{ $prodi->jenjang }}</span></td>
                                                                <td class="px-4 py-2">
                                                                    <a href="{{ route('admin.program-studi.edit', $prodi->id) }}" class="btn btn-sm btn-light border text-primary py-0"><i class="bi bi-pencil" style="font-size: 0.75rem;"></i></a>
                                                                </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="4" class="text-center py-3 text-muted small">Belum ada program studi di fakultas ini.</td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-folder-x fs-1 text-muted opacity-25 d-block mb-3"></i>
                    <p class="text-muted mb-0">Belum ada Fakultas pada Tahun Akademik yang dipilih.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
