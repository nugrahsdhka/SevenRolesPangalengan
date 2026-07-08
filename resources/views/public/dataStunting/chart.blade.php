@php
    $availablePeriods = $availablePeriods ?? collect();
    $selectedPeriod = $selectedPeriod ?? request('period');
    $currentView = $currentView ?? 'chart';
@endphp

{{-- ===== Konten Tab: CHART ===== --}}
<div class="space-y-2">
    {{-- ========== HEADER: FILTER SECTION ========== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-2">
            <form action="{{ route('data-stunting') }}" method="GET" id="chartFilterFormHeader"
                  class="flex flex-col lg:flex-row items-end gap-4">

                {{-- Pastikan tetap di tab chart --}}
                <input type="hidden" name="view" value="chart">

                {{-- Filter Periode --}}
                <div class="w-full lg:w-48">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Periode Laporan
                    </label>
                    <select name="period" id="chartPeriodeInput"
                            class="w-full px-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary-500 transition-all cursor-pointer">
                        <option value="" @selected(empty($selectedPeriod))>Semua Periode</option>
                        @foreach ($availablePeriods as $ym)
                            @php
                                try {
                                    $label = \Carbon\Carbon::createFromFormat('Y-m', $ym)->isoFormat('MMMM Y');
                                } catch (\Exception $e) {
                                    $label = $ym;
                                }
                            @endphp
                            <option value="{{ $ym }}" @selected($selectedPeriod === $ym)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Search Desa (Placeholder untuk menyamakan layout filter) --}}
                <div class="w-full lg:flex-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">
                        Pencarian Spesifik
                    </label>
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama desa..."
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-sm font-bold text-gray-700 focus:ring-2 focus:ring-primary-500 transition-all">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-2 w-full lg:w-auto">
                    <button type="submit"
                            class="flex-1 lg:flex-none px-8 py-3 bg-primary-500 text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary-600 shadow-lg shadow-primary-200 transition-all active:scale-95">
                        Tampilkan Chart
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========== CARD CHART AREA ========== --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-10">
            <div id="chartContainer" class="relative w-full h-[60vh] md:h-[75vh]">
                <canvas id="stuntingChart"
                        data-chart-url="{{ route('data-stunting.chart-zs', ['period' => $selectedPeriod, 'search' => request('search'), 'tingkat_zs' => request('tingkat_zs')]) }}"
                        class="absolute inset-0 w-full h-full">
                </canvas>

                {{-- Loading State --}}
                <div id="chartMessage"
                     class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm z-10 hidden">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 border-4 border-primary-200 border-t-primary-500 rounded-full animate-spin"></div>
                        <p class="mt-4 text-sm font-black text-gray-900 uppercase tracking-widest">Sinkronisasi Data...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

    const openChartInfoModalBtn = document.getElementById('openChartInfoModalBtn');
    const openChartInfoModalBtnMobile = document.getElementById('openChartInfoModalBtnMobile');
    const openChartInfoModalBtnDesktop = document.getElementById('openChartInfoModalBtnDesktop');

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
        const searchParam = urlParams.get('search') || '';
        const tingkatZsParam = urlParams.get('tingkat_zs') || '';
        const periodParam = urlParams.get('period');

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
                    <svg class="w-12 h-12 sm:w-16 sm:h-16 text-primary-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm sm:text-base font-semibold text-primary-600">Gagal memuat data chart</span>
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
        const DATA_LABEL_ALIGN = 'left';
        const DATA_LABEL_OFFSET = 1;
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

                    // Garis putus-putus rata-rata
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

                    // Label pill "Avg xx"
                    const labelText = 'Avg ' + avg.toFixed(2);
                    ctx.font = '600 12px Figtree, sans-serif';
                    const textW = ctx.measureText(labelText).width;
                    const padX = 10;
                    const boxH = 24;
                    const boxW = textW + padX * 2;
                    const r = 12;
                    const boxY = top + 6;
                    const boxX = Math.min(Math.max(xPos - boxW / 2, left + 4), right - boxW - 4);

                    ctx.shadowColor = 'rgba(99, 102, 241, 0.2)';
                    ctx.shadowBlur = 8;
                    ctx.shadowOffsetY = 2;

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
        const minValue = finiteVals.length ? Math.min(...finiteVals) - 0.2 : -4;
        const maxValue = finiteVals.length ? Math.max(...finiteVals) + 0.2 : 0.5;

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
        const borderColors = chartValues.map(v => getBarColor(v).borderColor);
        const hoverBgColors = chartValues.map(v => getBarColor(v).hoverBackgroundColor);

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
                    '<svg class="w-12 h-12 sm:w-16 sm:h-16 text-primary-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>' +
                    '</svg>' +
                    '<span class="text-sm sm:text-base font-semibold text-primary-600">Terjadi error saat menampilkan chart</span>' +
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