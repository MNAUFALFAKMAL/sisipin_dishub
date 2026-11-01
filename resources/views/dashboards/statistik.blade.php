<!DOCTYPE html>
<html lang="id" x-data="{
    // Alpine.js state untuk statistik dashboard
    stats: null,
    perBulan: [],
    perBidang: [],
    isLoading: false,
    error: null,
    filterBulan: 'all',
    filterBidang: 'all',
    currentWidth: '1366'
}" @keydown.escape.window="closeError">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Statistik & Grafik Surat — SISIPIN DISHUB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@^4.4.0"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        navy: '#0F1C3F',
                        darknavy: '#1A365D',
                        gold: '#f59e0b',
                        midnight: '#111827',
                        subtle: '#f1f5f9',
                    }
                }
            }
        };
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wghtlight@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-subtle min-h-screen">

<!-- Navbar -->
<nav class="bg-navy text-white border-b border-gray-200 dark:bg-darknavy" x-data="{
    currentPage: 'statistik'
}">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" class="group-hover:text-gold" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="font-semibold tracking-wide">Statistik & Grafik Surat</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="/dashboard" class="text-white hover:text-gold transition-colors text-sm font-medium">Kembali ke Dashboard</a>
        </div>
    </div>
</nav>

<!-- Breadcrumb -->
<nav class="bg-white dark:bg-neutral-900 border-b border-gray-200/20" x-data="{
    showMobileMenu: false
}">
    <div class="max-w-7xl mx-auto px-4 py-2 flex items-center">
        <ol class="flex flex-wrap items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
            <li><a href="/" class="hover:text-navy transition-colors">Beranda</a></li>
            <li aria-current="page"><span class="text-navy font-medium">Dashboard</span> / <span class="text-gray-500">Statistik</span></li>
        </ol>
    </div>
</nav>

