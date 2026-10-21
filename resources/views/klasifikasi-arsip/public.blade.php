@extends('layouts.landing')

@section('page_title', 'Kode Klasifikasi Arsip — SISIPIN DISHUB LAMPUNG')

@section('page_description', 'Cari kode klasifikasi arsip JRA SKKAAD Lampung secara online tanpa login. Panduan kode surat dan arsip internal Dinas Perhubungan Provinsi Lampung.')

@section('content')

{{-- =====================================================================
     PAGE HEADER - MODERN REDESIGN
====================================================================== --}}
<div class="pk-page-header-modern">
    <div class="pk-header-container">
        
        {{-- Breadcrumb --}}
        <nav class="pk-breadcrumb-modern" aria-label="Breadcrumb">
            <a href="{{ url('/') }}" class="pk-breadcrumb-link-modern">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                Beranda
            </a>
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="pk-breadcrumb-sep"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            <span class="pk-breadcrumb-current-modern">Kode Klasifikasi JRA</span>
        </nav>

        {{-- Badge --}}
        <div class="pk-badge-modern">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
            Akses Publik
        </div>

        {{-- Title & Desc --}}
        <h1 class="pk-title-modern">Kode Klasifikasi &amp; Jadwal Retensi Arsip</h1>
        <p class="pk-desc-modern">
            Telusuri 2.985+ kode klasifikasi arsip dan ketentuan JRA resmi Dinas Perhubungan Provinsi Lampung 
            berdasarkan Peraturan Gubernur &amp; standar ANRI — akses terbuka untuk umum.
        </p>

        {{-- Stats Pills --}}
        <div class="pk-stats-modern pk-stats-2col">
            <div class="pk-stat-card-modern">
                <div class="pk-stat-number-modern">2.985+</div>
                <div class="pk-stat-label-modern">Kode Klasifikasi</div>
            </div>
            <div class="pk-stat-card-modern">
                <div class="pk-stat-number-modern">JRA 2026</div>
                <div class="pk-stat-label-modern">SKKAAD Lampung</div>
            </div>
        </div>

    </div>
</div>

