<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_desas', function (Blueprint $table) {
            // Menambahkan kolom 'nama_desa' setelah kolom 'user_id'
            $table->string('nama_desa')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_desas', function (Blueprint $table) {
            $table->dropColumn('nama_desa');
        });
    }
};