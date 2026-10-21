<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use setasign\Fpdi\Tcpdf\Fpdi as TcpdfFpdi;

class PdfUtilityController extends Controller
{
    public function index()
    {
        return view('pdf-utility.index');
    }

    public function fotoKePdf()
    {
        return view('pdf-utility.foto-ke-pdf');
    }

    public function wordKePdf()
    {
        return view('pdf-utility.word-ke-pdf');
    }

    public function gabungPdf()
    {
        return view('pdf-utility.gabung-pdf');
    }

    // ── Foto ke PDF (mPDF) ─────────────────────────────────────────────────
    public function imagesToPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $request->validate([
            'images' => 'required|array|max:20',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:20480',
            'title' => 'nullable|string|max:255',
        ]);

        try {
            $images = $request->file('images');
            $title = $request->input('title') ?: 'foto_konversi_' . time();
            $safeTitle = Str::slug($title);

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_top' => 0,
                'margin_bottom' => 0,
                'margin_left' => 0,
                'margin_right' => 0,
            ]);

            foreach ($images as $image) {
                $mpdf->AddPageByArray([
                    'margin-left' => 0,
                    'margin-right' => 0,
                    'margin-top' => 0,
                    'margin-bottom' => 0,
                ]);
                list($w, $h) = getimagesize($image->getRealPath());
                $imgW = $mpdf->w - $mpdf->lMargin - $mpdf->rMargin;
                $imgH = $mpdf->h - $mpdf->tMargin - $mpdf->bMargin;
                $ratio = min($imgW / $w, $imgH / $h);
                $finalW = $w * $ratio;
                $finalH = $h * $ratio;
                $x = ($mpdf->w - $finalW) / 2;
                $y = ($mpdf->h - $finalH) / 2;
                $mpdf->Image($image->getRealPath(), $x, $y, $finalW, $finalH, 'jpg', '', true, false);
            }

            $outputDir = public_path('storage/pdf-utility');
            if (!File::exists($outputDir)) File::makeDirectory($outputDir, 0755, true);

            $outputPath = $outputDir . '/' . uniqid('img_') . '.pdf';
            $mpdf->Output($outputPath, \Mpdf\Output\Destination::FILE);

            return response()->download($outputPath, $safeTitle . '.pdf', [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $safeTitle . '.pdf"',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal konversi Foto: ' . $e->getMessage());
        }
    }

    // ── Gabung PDF (FPDI + TCPDF) ─────────────────────────────────────────
    public function mergePdfs(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $request->validate([
            'pdfs' => 'required|array|max:20',
            'pdfs.*' => 'file|mimes:pdf|max:20480',
            'title' => 'nullable|string|max:255',
        ]);

        try {
            $title = $request->input('title') ?: 'gabungan_' . time();
            $safeTitle = Str::slug($title);

            // Gunakan FPDI + TCPDF untuk merge PDF yang sudah ada
            // FPDI dirancang khusus untuk membaca & menyalin halaman PDF
            $pdf = new TcpdfFpdi();
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false);

            foreach ($request->file('pdfs') as $pdfFile) {
                $pageCount = $pdf->setSourceFile($pdfFile->getRealPath());

                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplIdx = $pdf->importPage($i);

                    // Baca ukuran asli halaman
                    $size   = $pdf->getTemplateSize($tplIdx);
                    $w      = $size['width'];
                    $h      = $size['height'];
                    $orient = ($w > $h) ? 'L' : 'P';

                    // Tambah halaman baru dengan ukuran & orientasi asli
                    $pdf->AddPage($orient, [$w, $h]);

                    // Tempel halaman PDF asli persis memenuhi halaman
                    $pdf->useTemplate($tplIdx, 0, 0, $w, $h);
                }
            }

            $outputDir = public_path('storage/pdf-utility');
            if (!File::exists($outputDir)) File::makeDirectory($outputDir, 0755, true);

            $outputPath = $outputDir . '/' . uniqid('merge_') . '.pdf';
            $pdf->Output($outputPath, 'F');

            return response()->download($outputPath, $safeTitle . '.pdf', [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $safeTitle . '.pdf"',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menggabungkan PDF: ' . $e->getMessage());
        }
    }

    // ── Word ke PDF (PHP COM → Word.Application, fallback mPDF) ──────────────
    public function wordToPdf(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        $request->validate([
            'word_file' => 'required|file|mimes:docx,doc|max:20480',
            'title' => 'nullable|string|max:255',
        ]);

        try {
            $file = $request->file('word_file');
            $title = $request->input('title') ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeTitle = Str::slug($title) ?: 'word_konversi_' . time();

            $outputDir = public_path('storage/pdf-utility');
            if (!File::exists($outputDir)) File::makeDirectory($outputDir, 0755, true);

            // Simpan file upload ke lokasi permanen sementara (COM butuh path absolut)
            $tempDocxName = uniqid('upload_') . '.' . $file->getClientOriginalExtension();
            $tempDocxPath = storage_path('app/' . $tempDocxName);
            $file->move(storage_path('app'), $tempDocxName);

            $outputPath = $outputDir . '/' . uniqid('word_') . '.pdf';

            // ── Metode 1: PowerShell COM Converter (Khusus Windows Local jika ada MS Word) ──
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $psScript = storage_path('app/convert_' . uniqid() . '.ps1');
                $srcEscaped = str_replace("'", "''", str_replace('/', '\\', realpath($tempDocxPath)));
                $dstEscaped = str_replace("'", "''", str_replace('/', '\\', $outputPath));

                $psContent = "\$src = '$srcEscaped'\n"
                    . "\$dst = '$dstEscaped'\n"
                    . "\$word = New-Object -ComObject Word.Application\n"
                    . "\$word.Visible = \$false\n"
                    . "\$word.DisplayAlerts = 0\n"
                    . "try {\n"
                    . "    \$doc = \$word.Documents.Open(\$src)\n"
                    . "    \$doc.SaveAs([ref]\$dst, [ref]17)\n"
                    . "    \$doc.Close(\$false)\n"
                    . "} finally {\n"
                    . "    \$word.Quit()\n"
                    . "}\n";
                File::put($psScript, $psContent);

                exec('powershell -NoProfile -NonInteractive -ExecutionPolicy Bypass -File "' . $psScript . '" 2>&1', $psOut, $psCode);
                @unlink($psScript);

                if (File::exists($outputPath) && File::size($outputPath) > 0) {
                    @unlink($tempDocxPath);
                    return response()->download($outputPath, $safeTitle . '.pdf', [
                        'Content-Type'        => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="' . $safeTitle . '.pdf"',
                    ])->deleteFileAfterSend(true);
                } else {
                    \Log::warning('PowerShell Word COM gagal di local. Output: ' . implode("\n", $psOut) . ' | Code: ' . $psCode);
                }
            }

            // ── Metode 2: LibreOffice CLI / soffice (Standar Server Linux/VPS/Hosting yang Sangat Rapih mendekati Google Docs) ──
            $sofficeCmd = null;
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Cek lokasi standar LibreOffice di Windows
                $possiblePaths = [
                    'C:\\Program Files\\LibreOffice\\program\\soffice.exe',
                    'C:\\Program Files (x86)\\LibreOffice\\program\\soffice.exe',
                ];
                foreach ($possiblePaths as $pPath) {
                    if (File::exists($pPath)) {
                        $sofficeCmd = '"' . $pPath . '"';
                        break;
                    }
                }
            } else {
                // Di Linux Server / VPS / Hosting
                $sofficeCmd = exec('which libreoffice || which soffice');
            }

            if ($sofficeCmd) {
                $outDirEsc = escapeshellarg($outputDir);
                $srcFileEsc = escapeshellarg(realpath($tempDocxPath));
                $cmd = "$sofficeCmd --headless --convert-to pdf --outdir $outDirEsc $srcFileEsc 2>&1";

                exec($cmd, $loOut, $loCode);

                // LibreOffice menghasilkan nama file sesuai nama file input (cth: upload_xxx.pdf)
                $generatedPdf = $outputDir . '/' . pathinfo($tempDocxPath, PATHINFO_FILENAME) . '.pdf';
                if (File::exists($generatedPdf) && File::size($generatedPdf) > 0) {
                    @unlink($tempDocxPath);
                    return response()->download($generatedPdf, $safeTitle . '.pdf', [
                        'Content-Type'        => 'application/pdf',
                        'Content-Disposition' => 'attachment; filename="' . $safeTitle . '.pdf"',
                    ])->deleteFileAfterSend(true);
                }
            }

            // ── Metode 3: Fallback mPDF ──
            $docxPath = $tempDocxPath;
            $kopSurat = $this->extractKopSurat($docxPath);

            $phpWord = \PhpOffice\PhpWord\IOFactory::load($docxPath);
            $htmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');

            $htmlPath = storage_path('app/temp_word_' . uniqid() . '.html');
            $htmlWriter->save($htmlPath);
            $htmlContent = file_get_contents($htmlPath);
            @unlink($htmlPath);

            $bodyMatch = [];
            preg_match('/<body[^>]*>(.*)<\/body>/is', $htmlContent, $bodyMatch);
            $bodyContent = $bodyMatch[1] ?? $htmlContent;

            $firstPara = $kopSurat['paragraphTexts'][0] ?? '';
            if (!empty($firstPara)) {
                $escaped = preg_quote(htmlspecialchars(mb_substr($firstPara, 0, 15)), '/');
                $bodyContent = preg_replace(
                    '/<p[^>]*>.*?' . $escaped . '.*?<\/p>\s*/s',
                    '',
                    $bodyContent
                );
            }
            $bodyContent = preg_replace('/<p[^>]*>\s*&amp;nbsp;\s*<\/p>\s*/s', '', $bodyContent);

            $styleMatch = [];
            preg_match('/<style[^>]*>(.*?)<\/style>/is', $htmlContent, $styleMatch);
            $rawStyles = $styleMatch[1] ?? '';
            $extraStyles = preg_replace('/@page\s+\w+\s*\{[^}]*\}/is', '', $rawStyles);

            $marginLeft = 30.0; $marginRight = 20.0; $marginTop = 45.0; $marginBottom = 25.0;
            if (preg_match('/@page\s+\w+\s*\{[^}]*margin-left:\s*([\d.]+)in[^}]*\}/is', $rawStyles, $m)) $marginLeft = floatval($m[1]) * 25.4;
            if (preg_match('/@page\s+\w+\s*\{[^}]*margin-right:\s*([\d.]+)in[^}]*\}/is', $rawStyles, $m)) $marginRight = floatval($m[1]) * 25.4;
            if (preg_match('/@page\s+\w+\s*\{[^}]*margin-top:\s*([\d.]+)in[^}]*\}/is', $rawStyles, $m)) $marginTop = floatval($m[1]) * 25.4;
            if (preg_match('/@page\s+\w+\s*\{[^}]*margin-bottom:\s*([\d.]+)in[^}]*\}/is', $rawStyles, $m)) $marginBottom = floatval($m[1]) * 25.4;

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => round($marginLeft),
                'margin_right' => round($marginRight),
                'margin_top' => round($marginTop),
                'margin_bottom' => round($marginBottom),
            ]);

            if (!empty($kopSurat['html'])) {
                $bodyContent = $kopSurat['html'] . $bodyContent;
            }

            $cleanHtml = '<html><head><meta charset="utf-8"><style>
                @page { margin: 15mm 15mm 15mm 15mm; }
                body { font-family: "Times New Roman", Times, Arial, sans-serif; font-size: 11pt; line-height: 1.3; color: #000; }
                p { margin: 0 0 6pt 0; padding: 0; text-align: justify; }
                h1, h2, h3, h4 { font-family: Arial, sans-serif; margin-top: 12pt; margin-bottom: 6pt; font-weight: bold; }
                h1 { font-size: 14pt; text-align: center; }
                h2 { font-size: 12pt; }
                table { border-collapse: collapse; width: 100%; margin-bottom: 10pt; page-break-inside: avoid; }
                table td, table th { border: 1px solid #000; padding: 5px 7px; vertical-align: top; font-size: 10.5pt; }
                table.kop, table.no-border, table.clean { border: none !important; }
                table.kop td, table.no-border td, table.clean td { border: none !important; padding: 2px 4px; }
                img { max-width: 100%; height: auto; display: block; margin: 0 auto; }
                ul, ol { margin-top: 0; margin-bottom: 6pt; padding-left: 20pt; }
                li { margin-bottom: 3pt; }
                .page-break { page-break-before: always; }
                ' . $extraStyles . '
            </style></head><body>' . $bodyContent . '</body></html>';

            $mpdf->WriteHTML($cleanHtml);
            $mpdf->Output($outputPath, \Mpdf\Output\Destination::FILE);

            @unlink($tempDocxPath);

            return response()->download($outputPath, $safeTitle . '.pdf', [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $safeTitle . '.pdf"',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal konversi Word: ' . $e->getMessage());
        }
    }

    // ── Ekstrak & rekonstruksi kop surat dari group shape (wpg) DOCX ─────────
    private function extractKopSurat(string $docxPath): array
    {
        $result = ['html' => '', 'bodyText' => '', 'paragraphTexts' => []];
        $zip = new \ZipArchive();
        if ($zip->open($docxPath) !== true) return $result;

        try {
            // Map rId -> image target
            $rels = $zip->getFromName('word/_rels/document.xml.rels');
            if ($rels === false) return $result;
            $imageTargets = [];
            preg_match_all('/Id="(rId\d+)"\s+Type="[^"]*image[^"]*"\s+Target="([^"]+)"/i', $rels, $matches, PREG_SET_ORDER);
            foreach ($matches as $m) {
                $imageTargets[$m[1]] = $m[2];
            }

            $documentXml = $zip->getFromName('word/document.xml');
            if ($documentXml === false) return $result;

            // Find the first group shape (wpg:wgp)
            preg_match('/<wpg:wgp>(.*?)<\/wpg:wgp>/s', $documentXml, $wgpMatch);
            if (empty($wgpMatch)) return $result;
            $wgpXml = $wgpMatch[1];

            // --- Extract image (pic:blipFill) ---
            $imgHtml = '';
            preg_match('/<a:blip[^>]*r:embed="(rId\d+)"[^>]*\/?>/i', $wgpXml, $blipMatch);
            if (!empty($blipMatch[1]) && isset($imageTargets[$blipMatch[1]])) {
                $target = $imageTargets[$blipMatch[1]];
                $mediaPath = 'word/' . $target;
                $imageData = $zip->getFromName($mediaPath);
                if ($imageData && !empty($imageData)) {
                    $ext = strtolower(pathinfo($target, PATHINFO_EXTENSION));
                    $mime = match ($ext) {
                        'png' => 'image/png',
                        'jpg', 'jpeg' => 'image/jpeg',
                        'gif' => 'image/gif',
                        'bmp' => 'image/bmp',
                        'webp' => 'image/webp',
                        'svg' => 'image/svg+xml',
                        default => 'image/png',
                    };
                    $b64 = base64_encode($imageData);
                    // Match image size from pic:spPr > a:xfrm > a:ext (the actual image, not the group shape)
                    preg_match('/<pic:spPr>.*?<a:ext cx="(\d+)" cy="(\d+)"/s', $wgpXml, $imgSize);
                    $imgW = !empty($imgSize) ? round($imgSize[1] / 914400 * 25.4, 1) : 18;
                    $imgH = !empty($imgSize) ? round($imgSize[2] / 914400 * 25.4, 1) : 18;
                    $imgHtml = '<img src="data:' . $mime . ';base64,' . $b64 . '"'
                        . ' width="' . $imgW . 'mm" height="' . $imgH . 'mm" style="display:inline-block;vertical-align:middle;" />';
                }
            }

            // --- Extract textbox (wps:txbx) paragraphs with formatting ---
            $textHtml = '';
            preg_match('/<wps:txbx>(.*?)<\/wps:txbx>/s', $wgpXml, $txbxMatch);
            if (!empty($txbxMatch[1])) {
                $txbxXml = $txbxMatch[1];
                // Extract each <w:p> paragraph
                preg_match_all('/<w:p[ >](.*?)<\/w:p>/s', $txbxXml, $pMatches);
                foreach ($pMatches[1] as $pXml) {
                    // Alignment
                    $align = 'center';
                    preg_match('/<w:jc w:val="(\w+)"/', $pXml, $jcMatch);
                    if (!empty($jcMatch[1])) $align = $jcMatch[1];

                    // Extract text runs with formatting
                    preg_match_all('/<w:r[ >](.*?)<\/w:r>/s', $pXml, $rMatches);
                    $paraHtml = '';
                    foreach ($rMatches[1] as $rXml) {
                        // Font size (in half-pts, convert to pt)
                        $sz = 11;
                        preg_match('/<w:sz w:val="(\d+)"/', $rXml, $szMatch);
                        if (!empty($szMatch[1])) $sz = intval($szMatch[1]) / 2;

                        // Bold
                        $bold = (strpos($rXml, '<w:b/>') !== false || strpos($rXml, '<w:b>') !== false);

                        // Font family
                        $font = 'Arial';
                        preg_match('/<w:rFonts[^>]*w:ascii="([^"]+)"/', $rXml, $fontMatch);
                        if (!empty($fontMatch[1])) $font = $fontMatch[1];

                        // Text content
                        preg_match('/<w:t[^>]*>(.*?)<\/w:t>/s', $rXml, $tMatch);
                        if (empty($tMatch)) continue;
                        $text = htmlspecialchars($tMatch[1]);

                        $style = 'font-family:' . $font . ';font-size:' . $sz . 'pt;' . ($bold ? 'font-weight:bold;' : '');
                        $paraHtml .= '<span style="' . $style . '">' . $text . '</span>';
                    }
                    if (!empty($paraHtml)) {
                        $textHtml .= '<p style="text-align:' . $align . ';margin:0;padding:1px 0;line-height:1.3;">' . $paraHtml . '</p>';
                    }
                }
            }

            // --- Extract horizontal line ---
            preg_match('/<a:ln[^>]*cap="flat"[^>]*cmpd="thickThin"[^>]*>/', $wgpXml, $lineMatch);
            $lineHtml = '';
            if (!empty($lineMatch)) {
                // Get line width
                preg_match('/w="(\d+)"/', $lineMatch[0], $lineW);
                $lnW = !empty($lineW) ? round($lineW[1] / 12700, 1) : 2;
                $lineHtml = '<hr style="border:none;border-top:' . max($lnW, 1) . 'pt solid #000;margin:4px 0 0 0;">';
            }

            // --- Assemble kop surat HTML ---
            // Layout: [logo + text side by side] + [line below]
            $kopHtml = '<table class="kop" style="width:100%;border-collapse:collapse;"><tr>';

            if (!empty($imgHtml)) {
                $kopHtml .= '<td style="width:' . $imgW . 'mm;vertical-align:middle;text-align:center;border:none;padding:0;">' . $imgHtml . '</td>';
                $kopHtml .= '<td style="vertical-align:middle;text-align:center;border:none;padding:0 0 0 4px;">' . $textHtml . '</td>';
            } else {
                $kopHtml .= '<td style="text-align:center;border:none;padding:0;">' . $textHtml . '</td>';
            }
            $kopHtml .= '</tr></table>' . $lineHtml;

            $result['html'] = $kopHtml;

            // --- Collect individual paragraph texts from group shape for removal ---
            $paragraphTexts = [];
            preg_match_all('/<w:p[ >](.*?)<\/w:p>/s', $txbxXml, $pMatches);
            foreach ($pMatches[1] as $pXml) {
                preg_match_all('/<w:t[^>]*>(.*?)<\/w:t>/s', $pXml, $tMatches);
                $paraText = '';
                foreach ($tMatches[1] as $t) $paraText .= trim($t);
                if (!empty($paraText)) $paragraphTexts[] = $paraText;
            }
            $result['paragraphTexts'] = $paragraphTexts;
            $result['bodyText'] = implode('', $paragraphTexts);

        } finally {
            $zip->close();
        }

        return $result;
    }
}