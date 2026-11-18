<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            // Puskesmas induk dari sebuah posyandu (dipakai untuk pilihan bertingkat di form pengaduan)
            $table->string('puskesmas', 100)->nullable()->after('desa_kelurahan')->index();
        });
    }

    public function down(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            $table->dropIndex(['puskesmas']);
            $table->dropColumn('puskesmas');
        });
    }
};
