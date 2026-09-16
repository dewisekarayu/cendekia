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
     * Tangani request chat ke AI API.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'array'
        ]);

        $messages = [];
        // Add system prompt
        $messages[] = [
            'role' => 'system',
            'content' => 'Anda adalah Asisten AI untuk dosen di Universitas Cendekia. Berikan jawaban yang profesional, informatif, dan membantu dosen dalam mengelola perkuliahan, RPS, atau materi ajar.'
        ];

        // Add history
        if ($request->has('history')) {
            foreach ($request->input('history') as $msg) {
                // Ensure we only pass role and content to the API
                $messages[] = [
                    'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                    'content' => $msg['content']
                ];
            }
        }

        // Add current message
        $messages[] = [
            'role' => 'user',
            'content' => $request->input('message')
        ];

        $providers = [
            [
                'url' => 'https://openrouter.ai/api/v1/chat/completions',
                'key' => env('OPENROUTER_API_KEY'),
                'model' => 'google/gemini-flash-1.5'
            ],
            [
                'url' => 'https://api.groq.com/openai/v1/chat/completions',
                'key' => env('GROQ_API_KEY'),
                'model' => 'mixtral-8x7b-32768'
            ],
            [
                'url' => 'https://openrouter.ai/api/v1/chat/completions',
                'key' => env('OPENROUTER_API_KEY'),
                'model' => 'meta-llama/llama-3.1-8b-instruct:free'
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
                    return response()->json([
                        'success' => true,
                        'message' => $data['choices'][0]['message']['content'] ?? 'Maaf, saya tidak dapat menghasilkan respons.'
                    ]);
                }
                
                $lastError = 'Status: ' . $response->status() . '. ' . $response->body();
            } catch (\Exception $e) {
                $lastError = $e->getMessage();
                continue;
            }
        }

        return response()->json([
            'success' => false,
            'error' => 'Gagal menghubungi semua API AI fallback. Terakhir: ' . $lastError
        ], 500);
    }
}
