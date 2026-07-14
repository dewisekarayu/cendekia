<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\KelasPerkuliahan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    /**
     * Tampilkan tab "Materi" untuk 1 kelas
     */
    public function index(KelasPerkuliahan $kelas)
    {
        $materiList = Materi::where('kelas_perkuliahan_id', $kelas->id)
            ->orderBy('pertemuan_ke')
            ->get();

        return view('portal.dosen.kelas-materi', compact('kelas', 'materiList'));
    }

    /**
     * Simpan materi baru
     */
    public function store(Request $request, KelasPerkuliahan $kelas)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'deskripsi'    => 'nullable|string',
            'file_materi'  => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx,mp4|max:102400',
        ], [
            'file_materi.max'   => 'Ukuran file maksimal 100MB.',
            'file_materi.mimes' => 'Format file harus PDF, PPT, DOCX, atau MP4.',
        ]);

        $filePath = null;
        $tipeFile = null;

        if ($request->hasFile('file_materi')) {
            $file     = $request->file('file_materi');
            $tipeFile = $file->getClientOriginalExtension();
            $filePath = $file->store('materi/' . $kelas->id, 'public');
        }

        Materi::create([
            'kelas_perkuliahan_id' => $kelas->id,
            'judul'                => $validated['judul'],
            'deskripsi'            => $validated['deskripsi'] ?? null,
            'pertemuan_ke'         => $validated['pertemuan_ke'],
            'file_path'            => $filePath,
            'tipe_file'            => $tipeFile,
        ]);

        // Send email notification to all enrolled students
        $materi = Materi::latest()->first();
        if ($materi) {
            NotificationService::notifyMateriBaru($materi, auth()->user());
        }

        return redirect()
            ->route('dosen.kelas-materi', $kelas->id)
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    /**
     * Data 1 materi untuk mengisi form edit (dipanggil via fetch/AJAX)
     */
    public function show(Materi $materi)
    {
        return response()->json($materi);
    }

    /**
     * Update materi
     */
    public function update(Request $request, Materi $materi)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'pertemuan_ke' => 'required|integer|min:1|max:16',
            'deskripsi'    => 'nullable|string',
            'file_materi'  => 'nullable|file|mimes:pdf,ppt,pptx,doc,docx,mp4|max:102400',
        ]);

        $data = [
            'judul'        => $validated['judul'],
            'deskripsi'    => $validated['deskripsi'] ?? null,
            'pertemuan_ke' => $validated['pertemuan_ke'],
        ];

        if ($request->hasFile('file_materi')) {
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }

            $file              = $request->file('file_materi');
            $data['tipe_file'] = $file->getClientOriginalExtension();
            $data['file_path'] = $file->store('materi/' . $materi->kelas_perkuliahan_id, 'public');
        }

        $materi->update($data);

        return redirect()
            ->route('dosen.kelas-materi', $materi->kelas_perkuliahan_id)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    /**
     * Hapus materi
     */
    public function destroy(Materi $materi)
    {
        if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
            Storage::disk('public')->delete($materi->file_path);
        }

        $kelasId = $materi->kelas_perkuliahan_id;
        $materi->delete();

        return redirect()
            ->route('dosen.kelas-materi', $kelasId)
            ->with('success', 'Materi berhasil dihapus.');
    }

    /**
     * Download file materi
     */
    public function download(Materi $materi)
    {
        if (!$materi->file_path || !Storage::disk('public')->exists($materi->file_path)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $materi->file_path,
            $materi->judul . '.' . $materi->tipe_file
        );
    }
}