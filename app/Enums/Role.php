<?php

namespace App\Enums;

enum Role: string
{
    case SUPER_ADMIN = 'super_admin';
    case KEPALA_DINAS = 'kepala_dinas';
    case KEPALA_BIDANG = 'kepala_bidang';
    case STAFF = 'staff';
    case TATA_USAHA = 'tata_usaha';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function validationRules(): string
    {
        return 'in:' . implode(',', self::values());
    }
}
