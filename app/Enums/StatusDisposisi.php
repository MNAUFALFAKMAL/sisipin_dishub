<?php

namespace App\Enums;

enum StatusDisposisi: string
{
    case BELUM = 'belum';
    case DIPROSES = 'diproses';
    case SELESAI = 'selesai';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function validationRules(): string
    {
        return 'in:' . implode(',', self::values());
    }
}
