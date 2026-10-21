<x-app-layout>
    @section('robots')
    <meta name="robots" content="noindex">
    @endsection

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Edit Pengguna: {{ $user->name }}</h1>
                <p class="text-slate-500 mt-1">Perbarui informasi pengguna</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 001.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-medium">Terdapat kesalahan pada formulir:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('name') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Masukkan nama lengkap" required autocomplete="name">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username/Email --}}
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1">Username / Email <span class="text-red-500">*</span></label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->email) }}"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('username') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Masukkan username atau email" required autocomplete="username">
                    @error('username')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">Digunakan untuk login ke sistem</p>
                </div>

                {{-- Password (Optional) --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password Baru <span class="text-slate-400">(Kosongkan jika tidak diubah)</span></label>
                    <div class="relative flex items-center">
                        <input type="password" name="password" id="password"
                            class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('password') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                            placeholder="Masukkan password baru" autocomplete="new-password" minlength="8">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg id="eye-icon-password" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-off-icon-password" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-slate-500">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</p>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner @error('password_confirmation') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        placeholder="Ulangi password baru" autocomplete="new-password">
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Peran --}}
                <div>
                    <label for="peran_id" class="block text-sm font-semibold text-slate-700 mb-1">Peran <span class="text-red-500">*</span></label>
                    <select name="peran_id" id="peran_id"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right @error('peran_id') border-red-300 focus:border-red-500 focus:ring-red-200 @enderror"
                        required>
                        <option value="">Pilih Peran</option>
                        @foreach($perans as $peran)
                            <option value="{{ $peran->id }}" {{ old('peran_id', $user->peran_id) == $peran->id ? 'selected' : '' }}>
                                {{ $peran->nama }} ({{ $peran->slug }})
                            </option>
                        @endforeach
                    </select>
                    @error('peran_id')
                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bidang --}}
                <div>
                    <label for="bidang_id" class="block text-sm font-semibold text-slate-700 mb-1">Bidang / Unit Kerja</label>
                    <select name="bidang_id" id="bidang_id"
                        class="block w-full px-4 py-3 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right">
                        <option value="">Pilih Bidang (Opsional)</option>
                        @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}" {{ old('bidang_id', $user->bidang_id) == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->nama_bidang }} ({{ $bidang->kode_bidang }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Aktif --}}
                <div>
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-brand shadow-sm focus:border-brand focus:ring focus:ring-brand/20">
                        <label for="is_active" class="ml-2 text-sm text-slate-600 font-medium">Aktif</label>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                    <a href="{{ route('users.index') }}"
                        class="inline-flex items-center px-4 py-3 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 text-sm font-medium text-white bg-gradient-to-r from-brand to-accent rounded-xl shadow-md hover:from-brand-dark hover:to-accent-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const eyeIcon = document.getElementById(`eye-icon-${inputId}`);
        const eyeOffIcon = document.getElementById(`eye-off-icon-${inputId}`);

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }
</script>
@endpush