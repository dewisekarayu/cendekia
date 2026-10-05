<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                <th>MATA KULIAH & KELAS</th>
                <th>DOSEN PENGAMPU</th>
                <th>JADWAL & RUANG</th>
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
                        <div class="fw-bold text-slate-800" style="font-size: 0.95rem;">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 0.75rem;">{{ $kelas->mataKuliah?->kode_mk ?? '-' }}</span>
                            <span class="text-primary fw-semibold" style="font-size: 0.8rem;">Kelas {{ $kelas->kode_kelas }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold text-slate-800 d-flex align-items-center" style="font-size: 0.875rem;">
                            {{ $kelas->dosen?->name ?? 'Belum Ditentukan' }}
                            @if($kelas->dosenPengampuTambahan && $kelas->dosenPengampuTambahan->count() > 0)
                                <span class="team-teaching-badge" title="Team Teaching: {{ $kelas->dosenPengampuTambahan->pluck('name')->implode(', ') }}">
                                    +{{ $kelas->dosenPengampuTambahan->count() }} Dosen
                                </span>
                            @endif
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;">
                            Prodi: {{ $kelas->programStudi?->nama_prodi ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold text-slate-800" style="font-size: 0.85rem;">
                            {{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}
                        </div>
                        <div class="text-muted mt-1" style="font-size: 0.8rem;">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $kelas->ruangan ?: 'TBA' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge px-3 py-2 fw-bold" style="background-color: #eff6ff; color: #1d4ed8; border-radius: 8px;">
                            <i class="bi bi-people-fill me-1"></i> {{ $kelas->mahasiswa->count() }} / {{ $kelas->kuota_mahasiswa }}
                        </span>
                    </td>
                    <td>
                        @if ($kelas->is_active && $kelas->status_kelas == 'aktif')
                            <span class="badge-status badge-status-aktif">
                                <span class="status-dot"></span> Aktif
                            </span>
                        @else
                            <span class="badge-status badge-status-nonaktif">
                                <span class="status-dot"></span> {{ ucfirst($kelas->status_kelas) }}
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
                            <i class="bi bi-inbox fs-1 text-slate-300 mb-2"></i>
                            <p class="fw-semibold mb-0">Belum ada data kelas perkuliahan.</p>
                            <small class="text-slate-400">Silakan tambahkan data kelas baru melalui tombol di atas.</small>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-4 py-3 border-top border-slate-100 gap-3">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <span class="text-xs fw-bold text-slate-500 text-uppercase tracking-wider text-nowrap">Show:</span>
            <select id="perPageSelect" name="per_page" class="form-select form-select-sm" style="width: 80px; border-radius: 0.5rem; height: 34px;">
                <option value="10" {{ $kelasList->perPage() == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $kelasList->perPage() == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $kelasList->perPage() == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $kelasList->perPage() == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
        <small class="text-muted">Menampilkan {{ $kelasList->firstItem() ?? 0 }}-{{ $kelasList->lastItem() ?? 0 }} dari {{ $kelasList->total() }} data</small>
    </div>
    @if($kelasList->hasPages())
        <div>
            {{ $kelasList->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
