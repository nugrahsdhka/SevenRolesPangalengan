<x-app-layout>
    <x-slot name="title">
        Penerbitan Data
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            {{-- Kiri: Judul Divisi + Nama User --}}
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight whitespace-nowrap">
                    {{ __('Admin') }}
                </h2>
            </div>

            {{-- Kanan: Dropdown Menu User (Lingkaran Profile) --}}
            <div class="relative" x-data="{ open: false }">
                {{-- Tombol Trigger Dropdown (Lingkaran Profile) --}}
                <button @click="open = !open" @click.away="open = false"
                    class="flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white font-semibold text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 flex-shrink-0"
                    type="button" aria-expanded="false" aria-haspopup="true" title="{{ auth()->user()->name }}">
                    {{-- Inisial Nama User (Huruf pertama dari setiap kata) --}}
                    @php
                        $words = explode(' ', trim(auth()->user()->name));
                        $initials = '';
                        foreach ($words as $word) {
                            if (!empty($word)) {
                                $initials .= strtoupper(substr($word, 0, 1));
                            }
                        }
                        // Ambil huruf pertama dan terakhir jika lebih dari 2 kata
                        if (strlen($initials) > 2) {
                            $initials = substr($initials, 0, 1) . substr($initials, -1);
                        }
                    @endphp
                    {{ $initials }}
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    @keydown.escape.window="open = false"
                    class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                    style="display: none;">
                    <div class="py-1">
                        {{-- Info User Header --}}
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>

                        {{-- Menu Profil --}}
                        <a href="{{ route('profile.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 flex-shrink-0"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0012 11z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">Profil Saya</span>
                        </a>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 my-1"></div>

                        {{-- User --}}
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"
                                fill="currentColor" aria-label="User">
                                <path
                                    d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-5.33 0-9 2.67-9 6a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1c0-3.33-3.67-6-9-6Z" />
                            </svg>
                            <span class="font-medium">Kelola User</span>
                        </a>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 my-1"></div>

                        {{-- Kelola Faskes --}}
                        <a href="{{ route('admin.faskes.index') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2Zm1 15h-2v-2h2Zm0-4h-2V7h2Z" />
                            </svg>
                            <span class="font-medium">Kelola Faskes</span>
                        </a>


                        {{-- Divider --}}
                        <div class="border-t border-gray-100 my-1"></div>

                        {{-- Home --}}
                        <a href="{{ route('welcome') }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                            </svg>
                            <span class="font-medium">Home</span>
                        </a>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 my-1"></div>

                        {{-- Menu Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="font-medium">Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-2 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50" x-data="{ openModal: false, modalData: null, modalTitle: '' }">
        <div class="max-w-7xl mx-auto px-1 sm:px-6 lg:px-1">
            {{-- Alert Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition
                    class="mb-2 flex items-start sm:items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-green-900 truncate">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800"><svg
                            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                    class="mb-2 flex items-start sm:items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-red-900 truncate">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-red-600 hover:text-red-800"><svg
                            class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg></button>
                </div>
            @endif

            {{-- Main Card --}}
            {{-- ✅ MODIFIKASI: Gunakan $activeTab dari controller --}}
            <section x-data="{ activeTab: '{{ $activeTab }}' }"
                class="rounded-2xl bg-white shadow-lg ring-1 ring-gray-900/5 overflow-visible sm:overflow-hidden">
                {{-- Navigasi Tab --}}
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

                                {{-- Data Gizi (aktif) --}}
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
                            {{-- Data Gizi (aktif) --}}
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

                            {{-- Data Pencegahan (read only) --}}
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

                            {{-- Data Penanggulangan (read only) --}}
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

                            {{-- Data Desa (read only) --}}
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

                {{-- Konten Tab --}}
                <div class="p-4 sm:p-6 lg:p-2">
                    {{-- Konten Gizi --}}
                    <div x-show="activeTab === 'gizi'">
                        {{-- TOMBOL AKSI MASSAL GIZI (DARI KODE ASLI ADMIN) --}}
                        <div class="mb-4 flex flex-col sm:flex-row gap-2 justify-end">
                            @if ($bulkActionData['gizi']['has_approved'])
                                <form x-ref="bulkPublishGizi" action="{{ route('admin.bulk_publish') }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="model_type" value="gizi">
                                    <input type="hidden" name="action" value="publish_all">
                                    <input type="hidden" name="active_tab" x-bind:value="activeTab">

                                    <button type="button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-xs font-semibold text-green-700 transition hover:bg-green-200"
                                        @click="
                                            window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                form: $refs.bulkPublishGizi,
                                                title: 'Publish Semua Approved',
                                                message: 'Semua data berstatus Approved pada tab ini akan dipublikasikan.\nLanjutkan?',
                                                okText: 'Ya, Publish',
                                                cancelText: 'Batal'
                                            }
                                            }))
                                        ">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Publish Semua Approved
                                    </button>
                                </form>
                            @endif

                            @if ($bulkActionData['gizi']['has_published'])
                                <form x-ref="bulkUnpublishGizi" action="{{ route('admin.bulk_publish') }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="model_type" value="gizi">
                                    <input type="hidden" name="action" value="unpublish_all">
                                    <input type="hidden" name="active_tab" x-bind:value="activeTab">

                                    <button type="button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-100 px-3 py-2 text-xs font-semibold text-yellow-700 transition hover:bg-yellow-200"
                                        @click="
                                            window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                form: $refs.bulkUnpublishGizi,
                                                title: 'Tarik Semua Published',
                                                message: 'Semua data berstatus Published pada tab ini akan ditarik (unpublish).\nLanjutkan?',
                                                okText: 'Ya, Tarik',
                                                cancelText: 'Batal'
                                            }
                                            }))
                                        ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Tarik Semua Published
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- DAFTAR DATA GIZI (FORMAT DARI CAMAT) --}}
                        @forelse ($dataToReview['gizi'] as $data)
                            @php
                                // Hitung rata-rata ZS TB/U
                                $avgZsTbU = $data->total_anak > 0 ? $data->sum_zs_tbu / $data->total_anak : null;

                                // Tentukan status desa berdasarkan rata-rata ZS TB/U
                                if (is_null($avgZsTbU) || $data->total_anak == 0) {
                                    $statusDesa = ['label' => 'N/A', 'color' => 'gray'];
                                } elseif ($avgZsTbU < -3) {
                                    $statusDesa = ['label' => 'Sangat Pendek', 'color' => 'red'];
                                } elseif ($avgZsTbU < -2) {
                                    $statusDesa = ['label' => 'Pendek', 'color' => 'orange'];
                                } elseif ($avgZsTbU <= 2) {
                                    $statusDesa = ['label' => 'Normal', 'color' => 'green'];
                                } else {
                                    $statusDesa = ['label' => 'Tinggi', 'color' => 'blue'];
                                }
                            @endphp

                            <div
                                class="py-4 border-b border-black/10 last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
                                {{-- Info Data Gizi Agregat --}}
                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-800">
                                        {{ strtoupper($data->desa_kelurahan) }}
                                    </p>
                                    <div class="text-xs text-gray-500 mt-1.5 space-y-0.5">
                                        <p>
                                            Diinput oleh: <span
                                                class="font-medium">{{ $data->user->name ?? 'N/A' }}</span>
                                        </p>
                                        <div class="flex flex-wrap gap-x-3 gap-y-1">
                                            <span>Total Anak: <span
                                                    class="font-medium text-gray-900">{{ $data->total_anak }}</span></span>
                                            <span>Stunting: <span
                                                    class="font-medium {{ $data->total_kasus_stunting > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $data->total_kasus_stunting }}</span></span>
                                            <span>Rata² ZS TB/U: <span
                                                    class="font-medium {{ $avgZsTbU < -2 ? 'text-red-600' : 'text-green-600' }}">{{ !is_null($avgZsTbU) ? number_format($avgZsTbU, 2) : '-' }}</span></span>
                                        </div>
                                        <p>
                                            Status Desa:
                                            <span
                                                class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium
                                                @if ($statusDesa['color'] === 'red') bg-red-100 text-red-700
                                                @elseif($statusDesa['color'] === 'orange') bg-orange-100 text-orange-700
                                                @elseif($statusDesa['color'] === 'green') bg-green-100 text-green-700
                                                @elseif($statusDesa['color'] === 'blue') bg-blue-100 text-blue-700
                                                @else bg-gray-100 text-gray-700 @endif">
                                                {{ $statusDesa['label'] }}
                                            </span>
                                        </p>
                                        <p class="text-gray-400">
                                            Diunggah:
                                            {{ \Carbon\Carbon::parse($data->tanggal_input_terbaru)->isoFormat('D MMM YYYY, HH:mm') }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Status & Action Buttons (VERSI ADMIN) --}}
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    {{-- Tombol Detail (ADMIN) --}}
                                    <a
                                        href="{{ route('admin.published.gizi.detail', ['desa_kelurahan' => $data->desa_kelurahan, 'from' => 'admin']) }}">
                                        <span
                                            class="text-xs text-blue-700 hover:text-blue-900 hover:underline">Detail</span>
                                    </a>

                                    {{-- Badge Status --}}
                                    @if ($data->status === 'published')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700 whitespace-nowrap">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published
                                        </span>
                                    @elseif($data->status === 'approved')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700 whitespace-nowrap">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved
                                        </span>
                                    @endif

                                    {{-- Tombol Aksi Gizi (ADMIN) --}}
                                    <form action="{{ route('admin.publish') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="model" value="DataGizi">
                                        {{-- Mengirim desa_kelurahan, bukan id --}}
                                        <input type="hidden" name="desa_kelurahan"
                                            value="{{ $data->desa_kelurahan }}">
                                        {{-- Mengirim status saat ini untuk logika toggle di controller --}}
                                        <input type="hidden" name="current_status" value="{{ $data->status }}">
                                        <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                        <button type="submit"
                                            class="px-3 py-1 text-xs font-medium text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} whitespace-nowrap">
                                            {{ $data->status === 'published' ? 'Tarik' : 'Publish' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4 text-center">Tidak ada data Gizi untuk diproses.</p>
                        @endforelse

                        {{-- Paginasi Kustom Gizi --}}
                        <div class="mt-6">
                            <x-custom-pagination :data="$dataToReview['gizi']->appends(['active_tab' => 'gizi'])" />
                        </div>
                    </div>

                    {{-- Konten Pencegahan --}}
                    <div x-show="activeTab === 'pencegahan'" style="display: none;">
                        {{-- TOMBOL AKSI MASSAL PENCEGAHAN --}}
                        <div class="mb-4 flex flex-col sm:flex-row gap-2 justify-end">
                            {{-- ✅ MODIFIKASI: Gunakan $bulkActionData --}}
                            @if ($bulkActionData['pencegahan']['has_approved'])
                                <form x-ref="bulkPublishPencegahan" action="{{ route('admin.bulk_publish') }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="model_type" value="gizi">
                                    <input type="hidden" name="action" value="publish_all">
                                    <input type="hidden" name="active_tab" x-bind:value="activeTab">

                                    <button type="button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-xs font-semibold text-green-700 transition hover:bg-green-200"
                                        @click="
                                            window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                form: $refs.bulkPublishGizi,
                                                title: 'Publish Semua Approved',
                                                message: 'Semua data berstatus Approved pada tab ini akan dipublikasikan.\nLanjutkan?',
                                                okText: 'Ya, Publish',
                                                cancelText: 'Batal'
                                            }
                                            }))
                                        ">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Publish Semua Approved
                                    </button>
                                </form>
                            @endif

                            @if ($bulkActionData['pencegahan']['has_published'])
                                <form x-ref="bulkUnpublishPencegahan" action="{{ route('admin.bulk_publish') }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="model_type" value="gizi">
                                    <input type="hidden" name="action" value="unpublish_all">
                                    <input type="hidden" name="active_tab" x-bind:value="activeTab">

                                    <button type="button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-100 px-3 py-2 text-xs font-semibold text-yellow-700 transition hover:bg-yellow-200"
                                        @click="
                                            window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                form: $refs.bulkUnpublishGizi,
                                                title: 'Tarik Semua Published',
                                                message: 'Semua data berstatus Published pada tab ini akan ditarik (unpublish).\nLanjutkan?',
                                                okText: 'Ya, Tarik',
                                                cancelText: 'Batal'
                                            }
                                            }))
                                        ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Tarik Semua Published
                                    </button>
                                </form>
                            @endif
                        </div>

                        @forelse ($dataToReview['pencegahan'] as $data)
                            <div
                                class="py-4 border-b border-black/10 last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $data->judul_kegiatan }}</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> |
                                        Periode: {{ $data->periode->format('F Y') }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <button type="button"
                                        @click="openModal = true; modalData = {{ Js::from($data) }}; modalTitle = 'Detail Data Pencegahan'"
                                        class="text-xs text-indigo-600 hover:underline px-2">Detail</button>
                                    @if ($data->status === 'published')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published
                                        </span>
                                    @elseif($data->status === 'approved')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved
                                        </span>
                                    @endif
                                    @if ($data->status !== 'pending')
                                        <form action="{{ route('admin.publish') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="model" value="DataPencegahan">
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                            <button type="submit"
                                                class="px-3 py-1 text-xs font-medium text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                {{ $data->status === 'published' ? 'Tarik' : 'Publish' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4 text-center">Tidak ada data Pencegahan untuk diproses.
                            </p>
                        @endforelse

                        {{-- ✅ IMPLEMENTASI: Paginasi Kustom Pencegahan --}}
                        <div class="mt-6">
                            <x-custom-pagination :data="$dataToReview['pencegahan']->appends(['active_tab' => 'pencegahan'])" />
                        </div>
                    </div>

                    {{-- Konten Penanggulangan --}}
                    <div x-show="activeTab === 'penanggulangan'" style="display: none;">
                        {{-- TOMBOL AKSI MASSAL PENANGGULANGAN --}}
                        <div class="mb-4 flex flex-col sm:flex-row gap-2 justify-end">
                            {{-- ✅ MODIFIKASI: Gunakan $bulkActionData --}}
                            @if ($bulkActionData['penanggulangan']['has_approved'])
                                <form x-ref="bulkPublishPenanggulangan" action="{{ route('admin.bulk_publish') }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="model_type" value="gizi">
                                    <input type="hidden" name="action" value="publish_all">
                                    <input type="hidden" name="active_tab" x-bind:value="activeTab">

                                    <button type="button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-xs font-semibold text-green-700 transition hover:bg-green-200"
                                        @click="
                                            window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                form: $refs.bulkPublishGizi,
                                                title: 'Publish Semua Approved',
                                                message: 'Semua data berstatus Approved pada tab ini akan dipublikasikan.\nLanjutkan?',
                                                okText: 'Ya, Publish',
                                                cancelText: 'Batal'
                                            }
                                            }))
                                        ">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Publish Semua Approved
                                    </button>
                                </form>
                            @endif

                            @if ($bulkActionData['penanggulangan']['has_published'])
                                <form x-ref="bulkUnpublishPenanggulangan" action="{{ route('admin.bulk_publish') }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="model_type" value="gizi">
                                    <input type="hidden" name="action" value="unpublish_all">
                                    <input type="hidden" name="active_tab" x-bind:value="activeTab">

                                    <button type="button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-100 px-3 py-2 text-xs font-semibold text-yellow-700 transition hover:bg-yellow-200"
                                        @click="
                                            window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                form: $refs.bulkUnpublishGizi,
                                                title: 'Tarik Semua Published',
                                                message: 'Semua data berstatus Published pada tab ini akan ditarik (unpublish).\nLanjutkan?',
                                                okText: 'Ya, Tarik',
                                                cancelText: 'Batal'
                                            }
                                            }))
                                        ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Tarik Semua Published
                                    </button>
                                </form>
                            @endif
                        </div>

                        @forelse ($dataToReview['penanggulangan'] as $data)
                            <div
                                class="py-4 border-b border-black/10 last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $data->jenis_bencana }}
                                        ({{ $data->jumlah_korban }} korban)</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> |
                                        Periode: {{ $data->periode->format('F Y') }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <button type="button"
                                        @click="openModal = true; modalData = {{ Js::from($data) }}; modalTitle = 'Detail Data Penanggulangan'"
                                        class="text-xs text-indigo-600 hover:underline px-2">Detail</button>
                                    @if ($data->status === 'published')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published
                                        </span>
                                    @elseif($data->status === 'approved')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved
                                        </span>
                                    @endif
                                    @if ($data->status !== 'pending')
                                        <form action="{{ route('admin.publish') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="model" value="DataPenanggulangan">
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                            <button type="submit"
                                                class="px-3 py-1 text-xs font-medium text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                {{ $data->status === 'published' ? 'Tarik' : 'Publish' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4 text-center">Tidak ada data Penanggulangan untuk
                                diproses.</p>
                        @endforelse

                        {{-- ✅ IMPLEMENTASI: Paginasi Kustom Penanggulangan --}}
                        <div class="mt-6">
                            <x-custom-pagination :data="$dataToReview['penanggulangan']->appends(['active_tab' => 'penanggulangan'])" />
                        </div>
                    </div>

                    {{-- Konten Desa --}}
                    <div x-show="activeTab === 'desa'" style="display: none;">
                        {{-- TOMBOL AKSI MASSAL DESA --}}
                        <div class="mb-4 flex flex-col sm:flex-row gap-2 justify-end">
                            @if ($bulkActionData['desa']['has_approved'])
                                <form x-ref="bulkPublishDesa" action="{{ route('admin.bulk_publish') }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="model_type" value="gizi">
                                    <input type="hidden" name="action" value="publish_all">
                                    <input type="hidden" name="active_tab" x-bind:value="activeTab">

                                    <button type="button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-green-100 px-3 py-2 text-xs font-semibold text-green-700 transition hover:bg-green-200"
                                        @click="
                                            window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                form: $refs.bulkPublishGizi,
                                                title: 'Publish Semua Approved',
                                                message: 'Semua data berstatus Approved pada tab ini akan dipublikasikan.\nLanjutkan?',
                                                okText: 'Ya, Publish',
                                                cancelText: 'Batal'
                                            }
                                            }))
                                        ">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Publish Semua Approved
                                    </button>
                                </form>
                            @endif

                            @if ($bulkActionData['desa']['has_published'])
                                <form x-ref="bulkUnpublishDesa" action="{{ route('admin.bulk_publish') }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="model_type" value="gizi">
                                    <input type="hidden" name="action" value="unpublish_all">
                                    <input type="hidden" name="active_tab" x-bind:value="activeTab">

                                    <button type="button"
                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-100 px-3 py-2 text-xs font-semibold text-yellow-700 transition hover:bg-yellow-200"
                                        @click="
                                            window.dispatchEvent(new CustomEvent('open-confirm', {
                                            detail: {
                                                form: $refs.bulkUnpublishGizi,
                                                title: 'Tarik Semua Published',
                                                message: 'Semua data berstatus Published pada tab ini akan ditarik (unpublish).\nLanjutkan?',
                                                okText: 'Ya, Tarik',
                                                cancelText: 'Batal'
                                            }
                                            }))
                                        ">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Tarik Semua Published
                                    </button>
                                </form>
                            @endif
                        </div>

                        @forelse ($dataToReview['desa'] as $data)
                            <div
                                class="py-4 border-b border-black/10 last:border-0 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 truncate">{{ $data->nama_desa }}</p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        Penduduk: {{ number_format($data->jumlah_penduduk) }} |
                                        Stunting: <span
                                            class="font-bold text-red-600">{{ $data->jumlah_kasus_stunting }}</span>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1 truncate">
                                        Diinput oleh: <span class="font-medium">{{ $data->user->name }}</span> |
                                        Periode: {{ $data->periode->format('F Y') }}
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:justify-end">
                                    <button type="button"
                                        @click="openModal = true; modalData = {{ Js::from($data) }}; modalTitle = 'Detail Data Desa'"
                                        class="text-xs text-indigo-600 hover:underline px-2">Detail</button>
                                    @if ($data->status === 'published')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>Published
                                        </span>
                                    @elseif($data->status === 'approved')
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>Approved
                                        </span>
                                    @endif
                                    @if ($data->status !== 'pending')
                                        <form action="{{ route('admin.publish') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="model" value="DataDesa">
                                            <input type="hidden" name="id" value="{{ $data->id }}">
                                            <input type="hidden" name="active_tab" x-bind:value="activeTab">
                                            <button type="submit"
                                                class="px-3 py-1 text-xs font-medium text-white rounded-lg {{ $data->status === 'published' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                {{ $data->status === 'published' ? 'Tarik' : 'Publish' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 pt-4 text-center">Tidak ada data Desa untuk diproses.</p>
                        @endforelse

                        {{-- ✅ IMPLEMENTASI: Paginasi Kustom Desa --}}
                        <div class="mt-6">
                            <x-custom-pagination :data="$dataToReview['desa']->appends(['active_tab' => 'desa'])" />
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    {{-- Utilitas kecil (opsional) --}}
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .preserve-lines {
            white-space: pre-line;
        }
    </style>
</x-app-layout>
