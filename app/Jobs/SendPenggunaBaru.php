<?php

namespace App\Jobs;

use App\Mail\PenggunaBaru;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPenggunaBaru implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public User $user,
        public string $role
    ) {}

    public function handle(): void
    {
        try {
            Mail::send(new PenggunaBaru($this->user, $this->role));
            
            Log::info('Email PenggunaBaru sent', [
                'user_id' => $this->user->id,
                'role' => $this->role,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send PenggunaBaru email', [
                'user_id' => $this->user->id,
                'role' => $this->role,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