{{-- =====================================================================
     MAIN CONTENT
====================================================================== --}}
<main class="pk-main" role="main">
    <div class="pk-container">

        {{-- Search Box --}}
        <div class="pk-search-box">
            <form method="GET" action="{{ route('public.klasifikasi') }}" class="pk-search-form" role="search">
                <div class="pk-search-input-wrap">
                    <svg class="pk-search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        id="search-input"
                        value="{{ request('search') }}"
                        placeholder="Cari kode klasifikasi (contoh: DA.00) atau nama uraian..."
                        class="pk-search-input"
                        autocomplete="off"
                        aria-label="Cari kode klasifikasi"
                    />
                    @if(request()->filled('search'))
                        <a href="{{ route('public.klasifikasi') }}" class="pk-search-clear" title="Hapus pencarian" aria-label="Hapus pencarian">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="pk-search-btn" id="search-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
            </form>
            @if(request()->filled('search'))
                <div class="pk-search-info">
                    Menampilkan hasil untuk: <strong>"{{ request('search') }}"</strong>
                    &nbsp;·&nbsp; <strong>{{ $klasifikasis->total() }}</strong> data ditemukan
                    &nbsp;&nbsp;
                    <a href="{{ route('public.klasifikasi') }}" class="pk-reset-link">Lihat semua →</a>
                </div>
            @endif
        </div>

        {{-- DB Error Banner --}}
        @if(!empty($dbError) && $dbError)
        <div style="display:flex;align-items:center;gap:12px;background:var(--color-accent-subtle);border:1.5px solid var(--color-accent-border);border-left:4px solid var(--color-siger);border-radius:12px;padding:14px 18px;margin-bottom:20px;color:var(--color-text);">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            <div>
                <p style="font-size:14px;font-weight:700;color:var(--color-text);margin:0 0 2px;">Database Tidak Tersedia</p>
                <p style="font-size:13px;color:var(--color-text-secondary);margin:0;">Data kode klasifikasi tidak bisa dimuat saat ini. Pastikan server database (MySQL) sudah berjalan di Laragon/XAMPP.</p>
            </div>
        </div>
        @endif

        {{-- Table Card --}}
        <div class="pk-table-card">
            {{-- Table --}}
            <div class="pk-table-scroll">
                <table class="pk-table" aria-label="Tabel Kode Klasifikasi Arsip">
                    <thead>
                        <tr class="pk-thead-row">
                            <th scope="col" class="pk-th pk-th-kode">Kode</th>
                            <th scope="col" class="pk-th pk-th-nama">Nama / Uraian Klasifikasi</th>
                            <th scope="col" class="pk-th pk-th-center">Retensi Aktif</th>
                            <th scope="col" class="pk-th pk-th-center">Retensi Inaktif</th>
                            <th scope="col" class="pk-th pk-th-center">Nasib Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="pk-tbody">
                        @forelse($klasifikasis as $index => $k)
                        <tr class="pk-tr {{ $index % 2 !== 0 ? 'pk-tr-alt' : '' }}">
                            {{-- Kode --}}
                            <td class="pk-td pk-td-kode">
                                <code class="pk-kode-badge">{{ $k->kode }}</code>
                            </td>

                            {{-- Nama --}}
                            <td class="pk-td pk-td-nama">
                                <div class="pk-nama-text">{{ $k->nama }}</div>
                                @if($k->dasar_pertimbangan)
                                    <div class="pk-dasar">{{ Str::limit($k->dasar_pertimbangan, 80) }}</div>
                                @endif
                            </td>

                            {{-- Retensi Aktif --}}
                            <td class="pk-td pk-td-center">
                                <span class="pk-retensi-badge pk-retensi-aktif">{{ $k->retensi_aktif_tahun }} Thn</span>
                            </td>

                            {{-- Retensi Inaktif --}}
                            <td class="pk-td pk-td-center">
                                <span class="pk-retensi-badge pk-retensi-inaktif">{{ $k->retensi_inaktif_tahun }} Thn</span>
                            </td>

                            {{-- Nasib Akhir --}}
                            <td class="pk-td pk-td-center">
                                @if($k->nasib_akhir === 'musnah')
                                    <span class="pk-nasib-badge pk-nasib-musnah">Musnah</span>
                                @elseif($k->nasib_akhir === 'permanen')
                                    <span class="pk-nasib-badge pk-nasib-permanen">Permanen</span>
                                @else
                                    <span class="pk-nasib-badge pk-nasib-dinilai">Dinilai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="pk-empty-cell">
                                <div class="pk-empty-state">
                                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <span class="pk-empty-title">Data tidak ditemukan</span>
                                    <span class="pk-empty-desc">Coba kata kunci atau kode klasifikasi lain.</span>
                                    <a href="{{ route('public.klasifikasi') }}" class="pk-empty-reset">Tampilkan Semua Data</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Table Footer: info + pagination --}}
            @if($klasifikasis->total() > 0)
            <div class="pk-table-footer">
                <span class="pk-table-info">
                    Menampilkan <strong>{{ $klasifikasis->firstItem() }}–{{ $klasifikasis->lastItem() }}</strong>
                    dari <strong>{{ $klasifikasis->total() }}</strong> kode klasifikasi
                </span>
                @if($klasifikasis->hasPages())
                    <div class="pk-pagination">
                        {{ $klasifikasis->withQueryString()->links() }}
                    </div>
                @endif
            </div>
            @endif
        </div>

        {{-- Info Box --}}
        <div class="pk-info-box">
            <div class="pk-info-icon" aria-hidden="true">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 11.518 1.352l-.042.021a.75.75 0 01-.518-1.352zm0 4.51a.75.75 0 100-1.5.75.75 0 000 1.5zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="pk-info-body">
                <p class="pk-info-title">Tentang Data Klasifikasi Ini</p>
                <p class="pk-info-text">
                    Data kode klasifikasi dan JRA ini bersumber dari <strong>KA-JRA SKKAAD 2026</strong> yang berlaku di lingkungan Pemerintah Provinsi Lampung.
                    Pengelolaan dan pembaruan data dilakukan oleh administrator sistem SISIPIN DISHUB.
                    Untuk akses penuh termasuk manajemen data, silakan
                    <a href="{{ route('login') }}">masuk ke sistem</a>.
                </p>
            </div>
        </div>

    </div>
</main>

{{-- =====================================================================
     PAGE STYLES
====================================================================== --}}
<style>
/* =================================================================
   PUBLIC KLASIFIKASI PAGE STYLES
   Color system: Navy #1A365D, Dark Navy #0F1C3F, Gold #f59e0b
   ================================================================= */

/* =================================================================
   PUBLIC KLASIFIKASI PAGE HEADER - MODERN REDESIGN
   ================================================================= */

.pk-page-header-modern {
    background: linear-gradient(135deg, #0F1C3F 0%, #1A365D 100%);
    padding: 80px 24px 64px;
    position: relative;
    overflow: hidden;
}

.pk-page-header-modern::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(245,158,11,.04) 1px, transparent 1px);
    background-size: 32px 32px;
    pointer-events: none;
}

