<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiMahasiswa;
use App\Models\KelasPerkuliahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    /**
     * Daftar semua sesi presensi untuk satu kelas yang diampu dosen.
     */
    public function index($kelasId)
    {
        $kelas = $this->kelasMilikDosen($kelasId, ['mahasiswa']);

        $absensiList = Absensi::where('kelas_perkuliahan_id', $kelasId)
            ->withCount([
                'absensiMahasiswa as hadir_count' => fn ($q) => $q->where('status', 'hadir'),
            ])
            ->orderByDesc('pertemuan_ke')
            ->paginate(10);

        $statistics = [
            'total_sesi' => Absensi::where('kelas_perkuliahan_id', $kelasId)->count(),
            'sesi_draft' => Absensi::where('kelas_perkuliahan_id', $kelasId)->draft()->count(),
            'sesi_buka' => Absensi::where('kelas_perkuliahan_id', $kelasId)->buka()->count(),
            'sesi_tutup' => Absensi::where('kelas_perkuliahan_id', $kelasId)->tutup()->count(),
        ];

        return view('dosen.absensi.index', compact('kelas', 'absensiList', 'statistics'));
    }

    /**
     * Form membuat sesi presensi baru untuk pertemuan berikutnya.
     */
    public function create($kelasId)
    {
        $kelas = $this->kelasMilikDosen($kelasId);

        $nextPertemuan = (Absensi::where('kelas_perkuliahan_id', $kelasId)->max('pertemuan_ke') ?? 0) + 1;

        return view('dosen.absensi.create', compact('kelas', 'nextPertemuan'));
    }

    /**
     * Simpan sesi presensi baru dengan status awal "draft".
     */
    public function store(Request $request, $kelasId)
    {
        $kelas = $this->kelasMilikDosen($kelasId);

        $validated = $request->validate([
            'pertemuan_ke' => ['required', 'integer', 'min:1', 'max:99'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'string', 'max:50'],
            'jam_selesai' => ['required', 'string', 'max:50'],
            'rangkuman' => ['nullable', 'string', 'max:500'],
            'berita_acara' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $sudahAda = Absensi::where('kelas_perkuliahan_id', $kelasId)
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->exists();

        if ($sudahAda) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Sesi presensi untuk pertemuan ke-' . $validated['pertemuan_ke'] . ' sudah ada.');
        }

        $absensi = Absensi::create([
            'kelas_perkuliahan_id' => $kelasId,
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'session_status' => 'draft',
            'rangkuman' => $validated['rangkuman'] ?? null,
            'berita_acara' => $validated['berita_acara'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('dosen.absensi.show', ['kelasId' => $kelasId, 'absensiId' => $absensi->id])
            ->with('success', 'Sesi presensi berhasil dibuat dengan status Draft. Buka sesi agar mahasiswa dapat presensi.');
    }

    /**
     * Detail satu sesi presensi: daftar seluruh mahasiswa kelas beserta status kehadirannya.
     */
    public function show($kelasId, $absensiId)
    {
        $kelas = $this->kelasMilikDosen($kelasId, ['mahasiswa']);
        $absensi = $this->absensiDiKelas($kelasId, $absensiId, ['absensiMahasiswa.mahasiswa']);

        $this->authorize('view', $absensi);

        $hadirMap = $absensi->absensiMahasiswa->keyBy('mahasiswa_id');

        $stats = [
            'total' => $kelas->mahasiswa->count(),
            'hadir' => $absensi->absensiMahasiswa->where('status', 'hadir')->count(),
            'izin' => $absensi->absensiMahasiswa->where('status', 'izin')->count(),
            'sakit' => $absensi->absensiMahasiswa->where('status', 'sakit')->count(),
            'alpha' => $kelas->mahasiswa->count() - $absensi->absensiMahasiswa->count(),
        ];

        return view('dosen.absensi.show', compact('kelas', 'absensi', 'hadirMap', 'stats'));
    }

    /**
     * Buka sesi presensi agar mahasiswa dapat check-in.
     */
    public function bukaSession($kelasId, $absensiId)
    {
        $this->kelasMilikDosen($kelasId);
        $absensi = $this->absensiDiKelas($kelasId, $absensiId);

        $this->authorize('manage', $absensi);

        if ($absensi->isBuka()) {
            return redirect()->back()->with('warning', 'Sesi presensi sudah dibuka.');
        }

        $absensi->bukaSession();

        return redirect()->back()->with('success', 'Sesi presensi telah dibuka. Mahasiswa dapat mulai melakukan presensi.');
    }

    /**
     * Tutup sesi presensi. Mahasiswa yang belum presensi otomatis dianggap Alpha
     * (dihitung dari ketidakhadiran baris, bukan disimpan eksplisit).
     */
    public function tutupSession($kelasId, $absensiId)
    {
        $this->kelasMilikDosen($kelasId);
        $absensi = $this->absensiDiKelas($kelasId, $absensiId);

        $this->authorize('manage', $absensi);

        if ($absensi->isTutup()) {
            return redirect()->back()->with('warning', 'Sesi presensi sudah ditutup.');
        }

        $absensi->tutupSession();

        return redirect()->back()->with('success', 'Sesi presensi telah ditutup.');
    }

    /**
     * Form edit kehadiran manual (dosen mengoreksi status per mahasiswa).
     */
    public function editAttendance($kelasId, $absensiId)
    {
        $kelas = $this->kelasMilikDosen($kelasId, ['mahasiswa']);
        $absensi = $this->absensiDiKelas($kelasId, $absensiId, ['absensiMahasiswa']);

        $this->authorize('manage', $absensi);

        return view('dosen.absensi.attendance', compact('kelas', 'absensi'));
    }

    /**
     * Simpan koreksi kehadiran manual dari dosen.
     */
    public function updateAttendance(Request $request, $kelasId, $absensiId)
    {
        $kelas = $this->kelasMilikDosen($kelasId, ['mahasiswa']);
        $absensi = $this->absensiDiKelas($kelasId, $absensiId);

        $this->authorize('manage', $absensi);

        $request->validate([
            'attendance' => ['required', 'array'],
            'attendance.*' => ['required', 'in:hadir,izin,sakit,alpha'],
            'keterangan' => ['nullable', 'array'],
            'keterangan.*' => ['nullable', 'string', 'max:255'],
        ]);

        $mahasiswaIdsValid = $kelas->mahasiswa->pluck('id')->all();

        foreach ($request->input('attendance') as $mahasiswaId => $status) {
            if (!in_array((int) $mahasiswaId, $mahasiswaIdsValid, true)) {
                continue; // abaikan id yang bukan mahasiswa kelas ini
            }

            AbsensiMahasiswa::updateOrCreate(
                [
                    'absensi_id' => $absensi->id,
                    'mahasiswa_id' => $mahasiswaId,
                ],
                [
                    'status' => $status,
                    'keterangan' => $request->input('keterangan.' . $mahasiswaId),
                    'waktu_absensi' => now(),
                ]
            );
        }

        return redirect()->route('dosen.absensi.show', ['kelasId' => $kelasId, 'absensiId' => $absensi->id])
            ->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    /**
     * Hapus sesi presensi beserta seluruh data kehadiran di dalamnya.
     */
    public function destroy($kelasId, $absensiId)
    {
        $this->kelasMilikDosen($kelasId);
        $absensi = $this->absensiDiKelas($kelasId, $absensiId);

        $this->authorize('manage', $absensi);

        $absensi->absensiMahasiswa()->delete();
        $absensi->delete();

        return redirect()->route('dosen.absensi.index', $kelasId)
            ->with('success', 'Sesi presensi berhasil dihapus.');
    }

    /**
     * Form edit presensi
     */
    public function edit($kelasId, $absensiId)
    {
        $kelas = $this->kelasMilikDosen($kelasId);
        $absensi = $this->absensiDiKelas($kelasId, $absensiId);

        $this->authorize('manage', $absensi);

        return view('dosen.absensi.edit', compact('kelas', 'absensi'));
    }

    /**
     * Update presensi
     */
    public function update(Request $request, $kelasId, $absensiId)
    {
        $kelas = $this->kelasMilikDosen($kelasId);
        $absensi = $this->absensiDiKelas($kelasId, $absensiId);

        $this->authorize('manage', $absensi);

        $validated = $request->validate([
            'pertemuan_ke' => ['required', 'integer', 'min:1', 'max:99'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'string', 'max:50'],
            'jam_selesai' => ['required', 'string', 'max:50'],
            'session_status' => ['required', 'in:draft,buka,tutup'],
            'rangkuman' => ['nullable', 'string', 'max:500'],
            'berita_acara' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        // Check duplicate pertemuan
        $sudahAda = Absensi::where('kelas_perkuliahan_id', $kelasId)
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->where('id', '!=', $absensi->id)
            ->exists();

        if ($sudahAda) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pertemuan ini untuk kelas sudah ada.');
        }

        $absensi->update($validated);

        return redirect()->route('dosen.absensi.show', ['kelasId' => $kelasId, 'absensiId' => $absensi->id])
            ->with('success', 'Presensi berhasil diperbarui.');
    }

    public function export($kelasId, $absensiId)
    {
        $this->kelasMilikDosen($kelasId);
        $this->absensiDiKelas($kelasId, $absensiId);

        return redirect()->route('dosen.absensi.show', ['kelasId' => $kelasId, 'absensiId' => $absensiId])
            ->with('info', 'Fitur export PDF akan segera tersedia.');
    }

    /**
     * Ambil kelas dan pastikan dosen yang login benar-benar mengampunya (dosen utama atau team teaching).
     */
    private function kelasMilikDosen($kelasId, array $with = []): KelasPerkuliahan
    {
        $user = Auth::user();

        return KelasPerkuliahan::where('id', $kelasId)
            ->where(function ($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereJsonContains('dosen_pengampu', $user->id);
            })
            ->with(array_merge(['mataKuliah'], $with))
            ->firstOrFail();
    }

    private function absensiDiKelas($kelasId, $absensiId, array $with = []): Absensi
    {
        return Absensi::where('id', $absensiId)
            ->where('kelas_perkuliahan_id', $kelasId)
            ->with($with)
            ->firstOrFail();
    }
}