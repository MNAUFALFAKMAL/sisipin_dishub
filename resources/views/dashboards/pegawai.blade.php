<x-app-layout>
    @section('robots')
    <meta name="robots" content="noindex">
    @endsection

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Dashboard Pegawai</h1>
                <p class="text-slate-500 mt-1">Selamat datang, {{ auth()->user()->name }} (Pegawai)</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-blue-100 text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Surat Masuk Saya</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\SuratMasuk::where('diinput_oleh', auth()->id())->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-yellow-100 text-yellow-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Surat Keluar Saya</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\SuratKeluar::where('dibuat_oleh', auth()->id())->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-green-100 text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Disposisi Untuk Saya</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\Disposisi::where('kepada_user_id', auth()->id())->where('status', '!=', 'selesai')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Total Surat</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\SuratMasuk::where('diinput_oleh', auth()->id())->count() + \App\Models\SuratKeluar::where('dibuat_oleh', auth()->id())->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('surat-masuk.create') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <p class="font-medium text-slate-700">Buat Surat Masuk</p>
                    </a>

                    <a href="{{ route('surat-keluar.create') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <p class="font-medium text-slate-700">Buat Surat Keluar</p>
                    </a>

                    <a href="{{ route('surat-masuk.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <p class="font-medium text-slate-700">Surat Masuk Saya</p>
                    </a>

                    <a href="{{ route('surat-keluar.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <p class="font-medium text-slate-700">Surat Keluar Saya</p>
                    </a>
                </div>
            </div>

            <!-- Disposisi untuk saya -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Disposisi Untuk Saya</h2>
                    <a href="{{ route('surat-masuk.index') }}" class="text-sm text-brand hover:text-brand-dark font-medium">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-sm text-slate-500 border-b border-slate-200">
                                <th class="pb-3 font-medium">No. Surat</th>
                                <th class="pb-3 font-medium">Perihal</th>
                                <th class="pb-3 font-medium">Dari</th>
                                <th class="pb-3 font-medium">Instruksi</th>
                                <th class="pb-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach(\App\Models\Disposisi::with('suratMasuk', 'dariUser')->where('kepada_user_id', auth()->id())->where('status', '!=', 'selesai')->latest()->take(5)->get() as $disposisi)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 text-sm text-slate-900">{{ $disposisi->suratMasuk->no_surat ?? '-' }}</td>
                                <td class="py-3 text-sm text-slate-600 max-w-xs truncate">{{ $disposisi->suratMasuk->perihal ?? '-' }}</td>
                                <td class="py-3 text-sm text-slate-600">{{ $disposisi->dariUser->name ?? '-' }}</td>
                                <td class="py-3 text-sm text-slate-500 max-w-xs truncate">{{ $disposisi->instruksi ?? '-' }}</td>
                                <td class="py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($disposisi->status === 'selesai') bg-green-100 text-green-800
                                        @elseif($disposisi->status === 'diproses') bg-yellow-100 text-yellow-800
                                        @elseif($disposisi->status === 'dibaca') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($disposisi->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500">Tidak ada disposisi yang perlu ditindaklanjuti</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>