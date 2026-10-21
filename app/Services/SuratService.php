<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;

class SuratService
{
    public static function parseTanggal(Request $request): void
    {
        foreach (['tanggal_terima', 'tanggal_surat'] as $field) {
            if (!$request->filled($field)) {
                continue;
            }
            $request->merge([$field => self::normalizeDateValue($request->input($field))]);
        }
        if ($request->filled('tanggal_terima') && empty($request->input('tanggal_surat'))) {
            $request->merge(['tanggal_surat' => $request->input('tanggal_terima')]);
        } elseif (empty($request->input('tanggal_terima')) && $request->filled('tanggal_surat')) {
            $request->merge(['tanggal_terima' => $request->input('tanggal_surat')]);
        }
    }

    /**
     * Normalisasi tanggal format Indonesia (day-first) menjadi Y-m-d.
     * "05/08/2026" (5 Agustus) diparsing eksplisit sebagai dd/mm/yyyy,
     * bukan default PHP yang menganggap m-d-Y (US) -> salah bulan.
     */
    public static function normalizeDateValue(string $value): string
    {
        $value = trim($value);
        if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $value, $m)) {
            $day = (int) $m[1];
            $month = (int) $m[2];
            $year = (int) $m[3];
            if ($day >= 1 && $day <= 31 && $month >= 1 && $month <= 12) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }
        if (preg_match('#^(\d{1,2})/(\d{1,2})/(\d{2})$#', $value, $m)) {
            $day = (int) $m[1];
            $month = (int) $m[2];
            $year = (int) $m[3];
            $year = $year < 70 ? 2000 + $year : 1900 + $year;
            if ($day >= 1 && $day <= 31 && $month >= 1 && $month <= 12) {
                return sprintf('%04d-%02d-%02d', $year, $month, $day);
            }
        }
        return str_replace('/', '-', $value);
    }

    public static function getPeriodeString(?string $startDate, ?string $endDate): string
    {
        if (!$startDate || !$endDate) {
            return '';
        }
        if (!strtotime($startDate) || !strtotime($endDate)) {
            return '';
        }
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        if ($start->format('Y-m-d') === $start->copy()->startOfYear()->format('Y-m-d') &&
            $end->format('Y-m-d') === $end->copy()->endOfYear()->format('Y-m-d')) {
            return 'Tahun ' . $start->format('Y');
        }
        if ($start->format('Y-m') === $end->format('Y-m') &&
            $start->format('Y-m-d') === $start->copy()->startOfMonth()->format('Y-m-d') &&
            $end->format('Y-m-d') === $end->copy()->endOfMonth()->format('Y-m-d')) {
            return 'Bulan ' . $start->translatedFormat('F Y');
        }
        return $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
    }

    public static function isPlaceholder($model): bool
    {
        if (str_contains($model->perihal ?? '', 'KOSONG')) {
            return true;
        }
        $identitas = $model->pengirim ?? $model->tujuan ?? null;
        return empty($model->no_surat)
            && empty($model->perihal)
            && empty($model->isi_ringkas)
            && empty($identitas)
            && empty($model->tanggal_surat)
            && empty($model->tanggal_terima)
            && empty($model->file_surat);
    }
}
