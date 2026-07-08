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
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $prodiFilter = $request->input('prodi');

        $query = User::role('mahasiswa')
            ->with('programStudi')
            ->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nip_nim', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($prodiFilter) {
            $query->where('program_studi_id', $prodiFilter);
        }

        $mahasiswa = $query->paginate(15)->withQueryString();
        $totalMahasiswa = User::role('mahasiswa')->count();
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.mahasiswa.index', compact('mahasiswa', 'totalMahasiswa', 'search', 'prodiFilter', 'programStudiList'));
    }

    public function create()
    {
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.mahasiswa.create', compact('programStudiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'             => ['required', 'string', 'max:255'],
            'nim'              => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ], [
            'nim.unique' => 'NIM sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
        ]);

        $mahasiswa = User::create([
            'name'             => $validated['nama'],
            'nip_nim'          => $validated['nim'],
            'email'            => $validated['email'],
            'password'         => Hash::make('mahasiswa123'),
            'program_studi_id' => $validated['program_studi_id'] ?? null,
        ]);

        $mahasiswa->assignRole('mahasiswa');

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Mahasiswa {$mahasiswa->name} berhasil ditambahkan. Password awal: mahasiswa123");
    }

    public function edit(User $mahasiswa)
    {
        abort_unless($mahasiswa->hasRole('mahasiswa'), 404);

        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.mahasiswa.edit', compact('mahasiswa', 'programStudiList'));
    }

    public function update(Request $request, User $mahasiswa)
    {
        abort_unless($mahasiswa->hasRole('mahasiswa'), 404);

        $validated = $request->validate([
            'nama'             => ['required', 'string', 'max:255'],
            'nim'              => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')->ignore($mahasiswa->id)],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($mahasiswa->id)],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
        ], [
            'nim.unique' => 'NIM sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
        ]);

        $mahasiswa->update([
            'name'             => $validated['nama'],
            'nip_nim'          => $validated['nim'],
            'email'            => $validated['email'],
            'program_studi_id' => $validated['program_studi_id'] ?? null,
        ]);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Data mahasiswa {$mahasiswa->name} berhasil diperbarui.");
    }

    public function destroy(User $mahasiswa)
    {
        abort_unless($mahasiswa->hasRole('mahasiswa'), 404);

        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}