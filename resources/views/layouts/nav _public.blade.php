    <nav class="sticky top-0 z-50 bg-white/80 backdrop-blur-xl shadow-lg border-b border-gray-200/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
    </nav>