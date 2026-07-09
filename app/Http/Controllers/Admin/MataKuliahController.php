<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));

        $query = MataKuliah::with('programStudi');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mk', 'like', "%{$search}%")
                  ->orWhere('kode_mk', 'like', "%{$search}%");
            });
        }

        $mataKuliah = $query->latest()->paginate(10)->withQueryString();

        if ($request->has('ajax')) {
            return view('admin.mata-kuliah.table', compact('mataKuliah'))->render();
        }

        return view('admin.mata-kuliah.index', compact('mataKuliah'));
    }

    public function create()
    {
        $prodiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.mata-kuliah.create', compact('prodiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studi,id',
            'kode_mk'          => 'required|string|max:20|unique:mata_kuliah,kode_mk',
            'nama_mk'          => 'required|string|max:255',
            'sks'              => 'required|integer|min:1|max:6',
            'semester_ke'      => 'required|integer|min:1|max:8',
            'deskripsi'        => 'nullable|string',
        ]);

        MataKuliah::create($validated);

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $prodiList = ProgramStudi::all();

        return view('admin.mata-kuliah.edit', [
            'mk' => $mataKuliah,
            'prodiList' => $prodiList,
        ]);
    }

    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);

        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studi,id',
            'kode_mk'          => 'required|string|max:20|unique:mata_kuliah,kode_mk,' . $mataKuliah->id,
            'nama_mk'          => 'required|string|max:255',
            'sks'              => 'required|integer|min:1|max:6',
            'semester_ke'      => 'required|integer|min:1|max:8',
            'deskripsi'        => 'nullable|string',
        ]);

        $mataKuliah->update($validated);

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->delete();

        return redirect()->route('admin.mata-kuliah.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }
}