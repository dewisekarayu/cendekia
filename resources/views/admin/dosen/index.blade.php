<x-admin-layout>
    <div class="container-fluid px-0">
        {{-- Flash Message Success Alert --}}
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center justify-content-between border-0 shadow-sm mb-4" style="border-radius: 0.85rem; background-color: #ecfdf5; color: #065f46;" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                    <span class="fw-semibold">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1 class="page-title mb-1">Manajemen Dosen</h1>
                <p class="text-muted mb-2" style="font-size: 0.875rem;">Kelola seluruh profil dosen, nomor induk (NIDN), dan status penugasan program studi.</p>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><span class="text-slate-500">Master Data</span></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Dosen</li>
                    </ol>
                </nav>
            </div>
            <!-- Action Buttons: Export PDF, Export Excel, Import CSV, Tambah Dosen -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                {{-- Dropdown Ekspor --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 0.75rem; height: 42px;">
                        <i class="bi bi-download"></i>
                        <span>Ekspor</span>
                        <i class="bi bi-chevron-down" style="font-size: 0.7rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border" style="border-radius: 0.75rem; min-width: 200px;">
                        <li>
                            <a href="{{ route('admin.dosen.export.pdf', request()->only(['search', 'program_studi_id'])) }}" id="btnExportPdf" class="dropdown-item d-flex align-items-center gap-2 py-2" target="_blank">
                                <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                <span>Ekspor PDF</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.dosen.export.excel', request()->only(['search', 'program_studi_id'])) }}" id="btnExportExcel" class="dropdown-item d-flex align-items-center gap-2 py-2">
                                <i class="bi bi-file-earmark-excel-fill text-success"></i>
                                <span>Ekspor Excel</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button type="button" class="btn btn-outline-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#modalImportDosen" style="border-radius: 0.75rem; height: 42px;">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    <span>Impor</span>
                </button>
                <a href="{{ route('admin.dosen.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="border-radius: 0.75rem; height: 42px;">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tambah Dosen</span>
                </a>
            </div>
        </div>

        <!-- Modal Wizard Impor Data Dosen (Upload & Preview Validasi) -->
        <div class="modal fade" id="modalImportDosen" tabindex="-1" aria-labelledby="modalImportDosenTitle" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
                    
                    <!-- Modal Header -->
                    <div class="modal-header border-bottom px-4 py-3 bg-light">
                        <div class="d-flex align-items-center gap-2">
                            <div class="p-2 bg-primary-subtle text-primary rounded-3">
                                <i class="bi bi-file-earmark-spreadsheet-fill fs-5"></i>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold text-slate-800 mb-0" id="modalImportDosenTitle">Impor Data Dosen via Excel/CSV</h5>
                                <p class="text-muted small mb-0">Unggah berkas spreadsheet dan tinjau validasi data sebelum disimpan ke database</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-4">

                        <!-- STEP 1: UPLOAD & DOWNLOAD TEMPLATE -->
                        <div id="stepUpload">
                            <div class="card border border-info-subtle bg-info-subtle/20 rounded-3 mb-4">
                                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                    <div>
                                        <h6 class="fw-bold text-slate-800 mb-2">
                                            <i class="bi bi-info-circle-fill text-info me-1"></i> Panduan Format Kolom Data
                                        </h6>
                                        <div class="text-muted small">
                                            <p class="mb-2">Pastikan urutan kolom dari kiri ke kanan sesuai format berikut:</p>
                                            <ul class="list-unstyled mb-0" style="padding-left: 0.25rem;">
                                                <li class="mb-1"><i class="bi bi-check2-circle text-primary me-1"></i> <strong>NIP / NIDN</strong> (Wajib, Unik)</li>
                                                <li class="mb-1"><i class="bi bi-check2-circle text-primary me-1"></i> <strong>Nama Lengkap & Email</strong> (Wajib, Email Unik)</li>
                                                <li class="mb-1"><i class="bi bi-dash-circle text-secondary me-1"></i> <strong>No. Telepon</strong> (Opsional)</li>
                                                <li class="mb-2"><i class="bi bi-check2-circle text-primary me-1"></i> <strong>Program Studi</strong> (Wajib). Pilihan valid:
                                                    <div class="mt-1 ms-4">
                                                        @foreach ($programStudiList as $prodi)
                                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle me-1 mb-1">{{ $prodi->nama_prodi }}</span>
                                                        @endforeach
                                                    </div>
                                                </li>
                                                <li><i class="bi bi-check2-circle text-primary me-1"></i> <strong>Status</strong> (Wajib). Pilihan valid: 
                                                    <div class="mt-1 ms-4">
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle me-1">Aktif</span>
                                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle me-1">Cuti</span>
                                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle me-1">Non-Aktif</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-2 text-nowrap">
                                        <a href="{{ route('admin.dosen.template') }}" class="btn btn-sm btn-outline-primary text-nowrap d-inline-flex align-items-center gap-1 shadow-sm">
                                            <i class="bi bi-download"></i> Unduh Template (.CSV)
                                        </a>
                                        <a href="{{ route('admin.dosen.import.view') }}" class="btn btn-sm btn-link text-slate-500 text-decoration-none">
                                            <i class="bi bi-gear me-1"></i> Halaman Panduan Impor
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <form id="formUploadPreview" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label for="fileDosen" class="form-label fw-semibold text-slate-700">Pilih Berkas CSV / Spreadsheet</label>
                                    <input class="form-control form-control-lg" type="file" id="fileDosen" name="file_dosen" accept=".csv, .txt" required>
                                    <div class="form-text text-muted">Format yang didukung: <code>.csv</code> atau <code>.txt</code> (Maks. 5MB). File dapat dibuka dan diedit di Microsoft Excel.</div>
                                </div>

                                <div id="uploadAlert" class="alert alert-danger d-none py-2 px-3 small" role="alert"></div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" id="btnProsesPreview" class="btn btn-primary px-4 d-inline-flex align-items-center gap-2">
                                        <span class="spinner-border spinner-border-sm d-none" id="spinnerUpload" role="status" aria-hidden="true"></span>
                                        <i class="bi bi-search" id="iconUpload"></i>
                                        <span>Cek & Tinjau Data</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- STEP 2: PREVIEW DATA & VALIDASI -->
                        <div id="stepPreview" class="d-none">
                            <!-- Ringkasan Statistik -->
                            <div class="row g-3 mb-3">
                                <div class="col-sm-4">
                                    <div class="p-3 border rounded-3 bg-light text-center">
                                        <div class="text-muted small fw-semibold">Total Baris Terbaca</div>
                                        <div class="fs-4 fw-bold text-dark" id="statTotalRows">0</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="p-3 border border-success-subtle bg-success-subtle/30 rounded-3 text-center">
                                        <div class="text-success small fw-semibold">Siap Diimpor (Valid)</div>
                                        <div class="fs-4 fw-bold text-success" id="statValidRows">0</div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="p-3 border border-danger-subtle bg-danger-subtle/30 rounded-3 text-center">
                                        <div class="text-danger small fw-semibold">Bermasalah (Invalid)</div>
                                        <div class="fs-4 fw-bold text-danger" id="statInvalidRows">0</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filter Baris & Tombol Ganti File -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="toggleOnlyErrors">
                                    <label class="form-check-label small fw-semibold text-slate-700" for="toggleOnlyErrors">
                                        Hanya tampilkan baris bermasalah (<span id="badgeErrorCount">0</span>)
                                    </label>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnGantiFile">
                                    <i class="bi bi-arrow-repeat me-1"></i> Unggah File Lain
                                </button>
                            </div>

                            <!-- Tabel Tinjauan (Preview) -->
                            <div class="table-responsive border rounded-3" style="max-height: 380px;">
                                <table class="table table-hover align-middle mb-0" id="tablePreview">
                                    <thead class="table-light sticky-top">
                                        <tr class="text-nowrap small text-uppercase fw-bold text-slate-600">
                                            <th style="width: 50px;">Baris</th>
                                            <th>Status</th>
                                            <th>NIP / NIDN</th>
                                            <th>Nama Lengkap</th>
                                            <th>Email</th>
                                            <th>No. Telepon</th>
                                            <th>Program Studi</th>
                                            <th>Status Dosen</th>
                                            <th>Validasi / Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbodyPreview">
                                        <!-- Diisi via JavaScript -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Footer Step 2 -->
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mt-4">
                                <div class="small text-muted" id="previewNote">
                                    <i class="bi bi-info-circle me-1"></i> Baris bermasalah (merah) akan dilewati dan tidak disimpan ke database.
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-light px-3" data-bs-dismiss="modal">Tutup</button>
                                    <button type="button" class="btn btn-success px-4 d-inline-flex align-items-center gap-2" id="btnKonfirmasiSimpan">
                                        <span class="spinner-border spinner-border-sm d-none" id="spinnerSimpan" role="status"></span>
                                        <i class="bi bi-check-circle-fill" id="iconSimpan"></i>
                                        <span id="textBtnSimpan">Simpan Data Valid</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="table-card">
            {{-- Bagian Form Filter & Pencarian Aktif --}}
            <div class="p-3.5 sm:p-4 border-b border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-800/50">
                <div class="row g-3 align-items-center">
                    {{-- Input Pencarian Otomatis (Live Search) --}}
                    <div class="col-md-7">
                        <div class="position-relative">
                            <input type="text" id="liveSearchInput" name="search" class="form-control ps-4" value="{{ $search ?? '' }}" placeholder="Cari Nama, NIDN, atau Email Dosen..." style="height: 44px; padding-right: 2.75rem;" autocomplete="off">
                            <div id="searchSpinner" class="spinner-border spinner-border-sm text-secondary d-none" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);" role="status"></div>
                            <i id="searchIcon" class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1rem;"></i>
                        </div>
                    </div>
                    
                    {{-- Dropdown Filter Program Studi --}}
                    <div class="col-md-5 d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
                        <form method="GET" action="{{ route('admin.dosen.index') }}" id="filterForm" class="d-flex align-items-center gap-2 flex-grow-1 flex-md-grow-0">
                            <input type="hidden" name="search" id="hiddenSearchInput" value="{{ $search ?? '' }}">
                            <input type="hidden" name="per_page" id="filterPerPageInput" value="{{ request('per_page', 10) }}">
                            
                            <select name="program_studi_id" id="prodiSelect" class="form-select" style="height: 44px; min-width: 180px;">
                                <option value="">Semua Program Studi</option>
                                @foreach ($programStudiList as $prodi)
                                    <option value="{{ $prodi->id }}" {{ ($prodiFilter ?? '') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                            
                            @if(($search ?? '') || ($prodiFilter ?? '') || request('per_page'))
                                <a href="{{ route('admin.dosen.index') }}" class="btn btn-light border d-flex align-items-center justify-content-center px-3" style="border-radius: 0.75rem; height: 44px;" title="Reset Filter">
                                    <i class="bi bi-arrow-clockwise"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            {{-- Container Utama Tabel (Akan di-refresh otomatis oleh JavaScript) --}}
            <div id="tableContainer">
                @include('admin.dosen.table')
            </div>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
        <div id="appToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastMessage"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    {{-- JavaScript Ajax Live Search & Modal Import Validasi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('liveSearchInput');
            const hiddenSearchInput = document.getElementById('hiddenSearchInput');
            const prodiSelect = document.getElementById('prodiSelect');
            const tableContainer = document.getElementById('tableContainer');
            const searchIcon = document.getElementById('searchIcon');
            const spinner = document.getElementById('searchSpinner');
            const btnExportPdf = document.getElementById('btnExportPdf');
            const btnExportExcel = document.getElementById('btnExportExcel');
            
            let typingTimer;
            const doneTypingInterval = 350;

            function showToast(msg, type = 'success') {
                const toastEl = document.getElementById('appToast');
                const toastMsg = document.getElementById('toastMessage');
                if (!toastEl) return;
                toastMsg.textContent = msg;
                toastEl.className = `toast align-items-center text-white border-0 bg-${type}`;
                const toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                toast.show();
            }

            function updateExportUrls(searchValue, prodiValue) {
                const perPageEl = document.getElementById('perPageSelect');
                const perPageValue = perPageEl ? perPageEl.value : (document.getElementById('filterPerPageInput') ? document.getElementById('filterPerPageInput').value : 10);

                const params = new URLSearchParams();
                if (searchValue) params.set('search', searchValue);
                if (prodiValue) params.set('program_studi_id', prodiValue);
                if (perPageValue) params.set('per_page', perPageValue);

                const pdfBase = "{{ route('admin.dosen.export.pdf') }}";
                const excelBase = "{{ route('admin.dosen.export.excel') }}";

                if (btnExportPdf) btnExportPdf.href = pdfBase + (params.toString() ? '?' + params.toString() : '');
                if (btnExportExcel) btnExportExcel.href = excelBase + (params.toString() ? '?' + params.toString() : '');
            }

            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    performSearch(1);
                });
            }

            searchInput.addEventListener('keyup', function () {
                clearTimeout(typingTimer);
                hiddenSearchInput.value = searchInput.value;
                typingTimer = setTimeout(performSearch, doneTypingInterval);
            });

            searchInput.addEventListener('keydown', function () {
                clearTimeout(typingTimer);
            });

            prodiSelect.addEventListener('change', () => performSearch(1));

            document.addEventListener('change', function (e) {
                if (e.target && e.target.id === 'perPageSelect') {
                    performSearch(1);
                }
            });

            function performSearch(page = 1) {
                searchIcon.classList.add('d-none');
                spinner.classList.remove('d-none');

                const searchValue = searchInput.value;
                const prodiValue = prodiSelect.value;
                const perPageEl = document.getElementById('perPageSelect');
                const perPageValue = perPageEl ? perPageEl.value : (document.getElementById('filterPerPageInput') ? document.getElementById('filterPerPageInput').value : 10);

                const filterPerPageInput = document.getElementById('filterPerPageInput');
                if (filterPerPageInput) filterPerPageInput.value = perPageValue;

                updateExportUrls(searchValue, prodiValue);

                const url = new URL(window.location.origin + window.location.pathname);
                url.searchParams.set('search', searchValue);
                url.searchParams.set('page', page);
                url.searchParams.set('per_page', perPageValue);
                if (prodiValue) {
                    url.searchParams.set('program_studi_id', prodiValue);
                }
                url.searchParams.set('ajax', '1');

                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        tableContainer.innerHTML = data;
                        spinner.classList.add('d-none');
                        searchIcon.classList.remove('d-none');

                        const browserUrl = new URL(url);
                        browserUrl.searchParams.delete('ajax');
                        window.history.pushState({}, '', browserUrl);
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        spinner.classList.add('d-none');
                        searchIcon.classList.remove('d-none');
                    });
            }

            window.performSearch = performSearch;

            // Intercept pagination clicks
            document.addEventListener('click', function (e) {
                const paginationLink = e.target.closest('#tableContainer .pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    const urlParams = new URLSearchParams(paginationLink.getAttribute('href').split('?')[1]);
                    const page = urlParams.get('page') || 1;
                    performSearch(page);
                }
            });

            /* ----------------------------------------------------
             * SKRIP MODAL WIZARD IMPOR DATA DOSEN VIA AJAX
             * ---------------------------------------------------- */
            const formUploadPreview = document.getElementById('formUploadPreview');
            const stepUpload         = document.getElementById('stepUpload');
            const stepPreview        = document.getElementById('stepPreview');
            const uploadAlert        = document.getElementById('uploadAlert');
            const btnProsesPreview   = document.getElementById('btnProsesPreview');
            const spinnerUpload      = document.getElementById('spinnerUpload');
            const iconUpload         = document.getElementById('iconUpload');
            const tbodyPreview       = document.getElementById('tbodyPreview');
            const toggleOnlyErrors   = document.getElementById('toggleOnlyErrors');
            const btnGantiFile       = document.getElementById('btnGantiFile');
            const btnKonfirmasiSimpan= document.getElementById('btnKonfirmasiSimpan');
            const spinnerSimpan      = document.getElementById('spinnerSimpan');
            const iconSimpan         = document.getElementById('iconSimpan');
            const textBtnSimpan      = document.getElementById('textBtnSimpan');

            let parsedRows = [];

            // A. Proses Upload Berkas & Dapatkan Preview
            formUploadPreview.addEventListener('submit', function (e) {
                e.preventDefault();
                uploadAlert.classList.add('d-none');
                spinnerUpload.classList.remove('d-none');
                iconUpload.classList.add('d-none');
                btnProsesPreview.disabled = true;

                const formData = new FormData(formUploadPreview);

                fetch("{{ route('admin.dosen.import.preview') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(res => {
                    spinnerUpload.classList.add('d-none');
                    iconUpload.classList.remove('d-none');
                    btnProsesPreview.disabled = false;

                    if (res.status === 200 && res.body.success) {
                        parsedRows = res.body.rows;
                        renderPreview(res.body.summary, parsedRows);
                        stepUpload.classList.add('d-none');
                        stepPreview.classList.remove('d-none');
                    } else {
                        uploadAlert.textContent = res.body.message || 'Gagal memproses berkas.';
                        uploadAlert.classList.remove('d-none');
                    }
                })
                .catch(err => {
                    spinnerUpload.classList.add('d-none');
                    iconUpload.classList.remove('d-none');
                    btnProsesPreview.disabled = false;
                    uploadAlert.textContent = 'Terjadi kesalahan sistem saat menghubungi server.';
                    uploadAlert.classList.remove('d-none');
                });
            });

            // B. Render Hasil Preview ke Tabel
            function renderPreview(summary, rows) {
                document.getElementById('statTotalRows').textContent   = summary.total_rows;
                document.getElementById('statValidRows').textContent   = summary.total_valid;
                document.getElementById('statInvalidRows').textContent = summary.total_invalid;
                document.getElementById('badgeErrorCount').textContent = summary.total_invalid;

                tbodyPreview.innerHTML = '';

                if (rows.length === 0) {
                    tbodyPreview.innerHTML = `<tr><td colspan="9" class="text-center py-4 text-muted">Berkas tidak memiliki data baris untuk diimpor.</td></tr>`;
                    btnKonfirmasiSimpan.disabled = true;
                    return;
                }

                btnKonfirmasiSimpan.disabled = summary.total_valid === 0;
                textBtnSimpan.textContent = `Simpan (${summary.total_valid} Data Valid)`;

                rows.forEach((row) => {
                    const tr = document.createElement('tr');
                    tr.className = row.is_valid ? 'row-valid' : 'row-invalid table-danger';

                    const statusBadge = row.is_valid 
                        ? `<span class="badge bg-success-subtle text-success border border-success-subtle"><i class="bi bi-check-circle me-1"></i> Valid</span>`
                        : `<span class="badge bg-danger-subtle text-danger border border-danger-subtle"><i class="bi bi-exclamation-triangle me-1"></i> Error</span>`;

                    const errorDetails = row.errors.length > 0 
                        ? row.errors.map(err => `<div class="text-danger small fw-semibold">&bull; ${err}</div>`).join('')
                        : `<span class="text-muted small"><i class="bi bi-check2 text-success me-1"></i>Siap diimpor</span>`;

                    const nipVal = row.nip || row.nim || '-';

                    tr.innerHTML = `
                        <td class="text-muted small">${row.row_number}</td>
                        <td>${statusBadge}</td>
                        <td class="fw-semibold ${row.errors.some(e => e.includes('NIP') || e.includes('NIDN')) ? 'text-danger text-decoration-underline' : ''}">${nipVal}</td>
                        <td>${row.nama || '-'}</td>
                        <td class="${row.errors.some(e => e.includes('Email')) ? 'text-danger text-decoration-underline' : ''}">${row.email || '-'}</td>
                        <td class="text-muted small">${row.telepon || '-'}</td>
                        <td class="${row.errors.some(e => e.includes('Prodi') || e.includes('Program Studi')) ? 'text-danger fw-semibold' : ''}">${row.prodi_name || '-'}</td>
                        <td><span class="badge bg-secondary-subtle text-secondary">${row.status_label}</span></td>
                        <td>${errorDetails}</td>
                    `;

                    tbodyPreview.appendChild(tr);
                });
            }

            // C. Filter Tampilan: Hanya Tampilkan Baris Error
            toggleOnlyErrors.addEventListener('change', function () {
                const rows = tbodyPreview.querySelectorAll('tr');
                rows.forEach(tr => {
                    if (toggleOnlyErrors.checked) {
                        if (tr.classList.contains('row-valid')) {
                            tr.style.display = 'none';
                        }
                    } else {
                        tr.style.display = '';
                    }
                });
            });

            // D. Kembali ke Step 1 (Ganti Berkas)
            btnGantiFile.addEventListener('click', function () {
                stepPreview.classList.add('d-none');
                stepUpload.classList.remove('d-none');
                formUploadPreview.reset();
                toggleOnlyErrors.checked = false;
            });

            // E. Eksekusi Simpan Data yang Valid ke Database
            btnKonfirmasiSimpan.addEventListener('click', function () {
                const validRows = parsedRows.filter(r => r.is_valid);
                if (validRows.length === 0) return;

                if (!confirm(`Konfirmasi: Simpan ${validRows.length} data dosen yang valid ke database?`)) {
                    return;
                }

                spinnerSimpan.classList.remove('d-none');
                iconSimpan.classList.add('d-none');
                btnKonfirmasiSimpan.disabled = true;

                fetch("{{ route('admin.dosen.import.store') }}", {
                    method: 'POST',
                    body: JSON.stringify({ rows: validRows }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(result => {
                    spinnerSimpan.classList.add('d-none');
                    iconSimpan.classList.remove('d-none');
                    btnKonfirmasiSimpan.disabled = false;

                    if (result.success) {
                        showToast(result.message, 'success');
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        showToast(result.message || 'Gagal menyimpan data.', 'danger');
                    }
                })
                .catch(err => {
                    spinnerSimpan.classList.add('d-none');
                    iconSimpan.classList.remove('d-none');
                    btnKonfirmasiSimpan.disabled = false;
                    showToast('Terjadi kesalahan saat menyimpan data.', 'danger');
                });
            });
        });
    </script>
</x-admin-layout>