<x-app-layout>
    <x-slot name="title">
        Edit Data Desa
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Data Desa') }}
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                    </span>
                    <h3 class="text-xl font-bold text-red-900">Formulir Edit Data Desa</h3>
                </div>

                <form method="POST" action="{{ route('desa.update', $data->id) }}">
                    @csrf
                    @method('PUT') {{-- Method untuk update --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Kolom Kiri --}}
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="periode" :value="__('Periode Laporan')" />
                                <x-text-input id="periode" class="block mt-1 w-full" type="month" name="periode" :value="old('periode', $data->periode->format('Y-m'))" required autofocus />
                                <x-input-error :messages="$errors->get('periode')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="nama_desa" :value="__('Nama Desa')" />
                                <x-text-input id="nama_desa" class="block mt-1 w-full" type="text" name="nama_desa" :value="old('nama_desa', $data->nama_desa)" required />
                                <x-input-error :messages="$errors->get('nama_desa')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="jumlah_penduduk" :value="__('Jumlah Penduduk')" />
                                <x-text-input id="jumlah_penduduk" class="block mt-1 w-full" type="number" name="jumlah_penduduk" :value="old('jumlah_penduduk', $data->jumlah_penduduk)" required />
                                <x-input-error :messages="$errors->get('jumlah_penduduk')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="luas_wilayah" :value="__('Luas Wilayah (dalam m²)')" />
                                <x-text-input id="luas_wilayah" class="block mt-1 w-full" type="number" name="luas_wilayah" :value="old('luas_wilayah', $data->luas_wilayah)" required />
                                <x-input-error :messages="$errors->get('luas_wilayah')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="jumlah_kasus_stunting">
                                    Jumlah Kasus Stunting <span class="text-sm text-gray-500">(Opsional)</span>
                                </x-input-label>
                                <x-text-input id="jumlah_kasus_stunting" class="block mt-1 w-full" type="number" name="jumlah_kasus_stunting" :value="old('jumlah_kasus_stunting', $data->jumlah_kasus_stunting)" />
                                <x-input-error :messages="$errors->get('jumlah_kasus_stunting')" class="mt-2" />
                            </div>
                        </div>
                        {{-- Kolom Kanan --}}
                        <div>
                            <x-input-label for="potensi_desa" :value="__('Potensi Desa')" />
                            <textarea id="potensi_desa" name="potensi_desa" rows="16" class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">{{ old('potensi_desa', $data->potensi_desa) }}</textarea>
                            <x-input-error :messages="$errors->get('potensi_desa')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-6">
                        <a href="{{ route('desa.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-4">{{ __('Batal') }}</a>
                        <x-primary-button class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:ring-red-500">{{ __('Simpan Perubahan') }}</x-primary-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>