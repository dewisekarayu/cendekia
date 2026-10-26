<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    /**
     * Tampilkan daftar pengumuman (paginated), lengkap dengan pembuatnya.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', $request->input('show', 10));
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        $pengumuman = Pengumuman::with('pembuat')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.pengumuman.index', compact('pengumuman', 'perPage'));
    }

    /**
     * Simpan pengumuman baru.
     */
    public function store(Request $request)
    {
        $validated = $this->validatedData($request);

        $pengumuman = Pengumuman::create([
            'dibuat_oleh'  => $request->user()->id,
            'judul'        => $validated['judul'],
            'isi'          => $validated['isi'],
            'untuk_semua'  => true,
            'penerima'     => $validated['penerima'],
        ]);

        // Kirim notifikasi ke Dosen & Mahasiswa agar muncul di notifikasi atas (navbar bell)
        NotificationService::notifyPengumumanBaru($pengumuman, $request->user());

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
            'untuk_semua' => true,
            'penerima'    => $validated['penerima'],
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
     * Hapus beberapa pengumuman yang dipilih dari halaman manajemen.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'pengumuman_ids' => ['required', 'array', 'min:1'],
            'pengumuman_ids.*' => ['required', 'integer', 'distinct', 'exists:pengumuman,id'],
        ]);

        $deletedCount = Pengumuman::whereIn('id', $validated['pengumuman_ids'])->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', "{$deletedCount} pengumuman berhasil dihapus.");
    }

    /**
     * Aturan validasi bersama untuk store & update.
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'judul'       => ['required', 'string', 'max:255'],
            'isi'         => ['required', 'string'],
            'penerima'    => ['required', 'in:dosen,mahasiswa,semua'],
        ]);
    }
}