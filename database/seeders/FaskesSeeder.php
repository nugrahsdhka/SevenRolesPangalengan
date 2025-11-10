<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FaskesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('faskes')->insert([
            [
                'nama_faskes' => 'RS Umum Karya Pangalengan Bhakti Sehat (RSU KPBS)',
                'jenis' => 'Rumah Sakit',
                'alamat' => 'Jl. Raya Pangalengan No. 340, Desa Pangalengan, Kec. Pangalengan, Kab. Bandung, Jawa Barat 40378',
                'desa_kelurahan' => 'Pangalengan',
                'lat' => -7.176282686622003,
                'lng' => 107.57285206763694,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_faskes' => 'Puskesmas Pangalengan DTP',
                'jenis' => 'Puskesmas',
                'alamat' => 'Jl. Raya Pangalengan No. 1 / Jl. Alun-Alun Pangalengan No. 1, Desa Pangalengan, Kec. Pangalengan, Kab. Bandung, Jawa Barat 40378',
                'desa_kelurahan' => 'Pangalengan',
                'lat' => -7.176432021036418,
                'lng' => 107.57093502160595,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_faskes' => 'Puskesmas Sukamanah',
                'jenis' => 'Puskesmas',
                'alamat' => 'Kp. Pintu, Desa Sukamanah, Kec. Pangalengan, Kab. Bandung, Jawa Barat',
                'desa_kelurahan' => 'Sukamanah',
                'lat' => -7.204669175058975,
                'lng' => 107.59055030626256,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_faskes' => 'Puskesmas Warnasari',
                'jenis' => 'Puskesmas',
                'alamat' => 'Jl. Raya Situ Cileunca No. 7, RT 02 RW 09, Desa Warnasari, Kec. Pangalengan, Kab. Bandung, Jawa Barat 40378',
                'desa_kelurahan' => 'Warnasari',
                'lat' => -7.194244949810938,
                'lng' => 107.54251090996532,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_faskes' => 'Klinik Pratama Sehat Medika Utama',
                'jenis' => 'Klinik',
                'alamat' => 'Jl. Raya Pangalengan, Kp. Sidamukti RT 007 RW 005, Desa Pangalengan, Kec. Pangalengan, Kab. Bandung, Jawa Barat',
                'desa_kelurahan' => 'Pangalengan',
                'lat' => -7.177903966301495,
                'lng' => 107.56860986393413,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama_faskes' => 'Apotek Pangalengan',
                'jenis' => 'Apotek',
                'alamat' => 'Jl. Pangalengan No. 358, Kp. Pangalengan, Kec. Pangalengan, Kab. Bandung, Jawa Barat 40378',
                'desa_kelurahan' => 'Pangalengan',
                'lat' => -7.176021583384276,
                'lng' => 107.57377770996516,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
