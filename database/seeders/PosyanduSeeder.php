<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosyanduSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/posyandu_faskes.csv');

        if (!file_exists($path)) {
            dd('File CSV tidak ditemukan di: ' . $path);
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle); // buang header

        if (!$header) {
            dd('File CSV kosong.');
        }

        $insertCount = 0;
        $updateCount = 0;
        $seen        = [];

        DB::transaction(function () use ($handle, &$insertCount, &$updateCount, &$seen) {
            // Semua posyandu lama dinonaktifkan dulu. Yang ada di CSV baru
            // akan diaktifkan lagi di bawah, sisanya tetap nonaktif (tidak dihapus).
            DB::table('faskes')->where('jenis', 'Posyandu')->update([
                'is_active'  => false,
                'updated_at' => now(),
            ]);

            // Kolom CSV: nama_posyandu,desa,puskesmas,lat,lng,alamat_posyandu,google_maps
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 6) continue;

                $nama = trim($row[0]);
                $desa = trim($row[1]);
                $puskesmas = trim($row[2]);
                $lat  = (float) str_replace(',', '.', trim($row[3]));
                $lng  = (float) str_replace(',', '.', trim($row[4]));
                $alamat = trim($row[5]);

                if ($nama === '' || $desa === '' || $puskesmas === '' || $lat == 0.0 || $lng == 0.0) continue;

                // Pengaman: lat Pangalengan harus negatif, lng sekitar 107.x
                if ($lat > 0 || $lng < 107 || $lng > 108) {
                    dump("Dilewati (koordinat tidak wajar): {$nama} ({$lat}, {$lng})");
                    continue;
                }

                $key = strtolower($nama . '|' . $desa);
                if (isset($seen[$key])) continue; // hindari duplikat dalam file
                $seen[$key] = true;

                $existing = DB::table('faskes')
                    ->where('jenis', 'Posyandu')
                    ->where('nama_faskes', $nama)
                    ->where('desa_kelurahan', $desa)
                    ->first();

                $data = [
                    'lat'            => $lat,
                    'lng'            => $lng,
                    'desa_kelurahan' => $desa,
                    'puskesmas'      => $puskesmas,
                    'alamat'         => $alamat !== '' ? $alamat : null,
                    'is_active'      => true,
                    'updated_at'     => now(),
                ];

                if ($existing) {
                    DB::table('faskes')->where('id', $existing->id)->update($data);
                    $updateCount++;
                } else {
                    DB::table('faskes')->insert($data + [
                        'nama_faskes' => $nama,
                        'jenis'       => 'Posyandu',
                        'created_at'  => now(),
                    ]);
                    $insertCount++;
                }
            }
        });

        fclose($handle);

        dump("Seeder posyandu selesai. Insert: {$insertCount} | Update: {$updateCount}");
    }
}
