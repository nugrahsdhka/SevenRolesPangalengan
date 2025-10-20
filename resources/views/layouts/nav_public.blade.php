<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl shadow-lg border-b border-gray-200/50">
    <div class="hidden md:block max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-center h-16">
            <div class="flex space-x-1">

                <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                    {{ __('Home') }}
                </x-nav-link>

                <x-nav-link :href="route('data-stunting')" :active="request()->routeIs('data-stunting')">
                    {{ __('Data Stunting') }}
                </x-nav-link>

                <x-nav-link :href="route('data-wilayah')" :active="request()->routeIs('data-wilayah')">
                    {{ __('Data Wilayah') }}
                </x-nav-link>

                <x-nav-link :href="route('analisis-hotspot')" :active="request()->routeIs('analisis-hotspot')">
                    {{ __('Analisis Hotspot') }}
                </x-nav-link>

                <x-nav-link :href="route('peta-faskes')" :active="request()->routeIs('peta-faskes')">
                    {{ __('Peta Faskes') }}
                </x-nav-link>

            </div>
        </div>
    </div>

    <x-dropdown>
        <x-slot:trigger>
            <div class="flex items-end justify-end p-2">
                <button>
                    <div class="ms-1 text-red-600">
                        <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M3 12H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                          <path d="M3 6H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                          <path d="M3 18H21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                </button>
            </div>
        </x-slot:trigger>

        <x-slot:content>

            <x-dropdown-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                {{ __('Home') }}
            </x-dropdown-link>
            
            <x-dropdown-link :href="route('data-stunting')" :active="request()->routeIs('data-stunting')">
                {{ __('Data Stunting') }}
            </x-dropdown-link>
            
            <x-dropdown-link :href="route('data-wilayah')" :active="request()->routeIs('data-wilayah')">
                {{ __('Data Wilayah') }}
            </x-dropdown-link>
            
            <x-dropdown-link :href="route('analisis-hotspot')" :active="request()->routeIs('analisis-hotspot')">
                {{ __('Analisis Hotspot') }}
            </x-dropdown-link>
            
            <x-dropdown-link :href="route('peta-faskes')" :active="request()->routeIs('peta-faskes')">
                {{ __('Peta Faskes') }}
            </x-dropdown-link>
                
        </x-slot:content>
    </x-dropdown>
</nav>