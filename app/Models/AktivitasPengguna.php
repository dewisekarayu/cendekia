<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasPengguna extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_pengguna';

    protected $fillable = [
        'user_id',
        'kelas_perkuliahan_id',
        'aksi',
        'deskripsi',
        'ip_address',
        'user_agent',
        'terjadi_pada',
    ];

    protected $casts = [
        'terjadi_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }
}
