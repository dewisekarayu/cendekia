<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\NilaiAkhir;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class NilaiAkhirController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $grade = $request->query('grade');

        $nilaiAkhir = NilaiAkhir::with(['kelasPerkuliahan.mataKuliah', 'mahasiswa'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('mahasiswa', fn ($subQuery) => $subQuery->where('name', 'like', "%{$search}%")->orWhere('nip_nim', 'like', "%{$search}%"))
                    ->orWhereHas('kelasPerkuliahan.mataKuliah', fn ($subQuery) => $subQuery->where('nama_mk', 'like', "%{$search}%"));
            })
            ->when($grade, fn ($query) => $query->where('grade', $grade))
            ->orderByDesc('nilai_akhir')
            ->paginate(12)
            ->withQueryString();

        return view('admin.nilai-akhir.index', compact('nilaiAkhir', 'search', 'grade'));
    }

    public function create()
    {
        return view('admin.nilai-akhir.create', [
            'nilai' => new NilaiAkhir(),
            'kelasList' => $this->kelasOptions(),
            'mahasiswaList' => User::role('mahasiswa')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['nilai_akhir'] = $this->hitungNilaiAkhir($data);
        $data['grade'] = $this->gradeFor($data['nilai_akhir']);

        NilaiAkhir::create($data);

        return redirect()->route('admin.nilai-akhir.index')->with('success', 'Nilai akhir berhasil dibuat.');
    }

    public function edit(NilaiAkhir $nilaiAkhir)
    {
        return view('admin.nilai-akhir.edit', [
            'nilai' => $nilaiAkhir,
            'kelasList' => $this->kelasOptions(),
            'mahasiswaList' => User::role('mahasiswa')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, NilaiAkhir $nilaiAkhir)
    {
        $data = $this->validated($request, $nilaiAkhir->id);
        $data['nilai_akhir'] = $this->hitungNilaiAkhir($data);
        $data['grade'] = $this->gradeFor($data['nilai_akhir']);
        $nilaiAkhir->update($data);

        return redirect()->route('admin.nilai-akhir.index')->with('success', 'Nilai akhir berhasil diperbarui.');
    }

    public function destroy(NilaiAkhir $nilaiAkhir)
    {
        $nilaiAkhir->delete();

        return redirect()->route('admin.nilai-akhir.index')->with('success', 'Nilai akhir berhasil dihapus.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'kelas_perkuliahan_id' => ['required', 'exists:kelas_perkuliahan,id'],
            'mahasiswa_id' => [
                'required',
                'exists:users,id',
                Rule::unique('nilai_akhir')->where(fn ($query) => $query
                    ->where('kelas_perkuliahan_id', $request->kelas_perkuliahan_id)
                    ->where('mahasiswa_id', $request->mahasiswa_id))->ignore($ignoreId),
            ],
            'nilai_kehadiran' => ['required', 'numeric', 'between:0,100'],
            'nilai_tugas' => ['required', 'numeric', 'between:0,100'],
            'nilai_quiz' => ['required', 'numeric', 'between:0,100'],
            'nilai_project' => ['required', 'numeric', 'between:0,100'],
            'nilai_uts' => ['required', 'numeric', 'between:0,100'],
            'nilai_uas' => ['required', 'numeric', 'between:0,100'],
            'catatan' => ['nullable', 'string'],
        ]);
    }

    private function hitungNilaiAkhir(array $data): float
    {