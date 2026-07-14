<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriFile extends Model
{
    protected $fillable = ['materi_id', 'file_path', 'nama_asli', 'tipe_file'];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
