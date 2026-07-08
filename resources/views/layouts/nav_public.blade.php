<nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)"
     :class="scrolled ? 'bg-white/90 backdrop-blur-xl shadow-lg shadow-gray-200/30 border-b border-white/20' : 'bg-white/80 backdrop-blur-md border-b border-gray-100/80'"
     class="sticky top-0 left-0 right-0 z-[100] transition-all duration-500">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            
            {{-- Logo --}}
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('index') }}" class="group flex items-center gap-3">
                    <div class="relative">
                        <div class="absolute -inset-1 bg-primary-500 rounded-full blur opacity-10 group-hover:opacity-30 transition duration-500"></div>
                        <img class="relative h-10 w-auto object-contain transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-3" 
                             src="{{ asset('img/logo-kab-bandung.png') }}" 
                             alt="Logo Kabupaten Bandung">
                    </div>
                    <div class="flex flex-col leading-none">
                        <div class="flex justify-start items-start gap-1">
                            <span class="font-black text-gray-900 tracking-tighter text-xs uppercase">Rumah</span>
                            <span class="font-bold text-gray-900 tracking-tighter text-xs uppercase">Stunting</span>
                        </div>
                        <span class="font-bold text-primary-500 tracking-tighter text-base uppercase">Pangalengan</span>
                    </div>
                </a>
            </div>

            {{-- Desktop Links --}}
            <div class="hidden md:flex items-center space-x-1 lg:space-x-2">
                @php
                    $navItems = [
                        ['route' => 'index', 'label' => 'Home', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'data-stunting', 'label' => 'Data Stunting', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ['route' => 'data-wilayah', 'label' => 'Data Wilayah', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'analisis-hotspot', 'label' => 'Analisis Hotspot', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z'],
                        ['route' => 'peta-faskes', 'label' => 'Faskes', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                    ];
                @endphp

                @foreach($navItems as $item)
                    <a href="{{ route($item['route']) }}" 
                       class="relative group px-4 py-2 rounded-xl transition-all duration-300 {{ request()->routeIs($item['route']) ? 'text-primary-500 bg-primary-50' : 'text-gray-500 hover:text-primary-500 hover:bg-primary-50/50' }}">
                        <div class="flex items-center space-x-2">
                            <svg class="h-4 w-4 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            <span class="text-sm font-black tracking-tight uppercase">{{ __($item['label']) }}</span>
                        </div>
                        @if(request()->routeIs($item['route']))
                            <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-full h-0.5 rounded-full bg-primary-500"></span>
                        @endif
                    </a>
                @endforeach

                @auth
                    <div class="h-6 w-px bg-gray-200 mx-4"></div>
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center gap-2 px-6 py-2.5 bg-gray-900 text-white rounded-2xl hover:bg-primary-500 transition-all duration-500 shadow-lg shadow-gray-200 hover:shadow-primary-200/50 group">
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
                        class="relative p-2.5 rounded-xl bg-gray-50/80 text-gray-900 transition-all active:scale-95 hover:bg-gray-100 border border-gray-100">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ============================================
         MOBILE MENU - TELEPORT KE BODY
         ============================================ --}}
    <template x-teleport="body">
        <div>
            {{-- Backdrop --}}
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 x-cloak
                 @click="mobileMenuOpen = false"
                 style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 99999; background: rgba(0,0,0,0.5); backdrop-filter: blur(8px);"
                 class="md:hidden">
            </div>

            {{-- Sidebar Panel --}}
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-full"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-full"
                 x-cloak
                 @click.away="mobileMenuOpen = false"
                 style="position: fixed; top: 0; right: 0; bottom: 0; width: 320px; max-width: 85vw; z-index: 999999; background: rgba(255,255,255,0.98); backdrop-filter: blur(20px); box-shadow: -8px 0 32px rgba(0,0,0,0.2);"
                 class="md:hidden overflow-y-auto">

                {{-- Mobile Header --}}
                <div style="position: sticky; top: 0; background: rgba(255,255,255,0.98); backdrop-filter: blur(20px); z-index: 10; padding: 20px 24px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: space-between;">
                    <span class="font-black text-gray-900 uppercase tracking-tight text-sm">Menu</span>
                    <button @click="mobileMenuOpen = false" class="p-2 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Mobile Links --}}
                <div style="padding: 20px 24px;">
                    @foreach($navItems as $item)
                        <a href="{{ route($item['route']) }}" 
                           @click="mobileMenuOpen = false"
                           class="flex items-center space-x-4 p-4 rounded-2xl transition-all {{ request()->routeIs($item['route']) ? 'bg-primary-50 text-primary-500' : 'text-gray-600 hover:bg-gray-50' }}"
                           style="margin-bottom: 8px;">
                            <div class="p-2.5 rounded-xl {{ request()->routeIs($item['route']) ? 'bg-white shadow-sm border border-primary-100' : 'bg-gray-100' }}">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                                </svg>
                            </div>
                            <span class="font-bold uppercase tracking-widest text-sm">{{ __($item['label']) }}</span>
                        </a>
                    @endforeach

                    @auth
                        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #f3f4f6;">
                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center justify-center w-full px-6 py-4 bg-gray-900 text-white rounded-2xl font-black uppercase tracking-widest shadow-lg hover:bg-primary-500 transition-colors">
                                Dashboard
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </template>
</nav>