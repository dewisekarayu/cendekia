<?php

namespace App\Mail;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengumumanBaru extends Mailable
{

    public function __construct(
        public Pengumuman $pengumuman,
        public User $recipient,
        public User $dosen
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->dosen->email, $this->dosen->name ?? 'Dosen'),
            subject: "📢 Pengumuman Baru: {$this->pengumuman->judul}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengumuman-baru',
            with: [
                'recipient' => $this->recipient,
                'dosen' => $this->dosen,
                'pengumuman' => $this->pengumuman,
                'kelas' => $this->pengumuman->kelasPerkuliahan,
                'createdAt' => $this->pengumuman->created_at->format('d M Y H:i'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
