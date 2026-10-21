<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Http\Requests\TemplateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $templates = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('templates.index', compact('templates'));
    }

    public function create()
    {
        $categories = $this->getCategories();
        return view('templates.create', compact('categories'));
    }

    public function store(TemplateRequest $request)
    {
        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.docx';

        // Simpan ke storage/app/public/templates (agar bisa diakses via serve-file)
        $destinationPath = storage_path('app/public/templates');
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $safeName);
        $filePath = 'storage/templates/' . $safeName;

        // Handle PDF (optional)
        $pdfPath = null;
        if ($request->hasFile('file_pdf')) {
            $pdf = $request->file('file_pdf');
            $pdfSafeName = Str::slug(pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.pdf';
            $pdf->move($destinationPath, $pdfSafeName);
            $pdfPath = 'storage/templates/' . $pdfSafeName;
        }

        $template = Template::create([
            'name' => $request->name,
            'category' => $request->category,
            'sub_kategori' => $request->sub_kategori,
            'file_path' => $filePath,
            'file_pdf' => $pdfPath,
            'placeholders' => null,
            'is_active' => true,
            'google_drive_link' => $request->google_drive_link,
        ]);

        return redirect()->route('templates.edit', $template->id)
            ->with('success', 'Template berhasil diunggah.');
    }

    public function show($id)
    {
        $template = Template::findOrFail($id);
        return view('templates.show', compact('template'));
    }

    public function edit($id)
    {
        $template = Template::findOrFail($id);
        $categories = $this->getCategories();
        return view('templates.edit', compact('template', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $template = Template::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'sub_kategori' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'file' => 'nullable|file|mimes:docx|max:10240',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
            'google_drive_link' => 'nullable|string|max:500',
        ]);

        $template->update([
            'name' => $request->name,
            'category' => $request->category,
            'sub_kategori' => $request->sub_kategori,
            'is_active' => $request->boolean('is_active'),
            'google_drive_link' => $request->google_drive_link,
        ]);

        $destinationPath = storage_path('app/public/templates');
        
        if ($request->hasFile('file')) {
            if ($template->file_path) {
                $oldPath = public_path($template->file_path);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            
            $file = $request->file('file');
            $safeName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.docx';
            $file->move($destinationPath, $safeName);
            $template->file_path = 'storage/templates/' . $safeName;
        }
        
        if ($request->hasFile('file_pdf')) {
            if ($template->file_pdf) {
                $oldPdfPath = public_path($template->file_pdf);
                if (File::exists($oldPdfPath)) {
                    File::delete($oldPdfPath);
                }
            }
            
            $pdf = $request->file('file_pdf');
            $pdfSafeName = Str::slug(pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.pdf';
            $pdf->move($destinationPath, $pdfSafeName);
            $template->file_pdf = 'storage/templates/' . $pdfSafeName;
        }

        $template->save();

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil diperbarui.');
    }

    public function removeFile($id)
    {
        $template = Template::findOrFail($id);
        if ($template->file_path) {
            $fullPath = public_path($template->file_path);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
            $storagePath = storage_path('app/public/' . str_replace('storage/', '', $template->file_path));
            if (File::exists($storagePath)) {
                File::delete($storagePath);
            }
            $template->file_path = '';
            $template->placeholders = null;
            $template->save();
        }
        return redirect()->route('templates.edit', $id)
            ->with('success', 'File .docx berhasil dihapus.');
    }

    public function removeFilePdf($id)
    {
        $template = Template::findOrFail($id);
        if ($template->file_pdf) {
            $fullPath = public_path($template->file_pdf);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
            $storagePath = storage_path('app/public/' . str_replace('storage/', '', $template->file_pdf));
            if (File::exists($storagePath)) {
                File::delete($storagePath);
            }
            $template->file_pdf = null;
            $template->save();
        }
        return redirect()->route('templates.edit', $id)
            ->with('success', 'File PDF pratinjau berhasil dihapus.');
    }

    public function destroy($id)
    {
        $template = Template::findOrFail($id);

        // Hapus file
        if ($template->file_path) {
            $fullPath = public_path($template->file_path);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
            $storagePath = storage_path('app/public/' . str_replace('storage/', '', $template->file_path));
            if (File::exists($storagePath)) {
                File::delete($storagePath);
            }
        }

        if ($template->file_pdf) {
            $fullPdfPath = public_path($template->file_pdf);
            if (File::exists($fullPdfPath)) {
                File::delete($fullPdfPath);
            }
            $storagePdfPath = storage_path('app/public/' . str_replace('storage/', '', $template->file_pdf));
            if (File::exists($storagePdfPath)) {
                File::delete($storagePdfPath);
            }
        }

        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }

    private function getCategories(): array
    {
        return [
            'A. Naskah Dinas Penugasan',
            'B. Naskah Dinas Korespondensi Internal',
            'C. Naskah Dinas Korespodensi Eksternal',
            'D. Naskah Dinas Khusus',
            'E. Lainnya',
        ];
    }
}