<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TataNaskahController extends Controller
{
    public function publicIndex(Request $request)
    {
        $searchQuery = $request->search ?? '';

        $query = Template::query()->where('is_active', true);

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('category', 'like', "%{$searchQuery}%")
                  ->orWhere('sub_kategori', 'like', "%{$searchQuery}%");
            });
        }

        $templates = $query->orderBy('category')->orderBy('sub_kategori')->get();

        $items = $templates->map(function (Template $template) {
            $kategori = $template->category;
            $subKategori = $template->sub_kategori;

            $hasPdf = !empty($template->serve_file_pdf);
            $filePdf = $template->serve_file_pdf;
            $fileDocx = $template->serve_file_path;
            $isDocxOnly = !$hasPdf && !empty($fileDocx);

            return (object) [
                'id' => 'tmpl_' . $template->id,
                'type' => 'filesystem',
                'kategori' => $kategori,
                'sub_kategori' => $subKategori,
                'nama_naskah' => $template->name,
                'definisi' => '',
                'file_docx' => $fileDocx,
                'file_pdf' => $filePdf,
                'has_preview' => $hasPdf,
                'preview_url' => $hasPdf ? route('file.serve', $filePdf) : null,
                'preview_label' => $kategori . ($subKategori ? ' → ' . $subKategori : ''),
                'is_docx_only' => $isDocxOnly,
                'docx_only_url' => $isDocxOnly ? route('file.serve', $fileDocx) : null,
                'placeholders' => [],
                'google_drive_link' => $template->google_drive_link,
            ];
        });

        $grouped = $items
            ->groupBy('kategori')
            ->sortKeys()
            ->map(function ($group) {
                return $group->sort(function ($a, $b) {
                    return strnatcmp((string) $a->sub_kategori, (string) $b->sub_kategori);
                })->values();
            });

        return view('tata-naskah.public', compact('grouped'));
    }
}
