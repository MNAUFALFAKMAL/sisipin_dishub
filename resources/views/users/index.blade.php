<x-app-layout>
    @section('robots')
    <meta name="robots" content="noindex">
    @endsection

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Manajemen Pengguna</h1>
                    <p class="text-slate-500 mt-1">Kelola pengguna sistem dan hak akses</p>
                </div>
                <a href="{{ route('users.create') }}"
                    class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-brand to-accent rounded-xl shadow-md hover:from-brand-dark hover:to-accent-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Tambah Pengguna
                </a>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center" role="alert">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Filter & Search --}}
            <form method="GET" action="{{ route('users.index') }}" class="mb-6 bg-white rounded-xl shadow-sm border border-slate-200 p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-slate-700 mb-1">Cari</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                placeholder="Cari nama atau email..." class="block w-full pl-10 pr-3 py-2.5 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium placeholder-slate-400 shadow-inner">
                        </div>
                    </div>

                    <div>
                        <label for="peran_id" class="block text-sm font-medium text-slate-700 mb-1">Filter Peran</label>
                        <select name="peran_id" id="peran_id" class="block w-full py-2.5 pl-3 pr-10 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right">
                            <option value="">Semua Peran</option>
                            @foreach($perans as $peran)
                                <option value="{{ $peran->id }}" {{ request('peran_id') == $peran->id ? 'selected' : '' }}>
                                    {{ $peran->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="bidang_id" class="block text-sm font-medium text-slate-700 mb-1">Filter Bidang</label>
                        <select name="bidang_id" id="bidang_id" class="block w-full py-2.5 pl-3 pr-10 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right">
                            <option value="">Semua Bidang</option>
                            @foreach($bidangs as $bidang)
                                <option value="{{ $bidang->id }}" {{ request('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                    {{ $bidang->nama_bidang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="is_active" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="is_active" id="is_active" class="block w-full py-2.5 pl-3 pr-10 bg-white/50 border border-white/60 focus:bg-white focus:border-brand focus:ring-4 focus:ring-brand/10 rounded-xl sm:text-sm transition-all duration-200 outline-none text-slate-800 font-medium appearance-none bg-no-repeat bg-right">
                            <option value="">Semua</option>
                            <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-colors">
                        Reset
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-brand rounded-xl hover:bg-brand-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </div>
            </form>

            {{-- Users Table --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                @if ($users->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Peran</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bidang</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Terakhir Login</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand to-accent flex items-center justify-center text-white font-medium text-sm">
                                                    {{ Str::upper($user->name[0]) }}
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium text-slate-900">{{ $user->name }}</p>
                                                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($user->peran?->slug === 'super_admin') bg-purple-100 text-purple-800
                                                @elseif($user->peran?->slug === 'kepala_dinas') bg-red-100 text-red-800
                                                @elseif($user->peran?->slug === 'kepala_bidang') bg-orange-100 text-orange-800
                                                @elseif($user->peran?->slug === 'tata_usaha') bg-blue-100 text-blue-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ $user->peran?->nama ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-600">
                                            {{ $user->bidang?->nama_bidang ?? '<span class="text-slate-400">-</span>' }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-slate-500">
                                            {{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : 'Belum login' }}
                                        </td>
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2" x-data="{ openDelete: false }">
                                                <a href="{{ route('users.edit', $user) }}"
                                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-brand bg-brand/10 rounded-lg hover:bg-brand/20 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </a>

                                                @if ($user->id !== auth()->id())
                                                    <button type="button"
                                                        @click="openDelete = true"
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Nonaktifkan
                                                    </button>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-400 bg-slate-100 rounded-lg cursor-not-allowed">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Nonaktifkan
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Delete Confirmation Modal --}}
                                            <div x-show="openDelete" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto" @keydown.escape.window="openDelete = false" @click.self="openDelete = false">
                                                <div class="flex min-h-full items-center justify-center p-4">
                                                    <div class="fixed inset-0 bg-black/50" @click="openDelete = false"></div>
                                                    <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6 transform transition-all">
                                                        <div class="flex items-center justify-between mb-4">
                                                            <h3 class="text-lg font-semibold text-slate-900">Konfirmasi Nonaktifkan</h3>
                                                            <button @click="openDelete = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        <p class="text-slate-600 mb-6">Apakah Anda yakin ingin menonaktifkan akun <strong>{{ $user->name }}</strong>? Pengguna tidak akan dapat login setelah dinonaktifkan.</p>
                                                        <div class="flex justify-end gap-3">
                                                            <button @click="openDelete = false" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors">
                                                                Batal
                                                            </button>
                                                            <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                                                                    Ya, Nonaktifkan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="px-4 py-4 border-t border-slate-200">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="py-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <h3 class="text-lg font-medium text-slate-900 mb-1">Belum ada pengguna</h3>
                        <p class="text-slate-500 mb-4">Mulai dengan menambahkan pengguna baru</p>
                        <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-brand to-accent rounded-xl shadow-md hover:from-brand-dark hover:to-accent-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Tambah Pengguna
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>