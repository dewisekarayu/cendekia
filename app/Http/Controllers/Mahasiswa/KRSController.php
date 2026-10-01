<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\KelasPerkuliahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KRSController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = $request->user();
        
        // Ambil KRS semester aktif atau draft terakhir
        $krs = Krs::where('mahasiswa_id', $mahasiswa->id)->latest()->first();

        // Jika belum ada, buat draft kosong untuk semester 1
        if (!$krs) {
            $krs = Krs::create([
                'mahasiswa_id' => $mahasiswa->id,
                'tahun_akademik' => date('Y') . '/' . (date('Y') + 1),
                'semester' => '1',
                'status' => 'draft',
            ]);
        }

        $krsItems = [];
        $totalSks = 0;
        
        if ($krs) {
            $krsItems = DB::table('krs_items')
                ->join('kelas_perkuliahan', 'krs_items.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
                ->join('mata_kuliah', 'kelas_perkuliahan.mata_kuliah_id', '=', 'mata_kuliah.id')
                ->where('krs_items.krs_id', $krs->id)
                ->select('krs_items.*', 'kelas_perkuliahan.kode_kelas', 'mata_kuliah.nama_mk', 'mata_kuliah.sks', 'mata_kuliah.semester')
                ->get();
                
            $totalSks = $krsItems->sum('sks');
        }

        // Daftar kelas yang tersedia untuk diambil
        $tersedia = KelasPerkuliahan::with('mataKuliah')
            ->whereNotIn('id', collect($krsItems)->pluck('kelas_perkuliahan_id')->toArray())
            ->get();

        return view('mahasiswa.krs.index', compact('krs', 'krsItems', 'tersedia', 'totalSks', 'mahasiswa'));
    }

    public function tambahKelas(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas_perkuliahan,id'
        ]);

        $krs = Krs::findOrFail($id);
        
        if ($krs->mahasiswa_id !== auth()->id()) {
            return abort(403);
        }

        if ($krs->status !== 'draft' && $krs->status !== 'ditolak') {
            return back()->with('error', 'KRS sudah diajukan atau disetujui, tidak dapat diubah.');
        }

        // Cek apakah sudah ditambahkan
        $exists = DB::table('krs_items')
            ->where('krs_id', $krs->id)
            ->where('kelas_perkuliahan_id', $request->kelas_id)
            ->exists();

        if (!$exists) {
            DB::table('krs_items')->insert([
                'krs_id' => $krs->id,
                'kelas_perkuliahan_id' => $request->kelas_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Mata kuliah berhasil ditambahkan ke KRS.');
    }

    public function hapusKelas(Request $request, $id, $kelas_id)
    {
        $krs = Krs::findOrFail($id);
        
        if ($krs->mahasiswa_id !== auth()->id()) {
            return abort(403);
        }

        if ($krs->status !== 'draft' && $krs->status !== 'ditolak') {
            return back()->with('error', 'KRS sudah diajukan atau disetujui, tidak dapat diubah.');
        }

        DB::table('krs_items')
            ->where('krs_id', $krs->id)
            ->where('kelas_perkuliahan_id', $kelas_id)
            ->delete();

        return back()->with('success', 'Mata kuliah dihapus dari KRS.');
    }

    public function ajukan(Request $request, $id)
    {
        $krs = Krs::findOrFail($id);
        
        if ($krs->mahasiswa_id !== auth()->id()) {
            return abort(403);
        }

        $itemsCount = DB::table('krs_items')->where('krs_id', $krs->id)->count();
        if ($itemsCount === 0) {
            return back()->with('error', 'Pilih minimal satu mata kuliah sebelum mengajukan KRS.');
        }

        $krs->update([
            'status' => 'diajukan',
        ]);

        return back()->with('success', 'KRS berhasil diajukan ke Dosen PA. Silakan tunggu persetujuan.');
    }
}
