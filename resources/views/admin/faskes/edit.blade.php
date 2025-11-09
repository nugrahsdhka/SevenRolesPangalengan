<x-app-layout>
    <x-slot name="title">
        RSP | Faskes
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Kelola Fasilitas Kesehatan
            </h2>

            {{-- Tombol kembali --}}
            <a href="{{ route('admin.published.index') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 shadow-sm">
                 <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 space-y-6">

                {{-- Error --}}
                @if ($errors->any())
                    <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">
                        <div class="font-semibold mb-1">Terdapat beberapa kesalahan:</div>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.faskes.update', $faskes) }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Nama Faskes --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">
                            Nama Faskes <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_faskes"
                               value="{{ old('nama_faskes', $faskes->nama_faskes) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    {{-- Jenis --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">
                            Jenis Faskes <span class="text-red-500">*</span>
                        </label>
                        @php
                            $jenisList = ['Puskesmas', 'Klinik', 'Posyandu', 'Rumah Sakit', 'Praktik Dokter', 'Lainnya'];
                            $selectedJenis = old('jenis', $faskes->jenis);
                        @endphp
                        <select name="jenis"
                                class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-red-500 focus:border-red-500"
                                required>
                            <option value="" disabled {{ $selectedJenis ? '' : 'selected' }}>-- Pilih jenis --</option>
                            @foreach ($jenisList as $jenis)
                                <option value="{{ $jenis }}" {{ $selectedJenis === $jenis ? 'selected' : '' }}>
                                    {{ $jenis }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Alamat --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">
                            Alamat
                        </label>
                        <textarea name="alamat" rows="2"
                                  class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-red-500 focus:border-red-500">{{ old('alamat', $faskes->alamat) }}</textarea>
                    </div>

                    {{-- Desa/Kelurahan --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">
                            Desa / Kelurahan
                        </label>
                        <input type="text" name="desa_kelurahan"
                               value="{{ old('desa_kelurahan', $faskes->desa_kelurahan) }}"
                               class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-red-500 focus:border-red-500">
                    </div>

                    {{-- Koordinat --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                Latitude (Lat) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="lat"
                                value="{{ old('lat', $faskes->lat) }}"
                                class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-red-500 focus:border-red-500"
                                placeholder="-7.1766000000001" required>
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">
                                Longitude (Lng) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="lng"
                                value="{{ old('lng', $faskes->lng) }}"
                                class="block w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-red-500 focus:border-red-500"
                                placeholder="107.57108195491446" required>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="flex items-center gap-2 pt-2">
                        <input type="hidden" name="is_active" value="0">

                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            class="rounded border-gray-300 text-red-600 focus:ring-red-500"
                            {{ old('is_active', $faskes->is_active) ? 'checked' : '' }}>
                        <label for="is_active" class="text-sm text-gray-700">
                            Faskes ini aktif
                        </label>
                    </div>

                    <div class="pt-4 flex justify-end gap-3">
                        <a href="{{ route('admin.faskes.index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-200 text-sm text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium shadow-sm hover:bg-red-700 hover:shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
