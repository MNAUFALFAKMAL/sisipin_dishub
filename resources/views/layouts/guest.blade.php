<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SISIPIN DISHUB') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-dishub.png') }}?v=1" />

    @yield('robots')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=raleway:500,700|open-sans:400,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Open Sans', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Raleway', sans-serif; }
        .bg-gradient-animate {
            background-color: #2b0e5d; /* Dark Violet-Navy from logo */
            background-image: linear-gradient(135deg, #2b0e5d 0%, #1f0745 100%);
            background-repeat: no-repeat;
            background-position: center center;
            background-size: cover;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 20px !important; /* card-radius = 20px */
            box-shadow: none !important; /* flat depth */
        }
        button[type="submit"] {
            background: #f59e0b !important; /* Siger Gold */
            color: #000000 !important; /* Black text for high contrast */
            font-weight: 700 !important;
            border-radius: 10px !important; /* button-radius = 10px */
            box-shadow: none !important;
            transition: all 0.3s ease;
        }
        button[type="submit"]:hover {
            background: #d97706 !important;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="antialiased bg-gradient-animate min-h-screen text-slate-800 flex items-center justify-center p-4">
    
    <!-- Floating Back to Home Button -->
    <a href="{{ url('/') }}" class="absolute top-6 left-6 z-20 flex items-center gap-2 px-4 py-2.5 bg-white/90 backdrop-blur-md border border-slate-200 hover:bg-white text-slate-700 hover:text-slate-900 rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition-all duration-200">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Beranda
    </a>

    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-white opacity-20 blur-3xl mix-blend-overlay animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-white opacity-20 blur-3xl mix-blend-overlay animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="w-full max-w-md glass-panel rounded-3xl p-8 relative z-10 transform transition-all hover:scale-[1.01] duration-300">
        <div class="flex flex-col items-center mb-8 text-center">
            <div class="w-20 h-20 bg-gradient-to-tr from-brand to-siger rounded-2xl flex items-center justify-center shadow-lg shadow-brand/30 mb-4 transform rotate-3 hover:rotate-0 transition duration-300">
                <img src="{{ asset('images/logo-dishub.png') }}" class="w-full h-full object-contain p-1 rounded-2xl" style="filter: drop-shadow(0 0 .5px white) drop-shadow(0 0 1px rgba(255,255,255,0.7));" alt="Logo Dishub" />
            </div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">SISIPIN<span class="text-siger">DISHUB</span></h1>
            <p class="text-sm font-medium text-slate-500 mt-2">Sistem Informasi Arsip Internal Dishub Provinsi Lampung</p>
        </div>

        {{ $slot }}
        
        <div class="mt-8 text-center">
            <p class="text-xs text-slate-500 font-medium">&copy; {{ date('Y') }} Dinas Perhubungan Provinsi Lampung</p>
        </div>
    </div>

</body>
</html>
