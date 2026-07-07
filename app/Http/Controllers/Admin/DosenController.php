<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = User::role('dosen')
            ->with('programStudi')
            ->latest()
            ->paginate(10);

        return view('admin.dosen.index', compact('dosen'));
    }

    public function create()
    {
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.dosen.create', compact('programStudiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ]);

        $dosen = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make('dosen123'),
            'program_studi_id' => $validated['program_studi_id'] ?? null,
        ]);

        $dosen->assignRole('dosen');

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil ditambahkan. Password awal: dosen123');
    }

    public function destroy(User $dosen)
    {
        abort_unless($dosen->hasRole('dosen'), 404);

        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil dihapus.');
    }
}
