<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('data_penanggulangans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('jenis_bencana');
            $table->integer('jumlah_korban');
            $table->text('tindakan');
            $table->string('status')->default('pending'); // 'pending', 'approved', 'published'
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('data_penanggulangans');
    }
};