<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Materi;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KelasController extends Controller
{
    public function kelasSaya(Request $request)
    {
        $search = trim($request->input('search', ''));

        $kelasQuery = $request->user()->kelasDiampu()->with(['mataKuliah.programStudi', 'mahasiswa']);

        if ($search !== '') {
            $kelasQuery->where(function ($query) use ($search) {
                $query->where('kode_kelas', 'like', "%{$search}%")
                    ->orWhereHas('mataKuliah', function ($subQuery) use ($search) {
                        $subQuery->where('nama_mk', 'like', "%{$search}%")
                            ->orWhere('kode_mk', 'like', "%{$search}%");
                    })
                    ->orWhereHas('mataKuliah.programStudi', function ($subQuery) use ($search) {
                        $subQuery->where('nama_prodi', 'like', "%{$search}%");
                    });
            });
        }

        $kelasList = $kelasQuery->get();

        return view('dosen.kelas-saya', compact('kelasList', 'search'));
    }

    public function show(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah.programStudi', 'mahasiswa'])
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($id);

        return view('dosen.kelas-detail', compact('kelas'));
    }

    public function tugas(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah', 'mahasiswa'])
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($id);

        $tugasList = Tugas::where('kelas_perkuliahan_id', $kelas->id)
            ->withCount([
                'pengumpulan as submitted_count' => fn ($q) => $q->where('status', '!=', 'belum_dikumpulkan'),
            ])
            ->latest()
            ->get();
        $perluDinilai = $tugasList->sum(fn ($tugas) => (int) ($tugas->submitted_count ?? 0));

        return view('dosen.kelas-tugas', compact('kelas', 'tugasList', 'perluDinilai'));
    }

    public function storeTugas(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'instruksi' => ['required', 'string', 'max:5000'],
            'deadline' => ['required', 'date', 'after:now'],
            'poin' => ['required', 'integer', 'min:1', 'max:100'],
            'template' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,ppt,pptx,xls,xlsx', 'max:25600'],
        ], [
            'deadline.after' => 'Deadline harus lebih besar dari waktu saat ini.',
            'template.max' => 'Ukuran lampiran maksimal 25MB.',
        ]);

        $lampiranPath = null;

        if ($request->hasFile('template')) {
            $lampiranPath = $request->file('template')->store('tugas/' . $kelas->id, 'public');
        }

        Tugas::create([
            'kelas_perkuliahan_id' => $kelas->id,
            'judul' => $validated['judul'],
            'instruksi' => $validated['instruksi'],
            'file_lampiran' => $lampiranPath,
            'deadline' => $validated['deadline'],
            'bobot_nilai' => $validated['poin'],
        ]);

        return redirect()->route('dosen.kelas-tugas', $kelas->id)
            ->with('success', 'Tugas berhasil dipublikasikan.');
    }

    public function materi(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah.programStudi', 'mahasiswa'])
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($id);

        // also provide list of classes the dosen teaches for sidebar/search consistency
        $kelasList = $request->user()->kelasDiampu()->with(['mataKuliah.programStudi', 'mahasiswa'])->get();

        $materiList = Materi::where('kelas_perkuliahan_id', $kelas->id)
            ->orderBy('pertemuan_ke')
            ->get();

        return view('dosen.kelas-materi', compact('kelas', 'kelasList', 'materiList'));
    }

    public function storeMateri(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'pertemuan_ke' => ['required', 'integer', 'min:1', 'max:32'],
            'file_materi' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,zip', 'max:102400'],
        ], [
            'file_materi.max' => 'Ukuran file materi maksimal 100MB.',
        ]);

        $filePath = null;
        $tipeFile = null;

        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $filePath = $file->store('materi/' . $kelas->id, 'public');
            $tipeFile = strtolower($file->getClientOriginalExtension());
        }

        Materi::create([
            'kelas_perkuliahan_id' => $kelas->id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'file_path' => $filePath,
            'tipe_file' => $tipeFile,
        ]);

        return redirect()->route('dosen.kelas-materi', $kelas->id)
            ->with('success', 'Materi berhasil ditambahkan.');
    }
}
