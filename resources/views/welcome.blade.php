<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Stunting — Pangalengan</title>

    <link rel="icon" href="{{ asset('img/logo-kab-bandung.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50">

    {{-- ====== HEADER / HERO SECTION (Desain Baru) ====== --}}
    <header class="relative overflow-hidden">
        {{-- Background gradien merah --}}
        <div class="absolute inset-0 bg-gradient-to-br from-red-900 via-red-700 to-rose-800"></div>

        {{-- Aksen blur halus --}}
        <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-rose-400/20 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 -left-20 h-96 w-96 rounded-full bg-rose-300/10 blur-3xl"></div>

        {{-- Tekstur dots halus --}}
        <div class="absolute inset-0 opacity-[0.07]">
            <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="dots" x="0" y="0" width="24" height="24" patternUnits="userSpaceOnUse">
                        <circle cx="1.2" cy="1.2" r="1.2" fill="white"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#dots)"/>
            </svg>
        </div>

        {{-- NAVBAR --}}
        <nav class="relative z-10">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    {{-- LOGO BARU --}}
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-white/15 ring-1 ring-white/20 backdrop-blur">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                    </span>
                    <div class="leading-tight">
                        <p class="text-sm font-medium tracking-wide text-rose-100/90">Sistem Informasi Stunting</p>
                        <h1 class="text-lg font-bold text-white">Kecamatan Pangalengan</h1>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                     @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-rose-50/90 hover:text-white transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="rounded-md bg-white/10 px-4 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-white/20 backdrop-blur hover:bg-white/20 transition">Masuk</a>
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        {{-- HERO content --}}
        <div class="relative z-10">
            <div class="mx-auto max-w-7xl px-4 pb-24 pt-12 sm:px-6 lg:px-8">
                <div class="max-w-3xl">
                    <h2 class="text-4xl font-bold leading-tight text-white md:text-5xl lg:text-6xl">
                        Portal Informasi Publik Penanganan Stunting
                    </h2>
                    <p class="mt-4 text-lg text-rose-100/90">Menyajikan data terverifikasi dari berbagai divisi untuk penanganan yang lebih terarah dan transparan.</p>
                </div>
            </div>
        </div>
    </header>

    {{-- ====== KONTEN DINAMIS DARI DATABASE ====== --}}
    <main class="mt-4">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-2">

                <!-- DATA GIZI PUBLIK -->
                @if($publishedData['gizi']->isNotEmpty())
                    <section class="p-6 sm:p-8 bg-white shadow-xl rounded-2xl ring-1 ring-black/5">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </span>
                            <h3 class="text-xl font-bold text-red-900">Informasi Gizi</h3>
                        </div>
                        <div class="space-y-5 border-t border-black/10 pt-4">
                            @foreach($publishedData['gizi'] as $data)
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $data->judul }}</h4>
                                    <p class="mt-1 text-gray-700 text-sm">{{ $data->deskripsi }}</p>
                                    <p class="mt-1 text-gray-700 text-sm">Periode: {{ $data->periode->format('F Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                
                <!-- DATA PENCEGAHAN PUBLIK -->
                @if($publishedData['pencegahan']->isNotEmpty())
                    <section class="p-6 sm:p-8 bg-white shadow-xl rounded-2xl ring-1 ring-black/5">
                        <div class="flex items-center gap-4 mb-4">
                             <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </span>
                            <h3 class="text-xl font-bold text-red-900">Kegiatan Pencegahan</h3>
                        </div>
                         <div class="space-y-5 border-t border-black/10 pt-4">
                            @foreach($publishedData['pencegahan'] as $data)
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $data->judul_kegiatan }}</h4>
                                    <p class="mt-1 text-gray-700 text-sm">{{ $data->laporan_singkat }}</p>
                                    <p class="text-xs text-gray-500 mt-2">Lokasi: {{ $data->lokasi }} | Tanggal: {{ \Carbon\Carbon::parse($data->tanggal_kegiatan)->format('d M Y') }}</p>
                                    <p class="mt-1 text-gray-700 text-sm">Periode: {{ $data->periode->format('F Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- DATA PENANGGULANGAN PUBLIK -->
                @if($publishedData['penanggulangan']->isNotEmpty())
                    <section class="p-6 sm:p-8 bg-white shadow-xl rounded-2xl ring-1 ring-black/5">
                        <div class="flex items-center gap-4 mb-4">
                             <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                            </span>
                            <h3 class="text-xl font-bold text-red-900">Laporan Penanggulangan</h3>
                        </div>
                         <div class="space-y-5 border-t border-black/10 pt-4">
                            @foreach($publishedData['penanggulangan'] as $data)
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $data->jenis_bencana }} ({{$data->jumlah_korban}} Korban)</h4>
                                    <p class="mt-1 text-gray-700 text-sm">{{ $data->tindakan }}</p>
                                    <p class="mt-1 text-gray-700 text-sm">Periode: {{ $data->periode->format('F Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
                
                <!-- DATA DESA PUBLIK -->
                @if($publishedData['desa']->isNotEmpty())
                    <section class="p-6 sm:p-8 bg-white shadow-xl rounded-2xl ring-1 ring-black/5">
                        <div class="flex items-center gap-4 mb-4">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-red-100 text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" /></svg>
                            </span>
                            <h3 class="text-xl font-bold text-red-900">Data Desa</h3>
                        </div>
                         <div class="space-y-5 border-t border-black/10 pt-4">
                            @foreach($publishedData['desa'] as $data)
                                <div>
                                    <h4 class="font-bold text-xl text-gray-800">{{ $data->nama_desa }}</h4>
                                    <h4 class="font-semibold text-gray-800">Penduduk: {{number_format($data->jumlah_penduduk)}} | Luas Wilayah: {{number_format($data->luas_wilayah)}} m²</h4>
                                    <h4 class="font-medium text-gray-800">Kasus Stunting: {{number_format($data->jumlah_kasus_stunting)}}</h4>
                                    <p class="mt-1 text-gray-700 text-sm">{{ $data->potensi_desa }}</p>
                                    <p class="mt-1 text-gray-700 text-sm">Periode: {{ $data->periode->format('F Y') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </main>

    <footer class="py-16 text-center text-sm text-black/50">
        Sistem Informasi Stunting Kecamatan Pangalengan &copy; {{ date('Y') }}
    </footer>

</body>
</html>