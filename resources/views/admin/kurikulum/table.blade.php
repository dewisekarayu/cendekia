<div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
        <thead class="table-light">
            <tr>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 50px;">No</th>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Program Studi</th>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Nama Kurikulum</th>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Tahun Mulai</th>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">Status</th>
                <th scope="col" class="py-3 px-4 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; width: 120px;">Aksi</th>
            </tr>
        </thead>
        <tbody class="border-top-0">
            @forelse($kurikulumList as $key => $kurikulum)
                <tr>
                    <td class="px-4 text-muted">{{ $kurikulumList->firstItem() + $key }}</td>
                    <td class="px-4">{{ $kurikulum->programStudi->nama_prodi ?? '-' }}</td>
                    <td class="px-4 fw-semibold">{{ $kurikulum->nama_kurikulum }}</td>
                    <td class="px-4">{{ $kurikulum->tahun_mulai }}</td>
                    <td class="px-4">
                        @if($kurikulum->is_active)
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Aktif</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.kurikulum.show', $kurikulum->id) }}" class="btn btn-sm btn-light text-secondary border" data-bs-toggle="tooltip" title="Lihat">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.kurikulum.edit', $kurikulum->id) }}" class="btn btn-sm btn-light text-primary border" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.kurikulum.destroy', $kurikulum->id) }}" method="POST" data-confirm="Yakin ingin menghapus kurikulum ini?">
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
                    <td colspan="6" class="text-center py-5">
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-journal-bookmark fs-1 text-muted mb-3 opacity-50"></i>
                            <h6 class="fw-semibold text-muted mb-1">Data Kurikulum Tidak Ditemukan</h6>
                            <p class="text-muted small mb-0">Belum ada data kurikulum atau kata kunci tidak cocok.</p>
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
            Menampilkan <span class="fw-semibold text-dark">{{ $kurikulumList->firstItem() ?? 0 }}</span> sampai <span class="fw-semibold text-dark">{{ $kurikulumList->lastItem() ?? 0 }}</span> dari <span class="fw-semibold text-dark">{{ $kurikulumList->total() }}</span> entri
        </div>
        <div class="d-flex align-items-center gap-3">
            <select id="perPageSelect" class="form-select form-select-sm" style="width: 70px;">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>
            <div>
                {{ $kurikulumList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
