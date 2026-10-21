<?php

namespace App\Enums;

enum JenisSurat: string
{
    case SURAT_MASUK = 'Surat Masuk';
    case UNDANGAN_MASUK = 'Undangan Masuk';
    case SURAT_KELUAR = 'Surat Keluar';
    case UNDANGAN_KELUAR = 'Undangan Keluar';
    case SPT = 'SPT';
    case SPPD = 'SPPD';
    case SK = 'SK (Surat Keterangan)';

    public static function masukValues(): array
    {
        return [self::SURAT_MASUK->value, self::UNDANGAN_MASUK->value];
    }

    public static function keluarValues(): array
    {
        return [self::SURAT_KELUAR->value, self::SPPD->value, self::UNDANGAN_KELUAR->value, self::SK->value, self::SPT->value];
    }

    public static function allValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function validationRules(): string
    {
        return 'in:' . implode(',', self::allValues());
    }
}
