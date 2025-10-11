<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_desas', function (Blueprint $table) {
            // Menambahkan kolom baru setelah 'potensi_desa'
            // default(0) berarti jika tidak diisi, nilainya akan menjadi 0
            $table->unsignedInteger('jumlah_kasus_stunting')->default(0)->after('potensi_desa');
        });
    }

    public function down(): void
    {
        Schema::table('data_desas', function (Blueprint $table) {
            $table->dropColumn('jumlah_kasus_stunting');
        });
    }
};