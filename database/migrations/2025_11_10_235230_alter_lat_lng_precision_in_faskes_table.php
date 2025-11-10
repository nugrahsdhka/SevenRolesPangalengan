<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            // Sesuaikan precision-nya
            $table->decimal('lat', 10, 8)->change();
            $table->decimal('lng', 11, 8)->change();
        });
    }

    public function down(): void
    {
        Schema::table('faskes', function (Blueprint $table) {
            // Balik ke semula kalau tadinya 10,2 (sesuaikan)
            $table->decimal('lat', 10, 2)->change();
            $table->decimal('lng', 11, 2)->change();
        });
    }
};
