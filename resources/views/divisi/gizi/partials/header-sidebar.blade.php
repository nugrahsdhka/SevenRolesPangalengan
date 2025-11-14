{{-- HEADER DENGAN TOMBOL --}}
<x-slot name="header">
    <div class="flex justify-between items-center gap-4" x-data="{ openSidebar: false }">
        {{-- Kiri: Tombol Menu + Judul Divisi --}}
        <div class="flex items-center gap-3 min-w-0 flex-1">
            {{-- Tombol Toggle Sidebar --}}
            <button @click="openSidebar = !openSidebar"
                class="flex items-center justify-center h-10 w-10 rounded-lg bg-gradient-to-br from-red-500 to-red-600 text-white hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 shadow-md hover:shadow-lg flex-shrink-0"
                type="button" title="Menu Gizi">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform duration-300"
                    :class="openSidebar ? 'rotate-90' : ''" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            {{-- Judul Divisi --}}
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight whitespace-nowrap">
                {{ __('Dashboard Divisi Gizi') }}
            </h2>
        </div>
        {{-- Kanan: Dropdown Menu User --}}
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false"
                class="flex items-center justify-center h-10 w-10 rounded-full bg-red-600 text-white font-semibold text-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all hover:scale-105 flex-shrink-0"
                type="button" aria-expanded="false" aria-haspopup="true" title="{{ auth()->user()->name }}">
                @php
                    $words = explode(' ', trim(auth()->user()->name));
                    $initials = '';
                    foreach ($words as $word) {
                        if (!empty($word)) {
                            $initials .= strtoupper(substr($word, 0, 1));
                        }
                    }
                    if (strlen($initials) > 2) {
                        $initials = substr($initials, 0, 1) . substr($initials, -1);
                    }
                @endphp
                {{ $initials }}
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                @keydown.escape.window="open = false"
                class="absolute right-0 mt-2 w-56 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 overflow-hidden"
                style="display: none;">
                <div class="py-1">
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
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
                    <div class="border-t border-gray-100 my-1"></div>
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
        {{-- BACKDROP --}}
        <div
            x-show="openSidebar"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-gray-900/40 backdrop-blur-sm"
            style="display: none;"
            @click="openSidebar = false"
        ></div>
        {{-- SIDEBAR --}}
        <aside
            x-show="openSidebar"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="-translate-x-full opacity-0"
            style="display: none;"
            class="fixed left-0 top-0 bottom-0 w-80 max-w-full z-50
                bg-white/95 backdrop-blur-xl border-r border-gray-200
                shadow-[0_10px_40px_rgba(15,23,42,0.35)] transform">
            <div class="p-6 space-y-6">
                {{-- Header dengan Close Button --}}
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-xl
                                    bg-gradient-to-br from-red-500 to-red-600 shadow-lg shadow-red-500/40">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Menu Gizi</h2>
                                <p class="text-xs text-gray-500 mt-0.5">Divisi Gizi</p>
                            </div>
                        </div>
                    </div>
                    {{-- Close button --}}
                    <button
                        @click="openSidebar = false"
                        class="p-2 rounded-lg hover:bg-gray-100 active:bg-gray-200 transition-colors"
                    >
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                {{-- Divider --}}
                <div class="h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                {{-- Navigation Menu --}}
                <nav class="space-y-2">
                    {{-- Data yang Sudah Diinput --}}
                    <a href="{{ route('gizi.index', [
                            'view' => 'data',
                            'period' => $periodYm ?? now()->format('Y-m'),
                            'status' => $status,
                        ]) }}"
                        class="group relative flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold
                            transition-all duration-200 overflow-hidden
                            {{ $section === 'data'
                                    ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg shadow-red-500/50'
                                    : 'bg-white/90 text-gray-700 hover:bg-red-50 hover:text-red-600 border border-gray-200 hover:border-red-300 hover:shadow-md' }}">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 opacity-0
                                    group-hover:opacity-100 transition-opacity duration-200
                                    {{ $section === 'data' ? 'hidden' : '' }}">
                        </div>
                        <span
                            class="relative flex h-10 w-10 items-center justify-center rounded-lg
                                transition-all duration-200
                                {{ $section === 'data'
                                        ? 'bg-white/20 text-white'
                                        : 'bg-red-100 text-red-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </span>
                        <span class="relative group-hover:text-white transition-colors">
                            Data Diinput
                        </span>
                        @if ($section === 'data')
                            <span class="relative ml-auto">
                                <span class="flex h-2 w-2 rounded-full bg-white"></span>
                            </span>
                        @endif
                    </a>

                    {{-- Tambah Data --}}
                    <a href="{{ route('gizi.index', [
                            'view' => 'tambah',
                            'period' => $periodYm ?? now()->format('Y-m'),
                            'status' => $status,
                        ]) }}"
                        class="group relative flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold
                            transition-all duration-200 overflow-hidden
                            {{ $section === 'tambah'
                                    ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg shadow-red-500/50'
                                    : 'bg-white/90 text-gray-700 hover:bg-red-50 hover:text-red-600 border border-gray-200 hover:border-red-300 hover:shadow-md' }}">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 opacity-0
                                    group-hover:opacity-100 transition-opacity duration-200
                                    {{ $section === 'tambah' ? 'hidden' : '' }}">
                        </div>
                        <span
                            class="relative flex h-10 w-10 items-center justify-center rounded-lg
                                transition-all duration-200
                                {{ $section === 'tambah'
                                        ? 'bg-white/20 text-white'
                                        : 'bg-red-100 text-red-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </span>
                        <span class="relative group-hover:text-white transition-colors">
                            Tambah Data
                        </span>
                        @if ($section === 'tambah')
                            <span class="relative ml-auto">
                                <span class="flex h-2 w-2 rounded-full bg-white"></span>
                            </span>
                        @endif
                    </a>

                    {{-- Detail Data --}}
                    <a href="{{ route('gizi.index', [
                            'view' => 'detail',
                            'period' => $periodYm ?? now()->format('Y-m'),
                        ]) }}"
                        class="group relative flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold
                            transition-all duration-200 overflow-hidden
                            {{ $section === 'detail'
                                    ? 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg shadow-red-500/50'
                                    : 'bg-white/90 text-gray-700 hover:bg-red-50 hover:text-red-600 border border-gray-200 hover:border-red-300 hover:shadow-md' }}">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-red-500 to-red-600 opacity-0
                                    group-hover:opacity-100 transition-opacity duration-200
                                    {{ $section === 'detail' ? 'hidden' : '' }}">
                        </div>
                        <span
                            class="relative flex h-10 w-10 items-center justify-center rounded-lg
                                transition-all duration-200
                                {{ $section === 'detail'
                                        ? 'bg-white/20 text-white'
                                        : 'bg-red-100 text-red-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        <span class="relative group-hover:text-white transition-colors">
                            Detail per Desa
                        </span>
                        @if ($section === 'detail')
                            <span class="relative ml-auto">
                                <span class="flex h-2 w-2 rounded-full bg-white"></span>
                            </span>
                        @endif
                    </a>
                </nav>
                {{-- Footer Info --}}
                <div class="mt-4 p-4 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl border border-gray-300">
                    <div class="flex items-start gap-3">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-500 text-white flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700 mb-1">Tips</p>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                Pastikan data yang diinput sudah sesuai dengan periode yang dipilih
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</x-slot>