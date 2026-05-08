@php
    $activePeriodYm = $periodYm ?? \Carbon\Carbon::parse($currentPeriod ?? now())->format('Y-m');
    $currentPeriodLabel = $currentPeriod
        ? \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y')
        : now()->isoFormat('MMMM Y');

    $hasAnomali = $dataDetail->getCollection()->contains('status', 'need_review');
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

        @if($hasAnomali)
            <div class="px-6 py-3 mt-3 justify-center bg-red-50/50 rounded-2xl border border-red-100/50 flex items-center gap-3">
                <span class="flex h-2 w-2 rounded-full bg-red-500"></span>
                <p class="text-[9px] font-black text-red-600 uppercase tracking-widest">
                    Statistik hanya menghitung data valid. Terdapat data anomali yang membutuhkan perbaikan.
                </p>
            </div>
        @endif
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
                    
                    {{-- Container Utama Header --}}
                    <div class="bg-white border-b border-gray-100 flex flex-col transition-all">
                        
                        {{-- BARIS 1: Judul & Label Periode --}}
                        <div class="px-4 sm:px-10 py-6 flex sm:flex-row sm:items-center justify-between gap-4">
                            {{-- Judul Desa --}}
                            <div class="flex items-center gap-3 min-w-0">
                                <span class="flex-shrink-0 w-2.5 h-2.5 bg-red-600 rounded-full shadow-[0_0_10px_rgba(220,38,38,0.5)]"></span>
                                <h5 class="text-[10px] sm:text-[11px] font-black text-gray-900 uppercase tracking-[0.2em] truncate">
                                    Individu: {{ $selectedDesa }}
                                </h5>
                            </div>

                            {{-- Badge Periode --}}
                            <div class="flex justify-evenly">
                                <div class="hidden px-4 sm:px-10 py-3 bg-white/30 backdrop-blur-md border-t border-white/40 md:flex items-center overflow-x-auto no-scrollbar gap-2 sm:gap-3">
                                    @php
                                        $currentStatusAnak = request('status_anak', 'all');
                                        $childFilters = [
                                            'all'         => ['label' => 'Semua',     'dot' => 'bg-gray-400',    'glow' => 'shadow-gray-400/40'],
                                            'pending'     => ['label' => 'Pending',   'dot' => 'bg-amber-500',   'glow' => 'shadow-amber-400/40'],
                                            'need_review' => ['label' => 'Anomali',   'dot' => 'bg-red-500',     'glow' => 'shadow-red-400/40'],
                                            'approved'    => ['label' => 'Approved',  'dot' => 'bg-blue-500',    'glow' => 'shadow-blue-400/40'],
                                            'published'   => ['label' => 'Published', 'dot' => 'bg-emerald-500', 'glow' => 'shadow-emerald-400/40'],
                                        ];
                                    @endphp

                                    @foreach($childFilters as $key => $f)
                                        <a href="{{ request()->fullUrlWithQuery(['status_anak' => $key]) }}"
                                            class="flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex-shrink-0
                                            {{ $currentStatusAnak === $key
                                                ? 'bg-white/70 backdrop-blur-sm text-gray-900 shadow-md ' . $f['glow'] . ' ring-1 ring-white/80'
                                                : 'bg-white/10 backdrop-blur-sm text-gray-400 hover:text-gray-600 hover:bg-white/30 hover:ring-1 hover:ring-white/60'
                                            }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $f['dot'] }} {{ $currentStatusAnak === $key ? 'shadow-sm ' . $f['glow'] : '' }}"></span>
                                            {{ $f['label'] }}
                                        </a>
                                    @endforeach
                                </div>

                                <div class="flex items-center self-start sm:self-auto">
                                    <span class="text-[9px] font-black text-gray-500 uppercase px-3 py-1.5 bg-gray-50 rounded-full border border-gray-100 whitespace-nowrap">
                                        {{ $currentPeriodLabel }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- BARIS 2: Navigasi Filter (Scrollable on Mobile) --}}
                        <div class="md:hidden px-4 sm:px-10 py-3 bg-white/30 backdrop-blur-md border-t border-white/40 flex items-center overflow-x-auto no-scrollbar gap-2 sm:gap-3">
                            @php
                                $currentStatusAnak = request('status_anak', 'all');
                                $childFilters = [
                                    'all'         => ['label' => 'Semua',     'dot' => 'bg-gray-400',    'glow' => 'shadow-gray-400/40'],
                                    'pending'     => ['label' => 'Pending',   'dot' => 'bg-amber-500',   'glow' => 'shadow-amber-400/40'],
                                    'need_review' => ['label' => 'Anomali',   'dot' => 'bg-red-500',     'glow' => 'shadow-red-400/40'],
                                    'approved'    => ['label' => 'Approved',  'dot' => 'bg-blue-500',    'glow' => 'shadow-blue-400/40'],
                                    'published'   => ['label' => 'Published', 'dot' => 'bg-emerald-500', 'glow' => 'shadow-emerald-400/40'],
                                ];
                            @endphp

                            @foreach($childFilters as $key => $f)
                                <a href="{{ request()->fullUrlWithQuery(['status_anak' => $key]) }}"
                                    class="flex items-center gap-2 px-4 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex-shrink-0
                                    {{ $currentStatusAnak === $key
                                        ? 'bg-white/70 backdrop-blur-sm text-gray-900 shadow-md ' . $f['glow'] . ' ring-1 ring-white/80'
                                        : 'bg-white/10 backdrop-blur-sm text-gray-400 hover:text-gray-600 hover:bg-white/30 hover:ring-1 hover:ring-white/60'
                                    }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $f['dot'] }} {{ $currentStatusAnak === $key ? 'shadow-sm ' . $f['glow'] : '' }}"></span>
                                    {{ $f['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="overflow-x-auto scrollbar-hide border-separate border-spacing-0 max-h-[70vh] md:max-h-[85vh]">
                        <table class="w-full min-w-[900px]">
                            <thead class="sticky top-0 z-20">
                                <tr class="bg-white border-b border-gray-100 shadow-sm">
                                    <th class="px-8 py-5 text-left text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">No</th>
                                    <th class="px-4 py-5 text-left text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">Nama Anak</th>
                                    <th class="px-4 py-5 text-center text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">BB/TB</th>
                                    <th class="px-4 py-5 text-center text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">Status Gizi</th>                                    
                                    @if(!in_array(request('status_anak'), ['approved', 'published']))
                                        <th class="px-4 py-5 text-center text-[9px] sm:text-[10px] font-black text-gray-400 uppercase bg-white border-b border-gray-100 tracking-[0.15em]">Aksi</th>                                         
                                    @endif
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
                                                    {{-- Highlight jika anomali --}}
                                                    <div class="relative">
                                                        <x-zscore-badge :value="$row->zs_tb_u" />
                                                        @if($row->status === 'need_review' && ($row->zs_tb_u < -6 || $row->zs_tb_u > 6))
                                                            <span class="absolute -top-1 -right-1 flex h-2 w-2">
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="text-[7px] sm:text-[8px] font-black text-red-400 uppercase">TB/U</span>
                                                </div>
                                                <div class="flex flex-col items-center gap-1.5">
                                                    {{-- Highlight jika anomali --}}
                                                    <div class="relative">
                                                        <x-zscore-badge :value="$row->zs_bb_u" />
                                                        @if($row->status === 'need_review' && ($row->zs_bb_u < -6 || $row->zs_bb_u > 6))
                                                            <span class="absolute -top-1 -right-1 flex h-2 w-2">
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="text-[7px] sm:text-[8px] font-black text-red-400 uppercase">BB/U</span>
                                                </div>
                                                <div class="flex flex-col items-center gap-1.5">
                                                    {{-- Highlight jika anomali --}}
                                                    <div class="relative">
                                                        <x-zscore-badge :value="$row->zs_bb_tb" />
                                                        @if($row->status === 'need_review' && ($row->zs_bb_tb < -6 || $row->zs_bb_tb > 6))
                                                            <span class="absolute -top-1 -right-1 flex h-2 w-2">
                                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <span class="text-[7px] sm:text-[8px] font-black text-red-400 uppercase">BB/TB</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-8 py-6 text-center">
                                            @if(in_array($row->status, ['need_review', 'pending']))
                                                <button @click="$dispatch('open-edit-modal', { 
                                                            id: {{ $row->id }}, 
                                                            nama: '{{ addslashes($row->nama) }}',
                                                            zs_tb_u: '{{ $row->zs_tb_u }}',
                                                            zs_bb_u: '{{ $row->zs_bb_u }}',
                                                            zs_bb_tb: '{{ $row->zs_bb_tb }}'
                                                        })"
                                                        class="p-2.5 bg-red-600 text-white border border-gray-100 rounded-xl hover:bg-red-600 hover:text-white hover:border-red-600 hover:shadow-lg hover:shadow-red-200 transition-all active:scale-90 group/edit">
                                                    <svg class="w-4 h-4 transition-transform group-hover/edit:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                @elseif ($row->status == 'published')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500 text-white shadow-sm">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Published
                                                    </span>
                                                @elseif ($row->status == 'approved')
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500 text-white shadow-sm">
                                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                        </svg>
                                                        Approved
                                                    </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-32 text-center text-gray-300 font-black uppercase text-[10px] tracking-[0.3em] italic">Data belum tersedia</td>
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

    {{-- MODAL PERBAIKAN DATA --}}
    <div x-data="{ open: false, data: {} }" 
        @open-edit-modal.window="open = true; data = $event.detail"
        x-show="open" 
        class="fixed inset-0 z-[100] overflow-y-auto" 
        x-cloak>
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Overlay --}}
            <div x-show="open" 
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                class="fixed inset-0 transition-opacity bg-gray-900/60 backdrop-blur-sm"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="open" 
                x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100">
                
                <form :action="'/divisi-gizi/' + data.id" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="desa_kelurahan" value="{{ $selectedDesa }}">     

                    <div class="px-8 pt-8 pb-6 bg-white">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="p-3 bg-red-50 text-red-600 rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight leading-none">Perbaiki <span class="text-red-600">Data</span></h3>
                                <p class="mt-1.5 text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none" x-text="data.nama"></p>
                            </div>
                        </div>

                        <div class="space-y-5">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1 text-center">ZS BB/U</label>
                                    <input type="number" step="0.01" name="zs_bb_u" x-model="data.zs_bb_u" 
                                        class="w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-xs font-black text-gray-700 text-center focus:ring-2 focus:ring-red-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1 text-center text-red-400">ZS TB/U</label>
                                    <input type="number" step="0.01" name="zs_tb_u" x-model="data.zs_tb_u" 
                                        class="w-full px-5 py-3.5 bg-red-50/50 border-none rounded-2xl text-xs font-black text-red-600 text-center focus:ring-2 focus:ring-red-500 transition-all ring-1 ring-red-100">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1 text-center">ZS BB/TB</label>
                                <input type="number" step="0.01" name="zs_bb_tb" x-model="data.zs_bb_tb" 
                                    class="w-full px-5 py-3.5 bg-gray-50 border-none rounded-2xl text-xs font-black text-gray-700 text-center focus:ring-2 focus:ring-red-500 transition-all">
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                            <p class="text-[9px] font-bold text-amber-700 leading-relaxed uppercase tracking-tight">
                                * Data dengan Z-Score di luar rentang -6 sampai 6 akan otomatis ditandai sebagai anomali dan tidak dihitung dalam statistik desa.
                            </p>
                        </div>
                    </div>

                    <div class="px-8 py-6 bg-gray-50 flex gap-3">
                        <button type="button" @click="open = false" 
                                class="flex-1 px-6 py-4 bg-white border border-gray-200 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-gray-100 transition-all active:scale-95">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-6 py-4 bg-gray-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-red-600 shadow-xl hover:shadow-red-200 transition-all active:scale-95">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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