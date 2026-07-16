<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'category',
        'message',
        'attachment_path',
        'status',
    ];

    /**
     * Relasi ke User pembuat tiket (jika terdaftar & login)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke seluruh balasan chat tiket ini
     */
    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }
}
