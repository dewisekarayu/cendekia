<x-admin-layout>
    <div class="container-fluid">
        <div class="mb-4">
            <h1 class="page-title mb-0">Edit Mata Kuliah</h1>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.mata-kuliah.index') }}">Mata Kuliah</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>

        <div class="table-card">
            <form method="POST" action="{{ route('admin.mata-kuliah.update', $mk->id) }}" style="padding: 1.5rem;">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Kode Mata Kuliah</label>
                    <input type="text" name="kode_mk" class="form-control" value="{{ $mk->kode_mk }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Mata Kuliah</label>
                    <input type="text" name="nama_mk" class="form-control" value="{{ $mk->nama_mk }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">SKS</label>
                    <input type="number" name="sks" class="form-control" value="{{ $mk->sks }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <input type="number" name="semester_ke" class="form-control" value="{{ $mk->semester_ke }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Dosen Pengampu</label>
                    <select name="dosen_id" class="form-select">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosenList as $d)
                            <option value="{{ $d->id }}" {{ $mk->dosen_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="program_studi_id" class="form-select">
                        @foreach($prodiList as $p)
                            <option value="{{ $p->id }}" {{ $mk->program_studi_id == $p->id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="4">{{ $mk->deskripsi }}</textarea>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('admin.mata-kuliah.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
