<x-app-layout>
    <x-slot name="title">
        Tambah Data Penanggulangan
    </x-slot>

    <div class="px-2 py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                {{-- Header --}}
                <div class="mb-8 flex items-center gap-3">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-md">
                        {{-- Ikon Peringatan Bencana --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex flex-col justify-start items-start">
                        <h3 class="text-lg font-bold text-red-700">Formulir Tambah Data Penanggulangan</h3>
                        <p class="text-xs font-bold text-red-700">Lengkapi Form di Bawah</p>
                    </div>
                </div>

                {{-- Form --}}
                <form method="POST" action="{{ route('penanggulangan.store') }}" class="space-y-6">
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

                        {{-- Jenis Bencana --}}
                        <div>
                            <x-input-label for="jenis_bencana" :value="__('Jenis Bencana')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="jenis_bencana" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="text" 
                                name="jenis_bencana" 
                                :value="old('jenis_bencana')" 
                                placeholder="Contoh: Banjir, Tanah Longsor"
                                required />
                            <x-input-error :messages="$errors->get('jenis_bencana')" class="mt-2" />
                        </div>

                        {{-- Jumlah Korban --}}
                        <div>
                            <x-input-label for="jumlah_korban" :value="__('Jumlah Korban Jiwa')" class="text-sm font-medium text-gray-700" />
                            <x-text-input id="jumlah_korban" 
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-2.5 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20" 
                                type="number" 
                                name="jumlah_korban" 
                                :value="old('jumlah_korban')" 
                                placeholder="0"
                                required />
                            <x-input-error :messages="$errors->get('jumlah_korban')" class="mt-2" />
                        </div>

                        {{-- Tindakan --}}
                        <div>
                            <x-input-label for="tindakan" :value="__('Tindakan yang Dilakukan')" class="text-sm font-medium text-gray-700" />
                            <textarea id="tindakan" 
                                name="tindakan" 
                                rows="5"
                                placeholder="Jelaskan tindakan penanggulangan yang telah dilakukan..."
                                class="mt-2 block w-full rounded-lg border-gray-300 bg-gray-50 px-4 py-3 transition focus:border-red-500 focus:bg-white focus:ring-2 focus:ring-red-500/20">{{ old('tindakan') }}</textarea>
                            <x-input-error :messages="$errors->get('tindakan')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-200 pt-6">
                        <a href="{{ route('penanggulangan.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-100">
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
