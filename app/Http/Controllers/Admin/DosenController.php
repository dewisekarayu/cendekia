<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DosenExport;
use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $prodiFilter = $request->input('program_studi_id');

        $query = User::role('dosen')->with('programStudi');

        // Fitur Pencarian Berjalan (Nama, NIDN/NIP, Email)
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip_nim', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Fitur Filter Program Studi Berjalan
        if ($prodiFilter) {
            $query->where('program_studi_id', $prodiFilter);
        }

        $perPage = (int) $request->input('per_page', $request->input('show', 10));
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $dosen = $query->latest()->paginate($perPage)->withQueryString();
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();

        // KUNCI PENCARIAN CEPAT: Jika request dikirim lewat AJAX ketikan, 
        // kembalikan potongan HTML tabelnya saja tanpa merender layout utama.
        if ($request->has('ajax')) {
            return view('admin.dosen.table', compact('dosen'))->render();
        }

        return view('admin.dosen.index', compact('dosen', 'programStudiList', 'search', 'prodiFilter'));
    }

    public function create()
    {
        $programStudiList = ProgramStudi::orderBy('nama_prodi')->get();
        return view('admin.dosen.create', compact('programStudiList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'nip_nim'          => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'status'           => ['required', 'in:aktif,non_aktif'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        $dosen = User::create([
            'name'             => $validated['name'],
            'nip_nim'          => $validated['nip_nim'],
            'email'            => $validated['email'],
            'password'         => Hash::make('dosen123'),
            'program_studi_id' => $validated['program_studi_id'] ?? null,
            'status'           => $validated['status'],
            'foto'             => $fotoPath,
        ]);

        $dosen->assignRole('dosen');

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil ditambahkan. Password awal: dosen123');
    }

    public function edit($id)
    {
        $dosenMember = User::findOrFail($id);
        abort_unless($dosenMember->hasRole('dosen'), 404);

        $prodiList = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.dosen.edit', compact('dosenMember', 'prodiList'));
    }

    public function update(Request $request, $id)
    {
        $dosenMember = User::findOrFail($id);
        abort_unless($dosenMember->hasRole('dosen'), 404);

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'nip_nim'          => ['required', 'string', 'max:50', Rule::unique('users', 'nip_nim')->ignore($dosenMember->id)],
            'email'            => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($dosenMember->id)],
            'program_studi_id' => ['nullable', 'exists:program_studi,id'],
            'status'           => ['required', 'in:aktif,non_aktif'],
            'foto'             => ['nullable', 'image', 'max:2048'],
        ]);

        $fotoPath = $dosenMember->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        $dosenMember->update([
            'name'             => $validated['name'],
            'nip_nim'          => $validated['nip_nim'],
            'email'            => $validated['email'],
            'program_studi_id' => $validated['program_studi_id'] ?? null,
            'status'           => $validated['status'],
            'foto'             => $fotoPath,
        ]);

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dosen = User::findOrFail($id);
        abort_unless($dosen->hasRole('dosen'), 404);

        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen berhasil dihapus.');
    }

    /**
     * Ekspor data dosen ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $search      = trim($request->input('search', ''));
        $prodiFilter = $request->input('program_studi_id');

        $query = User::role('dosen')->with('programStudi');

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

        $dosen = $query->latest()->get();

        $prodiName = null;
        if ($prodiFilter) {
            $prodi = ProgramStudi::find($prodiFilter);
            $prodiName = $prodi?->nama_prodi;
        }

        $filters = [
            'search' => $search,
            'prodi'  => $prodiName,
        ];

        $pdf = Pdf::loadView('admin.dosen.pdf', compact('dosen', 'filters'))
            ->setPaper('a4', 'landscape');

        $filename = 'data-dosen-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Ekspor data dosen ke Excel (.xlsx).
     */
    public function exportExcel(Request $request)
    {
        $search      = trim($request->input('search', ''));
        $prodiFilter = $request->input('program_studi_id');

        $filename = 'data-dosen-' . now()->format('Ymd-His') . '.xlsx';

        return Excel::download(
            new DosenExport($search, $prodiFilter),
            $filename
        );
    }

    /**
     * 1. Unduh Berkas Template Spreadsheet CSV Dosen
     */
    public function downloadTemplate()
    {
        $filename = 'template-impor-dosen.csv';
        $headers  = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $namaProdiContoh = ProgramStudi::first()?->nama_prodi ?? 'Teknik Informatika';

        $callback = function () use ($namaProdiContoh) {
            $handle = fopen('php://output', 'w');
            // Tambahkan BOM untuk UTF-8 agar nama/gelar terbaca rapi di Excel
            fputs($handle, "\xEF\xBB\xBF");

            // Header Kolom CSV
            fputcsv($handle, [
                'NIP / NIDN',
                'Nama Lengkap & Gelar',
                'Email',
                'No. Telepon',
                'Program Studi',
                'Status'
            ], ';');

            // Baris Contoh 1
            fputcsv($handle, [
                '198501012010011001',
                'Prof. Dr. Budi Santoso, M.Kom.',
                'budi.santoso@dosen.cendekia.ac.id',
                '081234567890',
                $namaProdiContoh,
                'Aktif'
            ], ';');

            // Baris Contoh 2
            fputcsv($handle, [
                '199003152015042002',
                'Dr. Siti Rahmawati, S.T., M.T.',
                'siti.rahmawati@dosen.cendekia.ac.id',
                '082198765432',
                $namaProdiContoh,
                'Aktif'
            ], ';');

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 2. Unggah Berkas & Tinjau Data Dosen (Preview & Validasi Sebelum Simpan)
     */
    public function previewImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_dosen' => 'nullable|file|mimes:csv,txt|max:5120',
            'file_csv'   => 'nullable|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('file_dosen') ?? $request->file('file_csv');

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'Pilih berkas CSV terlebih dahulu.',
            ], 422);
        }

        $path = $file->getRealPath();

        // Deteksi pembatas kolom (delimiter): koma (,) atau titik koma (;)
        $firstLine = fgets(fopen($path, 'r'));
        $delimiter = (substr_count($firstLine, ';') >= substr_count($firstLine, ',')) ? ';' : ',';

        $handle = fopen($path, 'r');

        // Cache data database ke memori agar validasi cepat
        $existingNips   = User::whereNotNull('nip_nim')->pluck('nip_nim')->flip()->toArray();
        $existingEmails = User::whereNotNull('email')->pluck('email')->map(fn($e) => strtolower(trim($e)))->flip()->toArray();

        $allProdis = ProgramStudi::all();
        $prodiMap  = [];
        foreach ($allProdis as $p) {
            $prodiMap[(string) $p->id] = $p;
            $prodiMap[strtolower(trim($p->nama_prodi))] = $p;
            if (!empty($p->kode_prodi)) {
                $prodiMap[strtolower(trim($p->kode_prodi))] = $p;
            }
        }

        $previewData  = [];
        $fileNips     = [];
        $fileEmails   = [];
        $totalValid   = 0;
        $totalInvalid = 0;
        $rowNumber    = 0;

        while (($row = fgetcsv($handle, 2000, $delimiter)) !== false) {
            $rowNumber++;

            // Abaikan baris header pertama
            if ($rowNumber === 1) {
                $row[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0] ?? '');
                $firstHeader = strtolower(trim($row[0]));
                if ($firstHeader === 'nip' || str_contains($firstHeader, 'nip') || str_contains($firstHeader, 'nidn')) {
                    continue;
                }
            }

            $nip       = trim($row[0] ?? '');
            $nama      = trim($row[1] ?? '');
            $email     = trim($row[2] ?? '');
            $telepon   = trim($row[3] ?? '');
            $prodiRaw  = trim($row[4] ?? '');
            $statusRaw = trim($row[5] ?? 'Aktif');

            // Lewati baris kosong total
            if (empty($nip) && empty($nama) && empty($email) && empty($prodiRaw)) {
                continue;
            }

            $errors = [];

            // A. Validasi NIP (Wajib & Unik)
            if ($nip === '') {
                $errors[] = 'NIP / NIDN wajib diisi.';
            } elseif (isset($existingNips[$nip])) {
                $errors[] = "NIP '{$nip}' sudah terdaftar di database.";
            } elseif (isset($fileNips[$nip])) {
                $errors[] = "NIP '{$nip}' duplikat dengan baris {$fileNips[$nip]} di dalam berkas.";
            } else {
                $fileNips[$nip] = $rowNumber;
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
                'nip'              => $nip,
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
     * 3. Simpan Baris Data Dosen yang Lolos Validasi
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
                if (empty($item['is_valid']) || $item['is_valid'] == false) {
                    continue;
                }

                $nip = $item['nip'] ?? ($item['nim'] ?? '');

                $exists = User::where('nip_nim', $nip)
                    ->orWhere('email', $item['email'])
                    ->exists();

                if ($exists) {
                    continue;
                }

                $user = User::create([
                    'name'              => $item['nama'],
                    'nip_nim'           => $nip,
                    'email'             => $item['email'],
                    'telepon'           => !empty($item['telepon']) ? $item['telepon'] : null,
                    'program_studi_id'  => $item['program_studi_id'] ?? null,
                    'status'            => $item['status'] ?? 'aktif',
                    'password'          => Hash::make($nip), // Kata sandi awal = NIP
                    'email_verified_at' => now(),
                ]);

                $user->assignRole('dosen');
                $importedCount++;
            }

            DB::commit();

            return response()->json([
                'success'        => true,
                'imported_count' => $importedCount,
                'message'        => "Sukses! Sebanyak {$importedCount} data dosen berhasil diimpor ke sistem.",
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
     * Tampilkan halaman UI Impor Data Dosen
     */
    public function importView()
    {
        $prodis = ProgramStudi::all();
        return view('admin.dosen.import', compact('prodis'));
    }

    /**
     * Import Data Dosen dari file CSV (Fallback)
     */
    public function importCsv(Request $request)
    {
        return $this->previewImport($request);
    }
}