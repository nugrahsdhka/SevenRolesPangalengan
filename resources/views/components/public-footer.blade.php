{{-- ====== PUBLIC FOOTER (dipakai semua halaman guest) ====== --}}
@php
    $footerMenu = [
        ['route' => 'index', 'label' => 'Home'],
        ['route' => 'data-stunting', 'label' => 'Data Stunting'],
        ['route' => 'data-wilayah', 'label' => 'Data Wilayah'],
        ['route' => 'analisis-hotspot', 'label' => 'Analisis Hotspot'],
        ['route' => 'peta-faskes', 'label' => 'Peta Faskes'],
    ];

    // Taruh file logo di public/img/logo-teknik-komputer.png (kalau belum ada, logo otomatis disembunyikan)
    $prodiLogo = 'img/logo-teknik-komputer.png';

    $footerLayanan = [
        ['route' => 'pengaduan.create', 'label' => 'Buat Pengaduan'],
        ['route' => 'pengaduan.track', 'label' => 'Cek Status Pengaduan'],
    ];
@endphp

<footer class="relative overflow-hidden bg-gray-900 text-gray-400">

    {{-- Aksen garis merah di atas --}}
    <div class="h-1 w-full bg-gradient-to-r from-primary-700 via-primary-500 to-primary-700"></div>

    {{-- Background blob --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full opacity-20 blur-[60px]"
             style="background: radial-gradient(circle, rgba(239,68,68,0.6) 0%, transparent 70%);"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">

            {{-- Identitas --}}
            <div>
                <a href="{{ route('index') }}" class="inline-flex items-center gap-3 group">
                    <img src="{{ asset('img/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung"
                         class="h-12 w-auto object-contain transition-transform duration-500 group-hover:scale-110">
                    <div class="flex flex-col leading-none">
                        <span class="font-black text-white tracking-tighter text-xs uppercase">Rumah Stunting</span>
                        <span class="font-bold text-primary-400 tracking-tighter text-lg uppercase">Pangalengan</span>
                    </div>
                </a>
                <p class="mt-4 text-sm leading-relaxed text-gray-400 max-w-xs">
                    Sistem informasi data stunting untuk memantau status gizi dan sebaran kasus
                    di Kecamatan Pangalengan, Kabupaten Bandung.
                </p>

                {{-- Dikembangkan oleh --}}
                <div class="mt-6 pt-5 border-t border-white/10 flex items-center gap-3">
                    @if (file_exists(public_path($prodiLogo)))
                        <div class="shrink-0 rounded-xl p-1.5">
                            <img src="{{ asset($prodiLogo) }}" alt="Logo S1 Teknik Komputer"
                                 class="h-12 w-auto object-contain">
                        </div>
                    @endif
                    <div class="flex flex-col leading-tight">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Dikembangkan oleh</span>
                        <span class="text-sm font-bold text-white">S1 Teknik Komputer</span>
                    </div>
                </div>
            </div>

            {{-- Menu --}}
            <div>
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-white mb-4">Menu</h3>
                <ul class="space-y-2.5">
                    @foreach ($footerMenu as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="text-sm font-semibold transition-colors hover:text-primary-400 {{ request()->routeIs($item['route']) ? 'text-primary-400' : '' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Layanan --}}
            <div>
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-white mb-4">Layanan Warga</h3>
                <ul class="space-y-2.5">
                    @foreach ($footerLayanan as $item)
                        <li>
                            <a href="{{ route($item['route']) }}"
                               class="text-sm font-semibold transition-colors hover:text-primary-400 {{ request()->routeIs($item['route']) ? 'text-primary-400' : '' }}">
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div class="mt-10 pt-6 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-2 text-center md:text-left">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} Rumah Stunting Pangalengan. All rights reserved.
            </p>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-600">
                Kecamatan Pangalengan &bull; v{{ config('app_version.version') }}
            </p>
        </div>
    </div>
</footer>
