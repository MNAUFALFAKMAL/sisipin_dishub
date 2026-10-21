<x-app-layout>
    @section('robots')
    <meta name="robots" content="noindex">
    @endsection

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Dashboard Tata Usaha</h1>
                <p class="text-slate-500 mt-1">Selamat datang, {{ auth()->user()->name }} (Tata Usaha)</p>
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
                            <p class="text-sm font-medium text-slate-500">Surat Masuk</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\SuratMasuk::count() }}</p>
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
                            <p class="text-sm font-medium text-slate-500">Surat Keluar</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\SuratKeluar::count() }}</p>
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
                            <p class="text-sm font-medium text-slate-500">Disposisi Belum Dibaca</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\Disposisi::where('status', 'baru')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Klasifikasi Arsip</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\KlasifikasiArsip::count() }}</p>
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
                        <p class="font-medium text-slate-700">Input Surat Masuk</p>
                    </a>

                    <a href="{{ route('surat-keluar.create') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        <p class="font-medium text-slate-700">Input Surat Keluar</p>
                    </a>

                    <a href="{{ route('surat-masuk.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <p class="font-medium text-slate-700">Kelola Disposisi</p>
                    </a>

                    <a href="{{ route('klasifikasi-arsip.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <p class="font-medium text-slate-700">Klasifikasi Arsip</p>
                    </a>
                </div>
            </div>

            <!-- Surat Masuk Terbaru -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Surat Masuk Terbaru</h2>
                    <a href="{{ route('surat-masuk.index') }}" class="text-sm text-brand hover:text-brand-dark font-medium">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-sm text-slate-500 border-b border-slate-200">
                                <th class="pb-3 font-medium">No. Surat</th>
                                <th class="pb-3 font-medium">Pengirim</th>
                                <th class="pb-3 font-medium">Perihal</th>
                                <th class="pb-3 font-medium">Tgl Terima</th>
                                <th class="pb-3 font-medium">Status Disposisi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach(\App\Models\SuratMasuk::latest()->take(5)->get() as $surat)
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 text-sm text-slate-900">{{ $surat->no_surat }}</td>
                                <td class="py-3 text-sm text-slate-600">{{ $surat->pengirim }}</td>
                                <td class="py-3 text-sm text-slate-600 max-w-xs truncate">{{ $surat->perihal }}</td>
                                <td class="py-3 text-sm text-slate-500">{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                                <td class="py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($surat->status_disposisi === 'selesai') bg-green-100 text-green-800
                                        @elseif($surat->status_disposisi === 'diproses') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($surat->status_disposisi) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500">Belum ada surat masuk</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>