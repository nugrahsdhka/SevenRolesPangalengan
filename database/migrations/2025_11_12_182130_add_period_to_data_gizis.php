<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            // Simpan periode sebagai tanggal (ambil tanggal 1 setiap bulan)
            $table->date('period')->nullable()->index()->after('user_id');
        });

        // Backfill: isi period dari created_at (dibulatkan ke tanggal 1)
        DB::statement("
            UPDATE data_gizis
            SET period = DATE_FORMAT(created_at, '%Y-%m-01')
            WHERE period IS NULL
        ");

        // Jadikan NOT NULL kalau perlu (opsional, tapi disarankan)
        Schema::table('data_gizis', function (Blueprint $table) {
            $table->date('period')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            $table->dropColumn('period');
        });
    }
};
