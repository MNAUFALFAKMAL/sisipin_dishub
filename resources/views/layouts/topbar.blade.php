{{-- ═══════════════════════════════════════════════════
     TOPBAR — SISIPIN DISHUB (Sistem Informasi Arsip Internal Dishub Provinsi Lampung)
     bg: white | border-bottom: #e2e8f0 | h: 60px
     ═══════════════════════════════════════════════════ --}}

<header class="topbar" role="banner">

    {{-- ── Kiri: Hamburger + Judul Halaman ────── --}}
    <div style="display:flex;align-items:center;gap:12px;min-width:0">

        {{-- Hamburger (mobile only, lg hidden) --}}
        <button data-sidebar-toggle
                type="button"
                aria-label="Buka atau tutup menu"
                aria-controls="sidebar"
                class="topbar-icon-btn"
                onmouseover="this.classList.add('topbar-icon-btn-hover')"
                onmouseout="this.classList.remove('topbar-icon-btn-hover')">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        {{-- Page Title --}}
        <div style="min-width:0">
            <h1 class="topbar-title">
                @yield('page-title', 'Dashboard')
            </h1>
            <p class="topbar-date hidden sm:block" id="topbar-date">
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
            <p class="topbar-date block sm:hidden" id="topbar-date-mobile">
                {{ now()->translatedFormat('d F Y') }}
            </p>
        </div>

    </div>

    {{-- ── Kanan: Waktu + User ──────── --}}
    <div style="display:flex;align-items:center;gap:12px;flex-shrink:0">

        <!-- Theme Toggle Button -->
        <button id="system-theme-toggle" type="button" aria-label="Ganti tema"
                class="topbar-icon-btn">
            <!-- Moon icon (shows in light theme) -->
            <svg id="system-moon-icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <!-- Sun icon (shows in dark theme) -->
            <svg id="system-sun-icon" class="hidden" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 6.343l-.707-.707m12.728 12.728l-.707-.707M6.343 17.657l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z"/>
            </svg>
        </button>

        {{-- Waktu --}}
        <span class="topbar-clock" id="topbar-clock">{{ now()->format('H:i') }} WIB</span>

    </div>{{-- /kanan --}}

</header>

<style>
    .topbar-title {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #212529;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .topbar-date {
        margin: 0;
        font-size: 13px;
        font-weight: 500;
        color: #94a3b8;
    }
    .topbar-clock {
        font-size: 14px;
        font-weight: 600;
        color: #94a3b8;
        padding: 0 4px;
    }
    .topbar-icon-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: none;
        background: none;
        border-radius: 8px;
        cursor: pointer;
        color: #64748b;
        transition: background 150ms, color 150ms;
    }
    .topbar-icon-btn:hover {
        background: rgba(0,0,0,0.06);
    }

    /* Dark mode overrides */
    .dark .topbar-title {
        color: #e2e8f0;
    }
    .dark .topbar-date {
        color: #64748b;
    }
    .dark .topbar-clock {
        color: #64748b;
    }
    .dark .topbar-icon-btn {
        color: #94a3b8;
    }
    .dark .topbar-icon-btn:hover {
        background: rgba(255,255,255,0.08);
    }
</style>

