<x-app-layout>
    <x-slot name="title">
        Tambah Data Gizi
    </x-slot>

    <div class="px-1 py-2 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                {{-- Header --}}
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-md">
                        {{-- Ikon Tambah --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-start items-start">
                        <h3 class="text-lg font-bold text-red-700">Formulir Tambah Data Gizi</h3>
                        <p class="text-xs font-bold text-red-700">Lengkapi Form di Bawah</p>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('gizi.store') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-5">
                        
                        {{-- Desa/Kelurahan --}}
                        <div>
                            <x-input-label for="desa_kelurahan" :value="__('Desa/Kelurahan')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="desa_kelurahan" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="text" 
                                name="desa_kelurahan" 
                                :value="old('desa_kelurahan')" 
                                placeholder="Contoh: Kelurahan Sukasari"
                                required 
                                autofocus />
                            <x-input-error :messages="$errors->get('desa_kelurahan')" class="mt-2" />
                        </div>

                        {{-- Berat & Tinggi --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="berat" :value="__('Berat (kg)')" class="text-sm font-medium text-gray-700" />
                                <x-text-input id="berat" 
                                    class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                    type="number" 
                                    name="berat" 
                                    :value="old('berat')" 
                                    step="0.01" min="0" max="999.99"
                                    placeholder="Contoh: 10.5"
                                    required />
                                <x-input-error :messages="$errors->get('berat')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tinggi" :value="__('Tinggi (cm)')" class="text-sm font-medium text-gray-700" />
                                <x-text-input id="tinggi" 
                                    class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                    type="number" 
                                    name="tinggi" 
                                    :value="old('tinggi')" 
                                    step="0.01" min="0" max="999.99"
                                    placeholder="Contoh: 70.2"
                                    required />
                                <x-input-error :messages="$errors->get('tinggi')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Data Opsional Hasil Kalkulasi --}}
                        <div class="pt-4 border-t">
                            <h4 class="text-md font-semibold text-gray-800">Hasil Status Gizi (Opsional)</h4>
                            <p class="text-sm text-gray-500 mb-4">Kolom ini bisa dikosongkan jika belum dihitung.</p>
                            
                            {{-- BB/U --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <x-input-label for="status_bb_u" :value="__('Status BB/U')" />
                                    <x-text-input id="status_bb_u" class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" type="text" name="status_bb_u" :value="old('status_bb_u')" placeholder="Gizi Baik, Gizi Kurang..." />
                                    <x-input-error :messages="$errors->get('status_bb_u')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="zs_bb_u" :value="__('Z-Score BB/U')" />
                                    <x-text-input id="zs_bb_u" class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" type="number" name="zs_bb_u" :value="old('zs_bb_u')" step="0.01" min="-99.99" max="99.99" placeholder="Contoh: -2.15" />
                                    <x-input-error :messages="$errors->get('zs_bb_u')" class="mt-2" />
                                </div>
                            </div>

                            {{-- TB/U --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                                <div>
                                    <x-input-label for="status_tb_u" :value="__('Status TB/U')" />
                                    <x-text-input id="status_tb_u" class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" type="text" name="status_tb_u" :value="old('status_tb_u')" placeholder="Normal, Stunted..." />
                                    <x-input-error :messages="$errors->get('status_tb_u')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="zs_tb_u" :value="__('Z-Score TB/U')" />
                                    <x-text-input id="zs_tb_u" class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" type="number" name="zs_tb_u" :value="old('zs_tb_u')" step="0.01" min="-99.99" max="99.99" placeholder="Contoh: -1.05" />
                                    <x-input-error :messages="$errors->get('zs_tb_u')" class="mt-2" />
                                </div>
                            </div>

                            {{-- BB/TB --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="status_bb_tb" :value="__('Status BB/TB')" />
                                    <x-text-input id="status_bb_tb" class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" type="text" name="status_bb_tb" :value="old('status_bb_tb')" placeholder="Gizi Baik, Wasted..." />
                                    <x-input-error :messages="$errors->get('status_bb_tb')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="zs_bb_tb" :value="__('Z-Score BB/TB')" />
                                    <x-text-input id="zs_bb_tb" class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" type="number" name="zs_bb_tb" :value="old('zs_bb_tb')" step="0.01" min="-99.99" max="99.99" placeholder="Contoh: 0.5" />
                                    <x-input-error :messages="$errors->get('zs_bb_tb')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                        <a href="{{ route('gizi.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                            Batal
                        </a>
                        <x-primary-button class="rounded-lg bg-gradient-to-r from-red-600 to-rose-600 px-6 py-2.5 text-sm font-semibold shadow-md transition hover:from-red-700 hover:to-rose-700 hover:shadow-lg focus:ring-red-500">
                            <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Data
                        </x-primary-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>