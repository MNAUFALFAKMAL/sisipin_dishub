<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\TahunArsipController;
use App\Http\Controllers\DashboardKearsipanController;
use App\Http\Controllers\Kp4Controller;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SISIPIN DISHUB — Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ── Public SEO routes (no auth required) ───────────────────────────────────
Route::get('/kode-klasifikasi', [\App\Http\Controllers\KlasifikasiArsipController::class, 'publicIndex'])->name('public.klasifikasi');
Route::get('/tata-naskah', [\App\Http\Controllers\TataNaskahController::class, 'publicIndex'])->name('public.tata-naskah');

Route::get('/sitemap.xml', function () {
    $content = view('seo.sitemap')->render();
    return response($content, 200)->header('Content-Type', 'application/xml');
})->name('sitemap');

Route::get('/robots.txt', function () {
    $content = view('seo.robots')->render();
    return response($content, 200)->header('Content-Type', 'text/plain');
})->name('robots');

// ── Serve File Storage (fallback jika symlink public/storage rusak) ────────
Route::get('/serve-file/{path}', function ($path) {
    // Sanitasi keamanan (P0): tolak path traversal & path absolut
    if ($path === '' || str_contains($path, '..')) {
        abort(404, 'File tidak ditemukan.');
    }

    // path bisa mengandung slash, mis: templates/kop-garuda.docx
    $bases = [
        storage_path('app/public'),   // upload baru, tanpa symlink
        public_path('storage'),       // upload lama atau via symlink
    ];

    $fullPath = null;
    foreach ($bases as $base) {
        $baseReal = realpath($base);
        if ($baseReal === false) {
            continue;
        }
        $candidate = realpath($baseReal . DIRECTORY_SEPARATOR . $path);
        if ($candidate !== false
            && str_starts_with($candidate, rtrim($baseReal, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)) {
            $fullPath = $candidate;
            break;
        }
    }
    if ($fullPath === null || !file_exists($fullPath)) {
        abort(404, 'File tidak ditemukan.');
    }
    // Ekstrak filename untuk Content-Disposition header
    $filename = basename($path);
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mimeTypes = [
        'pdf' => 'application/pdf',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'doc' => 'application/msword',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'xls' => 'application/vnd.ms-excel',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
    ];
    $contentType = $mimeTypes[$extension] ?? mime_content_type($fullPath) ?: 'application/octet-stream';
    $content = file_get_contents($fullPath);
    if ($content === false) {
        abort(500, 'Gagal membaca file.');
    }
    return response($content, 200)->header('Content-Type', $contentType)
        ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
})->where('path', '.*')->name('file.serve');

// ── Dashboard ──────────────────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Role-specific dashboards
    Route::get('/admin', function () {
        return view('dashboards.admin');
    })->name('admin.dashboard')->middleware('role:super_admin');

    Route::get('/tata-usaha', function () {
        return view('dashboards.tata-usaha');
    })->name('tata-usaha.dashboard')->middleware('role:tata_usaha');

    Route::get('/pegawai', function () {
        return view('dashboards.pegawai');
    })->name('pegawai.dashboard')->middleware('role:pegawai');

    Route::get('/kepala-dinas', function () {
        return view('dashboards.kepala-dinas');
    })->name('kepala-dinas.dashboard')->middleware('role:kepala_dinas');

    Route::get('/kepala-bidang', function () {
        return view('dashboards.kepala-bidang');
    })->name('kepala-bidang.dashboard')->middleware('role:kepala_bidang');

    // Default dashboard (fallback)
    Route::get('/dashboard', [DashboardKearsipanController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export/excel', [DashboardKearsipanController::class, 'exportExcel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardKearsipanController::class, 'exportPdf'])->name('dashboard.export.pdf');
    Route::get('/pembelajaran', [DashboardKearsipanController::class, 'pembelajaran'])->name('pembelajaran');

    // ── Profil (bawaan Breeze) ─────────────────────────────────────────────
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── Manajemen Pengguna (Super Admin only) ──────────────────────────────
    Route::middleware(['role:super_admin'])->prefix('pengguna')->name('users.')->group(function () {
        Route::get('/',            [UserController::class, 'index'])->name('index');
        Route::get('/tambah',      [UserController::class, 'create'])->name('create');
        Route::post('/',           [UserController::class, 'store'])->name('store');

        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}',      [UserController::class, 'update'])->name('update');
        Route::delete('/{user}',   [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/activate', [UserController::class, 'activate'])->name('activate');
    });

    // ── Audit Logs (Super Admin only) ─────────────────────────────────────
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // ── Klasifikasi Arsip & Tata Naskah (Data Master SIKD) ──────────────────
    Route::get('klasifikasi-arsip/search', [\App\Http\Controllers\KlasifikasiArsipController::class, 'search'])->name('klasifikasi-arsip.search');
    Route::middleware(['role:super_admin,tata_usaha'])->group(function () {
        Route::resource('klasifikasi-arsip', \App\Http\Controllers\KlasifikasiArsipController::class);
    });

    // ── Data Pegawai (super_admin & tata_usaha) ─────────────────────────────
    Route::middleware(['role:super_admin,tata_usaha'])->group(function () {
        Route::patch('pegawai/{pegawai}/activate', [PegawaiController::class, 'activate'])->name('pegawai.activate');
        Route::delete('pegawai/{pegawai}/force-delete', [PegawaiController::class, 'forceDestroy'])->name('pegawai.force-delete');
        Route::resource('pegawai', PegawaiController::class);
    });

    // ── Modul Surat Menyurat ── (Minggu 2) ────────────────────────────────
    Route::get('surat-masuk/export/template', [SuratMasukController::class, 'downloadTemplate'])->name('surat-masuk.export.template');
    Route::get('surat-masuk/export/excel', [SuratMasukController::class, 'exportExcel'])->name('surat-masuk.export.excel');
    Route::get('surat-masuk/export/pdf', [SuratMasukController::class, 'exportPdf'])->name('surat-masuk.export.pdf');
    Route::post('surat-masuk/import/excel', [SuratMasukController::class, 'importExcel'])->name('surat-masuk.import.excel');
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::post('surat-masuk/{surat_masuk}/disposisi', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');
    Route::resource('disposisi', DisposisiController::class);
    Route::post('disposisi/{disposisi}/proses', [DisposisiController::class, 'proses'])->name('disposisi.proses');
 
    Route::get('surat-keluar/export/template', [SuratKeluarController::class, 'downloadTemplate'])->name('surat-keluar.export.template');
    Route::get('surat-keluar/export/excel', [SuratKeluarController::class, 'exportExcel'])->name('surat-keluar.export.excel');
    Route::get('surat-keluar/export/pdf', [SuratKeluarController::class, 'exportPdf'])->name('surat-keluar.export.pdf');
    Route::post('surat-keluar/import/excel', [SuratKeluarController::class, 'importExcel'])->name('surat-keluar.import.excel');
    Route::resource('surat-keluar', SuratKeluarController::class);

    // Tahun Arsip (dropdown navigasi sidebar)
    Route::post('tahun-arsip', [TahunArsipController::class, 'store'])->name('tahun-arsip.store');
    Route::delete('tahun-arsip/{tahun}', [TahunArsipController::class, 'destroy'])->name('tahun-arsip.destroy')->where('tahun', '[0-9]+');

    // ── KP4 (Generate Dokumen Tunjangan Keluarga) ──────────────────────────
    Route::get('/kp4', [Kp4Controller::class, 'index'])->name('kp4.index');
    Route::post('/kp4/generate', [Kp4Controller::class, 'generate'])->name('kp4.generate');

    // ── Manajemen Template Tata Naskah ─────────────────────────────────────
    Route::middleware(['role:super_admin,tata_usaha'])->group(function () {
        Route::post('templates/{id}/remove-file', [App\Http\Controllers\TemplateController::class, 'removeFile'])->name('templates.remove-file')->whereNumber('id');
        Route::post('templates/{id}/remove-file-pdf', [App\Http\Controllers\TemplateController::class, 'removeFilePdf'])->name('templates.remove-file-pdf')->whereNumber('id');
        Route::resource('templates', App\Http\Controllers\TemplateController::class);
    });

    // ── Utilitas PDF ───────────────────────────────────────────────────────
    Route::prefix('pdf-utility')->name('pdf-utility.')->group(function () {
        Route::get('/', [App\Http\Controllers\PdfUtilityController::class, 'index'])->name('index');
        Route::get('/foto-ke-pdf', [App\Http\Controllers\PdfUtilityController::class, 'fotoKePdf'])->name('foto-ke-pdf');
        Route::get('/word-ke-pdf', [App\Http\Controllers\PdfUtilityController::class, 'wordKePdf'])->name('word-ke-pdf');
        Route::get('/gabung-pdf', [App\Http\Controllers\PdfUtilityController::class, 'gabungPdf'])->name('gabung-pdf');
        Route::post('/images-to-pdf', [App\Http\Controllers\PdfUtilityController::class, 'imagesToPdf'])->name('images-to-pdf');
        Route::post('/merge-pdfs', [App\Http\Controllers\PdfUtilityController::class, 'mergePdfs'])->name('merge-pdfs');
        Route::post('/word-to-pdf', [App\Http\Controllers\PdfUtilityController::class, 'wordToPdf'])->name('word-to-pdf');
    });
});

require __DIR__.'/auth.php';
