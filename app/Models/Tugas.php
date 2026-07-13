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
        'deadline',
        'bobot_nilai',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

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
}