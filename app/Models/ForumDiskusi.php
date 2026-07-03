<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumDiskusi extends Model
{
    use HasFactory;

    protected $table = 'forum_diskusi';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'dibuat_oleh',
        'judul',
        'isi',
    ];

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    public function komentar()
    {
        return $this->hasMany(KomentarDiskusi::class, 'forum_diskusi_id');
    }
}