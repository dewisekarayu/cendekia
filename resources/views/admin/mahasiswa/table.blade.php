{{-- resources/views/admin/mahasiswa/table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="width: 60px; text-align: center; padding-left: 1.5rem;">NO</th>
                <th style="width: 130px;">NIM</th>
                <th>FOTO & NAMA LENGKAP</th>
                <th>PROGRAM STUDI</th>
                <th>STATUS</th>
                <th class="text-center" style="width: 120px;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mahasiswa as $item)
                @php
                    $statusValue = strtolower($item->status ?? 'aktif');
                @endphp
                <tr>
                    <td style="padding-left: 1.5rem;" class="text-center font-monospace text-slate-500 fw-bold">
                        {{ ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <span class="badge-code">{{ $item->nip_nim }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $item->foto ? asset('storage/' . $item->foto) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($item->name) }}"
                                 style="width: 38px; height: 38px; border-radius: 50%; border: 1.5px solid #e2e8f0; object-fit: cover; flex-shrink: 0;" alt="{{ $item->name }}">
                            <div>
                                <div class="fw-bold text-slate-800 dark:text-white" style="font-size: 0.9rem;">{{ $item->name }}</div>
                                <small class="text-slate-400" style="font-size: 0.775rem;">{{ $item->email }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-akreditasi">
                            {{ $item->programStudi?->nama_prodi ?? 'Belum memilih prodi' }}
                        </span>
                    </td>
                    <td>
                        @if($statusValue === 'aktif')
                            <span class="badge-status badge-status-aktif">
                                <span class="status-dot"></span>
                                Aktif
                            </span>
                        @elseif($statusValue === 'cuti')
                            <span class="badge-status badge-status-cuti">
                                <span class="status-dot"></span>
                                Cuti
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
                            <a href="{{ route('admin.mahasiswa.edit', $item->id) }}" class="action-btn action-btn-edit" title="Edit">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.mahasiswa.destroy', $item->id) }}" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')">
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
                            <i class="bi bi-mortarboard fs-1 text-slate-300 dark:text-slate-600 mb-2"></i>
                            <p class="fw-semibold mb-0">Belum ada data mahasiswa.</p>
                            <small class="text-slate-400">Silakan tambahkan data mahasiswa baru melalui tombol di atas.</small>
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
            <select id="perPageSelect" name="per_page" class="form-select form-select-sm shadow-sm" style="width: 80px; min-height: 38px; padding: 6px 28px 6px 12px; font-size: 0.875rem; font-weight: 600; line-height: 1.5; border-radius: 0.5rem;">
                <option value="10" {{ $mahasiswa->perPage() == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ $mahasiswa->perPage() == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ $mahasiswa->perPage() == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $mahasiswa->perPage() == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
        <small class="text-muted">Menampilkan {{ $mahasiswa->firstItem() ?? 0 }}-{{ $mahasiswa->lastItem() ?? 0 }} dari {{ $mahasiswa->total() }} data</small>
    </div>
    @if($mahasiswa->hasPages())
        <div>
            {{ $mahasiswa->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>