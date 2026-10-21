<x-guest-layout>
    @section('robots')
    <meta name="robots" content="noindex">
    @endsection

    {{-- Status Sesi --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5" novalidate>
        @csrf

        {{-- Surel --}}
        <div>
            <label for="surel" class="label">{{ __('Surel') }}</label>
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

        {{-- Kata Sandi --}}
        <div>
            <div class="flex items-center justify-between">
                <label for="kata_sandi" class="label mb-0">{{ __('Kata Sandi') }}</label>
                <a href="{{ route('password.request') }}" class="text-sm text-primary hover:underline">{{ __('Lupa kata sandi?') }}</a>
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

        {{-- Ingat Saya --}}
        <div class="flex items-center">
            <input 
                type="checkbox" 
                id="ingat_saya" 
                name="remember" 
                class="w-4 h-4 text-primary border-neutral-300 rounded focus:ring-primary focus:ring-2"
                aria-describedby="remember-hint"
            >
            <label for="ingat_saya" class="ml-2 text-sm text-neutral-700 dark:text-neutral-300 cursor-pointer">
                {{ __('Ingat saya pada perangkat ini') }}
            </label>
            <p id="remember-hint" class="sr-only">Centang untuk tetap masuk selama 30 hari pada perangkat ini</p>
        </div>

        {{-- Tombol Masuk --}}
        <button 
            type="submit" 
            class="btn-primary w-full py-3 text-base"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            {{ __('Masuk') }}
        </button>
    </form>

</x-guest-layout>