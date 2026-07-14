<?php

namespace App\Mail;

use App\Models\PengumpulanTugas as PengumpulanTugasModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengumpulanTugas extends Mailable
{
    public function __construct(
        public PengumpulanTugasModel $submission,
        public User $dosen
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@cendekia.local', 'Sistem Cendekia'),
            subject: "📬 Ada Tugas Baru yang Dikumpulkan: {$this->submission->tugas->judul}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengumpulan-tugas',
            with: [
                'dosen' => $this->dosen,
                'mahasiswa' => $this->submission->mahasiswa,
                'submission' => $this->submission,
                'tugas' => $this->submission->tugas,
                'kelas' => $this->submission->tugas->kelasPerkuliahan,
                'submittedAt' => $this->submission->created_at->format('d M Y H:i'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
