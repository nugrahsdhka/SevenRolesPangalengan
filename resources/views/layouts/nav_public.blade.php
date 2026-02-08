<nav x-data="{ mobileMenuOpen: false }" 
     class="sticky top-0 z-[60] bg-white border-b border-gray-100 shadow-md transition-all duration-300 font-bold">
    
    {{-- Desktop Navigation --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            {{-- Logo Section (Konsisten di semua route) --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('welcome') }}" class="group flex items-center gap-3">
                    <div class="relative">
                        <div class="absolute -inset-1 bg-red-500 rounded-full blur opacity-10 group-hover:opacity-30 transition duration-500"></div>
                        <img class="relative h-10 w-auto object-contain transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-3" 
                             src="{{ asset('img/logo-kab-bandung.png') }}" 
                             alt="Logo Kabupaten Bandung">
                    </div>
                    <div class="flex flex-col leading-none">
                        <div class="flex justify-start items-start gap-1">
                            <span class="font-black text-gray-900 tracking-tighter text-xs uppercase">Rumah</span>
                            <span class="font-bold text-gray-900 tracking-tighter text-xs uppercase">Stunting</span>
                        </div>
                        <span class="font-bold text-red-600 tracking-tighter text-base uppercase">Pangalengan</span>
                    </div>
                </a>
            </div>

            {{-- Nav Links (Konsisten di semua route) --}}
            <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                @php
                    $navItems = [
                        ['route' => 'welcome', 'label' => 'Home', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'data-stunting', 'label' => 'Data Stunting', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ['route' => 'data-wilayah', 'label' => 'Data Wilayah', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'analisis-hotspot', 'label' => 'Analisis Hotspot', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        ['route' => 'peta-faskes', 'label' => 'Faskes', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="relative group px-4 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs($item['route']) ? 'text-red-600 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                        <div class="flex items-center space-x-2">
                            <svg class="h-4 w-4 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            <span class="text-sm font-black tracking-tight uppercase">{{ __($item['label']) }}</span>
                        </div>
                    </a>
                @endforeach

                @auth
                    <div class="h-6 w-px bg-gray-200 mx-4"></div>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-2 px-6 py-2.5 bg-gray-900 text-white rounded-2xl hover:bg-red-600 transition-all duration-500 shadow-lg shadow-gray-200 hover:shadow-red-200 group">
                        <span class="text-sm font-black uppercase tracking-widest">Dashboard</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                @endauth
            </div>

            {{-- Mobile Toggle --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="p-2 rounded-xl bg-gray-50 text-gray-900 transition-all active:scale-95">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7" /></svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-cloak
         class="md:hidden absolute top-full left-0 w-full bg-white shadow-2xl border-t border-gray-100 py-6 px-6 space-y-2 z-50">
        
        @foreach($navItems as $item)
            <a href="{{ route($item['route']) }}" 
               class="flex items-center space-x-4 p-4 rounded-2xl transition-all {{ request()->routeIs($item['route']) ? 'bg-red-50 text-red-600' : 'text-gray-600 active:bg-gray-50' }}">
                <div class="p-2 rounded-lg {{ request()->routeIs($item['route']) ? 'bg-white shadow-sm' : 'bg-gray-100' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" /></svg>
                </div>
                <span class="font-bold uppercase tracking-widest text-sm">{{ __($item['label']) }}</span>
            </a>
        @endforeach

        @auth
            <div class="my-4 border-t border-gray-100 pt-4 text-center">
                <a href="{{ route('dashboard') }}" class="inline-block w-full px-6 py-4 bg-red-600 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg">Kembali ke Dashboard</a>
            </div>
        @endauth
    </div>
</nav>