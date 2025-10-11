<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Stunting — Pangalengan</title>

    <link rel="icon" href="{{ asset('img/logo-kab-bandung.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">

    {{-- ====== HEADER / HERO SECTION (Desain Anda) ====== --}}
    <header class="relative overflow-hidden bg-gradient-to-br from-red-600 via-red-500 to-rose-900">
        {{-- Animated gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-red-900/40 via-transparent to-rose-900/40"></div>

        {{-- Geometric patterns --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 h-64 w-64 rounded-full bg-white blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 h-96 w-96 rounded-full bg-white blur-3xl"></div>
        </div>

        {{-- Modern dots pattern --}}
        <div class="absolute inset-0 opacity-[0.15]">
            <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="modern-dots" x="0" y="0" width="32" height="32" patternUnits="userSpaceOnUse">
                        <circle cx="2" cy="2" r="1.5" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#modern-dots)"/>
            </svg>
        </div>

        {{-- Decorative shapes --}}
        <div class="pointer-events-none absolute -top-40 -right-40 h-80 w-80 rounded-full border border-white/20"></div>
        <div class="pointer-events-none absolute top-1/2 -left-20 h-60 w-60 rounded-full border-2 border-white/10"></div>

        {{-- NAVBAR --}}
        <nav class="relative z-20">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    {{-- Modern Logo with glow effect --}}
                    <div class="relative">
                        <div class="absolute inset-0 rounded-2xl bg-white/20 blur-md"></div>
                        <span class="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-white/20 shadow-lg ring-1 ring-white/30 backdrop-blur-xl transition hover:bg-white/30 hover:scale-105">
                            <svg class="h-7 w-7 text-white drop-shadow-lg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                            </svg>
                        </span>
                    </div>
                    <div class="leading-tight">
                        <p class="text-xs font-semibold uppercase tracking-wider text-white/80 sm:text-sm">Sistem Informasi Stunting</p>
                        <h1 class="text-xl font-bold text-white drop-shadow-sm sm:text-2xl">Kecamatan Pangalengan</h1>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="group relative overflow-hidden rounded-xl bg-white/10 px-5 py-2.5 text-sm font-semibold text-white shadow-lg ring-1 ring-white/20 backdrop-blur-xl transition hover:bg-white/20 hover:shadow-xl">
                                <span class="relative z-10">Dashboard</span>
                                <div class="absolute inset-0 -z-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 opacity-0 transition-opacity group-hover:opacity-100"></div>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="group relative overflow-hidden rounded-xl bg-white px-6 py-2.5 text-sm font-bold text-red-600 shadow-xl transition hover:shadow-2xl hover:scale-105">
                                <span class="relative z-10">Masuk</span>
                                <div class="absolute inset-0 -z-0 bg-gradient-to-r from-red-50 to-rose-50 opacity-0 transition-opacity group-hover:opacity-100"></div>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        {{-- HERO content with modern styling --}}
        <div class="relative z-10">
            <div class="mx-auto max-w-7xl px-4 pb-32 pt-16 sm:px-6 lg:px-8">
                <div class="max-w-4xl">
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-white/15 px-4 py-2 backdrop-blur-xl ring-1 ring-white/20">
                        <span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-75"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-white"></span></span>
                        <span class="text-sm font-medium text-white">Portal Terpercaya & Terverifikasi</span>
                    </div>
                    <h2 class="mb-6 text-5xl font-extrabold leading-tight text-white drop-shadow-lg md:text-6xl lg:text-7xl">
                        Portal Informasi Publik
                        <span class="bg-gradient-to-r from-white via-rose-100 to-white bg-clip-text text-transparent">Penanganan Stunting</span>
                    </h2>
                    <p class="mb-8 max-w-2xl text-lg leading-relaxed text-white/90 sm:text-xl">
                        Menyajikan data terverifikasi dari berbagai divisi untuk penanganan yang lebih terarah, transparan, dan efektif.
                    </p>
                </div>
            </div>
        </div>

        {{-- Bottom wave decoration --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg class="w-full text-slate-50" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="currentColor"/></svg>
        </div>
    </header>

    {{-- ====== KONTEN DINAMIS DARI DATABASE ====== --}}
    <main class="mt-9">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2">
                {{-- DATA GIZI PUBLIK --}}
                @if($publishedData['gizi']->isNotEmpty())
                    <section class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 transition hover:shadow-xl sm:p-8">
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-red-500/5 blur-2xl transition-transform group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="mb-6 flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-md"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg></span>
                                <h3 class="text-lg font-bold text-gray-900">Informasi Gizi</h3>
                            </div>
                            
                            {{-- ✅ PERBAIKAN: Tambahkan max-h-96, overflow-y-auto, dan pr-4 --}}
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-4">
                                @foreach($publishedData['gizi'] as $data)
                                    <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-4 transition hover:border-red-200 hover:bg-red-50/30">
                                        <h4 class="font-semibold text-gray-900">{{ $data->judul }}</h4>
                                        <p class="mt-2 text-sm leading-relaxed text-gray-600 preserve-lines">{{ $data->deskripsi }}</p>
                                        <p class="mt-2 text-xs text-gray-500">{{ $data->periode->format('F Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                
                {{-- DATA PENCEGAHAN PUBLIK --}}
                @if($publishedData['pencegahan']->isNotEmpty())
                    <section class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 transition hover:shadow-xl sm:p-8">
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-rose-500/5 blur-2xl transition-transform group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="mb-6 flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-md"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg></span>
                                <h3 class="text-lg font-bold text-gray-900">Kegiatan Pencegahan</h3>
                            </div>

                            {{-- ✅ PERBAIKAN: Tambahkan max-h-96, overflow-y-auto, dan pr-4 --}}
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-4">
                                @foreach($publishedData['pencegahan'] as $data)
                                    <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-4 transition hover:border-rose-200 hover:bg-rose-50/30">
                                        <h4 class="font-semibold text-gray-900">{{ $data->judul_kegiatan }}</h4>
                                        <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $data->laporan_singkat }}</p>
                                        <div class="mt-3 flex flex-wrap gap-3 text-xs text-gray-500"><span class="inline-flex items-center gap-1"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>{{ $data->lokasi }}</span><span>•</span><span>{{ \Carbon\Carbon::parse($data->tanggal_kegiatan)->format('d M Y') }}</span><span>•</span><span>{{ $data->periode->format('F Y') }}</span></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                {{-- DATA PENANGGULANGAN PUBLIK --}}
                @if($publishedData['penanggulangan']->isNotEmpty())
                    <section class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 transition hover:shadow-xl sm:p-8">
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-orange-500/5 blur-2xl transition-transform group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="mb-6 flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-orange-500 to-red-600 text-white shadow-md"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg></span>
                                <h3 class="text-lg font-bold text-gray-900">Laporan Penanggulangan</h3>
                            </div>

                            {{-- ✅ PERBAIKAN: Tambahkan max-h-96, overflow-y-auto, dan pr-4 --}}
                            <div class="space-y-4 max-h-96 overflow-y-auto pr-4">
                                @foreach($publishedData['penanggulangan'] as $data)
                                    <div class="rounded-lg border border-gray-100 bg-gray-50/50 p-4 transition hover:border-orange-200 hover:bg-orange-50/30">
                                        <div class="flex items-start justify-between gap-2"><h4 class="font-semibold text-gray-900">{{ $data->jenis_bencana }}</h4><span class="rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">{{$data->jumlah_korban}} Korban</span></div>
                                        <p class="mt-2 text-sm leading-relaxed text-gray-600">{{ $data->tindakan }}</p>
                                        <p class="mt-2 text-xs text-gray-500">{{ $data->periode->format('F Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                
                {{-- DATA DESA PUBLIK (Tanpa Scroll Vertikal) --}}
                @if($publishedData['desa']->isNotEmpty())
                    <section 
                        x-data="{
                            items: {{ Js::from($publishedData['desa']) }},
                            currentIndex: 0,
                            get currentItem() {
                                return this.items[this.currentIndex];
                            },
                            next() {
                                this.currentIndex = this.currentIndex === this.items.length - 1 ? 0 : this.currentIndex + 1;
                            },
                            previous() {
                                this.currentIndex = this.currentIndex === 0 ? this.items.length - 1 : this.currentIndex - 1;
                            }
                        }"
                        class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 transition hover:shadow-xl sm:p-8">
                        <div class="absolute -right-8 -top-8 h-24 w-24 rounded-full bg-emerald-500/5 blur-2xl transition-transform group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="mb-6 flex items-center gap-3">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md"><svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" /></svg></span>
                                <h3 class="text-lg font-bold text-gray-900">Data Desa</h3>
                            </div>
                            
                            <div class="min-h-[320px] sm:min-h-[280px]">
                                <template x-if="items.length > 0">
                                    <div x-show="items.length > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0">
                                        <h4 class="text-xl font-bold text-gray-900" x-text="currentItem.nama_desa"></h4>
                                        <div class="mt-4 grid grid-cols-3 gap-3">
                                            <div class="rounded-lg bg-white p-3 shadow-sm ring-1 ring-gray-200/50"><p class="text-xs text-gray-500">Penduduk</p><p class="mt-1 text-base font-bold text-gray-900" x-text="new Intl.NumberFormat('id-ID').format(currentItem.jumlah_penduduk)"></p></div>
                                            <div class="rounded-lg bg-red-50 p-3 ring-1 ring-red-200/50"><p class="text-xs text-red-900">Kasus Stunting</p><p class="mt-1 text-base font-bold text-red-600" x-text="new Intl.NumberFormat('id-ID').format(currentItem.jumlah_kasus_stunting)"></p></div>
                                            <div class="rounded-lg bg-white p-3 shadow-sm ring-1 ring-gray-200/50"><p class="text-xs text-gray-500">Luas Wilayah</p><p class="mt-1 text-sm font-semibold text-gray-900"><span x-text="new Intl.NumberFormat('id-ID').format(currentItem.luas_wilayah)"></span> m²</p></div>
                                        </div>
                                        <div class="mt-4 rounded-lg bg-white p-3 shadow-sm ring-1 ring-gray-200/50"><p class="text-xs text-gray-500">Potensi Desa</p><p class="mt-1 text-sm font-semibold text-gray-900"><span x-text="currentItem.potensi_desa"></span></p></div>
                                        <p class="mt-3 text-xs text-gray-500" x-text="`Periode Laporan: ${new Date(currentItem.periode).toLocaleString('id-ID', { month: 'long', year: 'numeric' })}`"></p>
                                    </div>
                                </template>
                            </div>

                            <div x-show="items.length > 1" class="mt-6 flex items-center justify-between border-t border-gray-200 pt-4">
                                <button @click="previous()" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"><span class="sr-only">Previous</span><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
                                <p class="text-sm font-medium text-gray-600">Desa <span x-text="currentIndex + 1"></span> dari <span x-text="items.length"></span></p>
                                <button @click="next()" class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed"><span class="sr-only">Next</span><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </main>

    <footer class="relative mt-20 overflow-hidden bg-gradient-to-br from-red-600 via-red-500 to-rose-900">
        <div class="absolute top-0 left-0 right-0">
            <svg class="w-full text-slate-50" viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"><path d="M0 60L60 54C120 48 240 36 360 32C480 28 600 32 720 34C840 36 960 36 1080 32C1200 28 1320 20 1380 16L1440 12V0H0V60Z" fill="currentColor"/></svg>
        </div>
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-red-900/20 to-transparent"></div>
        <div class="relative z-10 px-4 py-12 text-center">
            <p class="text-sm text-slate-50">&copy; {{ date('Y') }} Sistem Informasi Stunting Kecamatan Pangalengan</p>
            <p class="mt-1 text-xs text-slate-50">Portal Terpercaya & Terverifikasi</p>
        </div>
    </footer>

</body>
</html>