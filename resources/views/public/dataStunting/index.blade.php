{{-- resources/views/public_view/dataStunting/index.blade.php --}}
<x-app-layout>
    {{-- ======================= TITLE ======================= --}}
    <x-slot name="title">
        RSP | Data Stunting
    </x-slot>

    {{-- ======================= NAV PUBLIC ======================= --}}
    @include('layouts.nav_public')

    @php
        // Ambil semua query param, lalu pakai untuk build URL tab
        $queryParams = request()->query();

        $tableUrl = request()->fullUrlWithQuery(array_merge($queryParams, [
            'view' => 'table',
        ]));

        $chartUrl = request()->fullUrlWithQuery(array_merge($queryParams, [
            'view' => 'chart',
        ]));
    @endphp

    {{-- ======================= TAB NAVIGATION (TABEL / CHART) ======================= --}}
    <div class="border-b border-gray-200 bg-gray-50">
        <div class="flex">
            {{-- Tombol Tab Tabel --}}
            <a href="{{ $tableUrl }}"
               id="tab-button-table"
               data-target="tab-table"
               class="relative flex-1 px-6 py-4 text-sm font-semibold focus:outline-none group transition-colors duration-200
                    {{ $currentView === 'table'
                        ? 'text-gray-900 bg-white'
                        : 'text-gray-500 bg-gray-50 hover:text-gray-700 hover:bg-gray-100' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Tabel
                </div>
                <div
                    class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-pink-600 {{ $currentView !== 'table' ? 'hidden' : '' }}">
                </div>
            </a>

            {{-- Tombol Tab Chart --}}
            <a href="{{ $chartUrl }}"
               id="tab-button-chart"
               data-target="tab-chart"
               class="relative flex-1 px-6 py-4 text-sm font-semibold focus:outline-none group transition-colors duration-200
                    {{ $currentView === 'chart'
                        ? 'text-gray-900 bg-white'
                        : 'text-gray-500 bg-gray-50 hover:text-gray-700 hover:bg-gray-100' }}">
                <div class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Chart
                </div>
                <div
                    class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 to-pink-600 {{ $currentView !== 'chart' ? 'hidden' : '' }}">
                </div>
            </a>
        </div>
    </div>

    {{-- ======================= KONTEN: HANYA RENDER YANG AKTIF ======================= --}}
    @if ($currentView === 'table')
        {{-- TAB: TABLE (FILTER + TABEL + MODAL INFO) --}}
        @include('public.dataStunting.table', [
            'currentView'  => $currentView,
            'search'       => $search ?? '',
            'tingkat_zs'   => $tingkat_zs ?? '',
            'dataStunting' => $dataStunting,
        ])
    @elseif ($currentView === 'chart')
        {{-- TAB: CHART (CHART + MODAL INFO) --}}
        @include('public.dataStunting.chart', [
            'currentView' => $currentView,
        ])
    @endif

    {{-- ======================= SCRIPT: CHART / MODAL ======================= --}}
    <script>
        window.stuntingChartInstance = window.stuntingChartInstance || null;

        const chartMessageEl = document.getElementById('chartMessage');
        const chartContainerEl = document.getElementById('chartContainer');

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

            if (openChartInfoModalButton) {
                openChartInfoModalButton.addEventListener('click', openChartInfoModal);
            }
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
</x-app-layout>
