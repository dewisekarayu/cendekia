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
        'program_studi_id',
        'semester_id',
        'kode_kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'kuota_mahasiswa',
        'status_kelas',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'kuota_mahasiswa' => 'integer',
    ];

    /**
     * Scope untuk kelas aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_kelas', 'aktif')->where('is_active', true);
    }

    /**
     * Scope untuk kelas berdasarkan program studi
     */
    public function scopeByProgramStudi($query, $programStudiId)
    {
        return $query->where('program_studi_id', $programStudiId);
    }

    /**
     * Scope untuk kelas berdasarkan semester
     */
    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    /**
     * Scope untuk kelas berdasarkan dosen
     */
    public function scopeByDosen($query, $dosenId)
    {
        return $query->where('dosen_id', $dosenId)
            ->orWhereHas('dosenPengampuTambahan', function ($q) use ($dosenId) {
                $q->where('users.id', $dosenId);
            });
    }

    /**
     * Relasi: kelas ini untuk mata kuliah apa
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    /**
     * Relasi: kelas ini diampu dosen siapa (dosen utama)
     */
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    /**
     * Relasi: dosen pengampu tambahan (team teaching) lewat tabel pivot kelas_dosen
     */
    public function dosenPengampuTambahan()
    {
        return $this->belongsToMany(User::class, 'kelas_dosen', 'kelas_perkuliahan_id', 'dosen_id')
            ->withPivot('is_koordinator')
            ->withTimestamps();
    }

    /**
     * Relasi: semua dosen pengampu (termasuk team teaching)
     */
    public function semuaDosenPengampu()
    {
        $utama = $this->dosen()->get();
        $tambahan = $this->dosenPengampuTambahan()->get();
        
        return $utama->merge($tambahan);
    }

    /**
     * Relasi: kelas ini di program studi apa
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    /**
     * Relasi: kelas ini di semester mana
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    /**
     * Relasi: mahasiswa yang terdaftar di kelas ini
     */
    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'kelas_mahasiswa', 'kelas_perkuliahan_id', 'mahasiswa_id')
            ->withPivot('tanggal_daftar')
            ->withTimestamps();
    }

    /**
     * Jumlah mahasiswa yang terdaftar di kelas ini
     */
    public function getJumlahMahasiswaAttribute()
    {
        return $this->mahasiswa()->count();
    }

    /**
     * Cek apakah kelas masih memiliki kuota tersedia
     */
    public function getKuotaTersediaAttribute()
    {
        return $this->kuota_mahasiswa - $this->jumlah_mahasiswa;
    }

    /**
     * Cek apakah kelas penuh
     */
    public function getIsPenuhAttribute()
    {
        return $this->jumlah_mahasiswa >= $this->kuota_mahasiswa;
    }

    /**
     * Validasi: apakah ada bentrok jadwal dosen
     */
    public function hasBentrokJadwalDosen()
    {
        // Cek bentrok untuk dosen utama
        $bentrokDosenUtama = self::where('dosen_id', $this->dosen_id)
            ->where('id', '!=', $this->id)
            ->where('hari', $this->hari)
            ->where(function($query) {
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhere(function($q) {
                        $q->where('jam_mulai', '<=', $this->jam_mulai)
                            ->where('jam_selesai', '>=', $this->jam_selesai);
                    });
            })
            ->exists();

        // Cek bentrok untuk dosen team teaching
        $bentrokTeamTeaching = false;
        $dosenTambahanIds = $this->dosenPengampuTambahan->pluck('id');
        if ($dosenTambahanIds->isNotEmpty()) {
            foreach ($dosenTambahanIds as $dosenId) {
                $bentrok = self::where(function ($q) use ($dosenId) {
                        $q->where('dosen_id', $dosenId)
                            ->orWhereHas('dosenPengampuTambahan', function ($sub) use ($dosenId) {
                                $sub->where('users.id', $dosenId);
                            });
                    })
                    ->where('id', '!=', $this->id)
                    ->where('hari', $this->hari)
                    ->where(function($query) {
                        $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                            ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                            ->orWhere(function($q) {
                                $q->where('jam_mulai', '<=', $this->jam_mulai)
                                    ->where('jam_selesai', '>=', $this->jam_selesai);
                            });
                    })
                    ->exists();

                if ($bentrok) {
                    $bentrokTeamTeaching = true;
                    break;
                }
            }
        }

        return $bentrokDosenUtama || $bentrokTeamTeaching;
    }

    /**
     * Validasi: apakah ada bentrok penggunaan ruangan
     */
    public function hasBentrokRuangan()
    {
        if (!$this->ruangan) {
            return false;
        }

        return self::where('ruangan', $this->ruangan)
            ->where('id', '!=', $this->id)
            ->where('hari', $this->hari)
            ->where(function($query) {
                $query->whereBetween('jam_mulai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$this->jam_mulai, $this->jam_selesai])
                    ->orWhere(function($q) {
                        $q->where('jam_mulai', '<=', $this->jam_mulai)
                            ->where('jam_selesai', '>=', $this->jam_selesai);
                    });
            })
            ->exists();
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

    public function pengumuman()
    {
        return $this->hasMany(Pengumuman::class, 'kelas_perkuliahan_id');
    }

    public function materi()
    {
        return $this->hasMany(Materi::class, 'kelas_perkuliahan_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'kelas_perkuliahan_id');
    }
}

