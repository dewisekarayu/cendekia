<?php

namespace App\Jobs;

use App\Mail\AbsensiDibuka;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendAbsensiDibuka implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 120;

    public function __construct(
        public Absensi $absensi,
        public User $mahasiswa,
        public User $dosen
    ) {}

    public function handle(): void
    {
        try {
            Mail::to($this->mahasiswa->email)->send(new AbsensiDibuka($this->absensi, $this->mahasiswa, $this->dosen));
            
            Log::info('Email AbsensiDibuka sent', [
                'absensi_id' => $this->absensi->id,
                'mahasiswa_id' => $this->mahasiswa->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send AbsensiDibuka email', [
                'absensi_id' => $this->absensi->id,
                'mahasiswa_id' => $this->mahasiswa->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
