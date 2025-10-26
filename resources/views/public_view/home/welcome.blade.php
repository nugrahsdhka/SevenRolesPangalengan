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
                <div class="flex flex-col lg:flex-row items-center gap-6 group w-full">
                    <div class="relative flex-shrink-0">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-r from-red-400 to-rose-500 blur-md opacity-0 group-hover:opacity-100 transition-opacity duration-500 animate-spin-slow"></div>
                        <div class="relative bg-red-600 p-3 rounded-full ring-4 ring-red-600/50 flex items-center justify-center transition-all duration-500 group-hover:ring-8 group-hover:ring-red-400/30 group-hover:scale-110 group-hover:rotate-6">
                            <img class="w-16 h-16 object-contain transition-transform duration-500 group-hover:scale-110 group-hover:-rotate-6" 
                                src="{{ asset('img/logo-kab-bandung.png') }}" 
                                alt="Logo Kabupaten Bandung">
                        </div>
                    </div>

                    <div class="relative min-w-[160px] bg-red-600/40 backdrop-blur-sm rounded-2xl px-6 py-4 border border-red-500/30 overflow-hidden">
                        <h1 class="text-xl font-bold relative z-10 text-white">Rumah Stunting Pangalengan</h1>
                        <p class="text-sm text-red-100 mt-0.5 relative z-10">Kabupaten Bandung • Provinsi Jawa Barat</p>
                        <div class="flex gap-3 mt-3 text-sm relative z-10">
                            <span class="font-medium px-3 py-1 bg-white/10 rounded-lg">5 Faskes</span>
                            <span class="font-medium px-3 py-1 bg-white/10 rounded-lg">13 Desa</span>
                            <span class="block md:hidden font-medium px-3 py-1 bg-white/10 rounded-lg">27.8% Kasus</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Stats Cards --}}
                <div class="hidden md:flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    
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