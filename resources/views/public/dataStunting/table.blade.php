@php
    $availablePeriods = $availablePeriods ?? collect();
    $selectedPeriod = $selectedPeriod ?? request('period');
    $currentView = $currentView ?? 'table';
@endphp

{{-- ===== Konten Tab: TABLE ===== --}}
<div class="space-y-4">
    {{-- ========== HEADER: FILTER & SEARCH ========== --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/50 overflow-hidden">
        <div class="p-4 md:p-6">
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
                            class="w-full px-4 py-3 bg-gray-50/80 border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all cursor-pointer hover:bg-white">
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
                            class="w-full px-4 py-3 bg-gray-50/80 border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all cursor-pointer hover:bg-white">
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
                               class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-100 rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all hover:bg-white">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit"
                            class="flex-1 lg:flex-none px-8 py-3 bg-primary-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-600 hover:shadow-lg hover:shadow-primary-200/50 active:scale-95 transition-all">
                        Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== CARD TABEL ========== --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100 uppercase">
                        <th class="px-6 py-5 text-left text-[10px] font-black text-gray-400 tracking-[0.2em]">Desa/Kelurahan</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Kasus Stunting</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Total Anak</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Rata-rata ZS TB/U</th>
                        <th class="px-4 py-5 text-center text-[10px] font-black text-gray-400 tracking-[0.2em]">Status Desa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100/80">
                    @forelse ($dataStunting as $data)
                        <tr class="group hover:bg-primary-50/40 transition-all duration-300 cursor-pointer">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-xs font-black text-primary-600">{{ substr($data->desa_kelurahan, 0, 1) }}</span>
                                    </div>
                                    <span class="text-sm font-black text-gray-900 group-hover:text-primary-600 transition-colors">
                                        {{ $data->desa_kelurahan }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold tracking-wide uppercase transition-all duration-300 hover:scale-105 bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 text-primary-700 shadow-sm shadow-primary-200/30 ring-1 ring-primary-200/60">
                                    {{ number_format($data->total_kasus_stunting) }} Kasus
                                </span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                <span class="text-sm font-bold text-gray-700 group-hover:text-gray-900 transition-colors">
                                    {{ number_format($data->total_anak) }}
                                </span>
                            </td>
                            <td class="px-4 py-5 text-center">
                                @include('components.zscore-badge', ['value' => $data->avg_zs_tbu])
                            </td>
                            <td class="px-4 py-5 text-center">
                                @php
                                    $val = $data->avg_zs_tbu;
                                    $statusClass = 'bg-gray-500'; $statusLabel = 'N/A';
                                    if($val < -3) { $statusClass = 'bg-primary-500'; $statusLabel = 'Sangat Pendek'; }
                                    elseif($val < -2) { $statusClass = 'bg-orange-500'; $statusLabel = 'Pendek'; }
                                    elseif($val <= 2) { $statusClass = 'bg-emerald-500'; $statusLabel = 'Normal'; }
                                    elseif($val > 2) { $statusClass = 'bg-blue-600'; $statusLabel = 'Tinggi'; }
                                @endphp
                                <x-zscore-badge :value="$statusLabel" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-400 font-black uppercase text-xs tracking-widest">Tidak ada data ditemukan</p>
                                    <p class="text-gray-300 text-xs mt-1">Coba ubah filter atau periode pencarian</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL OVERLAY (KONSISTEN) --}}
<div id="infoModalOverlay" class="fixed inset-0 bg-gray-900/60 backdrop-blur-md z-[100] flex items-center justify-center p-4 hidden transition-all duration-300">
    <div id="infoModalContent" class="bg-white/95 backdrop-blur-xl w-full max-w-xl rounded-[2.5rem] shadow-2xl shadow-black/10 overflow-hidden transform scale-95 opacity-0 transition-all duration-300 border border-white/50">
        <div class="p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Informasi Klasifikasi</h3>
                <button id="closeInfoModalBtnHeader" class="p-2 hover:bg-gray-100 rounded-full transition-all hover:rotate-90">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <div class="space-y-3">
                @php
                    $legends = [
                        ['label' => 'Sangat Pendek', 'color' => 'bg-primary-500', 'desc' => 'Rata-rata Z-Score < -3 SD'],
                        ['label' => 'Pendek', 'color' => 'bg-orange-500', 'desc' => 'Rata-rata Z-Score -3 s/d < -2 SD'],
                        ['label' => 'Normal', 'color' => 'bg-emerald-500', 'desc' => 'Rata-rata Z-Score -2 s/d 2 SD'],
                        ['label' => 'Tinggi', 'color' => 'bg-blue-600', 'desc' => 'Rata-rata Z-Score > 2 SD'],
                    ];
                @endphp
                @foreach($legends as $leg)
                    <div class="flex items-center gap-4 p-4 bg-gray-50/80 rounded-2xl border border-gray-100 transition-all hover:bg-white hover:shadow-md hover:shadow-gray-100">
                        <div class="w-4 h-4 rounded-full {{ $leg['color'] }} shadow-lg shadow-black/5 ring-2 ring-white"></div>
                        <div>
                            <p class="text-xs font-black text-gray-900 uppercase tracking-widest">{{ $leg['label'] }}</p>
                            <p class="text-[11px] text-gray-500 font-bold uppercase">{{ $leg['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <button id="closeInfoModalBtnFooter" class="w-full mt-8 py-4 bg-gray-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-primary-500 hover:shadow-lg hover:shadow-primary-200/50 active:scale-95 transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>