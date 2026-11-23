<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hapus permanen pengaduan selesai/ditolak yang statusnya sudah lebih dari 3 bulan.
// Butuh cron server: * * * * * cd /path-ke-project && php artisan schedule:run >> /dev/null 2>&1
Schedule::command('complaints:purge-expired')->dailyAt('02:00');
