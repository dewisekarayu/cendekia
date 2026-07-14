<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id',
        'materi_baru',
        'tugas_baru',
        'pengumuman_baru',
        'nilai_baru',
        'absensi_dibuka',
        'pengumpulan_tugas',
        'pesan_baru',
        'pengguna_baru',
    ];

    protected $casts = [
        'materi_baru' => 'boolean',
        'tugas_baru' => 'boolean',
        'pengumuman_baru' => 'boolean',
        'nilai_baru' => 'boolean',
        'absensi_dibuka' => 'boolean',
        'pengumpulan_tugas' => 'boolean',
        'pesan_baru' => 'boolean',
        'pengguna_baru' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get or create preferences for a user with defaults
     */
    public static function forUser($userId)
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            [
                'materi_baru' => true,
                'tugas_baru' => true,
                'pengumuman_baru' => true,
                'nilai_baru' => true,
                'absensi_dibuka' => true,
                'pengumpulan_tugas' => true,
                'pesan_baru' => true,
                'pengguna_baru' => true,
            ]
        );
    }

    /**
     * Check if user wants to receive notification for a type
     */
    public function isEnabled($type)
    {
        return (bool) $this->{$type};
    }
}

