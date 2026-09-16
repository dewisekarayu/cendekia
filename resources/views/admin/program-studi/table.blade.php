{{-- resources/views/admin/program-studi/table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                <th style="width: 140px;">KODE PRODI</th>
                <th>NAMA PROGRAM STUDI</th>
                <th>JENJANG PENDIDIKAN</th>
                <th>AKREDITASI</th>
                <th>STATUS</th>
                <th class="text-center" style="width: 120px;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($prodiList as $prodi)
                <tr>
                    <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                        {{ ($prodiList->currentPage() - 1) * $prodiList->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <span class="badge-code">{{ $prodi->kode_prodi }}</span>
                    </td>
                    <td>
                        <span class="fw-bold text-slate-800 dark:text-white" style="font-size: 0.925rem;">{{ $prodi->nama_prodi }}</span>
                    </td>
                    <td>
                        <span class="text-secondary fw-semibold">{{ $prodi->jenjang }} - {{ $prodi->jenjang === 'S1' ? 'Sarjana' : 'Program ' . $prodi->jenjang }}</span>
                    </td>
                    <td>
                        <span class="badge-akreditasi">{{ $prodi->akreditasi ?? 'Baik' }}</span>
                    </td>
                    <td>
                        @if($prodi->status == 1)
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
                            <a href="{{ route('admin.program-studi.edit', $prodi->id) }}" class="action-btn action-btn-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.program-studi.destroy', $prodi->id) }}" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data program studi ini?')">
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
                            <i class="bi bi-diagram-3 fs-1 text-slate-300 dark:text-slate-600 mb-2"></i>
                            <p class="fw-semibold mb-0">Belum ada data program studi.</p>
                            <small class="text-slate-400">Silakan tambahkan data prodi baru melalui tombol di atas.</small>
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
                <option value="10" {{ $prodiList->perPage() == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $prodiList->perPage() == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $prodiList->perPage() == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $prodiList->perPage() == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
        <small class="text-muted">Menampilkan {{ $prodiList->firstItem() ?? 0 }}-{{ $prodiList->lastItem() ?? 0 }} dari {{ $prodiList->total() }} data</small>
    </div>
    @if($prodiList->hasPages())
        <div>
            {{ $prodiList->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>