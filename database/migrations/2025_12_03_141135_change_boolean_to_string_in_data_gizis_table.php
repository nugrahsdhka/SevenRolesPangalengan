<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            $table->string('kia', 20)->nullable()->change();
            $table->string('imd', 20)->nullable()->change();
            $table->string('mbg', 20)->nullable()->change();
            $table->string('edema', 20)->nullable()->change();
            $table->string('vit_a', 20)->nullable()->change();
            $table->string('pmt_biskuit', 20)->nullable()->change();
            $table->string('taburia', 20)->nullable()->change();
            $table->string('kelas_ibu_balita', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('data_gizis', function (Blueprint $table) {
            $table->boolean('kia')->nullable()->change();
            $table->boolean('imd')->nullable()->change();
            $table->boolean('mbg')->nullable()->change();
            $table->boolean('edema')->nullable()->change();
            $table->boolean('vit_a')->nullable()->change();
            $table->boolean('pmt_biskuit')->nullable()->change();
            $table->boolean('taburia')->nullable()->change();
            $table->boolean('kelas_ibu_balita')->nullable()->change();
        });
    }
};
