<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Tcpdf\Fpdi as TcpdfFpdi;

class FileConverterService
{
    protected int $maxWidth = 4000;

    protected int $jpegQuality = 92;

    public function convertToPdf(UploadedFile $file, string $storageDir): string
    {
        $ext = strtolower($file->getClientOriginalExtension());

        if ($ext === 'pdf') {
            return $file->store($storageDir, 'public');
        }

        return $this->mergeToPdf([$file], $storageDir);
    }

    public function mergeToPdf(array $files, string $storageDir): string
    {
        $pdf = new TcpdfFpdi;
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetAutoPageBreak(false);

        foreach ($files as $file) {
            $ext = strtolower($file->getClientOriginalExtension());

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $img = $this->loadImage($file);
                if (! $img) {
                    continue;
                }
                $img = $this->resizeImage($img);
                $tempJpg = tempnam(sys_get_temp_dir(), 'merge_').'.jpg';
                imagejpeg($img, $tempJpg, $this->jpegQuality);
                imagedestroy($img);

                [$w, $h] = getimagesize($tempJpg);
                $orient = ($w > $h) ? 'L' : 'P';

                $pdf->AddPage($orient, 'A4');
                // Fit to A4 page
                $pageWidth = $pdf->getPageWidth();
                $pageHeight = $pdf->getPageHeight();
                $pdf->Image($tempJpg, 0, 0, $pageWidth, $pageHeight, '', '', '', false, 300, '', false, false, 0);

                @unlink($tempJpg);
            } elseif ($ext === 'pdf') {
                try {
                    $pdfPath = $file->getRealPath();

                    // Normalisasi PDF dengan Ghostscript jika tersedia
                    $normalizedPath = $this->normalizePdfWithGhostscript($pdfPath);

                    $pageCount = $pdf->setSourceFile($normalizedPath);

                    for ($i = 1; $i <= $pageCount; $i++) {
                        $tplIdx = $pdf->importPage($i);
                        $size = $pdf->getTemplateSize($tplIdx);
                        $w = $size['width'];
                        $h = $size['height'];
                        $orient = ($w > $h) ? 'L' : 'P';

                        $pdf->AddPage($orient, [$w, $h]);
                        $pdf->useTemplate($tplIdx, 0, 0, $w, $h);
                    }

                    // Hapus file temporari jika dibuat
                    if ($normalizedPath !== $pdfPath && file_exists($normalizedPath)) {
                        @unlink($normalizedPath);
                    }
                } catch (\Exception $e) {
                    \Log::error('PDF Conversion Error: '.$e->getMessage());
                    throw new \RuntimeException('Gagal memproses file PDF: '.$e->getMessage().'. Coba simpan ulang file PDF Anda dengan "Print to PDF" dari browser atau PDF reader sebelum mengunggah kembali.');
                }
            }
        }

        $filename = $storageDir.'/'.md5(uniqid('', true)).'.pdf';

        // Tulis lewat Storage agar konsisten dengan convertToPdf() dan bisa di-fake saat test
        Storage::disk('public')->put($filename, $pdf->Output('', 'S'));

        return $filename;
    }

    protected function loadImage(UploadedFile $file): ?object
    {
        $path = $file->getRealPath();
        $ext = strtolower($file->getClientOriginalExtension());

        return match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => @imagecreatefromwebp($path),
            'gif' => @imagecreatefromgif($path),
            default => null,
        };
    }

    protected function resizeImage(object $img): object
    {
        $origW = imagesx($img);
        $origH = imagesy($img);

        if ($origW <= $this->maxWidth) {
            return $img;
        }

        $ratio = $this->maxWidth / $origW;
        $newW = (int) round($origW * $ratio);
        $newH = (int) round($origH * $ratio);

        $resized = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        return $resized;
    }

    protected function normalizePdfWithGhostscript(string $pdfPath): string
    {
        $normalizedPath = $pdfPath.'_norm.pdf';
        $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile='.$normalizedPath.' '.escapeshellarg($pdfPath);

        $output = [];
        $returnVar = 0;
        exec($cmd, $output, $returnVar);

        if ($returnVar === 0 && file_exists($normalizedPath)) {
            return $normalizedPath;
        }

        // Fallback: jika Ghoscript gagal, kembalikan file asli
        return $pdfPath;
    }
}
