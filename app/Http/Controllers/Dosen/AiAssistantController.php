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

        foreach ($providers as $provider) {
            if (empty($provider['key'])) continue;

            try {
                $response = Http::withHeaders([
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

        $prompt = "Buatkan instruksi tugas perkuliahan untuk mahasiswa dengan judul '$judul'. \n"
                . "PENTING:\n"
                . "1. LANGSUNG tuliskan poin-poin instruksi pengerjaan tugasnya (apa yang harus dikerjakan dan format pengumpulannya).\n"
                . "2. JANGAN menulis ulang Judul Tugas, Bobot Poin, atau Waktu Pengumpulan di dalam teks, karena sudah ada kolomnya tersendiri di sistem.\n"
                . "3. JANGAN gunakan kalimat pembuka/penutup seperti 'Berikut adalah instruksi...' atau 'Selamat mengerjakan'.\n"
                . "4. Gunakan bahasa Indonesia yang baku, terstruktur (gunakan bullet/numbering), ringkas, dan profesional ala dosen perguruan tinggi.";

        $messages = [
            ['role' => 'user', 'content' => $prompt]
        ];

        try {
            $content = $this->callAiApi($messages);
            if ($content) {
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
}
