<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
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
        $filePath = $this->storeUploadedFile($request->file('file'), 'template');
        $pdfPath = $request->hasFile('file_pdf')
            ? $this->storeUploadedFile($request->file('file_pdf'), 'pratinjau')
            : null;

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
            'file' => 'nullable|file|mimes:docx,pdf|max:10240',
            'file_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'google_drive_link' => 'nullable|string|max:500',
        ]);

        $template->update([
            'name' => $request->name,
            'category' => $request->category,
            'sub_kategori' => $request->sub_kategori,
            'is_active' => $request->boolean('is_active'),
            'google_drive_link' => $request->google_drive_link,
        ]);

        if ($request->hasFile('file')) {
            $this->deleteStoredFile($template->file_path);
            $template->file_path = $this->storeUploadedFile($request->file('file'), 'template');
        }

        if ($request->hasFile('file_pdf')) {
            $this->deleteStoredFile($template->file_pdf);
            $template->file_pdf = $this->storeUploadedFile($request->file('file_pdf'), 'pratinjau');
        }

        $template->save();

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil diperbarui.');
    }

    public function removeFile($id)
    {
        $template = Template::findOrFail($id);

        if ($template->file_path) {
            $this->deleteStoredFile($template->file_path);
            $template->file_path = '';
            $template->placeholders = null;
            $template->save();
        }

        return redirect()->route('templates.edit', $id)
            ->with('success', 'File template berhasil dihapus.');
    }

    public function removeFilePdf($id)
    {
        $template = Template::findOrFail($id);

        if ($template->file_pdf) {
            $this->deleteStoredFile($template->file_pdf);
            $template->file_pdf = null;
            $template->save();
        }

        return redirect()->route('templates.edit', $id)
            ->with('success', 'File PDF pratinjau berhasil dihapus.');
    }

    public function destroy($id)
    {
        $template = Template::findOrFail($id);

        $this->deleteStoredFile($template->file_path);
        $this->deleteStoredFile($template->file_pdf);

        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }

    /**
     * Simpan berkas dengan nama yang aman dan unik.
     */
    private function storeUploadedFile(UploadedFile $file, string $fallback): string
    {
        $directory = storage_path('app/public/templates');
        File::ensureDirectoryExists($directory, 0755, true);

        $originalName = basename($file->getClientOriginalName());
        $baseName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) ?: $fallback;
        $extension = strtolower($file->getClientOriginalExtension() ?: (string) $file->extension());

        $fileName = $baseName.'_'.now()->format('YmdHis').'_'.Str::random(6).'.'.$extension;

        $file->move($directory, $fileName);

        return 'storage/templates/'.$fileName;
    }

    /**
     * Hapus berkas fisik dari storage maupun public/storage.
     */
    private function deleteStoredFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fileName = basename(str_replace('\\', '/', $path));

        $candidates = [
            storage_path('app/public/templates/'.$fileName),
            public_path('storage/templates/'.$fileName),
        ];

        foreach ($candidates as $candidate) {
            if (File::isFile($candidate)) {
                File::delete($candidate);
            }
        }
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
