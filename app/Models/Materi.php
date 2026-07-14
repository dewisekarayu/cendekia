<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'judul',
        'deskripsi',
        'pertemuan_ke',
        'file_path',
        'tipe_file',
    ];

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }

    // Many-to-many: satu materi bisa diajarkan oleh 1-2 dosen
    public function dosen()
    {
        return $this->belongsToMany(User::class, 'dosen_materi', 'materi_id', 'dosen_id')->withTimestamps();
    }

    public function files()
    {
        return $this->hasMany(MateriFile::class, 'materi_id');
    }
}