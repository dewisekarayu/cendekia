<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;

use App\Models\KelasPerkuliahan;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{

    // public function index(Request $request, $kelas_perkuliahan_id)
    // {
    //     KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($kelas_perkuliahan_id);

    //     $pengumuman = Pengumuman::with('pembuat')
    //         ->where('kelas_perkuliahan_id', $kelas_perkuliahan_id)
    //         ->latest()
    //         ->paginate(10);

    // Tampilkan semua pengumuman dari semua kelas yang diampu dosen
    public function index(Request $request)
    {
        $kelasIds = $request->user()->kelasDiajar()->pluck('id');

        $pengumuman = Pengumuman::with(['pembuat', 'kelasPerkuliahan'])
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->latest()
            ->paginate(6);

        $kelasList = $request->user()->kelasDiajar()->orderBy('kode_kelas')->get();

        return view('dosen.kelas-pengumuman', compact('pengumuman', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'kelas_perkuliahan_id' => ['required', 'exists:kelas_perkuliahan,id'],
        ]);

        // Pastikan kelas yang dipilih memang milik dosen ini
        $isOwner = $request->user()->kelasDiajar()->where('id', $validated['kelas_perkuliahan_id'])->exists();
        abort_unless($isOwner, 403, 'Anda tidak mengajar kelas ini.');


        Pengumuman::create([
            'kelas_perkuliahan_id' => $validated['kelas_perkuliahan_id'],
            'dibuat_oleh' => $request->user()->id,
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'untuk_semua' => $request->boolean('untuk_semua'),
        ]);

        return redirect()->route('dosen.kelas-pengumuman.index')
            ->with('success', 'Pengumuman kelas berhasil diterbitkan.');
    }


    // public function store(Request $request, $kelas_perkuliahan_id)
    // {
    //     KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($kelas_perkuliahan_id);


    public function update(Request $request, Pengumuman $pengumuman)
    {
        // Pastikan pengumuman milik dosen yang sedang login
        abort_unless($pengumuman->kelasPerkuliahan->dosen_id === $request->user()->id, 403, 'Anda tidak memiliki akses untuk mengedit pengumuman ini.');

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
        ]);

        $pengumuman->update([
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'untuk_semua' => $request->boolean('untuk_semua'),
        ]);

        return redirect()->route('dosen.kelas-pengumuman.index')
            ->with('success', 'Pengumuman kelas berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('dosen.kelas-pengumuman.index')
            ->with('success', 'Pengumuman kelas berhasil dihapus.');
    }
}
