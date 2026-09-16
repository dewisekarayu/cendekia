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

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => 'llama3-8b-8192', // Fast model on Groq
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

            return response()->json([
                'success' => false,
                'error' => 'Gagal menghubungi API AI (Status: ' . $response->status() . '). ' . $response->body()
            ], 500);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
