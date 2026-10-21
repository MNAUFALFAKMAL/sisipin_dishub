{{-- ═══════════════════════════════════════════════════
     SIDEBAR — SISIPIN DISHUB (Sistem Informasi Arsip Internal Dishub Provinsi Lampung)
     Warna: color.surface.raised = #1e3a8a (DESIGN.md)
     ═══════════════════════════════════════════════════ --}}

@php
    $role    = Auth::user()->role ?? '';
    $current = request()->route()->getName();
@endphp

<style>
/* ── Sidebar custom classes (extracted from inline styles) ── */
.sb-logo-container {
    display: flex;
    align-items: center;
    gap: 10px;
    height: 60px;
    padding: 0 16px;
    flex-shrink: 0;
    border-bottom: 1px solid rgba(255,255,255,.1);
}
.sb-logo-wrapper {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: var(--siger);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: visible;
}
.sb-logo-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    padding: 2px;
    filter: drop-shadow(0 0 .5px white) drop-shadow(0 0 1px rgba(255,255,255,0.7));
}
.sb-brand-title {
    font-size: 17px;
    font-weight: 800;
    color: #fff;
    line-height: 1.2;
    margin: 0;
    letter-spacing: 0.02em;
}
.sb-brand-highlight {
    color: var(--siger);
}
.sb-brand-subtitle {
    font-size: 12px;
    color: rgba(255,255,255,.5);
    margin: 0;
    letter-spacing: .08em;
}
.sb-nav {
    flex: 1;
    overflow-y: auto;
    padding: 12px 8px;
}
.sb-section {
    margin-top: 16px;
}
.sb-sub-label {
    padding: 8px 16px 4px 16px;
    font-size: 11px;
    font-weight: 700;
    color: rgba(255,255,255,0.4);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.sb-sub-label-lg {
    padding-top: 16px;
}
.sb-user-footer {
    flex-shrink: 0;
    border-top: 1px solid rgba(255,255,255,.1);
    padding: 14px 16px;
    background: rgba(0,0,0,0.15);
}
.sb-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
}
.sb-user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--siger);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}
.sb-user-details {
    min-width: 0;
    flex: 1;
}
.sb-user-name {
    margin: 0;
    font-size: 15px;
    font-weight: 700;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sb-user-role {
    margin: 0;
    font-size: 12px;
    color: rgba(255,255,255,.5);
    text-transform: capitalize;
}
.sb-actions {
    display: flex;
    gap: 14px;
    border-top: 1px solid rgba(255,255,255,0.08);
    padding-top: 10px;
}
.sb-action-link {
    font-size: 13px;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 150ms;
}
.sb-action-link:hover {
    color: #fff;
}
.sb-logout-form {
    margin: 0;
    display: inline;
}
.sb-logout-btn {
    background: none;
    border: none;
    padding: 0;
    font-size: 13px;
    color: #f87171;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 150ms;
}
.sb-logout-btn:hover {
    color: #ef4444;
}
/* ── Input Tahun (kontras adaptif) ──
   @tailwindcss/forms memaksa background #fff pada input[type=number]
   di light mode (mengalahkan utility bg-white/10), sehingga teks putih
   tidak terlihat. Kelas ini mengunci background + warna teks secara
   eksplisit: light = field terang + teks gelap, dark = field gelap + teks terang. */
.sb-tahun-input {
    background-color: #ffffff !important;
    color: #1e293b !important;
    border-color: rgba(15, 23, 42, 0.2) !important;
    /* color-scheme light = panah spinner native tetap gelap di atas field putih */
    color-scheme: light !important;
}
.sb-tahun-input::placeholder {
    color: #64748b !important;
    opacity: 1;
}
.sb-tahun-input.sb-tahun-input-error {
    border-color: #f87171 !important;
}
.dark .sb-tahun-input {
    background-color: rgba(255, 255, 255, 0.1) !important;
    color: #f1f5f9 !important;
    border-color: rgba(255, 255, 255, 0.15) !important;
    /* color-scheme dark = panah spinner native jadi terang di atas field gelap */
    color-scheme: dark !important;
}
.dark .sb-tahun-input::placeholder {
    color: rgba(255, 255, 255, 0.4) !important;
}
.dark .sb-tahun-input.sb-tahun-input-error {
    border-color: #f87171 !important;
}
/* ── Spinner arrows (▲/▼) Tambah Tahun ──
   Terbukti Chromium TIDAK menerapkan background/filter/opacity pada
   pseudo-element ::-webkit-inner/outer-spin-button (selalu default).
   Solusi andal & lintas-browser: properti `color-scheme` pada elemen input
   (light = panah gelap di field putih, dark = panah terang di field gelap)
   yang disetel di blok .sb-tahun-input / .dark .sb-tahun-input di atas.
   Firefox memakai spinner native OS-nya dan ikut mengikuti color-scheme. */
</style>

{{-- ── Logo & Brand ─────────────────────────────── --}}
<div class="sb-logo-container">

<div class="sb-logo-wrapper">
        <img src="{{ asset('images/logo-dishub.png') }}" class="sb-logo-img" alt="Logo Dishub" />
    </div>
    <div>
        <p class="sb-brand-title">SISIPIN<span class="sb-brand-highlight">DISHUB</span></p>
        <p class="sb-brand-subtitle">Prov. Lampung</p>
    </div>
</div>

{{-- ── Navigation ────────────────────────────────── --}}
<nav class="sb-nav scrollbar-hide" aria-label="Navigasi modul">

    {{-- Dashboard --}}
    <a href="{{ route('dashboard') }}"
       class="nav-link {{ Str::startsWith($current, 'dashboard') ? 'active' : '' }}"
       aria-current="{{ Str::startsWith($current, 'dashboard') ? 'page' : 'false' }}">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span>Dashboard</span>
    </a>

    {{-- Panduan Pengguna (Pembelajaran) --}}
    <a href="{{ route('pembelajaran') }}"
       class="nav-link {{ Str::startsWith($current, 'pembelajaran') ? 'active' : '' }}"
       aria-current="{{ Str::startsWith($current, 'pembelajaran') ? 'page' : 'false' }}">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <span>Panduan (Pembelajaran)</span>
    </a>

    {{-- ── Surat Menyurat ─────────────────────── --}}
    @php
        $curTahun = request('tahun');
        $curJenis = request('jenis_surat');
        $curRoute = request()->route()->getName();
        $sidebarHasTahun = (request()->has('tahun') || $errors->has('tahun') || session('tahun_arsip_baru')) ? 'true' : 'false';
        $sidebarTahunAktif = is_numeric($curTahun) ? "'" . $curTahun . "'" : "''";
        $sidebarAddTahunOpen = $errors->has('tahun') || session('tahun_arsip_baru') ? 'true' : 'false';
        $tahunBerikutnya = $tahunArsipList->isNotEmpty() ? (int) $tahunArsipList->first() + 1 : (int) date('Y');
        $tahunBerikutnya = max(2000, min($tahunBerikutnya, 2100));
    @endphp
    <div class="sb-section"
         x-data="{
            arsipOpen: {{ $sidebarHasTahun }},
            tahunOpen: {{ $sidebarHasTahun }},
            tahunAktif: {{ $sidebarTahunAktif }},
            addTahunOpen: {{ $sidebarAddTahunOpen }},
            hapusTahun: null
         }">
        <p class="sidebar-section-label">Surat Menyurat</p>

        {{-- Arsip Surat (dropdown induk) --}}
        <button type="button"
                @click="arsipOpen = !arsipOpen"
                class="nav-link w-full justify-between text-left"
                :aria-expanded="arsipOpen ? 'true' : 'false'">
            <span class="flex items-center gap-3">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Arsip Surat</span>
            </span>
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                 class="transition-transform duration-200" :class="arsipOpen ? 'rotate-180' : ''" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="arsipOpen" x-transition.opacity x-cloak>
            {{-- Tahun (dropdown tahun) --}}
            <button type="button"
                    @click="tahunOpen = !tahunOpen"
                    class="nav-link w-full justify-between text-left"
                    :aria-expanded="tahunOpen ? 'true' : 'false'">
                <span class="flex items-center gap-3">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Tahun</span>
                </span>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                     class="transition-transform duration-200" :class="tahunOpen ? 'rotate-180' : ''" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="tahunOpen" x-transition.opacity x-cloak>
                @forelse($tahunArsipList as $tahun)
                    {{-- Satu tahun: klik → expand 7 sub-menu --}}
                    <div>
                        <div class="flex items-center">
                            <button type="button"
                                    @click="tahunAktif = (tahunAktif === '{{ $tahun }}' ? '' : '{{ $tahun }}')"
                                    class="nav-link flex-1 justify-between text-left"
                                    :aria-expanded="tahunAktif === '{{ $tahun }}' ? 'true' : 'false'">
                                <span>{{ $tahun }}</span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                     class="transition-transform duration-200" :class="tahunAktif === '{{ $tahun }}' ? 'rotate-180' : ''" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            @if($tahunArsipTersimpan->contains((int) $tahun))
                            <button type="button"
                                    @click.stop="hapusTahun = '{{ $tahun }}'"
                                    class="nav-link !w-auto shrink-0 px-2 text-white/40 transition hover:text-red-300"
                                    :aria-label="'Hapus tahun {{ $tahun }}'"
                                    title="Hapus tahun {{ $tahun }}">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                            @endif
                        </div>

                        {{-- 7 sub-menu jenis surat (scoped ke tahun) --}}
                        <div x-show="tahunAktif === '{{ $tahun }}'" x-cloak class="ml-3 border-l border-white/10 pl-1">
                            <a href="{{ route('surat-masuk.index', ['jenis_surat' => 'Surat Masuk', 'tahun' => $tahun]) }}"
                               class="nav-link {{ Str::startsWith($curRoute, 'surat-masuk') && $curJenis == 'Surat Masuk' && $curTahun == (string) $tahun ? 'active' : '' }}">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>Surat Masuk</span>
                            </a>
                            <a href="{{ route('surat-masuk.index', ['jenis_surat' => 'Undangan Masuk', 'tahun' => $tahun]) }}"
                               class="nav-link {{ Str::startsWith($curRoute, 'surat-masuk') && $curJenis == 'Undangan Masuk' && $curTahun == (string) $tahun ? 'active' : '' }}">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Undangan Masuk</span>
                            </a>
                            <a href="{{ route('surat-keluar.index', ['jenis_surat' => 'Surat Keluar', 'tahun' => $tahun]) }}"
                               class="nav-link {{ Str::startsWith($curRoute, 'surat-keluar') && $curJenis == 'Surat Keluar' && $curTahun == (string) $tahun ? 'active' : '' }}">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <span>Surat Keluar</span>
                            </a>
                            <a href="{{ route('surat-keluar.index', ['jenis_surat' => 'Undangan Keluar', 'tahun' => $tahun]) }}"
                               class="nav-link {{ Str::startsWith($curRoute, 'surat-keluar') && $curJenis == 'Undangan Keluar' && $curTahun == (string) $tahun ? 'active' : '' }}">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Undangan Keluar</span>
                            </a>
                            <a href="{{ route('surat-keluar.index', ['jenis_surat' => 'SPPD', 'tahun' => $tahun]) }}"
                               class="nav-link {{ Str::startsWith($curRoute, 'surat-keluar') && $curJenis == 'SPPD' && $curTahun == (string) $tahun ? 'active' : '' }}">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10 M13 8h6a1 1 0 011 1v4l-4-4H13"/>
                                </svg>
                                <span>SPPD</span>
                            </a>
                            <a href="{{ route('surat-keluar.index', ['jenis_surat' => 'SK (Surat Keterangan)', 'tahun' => $tahun]) }}"
                               class="nav-link {{ Str::startsWith($curRoute, 'surat-keluar') && $curJenis == 'SK (Surat Keterangan)' && $curTahun == (string) $tahun ? 'active' : '' }}">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>SK</span>
                            </a>
                            <a href="{{ route('surat-keluar.index', ['jenis_surat' => 'SPT', 'tahun' => $tahun]) }}"
                               class="nav-link {{ Str::startsWith($curRoute, 'surat-keluar') && $curJenis == 'SPT' && $curTahun == (string) $tahun ? 'active' : '' }}">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <span>SPT</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="sb-sub-label">Belum ada tahun. Tambahkan tahun baru di bawah.</p>
                @endforelse

                {{-- + Tambah Tahun --}}
                <button type="button"
                        @click="addTahunOpen = !addTahunOpen"
                        class="nav-link w-full justify-between text-left"
                        :aria-expanded="addTahunOpen ? 'true' : 'false'">
                    <span class="flex items-center gap-3">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Tambah Tahun</span>
                    </span>
                </button>
                <form x-show="addTahunOpen" x-cloak method="POST" action="{{ route('tahun-arsip.store') }}"
                      class="space-y-2 px-4 pb-3">
                    @csrf
                    <input type="number" name="tahun" min="2000" max="2100" required
                           placeholder="contoh: 2027"
                           value="{{ old('tahun', $tahunBerikutnya) }}"
                           class="sb-tahun-input w-full rounded-md border px-3 py-2 text-sm focus:border-white/30 focus:outline-none focus:ring-1 focus:ring-white/20 {{ $errors->has('tahun') ? 'sb-tahun-input-error' : '' }}" />
                    @error('tahun')
                        <p class="text-xs font-medium text-red-400">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                            class="w-full rounded-md bg-[var(--siger)] px-3 py-2 text-sm font-semibold text-[#1a365d] transition hover:opacity-90">
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        {{-- Modal konfirmasi hapus tahun --}}
        <div x-show="hapusTahun !== null" x-cloak
             class="fixed inset-0 z-[70] flex items-center justify-center bg-black/60 p-4"
             @keydown.escape.window="hapusTahun = null">
            <div class="w-full max-w-sm rounded-2xl bg-white p-6 shadow-2xl dark:bg-slate-800">
                <h3 class="text-lg font-bold text-gray-800 dark:text-slate-100">Hapus Tahun Arsip</h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-slate-300">
                    Yakin ingin menghapus tahun <strong x-text="hapusTahun"></strong> dari navigasi?
                    Tahun hanya bisa dihapus bila tidak ada surat masuk/keluar yang tercatat pada tahun tersebut.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="hapusTahun = null" class="btn-secondary">Batal</button>
                    <form method="POST" :action="`{{ url('tahun-arsip') }}/${hapusTahun}`" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary border-red-600 bg-red-600 hover:bg-red-700">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{-- ── SIKD — Kearsipan Dinamis ────────────── --}}
    @if(in_array($role, ['super_admin', 'tata_usaha']))
    <div class="sb-section">
        <p class="sidebar-section-label">Kearsipan Dinamis (SIKD)</p>

        {{-- Klasifikasi Arsip --}}
        <a href="{{ route('klasifikasi-arsip.index') }}"
           class="nav-link {{ Str::startsWith($current, 'klasifikasi-arsip') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span>Klasifikasi Arsip</span>
        </a>

        {{-- Data Pegawai --}}
        <a href="{{ route('pegawai.index') }}"
           class="nav-link {{ Str::startsWith($current, 'pegawai') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Data Pegawai</span>
        </a>

        {{-- Manajemen Template --}}
        <a href="{{ route('templates.index') }}"
           class="nav-link {{ Str::startsWith($current, 'templates') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            <span>Manajemen Template</span>
        </a>

        {{-- Utilitas PDF --}}
        <a href="{{ route('pdf-utility.index') }}"
           class="nav-link {{ Str::startsWith($current, 'pdf-utility') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <span>Utilitas PDF</span>
        </a>

        {{-- Generate Dokumen KP4 --}}
        <a href="{{ route('kp4.index') }}"
           class="nav-link {{ Str::startsWith($current, 'kp4') ? 'active' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Generate KP4</span>
        </a>
    </div>
    @endif

    {{-- ── Admin ─────────────────────────────────── --}}
    @if($role === 'super_admin')
    <div class="sb-section">
        <p class="sidebar-section-label">Administrasi</p>

        <a href="{{ route('users.index') }}"
           class="nav-link {{ Str::startsWith($current, 'users') ? 'active' : '' }}"
           aria-current="{{ Str::startsWith($current, 'users') ? 'page' : 'false' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span>Manajemen Pengguna</span>
        </a>

        <a href="{{ route('audit-logs.index') }}"
           class="nav-link {{ Str::startsWith($current, 'audit-logs') ? 'active' : '' }}"
           aria-current="{{ Str::startsWith($current, 'audit-logs') ? 'page' : 'false' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 00-2 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span>Audit Log</span>
        </a>
    </div>
    @endif

</nav>

{{-- ── User Footer ─────────────────────────────── --}}
<div class="sb-user-footer">
    <div class="sb-user-info">
<div class="sb-user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="sb-user-details">
<p class="sb-user-name">
                {{ Auth::user()->name }}
            </p>
<p class="sb-user-role">
                {{ Auth::user()->role_label }}
            </p>
        </div>
    </div>
    <div class="sb-actions">
        <a href="{{ route('home') }}" class="sb-action-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Beranda
        </a>
        <form method="POST" action="{{ route('logout') }}" class="sb-logout-form">
            @csrf
            <button type="submit" class="sb-logout-btn">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</div>
