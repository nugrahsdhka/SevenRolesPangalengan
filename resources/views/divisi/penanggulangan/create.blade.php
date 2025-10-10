<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Tambah Data Penanggulangan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Card transparan --}}
            <div class="bg-white/10 backdrop-blur-lg ring-1 ring-white/20 shadow-xl rounded-2xl">
                <div class="p-6 text-white">
                    <form method="POST" action="{{ route('penanggulangan.store') }}">
                        @csrf

                        {{-- Jenis Bencana --}}
                        <div>
                            <x-input-label for="jenis_bencana" :value="__('Jenis Bencana')" class="text-red-900"/>
                            <x-text-input id="jenis_bencana"
                                class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500"
                                type="text" name="jenis_bencana" :value="old('jenis_bencana')" required autofocus />
                            <x-input-error :messages="$errors->get('jenis_bencana')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Jumlah Korban --}}
                        <div class="mt-4">
                            <x-input-label for="jumlah_korban" :value="__('Jumlah Korban')" class="text-red-900"/>
                            <x-text-input id="jumlah_korban"
                                class="block mt-1 w-full bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500"
                                type="number" name="jumlah_korban" :value="old('jumlah_korban')" required />
                            <x-input-error :messages="$errors->get('jumlah_korban')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Tindakan --}}
                        <div class="mt-4">
                            <x-input-label for="tindakan" :value="__('Tindakan yang Dilakukan')" class="text-red-900"/>
                            <textarea id="tindakan" name="tindakan"
                                class="block mt-1 w-full rounded-md bg-white/10 text-red-900 placeholder-red-600 border-red-300 focus:border-red-500 focus:ring-red-500 shadow-sm"
                                rows="5">{{ old('tindakan') }}</textarea>
                            <x-input-error :messages="$errors->get('tindakan')" class="mt-2 text-red-200" />
                        </div>

                        {{-- Tombol --}}
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('penanggulangan.index') }}"
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
