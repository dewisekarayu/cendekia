<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AktivitasPengguna;
use App\Models\KelasPerkuliahan;
use App\Models\User;
use Illuminate\Http\Request;

class AktivitasPenggunaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $aksi = $request->query('aksi');

        $aktivitas = AktivitasPengguna::with(['user', 'kelasPerkuliahan.mataKuliah'])
            ->when($search, function ($query) use ($search) {
                $query->where('aksi', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($subQuery) => $subQuery->where('name', 'like', "%{$search}%")->orWhere('nip_nim', 'like', "%{$search}%"));
            })
            ->when($aksi, fn ($query) => $query->where('aksi', $aksi))
            ->latest('terjadi_pada')
            ->paginate(15)
            ->withQueryString();

        return view('admin.aktivitas.index', compact('aktivitas', 'search', 'aksi'));
    }

    public function create()
    {
        return view('admin.aktivitas.create', [
            'aktivitasItem' => new AktivitasPengguna(['terjadi_pada' => now()]),
            'userList' => User::orderBy('name')->get(),
            'kelasList' => $this->kelasOptions(),
        ]);
    }

    public function store(Request $request)
    {
        AktivitasPengguna::create($this->validated($request));

        return redirect()->route('admin.aktivitas.index')->with('success', 'Aktivitas berhasil dicatat.');
    }

    public function edit(AktivitasPengguna $aktivitas)
    {
        return view('admin.aktivitas.edit', [
            'aktivitasItem' => $aktivitas,
            'userList' => User::orderBy('name')->get(),
            'kelasList' => $this->kelasOptions(),
        ]);
    }

    public function update(Request $request, AktivitasPengguna $aktivitas)
    {
        $aktivitas->update($this->validated($request));

        return redirect()->route('admin.aktivitas.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy(AktivitasPengguna $aktivitas)
    {
        $aktivitas->delete();

        return redirect()->route('admin.aktivitas.index')->with('success', 'Aktivitas berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'kelas_perkuliahan_id' => ['nullable', 'exists:kelas_perkuliahan,id'],
            'aksi' => ['required', 'string', 'max:80'],
            'deskripsi' => ['nullable', 'string'],
            'ip_address' => ['nullable', 'ip'],
            'user_agent' => ['nullable', 'string', 'max:255'],
            'terjadi_pada' => ['required', 'date'],
        ]);
    }

    private function kelasOptions()
    {
        return KelasPerkuliahan::with(['mataKuliah', 'dosen'])->orderBy('kode_kelas')->get();
    }
}