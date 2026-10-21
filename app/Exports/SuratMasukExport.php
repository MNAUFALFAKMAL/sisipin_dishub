<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

use Maatwebsite\Excel\Concerns\WithTitle;
use App\Traits\PreventsFormulaInjection;

class SuratMasukExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle
{
    use PreventsFormulaInjection;

    protected $collection;
    protected $title;

    public function __construct($collection, $title = 'Surat Masuk')
    {
        // Sort by no_agenda descending (latest first; cast as integer then by string for sub-numbers)
        $this->collection = $collection->sortByDesc(function($item) {
            return [(int)$item->no_agenda, $item->no_agenda];
        })->values();
        $this->title = $title;
    }

    public function title(): string
    {
        return $this->title;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->collection;
    }

    public function headings(): array
    {
        return [
            'Nomor Urut',
            'Tanggal',
            'Kode',
            'Pengirim',
            'Nomor Surat',
            'Tanggal Surat',
            'Uraian',
            'Disposisi',
            'Keterangan'
        ];
    }

    public function map($surat): array
    {
        return [
            $this->safeCell($surat->no_agenda),
            $surat->tanggal_terima ? $surat->tanggal_terima->format('Y-m-d') : '-',
            $this->safeCell($surat->klasifikasi ? $surat->klasifikasi->kode : '-'),
            $this->safeCell($surat->pengirim ?? '-'),
            $this->safeCell($surat->no_surat ?? '-'),
            $surat->tanggal_surat ? $surat->tanggal_surat->format('Y-m-d') : '-',
            $this->safeCell($surat->perihal ?? '-'),
            $this->safeCell($surat->bidangTujuans->pluck('nama_bidang')->implode(', ') ?: '-'),
            $this->safeCell($surat->keterangan ?? '-'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestCol = $sheet->getHighestColumn();
        
        $range = 'A1:' . $highestCol . $highestRow;
        
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
        $sheet->getStyle('A1:' . $highestCol . '1')->applyFromArray([
            'font' => [
                'bold' => true,
            ]
        ]);

        // Specific alignment for Uraian (Column G)
        if ($highestRow > 1) {
            $sheet->getStyle('G2:G' . $highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'indent' => 3,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ]
            ]);
        }
    }
}
