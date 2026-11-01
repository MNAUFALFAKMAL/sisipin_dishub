@extends('layouts.landing')

@section('page_title', 'Tata Naskah Dinas — SISIPIN DISHUB LAMPUNG')

@section('page_description', 'Unduh dan lihat katalog tata naskah dinas Dishub Provinsi Lampung: surat dinas, nota dinas, surat perintah tugas (SPT), SPPD, dan format dokumen resmi lainnya.')

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
            <span class="pk-breadcrumb-current-modern">Tata Naskah Dinas</span>
        </nav>

        {{-- Badge --}}
        <div class="pk-badge-modern">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            Pedoman Administrasi Resmi
        </div>

        {{-- Title & Desc --}}
        <h1 class="pk-title-modern">Tata Naskah Dinas</h1>
        <p class="pk-desc-modern">
            Katalog template dan pedoman penyusunan naskah dinas resmi di lingkungan Dinas Perhubungan Provinsi Lampung — pratinjau langsung format PDF dan unduh template DOCX.
        </p>

        {{-- Stats Cards --}}
        <div class="pk-stats-modern">
            <div class="pk-stat-card-modern">
                <div class="pk-stat-number-modern">{{ $totalCount }}</div>
                <div class="pk-stat-label-modern">Template Tersedia</div>
            </div>
            <div class="pk-stat-card-modern">
                <div class="pk-stat-number-modern">{{ $kategoriCount }}</div>
                <div class="pk-stat-label-modern">Kategori Naskah</div>
            </div>
            <div class="pk-stat-card-modern">
                <div class="pk-stat-number-modern">{{ $subKategoriCount }}</div>
                <div class="pk-stat-label-modern">Sub-kategori</div>
            </div>
        </div>

    </div>
</div>

