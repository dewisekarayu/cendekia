<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataKuliah extends Model
{
     use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = [
        'program_studi_id',
        'kode_mk',
        'nama_mk',
        'sks',
        'semester_ke',
        'deskripsi',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    /**
     * Relasi: satu mata kuliah bisa punya banyak kelas perkuliahan
     */
    public function kelasPerkuliahan()
    {
        return $this->hasMany(KelasPerkuliahan::class, 'mata_kuliah_id');
    }
}

