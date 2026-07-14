@extends('emails.layout', ['role' => 'Dosen'])

@section('content')
<div class="email-header">
    <h1>📬 Tugas Baru Dikumpulkan</h1>
    <p>Ada submission baru yang perlu diperiksa</p>
</div>

<div class="email-body">
    <div class="greeting">
        Halo {{ $dosen->name }},
    </div>

    <p>Salah satu mahasiswa Anda telah mengumpulkan tugas. Berikut detailnya:</p>

    <div class="info-box">
        <p><strong>👤 Mahasiswa:</strong><br>{{ $mahasiswa->name }} ({{ $mahasiswa->nim ?? 'N/A' }})</p>
        <p style="margin-top: 12px;"><strong>📝 Tugas:</strong><br>{{ $tugas->judul_tugas }}</p>
        <p style="margin-top: 12px;"><strong>🏫 Kelas:</strong><br>{{ $kelas->mataKuliah->nama_mk }} ({{ $kelas->kode_kelas }})</p>
        <p style="margin-top: 12px;"><strong>📅 Dikumpulkan pada:</strong><br>{{ $submittedAt }}</p>
        @if($submission->updated_at->isAfter($tugas->deadline_tugas))
            <p style="margin-top: 12px;"><strong style="color: #DC2626;">⏰ Status: TERLAMBAT</strong></p>
        @else
            <p style="margin-top: 12px;"><strong style="color: #059669;">✓ Status: TEPAT WAKTU</strong></p>
        @endif
    </div>

    <div class="content-section">
        <h2>📊 Jumlah Pengumpulan</h2>
        <p>{{ $kelas->kelasMahasiswa()->count() }} mahasiswa dalam kelas ini.</p>
    </div>

    <div style="text-align: center; margin-top: 32px;">
        <a href="{{ route('dosen.tugas.submissions', ['kelas' => $kelas->id, 'tugas' => $tugas->id]) }}" class="cta-button">
            Lihat Semua Pengumpulan
        </a>
    </div>

    <div class="section-divider"></div>

    <p class="text-muted" style="font-size: 13px;">
        Harap segera memberikan penilaian dan feedback untuk mahasiswa. Email ini dikirim secara otomatis oleh sistem Cendekia.
    </p>
</div>
@endsection
