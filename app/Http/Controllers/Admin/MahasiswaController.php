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
        // 1. Validasi mengambil key 'nim' sesuai dengan name="..." di file Blade kamu
        $validated = $request->validate([
            'nama'             => ['required', 'string', 'max:255'],
            'nim'              => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')], // validasi keunikan tetap di kolom nip_nim
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ]);

        // 2. Petakan input 'nim' dari form untuk disimpan ke kolom 'nip_nim' database
        $mahasiswa = User::create([
            'name'             => $validated['nama'],
            'nip_nim'          => $validated['nim'], // <-- Mengambil data dari $validated['nim']
            'email'            => $validated['email'],
            'password'         => Hash::make('mahasiswa123'),
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