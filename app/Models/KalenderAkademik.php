<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class KalenderAkademik extends Model
{
    use HasFactory;

    protected $table = 'kalender_akademik';

    protected $fillable = [
        'semester_id',
        'judul',
        'deskripsi',
        'catatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_kegiatan',
        'warna',
        'is_published',
        'is_all_day',
        'waktu_mulai',
        'waktu_selesai',
        'lokasi',
        'created_by',
        'updated_by',
    ];

    /**
     * BUG FIX: waktu_mulai & waktu_selesai sebelumnya di-cast sebagai 'datetime:H:i'
     * yang menyebabkan Carbon memparsing string 'H:i' sebagai full timestamp,
     * dan saat di-JSON muncul sebagai "2024-11-20 08:00:00" bukan "08:00".
     * Fix: cast sebagai 'string' agar tersimpan dan dibaca apa adanya dari DB.
     *
     * tanggal_mulai & tanggal_selesai tetap 'date' agar Carbon date comparison bekerja.
     */
    protected $casts = [
        'tanggal_mulai'  => 'date',
        'tanggal_selesai' => 'date',
        'is_published'   => 'boolean',
        'is_all_day'     => 'boolean',
        'waktu_mulai'    => 'string',
        'waktu_selesai'  => 'string',
    ];

    /**
     * BUG FIX: Accessor tidak muncul saat @json() karena tidak di-append.
     * Tambah semua accessor yang dibutuhkan oleh view (Alpine.js).
     */
    protected $appends = [
        'jenis_kegiatan_label',
        'jenis_kegiatan_icon',
        'waktu_formatted',
        'status_badge',
        'status_badge_admin',
        'is_multi_day',
        'is_past',
        'is_today_event',
        'is_ongoing',
    ];

    // ==========================================
    // RELATIONS
    // ==========================================

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function aktivitasLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(KalenderAktivitasLog::class, 'kalender_akademik_id');
    }

    // ==========================================
    // SCOPES
    // ==========================================

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    public function scopeByJenisKegiatan($query, $jenis)
    {
        return $query->where('jenis_kegiatan', $jenis);
    }

    /**
     * BUG FIX: Scope byDateRange sudah benar menangani multi-hari,
     * tetapi perlu memastikan perbandingan tanggal menggunakan format date saja.
     */
    public function scopeByDateRange($query, $start, $end)
    {
        return $query->whereDate('tanggal_mulai', '<=', $end)
            ->where(function ($q) use ($start) {
                $q->whereNull('tanggal_selesai')
                  ->orWhereDate('tanggal_selesai', '>=', $start);
            });
    }

    /**
     * BUG FIX: Scope upcoming defaultnya 7 hari, tapi controller memanggil upcoming(30).
     * Biarkan konsisten, tambah orderBy.
     */
    public function scopeUpcoming($query, $days = 30)
    {
        return $query->whereDate('tanggal_mulai', '>=', now()->toDateString())
            ->whereDate('tanggal_mulai', '<=', now()->addDays($days)->toDateString())
            ->orderBy('tanggal_mulai')
            ->orderBy('waktu_mulai');
    }

    /**
     * BUG FIX: Scope today menggunakan now() (datetime) untuk perbandingan date.
     * Gunakan toDateString() agar tidak terpengaruh waktu server.
     */
    public function scopeToday($query)
    {
        $today = now()->toDateString();
        return $query->whereDate('tanggal_mulai', '<=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('tanggal_selesai')
                  ->orWhereDate('tanggal_selesai', '>=', $today);
            })
            ->orderBy('tanggal_mulai')
            ->orderBy('waktu_mulai');
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal_mulai', now()->month)
            ->whereYear('tanggal_mulai', now()->year);
    }

    // ==========================================
    // ACCESSORS
    // ==========================================

    protected function jenisKegiatanLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->jenis_kegiatan) {
                'uts'                     => 'UTS',
                'uas'                     => 'UAS',
                'libur_nasional'          => 'Libur Nasional',
                'libur_akademik'          => 'Libur Akademik',
                'deadline_tugas'          => 'Deadline Tugas',
                'deadline_skripsi'        => 'Deadline Skripsi',
                'pengumuman_nilai'        => 'Pengumuman Nilai',
                'praktikum'               => 'Praktikum',
                'wisuda'                  => 'Wisuda',
                'orientasi_mahasiswa_baru' => 'Orientasi Mahasiswa Baru',
                'pembayaran_ukt'          => 'Pembayaran UKT',
                'pengisian_krs'           => 'Pengisian KRS',
                'pengisian_khs'           => 'Pengisian KHS',
                'cuti_akademik'           => 'Cuti Akademik',
                default                   => 'Lainnya',
            }
        );
    }

    protected function jenisKegiatanIcon(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->jenis_kegiatan) {
                'uts', 'uas'              => 'bi-file-earmark-text',
                'libur_nasional',
                'libur_akademik'          => 'bi-calendar-x',
                'deadline_tugas'          => 'bi-clock-history',
                'deadline_skripsi'        => 'bi-mortarboard',
                'pengumuman_nilai'        => 'bi-award',
                'praktikum'               => 'bi-beaker',
                'wisuda'                  => 'bi-mortarboard-fill',
                'orientasi_mahasiswa_baru' => 'bi-people',
                'pembayaran_ukt'          => 'bi-credit-card',
                'pengisian_krs'           => 'bi-journal-plus',
                'pengisian_khs'           => 'bi-journal-check',
                'cuti_akademik'           => 'bi-calendar-minus',
                default                   => 'bi-calendar-event',
            }
        );
    }

    /**
     * BUG FIX: waktu_mulai/waktu_selesai sekarang cast sebagai string,
     * sehingga bisa langsung dipakai tanpa Carbon::parse.
     */
    protected function waktuFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->is_all_day) {
                    return 'Sepanjang Hari';
                }
                $mulai   = $this->waktu_mulai   ? substr($this->waktu_mulai, 0, 5)   : '00:00';
                $selesai = $this->waktu_selesai ? substr($this->waktu_selesai, 0, 5) : null;
                return $selesai ? "{$mulai} – {$selesai}" : $mulai;
            }
        );
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->is_published) {
                    return ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-800'];
                }
                if ($this->tanggal_mulai->gt(now())) {
                    return ['label' => 'Akan Datang', 'class' => 'bg-blue-100 text-blue-800'];
                }
                if ($this->tanggal_selesai && $this->tanggal_selesai->lt(now()->startOfDay())) {
                    return ['label' => 'Selesai', 'class' => 'bg-green-100 text-green-800'];
                }
                return ['label' => 'Berlangsung', 'class' => 'bg-emerald-100 text-emerald-800'];
            }
        );
    }

    protected function statusBadgeAdmin(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->is_published) {
                    return ['label' => 'Draft', 'bg' => '#F1F3F9', 'color' => '#64748B'];
                }
                if ($this->tanggal_mulai->gt(now())) {
                    return ['label' => 'Akan Datang', 'bg' => '#DBEAFE', 'color' => '#1D4ED8'];
                }
                if ($this->tanggal_selesai && $this->tanggal_selesai->lt(now()->startOfDay())) {
                    return ['label' => 'Selesai', 'bg' => '#DCFCE7', 'color' => '#166534'];
                }
                return ['label' => 'Berlangsung', 'bg' => '#D1FAE5', 'color' => '#059669'];
            }
        );
    }

    protected function isMultiDay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_selesai && $this->tanggal_selesai->gt($this->tanggal_mulai)
        );
    }

    protected function isPast(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_selesai 
                ? $this->tanggal_selesai->lt(now()->startOfDay())
                : $this->tanggal_mulai->lt(now()->startOfDay())
        );
    }

    protected function isTodayEvent(): Attribute
    {
        return Attribute::make(
            get: function () {
                $today = now()->toDateString();
                return $this->tanggal_mulai->toDateString() === $today ||
                       ($this->tanggal_selesai && $this->tanggal_selesai->toDateString() === $today);
            }
        );
    }

    protected function isOngoing(): Attribute
    {
        return Attribute::make(
            get: function () {
                $today = now()->startOfDay();
                return $this->tanggal_mulai->lte($today) &&
                       ($this->tanggal_selesai === null || $this->tanggal_selesai->gte($today));
            }
        );
    }

    // ==========================================
    // STATIC HELPERS
    // ==========================================

    public static function getJenisKegiatanOptions(): array
    {
        return [
            'uts'                     => 'UTS',
            'uas'                     => 'UAS',
            'libur_nasional'          => 'Libur Nasional',
            'libur_akademik'          => 'Libur Akademik',
            'deadline_tugas'          => 'Deadline Tugas',
            'deadline_skripsi'        => 'Deadline Skripsi',
            'pengumuman_nilai'        => 'Pengumuman Nilai',
            'praktikum'               => 'Praktikum',
            'wisuda'                  => 'Wisuda',
            'orientasi_mahasiswa_baru' => 'Orientasi Mahasiswa Baru',
            'pembayaran_ukt'          => 'Pembayaran UKT',
            'pengisian_krs'           => 'Pengisian KRS',
            'pengisian_khs'           => 'Pengisian KHS',
            'cuti_akademik'           => 'Cuti Akademik',
            'lainnya'                 => 'Lainnya',
        ];
    }

    public static function getWarnaPresets(): array
    {
        return [
            '#002B6B' => 'Biru Tua (Default)',
            '#DC2626' => 'Merah (Ujian)',
            '#16A34A' => 'Hijau (Libur)',
            '#EA580C' => 'Oranye (Deadline)',
            '#9333EA' => 'Ungu (Acara Khusus)',
            '#0891B2' => 'Cyan (Informasi)',
            '#E11D48' => 'Pink (Penting)',
            '#65A30D' => 'Lime (Praktikum)',
            '#F59E0B' => 'Amber (Pembayaran)',
        ];
    }
}