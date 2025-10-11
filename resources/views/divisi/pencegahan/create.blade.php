<x-app-layout>
    <x-slot name="title">
        Tambah Data Pencegahan
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="p-6 sm:p-8 bg-white shadow-lg rounded-2xl ring-1 ring-black/5">
                <div class="flex items-center gap-4 mb-6">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                    </span>
                    <h3 class="text-xl font-bold text-red-900">Formulir Input Data Pencegahan</h3>
                </div>

                <form method="POST" action="{{ route('pencegahan.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="periode" :value="__('Periode Laporan')" />
                            <x-text-input id="periode" class="block mt-1 w-full" type="month" name="periode" :value="old('periode')" required autofocus />
                            <x-input-error :messages="$errors->get('periode')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="judul_kegiatan" :value="__('Judul Kegiatan')" />
                            <x-text-input id="judul_kegiatan" class="block mt-1 w-full" type="text" name="judul_kegiatan" :value="old('judul_kegiatan')" required autofocus />
                            <x-input-error :messages="$errors->get('judul_kegiatan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="lokasi" :value="__('Lokasi Kegiatan')" />
                            <x-text-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" :value="old('lokasi')" required />
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_kegiatan" :value="__('Tanggal Kegiatan')" />
                            <x-text-input id="tanggal_kegiatan" class="block mt-1 w-full" type="date" name="tanggal_kegiatan" :value="old('tanggal_kegiatan')" required />
                            <x-input-error :messages="$errors->get('tanggal_kegiatan')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="laporan_singkat" :value="__('Laporan Singkat')" />
                            <textarea id="laporan_singkat" name="laporan_singkat" rows="5"
                                      class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">{{ old('laporan_singkat') }}</textarea>
                            <x-input-error :messages="$errors->get('laporan_singkat')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-6">
                        <a href="{{ route('pencegahan.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-4">
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