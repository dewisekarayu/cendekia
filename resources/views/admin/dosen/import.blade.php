@extends('layouts.admin')

@section('title', 'Impor Masal Dosen')

@section('content')
<style>
    .import-wrapper {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #334155;
        max-width: 1040px;
        margin: 0 auto;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e2e8f0;
    }
    .page-header h4 {
        color: #64748b;
        font-size: 1.35rem;
        font-weight: 400;
        margin: 0;
        letter-spacing: -0.02em;
    }
    .page-header h4 span {
        color: #002B6B; /* Cendekia Deep Blue */
        font-weight: 800;
    }
    .step-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .step-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 43, 107, 0.07);
        border-color: #cbd5e1;
    }
    .step-header {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }
    .step-number {
        background: linear-gradient(135deg, #2563eb, #002B6B);
        color: white;
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    }
    .step-title {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
        letter-spacing: -0.01em;
    }
    .step-desc {
        color: #64748b;
        font-size: 0.925rem;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    /* Buttons */
    .btn-blue-outline {
        background: #eff6ff;
        color: #002B6B;
        border: 1.5px solid #bfdbfe;
        padding: 10px 22px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.9rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s ease;
    }
    .btn-blue-outline:hover {
        background: #002B6B;
        color: white;
        border-color: #002B6B;
        box-shadow: 0 4px 14px rgba(0, 43, 107, 0.25);
    }
    .btn-blue-solid {
        background: linear-gradient(135deg, #2563eb, #002B6B);
        color: white;
        border: none;
        padding: 14px 36px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 6px 20px rgba(0, 43, 107, 0.3);
        cursor: pointer;
    }
    .btn-blue-solid:hover {
        background: linear-gradient(135deg, #1d4ed8, #001d4a);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 43, 107, 0.4);
        color: white;
    }
    
    /* Alerts */
    .alert-premium-yellow {
        background: #fffbeb;
        border: 1px solid #fde68a;
        color: #92400e;
        padding: 14px 20px;
        border-radius: 12px;
        font-size: 0.875rem;
        display: flex;
        gap: 14px;
        align-items: flex-start;
        box-shadow: 0 2px 10px rgba(251, 191, 36, 0.1);
    }
    .alert-premium-yellow i {
        color: #d97706;
        font-size: 1.25rem;
    }
    
    /* SOP Grid */
    .sop-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-top: 24px;
    }
    .sop-box {
        border: 1px solid #e2e8f0;
        border-top: 4px solid #2563eb;
        border-radius: 14px;
        padding: 22px;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.015);
    }
    .sop-box-title {
        color: #1e293b;
        font-weight: 800;
        font-size: 0.975rem;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .sop-box-title i {
        color: #2563eb;
        background: #eff6ff;
        padding: 7px;
        border-radius: 8px;
        font-size: 1.1rem;
    }
    .sop-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 0.875rem;
    }
    .sop-item:last-child {
        border-bottom: none;
    }
    .sop-item-label {
        font-weight: 600;
        color: #475569;
    }
    .sop-item-val {
        color: #64748b;
        text-align: right;
        font-weight: 500;
    }
    .badge-wajib {
        background: #ef4444;
        color: white;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-left: 6px;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.25);
    }
    
    /* Prodi List Styling */
    .prodi-list-container {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-top: 10px;
        overflow: hidden;
    }
    .prodi-list-box {
        max-height: 130px;
        overflow-y: auto;
        padding: 4px 0;
    }
    .prodi-list-box::-webkit-scrollbar {
        width: 5px;
    }
    .prodi-list-box::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    .prodi-list-box::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .prodi-item {
        padding: 8px 16px;
        font-size: 0.85rem;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: background 0.2s;
    }
    .prodi-item:hover {
        background-color: #f1f5f9;
    }
    .prodi-id-badge {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 800;
        padding: 2px 10px;
        border-radius: 6px;
        min-width: 32px;
        text-align: center;
        font-family: monospace;
        font-size: 0.8rem;
        border: 1px solid #dbeafe;
    }

    /* Pro Tip Alert */
    .alert-protip {
        background: linear-gradient(135deg, #002B6B 0%, #1e3a8a 100%);
        color: white;
        padding: 22px 24px;
        border-radius: 14px;
        margin-top: 24px;
        display: flex;
        gap: 18px;
        box-shadow: 0 8px 24px rgba(0, 43, 107, 0.25);
    }
    .alert-protip i {
        color: #fbbf24;
        font-size: 1.85rem;
        text-shadow: 0 0 12px rgba(251, 191, 36, 0.4);
        flex-shrink: 0;
        margin-top: 2px;
    }
    .alert-protip-content h6 {
        color: #ffffff;
        font-weight: 800;
        margin-bottom: 8px;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
    }
    .alert-protip-content ol {
        margin: 0;
        padding-left: 18px;
        color: #dbeafe;
        font-size: 0.875rem;
        line-height: 1.65;
    }
    .alert-protip-content li::marker {
        color: #93c5fd;
        font-weight: bold;
    }

    /* Upload Area */
    .upload-area {
        border: 2px dashed #93c5fd;
        border-radius: 16px;
        padding: 44px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
        margin-bottom: 24px;
    }
    .upload-area:hover {
        background: #eff6ff;
        border-color: #2563eb;
        transform: scale(1.005);
    }
    .upload-area input[type="file"] {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
    }
    .upload-area i.upload-icon {
        font-size: 3.25rem;
        color: #2563eb;
        margin-bottom: 14px;
        display: block;
        transition: transform 0.3s ease, color 0.3s ease;
    }
    .upload-area:hover i.upload-icon {
        transform: translateY(-4px);
        color: #002B6B;
    }
    .upload-area h5 {
        color: #0f172a;
        font-weight: 800;
        margin-bottom: 6px;
        font-size: 1.1rem;
    }
    .upload-area p {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .sop-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
        .alert-protip { flex-direction: column; gap: 10px; }
    }
</style>

<div class="container-fluid py-4">
    <div class="import-wrapper">
        <div class="page-header">
            <div class="d-flex align-items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-[#002B6B] dark:text-blue-400 d-flex align-items-center justify-center border border-blue-100 dark:border-blue-900/50">
                    <i class="bi bi-file-earmark-arrow-up text-lg"></i>
                </div>
                <div>
                    <h4>Dosen / <span>Impor Masal Data</span></h4>
                    <p class="text-xs text-slate-500 m-0">Unggah data dosen secara kolektif menggunakan berkas CSV</p>
                </div>
            </div>
            <a href="{{ route('admin.dosen.index') }}" class="btn btn-light border fw-bold text-slate-600 shadow-sm rounded-pill px-3 py-2 text-xs d-inline-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i> Kembali ke Daftar Dosen
            </a>
        </div>

        <!-- Step 1 -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number">1</div>
                <div>
                    <div class="step-title">Unduh Template CSV Dosen</div>
                    <div class="step-desc">Sistem akan membaca kolom sesuai format standar. Anda tinggal mengisi data dosen di file tersebut.</div>
                    <a href="data:text/csv;charset=utf-8,Nama Lengkap,NIP,Email,ID Program Studi%0AProf. Budi,19790001,budi@dosen.cendekia.ac.id,1" download="template_dosen.csv" class="btn-blue-outline">
                        <i class="bi bi-download"></i> Unduh Template CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number">2</div>
                <div class="w-100">
                    <div class="step-title">Petunjuk & Struktur Kolom (SOP)</div>
                    
                    <div class="alert-premium-yellow mt-3">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            <strong class="text-amber-950">PENTING:</strong> Kolom bertanda <span class="badge-wajib ms-0 me-1">WAJIB*</span> harus diisi. Untuk kolom bernotasi <strong>(ID)</strong>, gunakan <strong>ID Angka</strong> sesuai data master di sistem.
                        </div>
                    </div>

                    <div class="sop-grid">
                        <div class="sop-box">
                            <div class="sop-box-title">
                                <i class="bi bi-person-vcard-fill"></i> GRUP 1: Identitas Utama
                            </div>
                            <div class="sop-item">
                                <div class="sop-item-label">Nama Lengkap & Gelar <span class="badge-wajib">*</span></div>
                                <div class="sop-item-val">Teks Bebas</div>
                            </div>
                            <div class="sop-item">
                                <div class="sop-item-label">NIP (Nomor Induk) <span class="badge-wajib">*</span></div>
                                <div class="sop-item-val">Angka (Unik)</div>
                            </div>
                            <div class="pt-3 text-slate-500" style="font-size: 0.825rem; line-height: 1.5;">
                                <i class="bi bi-shield-check text-emerald-600 me-1"></i> NIP juga akan otomatis dijadikan sebagai <strong>Password</strong> login awal akun.
                            </div>
                        </div>

                        <div class="sop-box">
                            <div class="sop-box-title">
                                <i class="bi bi-mortarboard-fill"></i> GRUP 2: Data Akademik
                            </div>
                            <div class="sop-item">
                                <div class="sop-item-label">Email <span class="badge-wajib">*</span></div>
                                <div class="sop-item-val">Format Email Standar</div>
                            </div>
                            <div class="sop-item">
                                <div class="sop-item-label">ID Program Studi <span class="badge-wajib">*</span></div>
                                <div class="sop-item-val">Isi dengan Angka ID</div>
                            </div>
                            <div class="pt-3">
                                <div class="mb-2 fw-bold text-slate-700" style="font-size: 0.85rem;"><i class="bi bi-database-fill text-blue-600 me-1"></i> Referensi ID Program Studi:</div>
                                <div class="prodi-list-container">
                                    <div class="prodi-list-box">
                                        @forelse($prodis as $prodi)
                                            <div class="prodi-item">
                                                <span class="prodi-id-badge">{{ $prodi->id }}</span>
                                                <span>{{ $prodi->nama_prodi }}</span>
                                            </div>
                                        @empty
                                            <div class="prodi-item text-slate-400 fst-italic">Belum ada data program studi.</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert-protip">
                        <i class="bi bi-lightbulb-fill"></i>
                        <div class="alert-protip-content">
                            <h6>PRO TIP: LANGKAH PENGGUNAAN SINGKAT</h6>
                            <ol>
                                <li>Unduh template CSV terlebih dahulu di Langkah 1.</li>
                                <li>Buka di Excel, isi data dosen sesuai panduan kolom di atas.</li>
                                <li><strong>Simpan (Save As)</strong> file tetap dalam format <strong>.csv</strong>, lalu unggah di Langkah 3.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number">3</div>
                <div class="w-100">
                    <div class="step-title">Unggah File CSV & Eksekusi</div>
                    <div class="step-desc">Tarik & lepas (Drag & Drop) file CSV yang sudah diisi ke area di bawah ini, atau klik untuk mencari file.</div>
                    
                    <form action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-area">
                            <input type="file" name="file_csv" id="file_csv" accept=".csv, .txt" required onchange="document.getElementById('fileNameLabel').innerHTML = '<div class=\'p-3 bg-emerald-50 text-emerald-700 rounded-xl d-inline-block fw-bold border border-emerald-200 mt-2 text-xs\'><i class=\'bi bi-file-earmark-check-fill me-2 fs-6\'></i>' + this.files[0].name + ' siap diimpor!</div>';">
                            <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                            <h5>Tarik & Lepas File CSV ke Sini</h5>
                            <p>atau <span class="text-blue-600 fw-bold text-decoration-underline">Klik untuk Mencari File</span></p>
                            <p class="mt-2 text-slate-400 fw-bold" style="font-size: 0.775rem; letter-spacing: 1px;">FORMAT DUKUNGAN: .CSV</p>
                            <div id="fileNameLabel" class="mt-2"></div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn-blue-solid">
                                <i class="bi bi-rocket-takeoff-fill fs-5"></i> Mulai Proses Impor
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
