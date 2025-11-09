<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faskes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_faskes');
            $table->string('jenis'); // Puskesmas, Klinik, Posyandu, dll
            $table->string('alamat')->nullable();
            $table->string('desa_kelurahan')->nullable(); // optional aja
            $table->decimal('lat');
            $table->decimal('lng');
            $table->boolean('is_active')->default(true);  // bisa nonaktif tanpa hapus
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faskes');
    }
};
