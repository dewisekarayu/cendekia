@extends('emails.layout', ['role' => 'Mahasiswa'])

@section('content')
<div class="email-header">
    <h1>✅ Nilai Tugas Anda</h1>
    <p>{{ $kelas->mataKuliah->nama_mk ?? 'Penilaian Tugas' }}</p>
</div>

<div class="email-body">
    <div class="greeting">
        Halo {{ $mahasiswa->name }},
    </div>

    <p>Nilai Anda untuk tugas telah dinilai. Berikut adalah hasil penilaiannya:</p>

    <div class="info-box">
        <p><strong>📝 Tugas:</strong><br>{{ $tugas->judul_tugas }}</p>
        <p style="margin-top: 12px;"><strong>⭐ Nilai Anda:</strong><br>
            <span style="font-size: 28px; font-weight: 800; color: #002B6B;">{{ $nilai ?? 'Belum Dinilai' }}</span>
        </p>
        <p style="margin-top: 12px;"><strong>📅 Dinilai pada:</strong><br>
            {{ $submission->updated_at->format('d M Y H:i') }}
        </p>
    </div>

    @if($feedback && $feedback != 'Tidak ada feedback')
    <div class="content-section">
        <h2>💬 Feedback dari Dosen</h2>
        <div style="background-color: #EBF8FF; padding: 16px; border-left: 4px solid #3B82F6; border-radius: 4px;">
            {!! nl2br(e($feedback)) !!}
        </div>
    </div>
    @endif

    <div class="content-section">
        <h2>📊 Statistik Kelas</h2>
        <p>Bandingan nilai Anda dengan nilai rata-rata kelas akan ditampilkan di dashboard Anda.</p>
    </div>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ route('mahasiswa.gradebook') }}" class="cta-button">
            Lihat Gradebook Lengkap
        </a>
    </div>

    <div class="section-divider"></div>

    <div class="content-section">
        <h2>📌 Tips Meningkatkan Nilai</h2>
        <ul style="margin-left: 20px;">
            <li>Baca feedback dosen dengan cermat</li>
            <li>Perhatikan area yang perlu diperbaiki</li>
            <li>Jangan ragu untuk bertanya ke dosen jika ada yang kurang jelas</li>
        </ul>
    </div>
</div>
@endsection
