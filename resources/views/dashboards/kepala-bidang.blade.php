<x-app-layout>
    @section('robots')
    <meta name="robots" content="noindex">
    @endsection

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Dashboard Kepala Bidang</h1>
                <p class="text-slate-500 mt-1">Selamat datang, {{ auth()->user()->name }} (Kepala Bidang {{ auth()->user()->bidang->nama_bidang ?? '' }})</p>
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
                            <p class="text-sm font-medium text-slate-500">Surat Masuk Bidang</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\SuratMasuk::where('bidang_tujuan_id', auth()->user()->bidang_id)->count() }}</p>
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
                            <p class="text-sm font-medium text-slate-500">Surat Keluar Bidang</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\SuratKeluar::where('bidang_id', auth()->user()->bidang_id)->count() }}</p>
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
                            <p class="text-sm font-medium text-slate-500">Disposisi Belum Selesai</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\Disposisi::where('kepada_bidang_id', auth()->user()->bidang_id)->where('status', '!=', 'selesai')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-xl bg-purple-100 text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-slate-500">Pegawai Bidang</p>
                            <p class="text-2xl font-bold text-slate-900">{{ \App\Models\Pegawai::where('bidang_id', auth()->user()->bidang_id)->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('surat-masuk.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <p class="font-medium text-slate-700">Kelola Surat Masuk</p>
                    </a>

                    <a href="{{ route('surat-keluar.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <p class="font-medium text-slate-700">Kelola Surat Keluar</p>
                    </a>

                    <a href="{{ route('disposisi.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <p class="font-medium text-slate-700">Kelola Disposisi</p>
                    </a>

                    <a href="{{ route('pegawai.index') }}" class="p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors text-center">
                        <svg class="w-8 h-8 mx-auto text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="font-medium text-slate-700">Data Pegawai</p>
                    </a>
                </div>
            </div>

            <!-- Surat Masuk Bidang Terbaru -->
            <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900">Surat Masuk ke Bidang</h2>
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
                            @foreach(\App\Models\SuratMasuk::where('bidang_tujuan_id', auth()->user()->bidang_id)->latest()->take(5)->get() as $surat)
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
                                <td colspan="5" class="py-8 text-center text-slate-500">Belum ada surat masuk ke bidang ini</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>