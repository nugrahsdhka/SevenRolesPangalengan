<?php
// app/Services/GeminiService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private string $apiKey;
    private string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('openai.gemini_api_key') ?? env('GEMINI_API_KEY');
    }

    /**
     * Chat completion pake Gemini 2.5 Flash
     */
    public function chat(string $systemPrompt, array $history, string $userMessage): array
    {
        $start = microtime(true); 
        $model = config('openai.gemini_model', 'gemini-2.5-flash');

        // Convert history dari format OpenAI ke format Gemini
        $contents = [];
        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $msg['content']]]
            ];
        }

        // Tambah pesan user sekarang
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $userMessage]]
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(30)->post(
            "{$this->baseUrl}/{$model}:generateContent?key={$this->apiKey}",
            [
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]]
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'maxOutputTokens' => 600,
                    'temperature' => 0.3,    // Rendah = faktual, gak ngawang
                    'topP' => 0.8,
                ],
            ]
        );

        $duration = round(microtime(true) - $start, 2);
        Log::info("Gemini API response time: {$duration} detik");

        if (!$response->successful()) {
            $error = $response->json();
            Log::error('Gemini API Error: ' . $response->body());
            
            // Handle rate limit
            if (isset($error['error']['code']) && $error['error']['code'] === 429) {
                sleep(3);
                return $this->chat($systemPrompt, $history, $userMessage); // Retry sekali
            }
            
            throw new \Exception('Gemini API error: ' . ($error['error']['message'] ?? 'Unknown error'));
        }

        return $response->json();
    }

    /**
     * Gemini 2.5 Flash support embedding juga! (embedding-001)
     */
    public function embed(string $text): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(30)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/embedding-001:embedContent?key={$this->apiKey}",
            [
                'model' => 'models/embedding-001',
                'content' => [
                    'parts' => [['text' => $text]]
                ],
            ]
        );

        if (!$response->successful()) {
            throw new \Exception('Gemini Embedding error: ' . $response->body());
        }

        return $response->json('embedding.values', []);
    }
}