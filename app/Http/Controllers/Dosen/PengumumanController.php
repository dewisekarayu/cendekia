<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Tampilkan semua pengumuman dari semua kelas yang diampu dosen
     */
    public function index(Request $request)
    {
        // SINKRONISASI: Mengubah kelasDiajar() menjadi kelasDiampu() sesuai isi model User
        $kelasIds = $request->user()->kelasDiampu()->pluck('id');

        $pengumuman = Pengumuman::with(['pembuat', 'kelasPerkuliahan'])
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->latest()
            ->paginate(6);

        // SINKRONISASI: Mengubah kelasDiajar() menjadi kelasDiampu()
        $kelasList = $request->user()->kelasDiampu()->orderBy('kode_kelas')->get();

        return view('dosen.kelas-pengumuman', compact('pengumuman', 'kelasList'));
    }

    /**
     * Simpan pengumuman baru untuk kelas tertentu.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'                => ['required', 'string', 'max:255'],
            'isi'                  => ['required', 'string'],
            'kelas_perkuliahan_id' => ['required', 'exists:kelas_perkuliahan,id'],
        ]);

        // SINKRONISASI: Mengubah kelasDiajar() menjadi kelasDiampu()
        $isOwner = $request->user()->kelasDiampu()->where('id', $validated['kelas_perkuliahan_id'])->exists();
        abort_unless($isOwner, 403, 'Anda tidak mengajar kelas ini.');

        Pengumuman::create([
            'kelas_perkuliahan_id' => $validated['kelas_perkuliahan_id'],
            'dibuat_oleh'          => $request->user()->id,
            'judul'                => $validated['judul'],
            'isi'                  => $validated['isi'],
            'untuk_semua'          => $request->boolean('untuk_semua'),
        ]);

        // Dialihkan kembali ke halaman indeks utama pengumuman dosen
        return redirect()->route('dosen.kelas-pengumuman.index')
            ->with('success', 'Pengumuman kelas berhasil diterbitkan.');
    }

    /**
     * Perbarui pengumuman kelas.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        // Memastikan pengumuman tersebut memang milik dosen yang sedang aktif login
        abort_unless($pengumuman->kelasPerkuliahan->dosen_id === $request->user()->id, 403, 'Anda tidak memiliki akses untuk mengedit pengumuman ini.');

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi'   => ['required', 'string'],
        ]);

        $pengumuman->update([
            'judul'       => $validated['judul'],
            'isi'         => $validated['isi'],
            'untuk_semua' => $request->boolean('untuk_semua'),
        ]);

        return redirect()->route('dosen.kelas-pengumuman.index')
            ->with('success', 'Pengumuman kelas berhasil diperbarui.');
    }

    /**
     * Hapus pengumuman kelas.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('dosen.kelas-pengumuman.index')
            ->with('success', 'Pengumuman kelas berhasil dihapus.');
    }
}