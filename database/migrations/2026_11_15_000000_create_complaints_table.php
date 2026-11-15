<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 20)->unique();

            // Data pelapor (opsional, boleh anonim)
            $table->string('nama', 100)->nullable();
            $table->string('kontak', 100)->nullable(); // no. HP atau email

            // Isi pengaduan
            $table->string('desa_kelurahan', 100);
            $table->string('kategori', 50);
            $table->string('judul', 150);
            $table->text('isi');

            // Penanganan oleh IT Admin
            $table->string('status', 20)->default('baru')->index();
            $table->text('tanggapan')->nullable();
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ditanggapi_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
