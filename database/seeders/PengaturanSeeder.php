<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengaturans = [
            // General settings
            [
                'kunci'       => 'app_name',
                'nilai'       => 'SISIPIN DISHUB Lampung',
                'tipe'        => 'string',
                'kelompok'    => 'general',
                'label'       => 'Nama Aplikasi',
                'deskripsi'   => 'Nama yang ditampilkan di header dan title',
                'is_public'   => true,
                'is_active'   => true,
            ],
            [
                'kunci'       => 'app_short_name',
                'nilai'       => 'SISIPIN',
                'tipe'        => 'string',
                'kelompok'    => 'general',
                'label'       => 'Nama Singkat Aplikasi',
                'deskripsi'   => 'Nama singkat untuk logo dan favicon',
                'is_public'   => true,
                'is_active'   => true,
            ],
            [
                'kunci'       => 'items_per_page',
                'nilai'       => '15',
                'tipe'        => 'integer',
                'kelompok'    => 'general',
                'label'       => 'Item per Halaman',
                'deskripsi'   => 'Jumlah data per halaman pada tabel',
                'is_public'   => false,
                'is_active'   => true,
            ],
            [
                'kunci'       => 'default_timezone',
                'nilai'       => 'Asia/Jakarta',
                'tipe'        => 'string',
                'kelompok'    => 'general',
                'label'       => 'Zona Waktu Default',
                'deskripsi'   => 'Zona waktu untuk seluruh aplikasi',
                'is_public'   => false,
                'is_active'   => true,
            ],

            // Surat settings
            [
                'kunci'       => 'surat_masuk_prefix',
                'nilai'       => 'SM',
                'tipe'        => 'string',
                'kelompok'    => 'surat',
                'label'       => 'Prefix Nomor Surat Masuk',
                'deskripsi'   => 'Prefix untuk penomoran otomatis surat masuk',
                'is_public'   => false,
                'is_active'   => true,
            ],
            [
                'kunci'       => 'surat_keluar_prefix',
                'nilai'       => 'SK',
                'tipe'        => 'string',
                'kelompok'    => 'surat',
                'label'       => 'Prefix Nomor Surat Keluar',
                'deskripsi'   => 'Prefix untuk penomoran otomatis surat keluar',
                'is_public'   => false,
                'is_active'   => true,
            ],
            [
                'kunci'       => 'auto_number_format',
                'nilai'       => '{PREFIX}/{ROMAN}/{TAHUN}/{NOMOR}',
                'tipe'        => 'string',
                'kelompok'    => 'surat',
                'label'       => 'Format Nomor Otomatis',
                'deskripsi'   => 'Format: {PREFIX}/{ROMAN}/{TAHUN}/{NOMOR} (contoh: SK/IV/2026/001)',
                'is_public'   => false,
                'is_active'   => true,
            ],

            // File upload settings
            [
                'kunci'       => 'max_file_size_mb',
                'nilai'       => '10',
                'tipe'        => 'integer',
                'kelompok'    => 'file',
                'label'       => 'Maksimal Ukuran File (MB)',
                'deskripsi'   => 'Batas ukuran file upload untuk lampiran surat',
                'is_public'   => false,
                'is_active'   => true,
            ],
            [
                'kunci'       => 'allowed_file_types',
                'nilai'       => '["pdf","doc","docx","jpg","jpeg","png"]',
                'tipe'        => 'json',
                'kelompok'    => 'file',
                'label'       => 'Tipe File Diizinkan',
                'deskripsi'   => 'Daftar ekstensi file yang diizinkan untuk upload',
                'is_public'   => false,
                'is_active'   => true,
            ],

            // Email settings
            [
                'kunci'       => 'mail_from_address',
                'nilai'       => 'no-reply@dishub.lampungprov.go.id',
                'tipe'        => 'string',
                'kelompok'    => 'email',
                'label'       => 'Email Pengirim Default',
                'deskripsi'   => 'Alamat email untuk pengiriman notifikasi sistem',
                'is_public'   => false,
                'is_active'   => true,
            ],
            [
                'kunci'       => 'mail_from_name',
                'nilai'       => 'SISIPIN DISHUB Lampung',
                'tipe'        => 'string',
                'kelompok'    => 'email',
                'label'       => 'Nama Pengirim Email',
                'deskripsi'   => 'Nama yang ditampilkan pada email notifikasi',
                'is_public'   => false,
                'is_active'   => true,
            ],
        ];

        foreach ($pengaturans as $pengaturan) {
            Pengaturan::updateOrCreate(
                ['kunci' => $pengaturan['kunci']],
                $pengaturan
            );
        }

        $this->command->info('✅ Seeder Pengaturan selesai! Total: ' . count($pengaturans) . ' pengaturan');
    }
}