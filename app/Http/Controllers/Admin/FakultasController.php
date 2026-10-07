<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index(Request $request)
    {
        // Load hierarchical data: Semester -> Fakultas -> Program Studi
        $semesters = \App\Models\Semester::with(['fakultas.programStudi'])->latest('tanggal_mulai')->get();
        return view('admin.fakultas.index', compact('semesters'));
    }

    public function create()
    {
        $semesters = \App\Models\Semester::latest()->get();
        return view('admin.fakultas.create', compact('semesters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_fakultas' => 'required|string|unique:fakultas,kode_fakultas',
            'nama_fakultas' => 'required|string|max:255',
            'semester_id'   => 'required|exists:semesters,id',
        ]);

        Fakultas::create($validated);
        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil ditambahkan');
    }

    public function show(Fakultas $fakultas)
    {
        return view('admin.fakultas.show', compact('fakultas'));
    }

    public function edit(Fakultas $fakultas)
    {
        $semesters = \App\Models\Semester::latest()->get();
        return view('admin.fakultas.edit', compact('fakultas', 'semesters'));
    }

    public function update(Request $request, Fakultas $fakultas)
    {
        $validated = $request->validate([
            'kode_fakultas' => 'required|string|unique:fakultas,kode_fakultas,' . $fakultas->id,
            'nama_fakultas' => 'required|string|max:255',
            'semester_id'   => 'required|exists:semesters,id',
        ]);

        $fakultas->update($validated);
        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil diperbarui');
    }

    public function destroy(Fakultas $fakultas)
    {
        $fakultas->delete();
        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil dihapus');
    }
}
