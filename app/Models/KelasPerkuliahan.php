<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasPerkuliahan extends Model
{
    use HasFactory;

    protected $table = 'kelas_perkuliahan';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'semester_id',
        'kode_kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi: kelas ini untuk mata kuliah apa
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Relasi: kelas ini diampu dosen siapa
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Relasi: kelas ini di semester mana
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    /**
     * Relasi: mahasiswa yang terdaftar di kelas ini (nanti dipakai di tabel kelas_mahasiswa)
     */
    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'kelas_perkuliahan_id', 'mahasiswa_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'kelas_perkuliahan_id');
    }

    /**
     * Relasi: forum diskusi untuk kelas ini
     */
    public function forum()
    {
        return $this->hasMany(ForumDiskusi::class, 'kelas_perkuliahan_id');
    }
}