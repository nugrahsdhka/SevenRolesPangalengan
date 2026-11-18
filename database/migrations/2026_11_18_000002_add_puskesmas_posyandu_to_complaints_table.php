<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Nullable supaya pengaduan lama (yang hanya punya desa) tetap valid
            $table->string('puskesmas', 100)->nullable()->after('kontak');
            $table->string('posyandu', 150)->nullable()->after('desa_kelurahan');
        });
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['puskesmas', 'posyandu']);
        });
    }
};
