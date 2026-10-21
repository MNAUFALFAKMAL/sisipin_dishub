<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
        'category',
        'sub_kategori',
        'placeholders',
        'is_active',
        'file_pdf',
        'google_drive_link',
    ];

    protected $casts = [
        'placeholders' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getServeFilePathAttribute(): ?string
    {
        return $this->file_path ? str_replace('storage/', '', $this->file_path) : null;
    }

    public function getServeFilePdfAttribute(): ?string
    {
        return $this->file_pdf ? str_replace('storage/', '', $this->file_pdf) : null;
    }
}