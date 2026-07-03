<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KomentarDiskusi extends Model
{
    use HasFactory;

    protected $table = 'komentar_diskusi';

    protected $fillable = [
        'forum_diskusi_id',
        'user_id',
        'isi',
    ];

    public function forumDiskusi()
    {
        return $this->belongsTo(ForumDiskusi::class, 'forum_diskusi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}