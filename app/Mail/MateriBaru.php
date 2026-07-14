<?php

namespace App\Mail;

use App\Models\Materi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MateriBaru extends Mailable
{

    public function __construct(
        public Materi $materi,
        public User $mahasiswa,
        public User $dosen
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->dosen->email, $this->dosen->name ?? 'Dosen'),
            subject: "📚 Materi Baru: {$this->materi->judul}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.materi-baru',
            with: [
                'mahasiswa' => $this->mahasiswa,
                'dosen' => $this->dosen,
                'materi' => $this->materi,
                'kelas' => $this->materi->kelasPerkuliahan,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
