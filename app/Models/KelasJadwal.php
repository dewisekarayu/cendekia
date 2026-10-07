<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasJadwal extends Model
{
    protected $table = 'kelas_jadwals';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
    ];

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }
}
