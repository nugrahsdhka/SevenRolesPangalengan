<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Stunting — Pangalengan</title>

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
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
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

    {{-- ====== MODERN HEADER WITH GLASSMORPHISM ====== --}}
    <header class="text-red-100 relative overflow-hidden bg-gradient-to-br from-red-800 via-red-800 to-rose-700">
        {{-- Top Bar --}}

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

        {{-- Main Header --}}
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- ✅ PERBAIKAN: flex-col lg:flex-row untuk menumpuk di mobile --}}
            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
                
                {{-- Left: Logo & Info --}}
                <div class="flex flex-col md:flex-row items-center gap-6 group w-full">
                    <div class="relative flex-shrink-0">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-red-400 to-rose-500 blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-spin-slow"></div>
                        <div class="relative bg-red-600 p-3 rounded-full ring-4 ring-red-600/50 flex items-center justify-center transition-all duration-500 group-hover:ring-8 group-hover:ring-red-400/30 group-hover:scale-110 group-hover:rotate-6">
                            <img class="w-16 h-16 object-contain transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-6" 
                                src="{{ asset('img/logo-kab-bandung.png') }}" 
                                alt="Logo Kabupaten Bandung">
                        </div>
                    </div>

                    <div class="relative min-w-[140px] bg-red-600/40 backdrop-blur-sm rounded-2xl px-6 py-4 border border-red-500/30 overflow-hidden">
                        <h1 class="text-xl font-bold relative z-10 text-white">Rumah Stunting Pangalengan</h1>
                        <p class="text-sm text-red-100 mt-0.5 relative z-10">Kabupaten Bandung • Provinsi Jawa Barat</p>
                        <div class="flex gap-3 mt-3 text-sm relative z-10">
                            <span class="font-medium px-3 py-1 bg-white/10 rounded-lg">5 Faskes</span>
                            <span class="font-medium px-3 py-1 bg-white/10 rounded-lg">13 Desa Terpetakan</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Stats Cards --}}
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    
                    <div class="relative bg-red-600/50 backdrop-blur-sm rounded-xl px-8 py-4 text-center flex-1 min-w-[140px] border border-red-500/30 overflow-hidden">
                        <p class="text-xs text-red-100 uppercase">Total Desa</p>
                        <p class="text-4xl font-bold mt-1 text-white">13</p>
                    </div>

                    <div class="relative bg-red-600/50 backdrop-blur-sm rounded-xl px-8 py-4 text-center flex-1 min-w-[140px] border border-red-500/30 overflow-hidden">
                        <p class="text-xs text-red-100 uppercase">Rata-rata (%)</p>
                        <p class="text-4xl font-bold mt-1 text-white">27.8%</p>
                    </div>

                    <div class="relative bg-red-600/50 backdrop-blur-sm rounded-xl px-8 py-4 text-center flex-1 min-w-[140px] border border-red-500/30 overflow-hidden">
                        <p class="text-xs text-red-100 uppercase">Faskes</p>
                        <p class="text-4xl font-bold mt-1 text-white">5</p>
                    </div>
                    
                </div>

            </div>

            {{-- Bottom Section: Chart & Filter --}}
            {{-- ✅ PERBAIKAN: flex-col lg:flex-row untuk menumpuk di mobile --}}
            <div class="mt-8 flex flex-col lg:flex-row items-start lg:items-end justify-between gap-6">
                
                {{-- Distribution Chart --}}
                <div class="flex-1 w-full group/chart">
                    <p class="text-sm font-medium mb-2 transition-all duration-300 group-hover/chart:text-red-50">Distribusi Tingkat</p>
                    <div class="hidden relative w-full bg-gray-700 rounded-full h-5 overflow-hidden md:flex shadow-lg transition-all duration-500 group-hover/chart:h-6 group-hover/chart:shadow-2xl group-hover/chart:shadow-red-500/30">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/chart:translate-x-full transition-transform duration-2000"></div>
                        <div class="relative bg-red-600 h-5 transition-all duration-500 group-hover/chart:h-6 group-hover/chart:bg-red-500 flex items-center justify-center" style="width: 54%"><span class="text-[10px] font-bold opacity-0 group-hover/chart:opacity-100 transition-opacity duration-300">54%</span></div>
                        <div class="relative bg-orange-500 h-5 transition-all duration-500 group-hover/chart:h-6 group-hover/chart:bg-orange-400 flex items-center justify-center" style="width: 31%"><span class="text-[10px] font-bold opacity-0 group-hover/chart:opacity-100 transition-opacity duration-300">31%</span></div>
                        <div class="relative bg-yellow-400 h-5 transition-all duration-500 group-hover/chart:h-6 group-hover/chart:bg-yellow-300 flex items-center justify-center" style="width: 15%"><span class="text-[10px] font-bold opacity-0 group-hover/chart:opacity-100 transition-opacity duration-300">15%</span></div>
                        <div class="relative bg-green-500 h-5 transition-all duration-500 group-hover/chart:h-6 group-hover/chart:bg-green-400" style="width: 0%"></div>
                    </div>

                    <div class="relative w-full bg-gray-700 rounded-full h-5 overflow-hidden flex shadow-lg transition-all duration-500 group-hover/chart:h-6 group-hover/chart:shadow-2xl group-hover/chart:shadow-red-500/30 md:hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/chart:translate-x-full transition-transform duration-2000"></div>
                        
                        <div class="relative bg-red-600 h-5 transition-all duration-500 group-hover/chart:h-6 group-hover/chart:bg-red-500 flex items-center justify-center" style="width: 54%">
                            <span class="text-[10px] font-bold text-white transition-opacity duration-300">54%</span>
                        </div>
                        
                        <div class="relative bg-orange-500 h-5 transition-all duration-500 group-hover/chart:h-6 group-hover/chart:bg-orange-400 flex items-center justify-center" style="width: 31%">
                            <span class="text-[10px] font-bold text-white transition-opacity duration-300">31%</span>
                        </div>
                        
                        <div class="relative bg-yellow-400 h-5 transition-all duration-500 group-hover/chart:h-6 group-hover/chart:bg-yellow-300 flex items-center justify-center" style="width: 15%">
                            <span class="text-[10px] font-bold text-white transition-opacity duration-300">15%</span>
                        </div>
                        
                        <div class="relative bg-green-500 h-5 transition-all duration-500 group-hover/chart:h-6 group-hover/chart:bg-green-400" style="width: 0%"></div>
                    </div>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm">
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 md:w-3 md:h-3 rounded-sm bg-red-600"></div>
                            <span class="text-xs">High 54%</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 md:w-3 md:h-3 rounded-sm bg-orange-500"></div>
                            <span class="text-xs">Medium 31%</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 md:w-3 md:h-3 rounded-sm bg-yellow-400"></div>
                            <span class="text-xs">Low 15%</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="w-2 h-2 md:w-3 md:h-3 rounded-sm bg-green-500"></div>
                            <span class="text-xs">Not Sig. 0%</span>
                        </div>
                    </div>
                </div>

                {{-- Date Filter --}}
                <div class="group/filter relative flex items-center gap-3 bg-red-600/30 backdrop-blur-sm rounded-xl px-4 py-3 border border-red-500/30 transition-all duration-500 hover:bg-red-600/50 hover:border-red-400/60 hover:shadow-2xl hover:shadow-red-500/20 overflow-hidden w-full lg:w-auto">
                    <input type="month" 
                        class="relative z-10 bg-white text-gray-900 text-sm rounded-lg px-3 py-2 border-0 focus:ring-2 focus:ring-red-400 transition-all duration-300 hover:shadow-lg hover:scale-105 flex-1" 
                        placeholder="Pilih Bulan">
                    <button type="button" 
                            class="relative z-10 px-6 py-2 bg-white text-red-700 text-sm font-semibold rounded-lg transition-all duration-300 hover:bg-red-50 hover:scale-110 hover:shadow-xl hover:shadow-red-500/30 active:scale-95 whitespace-nowrap overflow-hidden group/btn flex-shrink-0">
                        <span class="absolute inset-0 -translate-x-full group-hover/btn:translate-x-full transition-transform duration-700 bg-gradient-to-r from-transparent via-red-200/50 to-transparent"></span>
                        <span class="relative z-10">Terapkan</span>
                    </button>
                </div>

            </div>
        </div>

        <style>
            @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            .animate-spin-slow { animation: spin-slow 8s linear infinite; }
            @keyframes scan { 0% { transform: translateX(-100%); } 100% { transform: translateX(1000%); } }
            .animate-scan { animation: scan 2s ease-in-out infinite; }
        </style>
    </header>

    {{-- ====== MODERN NAVIGATION WITH GLASS EFFECT ====== --}}
    @include('layouts.nav_public')

    {{-- ====== MODERN CONTENT CARDS ====== --}}
    <main class="py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-2">
                
                {{-- DATA GIZI - Enhanced Card --}}
                @if($publishedData['gizi']->isNotEmpty())
                    <section class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-xl ring-1 ring-gray-900/5 transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">
                        <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br from-red-500/10 to-rose-600/10 blur-3xl transition-all duration-500 group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="mb-8 flex items-center gap-4">
                                <div class="relative">
                                    <div class="absolute inset-0 animate-ping rounded-2xl bg-red-500/20"></div>
                                    <span class="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg ring-4 ring-red-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-gray-900">Informasi Gizi</h3>
                                    <p class="text-sm text-gray-500 font-medium">Edukasi & Panduan Nutrisi</p>
                                </div>
                            </div>
                            <div class="space-y-4 max-h-[420px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($publishedData['gizi'] as $data)
                                    <div class="group/item relative overflow-hidden rounded-2xl border border-gray-100 bg-gradient-to-br from-gray-50 to-white p-5 transition-all duration-300 hover:border-red-200 hover:shadow-lg hover:scale-[1.02]">
                                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-red-500/5 to-transparent rounded-full blur-2xl transition-opacity opacity-0 group-hover/item:opacity-100"></div>
                                        <h4 class="font-bold text-gray-900 text-base flex items-start gap-2">
                                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                            </svg>
                                            {{ $data->judul }}
                                        </h4>
                                        <p class="mt-3 text-sm leading-relaxed text-gray-600 preserve-lines">{{ $data->deskripsi }}</p>
                                        <div class="mt-4 inline-flex items-center gap-2 rounded-lg bg-red-50 px-3 py-1.5 text-xs font-semibold text-red-700">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                            </svg>
                                            {{ $data->periode->format('F Y') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                
                {{-- DATA PENCEGAHAN - Enhanced Card --}}
                @if($publishedData['pencegahan']->isNotEmpty())
                    <section class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-xl ring-1 ring-gray-900/5 transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">
                        <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br from-rose-500/10 to-pink-600/10 blur-3xl transition-all duration-500 group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="mb-8 flex items-center gap-4">
                                <div class="relative">
                                    <div class="absolute inset-0 animate-ping rounded-2xl bg-rose-500/20"></div>
                                    <span class="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 text-white shadow-lg ring-4 ring-rose-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-gray-900">Kegiatan Pencegahan</h3>
                                    <p class="text-sm text-gray-500 font-medium">Program & Intervensi Aktif</p>
                                </div>
                            </div>
                            <div class="space-y-4 max-h-[420px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($publishedData['pencegahan'] as $data)
                                    <div class="group/item relative overflow-hidden rounded-2xl border border-gray-100 bg-gradient-to-br from-gray-50 to-white p-5 transition-all duration-300 hover:border-rose-200 hover:shadow-lg hover:scale-[1.02]">
                                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-rose-500/5 to-transparent rounded-full blur-2xl transition-opacity opacity-0 group-hover/item:opacity-100"></div>
                                        <h4 class="font-bold text-gray-900 text-base flex items-start gap-2">
                                            <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v2a2 2 0 100 4v2a2 2 0 01-2 2H4a2 2 0 01-2-2v-2a2 2 0 100-4V6z"/>
                                            </svg>
                                            {{ $data->judul_kegiatan }}
                                        </h4>
                                        <p class="mt-3 text-sm leading-relaxed text-gray-600">{{ $data->laporan_singkat }}</p>
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                {{ $data->lokasi }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                                </svg>
                                                {{ \Carbon\Carbon::parse($data->tanggal_kegiatan)->format('d M Y') }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                                                {{ $data->periode->format('F Y') }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif

                {{-- DATA PENANGGULANGAN - Enhanced Card --}}
                @if($publishedData['penanggulangan']->isNotEmpty())
                    <section class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-xl ring-1 ring-gray-900/5 transition-all duration-500 hover:shadow-2xl hover:-translate-y-1">
                        <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br from-orange-500/10 to-red-600/10 blur-3xl transition-all duration-500 group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="mb-8 flex items-center gap-4">
                                <div class="relative">
                                    <div class="absolute inset-0 animate-ping rounded-2xl bg-orange-500/20"></div>
                                    <span class="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 text-white shadow-lg ring-4 ring-orange-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-gray-900">Laporan Penanggulangan</h3>
                                    <p class="text-sm text-gray-500 font-medium">Respons & Mitigasi Bencana</p>
                                </div>
                            </div>
                            <div class="space-y-4 max-h-[420px] overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($publishedData['penanggulangan'] as $data)
                                    <div class="group/item relative overflow-hidden rounded-2xl border border-gray-100 bg-gradient-to-br from-gray-50 to-white p-5 transition-all duration-300 hover:border-orange-200 hover:shadow-lg hover:scale-[1.02]">
                                        <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-orange-500/5 to-transparent rounded-full blur-2xl transition-opacity opacity-0 group-hover/item:opacity-100"></div>
                                        <div class="flex items-start justify-between gap-3">
                                            <h4 class="font-bold text-gray-900 text-base flex items-start gap-2 flex-1">
                                                <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                                </svg>
                                                {{ $data->jenis_bencana }}
                                            </h4>
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-gradient-to-r from-red-500 to-red-600 px-4 py-1.5 text-xs font-bold text-white shadow-lg ring-2 ring-red-100">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                                </svg>
                                                {{ $data->jumlah_korban }} Korban
                                            </span>
                                        </div>
                                        <p class="mt-3 text-sm leading-relaxed text-gray-600">{{ $data->tindakan }}</p>
                                        <div class="mt-4 inline-flex items-center gap-2 rounded-lg bg-orange-50 px-3 py-1.5 text-xs font-semibold text-orange-700">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                            </svg>
                                            {{ $data->periode->format('F Y') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                @endif
                
                {{-- DATA DESA - Enhanced Carousel Card --}}
                @if($publishedData['desa']->isNotEmpty())
                    <section 
                        x-data="{
                            items: {{ Js::from($publishedData['desa']) }},
                            currentIndex: 0,
                            get currentItem() { return this.items[this.currentIndex]; },
                            next() { this.currentIndex = this.currentIndex === this.items.length - 1 ? 0 : this.currentIndex + 1; },
                            previous() { this.currentIndex = this.currentIndex === 0 ? this.items.length - 1 : this.currentIndex - 1; }
                        }"
                        class="group relative overflow-hidden rounded-3xl bg-white p-8 shadow-xl ring-1 ring-gray-900/5 transition-all duration-500 hover:shadow-2xl hover:-translate-y-1"
                    >
                        <div class="absolute -right-12 -top-12 h-32 w-32 rounded-full bg-gradient-to-br from-emerald-500/10 to-teal-600/10 blur-3xl transition-all duration-500 group-hover:scale-150"></div>
                        <div class="relative">
                            <div class="mb-8 flex items-center gap-4">
                                <div class="relative">
                                    <div class="absolute inset-0 animate-ping rounded-2xl bg-emerald-500/20"></div>
                                    <span class="relative inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-lg ring-4 ring-emerald-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21m0 0h4.5V3.545M12.75 21h7.5V10.75M2.25 21h1.5m18 0h-18M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m-3-1l-1.5.545m0 0l-2.25 1.318m5.25-1.75l-5.25 1.75" />
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-extrabold text-gray-900">Data Desa</h3>
                                    <p class="text-sm text-gray-500 font-medium">Profil & Statistik Wilayah</p>
                                </div>
                            </div>
                            <div class="min-h-[360px]">
                                <template x-if="items.length > 0">
                                    <div x-show="items.length > 0" 
                                         x-transition:enter="transition ease-out duration-300" 
                                         x-transition:enter-start="opacity-0 transform translate-x-8" 
                                         x-transition:enter-end="opacity-100 transform translate-x-0">
                                        <div class="mb-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 p-6 ring-1 ring-emerald-200/50">
                                            <h4 class="text-2xl font-black text-gray-900 flex items-center gap-2" x-text="currentItem.nama_desa">
                                            </h4>
                                            <div class="mt-1 inline-flex items-center gap-1.5 rounded-lg bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/>
                                                </svg>
                                                Kecamatan Pangalengan
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                            <div class="group/stat rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 p-5 ring-1 ring-blue-200/50 transition-all hover:shadow-lg hover:scale-105">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500 text-white shadow-md">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                                        </svg>
                                                    </div>
                                                    <p class="text-xs font-bold text-blue-900 uppercase tracking-wide">Penduduk</p>
                                                </div>
                                                <p class="text-2xl font-black text-blue-900" x-text="new Intl.NumberFormat('id-ID').format(currentItem.jumlah_penduduk)"></p>
                                                <p class="text-xs text-blue-700 mt-1 font-medium">Total Jiwa</p>
                                            </div>
                                            <div class="group/stat rounded-2xl bg-gradient-to-br from-red-50 to-red-100 p-5 ring-1 ring-red-200/50 transition-all hover:shadow-lg hover:scale-105">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500 text-white shadow-md">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                                                        </svg>
                                                    </div>
                                                    <p class="text-xs font-bold text-red-900 uppercase tracking-wide">Stunting</p>
                                                </div>
                                                <p class="text-2xl font-black text-red-600" x-text="new Intl.NumberFormat('id-ID').format(currentItem.jumlah_kasus_stunting)"></p>
                                                <p class="text-xs text-red-700 mt-1 font-medium">Kasus Terdaftar</p>
                                            </div>
                                            <div class="group/stat rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100 p-5 ring-1 ring-emerald-200/50 transition-all hover:shadow-lg hover:scale-105">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-md">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z"/>
                                                        </svg>
                                                    </div>
                                                    <p class="text-xs font-bold text-emerald-900 uppercase tracking-wide">Luas</p>
                                                </div>
                                                <p class="text-lg font-black text-emerald-900">
                                                    <span x-text="new Intl.NumberFormat('id-ID').format(currentItem.luas_wilayah)"></span>
                                                    <span class="text-base">km²</span>
                                                </p>
                                                <p class="text-xs text-emerald-700 mt-1 font-medium">Luas Wilayah</p>
                                            </div>
                                        </div>
                                        <div class="rounded-2xl bg-gradient-to-br from-amber-50 to-orange-50 p-5 ring-1 ring-amber-200/50 mb-4">
                                            <div class="flex items-start gap-3">
                                                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white shadow-md">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">Potensi Desa</p>
                                                    <p class="text-sm font-semibold text-gray-900 leading-relaxed" x-text="currentItem.potensi_desa"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs text-gray-500">
                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                                            </svg>
                                            <span class="font-semibold" x-text="`Periode: ${new Date(currentItem.periode).toLocaleString('id-ID', { month: 'long', year: 'numeric' })}`"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div x-show="items.length > 1" class="mt-8 flex items-center justify-between border-t border-gray-200 pt-6">
                                <button @click="previous()" 
                                        class="group/btn inline-flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 text-gray-700 transition-all duration-300 hover:from-emerald-500 hover:to-teal-600 hover:text-white hover:shadow-lg hover:scale-110 active:scale-95 ring-2 ring-gray-200 hover:ring-emerald-300">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-6 w-6 transition-transform group-hover/btn:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-gray-900">
                                        Desa <span class="text-emerald-600" x-text="currentIndex + 1"></span> 
                                        <span class="text-gray-400">dari</span> 
                                        <span class="text-emerald-600" x-text="items.length"></span>
                                    </p>
                                    <div class="mt-2 flex justify-center gap-1.5">
                                        <template x-for="(item, index) in items" :key="index">
                                            <button @click="currentIndex = index"
                                                    class="h-2 rounded-full transition-all duration-300"
                                                    :class="currentIndex === index ? 'w-8 bg-emerald-500' : 'w-2 bg-gray-300 hover:bg-gray-400'">
                                            </button>
                                        </template>
                                    </div>
                                </div>
                                <button @click="next()" 
                                        class="group/btn inline-flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 text-gray-700 transition-all duration-300 hover:from-emerald-500 hover:to-teal-600 hover:text-white hover:shadow-lg hover:scale-110 active:scale-95 ring-2 ring-gray-200 hover:ring-emerald-300">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-6 w-6 transition-transform group-hover/btn:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
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

</body>
</html>