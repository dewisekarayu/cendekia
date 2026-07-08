<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkhir extends Model
{
    use HasFactory;

    protected $table = 'nilai_akhir';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'mahasiswa_id',
        'nilai_kehadiran',
        'nilai_tugas',
        'nilai_quiz',
        'nilai_project',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'grade',
        'catatan',
    ];

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
