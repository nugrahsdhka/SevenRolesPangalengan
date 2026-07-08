<x-app-layout>
    <x-slot name="title">
        RSP | Faskes
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-black text-lg sm:text-xl text-gray-900 leading-tight tracking-tight uppercase">
                Tambah Data <span class="text-primary-500">Faskes</span>
            </h2>

            {{-- Tombol kembali --}}
            <a href="{{ route('admin.faskes.index') }}"
               class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 bg-white/80 backdrop-blur-md px-4 py-2.5 text-sm font-black text-gray-700 transition-all hover:bg-white hover:border-primary-200 hover:text-primary-500 shadow-sm hover:shadow-md active:scale-95">
                <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6 px-1">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 backdrop-blur-xl shadow-sm rounded-3xl border border-white/50 p-6 md:p-8 space-y-6">

                {{-- Error --}}
                @if ($errors->any())
                    <div class="rounded-2xl bg-primary-50/80 border border-primary-200/60 px-5 py-4 text-sm text-primary-800 backdrop-blur-sm">
                        <div class="font-black mb-2 uppercase tracking-wider text-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terdapat beberapa kesalahan:
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-xs font-semibold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.faskes.store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Nama Faskes --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                            Nama Faskes <span class="text-primary-500">*</span>
                        </label>
                        <input type="text" name="nama_faskes" value="{{ old('nama_faskes') }}"
                               class="block w-full rounded-2xl border-gray-200 bg-gray-50/80 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all px-4 py-3"
                               placeholder="Contoh: Puskesmas Pangalengan" required>
                    </div>

                    {{-- Jenis --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                            Jenis Faskes <span class="text-primary-500">*</span>
                        </label>
                        <select name="jenis"
                                class="block w-full rounded-2xl border-gray-200 bg-gray-50/80 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all px-4 py-3 cursor-pointer"
                                required>
                            <option value="" disabled {{ old('jenis') ? '' : 'selected' }}>-- Pilih jenis --</option>
                            @php
                                $jenisList = [
                                    'Puskesmas',
                                    'Klinik',
                                    'Posyandu',
                                    'Rumah Sakit',
                                    'Praktik Dokter',
                                    'Lainnya',
                                ];
                            @endphp
                            @foreach ($jenisList as $jenis)
                                <option value="{{ $jenis }}" {{ old('jenis') === $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Alamat --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                            Alamat
                        </label>
                        <textarea name="alamat" rows="3"
                                  class="block w-full rounded-2xl border-gray-200 bg-gray-50/80 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all px-4 py-3 resize-none"
                                  placeholder="Jl. ....">{{ old('alamat') }}</textarea>
                    </div>

                    {{-- Desa/Kelurahan --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                            Desa / Kelurahan
                        </label>
                        <input type="text" name="desa_kelurahan" value="{{ old('desa_kelurahan') }}"
                               class="block w-full rounded-2xl border-gray-200 bg-gray-50/80 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all px-4 py-3"
                               placeholder="Contoh: Margamulya">
                    </div>

                    {{-- Koordinat --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                                Latitude (Lat) <span class="text-primary-500">*</span>
                            </label>
                            <input type="text" name="lat" value="{{ old('lat') }}"
                                   class="block w-full rounded-2xl border-gray-200 bg-gray-50/80 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all px-4 py-3"
                                   placeholder="-7.1766000000001" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-gray-500 uppercase tracking-widest">
                                Longitude (Lng) <span class="text-primary-500">*</span>
                            </label>
                            <input type="text" name="lng" value="{{ old('lng') }}"
                                   class="block w-full rounded-2xl border-gray-200 bg-gray-50/80 text-sm font-bold text-gray-700 shadow-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all px-4 py-3"
                                   placeholder="107.57108195491446" required>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-3 pt-2 p-4 bg-primary-50/40 rounded-2xl border border-primary-100/50">
                        <input type="hidden" name="is_active" value="0">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                   class="w-5 h-5 rounded-lg border-gray-300 text-primary-500 focus:ring-primary-500 focus:ring-offset-0 transition-all cursor-pointer"
                                   {{ old('is_active', 1) ? 'checked' : '' }}>
                        </div>
                        <label for="is_active" class="text-sm font-bold text-gray-700 cursor-pointer select-none">
                            Aktifkan faskes ini
                        </label>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('admin.faskes.index') }}"
                           class="inline-flex items-center px-6 py-3 rounded-2xl border border-gray-200 text-sm font-black text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all active:scale-95 shadow-sm">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl bg-primary-500 text-white text-sm font-black shadow-lg shadow-primary-200/50 hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-200/40 active:scale-95 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Faskes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>