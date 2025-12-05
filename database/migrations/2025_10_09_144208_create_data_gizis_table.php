<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('data_gizis', function (Blueprint $table) {
            $table->id();

            // Relasi ke user
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Periode (tanggal 1 bulan tersebut)
            $table->date('period')->index();

            // ================================
            // IDENTITAS POSYANDU & KUNJUNGAN
            // ================================
            $table->string('posyandu', 100)->nullable();
            $table->date('tgl_kunjungan')->nullable();
            $table->string('desa_kelurahan', 100)->nullable();

            // Alamat / RT / RW
            $table->string('alamat', 255)->nullable();
            $table->string('rt', 50)->nullable();   // aman dari berbagai format Excel
            $table->string('rw', 50)->nullable();

            // ================================
            // DATA ANAK
            // ================================
            $table->string('nama', 100)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->unsignedSmallInteger('usia')->nullable();
            $table->unsignedTinyInteger('anak_ke')->nullable();
            $table->string('jenis_kelamin', 5)->nullable();

            $table->string('nik', 30)->nullable();
            $table->string('nik_eppgbm', 50)->nullable();
            $table->string('nomor_kk', 30)->nullable();

            $table->decimal('bb_lahir', 6, 2)->nullable();
            $table->decimal('tb_lahir', 6, 2)->nullable();

            // ================================
            // KIA / IMD / MBG (banyak variasi, jadi string supaya aman)
            // ================================
            $table->string('kia', 20)->nullable();
            $table->string('imd', 20)->nullable();
            $table->string('mbg', 20)->nullable();

            // ================================
            // ASI Bulan 1–6
            // ================================
            $table->string('asi_bulan_1', 50)->nullable();
            $table->string('asi_bulan_2', 50)->nullable();
            $table->string('asi_bulan_3', 50)->nullable();
            $table->string('asi_bulan_4', 50)->nullable();
            $table->string('asi_bulan_5', 50)->nullable();
            $table->string('asi_bulan_6', 50)->nullable();

            // ================================
            // PENGUKURAN KUNJUNGAN
            // ================================
            $table->decimal('berat', 6, 2)->nullable();    // HARUS NULLABLE
            $table->decimal('tinggi', 6, 2)->nullable();   // HARUS NULLABLE

            $table->decimal('lila', 6, 2)->nullable();
            $table->decimal('lingkar_kepala', 6, 2)->nullable();
            $table->string('cara_ukur', 50)->nullable();
            $table->string('edema', 20)->nullable();

            // ================================
            // VITAMIN & INTERVENSI
            // ================================
            $table->string('vit_a', 20)->nullable();
            $table->string('pmt_biskuit', 20)->nullable();
            $table->string('taburia', 20)->nullable();
            $table->string('kelas_ibu_balita')->nullable();

            // ================================
            // DATA ORANG TUA
            // ================================
            $table->string('nama_ortu', 100)->nullable();
            $table->string('nik_ortu', 50)->nullable();
            $table->string('hp_ortu', 30)->nullable();

            // ================================
            // BB / TB / BB-TB
            // ================================
            // Semua kolom status STRING
            // Semua Z-score DECIMAL
            // Semua angka mentah STRING (bukan decimal)
            $table->string('bb_u', 20)->nullable();
            $table->decimal('zs_bb_u', 5, 2)->nullable();
            $table->string('status_bb_u', 50)->nullable();

            $table->string('tb_u', 20)->nullable();
            $table->decimal('zs_tb_u', 5, 2)->nullable();
            $table->string('status_tb_u', 50)->nullable();

            $table->string('bb_tb', 20)->nullable();
            $table->decimal('zs_bb_tb', 5, 2)->nullable();
            $table->string('status_bb_tb', 50)->nullable();

            // ================================
            // WORKFLOW STATUS
            // ================================
            $table->string('status', 20)->default('pending');
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('data_gizis');
    }
};
