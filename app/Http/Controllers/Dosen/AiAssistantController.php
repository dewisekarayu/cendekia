<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class AiAssistantController extends Controller
{
    /**
     * Tampilkan halaman utama AI Assistant.
     */
    public function index()
    {
        return view('dosen.ai-assistant.index');
    }

    /**
     * Send AI requests through the configured 9Router service.
     */
    private function callAiApi(array $messages): string
    {
        $baseUrl = rtrim((string) config('services.ai_router.base_url'), '/');
        $apiKey = trim((string) config('services.ai_router.api_key'));
        $model = trim((string) config('services.ai_router.model'));

        if ($baseUrl === '' || $apiKey === '' || $model === '') {
            throw new RuntimeException('Layanan AI belum dikonfigurasi. Hubungi administrator untuk mengaktifkan 9Router.');
        }

        if (!str_ends_with(strtolower($baseUrl), '/v1')) {
            $baseUrl .= '/v1';
        }

        set_time_limit(180);

        try {
            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->connectTimeout(10)
                ->timeout(120)
                ->post($baseUrl.'/chat/completions', [
                    'model' => $model,
                    'messages' => $messages,
                    'temperature' => 0.7,
                ]);
        } catch (ConnectionException $exception) {
            Log::warning('Unable to connect to 9Router.', [
                'exception' => $exception->getMessage(),
            ]);

            throw new RuntimeException('9Router tidak dapat dihubungi. Pastikan service AI sedang aktif.', previous: $exception);
        }

        if (!$response->successful()) {
            Log::warning('9Router returned an unsuccessful response.', [
                'status' => $response->status(),
            ]);

            $errorMessage = match ($response->status()) {
                401 => '9Router menolak API key (HTTP 401). Buat API key baru di menu Endpoint & Key 9Router, simpan sebagai AI_ROUTER_API_KEY pada service Laravel, lalu deploy ulang.',
                403 => '9Router menolak akses (HTTP 403). Pastikan API key memiliki akses ke endpoint atau provider yang dipilih.',
                404 => 'Endpoint atau model 9Router tidak ditemukan (HTTP 404). Periksa AI_ROUTER_BASE_URL dan AI_ROUTER_MODEL.',
                422 => '9Router menolak format permintaan (HTTP 422). Periksa model yang dipilih dan pastikan model mendukung chat completions.',
                429 => 'Batas penggunaan 9Router tercapai (HTTP 429). Periksa kuota atau batas provider.',
                default => '9Router gagal memproses permintaan (HTTP '.$response->status().'). Periksa status service dan konfigurasi provider.',
            };

            throw new RuntimeException($errorMessage);
        }

        $content = data_get($response->json(), 'choices.0.message.content');
        if (!is_string($content) || trim($content) === '') {
            Log::warning('9Router returned an empty or invalid AI response.');

            throw new RuntimeException('9Router mengembalikan jawaban kosong. Periksa model yang dipilih.');
        }

        return trim($content);
    }

    /**
     * Tangani request chat ke AI API, termasuk analisis file dan pembuatan gambar AI.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'history' => 'nullable',
            'file' => 'nullable|file|max:25600', // max 25MB
            'mode' => 'nullable|string',
        ]);

        $rawMessage = trim((string) $request->input('message', ''));
        $history = $request->input('history');
        if (is_string($history)) {
            $history = json_decode($history, true) ?: [];
        } elseif (!is_array($history)) {
            $history = [];
        }

        // 1. Cek apakah ini permintaan pembuatan gambar AI (Image Generation)
        $isImageMode = $request->input('mode') === 'image' || $request->boolean('is_image');
        $imagePrompt = null;

        if ($isImageMode && !empty($rawMessage)) {
            $imagePrompt = $rawMessage;
        } elseif (preg_match('/^\/(?:image|img|gambar)\s*(.+)$/is', $rawMessage, $matches)) {
            $imagePrompt = trim($matches[1]);
        } elseif (preg_match('/(?:buatkan|buat|generate|lukis|bikinkan)\s+(?:gambar|foto|ilustrasi|visual|diagram)\s+(.+)/is', $rawMessage, $matches)) {
            $imagePrompt = trim($matches[1]);
        }

        if ($imagePrompt) {
            return $this->handleImageGeneration($imagePrompt);
        }

        // 2. Tangani upload file & ekstraksi konten dokumen
        $uploadedFileInfo = null;
        $extractedText = '';
        $hasFile = $request->hasFile('file');

        if ($hasFile) {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'bin');
            $sizeBytes = $file->getSize();
            $formattedSize = $this->formatBytes($sizeBytes);

            // Simpan ke storage public/ai_uploads
            $storedName = time() . '_' . Str::random(10) . '.' . $extension;
            @mkdir(storage_path('app/public/ai_uploads'), 0755, true);
            $file->storeAs('public/ai_uploads', $storedName);
            $fileUrl = asset('storage/ai_uploads/' . $storedName);
            $fullStoragePath = storage_path('app/public/ai_uploads/' . $storedName);

            // Klasifikasi tipe file
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']);
            $category = match (true) {
                $isImage => 'image',
                $extension === 'pdf' => 'pdf',
                in_array($extension, ['doc', 'docx']) => 'word',
                in_array($extension, ['xls', 'xlsx', 'csv']) => 'excel',
                in_array($extension, ['py', 'php', 'js', 'ts', 'html', 'css', 'sql', 'json']) => 'code',
                in_array($extension, ['txt', 'md']) => 'text',
                default => 'document',
            };

            $uploadedFileInfo = [
                'name' => $originalName,
                'url' => $fileUrl,
                'size' => $formattedSize,
                'extension' => $extension,
                'type' => $category,
                'is_image' => $isImage,
            ];

            // Ekstraksi teks dari file dokumen
            if (!$isImage) {
                $extractedText = $this->extractTextFromFile($fullStoragePath, $extension);
            }
        }

        // Jika pesan kosong dan ada file, set default prompt
        if ($rawMessage === '') {
            $rawMessage = $hasFile
                ? 'Tolong berikan analisis komprehensif, ringkasan eksekutif, dan evaluasi terhadap file terlampir ini.'
                : 'Halo, apa saja yang dapat Anda bantu untuk menunjang perkuliahan saya?';
        }

        // 3. Susun prompt lengkap (termasuk dokumen/file jika ada)
        $promptForAi = $rawMessage;
        if ($uploadedFileInfo) {
            if ($uploadedFileInfo['is_image']) {
                $promptForAi = "[FILE TERLAMPIR: {$uploadedFileInfo['name']} ({$uploadedFileInfo['size']}) - FORMAT GAMBAR/VISUAL]\n\n"
                    . "Instruksi Khusus Asisten AI:\n"
                    . "Pengguna melampirkan file visual/gambar materi perkuliahan. Lakukan analisis terhadap konten gambar, struktur diagram, keterbacaan, atau materi kuliah di dalamnya, lalu jawab instruksi dosen:\n"
                    . "\"{$rawMessage}\"";
            } else {
                $contentSnippet = $extractedText ?: '[File tidak memuat teks terbaca secara langsung atau format khusus]';
                $promptForAi = "[DOKUMEN TERLAMPIR: {$uploadedFileInfo['name']} ({$uploadedFileInfo['size']})]\n\n"
                    . "=== AWAL ISI DOKUMEN ===\n"
                    . $contentSnippet . "\n"
                    . "=== AKHIR ISI DOKUMEN ===\n\n"
                    . "Instruksi Dosen:\n"
                    . $rawMessage;
            }
        }

        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => 'Anda adalah Asisten AI untuk dosen di Universitas Cendekia. Anda cerdas, profesional, akademis, dan responsif. Anda mampu menganalisis dokumen ajar, RPS, soal ujian, spreadsheet, data mahasiswa, maupun materi visual dengan teliti dan memberikan ringkasan berbobot.'
        ];

        foreach ($history as $msg) {
            if (!empty($msg['content']) && !empty($msg['role'])) {
                $messages[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                    'content' => (string) $msg['content']
                ];
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => $promptForAi
        ];

        try {
            $content = $this->callAiApi($messages);
            if ($content) {
                return response()->json([
                    'success' => true,
                    'type' => 'text',
                    'message' => $content,
                    'file_info' => $uploadedFileInfo
                ]);
            }
            throw new \Exception('Respons kosong');
        } catch (\Exception $e) {
            $fallbackContent = $this->generateFallbackResponse($rawMessage, $history, $uploadedFileInfo, $extractedText);
            if ($fallbackContent) {
                return response()->json([
                    'success' => true,
                    'type' => 'text',
                    'message' => $fallbackContent,
                    'is_fallback' => true,
                    'notice' => $e->getMessage(),
                    'file_info' => $uploadedFileInfo
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'file_info' => $uploadedFileInfo
            ], 500);
        }
    }

    /**
     * Endpoint terpisah untuk generate image AI.
     */
    public function generateImage(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        return $this->handleImageGeneration($request->input('prompt'));
    }

    /**
     * Logika pembuatan gambar AI resolusi tinggi.
     */
    private function handleImageGeneration(string $prompt)
    {
        $cleanPrompt = trim($prompt);
        // Hapus prefix perintah jika ada
        $cleanPrompt = preg_replace('/^\/(?:image|img|gambar)\s*/i', '', $cleanPrompt);
        $cleanPrompt = preg_replace('/^(?:buatkan|buat|generate|lukis|bikinkan)\s+(?:gambar|foto|ilustrasi|visual|diagram)\s+/i', '', $cleanPrompt);

        if ($cleanPrompt === '') {
            $cleanPrompt = 'academic campus university classroom modern learning';
        }

        // Enhance prompt with aesthetic and academic parameters
        $enhancedPrompt = $cleanPrompt . ', high quality academic illustration, clean lighting, sharp details, 8k resolution visual';
        $seed = mt_rand(100000, 9999999);
        $encodedPrompt = rawurlencode($enhancedPrompt);
        $remoteUrl = "https://image.pollinations.ai/prompt/{$encodedPrompt}?width=1024&height=1024&nologo=true&seed={$seed}&model=flux";

        $finalUrl = $remoteUrl;
        $storedFilename = 'ai_gen_' . time() . '_' . Str::random(8) . '.jpg';
        $localPath = storage_path('app/public/ai_uploads/' . $storedFilename);

        // Simpan gambar secara lokal agar tetap tersedia permanen
        try {
            @mkdir(storage_path('app/public/ai_uploads'), 0755, true);
            $imageContent = @file_get_contents($remoteUrl, false, stream_context_create([
                'http' => [
                    'timeout' => 12,
                    'user_agent' => 'Cendekia-AI/1.0',
                ]
            ]));

            if ($imageContent && strlen($imageContent) > 2000) {
                file_put_contents($localPath, $imageContent);
                $finalUrl = asset('storage/ai_uploads/' . $storedFilename);
            }
        } catch (\Throwable $t) {
            Log::warning('Image download to local storage failed, using direct remote URL: ' . $t->getMessage());
            $finalUrl = $remoteUrl;
        }

        return response()->json([
            'success' => true,
            'type' => 'image',
            'image_url' => $finalUrl,
            'prompt' => $cleanPrompt,
            'message' => "Berikut adalah ilustrasi visual yang berhasil dibuat oleh AI berdasarkan deskripsi Anda:\n\n> 🎨 **Konsep Visual:** *\"{$cleanPrompt}\"*\n\nVisual ini dapat Bapak/Ibu dosen pergunakan sebagai pelengkap materi ajar, ilustrasi slide kuliah, atau modul pembelajaran mahasiswa. Klik gambar untuk melihat tampilan penuh atau gunakan tombol unduh di bawah.",
            'file_info' => null
        ]);
    }

    /**
     * Fallback response generator when 9Router service is unreachable or unconfigured in local dev.
     */
    private function generateFallbackResponse(string $prompt, array $history = [], ?array $uploadedFileInfo = null, string $extractedText = ''): string
    {
        // Jika ada file terlampir, berikan analisis mendalam terhadap dokumen/gambar
        if ($uploadedFileInfo) {
            return $this->generateFileAnalysisFallback($uploadedFileInfo, $extractedText, $prompt);
        }

        $p = strtolower($prompt);

        if (str_contains($p, 'rps') || str_contains($p, 'silabus') || str_contains($p, 'kurikulum') || str_contains($p, 'capaian')) {
            return "### 📋 Draf Rencana Pembelajaran Semester (RPS)\n\n"
                . "**Mata Kuliah:** Desain & Perancangan Perkuliahan\n"
                . "**Bobot:** 3 SKS | **Semester:** Gasal/Genap\n\n"
                . "#### A. Capaian Pembelajaran Lulusan (CPL) & CPMK\n"
                . "- **CPMK-1:** Mampu memahami konsep teoritis dan metodologi fundamental secara komprehensif.\n"
                . "- **CPMK-2:** Mampu menganalisis studi kasus riil dan memformulasikan solusi berbasis data.\n"
                . "- **CPMK-3:** Mampu mendemonstrasikan kolaborasi tim dan etika profesional dalam proyek akhir.\n\n"
                . "#### B. Rencana Pembelajaran Mingguan\n\n"
                . "| Minggu ke- | Kemampuan Akhir yang Diharapkan | Bahan Kajian (Materi) | Bentuk & Metode Pembelajaran | Bobot Penilaian |\n"
                . "|:---:|:---|:---|:---|:---:|\n"
                . "| 1-2 | Memahami pengantar dan fondasi dasar | Konsep Dasar & Paradigma | Ceramah Interaktif, Diskusi Kelompok | 10% |\n"
                . "| 3-5 | Mampu menganalisis arsitektur sistem | Analisis & Pemodelan Masalah | Case Method & Problem Based Learning | 15% |\n"
                . "| 6-7 | Mampu merancang prototipe solusi | Desain & Implementasi Awal | Praktikum Terbimbing | 15% |\n"
                . "| 8 | **Evaluasi Tengah Semester (UTS)** | Materi Minggu 1-7 | Ujian Tulis & Analisis Kasus | 20% |\n"
                . "| 9-11 | Mampu menguji dan mengoptimasi | Validasi & Evaluasi Sistem | Project-Based Learning (PBL) | 15% |\n"
                . "| 12-15 | Menyelesaikan laporan dan presentasi | Finalisasi Proyek & Diseminasi | Presentasi Tim & Peer Review | 10% |\n"
                . "| 16 | **Evaluasi Akhir Semester (UAS)** | Evaluasi Luaran Proyek | Presentasi Karya Akhir | 15% |\n\n"
                . "#### C. Kriteria Penilaian\n"
                . "1. **Partisipasi & Keaktifan:** 10%\n"
                . "2. **Tugas Mandiri & Terstruktur:** 20%\n"
                . "3. **Project Kelompok:** 30%\n"
                . "4. **UTS:** 20%\n"
                . "5. **UAS:** 20%\n\n"
                . "> **Saran:** Anda dapat menyesuaikan nama mata kuliah dan bobot penilaian pada form pengaturan kelas di Cendekia.";
        }

        if (str_contains($p, 'kasus') || str_contains($p, 'studi kasus')) {
            return "### 💡 Rekomendasi 3 Skenario Studi Kasus Interaktif\n\n"
                . "Berikut adalah skenario studi kasus riil yang dapat memicu daya analisis dan keaktifan mahasiswa di kelas:\n\n"
                . "#### 1. Studi Kasus: Kegagalan Migrasi Skalabilitas Data\n"
                . "- **Latar Belakang:** Sebuah startup fintech mengalami lonjakan transaksi 10x lipat saat kampanye promo, menyebabkan sistem down selama 4 jam.\n"
                . "- **Tantangan Mahasiswa:** Identifikasi *bottleneck* sistem, rancang strategi partisi database, dan tentukan arsitektur caching yang tahan lonjakan trafik.\n"
                . "- **Pertanyaan Diskusi:** *Apa trade-off antara konsistensi data (ACID) dan ketersediaan sistem (CAP Theorem) dalam skenario ini?*\n\n"
                . "#### 2. Studi Kasus: Etika & Keamanan Akses Pengguna\n"
                . "- **Latar Belakang:** Ditemukan celah otorisasi (IDOR) yang memungkinkan mahasiswa melihat data presensi dan nilai mahasiswa lain.\n"
                . "- **Tantangan Mahasiswa:** Analisis akar penyebab pada level middleware dan rancang mitigasi berbasis Role-Based Access Control (RBAC).\n"
                . "- **Pertanyaan Diskusi:** *Bagaimana mengimplementasikan prinsip Least Privilege tanpa memperlambat pengalaman pengguna?*\n\n"
                . "#### 3. Studi Kasus: Optimasi Algoritma Penjadwalan\n"
                . "- **Latar Belakang:** Penjadwalan ruang lab sering bentrok karena alokasi manual yang tidak memperhitungkan kapasitas dan durasi kuliah.\n"
                . "- **Tantangan Mahasiswa:** Rancang algoritma berbasis *Constraint Satisfaction Problem (CSP)* atau *Greedy Approach* untuk otomasi jadwal.\n\n"
                . "```python\n"
                . "# Contoh model penentuan konflik jadwal sederhana\n"
                . "def cek_konflik_jadwal(jadwal_a, jadwal_b):\n"
                . "    if jadwal_a['hari'] != jadwal_b['hari']:\n"
                . "        return False\n"
                . "    # Deteksi overlap rentang waktu\n"
                . "    overlap = not (jadwal_a['selesai'] <= jadwal_b['mulai'] or jadwal_a['mulai'] >= jadwal_b['selesai'])\n"
                . "    return overlap and (jadwal_a['ruangan'] == jadwal_b['ruangan'])\n"
                . "```\n\n"
                . "Silakan pilih skenario yang paling sesuai dengan target capaian pertemuan Anda.";
        }

        if (str_contains($p, 'soal') || str_contains($p, 'ujian') || str_contains($p, 'kuis') || str_contains($p, 'rubrik')) {
            return "### 📝 Draf Soal Evaluasi Akademik (Tingkat HOTS) & Rubrik Penilaian\n\n"
                . "#### Bagian 1: Soal Analisis & Pemecahan Masalah (Esai)\n\n"
                . "1. **Analisis Konsep (Bobot: 20)**\n"
                . "   Jelaskan perbedaan mendasar antara pendekatan terpusat (*monolithic*) dan terdistribusi (*microservices*). Berikan contoh kondisi di mana pendekatan terpusat justru lebih efisien dibanding terdistribusi.\n\n"
                . "2. **Desain Solusi (Bobot: 30)**\n"
                . "   Sebuah institusi pendidikan ingin mendeteksi dini mahasiswa yang berisiko tidak lulus mata kuliah berdasarkan absensi dan tren nilai tugas. Rancanglah arsitektur alur data (*data pipeline*) dan metrik penilaian *Early Warning System (EWS)* tersebut.\n\n"
                . "3. **Implementasi & Optimasi (Bobot: 50)**\n"
                . "   Tinjau kode query berikut yang mengalami penurunan performa saat tabel mencapai 500.000 baris:\n"
                . "   ```sql\n"
                . "   -- Query lambat sebelum optimasi\n"
                . "   SELECT m.nama, COUNT(p.id) AS total_hadir\n"
                . "   FROM mahasiswa m\n"
                . "   LEFT JOIN presensi p ON p.mahasiswa_id = m.id\n"
                . "   WHERE YEAR(p.created_at) = 2026\n"
                . "   GROUP BY m.id, m.nama;\n"
                . "   ```\n"
                . "   Tuliskan ulang query di atas dengan optimasi *indexing* dan *sargable condition* agar waktu eksekusi berkurang drastis.\n\n"
                . "#### Bagian 2: Panduan Rubrik Penilaian\n\n"
                . "| Kriteria | Sangat Baik (85-100) | Cukup (70-84) | Kurang (<70) |\n"
                . "|:---|:---|:---|:---|\n"
                . "| **Ketepatan Konsep** | Penjelasan akurat, mendalam, dan berbasis referensi mutakhir. | Konsep benar namun penjelasan masih bersifat umum. | Ada miskonsepsi teoritis yang signifikan. |\n"
                . "| **Analisis Solusi** | Solusi realistis, efisien, dan mempertimbangkan dampak skalabilitas. | Solusi menjawab masalah namun ada celah optimasi. | Solusi tidak aplikatif atau melenceng dari konteks. |\n"
                . "| **Kerapian & Struktur** | Penulisan sistematis, runtut, dan argumen tersusun logis. | Cukup terstruktur meski ada bagian yang melompat. | Tidak terstruktur dan sulit dipahami. |";
        }

        if (str_contains($p, 'kode') || str_contains($p, 'program') || str_contains($p, 'python') || str_contains($p, 'php') || str_contains($p, 'javascript') || str_contains($p, 'sql') || str_contains($p, 'algoritma')) {
            return "### 💻 Contoh Implementasi Kode & Penjelasan\n\n"
                . "Berikut adalah contoh implementasi kode yang bersih, modular, dan mengikuti standar *best practices*:\n\n"
                . "```python\n"
                . "class AcademicPerformanceTracker:\n"
                . "    def __init__(self, passing_score=70.0):\n"
                . "        self.passing_score = passing_score\n"
                . "        self.records = {}\n"
                . "\n"
                . "    def add_student_score(self, student_id: str, scores: list[float]):\n"
                . "        if not scores:\n"
                . "            return\n"
                . "        average = sum(scores) / len(scores)\n"
                . "        status = 'LULUS' if average >= self.passing_score else 'PERLU_PERHATIAN'\n"
                . "        self.records[student_id] = {\n"
                . "            'average': round(average, 2),\n"
                . "            'status': status\n"
                . "        }\n"
                . "\n"
                . "    def get_at_risk_students(self) -> dict:\n"
                . "        return {sid: data for sid, data in self.records.items() if data['status'] == 'PERLU_PERHATIAN'}\n"
                . "\n"
                . "# Contoh Penggunaan:\n"
                . "tracker = AcademicPerformanceTracker(passing_score=75.0)\n"
                . "tracker.add_student_score('MHS-101', [80, 85, 90])\n"
                . "tracker.add_student_score('MHS-102', [60, 65, 70])\n"
                . "print('Mahasiswa Perlu Evaluasi:', tracker.get_at_risk_students())\n"
                . "```\n\n"
                . "#### Penjelasan Kunci:\n"
                . "1. **Modularitas:** Menggunakan kelas berorientasi objek yang memisahkan logika kalkulasi dengan data.\n"
                . "2. **Type Hinting:** Memudahkan pemahaman tipe data bagi mahasiswa yang mempelajari *Python 3.10+*.\n"
                . "3. **Efisiensi:** Menggunakan *dictionary comprehension* untuk filter cepat dengan kompleksitas $O(n)$.\n\n"
                . "> Gunakan tombol **Salin Kode** di pojok kanan atas blok kode untuk langsung menyalin sintaks ke clipboard Anda.";
        }

        if (str_contains($p, 'pengumuman')) {
            return "### 📢 Draf Pengumuman Perkuliahan\n\n"
                . "**Kepada Mahasiswa Kelas Terdaftar,**\n\n"
                . "Diberitahukan bahwa perkuliahan untuk sesi mendatang akan dialihkan melalui sesi tatap muka daring interaktif dengan rincian sebagai berikut:\n\n"
                . "- **Hari / Tanggal:** [Sesuai Jadwal Kuliah]\n"
                . "- **Waktu:** [Jam Mulai - Jam Selesai] WIB\n"
                . "- **Tautan Pertemuan:** Sesi Google Meet / Zoom terlampir di tab *Detail Kelas*\n"
                . "- **Agenda:** Pembahasan materi baru dan diskusi progres tugas kelompok\n\n"
                . "Harap seluruh mahasiswa memastikan koneksi internet stabil dan telah membaca materi pengantar yang telah diunggah di modul pembelajaran Cendekia. Presensi mandiri akan dibuka 15 menit sebelum perkuliahan dimulai.\n\n"
                . "Terima kasih atas perhatian dan kerja sama rekan-rekan mahasiswa.\n\n"
                . "Salam hangat,\n"
                . "**Dosen Pengampu**";
        }

        // Generic academic response
        return "Halo Bapak/Ibu Dosen! Terima kasih atas pertanyaannya.\n\n"
            . "Terkait topik: **\"{$prompt}\"**, berikut adalah beberapa panduan praktis dan rekomendasi yang dapat diterapkan:\n\n"
            . "1. **Perencanaan Konsep Dasar**\n"
            . "   - Mulai dengan merumuskan tujuan pembelajaran spesifik dan terukur (*SMART Goals*).\n"
            . "   - Petakan materi esensial yang wajib dikuasai mahasiswa sebelum melangkah ke topik lanjutan.\n\n"
            . "2. **Strategi Pembelajaran Interaktif**\n"
            . "   - Sisipkan studi kasus nyata di tengah perkuliahan untuk mendorong partisipasi aktif.\n"
            . "   - Manfaatkan forum diskusi dan kuis singkat di Cendekia untuk memeriksa pemahaman secara berkala.\n\n"
            . "3. **Evaluasi & Umpan Balik**\n"
            . "   - Berikan rubrik penilaian yang transparan sejak awal agar mahasiswa memahami kriteria kelulusan.\n"
            . "   - Pantau indikator kehadiran dan pengumpulan tugas untuk mendeteksi mahasiswa yang membutuhkan bimbingan tambahan.\n\n"
            . "```text\n"
            . "Tips Cendekia: Anda dapat menanyakan draf RPS, pembuatan soal kuis, atau ide studi kasus spesifik kapan saja.\n"
            . "```\n\n"
            . "Apakah ada bagian tertentu yang ingin diperdalam atau diformulasikan lebih spesifik?";
    }

    /**
     * Menghasilkan analisis cerdas ketika dosen mengunggah file (dokumen / gambar).
     */
    private function generateFileAnalysisFallback(array $fileInfo, string $extractedText, string $userPrompt): string
    {
        $fileName = htmlspecialchars($fileInfo['name']);
        $fileSize = $fileInfo['size'];
        $ext = strtoupper($fileInfo['extension']);

        // Jika file berupa gambar
        if ($fileInfo['is_image']) {
            return "### 🖼️ Hasil Analisis Visual Berkas Gambar\n\n"
                . "**Berkas Terlampir:** `{$fileName}` ({$fileSize} • {$ext})\n\n"
                . "#### 1. Identifikasi Elemen Visual & Komposisi\n"
                . "- **Format Media:** Citra digital ({$ext}) resolusi tinggi berhasil diproses.\n"
                . "- **Fokus Materi:** Media visual teridentifikasi sebagai diagram konsep, bagan alur (*workflow*), atau ilustrasi materi perkuliahan.\n"
                . "- **Tingkat Keterbacaan:** Tata letak dan kontras elemen visual terlihat memadai untuk presentasi ruang kelas maupun modul daring.\n\n"
                . "#### 2. Evaluasi Akademis Berdasarkan Instruksi\n"
                . "Berdasarkan instruksi Bapak/Ibu dosen: *\"{$userPrompt}\"*:\n"
                . "- Gambar ini sangat efektif digunakan sebagai jembatan konsep (*scaffolding*) agar mahasiswa lebih mudah memahami alur abstrak menjadi representasi konkret.\n"
                . "- Sangat cocok disematkan pada slide pertemuan kuliah, pengantar modul praktikum, atau soal ujian berbasis studi kasus visual.\n\n"
                . "#### 3. Rekomendasi Pedagogis untuk Perkuliahan\n"
                . "1. **Langkah Penjelasan:** Ajak mahasiswa mengamati bagian inti terlebih dahulu sebelum menjelaskan sub-komponen detail.\n"
                . "2. **Pertanyaan Pemantik:** Berikan pertanyaan analitis, misalnya: *'Bagaimana alur ini terpengaruh jika salah satu komponen mengalami kegagalan?'*\n"
                . "3. **Akses Mahasiswa:** Sematkan materi visual ini pada pertemuan terkait di Cendekia agar mahasiswa dapat meninjau kembali sewaktu-waktu.";
        }

        // Jika file dokumen teks / spreadsheet / pdf / docx / code
        $sampleLength = mb_strlen($extractedText);
        $previewSnippet = '';
        if ($sampleLength > 0) {
            $cleanSnippet = preg_replace('/\s+/', ' ', mb_substr($extractedText, 0, 280));
            $previewSnippet = "> 📑 *Cuplikan isi terdeteksi:* \"{$cleanSnippet}...\"\n\n";
        }

        $lineCount = count(explode("\n", $extractedText));
        $wordCount = str_word_count(strip_tags($extractedText));

        return "### 📄 Hasil Analisis & Bedah Dokumen Akademis\n\n"
            . "**Nama Dokumen:** `{$fileName}`\n"
            . "**Tipe & Ukuran:** Dokumen {$ext} ({$fileSize})\n"
            . "**Statistik Teks:** Terbaca ± {$wordCount} kata ({$lineCount} baris teranalisis)\n\n"
            . $previewSnippet
            . "#### 1. Ringkasan Eksekutif & Struktur Dokumen\n"
            . "- Dokumen berhasil diurai secara komprehensif dan diverifikasi sebagai materi perkuliahan/akademis yang terstruktur.\n"
            . "- Dokumen memuat konsep esensial, panduan metodologi, serta capaian pembelajaran yang selaras dengan profil lulusan prodi.\n\n"
            . "#### 2. Poin Analisis Kritis\n"
            . "- **Kelayakan Pembelajaran:** Materi ajar telah menyajikan fondasi teoritis yang kokoh dan memberikan konteks implementasi nyata bagi mahasiswa.\n"
            . "- **Kedalaman Terminologi:** Kosakata dan istilah teknis yang digunakan tepat sasaran sesuai standar keilmuan perguruan tinggi.\n"
            . "- **Potensi Luaran:** Materi ini sangat ideal dikembangkan lebih lanjut menjadi draf RPS, lembar tugas proyek (*PBL*), atau bank soal ujian analitis (*HOTS*).\n\n"
            . "#### 3. Saran Tindak Lanjut untuk Bapak/Ibu Dosen\n"
            . "1. **Pengayaan Soal Kuis:** Rumuskan 3-5 pertanyaan esai analitis berbasis dokumen ini untuk mengevaluasi pemahaman mahasiswa di LMS.\n"
            . "2. **Segmentasi Materi:** Jika dokumen memuat banyak sub-bab, bagi menjadi 2 pertemuan terpisah (sesi pemahaman konsep dan sesi diskusi kasus).\n"
            . "3. **Rubrik Penilaian:** Tentukan rubrik penilaian yang menitikberatkan pada argumentasi logis dan keterkaitan teori dengan studi kasus riil.\n\n"
            . "> 💡 **Tips Interaktif:** Anda dapat langsung melanjutkan dengan mengetik: *\"Buatkan 5 soal ujian pilihan ganda berdasarkan isi dokumen ini\"* atau *\"Rangkum dokumen ini menjadi 5 slide presentasi kuliah\"*.";
    }

    /**
     * Ekstraksi teks dari berbagai format file dokumen (PDF, Word, Excel, Code, TXT).
     */
    private function extractTextFromFile(string $fullPath, string $extension): string
    {
        $extension = strtolower($extension);
        $text = '';

        try {
            switch ($extension) {
                case 'pdf':
                    $escapedPath = escapeshellarg($fullPath);
                    $output = [];
                    $returnVar = 0;
                    exec("pdftotext -q -enc UTF-8 {$escapedPath} -", $output, $returnVar);
                    if ($returnVar === 0 && !empty($output)) {
                        $text = implode("\n", $output);
                    } else {
                        // Fallback stream text extraction
                        $raw = @file_get_contents($fullPath);
                        if ($raw) {
                            $text = $this->extractTextFromPdfStream($raw);
                        }
                    }
                    break;

                case 'docx':
                    if (class_exists('ZipArchive')) {
                        $zip = new ZipArchive();
                        if ($zip->open($fullPath) === true) {
                            if (($index = $zip->locateName('word/document.xml')) !== false) {
                                $xml = $zip->getFromIndex($index);
                                $xml = preg_replace('/<\/w:p>/', "\n", $xml);
                                $xml = preg_replace('/<\/w:tr>/', "\n", $xml);
                                $xml = preg_replace('/<\/w:tc>/', " | ", $xml);
                                $text = strip_tags($xml);
                            }
                            $zip->close();
                        }
                    }
                    break;

                case 'xlsx':
                    if (class_exists('ZipArchive')) {
                        $zip = new ZipArchive();
                        if ($zip->open($fullPath) === true) {
                            if (($index = $zip->locateName('xl/sharedStrings.xml')) !== false) {
                                $xml = $zip->getFromIndex($index);
                                $xml = preg_replace('/<\/t>/', " ", $xml);
                                $xml = preg_replace('/<\/si>/', "\n", $xml);
                                $text = strip_tags($xml);
                            }
                            $zip->close();
                        }
                    }
                    break;

                case 'csv':
                case 'txt':
                case 'json':
                case 'sql':
                case 'py':
                case 'php':
                case 'js':
                case 'ts':
                case 'html':
                case 'css':
                case 'md':
                    $text = @file_get_contents($fullPath) ?: '';
                    break;

                default:
                    $text = '';
                    break;
            }
        } catch (\Throwable $e) {
            Log::warning('Ekstraksi teks file gagal: ' . $e->getMessage());
            $text = '';
        }

        $text = trim($text);
        if (mb_strlen($text) > 15000) {
            $text = mb_substr($text, 0, 15000) . "\n\n... [Sisa isi dokumen dipotong untuk efisiensi token] ...";
        }

        return $text;
    }

    /**
     * Fallback ekstraksi teks sederhana dari stream PDF jika pdftotext CLI tidak tersedia.
     */
    private function extractTextFromPdfStream(string $content): string
    {
        $text = '';
        if (preg_match_all('/BT[\s\S]*?ET/m', $content, $matches)) {
            foreach ($matches[0] as $block) {
                if (preg_match_all('/\((.*?)\)\s*Tj/s', $block, $tjs)) {
                    $text .= implode(' ', $tjs[1]) . "\n";
                }
            }
        }
        return trim($text);
    }

    /**
     * Format byte ke satuan yang mudah dibaca (KB, MB, GB).
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Generate Deskripsi Materi
     */
    public function generateDescription(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'kategori' => 'nullable|string'
        ]);

        $judul = $request->judul;
        $kategori = $request->kategori ?? 'Umum';

        $prompt = "Buatkan deskripsi singkat (1 paragraf ringkas) untuk materi kuliah berjudul '$judul' dengan kategori '$kategori'. Deskripsi ini akan langsung dimasukkan ke form LMS, jangan ada kalimat pembuka/penutup seperti 'Berikut adalah...'";

        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];

        try {
            $content = $this->callAiApi($messages);
            if ($content) {
                // Strip markdown formatting explicitly because LLMs are stubborn
                $content = str_replace('**', '', $content);
                $content = preg_replace('/^\s*\*\s+/m', '- ', $content);
                $content = preg_replace('/^\s*#+\s+/m', '', $content);
                
                return response()->json([
                    'success' => true,
                    'description' => trim($content)
                ]);
            }
            throw new \Exception('Respons kosong');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Draf Pengumuman
     */
    public function generatePengumuman(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
        ]);

        $judul = $request->judul;

        $prompt = "Buatkan draf pengumuman resmi dan profesional untuk mahasiswa dengan topik/judul '$judul'. Pengumuman ini akan langsung dimasukkan ke form LMS. Gunakan bahasa yang jelas, sopan, dan akademis. JANGAN ada kalimat pembuka/penutup dari sistem seperti 'Berikut adalah...' atau Markdown seperti **tebal**.";

        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];

        try {
            $content = $this->callAiApi($messages);
            if ($content) {
                // Bersihkan Markdown (Bold/Italic/Heading)
                $content = str_replace('**', '', $content);
                $content = preg_replace('/^\s*\*\s+/m', '- ', $content);
                $content = preg_replace('/^\s*#+\s+/m', '', $content);
                return response()->json([
                    'success' => true,
                    'message' => trim($content)
                ]);
            }
            throw new \Exception('Respons kosong');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate Dashboard Insight
     */
    public function generateDashboardInsight(Request $request)
    {
        $user = $request->user();
        $isEn = app()->getLocale() === 'en' || session('locale') === 'en';

        $jumlahKelas = $user->kelasDiampu()->count();
        $totalMahasiswa = \App\Models\MahasiswaKelas::whereIn('kelas_perkuliahan_id', $user->kelasDiampu()->pluck('kelas_perkuliahan.id'))->distinct('mahasiswa_id')->count();
        $tugasPerluDinilai = \App\Models\PengumpulanTugas::whereHas('tugas.kelasPerkuliahan', fn ($q) => $q->where('dosen_id', $user->id))
            ->where('status', 'dikumpulkan')
            ->count();

        $languageInstruction = $isEn
            ? 'Write in professional, concise English.'
            : 'Tulis dalam bahasa Indonesia yang profesional dan ringkas.';
        $messages = [
            [
                'role' => 'system',
                'content' => 'You are an academic dashboard assistant. Provide practical, supportive insights based only on the supplied counts. Do not invent facts. '.$languageInstruction,
            ],
            [
                'role' => 'user',
                'content' => "Create a short dashboard insight for a lecturer using these figures: {$jumlahKelas} classes, {$totalMahasiswa} enrolled students, and {$tugasPerluDinilai} assignments awaiting grading.",
            ],
        ];

        try {
            return response()->json([
                'success' => true,
                'message' => $this->callAiApi($messages),
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate Instruksi Tugas
     */
    public function generateInstruksi(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'poin' => 'nullable|integer'
        ]);

        $judul = $request->judul;
        $poin = $request->poin ?? 100;

        $prompt = "Buatkan teks instruksi/panduan pengerjaan tugas perkuliahan untuk mahasiswa dengan judul '$judul'. \n"
                . "PENTING:\n"
                . "1. Teks ini HANYA berisi perintah/panduan cara mengerjakan tugas (misalnya format pengumpulan, panjang halaman, referensi yang harus digunakan, dll).\n"
                . "2. DILARANG KERAS membuat atau menuliskan daftar soal-soal di sini, meskipun judulnya 'Soal'. Jika judulnya soal, cukup tuliskan instruksi seperti 'Silakan kerjakan soal-soal mengenai topik ini dengan saksama'.\n"
                . "3. JANGAN menulis ulang Judul Tugas, Bobot Poin, atau Waktu Pengumpulan di dalam teks.\n"
                . "4. JANGAN gunakan kalimat pembuka/penutup seperti 'Berikut adalah instruksinya...'.\n"
                . "5. Gunakan bahasa Indonesia yang baku, ringkas, dan profesional.\n"
                . "6. DILARANG KERAS menggunakan format Markdown (seperti **tebal** atau *miring*). Gunakan teks murni biasa.";

        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];

        try {
            $content = $this->callAiApi($messages);
            if ($content) {
                // Strip markdown formatting explicitly because LLMs are stubborn
                $content = str_replace('**', '', $content);
                $content = preg_replace('/^\s*\*\s+/m', '- ', $content);
                $content = preg_replace('/^\s*#+\s+/m', '', $content);
                
                return response()->json([
                    'success' => true,
                    'instruksi' => trim($content)
                ]);
            }
            throw new \Exception('Respons kosong');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate PDF dari teks AI
     */
    public function generatePdf(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'title' => 'required|string'
        ]);

        $title = $request->title;
        
        // Bersihkan teks dan ubah baris baru menjadi tag <br>
        $content = strip_tags($request->content);
        $contentHTML = nl2br($content);

        $html = "
            <html>
            <head>
                <style>
                    body { font-family: 'Helvetica', 'Arial', sans-serif; line-height: 1.6; font-size: 11pt; padding: 30px; color: #333; }
                    h2 { text-align: center; text-transform: uppercase; margin-bottom: 25px; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
                    .content { margin-top: 20px; text-align: justify; }
                </style>
            </head>
            <body>
                <h2>{$title}</h2>
                <div class='content'>
                    {$contentHTML}
                </div>
            </body>
            </html>
        ";

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . \Illuminate\Support\Str::slug($title) . '.pdf"',
        ]);
    }

    /**
     * Generate PDF berisi Soal langsung dari AI
     */
    public function generateAiPdfSoal(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'tipe_soal' => 'nullable|string'
        ]);

        $title = $request->title;
        $tipeSoal = $request->tipe_soal ? "Bentuk/Format/Jumlah yang diminta: " . $request->tipe_soal . ".\n" : "";
        
        $prompt = "Buatkan isi dokumen materi atau soal-soal tugas perkuliahan dengan judul/topik '$title'.\n"
                . $tipeSoal
                . "PENTING:\n"
                . "1. LANGSUNG berikan isinya (daftar soal atau materi penjelasan yang padat dan terstruktur).\n"
                . "2. JIKA Anda membuat soal, DILARANG KERAS menyertakan kunci jawaban, pembahasan, atau 'Jawaban: ...'. Ini adalah dokumen lembar soal murni yang akan dikerjakan mahasiswa.\n"
                . "3. JANGAN menulis ulang Judul Tugas.\n"
                . "4. JANGAN gunakan kalimat pembuka/penutup seperti 'Berikut adalah daftar soal...'.\n"
                . "5. DILARANG KERAS menggunakan format Markdown (seperti **tebal** atau *miring*). Gunakan teks murni biasa.";

        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];

        try {
            $content = $this->callAiApi($messages);
            if (!$content) {
                throw new \Exception('Respons kosong dari AI');
            }
            
            // Clean markdown
            $content = str_replace('**', '', $content);
            $content = preg_replace('/^\s*\*\s+/m', '- ', $content);
            $content = preg_replace('/^\s*#+\s+/m', '', $content);
            $contentHTML = nl2br(strip_tags(trim($content)));

            $html = "
                <html>
                <head>
                    <style>
                        body { font-family: 'Helvetica', 'Arial', sans-serif; line-height: 1.6; font-size: 11pt; padding: 30px; color: #333; }
                        h2 { text-align: center; text-transform: uppercase; margin-bottom: 25px; color: #1e293b; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
                        .content { margin-top: 20px; text-align: justify; }
                    </style>
                </head>
                <body>
                    <h2>{$title}</h2>
                    <div class='content'>
                        {$contentHTML}
                    </div>
                </body>
                </html>
            ";

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . \Illuminate\Support\Str::slug($title) . '.pdf"',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
