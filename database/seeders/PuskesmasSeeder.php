<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PuskesmasSeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/puskesmas_faskes.csv');

        if (!file_exists($path)) {
            dd('File CSV tidak ditemukan di: ' . $path);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!$lines || count($lines) <= 1) {
            dd('File CSV kosong atau cuma header.');
        }

        array_shift($lines); // hapus header

        $insertCount = 0;
        $updateCount = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            $row = str_getcsv($line, ';');

            if (count($row) < 4) continue;

            $nama   = trim($row[0]);
            $alamat = trim($row[1]);
            $latRaw = str_replace(',', '.', trim($row[2]));
            $lngRaw = str_replace(',', '.', trim($row[3]));

            if ($nama === '' || $alamat === '' || $latRaw === '' || $lngRaw === '') continue;

            $lat = (float) $latRaw;
            $lng = (float) $lngRaw;

            // check if already exists
            $existing = DB::table('faskes')
                ->where('nama_faskes', $nama)
                ->where('alamat', $alamat)
                ->first();

            if ($existing) {
                // UPDATE
                DB::table('faskes')->where('id', $existing->id)->update([
                    'lat'        => $lat,
                    'lng'        => $lng,
                    'jenis'      => 'Puskesmas',
                    'is_active'  => true,
                    'updated_at' => now(),
                ]);

                $updateCount++;
            } else {
                // INSERT
                DB::table('faskes')->insert([
                    'nama_faskes' => $nama,
                    'alamat'      => $alamat,
                    'lat'         => $lat,
                    'lng'         => $lng,
                    'jenis'       => 'Puskesmas',
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);

                $insertCount++;
            }
        }

        dump("Seeder faskes selesai. Insert: {$insertCount} | Update: {$updateCount}");
    }
}
