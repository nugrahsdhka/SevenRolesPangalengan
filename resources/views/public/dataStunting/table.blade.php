@php
    $availablePeriods = $availablePeriods ?? collect();
    $selectedPeriod = $selectedPeriod ?? request('period');
    $currentView = $currentView ?? 'table';
@endphp

{{-- ===== Konten Tab: TABLE ===== --}}
<div class="space-y-2">
    {{-- ========== HEADER: FILTER & SEARCH ========== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-2">
            <form action="{{ route('data-stunting') }}" method="GET" id="stuntingFilterFormHeader"
                  class="flex flex-col lg:flex-row items-end gap-4">
                
                {{-- Pertahankan view (table/chart) --}}
                <input type="hidden" name="view" value="{{ $currentView }}">

                {{-- Filter Periode --}}
                <div class="w-full lg:w-48">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Periode
                    </label>
                    <select name="period" id="stuntingPeriodeInput"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 transition-all cursor-pointer">
                        <option value="" @selected(empty($selectedPeriod))>Semua Periode</option>
                        @foreach ($availablePeriods as $ym)
                            @php
                                try {
                                    $label = \Carbon\Carbon::createFromFormat('Y-m', $ym)->isoFormat('MMMM Y');
                                } catch (\Exception $e) { $label = $ym; }
                            @endphp
                            <option value="{{ $ym }}" @selected($selectedPeriod === $ym)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Status ZS --}}
                <div class="hidden md:block w-full lg:w-64">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Status ZS TB/U
                    </label>
                    <select name="tingkat_zs" id="tingkat_zs"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 transition-all cursor-pointer">
                        <option value="" @selected(request('tingkat_zs') == '')>Semua Status</option>
                        <option value="sangat_pendek" @selected(request('tingkat_zs') === 'sangat_pendek')>🔴 Sangat Pendek</option>
                        <option value="pendek" @selected(request('tingkat_zs') === 'pendek')>🟠 Pendek</option>
                        <option value="normal" @selected(request('tingkat_zs') === 'normal')>🟢 Normal</option>
                        <option value="tinggi" @selected(request('tingkat_zs') === 'tinggi')>🔵 Tinggi</option>
                    </select>
                </div>

                {{-- Search Desa --}}
                <div class="w-full lg:flex-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Cari Desa
                    </label>
                    <div class="relative group">
                        <input type="text" name="search" id="search" value="{{ $search ?? '' }}"
                               placeholder="Ketik nama desa..."
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-red-500 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit" 
                            class="flex-1 lg:flex-none px-8 py-3 bg-red-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95">
                        Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== CARD TABEL ========== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100 uppercase">
                        <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 tracking-[0.2em]">Desa/Kelurahan</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Kasus Stunting</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Total Anak</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Rata-rata ZS TB/U</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Status Desa</th>
                        <th class="px-6 py-5 text-right text-[10px] font-black text-gray-400 tracking-[0.2em]">Periode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($dataStunting as $data)
                        <tr class="group hover:bg-red-50/30 transition-colors">
                            <td class="px-6 py-5">
                                <span class="text-sm font-black text-gray-900 group-hover:text-red-600 transition-colors">
                                    {{ $data->desa_kelurahan }}
                                </span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <span class="px-3 py-1 bg-red-50 text-red-600 text-xs font-black rounded-full">
                                    {{ number_format($data->total_kasus_stunting) }} Kasus
                                </span>
                            </td>
                            <td class="px-4 py-5 text-center text-sm font-bold text-gray-600">
                                {{ number_format($data->total_anak) }}
                            </td>
                            <td class="px-4 py-5 text-center">
                                @include('components.zscore-badge', ['value' => $data->avg_zs_tbu])
                            </td>
                            <td class="px-4 py-5 text-center">
                                @php
                                    $val = $data->avg_zs_tbu;
                                    $statusClass = 'bg-gray-500'; $statusLabel = 'N/A';
                                    if($val < -3) { $statusClass = 'bg-red-600'; $statusLabel = 'Sangat Pendek'; }
                                    elseif($val < -2) { $statusClass = 'bg-orange-500'; $statusLabel = 'Pendek'; }
                                    elseif($val <= 2) { $statusClass = 'bg-emerald-500'; $statusLabel = 'Normal'; }
                                    elseif($val > 2) { $statusClass = 'bg-blue-600'; $statusLabel = 'Tinggi'; }
                                @endphp
                                <span class="inline-block px-4 py-1.5 {{ $statusClass }} text-white text-[10px] font-black uppercase tracking-tighter rounded-full shadow-sm">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2 text-gray-400 font-bold text-xs">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $data->periode ? \Carbon\Carbon::parse($data->periode)->format("M 'y") : '-' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <p class="text-gray-400 font-black uppercase text-xs tracking-widest">Tidak ada data ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL OVERLAY (KONSISTEN) --}}
<div id="infoModalOverlay" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-[100] flex items-center justify-center p-4 hidden transition-all duration-300">
    <div id="infoModalContent" class="bg-white w-full max-w-xl rounded-[2.5rem] shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900 uppercase">Informasi Klasifikasi</h3>
                <button id="closeInfoModalBtnHeader" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            
            <div class="space-y-4">
                @php
                    $legends = [
                        ['label' => 'Sangat Pendek', 'color' => 'bg-red-600', 'desc' => 'Rata-rata Z-Score < -3 SD'],
                        ['label' => 'Pendek', 'color' => 'bg-orange-500', 'desc' => 'Rata-rata Z-Score -3 s/d < -2 SD'],
                        ['label' => 'Normal', 'color' => 'bg-emerald-500', 'desc' => 'Rata-rata Z-Score -2 s/d 2 SD'],
                        ['label' => 'Tinggi', 'color' => 'bg-blue-600', 'desc' => 'Rata-rata Z-Score > 2 SD'],
                    ];
                @endphp
                @foreach($legends as $leg)
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl">
                        <div class="w-4 h-4 rounded-full {{ $leg['color'] }} shadow-lg shadow-black/5"></div>
                        <div>
                            <p class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ $leg['label'] }}</p>
                            <p class="text-[11px] text-gray-500 font-bold uppercase">{{ $leg['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <button id="closeInfoModalBtnFooter" class="w-full mt-8 py-4 bg-gray-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-red-600 transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>