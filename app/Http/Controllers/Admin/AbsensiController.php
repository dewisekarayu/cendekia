<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\AbsensiMahasiswa;
use App\Models\KelasPerkuliahan;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Daftar semua presensi di semua kelas
     */
    public function index()
    {
        $absensiList = Absensi::with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.dosen'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        $statistics = [
            'total' => Absensi::count(),
            'draft' => Absensi::draft()->count(),
            'buka' => Absensi::buka()->count(),
            'tutup' => Absensi::tutup()->count(),
            'total_mahasiswa_hadir' => AbsensiMahasiswa::where('status', 'hadir')->count(),
            'total_alpha' => AbsensiMahasiswa::where('status', 'alpha')->count(),
        ];

        return view('admin.absensi.index', compact('absensiList', 'statistics'));
    }

    /**
     * Form buat presensi baru
     */
    public function create()
    {
        $kelasList = KelasPerkuliahan::with(['mataKuliah', 'dosen'])
            ->orderBy('kode_kelas')
            ->get();

        return view('admin.absensi.create', compact('kelasList'));
    }

    /**
     * Simpan presensi baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_perkuliahan_id' => ['required', 'exists:kelas_perkuliahan,id'],
            'pertemuan_ke' => ['required', 'integer', 'min:1', 'max:16'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'rangkuman' => ['nullable', 'string', 'max:500'],
            'berita_acara' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $sudahAda = Absensi::where('kelas_perkuliahan_id', $validated['kelas_perkuliahan_id'])
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->exists();

        if ($sudahAda) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Presensi untuk pertemuan ini di kelas tersebut sudah ada.');
        }

        $absensi = Absensi::create([
            'kelas_perkuliahan_id' => $validated['kelas_perkuliahan_id'],
            'pertemuan_ke' => $validated['pertemuan_ke'],
            'tanggal' => $validated['tanggal'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'session_status' => 'draft',
            'rangkuman' => $validated['rangkuman'] ?? null,
            'berita_acara' => $validated['berita_acara'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('admin.absensi.show', $absensi->id)
            ->with('success', 'Presensi berhasil dibuat.');
    }

    /**
     * Tampilkan detail presensi
     */
    public function show(Absensi $absensi)
    {
        $absensi->load(['kelasPerkuliahan.mahasiswa', 'absensiMahasiswa.mahasiswa']);

        $stats = [
            'total' => $absensi->kelasPerkuliahan->mahasiswa->count(),
            'hadir' => $absensi->absensiMahasiswa->where('status', 'hadir')->count(),
            'izin' => $absensi->absensiMahasiswa->where('status', 'izin')->count(),
            'sakit' => $absensi->absensiMahasiswa->where('status', 'sakit')->count(),
            'alpha' => $absensi->kelasPerkuliahan->mahasiswa->count() - $absensi->absensiMahasiswa->count(),
        ];

        $hadirMap = $absensi->absensiMahasiswa->keyBy('mahasiswa_id');

        return view('admin.absensi.show', compact('absensi', 'stats', 'hadirMap'));
    }

    /**
     * Form edit presensi
     */
    public function edit(Absensi $absensi)
    {
        $kelasList = KelasPerkuliahan::orderBy('kode_kelas')->get();
        
        return view('admin.absensi.edit', compact('absensi', 'kelasList'));
    }

    /**
     * Update presensi
     */
    public function update(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'kelas_perkuliahan_id' => ['required', 'exists:kelas_perkuliahan,id'],
            'pertemuan_ke' => ['required', 'integer', 'min:1', 'max:16'],
            'tanggal' => ['required', 'date'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'session_status' => ['required', 'in:draft,buka,tutup'],
            'rangkuman' => ['nullable', 'string', 'max:500'],
            'berita_acara' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        // Cek duplikasi untuk kelas & pertemuan berbeda
        $sudahAda = Absensi::where('kelas_perkuliahan_id', $validated['kelas_perkuliahan_id'])
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->where('id', '!=', $absensi->id)
            ->exists();

        if ($sudahAda) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pertemuan ini untuk kelas tersebut sudah ada.');
        }

        $absensi->update($validated);

        return redirect()->route('admin.absensi.show', $absensi->id)
            ->with('success', 'Presensi berhasil diperbarui.');
    }

    /**
     * Form edit kehadiran mahasiswa
     */
    public function editAttendance(Absensi $absensi)
    {
        $absensi->load(['kelasPerkuliahan.mahasiswa', 'absensiMahasiswa']);

        return view('admin.absensi.attendance', compact('absensi'));
    }

    /**
     * Update kehadiran mahasiswa
     */
    public function updateAttendance(Request $request, Absensi $absensi)
    {
        $validated = $request->validate([
            'attendance' => ['required', 'array'],
            'attendance.*' => ['required', 'in:hadir,izin,sakit,alpha'],
            'keterangan' => ['nullable', 'array'],
            'keterangan.*' => ['nullable', 'string', 'max:255'],
        ]);

        $mahasiswaIds = $absensi->kelasPerkuliahan->mahasiswa->pluck('id')->all();

        foreach ($request->input('attendance') as $mahasiswaId => $status) {
            if (!in_array((int) $mahasiswaId, $mahasiswaIds, true)) {
                continue;
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

        return redirect()->route('admin.absensi.show', $absensi->id)
            ->with('success', 'Data kehadiran berhasil diperbarui.');
    }

    /**
     * Hapus presensi
     */
    public function destroy(Absensi $absensi)
    {
        $absensi->absensiMahasiswa()->delete();
        $absensi->delete();

        return redirect()->route('admin.absensi.index')
            ->with('success', 'Presensi berhasil dihapus.');
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['exists:absensi,id'],
        ]);

        Absensi::whereIn('id', $validated['ids'])->delete();

        return redirect()->route('admin.absensi.index')
            ->with('success', count($validated['ids']) . ' presensi berhasil dihapus.');
    }
}
