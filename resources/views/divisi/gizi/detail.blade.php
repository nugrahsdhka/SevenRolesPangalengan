<x-app-layout>
    <x-slot name="title">
        Detail Data Gizi - {{ strtoupper($desaKelurahan) }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            {{-- Kiri: Judul --}}
            <div class="flex items-center gap-3 min-w-0 flex-1">
                <a href="{{ route('gizi.index') }}" class="flex-shrink-0 p-2 rounded-lg hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="min-w-0">
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                        {{ strtoupper($desaKelurahan) }}
                    </h2>
                    <p class="text-sm text-gray-500">Detail Data Gizi per Anak</p>
                </div>
            </div>

            {{-- Kanan: Dropdown Menu User --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" 
                        class="flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white font-semibold text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 flex-shrink-0"
                        type="button" title="{{ auth()->user()->name }}">
                    @php
                        $words = explode(' ', trim(auth()->user()->name));
                        $initials = '';
                        foreach ($words as $word) { if (!empty($word)) { $initials .= strtoupper(substr($word, 0, 1)); } }
                        if (strlen($initials) > 2) { $initials = substr($initials, 0, 1) . substr($initials, -1); }
                    @endphp
                    {{ $initials }}
                </button>

                <div x-show="open" x-transition
                     class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden" style="display: none;">
                    <div class="py-1">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <a href="{{ route('profile.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0012 11z" clip-rule="evenodd" /></svg>
                            <span class="font-medium">Profil Saya</span>
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                <span class="font-medium">Log Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="px-2 py-8 bg-gradient-to-br from-slate-50 via-rose-50/30 to-slate-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Alert Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                     class="mb-6 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <svg class="h-5 w-5 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                    <button @click="show = false" class="ml-auto text-green-600 hover:text-green-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                     class="mb-6 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <svg class="h-5 w-5 text-red-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                    <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            @endif

            {{-- Card Statistik Desa --}}
            {{-- Card Statistik Desa - Desktop: Grid, Mobile: Dropdown --}}
            <div x-data="{ 
                openStats: false,
                selectedStat: 'total',
                stats: {
                    total: {
                        label: 'Total Anak',
                        value: '{{ $statistik->total_anak ?? 0 }}',
                        color: 'blue',
                        icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'
                    },
                    stunting: {
                        label: 'Kasus Stunting',
                        value: '{{ $statistik->total_stunting ?? 0 }}',
                        subtitle: '{{ $statistik->total_anak > 0 ? number_format(($statistik->total_stunting / $statistik->total_anak) * 100, 1) : 0 }}%',
                        color: 'red',
                        icon: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                    },
                    tinggi: {
                        label: 'Rata-rata Tinggi',
                        value: '{{ $statistik->avg_tinggi ? number_format($statistik->avg_tinggi, 1) : 0 }} cm',
                        color: 'green',
                        icon: 'M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z'
                    },
                    zs: {
                        label: 'Rata-rata ZS TB/U',
                        value: '{{ $statistik->avg_zs_tbu ? number_format($statistik->avg_zs_tbu, 2) : "0.00" }}',
                        color: '{{ $statistik->avg_zs_tbu < -2 ? "red" : "green" }}',
                        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
                    }
                }
            }" class="mb-6">
                
                {{-- Mobile View: Dropdown --}}
                <div class="lg:hidden">
                    <div class="relative">
                        {{-- Selected Stat Display --}}
                        <button @click="openStats = !openStats" 
                                class="w-full bg-white rounded-xl p-5 shadow-sm ring-1 ring-gray-900/5 flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1">
                                {{-- Icon --}}
                                <div class="p-3 rounded-lg"
                                    :class="{
                                        'bg-blue-100': selectedStat === 'total',
                                        'bg-red-100': selectedStat === 'stunting',
                                        'bg-green-100': selectedStat === 'tinggi',
                                        'bg-red-100': selectedStat === 'zs' && {{ $statistik->avg_zs_tbu < -2 ? 'true' : 'false' }},
                                        'bg-green-100': selectedStat === 'zs' && {{ $statistik->avg_zs_tbu >= -2 ? 'true' : 'false' }}
                                    }">
                                    <svg class="w-6 h-6" 
                                        :class="{
                                            'text-blue-600': selectedStat === 'total',
                                            'text-red-600': selectedStat === 'stunting',
                                            'text-green-600': selectedStat === 'tinggi',
                                            'text-red-600': selectedStat === 'zs' && {{ $statistik->avg_zs_tbu < -2 ? 'true' : 'false' }},
                                            'text-green-600': selectedStat === 'zs' && {{ $statistik->avg_zs_tbu >= -2 ? 'true' : 'false' }}
                                        }"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stats[selectedStat].icon"/>
                                    </svg>
                                </div>
                                
                                {{-- Content --}}
                                <div class="text-left flex-1">
                                    <p class="text-sm font-medium text-gray-500" x-text="stats[selectedStat].label"></p>
                                    <p class="text-2xl font-bold mt-1"
                                    :class="{
                                        'text-gray-900': selectedStat === 'total' || selectedStat === 'tinggi',
                                        'text-red-600': selectedStat === 'stunting' || (selectedStat === 'zs' && {{ $statistik->avg_zs_tbu < -2 ? 'true' : 'false' }}),
                                        'text-green-600': selectedStat === 'zs' && {{ $statistik->avg_zs_tbu >= -2 ? 'true' : 'false' }}
                                    }"
                                    x-text="stats[selectedStat].value"></p>
                                    <p class="text-xs text-gray-500 mt-1" x-show="stats[selectedStat].subtitle" x-text="stats[selectedStat].subtitle"></p>
                                </div>
                            </div>
                            
                            {{-- Dropdown Arrow --}}
                            <svg class="w-5 h-5 text-gray-400 transition-transform" 
                                :class="{ 'rotate-180': openStats }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="openStats" 
                            @click.away="openStats = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute z-10 mt-2 w-full bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden"
                            style="display: none;">
                            
                            {{-- Total Anak --}}
                            <button @click="selectedStat = 'total'; openStats = false"
                                    class="w-full px-5 py-4 hover:bg-gray-50 transition flex items-center gap-3 border-b border-gray-100">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-700">Total Anak</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $statistik->total_anak ?? 0 }}</p>
                                </div>
                            </button>

                            {{-- Kasus Stunting --}}
                            <button @click="selectedStat = 'stunting'; openStats = false"
                                    class="w-full px-5 py-4 hover:bg-gray-50 transition flex items-center gap-3 border-b border-gray-100">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-700">Kasus Stunting</p>
                                    <p class="text-lg font-bold text-red-600">{{ $statistik->total_stunting ?? 0 }} <span class="text-xs text-gray-500">({{ $statistik->total_anak > 0 ? number_format(($statistik->total_stunting / $statistik->total_anak) * 100, 1) : 0 }}%)</span></p>
                                </div>
                            </button>

                            {{-- Rata-rata Tinggi --}}
                            <button @click="selectedStat = 'tinggi'; openStats = false"
                                    class="w-full px-5 py-4 hover:bg-gray-50 transition flex items-center gap-3 border-b border-gray-100">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-700">Rata-rata Tinggi</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $statistik->avg_tinggi ? number_format($statistik->avg_tinggi, 1) : 0 }} cm</p>
                                </div>
                            </button>

                            {{-- Rata-rata ZS TB/U --}}
                            <button @click="selectedStat = 'zs'; openStats = false"
                                    class="w-full px-5 py-4 hover:bg-gray-50 transition flex items-center gap-3">
                                <div class="p-2 {{ $statistik->avg_zs_tbu < -2 ? 'bg-red-100' : 'bg-green-100' }} rounded-lg">
                                    <svg class="w-5 h-5 {{ $statistik->avg_zs_tbu < -2 ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-medium text-gray-700">Rata-rata ZS TB/U</p>
                                    <p class="text-lg font-bold {{ $statistik->avg_zs_tbu < -2 ? 'text-red-600' : 'text-green-600' }}">{{ $statistik->avg_zs_tbu ? number_format($statistik->avg_zs_tbu, 2) : '0.00' }}</p>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Desktop View: Grid (Tetap sama seperti sebelumnya) --}}
                <div class="hidden lg:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    {{-- Total Anak --}}
                    <div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-gray-900/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Anak</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statistik->total_anak ?? 0 }}</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Total Stunting --}}
                    <div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-gray-900/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Kasus Stunting</p>
                                <p class="text-2xl font-bold text-red-600 mt-1">{{ $statistik->total_stunting ?? 0 }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $statistik->total_anak > 0 ? number_format(($statistik->total_stunting / $statistik->total_anak) * 100, 1) : 0 }}%
                                </p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-lg">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Rata-rata TB --}}
                    <div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-gray-900/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Rata-rata Tinggi</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $statistik->avg_tinggi ? number_format($statistik->avg_tinggi, 1) : 0 }} cm</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Rata-rata ZS TB/U --}}
                    <div class="bg-white rounded-xl p-5 shadow-sm ring-1 ring-gray-900/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Rata-rata ZS TB/U</p>
                                <p class="text-2xl font-bold {{ $statistik->avg_zs_tbu < -2 ? 'text-red-600' : 'text-green-600' }} mt-1">
                                    {{ $statistik->avg_zs_tbu ? number_format($statistik->avg_zs_tbu, 2) : '0.00' }}
                                </p>
                            </div>
                            <div class="p-3 {{ $statistik->avg_zs_tbu < -2 ? 'bg-red-100' : 'bg-green-100' }} rounded-lg">
                                <svg class="w-6 h-6 {{ $statistik->avg_zs_tbu < -2 ? 'text-red-600' : 'text-green-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Data Individual --}}
            <section class="rounded-2xl bg-white p-6 shadow-lg ring-1 ring-gray-900/5 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Data Individual Anak</h3>
                        <p class="text-sm text-gray-500">Daftar lengkap data gizi per anak di {{ strtoupper($desaKelurahan) }}</p>
                    </div>
                </div>

                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr class="border-b border-gray-200">
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">No</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">Tanggal Input</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Berat (kg)</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Tinggi (cm)</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">BB/U</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">ZS BB/U</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">TB/U</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">ZS TB/U</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">BB/TB</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">ZS BB/TB</th>
                                    <th scope="col" class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($dataGizi as $index => $data)
                                    <tr class="transition hover:bg-gray-50">
                                        {{-- No --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600">
                                            {{ $dataGizi->firstItem() + $index }}
                                        </td>
                                        {{-- Tanggal Input --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($data->created_at)->isoFormat('D MMM YYYY, HH:mm') }}
                                        </td>
                                        {{-- Berat --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-900 text-center font-medium">
                                            {{ number_format($data->berat, 2) }}
                                        </td>
                                        {{-- Tinggi --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-900 text-center font-medium">
                                            {{ number_format($data->tinggi, 2) }}
                                        </td>
                                        {{-- BB/U --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 text-center">
                                            {{ $data->status_bb_u ?? '-' }}
                                        </td>
                                        {{-- ZS BB/U --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-center">
                                            <span class="font-medium {{ $data->zs_bb_u < -2 ? 'text-red-600' : 'text-gray-900' }}">
                                                {{ $data->zs_bb_u ? number_format($data->zs_bb_u, 2) : '-' }}
                                            </span>
                                        </td>
                                        {{-- TB/U --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 text-center">
                                            {{ $data->status_tb_u ?? '-' }}
                                        </td>
                                        {{-- ZS TB/U --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-center">
                                            <span class="font-medium {{ $data->zs_tb_u < -2 ? 'text-red-600' : 'text-gray-900' }}">
                                                {{ $data->zs_tb_u ? number_format($data->zs_tb_u, 2) : '-' }}
                                            </span>
                                        </td>
                                        {{-- BB/TB --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-600 text-center">
                                            {{ $data->status_bb_tb ?? '-' }}
                                        </td>
                                        {{-- ZS BB/TB --}}
                                        <td class="whitespace-nowrap px-4 py-4 text-sm text-center">
                                            <span class="font-medium {{ $data->zs_bb_tb < -2 ? 'text-red-600' : 'text-gray-900' }}">
                                                {{ $data->zs_bb_tb ? number_format($data->zs_bb_tb, 2) : '-' }}
                                            </span>
                                        </td>
                                        {{-- Aksi --}}
                                        <td class="whitespace-nowrap px-4 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('gizi.edit', $data->id) }}" 
                                                   class="text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                                                    Edit
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <form action="{{ route('gizi.destroy', $data->id) }}" method="POST" 
                                                      onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="px-6 py-12 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                            <p class="mt-4 text-sm text-gray-500">Belum ada data untuk desa ini</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pagination --}}
                @php $paginator = $dataGizi; @endphp {{-- Ganti nama variabel untuk kompatibilitas --}}
                @if ($paginator->hasPages())
                    <div class="mt-6 sm:mt-8 px-4 sm:px-0">
                        {{-- Mobile Version: Compact Pagination --}}
                        <div class="flex sm:hidden flex-col gap-3">
                            <div class="flex items-center justify-center">
                                <nav class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                                    {{-- Previous Button --}}
                                    @if ($paginator->onFirstPage())
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></span>
                                    @else
                                        <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a>
                                    @endif

                                    {{-- First Page --}}
                                    @if ($paginator->currentPage() == 1) 
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">1</span>
                                    @else 
                                        <a href="{{ $paginator->url(1) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">1</a> 
                                    @endif

                                    {{-- Left Ellipsis --}}
                                    @if ($paginator->currentPage() > 2) 
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400">...</span> 
                                    @endif

                                    {{-- Current Page (if not first or last) --}}
                                    @if ($paginator->currentPage() != 1 && $paginator->currentPage() != $paginator->lastPage()) 
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">{{ $paginator->currentPage() }}</span> 
                                    @endif

                                    {{-- Right Ellipsis --}}
                                    @if ($paginator->currentPage() < $paginator->lastPage() - 1) 
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-400">...</span> 
                                    @endif

                                    {{-- Last Page --}}
                                    @if ($paginator->lastPage() > 1)
                                        @if ($paginator->currentPage() == $paginator->lastPage()) 
                                            <span class="inline-flex items-center justify-center w-9 h-9 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">{{ $paginator->lastPage() }}</span>
                                        @else 
                                            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">{{ $paginator->lastPage() }}</a> 
                                        @endif
                                    @endif

                                    {{-- Next Button --}}
                                    @if ($paginator->hasMorePages())
                                        <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                                    @else
                                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-md text-gray-300 cursor-not-allowed"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span>
                                    @endif
                                </nav>
                            </div>
                            
                            {{-- Mobile Go To Form --}}
                            <div class="flex items-center justify-center gap-2 mt-3">
                                <span class="text-sm text-gray-500">Go to:</span>
                                <form method="GET" action="{{ $paginator->url(1) }}" class="flex items-center gap-2" onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $paginator->lastPage() }}) window.location.href = this.action.replace('page=1', 'page=' + page);">
                                    <input type="number" name="page" min="1" max="{{ $paginator->lastPage() }}" placeholder="{{ $paginator->currentPage() }}" class="w-16 px-3 py-2 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">Go</button>
                                </form>
                            </div>
                        </div>

                        {{-- Desktop/Tablet Version: Custom Pagination --}}
                        <div class="hidden sm:flex flex-col items-center gap-3">
                            <nav class="inline-flex items-center gap-1 rounded-lg bg-white p-1.5 shadow-sm border border-gray-100">
                                {{-- Previous Button --}}
                                @if ($paginator->onFirstPage()) 
                                    <span class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-300 cursor-not-allowed"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></span>
                                @else 
                                    <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></a> 
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($paginator->links()->elements as $element)
                                    {{-- "Three Dots" Separator --}}
                                    @if (is_string($element)) 
                                        <span class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-400">...</span> 
                                    @endif
                                    {{-- Array Of Links --}}
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $paginator->currentPage()) 
                                                <span class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md bg-red-500 text-white font-medium text-sm shadow-sm">{{ $page }}</span>
                                            @else 
                                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 font-medium text-sm transition-all duration-200">{{ $page }}</a> 
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- Next Button --}}
                                @if ($paginator->hasMorePages()) 
                                    <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-600 hover:bg-red-50 hover:text-red-600 transition-all duration-200"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></a>
                                @else 
                                    <span class="inline-flex items-center justify-center w-9 h-9 md:w-10 md:h-10 rounded-md text-gray-300 cursor-not-allowed"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></span> 
                                @endif
                            </nav>
                            
                            {{-- Desktop Go To Form --}}
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-sm text-gray-500">Go to page:</span>
                                <form method="GET" action="{{ $paginator->url(1) }}" class="flex items-center gap-2" onsubmit="event.preventDefault(); const page = this.querySelector('input[name=page]').value; if(page > 0 && page <= {{ $paginator->lastPage() }}) window.location.href = this.action.replace('page=1', 'page=' + page);">
                                    <input type="number" name="page" min="1" max="{{ $paginator->lastPage() }}" placeholder="{{ $paginator->currentPage() }}" class="w-20 px-3 py-1.5 text-sm text-center border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent">
                                    <button type="submit" class="px-4 py-1.5 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition-all duration-200 shadow-sm">Go</button>
                                </form>
                            </div>
                        </div>

                        {{-- Info Text --}}
                        <div class="mt-3 sm:mt-4 text-center">
                            <p class="text-xs sm:text-sm text-gray-500">
                                Menampilkan <span class="font-medium text-gray-700">{{ $paginator->firstItem() }}</span>
                                sampai <span class="font-medium text-gray-700">{{ $paginator->lastItem() }}</span>
                                dari <span class="font-medium text-gray-700">{{ $paginator->total() }}</span> data anak
                            </p>
                        </div>
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>