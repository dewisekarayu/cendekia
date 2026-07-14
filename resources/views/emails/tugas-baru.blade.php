@extends('emails.layout', ['role' => 'Mahasiswa'])

@section('content')
<div class="email-header">
    <h1>📝 Tugas Baru Diberikan</h1>
    <p>{{ $kelas->mataKuliah->nama_mk ?? 'Tugas Pembelajaran' }}</p>
</div>

<div class="email-body">
    <div class="greeting">
        Halo {{ $mahasiswa->name }},
    </div>

    <p>Dosen Anda telah memberikan tugas baru yang perlu Anda kerjakan. Berikut detail tugasnya:</p>

    <div class="info-box">
        <p><strong>📋 Judul Tugas:</strong><br>{{ $tugas->judul_tugas }}</p>
        <p style="margin-top: 12px;"><strong>🏫 Kelas:</strong><br>{{ $kelas->mataKuliah->nama_mk }} ({{ $kelas->kode_kelas }})</p>
        <p style="margin-top: 12px;"><strong>👨‍🏫 Dosen:</strong><br>{{ $dosen->name }}</p>
        <p style="margin-top: 12px;"><strong>⏰ Deadline:</strong><br><span class="highlight">{{ $deadlineFormatted }}</span></p>
    </div>

    @if($tugas->deskripsi)
    <div class="content-section">
        <h2>Deskripsi Tugas</h2>
        <p>{!! nl2br(e($tugas->deskripsi)) !!}</p>
    </div>
    @endif

    <div class="content-section">
        <h2>⚠️ Penting!</h2>
        <ul style="margin-left: 20px;">
            <li>Pastikan mengumpulkan tugas sebelum deadline</li>
            <li>Format file yang diizinkan: PDF, DOC, DOCX, JPG, PNG</li>
            <li>Ukuran file maksimal: 10 MB</li>
        </ul>
    </div>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ route('mahasiswa.pengumpulan-tugas.show', $tugas->id) }}" class="cta-button">
            Kerjakan Tugas
        </a>
    </div>

    <div class="section-divider"></div>

    <p class="text-muted" style="font-size: 13px;">
        Sisa waktu untuk mengumpulkan: <strong>{{ \Carbon\Carbon::now()->diffInDays($tugas->deadline_tugas) }} hari</strong>
    </p>
</div>
@endsection
