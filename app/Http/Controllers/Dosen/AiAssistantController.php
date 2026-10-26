<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

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
     * Tangani request chat ke AI API.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'array'
        ]);

        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => 'Anda adalah Asisten AI untuk dosen di Universitas Cendekia. Berikan jawaban yang profesional, informatif, dan membantu dosen dalam mengelola perkuliahan, RPS, atau materi ajar.'
        ];

        if ($request->has('history')) {
            foreach ($request->input('history') as $msg) {
                $messages[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                    'content' => $msg['content']
                ];
            }
        }

        $messages[] = [
            'role' => 'user',
            'content' => $request->input('message')
        ];

        try {
            $content = $this->callAiApi($messages);
            if ($content) {
                return response()->json([
                    'success' => true,
                    'message' => $content
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
