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

        $pengumpulan = PengumpulanTugas::with('tugas')
            ->where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->where('status', '!=', PengumpulanTugas::STATUS_BELUM_DIKUMPUL) // tambahkan ini
            ->first();

        $tugas->load('kelasPerkuliahan.mataKuliah');

        return view('mahasiswa.pengumpulan-tugas', [
            'tugas'       => $tugas,
            'pengumpulan' => $pengumpulan,
        ]);
    }

    /**
     * Simpan / perbarui jawaban yang diunggah mahasiswa.
     */
    public function store(Request $request, Tugas $tugas)
    {
        $validated = $request->validate([
            'file_jawaban' => ['required', 'file', 'mimes:pdf,zip', 'max:10240'], // 10 MB
            'catatan'      => ['nullable', 'string', 'max:1000'],
        ]);

        $mahasiswaId = Auth::id();

        // Cek apakah sudah lewat batas waktu
        $isLate = now()->gt($tugas->deadline);

        // Kalau sebelumnya sudah ada jawaban, hapus file lama biar tidak menumpuk
        $existing = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->first();

        if ($existing && $existing->file_jawaban) {
            Storage::disk('public')->delete($existing->file_jawaban);
        }

        // Simpan file baru ke storage/app/public/pengumpulan/{tugas_id}
        $path = $request->file('file_jawaban')->store(
            "pengumpulan/{$tugas->id}",
            'public'
        );

        $pengumpulan = PengumpulanTugas::updateOrCreate(
            [
                'tugas_id'     => $tugas->id,
                'mahasiswa_id' => $mahasiswaId,
            ],
            [
                'file_jawaban' => $path,
                'catatan'      => $validated['catatan'] ?? null,
                'waktu_kumpul' => now(),
                'status'       => $isLate
                    ? PengumpulanTugas::STATUS_TERLAMBAT
                    : PengumpulanTugas::STATUS_DIKUMPUL,
                // nilai & feedback_dosen sengaja tidak disentuh di sini,
                // itu diisi dosen lewat halaman penilaian
            ]
        );

        return redirect()
            ->route('mahasiswa.pengumpulan-tugas.show', $tugas->id)
            ->with('success', 'Tugas berhasil dikumpulkan.');
    }

    /**
     * Batalkan / hapus pengumpulan yang belum dinilai.
     */
    public function destroy(Tugas $tugas)
    {
        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', Auth::id())
            ->firstOrFail();

        abort_if(
            $pengumpulan->status === PengumpulanTugas::STATUS_DINILAI,
            403,
            'Tugas yang sudah dinilai tidak dapat dibatalkan.'
        );

        if ($pengumpulan->file_jawaban) {
            Storage::disk('public')->delete($pengumpulan->file_jawaban);
        }

        $pengumpulan->delete();

        return redirect()
            ->route('mahasiswa.pengumpulan-tugas.show', $tugas->id)
            ->with('success', 'Pengumpulan tugas dibatalkan.');
    }
}