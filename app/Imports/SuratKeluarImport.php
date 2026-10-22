<?php

namespace App\Imports;

use App\Models\SuratKeluar;
use App\Models\KlasifikasiArsip;
use App\Models\Bidang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SuratKeluarImport implements \Maatwebsite\Excel\Concerns\ToModel, \Maatwebsite\Excel\Concerns\WithHeadingRow, \Maatwebsite\Excel\Concerns\WithValidation, \Maatwebsite\Excel\Concerns\WithBatchInserts, \Maatwebsite\Excel\Concerns\WithChunkReading, \Maatwebsite\Excel\Concerns\SkipsEmptyRows
{
    use \Maatwebsite\Excel\Concerns\Importable;

    protected ?string $defaultJenisSurat;
    protected ?int $defaultTahun;
    protected array $errors = [];
    protected int $rowCount = 0;
    protected int $successCount = 0;
    protected int $errorCount = 0;

    public function __construct(?string $defaultJenisSurat = 'SPT', ?int $defaultTahun = null)
    {
        $this->defaultJenisSurat = $defaultJenisSurat ?: 'SPT';
        $this->defaultTahun = $defaultTahun;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function limit(): int
    {
        return 10000;
    }

    public function rules(): array
    {
        return [
            'no_agenda' => 'required|string|max:50',
            'tahun' => 'required|integer|min:2020|max:2030',
            'no_surat' => 'required|string|max:100',
            'tanggal_surat' => 'required|date|before_or_equal:today',
            'tanggal_terima' => 'required|date|before_or_equal:today',
            'jenis_surat' => 'required|in:SPT,SPPD,SK,Undangan',
            'tujuan' => 'required|string|max:500',
            'perihal' => 'required|string|max:255',
            'isi_ringkas' => 'nullable|string|max:1000',
            'klasifikasi_id' => 'required|exists:klasifikasi_arsips,id',
            'bidang_id' => 'required|exists:bidangs,id',
            'penandatangan' => 'required|string|max:150',
            'keterangan' => 'nullable|string|max:1000',
            'indeks_berkas' => 'nullable|string|max:100',
            'tingkat_keamanan' => 'nullable|in:Biasa,Rahasia,Sangat Rahasia,Terbatas',
            'masa_simpan' => 'nullable|string|max:100',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'no_agenda.required' => 'Nomor agenda wajib diisi.',
            'no_agenda.unique' => 'Nomor agenda sudah digunakan untuk tahun ini.',
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.exists' => 'Tahun tidak valid atau belum terdaftar di master tahun arsip.',
            'no_surat.required' => 'Nomor surat wajib diisi.',
            'tanggal_surat.required' => 'Tanggal surat wajib diisi.',
            'tanggal_surat.before_or_equal' => 'Tanggal surat tidak boleh melebihi hari ini.',
            'tanggal_terima.required' => 'Tanggal keluar wajib diisi.',
            'tanggal_terima.before_or_equal' => 'Tanggal keluar tidak boleh melebihi hari ini.',
            'jenis_surat.required' => 'Jenis surat wajib dipilih.',
            'jenis_surat.in' => 'Jenis surat tidak valid. Pilih: SPT, SPPD, SK, atau Undangan.',
            'tujuan.required' => 'Tujuan surat wajib diisi.',
            'perihal.required' => 'Perihal wajib diisi.',
            'klasifikasi_id.required' => 'Klasifikasi arsip wajib dipilih.',
            'klasifikasi_id.exists' => 'Klasifikasi arsip tidak valid.',
            'bidang_id.required' => 'Bidang wajib dipilih.',
            'bidang_id.exists' => 'Bidang tidak valid.',
            'penandatangan.required' => 'Penandatangan wajib diisi.',
        ];
    }

    public function model(array $row)
    {
        // 1. Determine jenis_surat
        $jenisSurat = 'SPT';
        $inputJenis = $row['jenis_surat'] ?? null;
        if ($inputJenis) {
            $inputJenis = trim($inputJenis);
            $map = [
                'surat keluar'          => 'Surat Keluar',
                'sppd'                  => 'SPPD',
                'undangan keluar'       => 'Undangan Keluar',
                'sk (surat keterangan)' => 'SK (Surat Keterangan)',
                'sk'                    => 'SK (Surat Keterangan)',
                'spt'                   => 'SPT',
                'sppd'                  => 'SPPD',
                'undangan keluar'       => 'Undangan Keluar',
                'undangan'              => 'Undangan Keluar',
            ];
            $lower = strtolower($inputJenis);
            if (isset($map[strtolower($inputJenis)])) {
                $jenisSurat = $map[strtolower($inputJenis)];
            }
        }

        // 2. Parse no_agenda (Nomor Urut)
        $noAgendaRaw = $row['no_agenda'] ?? $row['nomor_urut'] ?? $row['no_urut'] ?? $row['nomor_agenda'] ?? null;
        $noAgenda = null;
        if ($noAgendaRaw !== null && $noAgendaRaw !== '') {
            $noAgenda = is_numeric($noAgendaRaw) ? (string)(int)$noAgendaRaw : trim((string)$noAgendaRaw);
        }

        // 3. Parse Dates
        $tanggalTerimaRaw = $row['tanggal_terima'] ?? $row['tanggal'] ?? $row['tgl_terima'] ?? $row['tanggal_keluar'] ?? $row['tgl_keluar'] ?? null;
        $tanggalTerima = $this->parseDate($tanggalTerimaRaw);

        $tanggalSuratRaw = $row['tanggal_surat'] ?? $row['tgl_surat'] ?? null;
        $tanggalSurat = $this->parseDate($tanggalSuratRaw);

        if ($tanggalTerima && !$tanggalSurat) {
            $tanggalSurat = $tanggalTerima;
        } elseif (!$tanggalTerima && $tanggalSurat) {
            $tanggalTerima = $tanggalSurat;
        }

        // 4. Find classification code
        $kodeRaw = $row['klasifikasi_id'] ?? $row['kode'] ?? $row['kode_klasifikasi'] ?? $row['klasifikasi'] ?? null;
        $klasifikasiId = $this->findKlasifikasiId($kodeRaw);

        // 5. Find bidang
        $bidangRaw = $row['bidang_id'] ?? $row['bidang'] ?? $row['bidang_id'] ?? null;
        $bidangId = $this->findBidangId($bidangRaw);

        // 5. Map attributes
        $tujuan = $this->getString($row, ['tujuan', 'tujuan_surat']);
        $noSurat = $this->getString($row, ['no_surat', 'nomor_surat']);
        $perihal = $this->getString($row, ['perihal', 'uraian', 'isi_ringkas']);
        $isiRingkas = $this->getString($row, ['isi_ringkas', 'isi']);
        $penandatangan = $this->getString($row, ['penandatangan', 'penandatangan']);
        $keterangan = $this->getString($row, ['keterangan', 'ket']);
        $indeksBerkas = $this->getString($row, ['indeks_berkas', 'indeks']);
        $tingkatKeamanan = $this->getString($row, ['tingkat_keamanan', 'keamanan']);
        $masaSimpan = $this->getString($row, ['masa_simpan', 'masa']);

        // Skip completely empty rows
        if (empty($row['no_agenda']) && empty($row['no_surat']) && empty($row['perihal']) && empty($row['tujuan'])) {
            return null;
        }

        // Determine year
        $year = $this->defaultTahun
            ? $this->defaultTahun
            : ($this->parseDate($row['tanggal_surat'] ?? $row['tgl_surat'] ?? null) 
                ? (int)\Carbon\Carbon::parse($this->parseDate($row['tanggal_surat'] ?? $row['tgl_surat'] ?? null))->year 
                : (int)date('Y'));

        // Auto-generate no_surat if empty
        $noSurat = $this->getString($row, ['no_surat', 'nomor_surat']);
        if (empty($noSurat) && $klasifikasiId && !empty($noAgenda)) {
            $klasifikasiObj = \App\Models\KlasifikasiArsip::find($this->findKlasifikasiId($row['klasifikasi_id'] ?? $row['kode'] ?? $row['kode_klasifikasi'] ?? $row['klasifikasi']));
            if ($klasifikasiObj) {
                $noSurat = sprintf('%s/%s/V.13/%s', trim($klasifikasiObj->kode), $noAgenda, $year);
            }
        }

        // Check duplicate no_agenda
        if ($noAgenda !== null) {
            $existing = SuratKeluar::where('jenis_surat', 'SPT')
                ->where('tahun', $year)
                ->where('no_agenda', $noAgenda)
                ->first();

            if ($existing) {
                return null; // Skip duplicate
            }
        }

        try {
            DB::beginTransaction();
            
            $suratKeluar = \App\Models\SuratKeluar::create([
                'no_agenda'       => $noAgenda,
                'tahun'           => $year,
                'jenis_surat'     => $this->getString($row, ['jenis_surat']) ?: 'SPT',
                'no_surat'        => $this->getString($row, ['no_surat', 'nomor_surat']),
                'tanggal_surat'   => $this->parseDate($row['tanggal_surat'] ?? $row['tgl_surat']),
                'tanggal_terima'  => $this->parseDate($row['tanggal_terima'] ?? $row['tanggal_terima']),
                'tujuan'          => $this->getString($row, ['tujuan', 'tujuan_surat']),
                'perihal'         => $this->getString($row, ['perihal', 'uraian']),
                'isi_ringkas'     => $this->getString($row, ['isi_ringkas', 'isi']),
                'klasifikasi_id'  => $this->findKlasifikasiId($row['klasifikasi_id'] ?? $row['kode'] ?? $row['kode_klasifikasi'] ?? $row['klasifikasi']),
                'bidang_id'       => $this->findBidangId($row['bidang_id'] ?? $row['bidang']),
                'penandatangan'   => $this->getString($row, ['penandatangan', 'penandatangan']),
                'file_surat'      => null,
                'dibuat_oleh'     => Auth::id(),
                'keterangan'      => $this->getString($row, ['keterangan', 'ket']),
                'indeks_berkas'   => $this->getString($row, ['indeks_berkas', 'indeks']),
                'tingkat_keamanan'=> $this->getString($row, ['tingkat_keamanan', 'keamanan']),
                'masa_simpan'     => $this->getString($row, ['masa_simpan', 'masa']),
                'status'          => 'draft',
            ]);

            DB::commit();
            
            return null; // Return null to prevent double save
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function onFailure(\Throwable $e): void
    {
        $this->errors[] = [
            'row' => $this->rowCount,
            'error' => $e->getMessage(),
        ];
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    public function getErrorCount(): int
    {
        return $this->errorCount;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
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

        $cleaned = str_replace(chr(194).chr(160), ' ', $cleaned);

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

        $klasifikasi = \App\Models\KlasifikasiArsip::where('kode', $kode)->first();
        if ($klasifikasi) return $klasifikasi->id;

        $parts = explode('.', $kode);
        while (count($parts) > 0) {
            array_pop($parts);
            $parentKode = implode('.', $parts);
            if (empty($parentKode)) break;
            $parent = \App\Models\KlasifikasiArsip::where('kode', $parentKode)->first();
            if ($parent) return $parent->id;
        }

        return null;
    }

    protected function findBidangId(?string $kode): ?int
    {
        if (empty($kode)) return null;
        $kode = trim($kode);

        $bidang = \App\Models\Bidang::where('kode_bidang', $kode)->first();
        if ($bidang) return $bidang->id;

        $bidang = \App\Models\Bidang::where('nama_bidang', 'like', "%{$kode}%")->first();
        if ($bidang) return $bidang->id;

        return null;
    }

    protected function getString(array $row, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (isset($row[$key]) && $row[$key] !== '') {
                return trim((string)$row[$key]);
            }
        }
        return null;
    }
}