<?php

namespace App\Http\Controllers;

use App\Enums\JenisSurat;
use App\Exports\SuratKeluarExport;
use App\Imports\SuratKeluarImport;
use App\Models\Bidang;
use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Services\SuratKeluarService;
use App\Services\SuratService;
use App\Traits\MergesPdfChunks;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SuratKeluarController extends Controller
{
    use MergesPdfChunks;

    protected $service;

    public function __construct(SuratKeluarService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if (! $request->has('jenis_surat')) {
            return redirect()->route('surat-keluar.index', array_merge($request->query(), ['jenis_surat' => JenisSurat::SURAT_KELUAR->value]));
        }
        if (! $request->has('tahun')) {
            return redirect()->route('surat-keluar.index', array_merge($request->query(), ['tahun' => date('Y')]));
        }
        $query = $this->buildFilterQuery($request);
        $suratKeluars = $query->paginate(10);

        return view('surat-keluar.index', compact('suratKeluars'));
    }

    public function create(Request $request)
    {
        $bidangs = Bidang::all();
        $daftarPegawai = $this->pegawaiOptions();
        $jenisSurat = $request->input('jenis_surat_active') ?: JenisSurat::SURAT_KELUAR->value;
        $tahun = $request->filled('tahun') ? (int) $request->tahun : (int) date('Y');
        $tahunAktif = $tahun;

        $year = $tahun;
        $lastSurat = SuratKeluar::where('jenis_surat', $jenisSurat)
            ->where('tahun', $year)
            ->orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastSurat && $lastSurat->no_surat) {
            $parts = explode('/', $lastSurat->no_surat);
            if (isset($parts[1]) && is_numeric($parts[1])) {
                $nextNumber = (int) $parts[1] + 1;
            } else {
                $nextNumber = SuratKeluar::where('jenis_surat', $jenisSurat)
                    ->where('tahun', $year)
                    ->count() + 1;
            }
        }
        $suggestedNoSurat = '800/'.$nextNumber.'/V.13/'.$tahun;

        $suggestedNoAgenda = $this->service->getNextAgenda($jenisSurat, $tahun);

        return view('surat-keluar.create', compact('bidangs', 'suggestedNoSurat', 'suggestedNoAgenda', 'daftarPegawai', 'tahunAktif'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_surat' => ['required', 'string', JenisSurat::validationRules()],
            'no_agenda' => 'nullable|string|max:50',
            'no_surat' => 'nullable|string|max:100',
            'tanggal_surat' => 'nullable|date',
            'tanggal_terima' => 'nullable|date',
            'tujuan' => 'nullable|string|max:500',
            'perihal' => 'nullable|string|max:255',
            'file_surat' => 'nullable|array|max:10',
            'file_surat.*' => 'file|mimes:pdf,jpg,jpeg,png,webp,gif|mimetypes:application/pdf,image/jpeg,image/png,image/webp,image/gif|max:5120',
            'bidang_ids' => 'nullable|array',
            'bidang_ids.*' => 'exists:bidangs,id',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_arsips,id',
        ]);

        try {
            $suratKeluar = $this->service->store($request);

            return redirect()
                ->route('surat-keluar.index', $this->indexRedirectParams($request, $suratKeluar->jenis_surat))
                ->with('success', $suratKeluar->jenis_surat.' berhasil ditambahkan.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->withErrors(['no_agenda' => $e->getMessage()]);
        }
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['bidang', 'dibuatOleh']);

        return view('surat-keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        $bidangs = Bidang::all();
        $daftarPegawai = $this->pegawaiOptions();

        return view('surat-keluar.edit', compact('suratKeluar', 'bidangs', 'daftarPegawai'));
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $request->validate([
            'jenis_surat' => ['required', 'string', JenisSurat::validationRules()],
            'no_agenda' => 'nullable|string|max:50',
            'no_surat' => 'nullable|string|max:100',
            'tanggal_surat' => 'nullable|date',
            'tanggal_terima' => 'nullable|date',
            'tujuan' => 'nullable|string|max:500',
            'perihal' => 'nullable|string|max:255',
            'file_surat' => 'nullable|array|max:10',
            'file_surat.*' => 'file|mimes:pdf,jpg,jpeg,png,webp,gif|mimetypes:application/pdf,image/jpeg,image/png,image/webp,image/gif|max:5120',
            'bidang_ids' => 'nullable|array',
            'bidang_ids.*' => 'exists:bidangs,id',
            'klasifikasi_id' => 'nullable|exists:klasifikasi_arsips,id',
        ]);

        try {
            $this->service->update($request, $suratKeluar);

            return redirect()
                ->route('surat-keluar.index', $this->indexRedirectParams($request, $suratKeluar->jenis_surat))
                ->with('success', $suratKeluar->jenis_surat.' berhasil diperbarui.');
        } catch (\RuntimeException $e) {
            return redirect()->back()->withInput()->withErrors(['no_agenda' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, SuratKeluar $suratKeluar)
    {
        foreach ($suratKeluar->file_surat as $path) {
            Storage::disk('public')->delete($path);
        }
        $jenis = $suratKeluar->jenis_surat;
        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index', $this->indexRedirectParams($request, $jenis))
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
        $jenis = $request->input('jenis_surat') ?: JenisSurat::SURAT_KELUAR->value;
        $data = collect();
        $this->buildFilterQuery($request)->chunk(500, function ($chunk) use (&$data) {
            $chunk->loadMissing('bidangs');
            foreach ($chunk as $row) {
                $data->push($row);
            }
        });
        $periodeStr = SuratService::getPeriodeString($request->start_date, $request->end_date);
        $filename = $jenis.($periodeStr ? ' '.$periodeStr : '').'.xlsx';

        return Excel::download(new SuratKeluarExport($data), $filename);
    }

    public function exportPdf(Request $request)
    {
        $jenis = $request->input('jenis_surat') ?: JenisSurat::SURAT_KELUAR->value;
        $periodeStr = SuratService::getPeriodeString($request->start_date, $request->end_date);
        $title = 'Laporan Data '.$jenis;
        $filename = $jenis.($periodeStr ? ' '.$periodeStr : '').'.pdf';

        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $htmlSegments = [];
        $query = $this->buildFilterQuery($request);
        $isFirstChunk = true;
        $query->chunk(150, function ($chunk) use (&$htmlSegments, $title, &$isFirstChunk) {
            $chunk->loadMissing('bidangs');
            $rowsHtml = '';
            foreach ($chunk as $surat) {
                $rowsHtml .= '<tr>
                    <td>'.e($surat->no_agenda).'</td>
                    <td>'.($surat->tanggal_terima ? Carbon::parse($surat->tanggal_terima)->translatedFormat('d F Y') : '-').'</td>
                    <td>'.e($surat->klasifikasi?->kode ?? '-').'</td>
                    <td>'.e($surat->tujuan ?? '-').'</td>
                    <td>'.e($surat->no_surat ?: '-').'</td>
                    <td>'.($surat->tanggal_surat ? Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') : '-').'</td>
                    <td class="text-left-indent">'.e($surat->perihal ?? '-').'</td>
                    <td>'.e($surat->bidangs->pluck('nama_bidang')->implode(', ') ?: '-').'</td>
                    <td>'.e($surat->keterangan ?: '-').'</td>
                </tr>';
            }
            $htmlSegments[] = view('surat-keluar.pdf', [
                'title' => $title,
                'rowsHtml' => $rowsHtml,
                'showHeader' => $isFirstChunk,
            ])->render();
            $isFirstChunk = false;
            unset($chunk, $rowsHtml);
        });

        return $this->downloadMergedPdf($filename, $htmlSegments);
    }

    private function buildFilterQuery(Request $request)
    {
        $query = SuratKeluar::with(['bidang', 'klasifikasi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                    ->orWhere('no_agenda', 'like', "%{$search}%")
                    ->orWhere('tujuan', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%");
            });
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
                    $query->whereBetween('tanggal_surat', [$start, $end]);
                    $request->merge(['start_date' => $start, 'end_date' => $end]);
                }
            } elseif ($type === 'tahun') {
                $year = $request->filter_tahun_only;
                if ($year) {
                    $start = "{$year}-01-01";
                    $end = "{$year}-12-31";
                    $query->whereBetween('tanggal_surat', [$start, $end]);
                    $request->merge(['start_date' => $start, 'end_date' => $end]);
                }
            } elseif ($type === 'rentang') {
                if ($request->filled('filter_start_date') && $request->filled('filter_end_date')) {
                    $query->whereBetween('tanggal_surat', [$request->filter_start_date, $request->filter_end_date]);
                    $request->merge(['start_date' => $request->filter_start_date, 'end_date' => $request->filter_end_date]);
                }
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_surat', [$request->start_date, $request->end_date]);
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

        $jenisSurat = $request->input('jenis_surat') ?: JenisSurat::SURAT_KELUAR->value;
        $tahun = $request->filled('tahun') ? (int) $request->tahun : null;

        try {
            Excel::import(new SuratKeluarImport($jenisSurat, $tahun), $request->file('file_excel'));

            return redirect()
                ->route('surat-keluar.index', ['jenis_surat' => $jenisSurat])
                ->with('success', 'Data '.$jenisSurat.' berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()
                ->route('surat-keluar.index', ['jenis_surat' => $jenisSurat])
                ->with('error', 'Gagal mengimpor data. Pastikan format sesuai template. Error: '.$e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new SuratKeluarExport(collect(), 'Template Surat Keluar'),
            'Template_Import_Surat_Keluar.xlsx'
        );
    }

    protected function pegawaiOptions(): array
    {
        $pegawai = Pegawai::where('status', 'aktif')
            ->orderBy('unit_bidang')
            ->orderBy('nama')
            ->get(['nama', 'unit_bidang']);

        if ($pegawai->isEmpty()) {
            return collect(config('pegawai_daftar_nama', []))
                ->map(fn (string $nama) => ['nama' => $nama, 'bidang' => 'Tanpa Bidang'])
                ->values()
                ->all();
        }

        return $pegawai->map(fn ($p) => [
            'nama' => $p->nama,
            'bidang' => trim((string) $p->unit_bidang) !== '' ? $p->unit_bidang : 'Tanpa Bidang',
        ])->all();
    }
}
