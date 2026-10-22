<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\KlasifikasiArsip;
use App\Models\SuratMasuk;
use App\Models\TahunArsip;
use App\Http\Requests\StoreSuratMasukRequest;
use App\Http\Requests\UpdateSuratMasukRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuratMasukExport;
use PDF;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', SuratMasuk::class);

        $jenisSurat = $request->input('jenis_surat', 'Surat Masuk');
        $tahun = $request->input('tahun', date('Y'));

        $query = $this->buildFilterQuery($request, $jenisSurat, $tahun);
        $suratMasuks = $query->paginate(20)->withQueryString();

        $tahunList = TahunArsip::orderByDesc('tahun')->pluck('tahun');
        $statusList = ['belum', 'diterima', 'disposisi', 'diproses', 'selesai', 'ditolak'];
        $bidangList = \App\Models\Bidang::orderBy('nama_bidang')->get(['id', 'nama_bidang', 'kode_bidang']);
        $klasifikasiList = \App\Models\KlasifikasiArsip::whereNull('parent_id')->orderBy('kode')->get(['id', 'kode', 'nama']);

        return view('surat-masuk.index', compact(
            'suratMasuks',
            'jenisSurat',
            'tahun',
            'tahunList',
            'statusList',
            'bidangList',
            'klasifikasiList'
        ));
    }

    public function search(Request $request)
    {
        $this->authorize('viewAny', SuratMasuk::class);

        $jenisSurat = $request->input('jenis_surat', 'Surat Masuk');
        $tahun = $request->input('tahun', date('Y'));

        $query = $this->buildFilterQuery($request, $jenisSurat, $tahun);

        // For AJAX search, limit to 50 results
        $suratMasuks = $query->limit(50)->get(['id', 'no_agenda', 'no_surat', 'tanggal_surat', 'tanggal_terima', 'pengirim', 'perihal', 'status_disposisi', 'tahun', 'jenis_surat']);

        return response()->json([
            'data' => $suratMasuks->map(function ($surat) {
                return [
                    'id' => $surat->id,
                    'no_agenda' => $surat->no_agenda,
                    'no_surat' => $surat->no_surat,
                    'tanggal_surat' => $surat->tanggal_surat?->format('d/m/Y'),
                    'tanggal_terima' => $surat->tanggal_terima?->format('d/m/Y'),
                    'pengirim' => $surat->pengirim,
                    'perihal' => $surat->perihal,
                    'status_disposisi' => $surat->status_disposisi,
                    'tahun' => $surat->tahun,
                    'jenis_surat' => $surat->jenis_surat,
                    'url_show' => route('surat-masuk.show', $surat->id),
                    'url_edit' => route('surat-masuk.edit', $surat->id),
                ];
            }),
            'total' => $query->count(),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', SuratMasuk::class);

        $jenisSurat = $request->input('jenis_surat', 'Surat Masuk');
        $tahun = $request->input('tahun', date('Y'));

        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $klasifikasiList = KlasifikasiArsip::orderBy('kode')->get(['id', 'kode', 'nama']);
        $suggestedNoAgenda = $this->getNextAgenda($jenisSurat, $tahun);

        return view('surat-masuk.create', compact(
            'bidangs',
            'klasifikasiList',
            'suggestedNoAgenda',
            'jenisSurat',
            'tahun'
        ));
    }

    public function store(StoreSuratMasukRequest $request)
    {
        $this->authorize('create', SuratMasuk::class);

        $filePaths = $this->handleFileUpload($request, 'surat-masuk');

        $suratMasuk = SuratMasuk::create([
            'no_agenda'       => $request->no_agenda,
            'tahun'           => $request->tahun,
            'jenis_surat'     => $request->jenis_surat,
            'no_surat'        => $request->no_surat,
            'tanggal_surat'   => $request->tanggal_surat,
            'tanggal_terima'  => $request->tanggal_terima,
            'pengirim'        => $request->pengirim,
            'perihal'         => $request->perihal,
            'isi_ringkas'     => $request->isi_ringkas,
            'klasifikasi_id'  => $request->klasifikasi_id,
            'bidang_tujuan_id'=> $request->bidang_tujuan_id,
            'file_surat'      => $filePaths,
            'diinput_oleh'    => Auth::id(),
            'keterangan'      => $request->keterangan,
            'status_disposisi'=> 'belum',
        ]);

        // Handle bidang tujuan multiple (pivot table)
        if ($request->filled('bidang_tujuan_ids')) {
            $suratMasuk->bidangTujuans()->sync($request->bidang_tujuan_ids);
        }

        return redirect()
            ->route('surat-masuk.index', ['jenis_surat' => $request->jenis_surat, 'tahun' => $request->tahun])
            ->with('success', $request->jenis_surat . ' berhasil ditambahkan.');
    }

    public function show(SuratMasuk $suratMasuk)
    {
        $this->authorize('view', $suratMasuk);

        $suratMasuk->load(['klasifikasi', 'bidangTujuan', 'bidangTujuans', 'diinputOleh', 'disposisis.dariUser', 'disposisis.kepadaUser', 'disposisis.kepadaBidang']);

        return view('surat-masuk.show', compact('suratMasuk'));
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        $this->authorize('update', $suratMasuk);

        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $klasifikasiList = KlasifikasiArsip::orderBy('kode')->get(['id', 'kode', 'nama']);

        return view('surat-masuk.edit', compact('suratMasuk', 'bidangs', 'klasifikasiList'));
    }

    public function update(UpdateSuratMasukRequest $request, SuratMasuk $suratMasuk)
    {
        $this->authorize('update', $suratMasuk);

        $filePaths = $suratMasuk->file_surat; // Keep existing files

        // Handle file deletions
        if ($request->filled('hapus_file')) {
            foreach ($request->hapus_file as $path) {
                if (in_array($path, $filePaths)) {
                    Storage::disk('public')->delete($path);
                    $filePaths = array_values(array_filter($filePaths, fn($p) => $p !== $path));
                }
            }
        }

        // Handle new file uploads
        $newFiles = $this->handleFileUpload($request, 'surat-masuk');
        $filePaths = array_merge($filePaths, $newFiles);

        $suratMasuk->update([
            'no_agenda'       => $request->no_agenda,
            'tahun'           => $request->tahun,
            'jenis_surat'     => $request->jenis_surat,
            'no_surat'        => $request->no_surat,
            'tanggal_surat'   => $request->tanggal_surat,
            'tanggal_terima'  => $request->tanggal_terima,
            'pengirim'        => $request->pengirim,
            'perihal'         => $request->perihal,
            'isi_ringkas'     => $request->isi_ringkas,
            'klasifikasi_id'  => $request->klasifikasi_id,
            'bidang_tujuan_id'=> $request->bidang_tujuan_id,
            'file_surat'      => $filePaths,
            'keterangan'      => $request->keterangan,
        ]);

        // Handle bidang tujuan multiple (pivot table)
        if ($request->filled('bidang_tujuan_ids')) {
            $suratMasuk->bidangTujuans()->sync($request->bidang_tujuan_ids);
        } else {
            $suratMasuk->bidangTujuans()->detach();
        }

        return redirect()
            ->route('surat-masuk.index', ['jenis_surat' => $request->jenis_surat, 'tahun' => $request->tahun])
            ->with('success', $request->jenis_surat . ' berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        $this->authorize('delete', $suratMasuk);

        // Delete files
        foreach ($suratMasuk->file_surat as $path) {
            Storage::disk('public')->delete($path);
        }

        $jenis = $suratMasuk->jenis_surat;
        $tahun = $suratMasuk->tahun;
        $suratMasuk->delete();

        return redirect()
            ->route('surat-masuk.index', ['jenis_surat' => $jenis, 'tahun' => $tahun])
            ->with('success', $jenis . ' berhasil dihapus.');
    }

    public function disposisi(SuratMasuk $suratMasuk)
    {
        $this->authorize('disposisi', $suratMasuk);

        return redirect()->route('disposisi.create', ['surat_masuk_id' => $suratMasuk->id]);
    }

    public function exportExcel(Request $request)
    {
        $jenisSurat = $request->input('jenis_surat', 'Surat Masuk');
        $tahun = $request->input('tahun', date('Y'));

        $query = $this->buildFilterQuery($request, $jenisSurat, $tahun);
        $data = collect();
        $query->chunk(500, function ($chunk) use (&$data) {
            $chunk->loadMissing('bidangTujuan', 'klasifikasi');
            foreach ($chunk as $row) {
                $data->push($row);
            }
        });

        $filename = $jenisSurat . '_' . $tahun . '.xlsx';

        return Excel::download(new SuratMasukExport($data), $filename);
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new SuratMasukExport(collect(), 'Template Surat Masuk'),
            'Template_Import_Surat_Masuk.xlsx'
        );
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx|max:10240',
            'jenis_surat' => 'nullable|string',
            'tahun' => 'nullable|integer|min:2020|max:2030',
        ]);

        $jenisSurat = $request->input('jenis_surat', 'Surat Masuk');
        $tahun = $request->filled('tahun') ? (int) $request->tahun : null;

        try {
            Excel::import(new \App\Imports\SuratMasukImport($jenisSurat, $tahun), $request->file('file_excel'));

            return redirect()
                ->route('surat-masuk.index', ['jenis_surat' => $jenisSurat])
                ->with('success', 'Data ' . $jenisSurat . ' berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()
                ->route('surat-masuk.index', ['jenis_surat' => $jenisSurat])
                ->with('error', 'Gagal mengimpor data. Pastikan format sesuai template. Error: ' . $e->getMessage());
        }
    }

    private function buildFilterQuery(Request $request, string $jenisSurat, string $tahun)
    {
        $query = SuratMasuk::with(['klasifikasi', 'bidangTujuan', 'bidangTujuans', 'diinputOleh'])
            ->where('jenis_surat', $jenisSurat)
            ->where('tahun', $tahun);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('no_agenda', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status_disposisi', $request->status);
        }

        if ($request->filled('bidang_id')) {
            $query->where('bidang_tujuan_id', $request->bidang_id);
        }

        if ($request->filled('klasifikasi_id')) {
            $query->where('klasifikasi_id', $request->klasifikasi_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_terima', [$request->start_date, $request->end_date]);
        }

        return $query->orderByRaw('CAST(no_agenda AS UNSIGNED) DESC, no_agenda DESC');
    }

    private function getNextAgenda(string $jenisSurat, int $tahun): int
    {
        $last = SuratMasuk::where('jenis_surat', $jenisSurat)
            ->where('tahun', $tahun)
            ->whereNotNull('no_agenda')
            ->orderByRaw('CAST(no_agenda AS UNSIGNED) DESC')
            ->value('no_agenda');

        return $last ? (int) $last + 1 : 1;
    }

    private function handleFileUpload(Request $request, string $folder): array
    {
        if (!$request->hasFile('file_surat')) {
            return [];
        }

        $files = $request->file('file_surat');
        $paths = [];
        $year = $request->input('tahun', date('Y'));

        foreach ($files as $file) {
            $hash = Str::random(40);
            $ext = $file->getClientOriginalExtension();
            $filename = $hash . '.' . $ext;
            $path = $file->storeAs("private/{$folder}/{$year}", $filename, 'public');
            $paths[] = $path;
        }

        return $paths;
    }

    public function exportPdf(SuratMasuk $suratMasuk)
    {
        $this->authorize('view', $suratMasuk);

        $suratMasuk->load(['klasifikasi', 'bidangTujuan', 'bidangTujuans', 'diinputOleh', 'disposisis.dariUser', 'disposisis.kepadaUser', 'disposisis.kepadaBidang']);

        $pdf = PDF::loadView('surat-masuk.pdf', compact('suratMasuk'));

        return $pdf->download('Surat_Masuk_' . $suratMasuk->no_agenda . '_' . $suratMasuk->tahun . '.pdf');
    }
}