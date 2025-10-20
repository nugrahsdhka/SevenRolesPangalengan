<x-app-layout>
    <x-slot name="title">
        Tambah Data Gizi
    </x-slot>

    <div class="px-2 py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                {{-- Header --}}
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-md">
                        {{-- Ikon Tambah --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Formulir Tambah Data Gizi</h3>
                        <p class="text-sm text-gray-500">Lengkapi informasi data gizi dengan teliti</p>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('gizi.store') }}" class="space-y-6">
                    @csrf

                    <div class="space-y-5">
                        {{-- Periode --}}
                        <div>
                            <x-input-label for="periode" :value="__('Periode Laporan')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="periode" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="month" 
                                name="periode" 
                                :value="old('periode')" 
                                required 
                                autofocus />
                            <x-input-error :messages="$errors->get('periode')" class="mt-2" />
                        </div>

                        {{-- Judul Data --}}
                        <div>
                            <x-input-label for="judul" :value="__('Judul Data')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="judul" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="text" 
                                name="judul" 
                                :value="old('judul')" 
                                placeholder="Contoh: Jadwal Posyandu Bulanan"
                                required />
                            <x-input-error :messages="$errors->get('judul')" class="mt-2" />
                        </div>

                        {{-- Deskripsi Lengkap --}}
                        <div>
                            <x-input-label for="deskripsi" :value="__('Deskripsi Lengkap')" class="text-sm font-medium text-gray-700" />
                            <textarea id="deskripsi" 
                                name="deskripsi" 
                                rows="5"
                                placeholder="Jelaskan detail informasi gizi di sini..."
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20">{{ old('deskripsi') }}</textarea>
                            <x-input-error :messages="$errors->get('deskripsi')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                        <a href="{{ route('gizi.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
                            Batal
                        </a>
                        <x-primary-button class="rounded-lg bg-gradient-to-r from-red-600 to-rose-600 px-6 py-2.5 text-sm font-semibold shadow-md transition hover:from-red-700 hover:to-rose-700 hover:shadow-lg focus:ring-red-500">
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
