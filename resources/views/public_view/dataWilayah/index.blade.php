<x-guest-layout>
    <x-slot name="title">
        RSP | Data Wilayah
    </x-slot>

    @include('layouts.nav_public')

    @php
        $search = $search ?? request()->query('search');
    @endphp

    {{-- Filter Card --}}
    <div class="p-2">
        <div class="mb-6">
            <div class="bg-white backdrop-blur-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6">
                    {{-- Form Filter --}}
                    <form action="{{ route('data-wilayah') }}" method="GET" id="filterForm">
                        <div class="flex flex-col lg:flex-row items-stretch lg:items-end gap-4">
                            <div class="flex-1">
                                <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Pencarian</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-red-500 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <input type="text" name="search" id="search"
                                        class="block w-full pl-12 pr-4 py-3 text-sm border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                                        placeholder="Cari nama desa..."
                                        value="{{ $search ?? '' }}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Konten Tabel ===== --}}
    <section id="tab-table">
        <div class="bg-white rounded-lg overflow-hidden">
            <div class="p-6">

                {{-- Judul Tabel dan Tombol Info --}}
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Tabel Data Wilayah</h2>
                    </div>

                    {{-- Tombol Info --}}
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
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 tracking-wider">
                                        desa
                                    </th>

                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                                        rata-rata tinggi (cm)
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                                        rata-rata berat (kg)
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                                        total anak
                                    </th>

                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                                        rata-rata zs bb/u
                                    </th>

                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                                        rata-rata zs tb/u
                                    </th>

                                    <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 tracking-wider">
                                        rata-rata zs bb/tb
                                    </th>

                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 tracking-wider">
                                        periode
                                    </th>
                                </tr>
                            </thead>

                            {{-- TBODY --}}
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse ($dataWilayah as $data)
                                    @php
                                        // Hitung rata-rata Z-Score
                                        $avgZsBbU = ($data->total_anak > 0 && !is_null($data->sum_zs_bbu)) ? ($data->sum_zs_bbu / $data->total_anak) : null;
                                        $avgZsTbU = ($data->total_anak > 0 && !is_null($data->sum_zs_tbu)) ? ($data->sum_zs_tbu / $data->total_anak) : null;
                                        $avgZsBbTb = ($data->total_anak > 0 && !is_null($data->sum_zs_bbtb)) ? ($data->sum_zs_bbtb / $data->total_anak) : null;
                                    @endphp

                                    <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-150 group">
                                        {{-- Desa --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-semibold text-center text-gray-900">
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
                                            @include('components.zscore-badge', ['value' => $avgZsBbU])
                                        </td>

                                        {{-- Rata-rata ZS TB/U --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @include('components.zscore-badge', ['value' => $avgZsTbU])
                                        </td>

                                        {{-- Rata-rata ZS BB/TB --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @include('components.zscore-badge', ['value' => $avgZsBbTb])
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
                                        {{-- ===== UPDATE COLSPAN DARI 6 MENJADI 8 ===== --}}
                                        <td colspan="8" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <p class="text-sm font-medium text-gray-500">
                                                    Tidak ada data yang ditemukan
                                                </p>
                                                @if($search)
                                                    <p class="text-xs text-gray-400 mt-1">Coba ubah kata kunci pencarian Anda.</p>
                                                @endif
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
                    {{ $dataWilayah->appends(request()->query())->links() }}
                </div>

            </div>
        </div>

        {{-- Modal Informasi Kolom Tabel --}}
        <div id="infoModalOverlay" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 hidden">
            {{-- Kontainer Modal --}}
            <div
                class="relative mx-auto border w-full max-w-md sm:max-w-lg md:max-w-xl shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0"
                id="infoModalContent"
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
                    <ul class="list-disc list-inside space-y-3 pl-4">
                        <li>
                            <strong>Desa:</strong> Nama desa yang datanya diagregasi.
                        </li>

                        <li>
                            <strong>Rata-Rata Tinggi (cm):</strong> Rata-rata tinggi badan (TB) anak dalam centimeter (cm) di desa tersebut pada periode ini.
                        </li>
                        <li>
                            <strong>Rata-Rata Berat (kg):</strong> Rata-rata berat badan (BB) anak dalam kilogram (kg) di desa tersebut pada periode ini.
                        </li>
                        <li>
                            <strong>Periode:</strong> Bulan dan tahun saat data ini di inputkan dan dipublikasi.
                        </li>
                        
                        <li>
                            <strong>Total Anak:</strong> Jumlah total anak yang datanya masuk dalam agregasi di periode tersebut.
                        </li>

                        <li>
                            <strong>Rata-Rata ZS (BB/U, TB/U, BB/TB):</strong> Rata-rata Z-Score (Standar Deviasi) untuk tiga indikator:
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
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-blue-500 align-middle"></span>
                                    <strong> Biru (Tinggi/Gizi Lebih):</strong> (&gt; +2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-green-500 align-middle"></span>
                                    <strong>Hijau (Normal):</strong> (-2 SD s/d +2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-orange-500 align-middle"></span>
                                    <strong>Oranye (Kekurangan Gizi/Pendek):</strong> (-3 SD s/d &lt; -2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-red-500 align-middle"></span>
                                    <strong>Merah (Kekurangan Gizi Akut/Sangat Pendek):</strong> (&lt; -3 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-gray-400 align-middle"></span>
                                    <strong>Abu-abu (N/A):</strong> (Data tidak cukup)
                                </li>
                            </ul>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalOverlay = document.getElementById('infoModalOverlay');
            const modalContent = document.getElementById('infoModalContent');
            const openBtn = document.getElementById('openInfoModalBtn');
            const closeBtnHeader = document.getElementById('closeInfoModalBtnHeader');
            const closeBtnFooter = document.getElementById('closeInfoModalBtnFooter');

            function openModal() {
                if (modalOverlay) {
                    modalOverlay.classList.remove('hidden');
                    // Trigger reflow
                    void modalContent.offsetWidth; 
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }
            }

            function closeModal() {
                if (modalOverlay) {
                    modalContent.classList.add('scale-95', 'opacity-0');
                    modalContent.classList.remove('scale-100', 'opacity-100');
                    setTimeout(() => {
                        modalOverlay.classList.add('hidden');
                    }, 300); // Wait for animation
                }
            }

            if (openBtn) {
                openBtn.addEventListener('click', openModal);
            }
            if (closeBtnHeader) {
                closeBtnHeader.addEventListener('click', closeModal);
            }
            if (closeBtnFooter) {
                closeBtnFooter.addEventListener('click', closeModal);
            }
            if (modalOverlay) {
                // Klik di luar modal untuk menutup
                modalOverlay.addEventListener('click', function(event) {
                    if (event.target === modalOverlay) {
                        closeModal();
                    }
                });
            }
            
            // Tutup modal dengan tombol Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && !modalOverlay.classList.contains('hidden')) {
                    closeModal();
                }
            });
        });


    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');
            const input = document.getElementById('search');
            if (!form || !input) return;

            let debounceTimer = null;
            function debounce(fn, delay) {
                return function () {
                    clearTimeout(debounceTimer);
                    const args = arguments;
                    debounceTimer = setTimeout(function () {
                        fn.apply(null, args);
                    }, delay);
                };
            }

            // Auto-submit GET saat user mengetik (tanpa Enter)
            const autoSubmit = debounce(function () {
                // Gunakan submit standar agar query lain (jika nanti ditambah) ikut terkirim
                if (typeof form.requestSubmit === 'function') {
                    form.requestSubmit();
                } else {
                    form.submit();
                }
            }, 500); // ganti 500 kalau mau lebih cepat/lambat

            input.addEventListener('input', autoSubmit);
        });
    </script>

</x-guest-layout>