<x-app-layout>
    <x-slot name="title">
        Divisi Gizi
    </x-slot>

    @php
        $periodYm = $currentPeriod ? \Carbon\Carbon::parse($currentPeriod)->format('Y-m') : null;

        $tabItems = [
            'pending' => [
                'label' => 'Pending',
                'bg' => 'bg-amber-50',
                'text' => 'text-amber-700',
                'border' => 'border-amber-500',
                'badge_bg' => 'bg-amber-100',
                'badge_text' => 'text-amber-700',
                'dot' => 'bg-amber-500',
            ],
            'approved' => [
                'label' => 'Approved',
                'bg' => 'bg-blue-50',
                'text' => 'text-blue-700',
                'border' => 'border-blue-500',
                'badge_bg' => 'bg-blue-100',
                'badge_text' => 'text-blue-700',
                'dot' => 'bg-blue-500',
            ],
            'published' => [
                'label' => 'Published',
                'bg' => 'bg-emerald-50',
                'text' => 'text-emerald-700',
                'border' => 'border-emerald-500',
                'badge_bg' => 'bg-emerald-100',
                'badge_text' => 'text-emerald-700',
                'dot' => 'bg-emerald-500',
            ],
        ];

        $counts = array_merge(['pending' => 0, 'approved' => 0, 'published' => 0], $statusCounts ?? []);
        $section = $view ?? 'data';
    @endphp

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
                    {{ __('Divisi Gizi') }}
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
                            <p class="text-sm text-gray-600">
                                Kelola data gizi per desa/kelurahan
                            </p>
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

                        {{-- Data yang Sudah Diinput --}}
                        <a href="{{ route('gizi.index', [
                                'view' => 'data',
                                'period' => $periodYm ?? now()->format('Y-m'),
                                'status' => $status,
                            ]) }}"
                        class="group relative flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold
                                transition-all duration-200 overflow-hidden
                                {{ $section === 'data'
                                        ? 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg shadow-blue-500/50'
                                        : 'bg-white/90 text-gray-700 hover:bg-blue-50 hover:text-blue-600 border border-gray-200 hover:border-blue-300 hover:shadow-md' }}">

                            <div
                                class="absolute inset-0 bg-gradient-to-r from-blue-500 to-blue-600 opacity-0
                                        group-hover:opacity-100 transition-opacity duration-200
                                        {{ $section === 'data' ? 'hidden' : '' }}">
                            </div>

                            <span
                                class="relative flex h-10 w-10 items-center justify-center rounded-lg
                                    transition-all duration-200
                                    {{ $section === 'data'
                                            ? 'bg-white/20 text-white'
                                            : 'bg-blue-100 text-blue-600' }}">
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

                        {{-- Detail Data --}}
                        <a href="{{ route('gizi.index', [
                                'view' => 'detail',
                                'period' => $periodYm ?? now()->format('Y-m'),
                            ]) }}"
                        class="group relative flex items-center gap-4 px-4 py-3.5 rounded-xl text-sm font-semibold
                                transition-all duration-200 overflow-hidden
                                {{ $section === 'detail'
                                        ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-lg shadow-emerald-500/50'
                                        : 'bg-white/90 text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-200 hover:border-emerald-300 hover:shadow-md' }}">

                            <div
                                class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-emerald-600 opacity-0
                                        group-hover:opacity-100 transition-opacity duration-200
                                        {{ $section === 'detail' ? 'hidden' : '' }}">
                            </div>

                            <span
                                class="relative flex h-10 w-10 items-center justify-center rounded-lg
                                    transition-all duration-200
                                    {{ $section === 'detail'
                                            ? 'bg-white/20 text-white'
                                            : 'bg-emerald-100 text-emerald-600' }}">
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
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-500 text-white flex-shrink-0">
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

    <div class="px-1 py-1">
        <div class="max-w-7xl ">

            {{-- Alerts --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="my-3 flex items-center gap-3 rounded-xl bg-green-50 p-4 ring-1 ring-green-200">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-green-900">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-green-600 hover:text-green-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="my-3 flex items-center gap-3 rounded-xl bg-red-50 p-4 ring-1 ring-red-200">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-900">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="flex-shrink-0 text-red-600 hover:text-red-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            @endif
            @if ($errors->any())
                <div class="my-3 p-4 bg-red-50 text-red-800 rounded-xl ring-1 ring-red-200 text-sm">
                    <p class="font-bold mb-2">Terjadi kesalahan validasi:</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex flex-col lg:flex-row">
                {{-- MAIN CONTENT --}}
                <section class="flex-1 rounded-2xl shadow-lg">
                    @if ($section === 'tambah')
                        @php
                            // Mode input: manual / import (default: manual)
                            $inputMode = request('mode', 'manual');

                            // Periode aktif (Y-m) + label tampilan
                            $activePeriodYm = $periodYm ?? \Carbon\Carbon::parse($currentPeriod ?? now())->format('Y-m');
                            $currentPeriodLabel = $currentPeriod
                                ? \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y')
                                : now()->isoFormat('MMMM Y');
                        @endphp

                        <x-slot name="title">
                            Gizi | Tambah Data
                        </x-slot>

                        <div class="px-2 py-2 flex flex-col gap-4">

                            {{-- HEADER: Judul + info periode --}}
                            <div class="rounded-xl shadow-sm border border-gray-200 bg-white px-4 py-4">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                    <div>
                                        <h3 class="text-center md:text-left text-sm md:text-lg font-extrabold text-gray-800">
                                            Tambah Data Gizi
                                        </h3>
                                    </div>

                                    <div class="text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2">
                                        <span class="font-medium text-gray-800">Periode aktif:</span>
                                        <span class="ml-1">{{ $currentPeriodLabel }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Main card --}}
                            <div class="w-full flex justify-center">
                                <div class="w-full max-w-3xl">
                                    {{-- TABS: Manual vs Import --}}
                                    <div class="border-b border-gray-200 mb-4">
                                        <nav class="flex flex-wrap gap-2" aria-label="Metode input">
                                            @php
                                                $tabItemsTambah = [
                                                    'manual' => [
                                                        'label' => 'Input Manual',
                                                        'bg'    => 'border-red-500 text-red-700 bg-red-50',
                                                    ],
                                                    'import' => [
                                                        'label' => 'Import Excel',
                                                        'bg'    => 'border-emerald-500 text-emerald-700 bg-emerald-50',
                                                    ],
                                                ];
                                            @endphp

                                            @foreach ($tabItemsTambah as $modeKey => $meta)
                                                @php $active = ($inputMode === $modeKey); @endphp
                                                <a href="{{ route('gizi.index', [
                                                        'view'  => 'tambah',
                                                        'mode'  => $modeKey,
                                                        'period'=> $activePeriodYm,
                                                    ]) }}"
                                                class="group inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium rounded-t-lg border-b-2 transition-all
                                                        {{ $active
                                                            ? $meta['bg'] . ' border-b-2'
                                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300' }}">
                                                    <span class="flex h-2 w-2 rounded-full
                                                                {{ $active ? 'bg-current' : 'bg-gray-300 group-hover:bg-gray-400' }}"></span>
                                                    <span>{{ $meta['label'] }}</span>
                                                </a>
                                            @endforeach
                                        </nav>
                                    </div>

                                    {{-- KONTEN: sesuai tab --}}
                                    @if ($inputMode === 'manual')
                                        {{-- ================= TAB: INPUT MANUAL ================= --}}
                                        <div class="max-w-3xl">
                                            <h4 class="text-sm font-semibold text-gray-900 mb-3">
                                                Input Manual
                                            </h4>
                                            <p class="text-xs text-gray-500 mb-4">
                                                Gunakan formulir ini untuk menambah satu data gizi anak.
                                            </p>

                                            <form action="{{ route('gizi.store') }}" method="POST" class="space-y-4">
                                                @csrf

                                                <div class="space-y-2">
                                                    <label for="manual_period" class="text-xs font-medium text-gray-700">Periode</label>
                                                    <input
                                                        type="month"
                                                        id="manual_period"
                                                        name="period"
                                                        value="{{ old('period', $activePeriodYm) }}"
                                                        required
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                    >
                                                </div>

                                                <div class="space-y-2">
                                                    <label for="desa_kelurahan" class="text-xs font-medium text-gray-700">Desa/Kelurahan</label>
                                                    <input
                                                        type="text"
                                                        id="desa_kelurahan"
                                                        name="desa_kelurahan"
                                                        value="{{ old('desa_kelurahan') }}"
                                                        required
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                        placeholder="Contoh: Pangalengan"
                                                    >
                                                </div>

                                                <div class="grid grid-cols-2 gap-3">
                                                    <div class="space-y-2">
                                                        <label for="berat" class="text-xs font-medium text-gray-700">Berat (kg)</label>
                                                        <input
                                                            type="number"
                                                            id="berat"
                                                            name="berat"
                                                            step="0.01"
                                                            min="0"
                                                            value="{{ old('berat') }}"
                                                            required
                                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                        >
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label for="tinggi" class="text-xs font-medium text-gray-700">Tinggi (cm)</label>
                                                        <input
                                                            type="number"
                                                            id="tinggi"
                                                            name="tinggi"
                                                            step="0.01"
                                                            min="0"
                                                            value="{{ old('tinggi') }}"
                                                            required
                                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                        >
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-3 gap-3">
                                                    {{-- BB/U --}}
                                                    <div class="space-y-2">
                                                        <label class="text-xs font-medium text-gray-700">Status BB/U</label>
                                                        <input
                                                            type="text"
                                                            name="status_bb_u"
                                                            value="{{ old('status_bb_u') }}"
                                                            class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                            placeholder="misal: Gizi Kurang"
                                                        >
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            name="zs_bb_u"
                                                            value="{{ old('zs_bb_u') }}"
                                                            class="mt-1 w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                            placeholder="Z-Score BB/U"
                                                        >
                                                    </div>

                                                    {{-- TB/U --}}
                                                    <div class="space-y-2">
                                                        <label class="text-xs font-medium text-gray-700">Status TB/U</label>
                                                        <input
                                                            type="text"
                                                            name="status_tb_u"
                                                            value="{{ old('status_tb_u') }}"
                                                            class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                            placeholder="misal: Pendek"
                                                        >
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            name="zs_tb_u"
                                                            value="{{ old('zs_tb_u') }}"
                                                            class="mt-1 w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                            placeholder="Z-Score TB/U"
                                                        >
                                                    </div>

                                                    {{-- BB/TB --}}
                                                    <div class="space-y-2">
                                                        <label class="text-xs font-medium text-gray-700">Status BB/TB</label>
                                                        <input
                                                            type="text"
                                                            name="status_bb_tb"
                                                            value="{{ old('status_bb_tb') }}"
                                                            class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                            placeholder="misal: Kurus"
                                                        >
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            name="zs_bb_tb"
                                                            value="{{ old('zs_bb_tb') }}"
                                                            class="mt-1 w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                                            placeholder="Z-Score BB/TB"
                                                        >
                                                    </div>
                                                </div>

                                                <div class="pt-2">
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 active:from-red-800 active:to-red-900 transition-all duration-200 shadow-md hover:shadow-lg"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Simpan Data
                                                    </button>
                                                </div>
                                            </form>
                                        </div>

                                    @else
                                        {{-- ================= TAB: IMPORT EXCEL ================= --}}
                                        <div
                                            x-data="{
                                                open: true,
                                                showFormatModal: false,
                                                isDragging: false,
                                                fileName: '',
                                                handleFiles(files) {
                                                    if (files.length > 0) {
                                                        this.fileName = files[0].name;
                                                        this.$refs.fileInput.files = files;
                                                    }
                                                }
                                            }"
                                            x-cloak
                                            class="max-w-3xl border border-gray-200 rounded-xl p-5 bg-white"
                                        >
                                            <div class="flex items-start justify-between gap-3 mb-3">
                                                <div>
                                                    <h4 class="text-sm font-semibold text-gray-900">
                                                        Import dari Excel
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        Gunakan file Excel dengan format yang sudah ditentukan.
                                                    </p>
                                                </div>
                                                <button
                                                    @click="showFormatModal = true"
                                                    type="button"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-all"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Lihat Contoh Format
                                                </button>
                                            </div>

                                            <form
                                                action="{{ route('gizi.import') }}"
                                                method="POST"
                                                enctype="multipart/form-data"
                                                class="space-y-4"
                                            >
                                                @csrf

                                                <div class="space-y-2">
                                                    <label for="import_period" class="text-xs font-medium text-gray-700">Periode Import</label>
                                                    <input
                                                        type="month"
                                                        id="import_period"
                                                        name="period"
                                                        required
                                                        value="{{ $activePeriodYm }}"
                                                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                                    >
                                                </div>

                                                <div
                                                    @dragover.prevent="isDragging = true"
                                                    @dragleave.prevent="isDragging = false"
                                                    @drop.prevent="isDragging=false; handleFiles($event.dataTransfer.files)"
                                                    :class="isDragging ? 'border-red-500 bg-red-50 scale-[1.02]' : 'border-gray-300 bg-gray-50'"
                                                    class="relative border-2 border-dashed rounded-xl p-6 sm:p-8 text-center transition-all duration-200 cursor-pointer hover:border-red-400 hover:bg-red-50/50"
                                                    @click="$refs.fileInput.click()"
                                                >
                                                    <div class="flex justify-center mb-3">
                                                        <div class="relative">
                                                            <svg
                                                                class="w-12 h-12 text-gray-300 transition-transform duration-200"
                                                                :class="isDragging ? 'scale-110 text-red-400' : ''"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="1.5"
                                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                                                />
                                                            </svg>
                                                            <div x-show="isDragging" class="absolute inset-0 flex items-center justify-center">
                                                                <div class="w-16 h-16 border-4 border-red-500 border-dashed rounded-full animate-spin"></div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div x-show="!fileName">
                                                        <p class="text-sm font-medium text-gray-700 mb-1">
                                                            <span class="text-red-600">Klik untuk upload</span> atau drag & drop file
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            Excel (XLSX, XLS, CSV) • Maksimal 2MB
                                                        </p>
                                                    </div>

                                                    <div
                                                        x-show="fileName"
                                                        x-transition
                                                        class="flex flex-col sm:flex-row items-center justify-center gap-3 p-3 bg-white rounded-lg border border-green-200 mt-2"
                                                    >
                                                        <div class="flex items-center gap-2">
                                                            <div class="flex-shrink-0 w-9 h-9 bg-green-100 rounded-full flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs font-medium text-gray-700 break-all" x-text="fileName"></span>
                                                        </div>
                                                        <button
                                                            type="button"
                                                            @click.stop="fileName=''; $refs.fileInput.value=''"
                                                            class="flex-shrink-0 p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <input
                                                        type="file"
                                                        name="file"
                                                        x-ref="fileInput"
                                                        @change="handleFiles($event.target.files)"
                                                        class="hidden"
                                                        accept=".xlsx,.xls,.csv"
                                                        required
                                                    >
                                                </div>

                                                <div class="pt-1 flex justify-end">
                                                    <button
                                                        type="submit"
                                                        :disabled="!fileName"
                                                        :class="fileName
                                                            ? 'bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-md hover:shadow-lg'
                                                            : 'bg-gray-200 cursor-not-allowed'"
                                                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white rounded-lg transition-all duration-200"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                        </svg>
                                                        Upload File
                                                    </button>
                                                </div>
                                            </form>

                                            {{-- MODAL FORMAT EXCEL --}}
                                            <div
                                                x-show="showFormatModal"
                                                x-transition:enter="transition ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="fixed inset-0 z-50 overflow-y-auto"
                                                style="display: none;"
                                                @keydown.escape.window="showFormatModal = false"
                                            >
                                                <div class="flex items-center justify-center min-h-screen px-4 py-8">
                                                    <div
                                                        @click="showFormatModal = false"
                                                        class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm transition-opacity"
                                                    ></div>

                                                    <div
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0 transform scale-95"
                                                        x-transition:enter-end="opacity-100 transform scale-100"
                                                        x-transition:leave="transition ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 transform scale-100"
                                                        x-transition:leave-end="opacity-0 transform scale-95"
                                                        class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden"
                                                    >
                                                        <div class="p-6 sm:p-8">
                                                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                                                                <div class="flex items-start gap-4">
                                                                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                                            />
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">
                                                                            Format Excel Import
                                                                        </h3>
                                                                        <p class="mt-1 text-sm text-gray-500">
                                                                            Pastikan file Excel Anda mengikuti format di bawah ini
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <button
                                                                    @click="showFormatModal = false"
                                                                    class="flex-shrink-0 p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-all"
                                                                >
                                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M6 18L18 6M6 6l12 12"
                                                                        />
                                                                    </svg>
                                                                </button>
                                                            </div>

                                                            <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 p-4 shadow-inner">
                                                                <img
                                                                    src="{{ asset('img/contohFormatExcel/divisiGizi.png') }}"
                                                                    alt="Contoh Format Excel"
                                                                    class="w-full h-auto rounded-lg shadow-md"
                                                                >
                                                                <p class="text-xs text-gray-400 text-center mt-4">
                                                                    Screenshot contoh format Excel yang benar
                                                                </p>
                                                            </div>

                                                            <div class="mt-6 p-5 bg-gradient-to-br from-red-50 to-orange-50 border border-red-200 rounded-xl">
                                                                <div class="flex gap-4">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                                                                />
                                                                            </svg>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-1 text-sm">
                                                                        <p class="font-semibold text-red-900 mb-3">
                                                                            Catatan Penting:
                                                                        </p>
                                                                        <ul class="space-y-2 text-red-800">
                                                                            <li class="flex items-start gap-2">
                                                                                <span class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                                                <span>Header (baris pertama) harus sesuai dengan contoh di atas</span>
                                                                            </li>
                                                                            <li class="flex items-start gap-2">
                                                                                <span class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                                                <span>Jangan ada kolom yang kosong</span>
                                                                            </li>
                                                                            <li class="flex items-start gap-2">
                                                                                <span class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                                                <span>Format file: .xlsx, .xls, atau .csv</span>
                                                                            </li>
                                                                            <li class="flex items-start gap-2">
                                                                                <span class="flex-shrink-0 w-1.5 h-1.5 bg-red-500 rounded-full mt-1.5"></span>
                                                                                <span>Maksimal ukuran file: 2MB</span>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="mt-8 flex justify-end">
                                                                <button
                                                                    @click="showFormatModal = false"
                                                                    class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 active:from-red-800 active:to-red-900 transition-all duration-200 shadow-md hover:shadow-lg"
                                                                >
                                                                    Mengerti
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @elseif ($section === 'data')
                        <x-slot name="title">
                            Gizi | Data Input 
                        </x-slot>

                        <div class="px-2 py-2 flex flex-col gap-4">

                            {{-- HEADER CARD: Judul + Tombol + Tabs + Periode --}}
                            <div class="rounded-xl shadow-sm border border-gray-200 bg-white px-4 py-4">
                                <div class="space-y-4">
                                    {{-- Header Section (judul + tombol tambah) --}}
                                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                            <h3 class="text-center md:text-left text-sm md:text-lg font-extrabold text-gray-800">
                                                Data Gizi per Desa/Kelurahan
                                            </h3>
                                        </div>

                                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                                            <a href="{{ route('gizi.index', [
                                                'view' => 'tambah',
                                                'period' => $periodYm ?? now()->format('Y-m'),
                                                'status' => 'pending',
                                            ]) }}"
                                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-red-600 to-red-700 rounded-lg hover:from-red-700 hover:to-red-800 active:from-red-800 active:to-red-900 transition-all duration-200 shadow-md hover:shadow-lg">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 4v16m8-8H4" />
                                                </svg>
                                                <span>Tambah / Import Data</span>
                                            </a>
                                        </div>
                                    </div>

                                    {{-- Status Tabs & Periode Selector --}}
                                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                        {{-- Navigation Tabs --}}
                                        <nav class="flex flex-col sm:flex-row sm:gap-2" role="tablist" aria-label="Status data">
                                            @foreach ($tabItems as $key => $meta)
                                                @php $active = ($status === $key); @endphp
                                                <a href="{{ route('gizi.index', [
                                                    'view' => 'data',
                                                    'period' => $periodYm ?? now()->format('Y-m'),
                                                    'status' => $key,
                                                ]) }}"
                                                role="tab" aria-selected="{{ $active ? 'true' : 'false' }}"
                                                class="group relative flex items-center justify-between sm:justify-center gap-3 px-4 py-3 sm:py-4 text-sm font-medium border-b-2 sm:border-b-3 transition-all duration-200
                                                {{ $active
                                                    ? $meta['text'] . ' ' . $meta['border'] . ' ' . $meta['bg']
                                                    : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 hover:bg-gray-50' }}">
                                                    <span class="flex items-center gap-2">
                                                        {{-- Animated Dot Indicator --}}
                                                        <span class="relative flex h-2 w-2 {{ $active ? '' : 'opacity-0 group-hover:opacity-50' }}">
                                                            @if ($active && $counts[$key] > 0)
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $meta['dot'] }} opacity-75"></span>
                                                            @endif
                                                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $meta['dot'] }}"></span>
                                                        </span>
                                                        <span>{{ $meta['label'] }}</span>
                                                    </span>

                                                    {{-- Elegant Badge --}}
                                                    <span
                                                        class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-1 rounded-full text-xs font-semibold transition-all duration-200
                                                        {{ $active
                                                            ? $meta['badge_bg'] . ' ' . $meta['badge_text'] . ' shadow-sm'
                                                            : 'bg-gray-100 text-gray-600 group-hover:bg-gray-200' }}">
                                                        {{ $counts[$key] ?? 0 }}
                                                    </span>

                                                    {{-- Active Indicator Line --}}
                                                    @if ($active)
                                                        <span class="absolute bottom-0 left-0 right-0 h-0.5 {{ $meta['bg'] }} hidden sm:block"></span>
                                                    @endif
                                                </a>
                                            @endforeach
                                        </nav>

                                        {{-- Periode Selector --}}
                                        <form method="GET" action="{{ route('gizi.index') }}"
                                            class="flex flex-wrap items-center gap-2 sm:gap-3">
                                            <input type="hidden" name="view" value="data" />
                                            <label class="text-sm text-gray-600">Periode:</label>

                                            <select name="period" required
                                                    class="text-sm rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500">
                                                @forelse($availablePeriods ?? [] as $p)
                                                    @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                                    <option value="{{ $ym }}" {{ $periodYm === $ym ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                                    </option>
                                                @empty
                                                    @php $ymNow = now()->format('Y-m'); @endphp
                                                    <option value="{{ $ymNow }}" selected>
                                                        {{ now()->isoFormat('MMMM Y') }}
                                                    </option>
                                                @endforelse
                                            </select>

                                            <input type="hidden" name="status" value="{{ $status ?? 'pending' }}" />

                                            <button type="submit"
                                                    class="px-2 md:px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition-all duration-200 shadow-sm hover:shadow-md">
                                                Tampilkan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- TABLE CARD: Tabel Agregasi dengan layout mirip section detail --}}
                            <div class="bg-white/30 backdrop-blur-md border border-gray-200/30 rounded-xl shadow-lg px-2 py-2">
                                <div class="relative">
                                    <div class="max-h-[64vh] md:max-h-[63vh] overflow-y-auto overflow-x-auto bg-white">
                                        <table class="w-full min-w-[1200px]">
                                            <thead class="sticky top-0 z-20 bg-gray-50 shadow-sm">
                                                <tr class="border-b border-gray-200">
                                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Periode
                                                    </th>
                                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Desa/Kelurahan
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Total Anak
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Avg. Berat (kg)
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Avg. Tinggi (cm)
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Avg. ZS BB/U
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Avg. ZS TB/U
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Avg. ZS BB/TB
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Status Data
                                                    </th>
                                                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-700">
                                                        Aksi
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @forelse ($aggregatedData as $item)
                                                    @php
                                                        $avgBerat =
                                                            $item->total_anak > 0
                                                                ? $item->sum_berat / $item->total_anak
                                                                : null;
                                                        $avgTinggi =
                                                            $item->total_anak > 0
                                                                ? $item->sum_tinggi / $item->total_anak
                                                                : null;
                                                        $avgZsBBU =
                                                            $item->total_anak > 0
                                                                ? $item->sum_zs_bbu / $item->total_anak
                                                                : null;
                                                        $avgZsTBU =
                                                            $item->total_anak > 0
                                                                ? $item->sum_zs_tbu / $item->total_anak
                                                                : null;
                                                        $avgZsBBTB =
                                                            $item->total_anak > 0
                                                                ? $item->sum_zs_bbtb / $item->total_anak
                                                                : null;
                                                    @endphp

                                                    <tr class="transition hover:bg-gray-50">
                                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">
                                                            {{ \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y') }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-4 py-3">
                                                            <div class="text-sm font-semibold text-gray-900 uppercase">
                                                                {{ $item->desa_kelurahan }}
                                                            </div>
                                                        </td>
                                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                                            {{ $item->total_anak }}
                                                        </td>

                                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                                            {{ !is_null($avgBerat) ? number_format($avgBerat, 2) : '-' }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600 text-center">
                                                            {{ !is_null($avgTinggi) ? number_format($avgTinggi, 2) : '-' }}
                                                        </td>

                                                        <td class="whitespace-nowrap px-4 py-3 text-center">
                                                            <x-zscore-badge :value="$avgZsBBU" />
                                                        </td>
                                                        <td class="whitespace-nowrap px-4 py-3 text-center">
                                                            <x-zscore-badge :value="$avgZsTBU" />
                                                        </td>
                                                        <td class="whitespace-nowrap px-4 py-3 text-center">
                                                            <x-zscore-badge :value="$avgZsBBTB" />
                                                        </td>

                                                        <td class="whitespace-nowrap px-4 py-3 text-center">
                                                            @if ($item->status === 'published')
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                                                    <span class="h-1.5 w-1.5 rounded-full bg-green-600"></span>
                                                                    Published
                                                                </span>
                                                            @elseif($item->status === 'approved')
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-3 py-1 text-xs font-medium text-blue-700">
                                                                    <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                                                    Approved
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700">
                                                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-600"></span>
                                                                    Pending
                                                                </span>
                                                            @endif
                                                        </td>

                                                        <td class="whitespace-nowrap px-4 py-3">
                                                            <div class="flex items-center justify-center gap-3">
                                                                <a href="{{ route('gizi.index', [
                                                                    'view' => 'detail',
                                                                    'desa' => $item->desa_kelurahan,
                                                                    'period' => $periodYm ?? now()->format('Y-m'),
                                                                ]) }}"
                                                                class="text-sm font-medium text-indigo-600 transition hover:text-indigo-800 hover:underline">
                                                                    Detail
                                                                </a>

                                                                <span class="text-gray-300">|</span>

                                                                <form action="{{ route('gizi.destroyAggregated') }}" method="POST" x-data>
                                                                    @csrf
                                                                    <input type="hidden" name="desa_kelurahan" value="{{ $item->desa_kelurahan }}">
                                                                    <input type="hidden" name="status" value="{{ $item->status }}">
                                                                    <input type="hidden" name="period" value="{{ $periodYm ?? now()->format('Y-m') }}">
                                                                    <button type="button"
                                                                            @click.prevent="$dispatch('open-confirm', { form: $el.closest('form') })"
                                                                            class="text-sm font-medium text-red-600 transition hover:text-red-800 hover:underline">
                                                                        Hapus
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="10" class="px-6 py-12 text-center">
                                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                                stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                            </svg>

                                                            @php
                                                                $totalDataInPeriod =
                                                                    ($statusCounts['pending'] ?? 0) +
                                                                    ($statusCounts['approved'] ?? 0) +
                                                                    ($statusCounts['published'] ?? 0);
                                                            @endphp

                                                            @if ($totalDataInPeriod === 0)
                                                                <p class="mt-4 text-sm text-gray-500">
                                                                    Data tidak ditemukan untuk periode ini
                                                                </p>
                                                            @else
                                                                @if ($status === 'pending')
                                                                    <p class="mt-4 text-sm text-gray-500">
                                                                        Semua data untuk periode ini sudah diproses (di-approve/publish).
                                                                    </p>
                                                                @elseif ($status === 'approved')
                                                                    <p class="mt-4 text-sm text-gray-500">
                                                                        Belum ada data gizi pada periode ini yang di-approve.
                                                                    </p>
                                                                @elseif ($status === 'published')
                                                                    <p class="mt-4 text-sm text-gray-500">
                                                                        Belum ada data gizi pada periode ini yang di-publish.
                                                                    </p>
                                                                @endif
                                                            @endif

                                                            @if ($status === 'pending')
                                                                <a href="{{ route('gizi.index', [
                                                                    'view' => 'tambah',
                                                                    'period' => $periodYm ?? now()->format('Y-m'),
                                                                ]) }}"
                                                                class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-red-600 hover:text-red-700">
                                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M12 4v16m8-8H4" />
                                                                    </svg>
                                                                    {{ $totalDataInPeriod === 0 ? 'Tambah Data Pertama' : 'Tambah Data Lagi' }}
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($section === 'detail')
                        <x-slot name="title">
                            Gizi | Detail Data
                        </x-slot>

                        @php
                            // Periode aktif dalam format Y-m (aman walau $currentPeriod null)
                            $activePeriodYm = $periodYm ?? \Carbon\Carbon::parse($currentPeriod ?? now())->format('Y-m');

                            // Label periode untuk tampilan (contoh: "November 2025")
                            $currentPeriodLabel = $currentPeriod
                                ? \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y')
                                : now()->isoFormat('MMMM Y');
                        @endphp

                        <div class="flex flex-col-reverse lg:flex-row gap-2 px-2 py-2 backdrop-blur-sm" x-data="{ sidebarOpen: false }">

                            {{-- List Desa --}}
                            <div class="order-2 lg:order-1 lg:relative z-50">

                                {{-- Sidebar desktop daftar desa --}}
                                <div class="hidden lg:block">
                                    <div class="lg:sticky lg:top-4">

                                        {{-- Tombol toggle sidebar (chevron) --}}
                                        <button type="button" @click="sidebarOpen = !sidebarOpen"
                                                class="absolute top-16 right-0 translate-x-full z-50
                                                        bg-white border border-l-0 border-gray-200 
                                                        rounded-r-lg px-1 py-3 text-gray-500 hover:text-emerald-600
                                                        active:bg-gray-100 transition-colors"
                                                title="Toggle sidebar">
                                            {{-- Ikon Chevron Left (tutup) --}}
                                            <svg x-show="sidebarOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                            </svg>
                                            {{-- Ikon Chevron Right (buka) --}}
                                            <svg x-show="!sidebarOpen" style="display: none;" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>

                                        {{-- Card sidebar desa --}}
                                        <div class="bg-white/40 backdrop-blur-md border border-gray-200/50 rounded-xl overflow-hidden transition-all duration-300 ease-in-out"
                                            :class="sidebarOpen ? 'lg:w-[250px]' : 'lg:w-0'">
                                            
                                            {{-- Header sidebar --}}
                                            <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-xl whitespace-nowrap">
                                                <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    Daftar Desa/Kelurahan
                                                </h4>
                                                @if (!$desaList->isEmpty())
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        {{ count($desaList) }} desa tersedia
                                                    </p>
                                                @endif
                                            </div>

                                            {{-- Isi sidebar: list desa / empty state --}}
                                            <div class="p-3 max-h-[50vh] sm:max-h-[60vh] lg:max-h-[calc(100vh-220px)] overflow-y-auto
                                                        transition-opacity duration-100"
                                                :class="!sidebarOpen ? 'opacity-0' : 'opacity-100'">
                                                @if ($desaList->isEmpty())
                                                    {{-- Empty state sidebar --}}
                                                    <div class="flex flex-col items-center justify-center py-8 text-center">
                                                        <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                        </svg>
                                                        <p class="mt-3 text-sm text-gray-500">
                                                            Tidak ada desa dengan data<br>pada periode ini
                                                        </p>
                                                    </div>
                                                @else
                                                    {{-- List desa --}}
                                                    <ul class="space-y-1.5">
                                                        @foreach ($desaList as $desa)
                                                            @php
                                                                $isActiveDesa = $selectedDesa && strtoupper($selectedDesa) === strtoupper($desa);
                                                            @endphp
                                                            <li>
                                                                <a href="{{ route('gizi.index', [
                                                                    'view' => 'detail',
                                                                    'period' => $activePeriodYm,
                                                                    'desa' => $desa,
                                                                ]) }}"
                                                                class="group flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                                                                        {{ $isActiveDesa
                                                                            ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md shadow-emerald-500/30'
                                                                            : 'text-gray-700 bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 border border-gray-200 hover:border-emerald-300 hover:shadow-sm' }}">
                                                                    <span class="truncate uppercase">{{ $desa }}</span>
                                                                    @if ($isActiveDesa)
                                                                        <span class="ml-2 flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-white/20">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                                                    d="M5 13l4 4L19 7" />
                                                                            </svg>
                                                                        </span>
                                                                    @else
                                                                        <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity text-emerald-600"
                                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M9 5l7 7-7 7" />
                                                                        </svg>
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Header kecil mobile di atas konten --}}
                                <div class="block md:hidden rounded-xl shadow-md border border-gray-200/40 p-3 bg-white/30 backdrop-blur-md mb-3">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex flex-col md:flex-row justify-between md:items-center">
                                            <div>
                                                <h3 class="text-center md:text-left text-sm md:text-lg font-extrabold text-gray-800">
                                                    Detail Data Gizi per Desa/Kelurahan
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Selector desa + periode untuk mobile --}}
                                <div class="block lg:hidden bg-white/40 backdrop-blur-md border border-gray-200/40 rounded-xl shadow-md p-3">
                                    
                                    {{-- Dropdown desa mobile --}}
                                    <label for="mobile-desa-selector" class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        Pilih Desa/Kelurahan
                                    </label>
                                    
                                    <select id="mobile-desa-selector"
                                            class="w-full text-sm rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm"
                                            onchange="if (this.value) window.location.href = this.value;">
                                        @if ($desaList->isEmpty())
                                            <option value="" disabled selected>Tidak ada desa tersedia</option>
                                        @else
                                            <option value="" {{ !$selectedDesa ? 'selected' : '' }} disabled>-- Pilih Desa --</option>
                                            @foreach ($desaList as $desa)
                                                @php
                                                    $isActiveDesa = $selectedDesa && strtoupper($selectedDesa) === strtoupper($desa);
                                                @endphp
                                                <option 
                                                    value="{{ route('gizi.index', [
                                                        'view' => 'detail',
                                                        'period' => $activePeriodYm,
                                                        'desa' => $desa,
                                                    ]) }}"
                                                    {{ $isActiveDesa ? 'selected' : '' }}>
                                                    {{ strtoupper($desa) }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>

                                    {{-- Form filter periode (mobile) --}}
                                    <form method="GET" action="{{ route('gizi.index') }}"
                                        class="flex flex-col md:flex-row md:items-center gap-3 md:gap-4 mt-2">
                                        <input type="hidden" name="view" value="detail">
                                        @if ($selectedDesa)
                                            <input type="hidden" name="desa" value="{{ $selectedDesa }}">
                                        @endif

                                        <div class="flex items-center gap-2 flex-1 md:flex-initial">
                                            <label class="hidden md:block text-sm font-medium text-gray-700 whitespace-nowrap">
                                                Periode:
                                            </label>
                                            <select name="period" required
                                                    class="flex-1 md:flex-initial md:min-w-[180px] text-sm rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 shadow-sm">
                                                @forelse($availablePeriods ?? [] as $p)
                                                    @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                                    <option value="{{ $ym }}" {{ $activePeriodYm === $ym ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                                    </option>
                                                @empty
                                                    @php $ymNow = now()->format('Y-m'); @endphp
                                                    <option value="{{ $ymNow }}" selected>
                                                        {{ now()->isoFormat('MMMM Y') }}
                                                    </option>
                                                @endforelse
                                            </select>
                                        </div>

                                        <button type="submit"
                                                class="w-full md:w-auto px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 active:scale-95 transition-all duration-200 shadow-md hover:shadow-lg">
                                            <span class="flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                                Tampilkan
                                            </span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Konten Utama --}}
                            <div class="order-1 lg:order-2 w-full flex-1 min-w-0 z-30">
                                
                                {{-- Header desktop detail + filter periode --}}
                                <div class="rounded-xl hidden md:block shadow-sm border p-4 bg-white mb-3">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex flex-col md:flex-row justify-between md:items-center">
                                            <div>
                                                <h3 class="text-center md:text-left text-sm md:text-lg font-extrabold text-gray-800">
                                                    Detail Data Gizi per Desa/Kelurahan
                                                </h3>
                                            </div>

                                            {{-- Form filter periode (desktop) --}}
                                            <form method="GET" action="{{ route('gizi.index') }}"
                                                class="hidden md:flex flex-col md:flex-row md:items-center gap-3 md:gap-4">
                                                <input type="hidden" name="view" value="detail">
                                                @if ($selectedDesa)
                                                    <input type="hidden" name="desa" value="{{ $selectedDesa }}">
                                                @endif

                                                <div class="flex items-center gap-2 flex-1 md:flex-initial">
                                                    <label class="hidden md:block text-sm font-medium text-gray-700 whitespace-nowrap">
                                                        Periode:
                                                    </label>
                                                    <select name="period" required
                                                            class="flex-1 md:flex-initial md:min-w-[180px] text-sm rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 shadow-sm">
                                                        @forelse($availablePeriods ?? [] as $p)
                                                            @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                                            <option value="{{ $ym }}" {{ $activePeriodYm === $ym ? 'selected' : '' }}>
                                                                {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                                            </option>
                                                        @empty
                                                            @php $ymNow = now()->format('Y-m'); @endphp
                                                            <option value="{{ $ymNow }}" selected>
                                                                {{ now()->isoFormat('MMMM Y') }}
                                                            </option>
                                                        @endforelse
                                                    </select>
                                                </div>

                                                <button type="submit"
                                                        class="w-full md:w-auto px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 active:scale-95 transition-all duration-200 shadow-md hover:shadow-lg">
                                                    <span class="flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                        </svg>
                                                        Tampilkan
                                                    </span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- Card utama detail (statistik + tabel) --}}
                                <div class="bg-white/30 backdrop-blur-md border border-gray-200/30 rounded-xl px-2 py-2">
                                    @if (!$selectedDesa)
                                        {{-- State jika belum memilih desa --}}
                                        <div class="flex flex-col items-center justify-center py-12 sm:py-16 px-4 text-center">
                                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Pilih Desa</h4>
                                                <p class="text-sm text-gray-500 max-w-md">
                                                    Pilih desa dari daftar untuk melihat detail data gizi
                                                </p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="spaye-y-4">
                                            {{-- Statistik ringkas per desa --}}
                                            <div x-data="{ isOpen: false }">
                                                {{-- Header toggle statistik --}}
                                                <button type="button"
                                                        @click="isOpen = !isOpen"
                                                        class="w-full text-left text-sm font-bold text-gray-900 mb-3 flex items-center justify-between">
                                                    <span class="flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Statistik Ringkas ({{ $selectedDesa }})
                                                    </span>
                                                    <span class="text-gray-500">
                                                        <svg x-show="isOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                        </svg>
                                                        <svg x-show="!isOpen" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </span>
                                                </button>

                                                {{-- Grid statistik --}}
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3"
                                                    x-show="isOpen"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 -translate-y-2"
                                                    x-transition:enter-end="opacity-100 translate-y-0"
                                                    x-transition:leave="transition ease-in duration-150"
                                                    x-transition:leave-start="opacity-100 translate-y-0"
                                                    x-transition:leave-end="opacity-0 -translate-y-2"
                                                    style="display: none;">
                                                    {{-- Total anak --}}
                                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-4 hover:shadow-md transition-shadow">
                                                        <p class="text-[10px] sm:text-xs font-bold text-blue-700 uppercase tracking-wide mb-2">
                                                            Total Anak
                                                        </p>
                                                        <p class="text-2xl sm:text-3xl font-bold text-blue-900">
                                                            {{ $detailStat->total_anak ?? 0 }}
                                                        </p>
                                                    </div>

                                                    {{-- Stunting --}}
                                                    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-4 hover:shadow-md transition-shadow">
                                                        <p class="text-[10px] sm:text-xs font-bold text-red-700 uppercase tracking-wide mb-2">
                                                            Stunting
                                                        </p>
                                                        <p class="text-2xl sm:text-3xl font-bold text-red-900">
                                                            {{ $detailStat->total_stunting ?? 0 }}
                                                        </p>
                                                        <p class="text-[10px] text-red-600 mt-1">TB/U &lt; -2</p>
                                                    </div>

                                                    {{-- Rata-rata tinggi --}}
                                                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200 p-4 hover:shadow-md transition-shadow">
                                                        <p class="text-[10px] sm:text-xs font-bold text-emerald-700 uppercase tracking-wide mb-2">
                                                            Avg Tinggi
                                                        </p>
                                                        <p class="text-2xl sm:text-3xl font-bold text-emerald-900">
                                                            {{ $detailStat && $detailStat->avg_tinggi ? number_format($detailStat->avg_tinggi, 1) : '-' }}
                                                        </p>
                                                        <p class="text-[10px] text-emerald-600 mt-1">cm</p>
                                                    </div>

                                                    {{-- Rata-rata ZS TB/U --}}
                                                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 p-4 hover:shadow-md transition-shadow">
                                                        <p class="text-[10px] sm:text-xs font-bold text-purple-700 uppercase tracking-wide mb-2">
                                                            Avg ZS TB/U
                                                        </p>
                                                        <div class="text-2xl sm:text-3xl font-bold text-purple-900 flex items-center h-9 sm:h-10">
                                                            @if ($detailStat && !is_null($detailStat->avg_zs_tbu))
                                                                <x-zscore-badge :value="$detailStat->avg_zs_tbu" /> 
                                                            @else
                                                                <span class="text-gray-400 text-2xl sm:text-3xl">-</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>                       

                                            {{-- Tabel data individu --}}
                                            <div>
                                                {{-- Header tabel + label periode --}}
                                                <div class="flex gap-2">
                                                    <h5 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        Data Individu
                                                    </h5>

                                                    <div class="text-sm text-gray-500">
                                                        {{ $currentPeriodLabel }}
                                                    </div>
                                                </div>

                                                {{-- Wrapper tabel + scroll --}}
                                                <div class="relative">
                                                    <div class="max-h-[64vh] md:max-h-[63vh] overflow-y-auto overflow-x-auto">
                                                        <table class="w-full min-w-[900px] text-sm text-left text-gray-700">
                                                            {{-- Header tabel sticky --}}
                                                            <thead class="sticky top-0 z-20 bg-white shadow-sm">
                                                                <tr class="border-b border-gray-200/80">
                                                                    <th class="px-3 md:px-4 py-3 text-left text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        No
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-left text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        Tanggal Input
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        Berat (kg)
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        Tinggi (cm)
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        BB/U
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        ZS BB/U
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        TB/U
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        ZS TB/U
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        BB/TB
                                                                    </th>
                                                                    <th class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                                        ZS BB/TB
                                                                    </th>
                                                                </tr>
                                                            </thead>

                                                            {{-- Body tabel --}}
                                                            <tbody class="bg-white divide-y divide-gray-100">
                                                                @forelse ($dataDetail as $row)
                                                                    <tr class="transition-colors even:bg-gray-50/60 hover:bg-emerald-50/60">
                                                                        {{-- Nomor urut global (pagination aware) --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-600 font-medium align-top">
                                                                            {{ $dataDetail->firstItem() + $loop->index }}
                                                                        </td>

                                                                        {{-- Tanggal input --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-700 align-top">
                                                                            {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}
                                                                        </td>

                                                                        {{-- Berat --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-900 text-center font-semibold align-top">
                                                                            {{ number_format($row->berat, 2) }}
                                                                        </td>

                                                                        {{-- Tinggi --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-900 text-center font-semibold align-top">
                                                                            {{ number_format($row->tinggi, 2) }}
                                                                        </td>

                                                                        {{-- Status BB/U --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                                            {{ $row->status_bb_u ?? '-' }}
                                                                        </td>

                                                                        {{-- Z-score BB/U --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                                            <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                                                <x-zscore-badge :value="$row->zs_bb_u" />
                                                                            </div>
                                                                        </td>

                                                                        {{-- Status TB/U --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                                            {{ $row->status_tb_u ?? '-' }}
                                                                        </td>

                                                                        {{-- Z-score TB/U --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                                            <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                                                <x-zscore-badge :value="$row->zs_tb_u" />
                                                                            </div>
                                                                        </td>

                                                                        {{-- Status BB/TB --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                                            {{ $row->status_bb_tb ?? '-' }}
                                                                        </td>

                                                                        {{-- Z-score BB/TB --}}
                                                                        <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                                            <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                                                <x-zscore-badge :value="$row->zs_bb_tb" />
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    {{-- Empty state tabel --}}
                                                                    <tr>
                                                                        <td colspan="10" class="px-6 py-10 text-center text-sm text-gray-500">
                                                                            <div class="flex flex-col items-center gap-3">
                                                                                <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                                                </svg>
                                                                                <div>
                                                                                    <p class="font-medium">
                                                                                        Tidak ada data individu untuk desa ini pada periode ini.
                                                                                    </p>
                                                                                    <p class="text-xs text-gray-400 mt-1">
                                                                                        Coba pilih periode lain atau pastikan data sudah diinput oleh divisi terkait.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                                {{-- Pagination bawaan Laravel --}}
                                                @if ($dataDetail && $dataDetail->hasPages())
                                                    <div class="mt-4">
                                                        {{ $dataDetail->links() }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>  
                            </div>
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-app-layout>