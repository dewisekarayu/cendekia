<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PenggunaBaru implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $role
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@cendekia.local', 'Admin Cendekia'),
            subject: "👤 Pengguna Baru Terdaftar: {$this->user->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pengguna-baru',
            with: [
                'user' => $this->user,
                'role' => $this->role,
                'registeredAt' => $this->user->created_at->format('d M Y H:i'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
