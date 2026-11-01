<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TataNaskahController extends Controller
{
    /**
     * Katalog tata naskah publik, dikelompokkan per kategori lalu sub-kategori.
     */
    public function publicIndex(Request $request)
    {
        $searchQuery = trim((string) $request->search);

        $query = Template::query()->where('is_active', true);

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('category', 'like', "%{$searchQuery}%")
                  ->orWhere('sub_kategori', 'like', "%{$searchQuery}%");
            });
        }

        $templates = $query->orderBy('name')->get();

        $items = $templates->map(function (Template $template) {
            $kategori = $template->category;
            $subKategori = $template->sub_kategori ?: 'Lainnya';

            $filePdf = $template->serve_file_pdf;
            $fileDocx = $template->serve_file_path;
            $isDocxOnly = empty($filePdf) && !empty($fileDocx);

            $docxUrl = $fileDocx ? route('file.serve', $fileDocx) : null;

            return (object) [
                'id' => 'tmpl_' . $template->id,
                'type' => 'filesystem',
                'kategori' => $kategori,
                'sub_kategori' => $subKategori,
                'nama_naskah' => $template->name,
                'file_docx' => $fileDocx,
                'file_pdf' => $filePdf,
                'has_preview' => !empty($filePdf) || $isDocxOnly,
                // PDF dibuka langsung di modal, DOCX memakai Google Docs Viewer.
                'preview_url' => $filePdf ? route('file.serve', $filePdf) : null,
                'docx_viewer_url' => $isDocxOnly
                    ? 'https://docs.google.com/viewer?embedded=true&url=' . rawurlencode($docxUrl)
                    : null,
                'download_url' => $docxUrl,
                'preview_label' => $kategori . ' - ' . $template->name,
                'is_docx_only' => $isDocxOnly,
                'google_drive_link' => $template->google_drive_link,
            ];
        });

        $grouped = $items
            ->groupBy('kategori')
            ->sortKeysUsing(fn ($a, $b) => strnatcasecmp((string) $a, (string) $b))
            ->map(function ($group) {
                return $group
                    ->groupBy('sub_kategori')
                    ->sortKeysUsing(fn ($a, $b) => strnatcasecmp((string) $a, (string) $b))
                    ->map(function ($subGroup) {
                        return $subGroup
                            ->sortBy(fn ($item) => mb_strtolower($item->nama_naskah))
                            ->values();
                    });
            });

        $totalCount = $items->count();
        $kategoriCount = $grouped->count();
        $subKategoriCount = $grouped->sum(fn ($subs) => $subs->count());

        return view('tata-naskah.public', compact(
            'grouped',
            'totalCount',
            'kategoriCount',
            'subKategoriCount',
            'searchQuery',
        ));
    }
}