<!-- Main Content -->
<main class="max-w-7xl mx-auto p-4 md:p-6">

    <!-- Kartu Statistik Row -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mb-6">
        <!-- Card: Total Masuk -->
        <article class="bg-white dark:bg-neutral-900 rounded-2xl border border-gray-200/20 p-5 shadow-sm transform hover:translate-y-1 hover:shadow-md transition-all" x-data="cardStats('masuk')" @click="refreshData">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-navy/10 flex items-center justify-center flex-shrink-0">
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 4v6H6zm5.5-4.5c0-1.05-.85-2-2-2S10 4.95 10 6c0 1.05.85 2 2 2s2-.95 2-2c0-93 53-119 89-119c16 0 33 12 33 33c0 5.5-2 9.5-5.5 11.5L17.5 15.5c.03-.2.06-.4.06-.6z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate" title="Total Surat Masuk">Total Surat Masuk</p>
                    <p class="text-2xl font-bold text-navy dark:text-gold mt-1" x-text="cardTotal('masuk')"></p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="cardTrend('masuk')"></p>
                </div>
            </div>
            <button type="button" class="mt-3 w-full py-2 bg-navy text-white rounded-md text-sm font-medium hover:bg-darknavy transition-colors" @click="openError('loading')">
                Muat Ulang
            </button>
        </article>

        <!-- Card: Total Keluar -->
        <article class="bg-white dark:bg-neutral-900 rounded-2xl border border-gray-200/20 p-5 shadow-sm transform hover:translate-y-1 hover:shadow-md transition-all" x-data="cardStats('keluar')" @click="refreshData">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-navy/10 flex items-center justify-center flex-shrink-0">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Total Surat Keluar</p>
                    <p class="text-2xl font-bold text-navy dark:text-gold mt-1" x-text="cardTotal('keluar')"></p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="cardTrend('keluar')"></p>
                </div>
            </div>
            <button type="button" class="mt-3 w-full py-2 bg-navy text-white rounded-md text-sm font-medium hover:bg-darknavy transition-colors" @click="openError('loading')">
                Muat Ulang
            </button>
        </article>

        <!-- Card: Belum Disposisi -->
        <article class="bg-white dark:bg-neutral-900 rounded-2xl border border-yellow-500/20 p-5 shadow-sm transform hover:translate-y-1 hover:shadow-md transition-all" x-data="cardStats('belum_disposisi')" @click="refreshData">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-yellow-500/10 flex items-center justify-center flex-shrink-0">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Belum Disposisi</p>
                    <p class="text-2xl font-bold text-navy dark:text-gold mt-1" x-text="cardTotal('belum_disposisi')"></p>
                    <p class="text-xs text-yellow-600 dark:text-yellow-400 mt-1" x-text="cardTrend('belum_disposisi')"></p>
                </div>
            </div>
            <button type="button" class="mt-3 w-full py-2 bg-navy text-white rounded-md text-sm font-medium hover:bg-darknavy transition-colors" @click="openError('loading')">
                Muat Ulang
            </button>
        </article>

        <!-- Card: Surat Bulan Ini -->
        <article class="bg-white dark:bg-neutral-900 rounded-2xl border border-gray-200/20 p-5 shadow-sm transform hover:translate-y-1 hover:shadow-md transition-all" x-data="cardStats('bulan_ini')" @click="refreshData">
            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-navy/10 flex items-center justify-center flex-shrink-0">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m5 6l4-4m-4 4l4 4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Surat Bulan Ini</p>
                    <p class="text-2xl font-bold text-navy dark:text-gold mt-1" x-text="cardTotal('bulan_ini')"></p>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">dari <?= now()->format('F Y') ?></p>
                </div>
            </div>
            <button type="button" class="mt-3 w-full py-2 bg-navy text-white rounded-md text-sm font-medium hover:bg-darknavy transition-colors" @click="openError('loading')">
                Muat Ulang
            </button>
        </article>
    </div>

    <!-- State: Loading -->
    <div x-show="isLoading" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 justify-center items-center py-12">
        <div class="col-span-full text-center">
            <div class="flex justify-center items-center h-12 border rounded w-16 h-16 mx-auto bg-navy/5 text-navy border-navy">
                <svg class="animate-spin h-6 w-6 text-navy" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/></svg>
            </div>
            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">Memuat statistik...</p>
        </div>
    </div>

    <!-- State: Error -->
    <div x-show="error" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50" id="error-modal">
        <div class="bg-white dark:bg-neutral-900 rounded-2xl p-8 max-w-md w-full text-center shadow-xl transform scale-100 transition-transform">
            <div class="w-16 h-16 rounded-full bg-red-100 mx-auto mb-4 flex items-center justify-center">
                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 0h.01m-6.938 4h13.86c4.161 0 6.938 3.385 6.938 6.938 0 3.162-1.25 5.878-3.538 7.523a14.92 14.92 0 01-7 2c-4.028 0-5.73-1.808-6.175-4.492a14.95 14.95 0 01-1-7.304 14.95 14.95 0 00-7.304 1 14.95 14.95 0 01-1.73.438c3.254 1.805 5.56 4.615 6.73 7.293a13.98 13.98 0 01-2.02 3.143 13.98 13.98 0 01-2.02-3.143 13.98 13.98 0 01.73-7.293c1.068-1.267 2.98-4.492 6.73-7.293a14.92 14.92 0 01-7 2c-4.006 0-5.73 1.808-6.175 4.492a14.95 14.95 0 01-1-7.304 14.95 14.95 0 00-7.304 1 14.95 14.95 0 01-1.73.438z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-navy dark:text-gold mb-2">Gagal Memuat</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Terjadi kesalahan saat memuat statistik. Coba lagi nanti.</p>
            <button id="error-retry" class="px-6 py-2 bg-navy text-white rounded-md text-sm font-medium hover:bg-darknavy transition-colors">Coba Lagi</button>
        </div>
    </div>

    <!-- Grafik Section -->
    <section class="mt-8 rounded-2xl border border-gray-200/20 bg-white dark:bg-neutral-900 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-navy dark:text-gold">Tren Surat per Bulan & Komposisi Bidang</h2>
            <div class="flex gap-2" role="tablist">
                <button role="tab" aria-selected="true" class="px-4 py-2 rounded-md bg-navy text-white text-sm font-medium" data-tab="tren">Tren Bulan</button>
                <button role="tab" aria-selected="false" class="px-4 py-2 rounded-md bg-gray-100 text-gray-700 text-sm font-medium" data-tab="doughnut">Komposisi Bidang</button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tren Chart -->
            <div x-show="currentTab === 'tren'" class="rounded-xl border p-4 bg-navy/5 dark:bg-navy/10">
                <canvas id="trenChart" height="300"></canvas>
            </div>

            <!-- Doughnut Chart -->
            <div x-show="currentTab === 'doughnut'" class="rounded-xl border p-4 bg-navy/5 dark:bg-navy/10">
                <canvas id="doughnutChart" height="300"></canvas>
            </div>
        </div>
    </section>

    <!-- Filter Section -->
    <section class="mt-8 rounded-xl border p-4 bg-navy/5 dark:bg-navy/10">
        <div class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" readonly
                    class="pl-8 py-2 border border-gray-300 rounded-md dark:bg-neutral-700 dark:text-white w-full focus:outline-none focus:ring-2 focus:ring-navy"
                    x-model="filterBulan"
                    @focus="applyFilter('bulan')"
                    @blur="resetFilter('bulan')" placeholder="Pilih bulan...">
            </div>

            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <select class="pl-8 py-2 border border-gray-300 rounded-md dark:bg-neutral-700 dark:text-white w-full focus:outline-none focus:ring-2 focus:ring-navy"
                    @change="applyFilter('bidang')"
                    x-model="filterBidang">
                    <option value="all">Pilih Bidang</option>
                    <option value="Semua">Semua</option>
                    <!-- Options akan diisi oleh Alpine dari per_bidang -->
                </select>
            </div>
        </div>
    </section>

