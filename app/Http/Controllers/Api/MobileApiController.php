<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MobileApiController extends Controller
{
    public function dashboardMahasiswa(Request $request)
    {
        $user = $request->user();
        
        // Mock data for student dashboard
        return response()->json([
            'status' => 'success',
            'data' => [
                'jadwal' => [
                    [
                        'id' => 1,
                        'course_name' => 'Pemrograman Lanjut',
                        'time' => '08:00 - 10:30',
                        'room' => 'Lab Komputer 1'
                    ],
                    [
                        'id' => 2,
                        'course_name' => 'Basis Data',
                        'time' => '13:00 - 15:30',
                        'room' => 'Ruang Teori 2'
                    ]
                ],
                'tugas_deadline' => [
                    [
                        'id' => 1,
                        'title' => 'Tugas CRUD Laravel',
                        'course' => 'Pemrograman Web',
                        'deadline' => 'Besok, 23:59'
                    ]
                ],
                'pengumuman' => [
                    [
                        'id' => 1,
                        'title' => 'Libur Nasional',
                        'content' => 'Perkuliahan ditiadakan pada hari Senin.',
                        'date' => '01 Okt 2026'
                    ]
                ]
            ]
        ]);
    }

    public function dashboardDosen(Request $request)
    {
        // Mock data for lecturer dashboard
        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => [
                    'total_kelas' => 9,
                    'tugas_perlu_dinilai' => 0,
                    'total_mahasiswa' => 76
                ],
                'jadwal_mengajar' => [
                    [
                        'id' => 1,
                        'course_name' => 'Kecerdasan Buatan',
                        'time' => '10:00 - 12:30',
                        'room' => 'Lab AI'
                    ]
                ]
            ]
        ]);
    }
}
