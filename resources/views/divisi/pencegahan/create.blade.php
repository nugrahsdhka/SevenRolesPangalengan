<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Tambah Data Pencegahan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Card transparan --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <form method="POST" action="{{ route('pencegahan.store') }}">
                        @csrf

                        {{-- Judul Kegiatan --}}
                        <div>
                            <x-input-label for="judul_kegiatan" :value="__('Judul Kegiatan')" class="text-red-900"/>
                            <x-text-input id="judul_kegiatan"
                                class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500"
                                type="text" name="judul_kegiatan" :value="old('judul_kegiatan')" required autofocus />
                            <x-input-error :messages="$errors->get('judul_kegiatan')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Lokasi --}}
                        <div class="mt-4">
                            <x-input-label for="lokasi" :value="__('Lokasi')" class="text-red-900"/>
                            <x-text-input id="lokasi"
                                class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500"
                                type="text" name="lokasi" :value="old('lokasi')" required />
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Tanggal --}}
                        <div class="mt-4">
                            <x-input-label for="tanggal_kegiatan" :value="__('Tanggal Kegiatan')" class="text-red-900"/>
                            <x-text-input id="tanggal_kegiatan"
                                class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500"
                                type="date" name="tanggal_kegiatan" :value="old('tanggal_kegiatan')" required />
                            <x-input-error :messages="$errors->get('tanggal_kegiatan')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Laporan Singkat --}}
                        <div class="mt-4">
                            <x-input-label for="laporan_singkat" :value="__('Laporan Singkat')" class="text-red-900"/>
                            <textarea id="laporan_singkat" name="laporan_singkat"
                                class="block mt-1 w-full rounded-md bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500 shadow-sm"
                                rows="5">{{ old('laporan_singkat') }}</textarea>
                            <x-input-error :messages="$errors->get('laporan_singkat')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Tombol --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('pencegahan.index') }}"
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
