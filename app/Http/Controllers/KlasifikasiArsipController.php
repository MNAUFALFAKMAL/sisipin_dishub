<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiArsip;
use Illuminate\Http\Request;

class KlasifikasiArsipController extends Controller
{
    public function publicIndex(Request $request)
    {
        $dbError = false;

        try {
            $query = KlasifikasiArsip::with('parent');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('kode', 'like', "%{$search}%")
                      ->orWhere('nama', 'like', "%{$search}%");
                });
            }

            $klasifikasis = $query->orderBy('kode')->paginate(10);
        } catch (\Exception $e) {
            // DB tidak tersedia — tampilkan halaman kosong (bukan 500)
            $klasifikasis = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);
            $dbError      = true;
        }

        return view('klasifikasi-arsip.public', compact('klasifikasis', 'dbError'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KlasifikasiArsip::with('parent');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $klasifikasis = $query->orderBy('kode')->paginate(10);
        return view('klasifikasi-arsip.index', compact('klasifikasis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = KlasifikasiArsip::orderBy('kode')->get();
        return view('klasifikasi-arsip.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:klasifikasi_arsips,kode',
            'nama' => 'required|string',
            'parent_id' => 'nullable|exists:klasifikasi_arsips,id',
            'retensi_aktif_tahun' => 'required|integer|min:0',
            'retensi_inaktif_tahun' => 'required|integer|min:0',
            'nasib_akhir' => 'required|in:musnah,permanen,dinilai',
            'keamanan' => 'nullable|string|max:255',
            'hak_akses' => 'nullable|string|max:255',
            'dasar_pertimbangan' => 'nullable|string',
            'unit_pengolah' => 'nullable|string|max:255',
        ]);

        KlasifikasiArsip::create($validated);

        return redirect()->route('klasifikasi-arsip.index')
                         ->with('success', 'Pola Klasifikasi Arsip berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KlasifikasiArsip $klasifikasiArsip)
    {
        return view('klasifikasi-arsip.show', compact('klasifikasiArsip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KlasifikasiArsip $klasifikasiArsip)
    {
        $parents = KlasifikasiArsip::where('id', '!=', $klasifikasiArsip->id)
                                    ->orderBy('kode')
                                    ->get();
        return view('klasifikasi-arsip.edit', compact('klasifikasiArsip', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KlasifikasiArsip $klasifikasiArsip)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:klasifikasi_arsips,kode,' . $klasifikasiArsip->id,
            'nama' => 'required|string',
            'parent_id' => 'nullable|exists:klasifikasi_arsips,id',
            'retensi_aktif_tahun' => 'required|integer|min:0',
            'retensi_inaktif_tahun' => 'required|integer|min:0',
            'nasib_akhir' => 'required|in:musnah,permanen,dinilai',
            'keamanan' => 'nullable|string|max:255',
            'hak_akses' => 'nullable|string|max:255',
            'dasar_pertimbangan' => 'nullable|string',
            'unit_pengolah' => 'nullable|string|max:255',
        ]);

        $klasifikasiArsip->update($validated);

        return redirect()->route('klasifikasi-arsip.index')
                         ->with('success', 'Pola Klasifikasi Arsip berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KlasifikasiArsip $klasifikasiArsip)
    {
        $klasifikasiArsip->delete();

        return redirect()->route('klasifikasi-arsip.index')
                         ->with('success', 'Pola Klasifikasi Arsip berhasil dihapus.');
    }

    /**
     * API for TomSelect AJAX Search
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $klasifikasis = KlasifikasiArsip::query();
        
        if ($query) {
            $klasifikasis->where('kode', 'like', "%{$query}%")
                         ->orWhere('nama', 'like', "%{$query}%");
        }
        
        $data = $klasifikasis->limit(200)->get(['id', 'kode', 'nama']);

        // Urutkan natural per-segmen numerik: 000.1.2 sebelum 000.1.10, 000.1 sebelum 000.1.1
        $data = $data->values()->sort(function ($a, $b) {
            $segA = explode('.', $a->kode);
            $segB = explode('.', $b->kode);
            $len = max(count($segA), count($segB));
            for ($i = 0; $i < $len; $i++) {
                $hasA = isset($segA[$i]);
                $hasB = isset($segB[$i]);
                if (!$hasA && $hasB) return -1; // A induk (lebih pendek) -> lebih dulu
                if ($hasA && !$hasB) return 1;  // B induk -> lebih dulu
                $na = (int)$segA[$i];
                $nb = (int)$segB[$i];
                if ($na !== $nb) return $na <=> $nb;
            }
            return 0;
        })->take(50);

        $results = $data->map(function ($item) {
            // kunci sort: tiap segmen di-pad 5 digit agar urut natural via string comparison
            $sortKey = collect(explode('.', $item->kode))
                ->map(fn ($seg) => str_pad($seg, 5, '0', STR_PAD_LEFT))
                ->implode('_');

            return [
                'value' => $item->id,
                'text' => $item->kode . ' - ' . $item->nama,
                'sort' => $sortKey,
            ];
        });

        return response()->json($results);
    }
}
