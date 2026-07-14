<?php

namespace App\Mail;

use App\Models\PengumpulanTugas;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NilaiBaru extends Mailable
{

    public function __construct(
        public PengumpulanTugas $submission,
        public User $mahasiswa,
        public User $dosen,
        public $gradedBy = 'Dosen'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->dosen->email, $this->dosen->name ?? 'Dosen'),
            subject: "✅ Nilai Tugas Anda: {$this->submission->tugas->judul_tugas}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.nilai-baru',
            with: [
                'mahasiswa' => $this->mahasiswa,
                'dosen' => $this->dosen,
                'submission' => $this->submission,
                'tugas' => $this->submission->tugas,
                'kelas' => $this->submission->tugas->kelasPerkuliahan,
                'nilai' => $this->submission->nilai,
                'feedback' => $this->submission->feedback_nilai ?? 'Tidak ada feedback',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
