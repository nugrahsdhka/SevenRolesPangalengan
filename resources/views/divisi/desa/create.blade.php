<x-app-layout>
    <x-slot name="title">
        Tambah Data Desa
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Data Desa Baru') }}
            </h2>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Log Out
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="p-6 sm:p-8 bg-white shadow-lg rounded-2xl ring-1 ring-black/5">
                <div class="flex items-center gap-4 mb-6">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" /></svg>
                    </span>
                    <h3 class="text-xl font-bold text-red-900">Formulir Input Data Desa</h3>
                </div>

                <form method="POST" action="{{ route('desa.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kolom Kiri --}}
                        <div class="space-y-6">
                             <div>
                                <x-input-label for="periode" :value="__('Periode Laporan')" />
                                <x-text-input id="periode" class="block mt-1 w-full" type="month" name="periode" :value="old('periode')" required autofocus />
                                <x-input-error :messages="$errors->get('periode')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nama_desa" :value="__('Nama Desa')" />
                                <x-text-input id="nama_desa" class="block mt-1 w-full" type="text" name="nama_desa" :value="old('nama_desa')" required />
                                <x-input-error :messages="$errors->get('nama_desa')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="jumlah_penduduk" :value="__('Jumlah Penduduk')" />
                                <x-text-input id="jumlah_penduduk" class="block mt-1 w-full" type="number" name="jumlah_penduduk" :value="old('jumlah_penduduk')" required />
                                <x-input-error :messages="$errors->get('jumlah_penduduk')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="luas_wilayah" :value="__('Luas Wilayah (dalam m²)')" />
                                <x-text-input id="luas_wilayah" class="block mt-1 w-full" type="number" name="luas_wilayah" :value="old('luas_wilayah')" required />
                                <x-input-error :messages="$errors->get('luas_wilayah')" class="mt-2" />
                            </div>
                            
                            <div>
                                <x-input-label for="jumlah_kasus_stunting">
                                    Jumlah Kasus Stunting <span class="text-sm text-gray-500">(Opsional)</span>
                                </x-input-label>
                                <x-text-input id="jumlah_kasus_stunting" class="block mt-1 w-full" type="number" name="jumlah_kasus_stunting" :value="old('jumlah_kasus_stunting', 0)" />
                                <x-input-error :messages="$errors->get('jumlah_kasus_stunting')" class="mt-2" />
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div>
                            <x-input-label for="potensi_desa" :value="__('Potensi Desa')" />
                            <textarea id="potensi_desa" name="potensi_desa" rows="16"
                                      class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">{{ old('potensi_desa') }}</textarea>
                            <x-input-error :messages="$errors->get('potensi_desa')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-6">
                        <a href="{{ route('desa.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-4">
                            {{ __('Batal') }}
                        </a>
                        <x-primary-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:ring-red-500">
                            {{ __('Simpan Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>