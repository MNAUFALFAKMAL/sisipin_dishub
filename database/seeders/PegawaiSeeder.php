<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pegawai;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('seeders/Data_Pegawai_9sheet.xlsx');

        if (file_exists($filePath)) {
            $this->seedFromExcel($filePath);
            return;
        }

        $this->command->warn("Excel file not found at: {$filePath}");
        $this->seedFromConfig();
    }

    /**
     * Seed data pegawai dari file Excel (sheet SEMUA PEGAWAI).
     */
    protected function seedFromExcel(string $filePath): void
    {
        $this->command->info("Loading Excel file: {$filePath}...");

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getSheetByName('SEMUA PEGAWAI');

            if (!$sheet) {
                $this->command->error("Sheet 'SEMUA PEGAWAI' not found in the Excel file!");
                $this->seedFromConfig();
                return;
            }

            $highestRow = $sheet->getHighestRow();
            $this->command->info("Reading {$highestRow} rows from sheet 'SEMUA PEGAWAI'...");

            $count = 0;

            $urutan = 0;
            for ($row = 2; $row <= $highestRow; $row++) {
                $nama = trim((string) ($sheet->getCell('B' . $row)->getValue() ?? ''));

                if ($nama === '') {
                    continue;
                }

                $urutan++;
                $nip = trim((string) ($sheet->getCell('C' . $row)->getValue() ?? ''));
                $pangkat = trim((string) ($sheet->getCell('D' . $row)->getValue() ?? ''));
                $golongan = trim((string) ($sheet->getCell('E' . $row)->getValue() ?? ''));
                $jabatan = trim((string) ($sheet->getCell('F' . $row)->getValue() ?? ''));
                $unitBidang = trim((string) ($sheet->getCell('G' . $row)->getValue() ?? ''));

                Pegawai::updateOrCreate(
                    ['nama' => $nama],
                    [
                        'nip' => $nip !== '' ? $nip : null,
                        'pangkat' => $pangkat !== '' ? $pangkat : null,
                        'golongan' => $golongan !== '' ? $golongan : null,
                        'jabatan' => $jabatan !== '' ? $jabatan : null,
                        'unit_bidang' => $unitBidang !== '' ? $unitBidang : null,
                        'status' => 'aktif',
                        'urutan_excel' => $urutan,
                    ]
                );

                $count++;
            }

            $this->command->info("Seeded {$count} pegawai dari Excel.");
        } catch (\Throwable $e) {
            Log::error('PegawaiSeeder Excel gagal: ' . $e->getMessage());
            $this->command->error('Gagal membaca Excel: ' . $e->getMessage());
            $this->seedFromConfig();
        }
    }

    /**
     * Fallback: seed dari config/pegawai_daftar_nama.php bila Excel tidak tersedia.
     */
    protected function seedFromConfig(): void
    {
        $names = config('pegawai_daftar_nama', []);

        if (empty($names)) {
            $this->command->warn('Config pegawai_daftar_nama kosong — tidak ada data untuk di-seed.');
            return;
        }

        $count = 0;
        foreach ($names as $nama) {
            $nama = trim((string) $nama);
            if ($nama === '') {
                continue;
            }

            Pegawai::updateOrCreate(
                ['nama' => $nama],
                ['status' => 'aktif']
            );

            $count++;
        }

        $this->command->info("Seeded {$count} pegawai dari config/pegawai_daftar_nama.php.");
    }
}
