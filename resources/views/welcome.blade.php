<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- SEO: Primary Meta Tags --}}
    <title>SISIPIN – Sistem Informasi Arsip Dinamis</title>
    <meta name="title" content="SISIPIN – Sistem Informasi Arsip Dinamis">
    <meta name="description" content="Sistem Informasi Arsip Dinamis Dinas Perhubungan Provinsi Lampung – akses terbuka untuk klasifikasi arsip dan tata naskah dinas">
    <meta name="keywords" content="SISIPIN, SISIPIN DISHUB LAMPUNG, sistem informasi arsip, Dinas Perhubungan Lampung, Dishub Lampung, surat masuk, surat keluar, arsip digital, Provinsi Lampung">
    <meta name="author" content="Dinas Perhubungan Provinsi Lampung">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Indonesian">
    <meta name="referrer" content="strict-origin-when-cross-origin">

    {{-- Canonical URL --}}
    @php($canonical = request()->url())
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:title" content="SISIPIN – Sistem Informasi Arsip Dinamis">
    <meta property="og:description" content="Sistem Informasi Arsip Dinamis Dinas Perhubungan Provinsi Lampung – akses terbuka untuk klasifikasi arsip dan tata naskah dinas">
    <meta property="og:image" content="{{ asset('images/logo-dishub.png') }}">
    <meta property="og:image:width" content="800">
    <meta property="og:image:height" content="600">
    <meta property="og:site_name" content="SISIPIN DISHUB LAMPUNG">
    <meta property="og:locale" content="id_ID">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ $canonical }}">
    <meta name="twitter:title" content="SISIPIN – Sistem Informasi Arsip Dinamis">
    <meta name="twitter:description" content="Sistem Informasi Arsip Dinamis Dinas Perhubungan Provinsi Lampung – akses terbuka untuk klasifikasi arsip dan tata naskah dinas">
    <meta name="twitter:image" content="{{ asset('images/logo-dishub.png') }}">

    {{-- Google Search Console Verification --}}
    <meta name="google-site-verification" content="l08wB3FJgUpxFgnq-zJxxC06xcO7V0BZZuEMQOMGvRQ">

    {{-- JSON-LD Structured Data --}}
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "GovernmentOrganization",
      "name": "Dinas Perhubungan Provinsi Lampung",
      "alternateName": ["Dishub Lampung", "DISHUB Prov. Lampung"],
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/logo-dishub.png') }}",
      "description": "SISIPIN – Sistem Informasi Arsip Dinamis Dinas Perhubungan Provinsi Lampung. Pengelolaan surat masuk, surat keluar, klasifikasi arsip JRA, dan laporan kearsipan secara digital.",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Bandar Lampung",
        "addressRegion": "Lampung",
        "addressCountry": "ID"
      }
    }
    </script>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 dark:bg-gray-900 flex flex-col items-center justify-center p-4">

    {{-- Skip Link --}}
    <a href="#main-content" class="sr-only focus:not-focus-visible:focus-visible:absolute top-4 left-4 bg-primary text-white px-4 py-2 rounded focus:opacity-100">Lewati ke konten utama</a>

    {{-- Hero Section --}}
    <main id="main-content">
        <div class="max-w-2xl w-full mb-12">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-primary-dark dark:text-primary-light mb-2">SISIPIN</h1>
                <p class="text-lg text-neutral-600 dark:text-neutral-400">Sistem Informasi Arsip Dinamis</p>
            </div>

            {{-- 3 Kalimat Singkat --}}
            <div class="space-y-4 max-w-2xl mx-auto">
                <p class="text-neutral-700 dark:text-neutral-300 leading-relaxed">
                    Kami menyediakan sistem informasi arsip dinamis yang memudahkan pengelolaan, pencarian, dan distribusi surat dinas di lingkungan Dinas Perhubungan Provinsi Lampung.
                </p>
                <p class="text-neutral-700 dark:text-neutral-300 leading-relaxed">
                    Dengan antarmuka yang ramah pengguna, semua stakeholders dapat mengakses klasifikasi arsip dan contoh naskah dinas secara terbuka dan efektif.
                </p>
                <p class="text-neutral-700 dark:text-neutral-300 leading-relaxed">
                    Sistem ini dirancang untuk meningkatkan transparansi dan efisiensi layanan kearsipan kepada publik umum.
                </p>
            </div>

            {{-- CTA Buttons --}}
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                <a href="/tata-naskah"
                   class="x-button x-button-primary w-full sm:w-auto">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Lihat Tata Naskah
                </a>
                <a href="/kode-klasifikasi"
                   class="x-button w-full sm:w-auto">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Lihat Klasifikasi
                </a>
                <a href="/login"
                   class="x-button w-full sm:w-auto">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 8v8m0-8v8M8 2v8m0-8V8m8 0v8l-8 8m-8-8l8 8"/></svg>
                    Masuk
                </a>
            </div>
        </div>
    </main>

    {{-- Fitur Cepat --}}
    <section class="mt-12 w-full max-w-3xl grid grid-cols-1 gap-4">
        <h2 class="text-xl font-semibold text-primary-dark dark:text-primary-light mb-3">Aksses Cepat</h2>
        <div class="grid grid-cols-2 gap-4">
            <article class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 hover:shadow-xl transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 19v-6h4v6H9v-5c4.5-1.3 8-4 8-9.5S13.5 3 9 3s-4.5 1.3-4.5 4v5h4V9c0-5 -4.25-8-9.5-8S.75 4 .75 9v4H5v5c-3.3 1.3-6 4.5-6 8.5 0 3.5 2.7 6.5 6 6.5z"/></svg>
                </div>
                <h3 class="text-sm font-medium text-primary-dark dark:text-primary-light">Klasifikasi Arsip</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">Cari kode klasifikasi arsip Pergub Lampung 16/2023</p>
            </article>
            <article class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 hover:shadow-xl transition-shadow">
                <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6v6l4 4v6H6zm5.5-4.5c0-1.05-.85-2-2-2S10 4.95 10 6c0 1.05.85 2 2 2s2-.95 2-2c0-93 53-119 89-119c16 0 33 12 33 33c0 5.5-2 9.5-5.5 11.5L17.5 15.5c.03-.2.06-.4.06-.6z"/></svg>
                </div>
                <h3 class="text-sm font-medium text-primary-dark dark:text-primary-light">Tata Naskah</h3>
                <p class="text-sm text-neutral-600 dark:text-neutral-400">Lihat contoh naskah dinas dan unduh template</p>
            </article>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="mt-auto w-full border-t border-neutral-200 dark:border-neutral-700 py-8 text-center">
        <p class="text-sm text-neutral-600 dark:text-neutral-400">
            <span>&copy; Ac 2026 Dinas Perhubungan Provinsi Lampung</span>
            <span class="mx-2">|</span>
            <a href="#" class="text-primary-dark dark:text-primary-light hover:underline">Kebijakan Privasi</a>
            <span class="mx-2">|</span>
            <a href="#" class="text-primary-dark dark:text-primary-light hover:underline">Syarat Penggunaan</a>
        </p>
    </footer>

</body>
</html>