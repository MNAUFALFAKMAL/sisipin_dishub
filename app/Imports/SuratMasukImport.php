<?php

namespace App\Imports;

use App\Models\SuratMasuk;
use App\Models\KlasifikasiArsip;
use App\Models\Bidang;
use App\Services\SuratService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;

class SuratMasukImport implements ToModel, WithHeadingRow
{
    protected ?string $defaultJenisSurat;
    protected ?int $defaultTahun;

    public function __construct(?string $defaultJenisSurat = 'Surat Masuk', ?int $defaultTahun = null)
    {
        $this->defaultJenisSurat = $defaultJenisSurat ?: 'Surat Masuk';
        $this->defaultTahun = $defaultTahun;
    }

    public function model(array $row)
    {
        // 1. Determine jenis_surat
        $jenisSurat = $this->defaultJenisSurat;
        $inputJenis = $row['jenis_surat'] ?? null;
        if ($inputJenis) {
            $inputJenis = trim($inputJenis);
            if (in_array(strtolower($inputJenis), ['undangan masuk', 'undangan'])) {
                $jenisSurat = 'Undangan Masuk';
            } elseif (in_array(strtolower($inputJenis), ['surat masuk'])) {
                $jenisSurat = 'Surat Masuk';
            }
        }

        // 2. Parse no_agenda (Nomor Urut)
        $noAgendaRaw = $row['nomor_urut'] ?? $row['no_urut'] ?? $row['no_agenda'] ?? $row['nomor_agenda'] ?? null;
        $noAgenda = null;
        if ($noAgendaRaw !== null && $noAgendaRaw !== '') {
            $noAgenda = is_numeric($noAgendaRaw) ? (string)(int)$noAgendaRaw : trim((string)$noAgendaRaw);
        }

        // 3. Parse Dates
        $tanggalTerimaRaw = $row['tanggal'] ?? $row['tanggal_terima'] ?? $row['tgl_terima'] ?? $row['tanggal_masuk'] ?? $row['tgl_masuk'] ?? null;
        $tanggalTerima = $this->parseDate($tanggalTerimaRaw);

        $tanggalSuratRaw = $row['tanggal_surat'] ?? $row['tgl_surat'] ?? null;
        $tanggalSurat = $this->parseDate($tanggalSuratRaw);

        if ($tanggalTerima && !$tanggalSurat) {
            $tanggalSurat = $tanggalTerima;
        } elseif (!$tanggalTerima && $tanggalSurat) {
            $tanggalTerima = $tanggalSurat;
        }

        // 4. Find classification code
        $kodeRaw = $row['kode'] ?? $row['kode_klasifikasi'] ?? $row['klasifikasi'] ?? null;
        $klasifikasiId = $this->findKlasifikasiId($kodeRaw);

        // 5. Map attributes — JANGAN paksa fallback jika kosong, ikuti data apa adanya
        $pengirim    = isset($row['pengirim']) && $row['pengirim'] !== '' ? trim((string)$row['pengirim'])
                     : (isset($row['asal_surat']) && $row['asal_surat'] !== '' ? trim((string)$row['asal_surat']) : null);
        $noSurat     = isset($row['nomor_surat']) && $row['nomor_surat'] !== '' ? trim((string)$row['nomor_surat'])
                     : (isset($row['no_surat']) && $row['no_surat'] !== '' ? trim((string)$row['no_surat']) : null);
        $perihal     = isset($row['uraian']) && $row['uraian'] !== '' ? trim((string)$row['uraian'])
                     : (isset($row['perihal']) && $row['perihal'] !== '' ? trim((string)$row['perihal'])
                     : (isset($row['isi_ringkas']) && $row['isi_ringkas'] !== '' ? trim((string)$row['isi_ringkas']) : null));
        $keterangan  = isset($row['keterangan']) && $row['keterangan'] !== '' ? trim((string)$row['keterangan'])
                     : (isset($row['ket']) && $row['ket'] !== '' ? trim((string)$row['ket']) : null);

        // Skip completely empty rows (tidak ada satu pun identifier bermakna)
        if (empty($noAgenda) && empty($noSurat) && empty($perihal) && empty($pengirim)) {
            return null;
        }

        // 6. Tentukan tahun untuk scoping (gunakan tahun dari parameter, atau tanggal terima, atau tahun sekarang)
        $year = $this->defaultTahun
            ? $this->defaultTahun
            : ($tanggalTerima ? (int)\Carbon\Carbon::parse($tanggalTerima)->year : (int)date('Y'));

        // 7. Gap-fill placeholder (sama persis dengan logika store() di controller)
        if (is_numeric($noAgenda)) {
            $targetInt = (int)$noAgenda;
            $cacheKey = $jenisSurat . '-' . $year;
            
            static $maxGapFilled = [];
            if (!isset($maxGapFilled[$cacheKey])) {
                $maxGapFilled[$cacheKey] = 0;
            }

            if ($targetInt > $maxGapFilled[$cacheKey]) {
                // Cari semua agenda yang sudah ada di database untuk tahun dan jenis ini
                $existingAgendas = SuratMasuk::where('jenis_surat', $jenisSurat)
                    ->where('tahun', $year)
                    ->pluck('no_agenda')
                    ->filter(function($val) {
                        return is_numeric($val);
                    })
                    ->map(function($val) {
                        return (int)$val;
                    })
                    ->toArray();

                $existingMap = array_flip($existingAgendas);

                $placeholdersToInsert = [];
                for ($i = 1; $i < $targetInt; $i++) {
                    if (!isset($existingMap[$i])) {
                        $placeholdersToInsert[] = [
                            'no_agenda'        => (string)$i,
                            'jenis_surat'      => $jenisSurat,
                            'tahun'            => $year,
                            'no_surat'         => null,
                            'tanggal_surat'    => null,
                            'tanggal_terima'   => null,
                            'pengirim'         => null,
                            'perihal'          => null,
                            'isi_ringkas'      => null,
                            'status_disposisi' => 'belum',
                            'keterangan'       => null,
                            'diinput_oleh'     => Auth::id(),
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ];
                        // prevent duplicates in the same insert array just in case
                        $existingMap[$i] = true; 
                    }
                }
                
                if (!empty($placeholdersToInsert)) {
                    foreach (array_chunk($placeholdersToInsert, 500) as $chunk) {
                        SuratMasuk::insert($chunk);
                    }
                }

                $maxGapFilled[$cacheKey] = $targetInt;
            }
        }

        // 8. Cek apakah no_agenda sudah ada (duplikasi)
        $existing = null;
        $isPlaceholder = false;
        if ($noAgenda !== null) {
            $existing = SuratMasuk::where('jenis_surat', $jenisSurat)
                ->where('tahun', $year)
                ->where('no_agenda', $noAgenda)
                ->first();

            $isPlaceholder = $existing && SuratService::isPlaceholder($existing);

            // Jika sudah ada data aktif, skip baris ini (jangan overwrite)
            if ($existing && !$isPlaceholder) {
                return null;
            }
        }

        // 9. Simpan data ke database
        $payload = [
            'no_agenda'        => $noAgenda,
            'klasifikasi_id'   => $klasifikasiId,
            'jenis_surat'      => $jenisSurat,
            'tahun'            => $year,
            'no_surat'         => $noSurat,
            'tanggal_surat'    => $tanggalSurat,
            'tanggal_terima'   => $tanggalTerima,
            'pengirim'         => $pengirim,
            'perihal'          => $perihal,
            'isi_ringkas'      => $perihal,
            'status_disposisi' => 'belum',
            'keterangan'       => $keterangan,
            'diinput_oleh'     => Auth::id(),
        ];

        if ($existing && $isPlaceholder) {
            // Update placeholder dengan data riil
            $existing->update($payload);
            $surat = $existing;
        } else {
            $surat = SuratMasuk::create($payload);
        }

        // 10. Attach Bidang (Disposisi)
        $disposisiRaw = $row['disposisi'] ?? $row['bidang'] ?? null;
        if ($disposisiRaw !== null && $disposisiRaw !== '') {
            $this->attachBidangs($surat, (string)$disposisiRaw);
        }

        return null; // Return null agar Laravel Excel tidak double-save
    }

