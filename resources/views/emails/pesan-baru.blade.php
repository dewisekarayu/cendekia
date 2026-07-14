@extends('emails.layout', ['role' => $recipient->isMahasiswa() ? 'Mahasiswa' : 'Dosen'])

@section('content')
<div class="email-header">
    <h1>💬 Pesan Baru di Forum</h1>
    <p>{{ $kelas->mataKuliah->nama_mk ?? 'Forum Diskusi' }}</p>
</div>

<div class="email-body">
    <div class="greeting">
        Halo {{ $recipient->name }},
    </div>

    <p><strong>{{ $sender->name }}</strong> telah memposting pesan baru di forum diskusi kelas Anda:</p>

    <div class="info-box">
        <p><strong>💬 Judul Topik:</strong><br>{{ $forum->judul }}</p>
        <p style="margin-top: 12px;"><strong>👤 Dari:</strong><br>{{ $sender->name }}</p>
        <p style="margin-top: 12px;"><strong>🏫 Kelas:</strong><br>{{ $kelas->mataKuliah->nama_mk }}</p>
    </div>

    <div class="content-section">
        <h2>Preview Pesan</h2>
        <div style="background-color: #F9FAFB; padding: 16px; border-radius: 6px; border-left: 4px solid #E5E7EB;">
            <p>{{ $preview }}</p>
        </div>
    </div>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ route('mahasiswa.kelas-forum', $kelas->id) }}" class="cta-button">
            Lihat Diskusi Lengkap
        </a>
    </div>

    <div class="section-divider"></div>

    <p class="text-muted" style="font-size: 13px;">
        Bergabunglah dalam diskusi untuk berbagi pemikiran dan menambah pemahaman bersama teman-teman Anda.
    </p>
</div>
@endsection
