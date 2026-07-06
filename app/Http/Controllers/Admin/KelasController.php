<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = KelasPerkuliahan::with(['mataKuliah.programStudi', 'dosen', 'semester', 'mahasiswa'])
            ->latest()
            ->get();

        return view('admin.kelas.index', compact('kelasList'));
    }

    public function create()
    {
        $mataKuliahList = MataKuliah::with('programStudi')->get();
        $dosenList = User::role('dosen')->get();
        $semesterList = Semester::all();

        return view('admin.kelas.create', compact('mataKuliahList', 'dosenList', 'semesterList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:users,id',
            'semester_id' => 'required|exists:semesters,id',
            'kode_kelas' => 'required|string|max:10',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'nullable|string|max:100',
        ]);

        $validated['is_active'] = true;

        KelasPerkuliahan::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dibuat.');
    }

    public function edit(KelasPerkuliahan $kelas)
    {
        $mataKuliahList = MataKuliah::with('programStudi')->get();
        $dosenList = User::role('dosen')->get();
        $semesterList = Semester::all();

        return view('admin.kelas.edit', compact('kelas', 'mataKuliahList', 'dosenList', 'semesterList'));
    }

    public function update(Request $request, KelasPerkuliahan $kelas)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:users,id',
            'semester_id' => 'required|exists:semesters,id',
            'kode_kelas' => 'required|string|max:10',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $kelas->update($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(KelasPerkuliahan $kelas)
    {
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}