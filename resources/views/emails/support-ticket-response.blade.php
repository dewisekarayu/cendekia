@extends('emails.layout', ['role' => 'pengguna'])

@section('content')
<div class="email-header">
    <h1>Pusat Bantuan Cendekia</h1>
    <p>Tiket Bantuan Anda Telah Ditanggapi</p>
</div>

<div class="email-body">
    <p class="greeting">Halo {{ $ticket->name }},</p>
    
    <div class="content-section">
        <p>Tim admin kami telah memproses dan menanggapi tiket bantuan yang Anda kirimkan.</p>
        
        <div class="info-box">
            <p><strong>Subjek Tiket:</strong> {{ $ticket->subject }}</p>
            <p><strong>Kategori:</strong> {{ ucfirst($ticket->category) }}</p>
            <p><strong>Pesan Anda:</strong><br><em>"{{ $ticket->message }}"</em></p>
        </div>

        <h2>Tanggapan Admin:</h2>
        <div class="info-box" style="border-left-color: #10B981; background-color: #F0FDF4;">
            <p>{!! nl2br(e($reply->message)) !!}</p>
        </div>

        <p>Jika Anda masih memiliki pertanyaan atau kendala lain, silakan balas email ini atau buat tiket bantuan baru di portal Cendekia.</p>
    </div>

    <div class="text-center" style="margin-top: 30px;">
        <a href="{{ route('help-center.index') }}" class="cta-button">Kunjungi Pusat Bantuan</a>
    </div>
</div>
@endsection
