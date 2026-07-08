{{-- ====== COMPACT GLASSMORPHISM HEADER ====== --}}
<header class="relative overflow-hidden bg-gradient-to-br from-primary-600 to-primary-800">
    
    {{-- Background Blobs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full opacity-30 bg-radial-gradient-primary blur-[50px]"
             style="background: radial-gradient(circle, rgba(248,113,133,0.5) 0%, transparent 70%);"></div>
        <div class="absolute -bottom-10 -left-10 w-60 h-60 rounded-full opacity-20 bg-radial-gradient-primary-dark blur-[40px]"
             style="background: radial-gradient(circle, rgba(127,29,29,0.6) 0%, transparent 70%);"></div>
    </div>

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

    {{-- Top Bar --}}
    <div class="relative py-2 border-b border-white/10 bg-white/5 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 flex justify-center md:justify-between items-center text-white/70">
            <p class="text-[10px] font-black uppercase tracking-[0.2em]">
                Sistem Informasi Data Stunting <span class="hidden md:inline opacity-50">• Kecamatan Pangalengan</span>
            </p>
            <p class="hidden md:block text-[10px] font-black tracking-[0.2em] opacity-50">
                {{ date('d M Y') }} | v{{ config('app_version.version') }}
            </p>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8 relative">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
            
            {{-- Left: Identity --}}
            <div class="flex flex-col md:flex-row items-center gap-4 text-center md:text-left w-full lg:w-auto">
                {{-- Logo --}}
                <div class="relative group">
                    <div class="absolute -inset-1 rounded-full opacity-30 group-hover:opacity-50 transition duration-500 bg-white/30 blur-[10px]"></div>
                    <div class="relative p-3 rounded-full bg-white/10 backdrop-blur-xl border border-white/20 transition-transform duration-500 group-hover:scale-105">
                        <img class="w-12 h-12 md:w-14 md:h-14 object-contain drop-shadow-lg"
                             src="{{ asset('img/logo-kab-bandung.png') }}" alt="Logo Kabupaten Bandung">
                    </div>
                </div>

                <div>
                    <h1 class="text-2xl md:text-4xl font-black text-white leading-tight tracking-tight">
                        Rumah <span class="text-primary-200" style="text-shadow: 0 0 20px rgba(248,113,133,0.5);">Stunting</span>
                    </h1>
                    <p class="text-primary-100/60 font-medium flex items-center justify-center md:justify-start gap-1.5 mt-1 text-sm">
                        <svg class="w-3.5 h-3.5 text-primary-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        Pangalengan, Kabupaten Bandung
                    </p>
                    <div class="mt-2 flex flex-wrap justify-center md:justify-start gap-1.5">
                        <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-full bg-white/10 border border-white/15 text-white/80">
                            Provinsi Jawa Barat
                        </span>
                        <span class="px-2.5 py-1 text-[9px] font-black uppercase tracking-widest rounded-full bg-white/10 border border-white/15 text-white/80">
                            Data Terverifikasi
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right: Stats --}}
            <div class="grid grid-cols-3 gap-2 w-full lg:w-auto">
                
                {{-- Total Desa --}}
                <div class="flex flex-col items-center justify-center p-3 md:p-4 rounded-xl bg-white/[0.08] backdrop-blur-xl border border-white/15 transition-all duration-300 hover:scale-105">
                    <span class="text-[9px] font-black text-white/40 uppercase tracking-wider">Total Desa</span>
                    <span class="text-xl md:text-2xl font-black text-white mt-0.5">{{ $total_desa ?? 0 }}</span>
                </div>

                {{-- Prevalensi --}}
                <div class="flex flex-col items-center justify-center p-3 md:p-4 rounded-xl bg-white/[0.12] backdrop-blur-xl border border-white/20 shadow-[0_4px_16px_rgba(0,0,0,0.1)] transition-all duration-300 hover:scale-105">
                    <span class="text-[9px] font-black text-white/40 uppercase tracking-wider">Prevalensi</span>
                    <span class="text-xl md:text-2xl font-black text-white mt-0.5">{{ number_format($rata_rata_kasus, 2) }}%</span>
                </div>

                {{-- Faskes --}}
                <div class="flex flex-col items-center justify-center p-3 md:p-4 rounded-xl bg-white/[0.08] backdrop-blur-xl border border-white/15 transition-all duration-300 hover:scale-105">
                    <span class="text-[9px] font-black text-white/40 uppercase tracking-wider">Faskes</span>
                    <span class="text-xl md:text-2xl font-black text-white mt-0.5">{{ $total_faskes ?? 0 }}</span>
                </div>
            </div>
        </div>

        {{-- Chart Card --}}
        <div class="mt-6 md:mt-8">
            <div class="rounded-2xl p-4 md:p-6 overflow-hidden relative bg-white/[0.06] backdrop-blur-[30px] border border-white/12">
                
                {{-- Card Header --}}
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 rounded-lg bg-white/10 border border-white/15">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm md:text-base font-black text-white uppercase tracking-tight">Status Stunting Balita (TB/U)</h3>
                            <p class="text-white/40 text-xs font-medium mt-0.5">Periode: <span class="text-white/70 font-bold uppercase">{{ $periodeLabel }}</span></p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <x-period-dropdown :available-periods="$availablePeriods" :selected-period="$selectedPeriod" variant="red" />
                        <div class="px-3 py-2 rounded-xl bg-white/10 border border-white/15">
                            <p class="text-[9px] font-black text-white/50 uppercase leading-none">Total Balita</p>
                            <p class="text-base font-black text-white">{{ number_format($total_anak) }}</p>
                        </div>
                    </div>
                </div>

                @if ($total_anak > 0)
                    <div x-data="{ active: null, hovered: null }">
                        
                        {{-- Progress Bar --}}
                        <div class="relative mb-4">
                            <div class="h-5 w-full rounded-full flex overflow-hidden bg-black/35 shadow-[inset_0_1px_3px_rgba(0,0,0,0.5)]">
                                
                                @php
                                    $segments = [
                                        'sangat_pendek' => ['bg' => '#dc2626', 'label' => 'Sangat Pendek'],
                                        'pendek'        => ['bg' => '#f97316', 'label' => 'Pendek'],
                                        'normal'        => ['bg' => '#10b981', 'label' => 'Normal'],
                                        'tinggi'        => ['bg' => '#0ea5e9', 'label' => 'Tinggi'],
                                    ];
                                    $filtered = array_filter($distribusi, fn($v) => ($v ?? 0) > 0);
                                    $keys = array_keys($filtered);
                                    $firstKey = $keys[0] ?? null;
                                    $lastKey = $keys[count($keys) - 1] ?? null;
                                @endphp

                                @foreach($segments as $key => $seg)
                                    @if(($distribusi[$key] ?? 0) > 0)
                                        @php 
                                            $pct = $distribusi[$key];
                                            $isFirst = $key === $firstKey;
                                            $isLast = $key === $lastKey;
                                        @endphp
                                        <div @mouseenter="hovered = '{{$key}}'; active = '{{$key}}'" 
                                             @mouseleave="hovered = null; active = null"
                                             class="h-full relative cursor-pointer transition-all duration-300"
                                             style="width: {{ $pct }}%; 
                                                    background: {{ $seg['bg'] }};
                                                    {{ $isFirst ? 'border-top-left-radius: 9999px; border-bottom-left-radius: 9999px;' : '' }}
                                                    {{ $isLast ? 'border-top-right-radius: 9999px; border-bottom-right-radius: 9999px;' : '' }}">
                                            
                                            @if($pct > 12)
                                                <div class="absolute inset-0 flex items-center justify-center">
                                                    <span class="text-[9px] font-black text-white drop-shadow-sm">{{ number_format($pct, 0) }}%</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Legend Grid - Glass Cards --}}
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                            @foreach($segments as $key => $seg)
                                @if(($distribusi[$key] ?? 0) > 0)
                                    <div @mouseenter="active = '{{ $key }}'" 
                                         @mouseleave="active = null"
                                         :class="active === '{{ $key }}' ? 'scale-[1.02]' : 'scale-100'"
                                         class="p-3 md:p-4 rounded-2xl transition-all duration-300 cursor-default relative overflow-hidden"
                                         style="background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%); 
                                                backdrop-filter: blur(16px) saturate(140%); 
                                                border: 1px solid rgba(255,255,255,0.15); 
                                                box-shadow: 0 4px 16px rgba(0,0,0,0.1), inset 0 1px 0 rgba(255,255,255,0.1);"
                                         :style="active === '{{ $key }}' || hovered === '{{ $key }}' 
                                            ? 'background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0.1) 100%); border-color: rgba(255,255,255,0.3);' 
                                            : ''">
                                        
                                        <div class="relative z-10">
                                            <div class="flex items-center gap-2 mb-2">
                                                <div class="w-2 h-2 rounded-sm" style="background: {{ $seg['bg'] }};"></div>
                                                <span class="text-[9px] font-black text-white/60 uppercase tracking-wider">{{ $seg['label'] }}</span>
                                            </div>

                                            <div class="flex items-baseline gap-1.5">
                                                <span class="text-xl md:text-2xl font-black text-white tracking-tighter">{{ number_format($distribusi[$key] ?? 0, 1) }}%</span>
                                                <span class="text-[10px] font-bold text-white/40">{{ number_format($distribusi_counts[$key] ?? 0) }} anak</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="py-8 flex flex-col items-center justify-center text-center">
                        <div class="p-3 rounded-xl bg-white/5 border border-white/10">
                            <svg class="w-6 h-6 text-white/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-white/30 font-bold uppercase text-[10px] tracking-widest">Tidak ada data ditemukan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>