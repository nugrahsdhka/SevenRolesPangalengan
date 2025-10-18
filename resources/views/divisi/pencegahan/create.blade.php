<x-app-layout>
    <x-slot name="title">
        Tambah Data Pencegahan
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                {{-- Header --}}
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-md">
                        {{-- Ikon Pencegahan (Shield) --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Formulir Tambah Data Pencegahan</h3>
                        <p class="text-sm text-gray-500">Lengkapi informasi kegiatan pencegahan</p>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('pencegahan.store') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
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
                        
                        {{-- Tanggal Kegiatan --}}
                        <div>
                            <x-input-label for="tanggal_kegiatan" :value="__('Tanggal Kegiatan')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="tanggal_kegiatan" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="date" 
                                name="tanggal_kegiatan" 
                                :value="old('tanggal_kegiatan')" 
                                required />
                            <x-input-error :messages="$errors->get('tanggal_kegiatan')" class="mt-2" />
                        </div>

                        {{-- Judul Kegiatan --}}
                        <div class="md:col-span-2">
                            <x-input-label for="judul_kegiatan" :value="__('Judul Kegiatan')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="judul_kegiatan" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="text" 
                                name="judul_kegiatan" 
                                :value="old('judul_kegiatan')" 
                                placeholder="Contoh: Sosialisasi Pencegahan Stunting"
                                required />
                            <x-input-error :messages="$errors->get('judul_kegiatan')" class="mt-2" />
                        </div>

                        {{-- Lokasi Kegiatan --}}
                        <div class="md:col-span-2">
                            <x-input-label for="lokasi" :value="__('Lokasi Kegiatan')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="lokasi" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="text" 
                                name="lokasi" 
                                :value="old('lokasi')" 
                                placeholder="Contoh: Balai Desa Margamulya"
                                required />
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>

                        {{-- Laporan Singkat --}}
                        <div class="md:col-span-2">
                            <x-input-label for="laporan_singkat" :value="__('Laporan Singkat Kegiatan')" class="text-sm font-medium text-gray-700" />
                            <textarea id="laporan_singkat" 
                                name="laporan_singkat" 
                                rows="5"
                                placeholder="Jelaskan secara singkat kegiatan yang telah dilakukan..."
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20">{{ old('laporan_singkat') }}</textarea>
                            <x-input-error :messages="$errors->get('laporan_singkat')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                        <a href="{{ route('pencegahan.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
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
