<?php

namespace App\Services;

use App\Models\KelasPerkuliahan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KelasEnrollmentService
{
    /**
     * Mendaftarkan mahasiswa secara otomatis ke kelas perkuliahan aktif yang sesuai dengan Program Studi.
     * 
     * Logika Cerdas:
     * 1. Memfilter kelas aktif berdasarkan program_studi_id mahasiswa.
     * 2. Memprioritaskan kelas untuk semester yang sesuai (default: Semester 1 untuk Maba).
     * 3. Mencegah duplikasi pendaftaran jika mata kuliah memiliki beberapa kelas paralel (misal Kelas A & B).
     * 4. Memilih kelas paralel yang masih memiliki sisa kuota dan memiliki jumlah peserta paling sedikit (Load Balancing).
     * 
     * @param User $mahasiswa
     * @param int|null $semesterKe
     * @return array
     */
    public function autoEnrollMahasiswa(User $mahasiswa, ?int $semesterKe = 1): array
    {
        if (!$mahasiswa->program_studi_id) {
            return [
                'success' => false,
                'enrolled' => [],
                'skipped' => [],
                'total_enrolled' => 0,
                'message' => 'Mahasiswa tidak memiliki data program studi.',
            ];
        }

        // Ambil seluruh kelas aktif yang sesuai dengan Program Studi mahasiswa
        $classesQuery = KelasPerkuliahan::query()
            ->where('is_active', true)
            ->where('status_kelas', 'aktif')
            ->where(function ($q) use ($mahasiswa) {
                $q->where('program_studi_id', $mahasiswa->program_studi_id)
                  ->orWhereHas('mataKuliah', function ($m) use ($mahasiswa) {
                      $m->where('program_studi_id', $mahasiswa->program_studi_id);
                  });
            })
            ->with(['mataKuliah', 'mahasiswa']);

        $availableClasses = $classesQuery->get();

        if ($availableClasses->isEmpty()) {
            return [
                'success' => true,
                'enrolled' => [],
                'skipped' => [],
                'total_enrolled' => 0,
                'message' => 'Belum ada kelas perkuliahan aktif pada program studi ini.',
            ];
        }

        // Jika ditentukan $semesterKe, prioritaskan mata kuliah semester tersebut
        if ($semesterKe !== null) {
            $semesterClasses = $availableClasses->filter(function ($kelas) use ($semesterKe) {
                return $kelas->mataKuliah && (int) $kelas->mataKuliah->semester_ke === (int) $semesterKe;
            });

            // Gunakan kelas spesifik semester jika tersedia, jika tidak fallback ke seluruh kelas aktif prodi
            if ($semesterClasses->isNotEmpty()) {
                $availableClasses = $semesterClasses;
            }
        }

        // Kelompokkan kelas berdasarkan mata_kuliah_id untuk menangani kelas paralel (Kelas A, Kelas B)
        $groupedByMatkul = $availableClasses->groupBy('mata_kuliah_id');

        $enrolled = [];
        $skipped = [];

        foreach ($groupedByMatkul as $matkulId => $classList) {
            $mataKuliahNama = $classList->first()->mataKuliah?->nama_mk ?? "Mata Kuliah #{$matkulId}";

            // 1. Cek apakah mahasiswa SUDAH terdaftar di salah satu kelas untuk mata kuliah ini
            $alreadyEnrolled = DB::table('kelas_mahasiswa')
                ->join('kelas_perkuliahan', 'kelas_mahasiswa.kelas_perkuliahan_id', '=', 'kelas_perkuliahan.id')
                ->where('kelas_mahasiswa.mahasiswa_id', $mahasiswa->id)
                ->where('kelas_perkuliahan.mata_kuliah_id', $matkulId)
                ->exists();

            if ($alreadyEnrolled) {
                continue;
            }

            // 2. Evaluasi kapasitas kuota untuk setiap kelas paralel
            $candidates = $classList->map(function ($k) {
                $count = $k->mahasiswa->count();
                $kuota = (int) ($k->kuota_mahasiswa ?: 40);
                return [
                    'kelas' => $k,
                    'count' => $count,
                    'kuota' => $kuota,
                    'sisa'  => $kuota - $count,
                ];
            });

            // Cari kelas yang masih memiliki sisa kuota (sisa > 0)
            $availableCandidates = $candidates->filter(fn($c) => $c['sisa'] > 0);

            if ($availableCandidates->isEmpty()) {
                $skipped[] = [
                    'mata_kuliah' => $mataKuliahNama,
                    'reason' => 'Kuota semua kelas paralel untuk mata kuliah ini telah penuh',
                ];
                continue;
            }

            // 3. Load Balancing: Pilih kelas yang pesertanya paling sedikit agar pembagian merata
            $chosenData = $availableCandidates->sortBy('count')->first();
            /** @var KelasPerkuliahan $chosenClass */
            $chosenClass = $chosenData['kelas'];

            // 4. Daftarkan mahasiswa ke kelas terpilih
            $chosenClass->mahasiswa()->attach($mahasiswa->id, [
                'tanggal_daftar' => now()->toDateString(),
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $enrolled[] = [
                'kelas_id'    => $chosenClass->id,
                'kode_kelas'  => $chosenClass->kode_kelas,
                'mata_kuliah' => $mataKuliahNama,
            ];
        }

        return [
            'success'        => true,
            'enrolled'       => $enrolled,
            'skipped'        => $skipped,
            'total_enrolled' => count($enrolled),
            'message'        => count($enrolled) > 0
                ? "Berhasil mendaftarkan mahasiswa ke " . count($enrolled) . " kelas perkuliahan prodi."
                : "Tidak ada kelas baru yang didaftarkan.",
        ];
    }

    /**
     * Mendaftarkan banyak mahasiswa secara massal (Batch/Bulk)
     * 
     * @param iterable $mahasiswas
     * @param int|null $semesterKe
     * @return array
     */
    public function autoEnrollBatch(iterable $mahasiswas, ?int $semesterKe = 1): array
    {
        $totalMahasiswa = 0;
        $totalKelasEnrolled = 0;

        foreach ($mahasiswas as $mhs) {
            $res = $this->autoEnrollMahasiswa($mhs, $semesterKe);
            if (!empty($res['enrolled'])) {
                $totalMahasiswa++;
                $totalKelasEnrolled += count($res['enrolled']);
            }
        }

        return [
            'total_mahasiswa_terdaftar' => $totalMahasiswa,
            'total_kelas_terhubung'     => $totalKelasEnrolled,
        ];
    }
}
