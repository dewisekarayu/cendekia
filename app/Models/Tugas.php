<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table = 'tugas';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'judul',
        'instruksi',
        'file_lampiran',
        'link_lampiran',
        'deadline',
        'bobot_nilai',
        'is_closed',
    ];

    protected $casts = [
        'deadline' => 'datetime',
        'is_closed' => 'boolean',
    ];

    public function isTutup(): bool
    {
        return (bool) ($this->is_closed || ($this->deadline && $this->deadline->isPast()));
    }

    public function isTerbuka(): bool
    {
        return !$this->isTutup();
    }

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }

    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    public function pengumpulan()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    public function files()
    {
        return $this->hasMany(TugasFile::class, 'tugas_id');
    }
    
    public function getDaysLeftAttribute(): int
    {
        return (int) floor(now()->diffInDays($this->deadline, false));
    }
}