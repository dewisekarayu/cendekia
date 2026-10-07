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
        'kuota_mahasiswa',
        'status_kelas',
        'is_active',
        'bobot_tugas',
        'bobot_uts',
        'bobot_uas',
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
     * Relasi: jadwal kelas (multiple)
     */
    public function jadwals()
    {
        return $this->hasMany(KelasJadwal::class, 'kelas_perkuliahan_id');
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
     * Accessor untuk properti jadwal (diambil dari jadwal pertama jika ada)
     * Ini memastikan backward compatibility dengan view yang menggunakan $kelas->hari, $kelas->jam_mulai, dll.
     */
    public function getHariAttribute()
    {
        return $this->jadwals->first()->hari ?? null;
    }

    public function getJamMulaiAttribute()
    {
        return $this->jadwals->first()->jam_mulai ?? null;
    }

    public function getJamSelesaiAttribute()
    {
        return $this->jadwals->first()->jam_selesai ?? null;
    }

    public function getRuanganAttribute()
    {
        return $this->jadwals->first()->ruangan ?? null;
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
     * Validasi: apakah ada bentrok jadwal dosen (dinamis dari parameter atau jadwal yang sudah ada)
     * $jadwalCek: array of ['hari' =>, 'jam_mulai' =>, 'jam_selesai' =>]
     * $dosenIdUtama: ID Dosen Utama (jika beda dengan $this->dosen_id, misal saat create)
     * $dosenTambahanIds: Array ID Dosen Tambahan
     */
    public static function cekBentrokDosenCustom($jadwalCek, $dosenIdUtama, $dosenTambahanIds = [], $ignoreKelasId = null)
    {
        $semuaDosen = array_merge([$dosenIdUtama], $dosenTambahanIds);
        $semuaDosen = array_unique(array_filter($semuaDosen));

        if (empty($semuaDosen) || empty($jadwalCek)) return false;

        foreach ($jadwalCek as $jadwal) {
            foreach ($semuaDosen as $dosenId) {
                // Cari kelas_jadwal yang bentrok, yang terhubung ke kelas_perkuliahan dimana dosenId ini mengajar
                $bentrok = KelasJadwal::where('hari', $jadwal['hari'])
                    ->where(function($query) use ($jadwal) {
                        $query->whereBetween('jam_mulai', [$jadwal['jam_mulai'], $jadwal['jam_selesai']])
                            ->orWhereBetween('jam_selesai', [$jadwal['jam_mulai'], $jadwal['jam_selesai']])
                            ->orWhere(function($q) use ($jadwal) {
                                $q->where('jam_mulai', '<=', $jadwal['jam_mulai'])
                                    ->where('jam_selesai', '>=', $jadwal['jam_selesai']);
                            });
                    })
                    ->whereHas('kelasPerkuliahan', function($q) use ($dosenId, $ignoreKelasId) {
                        if ($ignoreKelasId) {
                            $q->where('id', '!=', $ignoreKelasId);
                        }
                        $q->where(function ($sub) use ($dosenId) {
                            $sub->where('dosen_id', $dosenId)
                                ->orWhereHas('dosenPengampuTambahan', function ($sub2) use ($dosenId) {
                                    $sub2->where('users.id', $dosenId);
                                });
                        });
                    })
                    ->exists();

                if ($bentrok) return true;
            }
        }
        return false;
    }

    /**
     * Cek bentrok untuk kelas ini berdasarkan jadwals (dipanggil setelah save atau cek dinamis)
     */
    public function hasBentrokJadwalDosen()
    {
        $jadwalCek = $this->jadwals->toArray();
        $dosenTambahanIds = $this->dosenPengampuTambahan->pluck('id')->toArray();
        return self::cekBentrokDosenCustom($jadwalCek, $this->dosen_id, $dosenTambahanIds, $this->id);
    }

    /**
     * Validasi: apakah ada bentrok penggunaan ruangan
     */
    public static function cekBentrokRuanganCustom($jadwalCek, $ignoreKelasId = null)
    {
        if (empty($jadwalCek)) return false;

        foreach ($jadwalCek as $jadwal) {
            if (empty($jadwal['ruangan'])) continue;

            $bentrok = KelasJadwal::where('ruangan', $jadwal['ruangan'])
                ->where('hari', $jadwal['hari'])
                ->where(function($query) use ($jadwal) {
                    $query->whereBetween('jam_mulai', [$jadwal['jam_mulai'], $jadwal['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$jadwal['jam_mulai'], $jadwal['jam_selesai']])
                        ->orWhere(function($q) use ($jadwal) {
                            $q->where('jam_mulai', '<=', $jadwal['jam_mulai'])
                                ->where('jam_selesai', '>=', $jadwal['jam_selesai']);
                        });
                });

            if ($ignoreKelasId) {
                $bentrok->where('kelas_perkuliahan_id', '!=', $ignoreKelasId);
            }

            if ($bentrok->exists()) return true;
        }

        return false;
    }

    public function hasBentrokRuangan()
    {
        $jadwalCek = $this->jadwals->toArray();
        return self::cekBentrokRuanganCustom($jadwalCek, $this->id);
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

