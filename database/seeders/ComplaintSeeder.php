<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data contoh pengaduan warga untuk development / demo.
 *
 * Jalankan manual (tidak dimasukkan ke DatabaseSeeder agar tidak ikut ke production):
 *   php artisan db:seed --class=ComplaintSeeder
 *
 * Aman dijalankan berulang kali: tiap data dikunci oleh kode tiket tetap.
 */
class ComplaintSeeder extends Seeder
{
    public function run(): void
    {
        // Jangan isi data contoh di production.
        if (app()->isProduction()) {
            $this->command?->warn('ComplaintSeeder dilewati di environment production.');
            return;
        }

        // Penangan = IT Admin pertama (kalau ada). Dipakai untuk pengaduan yang sudah ditanggapi.
        $adminId = User::role('IT Admin')->value('id');

        $data = [
            [
                'ticket_code'    => 'PGD-CONTOH01',
                'nama'           => 'Siti Aminah',
                'kontak'         => '081234567801',
                'desa_kelurahan' => 'Warnasari',
                'kategori'       => 'layanan_kesehatan',
                'judul'          => 'Posyandu RW 05 sudah dua bulan tidak buka',
                'isi'            => 'Posyandu di RW 05 sudah dua bulan tidak buka sehingga balita kami tidak bisa ditimbang dan diukur tinggi badannya. Mohon ada kejelasan jadwal pelayanan.',
                'status'         => 'baru',
                'tanggapan'      => null,
                'hari_lalu'      => 1,
            ],
            [
                'ticket_code'    => 'PGD-CONTOH02',
                'nama'           => null, // anonim
                'kontak'         => null,
                'desa_kelurahan' => 'Sukamanah',
                'kategori'       => 'kasus_stunting',
                'judul'          => 'Ada balita yang tampak sangat pendek dan kurus',
                'isi'            => 'Di lingkungan kami ada balita usia sekitar 2 tahun yang tinggi badannya jauh di bawah anak seusianya dan tampak kurus. Keluarganya belum pernah dibawa ke puskesmas. Mohon dikunjungi petugas gizi.',
                'status'         => 'diproses',
                'tanggapan'      => 'Terima kasih atas laporannya. Petugas gizi puskesmas sedang menjadwalkan kunjungan rumah dan pengukuran ulang.',
                'hari_lalu'      => 4,
            ],
            [
                'ticket_code'    => 'PGD-CONTOH03',
                'nama'           => 'Budi Santoso',
                'kontak'         => 'budi.santoso@example.com',
                'desa_kelurahan' => 'Pangalengan',
                'kategori'       => 'bantuan_gizi',
                'judul'          => 'Pemberian makanan tambahan tidak merata',
                'isi'            => 'Paket makanan tambahan untuk balita di RT 02 belum kami terima, padahal RT sebelah sudah menerima dua kali. Mohon dicek pendataan penerimanya.',
                'status'         => 'selesai',
                'tanggapan'      => 'Pendataan penerima sudah diperbarui dan paket untuk RT 02 telah disalurkan melalui kader posyandu.',
                'hari_lalu'      => 12,
            ],
            [
                'ticket_code'    => 'PGD-CONTOH04',
                'nama'           => 'Rina Marlina',
                'kontak'         => '081234567804',
                'desa_kelurahan' => 'Warnasari',
                'kategori'       => 'sanitasi_air',
                'judul'          => 'Sumber air bersih warga sering keruh',
                'isi'            => 'Sumber air yang dipakai sebagian besar warga di kampung kami sering keruh saat musim hujan. Banyak anak sering diare. Mohon dibantu pemeriksaan kualitas air dan perbaikan saluran.',
                'status'         => 'diproses',
                'tanggapan'      => 'Laporan diteruskan ke bidang kesehatan lingkungan untuk pemeriksaan sampel air.',
                'hari_lalu'      => 7,
            ],
            [
                'ticket_code'    => 'PGD-CONTOH05',
                'nama'           => 'Dedi Kurniawan',
                'kontak'         => null,
                'desa_kelurahan' => 'Sukamanah',
                'kategori'       => 'data_informasi',
                'judul'          => 'Data prevalensi desa kami tampak tidak sesuai',
                'isi'            => 'Angka prevalensi stunting desa kami di halaman Data Wilayah terlihat jauh berbeda dari hasil penimbangan terakhir di posyandu. Mohon diperiksa kembali sumber datanya.',
                'status'         => 'baru',
                'tanggapan'      => null,
                'hari_lalu'      => 0,
            ],
            [
                'ticket_code'    => 'PGD-CONTOH06',
                'nama'           => null,
                'kontak'         => null,
                'desa_kelurahan' => 'Pangalengan',
                'kategori'       => 'lainnya',
                'judul'          => 'Tes pengaduan',
                'isi'            => 'Ini hanya percobaan pengiriman pengaduan dan tidak berisi laporan yang perlu ditindaklanjuti.',
                'status'         => 'ditolak',
                'tanggapan'      => 'Pengaduan ditutup karena berupa percobaan dan tidak memuat laporan yang dapat ditindaklanjuti.',
                'hari_lalu'      => 15,
            ],
        ];

        foreach ($data as $row) {
            $hariLalu = $row['hari_lalu'];
            unset($row['hari_lalu']);

            $complaint = Complaint::firstOrNew(['ticket_code' => $row['ticket_code']]);
            $complaint->fill($row);

            // Yang sudah ditanggapi: catat penangan & waktu tanggapan.
            if ($row['tanggapan']) {
                $complaint->ditangani_oleh = $adminId;
                $complaint->ditanggapi_at  = now()->subDays(max($hariLalu - 1, 0));
            }

            // Variasikan tanggal masuk agar daftar terlihat natural.
            $complaint->created_at = now()->subDays($hariLalu)->subMinutes(random_int(0, 600));
            $complaint->save();
        }

        $this->command?->info('ComplaintSeeder: ' . count($data) . ' pengaduan contoh terisi.');
    }
}