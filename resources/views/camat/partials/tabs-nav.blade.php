{{-- Navigasi Tab (Header + Tabs) --}}
<div class="border-b border-gray-200">
    {{-- Header Title --}}
    <div class="flex flex-col justify-center items-center py-2 border-b">
        <h1 class="text-md font-bold text-red-700">Penerbitan Data</h1>
        <p class="text-sm font-bold text-red-700">
            <span x-show="activeTab === 'gizi'">Divisi Gizi</span>
            <span x-show="activeTab === 'pencegahan'">Divisi Pencegahan</span>
            <span x-show="activeTab === 'penanggulangan'">Divisi Penanggulangan</span>
            <span x-show="activeTab === 'desa'">Divisi Desa</span>
        </p>
    </div>

    {{-- Mobile View: Dropdown --}}
    <div class="sm:hidden" x-data="{ open: false }">
        <div class="relative">
            {{-- Dropdown Button --}}
            <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 text-left bg-white border-b-2 border-red-600 hover:bg-gray-50">
                <div class="flex items-center gap-2">
                    {{-- Icon berdasarkan tab aktif --}}
                    <svg x-show="activeTab === 'gizi'" class="h-5 w-5 text-red-600 flex-shrink-0"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <svg x-show="activeTab === 'pencegahan'"
                        class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <svg x-show="activeTab === 'penanggulangan'"
                        class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <svg x-show="activeTab === 'desa'" class="h-5 w-5 text-red-600 flex-shrink-0"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" />
                    </svg>

                    {{-- Label tab aktif --}}
                    <span class="font-medium text-sm text-red-600">
                        <span x-show="activeTab === 'gizi'">Data Gizi</span>
                        <span x-show="activeTab === 'pencegahan'">Data Pencegahan</span>
                        <span x-show="activeTab === 'penanggulangan'">Data Penanggulangan</span>
                        <span x-show="activeTab === 'desa'">Data Desa</span>
                    </span>
                </div>

                {{-- Dropdown Arrow --}}
                <svg class="h-5 w-5 text-gray-400 transition-transform" :class="{ 'rotate-180': open }"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="open" @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute z-10 mt-1 w-full bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden"
                style="display: none;">

                {{-- Data Gizi --}}
                <button @click="activeTab = 'gizi'; open = false"
                    :class="{ 'bg-red-50 text-red-700': activeTab === 'gizi', 'text-gray-700 hover:bg-gray-50': activeTab !== 'gizi' }"
                    class="w-full flex items-center gap-3 px-4 py-3 text-left border-b border-gray-100 transition">
                    <svg class="h-5 w-5 flex-shrink-0"
                        :class="{ 'text-red-600': activeTab === 'gizi', 'text-gray-400': activeTab !== 'gizi' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="font-medium text-sm">Data Gizi</span>
                    <svg x-show="activeTab === 'gizi'" class="h-5 w-5 ml-auto text-red-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </button>

                {{-- Data Pencegahan (read only) --}}
                <button @click="activeTab = 'pencegahan'; open = false" disabled
                    :class="{ 'bg-red-50 text-red-700': activeTab === 'pencegahan', 'text-gray-700': activeTab !== 'pencegahan' }"
                    class="w-full flex items-center gap-3 px-4 py-3 text-left border-b border-gray-100 transition cursor-not-allowed opacity-60">
                    <svg class="h-5 w-5 flex-shrink-0"
                        :class="{ 'text-red-600': activeTab === 'pencegahan', 'text-gray-400': activeTab !== 'pencegahan' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="font-medium text-sm">Data Pencegahan</span>
                    <svg x-show="activeTab === 'pencegahan'" class="h-5 w-5 ml-auto text-red-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </button>

                {{-- Data Penanggulangan (read only) --}}
                <button @click="activeTab = 'penanggulangan'; open = false" disabled
                    :class="{ 'bg-red-50 text-red-700': activeTab === 'penanggulangan', 'text-gray-700': activeTab !== 'penanggulangan' }"
                    class="w-full flex items-center gap-3 px-4 py-3 text-left border-b border-gray-100 transition cursor-not-allowed opacity-60">
                    <svg class="h-5 w-5 flex-shrink-0"
                        :class="{ 'text-red-600': activeTab === 'penanggulangan', 'text-gray-400': activeTab !== 'penanggulangan' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <span class="font-medium text-sm">Data Penanggulangan</span>
                    <svg x-show="activeTab === 'penanggulangan'" class="h-5 w-5 ml-auto text-red-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </button>

                {{-- Data Desa (read only) --}}
                <button @click="activeTab = 'desa'; open = false" disabled
                    :class="{ 'bg-red-50 text-red-700': activeTab === 'desa', 'text-gray-700': activeTab !== 'desa' }"
                    class="w-full flex items-center gap-3 px-4 py-3 text-left transition cursor-not-allowed opacity-60">
                    <svg class="h-5 w-5 flex-shrink-0"
                        :class="{ 'text-red-600': activeTab === 'desa', 'text-gray-400': activeTab !== 'desa' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" />
                    </svg>
                    <span class="font-medium text-sm">Data Desa</span>
                    <svg x-show="activeTab === 'desa'" class="h-5 w-5 ml-auto text-red-600"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Desktop View: Tab Navigation --}}
    <nav class="hidden sm:flex -mb-px" aria-label="Tabs">
        <div class="flex w-full">
            {{-- Data Gizi --}}
            <button @click="activeTab = 'gizi'"
                :class="{ 'border-red-600 text-red-600': activeTab === 'gizi', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gizi' }"
                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>Data Gizi</span>
            </button>

            {{-- Data Pencegahan --}}
            <button @click="activeTab = 'pencegahan'" disabled
                :class="{ 'border-red-600 text-red-600': activeTab === 'pencegahan', 'border-transparent text-gray-500': activeTab !== 'pencegahan' }"
                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2 cursor-not-allowed opacity-60">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>Data Pencegahan</span>
            </button>

            {{-- Data Penanggulangan --}}
            <button @click="activeTab = 'penanggulangan'" disabled
                :class="{ 'border-red-600 text-red-600': activeTab === 'penanggulangan', 'border-transparent text-gray-500': activeTab !== 'penanggulangan' }"
                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2 cursor-not-allowed opacity-60">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                <span>Data Penanggulangan</span>
            </button>

            {{-- Data Desa --}}
            <button @click="activeTab = 'desa'" disabled
                :class="{ 'border-red-600 text-red-600': activeTab === 'desa', 'border-transparent text-gray-500': activeTab !== 'desa' }"
                class="w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm focus:outline-none flex justify-center items-center gap-2 cursor-not-allowed opacity-60">
                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" />
                </svg>
                <span>Data Desa</span>
            </button>
        </div>
    </nav>
</div>
