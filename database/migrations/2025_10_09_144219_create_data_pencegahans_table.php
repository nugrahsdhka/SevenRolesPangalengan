<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('data_pencegahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul_kegiatan');
            $table->string('lokasi');
            $table->date('tanggal_kegiatan');
            $table->text('laporan_singkat');
            $table->string('status')->default('pending'); // 'pending', 'approved', 'published'
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('data_pencegahans');
    }
};