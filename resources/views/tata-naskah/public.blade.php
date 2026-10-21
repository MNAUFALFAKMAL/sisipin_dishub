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
            Katalog template dan pedoman penyusunan naskah dinas resmi di lingkungan Dinas Perhubungan Provinsi Lampung — 
            pratinjau langsung format PDF dan unduh template DOCX.
        </p>

        {{-- Stats Cards --}}
        <div class="pk-stats-modern pk-stats-2col">
            <div class="pk-stat-card-modern">
                <div class="pk-stat-number-modern">{{ $grouped->flatten()->count() }}</div>
                <div class="pk-stat-label-modern">Template Tersedia</div>
            </div>
            <div class="pk-stat-card-modern">
                <div class="pk-stat-number-modern">{{ $grouped->count() }}</div>
                <div class="pk-stat-label-modern">Kategori Naskah</div>
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
          openPreview(url, title) {
              this.previewUrl = url;
              this.previewTitle = title;
              document.body.style.overflow = 'hidden';
          },
          closePreview() { 
              this.previewUrl = null; 
              this.previewTitle = null; 
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
                        value="{{ request('search') }}"
                        placeholder="Cari nama naskah atau kategori (contoh: Nota Dinas, SPPD)..."
                        class="pk-search-input"
                        autocomplete="off"
                        aria-label="Cari tata naskah"
                    />
                    @if(request()->filled('search'))
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
            @if(request()->filled('search'))
                <div class="pk-search-info">
                    Hasil pencarian: <strong>"{{ request('search') }}"</strong>
                    &nbsp;·&nbsp; <strong>{{ $grouped->flatten()->count() }}</strong> template ditemukan
                    &nbsp;&nbsp;
                    <a href="{{ route('public.tata-naskah') }}" class="pk-reset-link">Lihat semua →</a>
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
               class="tn-drive-btn" id="tn-drive-btn">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Buka Drive
            </a>
        </div>



        {{-- ============================================================
             CONTENT: Panels Layout (Product Grid kiri + Preview kanan)
        ============================================================= --}}
        <div class="tn-layout">

            {{-- ---- LEFT: Product Catalog Grid ---- --}}
            <div class="tn-catalog">

                @if($grouped->isEmpty())
                    <div class="pk-table-card" style="padding: 60px 24px; text-align: center; background:var(--card-bg); border:1px solid var(--card-border); color:var(--color-text);">
                        <svg width="48" height="48" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5" viewBox="0 0 24 24" style="margin: 0 auto 12px; display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <h3 style="font-size:18px;font-weight:700;color:var(--color-text);margin-bottom:8px;">Tidak Ada Template</h3>
                        <p style="color:var(--color-text-secondary);font-size:14px;margin-bottom:20px;">Belum ada data naskah atau tidak cocok dengan pencarian.</p>
                        <a href="{{ route('public.tata-naskah') }}" class="tn-reset-btn">Tampilkan Semua</a>
                    </div>
                @else
                    @foreach($grouped as $kategori => $items)
                        <div class="tn-category-block">

                            {{-- Category Header --}}
                            <div class="tn-cat-header">
                                <span class="tn-cat-bar"></span>
                                <h2 class="tn-cat-title">{{ $kategori }}</h2>
                                <span class="tn-cat-count">{{ count($items) }} item</span>
                            </div>

                            {{-- Products Grid --}}
                            <div class="tn-product-grid">
                                @foreach($items as $item)
                                    <div class="tn-card {{ $item->has_preview ? 'has-preview' : '' }}"
                                         id="tn-card-{{ $item->id }}">

                                        {{-- === Thumbnail Area === --}}
                                        <div class="tn-card-thumb"
                                              @if($item->has_preview)
                                                    @click="openPreview('{{ $item->preview_url }}', @js($item->preview_label))"
                                                    title="Klik pratinjau {{ $item->nama_naskah }}"
                                                    tabindex="0"
                                                    role="button"
                                                    aria-label="Pratinjau {{ $item->nama_naskah }}"
                                                    @keydown.enter="openPreview('{{ $item->preview_url }}', @js($item->preview_label))"
                                             @endif
                                        >
                                            {{-- Document icon / Thumbnail --}}
                                            @if(!empty($item->file_pdf))
                                                <div class="tn-thumb-container">
                                                    <div class="tn-thumb-loader"></div>
                                                    <canvas class="tn-pdf-thumb" data-pdf-url="{{ $item->preview_url }}"></canvas>
                                                </div>
                                            @elseif($item->docx_only_url)
                                                <div class="tn-docx-only-thumb">
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                                    </svg>
                                                    <span class="tn-docx-only-label">Dokumen .docx</span>
                                                </div>
                                            @else
                                                <div class="tn-na-thumb">
                                                    <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                                    </svg>
                                                    <span class="tn-na-label">Segera Hadir</span>
                                                </div>
                                            @endif

                                            {{-- Status badge atas --}}
                                            @if($item->has_preview)
                                                <div class="tn-status-badge tn-status-preview" aria-hidden="true">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    PDF
                                                </div>
                                            @elseif($item->docx_only_url)
                                                <div class="tn-status-badge tn-status-docx" aria-hidden="true">
                                                    <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                                    DOCX
                                                </div>
                                            @endif

                                            {{-- Hover overlay --}}
                                            @if($item->has_preview)
                                                <div class="tn-card-overlay" aria-hidden="true">
                                                    <span class="tn-overlay-pill">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                        Pratinjau Dokumen
                                                    </span>
                                                </div>
                                            @elseif($item->docx_only_url)
                                                <div class="tn-card-overlay" aria-hidden="true"
                                                     @click="window.open(@js($item->docx_only_url), '_blank')"
                                                     role="button" tabindex="0"
                                                     @keydown.enter="window.open(@js($item->docx_only_url), '_blank')">
                                                    <span class="tn-overlay-pill">
                                                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                        Buka Dokumen
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- === Card Body === --}}
                                        <div class="tn-card-body">

                                            {{-- Sub-kategori badge --}}
                                            @if($item->sub_kategori)
                                                <span class="tn-card-sub">{{ $item->sub_kategori }}</span>
                                            @endif

                                            {{-- Nama naskah --}}
                                            <h3 class="tn-card-title" title="{{ $item->nama_naskah }}">{{ $item->nama_naskah }}</h3>
                                        </div>

                                        {{-- === Card Footer === --}}
                                        <div class="tn-card-footer">
                                            @if($item->file_docx)
                                                <a href="{{ route('file.serve', $item->file_docx) }}" target="_blank" class="tn-card-action tn-action-docx" title="Unduh template .docx">
                                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                                    Unduh .docx
                                                </a>
                                            @endif
                                            @if(!empty($item->google_drive_link))
                                                <a href="{{ $item->google_drive_link }}"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="tn-card-action tn-action-drive"
                                                   title="Lihat di Google Drive">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                        <path d="M6.28 3L2 10.4l4.28 7.4h11.44L22 10.4 17.72 3H6.28z"/>
                                                    </svg>
                                                    Drive
                                                </a>
                                            @else
                                                <a href="https://drive.google.com/drive/folders/11HIWrRq8WFZ8jpfMEzhSEmNjoAOXhJ5k?usp=drive_link"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="tn-card-action tn-action-drive"
                                                   title="Folder lengkap Google Drive">
                                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                        <path d="M6.28 3L2 10.4l4.28 7.4h11.44L22 10.4 17.72 3H6.28z"/>
                                                    </svg>
                                                    Drive
                                                </a>
                                            @endif
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>{{-- end .tn-catalog --}}

            {{-- ---- MODAL OVERLAY: Inline Preview Panel ---- --}}
            <div class="tn-modal-overlay" x-show="previewUrl" x-cloak
                 @click.self="closePreview()"
                 x-transition:enter="tn-modal-enter"
                 x-transition:enter-start="tn-modal-enter-start"
                 x-transition:enter-end="tn-modal-enter-end"
                 x-transition:leave="tn-modal-leave"
                 x-transition:leave-start="tn-modal-leave-start"
                 x-transition:leave-end="tn-modal-leave-end">

                <div class="tn-preview-panel">

                {{-- Panel Header --}}
                <div class="tn-panel-header">
                    <div class="tn-panel-header-left">
                        <div class="tn-panel-icon" aria-hidden="true">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="tn-panel-title" x-text="previewTitle">Pratinjau</span>
                    </div>
                    <button @click="closePreview()" class="tn-panel-close" title="Tutup pratinjau" aria-label="Tutup pratinjau">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Panel Body: <object> PDF inline --}}
                <div class="tn-panel-body">
                    <template x-if="previewUrl">
                        <div style="position:absolute;inset:0;display:flex;flex-direction:column;">
                            <object
                                :data="previewUrl + '#toolbar=1&navpanes=0&scrollbar=1&view=FitH'"
                                type="application/pdf"
                                style="flex:1;width:100%;border:none;background:var(--color-bg-subtle);"
                                id="tn-pdf-obj"
                            >
                                {{-- Fallback --}}
                                <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;gap:16px;padding:24px;text-align:center;color:var(--color-text);">
                                    <svg width="48" height="48" fill="none" stroke="var(--color-text-muted)" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    <p style="color:var(--color-text-secondary);font-size:14px;margin:0;">Browser Anda tidak mendukung pratinjau PDF langsung.</p>
                                    <a :href="previewUrl" target="_blank"
                                       style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:var(--color-bg-raised);color:var(--color-text-on-raised);border-radius:9px;font-size:13px;font-weight:700;text-decoration:none;">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        Buka PDF di Tab Baru
                                    </a>
                                </div>
                            </object>
                        </div>
                    </template>
                    <template x-if="!previewUrl">
                        <div class="tn-panel-loader">
                            <div class="tn-spinner"></div>
                            <span style="font-size:13px;color:var(--color-text-secondary);margin-top:10px;">Pilih dokumen untuk pratinjau</span>
                        </div>
                    </template>
                </div>

                {{-- Panel Footer --}}
                <div class="tn-panel-footer">
                    <a :href="previewUrl" target="_blank" rel="noopener noreferrer"
                       class="tn-panel-drive-btn" id="tn-panel-open-btn"
                       x-show="previewUrl">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        Buka PDF di Tab Baru
                    </a>
                    <button @click="closePreview()" class="tn-panel-close-btn">
                        Tutup
                    </button>
                </div>

            </div>{{-- end .tn-preview-panel --}}
            </div>{{-- end .tn-modal-overlay --}}

        </div>{{-- end .tn-layout --}}

        {{-- Info Box --}}
        <div class="pk-info-box" style="margin-top: 32px;">
            <div class="pk-info-icon" aria-hidden="true">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 11.518 1.352l-.042.021a.75.75 0 01-.518-1.352zm0 4.51a.75.75 0 100-1.5.75.75 0 000 1.5zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="pk-info-body">
                <p class="pk-info-title">Tentang Tata Naskah Dinas</p>
                <p class="pk-info-text">
                    Seluruh template ini merupakan pedoman resmi tata naskah administrasi perkantoran di lingkungan Dishub Prov. Lampung.
                    Untuk koleksi lengkap, kunjungi <a href="https://drive.google.com/drive/folders/11HIWrRq8WFZ8jpfMEzhSEmNjoAOXhJ5k?usp=drive_link" target="_blank" rel="noopener noreferrer">Google Drive resmi</a>.
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

