<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KRSController extends Controller
{
    public function index(Request $request)
    {
        $dosen = $request->user();

        // Ambil mahasiswa bimbingan Dosen PA ini
        $mahasiswaBimbingan = User::where('dosen_pa_id', $dosen->id)
            ->with(['krs' => function ($query) {
                // Ambil krs semester aktif, kita asumsikan yang terbaru
                $query->latest()->limit(1);
            }])
            ->get();

        return view('dosen.krs.index', compact('mahasiswaBimbingan'));
    }

    public function show($id)
    {
        $krs = DB::table('krs')->where('id', $id)->first();
        if (!$krs) {
            return abort(404);
        }

        $mahasiswa = User::findOrFail($krs->mahasiswa_id);
        
        // Pastikan Dosen PA yang sedang login berhak melihat
        if ($mahasiswa->dosen_pa_id !== auth()->id()) {
            return abort(403, 'Unauthorized access to this KRS.');
        }

        $krsItems = DB::table('krs_items')
            ->join('kelas_perkuliahan', 'krs_items.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
            ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->where('krs_items.krs_id', $krs->id)
            ->select('krs_items.*', 'kelas_perkuliahan.kode_kelas', 'mata_kuliah.nama_mk', 'mata_kuliah.sks', 'mata_kuliah.semester')
            ->get();

        return view('dosen.krs.show', compact('krs', 'mahasiswa', 'krsItems'));
    }

    public function approve(Request $request, $id)
    {
        $krs = DB::table('krs')->where('id', $id)->first();
        if (!$krs) {
            return back()->with('error', 'KRS tidak ditemukan.');
        }

        DB::table('krs')->where('id', $id)->update([
            'status' => 'disetujui',
            'catatan_pembimbing' => $request->input('catatan'),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'KRS berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $krs = DB::table('krs')->where('id', $id)->first();
        if (!$krs) {
            return back()->with('error', 'KRS tidak ditemukan.');
        }

        DB::table('krs')->where('id', $id)->update([
            'status' => 'ditolak',
            'catatan_pembimbing' => $request->input('catatan'),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'KRS ditolak.');
    }
}
