<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumpulanTugasFile extends Model
{
    protected $fillable = ['pengumpulan_tugas_id', 'file_path', 'nama_asli'];

    public function pengumpulan()
    {
        return $this->belongsTo(PengumpulanTugas::class, 'pengumpulan_tugas_id');
    }
}
