<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('data_gizis', function (Blueprint $table) {
            $table->id();

            // Relasi ke user penginput
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Periode (tanggal 1 setiap bulan), penting untuk filter & grafik
            $table->date('period')->index();

            // ================================
            // Kolom identitas posyandu & kunjungan
            // ================================
            // Nama Posyandu
            $table->string('posyandu', 100)->nullable();

            // Tgl Kunjungan
            $table->date('tgl_kunjungan')->nullable();

            // Desa/Kel
            $table->string('desa_kelurahan', 100);

            // Alamat, RT, RW
            $table->string('alamat', 255)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();

            // ================================
            // Data anak
            // ================================
            // Nama Anak
            $table->string('nama', 100);

            // Tgl Lahir
            $table->date('tgl_lahir')->nullable();

            // Usia (mis: dalam bulan)
            $table->unsignedSmallInteger('usia')->nullable();

            // Anak Ke-
            $table->unsignedTinyInteger('anak_ke')->nullable();

            // Jenis Kelamin (L/P)
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();

            // NIK
            $table->string('nik', 20)->nullable();

            // NIK EPPGBM
            $table->string('nik_eppgbm', 30)->nullable();

            // Nomor KK
            $table->string('nomor_kk', 30)->nullable();

            // BB Lahir (kg), TB Lahir (cm)
            $table->decimal('bb_lahir', 5, 2)->nullable();
            $table->decimal('tb_lahir', 5, 2)->nullable();

            // ================================
            // Status KIA, IMD, MBG
            // (nullable boolean: null = belum diisi)
            // ================================
            $table->boolean('kia')->nullable();   // punya buku KIA atau tidak
            $table->boolean('imd')->nullable();   // Inisiasi Menyusu Dini
            $table->boolean('mbg')->nullable();   // Menyusu Bayi/Gizi (sesuai definisi internal)

            // ================================
            // ASI Bulan 1 - 6
            // (biasanya berupa kode/status: eksklusif, parsial, dsb → pakai string)
            // ================================
            $table->string('asi_bulan_1', 50)->nullable();
            $table->string('asi_bulan_2', 50)->nullable();
            $table->string('asi_bulan_3', 50)->nullable();
            $table->string('asi_bulan_4', 50)->nullable();
            $table->string('asi_bulan_5', 50)->nullable();
            $table->string('asi_bulan_6', 50)->nullable();

            // ================================
            // Pengukuran saat kunjungan
            // ================================
            // BB (kg) & TB (cm)
            $table->decimal('berat', 5, 2);     // BB (kg)
            $table->decimal('tinggi', 5, 2);    // TB (cm)

            // LILA & Lingkar Kepala
            $table->decimal('lila', 5, 2)->nullable();
            $table->decimal('lingkar_kepala', 5, 2)->nullable();

            // Cara Ukur
            $table->string('cara_ukur', 50)->nullable();

            // Edema (0/1)
            $table->boolean('edema')->nullable();

            // ================================
            // Vitamin & Intervensi
            // ================================
            $table->boolean('vit_a')->nullable();
            $table->boolean('pmt_biskuit')->nullable();
            $table->boolean('taburia')->nullable();
            $table->boolean('kelas_ibu_balita')->nullable();

            // ================================
            // Data orang tua
            // ================================
            $table->string('nama_ortu', 100)->nullable();
            $table->string('nik_ortu', 30)->nullable();
            $table->string('hp_ortu', 20)->nullable();

            // ================================
            // Kolom BB/U
            // ================================
            // BB/U (bisa berupa kategori/kelas singkat atau simbol, jadi string)
            $table->string('bb_u', 20)->nullable();

            // ZS BB/U
            $table->decimal('zs_bb_u', 4, 2)->nullable();

            // Kategori BB/U → pakai status_bb_u (mapping ke header "Kategori BB/U")
            $table->string('status_bb_u', 50)->nullable();

            // ================================
            // Kolom TB/U
            // ================================
            $table->string('tb_u', 20)->nullable();
            $table->decimal('zs_tb_u', 4, 2)->nullable();
            $table->string('status_tb_u', 50)->nullable();

            // ================================
            // Kolom BB/TB
            // ================================
            $table->string('bb_tb', 20)->nullable();
            $table->decimal('zs_bb_tb', 4, 2)->nullable();
            $table->string('status_bb_tb', 50)->nullable();

            // ================================
            // Workflow status
            // ================================
            $table->string('status')->default('pending');   // pending, approved, published
            $table->boolean('is_published')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('data_gizis');
    }
};
