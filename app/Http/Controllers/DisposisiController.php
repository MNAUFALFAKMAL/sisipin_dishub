<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Http\Requests\StoreDisposisiRequest;
use App\Http\Requests\UpdateDisposisiRequest;
use App\Http\Requests\ProsesDisposisiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisposisiController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Disposisi::class);

        $query = Disposisi::with(['suratMasuk', 'dariUser', 'kepadaUser', 'kepadaBidang'])
            ->orderByDesc('tanggal_disposisi');

        // Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('prioritas')) {
            $query->where('prioritas', $request->prioritas);
        }

        if ($request->filled('user_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('dari_user_id', $request->user_id)
                  ->orWhere('kepada_user_id', $request->user_id);
            });
        }

        if ($request->filled('bidang_id')) {
            $query->where('kepada_bidang_id', $request->bidang_id);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_disposisi', [$request->start_date, $request->end_date]);
        }

        $disposisis = $query->paginate(15)->withQueryString();

        $users = User::whereHas('peran', function ($q) {
            $q->whereIn('slug', ['super_admin', 'tata_usaha', 'kepala_dinas', 'kepala_bidang']);
        })->orderBy('name')->get();

        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('disposisi.index', compact('disposisis', 'users', 'bidangs'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Disposisi::class);

        $suratMasukId = $request->input('surat_masuk_id');
        $suratMasuk = null;

        if ($suratMasukId) {
            $suratMasuk = SuratMasuk::findOrFail($suratMasukId);
            $this->authorize('disposisi', $suratMasuk);
        }

        $users = User::whereHas('peran', function ($q) {
            $q->whereIn('slug', ['tata_usaha', 'kepala_dinas', 'kepala_bidang', 'staff']);
        })->orderBy('name')->get();

        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('disposisi.create', compact('suratMasuk', 'users', 'bidangs'));
    }

    public function store(StoreDisposisiRequest $request)
    {
        $this->authorize('create', Disposisi::class);

        $suratMasuk = SuratMasuk::findOrFail($request->surat_masuk_id);
        $this->authorize('disposisi', $suratMasuk);

        $disposisi = Disposisi::create([
            'surat_masuk_id'  => $request->surat_masuk_id,
            'dari_user_id'    => Auth::id(),
            'kepada_user_id'  => $request->kepada_user_id,
            'kepada_bidang_id'=> $request->kepada_bidang_id,
            'instruksi'       => $request->instruksi,
            'prioritas'       => $request->prioritas,
            'sifat'           => $request->sifat,
            'batas_waktu'     => $request->batas_waktu,
            'catatan'         => $request->catatan,
            'status'          => 'baru',
        ]);

        // Update status surat masuk
        $suratMasuk->update(['status_disposisi' => 'disposisi']);

        return redirect()
            ->route('disposisi.show', $disposisi)
            ->with('success', 'Disposisi berhasil dibuat. Surat masuk status diperbarui menjadi "Disposisi".');
    }

    public function show(Disposisi $disposisi)
    {
        $this->authorize('view', $disposisi);

        $disposisi->load(['suratMasuk', 'dariUser', 'kepadaUser', 'kepadaBidang']);

        return view('disposisi.show', compact('disposisi'));
    }

    public function edit(Disposisi $disposisi)
    {
        $this->authorize('update', $disposisi);

        $users = User::whereHas('peran', function ($q) {
            $q->whereIn('slug', ['tata_usaha', 'kepala_dinas', 'kepala_bidang', 'staff']);
        })->orderBy('name')->get();

        $bidangs = Bidang::orderBy('nama_bidang')->get();

        return view('disposisi.edit', compact('disposisi', 'users', 'bidangs'));
    }

    public function update(UpdateDisposisiRequest $request, Disposisi $disposisi)
    {
        $this->authorize('update', $disposisi);

        $disposisi->update($request->only([
            'kepada_user_id',
            'kepada_bidang_id',
            'instruksi',
            'prioritas',
            'sifat',
            'batas_waktu',
            'catatan',
        ]));

        return redirect()
            ->route('disposisi.show', $disposisi)
            ->with('success', 'Disposisi berhasil diperbarui.');
    }

    public function proses(ProsesDisposisiRequest $request, Disposisi $disposisi)
    {
        $this->authorize('proses', $disposisi);

        $status = $request->status_disposisi;

        if ($status === 'diproses') {
            $disposisi->markAsProcessing();
        } elseif ($status === 'selesai') {
            $disposisi->markAsCompleted($request->catatan_hasil);
        } elseif ($status === 'ditolak') {
            $disposisi->markAsRejected($request->catatan_hasil);
        }

        // Update status surat masuk based on all its disposisi
        $this->updateSuratMasukStatus($disposisi->surat_masuk_id);

        return redirect()
            ->route('disposisi.show', $disposisi)
            ->with('success', 'Disposisi berhasil diproses dengan status: ' . ucfirst($status) . '.');
    }

    public function destroy(Disposisi $disposisi)
    {
        $this->authorize('delete', $disposisi);

        $suratMasukId = $disposisi->surat_masuk_id;
        $disposisi->delete();

        // Update status surat masuk if no more disposisi
        $this->updateSuratMasukStatus($suratMasukId);

        return redirect()
            ->route('disposisi.index')
            ->with('success', 'Disposisi berhasil dihapus.');
    }

    private function updateSuratMasukStatus(int $suratMasukId): void
    {
        $suratMasuk = SuratMasuk::find($suratMasukId);
        if (!$suratMasuk) {
            return;
        }

        $disposisis = $suratMasuk->disposisis;

        if ($disposisis->isEmpty()) {
            $suratMasuk->update(['status_disposisi' => 'belum']);
            return;
        }

        $allCompleted = $disposisis->every(fn($d) => $d->status === 'selesai');
        $allRejected = $disposisis->every(fn($d) => $d->status === 'ditolak');
        $anyProcessing = $disposisis->contains(fn($d) => $d->status === 'diproses');
        $anyRead = $disposisis->contains(fn($d) => $d->status === 'dibaca');

        if ($allCompleted) {
            $suratMasuk->update(['status_disposisi' => 'selesai']);
        } elseif ($allRejected) {
            $suratMasuk->update(['status_disposisi' => 'ditolak']);
        } elseif ($anyProcessing) {
            $suratMasuk->update(['status_disposisi' => 'diproses']);
        } elseif ($anyRead) {
            $suratMasuk->update(['status_disposisi' => 'dibaca']);
        } else {
            $suratMasuk->update(['status_disposisi' => 'disposisi']);
        }
    }
}