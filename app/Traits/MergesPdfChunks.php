<?php

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Tcpdf\Fpdi as TcpdfFpdi;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait MergesPdfChunks
{
    protected function downloadMergedPdf(string $filename, array $htmlSegments): BinaryFileResponse
    {
        $tmpFiles = [];

        try {
            foreach ($htmlSegments as $html) {
                $tmp = tempnam(sys_get_temp_dir(), 'pdf_chunk_') . '.pdf';
                $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');
                file_put_contents($tmp, $pdf->output());
                $tmpFiles[] = $tmp;
                unset($pdf, $html);
            }

            $merger = new TcpdfFpdi();
            $merger->setPrintHeader(false);
            $merger->setPrintFooter(false);

            foreach ($tmpFiles as $file) {
                $pageCount = $merger->setSourceFile($file);
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplIdx = $merger->importPage($i);
                    $size = $merger->getTemplateSize($tplIdx);
                    $w = $size['width'];
                    $h = $size['height'];
                    $orient = ($w > $h) ? 'L' : 'P';
                    $merger->AddPage($orient, [$w, $h]);
                    $merger->useTemplate($tplIdx, 0, 0, $w, $h);
                }
            }

            $outPath = tempnam(sys_get_temp_dir(), 'merged_pdf_') . '.pdf';
            $merger->Output($outPath, 'F');

            return response()
                ->download($outPath, $filename)
                ->deleteFileAfterSend(true);
        } finally {
            foreach ($tmpFiles as $file) {
                @unlink($file);
            }
        }
    }
}