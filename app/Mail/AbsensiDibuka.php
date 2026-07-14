<?php

namespace App\Mail;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class AbsensiDibuka extends Mailable
{
    public function __construct(
        public Absensi $absensi,
        public User $mahasiswa,
        public User $dosen
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@cendekia.local', 'Sistem Cendekia'),
            subject: "⏰ Sesi Presensi Dibuka - Pertemuan {$this->absensi->pertemuan_ke}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.absensi-dibuka',
            with: [
                'mahasiswa' => $this->mahasiswa,
                'dosen' => $this->dosen,
                'absensi' => $this->absensi,
                'kelas' => $this->absensi->kelasPerkuliahan,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
