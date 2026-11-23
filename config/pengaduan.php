<?php

/*
|--------------------------------------------------------------------------
| Notifikasi email pengaduan warga
|--------------------------------------------------------------------------
| Alamat email TIDAK ditulis di sini (repo ini publik). Isi lewat .env,
| lihat blok PENGADUAN_MAIL_* di .env.example.
|
| Kunci puskesmas harus SAMA PERSIS (huruf besar) dengan kolom `puskesmas`
| pada tabel faskes / posyandu_faskes.csv.
*/
return [
    'notify' => [
        'puskesmas' => [
            'PANGALENGAN DTP' => env('PENGADUAN_MAIL_PANGALENGAN'),
            'WARNASARI'       => env('PENGADUAN_MAIL_WARNASARI'),
            'SUKAMANAH'       => env('PENGADUAN_MAIL_SUKAMANAH'),
        ],

        // Selalu menerima email untuk SEMUA pengaduan (hanya memantau).
        'camat' => env('PENGADUAN_MAIL_CAMAT'),
        'admin' => env('PENGADUAN_MAIL_ADMIN'),
    ],
];
