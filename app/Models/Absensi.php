<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'pertemuan_ke',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'session_status',
        'rangkuman',
        'berita_acara',
        'catatan',
        'waktu_buka',
        'waktu_tutup',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_buka' => 'datetime',
        'waktu_tutup' => 'datetime',
    ];

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }

    public function absensiMahasiswa()
    {
        return $this->hasMany(AbsensiMahasiswa::class, 'absensi_id');
    }

    /**
     * Cek apakah sesi absensi sudah dibuka
     */
    public function isBuka(): bool
    {
        return $this->session_status === 'buka';
    }

    /**
     * Cek apakah sesi absensi sudah ditutup
     */
    public function isTutup(): bool
    {
        return $this->session_status === 'tutup';
    }

    /**
     * Cek apakah masih dalam draft
     */
    public function isDraft(): bool
    {
        return $this->session_status === 'draft';
    }

    /**
     * Buka sesi absensi
     */
    public function bukaSession(): bool
    {
        $this->session_status = 'buka';
        $this->waktu_buka = now();
        return $this->save();
    }

    /**
     * Tutup sesi absensi
     */
    public function tutupSession(): bool
    {
        $this->session_status = 'tutup';
        $this->waktu_tutup = now();
        return $this->save();
    }

    /**
     * Get status badge color untuk UI
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->session_status) {
            'draft' => 'warning',
            'buka' => 'success',
            'tutup' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status label untuk UI
     */
    public function getStatusLabel(): string
    {
        return match($this->session_status) {
            'draft' => 'Draft',
            'buka' => 'Dibuka',
            'tutup' => 'Ditutup',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get durasi pertemuan (in minutes)
     */
    public function getDurasi(): ?int
    {
        if (!$this->jam_mulai || !$this->jam_selesai) {
            return null;
        }

        $mulai = Carbon::createFromTimeString($this->jam_mulai);
        $selesai = Carbon::createFromTimeString($this->jam_selesai);
        
        return $selesai->diffInMinutes($mulai);
    }

    /**
     * Scope untuk mendapatkan sesi yang sudah dibuka
     */
    public function scopeBuka($query)
    {
        return $query->where('session_status', 'buka');
    }

    /**
     * Scope untuk mendapatkan sesi yang masih draft
     */
    public function scopeDraft($query)
    {
        return $query->where('session_status', 'draft');
    }

    /**
     * Scope untuk mendapatkan sesi yang sudah ditutup
     */
    public function scopeTutup($query)
    {
        return $query->where('session_status', 'tutup');
    }

    /**
     * Scope untuk mendapatkan sesi hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', today());
    }
}