<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'kelas_perkuliahan_id',
        'judul',
        'pesan',
        'tipe',
        'url',
        'dibaca_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelasPerkuliahan()
    {
        return $this->belongsTo(KelasPerkuliahan::class, 'kelas_perkuliahan_id');
    }

    /**
     * Get visual badge styling and label based on notification type
     */
    public function getTypeMeta(): array
    {
        return match($this->tipe) {
            'tugas' => [
                'label' => 'Tugas',
                'bg_class' => 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300',
                'icon_bg' => 'bg-blue-500 text-white',
                'border_class' => 'border-blue-200 dark:border-blue-800/40',
                'icon' => 'clipboard-document-list',
            ],
            'materi' => [
                'label' => 'Materi',
                'bg_class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300',
                'icon_bg' => 'bg-emerald-500 text-white',
                'border_class' => 'border-emerald-200 dark:border-emerald-800/40',
                'icon' => 'book-open',
            ],
            'pengumuman', 'informasi' => [
                'label' => 'Pengumuman',
                'bg_class' => 'bg-purple-100 text-purple-700 dark:bg-purple-950/60 dark:text-purple-300',
                'icon_bg' => 'bg-purple-500 text-white',
                'border_class' => 'border-purple-200 dark:border-purple-800/40',
                'icon' => 'megaphone',
            ],
            'presensi' => [
                'label' => 'Presensi',
                'bg_class' => 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300',
                'icon_bg' => 'bg-amber-500 text-white',
                'border_class' => 'border-amber-200 dark:border-amber-800/40',
                'icon' => 'clock',
            ],
            'nilai' => [
                'label' => 'Nilai',
                'bg_class' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300',
                'icon_bg' => 'bg-indigo-500 text-white',
                'border_class' => 'border-indigo-200 dark:border-indigo-800/40',
                'icon' => 'academic-cap',
            ],
            default => [
                'label' => 'Aktivitas',
                'bg_class' => 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300',
                'icon_bg' => 'bg-slate-500 text-white',
                'border_class' => 'border-slate-200 dark:border-slate-700',
                'icon' => 'bell',
            ],
        };
    }
}
