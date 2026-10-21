@extends('layouts.admin')

@section('title', 'Peserta Kelas Perkuliahan')

@section('content')
<style>
    /* Scoped Design & Responsive Rules for Peserta Kelas */
    .kelas-header-actions {
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }

    .kelas-action-btn {
        height: 42px !important;
        padding: 0 1.15rem !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        border-radius: 0.75rem !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 0.5rem !important;
        white-space: nowrap !important;
        text-decoration: none !important;
        transition: all 0.2s ease !important;
    }

    .btn-action-back {
        background-color: #ffffff !important;
        color: #475569 !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    }
    .btn-action-back:hover {
        background-color: #f8fafc !important;
        color: #0f172a !important;
        border-color: #cbd5e1 !important;
        transform: translateY(-1px);
    }

    .btn-action-auto {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1.5px solid #bfdbfe !important;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
    }
    .btn-action-auto:hover {
        background-color: #dbeafe !important;
        color: #1e40af !important;
        border-color: #93c5fd !important;
        transform: translateY(-1px);
    }

    .btn-action-add {
        background: linear-gradient(135deg, #002B6B 0%, #001f4d 100%) !important;
        color: #ffffff !important;
        border: none !important;
        box-shadow: 0 4px 14px rgba(0, 43, 107, 0.22) !important;
    }
    .btn-action-add:hover {
        filter: brightness(1.1);
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 43, 107, 0.3) !important;
    }

    /* Desktop & Tablet (>= 768px): Always 1 Neat Horizontal Row */
    @media (min-width: 768px) {
        .kelas-header-actions {
            flex-wrap: nowrap !important;
            width: auto !important;
            justify-content: flex-end !important;
        }
        .kelas-action-btn {
            width: auto !important;
            flex-grow: 0 !important;
        }
        .search-wrapper {
            width: 280px !important;
        }
        .modal-footer-btn {
            width: auto !important;
        }
    }

    /* Mobile (< 768px): 2-Row Symmetrical Grid */
    @media (max-width: 767.98px) {
        .kelas-header-actions {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 0.5rem !important;
            width: 100% !important;
        }
        .kelas-header-actions > form {
            display: contents !important;
        }
        .btn-action-back {
            grid-column: 1 !important;
            width: 100% !important;
        }
        .btn-action-auto {
            grid-column: 2 !important;
            width: 100% !important;
        }
        .btn-action-add {
            grid-column: span 2 !important;
            width: 100% !important;
        }
        .search-wrapper {
            width: 100% !important;
        }
        .modal-footer-btn {
            width: 100% !important;
        }
    }

    /* If only back button is present (e.g. class full), make it auto width */
    .kelas-header-actions:has(> .btn-action-back:only-child) {
        display: flex !important;
    }

    /* Dark Mode Harmonization */
    html.dark .btn-action-back {
        background-color: #1e293b !important;
        color: #cbd5e1 !important;
        border-color: #334155 !important;
    }
    html.dark .btn-action-back:hover {
        background-color: #334155 !important;
        color: #ffffff !important;
    }
    html.dark .btn-action-auto {
        background-color: rgba(30, 58, 138, 0.3) !important;
        color: #93c5fd !important;
        border-color: rgba(59, 130, 246, 0.4) !important;
    }
</style>

<div>
    <!-- Breadcrumb -->
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-2">
        <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}" class="text-decoration-none text-muted">Akademik</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}" class="text-decoration-none text-muted">Kelas Perkuliahan</a></li>
            <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 600;">Peserta Kelas</li>
        </ol>
    </nav>

    <!-- Page Header & Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="page-title fs-3 fw-bold mb-1" style="color: #002B6B;">Peserta Kelas Perkuliahan</h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Daftar mahasiswa yang terdaftar di kelas <strong class="text-dark">{{ $kelas->mataKuliah?->nama_mk ?? '-' }} - Kelas {{ $kelas->kode_kelas }}</strong>
            </p>
        </div>

        <!-- Action Toolbar (Single row on Desktop, Clean 2-row grid on Mobile) -->
        <div class="kelas-header-actions">
            <a href="{{ route('admin.kelas.index') }}" class="btn kelas-action-btn btn-action-back">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>

            @if($kelas->mahasiswa->count() < $kelas->kuota_mahasiswa)
                <form action="{{ route('admin.kelas.mahasiswa.auto-enroll', $kelas->id) }}" 
                      method="POST" 
                      class="d-contents m-0 p-0" 
                      data-confirm="Tarik otomatis mahasiswa aktif prodi {{ $kelas->programStudi?->nama_prodi }} yang belum memiliki kelas ke dalam kelas ini?">
                    @csrf
                    <button type="submit" class="btn kelas-action-btn btn-action-auto">
                        <i class="bi bi-magic text-primary"></i>
                        <span>Auto-Enroll Prodi</span>
                    </button>
                </form>

                <button type="button" 
                        class="btn kelas-action-btn btn-action-add" 
                        data-bs-toggle="modal" 
                        data-bs-target="#modalTambahMahasiswa">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tambah Mahasiswa</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #ecfdf5; color: #065f46;" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <span class="fw-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #fef2f2; color: #991b1b;" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <span class="fw-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #eff6ff; color: #1e40af;" role="alert">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                <span class="fw-semibold">{{ session('info') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card Info Kelas & Kapasitas Kuota -->
    <div class="row g-3 mb-4">
        <!-- Informasi Kelas -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm p-3 p-sm-4 h-100" style="border-radius: 1rem; background: #ffffff;">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="text-uppercase tracking-wider fw-bold text-muted" style="letter-spacing: 0.05em; font-size: 0.75rem;">INFORMASI KELAS</span>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1 fw-bold rounded-pill" style="font-size: 0.75rem;">
                        Kelas Aktif
                    </span>
                </div>

                <h4 class="fw-bold mb-3" style="color: #002B6B;">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</h4>

                <div class="row g-2 pt-1">
                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2.5 p-2.5 rounded-3 bg-light border border-light-subtle">
                            <div class="rounded-2 bg-white shadow-xs text-primary d-flex align-items-center justify-content-center border shrink-0" style="width: 34px; height: 34px;">
                                <i class="bi bi-tag-fill"></i>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <div class="text-muted fw-semibold" style="font-size: 0.7rem;">KODE KELAS</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;" title="{{ $kelas->kode_kelas }}">{{ $kelas->kode_kelas }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2.5 p-2.5 rounded-3 bg-light border border-light-subtle">
                            <div class="rounded-2 bg-white shadow-xs text-primary d-flex align-items-center justify-content-center border shrink-0" style="width: 34px; height: 34px;">
                                <i class="bi bi-mortarboard-fill"></i>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <div class="text-muted fw-semibold" style="font-size: 0.7rem;">PROGRAM STUDI</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;" title="{{ $kelas->programStudi?->nama_prodi ?? '-' }}">{{ $kelas->programStudi?->nama_prodi ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2.5 p-2.5 rounded-3 bg-light border border-light-subtle">
                            <div class="rounded-2 bg-white shadow-xs text-primary d-flex align-items-center justify-content-center border shrink-0" style="width: 34px; height: 34px;">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <div class="text-muted fw-semibold" style="font-size: 0.7rem;">DOSEN PENGAMPU</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;" title="{{ $kelas->dosen?->name ?? '-' }}">{{ $kelas->dosen?->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="d-flex align-items-center gap-2.5 p-2.5 rounded-3 bg-light border border-light-subtle">
                            <div class="rounded-2 bg-white shadow-xs text-primary d-flex align-items-center justify-content-center border shrink-0" style="width: 34px; height: 34px;">
                                <i class="bi bi-calendar-event-fill"></i>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <div class="text-muted fw-semibold" style="font-size: 0.7rem;">SEMESTER</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.85rem;" title="{{ $kelas->semester?->nama_semester ?? '-' }}">{{ $kelas->semester?->nama_semester ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kapasitas Kelas -->
        <div class="col-12 col-lg-4">
            @php
                $terisi = $pesertaKelas->count();
                $kuota = $kelas->kuota_mahasiswa ?: 40;
                $persentase = $kuota > 0 ? min(100, round(($terisi / $kuota) * 100)) : 0;
                $isPenuh = $terisi >= $kuota;
            @endphp
            <div class="card border-0 shadow-sm p-3 p-sm-4 h-100 d-flex flex-column justify-content-between" style="border-radius: 1rem; background: #ffffff;">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-uppercase tracking-wider fw-bold text-muted" style="letter-spacing: 0.05em; font-size: 0.75rem;">KAPASITAS KELAS</span>
                        @if($isPenuh)
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                <i class="bi bi-x-circle me-1"></i> Penuh
                            </span>
                        @else
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                <i class="bi bi-check-circle me-1"></i> Tersedia {{ $kuota - $terisi }} Kursi
                            </span>
                        @endif
                    </div>

                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h1 class="display-6 fw-bold mb-0 {{ $isPenuh ? 'text-danger' : 'text-slate-900' }}" style="line-height: 1;">{{ $terisi }}</h1>
                        <span class="text-muted fw-semibold" style="font-size: 0.95rem;">/ {{ $kuota }} Mahasiswa</span>
                    </div>

                    <p class="text-muted small mb-3" style="font-size: 0.8rem;">
                        {{ $isPenuh ? 'Kapasitas maksimal rombel telah tercapai.' : 'Masih ada kuota kosong yang dapat diisi mahasiswa.' }}
                    </p>
                </div>

                <div>
                    <div class="d-flex justify-content-between align-items-center text-muted small mb-1.5" style="font-size: 0.75rem; font-weight: 600;">
                        <span>Keterisian: {{ $persentase }}%</span>
                        <span>Batas Kuota: {{ $kuota }}</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 999px; background-color: #f1f5f9;">
                        <div class="progress-bar {{ $isPenuh ? 'bg-danger' : ($persentase > 80 ? 'bg-warning' : 'bg-primary') }}" 
                             role="progressbar" 
                             style="width: {{ $persentase }}%; border-radius: 999px; transition: width 0.6s ease; background-color: #002B6B !important;" 
                             aria-valuenow="{{ $persentase }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Peserta Kelas -->
    <div class="card border-0 shadow-sm p-3 p-sm-4 mb-4" style="border-radius: 1rem; background: #ffffff;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-3 border-bottom">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 d-flex align-items-center justify-content-center shadow-xs shrink-0" style="width: 44px; height: 44px; background: #eff6ff; color: #002B6B;">
                    <i class="bi bi-people-fill fs-5"></i>
                </div>
                <div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="fw-bold mb-0 text-slate-900" style="color: #002B6B;">Daftar Peserta Terdaftar</h5>
                        <span class="badge bg-light text-primary border px-2.5 py-1 rounded-pill" style="font-size: 0.75rem;">
                            {{ $pesertaKelas->count() }} Mahasiswa
                        </span>
                    </div>
                    <p class="text-muted small mb-0 mt-0.5">Daftar mahasiswa yang secara resmi terdaftar dan mengikuti kegiatan perkuliahan pada kelas ini.</p>
                </div>
            </div>

            <div class="d-flex align-items-center w-100 w-md-auto">
                <div class="input-group search-wrapper">
                    <span class="input-group-text bg-light border-end-0 text-muted" style="border-radius: 0.75rem 0 0 0.75rem; height: 40px;">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" 
                           id="searchInput" 
                           class="form-control bg-light border-start-0 ps-0" 
                           placeholder="Cari nama atau NIM..." 
                           style="border-radius: 0 0.75rem 0.75rem 0; height: 40px; font-size: 0.875rem;">
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="pesertaTable">
                <thead style="background-color: #f8fafc; border-top: 1px solid #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                    <tr class="text-muted small text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                        <th style="width: 50px; text-align: center; padding: 14px 12px;" class="text-nowrap">#</th>
                        <th style="width: 140px; padding: 14px 12px;" class="text-nowrap">NIM</th>
                        <th style="min-width: 200px; padding: 14px 12px;">NAMA MAHASISWA</th>
                        <th style="width: 130px; padding: 14px 12px;" class="text-nowrap">ANGKATAN</th>
                        <th style="width: 160px; padding: 14px 12px;" class="text-nowrap">TGL TERDAFTAR</th>
                        <th style="width: 100px; text-align: center; padding: 14px 12px;" class="text-nowrap">AKSI</th>
                    </tr>
                </thead>
                <tbody id="studentTableBody">
                    @forelse($pesertaKelas as $index => $mhs)
                    @php
                        $angkatan = substr($mhs->nip_nim, 0, 2) ? '20' . substr($mhs->nip_nim, 0, 2) : '-';
                        $tglDaftar = $mhs->pivot->tanggal_daftar 
                            ? \Carbon\Carbon::parse($mhs->pivot->tanggal_daftar)->translatedFormat('d M Y') 
                            : ($mhs->pivot->created_at ? $mhs->pivot->created_at->translatedFormat('d M Y') : '-');
                    @endphp
                    <tr class="student-row" data-name="{{ strtolower($mhs->name) }}" data-nim="{{ strtolower($mhs->nip_nim) }}" style="border-bottom: 1px solid #f1f5f9;">
                        <td class="text-center font-monospace text-slate-400 fw-bold" style="font-size: 0.85rem;">{{ $index + 1 }}</td>
                        <td class="text-nowrap">
                            <span class="font-monospace fw-bold px-2 py-1 rounded" style="background-color: #f1f5f9; color: #334155; font-size: 0.825rem; border: 1px solid #e2e8f0;">
                                {{ $mhs->nip_nim }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2.5">
                                <img src="{{ $mhs->foto ? asset('storage/' . $mhs->foto) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($mhs->name) }}" 
                                     alt="Avatar" 
                                     class="rounded-circle border shrink-0" 
                                     style="width: 36px; height: 36px; object-fit: cover;">
                                <div class="min-w-0">
                                    <div class="fw-bold text-slate-800 text-truncate" style="max-width: 240px; font-size: 0.9rem;" title="{{ $mhs->name }}">{{ $mhs->name }}</div>
                                    <div class="text-muted text-truncate" style="max-width: 240px; font-size: 0.775rem;" title="{{ $mhs->email }}">{{ $mhs->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-nowrap">
                            <span class="badge border px-2.5 py-1 rounded-pill" style="font-size: 0.75rem; background-color: #f8fafc; border-color: #e2e8f0 !important; color: #475569;">
                                Angkatan {{ $angkatan }}
                            </span>
                        </td>
                        <td class="text-nowrap">
                            <div class="text-slate-600 small d-flex align-items-center gap-1.5" style="font-size: 0.825rem;">
                                <i class="bi bi-calendar3 text-muted"></i>
                                <span>{{ $tglDaftar }}</span>
                            </div>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.kelas.mahasiswa.remove', [$kelas->id, $mhs->id]) }}" 
                                  method="POST" 
                                  class="d-inline m-0 p-0"
                                  data-confirm="Apakah Anda yakin ingin mengeluarkan {{ $mhs->name }} dari kelas perkuliahan ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 px-2.5 py-1 text-nowrap" 
                                        style="border-radius: 0.5rem; font-size: 0.775rem; font-weight: 600;" 
                                        title="Keluarkan dari Kelas">
                                    <i class="bi bi-trash3"></i>
                                    <span>Keluarkan</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr id="emptyRow">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center justify-content-center py-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px; background: #f1f5f9;">
                                    <i class="bi bi-people fs-2 text-slate-400"></i>
                                </div>
                                <h6 class="fw-bold text-slate-700 mb-1">Belum Ada Peserta Terdaftar</h6>
                                <p class="small text-muted mb-3" style="max-width: 380px;">
                                    Kelas ini belum memiliki mahasiswa terdaftar. Gunakan tombol "Tambah Mahasiswa" atau "Auto-Enroll Prodi" untuk mengisi peserta kelas.
                                </p>
                                @if($kelas->mahasiswa->count() < $kelas->kuota_mahasiswa)
                                    <button type="button" 
                                            class="btn btn-sm btn-primary px-3 py-2 fw-semibold d-inline-flex align-items-center gap-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalTambahMahasiswa" 
                                            style="background-color: #002B6B; border: none; border-radius: 0.65rem;">
                                        <i class="bi bi-person-plus-fill"></i> Tambah Mahasiswa Sekarang
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="6" class="text-center py-5 text-muted">
                            <div class="py-3 text-center">
                                <i class="bi bi-search fs-2 text-slate-300 mb-2 d-block"></i>
                                <h6 class="fw-bold text-slate-600 mb-1">Tidak Ditemukan</h6>
                                <p class="small text-muted mb-0">Tidak ada mahasiswa yang cocok dengan kata kunci pencarian Anda.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mt-4 pt-3 border-top text-muted small" style="font-size: 0.8rem;">
            <div>
                Menampilkan <span id="visibleCount" class="fw-bold text-dark">{{ $pesertaKelas->count() }}</span> dari total <span class="fw-bold text-dark">{{ $pesertaKelas->count() }}</span> mahasiswa terdaftar
            </div>
            <div>
                Program Studi: <strong class="text-dark">{{ $kelas->programStudi?->nama_prodi ?? '-' }}</strong>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Mahasiswa Manual -->
<div class="modal fade" id="modalTambahMahasiswa" tabindex="-1" aria-labelledby="modalTambahMahasiswaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered px-3 px-sm-0">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <div class="modal-header border-bottom pb-3 px-3 px-sm-4 pt-3 pt-sm-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-2 p-1.5 bg-primary-subtle text-primary d-flex align-items-center justify-content-center shrink-0" style="width: 32px; height: 32px;">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-dark mb-0 fs-6 fs-sm-5" id="modalTambahMahasiswaLabel">
                        Tambah Mahasiswa ke Kelas
                    </h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.kelas.mahasiswa.add', $kelas->id) }}" method="POST">
                @csrf
                <div class="modal-body p-3 p-sm-4">
                    <div class="p-3 mb-3 rounded-3 bg-light border border-light-subtle">
                        <div class="text-muted small mb-1">Mata Kuliah & Kelas Tujuan:</div>
                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</div>
                        <div class="text-muted small">Kelas: <span class="fw-semibold text-primary">{{ $kelas->kode_kelas }}</span> &bull; Prodi: {{ $kelas->programStudi?->nama_prodi ?? '-' }}</div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-bold small text-dark mb-1">
                            Pilih Mahasiswa <span class="text-danger">*</span>
                        </label>
                        @if($mahasiswaTersedia->isEmpty())
                            <div class="alert alert-warning small mb-0 d-flex align-items-center gap-2" role="alert" style="border-radius: 0.65rem;">
                                <i class="bi bi-info-circle-fill fs-5 text-warning shrink-0"></i>
                                <span>Seluruh mahasiswa aktif di Program Studi ini sudah terdaftar di kelas perkuliahan ini.</span>
                            </div>
                        @else
                            <select name="mahasiswa_id" class="form-select" required style="border-radius: 0.65rem; height: 42px; font-size: 0.875rem;">
                                <option value="">-- Cari / Pilih Mahasiswa --</option>
                                @foreach($mahasiswaTersedia as $item)
                                    @php
                                        $itemAngkatan = substr($item->nip_nim, 0, 2) ? '20' . substr($item->nip_nim, 0, 2) : '';
                                    @endphp
                                    <option value="{{ $item->id }}">
                                        {{ $item->nip_nim }} &mdash; {{ $item->name }} {{ $itemAngkatan ? "(Angkatan {$itemAngkatan})" : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted small mt-1.5" style="font-size: 0.775rem;">
                                Menampilkan mahasiswa aktif prodi yang belum terdaftar di kelas ini.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer border-top px-3 px-sm-4 py-3 bg-light d-flex flex-column-reverse flex-sm-row justify-content-sm-end gap-2" style="border-radius: 0 0 1rem 1rem;">
                    <button type="button" class="btn btn-light border px-3 py-2 fw-semibold text-secondary modal-footer-btn m-0" data-bs-dismiss="modal" style="border-radius: 0.65rem; font-size: 0.85rem;">Batal</button>
                    @if($mahasiswaTersedia->isNotEmpty())
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold d-inline-flex align-items-center justify-content-center gap-1.5 modal-footer-btn m-0" style="background-color: #002B6B; border: none; border-radius: 0.65rem; font-size: 0.85rem;">
                            <i class="bi bi-check-lg"></i>
                            <span>Tambahkan ke Kelas</span>
                        </button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const studentRows = document.querySelectorAll('.student-row');
        const visibleCountEl = document.getElementById('visibleCount');
        const noResultsRow = document.getElementById('noResultsRow');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = searchInput.value.toLowerCase().trim();
                let count = 0;

                studentRows.forEach(row => {
                    const name = (row.getAttribute('data-name') || '').toLowerCase();
                    const nim = (row.getAttribute('data-nim') || '').toLowerCase();

                    if (name.includes(query) || nim.includes(query)) {
                        row.style.display = '';
                        count++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (visibleCountEl) {
                    visibleCountEl.textContent = count;
                }

                if (noResultsRow) {
                    noResultsRow.style.display = (count === 0 && studentRows.length > 0) ? '' : 'none';
                }
            });
        }
    });
</script>
@endpush
