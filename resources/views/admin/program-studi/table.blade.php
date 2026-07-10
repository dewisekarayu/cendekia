{{-- resources/views/admin/program-studi/table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>KODE PRODI</th>
                <th>NAMA PROGRAM STUDI</th>
                <th>JENJANG PENDIDIKAN</th>
                <th>AKREDITASI</th>
                <th>STATUS</th>
                <th class="text-center">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($prodiList as $prodi)
                <tr>
                    <td style="font-weight: 700; color: #002B6B;">{{ $prodi->kode_prodi }}</td>
                    <td style="font-weight: 600;">{{ $prodi->nama_prodi }}</td>
                    <td>{{ $prodi->jenjang }} - {{ $prodi->jenjang === 'S1' ? 'Sarjana' : 'Program ' . $prodi->jenjang }}</td>
                    <td>
                        <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-semibold">{{ $prodi->akreditasi ?? 'Baik' }}</span>
                    </td>
                    <td>
                        @if($prodi->status == 1)
                            <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">Aktif</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-2 py-1">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="action-buttons justify-content-center">
                            <a href="{{ route('admin.program-studi.edit', $prodi->id) }}" class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('admin.program-studi.destroy', $prodi->id) }}" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus prodi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">Belum ada data program studi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(0, 43, 107, 0.08);">
    {{ $prodiList->links() }}
</div>