.pk-header-container {
    position: relative;
    z-index: 2;
    max-width: 900px;
    margin: 0 auto;
    text-align: center;
}

/* Breadcrumb modern */
.pk-breadcrumb-modern {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    font-size: 12px;
    font-weight: 500;
}

.pk-breadcrumb-link-modern {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: rgba(255, 255, 255, 0.65);
    text-decoration: none;
    transition: all 0.25s ease;
}

.pk-breadcrumb-link-modern:hover {
    color: #fbbf24;
}

.pk-breadcrumb-link-modern svg {
    flex-shrink: 0;
}

.pk-breadcrumb-sep {
    opacity: 0.35;
    flex-shrink: 0;
}

.pk-breadcrumb-current-modern {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
}

/* Badge modern */
.pk-badge-modern {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    background: rgba(245, 158, 11, 0.12);
    border: 1px solid rgba(245, 158, 11, 0.25);
    color: #fbbf24;
    padding: 6px 16px;
    border-radius: 100px;
    margin-bottom: 18px;
    backdrop-filter: blur(4px);
}

/* Title modern */
.pk-title-modern {
    font-family: var(--font-display);
    font-size: clamp(28px, 5vw, 48px);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: #ffffff;
    margin: 0 0 16px;
}

/* Description modern */
.pk-desc-modern {
    font-size: 15px;
    line-height: 1.75;
    color: rgba(255, 255, 255, 0.75);
    max-width: 680px;
    margin: 0 auto 40px;
}

/* Stats grid modern */
.pk-stats-modern {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 0;
}

