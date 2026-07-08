<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'kelas_perkuliahan_id',
        'judul',
        'pesan',
        'tipe',
        'url',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
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
