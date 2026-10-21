<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // middleware akan mengatur hak akses
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'sub_kategori' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:docx|max:10240',
            'file_pdf' => 'nullable|file|mimes:pdf|max:20480',
            'google_drive_link' => 'nullable|string|max:500',
        ];
    }
}
