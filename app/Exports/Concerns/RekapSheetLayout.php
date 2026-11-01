<?php

namespace App\Exports\Concerns;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

trait RekapSheetLayout
{
    protected ?string $periodeLabel = null;

    protected ?string $reportTitle = null;

    protected function reportMode(): bool
    {
        return $this->periodeLabel !== null && $this->periodeLabel !== '';
    }

    public function startCell(): string
    {
        return $this->reportMode() ? 'A3' : 'A1';
    }

    protected function headerRow(): int
    {
        return $this->reportMode() ? 3 : 1;
    }

    /**
     * Tulis baris judul dan periode di atas header tabel.
     */
    protected function applyReportHeader(Worksheet $sheet): void
    {
        if (! $this->reportMode()) {
            return;
        }

        $lastColumn = $sheet->getHighestColumn();

        $sheet->mergeCells("A1:{$lastColumn}1");
        $sheet->setCellValue('A1', $this->reportTitle ?? 'REKAP SURAT DINAS PERHUBUNGAN PROVINSI LAMPUNG');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(24);

        $sheet->mergeCells("A2:{$lastColumn}2");
        $sheet->setCellValue('A2', 'Periode: '.$this->periodeLabel);
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(11);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    /**
     * Lebar kolom mengikuti header dan isi sel, dibatasi agar tetap terbaca.
     */
    protected function applyColumnWidths(Worksheet $sheet, array $headings): void
    {
        foreach ($headings as $index => $heading) {
            $width = mb_strlen((string) $heading);

            foreach ($this->collection as $row) {
                $values = $this->map($row);
                $width = max($width, mb_strlen((string) ($values[$index] ?? '')));
            }

            $sheet->getColumnDimensionByColumn($index + 1)
                ->setWidth(min(max($width + 2, 10), 45));
        }
    }
}
