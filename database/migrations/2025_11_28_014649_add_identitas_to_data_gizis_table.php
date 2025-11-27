<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {

            // Menambahkan kolom baru
            $table->string('nama', 100)->after('user_id');
            $table->string('nik', 20)->nullable()->after('nama');
            $table->string('posyandu', 100)->nullable()->after('nik');
        });
    }

    public function down(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            $table->dropColumn(['nama', 'nik', 'posyandu']);
        });
    }
};
