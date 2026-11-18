<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Puskesmas tempat petugas bertugas; menentukan pengaduan mana yang boleh ia balas
            $table->string('puskesmas', 100)->nullable()->after('email')->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['puskesmas']);
            $table->dropColumn('puskesmas');
        });
    }
};
