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

    @include('public.home.header')

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
