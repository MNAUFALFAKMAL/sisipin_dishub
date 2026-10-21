<?php

namespace App\Http\Controllers;

use App\Enums\JenisSurat;
use App\Enums\StatusDisposisi;
use App\Exports\SuratMasukExport;
use App\Imports\SuratMasukImport;
use App\Models\Bidang;
use App\Models\SuratMasuk;
use App\Services\SuratMasukService;
use App\Services\SuratService;
use App\Traits\MergesPdfChunks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SuratMasukController extends Controller
{
    use MergesPdfChunks;

    protected $service;

    public function __construct(SuratMasukService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if (! $request->has('jenis_surat')) {
            return redirect()->route('surat-masuk.index', array_merge($request->query(), ['jenis_surat' => JenisSurat::SURAT_MASUK->value]));
        }
        if (! $request->has('tahun')) {
            return redirect()->route('surat-masuk.index', array_merge($request->query(), ['tahun' => date('Y')]));
        }
        $query = $this->buildFilterQuery($request);
        $suratMasuks = $query->paginate(10);

        return view('surat-masuk.index', compact('suratMasuks'));
    }

    public function create(Request $request)
    {
        $bidangs = Bidang::all();
        $jenisSurat = $request->input('jenis_surat_active') ?: JenisSurat::SURAT_MASUK->value;
        $tahun = $request->filled('tahun') ? (int) $request->tahun : (int) date('Y');
        $suggestedNoAgenda = $this->service->getNextAgenda($jenisSurat, $tahun);
        $tahunAktif = $tahun;

        return view('surat-masuk.create', compact('bidangs', 'suggestedNoAgenda', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => ['required', 'string', JenisSurat::validationRules()],
            'no_agenda' => 'nullable|string|max:50',
            'no_surat' => 'nullable|string|max:100',
            'tanggal_surat' => 'nullable|date',
            'tanggal_terima' => 'nullable|date',
            'pengirim' => 'nullable|string|max:150',
            'perihal' => 'nullable|string|max:255',
            'file_surat' => 'nullable|array|max:10',
            'file_surat.*' => 'file|mimes:pdf,jpg,jpeg,png,webp,gif|mimetypes:application/pdf,image/jpeg,image/png,image/webp,image/gif|max:5120',
            'bidang_tujuan_ids' => 'nullable|array',
            'bidang_tujuan_ids.*' => 'exists:bidangs,id',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_arsips,id',
        ]);

        try {
            $suratMasuk = $this->service->store($request);

            return redirect()
                ->route('surat-masuk.index', $this->indexRedirectParams($request, $suratMasuk->jenis_surat))
                ->with('success', $suratMasuk->jenis_surat.' berhasil ditambahkan.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->withErrors(['no_agenda' => $e->getMessage()]);
        }
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['bidangTujuan', 'diinputOleh']);

        return view('surat-masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        $bidangs = Bidang::all();

        return view('surat-masuk.edit', compact('suratMasuk', 'bidangs'));
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $request->validate([
            'jenis_surat' => ['required', 'string', JenisSurat::validationRules()],
            'no_agenda' => 'nullable|string|max:50',
            'no_surat' => 'nullable|string|max:100',
            'tanggal_surat' => 'nullable|date',
            'tanggal_terima' => 'nullable|date',
            'pengirim' => 'nullable|string|max:150',
            'perihal' => 'nullable|string|max:255',
            'file_surat' => 'nullable|array|max:10',
            'file_surat.*' => 'file|mimes:pdf,jpg,jpeg,png,webp,gif|mimetypes:application/pdf,image/jpeg,image/png,image/webp,image/gif|max:5120',
            'bidang_tujuan_ids' => 'nullable|array',
            'bidang_tujuan_ids.*' => 'exists:bidangs,id',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_arsips,id',
            'status_disposisi' => ['required', StatusDisposisi::validationRules()],
        ]);

        try {
            $this->service->update($request, $suratMasuk);

            return redirect()
                ->route('surat-masuk.index', $this->indexRedirectParams($request, $suratMasuk->jenis_surat))
                ->with('success', $suratMasuk->jenis_surat.' berhasil diperbarui.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->withErrors(['no_agenda' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, SuratMasuk $suratMasuk)
    {
        foreach ($suratMasuk->file_surat as $path) {
            Storage::disk('public')->delete($path);
        }
        $jenis = $suratMasuk->jenis_surat;
        $suratMasuk->delete();

        return redirect()
            ->route('surat-masuk.index', $this->indexRedirectParams($request, $jenis))
            ->with('success', $jenis.' berhasil dihapus.');
    }

    private function indexRedirectParams(Request $request, string $jenis): array
    {
        $params = ['jenis_surat' => $jenis];
        if ($request->filled('tahun')) {
            $params['tahun'] = (int) $request->tahun;
        }

        return $params;
    }

    public function exportExcel(Request $request)
    {
        $jenis = $request->input('jenis_surat') ?: JenisSurat::SURAT_MASUK->value;
        $data = collect();
        $this->buildFilterQuery($request)->chunk(500, function ($chunk) use (&$data) {
            $chunk->loadMissing('bidangTujuan');
            foreach ($chunk as $row) {
                $data->push($row);
            }
        });
        $periodeStr = SuratService::getPeriodeString($request->start_date, $request->end_date);
        $filename = $jenis.($periodeStr ? ' '.$periodeStr : '').'.xlsx';

        return Excel::download(new SuratMasukExport($data), $filename);
    }

    public function exportPdf(Request $request)
    {
        $jenis = $request->input('jenis_surat') ?: JenisSurat::SURAT_MASUK->value;
        $title = 'Laporan Data '.$jenis;
        $periodeStr = SuratService::getPeriodeString($request->start_date, $request->end_date);
        $filename = $jenis.($periodeStr ? ' '.$periodeStr : '').'.pdf';

        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $htmlSegments = [];
        $query = $this->buildFilterQuery($request);
        $isFirstChunk = true;
        $query->chunk(150, function ($chunk) use (&$htmlSegments, $title, &$isFirstChunk) {
            $chunk->loadMissing('bidangTujuans');
            $htmlSegments[] = view('surat-masuk.pdf', [
                'suratMasuks' => $chunk,
                'title' => $title,
                'showHeader' => $isFirstChunk,
            ])->render();
            $isFirstChunk = false;
            unset($chunk);
        });

        return $this->downloadMergedPdf($filename, $htmlSegments);
    }

    private function buildFilterQuery(Request $request)
    {
        $query = SuratMasuk::with(['bidangTujuan', 'klasifikasi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                    ->orWhere('no_agenda', 'like', "%{$search}%")
                    ->orWhere('pengirim', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_disposisi', $request->status);
        }

        if ($request->filled('jenis_surat')) {
            $query->where('jenis_surat', $request->jenis_surat);
        }

        if ($request->filled('tahun')) {
            $tahun = (int) $request->tahun;
            $query->where('tahun', $tahun);
        }

        if ($request->filled('filter_type') && $request->filter_type !== 'semua') {
            $type = $request->filter_type;
            if ($type === 'bulan') {
                $month = str_pad($request->filter_bulan, 2, '0', STR_PAD_LEFT);
                $year = $request->filter_tahun;
                if ($month && $year) {
                    $start = "{$year}-{$month}-01";
                    $end = date('Y-m-t', strtotime($start));
                    $query->whereBetween('tanggal_terima', [$start, $end]);
                    $request->merge(['start_date' => $start, 'end_date' => $end]);
                }
            } elseif ($type === 'tahun') {
                $year = $request->filter_tahun_only;
                if ($year) {
                    $start = "{$year}-01-01";
                    $end = "{$year}-12-31";
                    $query->whereBetween('tanggal_terima', [$start, $end]);
                    $request->merge(['start_date' => $start, 'end_date' => $end]);
                }
            } elseif ($type === 'rentang') {
                if ($request->filled('filter_start_date') && $request->filled('filter_end_date')) {
                    $query->whereBetween('tanggal_terima', [$request->filter_start_date, $request->filter_end_date]);
                    $request->merge(['start_date' => $request->filter_start_date, 'end_date' => $request->filter_end_date]);
                }
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_terima', [$request->start_date, $request->end_date]);
        }

        return $query->orderByRaw('CAST(no_agenda AS UNSIGNED) DESC, no_agenda DESC');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx|max:10240',
            'jenis_surat' => 'nullable|string',
            'tahun' => 'nullable|integer|min:2020|max:2030',
        ]);

        $jenisSurat = $request->input('jenis_surat') ?: JenisSurat::SURAT_MASUK->value;
        $tahun = $request->filled('tahun') ? (int) $request->tahun : null;

        try {
            Excel::import(new SuratMasukImport($jenisSurat, $tahun), $request->file('file_excel'));

            return redirect()
                ->route('surat-masuk.index', ['jenis_surat' => $jenisSurat])
                ->with('success', 'Data '.$jenisSurat.' berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()
                ->route('surat-masuk.index', ['jenis_surat' => $jenisSurat])
                ->with('error', 'Gagal mengimpor data. Pastikan format sesuai template. Error: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new SuratMasukExport(collect(), 'Template Surat Masuk'),
            'Template_Import_Surat_Masuk.xlsx'
        );
    }
}
