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
     * sama-sama memakai tanggal surat agar hasil rekap konsisten.
     */
    private const TANGGAL_MASUK = 'tanggal_surat';

    private const TANGGAL_KELUAR = 'tanggal_surat';

    public function getSuratStats(): array
    {
        $smStats = SuratMasuk::whereNotNull('perihal')
            ->selectRaw('jenis_surat, status_disposisi, COUNT(*) as total')
            ->groupBy('jenis_surat', 'status_disposisi')
            ->get();

        $skStats = SuratKeluar::whereNotNull('perihal')
            ->selectRaw('jenis_surat, COUNT(*) as total')
            ->groupBy('jenis_surat')
            ->get();

        $result = [
            'surat_masuk' => 0, 'undangan_masuk' => 0,
            'surat_keluar' => 0, 'undangan_keluar' => 0,
            'spt' => 0, 'sppd' => 0,
            'sk' => 0,
            'belum_disposisi' => 0, 'diproses' => 0,
        ];

        $mapSm = ['Surat Masuk' => 'surat_masuk', 'Undangan Masuk' => 'undangan_masuk'];
        $mapSk = ['Surat Keluar' => 'surat_keluar', 'Undangan Keluar' => 'undangan_keluar',
            'SPT' => 'spt', 'SPPD' => 'sppd', 'SK (Surat Keterangan)' => 'sk'];

        foreach ($smStats as $row) {
            $key = $mapSm[$row->jenis_surat] ?? null;
            if ($key) {
                $result[$key] += (int) $row->total;
            }
            if ($row->status_disposisi === 'belum') {
                $result['belum_disposisi'] += (int) $row->total;
            }
            if ($row->status_disposisi === 'diproses') {
                $result['diproses'] += (int) $row->total;
            }
        }
        foreach ($skStats as $row) {
            $key = $mapSk[$row->jenis_surat] ?? null;
            if ($key) {
                $result[$key] = (int) $row->total;
            }
        }

        return $result;
    }

    public function getChartData(): array
    {
        $since = now()->startOfMonth()->subMonths(5)->startOfMonth();

        $smChart = SuratMasuk::whereNotNull('perihal')->where('tanggal_terima', '>=', $since)
            ->selectRaw('jenis_surat, YEAR(tanggal_terima) as y, MONTH(tanggal_terima) as m, COUNT(*) as total')
            ->groupBy('jenis_surat', 'y', 'm')
            ->get()
            ->keyBy(fn ($r) => $r->jenis_surat.'|'.$r->y.'|'.$r->m);

        $skChart = SuratKeluar::whereNotNull('perihal')->where('tanggal_surat', '>=', $since)
            ->selectRaw('jenis_surat, YEAR(tanggal_surat) as y, MONTH(tanggal_surat) as m, COUNT(*) as total')
            ->groupBy('jenis_surat', 'y', 'm')
            ->get()
            ->keyBy(fn ($r) => $r->jenis_surat.'|'.$r->y.'|'.$r->m);

        $jenisKeys = [
            'Surat Masuk' => 'surat_masuk', 'Undangan Masuk' => 'undangan_masuk',
            'Surat Keluar' => 'surat_keluar', 'SPT' => 'spt', 'SPPD' => 'sppd',
            'SK (Surat Keterangan)' => 'sk', 'Undangan Keluar' => 'undangan_keluar',
        ];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->startOfMonth()->subMonths($i);
            $m = $bulan->month;
            $y = $bulan->year;
            $row = ['label' => $bulan->translatedFormat('M Y')];
            foreach ($jenisKeys as $dbJenis => $key) {
                $lookup = $smChart->get($dbJenis.'|'.$y.'|'.$m)
                    ?? $skChart->get($dbJenis.'|'.$y.'|'.$m);
                $row[$key] = $lookup ? (int) $lookup->total : 0;
            }
            $data[] = $row;
        }

        return $data;
    }

    /**
     * Mengambil data statistik untuk endpoint /dashboard/statistik.
     *
     * @return array{total_masuk: int, total_keluar: int, per_bulan: array<int, array{bulan: string, masuk: int, keluar: int}>, per_bidang: array<int, array{bidang_id: int, nama_bidang: string, masuk: int, keluar: int}>}
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

    public function getSuratMasukForExport(?string $startDate, ?string $endDate, ?int $bidangId = null): array
    {
        $q = SuratMasuk::with(['klasifikasi', 'bidangTujuans']);
        if ($startDate) {
            $q->whereDate('tanggal_surat', '>=', $startDate);
        }
        if ($endDate) {
            $q->whereDate('tanggal_surat', '<=', $endDate);
        }
        if ($bidangId) {
            $q->where('bidang_tujuan_id', $bidangId);
        }
        $all = $q->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');

        return [
            'Surat Masuk' => ['type' => 'masuk', 'items' => $all->get('Surat Masuk', collect())],
            'Undangan Masuk' => ['type' => 'masuk', 'items' => $all->get('Undangan Masuk', collect())],
        ];
    }

    public function getSuratKeluarForExport(?string $startDate, ?string $endDate, ?int $bidangId = null): array
    {
        $q = SuratKeluar::with(['klasifikasi', 'bidangs']);
        if ($startDate) {
            $q->whereDate('tanggal_surat', '>=', $startDate);
        }
        if ($endDate) {
            $q->whereDate('tanggal_surat', '<=', $endDate);
        }
        if ($bidangId) {
            $q->where('bidang_id', $bidangId);
        }
        $all = $q->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');

        return [
            'Surat Keluar' => ['type' => 'keluar', 'items' => $all->get('Surat Keluar', collect())],
            'Undangan Keluar' => ['type' => 'keluar', 'items' => $all->get('Undangan Keluar', collect())],
            'SPT' => ['type' => 'keluar', 'items' => $all->get('SPT', collect())],
            'SPPD' => ['type' => 'keluar', 'items' => $all->get('SPPD', collect())],
            'SK' => ['type' => 'keluar', 'items' => $all->get('SK (Surat Keterangan)', collect())],
        ];
    }
}
