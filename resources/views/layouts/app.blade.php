<!DOCTYPE html>
<html lang="id" class="h-full" x-data="{ sidebarOpen: false, theme: 'light' }" :class="{ dark: theme === 'dark' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SISIPIN DISHUB') }} @yield('title')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="h-full bg-neutral-50 dark:bg-neutral-900 text-neutral-900 dark:text-neutral-100 transition-colors duration-200">
    <!-- Skip Link untuk Aksesibilitas -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary focus:text-white focus:rounded-lg">
        Lewati ke konten utama
    </a>

    <!-- Mobile Sidebar Overlay -->
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        @click="sidebarOpen = false"
        aria-hidden="true"
    ></div>

    <!-- Sidebar -->
    <aside 
        x-show="sidebarOpen" 
        x-transition:enter="transition-transform ease-out duration-200"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition-transform ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-neutral-800 border-r border-neutral-200 dark:border-neutral-700 transform -translate-x-full lg:translate-x-0 lg:static lg:z-auto"
        role="navigation"
        aria-label="Navigasi utama"
    >
        <div class="flex flex-col h-full">
            <!-- Logo & Brand -->
            <div class="flex items-center gap-3 p-4 border-b border-neutral-200 dark:border-neutral-700">
                <svg class="w-8 h-8 text-primary" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/>
                </svg>
                <span class="text-xl font-bold text-primary-dark">SISIPIN</span>
                <span class="text-xs text-neutral-500 dark:text-neutral-400">DISHUB</span>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto" role="navigation" aria-label="Menu utama">
                <!-- Menu Surat -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">SURAT</h3>
                    <a href="{{ route('surat-masuk.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-primary-light dark:hover:bg-primary/20 transition-colors"
                       role="menuitem">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Surat Masuk
                    </a>
                    <a href="{{ route('surat-keluar.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-primary-light dark:hover:bg-primary/20 transition-colors"
                       role="menuitem">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Surat Keluar
                    </a>
                </div>

                <!-- Menu Klasifikasi -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">ARSIP</h3>
                    <a href="{{ route('klasifikasi-arsip.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-primary-light dark:hover:bg-primary/20 transition-colors"
                       role="menuitem">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Kode Klasifikasi
                    </a>
                    <a href="{{ route('public.tata-naskah') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-primary-light dark:hover:bg-primary/20 transition-colors"
                       role="menuitem">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Tata Naskah
                    </a>
                </div>

                <!-- Menu Laporan -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">LAPORAN</h3>
                    <a href="{{ route('laporan.index') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-primary-light dark:hover:bg-primary/20 transition-colors"
                       role="menuitem">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Laporan & Cetak
                    </a>
                </div>

                <!-- Menu Dashboard -->
                <div>
                    <h3 class="px-3 py-2 text-xs font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">UTAMA</h3>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-3 px-3 py-2.5 text-sm text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-primary-light dark:hover:bg-primary/20 transition-colors"
                       role="menuitem">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Dasbor
                    </a>
                </div>
            </nav>

            <!-- User Info & Actions -->
            <div class="p-4 border-t border-neutral-200 dark:border-neutral-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-medium">
                        {{ strtoupper(auth()->user()->name[0]) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-neutral-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">{{ auth()->user()->role ?? 'Staff Subbagian' }}</p>
                    </div>
                </div>
                <div class="space-y-1">
                    <a href="{{ route('profil.show') }}" 
                       class="flex items-center gap-3 px-3 py-2 text-sm text-neutral-700 dark:text-neutral-300 rounded-lg hover:bg-neutral-100 dark:hover:bg-neutral-800 transition-colors"
                       role="menuitem">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profil
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-3 px-3 py-2 text-sm text-danger hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors text-left"
                                role="menuitem">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:pl-64 flex flex-col min-h-screen">
        <!-- Top Header -->
        <header class="sticky top-0 z-30 bg-white dark:bg-neutral-800 border-b border-neutral-200 dark:border-neutral-700 shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                <!-- Mobile Menu Button -->
                <button 
                    @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-lg text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors"
                    aria-label="Buka menu navigasi"
                    aria-expanded="false"
                    aria-controls="sidebar"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Page Title -->
                <div class="flex-1 lg:flex-none">
                    <h1 class="text-lg lg:text-xl font-semibold text-neutral-900 dark:text-white truncate">
                        @yield('page-title', 'SISIPIN DISHUB')
                    </h1>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-2 lg:gap-4">
                    <!-- Theme Toggle -->
                    <button 
                        @click="theme = theme === 'light' ? 'dark' : 'light'; localStorage.setItem('theme', theme)"
                        class="p-2 rounded-lg text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors"
                        aria-label="Toggle tema"
                        :aria-pressed="theme === 'dark'"
                    >
                        <span x-show="theme === 'light'" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                        </span>
                        <span x-show="theme === 'dark'" aria-hidden="true">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </span>
                    </button>

                    <!-- Notifications -->
                    <div class="relative hidden sm:block">
                        <button 
                            class="p-2 rounded-lg text-neutral-600 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 transition-colors relative"
                            aria-label="Notifikasi"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-danger rounded-full" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main id="main-content" class="flex-1 p-4 lg:p-6" role="main">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-neutral-800 border-t border-neutral-200 dark:border-neutral-700 py-4 px-4 lg:px-6 lg:pl-64">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-neutral-500 dark:text-neutral-400">
                <p>&copy; {{ date('Y') }} Dinas Perhubungan Provinsi Lampung. Hak Cipta Dilindungi.</p>
                <p class="flex items-center gap-2">
                    <span>SISIPIN DISHUB v1.0.0</span>
                    <span aria-hidden="true">|</span>
                    <span>Sistem Informasi Arsip Dinamis</span>
                </p>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    <script>
        // Initialize theme from localStorage
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            } else if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>