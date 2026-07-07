<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $tipe = $request->query('tipe');

        $notifikasi = Notifikasi::with(['user', 'kelasPerkuliahan.mataKuliah'])
            ->when($search, function ($query) use ($search) {
                $query->where('judul', 'like', "%{$search}%")
                    ->orWhere('pesan', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($subQuery) => $subQuery->where('name', 'like', "%{$search}%"));
            })
            ->when($tipe, fn ($query) => $query->where('tipe', $tipe))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.notifikasi.index', compact('notifikasi', 'search', 'tipe'));
    }

    public function create()
    {
        return view('admin.notifikasi.create', [
            'notifikasiItem' => new Notifikasi(['tipe' => 'informasi']),
            'userList' => User::orderBy('name')->get(),
            'kelasList' => $this->kelasOptions(),
        ]);
    }

    public function store(Request $request)
    {
        Notifikasi::create($this->validated($request));

        return redirect()->route('admin.notifikasi.index')->with('success', 'Notifikasi berhasil dibuat.');
    }

    public function edit(Notifikasi $notifikasi)
    {
        return view('admin.notifikasi.edit', [
            'notifikasiItem' => $notifikasi,
            'userList' => User::orderBy('name')->get(),
            'kelasList' => $this->kelasOptions(),
        ]);
    }

    public function update(Request $request, Notifikasi $notifikasi)
    {
        $notifikasi->update($this->validated($request));

        return redirect()->route('admin.notifikasi.index')->with('success', 'Notifikasi berhasil diperbarui.');
    }

    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();

        return redirect()->route('admin.notifikasi.index')->with('success', 'Notifikasi berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'kelas_perkuliahan_id' => ['nullable', 'exists:kelas_perkuliahan,id'],
            'judul' => ['required', 'string', 'max:150'],
            'pesan' => ['required', 'string'],
            'tipe' => ['required', 'in:informasi,tugas,ujian,nilai,presensi'],
            'url' => ['nullable', 'string', 'max:255'],
            'dibaca_pada' => ['nullable', 'date'],
        ]);
    }

    private function kelasOptions()
    {
        return KelasPerkuliahan::with(['mataKuliah', 'dosen'])->orderBy('kode_kelas')->get();
    }
}