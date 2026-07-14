<?php

namespace App\Mail;

use App\Models\ForumDiskusi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PesanBaru implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public ForumDiskusi $forum,
        public User $recipient,
        public User $sender
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address($this->sender->email, $this->sender->name ?? 'User'),
            subject: "💬 Pesan Baru dari {$this->sender->name}: {$this->forum->judul}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pesan-baru',
            with: [
                'recipient' => $this->recipient,
                'sender' => $this->sender,
                'forum' => $this->forum,
                'kelas' => $this->forum->kelasPerkuliahan,
                'preview' => substr(strip_tags($this->forum->isi), 0, 150) . '...',
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
