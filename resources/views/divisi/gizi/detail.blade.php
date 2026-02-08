@php
    // Periode aktif dalam format Y-m (aman walau $currentPeriod null)
    $activePeriodYm = $periodYm ?? \Carbon\Carbon::parse($currentPeriod ?? now())->format('Y-m');
    // Label periode untuk tampilan (contoh: "November 2025")
    $currentPeriodLabel = $currentPeriod
        ? \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y')
        : now()->isoFormat('MMMM Y');
@endphp

<div class="flex flex-col-reverse lg:flex-row gap-1 px-1 py-1 min-h-screen bg-gray-50/50" x-data="{ sidebarOpen: false }">
    
    {{-- List Desa (Sidebar Section) --}}
    <div class="order-2 lg:order-1 relative">
        <div class="lg:sticky lg:top-6">
            {{-- Toggle Button (Desktop Only) --}}
            <button type="button" 
                @click="sidebarOpen = !sidebarOpen"
                class="hidden lg:flex absolute -right-3 top-10 z-[60] w-7 h-7 bg-white border border-gray-200 rounded-full items-center justify-center shadow-sm hover:text-emerald-600 hover:border-emerald-300 transition-all duration-300"
                :class="sidebarOpen ? 'rotate-0' : 'rotate-180'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            {{-- Sidebar & Mobile Dropdown Section --}}
            <div class="order-2 lg:order-1 relative">
                
                {{-- 1. DROPDOWN VIEW (Hanya muncul di Mobile/Tablet < 1024px) --}}
                <div class="block lg:hidden w-full mb-4">
                    <div x-data="{ mobileMenuOpen: false }" class="relative">
                        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                            class="w-full flex items-center justify-between px-4 py-3 bg-white border border-gray-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-emerald-500 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="p-1.5 bg-emerald-100 rounded-lg text-emerald-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span class="font-bold text-gray-700">
                                    {{ $selectedDesa ? strtoupper($selectedDesa) : 'Pilih Wilayah Kerja' }}
                                </span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200" :class="mobileMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown Content Mobile --}}
                        <div x-show="mobileMenuOpen" 
                            @click.away="mobileMenuOpen = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="absolute z-[70] mt-2 w-full bg-white border border-gray-200 rounded-2xl shadow-xl max-h-[60vh] overflow-y-auto p-2">
                            @foreach ($desaList as $desa)
                                <a href="{{ route('gizi.index', ['view' => 'detail', 'period' => $activePeriodYm, 'desa' => $desa]) }}"
                                class="block px-4 py-3 rounded-xl text-sm {{ $selectedDesa == $desa ? 'bg-emerald-50 text-emerald-700 font-bold' : 'text-gray-600 active:bg-gray-50' }}">
                                    {{ strtoupper($desa) }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- 2. SIDEBAR VIEW (Hanya muncul di Desktop >= 1024px) --}}
                <div class="hidden lg:block lg:sticky lg:top-6">
                    {{-- Toggle Button --}}
                    <button type="button" 
                        @click="sidebarOpen = !sidebarOpen"
                        class="absolute -right-3 top-10 z-[60] w-7 h-7 bg-white border border-gray-200 rounded-full flex items-center justify-center shadow-md hover:text-emerald-600 hover:border-emerald-300 transition-all duration-300"
                        :class="sidebarOpen ? 'rotate-0' : 'rotate-180'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    {{-- Sidebar Container --}}
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden transition-all duration-500 ease-in-out"
                        :class="sidebarOpen ? 'w-[280px] opacity-100 translate-x-0' : 'w-0 opacity-0 -translate-x-4 border-transparent shadow-none'">
                        
                        {{-- Sidebar Header --}}
                        <div class="p-5 border-b border-gray-100 bg-gradient-to-br from-gray-50 to-white whitespace-nowrap">
                            <h4 class="text-sm font-bold text-gray-900 flex items-center gap-3">
                                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <span>Wilayah Kerja</span>
                            </h4>
                            @if (!$desaList->isEmpty())
                                <p class="text-[11px] text-gray-400 mt-2 font-medium uppercase tracking-wider">
                                    {{ count($desaList) }} Desa Terdaftar
                                </p>
                            @endif
                        </div>

                        {{-- Sidebar Content --}}
                        <div class="p-3 max-h-[calc(100vh-200px)] overflow-y-auto custom-scrollbar">
                            <div class="grid grid-cols-1 gap-1 min-w-[240px]">
                                @foreach ($desaList as $desa)
                                    @php $isActive = $selectedDesa && strtoupper($selectedDesa) === strtoupper($desa); @endphp
                                    <a href="{{ route('gizi.index', ['view' => 'detail', 'period' => $activePeriodYm, 'desa' => $desa]) }}"
                                    class="group flex items-center justify-between px-4 py-3 rounded-xl text-sm transition-all duration-200
                                    {{ $isActive ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200 font-semibold' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                                        <span class="truncate">{{ strtoupper($desa) }}</span>
                                        @if ($isActive)
                                            <svg class="w-4 h-4 text-emerald-200" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Konten Utama --}}
    <div class="order-1 lg:order-2 flex-1 min-w-0 space-y-1">

        {{-- Top Header Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4 lg:p-6 transition-all">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                <div>
                    <h3 class="text-lg lg:text-xl font-extrabold text-gray-900 tracking-tight">
                        Detail Monitoring Gizi
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Data komprehensif pertumbuhan anak per desa.
                    </p>
                </div>

                {{-- Period Filter --}}
                <form method="GET" action="{{ route('gizi.index') }}" class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                    <input type="hidden" name="view" value="detail">
                    @if ($selectedDesa) <input type="hidden" name="desa" value="{{ $selectedDesa }}"> @endif
                    
                    <div class="relative flex-1 lg:flex-initial min-w-[160px]">
                        <select name="period" required
                            class="w-full pl-4 pr-10 py-2.5 text-sm bg-gray-50 border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 appearance-none transition-all cursor-pointer font-medium text-gray-700">
                            @forelse($availablePeriods ?? [] as $p)
                                @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                <option value="{{ $ym }}" {{ $activePeriodYm === $ym ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                </option>
                            @empty
                                <option value="{{ now()->format('Y-m') }}">{{ now()->isoFormat('MMMM Y') }}</option>
                            @endforelse
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                    </div>

                    <button type="submit"
                        class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md shadow-emerald-100 transition-all active:scale-95 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        Filter
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Content Display --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden min-h-[400px]">
            @if (!$selectedDesa)
                <div class="flex flex-col items-center justify-center py-6 px-6 text-center">
                    <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mb-6 animate-bounce text-emerald-500">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900">Mulai Eksplorasi Data</h4>
                    <p class="text-gray-500 mt-2 max-w-sm mx-auto">Silakan pilih salah satu desa di menu samping untuk menampilkan detail data statistik dan daftar individu.</p>
                </div>
            @else
                <div class="p-4 lg:p-6 space-y-6">
                    
                    {{-- Quick Stats Area --}}
                    <div x-data="{ isOpen: false }" class="bg-gray-50/50 rounded-2xl p-4 border border-gray-100">
                        <button @click="isOpen = !isOpen" class="flex items-center justify-between w-full mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2z" /></svg>
                                </div>
                                <span class="font-bold text-gray-800">Ikhtisar Data: {{ strtoupper($selectedDesa) }}</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="isOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" x-show="isOpen" x-collapse>
                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Total Anak</p>
                                <p class="text-2xl font-black text-blue-600">{{ $detailStat->total_anak ?? 0 }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1 text-red-500">Stunting (TB/U)</p>
                                <p class="text-2xl font-black text-red-600">{{ $detailStat->total_stunting ?? 0 }}</p>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Rata-rata Tinggi</p>
                                <p class="text-2xl font-black text-emerald-600">{{ $detailStat->avg_tinggi ? number_format($detailStat->avg_tinggi, 1) : '-' }} <span class="text-xs text-gray-400">cm</span></p>
                            </div>
                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Avg Z-Score TB/U</p>
                                <div class="mt-1">
                                    @if ($detailStat && !is_null($detailStat->avg_zs_tbu))
                                        <x-zscore-badge :value="$detailStat->avg_zs_tbu" />
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table Area --}}
                    <div class="flex flex-col">
                        <div class="flex items-center justify-between mb-4">
                            <h5 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                Daftar Riwayat Individu
                            </h5>
                            <span class="text-[11px] font-medium bg-gray-100 text-gray-500 px-2 py-1 rounded-md">{{ $currentPeriodLabel }}</span>
                        </div>

                        {{-- Container utama tabel dengan pembatas tinggi --}}
                        <div class="relative rounded-xl border border-gray-100 shadow-sm bg-white overflow-hidden">
                            <div class="overflow-x-auto overflow-y-auto custom-scrollbar" 
                                style="max-height: calc(100vh - 130px);"> {{-- Ubah 350px jika jarak atas lebih tinggi/rendah --}}
                                
                                <table class="w-full text-sm text-left border-collapse">
                                    {{-- Header tetap di atas (Sticky) --}}
                                    <thead class="sticky top-0 z-20 bg-gray-50 text-gray-500 font-bold text-[11px] uppercase tracking-wider border-b border-gray-100 shadow-sm">
                                        <tr>
                                            <th class="px-4 py-4 bg-gray-50">No</th>
                                            <th class="px-4 py-4 bg-gray-50">Nama Anak</th>
                                            <th class="px-4 py-4 bg-gray-50">Input</th>
                                            <th class="px-4 py-4 text-center bg-gray-50">BB (kg)</th>
                                            <th class="px-4 py-4 text-center bg-gray-50">TB (cm)</th>
                                            <th class="px-4 py-4 text-center bg-gray-50">BB/U</th>
                                            <th class="px-4 py-4 text-center bg-gray-50">TB/U</th>
                                            <th class="px-4 py-4 text-center bg-gray-50">BB/TB</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse ($dataDetail as $row)
                                            <tr class="hover:bg-emerald-50/30 transition-colors">
                                                <td class="px-4 py-4 font-medium text-gray-400">{{ $dataDetail->firstItem() + $loop->index }}</td>
                                                <td class="px-4 py-4 font-bold text-gray-800 whitespace-nowrap">{{ $row->nama }}</td>
                                                <td class="px-4 py-4 text-xs text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($row->created_at)->format('d M y') }}</td>
                                                <td class="px-4 py-4 text-center font-semibold">{{ number_format($row->berat, 1) }}</td>
                                                <td class="px-4 py-4 text-center font-semibold">{{ number_format($row->tinggi, 1) }}</td>
                                                <td class="px-4 py-4">
                                                    <div class="flex flex-col items-center gap-1">
                                                        <x-zscore-badge :value="$row->zs_bb_u" />
                                                        <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">{{ $row->status_bb_u }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="flex flex-col items-center gap-1">
                                                        <x-zscore-badge :value="$row->zs_tb_u" />
                                                        <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">{{ $row->status_tb_u }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4">
                                                    <div class="flex flex-col items-center gap-1">
                                                        <x-zscore-badge :value="$row->zs_bb_tb" />
                                                        <span class="text-[10px] text-gray-400 font-medium whitespace-nowrap">{{ $row->status_bb_tb }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="px-6 py-12 text-center">
                                                    <div class="flex flex-col items-center opacity-40">
                                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                                        <p class="text-sm">Belum ada data untuk periode ini.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

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

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #10b981; }
</style>


