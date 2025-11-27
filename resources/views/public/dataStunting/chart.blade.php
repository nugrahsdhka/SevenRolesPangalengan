@php
    $availablePeriods = $availablePeriods ?? collect();
    $selectedPeriod   = $selectedPeriod ?? request('period');
@endphp

{{-- ===== Konten Tab: CHART ===== --}}
<div class="pb-2">
    <section id="tab-chart" class="{{ $currentView === 'chart' ? '' : 'hidden' }}">
        <div class="px-2 space-y-3">
    
            <div class="pt-3">
                <div class="bg-white backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="p-3">
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                            {{-- Title Section --}}
                            <div class="w-full lg:w-auto">
                                <div class="flex justify-between">
                                    <div>
                                        <h1 class="text-xl font-bold text-gray-900">
                                            Chart Data Stunting
                                        </h1>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Stunting Kecamatan Pangalengan
                                        </p>
                                    </div>
    
                                    {{-- Info Button - Mobile (kiri atas, small screen) --}}
                                    <button id="openChartInfoModalBtnMobile"
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
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
                                {{-- Filter Periode --}}
                                <div class="flex-1 sm:flex-initial">
                                    <div
                                        class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                        <form action="{{ route('data-stunting') }}"
                                            method="GET"
                                            id="chartFilterFormHeader"
                                            class="p-3 sm:p-4 flex flex-wrap items-end gap-3 sm:gap-4">

                                            {{-- Pastikan tetap di tab chart --}}
                                            <input type="hidden" name="view" value="chart">

                                            {{-- Periode --}}
                                            <div class="flex-1 min-w-[200px]">
                                                <label
                                                    class="flex items-center gap-2 text-sm font-semibold text-gray-700 mb-1">
                                                    <div
                                                        class="flex-shrink-0 w-5 h-5 bg-red-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                    Periode
                                                </label>

                                                <select id="chartPeriodeInput"
                                                        name="period"
                                                        class="w-full sm:w-48 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700
                                                            focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:outline-none
                                                            hover:border-gray-400 transition-colors duration-200">
                                                    <option value="" @selected(empty($selectedPeriod))>
                                                        Semua Periode
                                                    </option>
                                                    @forelse ($availablePeriods as $ym)
                                                        @php
                                                            try {
                                                                $periodCarbon = \Carbon\Carbon::createFromFormat('Y-m', $ym);
                                                                $label = $periodCarbon->isoFormat('MMMM Y');
                                                            } catch (\Exception $e) {
                                                                $label = $ym;
                                                            }
                                                        @endphp
                                                        <option value="{{ $ym }}" @selected($selectedPeriod === $ym)>
                                                            {{ $label }}
                                                        </option>
                                                    @empty
                                                        <option value="{{ now()->format('Y-m') }}" selected>
                                                            {{ now()->isoFormat('MMMM Y') }}
                                                        </option>
                                                    @endforelse
                                                </select>
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
                                                            stroke-width="2"
                                                            d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                                                    </svg>
                                                    <span>Tampilkan Chart</span>
                                                </button>
                                            </div>

                                        </form>
                                    </div>
                                </div>

                                {{-- Tombol Info Modal - Desktop (kanan) --}}
                                <button id="openChartInfoModalBtnDesktop"
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
    
            {{-- ========== CARD CHART ========== --}}
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                {{-- CHART WRAPPER (tanpa header lagi biar nggak dobel) --}}
                <div class="bg-white">
                    <div class="p-3 sm:p-4">
                        <div id="chartContainer"
                             class="relative w-full h-[55vh] md:h-[80vh]">
                            <canvas
                                id="stuntingChart"
                                data-chart-url="{{ route('data-stunting.chart-zs', [
                                    'period'      => $selectedPeriod,
                                    'search'      => $search ?? null,
                                    'tingkat_zs'  => $tingkat_zs ?? null,
                                ]) }}"
                                class="absolute inset-0 w-full h-full">
                            </canvas>
    
                            {{-- Pesan loading chart --}}
                            <div id="chartMessage"
                                 class="absolute inset-0 flex flex-col items-center justify-center text-gray-500 hidden px-4">
                                <svg class="w-10 h-10 sm:w-14 sm:h-14 text-red-400 mb-3 sm:mb-4 animate-pulse"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span class="text-sm sm:text-base font-semibold text-gray-700 text-center">
                                    Memuat data chart...
                                </span>
                                <div class="mt-3 w-40 sm:w-48 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-red-500 to-rose-500 rounded-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            {{-- Modal Keterangan Chart --}}
            <div id="chartInfoModalOverlay"
                class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 hidden">
                <div id="chartInfoModalContent"
                    class="relative mx-auto border w-full max-w-md sm:max-w-lg md:max-w-xl shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0">

                    {{-- Modal Header --}}
                    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                        <p class="text-lg font-bold text-gray-800">Keterangan Chart Stunting</p>
                        <button id="closeChartInfoModalBtnHeader"
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
                                <strong>Sumbu X:</strong>
                                Menunjukkan nilai <strong>rata-rata Z-Score TB/U</strong>
                                (Tinggi Badan menurut Umur) tiap desa. Semakin ke kiri nilainya
                                semakin rendah (indikasi stunting lebih berat), semakin ke kanan
                                mendekati atau melewati nol berarti kondisi lebih baik.
                            </li>

                            <li>
                                <strong>Sumbu Y:</strong>
                                Daftar <strong>desa/kelurahan</strong> yang dibandingkan pada periode yang dipilih.
                                Biasanya diurutkan dari rata-rata Z-Score TB/U terendah ke tertinggi,
                                sehingga desa dengan masalah stunting lebih berat cenderung berada di bagian atas chart.
                            </li>

                            <li>
                                <strong>Setiap Bar:</strong>
                                Mewakili satu desa. Saat kursor diarahkan ke bar,
                                akan muncul tooltip yang menampilkan <strong>nama desa</strong>
                                dan nilai <strong>ZS TB/U (dibulatkan 2 digit)</strong>.
                            </li>

                            <li>
                                <strong>Warna Bar (Kategori Z-Score TB/U):</strong>
                                <ul class="ml-6 mt-1 space-y-1 text-xs list-none">
                                    <li>
                                        <span class="inline-block w-3 h-3 rounded-full mr-2 bg-red-500 align-middle"></span>
                                        <strong>Merah (Sangat Pendek):</strong>
                                        rata-rata &lt; -3 SD &rarr; masalah stunting sangat berat.
                                    </li>
                                    <li>
                                        <span class="inline-block w-3 h-3 rounded-full mr-2 bg-orange-500 align-middle"></span>
                                        <strong>Oranye (Pendek):</strong>
                                        rata-rata -3 SD s/d &lt; -2 SD &rarr; masih berisiko stunting.
                                    </li>
                                    <li>
                                        <span class="inline-block w-3 h-3 rounded-full mr-2 bg-green-500 align-middle"></span>
                                        <strong>Hijau (Normal):</strong>
                                        rata-rata -2 SD s/d +2 SD &rarr; kondisi pertumbuhan tinggi badan menurut umur tergolong normal.
                                    </li>
                                    <li>
                                        <span class="inline-block w-3 h-3 rounded-full mr-2 bg-blue-500 align-middle"></span>
                                        <strong>Biru (Tinggi):</strong>
                                        rata-rata &gt; +2 SD &rarr; rata-rata tinggi badan anak di desa tersebut di atas standar.
                                    </li>
                                    <li>
                                        <span class="inline-block w-3 h-3 rounded-full mr-2 bg-gray-400 align-middle"></span>
                                        <strong>Abu-abu (N/A):</strong>
                                        data tidak cukup atau nilai rata-rata tidak dapat dihitung.
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <strong>Garis Rata-Rata (Avg):</strong>
                                Ditampilkan sebagai garis putus-putus vertikal dengan label
                                <em>"Avg xx"</em>. Garis ini menunjukkan <strong>rata-rata keseluruhan Z-Score TB/U</strong>
                                dari seluruh desa pada periode dan filter yang sedang digunakan,
                                sehingga memudahkan untuk melihat desa yang berada <em>di bawah</em> atau <em>di atas</em> rata-rata.
                            </li>

                            <li>
                                <strong>Interpretasi Cepat:</strong>
                                Jika banyak bar berwarna <span class="font-semibold text-red-600">merah</span> atau
                                <span class="font-semibold text-orange-500">oranye</span> yang berada di sisi kiri,
                                berarti masih banyak desa dengan rata-rata Z-Score TB/U rendah (stunting berat).
                                Sebaliknya, semakin banyak bar <span class="font-semibold text-green-600">hijau</span> atau
                                <span class="font-semibold text-blue-600">biru</span> yang mendekati atau melewati nol,
                                menunjukkan kondisi pertumbuhan anak di kecamatan tersebut secara umum semakin baik.
                            </li>

                            <li>
                                <strong>Filter Periode & Pencarian:</strong>
                                Data pada chart mengikuti <strong>periode</strong> yang dipilih di dropdown
                                dan filter lain seperti pencarian desa atau tingkat Z-Score.
                                Ubah filter, lalu klik <em>"Tampilkan Chart"</em> untuk memperbarui visualisasi.
                            </li>
                        </ul>

                        <p class="mt-3 text-xs text-gray-500">
                            <em>*SD = Standar Deviasi dari median standar pertumbuhan WHO.</em>
                        </p>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-right rounded-b-xl">
                        <button id="closeChartInfoModalBtnFooter"
                                class="px-5 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

    <script>
        window.stuntingChartInstance = window.stuntingChartInstance || null;

        const chartMessageEl = document.getElementById('chartMessage');
        const chartContainerEl = document.getElementById('chartContainer');

        const openInfoModalButton = document.getElementById('openInfoModalBtn');
        const infoModalOverlay = document.getElementById('infoModalOverlay');
        const infoModalContent = document.getElementById('infoModalContent');
        const closeInfoModalButtonHeader = document.getElementById('closeInfoModalBtnHeader');
        const closeInfoModalButtonFooter = document.getElementById('closeInfoModalBtnFooter');

        const openChartInfoModalBtn       = document.getElementById('openChartInfoModalBtn');          
        const openChartInfoModalBtnMobile = document.getElementById('openChartInfoModalBtnMobile');    
        const openChartInfoModalBtnDesktop= document.getElementById('openChartInfoModalBtnDesktop');   

        const chartInfoModalOverlay = document.getElementById('chartInfoModalOverlay');
        const chartInfoModalContent = document.getElementById('chartInfoModalContent');

        const closeChartInfoModalButtonHeader = document.getElementById('closeChartInfoModalBtnHeader');
        const closeChartInfoModalButtonFooter = document.getElementById('closeChartInfoModalBtnFooter');

        async function loadChartData() {
            // Hancurkan instance lama
            if (window.stuntingChartInstance) {
                window.stuntingChartInstance.destroy();
                window.stuntingChartInstance = null;
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

            const urlParams = new URLSearchParams(window.location.search);
            const searchParam     = urlParams.get('search') || '';
            const tingkatZsParam  = urlParams.get('tingkat_zs') || '';
            const periodParam     = urlParams.get('period'); // boleh null / "" / "2025-01"

            // Buat URL fetch
            const chartDataUrl = new URL(@json(route('data-stunting.chart-zs')));

            if (searchParam) {
                chartDataUrl.searchParams.set('search', searchParam);
            }

            if (tingkatZsParam) {
                chartDataUrl.searchParams.set('tingkat_zs', tingkatZsParam);
            }

            if (periodParam !== null) {
                chartDataUrl.searchParams.set('period', periodParam);
            }

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
            const DATA_LABEL_ALIGN   = 'left';
            const DATA_LABEL_OFFSET  = 1;
            const LAYOUT_LEFT_PADDING = 0;

            // Registrasi plugin datalabel (sekali saja)
            if (window.ChartDataLabels && !window.__cdlRegistered) {
                Chart.register(window.ChartDataLabels);
                window.__cdlRegistered = true;
            }

            // Registrasi plugin garis rata-rata (sekali saja)
            if (!window.__avgLineRegistered) {
                const avgLinePlugin = {
                    id: 'avgLine',
                    afterDatasetsDraw(chart, _args, pluginOptions) {
                        const ca     = chart.chartArea;
                        const xScale = chart.scales?.x;
                        if (!ca || !xScale) return;

                        const rawVals = Array.isArray(pluginOptions.values) ? pluginOptions.values : [];
                        const nums    = rawVals.filter(v => Number.isFinite(v));
                        if (!nums.length) return;

                        const avg = nums.reduce((a, b) => a + b, 0) / nums.length;
                        if (avg < xScale.min || avg > xScale.max) return;

                        const xPos = xScale.getPixelForValue(avg);
                        const { top, bottom, left, right } = ca;
                        const ctx = chart.ctx;

                        ctx.save();

                        // Garis putus-putus rata-rata
                        const gradient = ctx.createLinearGradient(0, top, 0, bottom);
                        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.8)');
                        gradient.addColorStop(1, 'rgba(99, 102, 241, 0.3)');

                        ctx.strokeStyle   = gradient;
                        ctx.setLineDash([8, 4]);
                        ctx.lineWidth     = 2.5;
                        ctx.shadowColor   = 'rgba(99, 102, 241, 0.3)';
                        ctx.shadowBlur    = 4;
                        ctx.beginPath();
                        ctx.moveTo(xPos, top);
                        ctx.lineTo(xPos, bottom);
                        ctx.stroke();
                        ctx.setLineDash([]);
                        ctx.shadowBlur = 0;

                        // Label pill “Avg xx”
                        const labelText = 'Avg ' + avg.toFixed(2);
                        ctx.font = '600 12px Figtree, sans-serif';
                        const textW = ctx.measureText(labelText).width;
                        const padX = 10;
                        const boxH = 24;
                        const boxW = textW + padX * 2;
                        const r    = 12;
                        const boxY = top + 6;
                        const boxX = Math.min(Math.max(xPos - boxW / 2, left + 4), right - boxW - 4);

                        ctx.shadowColor   = 'rgba(99, 102, 241, 0.2)';
                        ctx.shadowBlur    = 8;
                        ctx.shadowOffsetY = 2;

                        const pillGradient = ctx.createLinearGradient(boxX, boxY, boxX, boxY + boxH);
                        pillGradient.addColorStop(0, 'rgba(255, 255, 255, 0.98)');
                        pillGradient.addColorStop(1, 'rgba(249, 250, 251, 0.98)');
                        ctx.fillStyle   = pillGradient;
                        ctx.strokeStyle = 'rgba(99, 102, 241, 0.25)';
                        ctx.lineWidth   = 1.5;

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

                        ctx.shadowBlur    = 0;
                        ctx.shadowOffsetY = 0;

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

            const canvas = document.getElementById('stuntingChart');
            if (!canvas) {
                console.error("Canvas 'stuntingChart' tidak ditemukan.");
                return;
            }

            const ctx2d = canvas.getContext('2d');

            // 🔥 PENTING: HANCURKAN INSTANCE LAMA SEBELUM BIKIN BARU
            if (window.stuntingChartInstance) {
                window.stuntingChartInstance.destroy();
                window.stuntingChartInstance = null;
            }
            const existingChart = Chart.getChart(canvas);
            if (existingChart) {
                existingChart.destroy();
            }


            ctx2d.clearRect(0, 0, canvas.width, canvas.height);

            // Kalau tidak ada data
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

            // Sort data berdasarkan nilai ZS
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

            const finiteVals = sortedValues.filter(v => Number.isFinite(v));
            const minValue   = finiteVals.length ? Math.min(...finiteVals) - 0.2 : -4;
            const maxValue   = finiteVals.length ? Math.max(...finiteVals) + 0.2 : 0.5;

            const chartValues = sortedValues.map(v => (v === null ? null : Number(v)));

            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;

            const TARGET_MAX = 0.2;
            const TARGET_MIN = -0.4;

            const xMax = Math.max(TARGET_MAX, 0);

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
            const borderColors     = chartValues.map(v => getBarColor(v).borderColor);
            const hoverBgColors    = chartValues.map(v => getBarColor(v).hoverBackgroundColor);

            try {
                window.stuntingChartInstance = new Chart(ctx2d, {
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
                        }]
                    },
                    options: {
                        indexAxis: 'y', // ⬅️ tetap horizontal seperti sebelumnya
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
                                max: xMax,
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
                                border: {
                                    display: false
                                }
                            },
                            y: {
                                grid: {
                                    display: false
                                },
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
                                border: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
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
                            avgLine: {
                                values: sortedValues
                            }
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
                                    ctx.fillRect(
                                        chartArea.left,
                                        chartArea.top,
                                        chartArea.right - chartArea.left,
                                        chartArea.bottom - chartArea.top
                                    );
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
                setTimeout(() => {
                    infoModalContent.classList.remove('scale-95', 'opacity-0');
                    infoModalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }

        function closeInfoModal() {
            if (infoModalOverlay && infoModalContent) {
                infoModalContent.classList.remove('scale-100', 'opacity-100');
                infoModalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    document.body.style.overflow = '';
                    infoModalOverlay.classList.add('hidden');
                }, 300);
            }
        }

        function openChartInfoModal() {
            if (chartInfoModalOverlay && chartInfoModalContent) {
                document.body.style.overflow = 'hidden';
                chartInfoModalOverlay.classList.remove('hidden');
                setTimeout(() => {
                    chartInfoModalContent.classList.remove('scale-95', 'opacity-0');
                    chartInfoModalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }

        function closeChartInfoModal() {
            if (chartInfoModalOverlay && chartInfoModalContent) {
                chartInfoModalContent.classList.remove('scale-100', 'opacity-100');
                chartInfoModalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    chartInfoModalOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                }, 300);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (openInfoModalButton) {
                openInfoModalButton.addEventListener('click', openInfoModal);
            }
            if (closeInfoModalButtonHeader) {
                closeInfoModalButtonHeader.addEventListener('click', closeInfoModal);
            }
            if (closeInfoModalButtonFooter) {
                closeInfoModalButtonFooter.addEventListener('click', closeInfoModal);
            }
            if (infoModalOverlay) {
                infoModalOverlay.addEventListener('click', function(event) {
                    if (event.target === infoModalOverlay) {
                        closeInfoModal();
                    }
                });
            }

            [openChartInfoModalBtn, openChartInfoModalBtnMobile, openChartInfoModalBtnDesktop]
                .filter(Boolean)
                .forEach(btn => {
                    btn.addEventListener('click', openChartInfoModal);
                });

            if (closeChartInfoModalButtonHeader) {
                closeChartInfoModalButtonHeader.addEventListener('click', closeChartInfoModal);
            }
            if (closeChartInfoModalButtonFooter) {
                closeChartInfoModalButtonFooter.addEventListener('click', closeChartInfoModal);
            }
            if (chartInfoModalOverlay) {
                chartInfoModalOverlay.addEventListener('click', function(event) {
                    if (event.target === chartInfoModalOverlay) {
                        closeChartInfoModal();
                    }
                });
            }

            // Tutup modal saat ESC
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    if (infoModalOverlay && !infoModalOverlay.classList.contains('hidden')) {
                        closeInfoModal();
                    }
                    if (chartInfoModalOverlay && !chartInfoModalOverlay.classList.contains('hidden')) {
                        closeChartInfoModal();
                    }
                }
            });

            // Kalau halaman ini sedang di view "chart", langsung load chart
            @if ($currentView === 'chart')
                loadChartData();
            @endif
        });

        // Re-render chart saat window resize (jika sedang di halaman chart)
        let resizeTimeout;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                if (window.stuntingChartInstance) {
                    console.log('Window resized, redrawing chart...');
                    loadChartData();
                }
            }, 300);
        });
    </script>