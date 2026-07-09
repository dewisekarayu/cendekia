<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class ProgramStudiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = ProgramStudi::query();

        // Fitur Pencarian Aktif (Nama Prodi atau Kode Prodi)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_prodi', 'like', "%{$search}%")
                  ->orWhere('kode_prodi', 'like', "%{$search}%");
            });
        }

        $prodiList = $query->latest()->paginate(10)->withQueryString();

        // KUNCI LIVE SEARCH AJAX
        if ($request->has('ajax')) {
            return view('admin.program-studi.table', compact('prodiList'))->render();
        }

        return view('admin.program-studi.index', compact('prodiList', 'search'));
    }

    public function create()
    {
        return view('admin.program-studi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_prodi' => 'required|string|max:20|unique:program_studi,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
            'jenjang'    => 'required|in:D3,D4,S1,S2',
            'akreditasi' => 'required|in:Unggul,A,B,Baik',
            'status'     => 'required|in:0,1',
        ]);

        ProgramStudi::create($validated);

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $prodi = ProgramStudi::findOrFail($id);
        return view('admin.program-studi.edit', compact('prodi'));
    }

    public function update(Request $request, $id)
    {
        $prodi = ProgramStudi::findOrFail($id);

        $validated = $request->validate([
            'kode_prodi' => 'required|string|max:20|unique:program_studi,kode_prodi,' . $prodi->id,
            'nama_prodi' => 'required|string|max:255',
            'jenjang'    => 'required|in:D3,D4,S1,S2',
            'akreditasi' => 'required|in:Unggul,A,B,Baik',
            'status'     => 'required|in:0,1',
        ]);

        $prodi->update($validated);

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $prodi = ProgramStudi::findOrFail($id);
        $prodi->delete();

        return redirect()->route('admin.program-studi.index')
            ->with('success', 'Program studi berhasil dihapus.');
    }
}