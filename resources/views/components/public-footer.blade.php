{{-- ====== PUBLIC FOOTER (dipakai semua halaman guest) ====== --}}
@php
    $footerMenu = [
        ['route' => 'index', 'label' => 'Home'],
        ['route' => 'data-stunting', 'label' => 'Data Stunting'],
        ['route' => 'data-wilayah', 'label' => 'Data Wilayah'],
        ['route' => 'analisis-hotspot', 'label' => 'Analisis Hotspot'],
        ['route' => 'peta-faskes', 'label' => 'Peta Faskes'],
    ];

    $footerLayanan = [
        ['route' => 'pengaduan.create', 'label' => 'Buat Pengaduan'],
        ['route' => 'pengaduan.track', 'label' => 'Cek Status Pengaduan'],
    ];

    // Taruh file logo di public/img/ (kalau file belum ada, logonya otomatis disembunyikan)
    $developers = [
        ['file' => 'img/logo-telkom-university.png', 'alt' => 'Logo Telkom University', 'name' => 'Telkom University'],
        ['file' => 'img/logo-teknik-komputer.png',   'alt' => 'Logo S1 Teknik Komputer', 'name' => 'S1 Teknik Komputer'],
    ];
@endphp

<footer class="relative bg-gray-900 text-gray-400">

    {{-- Aksen garis tipis di atas --}}
    <div class="h-px w-full bg-gradient-to-r from-transparent via-primary-500/60 to-transparent"></div>

    <div class="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 pt-8 pb-5 md:pt-14 md:pb-8">

        {{-- Mobile: 2 kolom (identitas full, menu & layanan berdampingan). Desktop: 12 kolom --}}
        <div class="grid grid-cols-2 md:grid-cols-12 gap-x-6 gap-y-7 md:gap-8">

            {{-- Identitas --}}
            <div class="col-span-2 md:col-span-5">
                <a href="{{ route('index') }}" class="inline-flex items-center gap-3 group">
                    <img src="{{ asset('img/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung"
                         class="h-9 md:h-11 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    <div class="flex flex-col leading-none">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-500">Rumah Stunting</span>
                        <span class="mt-1 text-base md:text-lg font-bold uppercase tracking-tight text-white">Pangalengan</span>
                    </div>
                </a>
                <p class="mt-3 md:mt-5 max-w-sm text-xs md:text-sm leading-relaxed text-gray-500">
                    Sistem informasi data stunting untuk memantau status gizi dan sebaran kasus
                    di Kecamatan Pangalengan, Kabupaten Bandung.
                </p>
            </div>

            {{-- Menu --}}
            <div class="col-span-1 md:col-span-3">
                <h3 class="mb-3 md:mb-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-500">Menu</h3>
                <ul class="space-y-2 md:space-y-3">
                    @foreach ($footerMenu as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="text-[13px] md:text-sm transition-colors duration-200 hover:text-white {{ request()->routeIs($item['route']) ? 'text-white font-medium' : '' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Layanan --}}
            <div class="col-span-1 md:col-span-4">
                <h3 class="mb-3 md:mb-5 text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-500">Layanan Warga</h3>
                <ul class="space-y-2 md:space-y-3">
                    @foreach ($footerLayanan as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="text-[13px] md:text-sm transition-colors duration-200 hover:text-white {{ request()->routeIs($item['route']) ? 'text-white font-medium' : '' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Dikembangkan oleh --}}
        <div class="mt-7 md:mt-12 pt-5 md:pt-8 border-t border-white/5 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-8">
            <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-500">
                Dikembangkan oleh
            </span>

            <div class="flex flex-wrap items-center gap-x-5 md:gap-x-8 gap-y-3">
                @foreach ($developers as $dev)
                    @if (file_exists(public_path($dev['file'])))
                        <div class="flex items-center gap-2 md:gap-3">
                            <img src="{{ asset($dev['file']) }}" alt="{{ $dev['alt'] }}"
                                 class="h-7 md:h-9 w-auto object-contain opacity-90 transition-opacity duration-200 hover:opacity-100">
                            <span class="text-xs md:text-sm font-medium text-gray-300">{{ $dev['name'] }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-5 md:mt-8 pt-4 md:pt-6 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-1 md:gap-2 text-center md:text-left">
            <p class="text-[11px] md:text-xs text-gray-600">
                &copy; {{ date('Y') }} Rumah Stunting Pangalengan. All rights reserved.
            </p>
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-gray-600">
                Kecamatan Pangalengan &bull; v{{ config('app_version.version') }}
            </p>
        </div>
    </div>
</footer>