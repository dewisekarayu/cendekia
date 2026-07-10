<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiMahasiswa;
use App\Models\KelasPerkuliahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Daftar semua kelas yang diikuti mahasiswa beserta ringkasan presensinya.
     */
    public function index()
    {
        $user = Auth::user();

        $kelasList = $user->kelasDiikuti()
            ->with(['mataKuliah', 'dosen'])
            ->paginate(10);

        return view('mahasiswa.absensi.index', compact('kelasList'));
    }

    /**
     * Halaman presensi untuk satu kelas: menampilkan sesi aktif hari ini (jika ada)
     * dan tombol "Absen Masuk".
     */
    public function kelasAbsensi($kelasId)
    {
        $user = Auth::user();
        $kelas = $this->kelasDiikutiMahasiswa($kelasId, ['mataKuliah', 'dosen']);

        $absensiAktif = Absensi::where('kelas_perkuliahan_id', $kelasId)
            ->where('session_status', 'buka')
            ->whereDate('tanggal', today())
            ->latest('created_at')
            ->first();

        $sudahAbsen = null;
        if ($absensiAktif) {
            $sudahAbsen = AbsensiMahasiswa::where('absensi_id', $absensiAktif->id)
                ->where('mahasiswa_id', $user->id)
                ->first();
        }

        $riwayatAbsensi = Absensi::where('kelas_perkuliahan_id', $kelasId)
            ->with(['absensiMahasiswa' => fn ($q) => $q->where('mahasiswa_id', $user->id)])
            ->orderByDesc('tanggal')
            ->limit(5)
            ->get();

        return view('mahasiswa.absensi.kelas-absensi', compact('kelas', 'absensiAktif', 'sudahAbsen', 'riwayatAbsensi'));
    }

    /**
     * Proses klik tombol "Absen Masuk".
     */
    public function absenMasuk(Request $request, $kelasId, $absensiId)
    {
        $user = Auth::user();
        $this->kelasDiikutiMahasiswa($kelasId);

        $absensi = Absensi::where('id', $absensiId)
            ->where('kelas_perkuliahan_id', $kelasId)
            ->firstOrFail();

        $this->authorize('checkIn', $absensi);

        $sudahAda = AbsensiMahasiswa::where('absensi_id', $absensi->id)
            ->where('mahasiswa_id', $user->id)
            ->exists();

        if ($sudahAda) {
            return redirect()->back()->with('warning', 'Anda sudah melakukan presensi untuk sesi ini.');
        }

        AbsensiMahasiswa::create([
            'absensi_id' => $absensi->id,
            'mahasiswa_id' => $user->id,
            'status' => 'hadir',
            'waktu_absensi' => now(),
        ]);

        return redirect()->route('mahasiswa.absensi.kelas', $kelasId)
            ->with('success', 'Presensi berhasil dicatat. Status Anda: Hadir.');
    }

    /**
     * Riwayat & statistik presensi mahasiswa untuk satu kelas.
     */
    public function show($kelasId)
    {
        $user = Auth::user();
        $kelas = $this->kelasDiikutiMahasiswa($kelasId, ['mataKuliah', 'dosen']);

        $absensiList = Absensi::where('kelas_perkuliahan_id', $kelasId)
            ->with(['absensiMahasiswa' => fn ($q) => $q->where('mahasiswa_id', $user->id)])
            ->orderByDesc('tanggal')
            ->paginate(10);

        if ($absensiList->isNotEmpty()) {
            $this->authorize('viewHistory', $absensiList->first());
        }

        $totalPertemuan = Absensi::where('kelas_perkuliahan_id', $kelasId)->count();

        $rekap = AbsensiMahasiswa::whereIn('absensi_id', function ($query) use ($kelasId) {
                $query->select('id')->from('absensi')->where('kelas_perkuliahan_id', $kelasId);
            })
            ->where('mahasiswa_id', $user->id)
            ->selectRaw('status, count(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $stats = [
            'totalPertemuan' => $totalPertemuan,
            'hadir' => $rekap->get('hadir', 0),
            'izin' => $rekap->get('izin', 0),
            'sakit' => $rekap->get('sakit', 0),
            'alpha' => $totalPertemuan - $rekap->sum(),
        ];

        return view('mahasiswa.absensi.show', compact('kelas', 'absensiList', 'stats'));
    }

    /**
     * Ambil kelas dan pastikan mahasiswa yang login benar-benar terdaftar di kelas tersebut.
     */
    private function kelasDiikutiMahasiswa($kelasId, array $with = []): KelasPerkuliahan
    {
        $user = Auth::user();

        return KelasPerkuliahan::where('id', $kelasId)
            ->whereHas('mahasiswa', fn ($q) => $q->where('users.id', $user->id))
            ->with($with)
            ->firstOrFail();
    }
}
