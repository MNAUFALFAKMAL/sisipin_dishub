<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — 
         SEO: Primary Meta Tags
         Target keywords: sisipin, sisipin dishub, sisipin dishub provinsi lampung
    — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — — —  --}}
    <title>@yield('page_title', 'SISIPIN DISHUB')</title>

    <meta name="title"       content="@yield('page_title', 'SISIPIN DISHUB')">
    <meta name="description" content="@yield('page_description', 'SISIPIN DISHUB - Sistem Informasi Arsip Internal Dinas Perhubungan Provinsi Lampung.')">
    <meta name="keywords"    content="SISIPIN, SISIPIN DISHUB LAMPUNG, sistem informasi arsip, Dinas Perhubungan Lampung, Dishub Lampung, surat masuk, surat keluar, arsip digital, JRA, SIKD, surat dinas, kode klasifikasi, tata naskah, Provinsi Lampung">
    <meta name="author"      content="Dinas Perhubungan Provinsi Lampung">
    <meta name="robots"      content="@yield('page_robots', 'index, follow')">
    <meta name="language"    content="Indonesian">
    <meta name="referrer"    content="strict-origin-when-cross-origin">

    {{-- Canonical URL — selalu menunjuk ke URL halaman saat ini (valid untuk semua halaman publik) --}}
    @php($canonical = request()->url())
    <link rel="canonical" href="{{ $canonical }}">

    {{-- —â™— Open Graph / Facebook —â™— --}}
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="{{ $canonical }}">
    <meta property="og:title"       content="@yield('page_title', 'SISIPIN DISHUB')">
    <meta property="og:description" content="@yield('page_description', 'SISIPIN DISHUB - Sistem Informasi Arsip Internal Dinas Perhubungan Provinsi Lampung.')">
    <meta property="og:image"       content="{{ asset('images/logo-dishub.png') }}">
    <meta property="og:image:width"  content="800">
    <meta property="og:image:height" content="600">
    <meta property="og:site_name"   content="SISIPIN DISHUB LAMPUNG">
    <meta property="og:locale"      content="id_ID">

    {{-- Twitter Card —â™— --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:url"         content="{{ $canonical }}">
    <meta name="twitter:title"       content="@yield('page_title', 'SISIPIN DISHUB')">
    <meta name="twitter:description" content="@yield('page_description', 'SISIPIN DISHUB - Sistem Informasi Arsip Internal Dinas Perhubungan Provinsi Lampung.')">
    <meta name="twitter:image"       content="{{ asset('images/logo-dishub.png') }}">

    {{-- Google Search Console Verification (DNS TXT aktif: l08wB3FJgUpxFgnq-zJxxC06xcO7V0BZZuEMQOMGvRQ) --}}
    <meta name="google-site-verification" content="l08wB3FJgUpxFgnq-zJxxC06xcO7V0BZZuEMQOMGvRQ">

    {{-- —— JSON-LD Structured Data (agar muncul di Google Knowledge Panel) —— --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "GovernmentOrganization",
      "name": "Dinas Perhubungan Provinsi Lampung",
      "alternateName": ["Dishub Lampung", "DISHUB Prov. Lampung"],
      "url": "{{ url('/') }}",
      "logo": "{{ asset('images/logo-dishub.png') }}",
      "description": "SISIPIN DISHUB LAMPUNG — Sistem Informasi Arsip Internal Dinas Perhubungan Provinsi Lampung. Pengelolaan surat masuk, surat keluar, klasifikasi arsip JRA, dan laporan kearsipan secara digital.",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Bandar Lampung",
        "addressRegion": "Lampung",
        "addressCountry": "ID"
      },
      "sameAs": [
        "https://dishub.lampungprov.go.id"
      ],
      "applicationCategory": "GovernmentApplication",
      "operatingSystem": "Web",
      "softwareVersion": "1.0",
      "keywords": "SISIPIN, SISIPIN DISHUB LAMPUNG, arsip digital, surat menyurat, Dinas Perhubungan Lampung"
    }
    </script>

    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@type": "WebSite",
      "name": "SISIPIN DISHUB LAMPUNG",
      "url": "{{ url('/') }}",
      "description": "Sistem Informasi Arsip Internal Dinas Perhubungan Provinsi Lampung",
      "inLanguage": "id-ID",
      "potentialAction": {
        "@type": "SearchAction",
        "target": {
          "@type": "EntryPoint",
          "urlTemplate": "{{ url('/') }}?q={search_term_string}"
        },
        "query-input": "required name=search_term_string"
      }
    }
    </script>

    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon-512.png') }}?v=2" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-512.png') }}?v=2" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-dishub.png') }}?v=1" />
    <link rel="sitemap" type="application/xml" title="Sitemap" href="{{ url('/sitemap.xml') }}">

    {{-- Fonts: Raleway & Open Sans --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=raleway:500,700,800,900|open-sans:400,600,700&display=swap" rel="stylesheet" />
    
    
    <script>
        (function() {
            var html = document.documentElement;
            var saved = localStorage.getItem('landing-theme');
            if (saved) {
                html.setAttribute('data-theme', saved);
            } else {
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                html.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        /* ================================================================
         * DESIGN TOKENS — Beranda (dishub.lampungprov.go.id)
         * Sumber: DESIGN.md & SKILL.md
         * ================================================================ */
        :root {
            /* Typography */
            --font-primary: 'Open Sans', sans-serif;
            --font-display: 'Raleway', sans-serif;
            --font-size-base: 16px;
            --font-size-xs:   10px;
            --font-size-sm:   13.33px;
            --font-size-md:   14px;
            --font-size-lg:   14.67px;
            --font-size-xl:   16px;
            --font-size-2xl:  24px;
            --font-size-3xl:  30px;
            --font-size-4xl:  48px;
            --font-weight-base: 400;
            --line-height-base: 24px;

            /* Spacing */
            --space-1: 1px;
            --space-2: 6px;
            --space-3: 8px;
            --space-4: 10px;
            --space-5: 12px;
            --space-6: 16px;
            --space-7: 25px;
            --space-8: 80px;

            /* Radius */
            --radius-xs: 10px; /* button-radius = 10px */
            --radius-sm: 20px; /* card-radius = 20px */
            --radius-md: 50px;  --radius-lg: 800px;

            /* Shadow - Flat depth as per design system spec */
            --shadow-1: none;
            --shadow-2: none;

            /* Motion */
            --motion: 500ms;
        }

        /* â”€â”€ LIGHT THEME (default) â”€â”€ */
        [data-theme="light"] {
            --color-bg:          #ffffff;
            --color-bg-subtle:   #f7f7f7; /* ghost-surface = #f7f7f7 */
            --color-bg-raised:   #1A365D; /* Deep Navy Blue (#1A365D) */
            --color-text:        #2D3748; /* primary-text = #2D3748 */
            --color-text-secondary: #555555;
            --color-text-muted:  #777777;
            --color-text-on-raised: #ffffff;
            --color-border:      #dee2e6;
            --color-border-subtle: #e9ecef;
            --color-accent:      #0d6efd; /* Electric Blue (#0d6efd) */
            --color-accent-hover:#0b5ed7;
            --color-accent-subtle: rgba(13, 110, 253, 0.08);
            --color-accent-border: rgba(13, 110, 253, 0.2);
            --color-siger:       #f59e0b; /* Siger Gold (#f59e0b) */
            --color-siger-hover: #d97706;
            --color-link:        #0d6efd;
            --color-link-hover:  #0b5ed7;
            --nav-bg:            rgba(255, 255, 255, 0.75);
            --nav-border:        #dee2e6;
            --card-bg:           #ffffff;
            --card-border:       #e9ecef;
            --hero-bg:           #1A365D;
            --hero-text:         #ffffff;
            --badge-bg:          rgba(245, 158, 11, 0.12); /* Siger gold transparent */
            --badge-border:      rgba(245, 158, 11, 0.3);
            --badge-text:        #f59e0b;
            --theme-icon-show:   "â˜€ï¸";
        }

        /* â”€â”€ DARK THEME â”€â”€ */
        [data-theme="dark"] {
            --color-bg:          #0f172a;
            --color-bg-subtle:   #1e293b;
            --color-bg-raised:   #1A365D;
            --color-text:        #f1f5f9;
            --color-text-secondary: #94a3b8;
            --color-text-muted:  #64748b;
            --color-text-on-raised: #ffffff;
            --color-border:      #334155;
            --color-border-subtle: #1e293b;
            --color-accent:      #3b82f6;
            --color-accent-hover:#60a5fa;
            --color-accent-subtle: rgba(59, 130, 246, 0.12);
            --color-accent-border: rgba(59, 130, 246, 0.25);
            --color-siger:       #fbbf24;
            --color-siger-hover: #f59e0b;
            --color-link:        #60a5fa;
            --color-link-hover:  #93bbfd;
            --nav-bg:            rgba(15, 23, 42, 0.85);
            --nav-border:        #334155;
            --card-bg:           #1e293b;
            --card-border:       #334155;
            --hero-bg:           #0f172a;
            --hero-text:         #f1f5f9;
            --badge-bg:          rgba(251, 191, 36, 0.12);
            --badge-border:      rgba(251, 191, 36, 0.25);
            --badge-text:        #fbbf24;
        }

        /* ================================================================
         * GLOBAL RESET & BASE
         * ================================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; scroll-padding-top: 80px; }

        body {
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-base);
            line-height: var(--line-height-base);
            background-color: var(--color-bg);
            color: var(--color-text);
            transition: background-color var(--motion), color var(--motion);
            -webkit-font-smoothing: antialiased;
        }

        a { color: var(--color-accent); text-decoration: none; }
        a:hover { text-decoration: underline; }
        a:focus-visible {
            outline: 3px solid var(--color-accent);
            outline-offset: 2px;
            border-radius: var(--radius-xs);
        }

        /* ================================================================
         * NAVBAR
         * ================================================================ */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            background: var(--nav-bg);
            border-bottom: 1px solid var(--nav-border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: var(--shadow-1);
            transition: background var(--motion), border-color var(--motion), box-shadow var(--motion);
        }
        .navbar.scrolled { box-shadow: var(--shadow-2); }

        .nav-inner {
            max-width: 1280px; margin: 0 auto;
            padding: 0 var(--space-6);
            height: 68px;
            display: flex; align-items: center; justify-content: space-between; gap: var(--space-6);
        }

        /* Logo */
        .nav-logo {
            display: flex; align-items: center;
            gap: var(--space-3); text-decoration: none; flex-shrink: 0;
        }
        .nav-logo:hover { text-decoration: none; }
        .logo-icon {
            width: 40px; height: 40px;
            background: var(--color-bg-raised);
            border-radius: var(--radius-xs);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-1);
        }
        .logo-icon svg { width: 22px; height: 22px; color: #ffffff; }
        .logo-wordmark { display: flex; flex-direction: column; }
        .logo-name {
            font-size: clamp(18px, 4.5vw, 22px);
            font-weight: 700;
            color: var(--color-text);
            line-height: 1.2;
            letter-spacing: -.01em;
        }
        .logo-name span { color: var(--color-siger); }
        .logo-tagline {
            font-size: 10px; font-weight: 600;
            color: var(--color-text-muted);
            text-transform: uppercase; letter-spacing: .1em;
        }

        /* Nav Links */
        .nav-links {
            display: flex; align-items: center; gap: var(--space-7);
            flex: 1; justify-content: center;
        }
        .nav-links a {
            font-size: var(--font-size-sm);
            font-weight: 600;
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: color var(--motion);
            padding: var(--space-2) 0;
            border-bottom: 2px solid transparent;
        }
        .nav-links a:hover { color: var(--color-link); border-bottom-color: var(--color-accent); text-decoration: none; }

        /* Nav Right Actions */
        .nav-actions { display: flex; align-items: center; gap: var(--space-3); flex-shrink: 0; }

        /* Theme Toggle */
        .theme-toggle {
            width: 36px; height: 36px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xs);
            background: var(--color-bg);
            color: var(--color-text-secondary);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            transition: all var(--motion);
            font-size: 16px;
        }
        .theme-toggle:hover {
            background: var(--color-bg-subtle);
            border-color: var(--color-accent);
            color: var(--color-accent);
        }
        .theme-toggle:focus-visible {
            outline: 3px solid var(--color-accent); outline-offset: 2px;
        }
        /* Sun icon for light mode, Moon for dark */
        [data-theme="light"] .icon-moon { display: block; }
        [data-theme="light"] .icon-sun  { display: none; }
        [data-theme="dark"]  .icon-moon { display: none; }
        [data-theme="dark"]  .icon-sun  { display: block; }

        /* Headings Display Font (Raleway) */
        h1, h2, h3, h4, h5, h6, .hero-title, .section-title, .feature-title, .step-title, .cta-title, .portal-title {
            font-family: var(--font-display) !important;
        }

        /* ================================================================
         * BUTTONS
         * ================================================================ */
        .btn {
            display: inline-flex; align-items: center; gap: var(--space-3);
            font-family: var(--font-primary);
            font-size: var(--font-size-sm); /* 13.33px */
            font-weight: 600;
            line-height: 1.5;
            border: 1px solid transparent; cursor: pointer;
            text-decoration: none;
            border-radius: var(--radius-xs); /* button-radius = 10px */
            padding: 12px 25px;
            transition: all var(--motion);
            white-space: nowrap;
        }
        .btn:focus-visible { outline: 3px solid var(--color-accent); outline-offset: 2px; text-decoration: none; }
        .btn:hover { text-decoration: none; }

        .btn-primary {
            background: var(--color-siger); /* Siger Gold = #f59e0b */
            color: #000000;
            border-color: var(--color-siger);
            font-weight: 700;
        }
        .btn-primary:hover {
            background: var(--color-siger-hover);
            border-color: var(--color-siger-hover);
            transform: translateY(-2px);
            color: #000000;
        }
        .btn-primary:active { transform: translateY(0); }

        .btn-outline {
            background: var(--color-bg-subtle); /* ghost-surface = #f7f7f7 */
            color: var(--color-text);
            border-color: var(--color-border);
        }
        .btn-outline:hover {
            background: var(--color-border-subtle);
            border-color: var(--color-accent);
            color: var(--color-accent);
            transform: translateY(-2px);
        }

        .btn-lg {
            font-size: var(--font-size-sm);
            padding: 14px 30px;
            border-radius: var(--radius-xs);
        }

        /* ================================================================
         * HERO SECTION
         * ================================================================ */
        .hero {
            background-color: var(--hero-bg);
            position: relative;
            overflow: hidden;
            padding-top: 68px; /* offset navbar */
        }

        /* Subtle geometric accent untuk dark mode hero */
        .hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #0F1C3F; /* Dark Navy base */
            background-image: 
                linear-gradient(135deg, rgba(26, 54, 93, 0.95) 0%, rgba(15, 28, 63, 0.85) 100%);
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
            pointer-events: none;
        }
        /* Grid dot pattern */
        .hero::after {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.06) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
            opacity: .1;
        }

        .hero-inner {
            position: relative; z-index: 10;
            max-width: 800px; margin: 0 auto;
            padding: var(--space-8) var(--space-6);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-height: calc(100vh - 68px);
        }

        /* Hero Text */
        .hero-text {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .hero-title {
            font-size: clamp(28px, 3.5vw, var(--font-size-4xl));
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -.02em;
            color: var(--hero-text);
            margin: 0 0 var(--space-6);
        }
        .hero-title .highlight {
            color: var(--color-siger);
        }

        .hero-desc {
            font-size: var(--font-size-lg);
            line-height: 26px;
            color: var(--hero-text);
            opacity: .7;
            margin: 0 0 var(--space-7);
            max-width: 600px;
        }

        .hero-actions {
            display: flex; align-items: center;
            justify-content: center;
            gap: var(--space-4); flex-wrap: wrap;
        }

        /* ================================================================
         * SECTION SHARED
         * ================================================================ */
        section { padding: var(--space-8) 0; }
        .section-inner { max-width: 1280px; margin: 0 auto; padding: 0 var(--space-6); }

        .section-header { text-align: center; margin-bottom: var(--space-7); }
        .section-eyebrow {
            display: inline-block;
            font-size: var(--font-size-xs);
            font-weight: 700; letter-spacing: .12em; text-transform: uppercase;
            color: var(--color-accent);
            margin-bottom: var(--space-5);
        }
        .section-title {
            font-size: clamp(24px, 3vw, var(--font-size-4xl));
            font-weight: 500; line-height: 1.2;
            letter-spacing: -.015em;
            color: var(--color-text);
            margin: 0 0 var(--space-5);
        }
        .section-desc {
            font-size: var(--font-size-lg);
            color: var(--color-text-secondary);
            max-width: 560px; margin: 0 auto;
            line-height: 26px;
        }



        /* ================================================================
         * FEATURES
         * ================================================================ */
        .features-section { background: var(--color-bg); }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-6);
        }
        @media (max-width: 1023px) {
            .features-grid {
                grid-template-columns: 1fr;
                gap: var(--space-5);
            }
        }

        .feature-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-sm);
            padding: var(--space-7);
            transition: all var(--motion);
            box-shadow: var(--shadow-1);
        }
        .feature-card:hover {
            border-color: var(--color-accent-border);
            background: var(--color-accent-subtle);
            box-shadow: var(--shadow-2);
            transform: translateY(-2px);
        }

        .feature-icon-wrap {
            width: 48px; height: 48px;
            border-radius: var(--radius-xs);
            background: var(--color-accent-subtle);
            border: 1px solid var(--color-accent-border);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: var(--space-5);
            transition: transform var(--motion);
        }
        .feature-card:hover .feature-icon-wrap { transform: scale(1.08); }
        .feature-icon-wrap svg { width: 24px; height: 24px; color: var(--color-accent); }

        .feature-title {
            font-size: var(--font-size-2xl);
            font-weight: 700; color: var(--color-text);
            margin: 0 0 var(--space-3);
        }
        .feature-desc {
            font-size: var(--font-size-sm);
            color: var(--color-text-secondary);
            line-height: 22px; margin: 0;
        }
        .feature-tag {
            display: inline-flex; align-items: center; gap: var(--space-2);
            font-size: var(--font-size-xs); font-weight: 600;
            color: var(--color-accent);
            background: var(--color-accent-subtle);
            border: 1px solid var(--color-accent-border);
            border-radius: var(--radius-md);
            padding: 2px var(--space-4);
            margin-top: var(--space-5);
        }

        /* ================================================================
         * HOW IT WORKS
         * ================================================================ */
        .how-section { background: var(--color-bg-subtle); border-top: 1px solid var(--color-border); border-bottom: 1px solid var(--color-border); }
        .steps-list {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: var(--space-6); counter-reset: steps;
        }
        .step-item { position: relative; padding-left: 64px; }
        .step-number {
            position: absolute; left: 0; top: 0;
            width: 44px; height: 44px;
            border-radius: var(--radius-xs);
            background: var(--color-bg-raised);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: var(--font-size-2xl); font-weight: 800;
        }
        .step-title { font-size: var(--font-size-2xl); font-weight: 700; color: var(--color-text); margin: 0 0 var(--space-3); }
        .step-desc { font-size: var(--font-size-sm); color: var(--color-text-secondary); line-height: 22px; margin: 0; }

        /* ================================================================
         * PORTAL INTEGRASI & TAUTAN TERKAIT
         * ================================================================ */
        .portals-section { background: var(--color-bg); border-top: 1px solid var(--color-border); }
        .portals-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: var(--space-6);
        }
        .portal-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xs);
            padding: var(--space-6);
            display: flex; flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--shadow-1);
            transition: all var(--motion);
            text-decoration: none !important;
        }
        .portal-card:hover {
            box-shadow: var(--shadow-2);
            border-color: var(--color-accent);
            transform: translateY(-2px);
        }
        .portal-badge {
            align-self: flex-start;
            font-size: 10px; font-weight: 700;
            padding: 4px 10px;
            border-radius: var(--radius-md);
            background: var(--color-accent-subtle);
            border: 1px solid var(--color-accent-border);
            color: var(--color-accent);
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: var(--space-4);
            display: inline-block;
        }
        [data-theme="dark"] .portal-badge {
            background: var(--badge-bg);
            border-color: var(--badge-border);
            color: var(--badge-text);
        }
        .portal-title {
            font-size: var(--font-size-2xl); font-weight: 700;
            color: var(--color-text); margin: 0 0 var(--space-3);
        }
        .portal-desc {
            font-size: var(--font-size-sm); color: var(--color-text-secondary);
            line-height: 22px; margin: 0 0 var(--space-5);
            flex-grow: 1;
        }
        .portal-link {
            font-size: var(--font-size-sm); font-weight: 600;
            color: var(--color-accent); display: flex; align-items: center; gap: var(--space-2);
            transition: color var(--motion);
        }
        .portal-card:hover .portal-link {
            color: var(--color-accent-hover);
        }
        @media (max-width: 767px) {
            .portals-grid {
                grid-template-columns: 1fr;
                gap: var(--space-5);
            }
        }

        /* ================================================================
         * CTA
         * ================================================================ */
        .cta-section {
            background: var(--color-bg-raised);
            position: relative; overflow: hidden;
        }
        .cta-section::before {
            content: '';
            position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.05) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .cta-inner {
            position: relative; z-index: 1;
            max-width: 700px; margin: 0 auto;
            padding: var(--space-8) var(--space-6);
            text-align: center;
        }
        .cta-title { font-size: clamp(24px, 3vw, 38px); font-weight: 800; color: var(--color-text-on-raised); margin: 0 0 var(--space-5); letter-spacing: -.015em; }
        .cta-desc { font-size: var(--font-size-lg); color: var(--color-text-on-raised); opacity: .65; margin: 0 0 var(--space-7); line-height: 26px; }
        .cta-actions { display: flex; gap: var(--space-4); justify-content: center; flex-wrap: wrap; }

        .btn-white {
            background: #ffffff;
            color: var(--color-bg-raised);
            font-weight: 700;
        }
        .btn-white:hover {
            background: #f0f6ff;
            color: var(--color-bg-raised);
            transform: translateY(-1px);
            box-shadow: var(--shadow-2);
        }
        .btn-ghost {
            background: rgba(255,255,255,.1);
            color: #ffffff;
            border: 1px solid rgba(255,255,255,.25);
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,.18);
            color: #ffffff;
        }

        /* ================================================================
         * FOOTER MODERN
         * ================================================================ */
        .footer-modern {
            background: var(--color-bg);
            border-top: 1px solid var(--color-border);
            padding: 80px 0 0;
            transition: background var(--motion);
        }

        .footer-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Footer Grid - 4 Columns */
        .footer-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
            gap: 40px;
            padding-bottom: 60px;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--color-border);
        }

        /* Brand Column */
        .footer-brand {
            display: flex;
            flex-direction: column;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 16px;
        }

        .footer-logo-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: color-mix(in srgb, var(--color-accent) 10%, transparent);
            border-radius: 12px;
            flex-shrink: 0;
        }

        .footer-logo-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 4px;
        }

        .footer-logo-text {
            display: flex;
            flex-direction: column;
        }

        .footer-logo-name {
            font-family: var(--font-display);
            font-size: 16px;
            font-weight: 700;
            color: var(--color-text);
        }

        .footer-logo-name span {
            color: var(--color-accent);
        }

        .footer-tagline {
            font-size: 13px;
            line-height: 1.6;
            color: var(--color-text-secondary);
            margin: 0;
        }

        /* Footer Columns */
        .footer-col {
            display: flex;
            flex-direction: column;
        }

        .footer-col-title {
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--color-text);
            margin: 0 0 16px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 0;
            padding: 0;
        }

        .footer-links a {
            font-size: 13px;
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: all 0.25s ease;
            display: inline-flex;
        }

        .footer-links a:hover {
            color: var(--color-accent);
            transform: translateX(3px);
        }

        /* Footer Contact */
        .footer-contact {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 0 0 16px;
            padding: 0;
        }

        .footer-contact li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: var(--color-text-secondary);
        }

        .footer-contact svg {
            color: var(--color-accent);
            flex-shrink: 0;
        }

        .footer-contact a {
            color: var(--color-text-secondary);
            text-decoration: none;
            transition: color 0.25s;
        }

        .footer-contact a:hover {
            color: var(--color-accent);
        }

        /* Social Buttons */
        .footer-social {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .footer-social-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: var(--color-bg-subtle);
            color: var(--color-text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.25s ease;
            border: 1px solid var(--color-border);
        }

        .footer-social-btn:hover {
            background: var(--color-accent);
            color: #ffffff;
            border-color: var(--color-accent);
            transform: translateY(-2px);
        }

        .footer-social-btn svg {
            width: 18px;
            height: 18px;
        }

        /* Map Section */
        .footer-map {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 40px;
            align-items: center;
            padding-bottom: 60px;
            margin-bottom: 40px;
            border-bottom: 1px solid var(--color-border);
        }

        .footer-map-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--color-text);
            margin: 0 0 12px;
        }

        .footer-map-address {
            font-size: 13px;
            line-height: 1.75;
            color: var(--color-text-secondary);
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 0;
        }

        .footer-map-address svg {
            color: var(--color-accent);
            flex-shrink: 0;
            margin-top: 2px;
        }

        .footer-map-embed {
            width: 100%;
            height: 280px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        /* Footer Bottom */
        .footer-bottom {
            text-align: center;
            padding: 30px 0;
        }

        .footer-copyright {
            font-size: 13px;
            color: var(--color-text-secondary);
            margin: 0 0 8px;
        }

        .footer-credits {
            font-size: 12px;
            color: var(--color-text-muted);
            margin: 0;
        }

        .footer-credits strong {
            color: var(--color-text-secondary);
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 32px;
            }
            .footer-map {
                grid-template-columns: 1fr;
            }
            .footer-map-embed {
                height: 240px;
            }
        }

        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 28px;
            }
            .footer-map {
                gap: 20px;
            }
            .footer-map-embed {
                height: 300px;
            }
        }

        /* ================================================================
         * RESPONSIVE
         * ================================================================ */
        @media (max-width: 1024px) {
            .hero-inner { grid-template-columns: 1fr; min-height: unset; padding: var(--space-7) var(--space-6); gap: var(--space-6); }
            .hero-visual { order: -1; }
            .hero-desc { max-width: 100%; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .stat-item:nth-child(2) { border-right: none; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-list { grid-template-columns: 1fr; gap: var(--space-6); }
            .footer-top { grid-template-columns: 1fr 1fr; gap: var(--space-6); }
            .footer-col-contact { grid-column: 1 / -1; }
        }

        /* ── Mobile Nav ── */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 36px;
            height: 36px;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-xs);
            background: transparent;
            cursor: pointer;
            padding: 6px;
            transition: all var(--motion);
        }
        .nav-hamburger:hover { background: var(--color-bg-subtle); }
        .nav-hamburger span {
            display: block;
            height: 2px;
            background: var(--color-text);
            border-radius: 2px;
            transition: all 0.3s ease;
            transform-origin: center;
        }
        .nav-hamburger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .nav-hamburger.open span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .nav-hamburger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* Mobile Drawer */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 60px; left: 0; right: 0;
            background: var(--nav-bg);
            border-bottom: 1px solid var(--nav-border);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            z-index: 999;
            padding: 16px;
            flex-direction: column;
            gap: 4px;
            animation: slideDown 0.2s ease;
        }
        .mobile-menu.open { display: flex; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .mobile-menu a {
            display: block;
            padding: 12px 16px;
            font-size: 15px;
            font-weight: 600;
            color: var(--color-text);
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
        }
        .mobile-menu a:hover { background: var(--color-bg-subtle); color: var(--color-accent); }
        .mobile-menu a.active { background: var(--color-accent-subtle); color: var(--color-accent); }
        .mobile-menu .mobile-divider { height: 1px; background: var(--color-border); margin: 8px 0; }
        .mobile-menu .mobile-login-btn {
            display: flex; align-items: center; justify-content: center;
            background: var(--color-siger); color: #000;
            font-weight: 700; border-radius: 10px;
            padding: 12px 16px; margin-top: 4px;
            text-decoration: none;
            transition: background 0.2s;
        }
        .mobile-menu .mobile-login-btn:hover { background: var(--color-siger-hover); color: #000; }

        /* Active nav link */
        .nav-links a.active {
            color: var(--color-accent);
            border-bottom-color: var(--color-accent);
        }

        @media (max-width: 768px) {
            html { scroll-padding-top: 72px; }
            .nav-links { display: none; }
            .nav-hamburger { display: flex; }
            .nav-inner { height: 60px; padding: 0 var(--space-4); }
            .navbar .btn { padding: 8px 16px; font-size: 13px; border-radius: 8px !important; }
            /* hide desktop login button on mobile, show in drawer */
            .nav-actions .btn { display: none; }
            .nav-actions { gap: 8px; }
        }
        @media (max-width: 640px) {
            .features-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stat-item:nth-child(even) { border-right: none; }
            .stat-item { border-bottom: 1px solid rgba(255,255,255,.12); }
            .hero-actions { flex-direction: column; align-items: center; width: 100%; gap: var(--space-4); }
            .hero-actions .btn { width: 100%; max-width: 320px; justify-content: center; padding: 12px 20px; }
            .cta-actions { flex-direction: column; align-items: center; width: 100%; gap: var(--space-4); }
            .cta-actions .btn { width: 100%; max-width: 320px; justify-content: center; }
            .footer-top { grid-template-columns: 1fr; gap: 40px; }
            .footer-col-links { grid-template-columns: 1fr; gap: 32px; }
            .mt-mobile { margin-top: 0; }
            .footer-bottom { flex-direction: column; align-items: center; text-align: center; }
        }
    </style>
</head>
<body>

<!-- ===================================================================
     NAVBAR
=================================================================== -->
<nav class="navbar" id="navbar" role="banner">
    <div class="nav-inner">

        <!-- Logo -->
        <a href="/" class="nav-logo" aria-label="SISIPIN DISHUB LAMPUNG - Beranda">
            <div class="logo-icon" style="overflow:visible">
                <img src="{{ asset('images/logo-dishub.png') }}" style="width:100%;height:100%;object-fit:contain; padding:2px; filter: drop-shadow(0 0 .5px white) drop-shadow(0 0 1px rgba(255,255,255,0.7));" alt="Logo Dishub" />
            </div>
            <div class="logo-wordmark">
                <span class="logo-name">SISIPIN<span>DISHUB</span></span>
                <span class="logo-tagline">Provinsi Lampung</span>
            </div>
        </a>

        <nav class="nav-links" aria-label="Menu utama">
            <a href="{{ url('/') }}" id="nav-home">Beranda</a>
            <a href="{{ url('/') }}#fitur" id="nav-fitur">Modul</a>
            <a href="{{ url('/') }}#referensi" id="nav-referensi">Referensi</a>
            <a href="{{ route('public.klasifikasi') }}" id="nav-klasifikasi">Kode Klasifikasi</a>
            <a href="{{ route('public.tata-naskah') }}" id="nav-tata-naskah">Tata Naskah</a>
        </nav>

        <!-- Right Actions -->
        <div class="nav-actions">
            <!-- Theme Toggle -->
            <button class="theme-toggle" id="theme-toggle" aria-label="Ganti tema gelap/terang" title="Ganti tema">
                <!-- Moon icon (tampil di light mode) -->
                <svg class="icon-moon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <!-- Sun icon (tampil di dark mode) -->
                <svg class="icon-sun" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 6.343l-.707-.707m12.728 12.728l-.707-.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
                </svg>
            </button>

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary" id="nav-dashboard">
                        Dashboard
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary" id="nav-login">
                        Masuk Sistem
                    </a>
                @endauth
            @endif

            <!-- Hamburger (mobile only) -->
            <button class="nav-hamburger" id="nav-hamburger" aria-label="Buka menu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Drawer Menu -->
<div class="mobile-menu" id="mobile-menu" aria-label="Menu mobile">
    <a href="{{ url('/') }}" id="mob-home">🏠 Beranda</a>
    <a href="{{ url('/') }}#fitur" id="mob-fitur">⚡ Modul Sistem</a>
    <a href="{{ url('/') }}#referensi" id="mob-referensi">📚 Referensi</a>
    <a href="{{ route('public.klasifikasi') }}" id="mob-klasifikasi">📁 Kode Klasifikasi JRA</a>
    <a href="{{ route('public.tata-naskah') }}" id="mob-tata">📋 Tata Naskah Dinas</a>
    <div class="mobile-divider"></div>
    @auth
        <a href="{{ url('/dashboard') }}" class="mobile-login-btn">Buka Dashboard →</a>
    @else
        <a href="{{ route('login') }}" class="mobile-login-btn">Masuk ke Sistem →</a>
    @endauth
</div>


@yield('content')

<!-- ===================================================================
     FOOTER MODERN REDESIGN
================================================================== -->
<footer class="footer-modern" role="contentinfo" id="bawah">
    <div class="footer-container">
        
        {{-- Footer Top: Grid 4 kolom --}}
        <div class="footer-grid">
            
            {{-- Column 1: Brand --}}
            <div class="footer-brand">
                <a href="{{ url('/') }}" class="footer-logo" aria-label="SISIPIN DISHUB LAMPUNG">
                    <div class="footer-logo-icon">
                        <img src="{{ asset('images/logo-dishub.png') }}" alt="Logo Dishub Lampung" />
                    </div>
                    <div class="footer-logo-text">
                        <span class="footer-logo-name">SISIPIN<span>DISHUB</span></span>
                    </div>
                </a>
                <p class="footer-tagline">
                    Sistem Informasi Arsip Internal Dinas Perhubungan Provinsi Lampung — platform digital untuk efisiensi tata kelola arsip dinas.
                </p>
            </div>

            {{-- Column 2: Modul --}}
            <div class="footer-col">
                <h3 class="footer-col-title">Modul</h3>
                <ul class="footer-links">
                    <li><a href="{{ url('/') }}#fitur">Surat Masuk</a></li>
                    <li><a href="{{ url('/') }}#fitur">Surat Keluar</a></li>
                    <li><a href="{{ route('public.klasifikasi') }}">Klasifikasi Arsip</a></li>
                    <li><a href="{{ route('public.tata-naskah') }}">Tata Naskah</a></li>
                </ul>
            </div>

            {{-- Column 3: Sistem --}}
            <div class="footer-col">
                <h3 class="footer-col-title">Sistem</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('login') }}">Masuk Sistem</a></li>
                    <li><a href="{{ url('/') }}#fitur">Dashboard</a></li>
                    <li><a href="{{ url('/') }}#referensi">Referensi</a></li>
                    <li><a href="https://dishub.lampungprov.go.id" target="_blank" rel="noopener" aria-label="Website resmi Dinas Perhubungan Provinsi Lampung">Website Resmi Dishub Lampung</a></li>
                </ul>
            </div>

            {{-- Column 4: Kontak --}}
            <div class="footer-col">
                <h3 class="footer-col-title">Kontak</h3>
                <ul class="footer-contact">
                    <li>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.273-3.973-6.869-6.869l1.293-.97c.362-.271.527-.733.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        <span>(0721) 703643</span>
                    </li>
                    <li>
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        <a href="mailto:dishubprovlampung@gmail.com">dishubprovlampung@gmail.com</a>
                    </li>
                </ul>
                
                <div class="footer-social">
                    <a href="https://www.instagram.com/dishubprovlampung?utm_source=ig_web_button_share_sheet&amp;igsh=ZDNlZDc0MzIxNw==" target="_blank" rel="noopener" class="footer-social-btn" aria-label="Instagram">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                    <a href="https://www.youtube.com/@dishubprovlampung5249" target="_blank" rel="noopener" class="footer-social-btn" aria-label="YouTube">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.5 12 3.5 12 3.5s-7.505 0-9.377.55a3.015 3.015 0 0 0-2.122 2.136C0 8.07 0 12 0 12s0 3.93.501 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.55 9.377.55 9.377.55s7.505 0 9.377-.55a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                    </a>
                    <a href="https://www.facebook.com/dishubprovlampung" target="_blank" rel="noopener" class="footer-social-btn" aria-label="Facebook">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                    </a>
                    <a href="https://x.com/dishublampung1" target="_blank" rel="noopener" class="footer-social-btn" aria-label="Twitter/X">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

        </div>

        {{-- Map Section --}}
        <div class="footer-map" data-aos="fade-up">
            <div class="footer-map-info">
                <h3 class="footer-map-title">Lokasi Kantor</h3>
                <p class="footer-map-address">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    Jl. Letkol Hendro Suratmin, Kel. Sukarame, Kec. Sukarame, Kota Bandar Lampung, Provinsi Lampung 35131
                </p>
            </div>
            <div class="footer-map-embed">
                <iframe 
                    src="https://www.google.com/maps?q=-5.373559,105.310990&z=15&output=embed"
                    width="100%" 
                    height="100%" 
                    style="border:0;border-radius:12px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="strict-origin-when-cross-origin"
                    title="Lokasi Dinas Perhubungan Provinsi Lampung">
                </iframe>
            </div>
        </div>

        {{-- Footer Bottom --}}
        <div class="footer-bottom">
            <p class="footer-copyright">
                &copy; {{ date('Y') }} Dinas Perhubungan Provinsi Lampung. Seluruh hak cipta dilindungi undang-undang.
            </p>
            <p class="footer-credits">
                Dibuat oleh <strong>Muhammad Naufal Fikri Akmal</strong> &amp; <strong>Nabila Ramadhani Mujahidin</strong>
            </p>
        </div>

    </div>
</footer>

<!-- ===================================================================
     SCRIPTS
=================================================================== -->
@stack('scripts')
</body>
</html>

