<?php

namespace App\Services;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\DB;

class DashboardKearsipanService
{
    public function getSuratStats(): array
    {
        $smStats = SuratMasuk::whereNotNull('perihal')
            ->selectRaw("jenis_surat, status_disposisi, COUNT(*) as total")
            ->groupBy('jenis_surat', 'status_disposisi')
            ->get();

        $skStats = SuratKeluar::whereNotNull('perihal')
            ->selectRaw("jenis_surat, COUNT(*) as total")
            ->groupBy('jenis_surat')
            ->get();

        $result = [
            'surat_masuk'     => 0, 'undangan_masuk'  => 0,
            'surat_keluar'    => 0, 'undangan_keluar' => 0,
            'spt'             => 0, 'sppd'            => 0,
            'sk'              => 0,
            'belum_disposisi' => 0, 'diproses'        => 0,
        ];

        $mapSm = ['Surat Masuk' => 'surat_masuk', 'Undangan Masuk' => 'undangan_masuk'];
        $mapSk = ['Surat Keluar' => 'surat_keluar', 'Undangan Keluar' => 'undangan_keluar',
                  'SPT' => 'spt', 'SPPD' => 'sppd', 'SK (Surat Keterangan)' => 'sk'];

        foreach ($smStats as $row) {
            $key = $mapSm[$row->jenis_surat] ?? null;
            if ($key) $result[$key] += (int) $row->total;
            if ($row->status_disposisi === 'belum') $result['belum_disposisi'] += (int) $row->total;
            if ($row->status_disposisi === 'diproses') $result['diproses'] += (int) $row->total;
        }
        foreach ($skStats as $row) {
            $key = $mapSk[$row->jenis_surat] ?? null;
            if ($key) $result[$key] = (int) $row->total;
        }

        return $result;
    }

    public function getChartData(): array
    {
        $since = now()->startOfMonth()->subMonths(5)->startOfMonth();

        $smChart = SuratMasuk::whereNotNull('perihal')->where('tanggal_terima', '>=', $since)
            ->selectRaw("jenis_surat, YEAR(tanggal_terima) as y, MONTH(tanggal_terima) as m, COUNT(*) as total")
            ->groupBy('jenis_surat', 'y', 'm')
            ->get()
            ->keyBy(fn($r) => $r->jenis_surat . '|' . $r->y . '|' . $r->m);

        $skChart = SuratKeluar::whereNotNull('perihal')->where('tanggal_surat', '>=', $since)
            ->selectRaw("jenis_surat, YEAR(tanggal_surat) as y, MONTH(tanggal_surat) as m, COUNT(*) as total")
            ->groupBy('jenis_surat', 'y', 'm')
            ->get()
            ->keyBy(fn($r) => $r->jenis_surat . '|' . $r->y . '|' . $r->m);

        $jenisKeys = [
            'Surat Masuk' => 'surat_masuk', 'Undangan Masuk' => 'undangan_masuk',
            'Surat Keluar' => 'surat_keluar', 'SPT' => 'spt', 'SPPD' => 'sppd',
            'SK (Surat Keterangan)' => 'sk', 'Undangan Keluar' => 'undangan_keluar',
        ];
        $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->startOfMonth()->subMonths($i);
            $m = $bulan->month; $y = $bulan->year;
            $row = ['label' => $bulan->translatedFormat('M Y')];
            foreach ($jenisKeys as $dbJenis => $key) {
                $lookup = $smChart->get($dbJenis . '|' . $y . '|' . $m)
                    ?? $skChart->get($dbJenis . '|' . $y . '|' . $m);
                $row[$key] = $lookup ? (int) $lookup->total : 0;
            }
            $data[] = $row;
        }
        return $data;
    }

    public function getSuratMasukForExport(?string $startDate, ?string $endDate): array
    {
        $q = SuratMasuk::with(['klasifikasi', 'bidangTujuans']);
        if ($startDate && $endDate) {
            $q->whereBetween('tanggal_terima', [$startDate, $endDate]);
        }
        $all = $q->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');

        return [
            'Surat Masuk'    => ['type' => 'masuk', 'items' => $all->get('Surat Masuk', collect())],
            'Undangan Masuk' => ['type' => 'masuk', 'items' => $all->get('Undangan Masuk', collect())],
        ];
    }

    public function getSuratKeluarForExport(?string $startDate, ?string $endDate): array
    {
        $q = SuratKeluar::with(['klasifikasi', 'bidangs']);
        if ($startDate && $endDate) {
            $q->whereBetween('tanggal_surat', [$startDate, $endDate]);
        }
        $all = $q->orderByRaw('CAST(no_agenda AS UNSIGNED) ASC')->get()->groupBy('jenis_surat');

        return [
            'Surat Keluar'    => ['type' => 'keluar', 'items' => $all->get('Surat Keluar', collect())],
            'Undangan Keluar' => ['type' => 'keluar', 'items' => $all->get('Undangan Keluar', collect())],
            'SPT'             => ['type' => 'keluar', 'items' => $all->get('SPT', collect())],
            'SPPD'            => ['type' => 'keluar', 'items' => $all->get('SPPD', collect())],
            'SK'              => ['type' => 'keluar', 'items' => $all->get('SK (Surat Keterangan)', collect())],
        ];
    }
}
