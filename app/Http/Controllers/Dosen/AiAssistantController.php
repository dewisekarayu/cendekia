<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
     * Helper: Kirim request ke AI providers dengan fallback.
     */
    private function callAiApi($messages)
    {
        $providers = [
            [
                'url' => 'https://api.groq.com/openai/v1/chat/completions',
                'key' => env('GROQ_API_KEY'),
                'model' => 'llama3-70b-8192'
            ],
            [
                'url' => 'https://api.groq.com/openai/v1/chat/completions',
                'key' => env('GROQ_API_KEY'),
                'model' => 'gemma2-9b-it'
            ],
            [
                'url' => 'https://openrouter.ai/api/v1/chat/completions',
                'key' => env('OPENROUTER_API_KEY'),
                'model' => 'meta-llama/llama-3.1-8b-instruct'
            ]
        ];

        $lastError = 'Semua API key tidak valid atau kosong.';

        // Prevent PHP from timing out when AI takes a long time
        set_time_limit(180);

        foreach ($providers as $provider) {
            if (empty($provider['key'])) continue;

            try {
                $response = Http::timeout(120)->withHeaders([
                    'Authorization' => 'Bearer ' . $provider['key'],
                    'Content-Type' => 'application/json',
                    'HTTP-Referer' => url('/'),
                    'X-Title' => 'Cendekia AI'
                ])->post($provider['url'], [
                    'model' => $provider['model'],
                    'messages' => $messages,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['choices'][0]['message']['content'] ?? null;
                }
                
                $lastError = 'Status: ' . $response->status() . '. ' . $response->body();
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                continue;
            }
        }

        throw new \Exception('Gagal menghubungi semua API AI fallback. Terakhir: ' . $lastError);
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