    protected function parseDate($value): ?string
    {
        if (empty($value)) return null;

        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }

        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        $cleaned = trim((string) $value);
        if (empty($cleaned) || $cleaned === '-') return null;

        // Clean common unicode spacing or non-breaking spaces
        $cleaned = str_replace(chr(194).chr(160), ' ', $cleaned);

        // Try parsing Indonesian Month names, e.g. "1 Juli 2026"
        if (preg_match('/^(\d{1,2})\s+(\w+)\s+(\d{4})$/i', $cleaned, $m)) {
            $bulanMap = [
                'januari' => '01', 'februari' => '02', 'maret' => '03', 'mrt' => '03',
                'april' => '04', 'mei' => '05', 'juni' => '06', 'jun' => '06',
                'juli' => '07', 'jul' => '07', 'agustus' => '08', 'ags' => '08',
                'september' => '09', 'sep' => '09', 'oktober' => '10', 'okt' => '10',
                'november' => '11', 'desember' => '12', 'des' => '12',
            ];
            $bln = strtolower($m[2]);
            if (isset($bulanMap[$bln])) {
                return sprintf('%04d-%02d-%02d', (int)$m[3], $bulanMap[$bln], (int)$m[1]);
            }
        }

        // Try standard dd/mm/yyyy or dd-mm-yyyy format
        $normalized = str_replace('/', '-', $cleaned);
        $parts = explode('-', $normalized);
        if (count($parts) === 3) {
            $d = (int)$parts[0];
            $m = (int)$parts[1];
            $y = (int)$parts[2];
            if ($y < 100) $y += 2000;
            if ($d > 0 && $d <= 31 && $m >= 1 && $m <= 12) {
                return sprintf('%04d-%02d-%02d', $y, $m, $d);
            }
        }

        $ts = strtotime($normalized);
        if ($ts !== false) {
            return date('Y-m-d', $ts);
        }

        return null;
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

    protected function attachBidangs($surat, ?string $disposisiText): void
    {
        if (empty($disposisiText)) return;

        $normalized = str_replace([',', ';', '/'], ' ', strtolower($disposisiText));
        $words = preg_split('/\s+/', $normalized);

        $bidangIds = [];
        foreach ($words as $word) {
            $word = trim($word);
            if (empty($word)) continue;

            $bidang = Bidang::where('kode_bidang', 'like', $word)
                ->orWhere('nama_bidang', 'like', "%{$word}%")
                ->first();

            if ($bidang) {
                $bidangIds[] = $bidang->id;
            }
        }

        $bidang = Bidang::where('nama_bidang', 'like', "%{$disposisiText}%")->first();
        if ($bidang) {
            $bidangIds[] = $bidang->id;
        }

        $bidangIds = array_unique($bidangIds);
        if (!empty($bidangIds)) {
            $surat->bidangTujuans()->sync($bidangIds);
        }
    }
}
