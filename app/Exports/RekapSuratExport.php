<?php

namespace App\Exports;

use App\Enums\JenisSurat;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapSuratExport implements WithMultipleSheets
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        $sheets = [];

        $qSm = SuratMasuk::query();
        $qSk = SuratKeluar::query();
        if ($this->startDate && $this->endDate) {
            $qSm->whereBetween('tanggal_terima', [$this->startDate, $this->endDate]);
            $qSk->whereBetween('tanggal_surat', [$this->startDate, $this->endDate]);
        }
        $smGrouped = $qSm->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');
        $skGrouped = $qSk->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');

        foreach (JenisSurat::masukValues() as $jenis) {
            $sheets[] = new SuratMasukExport($smGrouped->get($jenis, collect()), $jenis);
        }
        foreach (JenisSurat::keluarValues() as $jenis) {
            $label = $jenis === 'SK (Surat Keterangan)' ? 'SK' : $jenis;
            $sheets[] = new SuratKeluarExport($skGrouped->get($jenis, collect()), $label);
        }

        return $sheets;
    }
}
