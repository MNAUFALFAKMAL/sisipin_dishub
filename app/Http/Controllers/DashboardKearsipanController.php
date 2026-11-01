<?php

namespace App\Http\Controllers;

use App\Exports\RekapSuratExport;
use App\Services\DashboardKearsipanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class DashboardKearsipanController extends Controller
{
    protected DashboardKearsipanService $dashboardKearsipanService;

    public function __construct(DashboardKearsipanService $dashboardKearsipanService)
    {
        $this->dashboardKearsipanService = $dashboardKearsipanService;
    }

    /**
     * Menampilkan dashboard Kearsipan Dinamis (SIKD)
     */
    public function index(Request $request)
    {
        $filters = $this->validatedFilters($request);

        $suratStats = $this->dashboardKearsipanService->getSuratStats(
            $filters['start'],
            $filters['end'],
            $filters['bidang_id']
        );
        $grafikSurat = $this->dashboardKearsipanService->getChartData(
            $filters['start'],
            $filters['end'],
            $filters['bidang_id']
        );

        return view('kearsipan.dashboard', compact('suratStats', 'grafikSurat'));
    }

    /**
     * Endpoint JSON statistik untuk grafik dashboard.
     */
    public function statistik(Request $request)
    {
        $filters = $this->validatedFilters($request);

        return response()->json($this->dashboardKearsipanService->getStatistik(
            $filters['start'],
            $filters['end'],
            $filters['bidang_id']
        ));
    }

    /**
     * Ekspor rekap semua data surat (multi-sheet)
     */
    public function exportExcel(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $startDate = $filters['start'];
        $endDate = $filters['end'];
        $bidangId = $filters['bidang_id'];

        $filename = 'Rekap Surat Dishub';
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            if ($start->format('Y-m-d') === $start->copy()->startOfYear()->format('Y-m-d') &&
                $end->format('Y-m-d') === $end->copy()->endOfYear()->format('Y-m-d')) {
                $filename .= ' Tahun '.$start->format('Y');
            } elseif ($start->format('Y-m') === $end->format('Y-m') &&
                $start->format('Y-m-d') === $start->copy()->startOfMonth()->format('Y-m-d') &&
                $end->format('Y-m-d') === $end->copy()->endOfMonth()->format('Y-m-d')) {
                $filename .= ' Bulan '.$start->translatedFormat('F Y');
            } else {
                $filename .= ' '.$start->translatedFormat('d F Y').' - '.$end->translatedFormat('d F Y');
            }
        } else {
            $filename .= ' Semua Waktu';
        }
        $filename .= '.xlsx';

        return Excel::download(new RekapSuratExport($startDate, $endDate, $bidangId), $filename);
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $startDate = $filters['start'];
        $endDate = $filters['end'];
        $bidangId = $filters['bidang_id'];

        $title = 'Rekap Laporan Data Surat';
        $periodeStr = 'Semua Waktu';

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            if ($start->format('Y-m-d') === $start->copy()->startOfYear()->format('Y-m-d') &&
                $end->format('Y-m-d') === $end->copy()->endOfYear()->format('Y-m-d')) {
                $periodeStr = 'Tahun '.$start->format('Y');
            } elseif ($start->format('Y-m') === $end->format('Y-m') &&
                $start->format('Y-m-d') === $start->copy()->startOfMonth()->format('Y-m-d') &&
                $end->format('Y-m-d') === $end->copy()->endOfMonth()->format('Y-m-d')) {
                $periodeStr = 'Bulan '.$start->translatedFormat('F Y');
            } else {
                $periodeStr = $start->translatedFormat('d F Y').' - '.$end->translatedFormat('d F Y');
            }
        }

        $data = array_merge(
            $this->dashboardKearsipanService->getSuratMasukForExport($startDate, $endDate, $bidangId),
            $this->dashboardKearsipanService->getSuratKeluarForExport($startDate, $endDate, $bidangId)
        );

        $pdf = Pdf::loadView('kearsipan.rekap-pdf', compact('title', 'periodeStr', 'data'))->setPaper('a4', 'landscape');

        return $pdf->download('Rekap Surat Dishub '.$periodeStr.'.pdf');
    }

    /**
     * Tampilan panduan pengguna (Fitur Pembelajaran)
     */
    public function pembelajaran()
    {
        return view('pembelajaran.index');
    }

    /**
     * @return array{start: ?string, end: ?string, bidang_id: ?int}
     */
    private function validatedFilters(Request $request): array
    {
        $validated = $request->validate([
            'start' => ['nullable', 'date'],
            'end' => [
                'nullable',
                'date',
                Rule::when($request->filled('start'), ['after_or_equal:start']),
            ],
            'start_date' => ['nullable', 'date'],
            'end_date' => [
                'nullable',
                'date',
                Rule::when($request->filled('start_date'), ['after_or_equal:start_date']),
            ],
            'bidang_id' => ['nullable', 'integer', 'exists:bidangs,id'],
        ]);

        return [
            'start' => $validated['start'] ?? $validated['start_date'] ?? null,
            'end' => $validated['end'] ?? $validated['end_date'] ?? null,
            'bidang_id' => isset($validated['bidang_id']) ? (int) $validated['bidang_id'] : null,
        ];
    }
}
