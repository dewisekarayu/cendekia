<?php

namespace App\Jobs;

use App\Mail\PengumumanBaru;
use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPengumumanBaru implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public Pengumuman $pengumuman,
        public User $recipient,
        public User $dosen
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->recipient->email)
                ->send(new PengumumanBaru($this->pengumuman, $this->recipient, $this->dosen));
            
            Log::info('Email PengumumanBaru sent', [
                'pengumuman_id' => $this->pengumuman->id,
                'recipient_id' => $this->recipient->id,
                'recipient_email' => $this->recipient->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send PengumumanBaru email', [
                'pengumuman_id' => $this->pengumuman->id,
                'recipient_id' => $this->recipient->id,
                'recipient_email' => $this->recipient->email,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
