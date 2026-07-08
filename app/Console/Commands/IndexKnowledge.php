<?php
// app/Console/Commands/IndexKnowledge.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IndexKnowledge extends Command
{
    protected $signature = 'knowledge:index 
                            {--clear : Hapus semua index sebelum reindex}';
    
    protected $description = 'Index knowledge base documents (recursive, support subfolders)';

    public function handle()
    {
        if ($this->option('clear')) {
            DB::table('knowledge_chunks')->truncate();
            $this->info('Index dibersihkan.');
        }

        $knowledgePath = storage_path('app/knowledge');
        
        // Recursive glob — cari semua .txt di semua subfolder
        $files = $this->getAllTxtFiles($knowledgePath);

        if (empty($files)) {
            $this->error('Nggak ada file .txt di ' . $knowledgePath . ' atau subfoldernya');
            return 1;
        }

        $this->info('Ditemukan ' . count($files) . ' file.');

        $totalInserted = 0;

        foreach ($files as $file) {
            $relativePath = str_replace($knowledgePath . DIRECTORY_SEPARATOR, '', $file);
            $folder = dirname($relativePath);

            static $currentFolder = '';
            if ($folder !== $currentFolder && $folder !== '.') {
                $currentFolder = $folder;
                $this->newLine();
                $this->info("📁 {$folder}/");
            }
            
            $content = file_get_contents($file);
            $chunks = $this->splitIntoChunks($content, 1000);

            // Simpan relative path (biar tau file-nya di subfolder mana)
            $relativePath = str_replace($knowledgePath . DIRECTORY_SEPARATOR, '', $file);

            foreach ($chunks as $i => $chunk) {
                if (empty(trim($chunk))) continue;

                DB::table('knowledge_chunks')->insert([
                    'source_file' => $relativePath, // contoh: "program/jadwal.txt"
                    'chunk_index' => $i,
                    'content' => $chunk,
                    'embedding' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalInserted++;
            }

            $this->info('  ✓ ' . $relativePath . ' → ' . count($chunks) . ' chunks');
        }

        $this->info("Done! Total {$totalInserted} chunks diindex.");
        return 0;
    }

    /**
     * Recursive cari semua .txt file
     */
    private function getAllTxtFiles(string $dir): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'txt') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    private function splitIntoChunks(string $text, int $maxLength): array
    {
        $paragraphs = preg_split('/\n\s*\n/', $text);
        $chunks = [];
        $current = '';

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim($paragraph);
            if (empty($paragraph)) continue;

            if (strlen($paragraph) > $maxLength) {
                if (!empty($current)) {
                    $chunks[] = $current;
                    $current = '';
                }
                
                $sentences = preg_split('/(?<=[.!?])\s+/', $paragraph);
                foreach ($sentences as $sentence) {
                    if (strlen($current) + strlen($sentence) > $maxLength) {
                        if (!empty($current)) $chunks[] = trim($current);
                        $current = $sentence;
                    } else {
                        $current .= ($current ? ' ' : '') . $sentence;
                    }
                }
            } elseif (strlen($current) + strlen($paragraph) > $maxLength) {
                if (!empty($current)) $chunks[] = trim($current);
                $current = $paragraph;
            } else {
                $current .= ($current ? "\n\n" : '') . $paragraph;
            }
        }

        if (!empty($current)) $chunks[] = trim($current);
        
        return $chunks;
    }
}