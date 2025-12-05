<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_gizis', function (Blueprint $table) {
            $table->id();

            // USER PENGINPUT
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // PERIODE (SELALU TGL 1)
            $table->date('period')->index();

            // =============================
            // IDENTITAS POSYANDU
            // =============================
            $table->text('posyandu')->nullable();            // EXCEL bisa panjang & ada formula
            $table->date('tgl_kunjungan')->nullable();
            $table->text('desa_kelurahan', 150)->nullable();      // WAJIB TEXT karena bisa ada formula
            $table->text('alamat')->nullable();              // bisa panjang & ada dummyfunction

            $table->string('rt', 50)->nullable();            // antisipasi format aneh
            $table->string('rw', 50)->nullable();

            // =============================
            // DATA ANAK
            // =============================
            $table->text('nama')->nullable();                // formula → TEXT
            $table->date('tgl_lahir')->nullable();

            $table->unsignedSmallInteger('usia')->nullable();
            $table->unsignedTinyInteger('anak_ke')->nullable();

            $table->string('jenis_kelamin', 10)->nullable();

            $table->text('nik')->nullable();                 // Excel memunculkan formula → TEXT wajib
            $table->text('nik_eppgbm')->nullable();
            $table->text('nomor_kk')->nullable();

            $table->decimal('bb_lahir', 8, 2)->nullable();
            $table->decimal('tb_lahir', 8, 2)->nullable();

            // =============================
            // KIA / IMD / MBG
            // =============================
            // Excel mengisi teks "Ya", "Tidak", atau formula IFERROR
            $table->text('kia')->nullable();
            $table->text('imd')->nullable();
            $table->text('mbg')->nullable();

            // =============================
            // ASI BULAN 1–6
            // =============================
            $table->text('asi_bulan_1')->nullable();
            $table->text('asi_bulan_2')->nullable();
            $table->text('asi_bulan_3')->nullable();
            $table->text('asi_bulan_4')->nullable();
            $table->text('asi_bulan_5')->nullable();
            $table->text('asi_bulan_6')->nullable();

            // =============================
            // PENGUKURAN
            // =============================
            // Kolom ini harus NULLABLE karena Excel sering kosong
            $table->decimal('berat', 8, 2)->nullable();
            $table->decimal('tinggi', 8, 2)->nullable();

            $table->decimal('lila', 8, 2)->nullable();
            $table->decimal('lingkar_kepala', 8, 2)->nullable();

            $table->text('cara_ukur')->nullable();
            $table->text('edema')->nullable();

            // =============================
            // INTERVENSI
            // =============================
            $table->text('vit_a')->nullable();
            $table->text('pmt_biskuit')->nullable();
            $table->text('taburia')->nullable();
            $table->text('kelas_ibu_balita')->nullable();    // WAJIB TEXT (file kamu error disini!)

            // =============================
            // BB/U, TB/U, BB/TB
            // =============================
            $table->text('bb_u')->nullable();
            $table->decimal('zs_bb_u', 8, 2)->nullable();
            $table->text('status_bb_u')->nullable();

            $table->text('tb_u')->nullable();
            $table->decimal('zs_tb_u', 8, 2)->nullable();
            $table->text('status_tb_u')->nullable();

            $table->text('bb_tb')->nullable();
            $table->decimal('zs_bb_tb', 8, 2)->nullable();
            $table->text('status_bb_tb')->nullable();

            // =============================
            // WORKFLOW STATUS
            // =============================
            $table->string('status', 20)->default('pending');
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_gizis');
    }
};
