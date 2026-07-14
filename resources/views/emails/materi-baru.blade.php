@extends('emails.layout', ['role' => 'Mahasiswa'])

@section('content')
<div class="email-header">
    <h1>📚 Materi Baru Tersedia</h1>
    <p>{{ $kelas->mataKuliah->nama_mk ?? 'Materi Pembelajaran' }}</p>
</div>

<div class="email-body">
    <div class="greeting">
        Halo {{ $mahasiswa->name }},
    </div>

    <p>Dosen Anda telah mengunggah materi pembelajaran baru. Silakan buka dan pelajari materi berikut:</p>

    <div class="info-box">
        <p><strong>📖 Judul Materi:</strong><br>{{ $materi->judul }}</p>
        <p style="margin-top: 12px;"><strong>🏫 Kelas:</strong><br>{{ $kelas->mataKuliah->nama_mk }} ({{ $kelas->kode_kelas }})</p>
        <p style="margin-top: 12px;"><strong>👨‍🏫 Dosen:</strong><br>{{ $dosen->name }}</p>
    </div>

    @if($materi->deskripsi)
    <div class="content-section">
        <h2>Deskripsi</h2>
        <p>{{ $materi->deskripsi }}</p>
    </div>
    @endif

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ route('mahasiswa.materi.buka', ['kelas' => $kelas->id, 'materi' => $materi->id]) }}" class="cta-button">
            Buka Materi
        </a>
    </div>

    <div class="section-divider"></div>

    <p class="text-muted" style="font-size: 13px;">
        Perhatian: Email ini dikirim secara otomatis oleh sistem Cendekia. Jangan balas email ini.
    </p>
</div>
@endsection
