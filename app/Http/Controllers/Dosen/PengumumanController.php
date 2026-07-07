<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    // Menampilkan halaman pengumuman versi statis (Tampilan Saja)
    public function index($kelas_perkuliahan_id)
    {
        // Membuat data palsu (mockup) agar tampilan tidak kosong saat diuji coba
        $pengumuman = collect([
            (object)[
                'id' => 1,
                'judul' => 'Perubahan Jadwal Ujian Tengah Semester (UTS) Ganjil',
                'isi' => 'Diberitahukan kepada seluruh mahasiswa bahwa pelaksanaan UTS Mata Kuliah Pemrograman Web yang semula dijadwalkan hari Senin, dialihkan menjadi hari Rabu dikarenakan adanya agenda rapat akreditasi fakultas.',
                'untuk_semua' => true,
                'kategori' => 'Academic',
                'lampiran' => 'dokumen_uts.pdf',
                'target' => 'All Students',
                'created_at' => now(),
                'pembuat' => (object)['name' => 'Prof. Dr. Ir. Budi Santoso']
            ],
            (object)[
                'id' => 2,
                'judul' => 'Pemeliharaan Rutin Server Portal Akademik',
                'isi' => 'Kami akan melakukan maintenance sistem pada hari Sabtu mulai pukul 23:00 WIB hingga Minggu pukul 04:00 WIB. Selama jendela waktu tersebut, Portal Akademik tidak akan dapat diakses.',
                'untuk_semua' => false,
                'kategori' => 'System Updates',
                'lampiran' => null,
                'target' => 'All Faculty',
                'created_at' => now()->subDays(2),
                'pembuat' => (object)['name' => 'IT Support Team']
            ]
        ]);

        // Mockup fungsi links() agar pagination palsu di Blade tidak error jika dipanggil
        $pengumuman->links = function() { return ''; };

        return view('dosen.kelas-pengumuman', compact('pengumuman', 'kelas_perkuliahan_id'));
    }

    // Simulasi menyimpan pengumuman tanpa ke database
    public function store(Request $request, $kelas_perkuliahan_id)
    {
        return redirect()->back()->with('success', '(Simulasi) Pengumuman kelas berhasil diterbitkan.');
    }

    // Simulasi menghapus pengumuman tanpa ke database
    public function destroy($id)
    {
        return redirect()->back()->with('success', '(Simulasi) Pengumuman kelas berhasil dihapus.');
    }
}