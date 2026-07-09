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

#[Fillable(['name', 'nip_nim', 'email', 'email_verified_at', 'password', 'program_studi_id', 'status', 'telepon', 'foto'])]
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

    /**
     * Helper methods for role checking with null safety
     */
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is dosen
     */
    public function isDosen(): bool
    {
        return $this->hasRole('dosen');
    }

    /**
     * Check if user is mahasiswa
     */
    public function isMahasiswa(): bool
    {
        return $this->hasRole('mahasiswa');
    }

    /**
     * Get user's primary role (for dashboard redirects)
     */
    public function getPrimaryRole(): string
    {
        if ($this->isAdmin()) {
            return 'admin';
        }
        if ($this->isDosen()) {
            return 'dosen';
        }
        if ($this->isMahasiswa()) {
            return 'mahasiswa';
        }
        return 'guest';
    }

    /**
     * Get dashboard route based on role
     */
    public function getDashboardRoute(): string
    {
        return match($this->getPrimaryRole()) {
            'admin' => route('admin.dashboard'),
            'dosen' => route('dosen.dashboard'),
            'mahasiswa' => route('mahasiswa.dashboard'),
            default => route('login'),
        };
    }

    /**
     * Check if user can access forum (basic check)
     */
    public function canAccessForum(ForumDiskusi $forum): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if ($this->isDosen()) {
            return $this->kelasDiampu()->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)->exists();
        }

        if ($this->isMahasiswa()) {
            return $this->kelasDiikuti()->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)->exists();
        }

        return false;
    }
}