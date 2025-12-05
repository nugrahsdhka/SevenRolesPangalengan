<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            // Index tunggal untuk kolom yang sering dipanggil di 'where'
            $table->index('user_id', 'idx_gizi_user');
            $table->index('period', 'idx_gizi_period');
            $table->index('status', 'idx_gizi_status');
            $table->index('desa_kelurahan', 'idx_gizi_desa');
            
            // Compound Index (Gabungan) untuk query spesifik Dashboard
            // Karena sering dipakai: whereYear(period) AND where(status)
            $table->index(['period', 'status'], 'idx_gizi_period_status');
        });
    }

    public function down()
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            // Hapus index jika rollback
            $table->dropIndex('idx_gizi_user');
            $table->dropIndex('idx_gizi_period');
            $table->dropIndex('idx_gizi_status');
            $table->dropIndex('idx_gizi_desa');
            $table->dropIndex('idx_gizi_period_status');
        });
    }
};