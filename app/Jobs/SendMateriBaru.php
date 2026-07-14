<?php

namespace App\Jobs;

use App\Mail\MateriBaru;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendMateriBaru implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public Materi $materi,
        public User $mahasiswa,
        public User $dosen
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->mahasiswa->email)
                ->send(new MateriBaru($this->materi, $this->mahasiswa, $this->dosen));
            
            Log::info('Email MateriBaru sent', [
                'materi_id' => $this->materi->id,
                'mahasiswa_id' => $this->mahasiswa->id,
                'mahasiswa_email' => $this->mahasiswa->email,
                'dosen_id' => $this->dosen->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send MateriBaru email', [
                'materi_id' => $this->materi->id,
                'mahasiswa_id' => $this->mahasiswa->id,
                'mahasiswa_email' => $this->mahasiswa->email,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('SendMateriBaru job failed after retries', [
            'materi_id' => $this->materi->id,
            'mahasiswa_id' => $this->mahasiswa->id,
            'exception' => $exception->getMessage(),
        ]);
    }
}
