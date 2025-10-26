<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pencegahans', function (Blueprint $table) {
            $table->date('periode')->after('user_id');
        });
        Schema::table('data_penanggulangans', function (Blueprint $table) {
            $table->date('periode')->after('user_id');
        });
        Schema::table('data_desas', function (Blueprint $table) {
            $table->date('periode')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('data_pencegahans', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
        Schema::table('data_penanggulangans', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
        Schema::table('data_desas', function (Blueprint $table) {
            $table->dropColumn('periode');
        });
    }
};