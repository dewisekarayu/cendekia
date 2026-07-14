@extends('emails.layout', ['role' => 'Mahasiswa'])

@section('content')
<div class="email-header">
    <h1>📢 Pengumuman Penting</h1>
    <p>{{ $kelas->mataKuliah->nama_mk ?? 'Pengumuman Kelas' }}</p>
</div>

<div class="email-body">
    <div class="greeting">
        Halo {{ $recipient->name }},
    </div>

    <p>Ada pengumuman penting dari dosen Anda:</p>

    <div class="info-box">
        <p><strong>📣 Judul:</strong><br>{{ $pengumuman->judul }}</p>
        <p style="margin-top: 12px;"><strong>👨‍🏫 Dari:</strong><br>{{ $dosen->name }}</p>
        <p style="margin-top: 12px;"><strong>📅 Tanggal:</strong><br>{{ $createdAt }}</p>
    </div>

    <div class="content-section">
        <h2>Isi Pengumuman</h2>
        <div style="background-color: #FAFAFA; padding: 16px; border-radius: 6px; line-height: 1.8;">
            {!! nl2br(e($pengumuman->isi)) !!}
        </div>
    </div>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ route('mahasiswa.kelas-detail', $kelas->id) }}" class="cta-button">
            Lihat di Dashboard
        </a>
    </div>

    <div class="section-divider"></div>

    <p class="text-muted" style="font-size: 13px;">
        Pastikan membaca pengumuman ini sepenuhnya untuk tidak melewatkan informasi penting.
    </p>
</div>
@endsection
