<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\KlasifikasiArsip;
use App\Models\SuratKeluar;
use App\Models\TahunArsip;
use App\Models\Pegawai;
use App\Http\Requests\StoreSuratKeluarRequest;
use App\Http\Requests\UpdateSuratKeluarRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuratKeluarExport;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', SuratKeluar::class);

        $jenisSurat = $request->input('jenis_surat', 'SPT');
        $tahun = $request->input('tahun', date('Y'));

        $query = $this->buildFilterQuery($request, $jenisSurat, $tahun);
        $suratKeluars = $query->paginate(15)->withQueryString();

        $tahunList = TahunArsip::orderByDesc('tahun')->pluck('tahun');

        return view('surat-keluar.index', compact('suratKeluars', 'jenisSurat', 'tahun', 'tahunList'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', SuratKeluar::class);

        $jenisSurat = $request->input('jenis_surat', 'SPT');
        $tahun = $request->input('tahun', date('Y'));

        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $klasifikasiList = KlasifikasiArsip::orderBy('kode')->get(['id', 'kode', 'nama']);
        $suggestedNoAgenda = $this->getNextAgenda($jenisSurat, $tahun);

        return view('surat-keluar.create', compact(
            'bidangs',
            'klasifikasiList',
            'suggestedNoAgenda',
            'jenisSurat',
            'tahun'
        ));
    }

    public function store(StoreSuratKeluarRequest $request)
    {
        $this->authorize('create', SuratKeluar::class);

        $filePaths = $this->handleFileUpload($request, 'surat-keluar');

        $suratKeluar = SuratKeluar::create([
            'no_agenda'       => $request->no_agenda,
            'tahun'           => $request->tahun,
            'jenis_surat'     => $request->jenis_surat,
            'no_surat'        => $request->no_surat,
            'tanggal_surat'   => $request->tanggal_surat,
            'tanggal_terima'  => $request->tanggal_terima,
            'tujuan'          => $request->tujuan,
            'perihal'         => $request->perihal,
            'isi_ringkas'     => $request->isi_ringkas,
            'klasifikasi_id'  => $request->klasifikasi_id,
            'bidang_id'       => $request->bidang_id,
            'penandatangan'   => $request->penandatangan,
            'file_surat'      => $filePaths,
            'dibuat_oleh'     => Auth::id(),
            'keterangan'      => $request->keterangan,
            'indeks_berkas'   => $request->indeks_berkas,
            'tingkat_keamanan'=> $request->tingkat_keamanan,
            'masa_simpan'     => $request->masa_simpan,
            'status'          => 'draft',
        ]);

        return redirect()
            ->route('surat-keluar.index', ['jenis_surat' => $request->jenis_surat, 'tahun' => $request->tahun])
            ->with('success', 'Surat Keluar (' . $request->jenis_surat . ') berhasil ditambahkan.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $this->authorize('view', $suratKeluar);

        $suratKeluar->load(['klasifikasi', 'bidang', 'dibuatOleh']);

        return view('surat-keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        $this->authorize('update', $suratKeluar);

        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $klasifikasiList = KlasifikasiArsip::orderBy('kode')->get(['id', 'kode', 'nama']);

        return view('surat-keluar.edit', compact('suratKeluar', 'bidangs', 'klasifikasiList'));
    }

    public function update(UpdateSuratKeluarRequest $request, SuratKeluar $suratKeluar)
    {
        $this->authorize('update', $suratKeluar);

        $filePaths = $suratKeluar->file_surat; // Keep existing files

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
        $newFiles = $this->handleFileUpload($request, 'surat-keluar');
        $filePaths = array_merge($filePaths, $newFiles);

        $suratKeluar->update([
            'no_agenda'       => $request->no_agenda,
            'tahun'           => $request->tahun,
            'jenis_surat'     => $request->jenis_surat,
            'no_surat'        => $request->no_surat,
            'tanggal_surat'   => $request->tanggal_surat,
            'tanggal_terima'  => $request->tanggal_terima,
            'tujuan'          => $request->tujuan,
            'perihal'         => $request->perihal,
            'isi_ringkas'     => $request->isi_ringkas,
            'klasifikasi_id'  => $request->klasifikasi_id,
            'bidang_id'       => $request->bidang_id,
            'penandatangan'   => $request->penandatangan,
            'file_surat'      => $filePaths,
            'keterangan'      => $request->keterangan,
            'indeks_berkas'   => $request->indeks_berkas,
            'tingkat_keamanan'=> $request->tingkat_keamanan,
            'masa_simpan'     => $request->masa_simpan,
        ]);

        return redirect()
            ->route('surat-keluar.index', ['jenis_surat' => $request->jenis_surat, 'tahun' => $request->tahun])
            ->with('success', 'Surat Keluar (' . $request->jenis_surat . ') berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        $this->authorize('delete', $suratKeluar);

        // Delete files
        foreach ($suratKeluar->file_surat as $path) {
            Storage::disk('public')->delete($path);
        }

        $jenis = $suratKeluar->jenis_surat;
        $tahun = $suratKeluar->tahun;
        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index', ['jenis_surat' => $jenis, 'tahun' => $tahun])
            ->with('success', 'Surat Keluar (' . $jenis . ') berhasil dihapus.');
    }

    public function exportExcel(Request $request)
    {
        $jenisSurat = $request->input('jenis_surat', 'SPT');
        $tahun = $request->input('tahun', date('Y'));

        $query = $this->buildFilterQuery($request, $jenisSurat, $tahun);
        $data = collect();
        $query->chunk(500, function ($chunk) use (&$data) {
            $chunk->loadMissing('bidang', 'klasifikasi');
            foreach ($chunk as $row) {
                $data->push($row);
            }
        });

        $filename = 'Surat_Keluar_' . $jenisSurat . '_' . $tahun . '.xlsx';

        return Excel::download(new SuratKeluarExport($data), $filename);
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new SuratKeluarExport(collect(), 'Template Surat Keluar'),
            'Template_Import_Surat_Keluar.xlsx'
        );
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx|max:10240',
            'jenis_surat' => 'nullable|string',
            'tahun' => 'nullable|integer|min:2020|max:2030',
        ]);

        $jenisSurat = $request->input('jenis_surat', 'SPT');
        $tahun = $request->filled('tahun') ? (int) $request->tahun : null;

        try {
            Excel::import(new \App\Imports\SuratKeluarImport($jenisSurat, $tahun), $request->file('file_excel'));

            return redirect()
                ->route('surat-keluar.index', ['jenis_surat' => $jenisSurat])
                ->with('success', 'Data Surat Keluar (' . $jenisSurat . ') berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()
                ->route('surat-keluar.index', ['jenis_surat' => $jenisSurat])
                ->with('error', 'Gagal mengimpor data. Pastikan format sesuai template. Error: ' . $e->getMessage());
        }
    }

    private function buildFilterQuery(Request $request, string $jenisSurat, string $tahun)
    {
        $query = SuratKeluar::with(['klasifikasi', 'bidang', 'dibuatOleh'])
            ->where('jenis_surat', $jenisSurat)
            ->where('tahun', $tahun);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                  ->orWhere('no_agenda', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bidang_id')) {
            $query->where('bidang_id', $request->bidang_id);
        }

        if ($request->filled('klasifikasi_id')) {
            $query->where('klasifikasi_id', $request->klasifikasi_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_surat', [$request->start_date, $request->end_date]);
        }

        return $query->orderByRaw('CAST(no_agenda AS UNSIGNED) DESC, no_agenda DESC');
    }

    private function getNextAgenda(string $jenisSurat, int $tahun): int
    {
        $last = SuratKeluar::where('jenis_surat', $jenisSurat)
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
}