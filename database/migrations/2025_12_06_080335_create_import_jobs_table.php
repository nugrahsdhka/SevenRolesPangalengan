<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('import_jobs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('data_gizi'); // kalau nanti mau jenis import lain
            $table->string('period', 7)->nullable();      // contoh: "2025-11"
            $table->string('file_path');                  // path file di storage

            $table->unsignedInteger('total_rows')->default(0);
            $table->unsignedInteger('processed_rows')->default(0);

            $table->enum('status', ['queued', 'running', 'success', 'failed'])
                  ->default('queued');

            $table->text('error_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_jobs');
    }
};
