<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SISIPIN DISHUB') }} — dalam pengembangan</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-light via-white to-white dark:from-primary-dark dark:via-neutral-900 dark:to-neutral-900 flex items-center justify-center p-4">
    <main class="w-full max-w-2xl text-center" role="main">
        <!-- Logo & Brand -->
        <div class="mb-10">
            <div class="flex items-center justify-center gap-4 mb-6">
                <svg class="w-16 h-16 text-primary" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/>
                </svg>
                <div class="text-left">
                    <h1 class="text-3xl font-bold text-primary-dark dark:text-primary-light">SISIPIN</h1>
                    <p class="text-base text-neutral-500 dark:text-neutral-400">DINAS PERHUBUNGAN PROVINSI LAMPUNG</p>
                </div>
            </div>
            <p class="text-lg text-neutral-600 dark:text-neutral-400">Sistem Informasi Arsip Dinamis</p>
        </div>

        <!-- Status Card -->
        <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-xl border border-neutral-200 dark:border-neutral-700 p-8 sm:p-12">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-warning/10 text-warning-dark dark:text-warning mb-6" role="status" aria-live="polite">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                <span class="font-medium text-base">Sedang dalam Pengembangan</span>
            </div>

            <h2 class="text-2xl font-semibold text-neutral-900 dark:text-white mb-4">SISIPIN DISHUB</h2>
            <p class="text-neutral-600 dark:text-neutral-400 mb-6 text-lg leading-relaxed">
                Aplikasi ini sedang dalam tahap pengembangan awal.<br>
                Fitur-fitur utama seperti manajemen surat masuk/keluar, disposisi, klasifikasi arsip, 
                dan laporan akan segera tersedia.
            </p>

            <div class="space-y-3 text-sm text-neutral-500 dark:text-neutral-400">
                <p><strong>Versi:</strong> 1.0.0 (Development)</p>
                <p><strong>Framework:</strong> Laravel {{ app()->version() }}</p>
                <p><strong>Frontend:</strong> Tailwind CSS v3 + Alpine.js + Vite</p>
            </div>

            <div class="mt-8 pt-6 border-t border-neutral-200 dark:border-neutral-700">
                <button type="button" class="btn-primary inline-flex items-center gap-2" disabled>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk ke Sistem (Belum Tersedia)
                </button>
                <p class="mt-2 text-xs text-neutral-500 dark:text-neutral-400">Fitur autentikasi akan segera tersedia</p>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-10 text-sm text-neutral-500 dark:text-neutral-400">
            <p>&copy; {{ date('Y') }} Dinas Perhubungan Provinsi Lampung</p>
            <p class="mt-1">Hak Cipta Dilindungi Undang-Undang</p>
        </footer>
    </main>
</body>
</html>