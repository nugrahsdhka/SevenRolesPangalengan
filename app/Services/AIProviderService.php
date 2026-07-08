<?php
// app/Services/AIProviderService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIProviderService
{
    private string $groqKey;
    private string $geminiKey;
    private string $openrouterKey;
    
    private string $groqModel;
    private string $geminiModel;
    private string $openrouterModel;

    public function __construct()
    {
        $this->groqKey = env('GROQ_API_KEY');
        $this->geminiKey = env('GEMINI_API_KEY');
        $this->openrouterKey = env('OPENROUTER_API_KEY');
        
        $this->groqModel = env('GROQ_MODEL', 'llama-3.1-8b-instant');
        $this->geminiModel = env('GEMINI_MODEL', 'gemini-2.5-flash');
        $this->openrouterModel = env('OPENROUTER_MODEL', 'meta-llama/llama-3.1-8b-instruct:free');
    }

    /**
     * Try Groq → Gemini → OpenRouter
     */
    public function chat(array $messages): array
    {
        $estimatedTokens = $this->estimateTokens($messages);
        Log::info('📊 TOKEN ESTIMATE', [
            'estimated_input_tokens' => $estimatedTokens,
            'message_count' => count($messages),
        ]);

        // 1. Try Groq
        try {
            return $this->tryGroq($messages);
        } catch (\Exception $e1) {
            Log::warning('❌ GROQ FAILED', ['error' => $e1->getMessage()]);
        }

        // 2. Try Gemini
        try {
            return $this->tryGemini($messages);
        } catch (\Exception $e2) {
            Log::warning('❌ GEMINI FAILED', ['error' => $e2->getMessage()]);
        }

        // 3. Try OpenRouter (last resort)
        try {
            return $this->tryOpenRouter($messages);
        } catch (\Exception $e3) {
            Log::error('💥 ALL PROVIDERS FAILED', [
                'groq' => $e1->getMessage() ?? 'not tried',
                'gemini' => $e2->getMessage() ?? 'not tried',
                'openrouter' => $e3->getMessage(),
            ]);
            throw $e3;
        }
    }

    // ==================== GROQ ====================
    private function tryGroq(array $messages): array
    {
        Log::info('🚀 TRYING GROQ', ['model' => $this->groqModel]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->groqKey,
            'Content-Type' => 'application/json',
        ])->timeout(30)->post('https://api.groq.com/openai/v1/chat/completions', [
            'model' => $this->groqModel,
            'messages' => $messages,
            'max_tokens' => 600,
            'temperature' => 0.3,
            'top_p' => 0.8,
            'presence_penalty' => 0.1,
            'frequency_penalty' => 0.1,
        ]);

        return $this->handleGroqResponse($response);
    }

    private function handleGroqResponse($response): array
    {
        $body = $response->json();

        if (!$response->successful()) {
            $error = $body['error']['message'] ?? 'Unknown';
            $code = $body['error']['code'] ?? 'unknown';
            
            Log::error('🚨 GROQ ERROR', [
                'status' => $response->status(),
                'code' => $code,
                'message' => $error,
            ]);

            if ($code === 'rate_limit_exceeded') {
                preg_match('/try again in ([\d.]+)s/', $error, $m);
                Log::warning('⏱️ GROQ RATE LIMIT', [
                    'retry_after' => $m[1] ?? 'unknown',
                ]);
            }
            
            throw new \Exception("Groq: {$code}");
        }

        Log::info('✅ GROQ SUCCESS', [
            'model' => $this->groqModel,
            'prompt_tokens' => $body['usage']['prompt_tokens'] ?? 'N/A',
            'completion_tokens' => $body['usage']['completion_tokens'] ?? 'N/A',
            'total_tokens' => $body['usage']['total_tokens'] ?? 'N/A',
        ]);

        return $body;
    }

    // ==================== GEMINI ====================
    private function tryGemini(array $messages): array
    {
        Log::info('🚀 TRYING GEMINI', ['model' => $this->geminiModel]);

        $systemPrompt = '';
        $contents = [];
        
        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemPrompt .= $msg['content'] . "\n";
            } else {
                $contents[] = [
                    'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $msg['content']]]
                ];
            }
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(30)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$this->geminiModel}:generateContent?key={$this->geminiKey}",
            [
                'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
                'contents' => $contents,
                'generationConfig' => [
                    'maxOutputTokens' => 600,
                    'temperature' => 0.3,
                    'topP' => 0.8,
                ],
            ]
        );

        return $this->handleGeminiResponse($response);
    }

    private function handleGeminiResponse($response): array
    {
        $body = $response->json();

        if (!$response->successful()) {
            $error = $body['error']['message'] ?? 'Unknown';
            $code = $body['error']['code'] ?? 'unknown';
            
            Log::error('🚨 GEMINI ERROR', [
                'status' => $response->status(),
                'code' => $code,
                'message' => substr($error, 0, 200),
            ]);

            if ($code === 429) {
                preg_match('/retry in ([\d.]+)s/', $error, $m);
                Log::warning('⏱️ GEMINI RATE LIMIT', ['retry_after' => $m[1] ?? 'unknown']);
            }
            
            throw new \Exception("Gemini: {$code}");
        }

        // Convert ke format OpenAI
        return [
            'choices' => [[
                'message' => [
                    'content' => $body['candidates'][0]['content']['parts'][0]['text'] ?? ''
                ],
                'finish_reason' => $body['candidates'][0]['finishReason'] ?? 'STOP'
            ]],
            'usage' => [
                'prompt_tokens' => $body['usageMetadata']['promptTokenCount'] ?? 'N/A',
                'completion_tokens' => $body['usageMetadata']['candidatesTokenCount'] ?? 'N/A',
                'total_tokens' => $body['usageMetadata']['totalTokenCount'] ?? 'N/A',
            ],
            'model' => $this->geminiModel,
        ];
    }

    // ==================== OPENROUTER ====================
    private function tryOpenRouter(array $messages): array
    {
        Log::info('🚀 TRYING OPENROUTER', ['model' => $this->openrouterModel]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openrouterKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => env('APP_URL', 'http://localhost'), // Wajib buat OpenRouter
            'X-Title' => 'StunAI Chatbot',
        ])->timeout(30)->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $this->openrouterModel,
            'messages' => $messages,
            'max_tokens' => 600,
            'temperature' => 0.3,
            'top_p' => 0.8,
        ]);

        return $this->handleOpenRouterResponse($response);
    }

    private function handleOpenRouterResponse($response): array
    {
        $body = $response->json();

        if (!$response->successful()) {
            $error = $body['error']['message'] ?? $response->body();
            
            Log::error('🚨 OPENROUTER ERROR', [
                'status' => $response->status(),
                'message' => substr($error, 0, 200),
            ]);
            
            throw new \Exception('OpenRouter: ' . $error);
        }

        Log::info('✅ OPENROUTER SUCCESS', [
            'model' => $body['model'] ?? $this->openrouterModel,
            'prompt_tokens' => $body['usage']['prompt_tokens'] ?? 'N/A',
            'completion_tokens' => $body['usage']['completion_tokens'] ?? 'N/A',
            'total_tokens' => $body['usage']['total_tokens'] ?? 'N/A',
        ]);

        return $body;
    }

    // ==================== UTILS ====================
    private function estimateTokens(array $messages): int
    {
        $totalChars = 0;
        foreach ($messages as $msg) {
            $totalChars += strlen($msg['content'] ?? '');
        }
        return (int) ceil($totalChars / 4);
    }
}