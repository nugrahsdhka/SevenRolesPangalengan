<x-guest-layout>
    <x-slot name="title">
        RSP | Data Wilayah
    </x-slot>

    @include('layouts.nav_public')

    @php
        $availablePeriods = $availablePeriods ?? collect();
        $selectedPeriod = $selectedPeriod ?? request('period');
        $search = $search ?? request()->query('search');
    @endphp

    <div class="pb-2">
        <section id="tab-wilayah">
            <div class="px-2 space-y-3">

                {{-- ========== HEADER: TITLE + FILTER PERIODE ========== --}}
                <div class="pt-3">
                    <div class="bg-white backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                        <div class="p-3">
                            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">

                                {{-- Title Section --}}
                                <div class="w-full lg:w-auto">
                                    <div class="flex justify-between">
                                        <div>
                                            <h1 class="text-xl font-bold text-gray-900">
                                                Tabel Data Wilayah
                                            </h1>
                                            <p class="mt-1 text-sm text-gray-500">
                                                Stunting Kecamatan Pangalengan
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Filter & Button Section --}}
                                <div class="flex items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                    <div class="flex">
                                        <div
                                            class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                            <form action="{{ route('data-wilayah') }}" method="GET"
                                                  id="wilayahFilterFormHeader"
                                                  class="p-3 sm:p-4 flex flex-wrap items-end gap-3 sm:gap-4">

                                                {{-- Periode --}}
                                                <div class="flex-1 min-w-[200px]">
                                                    <label
                                                        class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-1">
                                                        <div
                                                            class="flex-shrink-0 w-5 h-5 bg-red-100 rounded-lg flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5 text-red-600" fill="none"
                                                                 stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                      stroke-width="2"
                                                                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                        Periode
                                                    </label>

                                                    <select id="wilayahPeriodeInput" name="period"
                                                            class="w-full sm:w-48 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700
                                                                   focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:outline-none
                                                                   hover:border-gray-400 transition-colors duration-200">
                                                        <option value="" @selected(empty($selectedPeriod))>
                                                            Semua Periode
                                                        </option>
                                                        @forelse ($availablePeriods as $ym)
                                                            @php
                                                                try {
                                                                    $periodCarbon = \Carbon\Carbon::createFromFormat(
                                                                        'Y-m',
                                                                        $ym,
                                                                    );
                                                                    $label = $periodCarbon->isoFormat('MMMM Y');
                                                                } catch (\Exception $e) {
                                                                    $label = $ym;
                                                                }
                                                            @endphp
                                                            <option value="{{ $ym }}"
                                                                @selected($selectedPeriod === $ym)>
                                                                {{ $label }}
                                                            </option>
                                                        @empty
                                                            <option value="{{ now()->format('Y-m') }}" selected>
                                                                {{ now()->isoFormat('MMMM Y') }}
                                                            </option>
                                                        @endforelse
                                                    </select>
                                                </div>

                                                {{-- Search Desa --}}
                                                <div class="flex-1 min-w-[200px]">
                                                    <label
                                                        class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-1">
                                                        <div
                                                            class="flex-shrink-0 w-5 h-5 bg-red-100 rounded-lg flex items-center justify-center">
                                                            <svg class="w-3.5 h-3.5 text-red-600"
                                                                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                  fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                                      clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <span>Pencarian Desa</span>
                                                    </label>
                                                    <div class="relative group">
                                                        <div
                                                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-500 transition-colors duration-200"
                                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                 fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4-4.816A6 6 0 012 8z"
                                                                      clip-rule="evenodd" />
                                                            </svg>
                                                        </div>
                                                        <input type="text" name="search" id="search"
                                                               class="block w-full pl-11 pr-4 py-2.5 text-sm border-2 border-gray-200 rounded-xl bg-white
                                                                      focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:outline-none
                                                                      hover:border-gray-300 hover:shadow-sm transition-all duration-200
                                                                      placeholder-gray-400"
                                                               placeholder="Cari nama desa..." value="{{ $search ?? '' }}">
                                                    </div>
                                                </div>

                                                {{-- Tombol Submit --}}
                                                <div class="w-full sm:w-auto flex justify-end">
                                                    <button type="submit"
                                                            class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl text-sm font-semibold
                                                                   shadow-md hover:shadow-xl hover:from-red-700 hover:to-pink-700
                                                                   transform hover:scale-105 active:scale-95 transition-all duration-200
                                                                   flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                             viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  stroke-width="2" d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                                                        </svg>
                                                        <span>Tampilkan Data</span>
                                                    </button>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== CARD TABEL ========== --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="relative">
                        <div class="max-h-[64vh] md:max-h-[64vh] overflow-y-auto overflow-x-auto bg-white">
                            <table class="w-full min-w-[1100px] table-auto divide-y divide-gray-200">

                                {{-- THEAD (STICKY) --}}
                                <thead class="sticky top-0 z-20 bg-gradient-to-r from-gray-50 to-gray-100 shadow-sm">
                                    <tr class="uppercase border-b border-gray-200 text-[11px] tracking-widest text-gray-700">
                                        <th class="px-4 sm:px-6 py-3.5 text-left font-bold">
                                            desa
                                        </th>
                                        <th class="px-4 sm:px-6 py-3.5 text-center font-bold whitespace-nowrap md:whitespace-normal">
                                            <p>rata-rata</p>
                                            <p>tinggi (cm)</p>
                                        </th>
                                        <th class="px-4 sm:px-6 py-3.5 text-center font-bold whitespace-nowrap md:whitespace-normal">
                                            <p>rata-rata</p>
                                            <p>berat (kg)</p>
                                        </th>
                                        <th class="px-4 sm:px-6 py-3.5 text-center font-bold">
                                            total anak
                                        </th>
                                        <th class="px-4 sm:px-6 py-3.5 text-center font-bold whitespace-nowrap md:whitespace-normal">
                                            <p>rata-rata</p>
                                            <p>zs bb/u</p>
                                        </th>
                                        <th class="px-4 sm:px-6 py-3.5 text-center font-bold whitespace-nowrap md:whitespace-normal">
                                            <p>rata-rata</p>
                                            <p>zs tb/u</p>
                                        </th>
                                        <th class="px-4 sm:px-6 py-3.5 text-center font-bold whitespace-nowrap md:whitespace-normal">
                                            <p>rata-rata</p>
                                            <p>zs bb/tb</p>
                                        </th>
                                        @php
                                            $currentPeriod = request('period') ?? ($selectedPeriod ?? 'semua periode');
                                        @endphp
                                        <th class="px-4 sm:px-6 py-3.5 text-right font-bold whitespace-nowrap">
                                            <p class="m-0">periode</p>
                                            <p class="m-0 text-[10px] text-gray-500 font-medium">
                                                ({{ $currentPeriod }})
                                            </p>
                                        </th>
                                        <th class="px-4 sm:px-6 py-3.5 text-center font-bold whitespace-nowrap">
                                            Detail
                                        </th>
                                    </tr>
                                </thead>

                                {{-- TBODY --}}
                                <tbody class="bg-white divide-y divide-gray-100 text-sm">
                                    @forelse ($dataWilayah as $data)
                                        @php
                                            $avgZsBbU =
                                                $data->total_anak > 0 && !is_null($data->sum_zs_bbu)
                                                    ? $data->sum_zs_bbu / $data->total_anak
                                                    : null;
                                            $avgZsTbU =
                                                $data->total_anak > 0 && !is_null($data->sum_zs_tbu)
                                                    ? $data->sum_zs_tbu / $data->total_anak
                                                    : null;
                                            $avgZsBbTb =
                                                $data->total_anak > 0 && !is_null($data->sum_zs_bbtb)
                                                    ? $data->sum_zs_bbtb / $data->total_anak
                                                    : null;
                                        @endphp

                                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-indigo-50/60 transition-colors duration-150">

                                            {{-- Desa --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap">
                                                <span class="flex items-center gap-2 text-[13px] font-semibold text-gray-900">
                                                    <span class="truncate max-w-[220px] sm:max-w-[260px]">
                                                        {{ $data->desa_kelurahan }}
                                                    </span>
                                                </span>
                                            </td>

                                            {{-- Rata-rata Tinggi --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-center">
                                                <span class="text-[13px] text-gray-700">
                                                    {{ is_null($data->avg_tb) ? 'N/A' : number_format($data->avg_tb, 1) }}
                                                </span>
                                            </td>

                                            {{-- Rata-rata Berat --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-center">
                                                <span class="text-[13px] text-gray-700">
                                                    {{ is_null($data->avg_bb) ? 'N/A' : number_format($data->avg_bb, 1) }}
                                                </span>
                                            </td>

                                            {{-- Total Anak --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-center">
                                                <span class="text-[13px] text-gray-700 font-medium">
                                                    {{ number_format($data->total_anak) }}
                                                </span>
                                            </td>

                                            {{-- Rata-rata ZS BB/U --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $avgZsBbU])
                                            </td>

                                            {{-- Rata-rata ZS TB/U --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $avgZsTbU])
                                            </td>

                                            {{-- Rata-rata ZS BB/TB --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', ['value' => $avgZsBbTb])
                                            </td>

                                            {{-- Periode --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-right">
                                                <div class="flex items-center gap-2 justify-end">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-[13px] text-gray-700 font-medium">
                                                        {{ $data->periode ? \Carbon\Carbon::parse($data->periode)->format("M 'y") : '-' }}
                                                    </span>
                                                </div>
                                            </td>

                                            {{-- Detail --}}
                                            <td class="px-4 sm:px-6 py-3.5 whitespace-nowrap text-center">
                                                <div class="relative inline-flex group">
                                                    <a href="{{ route('data-wilayah-detail-gizi', [
                                                            'desa'   => $data->desa_kelurahan,
                                                            'period' => $selectedPeriod,
                                                        ]) }}"
                                                    class="inline-flex items-center justify-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                                            class="w-4 h-4 text-blue-900">
                                                            <circle cx="50" cy="50" r="45" fill="none" stroke="#000" stroke-width="2"/>
                                                            <line x1="50" y1="25" x2="50" y2="60" stroke="#000" stroke-width="3" stroke-linecap="round"/>
                                                            <circle cx="50" cy="70" r="3" fill="#000"/>
                                                        </svg>
                                                    </a>
                                                    <span class="pointer-events-none absolute left-1/2 -translate-x-1/2 -top-8
                                                                px-2 py-1 text-[11px] text-white bg-gray-800 rounded-md shadow-lg
                                                                opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100
                                                                transition duration-200  z-50">
                                                        Detail
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0 18 0z" />
                                                    </svg>
                                                    <p class="text-sm font-medium text-gray-500">
                                                        Tidak ada data yang ditemukan
                                                    </p>
                                                    @if ($search)
                                                        <p class="text-xs text-gray-400 mt-1">
                                                            Coba ubah kata kunci pencarian Anda.
                                                        </p>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>

                    {{-- PAGINATION --}}
                    @if ($dataWilayah instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-xs text-gray-500">
                                    Halaman
                                    <span class="font-semibold text-gray-700">{{ $dataWilayah->currentPage() }}</span>
                                    dari
                                    <span class="font-semibold text-gray-700">{{ $dataWilayah->lastPage() }}</span>
                                </div>
                                <div>
                                    {{ $dataWilayah->appends([
                                        'search' => $search,
                                        'period' => $selectedPeriod,
                                    ])->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </section>
    </div>
</x-guest-layout>
