<?php

namespace App\Console\Commands;

use App\Models\Complaint;
use Illuminate\Console\Command;

class PurgeExpiredComplaints extends Command
{
    protected $signature = 'complaints:purge-expired {--dry-run : Hanya tampilkan jumlahnya, tidak menghapus}';

    protected $description = 'Hapus permanen pengaduan Selesai/Ditolak yang statusnya diperbarui lebih dari 3 bulan lalu';

    public function handle(): int
    {
        $query = Complaint::kedaluwarsa();
        $total = (clone $query)->count();

        if ($this->option('dry-run')) {
            $this->info("{$total} pengaduan akan dihapus permanen (dry-run, tidak ada yang dihapus).");

            return self::SUCCESS;
        }

        $deleted = 0;
        $query->chunkById(200, function ($complaints) use (&$deleted) {
            foreach ($complaints as $complaint) {
                $complaint->delete();
                $deleted++;
            }
        });

        $this->info("{$deleted} pengaduan kedaluwarsa dihapus permanen.");

        return self::SUCCESS;
    }
}
