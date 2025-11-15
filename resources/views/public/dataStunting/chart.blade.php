{{-- resources/views/public/dataStunting/chart.blade.php --}}

@php
    // fallback biar aman kalau lupa dikirim dari controller
    $availablePeriods = $availablePeriods ?? collect();
    $selectedPeriod   = $selectedPeriod ?? request('period');
@endphp

{{-- ===== Konten Tab: CHART ===== --}}
<section id="tab-chart" class="{{ $currentView === 'chart' ? '' : 'hidden' }}">
    <div class="px-2 space-y-3">

        {{-- Header Section: Title + Info Button (MOBILE) --}}
        <div class="flex items-start justify-between gap-4 px-1 md:hidden">
            {{-- Title Section - Mobile --}}
            <div class="flex-1 mb-2">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0 flex items-center justify-center">
                        <div class="text-center">
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-1 tracking-tight">
                                Chart Data Stunting
                            </h1>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Button - Mobile (Top Right) --}}
            <button id="openChartInfoModalBtnMobile"
                class="flex-shrink-0 w-12 h-12 flex items-center justify-center text-white bg-gradient-to-br from-red-600 via-red-500 to-pink-600 rounded-2xl shadow-lg hover:shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300 group relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <svg class="w-6 h-6 relative z-10 transition-transform group-hover:rotate-12 duration-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                </svg>
            </button>
        </div>

        {{-- ========== CARD FILTER (FORM) ========== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 lg:p-6">
                <div class="flex flex-col lg:flex-row items-stretch gap-4">

                    {{-- Filter Card (isi form) --}}
                    <div class="flex-1">
                        <div class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                            <form action="{{ route('data-stunting') }}"
                                  method="GET"
                                  id="chartFilterFormHeader"
                                  class="p-4 sm:p-5 lg:p-6">
                                
                                {{-- Pastikan tetap di tab chart --}}
                                <input type="hidden" name="view" value="chart">

                                {{-- Grid filter: Periode + Tombol --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-5">

                                    {{-- Periode Filter --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                                            <div class="flex-shrink-0 w-5 h-5 bg-red-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <span>Periode</span>
                                        </label>
                                        <div class="relative">
                                            <select id="chartPeriodeInput"
                                                    name="period"
                                                    class="w-full px-4 py-3 pr-10 border-2 border-gray-200 rounded-xl text-sm font-medium text-gray-700 bg-white appearance-none cursor-pointer
                                                        focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:outline-none
                                                        hover:border-gray-300 hover:shadow-sm transition-all duration-200">
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
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="flex items-end">
                                        <button type="submit"
                                            class="w-full px-6 py-3 bg-gradient-to-r from-red-600 via-red-500 to-pink-600 text-white rounded-xl text-sm font-bold
                                                shadow-lg hover:shadow-xl hover:from-red-700 hover:via-red-600 hover:to-pink-700
                                                transform hover:scale-105 active:scale-95 transition-all duration-300
                                                flex items-center justify-center gap-2 group relative overflow-hidden">
                                            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                            <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 6h16M4 10h16M4 14h10M4 18h6" />
                                            </svg>
                                            <span class="relative z-10">Tampilkan Chart</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> {{-- end Filter Card --}}
                </div>
            </div>
        </div>

        {{-- ========== CARD CHART ========== --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            {{-- TITLE + INFO DESKTOP --}}
            <div class="hidden md:flex justify-between items-center bg-white px-6 py-4 border-b border-gray-200">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight">
                        Chart Data Stunting
                    </h2>
                </div>

                <div>
                    <button id="openChartInfoModalBtnDesktop"
                        class="flex-shrink-0 w-14 h-14 flex items-center justify-center text-white bg-gradient-to-br from-red-600 via-red-500 to-pink-600 rounded-2xl shadow-lg hover:shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300 group relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg class="w-7 h-7 relative z-10 transition-transform group-hover:rotate-12 duration-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- CHART WRAPPER --}}
            <div class="bg-white">
                <div class="p-3 sm:p-4">
                    <div id="chartContainer"
                         class="relative w-full min-h-[320px] h-[56vh] sm:h-[60vh] md:h-[65vh] lg:h-[70vh]">
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
                            <svg class="w-10 h-10 sm:w-14 sm:h-14 text-blue-400 mb-3 sm:mb-4 animate-pulse"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="text-sm sm:text-base font-semibold text-gray-700 text-center">
                                Memuat data chart...
                            </span>
                            <div class="mt-3 w-40 sm:w-48 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-pulse"></div>
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
                        <li><strong>Sumbu X:</strong> Menunjukkan nilai rata-rata Z-Score TB/U (Tinggi Badan menurut Umur) di setiap desa.</li>
                        <li><strong>Sumbu Y:</strong> Daftar desa/kelurahan yang datanya dibandingkan.</li>
                        <li><strong>Warna Bar:</strong> Biasanya mencerminkan kategori status stunting (misalnya sangat pendek, pendek, normal, tinggi).</li>
                        <li><strong>Garis Batas:</strong> Dapat digunakan untuk menandai batas penting seperti -3 SD, -2 SD, dan +2 SD.</li>
                        <li>
                            Chart ini membantu melihat desa mana yang masih memiliki rata-rata Z-Score TB/U rendah (indikasi stunting)
                            dan desa mana yang sudah berada di kisaran normal atau lebih baik.
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

{{-- SCRIPT MODAL CHART --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvasEl     = document.getElementById('stuntingChart');
        const chartMessage = document.getElementById('chartMessage');

        if (!canvasEl) return;

        const ctx      = canvasEl.getContext('2d');
        const chartUrl = canvasEl.dataset.chartUrl; // <-- ini akan jadi /data-stunting/chart-zs?period=...

        if (!chartUrl) return;

        if (chartMessage) {
            chartMessage.classList.remove('hidden');
        }

        fetch(chartUrl)
            .then(res => res.json())
            .then(data => {
                if (chartMessage) chartMessage.classList.add('hidden');

                const labels = data.labels || [];
                const values = data.values || [];

                if (window.stuntingChartInstance) {
                    window.stuntingChartInstance.destroy();
                }

                window.stuntingChartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Rata-rata ZS TB/U',
                            data: values,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                    }
                });
            })
            .catch(err => {
                console.error('Error load chart data:', err);
                if (chartMessage) {
                    chartMessage.classList.remove('hidden');
                    chartMessage.querySelector('span').textContent =
                        'Gagal memuat data chart.';
                }
            });
    });
</script>

