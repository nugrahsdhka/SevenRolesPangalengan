<x-app-layout>
    <x-slot name="title">
        Tambah Data Gizi
    </x-slot>

    <div class="py-12 bg-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="p-6 sm:p-8 bg-white shadow-lg rounded-2xl ring-1 ring-black/5">
                <div class="flex items-center gap-4 mb-6">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                        <svg xmlns="http://www.w.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <h3 class="text-xl font-bold text-red-900">Formulir Input Data Gizi</h3>
                </div>

                <form method="POST" action="{{ route('gizi.store') }}">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <x-input-label for="judul" :value="__('Judul Data')" />
                            <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul')" required autofocus />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi Lengkap')" />
                            <textarea id="deskripsi" name="deskripsi" rows="5"
                                      class="block mt-1 w-full border-gray-300 focus:border-red-500 focus:ring-red-500 rounded-md shadow-sm">{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 border-t border-gray-200 pt-6">
                        <a href="{{ route('gizi.index') }}" class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-4">
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