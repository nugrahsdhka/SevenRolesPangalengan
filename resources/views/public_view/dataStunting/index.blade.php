<x-guest-layout>
    <x-slot name="title">
        Data Stunting Publik
    </x-slot>

    {{-- Navigasi Publik --}}
    @include('layouts.nav_public')

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
    </div>{{-- Akhir div x-show filter card --}}

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

    {{-- Konten Tab: TABLE --}}
    <section id="tab-table" class="{{ $currentView === 'table' ? '' : 'hidden' }}">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-6">
                 {{-- Judul Tabel --}}
                 <div class="flex items-center justify-between mb-6">
                     <div>
                         <h2 class="text-xl font-bold text-gray-900">Table Data Stunting</h2>
                         <p class="text-sm text-gray-500 mt-1">Informasi lengkap tingkat stunting per desa</p>
                     </div>
                     <button class="p-2.5 text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-xl hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                     </button>
                 </div>
                 {{-- Tabel --}}
                 <div class="overflow-hidden rounded-xl border border-gray-200">
                     <div class="overflow-x-auto">
                         <table class="min-w-full divide-y divide-gray-200">
                             <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                                 <tr class="uppercase">
                                     <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider"><div class="flex items-center gap-2">desa <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" /></svg></div></th>
                                     <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">kasus stunting</th>
                                     <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">total anak</th>
                                     <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">rata-rata zs tb/u</th>
                                     <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">status desa</th>
                                     <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">periode</th>
                                 </tr>
                             </thead>
                             <tbody class="bg-white divide-y divide-gray-100">
                                 @forelse ($dataStunting as $data)
                                     @php $avgZsTbU = $data->avg_zs_tbu; @endphp
                                     <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-150 group">
                                         {{-- Kolom Desa --}}
                                         <td class="px-6 py-4 whitespace-nowrap">
                                             <div class="flex items-center">
                                                 <div class="w-2 h-2 bg-red-500 rounded-full mr-3 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                                 <span class="text-sm font-semibold text-gray-900">{{ $data->desa_kelurahan }}</span>
                                             </div>
                                         </td>
                                         {{-- Kolom Kasus Stunting --}}
                                         <td class="px-6 py-4 whitespace-nowrap text-center">
                                             <span class="text-sm font-bold text-gray-900">{{ number_format($data->total_kasus_stunting) }}</span>
                                         </td>
                                         {{-- Kolom Total Anak --}}
                                         <td class="px-6 py-4 whitespace-nowrap text-center">
                                             <span class="text-sm text-gray-700">{{ number_format($data->total_anak) }}</span>
                                         </td>
                                         {{-- Kolom Rata-rata ZS TB/U --}}
                                         <td class="px-6 py-4 whitespace-nowrap text-center">
                                             <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm
                                                 @if(is_null($avgZsTbU) || $data->total_anak == 0)
                                                     bg-gradient-to-r from-gray-400 to-gray-500 text-white
                                                 @elseif($avgZsTbU < -3)
                                                     bg-gradient-to-r from-red-500 to-red-600 text-white
                                                 @elseif($avgZsTbU < -2)
                                                     bg-gradient-to-r from-orange-500 to-orange-600 text-white
                                                 @elseif($avgZsTbU <= 2)
                                                     bg-gradient-to-r from-green-500 to-green-600 text-white
                                                 @else
                                                     bg-gradient-to-r from-blue-500 to-blue-600 text-white
                                                 @endif
                                             ">
                                                 @if(is_null($avgZsTbU) || $data->total_anak == 0)
                                                     N/A
                                                 @else
                                                     {{ number_format($avgZsTbU, 2) }}
                                                 @endif
                                             </span>
                                         </td>
                                         {{-- Kolom Status Desa --}}
                                         <td class="whitespace-nowrap px-6 py-4 text-center">
                                             @if(is_null($avgZsTbU) || $data->total_anak == 0)
                                                 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-gray-400 to-gray-500 text-white"> N/A </span>
                                             @elseif($avgZsTbU < -3)
                                                 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-red-500 to-red-600 text-white"> Sangat Pendek ({{ number_format($avgZsTbU, 2) }}) </span>
                                             @elseif($avgZsTbU < -2)
                                                 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-orange-500 to-orange-600 text-white"> Pendek ({{ number_format($avgZsTbU, 2) }}) </span>
                                             @elseif($avgZsTbU <= 2)
                                                 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-green-500 to-green-600 text-white"> Normal ({{ number_format($avgZsTbU, 2) }}) </span>
                                             @else
                                                 <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold shadow-sm bg-gradient-to-r from-blue-500 to-blue-600 text-white"> Tinggi ({{ number_format($avgZsTbU, 2) }}) </span>
                                             @endif
                                         </td>
                                         {{-- Kolom Periode --}}
                                         <td class="px-6 py-4 whitespace-nowrap text-right">
                                             <div class="flex items-center gap-2 justify-end">
                                                 <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                 <span class="text-sm text-gray-700 font-medium">{{ \Carbon\Carbon::parse($data->periode)->format("M 'y") }}</span>
                                             </div>
                                         </td>
                                     </tr>
                                 @empty
                                     <tr>
                                         <td colspan="6" class="px-6 py-12 text-center">
                                             <div class="flex flex-col items-center justify-center">
                                                 <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                 <p class="text-sm font-medium text-gray-500">Tidak ada data yang ditemukan</p>
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
    </section>

    {{-- Konten Tab: CHART --}}
    <section id="tab-chart" class="{{ $currentView === 'chart' ? '' : 'hidden' }}">
         <div class="bg-white shadow-lg rounded-lg overflow-hidden p-6">
             <h2 class="text-xl font-bold text-gray-900 mb-4">Chart Rata-rata ZS TB/U per Desa</h2>
             <p class="text-sm text-gray-500 mb-6">Visualisasi data Z-Score dari desa paling tidak stunting (atas) ke paling stunting (bawah), sesuai filter yang diterapkan.</p>
             <div class="relative h-[600px]" id="chartContainer">
                 <canvas id="stuntingChart"></canvas>
                 <div id="chartMessage" class="absolute inset-0 flex items-center justify-center text-gray-500 text-sm hidden">Memuat data chart...</div>
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
                 // Coba akses state Alpine jika ada
                 if (filterCardWrapper.__x) {
                      filterCardWrapper.__x.$data.isTableActive = (tabName === 'table');
                 } else {
                      // Fallback: langsung ubah style display
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
                // Beri sedikit jeda agar elemen canvas visible
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
                 chartMessageEl.textContent = 'Memuat data chart...';
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
                 // Lakukan fetch
                 const response = await fetch(chartDataUrl.toString());
                 if (!response.ok) {
                     throw new Error(`HTTP error! status: ${response.status}`);
                 }
                 const data = await response.json();
                 console.log('Chart data received:', data);

                 // Sembunyikan pesan loading
                 if (chartMessageEl) {
                     chartMessageEl.classList.add('hidden');
                 }

                 // Gambar chart
                 drawStuntingChart(data.labels, data.values);

             } catch (error) {
                 // Handle error fetch
                 console.error('Error fetching or processing chart data:', error);
                 if (chartMessageEl) {
                     chartMessageEl.textContent = 'Gagal memuat data chart. Coba refresh.';
                     chartMessageEl.classList.remove('hidden');
                 }
                 // Bersihkan canvas jika error
                 const canvasElement = document.getElementById('stuntingChart');
                 if (canvasElement) {
                     const context = canvasElement.getContext('2d');
                     context.clearRect(0, 0, canvasElement.width, canvasElement.height);
                 }
             }
        }

        function drawStuntingChart(labels, values) {
            const ctx = document.getElementById('stuntingChart');
            if (!ctx) {
                 console.error("Canvas element 'stuntingChart' not found for drawing.");
                 return;
            }
            const context = ctx.getContext('2d');
            context.clearRect(0, 0, ctx.width, ctx.height); // Selalu bersihkan canvas

            // Tampilkan pesan jika tidak ada data
            if (!labels || labels.length === 0 || !values || values.length === 0) {
                console.warn('No labels or values provided for drawing chart.');
                context.textAlign = 'center';
                context.fillStyle = '#6b7280'; // gray-500
                context.font = '14px Figtree, sans-serif';
                context.fillText('Tidak ada data chart untuk ditampilkan pada filter ini.', ctx.width / 2, ctx.height / 2);
                return;
            }

            // --- Urutkan data dari nilai TERBESAR (di atas) ke TERKECIL (di bawah) ---
            const dataPoints = labels.map((label, index) => ({
                label,
                value: (values[index] !== null && values[index] !== undefined) ? parseFloat(values[index]) : null
            }));
            dataPoints.sort((a, b) => {
                // Tangani nilai null: taruh di paling bawah
                if (a.value === null && b.value === null) return 0;
                if (a.value === null) return 1;
                if (b.value === null) return -1;

                // Urutkan dari nilai terbesar ke terkecil
                return a.value - b.value;
            });
            const sortedLabels = dataPoints.map(d => d.label);
            const sortedValues = dataPoints.map(d => d.value);
            // --- Akhir Urutkan data ---

            // --- Fungsi Warna Bar ---
            const getBarColor = (value) => {
                if (value === null) {
                    return 'rgba(156, 163, 175, 0.6)'; // Abu-abu (N/A)
                } else if (value < -3) {
                    return 'rgba(239, 68, 68, 0.6)';      // Merah (Sangat Pendek)
                } else if (value < -2) {
                    return 'rgba(249, 115, 22, 0.6)';     // Oranye (Pendek)
                } else if (value <= 2) {
                    return 'rgba(34, 197, 94, 0.6)';     // Hijau (Normal)
                } else {
                    return 'rgba(59, 130, 246, 0.6)';     // Biru (Tinggi)
                }
            };
            const backgroundColors = sortedValues.map(value => getBarColor(value));
            // --- Akhir Fungsi Warna Bar ---

            try {
                // Buat instance chart baru
                stuntingChartInstance = new Chart(context, {
                    type: 'bar',
                    data: {
                        labels: sortedLabels,
                        datasets: [{
                            label: 'Rata-rata ZS TB/U',
                            data: sortedValues,
                            backgroundColor: backgroundColors,
                            borderColor: backgroundColors.map(c => c.replace('0.6', '1')),
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y', // Bar horizontal
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: { // Sumbu Nilai (kanan)
                                beginAtZero: false, // Tidak mulai dari 0
                                min: -4, // Set minimum sumbu X
                                title: {
                                    display: true,
                                    text: 'Rata-rata ZS TB/U'
                                },
                                grid: {
                                    // Garis bantu di 0
                                    color: gridContext => gridContext.tick.value === 0 ? 'rgba(255, 99, 132, 0.5)' : Chart.defaults.borderColor,
                                    lineWidth: gridContext => gridContext.tick.value === 0 ? 2 : 1,
                                }
                            },
                            y: { // Sumbu Kategori/Label (kiri)
                                title: {
                                    display: true,
                                    // Judul sumbu diubah
                                    text: 'Desa/Kelurahan (Urut Status Terbaik ke Terburuk)'
                                },
                                ticks: {
                                    autoSkip: false // Tampilkan semua label desa
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false // Sembunyikan legenda
                            },
                            tooltip: {
                                callbacks: {
                                    // Format tooltip
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        // Gunakan parsed.x karena indexAxis: 'y'
                                        if (context.parsed.x !== null) {
                                            label += context.parsed.x.toFixed(2);
                                        } else {
                                            label += 'N/A';
                                        }
                                        return label;
                                    }
                                }
                            },
                            // Plugin datalabels (jika diinstall)
                            // datalabels: {
                            //      anchor: 'end',
                            //      align: 'right', // Posisi label di ujung kanan bar
                            //      formatter: (value) => value !== null ? value.toFixed(2) : 'N/A',
                            //      color: '#333' // Warna teks label
                            // }
                        }
                    }
                });
                console.log('Chart drawn successfully (Horizontal Desc)!');
            } catch (error) {
                // Handle error pembuatan chart
                console.error('!!! Error while drawing Chart:', error);
                if (chartMessageEl) {
                    chartMessageEl.textContent = 'Terjadi error saat menampilkan chart.';
                    chartMessageEl.classList.remove('hidden');
                }
            }
        }

        // Panggil setActiveTab saat DOM siap
        document.addEventListener('DOMContentLoaded', () => {
             // Inisialisasi state filter card
             const filterWrapper = document.querySelector('[x-data="{ isTableActive: @json($currentView === 'table') }"]');
             if (filterWrapper && !filterWrapper.__x) {
                 console.log("Initializing Alpine on filter wrapper...");
                 Alpine.initTree(filterWrapper);
             } else if (!filterWrapper) {
                  console.warn("Filter card wrapper not found for Alpine init.");
             }
             // Set tab aktif berdasarkan state awal
             console.log("DOM Ready, setting initial active tab:", currentActiveTab);
             setActiveTab(currentActiveTab);
        });

    </script>
    @endpush

</x-guest-layout>