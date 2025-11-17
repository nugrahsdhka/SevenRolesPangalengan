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

                                        {{-- Info Button - Mobile --}}
                                        <button id="openInfoModalBtnMobile"
                                            class="block md:hidden flex-shrink-0 self-center sm:self-auto p-3 text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-xl
                                                shadow-lg hover:shadow-xl hover:from-red-700 hover:to-pink-700
                                                transform hover:scale-110 active:scale-95 transition-all duration-200
                                                group relative">
                                            <svg class="w-6 h-6 transition-transform group-hover:rotate-12 duration-200"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Filter & Button Section --}}
                                <div class="flex items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                    {{-- Filter Periode + Search --}}
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
                                                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
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

                                    {{-- Tombol Info Modal - Desktop (kanan) --}}
                                    <button id="openInfoModalBtnDesktop"
                                        class="hidden md:block flex-shrink-0 self-center sm:self-auto p-3 text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-xl
                                            shadow-lg hover:shadow-xl hover:from-red-700 hover:to-pink-700
                                            transform hover:scale-110 active:scale-95 transition-all duration-200
                                            group relative">
                                        <svg class="w-6 h-6 transition-transform group-hover:rotate-12 duration-200"
                                            fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                                        </svg>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== CARD TABEL ========== --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="relative">
                        {{-- SCROLL CONTAINER: VERTICAL + HORIZONTAL --}}
                        <div class="max-h-[64vh] md:max-h-[64vh] overflow-y-auto overflow-x-auto bg-white">
                            <table class="w-full min-w-[1200px] divide-y divide-gray-200">

                                {{-- THEAD (STICKY) --}}
                                <thead class="sticky top-0 z-20 bg-gradient-to-r from-gray-50 to-gray-100 shadow-sm">
                                    <tr class="uppercase border-b border-gray-200">

                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 tracking-wider">
                                            desa
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                            <p>
                                                rata-rata
                                            </p>
                                            <p>tinggi (cm)</p>
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                            <p>
                                                rata-rata
                                            </p>
                                            <p>berat (kg)</p>
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                                            total anak
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                            <p>
                                                rata-rata
                                            </p>
                                            <p>zs bb/u</p>
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                            <p>
                                                rata-rata
                                            </p>
                                            <p>zs tb/u</p>
                                        </th>

                                        <th scope="col"
                                            class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap md:whitespace-normal">
                                            <p>
                                                rata-rata
                                            </p>
                                            <p>zs bb/tb</p>
                                        </th>

                                        @php
                                            $currentPeriod = request('period') ?? ($selectedPeriod ?? 'semua periode');
                                        @endphp

                                        <th scope="col"
                                            class="px-6 py-4 text-right text-xs font-bold text-gray-700 tracking-wider whitespace-nowrap">

                                            <p class="m-0">
                                                periode
                                            </p>

                                            <p class="m-0 text-[10px] text-gray-500 font-medium">
                                                ({{ $currentPeriod }})
                                            </p>
                                        </th>
                                    </tr>
                                </thead>

                                {{-- TBODY --}}
                                <tbody class="bg-white divide-y divide-gray-100">
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

                                        <tr class="hover:bg-gray-50 transition-colors duration-150">

                                            {{-- Desa --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-sm font-semibold text-gray-900">
                                                    {{ $data->desa_kelurahan }}
                                                </span>
                                            </td>

                                            {{-- Rata-rata Tinggi --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm text-gray-700">
                                                    {{ is_null($data->avg_tb) ? 'N/A' : number_format($data->avg_tb, 1) }}
                                                </span>
                                            </td>

                                            {{-- Rata-rata Berat --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm text-gray-700">
                                                    {{ is_null($data->avg_bb) ? 'N/A' : number_format($data->avg_bb, 1) }}
                                                </span>
                                            </td>

                                            {{-- Total Anak --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="text-sm text-gray-700">
                                                    {{ number_format($data->total_anak) }}
                                                </span>
                                            </td>

                                            {{-- Rata-rata ZS BB/U --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', [
                                                    'value' => $avgZsBbU,
                                                ])
                                            </td>

                                            {{-- Rata-rata ZS TB/U --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', [
                                                    'value' => $avgZsTbU,
                                                ])
                                            </td>

                                            {{-- Rata-rata ZS BB/TB --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @include('components.zscore-badge', [
                                                    'value' => $avgZsBbTb,
                                                ])
                                            </td>

                                            {{-- Periode --}}
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="flex items-center gap-2 justify-end">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <span class="text-sm text-gray-700 font-medium">
                                                        {{ $data->periode ? \Carbon\Carbon::parse($data->periode)->format("M 'y") : '-' }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                </div>

                {{-- Modal Informasi Kolom Tabel --}}
                <div id="infoModalOverlay"
                    class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 hidden">
                    <div id="infoModalContent"
                        class="relative mx-auto border w-full max-w-md sm:max-w-lg md:max-w-xl shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0">
                        {{-- Modal Header --}}
                        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                            <p class="text-lg font-bold text-gray-800">Keterangan Kolom Tabel Data Wilayah</p>
                            <button id="closeInfoModalBtnHeader"
                                class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="px-6 py-5 space-y-4 text-sm text-gray-700 max-h-[70vh] overflow-y-auto">
                            <ul class="list-disc list-inside space-y-3 pl-4">
                                <li>
                                    <strong>Desa:</strong> Nama desa yang datanya diagregasi.
                                </li>

                                <li>
                                    <strong>Rata-Rata Tinggi (cm):</strong> Rata-rata tinggi badan (TB) anak dalam
                                    centimeter (cm) di desa tersebut pada periode ini.
                                </li>
                                <li>
                                    <strong>Rata-Rata Berat (kg):</strong> Rata-rata berat badan (BB) anak dalam
                                    kilogram (kg) di desa tersebut pada periode ini.
                                </li>

                                <li>
                                    <strong>Total Anak:</strong> Jumlah total anak yang datanya masuk dalam agregasi di
                                    periode tersebut.
                                </li>

                                <li>
                                    <strong>Rata-Rata ZS (BB/U, TB/U, BB/TB):</strong> Rata-rata Z-Score (Standar
                                    Deviasi) untuk tiga indikator:
                                    <ul class="ml-6 mt-1 space-y-1 text-xs list-none">
                                        <li><strong>BB/U:</strong> Berat Badan menurut Umur</li>
                                        <li><strong>TB/U:</strong> Tinggi Badan menurut Umur (Indikator Stunting)</li>
                                        <li><strong>BB/TB:</strong> Berat Badan menurut Tinggi Badan</li>
                                    </ul>
                                </li>

                                <li>
                                    <strong>Klasifikasi Warna Z-Score:</strong>
                                    <ul class="ml-6 mt-1 space-y-1 text-xs list-none">
                                        <li>
                                            <span
                                                class="inline-block w-3 h-3 rounded-full mr-2 bg-blue-500 align-middle"></span>
                                            <strong> Biru (Tinggi/Gizi Lebih):</strong> (&gt; +2 SD)
                                        </li>
                                        <li>
                                            <span
                                                class="inline-block w-3 h-3 rounded-full mr-2 bg-green-500 align-middle"></span>
                                            <strong>Hijau (Normal):</strong> (-2 SD s/d +2 SD)
                                        </li>
                                        <li>
                                            <span
                                                class="inline-block w-3 h-3 rounded-full mr-2 bg-orange-500 align-middle"></span>
                                            <strong>Oranye (Kekurangan Gizi/Pendek):</strong> (-3 SD s/d &lt; -2 SD)
                                        </li>
                                        <li>
                                            <span
                                                class="inline-block w-3 h-3 rounded-full mr-2 bg-red-500 align-middle"></span>
                                            <strong>Merah (Kekurangan Gizi Akut/Sangat Pendek):</strong> (&lt; -3 SD)
                                        </li>
                                        <li>
                                            <span
                                                class="inline-block w-3 h-3 rounded-full mr-2 bg-gray-400 align-middle"></span>
                                            <strong>Abu-abu (N/A):</strong> (Data tidak cukup)
                                        </li>
                                    </ul>
                                </li>

                                <li>
                                    <strong>Periode:</strong> Bulan dan tahun saat data ini diinputkan dan dipublikasi.
                                </li>
                            </ul>

                            <p class="mt-3 text-xs text-gray-500">
                                <em>*SD = Standar Deviasi dari median standar pertumbuhan WHO.</em>
                            </p>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-right rounded-b-xl">
                            <button id="closeInfoModalBtnFooter"
                                class="px-5 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

    {{-- SCRIPT MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const overlay = document.getElementById('infoModalOverlay');
            const modal = document.getElementById('infoModalContent');

            const openButtons = [
                document.getElementById('openInfoModalBtnMobile'),
                document.getElementById('openInfoModalBtnDesktop'),
            ].filter(Boolean);

            const closeButtons = [
                document.getElementById('closeInfoModalBtnHeader'),
                document.getElementById('closeInfoModalBtnFooter'),
            ].filter(Boolean);

            function openModal() {
                if (!overlay || !modal) return;

                overlay.classList.remove('hidden');

                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0', 'scale-95');
                    modal.classList.add('opacity-100', 'scale-100');
                });
            }

            function closeModal() {
                if (!overlay || !modal) return;

                modal.classList.add('opacity-0', 'scale-95');
                modal.classList.remove('opacity-100', 'scale-100');

                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 200);
            }

            openButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openModal();
                });
            });

            closeButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeModal();
                });
            });

            if (overlay) {
                overlay.addEventListener('click', function(e) {
                    if (e.target === overlay) {
                        closeModal();
                    }
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && overlay && !overlay.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });
    </script>
</x-guest-layout>
