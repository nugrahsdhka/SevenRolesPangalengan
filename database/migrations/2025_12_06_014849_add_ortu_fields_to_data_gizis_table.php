<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            // Tambah setelah kelas_ibu_balita biar rapi (opsional)
            $table->text('nama_ortu')->nullable()->after('kelas_ibu_balita');
            $table->text('nik_ortu')->nullable()->after('nama_ortu');
            $table->text('hp_ortu')->nullable()->after('nik_ortu');
        });
    }

    public function down(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            $table->dropColumn(['nama_ortu', 'nik_ortu', 'hp_ortu']);
        });
    }
};
