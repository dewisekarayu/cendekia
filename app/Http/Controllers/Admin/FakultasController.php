<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Http\Request;

class FakultasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $fakultasList = Fakultas::when($search, function($q) use ($search) {
                $q->where('kode_fakultas', 'like', "%{$search}%")
                  ->orWhere('nama_fakultas', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
            
        if ($request->ajax()) {
            return view('admin.fakultas.table', compact('fakultasList'))->render();
        }

        return view('admin.fakultas.index', compact('fakultasList', 'search'));
    }

    public function create()
    {
        return view('admin.fakultas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_fakultas' => 'required|string|unique:fakultas,kode_fakultas',
            'nama_fakultas' => 'required|string|max:255',
        ]);

        Fakultas::create($validated);
        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil ditambahkan');
    }

    public function show(Fakultas $fakultas)
    {
        $fakulta = $fakultas; // matching view variable
        return view('admin.fakultas.show', compact('fakulta'));
    }

    public function edit(Fakultas $fakultas)
    {
        return view('admin.fakultas.edit', compact('fakultas'));
    }

    public function update(Request $request, Fakultas $fakulta)
    {
        $validated = $request->validate([
            'kode_fakultas' => 'required|string|unique:fakultas,kode_fakultas,' . $fakulta->id,
            'nama_fakultas' => 'required|string|max:255',
        ]);

        $fakulta->update($validated);
        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil diperbarui');
    }

    public function destroy(Fakultas $fakulta)
    {
        $fakulta->delete();
        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil dihapus');
    }
}
