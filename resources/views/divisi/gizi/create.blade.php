<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Tambah Data Gizi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Card transparan elegan --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <form method="POST" action="{{ route('gizi.store') }}">
                        @csrf

                        {{-- Judul --}}
                        <div>
                            <x-input-label for="judul" :value="__('Judul')" class="text-red-900"/>
                            <x-text-input id="judul"
                                class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500"
                                type="text" name="judul" :value="old('judul')" required autofocus />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-4">
                            <x-input-label for="deskripsi" :value="__('Deskripsi')" class="text-red-900"/>
                            <textarea id="deskripsi" name="deskripsi"
                                class="block mt-1 w-full rounded-md bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500 shadow-sm"
                                rows="5">{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Tombol --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('gizi.index') }}"
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