.pk-stat-card-modern {
    background: rgba(255, 255, 255, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 18px 16px;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.pk-stat-card-modern:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(245, 158, 11, 0.3);
    transform: translateY(-2px);
}

.pk-stat-number-modern {
    font-family: var(--font-display);
    font-size: clamp(18px, 3vw, 24px);
    font-weight: 800;
    color: #fbbf24;
    line-height: 1.1;
    margin-bottom: 4px;
}

.pk-stat-label-modern {
    font-size: 12px;
    font-weight: 600;
    color: rgba(255, 255, 255, 0.65);
    letter-spacing: 0.02em;
}

.pk-stats-2col {
    grid-template-columns: repeat(2, 1fr);
}

/* Responsive */
@media (max-width: 768px) {
    .pk-page-header-modern {
        padding: 60px 20px 48px;
    }

    .pk-stats-modern {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .pk-desc-modern {
        margin-bottom: 28px;
    }
}

@media (max-width: 640px) {
    .pk-stats-modern {
        grid-template-columns: 1fr;
    }

    .pk-title-modern {
        font-size: clamp(24px, 4vw, 32px);
    }
}

/* Stats row */
.pk-stats-row {
    display: inline-flex; align-items: center; gap: 0;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 12px; padding: 10px 20px;
    flex-wrap: wrap; justify-content: center;
}
.pk-stat-pill {
    font-size: 13px; color: rgba(255,255,255,.75);
    padding: 0 16px;
}
.pk-stat-pill strong { color: #f59e0b; }
.pk-stat-sep {
    width: 1px; height: 20px;
    background: rgba(255,255,255,.2); flex-shrink: 0;
}

/* ----- MAIN ----- */
.pk-main { padding: 40px 0 80px; background: #f7fafc; }
[data-theme="dark"] .pk-main { background: #0a0a0a; }
.pk-container {
    max-width: 1100px; margin: 0 auto;
    padding: 0 24px;
}

/* ----- SEARCH BOX ----- */
.pk-search-box {
    background: #ffffff;
    border: 1.5px solid #e8edf5;
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(26,54,93,.06);
}
[data-theme="dark"] .pk-search-box { background: #111; border-color: #222; }

.pk-search-form { display: flex; gap: 10px; }
@media (max-width: 640px) { .pk-search-form { flex-direction: column; } }

.pk-search-input-wrap {
    flex: 1; position: relative; display: flex; align-items: center;
}
.pk-search-icon {
    position: absolute; left: 14px;
    color: #94a3b8; pointer-events: none; flex-shrink: 0;
}
.pk-search-input {
    width: 100%;
    padding: 12px 44px 12px 44px;
    font-size: 14px;
    border: 1.5px solid #d1d9e6;
    border-radius: 10px;
    background: #f9fafc;
    color: #1e293b;
    outline: none;
    transition: all .2s;
    font-family: inherit;
}
.pk-search-input:focus {
    border-color: #1A365D;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(26,54,93,.1);
}
.pk-search-input::placeholder { color: #94a3b8; }
[data-theme="dark"] .pk-search-input { background: #0d0d0d; border-color: #333; color: #e2e8f0; }
[data-theme="dark"] .pk-search-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,.15); }

.pk-search-clear {
    position: absolute; right: 12px;
    display: flex; align-items: center;
    color: #94a3b8; text-decoration: none;
    transition: color .2s; padding: 4px;
    border-radius: 6px;
}
.pk-search-clear:hover { color: #1A365D; background: #f0f4ff; text-decoration: none; }

.pk-search-btn {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 12px 24px;
    font-size: 14px; font-weight: 700;
    background: #1A365D; color: #ffffff;
    border: none; border-radius: 10px;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
    font-family: inherit;
}
.pk-search-btn:hover { background: #0F1C3F; transform: translateY(-1px); }
.pk-search-btn:active { transform: none; }

.pk-search-info {
    margin-top: 12px; padding-top: 12px;
    border-top: 1px solid #f1f5f9;
    font-size: 13px; color: #64748b;
}
[data-theme="dark"] .pk-search-info { border-color: #1a1a1a; color: #94a3b8; }
.pk-search-info strong { color: #1A365D; }
[data-theme="dark"] .pk-search-info strong { color: #93c5fd; }
.pk-reset-link { color: #1A365D; font-weight: 600; text-decoration: none; }
.pk-reset-link:hover { color: #f59e0b; text-decoration: none; }

/* ----- TABLE CARD ----- */
.pk-table-card {
    background: #ffffff;
    border: 1.5px solid #e8edf5;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(26,54,93,.06);
    margin-bottom: 20px;
}
[data-theme="dark"] .pk-table-card { background: #111; border-color: #222; }

.pk-table-scroll { overflow-x: auto; }
.pk-table {
    width: 100%; border-collapse: collapse;
    font-size: 14px;
}

/* Thead */
.pk-thead-row { background: #1A365D; }
.pk-th {
    padding: 14px 20px;
    font-size: 11px; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase;
    color: rgba(255,255,255,.85);
    white-space: nowrap;
    border-bottom: none;
}
.pk-th-kode   { width: 110px; }
.pk-th-center { text-align: center; width: 130px; }

/* Tbody */
.pk-tbody tr { transition: background .15s; }
.pk-tr { background: #ffffff; border-bottom: 1px solid #f1f5f9; }
.pk-tr:last-child { border-bottom: none; }
.pk-tr-alt { background: #f9fafb; }
.pk-tr:hover { background: #eef2ff; }
[data-theme="dark"] .pk-tr { background: #111; border-color: #1a1a1a; }
[data-theme="dark"] .pk-tr-alt { background: #0e0e0e; }
[data-theme="dark"] .pk-tr:hover { background: #1a1f2e; }

.pk-td { padding: 13px 20px; vertical-align: middle; }
.pk-td-center { text-align: center; }

/* Kode badge */
.pk-kode-badge {
    display: inline-block;
    font-family: 'Courier New', monospace;
    font-size: 13px; font-weight: 700;
    color: #1A365D; background: #eef2ff;
    border: 1px solid #c7d2fe;
    padding: 3px 9px; border-radius: 7px;
    white-space: nowrap;
    letter-spacing: .04em;
}
[data-theme="dark"] .pk-kode-badge { color: #93c5fd; background: rgba(26,54,93,.3); border-color: rgba(26,54,93,.6); }

/* Nama */
.pk-nama-text {
    font-size: 14px; font-weight: 600;
    color: #1e293b; line-height: 1.4;
}
[data-theme="dark"] .pk-nama-text { color: #e2e8f0; }
.pk-dasar {
    font-size: 12px; color: #94a3b8;
    margin-top: 3px; line-height: 1.4;
}

/* Retensi badge */
.pk-retensi-badge {
    display: inline-block;
    font-size: 12px; font-weight: 700;
    padding: 4px 10px; border-radius: 8px;
    white-space: nowrap;
}
.pk-retensi-aktif   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
.pk-retensi-inaktif { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
[data-theme="dark"] .pk-retensi-aktif   { background: rgba(21,128,61,.15); border-color: rgba(21,128,61,.3); }
[data-theme="dark"] .pk-retensi-inaktif { background: rgba(180,83,9,.15);  border-color: rgba(180,83,9,.3); }

/* Nasib akhir badge */
.pk-nasib-badge {
    display: inline-block;
    font-size: 11px; font-weight: 800;
    padding: 4px 12px; border-radius: 999px;
    white-space: nowrap; letter-spacing: .04em;
    text-transform: uppercase;
}
.pk-nasib-musnah   { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
.pk-nasib-permanen { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
.pk-nasib-dinilai  { background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; }
[data-theme="dark"] .pk-nasib-musnah   { background: rgba(190,18,60,.12); border-color: rgba(190,18,60,.3); }
[data-theme="dark"] .pk-nasib-permanen { background: rgba(4,120,87,.12);  border-color: rgba(4,120,87,.3); }
[data-theme="dark"] .pk-nasib-dinilai  { background: rgba(71,85,105,.15); border-color: rgba(71,85,105,.3); }

/* Empty state */
.pk-empty-cell { padding: 60px 24px; text-align: center; }
.pk-empty-state {
    display: flex; flex-direction: column; align-items: center; gap: 8px;
}
.pk-empty-state svg { color: #cbd5e1; margin-bottom: 4px; }
.pk-empty-title { font-size: 16px; font-weight: 700; color: #475569; }
.pk-empty-desc  { font-size: 13px; color: #94a3b8; }
.pk-empty-reset {
    margin-top: 8px;
    display: inline-block; padding: 8px 20px;
    font-size: 13px; font-weight: 700;
    background: #1A365D; color: #ffffff;
    border-radius: 8px; text-decoration: none;
    transition: background .2s;
}
.pk-empty-reset:hover { background: #0F1C3F; color: #ffffff; text-decoration: none; }

/* Table footer */
.pk-table-footer {
    display: flex; align-items: center;
    justify-content: space-between; flex-wrap: wrap;
    gap: 12px;
    padding: 14px 20px;
    background: #f9fafc;
    border-top: 1px solid #e8edf5;
}
[data-theme="dark"] .pk-table-footer { background: #0d0d0d; border-color: #1a1a1a; }

.pk-table-info {
    font-size: 13px; color: #64748b;
}
[data-theme="dark"] .pk-table-info { color: #94a3b8; }
.pk-table-info strong { color: #1A365D; }
[data-theme="dark"] .pk-table-info strong { color: #93c5fd; }

.pk-pagination { display: flex; align-items: center; }

/* Pagination dark mode for landing page (data-theme based) */
[data-theme="dark"] .pk-pagination nav span:first-child,
[data-theme="dark"] .pk-pagination nav span:last-child {
    background: #1a1a1a !important;
    border-color: #2a2a2a !important;
    color: #555 !important;
}
[data-theme="dark"] .pk-pagination nav a {
    background: #1a1a1a !important;
    border-color: #2a2a2a !important;
    color: #94a3b8 !important;
}
[data-theme="dark"] .pk-pagination nav a:hover {
    background: #222 !important;
    border-color: #f59e0b !important;
    color: #fbbf24 !important;
}

/* ----- INFO BOX ----- */
.pk-info-box {
    display: flex; align-items: flex-start; gap: 16px;
    background: #f0f4ff;
    border: 1px solid #c7d2fe;
    border-left: 4px solid #1A365D;
    border-radius: 12px;
    padding: 18px 20px;
    margin-top: 8px;
}
[data-theme="dark"] .pk-info-box { background: rgba(26,54,93,.15); border-color: rgba(26,54,93,.5); border-left-color: #f59e0b; }

.pk-info-icon {
    width: 36px; height: 36px; flex-shrink: 0;
    border-radius: 10px;
    background: #1A365D; color: #ffffff;
    display: flex; align-items: center; justify-content: center;
}
.pk-info-title { font-size: 14px; font-weight: 700; color: #1A365D; margin: 0 0 4px; }
[data-theme="dark"] .pk-info-title { color: #93c5fd; }
.pk-info-text { font-size: 13px; color: #475569; line-height: 1.65; margin: 0; }
[data-theme="dark"] .pk-info-text { color: #94a3b8; }
.pk-info-text a { color: #1A365D; font-weight: 600; }
[data-theme="dark"] .pk-info-text a { color: #f59e0b; }
.pk-info-text a:hover { color: #f59e0b; }

/* ----- RESPONSIVE ----- */
@media (max-width: 640px) {
    .pk-page-header { padding: 90px 0 36px; }
    .pk-main { padding: 24px 0 60px; }
    .pk-container { padding: 0 14px; }
    .pk-search-box { padding: 16px; }
    .pk-stats-row { gap: 4px; padding: 8px 12px; }
    .pk-stat-pill { padding: 0 8px; font-size: 12px; }
    .pk-table-footer { flex-direction: column; align-items: flex-start; }
    .pk-th, .pk-td { padding: 11px 12px; }
    .pk-info-box { flex-direction: column; gap: 10px; }
}
</style>

@endsection
