<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $prodiFilter = $request->input('program_studi_id');

        $query = User::role('dosen')->with('programStudi');

        // Fitur Pencarian Berjalan (Nama, NIDN/NIP, Email)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Program Studi Berjalan
        if ($prodiFilter) {
            $query->where('program_studi_id', $prodiFilter);
        }

        $perPage = (int) $request->input('per_page', $request->input('show', 10));
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $dosen = $query->latest()->paginate($perPage)->withQueryString();
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        // KUNCI PENCARIAN CEPAT: Jika request dikirim lewat AJAX ketikan, 
        // kembalikan potongan HTML tabelnya saja tanpa merender layout utama.
        if ($request->has('ajax')) {
            return view('admin.dosen.table', compact('dosen'))->render();
        }

        return view('admin.dosen.index', compact('dosen', 'programStudiList', 'search', 'prodiFilter'));
    }

    public function create()
    {
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();
        return view('admin.dosen.create', compact('programStudiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'nip_nim'          => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'status'           => ['required', 'in:aktif,non_aktif'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        $dosen = User::create([
            'name'             => $validated['name'],
            'nip_nim'          => $validated['nip_nim'],
            'email'            => $validated['email'],
            'password'         => Hash::make('dosen123'),
            'program_studi_id' => $validated['program_studi_id'] ?? null,
            'status'           => $validated['status'],
            'foto'             => $fotoPath,
        ]);

        $dosen->assignRole('dosen');

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil ditambahkan. Password awal: dosen123');
    }

    public function edit($id)
    {
        $dosenMember = User::findOrFail($id);
        abort_unless($dosenMember->hasRole('dosen'), 404);

        $prodiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.dosen.edit', compact('dosenMember', 'prodiList'));
    }

    public function update(Request $request, $id)
    {
        $dosenMember = User::findOrFail($id);
        abort_unless($dosenMember->hasRole('dosen'), 404);

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'nip_nim'          => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')->ignore($dosenMember->id)],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dosenMember->id)],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'status'           => ['required', 'in:aktif,non_aktif'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ]);

        $fotoPath = $dosenMember->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        $dosenMember->update([
            'name'             => $validated['name'],
            'nip_nim'          => $validated['nip_nim'],
            'email'            => $validated['email'],
            'program_studi_id' => $validated['program_studi_id'] ?? null,
            'status'           => $validated['status'],
            'foto'             => $fotoPath,
        ]);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dosen = User::findOrFail($id);
        abort_unless($dosen->hasRole('dosen'), 404);

        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil dihapus.');
    }

    /**
     * Import Data Dosen dari file CSV
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file_csv');
        $handle = fopen($file->getPathname(), "r");
        
        $header = true;
        $count = 0;

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($header) {
                $header = false;
                continue; // Skip header row
            }

            // Urutan CSV: Nama Lengkap, NIP, Email, ID Prodi
            if (isset($data[0], $data[1], $data[2], $data[3])) {
                $user = User::updateOrCreate(
                    ['nip_nim' => $data[1]], // NIP
                    [
                        'name' => $data[0],
                        'email' => $data[2],
                        'password' => \Illuminate\Support\Facades\Hash::make($data[1]), // Default password = NIP
                        'program_studi_id' => $data[3],
                        'email_verified_at' => now(),
                    ]
                );
                
                // Beri peran dosen
                if (!$user->hasRole('dosen')) {
                    $user->assignRole('dosen');
                }
                $count++;
            }
        }
        fclose($handle);

        return redirect()->route('admin.dosen.index')->with('success', "$count data dosen berhasil diimpor.");
    }
}