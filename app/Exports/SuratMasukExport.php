<?php

namespace App\Exports;

use App\Exports\Concerns\RekapSheetLayout;
use App\Traits\PreventsFormulaInjection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SuratMasukExport implements FromCollection, WithCustomStartCell, WithEvents, WithHeadings, WithMapping, WithStyles, WithTitle
{
    use PreventsFormulaInjection;
    use RekapSheetLayout;

    protected $collection;

    protected $title;

    public function __construct($collection, $title = 'Surat Masuk', ?string $periodeLabel = null, ?string $reportTitle = null)
    {
        // Sort by no_agenda descending (latest first; cast as integer then by string for sub-numbers)
        $this->collection = $collection->sortByDesc(function ($item) {
            return [(int) $item->no_agenda, $item->no_agenda];
        })->values();
        $this->title = $title;
        $this->periodeLabel = $periodeLabel;
        $this->reportTitle = $reportTitle;
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return [
            'No Agenda',
            'Tahun',
            'Nomor Surat',
            'Tanggal Surat',
            'Tanggal Terima',
            'Pengirim/Asal Surat',
            'Perihal',
            'Isi Ringkas',
            'Bidang',
            'Klasifikasi',
            'Status',
            'Tanggal Dibuat',
            'Dicatat Oleh',
        ];
    }

    public function map($surat): array
    {
        // Template mode: return example data
        if (str_contains($this->title, 'Template')) {
            return [
                '1',
                date('Y'),
                '001/SM/DISHUB/2026',
                date('Y-m-d'),
                date('Y-m-d'),
                'Instansi Pemerintah Provinsi Lampung',
                'Undangan Rapat Koordinasi',
                'Rapat koordinasi bulan Januari 2026',
                'Sekretariat',
                '000.1',
                'Diterima',
                now()->format('Y-m-d H:i:s'),
                'Admin',
            ];
        }

        return [
            $this->safeCell($surat->no_agenda),
            $surat->tahun,
            $this->safeCell($surat->no_surat ?? '-'),
            $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : '-',
            $surat->tanggal_terima ? $surat->tanggal_terima->format('Y-m-d') : '-',
            $this->safeCell($surat->pengirim ?? '-'),
            $this->safeCell($surat->perihal ?? '-'),
            $this->safeCell($surat->isi_ringkas ?? '-'),
            $this->safeCell($surat->bidangTujuans->pluck('nama_bidang')->implode(', ') ?: '-'),
            $this->safeCell($surat->klasifikasi ? $surat->klasifikasi->kode : '-'),
            $this->safeCell(ucfirst($surat->status_disposisi ?? '-')),
            $surat->created_at ? $surat->created_at->format('Y-m-d H:i:s') : '-',
            $this->safeCell($surat->diinputOleh ? $surat->diinputOleh->name : '-'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $headerRow = $this->headerRow();
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();

        $range = 'A'.$headerRow.':'.$highestCol.$highestRow;

        $sheet->getStyle($range)->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Header bold
        $sheet->getStyle('A'.$headerRow.':'.$highestCol.$headerRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);

        // Specific alignment for Perihal (Column G)
        if ($highestRow > $headerRow) {
            $sheet->getStyle('G'.($headerRow + 1).':G'.$highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'indent' => 3,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]);
        }
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $headerRow = $this->headerRow();

                $this->applyReportHeader($sheet);
                $this->applyColumnWidths($sheet, $this->headings());

                // Auto-filter on header row
                $sheet->setAutoFilter('A'.$headerRow.':M'.$headerRow);

                // Add second sheet with instructions for template
                if (str_contains($this->title, 'Template')) {
                    $spreadsheet = $event->sheet->getParent();
                    $instructionSheet = $spreadsheet->createSheet();
                    $instructionSheet->setTitle('Panduan');

                    $instructions = [
                        ['PANDUAN PENGGUNAAN TEMPLATE IMPORT SURAT MASUK'],
                        [''],
                        ['KETENTUAN UMUM:'],
                        ['1. Gunakan template ini untuk mengimpor data surat masuk.'],
                        ['2. Jangan menghapus atau mengubah header kolom (baris 1).'],
                        ['3. Isi data mulai dari baris 2.'],
                        ['4. Format tanggal: YYYY-MM-DD (contoh: 2026-01-15).'],
                        ['5. Kolom wajib diisi: No Agenda, Tahun, Nomor Surat, Tanggal Surat, Tanggal Terima, Pengirim/Asal Surat, Perihal, Bidang, Klasifikasi.'],
                        ['6. Bidang: Isi dengan kode bidang (contoh: SEK, LLA, TSP).'],
                        ['10. Isi Ringkas: Opsional, ringkasan isi surat.'],
                        ['11. Tanggal Dibuat dan Dicatat Oleh akan diisi otomatis saat import.'],
                        [''],
                        ['CONTOH DATA:'],
                        ['No Agenda', 'Tahun', 'Nomor Surat', 'Tanggal Surat', 'Tanggal Terima', 'Pengirim/Asal Surat', 'Perihal', 'Isi Ringkas', 'Bidang', 'Klasifikasi', 'Status', 'Tanggal Dibuat', 'Dicatat Oleh'],
                        ['1', '2026', '001/SM/DISHUB/2026', '2026-01-15', '2026-01-16', 'Instansi Pemerintah Provinsi Lampung', 'Undangan Rapat Koordinasi', 'Rapat koordinasi bulan Januari', 'Sekretariat', '000.1', 'Diterima', '', ''],
                        ['2', '2026', '002/SM/DISHUB/2026', '2026-01-20', '2026-01-21', 'Dinas Pendidikan Provinsi Lampung', 'Permohonan Data', 'Permohonan data statistik pendidikan', 'LLA', '100', 'Diterima', '', ''],
                    ];

                    foreach ($instructions as $rowIndex => $row) {
                        foreach ($row as $colIndex => $cell) {
                            $instructionSheet->setCellValueByColumnAndRow($colIndex + 1, $rowIndex + 1, $cell);
                        }
                    }

                    // Style the instruction sheet
                    $instructionSheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                    $instructionSheet->getStyle('A3')->getFont()->setBold(true);
                    $instructionSheet->getStyle('A13')->getFont()->setBold(true);
                }
            },
        ];
    }
}
