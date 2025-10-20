<x-app-layout>
    <x-slot name="title">
        Tambah Data Desa
    </x-slot>

    <div class="px-2 py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                {{-- Header --}}
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Formulir Tambah Data Desa</h3>
                        <p class="text-sm text-gray-500">Lengkapi informasi data desa dengan teliti</p>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('desa.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        {{-- Kolom Kiri --}}
                        <div class="space-y-5">
                            {{-- Periode --}}
                            <div>
                                <x-input-label for="periode" :value="__('Periode Laporan')" class="text-sm font-medium text-gray-700" />
                                <x-text-input id="periode" 
                                    class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" 
                                    type="month" 
                                    name="periode" 
                                    :value="old('periode')" 
                                    required 
                                    autofocus />
                                <x-input-error :messages="$errors->get('periode')" class="mt-2" />
                            </div>

                            {{-- Nama Desa --}}
                            <div>
                                <x-input-label for="nama_desa" :value="__('Nama Desa')" class="text-sm font-medium text-gray-700" />
                                <x-text-input id="nama_desa" 
                                    class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" 
                                    type="text" 
                                    name="nama_desa" 
                                    :value="old('nama_desa')" 
                                    placeholder="Contoh: Desa Margamulya"
                                    required />
                                <x-input-error :messages="$errors->get('nama_desa')" class="mt-2" />
                            </div>

                            {{-- Jumlah Penduduk --}}
                            <div>
                                <x-input-label for="jumlah_penduduk" :value="__('Jumlah Penduduk')" class="text-sm font-medium text-gray-700" />
                                <x-text-input id="jumlah_penduduk" 
                                    class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" 
                                    type="number" 
                                    name="jumlah_penduduk" 
                                    :value="old('jumlah_penduduk')" 
                                    placeholder="0"
                                    required />
                                <x-input-error :messages="$errors->get('jumlah_penduduk')" class="mt-2" />
                            </div>

                            {{-- Luas Wilayah --}}
                            <div>
                                <x-input-label for="luas_wilayah" :value="__('Luas Wilayah (m²)')" class="text-sm font-medium text-gray-700" />
                                <x-text-input id="luas_wilayah" 
                                    class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" 
                                    type="number" 
                                    name="luas_wilayah" 
                                    :value="old('luas_wilayah')" 
                                    placeholder="0"
                                    required />
                                <x-input-error :messages="$errors->get('luas_wilayah')" class="mt-2" />
                            </div>
                            
                            {{-- Jumlah Kasus Stunting --}}
                            <div>
                                <x-input-label for="jumlah_kasus_stunting" class="text-sm font-medium text-gray-700">
                                    Jumlah Kasus Stunting <span class="text-xs font-normal text-gray-500">(Opsional)</span>
                                </x-input-label>
                                <x-text-input id="jumlah_kasus_stunting" 
                                    class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20" 
                                    type="number" 
                                    name="jumlah_kasus_stunting" 
                                    :value="old('jumlah_kasus_stunting', 0)" 
                                    placeholder="0" />
                                <x-input-error :messages="$errors->get('jumlah_kasus_stunting')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div>
                            <x-input-label for="potensi_desa" :value="__('Potensi Desa')" class="text-sm font-medium text-gray-700" />
                            <textarea id="potensi_desa" 
                                name="potensi_desa" 
                                rows="20"
                                placeholder="Deskripsikan potensi dan karakteristik desa..."
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 transition focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">{{ old('potensi_desa') }}</textarea>
                            <x-input-error :messages="$errors->get('potensi_desa')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                        <a href="{{ route('desa.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                            Batal
                        </a>
                        <x-primary-button class="rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-2.5 text-sm font-semibold shadow-md transition hover:from-emerald-700 hover:to-teal-700 hover:shadow-lg focus:ring-emerald-500">
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