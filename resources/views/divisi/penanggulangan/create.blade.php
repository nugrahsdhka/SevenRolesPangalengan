<x-app-layout>
    <x-slot name="title">
        Tambah Data Penanggulangan
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="p-6 sm:p-8 bg-white shadow-lg rounded-2xl ring-1 ring-black/5">
                <div class="flex items-center gap-4 mb-6">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    </span>
                    <h3 class="text-xl font-bold text-red-900">Formulir Input Data Penanggulangan</h3>
                </div>

                <form method="POST" action="{{ route('penanggulangan.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="jenis_bencana" :value="__('Jenis Bencana')" />
                            <x-text-input id="jenis_bencana" class="block mt-1 w-full" type="text" name="jenis_bencana" :value="old('jenis_bencana')" required autofocus />
                            <x-input-error :messages="$errors->get('jenis_bencana')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="jumlah_korban" :value="__('Jumlah Korban')" />
                            <x-text-input id="jumlah_korban" class="block mt-1 w-full" type="number" name="jumlah_korban" :value="old('jumlah_korban')" required />
                            <x-input-error :messages="$errors->get('jumlah_korban')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="tindakan" :value="__('Tindakan yang Dilakukan')" />
                            <textarea id="tindakan" name="tindakan" rows="5"
                                      class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">{{ old('tindakan') }}</textarea>
                            <x-input-error :messages="$errors->get('tindakan')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-6">
                        <a href="{{ route('penanggulangan.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-4">
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