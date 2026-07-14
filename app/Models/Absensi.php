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

    /**
     * Get attendance statistics for this session
     */
    public function getAttendanceStats(): array
    {
        $mahasiswaCount = $this->kelasPerkuliahan->mahasiswa->count();
        $hadirCount = $this->absensiMahasiswa->where('status', 'hadir')->count();
        $izinCount = $this->absensiMahasiswa->where('status', 'izin')->count();
        $sakitCount = $this->absensiMahasiswa->where('status', 'sakit')->count();
        $alphaCount = $mahasiswaCount - $this->absensiMahasiswa->count();

        return [
            'total_mahasiswa' => $mahasiswaCount,
            'hadir' => $hadirCount,
            'izin' => $izinCount,
            'sakit' => $sakitCount,
            'alpha' => $alphaCount,
            'hadir_pct' => $mahasiswaCount > 0 ? round(($hadirCount / $mahasiswaCount) * 100) : 0,
            'absensi_pct' => $mahasiswaCount > 0 ? round((($izinCount + $sakitCount) / $mahasiswaCount) * 100) : 0,
            'alpha_pct' => $mahasiswaCount > 0 ? round(($alphaCount / $mahasiswaCount) * 100) : 0,
        ];
    }

    /**
     * Check if attendance is locked (session tutup or older than 1 day)
     */
    public function isLocked(): bool
    {
        return $this->isTutup() || $this->tanggal->diffInDays(today()) > 0;
    }

    /**
     * Get formatted duration time
     */
    public function getFormattedDuration(): string
    {
        if (!$this->jam_mulai || !$this->jam_selesai) {
            return '—';
        }

        $durasi = $this->getDurasi();
        $jam = floor($durasi / 60);
        $menit = $durasi % 60;

        if ($jam > 0) {
            return "{$jam}j {$menit}m";
        }
        return "{$menit}m";
    }

    /**
     * Check if attendance can be opened
     */
    public function canBeOpened(): bool
    {
        return $this->isDraft() && !$this->isLocked();
    }

    /**
     * Check if attendance can be closed
     */
    public function canBeClosed(): bool
    {
        return $this->isBuka();
    }
}