<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Tampilkan daftar pengumuman (paginated), lengkap dengan pembuatnya.
     */
    public function index()
    {
        $pengumuman = Pengumuman::with('pembuat')
            ->latest()
            ->paginate(6)
            ->withQueryString();

        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    /**
     * Simpan pengumuman baru.
     */
    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        Pengumuman::create([
            'dibuat_oleh'  => $request->user()->id,
            'judul'        => $validated['judul'],
            'isi'          => $validated['isi'],
            'untuk_semua'  => $request->boolean('untuk_semua', true),
        ]);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    /**
     * Perbarui pengumuman yang sudah ada.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $this->validatedData($request);

        $pengumuman->update([
            'judul'       => $validated['judul'],
            'isi'         => $validated['isi'],
            'untuk_semua' => $request->boolean('untuk_semua', true),
        ]);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Hapus pengumuman.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    /**
     * Aturan validasi bersama untuk store & update.
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'judul'       => ['required', 'string', 'max:255'],
            'isi'         => ['required', 'string'],
            'untuk_semua' => ['nullable', 'boolean'],
        ]);
    }
}