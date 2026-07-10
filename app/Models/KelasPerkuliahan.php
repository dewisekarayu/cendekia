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
        'tahun_akademik',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'kuota_mahasiswa',
        'status_kelas',
        'is_active',
        'dosen_pengampu',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'dosen_pengampu' => 'array',
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
            ->orWhereJsonContains('dosen_pengampu', $dosenId);
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
     * Relasi: semua dosen pengampu (termasuk team teaching)
     */
    public function semuaDosenPengampu()
    {
        $dosenIds = $this->dosen_pengampu ?? [];
        $dosenIds[] = $this->dosen_id;
        
        return User::whereIn('id', $dosenIds)->get();
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
        if ($this->dosen_pengampu) {
            foreach ($this->dosen_pengampu as $dosenId) {
                $bentrok = self::where('dosen_id', $dosenId)
                    ->orWhereJsonContains('dosen_pengampu', $dosenId)
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
}

