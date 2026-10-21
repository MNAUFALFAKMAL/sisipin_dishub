<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\TahunArsip;
use Illuminate\Http\Request;

class TahunArsipController extends Controller
{
    /**
     * Simpan tahun arsip baru agar muncul di dropdown navigasi sidebar.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'between:2000,2100', 'unique:tahun_arsips,tahun'],
        ], [
            'tahun.required' => 'Tahun wajib diisi.',
            'tahun.integer'   => 'Tahun harus berupa angka.',
            'tahun.between'   => 'Tahun harus antara 2000 dan 2100.',
            'tahun.unique'    => 'Tahun tersebut sudah tersedia.',
        ]);

        TahunArsip::create(['tahun' => (int) $validated['tahun']]);

        return back()->with('success', "Tahun {$validated['tahun']} berhasil ditambahkan.")
                     ->with('tahun_arsip_baru', (int) $validated['tahun']);
    }

    /**
     * Hapus tahun arsip dari navigasi sidebar.
     * Ditolak bila masih ada surat masuk/keluar yang tercatat pada tahun tersebut.
     */
    public function destroy(string $tahun)
    {
        $tahun = (int) $tahun;
        $arsip = TahunArsip::where('tahun', $tahun)->first();

        if (! $arsip) {
            return back()->with('error', "Tahun {$tahun} tidak ditemukan.");
        }

        $jumlahMasuk = SuratMasuk::where('tahun', $tahun)->count();
        $jumlahKeluar = SuratKeluar::where('tahun', $tahun)->count();

        if ($jumlahMasuk > 0 || $jumlahKeluar > 0) {
            return back()->with('error', "Tahun {$tahun} tidak dapat dihapus karena masih memiliki {$jumlahMasuk} surat masuk dan {$jumlahKeluar} surat keluar.");
        }

        $arsip->delete();

        return back()->with('success', "Tahun {$tahun} berhasil dihapus dari navigasi.");
    }
}
