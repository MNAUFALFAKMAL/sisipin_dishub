<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peran;

class PeranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perans = [
            [
                'nama'      => 'Super Admin',
                'slug'      => 'super_admin',
                'deskripsi' => 'Akses penuh ke seluruh sistem termasuk manajemen pengguna dan pengaturan',
                'is_active' => true,
            ],
            [
                'nama'      => 'Tata Usaha',
                'slug'      => 'tata_usaha',
                'deskripsi' => 'Mengelola surat masuk/keluar, disposisi, dan administrasi umum',
                'is_active' => true,
            ],
            [
                'nama'      => 'Pegawai',
                'slug'      => 'pegawai',
                'deskripsi' => 'Membuat dan mengelola surat serta tugas sehari-hari',
                'is_active' => true,
            ],
            [
                'nama'      => 'Kepala Dinas',
                'slug'      => 'kepala_dinas',
                'deskripsi' => 'Menyetujui surat keluar dan mengakses laporan/laporan dashboard',
                'is_active' => true,
            ],
            [
                'nama'      => 'Kepala Bidang',
                'slug'      => 'kepala_bidang',
                'deskripsi' => 'Menyetujui disposisi dan mengelola surat dalam bidangnya',
                'is_active' => true,
            ],
            [
                'nama'      => 'Staff',
                'slug'      => 'staff',
                'deskripsi' => 'Membuat surat dan melaksanakan tugas operasional',
                'is_active' => true,
            ],
        ];

        foreach ($perans as $peran) {
            Peran::updateOrCreate(
                ['slug' => $peran['slug']],
                $peran
            );
        }

        $this->command->info('✅ Seeder Peran selesai! Total: ' . count($perans) . ' peran');
    }
}