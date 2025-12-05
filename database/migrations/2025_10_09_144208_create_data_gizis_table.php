<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_gizis', function (Blueprint $table) {
            $table->id();

            // 1. INDEX KRUSIAL: Foreign Key otomatis ter-index
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 2. INDEX KRUSIAL: Periode
            $table->date('period')->index();

            // =============================
            // IDENTITAS POSYANDU
            // =============================
            // Ubah ke STRING agar lebih hemat & cepat dibanding TEXT
            $table->string('posyandu', 150)->nullable();
            $table->date('tgl_kunjungan')->nullable();
            
            // PERBAIKAN UTAMA: Ganti TEXT jadi STRING agar bisa di-Index & Group By Cepat
            $table->string('desa_kelurahan', 150)->nullable();
            
            $table->string('alamat', 255)->nullable(); // String 255 cukup untuk alamat
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();

            // =============================
            // DATA ANAK
            // =============================
            $table->string('nama', 150)->nullable();
            $table->date('tgl_lahir')->nullable();

            $table->unsignedSmallInteger('usia')->nullable();
            $table->unsignedTinyInteger('anak_ke')->nullable();

            $table->string('jenis_kelamin', 10)->nullable();

            // NIK sebaiknya String agar bisa dicari/di-index cepat
            $table->string('nik', 50)->nullable()->index(); 
            $table->string('nik_eppgbm', 50)->nullable();
            $table->string('nomor_kk', 50)->nullable();

            $table->decimal('bb_lahir', 8, 2)->nullable();
            $table->decimal('tb_lahir', 8, 2)->nullable();

            // =============================
            // KIA / IMD / MBG
            // =============================
            $table->string('kia', 50)->nullable();
            $table->string('imd', 50)->nullable();
            $table->string('mbg', 50)->nullable();

            // =============================
            // ASI BULAN 1–6
            // =============================
            // Pakai String cukup, jarang ada deskripsi panjang disini
            $table->string('asi_bulan_1', 50)->nullable();
            $table->string('asi_bulan_2', 50)->nullable();
            $table->string('asi_bulan_3', 50)->nullable();
            $table->string('asi_bulan_4', 50)->nullable();
            $table->string('asi_bulan_5', 50)->nullable();
            $table->string('asi_bulan_6', 50)->nullable();

            // =============================
            // PENGUKURAN
            // =============================
            $table->decimal('berat', 8, 2)->nullable();
            $table->decimal('tinggi', 8, 2)->nullable();
            $table->decimal('lila', 8, 2)->nullable();
            $table->decimal('lingkar_kepala', 8, 2)->nullable();

            $table->string('cara_ukur', 100)->nullable();
            $table->string('edema', 50)->nullable();

            // =============================
            // INTERVENSI
            // =============================
            $table->string('vit_a', 100)->nullable();
            $table->string('pmt_biskuit', 100)->nullable();
            $table->string('taburia', 100)->nullable();
            
            // Perbaikan Text yang Anda sebut error
            $table->text('kelas_ibu_balita')->nullable(); 

            // Orang Tua (Tambahan biar sinkron sama Model)
            $table->string('nama_ortu', 150)->nullable();
            $table->string('nik_ortu', 50)->nullable();
            $table->string('hp_ortu', 50)->nullable();

            // =============================
            // BB/U, TB/U, BB/TB
            // =============================
            // Status gizi string saja cukup (Sangat Pendek, Pendek, Normal)
            $table->decimal('bb_u', 8, 2)->nullable(); // Nilai Z-Score mentah
            $table->decimal('zs_bb_u', 8, 2)->nullable();
            $table->string('status_bb_u', 100)->nullable();

            $table->decimal('tb_u', 8, 2)->nullable();
            $table->decimal('zs_tb_u', 8, 2)->nullable();
            $table->string('status_tb_u', 100)->nullable();

            $table->decimal('bb_tb', 8, 2)->nullable();
            $table->decimal('zs_bb_tb', 8, 2)->nullable();
            $table->string('status_bb_tb', 100)->nullable();

            // =============================
            // WORKFLOW STATUS
            // =============================
            $table->string('status', 20)->default('pending')->index();
            $table->boolean('is_published')->default(false);

            $table->timestamps();

            // =========================================================
            // COMPOUND INDEXES (SENJATA RAHASIA AGAR TIDAK LEMOT)
            // =========================================================
            
            // 1. Untuk Dashboard Utama (Filter Periode -> Filter Status -> Group By Desa)
            // Query: select ... where period='...' and status='...' group by desa_kelurahan
            $table->index(['period', 'status', 'desa_kelurahan'], 'idx_dashboard_main');

            // 2. Untuk Filter User (Misal: User A input apa saja bulan ini?)
            $table->index(['user_id', 'period'], 'idx_user_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_gizis');
    }
};