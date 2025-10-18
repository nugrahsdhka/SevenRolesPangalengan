<x-app-layout>
    <x-slot name="title">
        Edit Data Pencegahan
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                {{-- Header --}}
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-md">
                        {{-- Ikon Edit --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Formulir Edit Data Pencegahan</h3>
                        <p class="text-sm text-gray-500">Perbarui informasi kegiatan pencegahan</p>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('pencegahan.update', $data->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                        {{-- Periode --}}
                        <div>
                            <x-input-label for="periode" :value="__('Periode Laporan')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="periode" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="month" 
                                name="periode" 
                                :value="old('periode', $data->periode->format('Y-m'))" 
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
                                :value="old('tanggal_kegiatan', $data->tanggal_kegiatan->format('Y-m-d'))" 
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
                                :value="old('judul_kegiatan', $data->judul_kegiatan)" 
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
                                :value="old('lokasi', $data->lokasi)" 
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
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20">{{ old('laporan_singkat', $data->laporan_singkat) }}</textarea>
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
                            Simpan Perubahan
                        </x-primary-button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>
