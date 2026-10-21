<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Bidang;
use App\Models\Peran;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial data.
     * - Bidang-bidang Dishub Lampung
     * - Peran (Super Admin, Tata Usaha, Pegawai, dll)
     * - Super Admin default
     */
    public function run(): void
    {
        // ── 1. Buat peran (roles) ─────────────────────────────────────────────
        $this->call(PeranSeeder::class);

        // ── 2. Buat bidang-bidang Dishub Lampung ──────────────────────────────
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Bidang::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $bidangs = [
            ['nama_bidang' => 'Sekretaris', 'kode_bidang' => 'SEK'],
            ['nama_bidang' => 'Sub Bag Umum dan Kep', 'kode_bidang' => 'UMK'],
            ['nama_bidang' => 'Sub Bag Keu dan Aset', 'kode_bidang' => 'KUA'],
            ['nama_bidang' => 'Sub Bag Perencanaan', 'kode_bidang' => 'REN'],
            ['nama_bidang' => 'Bidang LLA', 'kode_bidang' => 'LLA'],
            ['nama_bidang' => 'Bidang TSP', 'kode_bidang' => 'TSP'],
            ['nama_bidang' => 'Bidang Binkes', 'kode_bidang' => 'BKS'],
            ['nama_bidang' => 'Bidang Pengembangan', 'kode_bidang' => 'PGB'],
            ['nama_bidang' => 'UPTD P3LLAJ', 'kode_bidang' => 'P3L'],
            ['nama_bidang' => 'UPTD Kepelabuhan', 'kode_bidang' => 'KPL'],
            ['nama_bidang' => 'UPTD Terminal', 'kode_bidang' => 'TRM'],
        ];

        foreach ($bidangs as $bidang) {
            Bidang::create($bidang);
        }

        // Truncate users table
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        User::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // ── 3. Buat akun Super Admin default ──────────────────────────────────
        $superAdminPeran = Peran::where('slug', 'super_admin')->first();
        $sekretarisBidang = Bidang::where('kode_bidang', 'SEK')->first();

        User::create([
            'name'      => 'Super Administrator',
            'nip'       => '199001012020011001',
            'email'     => 'dishub2026lampung',
            'peran_id'  => $superAdminPeran?->id,
            'bidang_id' => $sekretarisBidang?->id,
            'password'  => Hash::make('dishublampung12345'),
            'is_active' => true,
        ]);

        // ── 4. Import Pengaturan Aplikasi ─────────────────────────────────────
        $this->call(PengaturanSeeder::class);

        // ── 5. Import Klasifikasi Arsip (JRA & SKKAAD) ────────────────────────
        $this->call(KlasifikasiArsipSeeder::class);

        // ── 6. Data Pegawai (sumber picker Tujuan SPT) ───────────────────────
        $this->call(PegawaiSeeder::class);

        // ── 7. Import data transaksi mock (Dinonaktifkan untuk hosting bersih) ──
        // $this->call(ArsipDishubSeeder::class);

        $this->command->info('✅ Seeder selesai! Akun yang dibuat:');
        $this->command->table(
            ['Email / Username', 'Role', 'Password'],
            [
                ['dishub2026lampung', 'super_admin',  'dishublampung12345'],
            ]
        );
    }
}
