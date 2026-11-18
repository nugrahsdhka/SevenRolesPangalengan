<?php

namespace Database\Seeders;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Data contoh pengaduan warga untuk development / demo:
 * 3 pengaduan untuk tiap puskesmas (Pangalengan DTP, Sukamanah, Warnasari).
 *
 * Jalankan manual (tidak dimasukkan ke DatabaseSeeder agar tidak ikut ke production):
 *   php artisan db:seed --class=ComplaintSeeder
 *
 * Aman dijalankan berulang kali: tiap data dikunci oleh kode tiket tetap.
 * Nama puskesmas / desa / posyandu mengikuti tabel faskes (posyandu_faskes.csv).
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

        // Hapus data contoh lama (PGD-CONTOH01..06) yang belum punya puskesmas.
        Complaint::where('ticket_code', 'like', 'PGD-CONTOH%')->delete();

        $data = [
            // ===================== PANGALENGAN DTP =====================
            [
                'ticket_code'    => 'PGD-PGL00001',
                'nama'           => 'Siti Aminah',
                'kontak'         => '081234567801',
                'puskesmas'      => 'PANGALENGAN DTP',
                'desa_kelurahan' => 'PANGALENGAN',
                'posyandu'       => 'MAWAR 1',
                'kategori'       => 'layanan_kesehatan',
                'judul'          => 'Posyandu sudah dua bulan tidak buka',
                'isi'            => 'Posyandu Mawar 1 sudah dua bulan tidak buka sehingga balita kami tidak bisa ditimbang dan diukur tinggi badannya. Mohon ada kejelasan jadwal pelayanan.',
                'status'         => 'baru',
                'tanggapan'      => null,
                'hari_lalu'      => 1,
            ],
            [
                'ticket_code'    => 'PGD-PGL00002',
                'nama'           => null, // anonim
                'kontak'         => null,
                'puskesmas'      => 'PANGALENGAN DTP',
                'desa_kelurahan' => 'LAMAJANG',
                'posyandu'       => 'GIRIMUKTI IV',
                'kategori'       => 'kasus_stunting',
                'judul'          => 'Ada balita yang tampak sangat pendek dan kurus',
                'isi'            => 'Di lingkungan kami ada balita usia sekitar 2 tahun yang tinggi badannya jauh di bawah anak seusianya dan tampak kurus. Keluarganya belum pernah membawanya ke puskesmas. Mohon dikunjungi petugas gizi.',
                'status'         => 'diproses',
                'tanggapan'      => 'Terima kasih atas laporannya. Petugas gizi sedang menjadwalkan kunjungan rumah dan pengukuran ulang.',
                'hari_lalu'      => 4,
            ],
            [
                'ticket_code'    => 'PGD-PGL00003',
                'nama'           => 'Budi Santoso',
                'kontak'         => 'budi.santoso@example.com',
                'puskesmas'      => 'PANGALENGAN DTP',
                'desa_kelurahan' => 'TRIBAKTIMULYA',
                'posyandu'       => 'MELATI V',
                'kategori'       => 'bantuan_gizi',
                'judul'          => 'Pemberian makanan tambahan tidak merata',
                'isi'            => 'Paket makanan tambahan untuk balita di RT 02 belum kami terima, padahal RT sebelah sudah menerima dua kali. Mohon dicek pendataan penerimanya.',
                'status'         => 'selesai',
                'tanggapan'      => 'Pendataan penerima sudah diperbarui dan paket untuk RT 02 telah disalurkan melalui kader posyandu.',
                'hari_lalu'      => 12,
            ],

            // ========================= SUKAMANAH =========================
            [
                'ticket_code'    => 'PGD-SKM00001',
                'nama'           => 'Dedi Kurniawan',
                'kontak'         => null,
                'puskesmas'      => 'SUKAMANAH',
                'desa_kelurahan' => 'SUKAMANAH',
                'posyandu'       => 'MUTIARA 01',
                'kategori'       => 'data_informasi',
                'judul'          => 'Data prevalensi desa kami tampak tidak sesuai',
                'isi'            => 'Angka prevalensi stunting desa kami di halaman Data Wilayah terlihat jauh berbeda dari hasil penimbangan terakhir di posyandu. Mohon diperiksa kembali sumber datanya.',
                'status'         => 'baru',
                'tanggapan'      => null,
                'hari_lalu'      => 0,
            ],
            [
                'ticket_code'    => 'PGD-SKM00002',
                'nama'           => 'Rina Marlina',
                'kontak'         => '081234567804',
                'puskesmas'      => 'SUKAMANAH',
                'desa_kelurahan' => 'BANJARSARI',
                'posyandu'       => 'CAMELIA 01',
                'kategori'       => 'sanitasi_air',
                'judul'          => 'Sumber air bersih warga sering keruh',
                'isi'            => 'Sumber air yang dipakai sebagian besar warga di kampung kami sering keruh saat musim hujan. Banyak anak sering diare. Mohon dibantu pemeriksaan kualitas air dan perbaikan saluran.',
                'status'         => 'diproses',
                'tanggapan'      => 'Laporan diteruskan ke bidang kesehatan lingkungan untuk pemeriksaan sampel air.',
                'hari_lalu'      => 7,
            ],
            [
                'ticket_code'    => 'PGD-SKM00003',
                'nama'           => 'Euis Komariah',
                'kontak'         => '081234567806',
                'puskesmas'      => 'SUKAMANAH',
                'desa_kelurahan' => 'WANASUKA',
                'posyandu'       => 'DELIMA 1',
                'kategori'       => 'layanan_kesehatan',
                'judul'          => 'Timbangan posyandu rusak dan belum diganti',
                'isi'            => 'Timbangan di Posyandu Delima 1 rusak sejak bulan lalu sehingga hasil penimbangan balita tidak akurat. Mohon dibantu penggantian alat timbang.',
                'status'         => 'selesai',
                'tanggapan'      => 'Timbangan baru sudah diserahkan ke kader posyandu dan penimbangan ulang telah dilakukan.',
                'hari_lalu'      => 10,
            ],

            // ========================== WARNASARI ==========================
            [
                'ticket_code'    => 'PGD-WRN00001',
                'nama'           => 'Asep Hidayat',
                'kontak'         => '081234567807',
                'puskesmas'      => 'WARNASARI',
                'desa_kelurahan' => 'WARNASARI',
                'posyandu'       => 'MAWAR 12',
                'kategori'       => 'bantuan_gizi',
                'judul'          => 'Bantuan makanan tambahan belum diterima ibu hamil',
                'isi'            => 'Beberapa ibu hamil di RW kami belum menerima makanan tambahan padahal sudah terdaftar di posyandu. Mohon dicek kembali data penerimanya.',
                'status'         => 'baru',
                'tanggapan'      => null,
                'hari_lalu'      => 2,
            ],
            [
                'ticket_code'    => 'PGD-WRN00002',
                'nama'           => null, // anonim
                'kontak'         => null,
                'puskesmas'      => 'WARNASARI',
                'desa_kelurahan' => 'MARGALUYU',
                'posyandu'       => 'ANGGREK 1',
                'kategori'       => 'kasus_stunting',
                'judul'          => 'Balita tetangga berat badannya tidak naik berbulan-bulan',
                'isi'            => 'Ada balita di dekat rumah kami yang berat badannya tidak naik selama beberapa bulan dan jarang dibawa ke posyandu. Mohon ditindaklanjuti oleh petugas.',
                'status'         => 'diproses',
                'tanggapan'      => 'Petugas gizi akan melakukan kunjungan rumah minggu ini untuk pengukuran dan konseling.',
                'hari_lalu'      => 5,
            ],
            [
                'ticket_code'    => 'PGD-WRN00003',
                'nama'           => 'Yanti Rosmawati',
                'kontak'         => 'yanti.r@example.com',
                'puskesmas'      => 'WARNASARI',
                'desa_kelurahan' => 'PULOSARI',
                'posyandu'       => 'ROS 1',
                'kategori'       => 'sanitasi_air',
                'judul'          => 'Saluran air bersih bocor di dekat posyandu',
                'isi'            => 'Pipa air bersih di dekat Posyandu Ros 1 bocor dan air menggenang sehingga becek dan berbau. Mohon dibantu perbaikan karena banyak anak bermain di sekitar sini.',
                'status'         => 'selesai',
                'tanggapan'      => 'Pipa yang bocor sudah diperbaiki bersama pihak desa dan lingkungan posyandu telah dibersihkan.',
                'hari_lalu'      => 14,
            ],
        ];

        foreach ($data as $row) {
            $hariLalu = $row['hari_lalu'];
            unset($row['hari_lalu']);

            $complaint = Complaint::firstOrNew(['ticket_code' => $row['ticket_code']]);
            $complaint->fill($row);

            // Yang sudah ditanggapi: catat petugas dari puskesmas terkait & waktu tanggapan.
            if ($row['tanggapan']) {
                $complaint->ditangani_oleh = User::role('Divisi Gizi')
                    ->where('puskesmas', $row['puskesmas'])
                    ->value('id');
                $complaint->ditanggapi_at = now()->subDays(max($hariLalu - 1, 0));
            }

            // Variasikan tanggal masuk agar daftar terlihat natural.
            $complaint->created_at = now()->subDays($hariLalu)->subMinutes(random_int(0, 600));
            $complaint->save();
        }

        $this->command?->info('ComplaintSeeder: ' . count($data) . ' pengaduan contoh terisi (3 per puskesmas).');
    }
}
