<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'absensi_mahasiswa';

    protected $fillable = [
        'absensi_id',
        'mahasiswa_id',
        'status',
        'waktu_absensi',
        'keterangan',
    ];

    protected $casts = [
        'waktu_absensi' => 'datetime',
    ];

    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    /**
     * Get status badge color untuk UI
     */
    public function getStatusBadgeColor(): string
    {
        return match($this->status) {
            'hadir' => 'success',
            'izin' => 'info',
            'sakit' => 'warning',
            'alpha' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'hadir' => 'Hadir',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Cek apakah status hadir
     */
    public function isHadir(): bool
    {
        return $this->status === 'hadir';
    }

    /**
     * Cek apakah status izin
     */
    public function isIzin(): bool
    {
        return $this->status === 'izin';
    }

    /**
     * Cek apakah status sakit
     */
    public function isSakit(): bool
    {
        return $this->status === 'sakit';
    }

    /**
     * Cek apakah status alpha
     */
    public function isAlpha(): bool
    {
        return $this->status === 'alpha';
    }

    /**
     * Scope untuk mendapatkan yang hadir
     */
    public function scopeHadir($query)
    {
        return $query->where('status', 'hadir');
    }

    /**
     * Scope untuk mendapatkan yang izin
     */
    public function scopeIzin($query)
    {
        return $query->where('status', 'izin');
    }

    /**
     * Scope untuk mendapatkan yang sakit
     */
    public function scopeSakit($query)
    {
        return $query->where('status', 'sakit');
    }

    /**
     * Scope untuk mendapatkan yang alpha
     */
    public function scopeAlpha($query)
    {
        return $query->where('status', 'alpha');
    }
}