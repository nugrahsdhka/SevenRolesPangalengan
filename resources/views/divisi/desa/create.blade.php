<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Tambah Data Desa Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Card transparan elegan --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <form method="POST" action="{{ route('desa.store') }}">
                        @csrf

                        {{-- Jumlah Penduduk --}}
                        <div>
                            <x-input-label for="jumlah_penduduk" :value="__('Jumlah Penduduk')" class="text-red-900"/>
                            <x-text-input id="jumlah_penduduk"
                                class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-200 border-red-300 focus:border-red-500 focus:ring-red-500"
                                type="number" name="jumlah_penduduk" :value="old('jumlah_penduduk')" required autofocus />
                            <x-input-error :messages="$errors->get('jumlah_penduduk')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Luas Wilayah --}}
                        <div class="mt-4">
                            <x-input-label for="luas_wilayah" :value="__('Luas Wilayah (dalam m²)')" class="text-red-900"/>
                            <x-text-input id="luas_wilayah"
                                class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-200 border-red-300 focus:border-red-500 focus:ring-red-500"
                                type="number" name="luas_wilayah" :value="old('luas_wilayah')" required />
                            <x-input-error :messages="$errors->get('luas_wilayah')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Potensi Desa --}}
                        <div class="mt-4">
                            <x-input-label for="potensi_desa" :value="__('Potensi Desa')" class="text-red-900"/>
                            <textarea id="potensi_desa" name="potensi_desa"
                                class="block mt-1 w-full rounded-md bg-white/10 text-red-900 placeholder-red-200 border-red-300 focus:border-red-500 focus:ring-red-500 shadow-sm"
                                rows="5">{{ old('potensi_desa') }}</textarea>
                            <x-input-error :messages="$errors->get('potensi_desa')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Button --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('desa.index') }}"
                               class="text-sm text-red-200 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-4">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button class="bg-red-600 hover:bg-red-700 focus:ring-red-500">
                                {{ __('Simpan Data') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
