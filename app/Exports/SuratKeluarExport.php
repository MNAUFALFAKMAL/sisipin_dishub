<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Traits\PreventsFormulaInjection;

class SuratKeluarExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    use PreventsFormulaInjection;

    protected $collection;
    protected $title;

    public function __construct($collection, $title = 'Surat Keluar')
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
            'No Agenda',
            'Tahun',
            'Nomor Surat',
            'Tanggal Surat',
            'Tanggal Kirim',
            'Jenis Surat',
            'Tujuan',
            'Perihal',
            'Isi Ringkas',
            'Bidang',
            'Klasifikasi',
            'Penandatangan',
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
                '800/1/V.13/2026',
                date('Y-m-d'),
                date('Y-m-d'),
                'SPT',
                'Kepala Dinas Pendidikan Provinsi Lampung',
                'Perintah Tugas Dinas Luar Kota',
                'Perintah tugas ke Jakarta untuk rapat koordinasi',
                'LLA',
                '000.1',
                'Kepala Dinas Perhubungan',
                'Draft',
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
            $this->safeCell($surat->jenis_surat ?? '-'),
            $this->safeCell($surat->tujuan ?? '-'),
            $this->safeCell($surat->perihal ?? '-'),
            $this->safeCell($surat->isi_ringkas ?? '-'),
            $this->safeCell($surat->bidang ? $surat->bidang->nama_bidang : '-'),
            $this->safeCell($surat->klasifikasi ? $surat->klasifikasi->kode : '-'),
            $this->safeCell($surat->penandatangan ?? '-'),
            $this->safeCell(ucfirst($surat->status ?? '-')),
            $surat->created_at ? $surat->created_at->format('Y-m-d H:i:s') : '-',
            $this->safeCell($surat->dibuatOleh ? $surat->dibuatOleh->name : '-'),
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
        
        // Specific alignment for Perihal (Column H)
        if ($highestRow > 1) {
            $sheet->getStyle('H2:H' . $highestRow)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'indent' => 3,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ]
            ]);
        }
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto-filter on header row
                $event->sheet->setAutoFilter('A1:O1');
                
                // Add second sheet with instructions for template
                if (str_contains($this->title, 'Template')) {
                    $spreadsheet = $event->sheet->getParent();
                    $instructionSheet = $spreadsheet->createSheet();
                    $instructionSheet->setTitle('Panduan');
                    
                    $instructions = [
                        ['PANDUAN PENGGUNAAN TEMPLATE IMPORT SURAT KELUAR'],
                        [''],
                        ['KETENTUAN UMUM:'],
                        ['1. Gunakan template ini untuk mengimpor data surat keluar.'],
                        ['2. Jangan menghapus atau mengubah header kolom (baris 1).'],
                        ['3. Isi data mulai dari baris 2.'],
                        ['4. Format tanggal: YYYY-MM-DD (contoh: 2026-01-15).'],
                        ['5. Kolom wajib diisi: No Agenda, Tahun, Nomor Surat, Tanggal Surat, Tanggal Kirim, Jenis Surat, Tujuan, Perihal, Bidang, Klasifikasi, Penandatangan.'],
                        ['6. Jenis Surat: Pilih dari SPT, SPPD, SK, Undangan.'],
                        ['7. Bidang: Isi dengan kode bidang (contoh: SEK, LLA, TSP).'],
                        ['8. Penandatangan: Nama pejabat yang menandatangani surat.'],
                        ['10. Status: Draft, Dikirim, atau Selesai.'],
                        [''],
                        ['CONTOH DATA:'],
                        ['No Agenda', 'Tahun', 'Nomor Surat', 'Tanggal Surat', 'Tanggal Kirim', 'Jenis Surat', 'Tujuan', 'Perihal', 'Isi Ringkas', 'Bidang', 'Klasifikasi', 'Penandatangan', 'Status', 'Tanggal Dibuat', 'Dicatat Oleh'],
                        ['1', '2026', '800/1/V.13/2026', '2026-01-15', '2026-01-15', 'SPT', 'Kepala Dinas Pendidikan Provinsi Lampung', 'Perintah Tugas Dinas Luar Kota', 'Perintah tugas ke Jakarta untuk rapat koordinasi', 'LLA', '000.1', 'Kepala Dinas Perhubungan', 'Draft', '', ''],
                        ['2', '2026', '800/2/V.13/2026', '2026-01-20', '2026-01-20', 'SK', 'Kepala Dinas Pendidikan Provinsi Lampung', 'Penetapan Jabatan', 'Penetapan jabatan fungsional', 'BKS', '100', 'Kepala Dinas Perhubungan', 'Selesai', '', ''],
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