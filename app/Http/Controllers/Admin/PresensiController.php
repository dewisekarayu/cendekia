<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Presensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PresensiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $status = $request->query('status');

        $presensi = Presensi::with(['kelasPerkuliahan.mataKuliah', 'mahasiswa', 'pencatat'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('mahasiswa', fn ($subQuery) => $subQuery->where('name', 'like', "%{$search}%")->orWhere('nip_nim', 'like', "%{$search}%"))
                    ->orWhereHas('kelasPerkuliahan.mataKuliah', fn ($subQuery) => $subQuery->where('nama_mk', 'like', "%{$search}%"));
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest('tanggal')
            ->paginate(12)
            ->withQueryString();

        return view('admin.presensi.index', compact('presensi', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.presensi.create', [
            'presensiItem' => new Presensi(['tanggal' => now(), 'status' => 'hadir']),
            'kelasList' => $this->kelasOptions(),
            'mahasiswaList' => User::role('mahasiswa')->orderBy('name')->get(),
            'dosenList' => User::role('dosen')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Presensi::create($this->validated($request));

        return redirect()->route('admin.presensi.index')->with('success', 'Presensi berhasil dicatat.');
    }

    public function edit(Presensi $presensi)
    {
        return view('admin.presensi.edit', [
            'presensiItem' => $presensi,
            'kelasList' => $this->kelasOptions(),
            'mahasiswaList' => User::role('mahasiswa')->orderBy('name')->get(),
            'dosenList' => User::role('dosen')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Presensi $presensi)
    {
        $presensi->update($this->validated($request, $presensi->id));

        return redirect()->route('admin.presensi.index')->with('success', 'Presensi berhasil diperbarui.');
    }

    public function destroy(Presensi $presensi)
    {
        $presensi->delete();

        return redirect()->route('admin.presensi.index')->with('success', 'Presensi berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kelas_perkuliahan_id' => ['required', 'exists:kelas_perkuliahan,id'],
            'mahasiswa_id' => ['required', 'exists:users,id'],
            'pertemuan_ke' => [
                'required',
                'integer',
                'between:1,16',
                Rule::unique('presensi')->where(fn ($query) => $query
                    ->where('kelas_perkuliahan_id', $request->kelas_perkuliahan_id)
                    ->where('mahasiswa_id', $request->mahasiswa_id)
                    ->where('pertemuan_ke', $request->pertemuan_ke))->ignore($ignoreId),
            ],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:hadir,izin,sakit,alpha'],
            'catatan' => ['nullable', 'string'],
            'dicatat_oleh' => ['nullable', 'exists:users,id'],
        ]);
    }

    private function kelasOptions()
    {
        return KelasPerkuliahan::with(['mataKuliah', 'dosen'])->orderBy('kode_kelas')->get();
    }
}