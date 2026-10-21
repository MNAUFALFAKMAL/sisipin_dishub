<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk - {{ config('app.name', 'SISIPIN DISHUB') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-light via-white to-white dark:from-primary-dark dark:via-neutral-900 dark:to-neutral-900 flex items-center justify-center p-4">
    <!-- Skip Link -->
    <a href="#login-form" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary focus:text-white focus:rounded-lg">
        Lewati ke formulir masuk
    </a>

    <div class="w-full max-w-md">
        <!-- Logo & Brand -->
        <div class="text-center mb-8">
            <div class="flex items-center justify-center gap-3 mb-4">
                <svg class="w-12 h-12 text-primary" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H6v-2h6v2zm4-4H6v-2h10v2zm0-4H6V7h10v2z"/>
                </svg>
                <div class="text-left">
                    <h1 class="text-2xl font-bold text-primary-dark dark:text-primary-light">SISIPIN</h1>
                    <p class="text-sm text-neutral-500 dark:text-neutral-400">DINAS PERHUBUNGAN PROVINSI LAMPUNG</p>
                </div>
            </div>
            <p class="text-neutral-600 dark:text-neutral-400">Sistem Informasi Arsip Dinamis</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-neutral-800 rounded-2xl shadow-xl border border-neutral-200 dark:border-neutral-700 p-6 sm:p-8" role="main">
            <header class="mb-6">
                <h2 id="login-heading" class="text-xl font-semibold text-neutral-900 dark:text-white">Masuk ke Akun Anda</h2>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Gunakan kredensial dinas untuk mengakses sistem</p>
            </header>

            <!-- Flash Messages -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-lg text-green-800 dark:text-green-200 text-sm" role="alert" aria-live="polite">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-700 rounded-lg text-red-800 dark:text-red-200 text-sm" role="alert" aria-live="assertive">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-4" novalidate>
                @csrf

                <!-- Surel -->
                <div>
                    <label for="surel" class="label">Surel</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input 
                            type="email" 
                            id="surel" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email"
                            autofocus
                            placeholder="nama@dishub.lampungprov.go.id"
                            class="input pl-10 @error('email') input-error @enderror"
                            aria-describedby="@error('email') email-error @else email-hint @enderror"
                            aria-invalid="@error('email') true @else false @enderror"
                        >
                    </div>
                    @error('email')
                        <p id="email-error" class="mt-1.5 text-sm text-danger" role="alert">{{ $message }}</p>
                    @else
                        <p id="email-hint" class="mt-1.5 text-sm text-neutral-500 dark:text-neutral-400">Format: nama@dishub.lampungprov.go.id</p>
                    @enderror
                </div>

                <!-- Kata Sandi -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="kata_sandi" class="label mb-0">Kata Sandi</label>
                        <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">Lupa kata sandi?</a>
                    </div>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <input 
                            type="password" 
                            id="kata_sandi" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi"
                            class="input pl-10 pr-12 @error('password') input-error @enderror"
                            aria-describedby="@error('password') password-error @else password-hint @enderror"
                            aria-invalid="@error('password') true @else false @enderror"
                        >
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword"
                            x-data="{ showPassword: false }"
                            :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                            :aria-pressed="showPassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300 transition-colors"
                        >
                            <span x-show="!showPassword" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </span>
                            <span x-show="showPassword" aria-hidden="true">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                    @error('password')
                        <p id="password-error" class="mt-1.5 text-sm text-danger" role="alert">{{ $message }}</p>
                    @else
                        <p id="password-hint" class="mt-1.5 text-sm text-neutral-500 dark:text-neutral-400">Masukkan kata sandi Anda</p>
                    @enderror
                </div>

                <!-- Ingat Saya -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="ingat_saya" 
                        name="remember" 
                        class="w-4 h-4 text-primary border-neutral-300 rounded focus:ring-primary focus:ring-2"
                        aria-describedby="remember-hint"
                    >
                    <label for="ingat_saya" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300 cursor-pointer">
                        Ingat saya pada perangkat ini
                    </label>
                    <p id="remember-hint" class="sr-only">Centang untuk tetap masuk selama 30 hari pada perangkat ini</p>
                </div>

                <!-- Tombol Masuk -->
                <button 
                    type="submit" 
                    class="btn-primary w-full py-3 text-base"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk
                </button>
            </form>
        </div>

        <!-- Footer Info -->
        <footer class="mt-6 text-center text-sm text-neutral-500 dark:text-neutral-400">
            <p>&copy; {{ date('Y') }} Dinas Perhubungan Provinsi Lampung</p>
            <p class="mt-1">SISIPIN DISHUB v1.0.0 — Sistem Informasi Arsip Dinamis</p>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>