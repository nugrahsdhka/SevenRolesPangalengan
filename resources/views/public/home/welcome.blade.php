<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RSP | Home</title>

    <link rel="icon" href="{{ asset('img/logo-kab-bandung.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .preserve-lines {
            white-space: pre-line;
        }

        .glass-effect {
            backdrop-filter: blur(12px) saturate(180%);
            background: rgba(255, 255, 255, 0.08);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .gradient-mesh {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>

<body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100">
    <x-loading img="{{ asset('img/logo-kab-bandung.png') }}" ring="true" mode="dual" size="120" logo="70"
        ringThickness="7" ringColor="#8B0000" ringTrail="#E5E7EB" speed="0.65s" />

    {{-- ====== MODERN HEADER WITH GLASSMORPHISM ====== --}}
    <header class="text-red-100 relative overflow-hidden bg-gradient-to-br from-red-800 via-red-800 to-rose-700">

        {{-- Title paling atas --}}
        <div class="hidden">
            {{-- Desktop View --}}
            <div class="hidden md:block relative bg-red-800 py-3 border-b border-red-600/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm font-medium tracking-wide">
                        Sistem Informasi Data Stunting Kecamatan Pangalengan
                    </p>
                </div>
            </div>
    
            {{-- Mobile View --}}
            <div class="block md:hidden relative bg-red-800 py-3 border-b border-red-600/50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm font-medium tracking-wide">
                        Sistem Informasi
                    </p>
                    <p class="text-center text-sm font-medium tracking-wide">
                        Data Stunting Kecamatan Pangalengan
                    </p>
                </div>
            </div>
        </div>

        <div class="relative bg-red-800 py-3 border-b border-red-600/50">
            <div class="flex justify-center gap-3 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-10 h-10 md:w-5 md:h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <p class="text-center text-sm font-medium tracking-wide">
                    Web Masih Dalam Pengembangan, Data Yang Ditampilkan Adalah Data Dummy
                </p>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-10 h-10 md:w-5 md:h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
        </div>

        {{-- Main Header --}}
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">

                {{-- Left: Logo & Info --}}
                <div class="flex flex-col lg:flex-row items-center gap-6 group w-full">
                    <div class="relative flex-shrink-0">
                        <div
                            class="absolute inset-0 rounded-full bg-gradient-to-r from-red-400 to-rose-500 blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-spin-slow">
                        </div>
                        <div
                            class="relative bg-red-600 p-3 rounded-full ring-4 ring-red-600/50 flex items-center justify-center transition-all duration-500 group-hover:ring-8 group-hover:ring-red-400/30 group-hover:scale-110 group-hover:rotate-6">
                            <img class="w-16 h-16 object-contain transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-6"
                                src="{{ asset('img/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung">
                        </div>
                    </div>

                    <div
                        class="relative min-w-[160px] bg-red-600/40 backdrop-blur-sm rounded-2xl px-6 py-4 border border-red-500/30 overflow-hidden">
                        <h1 class="text-xl font-bold relative z-10 text-white">Rumah Stunting Pangalengan</h1>
                        <p class="text-sm text-red-100 mt-0.5 relative z-10">Kabupaten Bandung • Provinsi Jawa Barat</p>
                        <div class="flex gap-3 mt-3 text-sm relative z-10">
                            <span class="font-medium px-3 py-1 bg-white/10 rounded-lg">{{ $total_faskes ?? 0 }}
                                Faskes</span>
                            <span class="font-medium px-3 py-1 bg-white/10 rounded-lg">{{ $total_desa ?? 0 }}
                                Desa</span>
                            <span
                                class="block md:hidden font-medium px-3 py-1 bg-white/10 rounded-lg">{{ number_format($rata_rata_kasus ?? 0, 1) }}%
                                Stunting</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Stats Cards --}}
                <div class="hidden md:flex flex-col sm:flex-row gap-4 w-full lg:w-auto">

                    <div
                        class="relative bg-red-600/50 backdrop-blur-sm rounded-xl px-8 py-4 flex flex-col items-center justify-center flex-1 min-w-[140px] border border-red-500/30 overflow-hidden">
                        <p class="text-xs text-red-100 uppercase tracking-wide">Total Desa</p>
                        <p class="text-4xl font-bold mt-1 text-white leading-none">{{ $total_desa ?? 0 }}</p>
                    </div>

                    <div
                        class="relative bg-red-600/50 backdrop-blur-sm rounded-xl px-8 py-4 flex flex-col items-center justify-center flex-1 min-w-[140px] border border-red-500/30 overflow-hidden">
                        <p class="text-xs text-red-100 uppercase tracking-wide">Stunting (%)</p>
                        <p class="text-4xl font-bold mt-1 text-white leading-none">
                            {{ number_format($rata_rata_kasus ?? 0, 2) }}%</p>
                    </div>

                    <div
                        class="relative bg-red-600/50 backdrop-blur-sm rounded-xl px-8 py-4 flex flex-col items-center justify-center flex-1 min-w-[140px] border border-red-500/30 overflow-hidden">
                        <p class="text-xs text-red-100 uppercase tracking-wide">Faskes</p>
                        <p class="text-4xl font-bold mt-1 text-white leading-none">{{ $total_faskes ?? 0 }}</p>
                    </div>

                </div>

            </div>

            {{-- ===== DISTRIBUTION CHART ===== --}}
            <div class="mt-6">
                <div
                    class="relative bg-red-700/30 backdrop-blur-sm rounded-2xl p-5 border border-red-600/40 overflow-hidden">
                    {{-- Decorative gradient overlay --}}
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/5 to-transparent pointer-events-none">
                    </div>

                    {{-- Header --}}
                    <div class="relative flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-white flex items-center gap-2">
                                <svg class="w-2 h-2 md:w-4 md:h-4 text-red-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Distribusi Tinggi Badan
                            </h3>
                            <p class="text-xs text-red-200/80 mt-0.5">Tinggi Badan menurut Umur (TB/U)</p>
                        </div>
                        @if ($total_anak > 0)
                            <div
                                class="text-right bg-red-600/30 backdrop-blur-sm rounded-xl px-4 py-2 border border-red-500/30">
                                <div class="text-2xl text-center font-bold text-white">{{ number_format($total_anak) }}
                                </div>
                                <div class="text-[10px] text-red-200/80 uppercase tracking-wide">Total Anak</div>
                            </div>
                        @endif
                    </div>

                    {{-- ===== LOGIKA IF/ELSE DIMULAI DI SINI ===== --}}
                    @if ($total_anak > 0)

                        <div x-data="{ activeCategory: null }" @mouseleave="activeCategory = null">

                            {{-- Bar Chart (Jika ADA Data) --}}
                            <div class="relative mb-4">
                                <div
                                    class="relative w-full bg-red-950/40 rounded-lg h-2.5 overflow-hidden flex border border-red-700/40 shadow-inner">
                                    @php
                                        // (PHP Anda tetap sama)
                                        $distSangatPendek = number_format($distribusi['sangat_pendek'] ?? 0, 2);
                                        $distPendek = number_format($distribusi['pendek'] ?? 0, 2);
                                        $distNormal = number_format($distribusi['normal'] ?? 0, 2);
                                        $distTinggi = number_format($distribusi['tinggi'] ?? 0, 2);
                                    @endphp

                                    @if (($distribusi['sangat_pendek'] ?? 0) > 0)
                                        <div @mouseenter="activeCategory = 'sangat_pendek'" {{-- 2. Tambahkan event mouseenter --}}
                                            {{-- 3. Ganti class hover: dengan :class dinamis --}}
                                            :class="{
                                                'from-red-400 to-red-500': activeCategory === 'sangat_pendek',
                                                'from-red-500 to-red-600': activeCategory !== 'sangat_pendek'
                                            }"
                                            class="relative bg-gradient-to-r h-2.5 transition-all duration-300"
                                            {{-- Hapus hover: --}}
                                            style="width: {{ $distribusi['sangat_pendek'] ?? 0 }}%">
                                            <div class="absolute inset-0 bg-white/10"></div>
                                        </div>
                                    @endif

                                    @if (($distribusi['pendek'] ?? 0) > 0)
                                        <div @mouseenter="activeCategory = 'pendek'" {{-- 2. Tambahkan event mouseenter --}}
                                            :class="{
                                                'from-orange-300 to-orange-400': activeCategory === 'pendek',
                                                'from-orange-400 to-orange-500': activeCategory !== 'pendek'
                                            }"
                                            class="relative bg-gradient-to-r h-2.5 transition-all duration-300"
                                            {{-- Hapus hover: --}} style="width: {{ $distribusi['pendek'] ?? 0 }}%">
                                            <div class="absolute inset-0 bg-white/10"></div>
                                        </div>
                                    @endif

                                    @if (($distribusi['normal'] ?? 0) > 0)
                                        <div @mouseenter="activeCategory = 'normal'" {{-- 2. Tambahkan event mouseenter --}}
                                            :class="{
                                                'from-emerald-300 to-emerald-400': activeCategory === 'normal',
                                                'from-emerald-400 to-emerald-500': activeCategory !== 'normal'
                                            }"
                                            class="relative bg-gradient-to-r h-2.5 transition-all duration-300"
                                            {{-- Hapus hover: --}} style="width: {{ $distribusi['normal'] ?? 0 }}%">
                                            <div class="absolute inset-0 bg-white/10"></div>
                                        </div>
                                    @endif

                                    @if (($distribusi['tinggi'] ?? 0) > 0)
                                        <div @mouseenter="activeCategory = 'tinggi'" {{-- 2. Tambahkan event mouseenter --}}
                                            :class="{
                                                'from-blue-300 to-blue-400': activeCategory === 'tinggi',
                                                'from-blue-400 to-blue-500': activeCategory !== 'tinggi'
                                            }"
                                            class="relative bg-gradient-to-r h-2.5 transition-all duration-300"
                                            {{-- Hapus hover: --}} style="width: {{ $distribusi['tinggi'] ?? 0 }}%">
                                            <div class="absolute inset-0 bg-white/10"></div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Legend Cards (Jika ADA Data) --}}
                            <div class="relative grid grid-cols-2 lg:grid-cols-4 gap-2.5">

                                {{-- Sangat Pendek --}}
                                <div @mouseenter="activeCategory = 'sangat_pendek'" {{-- 2. Tambahkan event mouseenter --}}
                                    {{-- 3. Ganti class hover: dengan :class dinamis --}}
                                    :class="{
                                        'bg-white/15 border-white/30 scale-[1.02]': activeCategory === 'sangat_pendek',
                                        'bg-white/10 border-white/20': activeCategory !== 'sangat_pendek'
                                    }"
                                    class="group relative backdrop-blur-sm rounded-xl p-3 border transition-all duration-300">
                                    {{-- Hapus hover: --}}

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-2 h-2 md:w-4 md:h-4 rounded-full bg-red-500 shadow-lg shadow-red-500/50 ring-2 ring-white/20">
                                            </div>
                                            <div>
                                                <div
                                                    class="hidden md:block text-[10px] md:text-[13px] font-bold text-white">
                                                    Sangat Pendek</div>
                                                <div
                                                    class="block md:hidden text-[10px] md:text-[13px] font-bold text-white">
                                                    Sgt. Pendek</div>
                                                <div class="text-[9px] md:text-[11px] text-red-200/70 mt-0.5">
                                                    {{ number_format($distribusi_counts['sangat_pendek'] ?? 0) }} anak
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-[10px] md:text-base font-bold text-white">
                                            {{ $distSangatPendek }}%</div>
                                    </div>
                                </div>

                                {{-- Pendek --}}
                                <div @mouseenter="activeCategory = 'pendek'" {{-- 2. Tambahkan event mouseenter --}}
                                    :class="{
                                        'bg-white/15 border-white/30 scale-[1.02]': activeCategory === 'pendek',
                                        'bg-white/10 border-white/20': activeCategory !== 'pendek'
                                    }"
                                    class="group relative backdrop-blur-sm rounded-xl p-3 border transition-all duration-300">
                                    {{-- Hapus hover: --}}

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-2 h-2 md:w-4 md:h-4 rounded-full bg-orange-400 shadow-lg shadow-orange-400/50 ring-2 ring-white/20">
                                            </div>
                                            <div>
                                                <div class="text-[10px] md:text-[13px] font-bold text-white">Pendek
                                                </div>
                                                <div class="text-[9px] md:text-[11px] text-red-200/70 mt-0.5">
                                                    {{ number_format($distribusi_counts['pendek'] ?? 0) }} anak
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-[10px] md:text-base font-bold text-white">
                                            {{ $distPendek }}%</div>
                                    </div>
                                </div>

                                {{-- Normal --}}
                                <div @mouseenter="activeCategory = 'normal'" {{-- 2. Tambahkan event mouseenter --}}
                                    :class="{
                                        'bg-white/15 border-white/30 scale-[1.02]': activeCategory === 'normal',
                                        'bg-white/10 border-white/20': activeCategory !== 'normal'
                                    }"
                                    class="group relative backdrop-blur-sm rounded-xl p-3 border transition-all duration-300">
                                    {{-- Hapus hover: --}}

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-2 h-2 md:w-4 md:h-4 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/50 ring-2 ring-white/20">
                                            </div>
                                            <div>
                                                <div class="text-[10px] md:text-[13px] font-bold text-white">Normal
                                                </div>
                                                <div class="text-[9px] md:text-[11px] text-red-200/70 mt-0.5">
                                                    {{ number_format($distribusi_counts['normal'] ?? 0) }} anak
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-[10px] md:text-base font-bold text-white">
                                            {{ $distNormal }}%</div>
                                    </div>
                                </div>

                                {{-- Tinggi --}}
                                <div @mouseenter="activeCategory = 'tinggi'" {{-- 2. Tambahkan event mouseenter --}}
                                    :class="{
                                        'bg-white/15 border-white/30 scale-[1.02]': activeCategory === 'tinggi',
                                        'bg-white/10 border-white/20': activeCategory !== 'tinggi'
                                    }"
                                    class="group relative backdrop-blur-sm rounded-xl p-3 border transition-all duration-300">
                                    {{-- Hapus hover: --}}

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-2 h-2 md:w-4 md:h-4 rounded-full bg-blue-500 shadow-lg shadow-blue-500/50 ring-2 ring-white/20">
                                            </div>
                                            <div>
                                                <div class="text-[10px] md:text-[13px] font-bold text-white">Tinggi
                                                </div>
                                                <div class="text-[9px] md:text-[11px] text-red-200/70 mt-0.5">
                                                    {{ number_format($distribusi_counts['tinggi'] ?? 0) }} anak
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-[10px] md:text-base font-bold text-white">
                                            {{ $distTinggi }}%</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @else
                        {{-- Tampilan Bar Chart (Jika TIDAK Ada Data) --}}
                        <div class="relative mb-4">
                            <div
                                class="relative w-full bg-red-950/40 rounded-lg h-2.5 overflow-hidden border border-dashed border-red-700/50">
                            </div>
                        </div>

                        {{-- Tampilan Legend (Jika TIDAK Ada Data) --}}
                        <div class="relative grid grid-cols-2 lg:grid-cols-4 gap-2.5 opacity-50">
                            <div class="bg-white/5 backdrop-blur-sm rounded-xl p-3 border border-white/10">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 md:w-4 md:h-4 rounded-full bg-red-300/30"></div>
                                        <div>
                                            <div class="text-[10px] md:text-[13px] font-bold text-red-200/50">Sangat
                                                Pendek</div>
                                            <div class="text-[9px] md:text-[11px] text-red-200/30 mt-0.5">— anak</div>
                                        </div>
                                    </div>
                                    <div class="text-[10px] md:text-base font-bold text-red-200/50">—</div>
                                </div>
                            </div>

                            <div class="bg-white/5 backdrop-blur-sm rounded-xl p-3 border border-white/10">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 md:w-4 md:h-4 rounded-full bg-red-300/30"></div>
                                        <div>
                                            <div class="text-[10px] md:text-[13px] font-bold text-red-200/50">Pendek
                                            </div>
                                            <div class="text-[9px] md:text-[11px] text-red-200/30 mt-0.5">— anak</div>
                                        </div>
                                    </div>
                                    <div class="text-[10px] md:text-base font-bold text-red-200/50">—</div>
                                </div>
                            </div>

                            <div class="bg-white/5 backdrop-blur-sm rounded-xl p-3 border border-white/10">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 md:w-4 md:h-4 rounded-full bg-red-300/30"></div>
                                        <div>
                                            <div class="text-[10px] md:text-[13px] font-bold text-red-200/50">Normal
                                            </div>
                                            <div class="text-[9px] md:text-[11px] text-red-200/30 mt-0.5">— anak</div>
                                        </div>
                                    </div>
                                    <div class="text-[10px] md:text-base font-bold text-red-200/50">—</div>
                                </div>
                            </div>

                            <div class="bg-white/5 backdrop-blur-sm rounded-xl p-3 border border-white/10">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 md:w-4 md:h-4 rounded-full bg-red-300/30"></div>
                                        <div>
                                            <div class="text-[10px] md:text-[13px] font-bold text-red-200/50">Tinggi
                                            </div>
                                            <div class="text-[9px] md:text-[11px] text-red-200/30 mt-0.5">— anak</div>
                                        </div>
                                    </div>
                                    <div class="text-[10px] md:text-base font-bold text-red-200/50">—</div>
                                </div>
                            </div>
                        </div>

                    @endif
                    {{-- ===== AKHIR LOGIKA IF/ELSE ===== --}}
                </div>
            </div>

        </div>
        </div>

        <style>
            @keyframes spin-slow {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            .animate-spin-slow {
                animation: spin-slow 8s linear infinite;
            }

            @keyframes scan {
                0% {
                    transform: translateX(-100%);
                }

                100% {
                    transform: translateX(1000%);
                }
            }

            .animate-scan {
                animation: scan 2s ease-in-out infinite;
            }
        </style>
    </header>

    {{-- ====== MODERN NAVIGATION WITH GLASS EFFECT ====== --}}
    @include('layouts.nav_public')

    {{-- ====== MODERN CONTENT CARDS (MENAMPILKAN DATA) ====== --}}
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Grid Kartu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- 1. Kartu Data Stunting --}}
            <a href="{{ route('data-stunting') }}"
                class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col">

                {{-- Header Kartu (Merah) --}}
                <div class="p-5 bg-gradient-to-r from-red-50 to-red-100/60 border-b border-red-200/50">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 p-3 bg-white rounded-full shadow-md border border-red-100">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Data Stunting
                            </h3>
                            <p class="text-sm text-red-700/80 font-medium">
                                Tabel & Chart Agregat Stunting
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Body Kartu (Putih) --}}
                <div class="p-5">
                    <p class="text-sm text-gray-600 mb-3">
                        Total kasus stunting (ZS TB/U &lt; -2 SD) dan total anak terukur dari seluruh desa:
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        {{-- Stat 1 --}}
                        <div class="flex-1 bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Kasus Stunting
                            </p>
                            <p class="text-3xl font-bold text-red-600">
                                {{ number_format($total_stunting) }}
                            </p>
                            <p class="text-sm font-medium text-gray-700">
                                anak
                            </p>
                        </div>
                        {{-- Stat 2 --}}
                        <div class="flex-1 bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Anak Terukur
                            </p>
                            <p class="text-3xl font-bold text-gray-800">
                                {{ number_format($total_anak) }}
                            </p>
                            <p class="text-sm font-medium text-gray-700">
                                anak
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Footer Kartu (Putih) --}}
                <div class="px-5 pb-5 mt-auto">
                    <div class="flex items-center justify-between text-sm font-medium text-red-600">
                        <span>Lihat Data Stunting</span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- 2. Kartu Data Wilayah --}}
            <a href="{{ route('data-wilayah') }}"
                class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col">

                {{-- Header Kartu (Merah) --}}
                <div class="p-5 bg-gradient-to-r from-red-50 to-red-100/60 border-b border-red-200/50">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 p-3 bg-white rounded-full shadow-md border border-red-100">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Data Wilayah
                            </h3>
                            <p class="text-sm text-red-700/80 font-medium">
                                Rata-rata Z-Score (Semua Indikator)
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Body Kartu (Putih) --}}
                <div class="p-5 space-y-3">
                    <p class="text-sm text-gray-600">
                        Rata-rata Z-Score gabungan dari seluruh anak yang terdata di Kecamatan Pangalengan:
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-center">
                        {{-- ZS BB/U --}}
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase">BB/U</p>
                            <p class="text-xl font-bold text-gray-800">
                                {{ number_format($avgZScores->avg_bbu ?? 0, 2) }}
                            </p>
                        </div>
                        {{-- ZS TB/U --}}
                        <div class="bg-red-50 rounded-xl p-3 border border-red-200">
                            <p class="text-xs font-semibold text-red-700 uppercase">TB/U</p>
                            <p class="text-xl font-bold text-red-600">
                                {{ number_format($avgZScores->avg_tbu ?? 0, 2) }}
                            </p>
                        </div>
                        {{-- ZS BB/TB --}}
                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-200">
                            <p class="text-xs font-semibold text-gray-500 uppercase">BB/TB</p>
                            <p class="text-xl font-bold text-gray-800">
                                {{ number_format($avgZScores->avg_bbtb ?? 0, 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Footer Kartu (Putih) --}}
                <div class="px-5 pb-5 mt-auto">
                    <div class="flex items-center justify-between text-sm font-medium text-red-600">
                        <span>Lihat Data Wilayah</span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- 3. Kartu Analisis Hotspot --}}
            <a href="{{ route('analisis-hotspot') }}"
                class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col">

                {{-- Header Kartu (Merah) --}}
                <div class="p-5 bg-gradient-to-r from-red-50 to-red-100/60 border-b border-red-200/50">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 p-3 bg-white rounded-full shadow-md border border-red-100">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Analisis Hotspot
                            </h3>
                            <p class="text-sm text-red-700/80 font-medium">
                                Peta Persebaran Z-Score TB/U
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Body Kartu (Putih) --}}
                <div class="p-5 space-y-3">
                    <p class="text-sm text-gray-600">
                        Desa dengan rata-rata Z-Score TB/U terendah (paling rawan) saat ini adalah:
                    </p>
                    @if ($desaTerawan)
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Desa Terawan</p>
                            <p class="text-2xl font-bold text-red-600 truncate">
                                {{ $desaTerawan->desa_kelurahan }}
                            </p>
                            <p class="text-sm font-medium text-gray-700">
                                Rata-rata ZS: <strong
                                    class="text-red-600">{{ number_format($desaTerawan->avg_zs_tbu, 2) }} SD</strong>
                            </p>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 text-center">
                            <p class="text-sm text-gray-500">Data desa rawan belum tersedia.</p>
                        </div>
                    @endif
                </div>

                {{-- Footer Kartu (Putih) --}}
                <div class="px-5 pb-5 mt-auto">
                    <div class="flex items-center justify-between text-sm font-medium text-red-600">
                        <span>Lihat Analisis Hotspot</span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </a>

            {{-- 4. Kartu Peta Faskes --}}
            <a href="{{ route('peta-faskes') }}"
                class="group relative bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col">

                {{-- Header Kartu (Merah) --}}
                <div class="p-5 bg-gradient-to-r from-red-50 to-red-100/60 border-b border-red-200/50">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 p-3 bg-white rounded-full shadow-md border border-red-100">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                <circle cx="12" cy="10" r="3" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round"></circle>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                Peta Faskes
                            </h3>
                            <p class="text-sm text-red-700/80 font-medium">
                                Lokasi Fasilitas Kesehatan
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Body Kartu (Putih) --}}
                <div class="p-5 space-y-3">
                    <p class="text-sm text-gray-600">
                        Kami telah memetakan lokasi fasilitas kesehatan (Puskesmas, Klinik, Rumah Sakit, dll) di seluruh
                        Kecamatan Pangalengan.
                    </p>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Faskes Terdaftar</p>
                        <p class="text-3xl font-bold text-red-600">
                            {{ number_format($total_faskes) }}
                        </p>
                        <p class="text-sm font-medium text-gray-700">
                            Lokasi
                        </p>
                    </div>
                </div>

                {{-- Footer Kartu (Putih) --}}
                <div class="px-5 pb-5 mt-auto">
                    <div class="flex items-center justify-between text-sm font-medium text-red-600">
                        <span>Lihat Peta Faskes</span>
                        <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </a>

        </div>
    </main>

    {{-- Custom Scrollbar Styles --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #e11d48, #f43f5e);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #be123c, #e11d48);
        }
    </style>

    <x-goToTop />

    <footer class="text-xs text-gray-500 text-center my-4">
        &copy; {{ date('Y') }} Rumah Stunting Pangalengan. All rights reserved.
    </footer>
</body>

</html>
