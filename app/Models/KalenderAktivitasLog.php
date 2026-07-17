<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KalenderAktivitasLog extends Model
{
    use HasFactory;

    protected $table = 'kalender_aktivitas_log';

    protected $fillable = [
        'user_id',
        'user_type',
        'event',
        'kalender_akademik_id',
        'kalender_akademik_judul',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
        'occurred_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'occurred_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kalenderAkademik(): BelongsTo
    {
        return $this->belongsTo(KalenderAkademik::class, 'kalender_akademik_id');
    }

    public static function log(string $event, ?KalenderAkademik $kalender = null, array $oldValues = [], array $newValues = [], ?string $description = null): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'user_type' => auth()->check() ? get_class(auth()->user()) : null,
            'event' => $event,
            'kalender_akademik_id' => $kalender?->id,
            'kalender_akademik_judul' => $kalender?->judul,
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'occurred_at' => now(),
        ]);
    }

    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'created' => 'Dibuat',
            'updated' => 'Diubah',
            'deleted' => 'Dihapus',
            'completed' => 'Diselesaikan',
            default => ucfirst($this->event),
        };
    }

    public function getEventColorAttribute(): string
    {
        return match ($this->event) {
            'created' => 'text-emerald-600 dark:text-emerald-400',
            'updated' => 'text-blue-600 dark:text-blue-400',
            'deleted' => 'text-red-600 dark:text-red-400',
            'completed' => 'text-indigo-600 dark:text-indigo-400',
            default => 'text-gray-600 dark:text-gray-400',
        };
    }

    public function getEventColorBgAttribute(): string
    {
        return match ($this->event) {
            'created' => 'bg-emerald-50 dark:bg-emerald-900/20',
            'updated' => 'bg-blue-50 dark:bg-blue-900/20',
            'deleted' => 'bg-red-50 dark:bg-red-900/20',
            'completed' => 'bg-indigo-50 dark:bg-indigo-900/20',
            default => 'bg-gray-50 dark:bg-gray-900/20',
        };
    }

    public function getEventIconAttribute(): string
    {
        return match ($this->event) {
            'created' => 'bi-plus-circle',
            'updated' => 'bi-pencil-square',
            'deleted' => 'bi-trash',
            'completed' => 'bi-check-circle',
            default => 'bi-info-circle',
        };
    }
}