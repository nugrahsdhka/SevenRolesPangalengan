<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            // Kapan status terakhir diubah. Dipakai untuk hitung mundur 3 bulan
            // sebelum pengaduan selesai/ditolak dihapus permanen otomatis.
            $table->timestamp('status_diperbarui_at')->nullable()->after('ditanggapi_at')->index();

            // Sampah (soft delete manual): diisi saat petugas membuang pengaduan.
            // Pengaduan tetap ada di database dan tetap bisa dicek warga lewat kode tiket.
            $table->timestamp('dibuang_at')->nullable()->after('status_diperbarui_at')->index();
            $table->foreignId('dibuang_oleh')->nullable()->after('dibuang_at')
                ->constrained('users')->nullOnDelete();
        });

        // Pengaduan lama yang sudah selesai/ditolak: anggap status terakhir diubah
        // pada updated_at, supaya hitung mundur 3 bulan tetap berjalan untuk data lama.
        DB::table('complaints')
            ->whereIn('status', ['selesai', 'ditolak'])
            ->whereNull('status_diperbarui_at')
            ->update(['status_diperbarui_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropConstrainedForeignId('dibuang_oleh');
            $table->dropIndex(['dibuang_at']);
            $table->dropIndex(['status_diperbarui_at']);
            $table->dropColumn(['dibuang_at', 'status_diperbarui_at']);
        });
    }
};
