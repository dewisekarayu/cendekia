{{-- resources/views/admin/mata-kuliah/table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                <th style="width: 140px;">KODE MK</th>
                <th>NAMA MATA KULIAH</th>
                <th>PROGRAM STUDI</th>
                <th class="text-center" style="width: 120px;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mataKuliah as $mk)
                <tr>
                    <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                        {{ ($mataKuliah->currentPage() - 1) * $mataKuliah->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <span class="badge-code">{{ $mk->kode_mk }}</span>
                    </td>
                    <td>
                        <span class="fw-bold text-slate-800 dark:text-white" style="font-size: 0.925rem;">{{ $mk->nama_mk }}</span>
                    </td>
                    <td>
                        <span class="badge-akreditasi">
                            {{ $mk->programStudi->nama_prodi ?? 'Umum / Semua Prodi' }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons justify-content-center">
                            <a href="{{ route('admin.mata-kuliah.edit', $mk->id) }}" class="action-btn action-btn-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>

                            <form action="{{ route('admin.mata-kuliah.destroy', $mk->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
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
                    <td colspan="5" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                            <i class="bi bi-book fs-1 text-slate-300 dark:text-slate-600 mb-2"></i>
                            <p class="fw-semibold mb-0">Belum ada data mata kuliah.</p>
                            <small class="text-slate-400">Silakan tambahkan data mata kuliah baru melalui tombol di atas.</small>
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
                <option value="10" {{ $mataKuliah->perPage() == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $mataKuliah->perPage() == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $mataKuliah->perPage() == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $mataKuliah->perPage() == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
        <small class="text-muted">Menampilkan {{ $mataKuliah->firstItem() ?? 0 }}-{{ $mataKuliah->lastItem() ?? 0 }} dari {{ $mataKuliah->total() }} data</small>
    </div>
    @if($mataKuliah->hasPages())
        <div>
            {{ $mataKuliah->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>