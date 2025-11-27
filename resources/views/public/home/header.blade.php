{{-- ====== MODERN HEADER WITH GLASSMORPHISM ====== --}}
<header class="text-red-100 relative overflow-hidden bg-gradient-to-br from-red-800 via-red-800 to-rose-700">

    @php
        // Siapkan label periode yang rapih (contoh: "November 2025" atau "Semua Periode")
        if (!empty($selectedPeriod)) {
            try {
                $periodeLabel = \Carbon\Carbon::createFromFormat('Y-m', $selectedPeriod)->isoFormat('MMMM Y');
            } catch (\Exception $e) {
                $periodeLabel = $selectedPeriod;
            }
        } else {
            $periodeLabel = 'Semua Periode';
        }
    @endphp

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
    <div class="relative w-full px-4 sm:px-6 lg:px-8 py-8">
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
                    <div class="flex justify-center items-center flex-col md:justify-start md:items-start">
                        <h1 class="text-xl font-bold relative z-10 text-white">Rumah Stunting Pangalengan</h1>
                        <p class="text-sm text-red-100 mt-0.5 relative z-10">Kabupaten Bandung • Provinsi Jawa Barat
                        </p>
                    </div>

                    {{-- Label Periode --}}
                    <div class="mt-2 text-xs text-red-100/90 relative z-10">
                        Periode data:
                        <span class="font-semibold">
                            {{ $periodeLabel }}
                        </span>
                    </div>

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
                <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
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
                        <p class="text-[11px] text-red-200/80 mt-0.5">
                            Periode:
                            <span class="font-semibold">{{ $periodeLabel }}</span>
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 md:gap-3">
                        {{-- Select Periode --}}
                        <div class="flex gap-2">
                            <x-period-dropdown
                                :available-periods="$availablePeriods"
                                :selected-period="$selectedPeriod"
                                variant="red"
                            />
    
    
                            {{-- Box Total Anak --}}
                            @if ($total_anak > 0)
                                <div
                                    class="text-right bg-red-600/30 backdrop-blur-sm rounded-xl px-4 py-2 border border-red-500/30">
                                    <div class="text-2xl text-center font-bold text-white">{{ number_format($total_anak) }}
                                    </div>
                                    <div class="text-[10px] text-red-200/80 uppercase tracking-wide">Total Anak</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ===== LOGIKA IF/ELSE DIMULAI DI SINI ===== --}}
                @if ($total_anak > 0)

                    <div x-data="{ activeCategory: null }" @mouseleave="activeCategory = null">

                        {{-- Bar Chart (Jika ADA Data) --}}
                        <div class="relative mb-4">
                            <div
                                class="relative w-full bg-red-950/40 rounded-lg h-2.5 overflow-hidden flex border border-red-700/40 shadow-inner">
                                @php
                                    $distSangatPendek = number_format($distribusi['sangat_pendek'] ?? 0, 2);
                                    $distPendek = number_format($distribusi['pendek'] ?? 0, 2);
                                    $distNormal = number_format($distribusi['normal'] ?? 0, 2);
                                    $distTinggi = number_format($distribusi['tinggi'] ?? 0, 2);
                                @endphp

                                @if (($distribusi['sangat_pendek'] ?? 0) > 0)
                                    <div @mouseenter="activeCategory = 'sangat_pendek'"
                                        :class="{
                                            'from-red-400 to-red-500': activeCategory === 'sangat_pendek',
                                            'from-red-500 to-red-600': activeCategory !== 'sangat_pendek'
                                        }"
                                        class="relative bg-gradient-to-r h-2.5 transition-all duration-300"
                                        style="width: {{ $distribusi['sangat_pendek'] ?? 0 }}%">
                                        <div class="absolute inset-0 bg-white/10"></div>
                                    </div>
                                @endif

                                @if (($distribusi['pendek'] ?? 0) > 0)
                                    <div @mouseenter="activeCategory = 'pendek'"
                                        :class="{
                                            'from-orange-300 to-orange-400': activeCategory === 'pendek',
                                            'from-orange-400 to-orange-500': activeCategory !== 'pendek'
                                        }"
                                        class="relative bg-gradient-to-r h-2.5 transition-all duration-300"
                                        style="width: {{ $distribusi['pendek'] ?? 0 }}%">
                                        <div class="absolute inset-0 bg-white/10"></div>
                                    </div>
                                @endif

                                @if (($distribusi['normal'] ?? 0) > 0)
                                    <div @mouseenter="activeCategory = 'normal'"
                                        :class="{
                                            'from-emerald-300 to-emerald-400': activeCategory === 'normal',
                                            'from-emerald-400 to-emerald-500': activeCategory !== 'normal'
                                        }"
                                        class="relative bg-gradient-to-r h-2.5 transition-all duration-300"
                                        style="width: {{ $distribusi['normal'] ?? 0 }}%">
                                        <div class="absolute inset-0 bg-white/10"></div>
                                    </div>
                                @endif

                                @if (($distribusi['tinggi'] ?? 0) > 0)
                                    <div @mouseenter="activeCategory = 'tinggi'"
                                        :class="{
                                            'from-blue-300 to-blue-400': activeCategory === 'tinggi',
                                            'from-blue-400 to-blue-500': activeCategory !== 'tinggi'
                                        }"
                                        class="relative bg-gradient-to-r h-2.5 transition-all duration-300"
                                        style="width: {{ $distribusi['tinggi'] ?? 0 }}%">
                                        <div class="absolute inset-0 bg-white/10"></div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Legend Cards (Jika ADA Data) --}}
                        <div class="relative grid grid-cols-2 lg:grid-cols-4 gap-2.5">

                            {{-- Sangat Pendek --}}
                            <div @mouseenter="activeCategory = 'sangat_pendek'"
                                :class="{
                                    'bg-white/15 border-white/30 scale-[1.02]': activeCategory === 'sangat_pendek',
                                    'bg-white/10 border-white/20': activeCategory !== 'sangat_pendek'
                                }"
                                class="group relative backdrop-blur-sm rounded-xl p-3 border transition-all duration-300">
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
                            <div @mouseenter="activeCategory = 'pendek'"
                                :class="{
                                    'bg-white/15 border-white/30 scale-[1.02]': activeCategory === 'pendek',
                                    'bg-white/10 border-white/20': activeCategory !== 'pendek'
                                }"
                                class="group relative backdrop-blur-sm rounded-xl p-3 border transition-all duration-300">
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
                            <div @mouseenter="activeCategory = 'normal'"
                                :class="{
                                    'bg-white/15 border-white/30 scale-[1.02]': activeCategory === 'normal',
                                    'bg-white/10 border-white/20': activeCategory !== 'normal'
                                }"
                                class="group relative backdrop-blur-sm rounded-xl p-3 border transition-all duration-300">
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
                            <div @mouseenter="activeCategory = 'tinggi'"
                                :class="{
                                    'bg-white/15 border-white/30 scale-[1.02]': activeCategory === 'tinggi',
                                    'bg-white/10 border-white/20': activeCategory !== 'tinggi'
                                }"
                                class="group relative backdrop-blur-sm rounded-xl p-3 border transition-all duration-300">
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
