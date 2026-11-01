<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    {{-- Halaman Utama / Landing Page --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Kode Klasifikasi Arsip (publik) --}}
    <url>
        <loc>{{ url('/kode-klasifikasi') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Tata Naskah (publik) --}}
    <url>
        <loc>{{ url('/tata-naskah') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- Klasifikasi Arsip (publik - searchable) --}}
    <url>
        <loc>{{ url('/klasifikasi-arsip/search') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>

    {{-- Surat Masuk (publik) --}}
    <url>
        <loc>{{ url('/surat-masuk') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>

    {{-- Surat Keluar (publik) --}}
    <url>
        <loc>{{ url('/surat-keluar') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.6</priority>
    </url>

    {{-- Template Dokument (publik) --}}
    <url>
        <loc>{{ url('/template') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.5</priority>
    </url>

    {{-- Panduan Pengguna (publik) --}}
    <url>
        <loc>{{ url('/pembelajaran') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>

    {{-- Dashboard (hanya untuk search engine, bisa di-noindex) --}}
    <url>
        <loc>{{ url('/dashboard') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- API & Internal Routes --}}
    <url>
        <loc>{{ url('/dashboard/statistik') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- Route CRUD lainnya yang public --}}
    <url>
        <loc>{{ url('/profile') }}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.1</priority>
    </url>

</urlset>