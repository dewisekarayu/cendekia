<?php

namespace App\Mail;

use App\Models\Tugas;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TugasBaru extends Mailable
{
    public function __construct(
        public Tugas $tugas,
        public User $mahasiswa,
        public User $dosen
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@cendekia.local', 'Sistem Cendekia'),
            subject: "📝 Tugas Baru: {$this->tugas->judul}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tugas-baru',
            with: [
                'mahasiswa' => $this->mahasiswa,
                'dosen' => $this->dosen,
                'tugas' => $this->tugas,
                'kelas' => $this->tugas->kelasPerkuliahan,
                'deadlineFormatted' => $this->tugas->deadline ? $this->tugas->deadline->format('d M Y H:i') : 'Tidak ada deadline',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
