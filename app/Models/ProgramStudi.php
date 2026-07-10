<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgramStudi extends Model
{
    use HasFactory;

    protected $table = 'program_studi';

    // TAMBAHKAN 'akreditasi' DAN 'status' DI SINI AGAR BISA DISIMPAN
    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
        'akreditasi',
        'status',
    ];

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'program_studi_id');
    }
}