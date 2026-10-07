@extends('layouts.admin')

@section('title', 'Kelola Mahasiswa Kelas')

@section('content')
<div class="container-fluid py-3">
    <div class="mb-4">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-1">
            <ol class="breadcrumb mb-1" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.kelas.index') }}" class="text-decoration-none text-muted">Kelas Perkuliahan</a></li>
                <li class="breadcrumb-item active" aria-current="page" style="color: #002B6B; font-weight: 500;">Kelola Mahasiswa</li>
            </ol>
        </nav>
        <h1 class="page-title h3 fw-bold mb-1" style="color: #002B6B;">Kelola Peserta Kelas</h1>
        <p class="text-muted mb-0" style="font-size: 0.9rem;">
            Pilih mahasiswa yang akan dimasukkan ke dalam kelas <strong class="text-dark">{{ $kelas->mataKuliah?->nama_mk ?? '-' }} - Kelas {{ $kelas->kode_kelas }}</strong>.
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-semibold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kesalahan:</div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 12px;">
        <form action="{{ route('admin.kelas.mahasiswa.sync', $kelas->id) }}" method="POST">
            @csrf
            
            <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-people-fill text-primary me-2"></i>Daftar Mahasiswa (Prodi {{ $kelas->programStudi?->nama_prodi ?? '-' }})</h5>
                    <p class="text-muted small mb-0">Centang kotak di samping nama mahasiswa untuk memasukkannya ke kelas ini. Kuota Kelas: {{ $kelas->kuota_mahasiswa }} Mahasiswa.</p>
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-3 gap-3">
                <div class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                    <div class="flex-grow-1">
                        <label class="form-label small fw-semibold text-muted mb-1">Cari Mahasiswa</label>
                        <input type="text" id="searchInput" class="form-control" placeholder="Ketik nama atau NIM...">
                    </div>
                    <div>
                        <label class="form-label small fw-semibold text-muted mb-1">Filter Angkatan</label>
                        <select id="angkatanFilter" class="form-select">
                            <option value="">Semua Angkatan</option>
                            <!-- Pilihan angkatan akan diisi otomatis lewat JS -->
                        </select>
                    </div>
                </div>
                <div class="text-muted small">
                    Menampilkan <span id="visibleCount" class="fw-bold text-dark">0</span> mahasiswa
                </div>
            </div>

            <div class="table-responsive mb-4">
                <table class="table table-hover align-middle border" id="mahasiswaTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>NIM</th>
                            <th>NAMA MAHASISWA</th>
                            <th>ANGKATAN</th>
                        </tr>
                    </thead>
                    <tbody id="studentTableBody">
                        @forelse($mahasiswas as $mhs)
                        @php
                            $angkatan = substr($mhs->nip_nim, 0, 2) ? '20' . substr($mhs->nip_nim, 0, 2) : '-';
                        @endphp
                        <tr class="student-row" data-name="{{ strtolower($mhs->name) }}" data-nim="{{ strtolower($mhs->nip_nim) }}" data-angkatan="{{ $angkatan }}">
                            <td class="text-center">
                                <div class="form-check d-flex justify-content-center">
                                    <input class="form-check-input student-checkbox" type="checkbox" name="mahasiswas[]" value="{{ $mhs->id }}" 
                                        {{ in_array($mhs->id, $mahasiswaIds) ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="font-monospace text-muted">{{ $mhs->nip_nim }}</td>
                            <td class="fw-semibold">{{ $mhs->name }}</td>
                            <td>{{ $angkatan }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada data mahasiswa di program studi ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-light border px-4 py-2 fw-semibold text-secondary" style="border-radius: 8px;">Batal</a>
                <button type="submit" class="btn btn-primary px-4 py-2 fw-semibold d-flex align-items-center gap-2" style="background-color: #002B6B; border: none; border-radius: 8px;">
                    <i class="bi bi-save"></i> Simpan Peserta Kelas
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const angkatanFilter = document.getElementById('angkatanFilter');
        const studentRows = document.querySelectorAll('.student-row');
        const visibleCountEl = document.getElementById('visibleCount');
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.student-checkbox');

        // Populate Angkatan Filter automatically
        const angkatanSet = new Set();
        studentRows.forEach(row => {
            const a = row.getAttribute('data-angkatan');
            if (a && a !== '-') angkatanSet.add(a);
        });
        const sortedAngkatan = Array.from(angkatanSet).sort().reverse();
        sortedAngkatan.forEach(a => {
            const opt = document.createElement('option');
            opt.value = a;
            opt.textContent = a;
            if (angkatanFilter) angkatanFilter.appendChild(opt);
        });

        function filterTable() {
            const query = searchInput ? searchInput.value.toLowerCase() : '';
            const angkatan = angkatanFilter ? angkatanFilter.value : '';
            let visibleCount = 0;

            studentRows.forEach(row => {
                const name = row.getAttribute('data-name');
                const nim = row.getAttribute('data-nim');
                const rowAngkatan = row.getAttribute('data-angkatan');

                const matchSearch = name.includes(query) || nim.includes(query);
                const matchAngkatan = angkatan === '' || rowAngkatan === angkatan;

                if (matchSearch && matchAngkatan) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleCountEl) visibleCountEl.textContent = visibleCount;
            updateSelectAllStatus();
        }

        if (searchInput) searchInput.addEventListener('input', filterTable);
        if (angkatanFilter) angkatanFilter.addEventListener('change', filterTable);

        // Select All Logic (Only affects VISIBLE rows)
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                studentRows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cb = row.querySelector('.student-checkbox');
                        if (cb) cb.checked = selectAll.checked;
                    }
                });
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateSelectAllStatus);
            });
        }

        function updateSelectAllStatus() {
            if (!selectAll) return;
            const visibleCheckboxes = Array.from(studentRows)
                .filter(row => row.style.display !== 'none')
                .map(row => row.querySelector('.student-checkbox'))
                .filter(cb => cb !== null);

            if (visibleCheckboxes.length === 0) {
                selectAll.checked = false;
                return;
            }

            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            selectAll.checked = allChecked;
        }

        // Init
        if (studentRows.length > 0) {
            filterTable();
        }
    });
</script>
@endpush
