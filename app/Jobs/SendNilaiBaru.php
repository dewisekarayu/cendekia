<?php

namespace App\Jobs;

use App\Mail\NilaiBaru;
use App\Models\PengumpulanTugas;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendNilaiBaru implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public PengumpulanTugas $submission,
        public User $mahasiswa,
        public User $dosen
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->mahasiswa->email)
                ->send(new NilaiBaru($this->submission, $this->mahasiswa, $this->dosen));
            
            Log::info('Email NilaiBaru sent', [
                'submission_id' => $this->submission->id,
                'mahasiswa_id' => $this->mahasiswa->id,
                'mahasiswa_email' => $this->mahasiswa->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send NilaiBaru email', [
                'submission_id' => $this->submission->id,
                'mahasiswa_id' => $this->mahasiswa->id,
                'mahasiswa_email' => $this->mahasiswa->email,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
