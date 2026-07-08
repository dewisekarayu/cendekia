<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'nip_nim', 'email', 'password', 'program_studi_id', 'status', 'telepon', 'foto'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi: kelas yang diampu (kalau user ini dosen)
     */
    public function kelasDiampu()
    {
        return $this->hasMany(KelasPerkuliahan::class, 'dosen_id');
    }

    /**
     * Relasi: kelas yang diikuti (kalau user ini mahasiswa)
     */
    public function kelasDiikuti()
    {
        return $this->belongsToMany(KelasPerkuliahan::class, 'kelas_mahasiswa', 'mahasiswa_id', 'kelas_perkuliahan_id');
    }

    /**
     * Relasi: tugas yang dikumpulkan (kalau user ini mahasiswa)
     */
    public function pengumpulanTugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'mahasiswa_id');
    }

    /**
     * Relasi: program studi milik user ini (kalau mahasiswa)
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }
}