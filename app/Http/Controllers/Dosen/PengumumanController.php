<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request, $kelas_perkuliahan_id)
    {
        KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($kelas_perkuliahan_id);

        $pengumuman = Pengumuman::with('pembuat')
            ->where('kelas_perkuliahan_id', $kelas_perkuliahan_id)
            ->latest()
            ->paginate(10);

        return view('dosen.kelas-pengumuman', compact('pengumuman', 'kelas_perkuliahan_id'));
    }

    public function store(Request $request, $kelas_perkuliahan_id)
    {
        KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($kelas_perkuliahan_id);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
        ]);

        Pengumuman::create([
            'kelas_perkuliahan_id' => $kelas_perkuliahan_id,
            'dibuat_oleh' => $request->user()->id,
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'untuk_semua' => false,
        ]);

        return redirect()->back()->with('success', 'Pengumuman kelas berhasil diterbitkan.');
    }

    public function destroy(Request $request, $id)
    {
        $pengumuman = Pengumuman::whereHas('kelasPerkuliahan', fn ($query) => $query->where('dosen_id', $request->user()->id))
            ->findOrFail($id);
        $pengumuman->delete();

        return redirect()->back()->with('success', 'Pengumuman kelas berhasil dihapus.');
    }
}
