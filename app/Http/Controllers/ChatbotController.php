<?php
// app/Http/Controllers/ChatbotController.php

namespace App\Http\Controllers;

use App\Services\AIProviderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    private AIProviderService $ai;

    public function __construct(AIProviderService $ai)
    {
        $this->ai = $ai;
    }

    public function chat(Request $request): JsonResponse
    {
        $startTime = microtime(true);
        
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $userMessage = $request->input('message');
        $sessionId = $request->session()->getId();
        $userName = $this->getUserName($request);

        Log::info('📝 NEW CHAT REQUEST', [
            'session_id' => substr($sessionId, 0, 8) . '...',
            'user_name_header' => $request->header('X-User-Name'),
            'user_name_parsed' => $userName ?? '(no name)',
            'message_length' => strlen($userMessage),
            'message_preview' => substr($userMessage, 0, 50),
        ]);

        try {
            $history = Cache::get("chat_history_{$sessionId}", []);
            
            Log::info('💾 HISTORY LOADED', [
                'history_count' => count($history),
                'session_id' => substr($sessionId, 0, 8) . '...',
            ]);

            // 🔥 DETEKSI: Kalo history penuh AI ngaku salah, reset
            if ($this->isHistoryToxic($history)) {
                Log::warning('🧨 TOXIC HISTORY DETECTED, RESETTING');
                $history = [];
                Cache::put("chat_history_{$sessionId}", [], now()->addHours(2));
            }

            if (count($history) > 4) {
                $history = array_slice($history, -4);
                Log::info('✂️ HISTORY TRIMMED', ['new_count' => count($history)]);
            }

            // Context retrieval
            $contextStart = microtime(true);
            $context = $this->findRelevantContext($userMessage);
            $contextTime = round(microtime(true) - $contextStart, 3);
            
            Log::info('🔍 CONTEXT RETRIEVED', [
                'db_query_time_ms' => $contextTime * 1000,
                'context_length' => strlen($context),
                'has_context' => !empty($context),
            ]);

            // Build messages
            $messages = [
                ['role' => 'system', 'content' => $this->getSystemPrompt($userName)],
            ];

            if (!empty($context)) {
                $messages[] = [
                    'role' => 'system',
                    'content' => "📚 INFORMASI INI SAJA YANG BOLEH KAMU PAKAI:\n\n{$context}\n\n⚠️ PENTING:\n- Jawab HANYA dari info di atas\n- Kalau user nanya sesuatu yang GAK ADA di info di atas, bilang: \"Wah, aku belum punya info lengkap soal itu.\"\n- JANGAN nebak, JANGAN tambahin \"tapi\", \"mungkin\", atau \"aku rasa\""
                ];
            } else {
                $messages[] = [
                    'role' => 'system',
                    'content' => '⚠️ TIDAK ADA INFO RELEVAN. Kalau user nanya soal gizi/stunting/web ini, bilang: "Wah, aku belum punya info lengkap soal itu. Hubungi Rumah Stunting langsung ya 📞 (022) 1234-5678"'
                ];
            }

            $messages = array_merge($messages, $this->sanitizeHistory($history));
            $messages[] = ['role' => 'user', 'content' => $userMessage];

            Log::info('📤 MESSAGES BUILT', [
                'total_messages' => count($messages),
                'system_messages' => count(array_filter($messages, fn($m) => $m['role'] === 'system')),
                'history_messages' => count(array_filter($messages, fn($m) => $m['role'] !== 'system')),
            ]);

            // Kirim ke AI
            $apiStart = microtime(true);
            $result = $this->ai->chat($messages);
            $apiTime = round(microtime(true) - $apiStart, 3);

            $reply = $result['choices'][0]['message']['content'] ?? 'Maaf, terjadi kesalahan.';
            
            Log::info('📥 AI RESPONSE RECEIVED', [
                'api_response_time_ms' => $apiTime * 1000,
                'reply_length' => strlen($reply),
                'reply_preview' => substr($reply, 0, 100),
            ]);

            // 🔥 BERSIHIN REPLY DARI TOXIC PATTERNS
            $replyBefore = $reply;
            $reply = $this->cleanReply($reply, $userName);
            
            if ($reply !== $replyBefore) {
                Log::info('🧹 REPLY CLEANED', [
                    'before' => substr($replyBefore, 0, 100),
                    'after' => substr($reply, 0, 100),
                ]);
            }

            // Save history
            $history[] = ['role' => 'user', 'content' => $userMessage];
            $history[] = ['role' => 'assistant', 'content' => $reply];
            Cache::put("chat_history_{$sessionId}", $history, now()->addHours(2));

            $totalTime = round(microtime(true) - $startTime, 3);
            
            Log::info('✅ CHAT COMPLETED', [
                'total_time_ms' => $totalTime * 1000,
                'user_name' => $userName ?? '(no name)',
                'final_reply_preview' => substr($reply, 0, 100),
            ]);

            return response()->json([
                'reply' => $reply,
                'status' => 'success',
            ]);

        } catch (\Exception $e) {
            $totalTime = round(microtime(true) - $startTime, 3);
            
            Log::error('💥 CHAT ERROR', [
                'total_time_ms' => $totalTime * 1000,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'reply' => 'Aduh, otakku lagi ngelag nih 😅 Coba lagi bentar ya!',
                'status' => 'error',
            ], 500);
        }
    }

    /**
     * 🔥 DETEKSI HISTORY TOXIC (AI ngaku salah terus)
     */
    private function isHistoryToxic(array $history): bool
    {
        $toxicPatterns = ['aku salah', 'salah lagi', 'minta maaf', 'harus hati-hati', 'aku tidak bisa', 'aku salah'];
        $toxicCount = 0;
        
        foreach ($history as $msg) {
            if ($msg['role'] !== 'assistant') continue;
            $contentLower = strtolower($msg['content']);
            foreach ($toxicPatterns as $pattern) {
                if (strpos($contentLower, $pattern) !== false) {
                    $toxicCount++;
                    break;
                }
            }
        }
        
        $isToxic = $toxicCount >= 2;
        
        if ($isToxic) {
            Log::warning('🧨 TOXIC HISTORY', [
                'toxic_count' => $toxicCount,
                'total_messages' => count($history),
            ]);
        }
        
        return $isToxic;
    }

    /**
     * Ambil nama user, filter kata gaul
     */
    private function getUserName(Request $request): ?string
    {
        $name = $request->header('X-User-Name');
        
        if (empty($name)) {
            Log::debug('👤 No user name provided');
            return null;
        }

        $bukanNama = ['gua', 'gue', 'gw', 'aku', 'saya', 'kamu', 'lu', 'loe', 'elo'];
        $nameLower = strtolower(trim($name));
        
        if (in_array($nameLower, $bukanNama)) {
            Log::info('👤 FILTERED NAME', [
                'original' => $name,
                'reason' => 'kata gaul detected',
            ]);
            return null;
        }

        Log::info('👤 USER NAME ACCEPTED', ['name' => $name]);
        return $name;
    }

    /**
     * 🔥 SYSTEM PROMPT — Tanpa trigger "aku salah"
     */
    private function getSystemPrompt(?string $userName): string
    {
        $namePart = $userName
            ? "User bernama {$userName}. Panggil dia \"{$userName}\" atau \"kamu\"."
            : "User belum kasih nama. Panggil \"kamu\" atau \"bro\".";

        $schedule = $this->getScheduleInfo();

        return <<<PROMPT
{$namePart}

Kamu adalah **StunAI** 🤖, asisten virtual Rumah Stunting Pangalengan.

🎯 PERAN:
- Jawab pertanyaan gizi, stunting, dan layanan Rumah Stunting
- Informasi AKURAT dan TERPERCAYA saja

🚫 LARANGAN KERAS:
1. JANGAN pakai "Anda", "Bapak/Ibu", "mohon maaf"
2. JANGAN mulai dengan "Berdasakan...", "Menurut..."
3. JANGAN nebak-nebak jawaban
4. JANGAN spam emoji (maks 2)
5. JANGAN pernah bilang "aku rasa", "mungkin karena", "sepertinya", "aku pikir" — ini tanda kamu lagi ngarang
6. JANGAN panik kalo user protes. Tetap jawab tenang.

✅ ATURAN JAWAB:
- Kalau info ada di context → jawab berdasarkan context
- Kalau info TIDAK ada di context → bilang: "Wah, aku belum punya info lengkap soal itu. Hubungi Rumah Stunting langsung ya 📞 (022) 1234-5678"
- JANGAN tambahin "tapi", "mungkin", atau nebak-nebak setelah bilang gak tau

✅ GAYA:
- "kamu/aku", santai tapi informatif
- Kalimat pendek, kayak chat WA
- Variasi pembuka: "Halo", "Nah", "Hmm", "Wih"

📍 KONTAK:
- Jl. Raya Pangalengan No. 123, Kec. Pangalengan, Kab. Bandung
- Telp: (022) 1234-5678
- WA: 0812-3456-7890
- Email: info@rumahstuntingpangalengan.id

📅 JADWAL:
{$schedule}
PROMPT;
    }

    private function sanitizeHistory(array $history): array
    {
        $cleaned = array_map(function ($msg) {
            if ($msg['role'] === 'assistant') {
                $msg['content'] = $this->cleanReply($msg['content'], null);
            }
            return $msg;
        }, $history);

        Log::debug('🧹 HISTORY SANITIZED', ['count' => count($cleaned)]);
        return $cleaned;
    }

    private function cleanReply(string $reply, ?string $userName): string
    {
        // 1. Ganti "Gua" → "kamu"
        $reply = str_ireplace('Gua', 'kamu', $reply);
        $reply = str_ireplace('"kamu"', 'kamu', $reply);
        $reply = str_ireplace('kami', 'aku', $reply);
        $reply = str_ireplace('Kami', 'Aku', $reply);

        // 2. Hapus toxic patterns (loop)
        $toxicPatterns = [
            '/aku\s+salah\b[^.]*\.?\s*/i' => '',
            '/aku\s+salah\s+lagi\b[^.]*\.?\s*/i' => '',
            '/salah\s+lagi\b[^.]*\.?\s*/i' => '',
            '/aku\s+harus\s+lebih\s+hati-hati\b[^.]*\.?\s*/i' => '',
            '/aku\s+minta\s+maaf\b[^.]*\.?\s*/i' => '',
            '/minta\s+maaf\b[^.]*\.?\s*/i' => '',
            '/maaf,?\s+aku\b[^.]*\.?\s*/i' => '',
            '/maaf\b[^.]*\.?\s*/i' => '',
            '/aku\s+tidak\s+bisa\b[^.]*\.?\s*/i' => '',
            '/aku\s+ngerti\b[^.]*\.?\s*/i' => '',
            '/aku\s+tahu,?\s+aku\b[^.]*\.?\s*/i' => '',
            '/aku\s+rasa\b[^.]*\.?\s*/i' => '',
            '/mungkin\s+karena\b[^.]*\.?\s*/i' => '',
            '/sepertinya\b[^.]*\.?\s*/i' => '',
            '/aku\s+pikir\b[^.]*\.?\s*/i' => '',
            '/tapi,?\s+dari\s+informasi\b[^.]*\.?\s*/i' => '',
            '/aku\s+tidak\s+bisa\s+memberikan\b[^.]*\.?\s*/i' => '',
            '/aku\s+tidak\s+bisa\s+memberikan\s+opini\b[^.]*\.?\s*/i' => '',
            '/aku\s+hanya\s+bisa\s+membantu\b[^.]*\.?\s*/i' => '',
            '/aku\s+hanya\s+bisa\s+memberikan\s+informasi\b[^.]*\.?\s*/i' => '',
        ];

        foreach ($toxicPatterns as $pattern => $replacement) {
            $reply = preg_replace($pattern, $replacement, $reply);
        }

        // 3. Hapus "aku tidak bisa panggil kamu" (pisah!)
        $reply = preg_replace('/aku\s+tidak\s+bisa\s+panggil\s+kamu\b[^.]*\.?\s*/i', '', $reply);

        // 4. Fix "wah aku belum punya info... tapi..." → hapus "tapi" part
        $reply = preg_replace('/wah,?\s+aku\s+belum\s+punya\s+info[^.]*\.?\s+tapi\b[^.]*\.?\s*/i', 'Wah, aku belum punya info lengkap soal itu.', $reply);

        // 5. Ganti "kamu" dengan nama user (1x aja)
        if ($userName) {
            $reply = preg_replace('/\bkamu\b/i', $userName, $reply, 1);
        }

        // 6. Bersihin spasi berlebih
        return trim(preg_replace('/\s+/', ' ', $reply));
    }

    private function getScheduleInfo(): string
    {
        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        $now = now();
        $todayNum = (int) $now->format('N');
        $todayName = $dayNames[$todayNum];
        $currentTime = $now->format('H:i');

        $isWeekday = $todayNum <= 5;
        $isWithinHours = $currentTime >= '08:00' && $currentTime <= '16:00';
        $isOpenNow = $isWeekday && $isWithinHours;

        $nextOpenDay = null;
        for ($i = 1; $i <= 7; $i++) {
            $checkNum = (($todayNum - 1 + $i) % 7) + 1;
            if ($checkNum <= 5) {
                $nextOpenDay = $i === 1 && $isWeekday && $currentTime < '08:00' ? $todayName : $dayNames[$checkNum];
                break;
            }
        }

        $consultDays = [2, 4];
        $isConsultToday = in_array($todayNum, $consultDays, true);

        $nextConsultDay = null;
        foreach ([2, 4] as $d) {
            $diff = ($d - $todayNum + 7) % 7;
            if ($diff === 0 && $currentTime < '12:00') {
                $nextConsultDay = $todayName;
                break;
            } elseif ($diff > 0 && $nextConsultDay === null) {
                $nextConsultDay = $dayNames[$d];
            }
        }

        $status = $isOpenNow
            ? "Sekarang ({$todayName}, {$currentTime}) BUKA."
            : "Sekarang ({$todayName}, {$currentTime}) TUTUP. Buka: {$nextOpenDay} jam 08:00.";

        $consult = $isConsultToday
            ? "Hari ini ada konsultasi gizi gratis 09:00-12:00."
            : "Konsultasi gratis berikutnya: {$nextConsultDay}, 09:00-12:00.";

        return "Jam: Senin-Jumat 08:00-16:00 WIB. {$status}\n{$consult}";
    }

    /**
     * 🔥 FIND RELEVANT CONTEXT — Ambil 1 chunk terbaik, bersihin metadata
     */
    private function findRelevantContext(string $query): string
    {
        $stopWords = ['yang', 'di', 'ke', 'dari', 'dan', 'atau', 'ini', 'itu', 'apa', 'bagaimana', 
                      'berapa', 'siapa', 'kapan', 'dimana', 'mengapa', 'saya', 'kamu', 'dia', 
                      'kita', 'mereka', 'adalah', 'merupakan', 'bisa', 'dapat', 'sudah', 'belum', 
                      'akan', 'the', 'is', 'are', 'what', 'how', 'who', 'when', 'where', 'why',
                      'gua', 'gue', 'gw', 'lu', 'loe', 'elo', 'aku', 'lah', 'ye', 'si', 'masa', 'ngasih'];

        $words = preg_split('/\s+/', strtolower($query));
        $keywords = array_filter($words, fn($w) => !in_array($w, $stopWords) && strlen($w) > 2);

        if (empty($keywords)) {
            Log::debug('🔍 No keywords found in query');
            return '';
        }

        Log::debug('🔍 KEYWORDS EXTRACTED', ['keywords' => array_values($keywords)]);

        $chunks = DB::table('knowledge_chunks')
            ->where(function ($q) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $q->orWhere('content', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->limit(5) // Ambil 5 dulu buat scoring
            ->get();

        // 🔥 PILIH 1 CHUNK PALING RELEVAN
        $bestChunk = null;
        $bestScore = 0;
        
        foreach ($chunks as $chunk) {
            $content = strtolower($chunk->content);
            $score = 0;
            foreach ($keywords as $kw) {
                $score += substr_count($content, $kw);
            }
            // Boost kalo keyword muncul di awal content
            $firstPara = substr($content, 0, 500);
            foreach ($keywords as $kw) {
                if (strpos($firstPara, $kw) !== false) $score += 2;
            }
            
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestChunk = $chunk;
            }
        }

        if (!$bestChunk) {
            Log::debug('🔍 No relevant chunk found');
            return '';
        }

        Log::debug('🔍 BEST CHUNK SELECTED', ['score' => $bestScore]);

        // Bersihin metadata dari chunk terbaik
        $content = $bestChunk->content;
        
        // Hapus markdown headers
        $content = preg_replace('/^#{1,6}\s+.*$/m', '', $content);
        // Hapus separator
        $content = preg_replace('/^---+$/m', '', $content);
        // Hapus line kosong berlebihan
        $content = preg_replace('/\n{3,}/', "\n\n", $content);
        
        $result = trim($content);

        Log::debug('🔍 CONTEXT CLEANED', ['original_length' => strlen($bestChunk->content), 'cleaned_length' => strlen($result)]);

        return $result;
    }

    public function clearHistory(Request $request): JsonResponse
    {
        $sessionId = $request->session()->getId();
        Cache::forget("chat_history_{$sessionId}");
        
        Log::info('🗑️ HISTORY CLEARED', ['session_id' => substr($sessionId, 0, 8) . '...']);

        return response()->json([
            'status' => 'success',
            'message' => 'Percakapan direset.',
        ]);
    }
}