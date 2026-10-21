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

</urlset>
