<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengumpulanTugas extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'tugas_id',
        'mahasiswa_id',
        'file_jawaban',
        'catatan',
        'waktu_kumpul',
        'nilai',
        'feedback_dosen',
        'status',
    ];

    protected $casts = [
        'waktu_kumpul' => 'datetime',
        'nilai'        => 'integer',
    ];

    // Status yang dipakai di kolom `status`
    public const STATUS_BELUM_DIKUMPUL = 'belum_dikumpulkan';
    public const STATUS_DIKUMPUL       = 'dikumpulkan';
    public const STATUS_DINILAI        = 'dinilai';
    public const STATUS_TERLAMBAT      = 'terlambat';

    public function tugas()
    {
        return $this->belongsTo(Tugas::class, 'tugas_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function files()
    {
        return $this->hasMany(PengumpulanTugasFile::class, 'pengumpulan_tugas_id');
    }

    public function getIsGradedAttribute(): bool
    {
        return $this->status === self::STATUS_DINILAI && !is_null($this->nilai);
    }

    public function getIsLateAttribute(): bool
    {
        if (!$this->waktu_kumpul || !$this->tugas) {
            return false;
        }

        return $this->waktu_kumpul->gt($this->tugas->deadline);
    }
}