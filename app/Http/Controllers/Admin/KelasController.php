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
        $programStudiList = \App\Models\ProgramStudi::all();
        
        // Tahun akademik pilihan (misal: 3 tahun ke depan)
        $tahunAkademikOptions = [];
        $currentYear = date('Y');
        for ($i = 0; $i < 3; $i++) {
            $year1 = $currentYear + $i;
            $year2 = $year1 + 1;
            $tahunAkademikOptions[] = "{$year1}/{$year2}";
        }

        return view('admin.kelas.create', compact('mataKuliahList', 'dosenList', 'semesterList', 'programStudiList', 'tahunAkademikOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:users,id',
            'program_studi_id' => 'required|exists:program_studi,id',
            'semester_id' => 'required|exists:semesters,id',
            'kode_kelas' => 'required|string|max:10',
            'tahun_akademik' => 'required|string|max:20',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'nullable|string|max:100',
            'kuota_mahasiswa' => 'required|integer|min:1|max:200',
            'dosen_pengampu' => 'nullable|array',
            'dosen_pengampu.*' => 'exists:users,id',
        ]);

        $validated['is_active'] = true;
        $validated['status_kelas'] = 'aktif';
        
        // Validasi bentrok jadwal dosen
        $kelas = new KelasPerkuliahan($validated);
        if ($kelas->hasBentrokJadwalDosen()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dosen memiliki jadwal yang bentrok pada hari dan waktu yang sama.');
        }
        
        // Validasi bentrok ruangan
        if ($kelas->hasBentrokRuangan()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ruangan sudah digunakan pada hari dan waktu yang sama.');
        }

        KelasPerkuliahan::create($validated);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dibuat.');
    }

    public function edit(KelasPerkuliahan $kelas)
    {
        $mataKuliahList = MataKuliah::with('programStudi')->get();
        $dosenList = User::role('dosen')->get();
        $semesterList = Semester::all();
        $programStudiList = \App\Models\ProgramStudi::all();
        
        // Tahun akademik pilihan
        $tahunAkademikOptions = [];
        $currentYear = date('Y');
        for ($i = 0; $i < 3; $i++) {
            $year1 = $currentYear + $i;
            $year2 = $year1 + 1;
            $tahunAkademikOptions[] = "{$year1}/{$year2}";
        }

        return view('admin.kelas.edit', compact('kelas', 'mataKuliahList', 'dosenList', 'semesterList', 'programStudiList', 'tahunAkademikOptions'));
    }

    public function update(Request $request, KelasPerkuliahan $kelas)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_id' => 'required|exists:users,id',
            'program_studi_id' => 'required|exists:program_studi,id',
            'semester_id' => 'required|exists:semesters,id',
            'kode_kelas' => 'required|string|max:10',
            'tahun_akademik' => 'required|string|max:20',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
            'ruangan' => 'nullable|string|max:100',
            'kuota_mahasiswa' => 'required|integer|min:1|max:200',
            'dosen_pengampu' => 'nullable|array',
            'dosen_pengampu.*' => 'exists:users,id',
            'status_kelas' => 'required|in:aktif,nonaktif,selesai,draft',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Validasi bentrok jadwal dosen (kecuali untuk diri sendiri)
        $kelas->fill($validated);
        if ($kelas->hasBentrokJadwalDosen()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dosen memiliki jadwal yang bentrok pada hari dan waktu yang sama.');
        }
        
        // Validasi bentrok ruangan
        if ($kelas->hasBentrokRuangan()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ruangan sudah digunakan pada hari dan waktu yang sama.');
        }

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