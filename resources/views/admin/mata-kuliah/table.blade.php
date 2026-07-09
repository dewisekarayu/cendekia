{{-- resources/views/admin/mata-kuliah/table.blade.php --}}
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">KODE MK</th>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">NAMA MATA KULIAH</th>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">PROGRAM STUDI</th>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem; text-align: center;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mataKuliah as $mk)
                <tr>
                    <td style="padding: 1.25rem 1.5rem; font-weight: 700; color: #002B6B;">{{ $mk->kode_mk }}</td>
                    <td style="padding: 1.25rem 1.5rem; font-weight: 600;">{{ $mk->nama_mk }}</td>
                    <td style="padding: 1.25rem 1.5rem; color: #475569;">
                        {{ $mk->programStudi->nama_prodi ?? '-' }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('admin.mata-kuliah.edit', $mk->id) }}" class="btn btn-sm btn-light border text-primary p-1.5 px-2" title="Edit" style="border-radius: 6px; color: #002B6B !important;">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form action="{{ route('admin.mata-kuliah.destroy', $mk->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-light border text-danger p-1.5 px-2" title="Hapus" style="border-radius: 6px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-5">
                        <i class="bi bi-book fs-2 d-block mb-2 text-black-50"></i>
                        Belum ada data mata kuliah.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end p-4 border-top">
    {{ $mataKuliah->links() }}
</div>