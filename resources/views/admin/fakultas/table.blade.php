<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
        <thead class="table-light">
            <tr>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 50px;">No</th>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Kode</th>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nama Fakultas</th>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            @forelse($fakultasList as $key => $fakultas)
                <tr>
                    <td class="px-4 text-muted">{{ $fakultasList->firstItem() + $key }}</td>
                    <td class="px-4"><span class="badge bg-light text-dark border">{{ $fakultas->kode_fakultas }}</span></td>
                    <td class="px-4 fw-semibold">{{ $fakultas->nama_fakultas }}</td>
                    <td class="px-4">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.fakultas.show', $fakultas->id) }}" class="btn btn-sm btn-light text-secondary border" data-bs-toggle="tooltip" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.fakultas.edit', $fakultas->id) }}" class="btn btn-sm btn-light text-primary border" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.fakultas.destroy', $fakultas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus fakultas ini? Data program studi di bawahnya mungkin terpengaruh.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light text-danger border" data-bs-toggle="tooltip" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-building fs-1 text-muted mb-3 opacity-50"></i>
                            <h6 class="fw-semibold text-muted mb-1">Data Fakultas Tidak Ditemukan</h6>
                            <p class="text-muted small mb-0">Belum ada data fakultas atau kata kunci tidak cocok.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="px-4 py-3 border-top border-slate-100">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="text-muted small">
            Menampilkan <span class="fw-semibold text-dark">{{ $fakultasList->firstItem() ?? 0 }}</span> sampai <span class="fw-semibold text-dark">{{ $fakultasList->lastItem() ?? 0 }}</span> dari <span class="fw-semibold text-dark">{{ $fakultasList->total() }}</span> entri
        </div>
        <div class="d-flex align-items-center gap-3">
            <select id="perPageSelect" class="form-select form-select-sm" style="width: 70px;">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
            <div>
                {{ $fakultasList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
