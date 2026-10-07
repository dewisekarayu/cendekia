<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                <th>NAMA SEMESTER</th>
                <th>TAHUN AJARAN</th>
                <th>JENIS</th>
                <th>TANGGAL PERIODE</th>
                <th class="text-center" style="width: 140px;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tahunAkademikList as $ta)
                <tr>
                    <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                        {{ ($tahunAkademikList->currentPage() - 1) * $tahunAkademikList->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <span class="fw-bold text-slate-800" style="font-size: 0.925rem;">{{ $ta->nama_semester }}</span>
                    </td>
                    <td>
                        <span class="text-muted fw-semibold">{{ $ta->tahun_ajaran }}</span>
                    </td>
                    <td>
                        <span class="badge {{ $ta->jenis == 'Ganjil' ? 'bg-primary' : 'bg-info' }} bg-opacity-10 text-{{ $ta->jenis == 'Ganjil' ? 'primary' : 'info' }} px-2 py-1 rounded-2 fw-semibold" style="font-size: 0.75rem;">
                            {{ $ta->jenis }}
                        </span>
                    </td>
                    <td>
                        <div class="text-muted small">
                            <i class="bi bi-calendar-event me-1"></i> {{ $ta->tanggal_mulai->format('d M Y') }} - {{ $ta->tanggal_selesai->format('d M Y') }}
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons justify-content-center">
                            <a href="{{ route('admin.tahun-akademik.show', $ta->id) }}" class="action-btn action-btn-view" title="Lihat">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('admin.tahun-akademik.edit', $ta->id) }}" class="action-btn action-btn-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.tahun-akademik.destroy', $ta->id) }}" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tahun akademik ini?')">
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
                    <td colspan="6" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center justify-content-center text-muted">
                            <i class="bi bi-calendar-x fs-1 text-slate-300 mb-2"></i>
                            <p class="fw-semibold mb-0">Belum ada data tahun akademik.</p>
                            <small class="text-slate-400">Silakan tambahkan data tahun akademik baru melalui tombol di atas.</small>
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
                <option value="10" {{ $tahunAkademikList->perPage() == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $tahunAkademikList->perPage() == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $tahunAkademikList->perPage() == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $tahunAkademikList->perPage() == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
        <small class="text-muted">Menampilkan {{ $tahunAkademikList->firstItem() ?? 0 }}-{{ $tahunAkademikList->lastItem() ?? 0 }} dari {{ $tahunAkademikList->total() }} data</small>
    </div>
    @if($tahunAkademikList->hasPages())
        <div>
            {{ $tahunAkademikList->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