{{-- =====================================================================
     MAIN CONTENT — Alpine.js state untuk panel preview & grid
====================================================================== --}}
<main class="pk-main" role="main"
      x-data="{
          previewUrl: null,
          previewTitle: null,
          previewType: null,
          previewFailed: false,
          openPreview(url, title, type) {
              this.previewFailed = false;
              this.previewUrl = url;
              this.previewTitle = title;
              this.previewType = type;
              document.body.style.overflow = 'hidden';
          },
          closePreview() {
              this.previewUrl = null;
              this.previewTitle = null;
              this.previewType = null;
              this.previewFailed = false;
              document.body.style.overflow = 'auto';
          }
      }"
      @keydown.escape.window="closePreview()">

    <div class="pk-container">

        {{-- Search Box --}}
        <div class="pk-search-box">
            <form method="GET" action="{{ route('public.tata-naskah') }}" class="pk-search-form" role="search">
                <div class="pk-search-input-wrap">
                    <svg class="pk-search-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input
                        type="text"
                        name="search"
                        id="tn-search-input"
                        value="{{ $searchQuery }}"
                        placeholder="Cari nama naskah atau kategori (contoh: Nota Dinas, SPPD)..."
                        class="pk-search-input"
                        autocomplete="off"
                        aria-label="Cari tata naskah"
                    />
                    @if($searchQuery !== '')
                        <a href="{{ route('public.tata-naskah') }}" class="pk-search-clear" title="Hapus pencarian" aria-label="Hapus pencarian">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </div>
                <button type="submit" class="pk-search-btn" id="tn-search-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Cari
                </button>
            </form>
            @if($searchQuery !== '')
                <div class="pk-search-info">
                    Hasil pencarian: <strong>"{{ $searchQuery }}"</strong>
                    &nbsp;A&nsp; <strong>{{ $grouped->flatten()->count() }}</strong> template ditemukan
                    &nbsp;&nbsp;
                    <a href="{{ route('public.tata-naskah') }}" class="pk-reset-link">Lihat semua</a>
                </div>
            @endif
        </div>

        {{-- Google Drive Banner --}}
        <div class="tn-drive-banner" id="tn-drive-banner">
            <div class="tn-drive-icon" aria-hidden="true">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="M6.28 3L2 10.4l4.28 7.4h11.44L22 10.4 17.72 3H6.28zM8.4 4.8h7.2l3.6 6.24H4.8L8.4 4.8zm-1.8 7.44h10.8l-2.16 3.76H8.76L6.6 12.24z"/>
                </svg>
            </div>
            <div class="tn-drive-body">
                <p class="tn-drive-title">Kumpulan Lengkap di Google Drive</p>
                <p class="tn-drive-desc">Seluruh template dan pedoman tata naskah tersedia dalam versi lengkap di Google Drive resmi Dishub Lampung.</p>
            </div>
            <a href="https://drive.google.com/drive/folders/11HIWrRq8WFZ8jpfMEzhSEmNjoAOXhJ5k?usp=drive_link"
               target="_blank" rel="noopener noreferrer"
               class="tn-drive-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Buka Drive
            </a>
        </div>


        {{-- ============================================================
             CONTENT: Accordion-Kelompok Grid (kategori → sub-kategori)
        ============================================================ --}}
        <div class="tn-layout">

            {{-- LEFT: Katalog Accordion Grid ---- --}}
            <details class="tn-accordion" @if($loop->first) open @endif>
                <summary class="tn-acc-summary">
                    <span class="tn-cat-bar" aria-hidden="true"></span>
                    <h2 class="tn-cat-title">{{ $kategori }}</h2>
                    <span class="tn-cat-count">{{ $kategoriTotal }} naskah</span>
                    <span class="tn-cat-subs">{{ $subGroups->count() }} sub-kategori</span>
                    <svg class="tn-acc-chevron" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </summary>

                <div class="tn-acc-body">
                    @foreach($subGroups as $subKategori => $items)
                        <section class="tn-sub-block" aria-labelledby="tn-sub-{{ $loop->parent->index }}-{{ $loop->index }}">
                            <header class="tn-sub-header">
                                <h3 class="tn-sub-title" id="tn-sub-{{ $loop->parent->index }}-{{ $loop->index }}">{{ $subKategori }}</h3>
                                <span class="tn-sub-count">{{ $items->count() }} naskah</span>
                                <span class="tn-sub-line" aria-hidden="true"></span>
                            </header>

                            <div class="tn-product-grid">
                                @foreach($items as $item)
                                    <article class="tn-card {{ $item->is_docx_only ? 'tn-card-docx-only' : '' }}" id="tn-card-{{ $item->id }}">

                                        {{-- === Thumbnail Area === --}}
                                        <div class="tn-card-thumb">
                                            <div class="tn-file-icon {{ $item->is_docx_only ? 'tn-file-docx' : 'tn-file-pdf' }}">
                                                <svg width="42" height="42" fill="none" stroke="currentColor" stroke-width="1.4" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 12.75h7.5M8.25 15.75h5.25"/>
                                                </svg>
                                                <span class="tn-file-label">{{ $item->is_docx_only ? 'Dokumen Word' : 'Dokumen PDF' }}</span>
                                            </div>
                                            @if($item->has_preview)
                                                <span class="tn-status-badge tn-status-preview">
                                                    PDF
                                                </span>
                                            @elseif($item->is_docx_only)
                                                <span class="tn-status-badge tn-status-docx">
                                                    DOCX
                                                </span>
                                            @endif
                                        </div>

                                        {{-- === Card Body === --}}
                                        <div class="tn-card-body">
                                            <p class="tn-card-cat">{{ $item->kategori }} <span aria-hidden="true">/</span> {{ $item->sub_kategori }}</p>
                                            <h4 class="tn-card-title">{{ $item->nama_naskah }}</h4>
                                        </div>

                                        {{-- === Card Footer === --}}
                                        <div class="tn-card-footer">
                                            @if($item->has_preview)
                                                <button type="button"
                                                        class="tn-btn tn-btn-preview"
                                                        @click="openPreview(@js($item->preview_url), @js($item->preview_label), 'pdf')"
                                                        aria-label="Pratinjau {{ $item->nama_naskah }}">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    Pratinjau
                                                </button>
                                            @endif

                                            @if($item->download_url)
                                                <a href="{{ $item->download_url }}"
                                                   class="tn-btn tn-btn-download"
                                                   download
                                                   aria-label="Unduh {{ $item->nama_naskah }}">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                                    Unduh
                                                </a>
                                            @endif

                                            @if($item->docx_viewer_url)
                                                <button type="button"
                                                        class="tn-btn tn-btn-preview"
                                                        @click="openPreview(@js($item->docx_viewer_url), @js($item->preview_label), 'docx')"
                                                        aria-label="Pratinjau {{ $item->nama_naskah }}">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    Pratinjau (Google Docs)
                                                </button>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </details>

        </div>

        {{-- Info Box --}}
        <div class="pk-info-box">
            <div class="pk-info-icon" aria-hidden="true">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 11.518 1.352l-.042.021a.75.75 0 01-.518-1.352zm0 4.51a.75.75 0 100-1.5.75.75 0 000 1.5zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="pk-info-body">
                <p class="pk-info-title">Tentang Tata Naskah Dinas</p>
                <p class="pk-info-text">
                    Seluruh template ini merupakan pedoman resmi tata naskah administrasi perkantoran di lingkungan Dishub Prov. Lampung. Untuk koleksi lengkap, kunjungi <a href="https://drive.google.com/drive/folders/11HIWrRq8WFZ8jpfMEzhSEmNjoAOXhJ5k?usp=drive_link" target="_blank" rel="noopener noreferrer">Google Drive resmi</a>.
                </p>
            </div>
        </div>

    </div>
