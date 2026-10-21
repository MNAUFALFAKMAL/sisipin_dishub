<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KlasifikasiArsip;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\User;

class ArsipDishubSeeder extends Seeder
{
    protected string $excelPath = '';

    public function run(): void
    {
        $this->excelPath = database_path('seeders/Arsip DISHUB.xlsx');
        $this->command->info('📄 Membaca Arsip DISHUB.xlsx...');

        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        SuratMasuk::truncate();
        SuratKeluar::truncate();
        DB::table('surat_masuk_bidang')->truncate();
        DB::table('surat_keluar_bidang')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($this->excelPath);
        } catch (\Exception $e) {
            $this->command->error('Gagal membaca file Excel: ' . $e->getMessage());
            return;
        }

        $admin = User::where('role', 'super_admin')->first()
              ?? User::where('role', 'tata_usaha')->first()
              ?? User::first();

        if (!$admin) {
            $this->command->error('Tidak ada user admin/TU, impor dibatalkan.');
            return;
        }

        $userId = $admin->id;

        $this->importSuratMasuk($spreadsheet, $userId);
        $this->importUndanganMasuk($spreadsheet, $userId);
        $this->importSuratKeluar($spreadsheet, $userId);
        $this->importSPT($spreadsheet, $userId);
        $this->importSPPD($spreadsheet, $userId);
        $this->importSK($spreadsheet, $userId);
        $this->importUndanganKeluar($spreadsheet, $userId);

