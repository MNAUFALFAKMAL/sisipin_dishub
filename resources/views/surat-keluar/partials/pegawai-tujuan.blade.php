{{-- Field Tujuan dengan Picker Pegawai (khusus SPT dan jenis lain) --}}
<div x-data="tujuanPegawai(@js($daftarPegawai ?? []), @js($tujuanValue ?? ''))" class="relative">
    <label for="tujuan" class="form-label">Tujuan</label>

    <div class="flex gap-2">
        <input id="tujuan" type="text" name="tujuan" x-model="tujuanText"
               class="form-input flex-1 @error('tujuan') border-red-500 @enderror"
               placeholder="Instansi / perorangan tujuan" />
        <button type="button"
                class="shrink-0 inline-flex items-center gap-1 rounded-md px-3 py-2 text-sm font-semibold border transition"
                @click="open = !open"
                :class="open ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300' : 'border-gray-200 bg-gray-50 text-gray-700 hover:border-indigo-400 hover:text-indigo-700 dark:border-[#1e2d45] dark:bg-gray-800 dark:text-gray-200 dark:hover:text-indigo-300'">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Pilih Pegawai
        </button>
    </div>

    @error('tujuan')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror

    {{-- Panel picker --}}
    <div x-show="open" x-cloak
         class="absolute z-20 mt-2 w-full max-h-80 overflow-hidden flex flex-col rounded-xl border border-gray-200 dark:border-[#1e2d45] bg-white dark:bg-[#161b27] shadow-2xl">
        <div class="p-3 border-b border-gray-100 dark:border-[#1e2d45]">
            <input type="text" x-model="q" placeholder="Cari nama pegawai..."
                   class="w-full form-input text-sm" />
            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                <span x-text="count"></span> pegawai dipilih — teks di atas terisi otomatis.
            </p>
        </div>

        <div class="flex-1 overflow-y-auto p-2 grid grid-cols-1 gap-1.5 bg-gray-50/50 dark:bg-gray-900/40">
            <template x-for="(grup, gi) in grouped" :key="gi">
                <div class="col-span-full">
                    <div class="sticky top-0 z-10 flex items-center justify-between gap-2 px-2.5 py-1.5 mb-1 rounded-md bg-gray-100/90 dark:bg-gray-800/90 backdrop-blur-sm">
                        <span class="text-[11px] font-bold uppercase tracking-wide text-indigo-700 dark:text-indigo-300" x-text="grup.bidang"></span>
                        <span class="text-[11px] font-semibold text-gray-400 dark:text-gray-500" x-text="grup.items.length + ' pegawai'"></span>
                    </div>
                    <div class="grid grid-cols-1 gap-1.5">
                        <template x-for="n in grup.items" :key="n">
                            <label class="flex items-center gap-2 rounded-md px-2.5 py-2 border cursor-pointer select-none transition text-sm"
                                   :class="isSelected(n)
                                       ? 'border-indigo-500 bg-indigo-50/40 dark:bg-indigo-900/30'
                                       : 'border-gray-100 hover:border-indigo-200 dark:border-[#1e2d45] dark:hover:border-indigo-500/50'">
                                <input type="checkbox"
                                       :checked="isSelected(n)"
                                       @change="toggle(n)"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 dark:border-[#334155] dark:bg-[#0f172a]" />
                                <span class="font-medium text-gray-700 dark:text-gray-200" x-text="n"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </template>
            <p x-show="grouped.length === 0" class="col-span-full text-sm text-gray-400 dark:text-gray-500 py-4 text-center">
                Tidak ada pegawai yang cocok.
            </p>
        </div>

        <div class="flex items-center justify-between gap-2 p-3 border-t border-gray-100 dark:border-[#1e2d45] bg-white dark:bg-[#161b27]">
            <button type="button" @click="clearAll"
                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition">
                Bersihkan
            </button>
            <button type="button" @click="open = false"
                    class="rounded-md px-3 py-1.5 text-sm font-semibold border border-gray-200 dark:border-[#1e2d45] text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                Batal
            </button>
            <button type="button" @click="apply"
                    class="rounded-md px-4 py-1.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                Terapkan
            </button>
        </div>
    </div>
</div>