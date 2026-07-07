<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = User::role('mahasiswa')
            ->with('programStudi')
            ->latest()
            ->paginate(10);
        $totalMahasiswa = User::role('mahasiswa')->count();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'totalMahasiswa'));
    }

    public function create()
    {
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.mahasiswa.create', compact('programStudiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ]);

        $mahasiswa = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make('mahasiswa123'),
            'program_studi_id' => $validated['program_studi_id'] ?? null,
        ]);

        $mahasiswa->assignRole('mahasiswa');

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil ditambahkan. Password awal: mahasiswa123');
    }

    public function destroy(User $mahasiswa)
    {
        abort_unless($mahasiswa->hasRole('mahasiswa'), 404);

        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
