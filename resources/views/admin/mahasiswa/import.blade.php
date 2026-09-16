@extends('layouts.admin')

@section('title', 'Impor Masal Mahasiswa')

@section('content')
<style>
    .import-wrapper {
        font-family: 'Inter', sans-serif;
        color: #334155;
        max-width: 1000px;
        margin: 0 auto;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .page-header h4 {
        color: #64748b;
        font-size: 1.5rem;
        font-weight: 300;
        margin: 0;
        letter-spacing: -0.02em;
    }
    .page-header h4 span {
        color: #4c1d95; /* Deep Purple */
        font-weight: 800;
    }
    .step-card {
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .step-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(76,29,149,0.08);
    }
    .step-header {
        display: flex;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 16px;
    }
    .step-number {
        background: linear-gradient(135deg, #7c3aed, #4c1d95);
        color: white;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(109, 40, 217, 0.4);
    }
    .step-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 6px;
        letter-spacing: -0.01em;
    }
    .step-desc {
        color: #64748b;
        font-size: 0.95rem;
        margin-bottom: 20px;
        line-height: 1.6;
    }
    
    /* Buttons */
    .btn-purple-outline {
        background: transparent;
        color: #5b21b6;
        border: 2px solid #5b21b6;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    .btn-purple-outline:hover {
        background: #5b21b6;
        color: white;
        box-shadow: 0 4px 15px rgba(91, 33, 182, 0.3);
    }
    .btn-purple-solid {
        background: linear-gradient(135deg, #6d28d9, #4c1d95);
        color: white;
        border: none;
        padding: 14px 36px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 6px 20px rgba(76, 29, 149, 0.35);
    }
    .btn-purple-solid:hover {
        background: linear-gradient(135deg, #5b21b6, #3b0764);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(76, 29, 149, 0.45);
        color: white;
    }
    
    /* Alerts */
    .alert-premium-yellow {
        background: #fffbeb;
        border: 1px solid #fef08a;
        color: #854d0e;
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 0.9rem;
        display: flex;
        gap: 16px;
        align-items: flex-start;
        box-shadow: 0 2px 10px rgba(253,230,138,0.3);
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
        border-top: 4px solid #6d28d9;
        border-radius: 12px;
        padding: 20px;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }
    .sop-box-title {
        color: #334155;
        font-weight: 800;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .sop-box-title i {
        color: #7c3aed;
        background: #f3e8ff;
        padding: 6px;
        border-radius: 6px;
    }
    .sop-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
        font-size: 0.9rem;
    }
    .sop-item:last-child {
        border-bottom: none;
    }
    .sop-item-label {
        font-weight: 600;
        color: #475569;
    }
    .sop-item-val {
        color: #94a3b8;
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
        box-shadow: 0 2px 4px rgba(239,68,68,0.3);
    }
    
    /* Prodi List Styling */
    .prodi-list-container {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-top: 12px;
        overflow: hidden;
    }
    .prodi-list-box {
        max-height: 130px;
        overflow-y: auto;
        padding: 6px 0;
    }
    .prodi-list-box::-webkit-scrollbar {
        width: 6px;
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
        background: #f3e8ff;
        color: #6d28d9;
        font-weight: 800;
        padding: 2px 10px;
        border-radius: 6px;
        min-width: 32px;
        text-align: center;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.8rem;
    }

    /* Pro Tip Alert */
    .alert-protip {
        background: linear-gradient(135deg, #4c1d95, #312e81);
        color: white;
        padding: 24px;
        border-radius: 12px;
        margin-top: 24px;
        display: flex;
        gap: 20px;
        box-shadow: 0 10px 25px rgba(49, 46, 129, 0.4);
    }
    .alert-protip i {
        color: #fde047;
        font-size: 2rem;
        text-shadow: 0 0 15px rgba(253,224,71,0.5);
    }
    .alert-protip-content h6 {
        color: #f8fafc;
        font-weight: 800;
        margin-bottom: 10px;
        font-size: 1rem;
        letter-spacing: 0.5px;
    }
    .alert-protip-content ol {
        margin: 0;
        padding-left: 20px;
        color: #cbd5e1;
        font-size: 0.9rem;
        line-height: 1.7;
    }
    .alert-protip-content li::marker {
        color: #a5b4fc;
        font-weight: bold;
    }

    /* Upload Area */
    .upload-area {
        border: 2px dashed #a5b4fc;
        border-radius: 16px;
        padding: 50px 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
        margin-bottom: 24px;
    }
    .upload-area:hover {
        background: #f3e8ff;
        border-color: #7c3aed;
        transform: scale(1.01);
    }
    .upload-area input[type="file"] {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
    }
    .upload-area i {
        font-size: 3.5rem;
        color: #8b5cf6;
        margin-bottom: 16px;
        display: block;
        transition: transform 0.3s ease;
    }
    .upload-area:hover i {
        transform: translateY(-5px);
        color: #6d28d9;
    }
    .upload-area h5 {
        color: #1e293b;
        font-weight: 800;
        margin-bottom: 8px;
        font-size: 1.15rem;
    }
    .upload-area p {
        color: #64748b;
        font-size: 0.95rem;
        margin: 0;
    }

    @media (max-width: 768px) {
        .sop-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; align-items: flex-start; gap: 8px; }
        .alert-protip { flex-direction: column; gap: 12px; }
    }
</style>

<div class="container-fluid py-4">
    <div class="import-wrapper">
        <div class="page-header">
            <h4>Mahasiswa / <span>Impor Masal Data</span></h4>
            <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-light border fw-bold text-secondary shadow-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Step 1 -->
        <div class="step-card">
            <div class="step-header">
                <div class="step-number">1</div>
                <div>
                    <div class="step-title">Unduh Template CSV Mahasiswa</div>
                    <div class="step-desc">Sistem akan membaca kolom sesuai format standar. Anda tinggal mengisi data mahasiswa di file tersebut.</div>
                    <a href="data:text/csv;charset=utf-8,Nama Lengkap,NIM,Email,ID Program Studi%0ABudi Santoso,20240001,budi@student.cendekia.ac.id,1" download="template_mahasiswa.csv" class="btn-purple-outline">
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
                    
                    <div class="alert-premium-yellow mt-4">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <div>
                            <strong class="text-yellow-900">PENTING:</strong> Kolom bertanda <span class="badge-wajib ms-0 me-1">WAJIB*</span> harus diisi. Untuk kolom bernotasi <strong>(ID)</strong>, gunakan <strong>ID Angka</strong> sesuai data master di sistem.
                        </div>
                    </div>

                    <div class="sop-grid">
                        <div class="sop-box">
                            <div class="sop-box-title">
                                <i class="bi bi-person-vcard-fill"></i> GRUP 1: Identitas Utama
                            </div>
                            <div class="sop-item">
                                <div class="sop-item-label">Nama Lengkap <span class="badge-wajib">*</span></div>
                                <div class="sop-item-val">Teks Bebas</div>
                            </div>
                            <div class="sop-item">
                                <div class="sop-item-label">NIM (Nomor Induk) <span class="badge-wajib">*</span></div>
                                <div class="sop-item-val">Angka (Unik)</div>
                            </div>
                            <div class="pt-3 text-slate-500" style="font-size: 0.8rem; line-height: 1.5;">
                                <i class="bi bi-shield-check text-emerald-500 me-1"></i> NIM juga akan otomatis dijadikan sebagai <strong>Password</strong> login awal akun.
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
                                <div class="mb-2 fw-bold text-slate-700" style="font-size: 0.85rem;"><i class="bi bi-database-fill text-indigo-500 me-1"></i> Referensi ID Program Studi:</div>
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
                                <li>Buka di Excel, isi data mahasiswa sesuai panduan kolom di atas.</li>
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
                    
                    <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="upload-area">
                            <input type="file" name="file_csv" id="file_csv" accept=".csv, .txt" required onchange="document.getElementById('fileNameLabel').innerHTML = '<div class=\'p-3 bg-emerald-50 text-emerald-700 rounded-lg d-inline-block fw-bold border border-emerald-200 mt-2\'><i class=\'bi bi-file-earmark-check-fill me-2\'></i>' + this.files[0].name + ' siap diimpor!</div>';">
                            <i class="bi bi-cloud-arrow-up-fill"></i>
                            <h5>Tarik & Lepas File CSV ke Sini</h5>
                            <p>atau <span class="text-indigo-600 fw-bold text-decoration-underline">Klik untuk Mencari File</span></p>
                            <p class="mt-2 text-slate-400 fw-bold" style="font-size: 0.8rem; letter-spacing: 1px;">FORMAT DUKUNGAN: .CSV</p>
                            <div id="fileNameLabel" class="mt-2"></div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn-purple-solid">
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
