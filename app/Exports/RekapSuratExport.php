<?php

namespace App\Exports;

use App\Enums\JenisSurat;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapSuratExport implements WithMultipleSheets
{
    protected ?string $startDate;

    protected ?string $endDate;

    protected ?int $bidangId;

    public function __construct(?string $startDate = null, ?string $endDate = null, ?int $bidangId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->bidangId = $bidangId;
    }

    public function sheets(): array
    {
        $qSm = SuratMasuk::query();
        $qSk = SuratKeluar::query();

        $this->applyFilters($qSm, 'tanggal_surat', 'bidang_tujuan_id');
        $this->applyFilters($qSk, 'tanggal_surat', 'bidang_id');

        $smGrouped = $qSm->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');
        $skGrouped = $qSk->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');

        $periodeLabel = $this->periodeLabel();
        $judul = 'REKAP SURAT DINAS PERHUBUNGAN PROVINSI LAMPUNG';
        $sheets = [];

        foreach (JenisSurat::masukValues() as $jenis) {
            $sheets[] = new SuratMasukExport($smGrouped->get($jenis, collect()), $jenis, $periodeLabel, $judul);
        }

        foreach (JenisSurat::keluarValues() as $jenis) {
            $label = $jenis === 'SK (Surat Keterangan)' ? 'SK' : $jenis;
            $sheets[] = new SuratKeluarExport($skGrouped->get($jenis, collect()), $label, $periodeLabel, $judul);
        }

        return $sheets;
    }

    private function applyFilters(Builder $query, string $dateColumn, string $bidangColumn): void
    {
        if ($this->startDate) {
            $query->whereDate($dateColumn, '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate($dateColumn, '<=', $this->endDate);
        }

        if ($this->bidangId) {
            $query->where($bidangColumn, $this->bidangId);
        }
    }

    private function periodeLabel(): string
    {
        if (! $this->startDate && ! $this->endDate) {
            return 'Semua Waktu';
        }

        $start = $this->startDate ? Carbon::parse($this->startDate) : null;
        $end = $this->endDate ? Carbon::parse($this->endDate) : null;

        if ($start && $end) {
            if ($start->format('Y-m-d') === $start->copy()->startOfYear()->format('Y-m-d')
                && $end->format('Y-m-d') === $end->copy()->endOfYear()->format('Y-m-d')) {
                return 'Tahun '.$start->format('Y');
            }

            if ($start->format('Y-m') === $end->format('Y-m')
                && $start->format('Y-m-d') === $start->copy()->startOfMonth()->format('Y-m-d')
                && $end->format('Y-m-d') === $end->copy()->endOfMonth()->format('Y-m-d')) {
                return 'Bulan '.$start->translatedFormat('F Y');
            }

            return $start->translatedFormat('d F Y').' - '.$end->translatedFormat('d F Y');
        }

        return $start
            ? 'Sejak '.$start->translatedFormat('d F Y')
            : 'Sampai '.$end->translatedFormat('d F Y');
    }
}
