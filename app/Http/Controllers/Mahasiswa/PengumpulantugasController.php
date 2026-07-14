<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PengumpulantugasController extends Controller
{
    /**
     * Tampilkan halaman detail tugas + form upload jawaban
     * untuk mahasiswa yang sedang login.
     */
    public function show(Tugas $tugas)
    {
        $mahasiswaId = Auth::id();
        $pengumpulan = PengumpulanTugas::with(['tugas', 'files'])
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', '!=', PengumpulanTugas::STATUS_BELUM_DIKUMPUL)
            ->first();
        $tugas->load('kelasPerkuliahan.mataKuliah');

        return view('mahasiswa.pengumpulan-tugas', [
            'tugas'       => $tugas,
            'pengumpulan' => $pengumpulan,
        ]);
    }

    public function store(Request $request, Tugas $tugas)
    {
        $validated = $request->validate([
            'file_jawaban'   => ['required', 'array', 'min:1', 'max:5'],
            'file_jawaban.*' => [
                'file',
                'mimes:pdf,zip,doc,docx,ppt,pptx,jpg,jpeg,png',
                'max:10240',
            ],
            'catatan'        => ['nullable', 'string', 'max:1000'],
        ]);
        
        $mahasiswaId = Auth::id();
        $isLate = now()->gt($tugas->deadline);

        $existing = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->first();

        // Kalau sudah pernah upload, hapus file lama dulu biar tidak menumpuk
        if ($existing) {
            foreach ($existing->files as $oldFile) {
                Storage::disk('public')->delete($oldFile->file_path);
            }
            $existing->files()->delete();
        }

        $pengumpulan = PengumpulanTugas::updateOrCreate(
            [
                'tugas_id'     => $tugas->id,
                'mahasiswa_id' => $mahasiswaId,
            ],
            [
                'catatan'      => $validated['catatan'] ?? null,
                'waktu_kumpul' => now(),
                'status'       => $isLate
                    ? PengumpulanTugas::STATUS_TERLAMBAT
                    : PengumpulanTugas::STATUS_DIKUMPUL,
            ]
        );

        // Simpan setiap file yang diunggah
        foreach ($request->file('file_jawaban') as $file) {
            $path = $file->store("pengumpulan/{$tugas->id}", 'public');

            $pengumpulan->files()->create([
                'file_path' => $path,
                'nama_asli' => $file->getClientOriginalName(),
            ]);
        }

        return redirect()
            ->route('mahasiswa.pengumpulan-tugas.show', $tugas->id)
            ->with('success', 'Tugas berhasil dikumpulkan.');
    }

    public function destroy(Tugas $tugas)
    {
        $pengumpulan = PengumpulanTugas::with('files')
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', Auth::id())
            ->firstOrFail();

        abort_if(
            $pengumpulan->status === PengumpulanTugas::STATUS_DINILAI,
            403,
            'Tugas yang sudah dinilai tidak dapat dibatalkan.'
        );

        foreach ($pengumpulan->files as $file) {
            Storage::disk('public')->delete($file->file_path);
        }

        $pengumpulan->delete(); // files ikut terhapus otomatis kalau pakai cascadeOnDelete

        return redirect()
            ->route('mahasiswa.pengumpulan-tugas.show', $tugas->id)
            ->with('success', 'Pengumpulan tugas dibatalkan.');
    }
}