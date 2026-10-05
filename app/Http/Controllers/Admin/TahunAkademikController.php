<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $query = Semester::query();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_semester', 'like', "%{$search}%")
                  ->orWhere('tahun_ajaran', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        
        $tahunAkademikList = $query->latest('tanggal_mulai')->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('admin.tahun-akademik.table', compact('tahunAkademikList'))->render();
        }

        return view('admin.tahun-akademik.index', compact('tahunAkademikList', 'search'));
    }

    public function create()
    {
        return view('admin.tahun-akademik.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_semester'   => 'required|string|max:255',
            'jenis'           => 'required|in:Ganjil,Genap',
            'tahun_ajaran'    => 'required|string|max:20',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active'       => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Jika diset aktif, nonaktifkan yang lain
        if ($validated['is_active']) {
            Semester::where('is_active', true)->update(['is_active' => false]);
        }

        Semester::create($validated);

        return redirect()->route('admin.tahun-akademik.index')
            ->with('success', 'Tahun Akademik / Semester berhasil ditambahkan.');
    }

    public function show(Semester $tahun_akademik)
    {
        return view('admin.tahun-akademik.show', compact('tahun_akademik'));
    }

    public function edit(Semester $tahun_akademik)
    {
        return view('admin.tahun-akademik.edit', compact('tahun_akademik'));
    }

    public function update(Request $request, Semester $tahun_akademik)
    {
        $validated = $request->validate([
            'nama_semester'   => 'required|string|max:255',
            'jenis'           => 'required|in:Ganjil,Genap',
            'tahun_ajaran'    => 'required|string|max:20',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active'       => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Jika diset aktif, nonaktifkan yang lain
        if ($validated['is_active']) {
            Semester::where('id', '!=', $tahun_akademik->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $tahun_akademik->update($validated);

        return redirect()->route('admin.tahun-akademik.index')
            ->with('success', 'Tahun Akademik / Semester berhasil diperbarui.');
    }

    public function destroy(Semester $tahun_akademik)
    {
        // Pengecekan sebelum hapus bisa ditambahkan di sini, misalnya apakah ada kelas/KRS yang nyangkut
        $tahun_akademik->delete();

        return redirect()->route('admin.tahun-akademik.index')
            ->with('success', 'Tahun Akademik / Semester berhasil dihapus.');
    }
}
