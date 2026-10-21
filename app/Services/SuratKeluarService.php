<?php

namespace App\Services;

use App\Models\SuratKeluar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SuratKeluarService
{
    protected FileConverterService $fileConverter;

    public function __construct(FileConverterService $fileConverter)
    {
        $this->fileConverter = $fileConverter;
    }

    public function getNextAgenda(string $jenisSurat, ?int $year = null): string
    {
        $year = $year ?? date('Y');
        $lastAgenda = SuratKeluar::where('jenis_surat', $jenisSurat)
            ->where('tahun', $year)
            ->whereRaw('no_agenda REGEXP "^[0-9]+$"')
            ->orderByRaw('CAST(no_agenda AS UNSIGNED) DESC')
            ->first();
        $nextAgenda = $lastAgenda && is_numeric($lastAgenda->no_agenda)
            ? (int) $lastAgenda->no_agenda + 1
            : 1;

        return (string) $nextAgenda;
    }

    public function resolveNoAgenda(?string $noAgendaRaw, string $jenisSurat, ?int $year = null): string
    {
        if (empty($noAgendaRaw)) {
            return $this->getNextAgenda($jenisSurat, $year);
        }

        return is_numeric($noAgendaRaw) ? (string) (int) $noAgendaRaw : $noAgendaRaw;
    }

    public function ensureGapFill(string $noAgendaFormatted, string $jenisSurat, ?int $year = null): void
    {
        if (! is_numeric($noAgendaFormatted)) {
            return;
        }
        $year = $year ?? date('Y');
        $targetAgendaInt = (int) $noAgendaFormatted;
        if ($targetAgendaInt <= 1) {
            return;
        }
        for ($i = 1; $i < $targetAgendaInt; $i++) {
            $placeholderAgenda = (string) $i;
            $exists = SuratKeluar::where('jenis_surat', $jenisSurat)
                ->where('tahun', $year)
                ->where('no_agenda', $placeholderAgenda)
                ->exists();
            if (! $exists) {
                SuratKeluar::create([
                    'no_agenda' => $placeholderAgenda,
                    'jenis_surat' => $jenisSurat,
                    'tahun' => $year,
                    'no_surat' => null,
                    'tanggal_surat' => null,
                    'tanggal_terima' => null,
                    'tujuan' => null,
                    'perihal' => null,
                    'isi_ringkas' => null,
                    'dibuat_oleh' => auth()->id(),
                ]);
            }
        }
    }

    public function findExisting(string $noAgenda, string $jenisSurat, ?int $year = null)
    {
        $year = $year ?? date('Y');

        return SuratKeluar::where('jenis_surat', $jenisSurat)
            ->where('tahun', $year)
            ->where('no_agenda', $noAgenda)
            ->first();
    }

    public function store(Request $request): SuratKeluar
    {
        SuratService::parseTanggal($request);

        $jenisSurat = $request->input('jenis_surat');
        $tanggalTerima = $request->input('tanggal_terima')
            ? Carbon::parse($request->input('tanggal_terima'))
            : Carbon::now();
        $year = $request->filled('tahun')
            ? (int) $request->input('tahun')
            : $tanggalTerima->year;

        $noAgendaFormatted = $this->resolveNoAgenda($request->input('no_agenda'), $jenisSurat, $year);

        $existing = $this->findExisting($noAgendaFormatted, $jenisSurat, $year);
        $isPlaceholder = $existing && SuratService::isPlaceholder($existing);

        if ($existing && ! $isPlaceholder) {
            throw new \RuntimeException('Nomor urut '.$request->input('no_agenda').' sudah digunakan dan memiliki data aktif.');
        }

        $this->ensureGapFill($noAgendaFormatted, $jenisSurat, $year);

        $data = $request->except(['file_surat', 'hapus_file']);
        $data['no_agenda'] = $noAgendaFormatted;
        $data['isi_ringkas'] = $request->perihal;
        $data['tahun'] = $year;
        $data['dibuat_oleh'] = auth()->id();

        if ($request->hasFile('file_surat')) {
            $uploaded = $request->file('file_surat');
            if (is_array($uploaded)) {
                $data['file_surat'] = [$this->fileConverter->mergeToPdf($uploaded, 'surat-keluar')];
            } else {
                $data['file_surat'] = [$this->fileConverter->convertToPdf($uploaded, 'surat-keluar')];
            }
        } else {
            $data['file_surat'] = null;
        }

        $data['bidang_id'] = is_array($request->bidang_ids)
            ? ($request->bidang_ids[0] ?? null)
            : null;

        if ($existing && $isPlaceholder) {
            $existing->update($data);
            $suratKeluar = $existing;
        } else {
            $suratKeluar = SuratKeluar::create($data);
        }

        $suratKeluar->bidangs()->sync($request->bidang_ids ?? []);

        return $suratKeluar;
    }

    public function update(Request $request, SuratKeluar $suratKeluar): SuratKeluar
    {
        SuratService::parseTanggal($request);

        $jenisSurat = $request->input('jenis_surat');
        $tanggalTerima = $request->input('tanggal_terima')
            ? Carbon::parse($request->input('tanggal_terima'))
            : Carbon::now();
        $year = $request->filled('tahun')
            ? (int) $request->input('tahun')
            : ($suratKeluar->tahun ?? $tanggalTerima->year);

        $noAgendaRaw = $request->input('no_agenda');
        $noAgendaFormatted = is_numeric($noAgendaRaw) ? (string) (int) $noAgendaRaw : $noAgendaRaw;
        $oldAgenda = $suratKeluar->no_agenda;

        if ($noAgendaFormatted !== $oldAgenda) {
            $existing = $this->findExisting($noAgendaFormatted, $jenisSurat, $year);
            $isPlaceholder = $existing && SuratService::isPlaceholder($existing);

            if ($existing) {
                if ($isPlaceholder) {
                    $existing->delete();
                } else {
                    throw new \RuntimeException('Nomor urut '.$noAgendaRaw.' sudah digunakan dan memiliki data aktif.');
                }
            }

            $this->ensureGapFill($noAgendaFormatted, $jenisSurat, $year);
        }

        $data = $request->except(['file_surat', 'hapus_file']);
        $data['no_agenda'] = $noAgendaFormatted;
        $data['isi_ringkas'] = $request->perihal;

        // Ambil daftar file saat ini (sudah array via accessor)
        $currentFiles = $suratKeluar->file_surat;

        // Proses hapus_file
        $hapusFileInput = $request->input('hapus_file');
        $filesToDelete = [];

        if ($hapusFileInput !== null) {
            if (is_array($hapusFileInput)) {
                // Hapus hanya path yang benar-benar ada di daftar saat ini
                $filesToDelete = array_intersect($hapusFileInput, $currentFiles);
            } elseif ($hapusFileInput === '1') {
                // Legacy: hapus semua
                $filesToDelete = $currentFiles;
            }

            foreach ($filesToDelete as $path) {
                Storage::disk('public')->delete($path);
            }

            // Sisa file yang tidak dihapus
            $currentFiles = array_values(array_diff($currentFiles, $filesToDelete));
        }

        // Proses file baru (append)
        if ($request->hasFile('file_surat')) {
            $uploaded = $request->file('file_surat');
            $newPath = is_array($uploaded)
                ? $this->fileConverter->mergeToPdf($uploaded, 'surat-keluar')
                : $this->fileConverter->convertToPdf($uploaded, 'surat-keluar');

            $currentFiles[] = $newPath;
        }

        $data['file_surat'] = $currentFiles ?: null;

        $data['bidang_id'] = is_array($request->bidang_ids)
            ? ($request->bidang_ids[0] ?? null)
            : null;

        $suratKeluar->update($data);
        $suratKeluar->bidangs()->sync($request->bidang_ids ?? []);

        if ($noAgendaFormatted !== $oldAgenda) {
            $existsOld = $this->findExisting($oldAgenda, $jenisSurat, $year);
            if (! $existsOld) {
                SuratKeluar::create([
                    'no_agenda' => $oldAgenda,
                    'jenis_surat' => $jenisSurat,
                    'tahun' => $year,
                    'no_surat' => null,
                    'tanggal_surat' => null,
                    'tanggal_terima' => null,
                    'tujuan' => null,
                    'perihal' => null,
                    'isi_ringkas' => null,
                    'dibuat_oleh' => auth()->id(),
                ]);
            }
        }

        return $suratKeluar;
    }
}
