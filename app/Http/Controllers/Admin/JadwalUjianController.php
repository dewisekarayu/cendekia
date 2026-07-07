<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalUjian;
use App\Models\KelasPerkuliahan;
use Illuminate\Http\Request;

class JadwalUjianController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $jenis = $request->query('jenis_ujian');

        $jadwalUjian = JadwalUjian::with('kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.dosen')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('kelasPerkuliahan.mataKuliah', function ($subQuery) use ($search) {
                    $subQuery->where('nama_mk', 'like', "%{$search}%")
                        ->orWhere('kode_mk', 'like', "%{$search}%");
                })->orWhereHas('kelasPerkuliahan.dosen', function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%");
                });
            })
            ->when($jenis, fn ($query) => $query->where('jenis_ujian', $jenis))
            ->orderBy('tanggal_ujian')
            ->paginate(10)
            ->withQueryString();

        return view('admin.jadwal-ujian.index', compact('jadwalUjian', 'search', 'jenis'));
    }

    public function create()
    {
        return view('admin.jadwal-ujian.create', [
            'kelasList' => $this->kelasOptions(),
            'jadwal' => new JadwalUjian(['is_published' => true]),
        ]);
    }

    public function store(Request $request)
    {
        JadwalUjian::create($this->validated($request));

        return redirect()->route('admin.jadwal-ujian.index')->with('success', 'Jadwal ujian berhasil dibuat.');
    }

    public function edit(JadwalUjian $jadwalUjian)
    {
        return view('admin.jadwal-ujian.edit', [
            'kelasList' => $this->kelasOptions(),
            'jadwal' => $jadwalUjian,
        ]);
    }

    public function update(Request $request, JadwalUjian $jadwalUjian)
    {
        $jadwalUjian->update($this->validated($request));

        return redirect()->route('admin.jadwal-ujian.index')->with('success', 'Jadwal ujian berhasil diperbarui.');
    }

    public function destroy(JadwalUjian $jadwalUjian)
    {
        $jadwalUjian->delete();

        return redirect()->route('admin.jadwal-ujian.index')->with('success', 'Jadwal ujian berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'kelas_perkuliahan_id' => ['required', 'exists:kelas_perkuliahan,id'],
            'jenis_ujian' => ['required', 'in:UTS,UAS,Quiz,Remedial'],
            'tanggal_ujian' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'ruangan' => ['nullable', 'string', 'max:100'],
            'catatan' => ['nullable', 'string'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }

    private function kelasOptions()
    {
        return KelasPerkuliahan::with(['mataKuliah', 'dosen', 'semester'])->orderBy('kode_kelas')->get();
    }
}