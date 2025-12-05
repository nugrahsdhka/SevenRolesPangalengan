<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            // BB & TB HARUS NULLABLE SUPAYA IMPORT TIDAK ERROR
            $table->decimal('berat', 5, 2)->nullable()->change();
            $table->decimal('tinggi', 5, 2)->nullable()->change();

            // OPTIONAL — Kolom-kolom berikut sering kosong di Excel
            $table->unsignedSmallInteger('usia')->nullable()->change();
            $table->unsignedTinyInteger('anak_ke')->nullable()->change();
            $table->string('rt', 5)->nullable()->change();
            $table->string('rw', 5)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            // Kembalikan seperti semula jika diperlukan
            $table->decimal('berat', 5, 2)->nullable(false)->change();
            $table->decimal('tinggi', 5, 2)->nullable(false)->change();
        });
    }
};
