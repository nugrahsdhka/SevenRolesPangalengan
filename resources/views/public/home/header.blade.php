{{-- ====== PREMIUM HEADER WITH ADVANCED GLASSMORPHISM ====== --}}
<header class="relative overflow-hidden bg-red-700">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-red-400 rounded-full blur-3xl opacity-50"></div>
    <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-80 h-80 bg-rose-900 rounded-full blur-3xl opacity-30"></div>

    @php
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

    {{-- Top Bar (Info Sistem) --}}
    <div class="relative bg-red-700 py-2 shadow-sm z-30">
        <div class="max-w-7xl mx-auto px-4 flex justify-center md:justify-between items-center text-white">
            <p class="text-[11px] md:text-xs font-bold uppercase tracking-[0.2em]">
                Sistem Informasi Data Stunting <span class="hidden md:inline">• Kecamatan Pangalengan</span>
            </p>
            <p class="hidden md:block text-[11px] md:text-xs font-bold uppercase tracking-[0.2em]">
                {{ date('d-M-Y') }} 
            </p>
        </div>
    </div>

    {{-- Main Content Section --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative z-20">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-10">
            
            {{-- Left: Identity --}}
            <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left w-full lg:w-auto">
                {{-- Logo with Dynamic Ring --}}
                <div class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-red-600 to-rose-500 rounded-full blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-red-600 p-4 rounded-full shadow-xl border border-red-700 transition-transform duration-500 group-hover:scale-105">
                        <img class="w-16 h-16 md:w-20 md:h-20 object-contain"
                             src="{{ asset('img/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung">
                    </div>
                </div>

                <div>
                    <h1 class="text-3xl md:text-4xl font-black text-white leading-tight">
                        Rumah <span class="text-red-100">Stunting</span>
                    </h1>
                    <p class="text-gray-200 font-medium flex items-center justify-center md:justify-start gap-2 mt-1 text-sm md:text-base">
                        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>
                        Pangalengan, Kabupaten Bandung
                    </p>
                    <div class="mt-4 flex flex-wrap justify-center md:justify-start gap-2">
                        <span class="px-3 py-1 bg-red-50 text-red-700 text-[11px] font-bold rounded-full border border-red-100">PROVINSI JAWA BARAT</span>
                        <span class="px-3 py-1 bg-gray-50 text-gray-600 text-[11px] font-bold rounded-full border border-gray-100">DATA TERVERIFIKASI</span>
                    </div>
                </div>
            </div>

            {{-- Right: Integrated Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 w-full lg:w-auto">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center transition-all hover:shadow-md">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Desa</span>
                    <span class="text-3xl font-black text-gray-900 mt-1">{{ $total_desa ?? 0 }}</span>
                </div>
                <div class="bg-red-600 p-5 rounded-2xl shadow-lg shadow-red-100 flex flex-col items-center justify-center transition-all hover:scale-105 group">
                    <span class="text-[10px] font-black text-red-100 uppercase tracking-widest">Prevalensi</span>
                    <span class="text-3xl font-black text-white mt-1 group-hover:scale-110 transition-transform">{{ number_format($rata_rata_kasus ?? 0, 1) }}%</span>
                </div>
                <div class="hidden md:flex bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center transition-all hover:shadow-md">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Faskes</span>
                    <span class="text-3xl font-black text-gray-900 mt-1">{{ $total_faskes ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Distribution Chart Card --}}
        <div class="mt-12 group">
            <div class="bg-red-500/70 backdrop-blur-xl rounded-3xl p-6 md:p-8 border border-red-700 shadow-xl overflow-hidden relative">
                {{-- Card Header --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 relative z-10">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-red-600 rounded-lg shadow-lg shadow-red-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-red-200 uppercase tracking-tight">Status Stunting Balita (TB/U)</h3>
                        </div>
                        <p class="text-red-300 text-sm font-medium">Periode Laporan: <span class="text-white font-bold uppercase">{{ $periodeLabel }}</span></p>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-period-dropdown :available-periods="$availablePeriods" :selected-period="$selectedPeriod" variant="red" />
                        <div class="bg-red-600 px-5 py-3 rounded-2xl shadow-lg">
                            <p class="text-[10px] font-bold text-red-100 uppercase leading-none">Total Balita</p>
                            <p class="text-xl font-black text-white mt-1">{{ number_format($total_anak) }}</p>
                        </div>
                    </div>
                </div>

                @if ($total_anak > 0)
                    <div x-data="{ active: null }" class="relative z-10">
                        {{-- Advanced Bar --}}
                        <div class="h-6 w-full bg-gray-100 rounded-full flex overflow-hidden shadow-inner p-1 mb-8">
                            @foreach(['sangat_pendek' => 'bg-red-600', 'pendek' => 'bg-orange-500', 'normal' => 'bg-emerald-500', 'tinggi' => 'bg-blue-600'] as $key => $color)
                                @if(($distribusi[$key] ?? 0) > 0)
                                    <div @mouseenter="active = '{{$key}}'" @mouseleave="active = null"
                                         class="{{ $color }} h-full transition-all duration-500 first:rounded-l-full last:rounded-r-full cursor-help relative group"
                                         style="width: {{ $distribusi[$key] }}%">
                                         <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- Legend Grid --}}
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            @php
                                $categories = [
                                    ['id' => 'sangat_pendek', 'label' => 'Sangat Pendek', 'color' => 'bg-red-600', 'text' => 'text-red-600'],
                                    ['id' => 'pendek', 'label' => 'Pendek', 'color' => 'bg-orange-500', 'text' => 'text-orange-500'],
                                    ['id' => 'normal', 'label' => 'Normal', 'color' => 'bg-emerald-500', 'text' => 'text-emerald-500'],
                                    ['id' => 'tinggi', 'label' => 'Tinggi', 'color' => 'bg-blue-600', 'text' => 'text-blue-600']
                                ];
                            @endphp

                            @foreach($categories as $cat)
                                <div @mouseenter="active = '{{ $cat['id'] }}'" @mouseleave="active = null"
                                    {{-- Logika Class: Saat di-hover menjadi lebih terang dan border lebih tegas --}}
                                    :class="active === '{{ $cat['id'] }}' 
                                            ? 'bg-white/25 border-white/50 scale-105 shadow-lg shadow-black/10' 
                                            : 'bg-white/10 border-white/20'"
                                    class="p-5 rounded-[2rem] border backdrop-blur-md transition-all duration-500 group cursor-default">
                                    
                                    <div class="flex items-center gap-3 mb-3">
                                        {{-- Dot Indikator dengan Glow Effect --}}
                                        <div class="w-3 h-3 rounded-full {{ $cat['color'] }} shadow-[0_0_10px_rgba(255,255,255,0.4)]"></div>
                                        
                                        {{-- Label Kategori --}}
                                        <span class="text-[10px] font-black text-red-100 uppercase tracking-[0.15em] opacity-80 group-hover:opacity-100 transition-opacity">
                                            {{ $cat['label'] }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col">
                                        {{-- Angka Persentase --}}
                                        <span class="text-3xl font-black text-white tracking-tighter leading-none">
                                            {{ number_format($distribusi[$cat['id']] ?? 0, 1) }}%
                                        </span>
                                        
                                        {{-- Counter Anak --}}
                                        <span class="text-[11px] font-bold text-red-200 mt-2 flex items-center gap-1.5">
                                            <svg class="w-3 h-3 opacity-60" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                            </svg>
                                            {{ number_format($distribusi_counts[$cat['id']] ?? 0) }} Anak
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="py-12 flex flex-col items-center justify-center text-center">
                        <div class="p-4 bg-gray-50 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">Tidak ada data untuk periode ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div 
        x-data="{ visible: true }"
        x-init="
            window.addEventListener('scroll', () => {
                if (window.scrollY > 0) visible = false
            })
        "
        x-show="visible"
        x-transition.opacity.duration.500ms
        class="hidden md:flex absolute bottom-6 left-1/2 -translate-x-1/2 z-40 flex-col items-center gap-2 text-white/80"
    >
        <span class="text-[10px] font-bold uppercase tracking-[0.3em] animate-scroll-indicator">
            Scroll ke bawah
        </span>
    </div>

</header>