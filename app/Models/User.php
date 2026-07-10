<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nip_nim',
        'email',
        'password',
        'program_studi_id',
        'status',
        'telepon',
        'foto',
        'email_verified_at'
    ];

    /**
     * Atribut yang harus disembunyikan dalam serialisasi JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut tipe data casting.
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
     * Relasi: materi yang diajarkan (kalau user ini dosen)
     * Many-to-many: satu dosen bisa mengajarkan beberapa materi
     */
    public function materi()
    {
        return $this->belongsToMany(Materi::class, 'dosen_materi', 'dosen_id', 'materi_id')->withTimestamps();
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

    /**
     * Check if user can access forum with detailed diagnostic information
     * Returns an array with access status and detailed reason
     */
    public function canAccessForumDetailed(ForumDiskusi $forum): array
    {
        $result = [
            'can_access' => false,
            'user_id' => $this->id,
            'user_role' => $this->getPrimaryRole(),
            'forum_id' => $forum->id,
            'forum_kelas_id' => $forum->kelas_perkuliahan_id,
            'reason' => '',
            'debug_info' => [],
        ];

        // Check if user is authenticated
        if (!$this->id) {
            $result['reason'] = 'User not authenticated';
            return $result;
        }

        // Admin check
        if ($this->isAdmin()) {
            $result['can_access'] = true;
            $result['reason'] = 'Admin user - full access';
            return $result;
        }

        // Dosen check
        if ($this->isDosen()) {
            $classesTeaching = $this->kelasDiampu()->pluck('id')->toArray();
            $result['debug_info']['classes_teaching'] = $classesTeaching;
            
            $hasAccess = in_array($forum->kelas_perkuliahan_id, $classesTeaching);
            if ($hasAccess) {
                $result['can_access'] = true;
                $result['reason'] = 'Dosen teaches this class';
                return $result;
            } else {
                $result['reason'] = 'Dosen does not teach this class';
                return $result;
            }
        }

        // Mahasiswa check
        if ($this->isMahasiswa()) {
            $classesEnrolled = $this->kelasDiikuti()->pluck('id')->toArray();
            $result['debug_info']['classes_enrolled'] = $classesEnrolled;
            
            $hasAccess = in_array($forum->kelas_perkuliahan_id, $classesEnrolled);
            if ($hasAccess) {
                $result['can_access'] = true;
                $result['reason'] = 'Mahasiswa enrolled in this class';
                return $result;
            } else {
                $result['reason'] = 'Mahasiswa not enrolled in this class';
                return $result;
            }
        }

        // No valid role
        $result['reason'] = 'User has no valid role (not admin, dosen, or mahasiswa)';
        $result['debug_info']['user_roles'] = $this->getRoleNames()->toArray();
        return $result;
    }
}