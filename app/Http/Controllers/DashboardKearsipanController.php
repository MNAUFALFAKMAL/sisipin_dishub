<?php

namespace App\Http\Controllers;

use App\Exports\RekapSuratExport;
use App\Models\Bidang;
use App\Models\User;
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
    public function index()
    {
        $suratStats = $this->dashboardKearsipanService->getSuratStats();
        $grafikSurat = $this->dashboardKearsipanService->getChartData();

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
        $filters = $this->exportFilters($request);
        $periode = $this->periodeLabel($filters['start'], $filters['end']);

        $filename = 'Rekap Surat Dishub '.$periode.'.xlsx';

        return Excel::download(
            new RekapSuratExport($filters['start'], $filters['end'], $filters['bidang_id']),
            $filename
        );
    }

    public function exportPdf(Request $request)
    {
        $filters = $this->exportFilters($request);
        $periodeStr = $this->periodeLabel($filters['start'], $filters['end']);
        $title = 'Rekap Surat Periode '.$periodeStr;

        $data = array_merge(
            $this->dashboardKearsipanService->getSuratMasukForExport(
                $filters['start'],
                $filters['end'],
                $filters['bidang_id']
            ),
            $this->dashboardKearsipanService->getSuratKeluarForExport(
                $filters['start'],
                $filters['end'],
                $filters['bidang_id']
            )
        );

        $bidang = $filters['bidang_id']
            ? Bidang::find($filters['bidang_id'])?->nama_bidang
            : null;

        $kepalaDinas = User::whereHas('peran', fn ($query) => $query->where('slug', 'kepala_dinas'))->first();

        $penandatangan = [
            'nama' => $kepalaDinas?->name ?: '................................',
            'nip' => $kepalaDinas?->nip ?: '................................',
        ];

        $pdf = Pdf::loadView(
            'kearsipan.rekap-pdf',
            compact('title', 'periodeStr', 'data', 'bidang', 'penandatangan')
        )->setPaper('a4', 'landscape');

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
    private function exportFilters(Request $request): array
    {
        return $this->validatedFilters($request);
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

    private function periodeLabel(?string $startDate, ?string $endDate): string
    {
        if (! $startDate && ! $endDate) {
            return 'Semua Waktu';
        }

        $start = $startDate ? Carbon::parse($startDate) : null;
        $end = $endDate ? Carbon::parse($endDate) : null;

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
