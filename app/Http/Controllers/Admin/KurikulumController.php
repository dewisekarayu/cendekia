<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        
        $kurikulumList = Kurikulum::with('programStudi')
            ->when($search, function($q) use ($search) {
                $q->where('nama_kurikulum', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
            
        if ($request->ajax()) {
            return view('admin.kurikulum.table', compact('kurikulumList'))->render();
        }

        return view('admin.kurikulum.index', compact('kurikulumList', 'search'));
    }

    public function create()
    {
        $programStudiList = ProgramStudi::all();
        return view('admin.kurikulum.create', compact('programStudiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kurikulum' => 'required|string|max:255',
            'tahun_mulai' => 'required|digits:4|integer|min:2000',
            'program_studi_id' => 'required|exists:program_studi,id',
            'is_active' => 'boolean'
        ]);

        Kurikulum::create($validated);
        return redirect()->route('admin.kurikulum.index')->with('success', 'Kurikulum berhasil ditambahkan');
    }

    public function show(Kurikulum $kurikulum)
    {
        $kurikulum->load('programStudi');
        return view('admin.kurikulum.show', compact('kurikulum'));
    }

    public function edit(Kurikulum $kurikulum)
    {
        $programStudiList = ProgramStudi::all();
        return view('admin.kurikulum.edit', compact('kurikulum', 'programStudiList'));
    }

    public function update(Request $request, Kurikulum $kurikulum)
    {
        $validated = $request->validate([
            'nama_kurikulum' => 'required|string|max:255',
            'tahun_mulai' => 'required|digits:4|integer|min:2000',
            'program_studi_id' => 'required|exists:program_studi,id',
            'is_active' => 'boolean'
        ]);
        
        if(!$request->has('is_active')) {
            $validated['is_active'] = false;
        }

        $kurikulum->update($validated);
        return redirect()->route('admin.kurikulum.index')->with('success', 'Kurikulum berhasil diperbarui');
    }

    public function destroy(Kurikulum $kurikulum)
    {
        $kurikulum->delete();
        return redirect()->route('admin.kurikulum.index')->with('success', 'Kurikulum berhasil dihapus');
    }
}
