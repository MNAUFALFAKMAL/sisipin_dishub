<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturans';

    protected $fillable = [
        'kunci',
        'nilai',
        'tipe',
        'kelompok',
        'label',
        'deskripsi',
        'is_public',
        'is_active',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get nilai dengan casting otomatis berdasarkan tipe
     */
    public function getNilaiAttribute($value)
    {
        $tipe = $this->attributes['tipe'] ?? 'string';

        return match ($tipe) {
            'integer' => (int) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Set nilai dengan encoding otomatis berdasarkan tipe
     */
    public function setNilaiAttribute($value)
    {
        $tipe = $this->attributes['tipe'] ?? 'string';

        $this->attributes['nilai'] = match ($tipe) {
            'json', 'array' => json_encode($value),
            default => $value,
        };
    }

    /**
     * Helper static method untuk get nilai dengan default
     */
    public static function get(string $kunci, $default = null)
    {
        $setting = static::where('kunci', $kunci)
            ->where('is_active', true)
            ->first();

        return $setting?->nilai ?? $default;
    }

    /**
     * Helper static method untuk set nilai
     */
    public static function set(string $kunci, $nilai, string $tipe = 'string'): static
    {
        return static::updateOrCreate(
            ['kunci' => $kunci],
            ['nilai' => $nilai, 'tipe' => $tipe]
        );
    }
}