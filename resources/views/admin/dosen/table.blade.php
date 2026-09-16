{{-- resources/views/admin/dosen/table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                <th>FOTO & NAMA LENGKAP</th>
                <th>NIDN</th>
                <th>PROGRAM STUDI</th>
                <th>KONTAK</th>
                <th>STATUS</th>
                <th class="text-center" style="width: 120px;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dosen as $item)
                <tr>
                    <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                        {{ ($dosen->currentPage() - 1) * $dosen->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($item->name) }}" 
                                 style="width: 38px; height: 38px; border-radius: 50%; background-color: #f1f5f9; border: 1.5px solid #e2e8f0; flex-shrink: 0;" alt="{{ $item->name }}">
                            <div>
                                <div class="fw-bold text-slate-800 dark:text-white" style="font-size: 0.9rem;">{{ $item->name }}</div>
                                <small class="text-slate-400" style="font-size: 0.775rem;">{{ $item->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-code">{{ $item->nip_nim }}</span>
                    </td>
                    <td>
                        <span class="badge-akreditasi">
                            {{ $item->programStudi?->nama_prodi ?? 'Lintas Prodi' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-slate-600 dark:text-slate-400 fw-medium" style="font-size: 0.85rem;">
                            {{ $item->telepon ?? '-' }}
                        </span>
                    </td>
                    <td>
                        @if($item->status == 'aktif')
                            <span class="badge-status badge-status-aktif">
                                <span class="status-dot"></span>
                                Aktif
                            </span>
                        @else
                            <span class="badge-status badge-status-nonaktif">
                                <span class="status-dot"></span>
                                Nonaktif
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons justify-content-center">
                            <a href="{{ route('admin.dosen.edit', $item->id) }}" class="action-btn action-btn-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.dosen.destroy', $item->id) }}" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?')">
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
                            <i class="bi bi-people fs-1 text-slate-300 dark:text-slate-600 mb-2"></i>
                            <p class="fw-semibold mb-0">Belum ada data dosen terdaftar.</p>
                            <small class="text-slate-400">Silakan tambahkan data dosen baru melalui tombol di atas.</small>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-4 py-3 border-t border-slate-100 dark:border-slate-700/60 gap-3">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <span class="text-xs fw-bold text-slate-500 uppercase tracking-wider text-nowrap">Show:</span>
            <select id="perPageSelect" name="per_page" class="form-select form-select-sm" style="width: 80px; border-radius: 0.5rem; height: 34px;">
                <option value="10" {{ $dosen->perPage() == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $dosen->perPage() == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $dosen->perPage() == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $dosen->perPage() == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
        <small class="text-muted">Menampilkan {{ $dosen->firstItem() ?? 0 }}-{{ $dosen->lastItem() ?? 0 }} dari {{ $dosen->total() }} data</small>
    </div>
    @if($dosen->hasPages())
        <div>
            {{ $dosen->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>