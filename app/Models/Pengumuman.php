<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumuman';

    protected $fillable = [
        'kelas_perkuliahan_id',
        'dibuat_oleh',
        'judul',
        'isi',
        'untuk_semua',
        'penerima',
    ];

    protected $casts = [
        'untuk_semua' => 'boolean',
    ];

    public function scopeVisibleToRole(Builder $query, string $role): Builder
    {
        return $query->where(function (Builder $query) use ($role) {
            $query->whereNotNull('kelas_perkuliahan_id')
                ->orWhere(function (Builder $query) use ($role) {
                    $query->whereNull('kelas_perkuliahan_id')
                        ->whereIn('penerima', [$role, 'semua']);
                });
        });
    }

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}