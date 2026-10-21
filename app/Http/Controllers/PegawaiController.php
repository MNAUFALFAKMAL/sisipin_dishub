<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Http\Requests\StorePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    /**
     * Display a listing of pegawai, grouped/filtered by unit_bidang.
     * Data is sorted per Excel order (bukan alfabetis a-z) untuk "Semua" view,
     * atau alfabetically by nama within the selected bidang.
     * The Excel order array is set to match the DATA_PEGAWAI_9SHEET.XLSX file order.
     */
    public function index(Request $request)
    {
        // Urutan bidang sesuai file Excel DATA_PEGAWAI_9SHEET.XLSX (bukan alfabetis a-z)
        // Order mengikuti urutan first-appearance di data Excel (sheet order):
        // 1. BIDANG TEKNIK SARANA DAN PRASARANA (sheet BIDANG TSP), 2. SEKRETARIAT,
        // 3. BIDANG LALU LINTAS DAN ANGKUTAN, 4. BIDANG PEMBINAAN KESELAMATAN TRANSPORTASI (BINKES),
        // 5. BIDANG PENGEMBANGAN TRANSPORTASI, 6. UPTD PENGELOLA PRASARANA...,
        // 7. UPTD TERMINAL, 8. UPTD KEPELABUHAN
        $excelBidangPrioritas = [
            'BIDANG TEKNIK SARANA DAN PRASARANA',
            'SEKRETARIAT',
            'BIDANG LALU LINTAS DAN ANGKUTAN',
            'BIDANG PEMBINAAN KESELAMATAN TRANSPORTASI',
            'BIDANG PENGEMBANGAN TRANSPORTASI',
            'UPTD PENGELOLA PRASARANA PERHUBUNGAN LALU LINTAS DAN ANGKUTAN JALAN',
            'UPTD TERMINAL',
            'UPTD KEPELABUHAN',
        ];
        $urutanExcel = array_flip($excelBidangPrioritas); // nilai lebih kecil = muncul lebih dulu di Excel

        $query = Pegawai::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', "%{$request->search}%")
                  ->orWhere('nip', 'like', "%{$request->search}%")
                  ->orWhere('jabatan', 'like', "%{$request->search}%")
                  ->orWhere('unit_bidang', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bidang')) {
            $query->where('unit_bidang', $request->bidang)
                  ->orderBy('urutan_excel');
        } else {
            // Semua: urutkan per bidang sesuai Excel, lalu urutan_excel
            $caseSql = 'CASE unit_bidang ';
            foreach ($excelBidangPrioritas as $idx => $bd) {
                $caseSql .= "WHEN '" . addslashes($bd) . "' THEN " . ($idx + 1) . ' ';
            }
            $caseSql .= 'ELSE 99 END';
            $query->orderByRaw($caseSql)->orderBy('urutan_excel');
        }

        $pegawais = $query->paginate(15)->withQueryString();

        $bidangListRaw = Pegawai::query()
            ->whereNotNull('unit_bidang')
            ->where('unit_bidang', '!=', '')
            ->distinct()
            ->pluck('unit_bidang')
            ->all();

        $bidangList = array_values(
            array_filter(
                $bidangListRaw,
                function($bidang) use ($urutanExcel) {
                    return isset($urutanExcel[$bidang]);
                }
            )
        );
        // Urutkan sesuai urutan Excel (index lebih kecil = lebih dulu)
        usort($bidangList, function($a, $b) use ($urutanExcel) {
            return ($urutanExcel[$a] ?? 99) <=> ($urutanExcel[$b] ?? 99);
        });

        $bidangCount = Pegawai::query()
            ->whereNotNull('unit_bidang')
            ->where('unit_bidang', '!=', '')
            ->where('status', 'aktif')
            ->select('unit_bidang', Pegawai::raw('COUNT(*) as total'))
            ->groupBy('unit_bidang')
            ->pluck('total', 'unit_bidang')
            ->all();

        return view('pegawai.index', compact('pegawais', 'bidangList', 'bidangCount', 'request'));
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(StorePegawaiRequest $request)
    {
        Pegawai::create($request->validated());

        return redirect()->route('pegawai.index')
            ->with('success', "Pegawai {$request->nama} berhasil ditambahkan.");
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(UpdatePegawaiRequest $request, Pegawai $pegawai)
    {
        $pegawai->update($request->validated());

        return redirect()->route('pegawai.index')
            ->with('success', "Data pegawai {$pegawai->nama} berhasil diperbarui.");
    }

    /**
     * Nonaktifkan pegawai (soft-deactivate via status).
     */
    public function destroy(Pegawai $pegawai)
    {
        $pegawai->update(['status' => 'tidak_aktif']);

        return redirect()->route('pegawai.index')
            ->with('success', "Pegawai {$pegawai->nama} telah dinonaktifkan.");
    }

    /**
     * Aktifkan kembali pegawai yang dinonaktifkan.
     */
    public function activate(Pegawai $pegawai)
    {
        $pegawai->update(['status' => 'aktif']);

        return back()->with('success', "Pegawai {$pegawai->nama} telah diaktifkan kembali.");
    }

    /**
     * Hapus permanent pegawai dari database.
     */
    public function forceDestroy(Pegawai $pegawai)
    {
        $nama = $pegawai->nama;
        $pegawai->delete();

        return redirect()->route('pegawai.index')
            ->with('success', "Pegawai {$nama} telah dihapus permanent.");
    }

    /**
     * Tampilkan detail pegawai.
     */
    public function show(Pegawai $pegawai)
    {
        return view('pegawai.show', compact('pegawai'));
    }
}
