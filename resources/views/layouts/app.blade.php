<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — SISIPIN DISHUB LAMPUNG</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-dishub.png') }}?v=1" />

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet"/>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        .flatpickr-day.selected, .flatpickr-day.selected:hover, .flatpickr-day.selected:focus {
            background: #1e3a8a !important;
            border-color: #1e3a8a !important;
        }
        .flatpickr-months .flatpickr-month {
            background: #1e3a8a !important;
            color: #fff !important;
        }
        .flatpickr-current-month .numInputWrapper span.arrowUp:after {
            border-bottom-color: #1e3a8a !important;
        }
        .flatpickr-current-month .numInputWrapper span.arrowDown:after {
            border-top-color: #1e3a8a !important;
        }
        .flatpickr-months .flatpickr-prev-month svg,
        .flatpickr-months .flatpickr-next-month svg {
            fill: #ffffff !important;
        }
        .flatpickr-months .flatpickr-prev-month:hover svg,
        .flatpickr-months .flatpickr-next-month:hover svg {
            fill: #f59e0b !important;
        }
        /* Dropdown bulan flatpickr: teks & latar dibuat kontras agar terlihat (light & dark) */
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            background: #1e3a8a !important;
            color: #fff !important;
            border-radius: 4px;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months:hover {
            background: #16307a !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month,
        .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #ffffff !important;
            color: #1e293b !important;
        }
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month,
        .dark .flatpickr-current-month .flatpickr-monthDropdown-months option {
            background: #161b27 !important;
            color: #e2e8f0 !important;
        }
    </style>

    <style>
        body { font-family: 'Inter', system-ui, sans-serif; font-size: 16px; line-height: 1.6; -webkit-font-smoothing: antialiased; }
        .app-shell { display: flex; height: 100vh; overflow: hidden; }

        .sidebar { width: 240px; flex-shrink: 0; background: #1A365D; display: flex; flex-direction: column; height: 100vh; position: fixed; left: 0; top: 0; bottom: 0; z-index: 40; transition: transform 300ms ease; overflow: hidden; }
        .sidebar.sidebar--hidden { transform: translateX(-100%); }

        .main-area { flex: 1; display: flex; flex-direction: column; overflow: hidden; margin-left: 240px; transition: margin-left 300ms ease; }
        .main-area.main--full { margin-left: 0; }

        .topbar { height: 60px; flex-shrink: 0; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; position: sticky; top: 0; z-index: 30; }
        .page-content { flex: 1; overflow-y: auto; padding: 12px; }
        @media (min-width: 640px) { .page-content { padding: 20px; } }

        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45); z-index: 35; }
        .sidebar-overlay.overlay--visible { display: block; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.sidebar--open { transform: translateX(0); }
            .main-area { margin-left: 0; }
        }
        @media (min-width: 1024px) {
            .sidebar { transform: translateX(0) !important; }
            .sidebar-overlay { display: none !important; }
            [data-sidebar-toggle] { display: none !important; }
        }
    </style>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body>

<div class="app-shell">
    <aside class="sidebar" id="sidebar" role="navigation" aria-label="Menu utama">
        @include('layouts.sidebar')
    </aside>

    <div class="sidebar-overlay" id="sidebar-overlay" aria-hidden="true"></div>

    <div class="main-area" id="main-area">
        @include('layouts.topbar')

        @if(session('success') || session('error'))
        <div style="padding: 12px 20px 0">
            @if(session('success'))
            <div role="alert" class="flex items-center gap-2.5 rounded-lg px-3.5 py-2.5 text-[13px] mb-2 border border-green-200 bg-green-50 text-green-800 dark:border-green-700/50 dark:bg-green-900/20 dark:text-green-300">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div role="alert" class="flex items-center gap-2.5 rounded-lg px-3.5 py-2.5 text-[13px] mb-2 border border-red-200 bg-red-50 text-red-800 dark:border-red-700/50 dark:bg-red-900/20 dark:text-red-300">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-5h2v2H9v-2zm0-8h2v6H9V5z" clip-rule="evenodd"/></svg>
                {{ session('error') }}
            </div>
            @endif
        </div>
        @endif

        <main class="page-content" id="page-content">
            @yield('content')
            {{ $slot ?? '' }}
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
