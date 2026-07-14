<?php

namespace App\Jobs;

use App\Mail\PesanBaru;
use App\Models\ForumDiskusi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPesanBaru implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public ForumDiskusi $forum,
        public User $recipient,
        public User $sender
    ) {}

    public function handle(): void
    {
        try {
            Mail::send(new PesanBaru($this->forum, $this->recipient, $this->sender));
            
            Log::info('Email PesanBaru sent', [
                'forum_id' => $this->forum->id,
                'recipient_id' => $this->recipient->id,
                'sender_id' => $this->sender->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send PesanBaru email', [
                'forum_id' => $this->forum->id,
                'recipient_id' => $this->recipient->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
