@extends('emails.layout', ['role' => 'Admin'])

@section('content')
<div class="email-header">
    <h1>👤 Pengguna Baru Terdaftar</h1>
    <p>Admin Notification - Cendekia LMS</p>
</div>

<div class="email-body">
    <div class="greeting">
        Halo Admin,
    </div>

    <p>Pengguna baru telah mendaftar di sistem Cendekia. Berikut adalah detail pendaftaran:</p>

    <div class="info-box">
        <p><strong>👤 Nama:</strong><br>{{ $user->name }}</p>
        <p style="margin-top: 12px;"><strong>📧 Email:</strong><br>{{ $user->email }}</p>
        <p style="margin-top: 12px;"><strong>🎓 Role:</strong><br><span class="highlight">{{ ucfirst($role) }}</span></p>
        <p style="margin-top: 12px;"><strong>📅 Terdaftar pada:</strong><br>{{ $registeredAt }}</p>
    </div>

    @if($role === 'mahasiswa')
    <div class="content-section">
        <h2>📋 Informasi Mahasiswa</h2>
        <p><strong>NIM:</strong> {{ $user->nim ?? 'Belum di-set' }}</p>
        <p><strong>Program Studi:</strong> {{ $user->programStudi->nama_program ?? 'Belum di-set' }}</p>
    </div>
    @elseif($role === 'dosen')
    <div class="content-section">
        <h2>👨‍🏫 Informasi Dosen</h2>
        <p><strong>NIP:</strong> {{ $user->nip ?? 'Belum di-set' }}</p>
        <p><strong>Program Studi:</strong> {{ $user->programStudi->nama_program ?? 'Belum di-set' }}</p>
    </div>
    @endif

    <div class="content-section">
        <h2>⚙️ Tindakan yang Diperlukan</h2>
        <ul style="margin-left: 20px;">
            <li>Verifikasi data pengguna jika diperlukan</li>
            <li>Pastikan pengguna sudah mengkonfirmasi email mereka</li>
            <li>Assign ke kelas atau program studi sesuai kebutuhan</li>
        </ul>
    </div>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ route('admin.user.show', $user->id) }}" class="cta-button">
            Kelola Pengguna
        </a>
    </div>

    <div class="section-divider"></div>

    <div class="content-section">
        <h2>📊 Statistik Pengguna</h2>
        <p>Total pengguna terdaftar akan ditampilkan di admin dashboard Anda.</p>
    </div>
</div>
@endsection
