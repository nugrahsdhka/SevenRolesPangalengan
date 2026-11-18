<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Nilai harus sama dengan kolom puskesmas pada tabel faskes (data posyandu).
        $map = [
            'puskesmaspangalengan@rumahstuntingpangalengan.id' => 'PANGALENGAN DTP',
            'puskesmassukamanah@rumahstuntingpangalengan.id'   => 'SUKAMANAH',
            'puskesmaswarnasari@rumahstuntingpangalengan.id'   => 'WARNASARI',
        ];

        foreach ($map as $email => $puskesmas) {
            DB::table('users')->where('email', $email)->update(['puskesmas' => $puskesmas]);
        }
    }

    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'puskesmaspangalengan@rumahstuntingpangalengan.id',
            'puskesmassukamanah@rumahstuntingpangalengan.id',
            'puskesmaswarnasari@rumahstuntingpangalengan.id',
        ])->update(['puskesmas' => null]);
    }
};
