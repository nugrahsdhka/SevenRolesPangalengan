<x-guest-layout>
    <x-slot name="title">
        RSP | Pengaduan Warga
    </x-slot>

    @include('layouts.nav_public')

    <div class="min-h-screen bg-gray-50/50 pb-12">
        <section class="max-w-3xl mx-auto px-4 py-6">

            {{-- Judul --}}
            <div class="mb-6 text-center md:text-left">
                <h1 class="text-2xl font-black text-gray-900 tracking-tight uppercase">
                    Pengaduan <span class="text-primary-500">Warga</span>
                </h1>
                <p class="mt-1 text-gray-500 font-bold uppercase text-[10px] tracking-[0.2em]">
                    Sampaikan permasalahan stunting di wilayah Kecamatan Pangalengan
                </p>
            </div>

            @include('Message.flash-message')

            <form method="POST" action="{{ route('pengaduan.store') }}"
                  class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-5">
                @csrf

                {{-- Honeypot anti-spam (disembunyikan dari manusia) --}}
                <div class="hidden" aria-hidden="true">
                    <label for="website">Website</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="nama" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">
                            Nama <span class="font-normal normal-case text-gray-400">(opsional)</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" maxlength="100"
                               class="w-full rounded-xl border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                               placeholder="Boleh dikosongkan">
                        @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kontak" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">
                            No. HP / Email <span class="font-normal normal-case text-gray-400">(opsional)</span>
                        </label>
                        <input type="text" id="kontak" name="kontak" value="{{ old('kontak') }}" maxlength="100"
                               class="w-full rounded-xl border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                               placeholder="Untuk tindak lanjut">
                        @error('kontak') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="desa_kelurahan" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">
                            Desa / Kelurahan <span class="text-primary-500">*</span>
                        </label>
                        <input type="text" id="desa_kelurahan" name="desa_kelurahan" list="desa-list"
                               value="{{ old('desa_kelurahan') }}" maxlength="100" required
                               class="w-full rounded-xl border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                               placeholder="Ketik atau pilih desa">
                        <datalist id="desa-list">
                            @foreach ($desaList as $desa)
                                <option value="{{ ucwords(strtolower($desa)) }}">
                            @endforeach
                        </datalist>
                        @error('desa_kelurahan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="kategori" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">
                            Kategori <span class="text-primary-500">*</span>
                        </label>
                        <select id="kategori" name="kategori" required
                                class="w-full rounded-xl border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500">
                            <option value="" disabled @selected(!old('kategori'))>Pilih kategori</option>
                            @foreach ($kategori as $value => $label)
                                <option value="{{ $value }}" @selected(old('kategori') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('kategori') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="judul" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">
                        Judul Pengaduan <span class="text-primary-500">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" value="{{ old('judul') }}" maxlength="150" required
                           class="w-full rounded-xl border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                           placeholder="Ringkasan singkat permasalahan">
                    @error('judul') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="isi" class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-1">
                        Isi Pengaduan <span class="text-primary-500">*</span>
                    </label>
                    <textarea id="isi" name="isi" rows="6" maxlength="3000" required
                              class="w-full rounded-xl border-gray-300 text-sm focus:border-primary-500 focus:ring-primary-500"
                              placeholder="Jelaskan kejadian, lokasi, dan waktu sedetail mungkin (minimal 20 karakter).">{{ old('isi') }}</textarea>
                    @error('isi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <p class="text-xs text-gray-400">
                    Nama dan kontak hanya dilihat petugas dan tidak ditampilkan di halaman publik.
                    Setelah mengirim, Anda akan mendapat <strong>kode tiket</strong> untuk memantau status.
                </p>

                <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                    <a href="{{ route('pengaduan.track') }}"
                       class="text-sm font-semibold text-gray-500 hover:text-primary-500 transition text-center">
                        Sudah punya kode tiket? Cek status
                    </a>
                    <button type="submit"
                            class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gray-900 text-white text-sm font-black uppercase tracking-widest hover:bg-primary-500 transition-all duration-500 shadow-lg shadow-gray-200">
                        Kirim Pengaduan
                    </button>
                </div>
            </form>
        </section>
    </div>
</x-guest-layout>
