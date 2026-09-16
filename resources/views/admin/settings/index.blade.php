@extends('layouts.admin')

@section('title', 'Pengaturan Sistem Global')

@section('content')
<style>
    .settings-container {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .settings-header {
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 20px;
        margin-bottom: 30px;
    }
    .settings-header h3 {
        color: #0f172a;
        font-weight: 700;
        margin-bottom: 5px;
    }
    .settings-header p {
        color: #64748b;
        font-size: 14px;
        margin: 0;
    }
    .form-group {
        margin-bottom: 24px;
    }
    .form-label {
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
        display: block;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        padding: 10px 15px;
        font-size: 14px;
    }
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .btn-save {
        background: #0f172a;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-save:hover {
        background: #1e293b;
        transform: translateY(-1px);
    }
    
    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #cbd5e1;
        transition: .4s;
        border-radius: 34px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #10b981;
    }
    input:checked + .slider:before {
        transform: translateX(24px);
    }
</style>

<div class="settings-container">
    <div class="settings-header">
        <h3>⚙️ Pengaturan Global Sistem</h3>
        <p>Kelola konfigurasi inti aplikasi Cendekia LMS. Perubahan akan berdampak pada seluruh pengguna.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 bg-success text-white shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label">Nama Aplikasi</label>
            <input type="text" class="form-control" name="app_name" value="{{ $settings['app_name']->value ?? 'Cendekia LMS' }}" placeholder="Contoh: Cendekia LMS">
            <small class="text-muted mt-1 d-block">Nama yang akan tampil di judul browser dan header.</small>
        </div>

        <div class="form-group">
            <label class="form-label">Email Kontak Dukungan (Bantuan)</label>
            <input type="email" class="form-control" name="support_email" value="{{ $settings['support_email']->value ?? 'support@cendekia.ac.id' }}">
        </div>

        <div class="form-group">
            <label class="form-label">Tahun Akademik Aktif</label>
            <input type="text" class="form-control" name="active_academic_year" value="{{ $settings['active_academic_year']->value ?? '2024/2025' }}" placeholder="Contoh: 2024/2025">
        </div>

        <div class="form-group">
            <label class="form-label">Semester Aktif</label>
            <select class="form-select" name="active_semester">
                <option value="Ganjil" {{ ($settings['active_semester']->value ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ ($settings['active_semester']->value ?? '') == 'Genap' ? 'selected' : '' }}>Genap</option>
                <option value="Pendek" {{ ($settings['active_semester']->value ?? '') == 'Pendek' ? 'selected' : '' }}>Pendek</option>
            </select>
        </div>

        <div class="form-group border-top pt-4 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <label class="form-label mb-0 text-danger">Mode Perawatan (Maintenance Mode)</label>
                    <small class="text-muted d-block mt-1">Jika diaktifkan, dosen dan mahasiswa tidak bisa login sementara waktu.</small>
                </div>
                
                <label class="switch">
                    <!-- Hidden field as fallback if unchecked -->
                    <input type="hidden" name="maintenance_mode" value="0">
                    <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['maintenance_mode']->value ?? '0') == '1' ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="form-group text-end mt-5">
            <button type="submit" class="btn btn-save">
                <i class="bi bi-save me-2"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection
