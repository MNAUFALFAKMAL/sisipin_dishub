@extends('layouts.landing')

@section('page_title', 'SISIPIN DISHUB LAMPUNG — Sistem Informasi Arsip Internal Dinas Perhubungan Provinsi Lampung')

@section('content')

{{-- =====================================================================
     PREMIUM HERO — Full-screen dark dengan animated ORB + floating elements
====================================================================== --}}
<section class="lp-hero">
    <div class="lp-hero-bg" aria-hidden="true">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="grid-pattern"></div>
    </div>

    <div class="lp-hero-inner">
        <div class="hero-badge" data-aos="fade-down" data-aos-delay="0">
            <span class="badge-dot"></span>
            Sistem Resmi Dinas Perhubungan Provinsi Lampung
        </div>

        <h1 class="hero-headline" data-aos="fade-up" data-aos-delay="100">
            <span class="hh-line">SISIPIN</span>
            <span class="hh-line hh-gold">DISHUB</span>
        </h1>

        <p class="hero-desc" data-aos="fade-up" data-aos-delay="200">
            Sistem Informasi Arsip Internal — platform digital terpadu untuk pengelolaan surat menyurat,
            klasifikasi arsip, dan pelaporan kearsipan di lingkungan Dinas Perhubungan Provinsi Lampung.
        </p>

        <div class="hero-actions" data-aos="fade-up" data-aos-delay="300">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-prime btn-glow">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-prime btn-glow">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                    Masuk ke Sistem
                </a>
            @endauth
            <a href="{{ route('public.klasifikasi') }}" class="btn-ghost">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Kode Klasifikasi JRA
            </a>
            <a href="{{ route('public.tata-naskah') }}" class="btn-ghost">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                Pedoman Tata Naskah
            </a>
        </div>
    </div>

    <div class="scroll-indicator" aria-hidden="true">
        <span class="scroll-text">Scroll</span>
        <span class="scroll-line"></span>
    </div>
</section>

{{-- =====================================================================
     TENTANG — Sekilas sistem
====================================================================== --}}
<section class="lp-about" id="fitur">
    <div class="lp-container">
        <div class="about-intro" data-aos="fade-up">
            <span class="section-label">Tentang Sistem</span>
            <h2 class="section-title">Arsip Digital, Kinerja Maksimal</h2>
            <p class="section-desc">
                SISIPIN DISHUB menghadirkan solusi kearsipan modern yang terintegrasi untuk seluruh kebutuhan
                administrasi surat menyurat Dinas Perhubungan Provinsi Lampung — dari pencatatan agenda
                hingga pelaporan analitik.
            </p>
        </div>

        <div class="bento-grid" data-aos="fade-up" data-aos-delay="100">
            <div class="bento-card bento-wide">
                <div class="bento-icon" style="--b-clr: #1A365D;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
                <h3>Surat Masuk &amp; Surat Keluar</h3>
                <p>Pencatatan agenda, disposisi multi-bidang, upload scan PDF, dan ekspor laporan Excel/PDF untuk 7 jenis surat dinas.</p>
                <div class="bento-mock">
                    <span class="mock-tag sm">Masuk</span>
                    <span class="mock-text">Undangan Rapat Forum Perhubungan</span>
                </div>
            </div>

            <div class="bento-card bento-tall">
                <div class="bento-icon" style="--b-clr: #f59e0b;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <h3>Klasifikasi Arsip JRA</h3>
                <p>Database 2.985 kode klasifikasi arsip resmi SKKAAD Lampung 2026.</p>
                <div class="bento-stat">
                    <span class="bento-stat-num">2.985</span>
                    <span class="bento-stat-lbl">Kode JRA</span>
                </div>
            </div>

            <div class="bento-card">
                <div class="bento-icon" style="--b-clr: #0d6efd;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <h3>Dashboard &amp; Analitik</h3>
                <p>Grafik tren 6 bulan, statistik per jenis surat, rekap multi-sheet Excel &amp; PDF landscape.</p>
            </div>

            <div class="bento-card">
                <div class="bento-icon" style="--b-clr: #059669;">
                    <svg width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                </div>
                <h3>Tata Naskah Dinas</h3>
                <p>21 template naskah dinas resmi — preview &amp; download langsung format PDF dan DOCX.</p>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================================
     STATS — Premium counter section
====================================================================== --}}
<section class="lp-stats">
    <div class="lp-container">
        <div class="stats-grid-premium">
            <div class="stat-premium" data-aos="fade-up" data-aos-delay="0">
                <div class="stat-icon-wrap" style="--sc: #f59e0b;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div class="stat-premium-body">
                    <span class="stat-premium-num">2.985</span>
                    <span class="stat-premium-lbl">Kode Klasifikasi JRA</span>
                </div>
            </div>
            <div class="stat-premium" data-aos="fade-up" data-aos-delay="100">
                <div class="stat-icon-wrap" style="--sc: #0d6efd;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                </div>
                <div class="stat-premium-body">
                    <span class="stat-premium-num">7</span>
                    <span class="stat-premium-lbl">Jenis Surat Terkelola</span>
                </div>
            </div>
            <div class="stat-premium" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-icon-wrap" style="--sc: #059669;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <div class="stat-premium-body">
                    <span class="stat-premium-num">8</span>
                    <span class="stat-premium-lbl">Bidang Dishub Terlayani</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================================
     RESOURCES — Referensi publik
====================================================================== --}}
<section class="lp-resources" id="referensi">
    <div class="lp-container">
        <div class="resources-intro" data-aos="fade-up">
            <span class="section-label">Akses Publik</span>
            <h2 class="section-title">Referensi Tanpa Batas</h2>
            <p class="section-desc">
                Dua modul referensi penting yang dapat diakses langsung oleh publik tanpa perlu masuk sistem.
            </p>
        </div>

        <div class="resources-grid-premium" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('public.klasifikasi') }}" class="resource-premium">
                <div class="resource-premium-bg"></div>
                <div class="resource-premium-content">
                    <div class="resource-premium-icon" style="--rc: #0d6efd;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div>
                        <h3 class="resource-premium-title">Kode Klasifikasi Arsip</h3>
                        <p class="resource-premium-desc">Cari dan telusuri 2.985 pola klasifikasi JRA sesuai Pergub Lampung.</p>
                    </div>
                    <span class="resource-premium-arrow">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </span>
                </div>
            </a>

            <a href="{{ route('public.tata-naskah') }}" class="resource-premium">
                <div class="resource-premium-bg"></div>
                <div class="resource-premium-content">
                    <div class="resource-premium-icon" style="--rc: #f59e0b;">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                    </div>
                    <div>
                        <h3 class="resource-premium-title">Pedoman Tata Naskah</h3>
                        <p class="resource-premium-desc">21 template naskah dinas resmi — pratinjau PDF &amp; unduh DOCX.</p>
                    </div>
                    <span class="resource-premium-arrow">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

{{-- =====================================================================
     CTA PREMIUM — Gradient card dengan glassmorphism
====================================================================== --}}
<section class="lp-cta">
    <div class="lp-container">
        <div class="cta-premium" data-aos="fade-up">
            <div class="cta-premium-bg" aria-hidden="true">
                <div class="cta-glow"></div>
            </div>
            <div class="cta-premium-content">
                <span class="cta-badge">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    Akses Internal
                </span>
                <h2 class="cta-premium-title">Siap Mengelola Arsip Digital?</h2>
                <p class="cta-premium-desc">
                    Masuk ke dashboard SISIPIN DISHUB untuk mulai mencatat, mengelola, dan melaporkan
                    arsip surat menyurat Dinas Perhubungan Provinsi Lampung.
                </p>
                <div class="cta-premium-actions">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-prime btn-glow">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-prime btn-glow">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                            Masuk ke Sistem
                        </a>
                    @endauth
                    <a href="{{ route('public.klasifikasi') }}" class="btn-ghost btn-ghost-white">
                        Jelajahi Publik
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- =====================================================================
     PAGE STYLES — Premium design system
====================================================================== --}}
<style>
/* ── HERO ──────────────────────────────────────────────────────────── */
.lp-hero {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    background: #0A1628;
    padding: 100px 24px 60px;
}
.lp-hero-bg {
    position: absolute;
    inset: 0;
    pointer-events: none;
    z-index: 0;
}
.orb {
    position: absolute;
    border-radius: 100%;
    filter: blur(120px);
    opacity: 0.3;
    animation: orbFloat 20s infinite alternate ease-in-out;
}
.orb-1 {
    width: 600px; height: 600px;
    background: radial-gradient(circle, #1A365D 0%, transparent 70%);
    top: -15%; left: -10%;
    animation-duration: 18s;
}
.orb-2 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
    bottom: -20%; right: -10%;
    animation-duration: 22s;
    animation-delay: 3s;
}
.orb-3 {
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(13, 110, 253, 0.06) 0%, transparent 70%);
    top: 40%; left: 50%;
    animation-duration: 25s;
    animation-delay: 6s;
}
@keyframes orbFloat {
    0% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(60px, -40px) scale(1.05); }
    66% { transform: translate(-40px, 30px) scale(0.95); }
    100% { transform: translate(20px, -20px) scale(1.02); }
}
.grid-pattern {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 40px 40px;
    opacity: 0.5;
}
.lp-hero-inner {
    position: relative;
    z-index: 2;
    max-width: 820px;
    margin: 0 auto;
    text-align: center;
}
.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 18px;
    border-radius: 100px;
    background: rgba(245, 158, 11, 0.08);
    border: 1px solid rgba(245, 158, 11, 0.2);
    color: #fbbf24;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.04em;
    margin-bottom: 32px;
    backdrop-filter: blur(8px);
}
.badge-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #fbbf24;
    animation: badgePulse 2s infinite ease-in-out;
}
@keyframes badgePulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
.hero-headline {
    margin: 0 0 24px;
    font-family: var(--font-display);
}
.hh-line {
    display: block;
    font-size: clamp(52px, 12vw, 110px);
    font-weight: 900;
    line-height: 1.02;
    letter-spacing: -0.04em;
    color: #ffffff;
    text-shadow: 0 2px 40px rgba(0,0,0,0.3);
}
.hh-gold {
    margin-top: -4px;
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 50%, #d97706 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-desc {
    font-size: clamp(15px, 1.4vw, 17px);
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.7);
    max-width: 640px;
    margin: 0 auto 36px;
}
.hero-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}
.btn-prime {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 30px;
    font-size: 14px;
    font-weight: 700;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    border: none;
    cursor: pointer;
    background: #fbbf24;
    color: #0A1628;
}
.btn-prime:hover {
    background: #f59e0b;
    color: #0A1628;
    transform: translateY(-2px);
}
.btn-glow {
    box-shadow: 0 4px 24px rgba(251, 191, 36, 0.3);
}
.btn-glow:hover {
    box-shadow: 0 6px 32px rgba(251, 191, 36, 0.45);
}
.btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 12px 22px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none !important;
    transition: all 0.25s ease;
    border: 1.5px solid rgba(255, 255, 255, 0.2);
    background: transparent;
    color: rgba(255, 255, 255, 0.85);
    cursor: pointer;
}
.btn-ghost:hover {
    background: rgba(255, 255, 255, 0.06);
    border-color: rgba(255, 255, 255, 0.4);
    color: #fff;
    transform: translateY(-2px);
}
.btn-ghost-white {
    border-color: rgba(255, 255, 255, 0.15);
    color: rgba(255, 255, 255, 0.8);
}
.scroll-indicator {
    position: absolute;
    bottom: 32px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    opacity: 0.35;
}
.scroll-text {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.5);
}
.scroll-line {
    width: 2px;
    height: 28px;
    background: linear-gradient(to bottom, rgba(255,255,255,0.4), transparent);
    border-radius: 2px;
    animation: scrollAnim 2s infinite ease-in-out;
}
@keyframes scrollAnim {
    0%, 100% { transform: scaleY(0.5); opacity: 0.3; }
    50% { transform: scaleY(1.5); opacity: 0.8; }
}

/* ── SECTION BASE ─────────────────────────────────────────────────── */
.lp-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 24px;
}
.section-label {
    display: inline-block;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--color-accent, #0d6efd);
    margin-bottom: 12px;
}
.section-title {
    font-family: var(--font-display);
    font-size: clamp(28px, 3.5vw, 40px);
    font-weight: 800;
    color: var(--color-text);
    margin: 0 0 16px;
    line-height: 1.2;
}
.section-desc {
    font-size: 15px;
    line-height: 1.75;
    color: var(--color-text-secondary);
    max-width: 640px;
    margin: 0 auto;
}

/* ── ABOUT ──────────────────────────────────────────────────────────── */
.lp-about {
    padding: 100px 0;
    background: var(--color-bg);
    transition: background var(--motion);
}
.about-intro {
    text-align: center;
    margin-bottom: 56px;
}
.bento-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: auto;
    gap: 20px;
}
.bento-wide { grid-column: span 2; }
.bento-tall { grid-row: span 2; display: flex; flex-direction: column; }
.bento-card {
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 20px;
    padding: 32px;
    transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    position: relative;
    overflow: hidden;
}
.bento-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--b-clr, #1A365D), transparent);
    opacity: 0;
    transition: opacity 0.4s ease;
}
.bento-card:hover::before { opacity: 1; }
.bento-card:hover {
    transform: translateY(-4px);
    border-color: var(--b-clr, #1A365D);
    box-shadow: 0 12px 40px rgba(0,0,0,0.06);
}
[data-theme="dark"] .bento-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.25);
}
.bento-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 18px;
    background: color-mix(in srgb, var(--b-clr) 10%, transparent);
    color: var(--b-clr);
    transition: transform 0.3s ease;
}
.bento-card:hover .bento-icon { transform: scale(1.08); }
.bento-card h3 {
    font-family: var(--font-display);
    font-size: 17px;
    font-weight: 700;
    color: var(--color-text);
    margin: 0 0 10px;
}
.bento-card p {
    font-size: 13px;
    color: var(--color-text-secondary);
    line-height: 1.65;
    margin: 0;
}
.bento-mock {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 18px;
    padding: 10px 14px;
    background: rgba(247, 248, 250, 0.5);
    border: 1px solid var(--card-border);
    border-radius: 10px;
    font-size: 11px;
}
[data-theme="dark"] .bento-mock { background: rgba(255,255,255,0.02); }
.mock-tag {
    font-size: 9px; font-weight: 700;
    padding: 2px 6px; border-radius: 4px;
    text-transform: uppercase;
}
.mock-tag.sm { background: rgba(13,110,253,0.1); color: #0d6efd; }
[data-theme="dark"] .mock-tag.sm { background: rgba(96,165,250,0.15); color: #60a5fa; }
.mock-text {
    color: var(--color-text-muted);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.bento-stat {
    margin-top: auto;
    padding-top: 20px;
    text-align: center;
}
.bento-stat-num {
    display: block;
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 38px);
    font-weight: 900;
    color: #f59e0b;
    line-height: 1.1;
}
.bento-stat-lbl {
    display: block;
    font-size: 12px;
    color: var(--color-text-muted);
    font-weight: 600;
    margin-top: 4px;
}

/* ── STATS ──────────────────────────────────────────────────────────── */
.lp-stats {
    padding: 0 0 80px;
    background: var(--color-bg);
    transition: background var(--motion);
}
.stats-grid-premium {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.stat-premium {
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 28px 24px;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 16px;
    transition: all 0.3s ease;
}
.stat-premium:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.04);
}
[data-theme="dark"] .stat-premium:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,0.2);
}
.stat-icon-wrap {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: color-mix(in srgb, var(--sc) 10%, transparent);
    color: var(--sc);
}
.stat-premium-body { display: flex; flex-direction: column; }
.stat-premium-num {
    font-family: var(--font-display);
    font-size: clamp(26px, 3vw, 36px);
    font-weight: 900;
    color: var(--color-text);
    line-height: 1.1;
}
.stat-premium-lbl {
    font-size: 12px;
    font-weight: 600;
    color: var(--color-text-muted);
}

/* ── RESOURCES ────────────────────────────────────────────────────── */
.lp-resources {
    padding: 0 0 100px;
    background: var(--color-bg);
    transition: background var(--motion);
}
.resources-intro { text-align: center; margin-bottom: 48px; }
.resources-grid-premium {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.resource-premium {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    text-decoration: none !important;
    display: block;
    transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.resource-premium:hover { transform: translateY(-4px); }
.resource-premium-bg {
    position: absolute;
    inset: 0;
    background: var(--card-bg);
    border: 1px solid var(--card-border);
    border-radius: 20px;
    transition: all 0.4s ease;
}
.resource-premium:hover .resource-premium-bg {
    border-color: var(--rc, #0d6efd);
    box-shadow: 0 12px 32px rgba(0,0,0,0.06);
}
[data-theme="dark"] .resource-premium:hover .resource-premium-bg {
    box-shadow: 0 12px 32px rgba(0,0,0,0.2);
}
.resource-premium-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 20px;
    padding: 28px 24px;
}
.resource-premium-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: color-mix(in srgb, var(--rc) 10%, transparent);
    color: var(--rc);
    transition: transform 0.3s ease;
}
.resource-premium:hover .resource-premium-icon { transform: scale(1.08); }
.resource-premium-title {
    font-family: var(--font-display);
    font-size: 15px;
    font-weight: 700;
    color: var(--color-text);
    margin: 0 0 4px;
}
.resource-premium-desc {
    font-size: 13px;
    color: var(--color-text-secondary);
    line-height: 1.5;
    margin: 0;
}
.resource-premium-arrow {
    color: var(--color-text-muted);
    flex-shrink: 0;
    margin-left: auto;
    transition: all 0.3s ease;
}
.resource-premium:hover .resource-premium-arrow {
    transform: translateX(6px);
    color: var(--rc, var(--color-accent));
}

/* ── CTA ────────────────────────────────────────────────────────── */
.lp-cta {
    padding: 0 24px 100px;
    background: var(--color-bg);
    transition: background var(--motion);
}
.cta-premium {
    position: relative;
    border-radius: 24px;
    overflow: hidden;
    background: linear-gradient(135deg, #0A1628 0%, #1A365D 50%, #0F1C3F 100%);
    padding: 60px 40px;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.06);
}
.cta-premium-bg { position: absolute; inset: 0; pointer-events: none; }
.cta-glow {
    position: absolute;
    top: -50%; left: -30%;
    width: 800px; height: 800px;
    background: radial-gradient(circle, rgba(245,158,11,0.04) 0%, transparent 60%);
    border-radius: 50%;
    animation: ctaGlow 8s infinite alternate ease-in-out;
}
@keyframes ctaGlow {
    0% { transform: translate(0, 0) scale(1); opacity: 0.5; }
    100% { transform: translate(100px, -60px) scale(1.3); opacity: 0.8; }
}
.cta-premium-content {
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin: 0 auto;
}
.cta-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #fbbf24;
    background: rgba(245, 158, 11, 0.1);
    border: 1px solid rgba(245, 158, 11, 0.2);
    padding: 6px 18px;
    border-radius: 100px;
    margin-bottom: 20px;
}
.cta-premium-title {
    font-family: var(--font-display);
    font-size: clamp(24px, 3vw, 36px);
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 16px;
    line-height: 1.2;
}
.cta-premium-desc {
    font-size: 15px;
    line-height: 1.75;
    color: rgba(255, 255, 255, 0.7);
    max-width: 500px;
    margin: 0 auto 32px;
}
.cta-premium-actions {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
}

/* ── RESPONSIVE ───────────────────────────────────────────────────── */
@media (max-width: 960px) {
    .bento-grid { grid-template-columns: repeat(2, 1fr); }
    .bento-wide, .bento-tall { grid-column: span 1; grid-row: span 1; }
    .stats-grid-premium { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 768px) {
    .lp-hero { min-height: 90vh; padding: 80px 20px 40px; }
    .hero-actions { flex-direction: column; align-items: center; }
    .btn-prime, .btn-ghost { width: 100%; max-width: 340px; justify-content: center; }
    .lp-about { padding: 64px 0; }
    .about-intro { margin-bottom: 36px; }
    .stats-grid-premium { grid-template-columns: 1fr; }
    .resources-grid-premium { grid-template-columns: 1fr; }
    .lp-resources { padding: 0 0 64px; }
    .cta-premium { padding: 40px 24px; }
    .cta-premium-actions { flex-direction: column; align-items: center; }
    .cta-premium-actions .btn-prime { max-width: 340px; }
    .lp-cta { padding: 0 20px 64px; }
    .resource-premium-arrow { display: none; }
}
@media (max-width: 640px) {
    .bento-grid { grid-template-columns: 1fr; }
    .stat-premium { flex-direction: column; text-align: center; padding: 24px 20px; }
    .stat-premium-body { align-items: center; }
}
@media (max-width: 480px) {
    .lp-hero { min-height: 85vh; padding: 60px 16px 30px; }
}
</style>

@endsection
