<nav class="sticky top-0 z-50 bg-white shadow-lg border-b border-gray-100">
    <div class="hidden md:block max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center h-16">
            <div class="flex items-center space-x-1">

                <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>{{ __('Home') }}</span>
                    </div>
                </x-nav-link>

                <x-nav-link :href="route('data-stunting')" :active="request()->routeIs('data-stunting')">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>{{ __('Data Stunting') }}</span>
                    </div>
                </x-nav-link>

                <x-nav-link :href="route('data-wilayah')" :active="request()->routeIs('data-wilayah')">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ __('Data Wilayah') }}</span>
                    </div>
                </x-nav-link>

                <x-nav-link :href="route('analisis-hotspot')" :active="request()->routeIs('analisis-hotspot')">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span>{{ __('Analisis Hotspot') }}</span>
                    </div>
                </x-nav-link>

                <x-nav-link :href="route('peta-faskes')" :active="request()->routeIs('peta-faskes')">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ __('Peta Faskes') }}</span>
                    </div>
                </x-nav-link>
                
                @auth                 
                    <div class="h-8 w-px bg-gray-200 mx-4"></div>   
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        <div class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-red-50 transition-all duration-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            <span>{{ __('Dashboard') }}</span>
                        </div>
                    </x-nav-link>
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="md:hidden">
        <x-dropdown>
            <x-slot:trigger>
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-lg bg-red-50 flex items-center justify-center">
                            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="text-base font-bold text-gray-800">Menu</span>
                    </div>
                    
                    <button class="p-2 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition-all duration-300">
                        <svg class="h-6 w-6 text-gray-700" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M3 12H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                          <path d="M3 6H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                          <path d="M3 18H21" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </x-slot:trigger>

            <x-slot:content>
                <div class="py-1 bg-white">
                    <x-dropdown-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                        <div class="flex items-center space-x-3 py-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-medium">{{ __('Home') }}</span>
                        </div>
                    </x-dropdown-link>
                    
                    <x-dropdown-link :href="route('data-stunting')" :active="request()->routeIs('data-stunting')">
                        <div class="flex items-center space-x-3 py-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span class="font-medium">{{ __('Data Stunting') }}</span>
                        </div>
                    </x-dropdown-link>
                    
                    <x-dropdown-link :href="route('data-wilayah')" :active="request()->routeIs('data-wilayah')">
                        <div class="flex items-center space-x-3 py-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">{{ __('Data Wilayah') }}</span>
                        </div>
                    </x-dropdown-link>
                    
                    <x-dropdown-link :href="route('analisis-hotspot')" :active="request()->routeIs('analisis-hotspot')">
                        <div class="flex items-center space-x-3 py-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span class="font-medium">{{ __('Analisis Hotspot') }}</span>
                        </div>
                    </x-dropdown-link>
                    
                    <x-dropdown-link :href="route('peta-faskes')" :active="request()->routeIs('peta-faskes')">
                        <div class="flex items-center space-x-3 py-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="font-medium">{{ __('Peta Faskes') }}</span>
                        </div>
                    </x-dropdown-link>

                    @auth                
                        <div class="my-2 border-t border-gray-200"></div>

                        <x-dropdown-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <div class="flex items-center space-x-3 py-2 text-red-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                </svg>
                                <span class="font-semibold">{{ __('Back To Dashboard') }}</span>
                            </div>
                        </x-dropdown-link>
                    @endauth
                </div>
            </x-slot:content>
        </x-dropdown>
    </div>
</nav>