<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KelasPerkuliahan;
use App\Models\Materi;
use App\Models\Tugas;
use App\Models\MateriFile;
use App\Models\TugasFile;
use App\Models\PengumpulanTugas;
use App\Services\NotificationService;
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
                'pengumpulan as submitted_count' => fn ($q) => $q->where('status', '!=', PengumpulanTugas::STATUS_BELUM_DIKUMPUL),
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
            'template' => ['nullable', 'array', 'max:5'],
            'template.*' => [
                'file',
                'mimes:pdf,doc,docx,zip,ppt,pptx,jpg,jpeg,png',
                'max:25600',
            ],
        ], [
            'deadline.after' => 'Deadline harus lebih besar dari waktu saat ini.',
            'template.*.max' => 'Ukuran setiap lampiran maksimal 25MB.',
        ]);

        $tugas = Tugas::create([
            'kelas_perkuliahan_id' => $kelas->id,
            'judul' => $validated['judul'],
            'instruksi' => $validated['instruksi'],
            'deadline' => $validated['deadline'],
            'bobot_nilai' => $validated['poin'],
        ]);

        if ($request->hasFile('template')) {
            foreach ($request->file('template') as $file) {
                $path = $file->store('tugas/' . $kelas->id, 'public');

                $tugas->files()->create([
                    'file_path' => $path,
                    'nama_asli' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Send email notification to all enrolled students
        NotificationService::notifyTugasBaru($tugas, auth()->user());

        return redirect()->route('dosen.kelas-tugas', $kelas->id)
            ->with('success', 'Tugas berhasil dipublikasikan.');
    }

    public function submissions(Request $request, $kelasId, $tugasId)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah', 'mahasiswa'])
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($kelasId);

        $tugas = Tugas::where('kelas_perkuliahan_id', $kelas->id)->findOrFail($tugasId);

        // Auto-buat baris "belum dikumpul" untuk mahasiswa yang belum punya record,
        // biar mereka tetap kelihatan di daftar (bukan cuma yang udah submit)
        $sudahAdaIds = PengumpulanTugas::where('tugas_id', $tugas->id)->pluck('mahasiswa_id');
        $belumAda = $kelas->mahasiswa->whereNotIn('id', $sudahAdaIds);

        foreach ($belumAda as $mhs) {
            PengumpulanTugas::create([
                'tugas_id' => $tugas->id,
                'mahasiswa_id' => $mhs->id,
                'status' => PengumpulanTugas::STATUS_BELUM_DIKUMPUL,
            ]);
        }

        $submissions = PengumpulanTugas::with(['mahasiswa', 'files'])
        ->where('tugas_id', $tugas->id)
        ->get()
        ->sortBy(fn ($p) => $p->mahasiswa->name ?? '')
        ->values();

        return view('dosen.kelas-tugas-submissions', compact('kelas', 'tugas', 'submissions'));
    }

    public function simpanNilai(Request $request, $kelasId, $tugasId, $pengumpulanId)
    {
        $kelas = KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($kelasId);
        $tugas = Tugas::where('kelas_perkuliahan_id', $kelas->id)->findOrFail($tugasId);
        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)->findOrFail($pengumpulanId);

        $validated = $request->validate([
            'nilai' => ['required', 'integer', 'min:0', 'max:100'],
            'feedback_dosen' => ['nullable', 'string', 'max:2000'],
        ], [
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.max' => 'Nilai maksimal 100.',
        ]);

        $pengumpulan->update([
            'nilai' => $validated['nilai'],
            'feedback_dosen' => $validated['feedback_dosen'] ?? null,
            'status' => PengumpulanTugas::STATUS_DINILAI,
        ]);

        // Send email notification to student about grade
        NotificationService::notifyNilaiBaru($pengumpulan, auth()->user());

        return back()->with('success', 'Nilai untuk ' . ($pengumpulan->mahasiswa->name ?? 'mahasiswa') . ' berhasil disimpan.');
    }

   public function bukaMateri(Request $request, $kelasId, $materiId)
    {
        $kelas = KelasPerkuliahan::with('mataKuliah.programStudi')
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($kelasId);

        $materi = Materi::with('files')
            ->where('kelas_perkuliahan_id', $kelas->id)
            ->findOrFail($materiId);

        return view('dosen.materi.buka', compact('kelas', 'materi'));
    }

    public function unduhMateri(Request $request, $kelasId, $materiId, $fileId)
    {
        $kelas = KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($kelasId);
        $materi = Materi::where('kelas_perkuliahan_id', $kelas->id)->findOrFail($materiId);
        $file = MateriFile::where('materi_id', $materi->id)->findOrFail($fileId);

        $path = Storage::disk('public')->path($file->file_path);
        abort_unless(file_exists($path), 404, 'File tidak ditemukan di server.');

        return response()->download($path, $file->nama_asli ?? basename($file->file_path));
    }

    public function previewMateri(Request $request, $kelasId, $materiId, $fileId)
    {
        $kelas = KelasPerkuliahan::where('dosen_id', $request->user()->id)->findOrFail($kelasId);
        $materi = Materi::where('kelas_perkuliahan_id', $kelas->id)->findOrFail($materiId);
        $file = MateriFile::where('materi_id', $materi->id)->findOrFail($fileId);

        $path = Storage::disk('public')->path($file->file_path);
        abort_unless(file_exists($path), 404, 'File tidak ditemukan di server.');

        return response()->file($path);
    }

    public function materi(Request $request, $id)
    {
        $kelas = KelasPerkuliahan::with(['mataKuliah.programStudi', 'mahasiswa'])
            ->where('dosen_id', $request->user()->id)
            ->findOrFail($id);

        $kelasList = $request->user()->kelasDiampu()->with(['mataKuliah.programStudi', 'mahasiswa'])->get();

        $materiList = Materi::where('kelas_perkuliahan_id', $kelas->id)
            ->with('files')
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
            'file_materi' => ['nullable', 'array', 'max:5'],
            'file_materi.*' => ['file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,mp4,zip,jpg,jpeg,png', 'max:102400'],
        ], [
            'file_materi.*.max' => 'Ukuran setiap file materi maksimal 100MB.',
        ]);

        $materi = Materi::create([
            'kelas_perkuliahan_id' => $kelas->id,
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'pertemuan_ke' => $validated['pertemuan_ke'],
        ]);

        if ($request->hasFile('file_materi')) {
            foreach ($request->file('file_materi') as $file) {
                $path = $file->store('materi/' . $kelas->id, 'public');

                $materi->files()->create([
                    'file_path' => $path,
                    'nama_asli' => $file->getClientOriginalName(),
                    'tipe_file' => strtolower($file->getClientOriginalExtension()),
                ]);
            }
        }

        // Send email notification to all enrolled students
        NotificationService::notifyMateriBaru($materi, auth()->user());

        return redirect()->route('dosen.kelas-materi', $kelas->id)
            ->with('success', 'Materi berhasil ditambahkan.');
    }
}
