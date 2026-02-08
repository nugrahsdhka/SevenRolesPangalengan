@php
    $activePeriodYm = $periodYm ?? \Carbon\Carbon::parse($currentPeriod ?? now())->format('Y-m');
    $currentPeriodLabel = $currentPeriod
        ? \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y')
        : now()->isoFormat('MMMM Y');
@endphp

<div class="relative flex flex-col lg:flex-row min-h-screen bg-gray-50/50" x-data="{ sidebarOpen: false }">
    
    {{-- ========== SMART SIDEBAR OVERLAY ========== --}}
    <div x-show="sidebarOpen" 
         class="fixed inset-0 z-[70] bg-red-900/20 backdrop-blur-sm transition-opacity duration-300"
         @click="sidebarOpen = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>
    </div>

    {{-- ========== RESPONSIVE SIDEBAR (OFF-CANVAS) ========== --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed top-0 left-0 z-[80] w-[85vw] sm:w-80 h-screen bg-white border-r border-gray-100 shadow-2xl transition-transform duration-500 ease-in-out lg:duration-300">
        
        <div class="flex flex-col h-full">
            {{-- Sidebar Header --}}
            <div class="p-6 sm:p-8 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h4 class="text-[10px] sm:text-xs font-black text-red-600 uppercase tracking-[0.2em]">Daftar Wilayah</h4>
                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">{{ count($desaList) }} Desa Terdata</p>
                </div>
                <button @click="sidebarOpen = false" class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Sidebar Scroll Area --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-1 custom-scrollbar">
                @foreach ($desaList as $desa)
                    @php $isActive = $selectedDesa && strtoupper($selectedDesa) === strtoupper($desa); @endphp
                    <a href="{{ route('gizi.index', ['view' => 'detail', 'period' => $activePeriodYm, 'desa' => $desa]) }}"
                       class="flex items-center justify-between px-5 py-4 rounded-2xl transition-all duration-300 group
                       {{ $isActive ? 'bg-red-600 text-white shadow-lg shadow-red-200' : 'text-gray-500 hover:bg-red-50 hover:text-red-600' }}">
                        <span class="text-[11px] font-black uppercase tracking-[0.1em] truncate">{{ $desa }}</span>
                        @if($isActive)
                            <svg class="w-4 h-4 text-red-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- ========== MAIN CONTENT SECTION ========== --}}
    <main class="flex-1 min-w-0 flex flex-col transition-all duration-300">
        
        {{-- RESPONSIVE TOPBAR --}}
        <header class="sticky top-0 z-20 bg-white/90 backdrop-blur-xl border-b border-gray-100 px-4 sm:px-6 lg:px-10 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                
                {{-- Left Side: Nav Control & Title --}}
                <div class="flex items-center gap-3 sm:gap-6">
                    <button @click="sidebarOpen = true" 
                            class="flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white rounded-xl shadow-lg hover:bg-red-600 transition-all active:scale-95 group">
                        <svg class="w-5 h-5 transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h7"/></svg>
                        <span class="text-[10px] font-black uppercase tracking-widest">Menu</span>
                    </button>

                    <div class="min-w-0">
                        <h1 class="text-lg sm:text-2xl lg:text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none truncate">
                            Data <span class="text-red-600">Detail</span>
                        </h1>
                        <p class="mt-1 text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] truncate">
                             {{ $selectedDesa ?? 'PILIH WILAYAH' }}
                        </p>
                    </div>
                </div>

                {{-- Right Side: Period Switcher --}}
                <form method="GET" action="{{ route('gizi.index') }}" class="w-full sm:w-auto">
                    <input type="hidden" name="view" value="detail">
                    @if ($selectedDesa) <input type="hidden" name="desa" value="{{ $selectedDesa }}"> @endif
                    <div class="relative group">
                        <select name="period" onchange="this.form.submit()"
                                class="w-full sm:w-auto pl-4 pr-10 py-3 bg-gray-50 border-none rounded-2xl text-[10px] font-black text-gray-700 uppercase tracking-widest focus:ring-2 focus:ring-red-500 transition-all appearance-none cursor-pointer group-hover:bg-gray-100">
                            @foreach($availablePeriods ?? [] as $p)
                                @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                <option value="{{ $ym }}" {{ $activePeriodYm === $ym ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($p)->isoFormat('MMM Y') }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>
                </form>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <div class="p-2 space-y-2 flex-1">
            @if (!$selectedDesa)
                <div class="min-h-[50vh] flex flex-col items-center justify-center text-center bg-white rounded-[2.5rem] sm:rounded-[3.5rem] border border-gray-100 shadow-sm px-6 py-12 transition-all">
                    <div class="w-20 h-20 sm:w-28 sm:h-28 bg-red-50 rounded-3xl flex items-center justify-center text-red-600 mb-8 animate-fade-in shadow-xl shadow-red-100/50">
                        <svg class="w-10 h-10 sm:w-14 sm:h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A2 2 0 012.447 15.276V5.276a2 2 0 011.106-1.789l5.447-2.724a2 2 0 011.789 0l5.447 2.724a2 2 0 011.106 1.789v10a2 2 0 01-1.106 1.789L10.789 20a2 2 0 01-1.578 0z" /></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black text-gray-900 uppercase">Pilih Wilayah Kerja</h3>
                    <p class="text-gray-400 font-bold text-[10px] sm:text-xs mt-3 uppercase tracking-[0.15em] max-w-xs mx-auto leading-relaxed">Klik tombol menu di kiri atas untuk melihat daftar desa yang tersedia.</p>
                </div>
            @else
                {{-- QUICK STATS (GRID RESPONSIVE) --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach([
                        ['label' => 'Populasi', 'val' => $detailStat->total_anak ?? 0, 'sub' => 'Jiwa', 'bg' => 'bg-white', 'text' => 'text-gray-900'],
                        ['label' => 'Stunting', 'val' => $detailStat->total_stunting ?? 0, 'sub' => 'Kasus', 'bg' => 'bg-red-600 shadow-red-100 shadow-xl', 'text' => 'text-white'],
                        ['label' => 'Rata-rata TB', 'val' => ($detailStat->avg_tinggi ? number_format($detailStat->avg_tinggi, 1) : '-'), 'sub' => 'CM', 'bg' => 'bg-white', 'text' => 'text-gray-900'],
                    ] as $stat)
                    <div class="{{ $stat['bg'] }} p-5 sm:p-7 rounded-[2.5rem] border border-gray-100 shadow-sm transition-transform hover:scale-[1.03]">
                        <span class="text-[8px] sm:text-[9px] font-black uppercase tracking-widest block mb-1 {{ str_contains($stat['bg'], 'red') ? 'text-red-200' : 'text-gray-400' }}">{{ $stat['label'] }}</span>
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl sm:text-3xl font-black {{ $stat['text'] }} tracking-tighter">{{ $stat['val'] }}</span>
                            <span class="text-[8px] font-bold {{ str_contains($stat['bg'], 'red') ? 'text-red-200' : 'text-gray-400' }} uppercase">{{ $stat['sub'] }}</span>
                        </div>
                    </div>
                    @endforeach
                    <div class="bg-white p-5 sm:p-7 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                        <span class="text-[8px] sm:text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Avg Z-Score</span>
                        <div class="scale-90 sm:scale-110">
                            @if ($detailStat && !is_null($detailStat->avg_zs_tbu))
                                <x-zscore-badge :value="$detailStat->avg_zs_tbu" />
                            @else
                                <span class="text-gray-300 font-black">-</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TABLE SECTION (CLEAN & SCROLLABLE) --}}
                <div class="bg-white rounded-[2.5rem] sm:rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col transition-all">
                    <div class="px-6 sm:px-10 py-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="w-2.5 h-2.5 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.5)]"></span>
                            <h5 class="text-[10px] sm:text-[11px] font-black text-gray-900 uppercase tracking-[0.2em]">Individu: {{ $selectedDesa }}</h5>
                        </div>
                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <span class="text-[9px] font-black text-gray-500 uppercase px-3 py-1.5 bg-gray-50 rounded-full border border-gray-100">{{ $currentPeriodLabel }}</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto scrollbar-hide border-separate border-spacing-0 max-h-[60vh] md:max-h-[55vh]">
                        <table class="w-full min-w-[900px]">
                            <thead class="sticky top-0 z-20">
                                <tr class="bg-white border-b border-gray-100 shadow-sm">
                                    <th class="px-8 py-5 text-left text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">No</th>
                                    <th class="px-4 py-5 text-left text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">Nama Anak</th>
                                    <th class="px-4 py-5 text-center text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">BB/TB</th>
                                    <th class="px-4 py-5 text-center text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">Status Gizi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($dataDetail as $row)
                                    <tr class="group hover:bg-red-50/40 transition-all duration-300">
                                        <td class="px-8 py-6 text-[10px] sm:text-xs font-black text-gray-300 group-hover:text-red-500 transition-colors">{{ ($dataDetail->firstItem() + $loop->index) }}</td>
                                        <td class="px-4 py-6">
                                            <p class="text-xs sm:text-sm font-black text-gray-900 uppercase group-hover:text-red-600 transition-colors truncate max-w-[200px]">{{ $row->nama }}</p>
                                            <p class="text-[9px] font-bold text-gray-400 uppercase mt-0.5 tracking-tighter">{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</p>
                                        </td>
                                        <td class="px-4 py-6 text-center">
                                            <div class="inline-flex flex-col sm:flex-row items-center gap-1 sm:gap-2 px-3 py-2 bg-gray-50 rounded-2xl border border-gray-100 group-hover:bg-white group-hover:border-red-100 transition-all">
                                                <span class="text-[11px] font-black text-gray-800">{{ number_format($row->berat, 1) }}<small class="text-[8px] text-gray-400 ml-0.5">KG</small></span>
                                                <span class="hidden sm:block text-gray-300">/</span>
                                                <span class="text-[11px] font-black text-gray-800">{{ number_format($row->tinggi, 1) }}<small class="text-[8px] text-gray-400 ml-0.5">CM</small></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-6">
                                            <div class="flex items-center justify-center gap-3 sm:gap-8 scale-75 sm:scale-100 origin-center">
                                                <div class="flex flex-col items-center gap-1.5">
                                                    <x-zscore-badge :value="$row->zs_bb_u" />
                                                    <span class="text-[7px] sm:text-[8px] font-black text-gray-400 uppercase">BB/U</span>
                                                </div>
                                                <div class="flex flex-col items-center gap-1.5">
                                                    <x-zscore-badge :value="$row->zs_tb_u" />
                                                    <span class="text-[7px] sm:text-[8px] font-black text-red-400 uppercase">TB/U</span>
                                                </div>
                                                <div class="flex flex-col items-center gap-1.5">
                                                    <x-zscore-badge :value="$row->zs_bb_tb" />
                                                    <span class="text-[7px] sm:text-[8px] font-black text-gray-400 uppercase">BB/TB</span>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-32 text-center text-gray-300 font-black uppercase text-[10px] tracking-[0.3em] italic">Data belum tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($dataDetail && $dataDetail->hasPages())
                        <div class="px-6 sm:px-10 py-6 border-t border-gray-50 bg-white pagination-rsp">
                            {{ $dataDetail->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </main>
</div>

<style>
    /* Premium UX Tweaks */
    [x-cloak] { display: none !important; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #fee2e2; border-radius: 10px; }

    /* Ultra Responsive Pagination */
    .pagination-rsp nav { @apply flex flex-wrap justify-center gap-2; }
    .pagination-rsp nav span[aria-current="page"] span {
        @apply bg-red-600 border-red-600 rounded-xl font-black text-[10px] sm:text-xs text-white shadow-lg shadow-red-200 px-3 sm:px-4 py-2 !important;
    }
    .pagination-rsp nav a, .pagination-rsp nav span {
        @apply rounded-xl border-gray-100 text-[10px] sm:text-xs font-black uppercase tracking-widest hover:bg-red-50 hover:text-red-600 transition-all px-3 sm:px-4 py-2 !important;
    }

    @keyframes fade-in {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fade-in { animation: fade-in 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>