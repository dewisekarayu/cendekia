<?php

namespace App\Http\Controllers\Admin;

use App\Exports\MahasiswaExport;
use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    /**
     * Tampilkan daftar mahasiswa beserta filter pencarian, prodi, dan status.
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $prodiFilter = $request->input('program_studi_id') ?? $request->input('prodi');
        $statusFilter = $request->input('status');

        $query = User::role('mahasiswa')->with('programStudi');

        // Filter Pencarian (Nama, NIM, atau Email)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter Program Studi
        if ($prodiFilter) {
            $query->where('program_studi_id', $prodiFilter);
        }

        // Filter Status
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        // Penomoran Halaman (Pagination)
        $perPage = (int) $request->input('per_page', $request->input('show', 10));
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $mahasiswa = $query->latest()->paginate($perPage)->withQueryString();

        // FITUR AJAX: Jika request meminta potongan tabel saja (live search/filter)
        if ($request->has('ajax')) {
            return view('admin.mahasiswa.table', compact('mahasiswa'))->render();
        }

        // Data Statistik Utama untuk Pencatatan Kartu
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalAktif = User::role('mahasiswa')->where('status', 'aktif')->count();
        $totalCuti = User::role('mahasiswa')->where('status', 'cuti')->count();
        $totalNonAktif = User::role('mahasiswa')->where('status', 'non_aktif')->count();

        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.mahasiswa.index', compact(
            'mahasiswa',
            'totalMahasiswa',
            'totalAktif',
            'totalCuti',
            'totalNonAktif',
            'programStudiList',
            'search',
            'prodiFilter',
            'statusFilter'
        ));
    }

    /**
     * Tampilkan formulir tambah mahasiswa baru.
     */
    public function create()
    {
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();
        return view('admin.mahasiswa.create', compact('programStudiList'));
    }

    /**
     * Simpan data mahasiswa baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'             => ['required', 'string', 'max:255'],
            'nim'              => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'status'           => ['required', Rule::in(['aktif', 'cuti', 'non_aktif'])],
            'telepon'          => ['nullable', 'string', 'max:20'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ], [
            'nim.unique'   => 'NIM sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        $mahasiswa = User::create([
            'name'             => $validated['nama'],
            'nip_nim'          => $validated['nim'],
            'email'            => $validated['email'],
            'password'         => Hash::make('mahasiswa123'),
            'program_studi_id' => $validated['program_studi_id'] ?? null,
            'status'           => $validated['status'],
            'telepon'          => $validated['telepon'] ?? null,
            'foto'             => $fotoPath,
        ]);

        $mahasiswa->assignRole('mahasiswa');

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Mahasiswa {$mahasiswa->name} berhasil ditambahkan. Password awal: mahasiswa123");
    }

    /**
     * Tampilkan formulir edit mahasiswa (Route Model Binding).
     */
    public function edit(User $mahasiswa)
    {
        abort_unless($mahasiswa->hasRole('mahasiswa'), 404);

        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        // PENYESUAIAN: Dilempar menggunakan kunci 'mahasiswaMember' agar sinkron dengan baris 17 file edit.blade.php
        return view('admin.mahasiswa.edit', [
            'mahasiswaMember'  => $mahasiswa, 
            'programStudiList' => $programStudiList
        ]);
    }

    /**
     * Perbarui data mahasiswa lama (Route Model Binding).
     */
    public function update(Request $request, User $mahasiswa)
    {
        abort_unless($mahasiswa->hasRole('mahasiswa'), 404);

        $validated = $request->validate([
            'nama'             => ['required', 'string', 'max:255'],
            'nim'              => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')->ignore($mahasiswa->id)],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($mahasiswa->id)],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'status'           => ['required', Rule::in(['aktif', 'cuti', 'non_aktif'])],
            'telepon'          => ['nullable', 'string', 'max:20'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ], [
            'nim.unique'   => 'NIM sudah terdaftar di sistem.',
            'email.unique' => 'Email sudah terdaftar di sistem.',
        ]);

        $fotoPath = $mahasiswa->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        $mahasiswa->update([
            'name'             => $validated['nama'],
            'nip_nim'          => $validated['nim'],
            'email'            => $validated['email'],
            'program_studi_id' => $validated['program_studi_id'] ?? null,
            'status'           => $validated['status'],
            'telepon'          => $validated['telepon'] ?? null,
            'foto'             => $fotoPath,
        ]);

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', "Data mahasiswa {$mahasiswa->name} berhasil diperbarui.");
    }

    /**
     * Hapus permanen data mahasiswa beserta berkas foto profilnya.
     */
    public function destroy(User $mahasiswa)
    {
        abort_unless($mahasiswa->hasRole('mahasiswa'), 404);

        if ($mahasiswa->foto) {
            Storage::disk('public')->delete($mahasiswa->foto);
        }

        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil dihapus.');
    }

    /**
     * 1. Unduh Template File CSV (Kompatibel dengan Microsoft Excel)
     */
    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_impor_mahasiswa.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // Sisipkan UTF-8 BOM agar Microsoft Excel di Windows membaca karakter tanpa masalah encoding
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header Kolom Sesuai Spesifikasi
            fputcsv($handle, [
                'NIM',
                'Nama Lengkap',
                'Email',
                'No. Telepon',
                'Program Studi',
                'Status'
            ], ';');

            // Baris Sampel Data
            $sampleProdi = ProgramStudi::first();
            $namaProdiContoh = $sampleProdi ? $sampleProdi->nama_prodi : 'Teknik Informatika';

            fputcsv($handle, ['20261001', 'Ahmad Dahlan', 'ahmad.dahlan@student.cendekia.ac.id', '081234567890', $namaProdiContoh, 'Aktif'], ';');
            fputcsv($handle, ['20261002', 'Siti Aminah', 'siti.aminah@student.cendekia.ac.id', '089876543210', $namaProdiContoh, 'Cuti'], ';');
            fputcsv($handle, ['20261003', 'Rian Hidayat', 'rian.hidayat@student.cendekia.ac.id', '', $namaProdiContoh, 'Non-Aktif'], ';');

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 2. Unggah Berkas & Tinjau Data (Preview & Validasi Sebelum Simpan)
     */
    public function previewImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_mahasiswa' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file_mahasiswa.required' => 'Pilih berkas CSV terlebih dahulu.',
            'file_mahasiswa.mimes'    => 'Format file harus berupa CSV (.csv) atau Text (.txt).',
            'file_mahasiswa.max'      => 'Ukuran berkas maksimal 5MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $file = $request->file('file_mahasiswa');
        $path = $file->getRealPath();

        // Deteksi pembatas kolom (delimiter): koma (,) atau titik koma (;)
        $firstLine = fgets(fopen($path, 'r'));
        $delimiter = (substr_count($firstLine, ';') >= substr_count($firstLine, ',')) ? ';' : ',';

        $handle = fopen($path, 'r');

        // Cache data database ke memori agar validasi cepat
        $existingNims = User::whereNotNull('nip_nim')->pluck('nip_nim')->flip()->toArray();
        $existingEmails = User::whereNotNull('email')->pluck('email')->map(fn($e) => strtolower(trim($e)))->flip()->toArray();
        
        $allProdis = ProgramStudi::all();
        $prodiMap = [];
        foreach ($allProdis as $p) {
            $prodiMap[(string) $p->id] = $p;
            $prodiMap[strtolower(trim($p->nama_prodi))] = $p;
            if (!empty($p->kode_prodi)) {
                $prodiMap[strtolower(trim($p->kode_prodi))] = $p;
            }
        }

        $previewData = [];
        $fileNims = [];
        $fileEmails = [];
        $totalValid = 0;
        $totalInvalid = 0;
        $rowNumber = 0;

        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            $rowNumber++;

            // Abaikan baris header pertama
            if ($rowNumber === 1) {
                // Hapus BOM jika ada pada kolom pertama
                $row[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0] ?? '');
                $firstHeader = strtolower(trim($row[0]));
                if ($firstHeader === 'nim' || str_contains($firstHeader, 'nim')) {
                    continue;
                }
            }

            $nim       = trim($row[0] ?? '');
            $nama      = trim($row[1] ?? '');
            $email     = trim($row[2] ?? '');
            $telepon   = trim($row[3] ?? '');
            $prodiRaw  = trim($row[4] ?? '');
            $statusRaw = trim($row[5] ?? 'Aktif');

            // Lewati baris kosong total
            if (empty($nim) && empty($nama) && empty($email) && empty($prodiRaw)) {
                continue;
            }

            $errors = [];

            // A. Validasi NIM (Wajib & Unik)
            if ($nim === '') {
                $errors[] = 'NIM wajib diisi.';
            } elseif (isset($existingNims[$nim])) {
                $errors[] = "NIM '{$nim}' sudah terdaftar di database.";
            } elseif (isset($fileNims[$nim])) {
                $errors[] = "NIM '{$nim}' duplikat dengan baris {$fileNims[$nim]} di dalam berkas.";
            } else {
                $fileNims[$nim] = $rowNumber;
            }

            // B. Validasi Nama Lengkap (Wajib)
            if ($nama === '') {
                $errors[] = 'Nama Lengkap wajib diisi.';
            }

            // C. Validasi Email (Wajib, Format Email, & Unik)
            $emailLower = strtolower($email);
            if ($email === '') {
                $errors[] = 'Email wajib diisi.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid.';
            } elseif (isset($existingEmails[$emailLower])) {
                $errors[] = "Email '{$email}' sudah terdaftar di database.";
            } elseif (isset($fileEmails[$emailLower])) {
                $errors[] = "Email '{$email}' duplikat dengan baris {$fileEmails[$emailLower]} di dalam berkas.";
            } else {
                $fileEmails[$emailLower] = $rowNumber;
            }

            // D. Validasi Program Studi (Wajib & Cocok dengan DB)
            $prodiId = null;
            $prodiNamaTampil = $prodiRaw;
            if ($prodiRaw === '') {
                $errors[] = 'Program Studi wajib diisi.';
            } else {
                $prodiLookupKey = strtolower($prodiRaw);
                if (isset($prodiMap[$prodiLookupKey])) {
                    $prodiId = $prodiMap[$prodiLookupKey]->id;
                    $prodiNamaTampil = $prodiMap[$prodiLookupKey]->nama_prodi;
                } else {
                    $errors[] = "Program Studi '{$prodiRaw}' tidak ditemukan di database.";
                }
            }

            // E. Validasi Status (Wajib: Aktif / Cuti / Non-Aktif)
            $normalizedStatus = strtolower(str_replace([' ', '-'], '_', $statusRaw));
            if ($normalizedStatus === 'nonaktif') {
                $normalizedStatus = 'non_aktif';
            }

            if (!in_array($normalizedStatus, ['aktif', 'cuti', 'non_aktif'])) {
                $errors[] = "Status '{$statusRaw}' tidak valid (Gunakan: Aktif, Cuti, atau Non-Aktif).";
                $normalizedStatus = 'aktif';
            }

            $isValid = count($errors) === 0;
            if ($isValid) {
                $totalValid++;
            } else {
                $totalInvalid++;
            }

            $statusLabels = [
                'aktif'     => 'Aktif',
                'cuti'      => 'Cuti',
                'non_aktif' => 'Non-Aktif',
            ];

            $previewData[] = [
                'row_number'       => $rowNumber,
                'nim'              => $nim,
                'nama'             => $nama,
                'email'            => $email,
                'telepon'          => $telepon,
                'prodi_name'       => $prodiNamaTampil ?: '-',
                'program_studi_id' => $prodiId,
                'status'           => $normalizedStatus,
                'status_label'     => $statusLabels[$normalizedStatus] ?? 'Aktif',
                'is_valid'         => $isValid,
                'errors'           => $errors,
            ];
        }

        fclose($handle);

        return response()->json([
            'success' => true,
            'summary' => [
                'total_rows'    => count($previewData),
                'total_valid'   => $totalValid,
                'total_invalid' => $totalInvalid,
            ],
            'rows'    => $previewData,
        ]);
    }

    /**
     * 3. Simpan Baris Data Mahasiswa yang Lolos Validasi
     */
    public function storeImport(Request $request)
    {
        $rows = $request->input('rows', []);

        if (empty($rows) || !is_array($rows)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data valid yang dikirim untuk diimpor.',
            ], 422);
        }

        $importedCount = 0;

        DB::beginTransaction();
        try {
            foreach ($rows as $item) {
                // Verifikasi flag validitas
                if (empty($item['is_valid']) || $item['is_valid'] == false) {
                    continue;
                }

                // Double check keunikan sebelum insert untuk mencegah race condition
                $exists = User::where('nip_nim', $item['nim'])
                    ->orWhere('email', $item['email'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                $user = User::create([
                    'name'              => $item['nama'],
                    'nip_nim'           => $item['nim'],
                    'email'             => $item['email'],
                    'telepon'           => !empty($item['telepon']) ? $item['telepon'] : null,
                    'program_studi_id'  => $item['program_studi_id'] ?? null,
                    'status'            => $item['status'] ?? 'aktif',
                    'password'          => Hash::make('mahasiswa123'), // Kata sandi awal = mahasiswa123
                    'email_verified_at' => now(),
                ]);

                $user->assignRole('mahasiswa');
                $importedCount++;
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'imported_count' => $importedCount,
                'message'        => "Sukses! Sebanyak {$importedCount} data mahasiswa berhasil diimpor ke sistem.",
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ekspor data mahasiswa ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $search       = trim($request->input('search', ''));
        $prodiFilter  = $request->input('program_studi_id');
        $statusFilter = $request->input('status');

        $query = User::role('mahasiswa')->with('programStudi');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($prodiFilter) {
            $query->where('program_studi_id', $prodiFilter);
        }
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $mahasiswa = $query->latest()->get();

        $prodiName = null;
        if ($prodiFilter) {
            $prodi = ProgramStudi::find($prodiFilter);
            $prodiName = $prodi?->nama_prodi;
        }

        $filters = [
            'search' => $search,
            'prodi'  => $prodiName,
            'status' => $statusFilter,
        ];

        $pdf = Pdf::loadView('admin.mahasiswa.pdf', compact('mahasiswa', 'filters'))
            ->setPaper('a4', 'landscape');

        $filename = 'data-mahasiswa-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Ekspor data mahasiswa ke Excel (.xlsx).
     */
    public function exportExcel(Request $request)
    {
        $search       = trim($request->input('search', ''));
        $prodiFilter  = $request->input('program_studi_id');
        $statusFilter = $request->input('status');

        $filename = 'data-mahasiswa-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new MahasiswaExport($search, $prodiFilter, $statusFilter),
            $filename
        );
    }

    /**
     * Fallback Impor CSV Langsung (Bila dipanggil secara tradisional)
     * Tampilkan halaman UI Impor Data Mahasiswa
     */
    public function importView()
    {
        $prodis = \App\Models\ProgramStudi::all();
        return view('admin.mahasiswa.import', compact('prodis'));
    }

    /**
     * Import Data Mahasiswa dari file CSV
     */
    public function importCsv(Request $request)
    {
        return $this->previewImport($request);
    }
}