.pk-stats-2col {
    grid-template-columns: repeat(2, 1fr);
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

/* ----- LAYOUT: Catalog + Preview Panel ----- */
.tn-layout {
    display: block; /* Removed flex since preview is now a modal */
}
.tn-catalog { width: 100%; }

/* Modal Overlay */
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

/* Alpine Transitions */
[x-cloak] { display: none !important; }
.tn-modal-enter { transition: opacity 0.3s ease; }
.tn-modal-enter-start { opacity: 0; }
.tn-modal-enter-end { opacity: 1; }
.tn-modal-leave { transition: opacity 0.2s ease; }
.tn-modal-leave-start { opacity: 1; }
.tn-modal-leave-end { opacity: 0; }

/* Panel Header */
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

/* Panel Body */
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

/* Panel Footer */
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

/* ----- CATEGORY BLOCK ----- */
.tn-category-block { margin-bottom: 36px; }
.tn-cat-header {
    display: flex; align-items: center; gap: 10px; margin-bottom: 16px;
}
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

/* ==================================================================
   TATA NASKAH CARDS — MODERN PREMIUM REDESIGN
   ================================================================== */

/* ----- PRODUCT GRID ----- */
.tn-product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 24px;
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

/* PDF Thumbnail */
.tn-thumb-container {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: #ffffff;
}
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
    position: absolute;
    width: 22px; height: 22px;
    border: 2px solid rgba(26,54,93,.18); border-top-color: #1A365D;
    border-radius: 50%; animation: tn-spin 0.8s linear infinite;
}
[data-theme="dark"] .tn-thumb-loader { border-color: rgba(245,158,11,.2); border-top-color: #f59e0b; }

/* DOCX-only Thumb */
.tn-docx-only-thumb {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 12px;
}
.tn-docx-only-thumb svg { width: 44px; height: 44px; color: #1A365D; opacity: .3; }
[data-theme="dark"] .tn-docx-only-thumb svg { color: #f59e0b; opacity: .3; }
.tn-docx-only-label { font-size: 11px; font-weight: 600; color: #94a3b8; letter-spacing: .02em; }

/* N/A Thumb */
.tn-na-thumb {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 12px;
}
.tn-na-thumb svg { width: 40px; height: 40px; color: #cbd5e1; }
.tn-na-label { font-size: 11px; font-weight: 600; color: #94a3b8; letter-spacing: .02em; }

/* ----- STATUS BADGE (top-right corner) ----- */
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
.tn-status-preview {
    background: rgba(26,54,93,.88); color: #fff;
}
[data-theme="dark"] .tn-status-preview { background: rgba(245,158,11,.85); color: #000; }
.tn-status-docx {
    background: rgba(26,54,93,.75); color: #fff;
}

/* ----- HOVER OVERLAY ----- */
.tn-card-overlay {
    position: absolute; inset: 0;
    background: rgba(15, 28, 63, 0.65);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.25s ease;
    z-index: 3;
}
.tn-card.has-preview:hover .tn-card-overlay,
.tn-card-thumb:hover .tn-card-overlay { opacity: 1; }

.tn-overlay-pill {
    display: inline-flex; align-items: center; gap: 8px;
    background: #ffffff; color: #1A365D;
    font-size: 13px; font-weight: 700;
    padding: 10px 20px; border-radius: 999px;
    transform: translateY(10px); transition: transform 0.3s cubic-bezier(.22,1,.36,1);
    box-shadow: 0 4px 16px rgba(0,0,0,.15);
}
.tn-card.has-preview:hover .tn-overlay-pill,
.tn-card-thumb:hover .tn-overlay-pill { transform: translateY(0); }
.tn-overlay-pill svg { width: 16px; height: 16px; flex-shrink: 0; }
[data-theme="dark"] .tn-overlay-pill { background: #1A365D; color: #fff; }

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
.tn-card-action {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px;
    font-size: 12px; font-weight: 600;
    border-radius: 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.tn-card-action:hover { text-decoration: none; }
.tn-action-docx {
    background: #f1f5f9; color: #1A365D;
    border: 1px solid #e2e8f0;
}
.tn-action-docx:hover { background: #1A365D; color: #fff; border-color: #1A365D; }
[data-theme="dark"] .tn-action-docx { background: rgba(26,54,93,.2); color: #93c5fd; border-color: rgba(26,54,93,.4); }
[data-theme="dark"] .tn-action-docx:hover { background: #1A365D; color: #fff; border-color: #1A365D; }
.tn-action-drive {
    background: rgba(245,158,11,.1); color: #92400e;
    border: 1px solid rgba(245,158,11,.25);
}
.tn-action-drive:hover { background: #f59e0b; color: #000; border-color: #f59e0b; }
[data-theme="dark"] .tn-action-drive { background: rgba(245,158,11,.08); color: #fcd34d; border-color: rgba(245,158,11,.15); }
[data-theme="dark"] .tn-action-drive:hover { background: #f59e0b; color: #000; border-color: #f59e0b; }

/* Reset button */
.tn-reset-btn {
    display: inline-block; padding: 10px 20px; font-size: 14px; font-weight: 700;
    background: #1A365D; color: #fff; border-radius: 9px; text-decoration: none; transition: background .2s;
}
.tn-reset-btn:hover { background: #0F1C3F; color: #fff; text-decoration: none; }

/* ----- INFO BOX ----- */
.pk-info-box {
    display: flex; align-items: flex-start; gap: 16px;
    background: #f0f4ff; border: 1px solid #c7d2fe;
    border-left: 4px solid #1A365D;
    border-radius: 12px; padding: 18px 20px;
}
[data-theme="dark"] .pk-info-box { background: rgba(26,54,93,.15); border-color: rgba(26,54,93,.5); border-left-color: #f59e0b; }
.pk-info-icon {
    width: 36px; height: 36px; flex-shrink: 0; border-radius: 10px;
    background: #1A365D; color: #fff; display: flex; align-items: center; justify-content: center;
}
.pk-info-title { font-size: 14px; font-weight: 700; color: #1A365D; margin: 0 0 4px; }
[data-theme="dark"] .pk-info-title { color: #93c5fd; }
.pk-info-text { font-size: 13px; color: #475569; line-height: 1.65; margin: 0; }
[data-theme="dark"] .pk-info-text { color: #94a3b8; }
.pk-info-text a { color: #1A365D; font-weight: 600; text-decoration: none; }
[data-theme="dark"] .pk-info-text a { color: #f59e0b; }
.pk-info-text a:hover { color: #f59e0b; text-decoration: underline; }

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
}
</style>
@endsection

@push('scripts')
@endpush
