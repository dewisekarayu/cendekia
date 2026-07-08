<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalUjian extends Model
{
    use HasFactory;

    protected $table = 'jadwal_ujian';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'jenis_ujian',
        'tanggal_ujian',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'catatan',
        'is_published',
    ];

    protected $casts = [
        'tanggal_ujian' => 'date',
        'is_published' => 'boolean',
    ];

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }
}
