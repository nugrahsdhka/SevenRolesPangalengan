<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Kolom-kolom ini dipakai oleh model, controller, dan view Divisi
 * (Pencegahan, Penanggulangan, Desa) tetapi tidak pernah dibuat oleh migrasi
 * mana pun. Di database yang sudah berjalan kolomnya kemungkinan sudah ada
 * (dibuat manual), sehingga setiap kolom dicek dulu dengan hasColumn().
 * Di instalasi baru (php artisan migrate / test dengan SQLite), migrasi ini
 * yang membuatnya.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['data_pencegahans', 'data_penanggulangans', 'data_desas'] as $tableName) {
            if (! Schema::hasColumn($tableName, 'periode')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->date('periode')->nullable()->index();
                });
            }
        }

        if (! Schema::hasColumn('data_desas', 'nama_desa')) {
            Schema::table('data_desas', function (Blueprint $table) {
                $table->string('nama_desa')->nullable();
            });
        }

        if (! Schema::hasColumn('data_desas', 'jumlah_kasus_stunting')) {
            Schema::table('data_desas', function (Blueprint $table) {
                $table->unsignedInteger('jumlah_kasus_stunting')->default(0);
            });
        }
    }

    /**
     * Sengaja tidak menghapus kolom: di database yang sudah berjalan kolom-kolom
     * ini bukan dibuat oleh migrasi ini, jadi rollback bisa menghapus data nyata.
     */
    public function down(): void
    {
        //
    }
};
