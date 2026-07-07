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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ]);

        $dosen = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make('dosen123'),
            'program_studi_id' => $validated['program_studi_id'] ?? null,
        ]);

        $dosen->assignRole('dosen');

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil ditambahkan. Password awal: dosen123');
    }

    public function edit($id)
    {
        // Mengambil data dosen berdasarkan ID
        $dosenMember = User::findOrFail($id); 
        
        // Mengambil semua daftar prodi untuk pilihan select option di form edit
        // Disamakan nama variabelnya dengan create/index jika perlu, di sini memakai prodiList agar pas dengan Blade Edit
        $prodiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.dosen.edit', compact('dosenMember', 'prodiList'));
    }

    public function update(Request $request, $id)
    {
        $dosenMember = User::findOrFail($id);

        // Validasi data input dari form edit
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dosenMember->id)],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ]);

        // Update data ke database
        $dosenMember->update($validated);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(User $dosen)
    {
        abort_unless($dosen->hasRole('dosen'), 404);

        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil dihapus.');
    }
}