</main>

{{-- =====================================================================
     PAGE STYLES
====================================================================== --}}
<style>
/* ================================================================
   TATA NASKAH PUBLIC PAGE — E-Commerce Product Catalog Style
   Base: Kode Klasifikasi (pk-*). Extended: tn-*
   Color: Navy #1A365D, Dark Navy #0F1C3F, Gold #f59e0b
   ================================================================ */

/* ----- PAGE HEADER MODERN (replaces old pk-page-header) ----- */
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
.pk-breadcrumb-link-modern:hover { color: #fbbf24; }
.pk-breadcrumb-link-modern svg { flex-shrink: 0; }
.pk-breadcrumb-sep { opacity: 0.35; flex-shrink: 0; }
.pk-breadcrumb-current-modern { color: rgba(255, 255, 255, 0.9); font-weight: 600; }
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
.pk-title-modern {
    font-family: var(--font-display);
    font-size: clamp(28px, 5vw, 48px);
    font-weight: 800;
    line-height: 1.15;
    letter-spacing: -0.03em;
    color: #ffffff;
    margin: 0 0 16px;
}
.pk-desc-modern {
    font-size: 15px;
    line-height: 1.75;
    color: rgba(255, 255, 255, 0.75);
    max-width: 680px;
    margin: 0 auto 40px;
}
.pk-stats-modern {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 0;
}
.pk-stats-2col { grid-template-columns: repeat(2, 1fr); gap: 16px; }
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
@media (max-width: 768px) {
    .pk-page-header-modern { padding: 60px 20px 48px; }
    .pk-stats-modern { grid-template-columns: repeat(2, 1fr); gap: 12px; }
    .pk-desc-modern { margin-bottom: 28px; }
}
@media (max-width: 640px) {
    .pk-stats-modern { grid-template-columns: 1fr; }
    .pk-title-modern { font-size: clamp(24px, 4vw, 32px); }
}

/* ----- MAIN ----- */
.pk-main { padding: 40px 0 80px; background: var(--color-bg-subtle, #f7f7f7); }
.pk-container { max-width: 1340px; margin: 0 auto; padding: 0 24px; }

/* ----- SEARCH BOX ----- */
.pk-search-box {
    background: #ffffff; border: 1.5px solid #e8edf5; border-radius: 16px;
    padding: 20px 24px; margin-bottom: 20px; box-shadow: 0 2px 12px rgba(26,54,93,.06);
}
[data-theme="dark"] .pk-search-box { background: #111; border-color: #222; }
.pk-search-form { display: flex; gap: 10px; }
@media (max-width: 600px) { .pk-search-form { flex-direction: column; } }
.pk-search-input-wrap { flex: 1; position: relative; display: flex; align-items: center; }
.pk-search-icon { position: absolute; left: 14px; color: #94a3b8; pointer-events: none; }
.pk-search-input {
    width: 100%; padding: 12px 44px; font-size: 14px;
    border: 1.5px solid #d1d9e6; border-radius: 10px; background: #f9fafc;
    color: #1e293b; outline: none; transition: all .2s; font-family: inherit;
}
.pk-search-input:focus { border-color: #1A365D; background: #fff; box-shadow: 0 0 0 3px rgba(26,54,93,.1); }
.pk-search-input::placeholder { color: #94a3b8; }
[data-theme="dark"] .pk-search-input { background: #0d0d0d; border-color: #333; color: #e2e8f0; }
[data-theme="dark"] .pk-search-input:focus { border-color: #f59e0b; box-shadow: 0 0 0 3px rgba(245,158,11,.15); }
.pk-search-clear {
    position: absolute; right: 12px; display: flex; align-items: center;
    color: #94a3b8; text-decoration: none; transition: color .2s; padding: 4px; border-radius: 6px;
}
.pk-search-clear:hover { color: #1A365D; background: #f0f4ff; text-decoration: none; }
.pk-search-btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 12px 24px;
    font-size: 14px; font-weight: 700; background: #1A365D; color: #fff;
    border: none; border-radius: 10px; cursor: pointer; transition: all .2s;
    white-space: nowrap; font-family: inherit;
}
.pk-search-btn:hover { background: #0F1C3F; transform: translateY(-1px); }
.pk-search-info { margin-top: 12px; padding-top: 12px; border-top: 1px solid #f1f5f9; font-size: 13px; color: #64748b; }
[data-theme="dark"] .pk-search-info { border-color: #1a1a1a; color: #94a3b8; }
.pk-search-info strong { color: #1A365D; }
[data-theme="dark"] .pk-search-info strong { color: #93c5fd; }
.pk-reset-link { color: #1A365D; font-weight: 600; text-decoration: none; }
.pk-reset-link:hover { color: #f59e0b; text-decoration: none; }

/* ----- GOOGLE DRIVE BANNER ----- */
.tn-drive-banner {
    display: flex; align-items: center; gap: 16px;
    background: #fff9f0; border: 1.5px solid #fed7aa;
    border-left: 4px solid #f59e0b;
    border-radius: 12px; padding: 16px 20px; margin-bottom: 28px;
    flex-wrap: wrap;
}
[data-theme="dark"] .tn-drive-banner { background: rgba(245,158,11,.08); border-color: rgba(245,158,11,.2); border-left-color: #f59e0b; }
.tn-drive-icon { width: 40px; height: 40px; flex-shrink: 0; border-radius: 10px; background: #f59e0b; color: #fff; display: flex; align-items: center; justify-content: center; }
.tn-drive-body { flex: 1; min-width: 180px; }
.tn-drive-title { font-size: 14px; font-weight: 700; color: #92400e; margin: 0 0 2px; }
[data-theme="dark"] .tn-drive-title { color: #fcd34d; }
.tn-drive-desc { font-size: 12px; color: #b45309; margin: 0; line-height: 1.5; }
[data-theme="dark"] .tn-drive-desc { color: #d97706; }
.tn-drive-btn {
    display: inline-flex; align-items: center; gap: 7px; padding: 10px 18px;
    font-size: 13px; font-weight: 700; background: #f59e0b; color: #000;
    border-radius: 9px; text-decoration: none; transition: all .2s; white-space: nowrap;
    flex-shrink: 0;
}
.tn-drive-btn:hover { background: #d97706; color: #000; transform: translateY(-1px); text-decoration: none; }

/* ----- LAYOUT: Accordion + Product Grid ----- */
.tn-layout { }
.tn-accordion {
    border: 1px solid #e8edf5;
    border-radius: 14px;
    margin-bottom: 36px;
    overflow: hidden;
}
.tn-accordion[open] { border-radius: 14px 14px 0 0; }
.tn-acc-summary {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 16px;
    cursor: pointer;
    user-select: none;
    background: #f8fafc;
    border-bottom: 1px solid #e8edf5;
    font-weight: 700;
    font-size: 14px;
}
.tn-acc-summary:hover { background: #eef2ff; }
[data-theme="dark"] .tn-acc-summary { background: rgba(26,54,93,.2); border-color: rgba(26,54,93,.4); color: #e2e8f0; }
[data-theme="dark"] .tn-acc-summary:hover { background: rgba(26,54,93,.3); }
.tn-cat-bar { width: 4px; height: 22px; background: #f59e0b; border-radius: 4px; flex-shrink: 0; }
.tn-cat-title {
    font-size: 18px; font-weight: 800; color: #1e293b;
    font-family: var(--font-display, 'Raleway', sans-serif);
    margin: 0;
}
[data-theme="dark"] .tn-cat-title { color: #e2e8f0; }
.tn-cat-count {
    font-size: 11px; font-weight: 700; background: #eef2ff; color: #1A365D;
    border: 1px solid #c7d2fe; padding: 3px 10px; border-radius: 999px;
}
[data-theme="dark"] .tn-cat-count { background: rgba(26,54,93,.3); border-color: rgba(26,54,93,.6); color: #93c5fd; }
.tn-acc-chevron { flex-shrink: 0; transition: transform .2s; }
.tn-acc-summary:hover .tn-acc-chevron { transform: rotate(180deg); }
.tn-acc-body { padding: 0 16px; }
.tn-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
}
@media (max-width: 1100px) {
    .tn-product-grid { grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
}
@media (max-width: 640px) {
    .tn-product-grid { grid-template-columns: 1fr; gap: 16px; }
}

/* ----- BASE CARD ----- */
.tn-card {
    background: #ffffff;
    border: 1px solid #e8edf5;
    border-radius: 14px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: all 0.25s cubic-bezier(.22,1,.36,1);
    box-shadow: 0 1px 4px rgba(26,54,93,.04);
}
[data-theme="dark"] .tn-card { background: #111; border-color: #222; }
.tn-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(26,54,93,.10);
    border-color: #1A365D;
}
[data-theme="dark"] .tn-card:hover { border-color: #f59e0b; box-shadow: 0 10px 24px rgba(245,158,11,.08); }
.tn-card.tn-card-docx-only { border-color: #d1d5e7; }
.tn-card.tn-card-docx-only:hover { border-color: #1A365D; }

/* ----- THUMBNAIL AREA ----- */
.tn-card-thumb {
    position: relative;
    height: 300px;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-bottom: 1px solid #f1f5f9;
}
[data-theme="dark"] .tn-card-thumb { background: #0a0a0a; border-color: #1a1a1a; }
.tn-card.has-preview .tn-card-thumb { cursor: pointer; }
.tn-thumb-container { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #ffffff; }
[data-theme="dark"] .tn-thumb-container { background: #0d0d0d; }
.tn-pdf-thumb {
    width: 100%; height: 100%;
    object-fit: contain; object-position: top;
    opacity: 0; transition: opacity 0.5s ease;
    background: #fff;
}
[data-theme="dark"] .tn-pdf-thumb { background: #0d0d0d; }
.tn-pdf-thumb.loaded { opacity: 1; }
.tn-thumb-loader {
    width: 22px; height: 22px;
    border: 2px solid rgba(26,54,93,.18); border-top-color: #1A365D;
    border-radius: 50%; animation: tn-spin 0.8s linear infinite;
}
[data-theme="dark"] .tn-thumb-loader { border-color: rgba(245,158,11,.2); border-top-color: #f59e0b; }
.tn-file-icon { position: relative; z-index: 2; }
.tn-file-icon svg { width: 100%; height: 100%; }
.tn-file-label {
    position: absolute; bottom: 8px; left: 8px;
    font-size: 10px; font-weight: 600; color: #64748b;
    white-space: nowrap;
}
[data-theme="dark"] .tn-file-label { color: #94a3b8; }

/* ----- STATUS BADGE ----- */
.tn-status-badge {
    position: absolute; top: 12px; right: 12px;
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 10px;
    font-size: 10px; font-weight: 700;
    border-radius: 8px;
    backdrop-filter: blur(4px);
    box-shadow: 0 2px 8px rgba(0,0,0,.08);
    z-index: 2;
    letter-spacing: .03em;
}
.tn-status-preview { background: rgba(26,54,93,.88); color: #fff; }
[data-theme="dark"] .tn-status-preview { background: rgba(245,158,11,.85); color: #000; }
.tn-status-docx { background: rgba(26,54,93,.75); color: #fff; }

/* ----- CARD BODY ----- */
.tn-card-body {
    padding: 14px 16px 8px;
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.tn-card-sub {
    display: inline-block;
    font-size: 10px; font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #0d6efd;
    margin-bottom: 1px;
}
[data-theme="dark"] .tn-card-sub { color: #60a5fa; }
.tn-card-title {
    font-family: var(--font-display, 'Raleway', sans-serif);
    font-size: 14px; font-weight: 700;
    color: #1e293b; line-height: 1.45;
    margin: 0;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
[data-theme="dark"] .tn-card-title { color: #e2e8f0; }

/* ----- CARD FOOTER ----- */
.tn-card-footer {
    display: flex; align-items: center; gap: 6px;
    padding: 10px 16px 14px;
    flex-wrap: wrap;
}
.tn-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    font-size: 12px; font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.tn-btn:hover { text-decoration: none; }
.tn-btn-preview {
    background: transparent; border: 1px solid #e2e8f0; color: #1A365D;
}
.tn-btn-preview:hover { background: #f1f5f9; border-color: #1A365D; color: #1A365D; }
[data-theme="dark"] .tn-btn-preview { border-color: rgba(26,54,93,.4); color: #93c5fd; }
[data-theme="dark"] .tn-btn-preview:hover { background: rgba(26,54,93,.2); border-color: #1A365D; color: #1A365D; }
.tn-btn-download {
    background: #f1f5f9; color: #1A365D; border: 1px solid #e2e8f0;
}
.tn-btn-download:hover { background: #1A365D; color: #fff; border-color: #1A365D; }
[data-theme="dark"] .tn-btn-download { background: rgba(26,54,93,.2); color: #93c5fd; border-color: rgba(26,54,93,.4); }
[data-theme="dark"] .tn-btn-download:hover { background: #1A365D; color: #fff; border-color: #1A365D; }
.tn-btn-drive {
    background: rgba(245,158,11,.1); color: #92400e;
    border: 1px solid rgba(245,158,11,.25);
}
.tn-btn-drive:hover { background: #f59e0b; color: #000; border-color: #f59e0b; }
[data-theme="dark"] .tn-btn-drive { background: rgba(245,158,11,.08); color: #fcd34d; border-color: rgba(245,158,11,.15); }
[data-theme="dark"] .tn-btn-drive:hover { background: #f59e0b; color: #000; border-color: #f59e0b; }

/* ----- SUB BLOCK ----- */
.tn-sub-block {
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid #f1f5f9;
}
.tn-sub-block:last-child { margin-bottom: 0; padding-bottom: 0; border-bottom: none; }
.tn-sub-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 12px;
}
.tn-sub-title {
    font-size: 14px; font-weight: 700; color: #1e293b;
    font-family: var(--font-display, 'Raleway', sans-serif);
}
[data-theme="dark"] .tn-sub-title { color: #e2e8f0; }
.tn-sub-count {
    font-size: 11px; font-weight: 600; background: #eef2ff; color: #1A365D;
    border: 1px solid #c7d2fe; padding: 3px 10px; border-radius: 999px;
}
[data-theme="dark"] .tn-sub-count { background: rgba(26,54,93,.3); border-color: rgba(26,54,93,.6); color: #93c5fd; }
.tn-sub-line {
    width: 40%; height: 1px;
    background: #e2e8f0; flex-shrink: 0;
}
[data-theme="dark"] .tn-sub-line { background: rgba(255,255,255,.1); }

/* ----- MODAL OVERLAY: Inline Preview Panel ----- */
.tn-modal-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(15, 28, 63, 0.85); backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    padding: 24px;
}
.tn-preview-panel {
    width: 100%; max-width: 1200px;
    background: #ffffff; border: 1.5px solid #e8edf5; border-radius: 16px;
    overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    height: calc(100vh - 48px);
    display: flex; flex-direction: column;
}
[data-theme="dark"] .tn-preview-panel { background: #111; border-color: #222; }
.tn-modal-enter { transition: opacity 0.3s ease; }
.tn-modal-enter-start { opacity: 0; }
.tn-modal-enter-end { opacity: 1; }
.tn-modal-leave { transition: opacity 0.2s ease; }
.tn-modal-leave-start { opacity: 1; }
.tn-modal-leave-end { opacity: 0; }
.tn-panel-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 16px; border-bottom: 1px solid #f1f5f9;
    background: #1A365D; flex-shrink: 0;
}
[data-theme="dark"] .tn-panel-header { background: #0d0d0d; border-color: #1a1a1a; }
.tn-panel-header-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
.tn-panel-icon {
    width: 30px; height: 30px; flex-shrink: 0; border-radius: 8px;
    background: rgba(255,255,255,.15); color: #ffffff;
    display: flex; align-items: center; justify-content: center;
}
.tn-panel-title {
    font-size: 13px; font-weight: 700; color: #fff;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.tn-panel-close {
    width: 30px; height: 30px; border: none; border-radius: 8px;
    background: rgba(255,255,255,.12); color: rgba(255,255,255,.75); cursor: pointer;
    display: flex; align-items: center; justify-content: center; transition: all .2s; flex-shrink: 0;
}
.tn-panel-close:hover { background: rgba(255,255,255,.25); color: #fff; }
.tn-panel-body { flex: 1; position: relative; background: #f1f5f9; overflow: hidden; }
[data-theme="dark"] .tn-panel-body { background: #000; }
.tn-panel-loader {
    position: absolute; inset: 0; display: flex; flex-direction: column;
    align-items: center; justify-content: center; pointer-events: none;
}
.tn-spinner {
    width: 36px; height: 36px;
    border: 3px solid rgba(26,54,93,.15); border-top-color: #1A365D;
    border-radius: 50%; animation: tn-spin 0.9s linear infinite;
}
@keyframes tn-spin { to { transform: rotate(360deg); } }
.tn-panel-footer {
    display: flex; align-items: center; justify-content: space-between; gap: 10px;
    padding: 12px 16px; border-top: 1px solid #f1f5f9; background: #f8fafc; flex-shrink: 0;
    flex-wrap: wrap;
}
[data-theme="dark"] .tn-panel-footer { background: #0a0a0a; border-color: #1a1a1a; }
.tn-panel-drive-btn {
    display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px;
    font-size: 12px; font-weight: 700; background: #f59e0b; color: #000;
    border-radius: 8px; text-decoration: none; transition: all .2s;
}
.tn-panel-drive-btn:hover { background: #d97706; color: #000; text-decoration: none; }
.tn-panel-close-btn {
    padding: 8px 14px; font-size: 12px; font-weight: 600;
    background: transparent; color: #64748b; border: 1px solid #e2e8f0;
    border-radius: 8px; cursor: pointer; transition: all .2s; font-family: inherit;
}
.tn-panel-close-btn:hover { background: #f1f5f9; color: #1e293b; }
[data-theme="dark"] .tn-panel-close-btn { color: #94a3b8; border-color: #333; }
[data-theme="dark"] .tn-panel-close-btn:hover { background: #1a1a1a; color: #fff; }

/* ----- EMPTY STATE ----- */
.tn-empty {
    padding: 60px 24px; text-align: center; background: var(--card-bg); border:1px solid var(--card-border); color:var(--color-text);
}
.tn-empty-icon { width: 46px; height: 46px; margin: 0 auto 12px; display:block; }
.tn-empty-title { font-size:18px; font-weight:700; color:var(--color-text); margin-bottom:8px; }
.tn-empty-text { color:var(--color-text-secondary); font-size:14px; margin-bottom:20px; }
.tn-empty-text a { color:#1A365D; text-decoration:none; font-weight:600; }
.tn-btn { display:inline-block; padding:10px 20px; font-size:14px; font-weight:700; background:#1A365D; color:#fff; border-radius:9px; text-decoration:none; transition:background .2s; }
.tn-btn:hover { background:#0F1C3F; color:#fff; text-decoration:none; }

/* ----- RESPONSIVE ----- */
@media (max-width: 1100px) {
    .tn-product-grid { grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
    .tn-card-thumb { height: 260px; }
}
@media (max-width: 640px) {
    .tn-modal-overlay { padding: 10px; }
    .tn-preview-panel { height: calc(100vh - 20px); border-radius: 12px; }
    .pk-page-header { padding: 90px 0 36px; }
    .pk-main { padding: 20px 0 60px; }
    .pk-container { padding: 0 14px; }
    .pk-stats-row { gap: 4px; padding: 8px 12px; }
    .pk-stat-pill { padding: 0 8px; font-size: 12px; }
    .tn-drive-banner { flex-direction: column; align-items: flex-start; }
    .tn-product-grid { grid-template-columns: 1fr; gap: 16px; }
    .tn-card-thumb { height: 240px; }
    .tn-card-body { padding: 12px 14px 6px; }
    .tn-card-footer { padding: 8px 14px 12px; }
    .pk-info-box { flex-direction: column; gap: 10px; }
    .tn-acc-summary { padding: 12px 14px; font-size: 13px; }
    .tn-cat-title { font-size: 16px; }
    .tn-cat-count { font-size: 10px; padding: 2px 8px; }
}

</style>

@endsection

@push('scripts')
@endpush