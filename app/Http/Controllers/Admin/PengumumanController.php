<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumuman = Pengumuman::with('pembuat')
            ->latest()
            ->paginate(6);

        return view('admin.pengumuman.index', compact('pengumuman'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'isi' => ['required', 'string'],
            'untuk_semua' => ['nullable', 'boolean'],
        ]);

        Pengumuman::create([
            'dibuat_oleh' => $request->user()->id,
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'untuk_semua' => $request->boolean('untuk_semua', true),
        ]);

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