</main>

<!-- Error Modal (sudah ada di atas, tapi pastikan JS) -->
<div x-show="showError" x-transition:enter="duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50" id="global-error">
    <div class="bg-white dark:bg-neutral-900 rounded-2xl p-8 max-w-md w-full text-center shadow-xl transform scale-100 transition-transform">
        <div class="w-16 h-16 rounded-full bg-red-100 mx-auto mb-4 flex items-center justify-center">
            <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 0h.01m-6.938 4h13.86c4.161 0 6.938 3.385 6.938 6.938 0 3.162-1.25 5.878-3.538 7.523a14.92 14.92 0 01-7 2c-4.028 0-5.73-1.808-6.175-4.492a14.95 14.95 0 01-1-7.304 14.95 14.95 0 00-7.304 1 14.95 14.95 0 01-1.73.438c3.254 1.805 5.56 4.615 6.73 7.293a13.98 13.98 0 01-2.02 3.143 13.98 13.98 0 01-2.02-3.143 13.98 13.98 0 01.73-7.293c1.068-1.267 2.98-4.492 6.73-7.293a14.92 14.92 0 01-7 2c-4.006 0-5.73 1.808-6.175 4.492a14.95 14.95 0 01-1-7.304 14.95 14.95 0 00-7.304 1 14.95 14.95 0 01-1.73.438z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-navy dark:text-gold mb-2">Gagal Memuat</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-6">Terjadi kesalahan saat memuat data. Coba lagi nanti.</p>
        <button id="global-error-retry" class="px-6 py-2 bg-navy text-white rounded-md text-sm font-medium hover:bg-darknavy transition-colors">Coba Lagi</button>
    </div>
</div>

<script>
    // Inisialisasi Chart.js setelah elemen ada
    document.addEventListener('alpine:init', () => {
        const ctxTren = document.getElementById('trenChart');
        if (ctxTren) {
            window.trenChart = new Chart(ctxTren, {
                type: 'bar',
                data: { labels: [], datasets: [
                    { label: 'Masuk', backgroundColor: 'rgba(59, 130, 246, 0.5)', borderColor: '#3b82f6', data: [] },
                    { label: 'Keluar', backgroundColor: 'rgba(34, 197, 94, 0.5)', borderColor: '#22c55e', data: [] }
                ]},
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 0 },
                    scales: {
                        y: { beginAtZero: true, ticks: { color: '#64748b' } },
                        x: { ticks: { color: '#64748b' } }
                    },
                    plugins: { legend: { labels: { color: '#64748b' } } }
                }
            });
        }

        const ctxDoughnut = document.getElementById('doughnutChart');
        if (ctxDoughnut) {
            window.doughnutChart = new Chart(ctxDoughnut, {
                type: 'doughnut',
                data: { labels: [], datasets: [
                    { data: [], backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#a855f7', '#ec4899', '#2563eb', '#8b5cf6']
                    }
                ]},
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 0 },
                    plugins: { legend: { labels: { color: '#64748b' } }, tooltip: { enabled: false } }
                }
            });
        }
    });

    // Helper: apply filter
    function applyFilter(type) {
        // Kirim ke backend via Alpine, lalu render chart
        alert('Filter ' + type + ' diterapkan (simulasi)');
    }

    // Helper: reset filter
    function resetFilter(type) {
        // reset filter
    }

    // Close error modal
    function closeError() {
        const modal = document.getElementById('error-modal');
        if (modal) modal.style.display = 'none';
        const globalModal = document.getElementById('global-error');
        if (globalModal) globalModal.style.display = 'none';
    }
</script>

</body>
</html>