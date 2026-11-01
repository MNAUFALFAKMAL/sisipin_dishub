<?php

namespace App\Services;

use App\Models\Bidang;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DashboardKearsipanService
{
    /**
     * Tanggal yang dipakai untuk periode laporan. Surat masuk dan surat keluar
     * sama-sama memakai tanggal surat agar hasil rekap konsisten dengan
     * hitungan manual "YEAR(tanggal_surat)".
     */
    private const TANGGAL_MASUK = 'tanggal_surat';

    private const TANGGAL_KELUAR = 'tanggal_surat';

    public function getSuratStats(?string $startDate = null, ?string $endDate = null, ?int $bidangId = null): array
    {
        $smStats = $this->applyFilters(
            SuratMasuk::query(),
            self::TANGGAL_MASUK,
            $startDate,
            $endDate,
            $bidangId,
            'bidang_tujuan_id'
        )
            ->selectRaw('jenis_surat, status_disposisi, COUNT(*) as total')
            ->groupBy('jenis_surat', 'status_disposisi')
            ->get();

        $skStats = $this->applyFilters(
            SuratKeluar::query(),
            self::TANGGAL_KELUAR,
            $startDate,
            $endDate,
            $bidangId,
            'bidang_id'
        )
            ->selectRaw('jenis_surat, COUNT(*) as total')
            ->groupBy('jenis_surat')
            ->get();

        $result = [
            'surat_masuk' => 0, 'undangan_masuk' => 0,
            'surat_keluar' => 0, 'undangan_keluar' => 0,
            'spt' => 0, 'sppd' => 0, 'sk' => 0,
            'belum_disposisi' => 0, 'diproses' => 0,
        ];

        $mapMasuk = $this->jenisMasukMap();
        $mapKeluar = $this->jenisKeluarMap();

        foreach ($smStats as $row) {
            $key = $mapMasuk[$row->jenis_surat] ?? null;
            if ($key !== null) {
                $result[$key] += (int) $row->total;
            }

            if ($row->status_disposisi === 'belum') {
                $result['belum_disposisi'] += (int) $row->total;
            } elseif ($row->status_disposisi === 'diproses') {
                $result['diproses'] += (int) $row->total;
            }
        }

        foreach ($skStats as $row) {
            $key = $mapKeluar[$row->jenis_surat] ?? null;
            if ($key !== null) {
                $result[$key] += (int) $row->total;
            }
        }

        return $result;
    }

    /**
     * Data grafik bulanan. Bila rentang tanggal tidak diberikan, dipakai
     * enam bulan terakhir (perilaku dashboard lama).
     */
    public function getChartData(?string $startDate = null, ?string $endDate = null, ?int $bidangId = null): array
    {
        [$start, $end] = $this->resolveRange($startDate, $endDate);
        $start = $start->copy()->startOfMonth();
        $end = $end->copy()->endOfMonth();

        $bulanMasuk = $this->monthExpression(self::TANGGAL_MASUK);
        $bulanKeluar = $this->monthExpression(self::TANGGAL_KELUAR);

        $smRows = $this->applyFilters(
            SuratMasuk::query(),
            self::TANGGAL_MASUK,
            $start->toDateString(),
            $end->toDateString(),
            $bidangId,
            'bidang_tujuan_id'
        )
            ->selectRaw("{$bulanMasuk} as periode, jenis_surat, COUNT(*) as total")
            ->groupBy('periode', 'jenis_surat')
            ->get();

        $skRows = $this->applyFilters(
            SuratKeluar::query(),
            self::TANGGAL_KELUAR,
            $start->toDateString(),
            $end->toDateString(),
            $bidangId,
            'bidang_id'
        )
            ->selectRaw("{$bulanKeluar} as periode, jenis_surat, COUNT(*) as total")
            ->groupBy('periode', 'jenis_surat')
            ->get();

        $smMap = $this->mapPeriodeJenis($smRows);
        $skMap = $this->mapPeriodeJenis($skRows);
        $mapMasuk = $this->jenisMasukMap();
        $mapKeluar = $this->jenisKeluarMap();

        $data = [];
        $cursor = $start->copy();

        while ($cursor->lte($end)) {
            $periode = $cursor->format('Y-m');
            $row = [
                'bulan' => $periode,
                'label' => $cursor->translatedFormat('M Y'),
            ];

            foreach (['surat_masuk', 'undangan_masuk', 'surat_keluar', 'undangan_keluar', 'spt', 'sppd', 'sk'] as $key) {
                $row[$key] = 0;
            }

            foreach ($mapMasuk as $jenis => $key) {
                $row[$key] += $smMap[$periode][$jenis] ?? 0;
            }

            foreach ($mapKeluar as $jenis => $key) {
                $row[$key] += $skMap[$periode][$jenis] ?? 0;
            }

            $data[] = $row;
            $cursor->addMonth();
        }

        return $data;
    }

    /**
     * Payload JSON untuk endpoint statistik dashboard.
     */
    public function getStatistik(?string $startDate = null, ?string $endDate = null, ?int $bidangId = null): array
    {
        [$start, $end] = $this->resolveRange($startDate, $endDate);
        $start = $start->copy()->startOfDay();
        $end = $end->copy()->endOfDay();

        $totalMasuk = $this->applyFilters(
            SuratMasuk::query(),
            self::TANGGAL_MASUK,
            $start->toDateString(),
            $end->toDateString(),
            $bidangId,
            'bidang_tujuan_id'
        )->count();

        $totalKeluar = $this->applyFilters(
            SuratKeluar::query(),
            self::TANGGAL_KELUAR,
            $start->toDateString(),
            $end->toDateString(),
            $bidangId,
            'bidang_id'
        )->count();

        $bulanMasuk = $this->monthExpression(self::TANGGAL_MASUK);
        $bulanKeluar = $this->monthExpression(self::TANGGAL_KELUAR);

        $smPerBulan = $this->applyFilters(
            SuratMasuk::query(),
            self::TANGGAL_MASUK,
            $start->toDateString(),
            $end->toDateString(),
            $bidangId,
            'bidang_tujuan_id'
        )
            ->selectRaw("{$bulanMasuk} as periode, COUNT(*) as total")
            ->groupBy('periode')
            ->pluck('total', 'periode');

        $skPerBulan = $this->applyFilters(
            SuratKeluar::query(),
            self::TANGGAL_KELUAR,
            $start->toDateString(),
            $end->toDateString(),
            $bidangId,
            'bidang_id'
        )
            ->selectRaw("{$bulanKeluar} as periode, COUNT(*) as total")
            ->groupBy('periode')
            ->pluck('total', 'periode');

        $perBulan = [];
        $cursor = $start->copy()->startOfMonth();
        $batas = $end->copy()->startOfMonth();

        while ($cursor->lte($batas)) {
            $periode = $cursor->format('Y-m');
            $perBulan[] = [
                'bulan' => $periode,
                'masuk' => (int) ($smPerBulan[$periode] ?? 0),
                'keluar' => (int) ($skPerBulan[$periode] ?? 0),
            ];
            $cursor->addMonth();
        }

        return [
            'total_masuk' => $totalMasuk,
            'total_keluar' => $totalKeluar,
            'per_bulan' => $perBulan,
            'per_bidang' => $this->getPerBidang($start, $end, $bidangId),
        ];
    }

    public function getSuratMasukForExport(?string $startDate, ?string $endDate, ?int $bidangId = null): array
    {
        $query = SuratMasuk::with(['klasifikasi', 'bidangTujuans']);

        $this->applyFilters(
            $query,
            self::TANGGAL_MASUK,
            $startDate,
            $endDate,
            $bidangId,
            'bidang_tujuan_id'
        );

        $all = $query->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');

        return [
            'Surat Masuk' => ['type' => 'masuk', 'items' => $all->get('Surat Masuk', collect())],
            'Undangan Masuk' => ['type' => 'masuk', 'items' => $all->get('Undangan Masuk', collect())],
        ];
    }

    public function getSuratKeluarForExport(?string $startDate, ?string $endDate, ?int $bidangId = null): array
    {
        $query = SuratKeluar::with(['klasifikasi', 'bidangs']);

        $this->applyFilters(
            $query,
            self::TANGGAL_KELUAR,
            $startDate,
            $endDate,
            $bidangId,
            'bidang_id'
        );

        $all = $query->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');

        return [
            'Surat Keluar' => ['type' => 'keluar', 'items' => $all->get('Surat Keluar', collect())],
            'Undangan Keluar' => [
                'type' => 'keluar',
                'items' => $all->get('Undangan Keluar', collect())
                    ->merge($all->get('Undangan', collect()))
                    ->values(),
            ],
            'SPT' => ['type' => 'keluar', 'items' => $all->get('SPT', collect())],
            'SPPD' => ['type' => 'keluar', 'items' => $all->get('SPPD', collect())],
            'SK' => [
                'type' => 'keluar',
                'items' => $all->get('SK (Surat Keterangan)', collect())
                    ->merge($all->get('SK', collect()))
                    ->values(),
            ],
        ];
    }

    private function getPerBidang(Carbon $start, Carbon $end, ?int $bidangId): array
    {
        $masuk = $this->applyFilters(
            SuratMasuk::query(),
            self::TANGGAL_MASUK,
            $start->toDateString(),
            $end->toDateString(),
            $bidangId,
            'bidang_tujuan_id'
        )
            ->selectRaw('bidang_tujuan_id as bidang_id, COUNT(*) as total')
            ->groupBy('bidang_tujuan_id')
            ->pluck('total', 'bidang_id');

        $keluar = $this->applyFilters(
            SuratKeluar::query(),
            self::TANGGAL_KELUAR,
            $start->toDateString(),
            $end->toDateString(),
            $bidangId,
            'bidang_id'
        )
            ->selectRaw('bidang_id, COUNT(*) as total')
            ->groupBy('bidang_id')
            ->pluck('total', 'bidang_id');

        $ids = $masuk->keys()
            ->merge($keluar->keys())
            ->filter(fn ($id) => $id !== null && $id !== '')
            ->unique()
            ->values();

        $namaBidang = $ids->isEmpty()
            ? collect()
            : Bidang::whereIn('id', $ids)->pluck('nama_bidang', 'id');

        $result = [];
        foreach ($ids as $id) {
            $result[] = [
                'bidang_id' => (int) $id,
                'nama_bidang' => $namaBidang[$id] ?? 'Tanpa Bidang',
                'masuk' => (int) ($masuk[$id] ?? 0),
                'keluar' => (int) ($keluar[$id] ?? 0),
            ];
        }

        usort($result, fn ($a, $b) => strcmp($a['nama_bidang'], $b['nama_bidang']));

        return $result;
    }

    private function applyFilters(
        Builder $query,
        string $dateColumn,
        ?string $startDate,
        ?string $endDate,
        ?int $bidangId,
        string $bidangColumn
    ): Builder {
        $model = $query->getModel();

        if ($startDate) {
            $query->whereDate($model->qualifyColumn($dateColumn), '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate($model->qualifyColumn($dateColumn), '<=', $endDate);
        }

        if ($bidangId) {
            $query->where($model->qualifyColumn($bidangColumn), $bidangId);
        }

        return $query;
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveRange(?string $startDate, ?string $endDate): array
    {
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : now()->endOfDay();
        $start = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : $end->copy()->startOfMonth()->subMonths(5)->startOfDay();

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        return [$start, $end];
    }

    private function monthExpression(string $column): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', {$column})",
            'pgsql' => "to_char({$column}, 'YYYY-MM')",
            default => "DATE_FORMAT({$column}, '%Y-%m')",
        };
    }

    private function mapPeriodeJenis($rows): array
    {
        $map = [];
        foreach ($rows as $row) {
            $map[$row->periode][$row->jenis_surat] = (int) $row->total;
        }

        return $map;
    }

    private function jenisMasukMap(): array
    {
        return [
            'Surat Masuk' => 'surat_masuk',
            'Undangan Masuk' => 'undangan_masuk',
        ];
    }

    private function jenisKeluarMap(): array
    {
        return [
            'Surat Keluar' => 'surat_keluar',
            'Undangan Keluar' => 'undangan_keluar',
            'Undangan' => 'undangan_keluar',
            'SPT' => 'spt',
            'SPPD' => 'sppd',
            'SK' => 'sk',
            'SK (Surat Keterangan)' => 'sk',
        ];
    }
}
