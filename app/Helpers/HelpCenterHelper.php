<?php

namespace App\Helpers;

class HelpCenterHelper
{
    /**
     * Get contextual help for different pages
     * 
     * @param string $context (e.g., 'kelas', 'absensi', 'tugas')
     * @param string $action (e.g., 'detail', 'index', 'session')
     * @return array
     */
    public static function getContextualHelp(string $context, string $action = 'index'): array
    {
        $helps = [
            'kelas' => [
                'detail' => [
                    'title' => 'Bantuan: Detail Kelas',
                    'message' => 'Halaman ini menampilkan semua materi, tugas, dan absensi untuk kelas ini. Gunakan tab di bawah untuk berpindah antar bagian.',
                    'tips' => [
                        'Klik tab "Materi" untuk melihat materi pembelajaran dari dosen',
                        'Tab "Tugas" berisi semua assignment yang perlu dikumpulkan',
                        'Tab "Absensi" mencatat kehadiran Anda di setiap pertemuan',
                        'Gunakan tombol "Isi Absen" saat sesi kelas sedang berlangsung',
                    ],
                    'links' => [
                        ['label' => 'Panduan Absensi', 'url' => route('help-center.guides')],
                        ['label' => 'FAQ Kelas', 'url' => route('help-center.index')],
                    ],
                ],
            ],
            'absensi' => [
                'index' => [
                    'title' => 'Bantuan: Daftar Absensi',
                    'message' => 'Halaman ini menampilkan semua sesi absensi dari kelas Anda. Klik pada kelas untuk melakukan absensi.',
                    'tips' => [
                        'Hanya kelas dengan status "Sedang Berlangsung" yang bisa diabsen',
                        'Klik tombol absensi saat sesi dibuka dosen agar tercatat hadir',
                        'Jika ada kesulitan, hubungi dosen untuk manual entry',
                    ],
                    'links' => [
                        ['label' => 'Panduan Absensi', 'url' => route('help-center.guides')],
                    ],
                ],
                'session' => [
                    'title' => 'Bantuan: Mengisi Absensi',
                    'message' => 'Klik tombol absensi di bawah untuk mencatat kehadiran Anda pada sesi ini.',
                    'tips' => [
                        'Pastikan Anda mengisi absensi sebelum sesi kelas berakhir',
                        'Pilih status kehadiran yang sesuai: Hadir, Izin, atau Sakit',
                        'Tunggu hingga sistem memproses dan menyimpan absensi Anda',
                        'Jika gagal, coba refresh halaman atau hubungi dosen',
                    ],
                    'links' => [
                        ['label' => 'FAQ Absensi', 'url' => route('help-center.index') . '?category=absensi'],
                    ],
                ],
            ],
        ];

        return $helps[$context][$action] ?? [
            'title' => ucfirst($context) . ' Help',
            'message' => 'Jika Anda memiliki pertanyaan, kunjungi Pusat Bantuan kami.',
            'links' => [
                ['label' => 'Pusat Bantuan', 'url' => route('help-center.index')],
            ],
        ];
    }
}