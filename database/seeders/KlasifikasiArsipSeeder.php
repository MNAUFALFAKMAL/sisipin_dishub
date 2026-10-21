<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KlasifikasiArsip;
use Illuminate\Support\Facades\Log;

class KlasifikasiArsipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Try CSV first (preferred), fallback to Excel
        $csvPath = database_path('seeders/data/klasifikasi_arsip.csv');
        $excelPath = database_path('seeders/KA-JRA- SKKAAD 2026.xlsx');

        if (file_exists($csvPath)) {
            $this->seedFromCsv($csvPath);
        } elseif (file_exists($excelPath)) {
            $this->seedFromExcel($excelPath);
        } else {
            $this->command->error("File not found: {$csvPath} or {$excelPath}");
        }
    }

    /**
     * Seed from CSV file
     */
    private function seedFromCsv(string $filePath): void
    {
        $this->command->info("Loading CSV file: {$filePath}...");

        try {
            $handle = fopen($filePath, 'r');
            if (!$handle) {
                $this->command->error("Cannot open CSV file: {$filePath}");
                return;
            }

            // Read header
            $header = fgetcsv($handle);
            if (!$header) {
                $this->command->error("CSV file is empty or invalid");
                fclose($handle);
                return;
            }

            // Normalize headers
            $header = array_map('trim', $header);
            $expectedHeaders = [
                'kode', 'nama', 'parent_kode', 'retensi_aktif_tahun',
                'retensi_inaktif_tahun', 'nasib_akhir', 'keamanan',
                'hak_akses', 'dasar_pertimbangan', 'unit_pengolah'
            ];

            $count = 0;
            $insertedCodes = [];

            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < count($expectedHeaders)) {
                    // Pad row if needed
                    $row = array_pad($row, count($expectedHeaders), '');
                }

                $data = array_combine($expectedHeaders, $row);

                $code = trim($data['kode'] ?? '');
                $name = trim($data['nama'] ?? '');
                $parentCode = trim($data['parent_kode'] ?? '');

                if (!$code || !$name) {
                    continue;
                }

                // Resolve parent_id from parent_kode
                $parentId = null;
                if ($parentCode && isset($insertedCodes[$parentCode])) {
                    $parentId = $insertedCodes[$parentCode];
                }

                // Parse numeric fields
                $retensiAktif = (int) ($data['retensi_aktif_tahun'] ?? 0);
                $retensiInaktif = (int) ($data['retensi_inaktif_tahun'] ?? 0);
                $nasibAkhir = $this->parseNasibAkhir($data['nasib_akhir'] ?? '');

                $record = KlasifikasiArsip::updateOrCreate(
                    ['kode' => $code],
                    [
                        'nama' => $name,
                        'parent_id' => $parentId,
                        'retensi_aktif_tahun' => $retensiAktif,
                        'retensi_inaktif_tahun' => $retensiInaktif,
                        'nasib_akhir' => $nasibAkhir,
                        'keamanan' => $data['keamanan'] ?: null,
                        'hak_akses' => $data['hak_akses'] ?: null,
                        'dasar_pertimbangan' => $data['dasar_pertimbangan'] ?: null,
                        'unit_pengolah' => $data['unit_pengolah'] ?: null,
                    ]
                );

                $insertedCodes[$code] = $record->id;
                $count++;
            }

            fclose($handle);

            $this->command->info("✅ Seeding Klasifikasi Arsip (CSV) selesai! Total: {$count}");

        } catch (\Exception $e) {
            $this->command->error("Error seeding klasifikasi from CSV: " . $e->getMessage());
            Log::error($e);
        }
    }

    /**
     * Seed from Excel file (fallback)
     */
    private function seedFromExcel(string $filePath): void
    {
        $this->command->info("Loading Excel file: {$filePath}...");

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
            $sheet = $spreadsheet->getSheetByName('KA-JRA-SKKAAD');

            if (!$sheet) {
                $this->command->error("Sheet 'KA-JRA-SKKAAD' not found in the Excel file!");
                return;
            }

            $highestRow = $sheet->getHighestRow();
            $this->command->info("Reading {$highestRow} rows from sheet 'KA-JRA-SKKAAD'...");

            $insertedCodes = [];
            $count = 0;

            for ($row = 5; $row <= $highestRow; $row++) {
                $colA = trim($sheet->getCell('A' . $row)->getValue() ?? '');
                $colB = trim($sheet->getCell('B' . $row)->getValue() ?? '');
                $colC = trim($sheet->getCell('C' . $row)->getValue() ?? '');
                $colD = trim($sheet->getCell('D' . $row)->getValue() ?? '');
                $colE = trim($sheet->getCell('E' . $row)->getValue() ?? '');

                $colF = trim($sheet->getCell('F' . $row)->getValue() ?? ''); // Retensi Aktif
                $colG = trim($sheet->getCell('G' . $row)->getValue() ?? ''); // Retensi Inaktif
                $colH = trim($sheet->getCell('H' . $row)->getValue() ?? ''); // Nasib Akhir
                $colI = trim($sheet->getCell('I' . $row)->getValue() ?? ''); // Keamanan
                $colJ = trim($sheet->getCell('J' . $row)->getValue() ?? ''); // Hak Akses
                $colK = trim($sheet->getCell('K' . $row)->getValue() ?? ''); // Dasar Pertimbangan
                $colL = trim($sheet->getCell('L' . $row)->getValue() ?? ''); // Unit Pengolah

                $code = null;
                $name = null;

                // Determine hierarchy and code/name
                if (!empty($colA) && preg_match('/^\d+$/', $colA)) {
                    $code = $colA;
                    $name = $colB;
                } elseif (!empty($colB) && preg_match('/^\d+\.\d+$/', $colB)) {
                    $code = $colB;
                    $name = $colC;
                } elseif (!empty($colC) && preg_match('/^\d+\.\d+\.\d+$/', $colC)) {
                    $code = $colC;
                    $name = $colD;
                } elseif (!empty($colD) && preg_match('/^\d+\.\d+\.\d+\.\d+$/', $colD)) {
                    $code = $colD;
                    $name = $colE;
                }

                if ($code === '500.14.5') {
                    $name = 'Evaluasi dan Pelaporan Sensus, Survei, dan Konsolidasi Data Statistik';
                }

                if (!$code || !$name) {
                    continue;
                }

                // Resolve parent_id
                $parentCode = $this->getParentCode($code);
                $parentId = null;
                if ($parentCode && isset($insertedCodes[$parentCode])) {
                    $parentId = $insertedCodes[$parentCode];
                }

                // Parse JRA and SKKAAD
                $retensiAktif = $this->parseTahun($colF);
                $retensiInaktif = $this->parseTahun($colG);
                $nasibAkhir = $this->parseNasibAkhir($colH);

                // Update or Create
                $record = KlasifikasiArsip::updateOrCreate(
                    ['kode' => $code],
                    [
                        'nama' => $name,
                        'parent_id' => $parentId,
                        'retensi_aktif_tahun' => $retensiAktif,
                        'retensi_inaktif_tahun' => $retensiInaktif,
                        'nasib_akhir' => $nasibAkhir,
                        'keamanan' => $colI ?: null,
                        'hak_akses' => $colJ ?: null,
                        'dasar_pertimbangan' => $colK ?: null,
                        'unit_pengolah' => $colL ?: null,
                    ]
                );

                $insertedCodes[$code] = $record->id;
                $count++;
            }

            $this->command->info("✅ Seeding Klasifikasi Arsip (Excel) selesai! Total: {$count}");

        } catch (\Exception $e) {
            $this->command->error("Error seeding klasifikasi from Excel: " . $e->getMessage());
            Log::error($e);
        }
    }

    /**
     * Get parent code from current hierarchical code
     */
    private function getParentCode(string $code): ?string
    {
        $parts = explode('.', $code);
        if (count($parts) > 1) {
            array_pop($parts);
            return implode('.', $parts);
        }
        return null;
    }

    /**
     * Parse years from retensi description text (e.g. "2 Tahun, Setelah..." -> 2)
     */
    private function parseTahun(string $text): int
    {
        if (empty($text)) {
            return 0;
        }
        if (preg_match('/(\d+)\s*Tahun/i', $text, $matches)) {
            return (int) $matches[1];
        }
        return 0;
    }

    /**
     * Parse nasib akhir from text
     */
    private function parseNasibAkhir(string $text): string
    {
        $text = strtolower(trim($text));
        if (empty($text)) {
            return 'dinilai';
        }
        if (str_contains($text, 'musnah')) {
            return 'musnah';
        }
        if (str_contains($text, 'permanen')) {
            return 'permanen';
        }
        return 'dinilai';
    }
}