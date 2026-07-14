<?php

namespace App\Jobs;

use App\Mail\PengumpulanTugas;
use App\Models\PengumpulanTugas as PengumpulanTugasModel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPengumpulanTugas implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public PengumpulanTugasModel $submission,
        public User $dosen
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->dosen->email)
                ->send(new PengumpulanTugas($this->submission, $this->dosen));
            
            Log::info('Email PengumpulanTugas sent', [
                'submission_id' => $this->submission->id,
                'dosen_id' => $this->dosen->id,
                'dosen_email' => $this->dosen->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send PengumpulanTugas email', [
                'submission_id' => $this->submission->id,
                'dosen_id' => $this->dosen->id,
                'dosen_email' => $this->dosen->email,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
