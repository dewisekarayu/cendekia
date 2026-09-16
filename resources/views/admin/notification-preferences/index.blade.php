@extends('layouts.admin')

@section('title', 'Pengaturan Preferensi Notifikasi')
@section('activeMenu', 'Pengaturan')

@section('content')
<div class="space-y-6">
    
    {{-- Page Header & Breadcrumbs --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-[#002B6B] dark:text-blue-400 border border-blue-100 dark:border-blue-800/60">
                    <i class="bi bi-sliders text-xs"></i>
                    Konfigurasi Sistem
                </span>
            </div>
            <h1 class="page-title mb-1 text-2xl sm:text-3xl font-extrabold text-[#002B6B] dark:text-white">
                Pengaturan Notifikasi Pengguna
            </h1>
            <nav style="--bs-breadcrumb-divider: '›';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan Notifikasi</li>
                </ol>
            </nav>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.user.index') }}" 
               class="btn btn-light border bg-white dark:bg-slate-800 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 d-inline-flex align-items-center gap-2 px-3.5 py-2 text-sm font-semibold shadow-sm"
               style="border-radius: 0.75rem;">
                <i class="bi bi-people"></i>
                <span>Kelola Akun Pengguna</span>
            </a>
        </div>
    </div>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 shadow-sm flex items-center justify-between gap-3 animate-fade-in" role="alert">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
                <div>
                    <strong class="font-bold block text-sm">Berhasil Disimpan!</strong>
                    <span class="text-xs text-emerald-700 dark:text-emerald-400">{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 shadow-sm flex items-start gap-3" role="alert">
            <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/60 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            </div>
            <div class="flex-1 text-sm">
                <strong class="font-bold block mb-1">Terjadi kesalahan:</strong>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.notification-preferences.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 sm:gap-4 items-end">
            <input type="hidden" name="per_page" value="{{ $perPage ?? 10 }}">
            
            {{-- Search Input --}}
            <div class="sm:col-span-6">
                <label for="search" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    Pencarian Pengguna
                </label>
                <div class="relative">
                    <input type="text" name="search" id="search" 
                           placeholder="Cari berdasarkan nama, email, atau NIM/NIP..." 
                           value="{{ $search }}"
                           class="form-control w-full">
                </div>
            </div>

            {{-- Role Filter --}}
            <div class="sm:col-span-4">
                <label for="role" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                    Filter Peran (Role)
                </label>
                <select name="role" id="role" class="form-select w-full">
                    <option value="">Semua Peran Civitas</option>
                    <option value="admin" @selected($role === 'admin')>Administrator</option>
                    <option value="dosen" @selected($role === 'dosen')>Dosen Pengajar</option>
                    <option value="mahasiswa" @selected($role === 'mahasiswa')>Mahasiswa</option>
                </select>
            </div>

            {{-- Action Button --}}
            <div class="sm:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary w-full d-flex align-items-center justify-content-center gap-2 py-2 text-sm">
                    <i class="bi bi-search"></i>
                    <span>Filter</span>
                </button>
                @if($search || $role || ($perPage ?? 10) != 10)
                    <a href="{{ route('admin.notification-preferences.index') }}" class="btn btn-light border d-flex align-items-center justify-content-center px-3" title="Reset Filter" style="border-radius: 0.75rem;">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Users Table Card --}}
    <div class="table-card">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                            <th>PENGGUNA</th>
                            <th>EMAIL</th>
                            <th>PERAN</th>
                            <th>STATUS PREFERENSI NOTIFIKASI</th>
                            <th class="text-center" style="width: 140px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                                    {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-[#002B6B] dark:text-blue-400 font-extrabold text-sm flex items-center justify-center border border-blue-100 dark:border-blue-800/60">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 dark:text-white" style="font-size: 0.9rem;">{{ $user->name }}</div>
                                            @if($user->nip_nim)
                                                <span class="text-xs text-slate-400 font-mono">{{ $user->nip_nim }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-slate-600 dark:text-slate-300 font-medium" style="font-size: 0.85rem;">{{ $user->email }}</span>
                                </td>
                                <td>
                                    @foreach($user->getRoleNames() as $userRole)
                                        @if($userRole === 'admin')
                                            <span class="badge-code" style="background-color: #eff6ff; color: #1e40af; border-color: #dbeafe;">
                                                <i class="bi bi-shield-check me-1"></i> Admin
                                            </span>
                                        @elseif($userRole === 'dosen')
                                            <span class="badge-code" style="background-color: #ecfdf5; color: #065f46; border-color: #a7f3d0;">
                                                <i class="bi bi-person-badge me-1"></i> Dosen
                                            </span>
                                        @else
                                            <span class="badge-code" style="background-color: #f8fafc; color: #475569; border-color: #e2e8f0;">
                                                <i class="bi bi-mortarboard me-1"></i> Mahasiswa
                                            </span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                        $prefs = $user->notificationPreferences;
                                        if (!$prefs) {
                                            $badgeHtml = '<span class="badge-status" style="background-color: #f1f5f9; color: #64748b;"><span class="status-dot" style="background-color: #94a3b8;"></span> Default Sistem</span>';
                                        } else {
                                            $enabledCount = collect([
                                                $prefs->materi_baru,
                                                $prefs->tugas_baru,
                                                $prefs->pengumuman_baru,
                                                $prefs->nilai_baru,
                                                $prefs->absensi_dibuka,
                                                $prefs->pengumpulan_tugas,
                                                $prefs->pesan_baru,
                                                $prefs->pengguna_baru,
                                            ])->filter()->count();
                                            $totalCount = 8;
                                            
                                            if ($enabledCount === $totalCount) {
                                                $badgeHtml = '<span class="badge-status badge-status-aktif"><span class="status-dot"></span> Semua Aktif ('.$enabledCount.'/'.$totalCount.')</span>';
                                            } elseif ($enabledCount === 0) {
                                                $badgeHtml = '<span class="badge-status badge-status-nonaktif"><span class="status-dot"></span> Semua Nonaktif</span>';
                                            } else {
                                                $badgeHtml = '<span class="badge-status badge-status-cuti"><span class="status-dot"></span> Sebagian Aktif ('.$enabledCount.'/'.$totalCount.')</span>';
                                            }
                                        }
                                    @endphp
                                    {!! $badgeHtml !!}
                                </td>
                                <td>
                                    <div class="action-buttons justify-content-center">
                                        <a href="{{ route('admin.notification-preferences.show', $user) }}" 
                                           class="btn btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 hover:bg-blue-600 hover:text-white transition"
                                           style="border-radius: 0.6rem;">
                                            <i class="bi bi-gear-fill"></i>
                                            <span>Kelola</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-4 py-3 border-t border-slate-100 dark:border-slate-700/60 gap-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <form method="GET" action="{{ route('admin.notification-preferences.index') }}" class="d-flex align-items-center gap-2">
                        @if($search)
                            <input type="hidden" name="search" value="{{ $search }}">
                        @endif
                        @if($role)
                            <input type="hidden" name="role" value="{{ $role }}">
                        @endif
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider text-nowrap">Show:</span>
                        <select name="per_page" class="form-select form-select-sm" style="width: 80px; border-radius: 0.5rem; height: 34px;" onchange="this.form.submit()">
                            <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
                            <option value="25" @selected(($perPage ?? 10) == 25)>25</option>
                            <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
                            <option value="100" @selected(($perPage ?? 10) == 100)>100</option>
                        </select>
                    </form>
                    <small class="text-muted">Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna</small>
                </div>
                @if($users->hasPages())
                    <div>
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        @else
            <div class="p-8 text-center text-slate-400">
                <i class="bi bi-person-x fs-1 d-block mb-2 text-slate-300 dark:text-slate-600"></i>
                <p class="font-semibold text-slate-600 dark:text-slate-400 mb-0">Tidak ada pengguna ditemukan dengan kriteria tersebut.</p>
            </div>
        @endif
    </div>

</div>
@endsection
