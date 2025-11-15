@php
    // Periode aktif dalam format Y-m (aman walau $currentPeriod null)
    $activePeriodYm = $periodYm ?? \Carbon\Carbon::parse($currentPeriod ?? now())->format('Y-m');
    // Label periode untuk tampilan (contoh: "November 2025")
    $currentPeriodLabel = $currentPeriod
        ? \Carbon\Carbon::parse($currentPeriod)->isoFormat('MMMM Y')
        : now()->isoFormat('MMMM Y');
@endphp
<div class="flex flex-col-reverse lg:flex-row gap-2 px-2 py-2 backdrop-blur-sm" x-data="{ sidebarOpen: false }">
    {{-- List Desa --}}
    <div class="order-2 lg:order-1 lg:relative z-50">
        {{-- Sidebar desktop daftar desa --}}
        <div class="hidden lg:block">
            <div class="lg:sticky lg:top-4">
                {{-- Tombol toggle sidebar (chevron) --}}
                <button type="button" @click="sidebarOpen = !sidebarOpen"
                    class="absolute top-16 right-0 translate-x-full z-50
                                bg-white border border-l-0 border-gray-200 
                                rounded-r-lg px-1 py-3 text-gray-500 hover:text-emerald-600
                                active:bg-gray-100 transition-colors"
                    title="Toggle sidebar">
                    {{-- Ikon Chevron Left (tutup) --}}
                    <svg x-show="sidebarOpen" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{-- Ikon Chevron Right (buka) --}}
                    <svg x-show="!sidebarOpen" style="display: none;" class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                {{-- Card sidebar desa --}}
                <div class="bg-white/40 backdrop-blur-md border border-gray-200/50 rounded-xl overflow-hidden transition-all duration-300 ease-in-out"
                    :class="sidebarOpen ? 'lg:w-[250px]' : 'lg:w-0'">

                    {{-- Header sidebar --}}
                    <div
                        class="p-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white rounded-t-xl whitespace-nowrap">
                        <h4 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            Daftar Desa/Kelurahan
                        </h4>
                        @if (!$desaList->isEmpty())
                            <p class="text-xs text-gray-500 mt-1">
                                {{ count($desaList) }} desa tersedia
                            </p>
                        @endif
                    </div>
                    {{-- Isi sidebar: list desa / empty state --}}
                    <div class="p-3 max-h-[50vh] sm:max-h-[60vh] lg:max-h-[calc(100vh-220px)] overflow-y-auto
                                transition-opacity duration-100"
                        :class="!sidebarOpen ? 'opacity-0' : 'opacity-100'">
                        @if ($desaList->isEmpty())
                            {{-- Empty state sidebar --}}
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <p class="mt-3 text-sm text-gray-500">
                                    Tidak ada desa dengan data<br>pada periode ini
                                </p>
                            </div>
                        @else
                            {{-- List desa --}}
                            <ul class="space-y-1.5">
                                @foreach ($desaList as $desa)
                                    @php
                                        $isActiveDesa =
                                            $selectedDesa && strtoupper($selectedDesa) === strtoupper($desa);
                                    @endphp
                                    <li>
                                        <a href="{{ route('gizi.index', [
                                            'view' => 'detail',
                                            'period' => $activePeriodYm,
                                            'desa' => $desa,
                                        ]) }}"
                                            class="group flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                                                {{ $isActiveDesa
                                                    ? 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md shadow-emerald-500/30'
                                                    : 'text-gray-700 bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 border border-gray-200 hover:border-emerald-300 hover:shadow-sm' }}">
                                            <span class="truncate uppercase">{{ $desa }}</span>
                                            @if ($isActiveDesa)
                                                <span
                                                    class="ml-2 flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-white/20">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </span>
                                            @else
                                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition-opacity text-emerald-600"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{-- Header kecil mobile di atas konten --}}
        <div
            class="block md:hidden rounded-xl shadow-md border border-gray-200/40 p-3 bg-white/30 backdrop-blur-md mb-3">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col md:flex-row justify-between md:items-center">
                    <div>
                        <h3 class="text-center md:text-left text-sm md:text-lg font-extrabold text-gray-800">
                            Detail Data Gizi per Desa/Kelurahan
                        </h3>
                    </div>
                </div>
            </div>
        </div>
        {{-- Selector desa + periode untuk mobile --}}
        <div class="block lg:hidden bg-white/40 backdrop-blur-md border border-gray-200/40 rounded-xl shadow-md p-3">

            {{-- Dropdown desa mobile --}}
            <label for="mobile-desa-selector" class="text-sm font-bold text-gray-900 flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Pilih Desa/Kelurahan
            </label>

            <select id="mobile-desa-selector"
                class="w-full text-sm rounded-lg border-gray-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm"
                onchange="if (this.value) window.location.href = this.value;">
                @if ($desaList->isEmpty())
                    <option value="" disabled selected>Tidak ada desa tersedia</option>
                @else
                    <option value="" {{ !$selectedDesa ? 'selected' : '' }} disabled>-- Pilih Desa --</option>
                    @foreach ($desaList as $desa)
                        @php
                            $isActiveDesa = $selectedDesa && strtoupper($selectedDesa) === strtoupper($desa);
                        @endphp
                        <option
                            value="{{ route('gizi.index', [
                                'view' => 'detail',
                                'period' => $activePeriodYm,
                                'desa' => $desa,
                            ]) }}"
                            {{ $isActiveDesa ? 'selected' : '' }}>
                            {{ strtoupper($desa) }}
                        </option>
                    @endforeach
                @endif
            </select>
            {{-- Form filter periode (mobile) --}}
            <form method="GET" action="{{ route('gizi.index') }}"
                class="flex flex-col md:flex-row md:items-center gap-3 md:gap-4 mt-2">
                <input type="hidden" name="view" value="detail">
                @if ($selectedDesa)
                    <input type="hidden" name="desa" value="{{ $selectedDesa }}">
                @endif
                <div class="flex items-center gap-2 flex-1 md:flex-initial">
                    <label class="hidden md:block text-sm font-medium text-gray-700 whitespace-nowrap">
                        Periode:
                    </label>
                    <select name="period" required
                        class="flex-1 md:flex-initial md:min-w-[180px] text-sm rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 shadow-sm">
                        @forelse($availablePeriods ?? [] as $p)
                            @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                            <option value="{{ $ym }}" {{ $activePeriodYm === $ym ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                            </option>
                        @empty
                            @php $ymNow = now()->format('Y-m'); @endphp
                            <option value="{{ $ymNow }}" selected>
                                {{ now()->isoFormat('MMMM Y') }}
                            </option>
                        @endforelse
                    </select>
                </div>
                <button type="submit"
                    class="w-full md:w-auto px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 active:scale-95 transition-all duration-200 shadow-md hover:shadow-lg">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Tampilkan
                    </span>
                </button>
            </form>
        </div>
    </div>
    {{-- Konten Utama --}}
    <div class="order-1 lg:order-2 w-full flex-1 min-w-0 z-30">

        {{-- Header desktop detail + filter periode --}}
        <div class="rounded-xl hidden md:block shadow-sm border p-4 bg-white mb-3">
            <div class="flex flex-col gap-4">
                <div class="flex flex-col md:flex-row justify-between md:items-center">
                    <div>
                        <h3 class="text-center md:text-left text-sm md:text-lg font-extrabold text-gray-800">
                            Detail Data Gizi per Desa/Kelurahan
                        </h3>
                    </div>
                    {{-- Form filter periode (desktop) --}}
                    <form method="GET" action="{{ route('gizi.index') }}"
                        class="hidden md:flex flex-col md:flex-row md:items-center gap-3 md:gap-4">
                        <input type="hidden" name="view" value="detail">
                        @if ($selectedDesa)
                            <input type="hidden" name="desa" value="{{ $selectedDesa }}">
                        @endif
                        <div class="flex items-center gap-2 flex-1 md:flex-initial">
                            <label class="hidden md:block text-sm font-medium text-gray-700 whitespace-nowrap">
                                Periode:
                            </label>
                            <select name="period" required
                                class="flex-1 md:flex-initial md:min-w-[180px] text-sm rounded-lg border-gray-300 focus:ring-red-500 focus:border-red-500 shadow-sm">
                                @forelse($availablePeriods ?? [] as $p)
                                    @php $ym = \Carbon\Carbon::parse($p)->format('Y-m'); @endphp
                                    <option value="{{ $ym }}"
                                        {{ $activePeriodYm === $ym ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                    </option>
                                @empty
                                    @php $ymNow = now()->format('Y-m'); @endphp
                                    <option value="{{ $ymNow }}" selected>
                                        {{ now()->isoFormat('MMMM Y') }}
                                    </option>
                                @endforelse
                            </select>
                        </div>
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 rounded-lg hover:from-red-600 hover:to-red-700 active:scale-95 transition-all duration-200 shadow-md hover:shadow-lg">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                Tampilkan
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        {{-- Card utama detail (statistik + tabel) --}}
        <div class="bg-white/30 backdrop-blur-md border border-gray-200/30 rounded-xl px-2 py-2">
            @if (!$selectedDesa)
                {{-- State jika belum memilih desa --}}
                <div class="flex flex-col items-center justify-center py-12 sm:py-16 px-4 text-center">
                    <div
                        class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-2">Pilih Desa</h4>
                        <p class="text-sm text-gray-500 max-w-md">
                            Pilih desa dari daftar untuk melihat detail data gizi
                        </p>
                    </div>
                </div>
            @else
                <div class="spaye-y-4">
                    {{-- Statistik ringkas per desa --}}
                    <div x-data="{ isOpen: false }">
                        {{-- Header toggle statistik --}}
                        <button type="button" @click="isOpen = !isOpen"
                            class="w-full text-left text-sm font-bold text-gray-900 mb-3 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                </svg>
                                Statistik Ringkas ({{ $selectedDesa }})
                            </span>
                            <span class="text-gray-500">
                                <svg x-show="isOpen" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 15l7-7 7 7" />
                                </svg>
                                <svg x-show="!isOpen" style="display: none;" class="w-5 h-5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>
                        {{-- Grid statistik --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3" x-show="isOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2" style="display: none;">
                            {{-- Total anak --}}
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 p-4 hover:shadow-md transition-shadow">
                                <p class="text-[10px] sm:text-xs font-bold text-blue-700 uppercase tracking-wide mb-2">
                                    Total Anak
                                </p>
                                <p class="text-2xl sm:text-3xl font-bold text-blue-900">
                                    {{ $detailStat->total_anak ?? 0 }}
                                </p>
                            </div>
                            {{-- Stunting --}}
                            <div
                                class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 p-4 hover:shadow-md transition-shadow">
                                <p class="text-[10px] sm:text-xs font-bold text-red-700 uppercase tracking-wide mb-2">
                                    Stunting
                                </p>
                                <p class="text-2xl sm:text-3xl font-bold text-red-900">
                                    {{ $detailStat->total_stunting ?? 0 }}
                                </p>
                                <p class="text-[10px] text-red-600 mt-1">TB/U &lt; -2</p>
                            </div>
                            {{-- Rata-rata tinggi --}}
                            <div
                                class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-xl border border-emerald-200 p-4 hover:shadow-md transition-shadow">
                                <p
                                    class="text-[10px] sm:text-xs font-bold text-emerald-700 uppercase tracking-wide mb-2">
                                    Avg Tinggi
                                </p>
                                <p class="text-2xl sm:text-3xl font-bold text-emerald-900">
                                    {{ $detailStat && $detailStat->avg_tinggi ? number_format($detailStat->avg_tinggi, 1) : '-' }}
                                </p>
                                <p class="text-[10px] text-emerald-600 mt-1">cm</p>
                            </div>
                            {{-- Rata-rata ZS TB/U --}}
                            <div
                                class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 p-4 hover:shadow-md transition-shadow">
                                <p
                                    class="text-[10px] sm:text-xs font-bold text-purple-700 uppercase tracking-wide mb-2">
                                    Avg ZS TB/U
                                </p>
                                <div
                                    class="text-2xl sm:text-3xl font-bold text-purple-900 flex items-center h-9 sm:h-10">
                                    @if ($detailStat && !is_null($detailStat->avg_zs_tbu))
                                        <x-zscore-badge :value="$detailStat->avg_zs_tbu" />
                                    @else
                                        <span class="text-gray-400 text-2xl sm:text-3xl">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 mb-1"></div>
                    {{-- Tabel data individu --}}
                    <div>
                        {{-- Header tabel + label periode --}}
                        <div class="flex gap-2">
                            <h5 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Data Individu
                            </h5>
                            <div class="text-sm text-gray-500">
                                {{ $currentPeriodLabel }}
                            </div>
                        </div>
                        {{-- Wrapper tabel + scroll --}}
                        <div class="relative">
                            <div class="max-h-[64vh] md:max-h-[49vh] overflow-y-auto overflow-x-auto">
                                <table class="w-full min-w-[900px] text-sm text-left text-gray-700">
                                    {{-- Header tabel sticky --}}
                                    <thead class="sticky top-0 z-20 bg-white shadow-sm">
                                        <tr class="border-b border-gray-200/80">
                                            <th
                                                class="px-3 md:px-4 py-3 text-left text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                No
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-left text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                Tanggal Input
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                Berat (kg)
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                Tinggi (cm)
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                BB/U
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                ZS BB/U
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                TB/U
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                ZS TB/U
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                BB/TB
                                            </th>
                                            <th
                                                class="px-3 md:px-4 py-3 text-center text-[10px] md:text-xs font-semibold tracking-wider uppercase text-gray-600">
                                                ZS BB/TB
                                            </th>
                                        </tr>
                                    </thead>
                                    {{-- Body tabel --}}
                                    <tbody class="bg-white divide-y divide-gray-100">
                                        @forelse ($dataDetail as $row)
                                            <tr class="transition-colors even:bg-gray-50/60 hover:bg-emerald-50/60">
                                                {{-- Nomor urut global (pagination aware) --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-600 font-medium align-top">
                                                    {{ $dataDetail->firstItem() + $loop->index }}
                                                </td>
                                                {{-- Tanggal input --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-700 align-top">
                                                    {{ \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') }}
                                                </td>
                                                {{-- Berat --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ number_format($row->berat, 2) }}
                                                </td>
                                                {{-- Tinggi --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-xs md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ number_format($row->tinggi, 2) }}
                                                </td>
                                                {{-- Status BB/U --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ $row->status_bb_u ?? '-' }}
                                                </td>
                                                {{-- Z-score BB/U --}}
                                                <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                    <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                        <x-zscore-badge :value="$row->zs_bb_u" />
                                                    </div>
                                                </td>
                                                {{-- Status TB/U --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ $row->status_tb_u ?? '-' }}
                                                </td>
                                                {{-- Z-score TB/U --}}
                                                <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                    <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                        <x-zscore-badge :value="$row->zs_tb_u" />
                                                    </div>
                                                </td>
                                                {{-- Status BB/TB --}}
                                                <td
                                                    class="whitespace-nowrap px-4 py-3 text-[11px] md:text-sm text-gray-900 text-center font-semibold align-top">
                                                    {{ $row->status_bb_tb ?? '-' }}
                                                </td>
                                                {{-- Z-score BB/TB --}}
                                                <td class="whitespace-nowrap px-4 py-3 text-xs text-center align-top">
                                                    <div class="flex flex-col items-center gap-0.5 sm:gap-1">
                                                        <x-zscore-badge :value="$row->zs_bb_tb" />
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            {{-- Empty state tabel --}}
                                            <tr>
                                                <td colspan="10"
                                                    class="px-6 py-10 text-center text-sm text-gray-500">
                                                    <div class="flex flex-col items-center gap-3">
                                                        <svg class="mx-auto h-10 w-10 text-gray-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                        </svg>
                                                        <div>
                                                            <p class="font-medium">
                                                                Tidak ada data individu untuk desa ini pada periode ini.
                                                            </p>
                                                            <p class="text-xs text-gray-400 mt-1">
                                                                Coba pilih periode lain atau pastikan data sudah diinput
                                                                oleh divisi terkait.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- Pagination bawaan Laravel --}}
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
