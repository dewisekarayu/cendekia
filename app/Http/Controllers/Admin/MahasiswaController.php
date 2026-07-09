<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $prodiFilter = $request->input('program_studi_id') ?? $request->input('prodi');
        $statusFilter = $request->input('status');

        $query = User::role('mahasiswa')->with('programStudi');

        // Filter Pencarian (Nama, NIM, atau Email)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter Program Studi
        if ($prodiFilter) {
            $query->where('program_studi_id', $prodiFilter);
        }

        // Filter Status
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // Penomoran Halaman (Pagination)
        $mahasiswa = $query->latest()->paginate(10)->withQueryString();

        // FITUR AJAX: Jika request meminta potongan tabel saja (live search/filter)
        if ($request->has('ajax')) {
            return view('admin.mahasiswa.table', compact('mahasiswa'))->render();
        }

        // Data Statistik Utama untuk Pencatatan Kartu (Hanya dihitung saat reload halaman penuh)
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalAktif = User::role('mahasiswa')->where('status', 'aktif')->count();
        $totalCuti = User::role('mahasiswa')->where('status', 'cuti')->count();
        $totalNonAktif = User::role('mahasiswa')->where('status', 'non_aktif')->count();

        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.mahasiswa.index', compact(
            'mahasiswa',
            'totalMahasiswa',
            'totalAktif',
            'totalCuti',
            'totalNonAktif',
            'programStudiList',
            'search',
            'prodiFilter',
            'statusFilter'
        ));
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
            'status'           => ['required', Rule::in(['aktif', 'cuti', 'non_aktif'])],
            'telepon'          => ['nullable', 'string', 'max:20'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ], [
            'nim.unique' => 'NIM sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        ([
            'nim.unique' => 'NIM sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
        ]);

        $mahasiswa = User::create([
            'name'             => $validated['nama'],
            'nip_nim'          => $validated['nim'],
            'email'            => $validated['email'],
            'password'         => Hash::make('mahasiswa123'),
            'program_studi_id' => $validated['program_studi_id'] ?? null,
            'status'           => $validated['status'],
            'telepon'          => $validated['telepon'] ?? null,
            'foto'             => $fotoPath,
        ]);

        $mahasiswa->assignRole('mahasiswa');

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Mahasiswa {$mahasiswa->name} berhasil ditambahkan. Password awal: mahasiswa123");
    }

    public function edit($id)
    {
        $mahasiswaMember = User::findOrFail($id);
        abort_unless($mahasiswaMember->hasRole('mahasiswa'), 404);

        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.mahasiswa.edit', compact('mahasiswaMember', 'programStudiList'));
    }

    public function update(Request $request, $id)
    {
        $mahasiswaMember = User::findOrFail($id);
        abort_unless($mahasiswaMember->hasRole('mahasiswa'), 404);

        $validated = $request->validate([
            'nama'             => ['required', 'string', 'max:255'],
            'nim'              => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')->ignore($mahasiswaMember->id)],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($mahasiswaMember->id)],
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
            'status'           => ['required', Rule::in(['aktif', 'cuti', 'non_aktif'])],
            'telepon'          => ['nullable', 'string', 'max:20'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ], [
            'nim.unique' => 'NIM sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
        ]);

        $fotoPath = $mahasiswaMember->foto;
        $fotoPath = $mahasiswa->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        $mahasiswa->update([
            'name'             => $validated['nama'],
            'nip_nim'          => $validated['nim'],
            'email'            => $validated['email'],
            'program_studi_id' => $validated['program_studi_id'] ?? null,
            'status'           => $validated['status'],
            'telepon'          => $validated['telepon'] ?? null,
            'foto'             => $fotoPath,
        ]);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Data mahasiswa {$mahasiswaMember->name} berhasil diperbarui.");
            ->with('success', "Data mahasiswa {$mahasiswa->name} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $mahasiswa = User::findOrFail($id);
        abort_unless($mahasiswa->hasRole('mahasiswa'), 404);

        if ($mahasiswa->foto) {
            Storage::disk('public')->delete($mahasiswa->foto);
        }

        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}