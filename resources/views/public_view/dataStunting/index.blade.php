<x-guest-layout>
    <x-slot name="title">
        Data Stunting
    </x-slot>

    {{-- Navigasi Publik --}}
    @include('layouts.nav_public')

    {{-- Tab Navigation --}}
    @php $queryParams = request()->query(); @endphp
    <div class="border-b border-gray-200 bg-gray-50 mb-6">
        <div class="flex">
            {{-- Tombol Tab Tabel --}}
            <a href="#"
               onclick="event.preventDefault(); setActiveTab('table');"
               id="tab-button-table"
               data-target="tab-table"
               class="tab-button relative flex-1 px-6 py-4 text-sm font-semibold focus:outline-none group transition-colors duration-200 {{ $currentView === 'table' ? 'text-gray-900 bg-white' : 'text-gray-500 bg-gray-50 hover:text-gray-700 hover:bg-gray-100' }}">
                <div class="flex items-center justify-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>Tabel</div>
                <div class="tab-indicator absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-pink-600 {{ $currentView !== 'table' ? 'hidden' : '' }}"></div>
            </a>
            {{-- Tombol Tab Chart --}}
            <a href="#"
               onclick="event.preventDefault(); setActiveTab('chart');"
               id="tab-button-chart"
               data-target="tab-chart"
               class="tab-button relative flex-1 px-6 py-4 text-sm font-semibold focus:outline-none group transition-colors duration-200 {{ $currentView === 'chart' ? 'text-gray-900 bg-white' : 'text-gray-500 bg-gray-50 hover:text-gray-700 hover:bg-gray-100' }}">
                <div class="flex items-center justify-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>Chart</div>
                 <div class="tab-indicator absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-pink-600 {{ $currentView !== 'chart' ? 'hidden' : '' }}"></div>
            </a>
        </div>
    </div>

    {{-- Filter Card (Hanya Tampil di Tab Tabel) --}}
    <div x-data="{ isTableActive: @json($currentView === 'table') }" x-show="isTableActive" x-transition>
        <div class="p-2">
            <div class="mb-6">
                <div class="bg-white backdrop-blur-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-6">
                        {{-- Form Filter --}}
                        <form action="{{ route('data-stunting') }}" method="GET" x-ref="filterForm">
                            <input type="hidden" name="view" id="viewInput" value="{{ $currentView }}">
                            <div class="flex flex-col lg:flex-row items-stretch lg:items-end gap-4">
                                {{-- Search Input --}}
                                <div class="flex-1">
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Pencarian</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                                        </div>
                                        <input type="text" name="search" id="search"
                                               @input.debounce.500ms="$refs.filterForm.submit()"
                                               class="block w-full pl-12 pr-4 py-3 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                                               placeholder="Cari nama desa..."
                                               value="{{ $search ?? '' }}">
                                    </div>
                                </div>
                                {{-- Tingkat Filter (Z-Score) --}}
                                <div class="w-full lg:w-56">
                                    <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Status Desa (ZS TB/U)</label>
                                    <div class="relative">
                                        <select id="tingkat_zs" name="tingkat_zs"
                                                @change="$refs.filterForm.submit()"
                                                class="block w-full pl-4 pr-10 py-3 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 appearance-none bg-white cursor-pointer">
                                            <option value="" @selected(empty($tingkat_zs))>Semua Status</option>
                                            <option value="na" @selected(($tingkat_zs ?? '') == 'na')>⚪ N/A</option>
                                            <option value="sangat_pendek" @selected(($tingkat_zs ?? '') == 'sangat_pendek')>🔴 Sangat Pendek (< -3)</option>
                                            <option value="pendek" @selected(($tingkat_zs ?? '') == 'pendek')>🟠 Pendek (-3 s/d < -2)</option>
                                            <option value="normal" @selected(($tingkat_zs ?? '') == 'normal')>🟢 Normal (-2 s/d 2)</option>
                                            <option value="tinggi" @selected(($tingkat_zs ?? '') == 'tinggi')>🔵 Tinggi (> 2)</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none"><svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>{{-- Akhir div x

    {{-- ===== Konten Tab: TABLE ===== --}}
    <section id="tab-table" class="{{ $currentView === 'table' ? '' : 'hidden' }}">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">

                {{-- Judul Tabel dan Tombol Info --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                    <h2 class="text-xl font-bold text-gray-900">Table Data Stunting</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Informasi lengkap tingkat stunting per desa
                    </p>
                    </div>

                    {{-- ✅ Tombol Info Dikasih ID --}}
                    <button
                    id="openInfoModalBtn"
                    class="p-2.5 text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-xl
                            hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                    >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        />
                    </svg>
                    </button>
                </div>

                {{-- Tabel --}}
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">

                        {{-- THEAD --}}
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr class="uppercase">
                            <th
                            scope="col"
                            class="px-6 py-4 text-left text-xs font-bold text-gray-700 tracking-wider"
                            >
                            <div class="flex items-center gap-2">
                                desa
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                                </svg>
                            </div>
                            </th>

                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                            kasus stunting
                            </th>

                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                            total anak
                            </th>

                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                            rata-rata zs tb/u
                            </th>

                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                            status desa
                            </th>

                            <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 tracking-wider">
                            periode
                            </th>
                        </tr>
                        </thead>

                        {{-- TBODY --}}
                        <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($dataStunting as $data)
                            @php
                            $avgZsTbU = $data->avg_zs_tbu;
                            @endphp

                            <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-150 group">
                            {{-- Desa --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                <div
                                    class="w-2 h-2 bg-red-500 rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"
                                ></div>
                                <span class="text-sm font-semibold text-gray-900">
                                    {{ $data->desa_kelurahan }}
                                </span>
                                </div>
                            </td>

                            {{-- Kasus Stunting --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-bold text-gray-900">
                                {{ number_format($data->total_kasus_stunting) }}
                                </span>
                            </td>

                            {{-- Total Anak --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm text-gray-700">
                                {{ number_format($data->total_anak) }}
                                </span>
                            </td>

                            {{-- Rata-rata ZS TB/U --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span
                                class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm
                                    @if (is_null($avgZsTbU) || $data->total_anak == 0)
                                    bg-gradient-to-r from-gray-400 to-gray-500 text-white
                                    @elseif ($avgZsTbU < -3)
                                    bg-gradient-to-r from-red-500 to-red-600 text-white
                                    @elseif ($avgZsTbU < -2)
                                    bg-gradient-to-r from-orange-500 to-orange-600 text-white
                                    @elseif ($avgZsTbU <= 2)
                                    bg-gradient-to-r from-green-500 to-green-600 text-white
                                    @else
                                    bg-gradient-to-r from-blue-500 to-blue-600 text-white
                                    @endif"
                                >
                                @if (is_null($avgZsTbU) || $data->total_anak == 0)
                                    N/A
                                @else
                                    {{ number_format($avgZsTbU, 2) }}
                                @endif
                                </span>
                            </td>

                            {{-- Status Desa --}}
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                @if (is_null($avgZsTbU) || $data->total_anak == 0)
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm
                                            bg-gradient-to-r from-gray-400 to-gray-500 text-white">
                                    N/A
                                </span>
                                @elseif ($avgZsTbU < -3)
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm
                                            bg-gradient-to-r from-red-500 to-red-600 text-white">
                                    Sangat Pendek ({{ number_format($avgZsTbU, 2) }})
                                </span>
                                @elseif ($avgZsTbU < -2)
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm
                                            bg-gradient-to-r from-orange-500 to-orange-600 text-white">
                                    Pendek ({{ number_format($avgZsTbU, 2) }})
                                </span>
                                @elseif ($avgZsTbU <= 2)
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm
                                            bg-gradient-to-r from-green-500 to-green-600 text-white">
                                    Normal ({{ number_format($avgZsTbU, 2) }})
                                </span>
                                @else
                                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm
                                            bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                                    Tinggi ({{ number_format($avgZsTbU, 2) }})
                                </span>
                                @endif
                            </td>

                            {{-- Periode --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex items-center gap-2 justify-end">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                                <span class="text-sm text-gray-700 font-medium">
                                    {{ \Carbon\Carbon::parse($data->periode)->format("M 'y") }}
                                </span>
                                </div>
                            </td>
                            </tr>

                        @empty
                            <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-sm font-medium text-gray-500">
                                    Tidak ada data yang ditemukan
                                </p>
                                </div>
                            </td>
                            </tr>
                        @endforelse
                        </tbody>

                    </table>
                    </div>
                </div>

                {{-- Paginasi Tabel --}}
                <div class="mt-6 p-6 border-t border-gray-100">
                    {{ $dataStunting->links() }}
                </div>

            </div>
        </div>

        {{-- Modal Informasi Kolom Tabel (STYLE DISAMAKAN DENGAN CHART) --}}
        <div id="infoModalOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 hidden">
            {{-- Kontainer Modal --}}
            <div
                class="relative mx-auto border w-full max-w-md sm:max-w-lg md:max-w-xl shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0"
                id="infoModalContent"
                @click.away="closeInfoModal()" {{-- Alpine Opsional --}}
                x-trap.inert="open" {{-- Alpine Opsional --}}
                >
                {{-- Modal Header --}}
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <p class="text-lg font-bold text-gray-800">Keterangan Kolom Tabel</p>
                    <button id="closeInfoModalBtnHeader" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-5 space-y-4 text-sm text-gray-700 max-h-[70vh] overflow-y-auto">
                    <ul class="list-disc list-inside space-y-1 pl-4">
                        <li>
                            <strong>Desa:</strong> Nama desa yang ada di Kecamatan Pangalengan.
                        </li>

                        <li>
                            <strong>Kasus Stunting:</strong> Jumlah anak yang terindikasi stunting berdasarkan ZS TB/U.
                        </li>

                        <li>
                            <strong>Total Anak:</strong> Jumlah anak yang mendapatkan pemeriksaan stunting di setiap desa.
                        </li>

                        <li>
                            <strong>Rata-Rata ZS TB/U:</strong> Rata-rata ZS TB/U dari setiap anak yang diperiksa di desa tersebut.
                        </li>

                        <li>
                            <strong>Status Desa:</strong> Klasifikasi desa berdasarkan nilai rata-rata ZS TB/U. 
                            <ul class="ml-6 mt-1 space-y-1 text-xs list-none">
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-blue-500 align-middle"></span>
                                    <strong> Tinggi:</strong> (&gt; +2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-green-500 align-middle"></span>
                                    <strong>Normal:</strong> (-2 SD s/d +2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-orange-500 align-middle"></span>
                                    <strong>Pendek:</strong> (-3 SD s/d &lt; -2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-red-500 align-middle"></span>
                                    <strong>Sangat Pendek:</strong> (&lt; -3 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-gray-400 align-middle"></span>
                                    <strong>N/A:</strong> (Data tidak cukup)
                                </li>
                            </ul>
                        </li>

                        <li>
                            <strong>Periode:</strong> Bulan dan tahun saat data ini di inputkan.
                        </li>
                    </ul>

                    <p class="mt-3 text-xs text-gray-500">
                        <em>*SD = Standar Deviasi dari median standar pertumbuhan WHO.</em>
                    </p>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-right rounded-b-xl">
                    <button id="closeInfoModalBtnFooter" class="px-5 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </section>


    {{-- Konten Tab: CHART --}}
    <section id="tab-chart" class="{{ $currentView === 'chart' ? '' : 'hidden' }}">
        <div class="bg-gradient-to-br from-blue-50/30 via-white to-purple-50/20 shadow-2xl rounded-3xl overflow-hidden border border-gray-100/50">
            <!-- Chart Container -->
            <div class="p-4 sm:p-8">
                <!-- Header Section -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Chart Data Stunting</h2>
                        <p class="text-sm text-gray-500 mt-1">Visualisasi data Z-Score dari desa paling tidak stunting ke paling stunting</p>
                    </div>
                    <button
                    id="openChartInfoModalBtn"
                    class="p-2.5 text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-xl
                            hover:shadow-lg transform hover:scale-105 transition-all duration-200"
                    >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path
                        fill-rule="evenodd"
                        clip-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        />
                    </svg>
                    </button>
                </div>

                <div class="bg-white rounded-2xl shadow-xl border border-gray-200/50 p-4 sm:p-6 backdrop-blur-sm">
                    <div class="relative h-[450px] sm:h-[550px] md:h-[650px]" id="chartContainer">
                        <canvas id="stuntingChart"></canvas>
                        <div id="chartMessage" class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 hidden">
                            <svg class="w-12 h-12 sm:w-16 sm:h-16 text-blue-400 mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span class="text-sm sm:text-base font-semibold text-gray-700">Memuat data chart...</span>
                            <div class="mt-3 w-48 h-1 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="chartInfoModalOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 hidden">
            <div
                class="relative mx-auto border w-full max-w-md sm:max-w-lg shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0"
                id="chartInfoModalContent"
                @click.away="closeChartInfoModal()" {{-- Alpine Opsional --}}
                x-trap.inert="open" {{-- Alpine Opsional --}}
                >

                {{-- Modal Header --}}
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <p class="text-lg font-bold text-gray-800">Keterangan Chart</p>
                    <button id="closeChartInfoModalBtnHeader" class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-6 py-5 space-y-4 text-sm text-gray-700 max-h-[70vh] overflow-y-auto">
                    <p>
                    Chart ini menampilkan visualisasi
                    <strong>Rata-rata Z-Score Tinggi Badan menurut Umur (ZS TB/U)</strong>
                    untuk setiap desa/kelurahan, berdasarkan data yang sesuai dengan filter aktif.
                    </p>

                    <ul class="list-disc list-inside space-y-1 pl-4">
                    <li>
                        <strong>Urutan:</strong> Desa diurutkan dari yang memiliki Z-Score
                        <strong>tertinggi</strong> (status gizi terbaik, paling atas) hingga yang
                        <strong>terendah</strong> (status gizi terburuk/paling stunting, paling bawah).
                    </li>

                    <li>
                        <strong>Panjang Bar:</strong> Panjang bar ke arah kanan menunjukkan nilai absolut Z-Score (jarak dari 0).
                    </li>

                    <li>
                        <strong>Warna Bar:</strong> Warna bar menunjukkan klasifikasi status desa berdasarkan nilai rata-rata ZS TB/U:
                        <ul class="ml-6 mt-1 space-y-1 text-xs list-none">
                        <li>
                            <span class="inline-block w-3 h-3 rounded-full mr-2 bg-blue-500 align-middle"></span>
                            <strong>Biru:</strong> Tinggi (&gt; +2 SD)
                        </li>
                        <li>
                            <span class="inline-block w-3 h-3 rounded-full mr-2 bg-green-500 align-middle"></span>
                            <strong>Hijau:</strong> Normal (-2 SD s/d +2 SD)
                        </li>
                        <li>
                            <span class="inline-block w-3 h-3 rounded-full mr-2 bg-orange-500 align-middle"></span>
                            <strong>Oranye:</strong> Pendek (-3 SD s/d &lt; -2 SD)
                        </li>
                        <li>
                            <span class="inline-block w-3 h-3 rounded-full mr-2 bg-red-500 align-middle"></span>
                            <strong>Merah:</strong> Sangat Pendek (&lt; -3 SD)
                        </li>
                        <li>
                            <span class="inline-block w-3 h-3 rounded-full mr-2 bg-gray-400 align-middle"></span>
                            <strong>Abu-abu:</strong> N/A (Data tidak cukup)
                        </li>
                        </ul>
                    </li>

                    <li><strong>Sumbu X (Atas):</strong> Menunjukkan nilai Z-Score TB/U.</li>
                    <li><strong>Sumbu Y (Kiri):</strong> Menunjukkan nama Desa/Kelurahan.</li>
                    <li>
                        <strong>Label Angka:</strong> Angka di ujung setiap bar (jika ada) menunjukkan nilai Z-Score asli desa tersebut.
                    </li>
                    <li>
                        <strong>Tooltip:</strong> Arahkan kursor ke bar untuk melihat nama desa dan nilai Z-Score asli.
                    </li>
                    </ul>

                    <p class="mt-3 text-xs text-gray-500">
                    <em>*SD = Standar Deviasi dari median standar pertumbuhan WHO.</em>
                    </p>

                </div>

                {{-- Modal Footer --}}
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-right rounded-b-xl">
                    <button id="closeChartInfoModalBtnFooter" class="px-5 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </section>

    @push('scripts')
    <script>
        // --- State Management ---
        let currentActiveTab = @json($currentView ?? 'table');
        let stuntingChartInstance = null;
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = {
            table: document.getElementById('tab-table'),
            chart: document.getElementById('tab-chart')
        };
        const viewInput = document.getElementById('viewInput');
        const chartMessageEl = document.getElementById('chartMessage');
        const chartContainerEl = document.getElementById('chartContainer');
        const filterCardWrapper = document.querySelector('[x-data="{ isTableActive: @json($currentView === 'table') }"]');

        const openInfoModalButton = document.getElementById('openInfoModalBtn');
        const infoModalOverlay = document.getElementById('infoModalOverlay');
        const infoModalContent = document.getElementById('infoModalContent');
        const closeInfoModalButtonHeader = document.getElementById('closeInfoModalBtnHeader');
        const closeInfoModalButtonFooter = document.getElementById('closeInfoModalBtnFooter');

        const openChartInfoModalButton = document.getElementById('openChartInfoModalBtn');
        const chartInfoModalOverlay = document.getElementById('chartInfoModalOverlay');
        const chartInfoModalContent = document.getElementById('chartInfoModalContent');
        const closeChartInfoModalButtonHeader = document.getElementById('closeChartInfoModalBtnHeader');
        const closeChartInfoModalButtonFooter = document.getElementById('closeChartInfoModalBtnFooter');


        // --- Fungsi Utama ---
        function setActiveTab(tabName) {
            console.log('Setting active tab to:', tabName);
            if (!tabContents.table || !tabContents.chart) {
                console.error("Tab content element(s) not found!");
                return;
            }

            currentActiveTab = tabName;

            // Update tombol tab
            tabButtons.forEach(button => {
                const target = button.dataset.target;
                const indicator = button.querySelector('.tab-indicator');
                const isActive = (target === `tab-${tabName}`);

                button.classList.toggle('text-gray-900', isActive);
                button.classList.toggle('bg-white', isActive);
                button.classList.toggle('text-gray-500', !isActive);
                button.classList.toggle('bg-gray-50', !isActive);
                button.classList.toggle('hover:text-gray-700', !isActive);
                button.classList.toggle('hover:bg-gray-100', !isActive);
                indicator?.classList.toggle('hidden', !isActive);
            });

            // Tampilkan/sembunyikan konten tab
            Object.keys(tabContents).forEach(key => {
                tabContents[key].classList.toggle('hidden', key !== tabName);
            });

            // Tampilkan/Sembunyikan Filter Card
            if (filterCardWrapper) {
                if (filterCardWrapper.__x) {
                    filterCardWrapper.__x.$data.isTableActive = (tabName === 'table');
                } else {
                    console.warn("Alpine context not found on filter wrapper, using direct style manipulation.");
                    filterCardWrapper.style.display = (tabName === 'table' ? '' : 'none');
                }
            } else {
                console.warn("Filter card wrapper element not found!");
            }

            // Update URL & hidden input
            if (viewInput) {
                viewInput.value = tabName;
            } else {
                console.warn("Hidden view input element not found!");
            }
            try {
                const url = new URL(window.location.href);
                url.searchParams.set('view', tabName);
                window.history.replaceState({}, '', url.toString());
            } catch (e) { console.warn("Could not update URL", e); }

            // Load chart jika tab chart aktif
            if (tabName === 'chart') {
                setTimeout(() => {
                    loadChartData();
                }, 50);
            }
        }

        async function loadChartData() {
            // Hancurkan instance lama
            if (stuntingChartInstance) {
                stuntingChartInstance.destroy();
                stuntingChartInstance = null;
                console.log('Previous chart instance destroyed.');
            }
            
            // Tampilkan pesan loading
            if (chartMessageEl) {
                chartMessageEl.innerHTML = `
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-blue-400 mb-4 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-sm sm:text-base font-semibold text-gray-700">Memuat data chart...</span>
                    <div class="mt-3 w-48 h-1 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-pulse"></div>
                    </div>
                `;
                chartMessageEl.classList.remove('hidden');
            }

            // Ambil filter dari URL
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search') || '';
            const tingkatZsParam = urlParams.get('tingkat_zs') || '';

            // Buat URL fetch
            const chartDataUrl = new URL(@json(route('data-stunting.chart-data-zs')));
            if (searchParam) chartDataUrl.searchParams.set('search', searchParam);
            if (tingkatZsParam) chartDataUrl.searchParams.set('tingkat_zs', tingkatZsParam);

            console.log('Fetching chart data from:', chartDataUrl.toString());

            try {
                const response = await fetch(chartDataUrl.toString());
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                console.log('Chart data received:', data);

                if (chartMessageEl) {
                    chartMessageEl.classList.add('hidden');
                }

                drawStuntingChart(data.labels, data.values);

            } catch (error) {
                console.error('Error fetching or processing chart data:', error);
                if (chartMessageEl) {
                    chartMessageEl.innerHTML = `
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm sm:text-base font-semibold text-red-600">Gagal memuat data chart</span>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2">Silakan refresh halaman atau coba lagi nanti</p>
                    `;
                    chartMessageEl.classList.remove('hidden');
                }
                const canvasElement = document.getElementById('stuntingChart');
                if (canvasElement) {
                    const context = canvasElement.getContext('2d');
                    context.clearRect(0, 0, canvasElement.width, canvasElement.height);
                }
            }
        }

        function drawStuntingChart(labels, values) {
            // =======================
            // 🔧 KNOB PENTING (atur di sini)
            // =======================
            const DATA_LABEL_ALIGN = 'left';   // 'left' = di luar bar (ke kiri), 'right' = di dalam bar (menempel)
            const DATA_LABEL_OFFSET = 1;      // jarak angka dari ujung bar (px). contoh: 6, 8, 10, 14, ...
            const LAYOUT_LEFT_PADDING = 0;    // tambah kalau label kepotong di sisi kiri (mis. 28–36)

            // === Registrasi plugin datalabels & avgLine (sekali saja) ===
            if (window.ChartDataLabels && !window.__cdlRegistered) {
                Chart.register(window.ChartDataLabels);
                window.__cdlRegistered = true;
            }
            if (!window.__avgLineRegistered) {
                const avgLinePlugin = {
                    id: 'avgLine',
                    afterDatasetsDraw(chart, _args, pluginOptions) {
                        const ca = chart.chartArea;
                        const xScale = chart.scales?.x;
                        if (!ca || !xScale) return;

                        const rawVals = Array.isArray(pluginOptions.values) ? pluginOptions.values : [];
                        const nums = rawVals.filter(v => Number.isFinite(v));
                        if (!nums.length) return;

                        const avg = nums.reduce((a, b) => a + b, 0) / nums.length;
                        if (avg < xScale.min || avg > xScale.max) return;

                        const xPos = xScale.getPixelForValue(avg);
                        const { top, bottom, left, right } = ca;
                        const ctx = chart.ctx;

                        ctx.save();
                        
                        // Garis dengan gradient
                        const gradient = ctx.createLinearGradient(0, top, 0, bottom);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.3)');
                        
                        ctx.strokeStyle = gradient;
                        ctx.setLineDash([8, 4]);
                        ctx.lineWidth = 2.5;
                        ctx.shadowColor = 'rgba(99, 102, 241, 0.3)';
                        ctx.shadowBlur = 4;
                        ctx.beginPath();
                        ctx.moveTo(xPos, top);
                        ctx.lineTo(xPos, bottom);
                        ctx.stroke();
                        ctx.setLineDash([]);
                        ctx.shadowBlur = 0;

                        // Pill label dengan shadow
                        const labelText = 'Avg ' + avg.toFixed(2);
                        ctx.font = '600 12px Figtree, sans-serif';
                        const textW = ctx.measureText(labelText).width;
                        const padX = 10;
                        const boxH = 24;
                        const boxW = textW + padX * 2;
                        const r = 12;
                        const boxY = top + 6;
                        const boxX = Math.min(Math.max(xPos - boxW / 2, left + 4), right - boxW - 4);

                        // Shadow untuk pill
                        ctx.shadowColor = 'rgba(99, 102, 241, 0.2)';
                        ctx.shadowBlur = 8;
                        ctx.shadowOffsetY = 2;

                        // Background gradient
                        const pillGradient = ctx.createLinearGradient(boxX, boxY, boxX, boxY + boxH);
                        pillGradient.addColorStop(0, 'rgba(255, 255, 255, 0.98)');
                        pillGradient.addColorStop(1, 'rgba(249, 250, 251, 0.98)');
                        ctx.fillStyle = pillGradient;
                        
                        ctx.strokeStyle = 'rgba(99, 102, 241, 0.25)';
                        ctx.lineWidth = 1.5;

                        ctx.beginPath();
                        ctx.moveTo(boxX + r, boxY);
                        ctx.lineTo(boxX + boxW - r, boxY);
                        ctx.quadraticCurveTo(boxX + boxW, boxY, boxX + boxW, boxY + r);
                        ctx.lineTo(boxX + boxW, boxY + boxH - r);
                        ctx.quadraticCurveTo(boxX + boxW, boxY + boxH, boxX + boxW - r, boxY + boxH);
                        ctx.lineTo(boxX + r, boxY + boxH);
                        ctx.quadraticCurveTo(boxX, boxY + boxH, boxX, boxY + boxH - r);
                        ctx.lineTo(boxX, boxY + r);
                        ctx.quadraticCurveTo(boxX, boxY, boxX + r, boxY);
                        ctx.closePath();
                        ctx.fill();
                        ctx.stroke();

                        ctx.shadowBlur = 0;
                        ctx.shadowOffsetY = 0;

                        // Text dengan gradient
                        const textGradient = ctx.createLinearGradient(boxX, boxY, boxX, boxY + boxH);
                        textGradient.addColorStop(0, '#4f46e5');
                        textGradient.addColorStop(1, '#6366f1');
                        ctx.fillStyle = textGradient;
                        ctx.fillText(labelText, boxX + padX, boxY + 15);
                        ctx.restore();
                    }
                };
                Chart.register(avgLinePlugin);
                window.__avgLineRegistered = true;
            }

            // === Ambil canvas ===
            const canvas = document.getElementById('stuntingChart');
            if (!canvas) {
                console.error("Canvas 'stuntingChart' tidak ditemukan.");
                return;
            }
            const ctx2d = canvas.getContext('2d');
            ctx2d.clearRect(0, 0, canvas.width, canvas.height);

            // === Guard data ===
            if (!labels || !labels.length || !values || !values.length) {
                if (chartMessageEl) {
                    chartMessageEl.innerHTML =
                        '<svg class="w-16 h-16 sm:w-20 sm:h-20 text-gray-200 mb-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>' +
                        '</svg>' +
                        '<span class="text-sm sm:text-base font-bold text-gray-700">Tidak ada data untuk ditampilkan</span>' +
                        '<p class="text-xs sm:text-sm text-gray-500 mt-2"></p>';
                    chartMessageEl.classList.remove('hidden');
                }
                return;
            }

            // === Sorting ===
            const dataPoints = labels.map((label, i) => ({
                label: label,
                value: (values[i] !== null && values[i] !== undefined) ? Number(values[i]) : null
            })).sort((a, b) => {
                if (a.value === null && b.value === null) return 0;
                if (a.value === null) return 1;
                if (b.value === null) return -1;
                return a.value - b.value;
            });

            const sortedLabels = dataPoints.map(d => d.label);
            const sortedValues = dataPoints.map(d => d.value);

            // === Skala X dinamis (negatif) ===
            const negs = sortedValues.filter(v => v !== null && v < 0);
            let minValue = -4;
            if (negs.length) {
                const lowest = Math.min(...negs);
                minValue = lowest - 0.2;
            }

            const chartValues = sortedValues.map(v => (v !== null && v >= 0) ? null : v);

            // === Responsif ===
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;

            // === Warna bar dengan gradient ===
            function getBarColor(value) {
                if (value === null) {
                    return {
                        backgroundColor: 'rgba(156, 163, 175, 0.75)',
                        borderColor: 'rgba(107, 114, 128, 0.4)',
                        hoverBackgroundColor: 'rgba(156, 163, 175, 0.95)'
                    };
                }
                if (value < -3) {
                    return {
                        backgroundColor: 'rgba(239, 68, 68, 0.9)',
                        borderColor: 'rgba(220, 38, 38, 0.5)',
                        hoverBackgroundColor: 'rgba(239, 68, 68, 1)'
                    };
                }
                if (value < -2) {
                    return {
                        backgroundColor: 'rgba(249, 115, 22, 0.9)',
                        borderColor: 'rgba(234, 88, 12, 0.5)',
                        hoverBackgroundColor: 'rgba(249, 115, 22, 1)'
                    };
                }
                if (value <= 2) {
                    return {
                        backgroundColor: 'rgba(34, 197, 94, 0.9)',
                        borderColor: 'rgba(22, 163, 74, 0.5)',
                        hoverBackgroundColor: 'rgba(34, 197, 94, 1)'
                    };
                }
                return {
                    backgroundColor: 'rgba(59, 130, 246, 0.9)',
                    borderColor: 'rgba(37, 99, 235, 0.5)',
                    hoverBackgroundColor: 'rgba(59, 130, 246, 1)'
                };
            }

            const backgroundColors = chartValues.map(v => getBarColor(v).backgroundColor);
            const borderColors = chartValues.map(v => getBarColor(v).borderColor);
            const hoverBgColors = chartValues.map(v => getBarColor(v).hoverBackgroundColor);

            // === Bangun chart ===
            try {
                stuntingChartInstance = new Chart(ctx2d, {
                    type: 'bar',
                    data: {
                        labels: sortedLabels,
                        datasets: [{
                            label: 'Rata-rata ZS TB/U',
                            data: chartValues,
                            backgroundColor: backgroundColors,
                            borderColor: borderColors,
                            borderWidth: 2,
                            borderRadius: isMobile ? 8 : 10,
                            borderSkipped: false,
                            barThickness: isMobile ? 20 : (isTablet ? 24 : 28),
                            categoryPercentage: 0.88,
                            barPercentage: 0.82,
                            hoverBackgroundColor: hoverBgColors,
                            hoverBorderWidth: 2.5,
                            shadowOffsetX: 0,
                            shadowOffsetY: 2,
                            shadowBlur: 4,
                            shadowColor: 'rgba(0, 0, 0, 0.1)'
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: LAYOUT_LEFT_PADDING,
                                right: isMobile ? 10 : 16,
                                top: 18,
                                bottom: 18
                            }
                        },
                        scales: {
                            x: {
                                min: minValue,
                                max: 0,
                                reverse: true,
                                position: 'top',
                                grid: {
                                    display: true,
                                    color: 'rgba(229, 231, 235, 0.5)',
                                    lineWidth: 1,
                                    drawBorder: false
                                },
                                ticks: {
                                    font: {
                                        size: isMobile ? 10 : (isTablet ? 11 : 12),
                                        family: "'Figtree', sans-serif",
                                        weight: '600'
                                    },
                                    color: '#6b7280',
                                    autoSkip: isMobile,
                                    maxTicksLimit: isMobile ? 5 : 8,
                                    padding: 10
                                },
                                border: { display: false }
                            },
                            y: {
                                grid: { display: false },
                                ticks: {
                                    autoSkip: false,
                                    font: {
                                        size: isMobile ? 7.5 : (isTablet ? 10 : 11),
                                        weight: '700',
                                        family: "'Figtree', sans-serif"
                                    },
                                    color: '#1f2937',
                                    padding: isMobile ? 2 : 8
                                },
                                border: { display: false }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                enabled: true,
                                backgroundColor: 'rgba(17, 24, 39, 0.97)',
                                titleColor: '#ffffff',
                                bodyColor: '#f3f4f6',
                                borderColor: 'rgba(99, 102, 241, 0.3)',
                                borderWidth: 1,
                                padding: isMobile ? 12 : 16,
                                cornerRadius: 8,
                                displayColors: false,
                                titleFont: {
                                    size: isMobile ? 13 : 15,
                                    weight: '700',
                                    family: "'Figtree', sans-serif"
                                },
                                bodyFont: {
                                    size: isMobile ? 12 : 14,
                                    weight: '500',
                                    family: "'Figtree', sans-serif"
                                },
                                filter: item => item.raw !== null && !Number.isNaN(item.raw),
                                position: 'nearest',
                                callbacks: {
                                    title: ctx => sortedLabels[ctx[0].dataIndex],
                                    label: ctx => {
                                        const val = sortedValues[ctx.dataIndex];
                                        return 'ZS TB/U: ' + (val !== null ? val.toFixed(2) : 'N/A');
                                    }
                                }
                            },
                            datalabels: {
                                display: ctx => {
                                    const v = ctx.dataset.data[ctx.dataIndex];
                                    return v !== null && !Number.isNaN(v);
                                },
                                anchor: 'end',
                                align: (ctx) => {
                                    // Kalau DATA_LABEL_ALIGN = 'left', align ke center vertikal
                                    // Kalau 'right', tetap center
                                    return DATA_LABEL_ALIGN === 'left' ? 'center' : 'center';
                                },
                                offset: DATA_LABEL_OFFSET,
                                clamp: true,
                                clip: false,
                                color: '#111827',
                                font: {
                                    size: isMobile ? 10 : (isTablet ? 11 : 12),
                                    weight: '800',
                                    family: "'Figtree', sans-serif"
                                },
                                formatter: (_val, ctx) => {
                                    const actual = sortedValues[ctx.dataIndex];
                                    return Number.isFinite(actual) ? actual.toFixed(2) : '';
                                },
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                borderRadius: 4,
                                padding: {
                                    top: 3,
                                    bottom: 3,
                                    left: 6,
                                    right: 6
                                }
                            },
                            avgLine: { values: sortedValues }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'y',
                            intersect: true
                        },
                        onHover: (evt, activeEls, chart) => {
                            const el = evt?.native?.target || chart.canvas;
                            el.style.cursor = (activeEls && activeEls.length) ? 'pointer' : 'default';
                        },
                        animation: {
                            duration: 1000,
                            easing: 'easeInOutCubic',
                            onProgress: function(animation) {
                                const ctx = this.ctx;
                                const chartArea = this.chartArea;
                                if (animation.currentStep === 1) {
                                    ctx.save();
                                    ctx.globalAlpha = 0.05;
                                    ctx.fillStyle = '#6366f1';
                                    ctx.fillRect(chartArea.left, chartArea.top, 
                                            chartArea.right - chartArea.left, 
                                            chartArea.bottom - chartArea.top);
                                    ctx.restore();
                                }
                            }
                        }
                    }
                });
            } catch (err) {
                console.error('Error draw chart:', err);
                if (chartMessageEl) {
                    chartMessageEl.innerHTML =
                        '<svg class="w-12 h-12 sm:w-16 sm:h-16 text-red-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>' +
                        '</svg>' +
                        '<span class="text-sm sm:text-base font-semibold text-red-600">Terjadi error saat menampilkan chart</span>' +
                        '<p class="text-xs sm:text-sm text-gray-500 mt-2">Silakan refresh halaman</p>';
                    chartMessageEl.classList.remove('hidden');
                }
            }
        }

        function openInfoModal() {
            if (infoModalOverlay && infoModalContent) {
                document.body.style.overflow = 'hidden';
                infoModalOverlay.classList.remove('hidden');
                // Trigger transition
                setTimeout(() => {
                    infoModalContent.classList.remove('scale-95', 'opacity-0');
                    infoModalContent.classList.add('scale-100', 'opacity-100');
                }, 10); // Jeda kecil
            } else {
                console.error('Info modal elements not found!');
            }
        }

        function closeInfoModal() {
            if (infoModalOverlay && infoModalContent) {
                infoModalContent.classList.remove('scale-100', 'opacity-100');
                infoModalContent.classList.add('scale-95', 'opacity-0');
                // Tunggu transisi selesai sebelum hide overlay
                setTimeout(() => {
                    document.body.style.overflow = '';
                    infoModalOverlay.classList.add('hidden');
                }, 300); // Samakan dengan durasi transisi
            }
        }

        function openChartInfoModal() {
            if (chartInfoModalOverlay && chartInfoModalContent) {
                document.body.style.overflow = 'hidden'; // Sembunyikan scrollbar body
                chartInfoModalOverlay.classList.remove('hidden');
                setTimeout(() => {
                    chartInfoModalContent.classList.remove('scale-95', 'opacity-0');
                    chartInfoModalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                console.error('Chart info modal elements not found!');
            }
        }

        function closeChartInfoModal() {
            if (chartInfoModalOverlay && chartInfoModalContent) {
                chartInfoModalContent.classList.remove('scale-100', 'opacity-100');
                chartInfoModalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    chartInfoModalOverlay.classList.add('hidden');
                    document.body.style.overflow = ''; // Munculkan lagi scrollbar body
                }, 300);
            }
        }

        // Panggil setActiveTab saat DOM siap
        document.addEventListener('DOMContentLoaded', () => {
            const filterWrapper = document.querySelector('[x-data="{ isTableActive: @json($currentView === 'table') }"]');
            if (filterWrapper && !filterWrapper.__x) {
                console.log("Initializing Alpine on filter wrapper...");
                Alpine.initTree(filterWrapper);
            } else if (!filterWrapper) {
                console.warn("Filter card wrapper not found for Alpine init.");
            }
            console.log("DOM Ready, setting initial active tab:", currentActiveTab);
            setActiveTab(currentActiveTab);

            if (openInfoModalButton) {
                openInfoModalButton.addEventListener('click', openInfoModal);
            } else {
                console.warn('Open info modal button not found.');
            }
            if (closeInfoModalButtonHeader) {
                closeInfoModalButtonHeader.addEventListener('click', closeInfoModal);
            }
            if (closeInfoModalButtonFooter) {
                closeInfoModalButtonFooter.addEventListener('click', closeInfoModal);
            }
            // Tutup saat klik overlay
            if (infoModalOverlay) {
                infoModalOverlay.addEventListener('click', function(event) {
                    // Hanya tutup jika klik di overlay (latar belakang), bukan di konten modalnya
                    if (event.target === infoModalOverlay) {
                        closeInfoModal();
                    }
                });
            }

            document.addEventListener('keydown', function(event) {
                // Cek apakah modal sedang tampil sebelum menutup
                if (event.key === 'Escape' && infoModalOverlay && !infoModalOverlay.classList.contains('hidden')) {
                    closeInfoModal();
                }
            });

            if (openChartInfoModalButton) {
                openChartInfoModalButton.addEventListener('click', openChartInfoModal);
            } else {
                 console.warn('Open chart info modal button not found.');
            }
            if (closeChartInfoModalButtonHeader) {
                closeChartInfoModalButtonHeader.addEventListener('click', closeChartInfoModal);
            }
            if (closeChartInfoModalButtonFooter) {
                closeChartInfoModalButtonFooter.addEventListener('click', closeChartInfoModal);
            }
            // Tutup saat klik overlay chart
            if (chartInfoModalOverlay) {
                chartInfoModalOverlay.addEventListener('click', function(event) {
                    if (event.target === chartInfoModalOverlay) {
                        closeChartInfoModal();
                    }
                });
            }

            // Tutup saat tekan tombol Escape (handle kedua modal)
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    if (infoModalOverlay && !infoModalOverlay.classList.contains('hidden')) {
                        closeInfoModal(); // Tutup modal tabel
                    }
                    if (chartInfoModalOverlay && !chartInfoModalOverlay.classList.contains('hidden')) {
                        closeChartInfoModal(); // Tutup modal chart
                    }
                }
            });
        });

        // Re-render chart saat window resize
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (currentActiveTab === 'chart' && stuntingChartInstance) {
                    console.log('Window resized, redrawing chart...');
                    loadChartData();
                }
            }, 300);
        });

    </script>
    @endpush

</x-guest-layout>