        $this->command->info('✅ Import dari Arsip DISHUB.xlsx selesai.');
    }

    protected function parseTanggal($value): ?string
    {
        if (empty($value)) return null;

        if ($value instanceof \DateTime) {
            $m = (int)$value->format('m');
            $d = (int)$value->format('d');
            if ($d >= 1 && $d <= 12 && $m >= 13 && $m <= 31) {
                return sprintf('%04d-%02d-%02d', (int)$value->format('Y'), $d, $m);
            }
            if ($m <= 12 && $d <= 31) {
                return $value->format('Y-m-d');
            }
            return null;
        }

        if (is_numeric($value)) {
            try {
                $dt = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
                $m = (int)$dt->format('m');
                $d = (int)$dt->format('d');
                if ($d >= 1 && $d <= 12 && $m >= 13 && $m <= 31) {
                    return sprintf('%04d-%02d-%02d', (int)$dt->format('Y'), $d, $m);
                }
                if ($m <= 12 && $d <= 31) {
                    return $dt->format('Y-m-d');
                }
                return null;
            } catch (\Exception $e) {
                return null;
            }
        }

        $cleaned = trim((string) $value);

        $parts = explode('/', $cleaned);
        if (count($parts) === 3) {
            $d = (int)$parts[0];
            $m = (int)$parts[1];
            $y = (int)$parts[2];
            if ($y < 100) $y += 2000;
            if ($d > 0 && $d <= 31 && $m >= 1 && $m <= 12) {
                return sprintf('%04d-%02d-%02d', $y, $m, $d);
            }
        }

        $partsDot = explode('-', $cleaned);
        if (count($partsDot) === 3) {
            $d = (int)$partsDot[0];
            $m = (int)$partsDot[1];
            $y = (int)$partsDot[2];
            if ($y < 100) $y += 2000;
            if ($d > 0 && $d <= 31 && $m >= 1 && $m <= 12) {
                return sprintf('%04d-%02d-%02d', $y, $m, $d);
            }
        }

        $ts = strtotime($cleaned);
        if ($ts !== false) {
            return date('Y-m-d', $ts);
        }

        return null;
    }

    protected function parseTanggalTerima($dateStr): ?string
    {
        if (empty($dateStr)) return null;

        $cleaned = trim((string) $dateStr);

        if (preg_match('/^(\d{1,2})\s+(\w+)\s+(\d{4})$/i', $cleaned, $m)) {
            $bulanMap = [
                'januari' => '01', 'februari' => '02', 'maret' => '03',
                'april' => '04', 'mei' => '05', 'juni' => '06',
                'juli' => '07', 'agustus' => '08', 'september' => '09',
                'oktober' => '10', 'november' => '11', 'desember' => '12',
            ];
            $bln = strtolower($m[2]);
            if (isset($bulanMap[$bln])) {
                return sprintf('%s-%s-%02d', $m[3], $bulanMap[$bln], (int)$m[1]);
            }
        }

        return $this->parseTanggal($dateStr);
    }

    protected function findKlasifikasiId(?string $kode): ?int
    {
        if (empty($kode)) return null;
        $kode = trim($kode);

        $klasifikasi = KlasifikasiArsip::where('kode', $kode)->first();
        if ($klasifikasi) return $klasifikasi->id;

        $parts = explode('.', $kode);
        while (count($parts) > 0) {
            array_pop($parts);
            $parentKode = implode('.', $parts);
            if (empty($parentKode)) break;
            $parent = KlasifikasiArsip::where('kode', $parentKode)->first();
            if ($parent) return $parent->id;
        }

        return null;
    }

    protected function generateNoAgenda(string $table, string $prefix): string
    {
        $count = DB::table($table)->where('no_agenda', 'like', $prefix . '%')->count();
        $no = $count + 1;
        return $prefix . '/' . str_pad($no, 3, '0', STR_PAD_LEFT);
    }

    protected function filterJuni(?string $tanggalSurat, ?string $tanggalTerima): bool
    {
        if (empty($tanggalSurat) && empty($tanggalTerima)) {
            return true;
        }
        $targets = [];
        if ($tanggalSurat) $targets[] = $tanggalSurat;
        if ($tanggalTerima) $targets[] = $tanggalTerima;

        foreach ($targets as $t) {
            $m = substr($t, 5, 2);
            $y = substr($t, 0, 4);
            if (($m === '06' || $m === '07') && $y === '2026') {
                return true;
            }
        }

        return false;
    }

    protected function attachBidangs($surat, ?string $disposisiText, string $relationName): void
    {
        if (empty($disposisiText)) return;

        // Split by common separators: comma, slash, semi-colon, or even spaces
        $normalized = str_replace([',', ';', '/'], ' ', strtolower($disposisiText));
        $words = preg_split('/\s+/', $normalized);

        $bidangIds = [];
        foreach ($words as $word) {
            $word = trim($word);
            if (empty($word)) continue;

            $bidang = \App\Models\Bidang::where('kode_bidang', 'like', $word)
                ->orWhere('nama_bidang', 'like', "%{$word}%")
                ->first();

            if ($bidang) {
                $bidangIds[] = $bidang->id;
            }
        }

        $bidang = \App\Models\Bidang::where('nama_bidang', 'like', "%{$disposisiText}%")->first();
        if ($bidang) {
            $bidangIds[] = $bidang->id;
        }

        $bidangIds = array_unique($bidangIds);
        if (!empty($bidangIds)) {
            $surat->$relationName()->sync($bidangIds);
        }
    }

    protected function importSuratMasuk($spreadsheet, int $userId): void
    {
        $this->command->info('📥 Surat Masuk...');

        $sheet = $spreadsheet->getSheetByName('Surat Masuk');
        if (!$sheet) {
            $this->command->warn('Sheet "Surat Masuk" tidak ditemukan.');
            return;
        }

        $rows = $sheet->toArray();
        $inserted = 0;
        $skipped = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $tanggalKolom     = $row[1] ?? null;
            $kode             = $row[2] ?? null;
            $pengirim         = $row[3] ?? null;
            $noSurat          = $row[4] ?? null;
            $tanggalSuratRaw  = $row[5] ?? null;
            $perihal          = $row[6] ?? null;
            $disposisi        = $row[7] ?? null;
            $keterangan       = $row[8] ?? null;

            $hasTanggalKolom  = !empty(trim((string)$tanggalKolom));
            $hasPengirim      = !empty(trim((string)$pengirim));
            $hasNoSurat       = !empty(trim((string)$noSurat));
            $hasPerihal       = !empty(trim((string)$perihal));
            $hasKeterangan    = !empty(trim((string)$keterangan));

            $tanggalSurat  = $this->parseTanggal($tanggalSuratRaw);
            $tanggalTerima = $this->parseTanggalTerima($tanggalKolom);

            $hasTanggal = $tanggalSurat || $tanggalTerima;
            $hasMeaningfulData = $hasPengirim || $hasNoSurat || $hasPerihal || $hasKeterangan;

            $isJuniJuli = $this->filterJuni($tanggalSurat, $tanggalTerima);

            if (!$hasMeaningfulData && !$hasTanggal) {
                continue;
            }

            if (!$isJuniJuli) {
                if ($hasTanggal) { $skipped++; }
                continue;
            }

            $klasifikasiId = $this->findKlasifikasiId($kode);
            $noAgenda = $this->generateNoAgenda('surat_masuks', 'AG-M');

            $surat = SuratMasuk::create([
                'no_agenda'         => $noAgenda,
                'klasifikasi_id'    => $klasifikasiId,
                'jenis_surat'       => 'Surat Masuk',
                'no_surat'          => $hasNoSurat ? trim($noSurat) : null,
                'tanggal_surat'     => $tanggalSurat,
                'tanggal_terima'    => $tanggalTerima,
                'pengirim'          => $hasPengirim ? trim($pengirim) : 'Tidak Diketahui',
                'perihal'           => $hasPerihal ? trim($perihal) : '-',
                'isi_ringkas'       => $hasPerihal ? trim($perihal) : ($hasKeterangan ? trim($keterangan) : '-'),
                'status_disposisi'  => 'belum',
                'catatan_disposisi' => !empty(trim((string)$disposisi)) ? trim($disposisi) : null,
                'keterangan'        => $hasKeterangan ? trim($keterangan) : null,
                'diinput_oleh'      => $userId,
            ]);

            if (!empty(trim((string)$disposisi))) {
                $this->attachBidangs($surat, $disposisi, 'bidangTujuans');
            }

            $inserted++;
        }

        $this->command->info("   ↳ {$inserted} Surat Masuk diimpor" . ($skipped > 0 ? " ({$skipped} skip)" : ""));
    }

    protected function importUndanganMasuk($spreadsheet, int $userId): void
    {
        $this->command->info('📨 Undangan Masuk...');

        $sheet = $spreadsheet->getSheetByName('Undangan Masuk');
        if (!$sheet) {
            $this->command->warn('Sheet "Undangan Masuk" tidak ditemukan.');
            return;
        }

        $rows = $sheet->toArray();
        $inserted = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $tanggalKolom     = $row[1] ?? null;
            $pengirim         = $row[3] ?? null;
            $noSurat          = $row[4] ?? null;
            $tanggalSuratRaw  = $row[5] ?? null;
            $perihal          = $row[6] ?? null;
            $disposisi        = $row[7] ?? null;
            $keterangan       = $row[8] ?? null;

            $hasTanggalKolom  = !empty(trim((string)$tanggalKolom));
            $hasPengirim      = !empty(trim((string)$pengirim));
            $hasNoSurat       = !empty(trim((string)$noSurat));
            $hasPerihal       = !empty(trim((string)$perihal));
            $hasKeterangan    = !empty(trim((string)$keterangan));

            $tanggalSurat  = $this->parseTanggal($tanggalSuratRaw);
            $tanggalTerima = $this->parseTanggalTerima($tanggalKolom);

            $hasData = $hasPengirim || $hasNoSurat || $hasPerihal || $hasKeterangan || $hasTanggalKolom;
            if (!$hasData) continue;

            if (!$this->filterJuni($tanggalSurat, $tanggalTerima)) continue;

            $noAgenda = $this->generateNoAgenda('surat_masuks', 'AG-M');

            $surat = SuratMasuk::create([
                'no_agenda'         => $noAgenda,
                'klasifikasi_id'    => null,
                'jenis_surat'       => 'Undangan Masuk',
                'no_surat'          => $hasNoSurat ? trim($noSurat) : null,
                'tanggal_surat'     => $tanggalSurat,
                'tanggal_terima'    => $tanggalTerima,
                'pengirim'          => $hasPengirim ? trim($pengirim) : 'Tidak Diketahui',
                'perihal'           => $hasPerihal ? trim($perihal) : '-',
                'isi_ringkas'       => $hasPerihal ? trim($perihal) : ($hasKeterangan ? trim($keterangan) : '-'),
                'status_disposisi'  => 'belum',
                'catatan_disposisi' => !empty(trim((string)$disposisi)) ? trim($disposisi) : null,
                'keterangan'        => $hasKeterangan ? trim($keterangan) : null,
                'diinput_oleh'      => $userId,
            ]);

            if (!empty(trim((string)$disposisi))) {
                $this->attachBidangs($surat, $disposisi, 'bidangTujuans');
            }

            $inserted++;
        }

        $this->command->info("   ↳ {$inserted} Undangan Masuk diimpor");
    }

    protected function importSuratKeluar($spreadsheet, int $userId): void
    {
        $this->command->info('📤 Surat Keluar...');

        $sheet = $spreadsheet->getSheetByName('Surat Keluar');
        if (!$sheet) {
            $this->command->warn('Sheet "Surat Keluar" tidak ditemukan.');
            return;
        }

        $rows = $sheet->toArray();
        $inserted = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $tanggalKolom     = $row[1] ?? null;
            $kode             = $row[2] ?? null;
            $tujuan           = $row[3] ?? null;
            $noSurat          = $row[4] ?? null;
            $tanggalSuratRaw  = $row[5] ?? null;
            $perihal          = $row[6] ?? null;
            $disposisi        = $row[7] ?? null;
            $keterangan       = $row[8] ?? null;

            $hasKode          = !empty(trim((string)$kode));
            $hasTujuan        = !empty(trim((string)$tujuan));
            $hasPerihal       = !empty(trim((string)$perihal));
            $hasKeterangan    = !empty(trim((string)$keterangan));
            $hasTanggalKolom  = !empty(trim((string)$tanggalKolom));

            $hasData = $hasKode || $hasTujuan || $hasPerihal || $hasKeterangan || $hasTanggalKolom;
            if (!$hasData) continue;

            $tanggalSurat  = $this->parseTanggal($tanggalSuratRaw);
            $tanggalTerima = $this->parseTanggalTerima($tanggalKolom);

            if (!$this->filterJuni($tanggalSurat, $tanggalTerima)) {
                continue;
            }

            $klasifikasiId = $this->findKlasifikasiId($kode);
            $noAgenda = $this->generateNoAgenda('surat_keluars', 'AG-K');

            $perihalFinal = $hasPerihal ? trim($perihal) : '-';
            $isiFinal = $hasPerihal ? trim($perihal) : ($hasKeterangan ? trim($keterangan) : '-');

            $surat = SuratKeluar::create([
                'no_agenda'      => $noAgenda,
                'klasifikasi_id' => $klasifikasiId,
                'jenis_surat'    => 'Surat Keluar',
                'no_surat'       => !empty(trim((string)$noSurat)) ? trim($noSurat) : null,
                'tanggal_surat'  => $tanggalSurat,
                'tanggal_terima' => $tanggalTerima,
                'tujuan'         => $hasTujuan ? trim($tujuan) : '-',
                'perihal'        => $perihalFinal,
                'isi_ringkas'    => $isiFinal,
                'keterangan'     => $hasKeterangan ? trim($keterangan) : null,
                'dibuat_oleh'    => $userId,
            ]);

            if (!empty(trim((string)$disposisi))) {
                $this->attachBidangs($surat, $disposisi, 'bidangs');
            }

            $inserted++;
        }

        $this->command->info("   ↳ {$inserted} Surat Keluar diimpor");
    }

    protected function importSPT($spreadsheet, int $userId): void
    {
        $this->command->info('📋 SPT (Surat Perintah Tugas)...');

        $sheet = $spreadsheet->getSheetByName('SPT');
        if (!$sheet) {
            $this->command->warn('Sheet "SPT" tidak ditemukan.');
            return;
        }

        $rows = $sheet->toArray();
        $inserted = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $tanggalKolom     = $row[1] ?? null;
            $kode             = $row[2] ?? null;
            $tujuan           = $row[3] ?? null;
            $perihal          = $row[6] ?? null;
            $ketCol7          = $row[7] ?? null;
            $ketCol8          = $row[8] ?? null;

            $hasKode          = !empty(trim((string)$kode));
            $hasTujuan        = !empty(trim((string)$tujuan));
            $hasPerihal       = !empty(trim((string)$perihal));
            $hasKet           = !empty(trim((string)$ketCol7)) || !empty(trim((string)$ketCol8));
            $hasTanggalKolom  = !empty(trim((string)$tanggalKolom));

            $hasData = $hasKode || $hasTujuan || $hasPerihal || $hasKet || $hasTanggalKolom;
            if (!$hasData) continue;

            $tanggalTerima = $this->parseTanggalTerima($tanggalKolom);

            if ($tanggalTerima) {
                $mg = substr($tanggalTerima, 5, 2);
                if (!in_array($mg, ['06', '07'])) continue;
            }

            $klasifikasiId = $this->findKlasifikasiId($kode);
            $noAgenda = $this->generateNoAgenda('surat_keluars', 'AG-K');

            $perihalFinal = $hasPerihal ? trim($perihal) : '-';

            $keterangan = null;
            $ketParts = [];
            if (!empty(trim((string)$ketCol7))) $ketParts[] = trim($ketCol7);
            if (!empty(trim((string)$ketCol8))) $ketParts[] = trim($ketCol8);
            if ($ketParts) $keterangan = implode(' | ', $ketParts);

            $isiFinal = $hasPerihal ? trim($perihal) : ($keterangan ? $keterangan : '-');

            $surat = SuratKeluar::create([
                'no_agenda'      => $noAgenda,
                'klasifikasi_id' => $klasifikasiId,
                'jenis_surat'    => 'SPT',
                'no_surat'       => null,
                'tanggal_surat'  => null,
                'tanggal_terima' => $tanggalTerima,
                'tujuan'         => $hasTujuan ? trim($tujuan) : '-',
                'perihal'        => $perihalFinal,
                'isi_ringkas'    => $isiFinal,
                'keterangan'     => $keterangan,
                'dibuat_oleh'    => $userId,
            ]);

            // For SPT, disposisi text might be in Col 7 or Col 8 if it represents Bidang
            $disposisi = ($row[7] ?? null) ?: ($row[8] ?? null);
            if (!empty(trim((string)$disposisi))) {
                $this->attachBidangs($surat, $disposisi, 'bidangs');
            }

            $inserted++;
        }

        $this->command->info("   ↳ {$inserted} SPT diimpor");
    }

    protected function importSPPD($spreadsheet, int $userId): void
    {
        $this->command->info('🧳 SPPD...');

        $sheet = $spreadsheet->getSheetByName('SPPD');
        if (!$sheet) {
            $this->command->warn('Sheet "SPPD" tidak ditemukan.');
            return;
        }

        $rows = $sheet->toArray();
        $inserted = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $tanggalKolom = $row[1] ?? null;
            $tujuan       = $row[3] ?? null;
            $perihal      = $row[6] ?? null;
            $disposisi    = $row[8] ?? null; // Disposisi is in Column I (index 8)
            $keterangan   = $row[7] ?? null;

            $hasTanggal = !empty(trim((string)$tanggalKolom));
            $hasTujuan   = !empty(trim((string)$tujuan));
            $hasPerihal  = !empty(trim((string)$perihal));
            $hasKet      = !empty(trim((string)$keterangan));

            $hasData = $hasTanggal || $hasTujuan || $hasPerihal || $hasKet;
            if (!$hasData) continue;

            $tanggalTerima = $this->parseTanggalTerima($tanggalKolom);

            if ($tanggalTerima) {
                $mg = substr($tanggalTerima, 5, 2);
                if (!in_array($mg, ['06', '07'])) continue;
            }

            $noAgenda = $this->generateNoAgenda('surat_keluars', 'AG-K');

            $perihalFinal = $hasPerihal ? trim($perihal) : 'Surat Perintah Perjalanan Dinas';
            $isiFinal     = $hasPerihal ? trim($perihal) : ($hasKet ? trim($keterangan) : 'SPPD Dishub Lampung');

            $surat = SuratKeluar::create([
                'no_agenda'      => $noAgenda,
                'klasifikasi_id' => null,
                'jenis_surat'    => 'SPPD',
                'no_surat'       => null,
                'tanggal_surat'  => null,
                'tanggal_terima' => $tanggalTerima,
                'tujuan'         => $hasTujuan ? trim($tujuan) : '-',
                'perihal'        => $perihalFinal,
                'isi_ringkas'    => $isiFinal,
                'keterangan'     => $hasKet ? trim($keterangan) : null,
                'dibuat_oleh'    => $userId,
            ]);

            if (!empty(trim((string)$disposisi))) {
                $this->attachBidangs($surat, $disposisi, 'bidangs');
            }

            $inserted++;
        }

        $this->command->info("   ↳ {$inserted} SPPD diimpor");
    }

    protected function importSK($spreadsheet, int $userId): void
    {
        $this->command->info('📜 SK (Surat Keterangan)...');

        $sheet = $spreadsheet->getSheetByName('SK (Surat Keterangan)');
        if (!$sheet) {
            $this->command->warn('Sheet "SK (Surat Keterangan)" tidak ditemukan.');
            return;
        }

        $rows = $sheet->toArray();
        $inserted = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $tanggalKolom = $row[1] ?? null;
            $kode         = $row[2] ?? null;
            $tujuan       = $row[3] ?? null;
            $perihal      = $row[6] ?? null;
            $keterangan   = $row[7] ?? null;
            $disposisi    = $row[8] ?? null;

            $hasTanggal    = !empty(trim((string)$tanggalKolom));
            $hasKode       = !empty(trim((string)$kode));
            $hasTujuan     = !empty(trim((string)$tujuan));
            $hasPerihal    = !empty(trim((string)$perihal));
            $hasKet        = !empty(trim((string)$keterangan));
            $hasDisposisi  = !empty(trim((string)$disposisi));

            $hasData = $hasTanggal || $hasKode || $hasTujuan || $hasPerihal || $hasKet || $hasDisposisi;
            if (!$hasData) continue;

            $tanggalTerima = $this->parseTanggalTerima($tanggalKolom);

            if ($tanggalTerima) {
                $mg = substr($tanggalTerima, 5, 2);
                if (!in_array($mg, ['06', '07'])) continue;
            }

            $klasifikasiId = $kode ? $this->findKlasifikasiId($kode) : null;
            $noAgenda = $this->generateNoAgenda('surat_keluars', 'AG-K');

            $perihalFinal = $hasPerihal ? trim($perihal) : 'Surat Keterangan';

            $isiFinal = $hasPerihal ? trim($perihal) : ($hasKet ? trim($keterangan) : 'SK Dishub Lampung');

            $surat = SuratKeluar::create([
                'no_agenda'      => $noAgenda,
                'klasifikasi_id' => $klasifikasiId,
                'jenis_surat'    => 'SK (Surat Keterangan)',
                'no_surat'       => null,
                'tanggal_surat'  => null,
                'tanggal_terima' => $tanggalTerima,
                'tujuan'         => $hasTujuan ? trim($tujuan) : '-',
                'perihal'        => $perihalFinal,
                'isi_ringkas'    => $isiFinal,
                'keterangan'     => $hasKet ? trim($keterangan) : null,
                'dibuat_oleh'    => $userId,
            ]);

            if (!empty(trim((string)$disposisi))) {
                $this->attachBidangs($surat, $disposisi, 'bidangs');
            }

            $inserted++;
        }

        $this->command->info("   ↳ {$inserted} SK diimpor");
    }

    protected function importUndanganKeluar($spreadsheet, int $userId): void
    {
        $this->command->info('📨 Undangan Keluar...');

        $sheet = $spreadsheet->getSheetByName('Undangan Keluar');
        if (!$sheet) {
            $this->command->warn('Sheet "Undangan Keluar" tidak ditemukan.');
            return;
        }

        $rows = $sheet->toArray();
        $inserted = 0;

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];

            $tanggalKolom = $row[1] ?? null;
            $kode         = $row[2] ?? null;
            $tujuan       = $row[3] ?? null;
            $perihal      = $row[6] ?? null;
            $keterangan   = $row[7] ?? null;
            $disposisi    = $row[8] ?? null; // Column I (index 8) is Disposisi

            $hasTanggal    = !empty(trim((string)$tanggalKolom));
            $hasKode       = !empty(trim((string)$kode));
            $hasTujuan     = !empty(trim((string)$tujuan));
            $hasPerihal    = !empty(trim((string)$perihal));
            $hasKet        = !empty(trim((string)$keterangan));

            $hasData = $hasTanggal || $hasKode || $hasTujuan || $hasPerihal || $hasKet;
            if (!$hasData) continue;

            $tanggalTerima = $this->parseTanggalTerima($tanggalKolom);

            if ($tanggalTerima) {
                $mg = substr($tanggalTerima, 5, 2);
                if (!in_array($mg, ['06', '07'])) continue;
            }

            $klasifikasiId = $kode ? $this->findKlasifikasiId($kode) : null;
            $noAgenda = $this->generateNoAgenda('surat_keluars', 'AG-K');

            $perihalFinal = $hasPerihal ? trim($perihal) : 'Undangan Kegiatan';
            $isiFinal     = $hasPerihal ? trim($perihal) : ($hasKet ? trim($keterangan) : 'Undangan Keluar Dishub Lampung');

            $surat = SuratKeluar::create([
                'no_agenda'      => $noAgenda,
                'klasifikasi_id' => $klasifikasiId,
                'jenis_surat'    => 'Undangan Keluar',
                'no_surat'       => null,
                'tanggal_surat'  => null,
                'tanggal_terima' => $tanggalTerima,
                'tujuan'         => $hasTujuan ? trim($tujuan) : '-',
                'perihal'        => $perihalFinal,
                'isi_ringkas'    => $isiFinal,
                'keterangan'     => $hasKet ? trim($keterangan) : null,
                'dibuat_oleh'    => $userId,
            ]);

            if (!empty(trim((string)$disposisi))) {
                $this->attachBidangs($surat, $disposisi, 'bidangs');
            }

            $inserted++;
        }

        $this->command->info("   ↳ {$inserted} Undangan Keluar diimpor");
    }
}