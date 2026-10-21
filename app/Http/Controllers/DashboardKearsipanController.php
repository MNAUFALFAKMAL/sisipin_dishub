<?php

namespace App\Http\Controllers;

use App\Services\DashboardKearsipanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
     * Ekspor rekap semua data surat (multi-sheet)
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (($startDate && !strtotime($startDate)) || ($endDate && !strtotime($endDate))) {
            return redirect()->back()->withErrors(['Tanggal tidak valid.']);
        }

        $filename = 'Rekap Surat Dishub';
        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            if ($start->format('Y-m-d') === $start->copy()->startOfYear()->format('Y-m-d') &&
                $end->format('Y-m-d') === $end->copy()->endOfYear()->format('Y-m-d')) {
                $filename .= ' Tahun ' . $start->format('Y');
            } elseif ($start->format('Y-m') === $end->format('Y-m') && 
                $start->format('Y-m-d') === $start->copy()->startOfMonth()->format('Y-m-d') && 
                $end->format('Y-m-d') === $end->copy()->endOfMonth()->format('Y-m-d')) {
                $filename .= ' Bulan ' . $start->translatedFormat('F Y');
            } else {
                $filename .= ' ' . $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
            }
        } else {
            $filename .= ' Semua Waktu';
        }
        $filename .= '.xlsx';

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RekapSuratExport($startDate, $endDate), $filename);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (($startDate && !strtotime($startDate)) || ($endDate && !strtotime($endDate))) {
            return redirect()->back()->withErrors(['Tanggal tidak valid.']);
        }

        $title = 'Rekap Laporan Data Surat';
        $periodeStr = 'Semua Waktu';

        if ($startDate && $endDate) {
            $start = Carbon::parse($startDate);
            $end = Carbon::parse($endDate);
            if ($start->format('Y-m-d') === $start->copy()->startOfYear()->format('Y-m-d') &&
                $end->format('Y-m-d') === $end->copy()->endOfYear()->format('Y-m-d')) {
                $periodeStr = 'Tahun ' . $start->format('Y');
            } elseif ($start->format('Y-m') === $end->format('Y-m') && 
                $start->format('Y-m-d') === $start->copy()->startOfMonth()->format('Y-m-d') && 
                $end->format('Y-m-d') === $end->copy()->endOfMonth()->format('Y-m-d')) {
                $periodeStr = 'Bulan ' . $start->translatedFormat('F Y');
            } else {
                $periodeStr = $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
            }
        }

        $data = array_merge(
            $this->dashboardKearsipanService->getSuratMasukForExport($startDate, $endDate),
            $this->dashboardKearsipanService->getSuratKeluarForExport($startDate, $endDate)
        );

        $pdf = Pdf::loadView('kearsipan.rekap-pdf', compact('title', 'periodeStr', 'data'))->setPaper('a4', 'landscape');
        return $pdf->download('Rekap Surat Dishub ' . $periodeStr . '.pdf');
    }

    /**
     * Tampilan panduan pengguna (Fitur Pembelajaran)
     */
    public function pembelajaran()
    {
        return view('pembelajaran.index');
    }
}
