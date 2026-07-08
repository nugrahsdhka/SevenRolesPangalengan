<?php
// database/migrations/xxxx_create_knowledge_chunks_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('knowledge_chunks', function (Blueprint $table) {
            $table->id();
            $table->string('source_file');
            $table->integer('chunk_index');
            $table->text('content');
            $table->json('embedding');
            $table->timestamps();
            
            $table->index('source_file');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('knowledge_chunks');
    }
};