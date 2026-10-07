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
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $prodiFilter = $request->input('program_studi_id');
        $dosenFilter = $request->input('dosen_id');
        $matkulFilter = $request->input('mata_kuliah_id');

        $query = KelasPerkuliahan::with(['mataKuliah.programStudi', 'dosen', 'semester', 'mahasiswa', 'jadwals']);

        if ($search !== '') {
            $query->where(function($q) use ($search) {
                $q->whereHas('mataKuliah', function ($sub) use ($search) {
                    $sub->where('nama_mk', 'like', "%{$search}%")
                      ->orWhere('kode_mk', 'like', "%{$search}%");
                })->orWhere('kode_kelas', 'like', "%{$search}%")
                  ->orWhereHas('dosen', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($prodiFilter) $query->where('program_studi_id', $prodiFilter);
        if ($dosenFilter) {
            $query->where(function($q) use ($dosenFilter) {
                $q->where('dosen_id', $dosenFilter);
            });
        }
        if ($matkulFilter) $query->where('mata_kuliah_id', $matkulFilter);

        // Data for filters
        $semesters = Semester::all();
        $prodis = \App\Models\ProgramStudi::all();
        $dosens = User::role('dosen')->get();
        $matkuls = MataKuliah::all();

        $allClasses = $query->latest()->get();
        $groupedByDosen = [];
        foreach ($allClasses as $k) {
            $d = $k->dosen;
            if ($d) {
                if (!isset($groupedByDosen[$d->id])) {
                    $groupedByDosen[$d->id] = [
                        'dosen' => $d,
                        'kelas' => []
                    ];
                }
                $groupedByDosen[$d->id]['kelas'][] = $k;
            }
        }

        return view('admin.kelas.index', compact('search', 'semesters', 'prodis', 'dosens', 'matkuls', 'groupedByDosen'));
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
            'kuota_mahasiswa' => 'required|integer|min:1|max:200',
            'jadwals' => 'required|array|min:1',
            'jadwals.*.hari' => 'required|string',
            'jadwals.*.jam_mulai' => 'required',
            'jadwals.*.jam_selesai' => 'required|after:jadwals.*.jam_mulai',
            'jadwals.*.ruangan' => 'nullable|string|max:100',
        ]);

        $validated['is_active'] = true;
        $validated['status_kelas'] = 'aktif';
        
        $jadwalCek = $validated['jadwals'];

        // Validasi bentrok jadwal dosen
        if (KelasPerkuliahan::cekBentrokDosenCustom($jadwalCek, $validated['dosen_id'], [])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dosen memiliki jadwal yang bentrok pada hari dan waktu yang sama.');
        }
        
        // Validasi bentrok ruangan
        if (KelasPerkuliahan::cekBentrokRuanganCustom($jadwalCek)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ruangan sudah digunakan pada hari dan waktu yang sama.');
        }

        $kelasData = $validated;
        unset($kelasData['jadwals']);
        
        $kelas = KelasPerkuliahan::create($kelasData);

        foreach ($jadwalCek as $j) {
            $kelas->jadwals()->create($j);
        }

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
            'kuota_mahasiswa' => 'required|integer|min:1|max:200',
            'status_kelas' => 'required|in:aktif,nonaktif,selesai,draft',
            'is_active' => 'nullable|boolean',
            'jadwals' => 'required|array|min:1',
            'jadwals.*.hari' => 'required|string',
            'jadwals.*.jam_mulai' => 'required',
            'jadwals.*.jam_selesai' => 'required|after:jadwals.*.jam_mulai',
            'jadwals.*.ruangan' => 'nullable|string|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        $jadwalCek = $validated['jadwals'];

        if (KelasPerkuliahan::cekBentrokDosenCustom($jadwalCek, $validated['dosen_id'], [], $kelas->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Dosen memiliki jadwal yang bentrok pada hari dan waktu yang sama.');
        }
        
        if (KelasPerkuliahan::cekBentrokRuanganCustom($jadwalCek, $kelas->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ruangan sudah digunakan pada hari dan waktu yang sama.');
        }

        $kelasData = $validated;
        unset($kelasData['jadwals']);
        
        $kelas->update($kelasData);

        $kelas->jadwals()->delete();
        foreach ($jadwalCek as $j) {
            $kelas->jadwals()->create($j);
        }

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(KelasPerkuliahan $kelas)
    {
        $kelas->delete();

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    public function mahasiswa(KelasPerkuliahan $kelas)
    {
        // Get all mahasiswa in the same program studi as the class
        $mahasiswas = User::role('mahasiswa')
            ->where('program_studi_id', $kelas->program_studi_id)
            ->get();
            
        $mahasiswaIds = $kelas->mahasiswa->pluck('id')->toArray();

        return view('admin.kelas.mahasiswa', compact('kelas', 'mahasiswas', 'mahasiswaIds'));
    }

    public function syncMahasiswa(Request $request, KelasPerkuliahan $kelas)
    {
        $request->validate([
            'mahasiswas' => 'nullable|array',
            'mahasiswas.*' => 'exists:users,id',
        ]);

        $kelas->mahasiswa()->sync($request->mahasiswas ?? []);

        return redirect()->route('admin.kelas.index')
            ->with('success', 'Peserta kelas berhasil diperbarui.');
    }
}