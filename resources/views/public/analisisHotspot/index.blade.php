<x-guest-layout>
    <x-slot name="title">
        RSP | Analisis Hotspot
    </x-slot>

    @include('layouts.nav_public')

    {{-- Header --}}
    <div class="p-1">
        <div class="bg-white backdrop-blur-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-3">
                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                    {{-- Title Section --}}
                    <div class="w-full lg:w-auto">
                        <div class="flex justify-between">
                            <div>
                                <h1 class="text-xl font-bold text-gray-900">
                                    Analisis Hotspot
                                </h1>
                                <p class="mt-1 text-sm text-gray-500">
                                    Stunting Kecamatan Pangalengan
                                </p>
                            </div>
    
                            <button id="openInfoModalBtnDesktop"
                                class="block md:hidden flex-shrink-0 self-center sm:self-auto p-3 text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-xl
                                    shadow-lg hover:shadow-xl hover:from-red-700 hover:to-pink-700
                                    transform hover:scale-110 active:scale-95 transition-all duration-200
                                    group relative">
                                <svg class="w-6 h-6 transition-transform group-hover:rotate-12 duration-200" fill="currentColor" viewBox="0 0 20 20">
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
                            <div class="bg-white/95 backdrop-blur-lg rounded-2xl shadow-lg border border-gray-200/50 overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <form id="periodeForm" class="p-3 sm:p-4 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-3 flex-1">
                                        <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 whitespace-nowrap">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Periode
                                        </label>
                                        <select id="periodeInput"
                                            class="w-full sm:flex-1 sm:min-w-[160px] px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700
                                                focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:outline-none
                                                hover:border-gray-400 transition-colors duration-200">

                                            @forelse ($availablePeriods as $p)
                                                @php
                                                    $ym = \Carbon\Carbon::parse($p)->format('Y-m');
                                                @endphp

                                                <option value="{{ $ym }}">
                                                    {{ \Carbon\Carbon::parse($p)->isoFormat('MMMM Y') }}
                                                </option>
                                            @empty
                                                <option value="{{ now()->format('Y-m') }}" selected>
                                                    {{ now()->isoFormat('MMMM Y') }}
                                                </option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <button type="button" id="applyPeriodeBtn"
                                        class="w-full sm:w-auto px-6 py-2.5 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-xl text-sm font-semibold
                                            shadow-md hover:shadow-xl hover:from-red-700 hover:to-pink-700
                                            transform hover:scale-105 active:scale-95 transition-all duration-200
                                            flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Tampilkan
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Tombol Info Modal --}}
                        <button id="openInfoModalBtnMobile"
                            class="hidden md:block flex-shrink-0 self-center sm:self-auto p-3 text-white bg-gradient-to-r from-red-600 to-pink-600 rounded-xl
                                shadow-lg hover:shadow-xl hover:from-red-700 hover:to-pink-700
                                transform hover:scale-110 active:scale-95 transition-all duration-200
                                group relative">
                            <svg class="w-6 h-6 transition-transform group-hover:rotate-12 duration-200" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="tab-map">
        <div class="bg-white rounded-lg overflow-hidden flex items-center justify-center">
            <div class="p-1 w-full">
                {{-- Map Container --}}
                <div id="map" class="rounded-lg w-full h-[73vh] md:h-[70vh]"></div>
            </div>
        </div>

        {{-- Modal --}}
        <div id="infoModalOverlay"
            class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4 hidden">
            <div class="relative mx-auto border w-full max-w-md sm:max-w-lg md:max-w-xl shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0"
                id="infoModalContent">
                {{-- Modal Header --}}
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <p class="text-lg font-bold text-gray-800">Keterangan Peta Hotspot</p>
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
                            <strong>Wilayah (Poligon):</strong> Setiap batas berwarna pada peta merepresentasikan satu
                            desa
                            di Kecamatan Pangalengan.
                        </li>
                        <li>
                            <strong>Rata-rata ZS TB/U:</strong> Warna desa ditentukan oleh nilai rata-rata Z-Score
                            <em>Tinggi Badan menurut Umur (TB/U)</em>, yang menjadi indikator utama stunting.
                        </li>
                        <li>
                            <strong>Popup Desa:</strong> Klik pada suatu desa untuk melihat:
                            <ul class="ml-6 mt-1 space-y-1 text-xs list-none">
                                <li>Status Desa (kategori berdasarkan ZS TB/U)</li>
                                <li>Rata-rata ZS TB/U</li>
                                <li>Jumlah kasus stunting</li>
                                <li>Total anak yang masuk dalam perhitungan</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Klasifikasi Warna Z-Score TB/U:</strong>
                            <ul class="ml-6 mt-2 space-y-1 text-xs list-none">
                                <li>
                                    <span
                                        class="inline-block w-3 h-3 rounded-full mr-2 bg-blue-500 align-middle"></span>
                                    <strong>Biru (Tinggi/Gizi Lebih):</strong> (&gt; +2 SD)
                                </li>
                                <li>
                                    <span
                                        class="inline-block w-3 h-3 rounded-full mr-2 bg-green-500 align-middle"></span>
                                    <strong>Hijau (Normal):</strong> (-2 SD s/d +2 SD)
                                </li>
                                <li>
                                    <span
                                        class="inline-block w-3 h-3 rounded-full mr-2 bg-orange-500 align-middle"></span>
                                    <strong>Oranye (Pendek/Kurang Gizi):</strong> (-3 SD s/d &lt; -2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-red-500 align-middle"></span>
                                    <strong>Merah (Sangat Pendek/Gizi Buruk):</strong> (&lt; -3 SD)
                                </li>
                                <li>
                                    <span
                                        class="inline-block w-3 h-3 rounded-full mr-2 bg-gray-400 align-middle"></span>
                                    <strong>Abu-abu (N/A):</strong> Data belum tersedia
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
                    <button id="closeInfoModalBtnFooter"
                        class="px-5 py-2 bg-gray-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
        {{-- Leaflet CSS --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

        <style>
            /* Fix untuk navbar & modal */
            .leaflet-container {
                z-index: 0 !important;
            }

            /* Hindari Tailwind ngerusak style image Leaflet */
            .leaflet-container img {
                max-width: none !important;
            }

            #map {
                width: 100%;
                /* tinggi diatur via Tailwind: h-[60vh] md:h-[600px] */
            }

            /* Popup style */
            .leaflet-popup-content-wrapper {
                border-radius: 16px;
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                    0 10px 10px -5px rgba(0, 0, 0, 0.04);
                border: 1px solid #e5e7eb;
            }

            .leaflet-popup-content {
                margin: 16px;
                font-family: system-ui, -apple-system, sans-serif;
            }

            .leaflet-popup-tip {
                box-shadow: 0 3px 14px rgba(0, 0, 0, 0.1);
            }

            /* Hover efek batas desa */
            .leaflet-interactive {
                transition: all 0.2s ease;
            }

            .leaflet-interactive:hover {
                filter: brightness(1.1);
                stroke-width: 2;
            }

            /* Tooltip nama desa */
            .desa-tooltip {
                background: rgba(31, 41, 55, 0.9);
                /* gray-800 */
                color: #fff;
                border-radius: 9999px;
                padding: 4px 10px;
                border: none;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
                font-size: 0.75rem;
                /* text-xs */
                font-weight: 600;
                /* semibold */
                pointer-events: none;
                /* biar gak ganggu klik */
            }

            .desa-tooltip::before {
                display: none;
            }

            /* Hilangin outline kotak saat diklik */
            .leaflet-container .leaflet-interactive:focus,
            .leaflet-container .leaflet-interactive:focus-visible {
                outline: none;
            }

            .leaflet-container:focus,
            .leaflet-container:focus-visible {
                outline: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        {{-- Leaflet --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                let map = L.map('map');
                let geoLayer = null;

                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                    {
                        maxZoom: 19,
                        attribution: '&copy; Esri'
                    }
                ).addTo(map);

                const periodeInput = document.getElementById('periodeInput');
                const applyBtn = document.getElementById('applyPeriodeBtn');

                function loadHotspotData() {
                    const period = periodeInput.value;

                    Promise.all([
                        fetch(`{{ route('api.hotspot') }}?period=${period}`).then(r => r.json()),
                        fetch('{{ asset('maps/PetaDesaKecPangalengan.geojson') }}').then(r => r.json())
                    ])
                    .then(([stats, geojson]) => {
                        if (geoLayer) {
                            map.removeLayer(geoLayer);
                        }

                        function getColor(zs) {
                            if (zs === null || zs === undefined || isNaN(zs)) return '#9E9E9E';
                            if (zs < -3) return '#D32F2F';
                            if (zs < -2) return '#F57C00';
                            if (zs <= 2) return '#388E3C';
                            return '#1976D2';
                        }

                        geoLayer = L.geoJSON(geojson, {
                            style: feature => {
                                const rawName = feature.properties.NAME_4?.trim().toUpperCase() ?? '';
                                const item = stats[rawName] ?? null;
                                const zs = item ? item.avg_zs_tbu : null;

                                return {
                                    color: '#FFF',
                                    weight: 0.4,
                                    fillColor: getColor(zs),
                                    fillOpacity: 0.45
                                };
                            },
                            onEachFeature: (feature, layer) => {
                                const rawName = feature.properties.NAME_4?.trim().toUpperCase() ?? '';
                                const item = stats[rawName] ?? null;

                                let html = `
                                    <div class="space-y-3">
                                        <div class="border-b border-gray-200 pb-2">
                                            <h3 class="text-lg font-bold text-gray-900">${rawName}</h3>
                                        </div>
                                `;

                                if (item) {
                                    html += `
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 text-sm">Status Desa:</span>
                                                <span class="font-semibold bg-blue-50 px-3 py-1 rounded-full text-xs">${item.kategori}</span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-600 text-sm">Rata-rata ZS TB/U:</span>
                                                <span class="font-bold text-sm" style="color:${getColor(item.avg_zs_tbu)}">
                                                    ${item.avg_zs_tbu}
                                                </span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-600 text-sm">Kasus Stunting:</span>
                                                <span class="text-red-600 font-semibold text-sm">${item.total_kasus_stunting}</span>
                                            </div>

                                            <div class="flex justify-between">
                                                <span class="text-gray-600 text-sm">Total Anak:</span>
                                                <span class="text-gray-900 font-semibold text-sm">${item.total_anak}</span>
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    html += `
                                        <div class="bg-gray-50 rounded-lg p-3">
                                            <p class="text-sm text-gray-600 text-center">
                                                Data belum tersedia
                                            </p>
                                        </div>
                                    `;
                                }

                                html += `</div>`;
                                layer.bindPopup(html);
                            }
                        }).addTo(map);

                        const bounds = geoLayer.getBounds();
                        map.fitBounds(bounds, { padding: [20, 20] });

                        const minZoom = 12;
                        if (map.getZoom() < minZoom) map.setZoom(minZoom);

                    }).catch(err => console.error(err));
                }

                // Load awal
                loadHotspotData();

                // Jika user klik tombol "Tampilkan"
                applyBtn.addEventListener('click', loadHotspotData);
            });
        </script>

        {{-- Modal --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const modalOverlay = document.getElementById('infoModalOverlay');
                const modalContent = document.getElementById('infoModalContent');

                // Semua tombol open modal
                const openButtons = [
                    document.getElementById('openInfoModalBtn'),
                    document.getElementById('openInfoModalBtnMobile'),
                    document.getElementById('openInfoModalBtnDesktop'),
                ].filter(Boolean); // buang null

                const closeBtnHeader = document.getElementById('closeInfoModalBtnHeader');
                const closeBtnFooter = document.getElementById('closeInfoModalBtnFooter');

                function openModal() {
                    modalOverlay.classList.remove('hidden');

                    // Trigger reflow
                    void modalContent.offsetWidth;

                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }

                function closeModal() {
                    modalContent.classList.add('scale-95', 'opacity-0');
                    modalContent.classList.remove('scale-100', 'opacity-100');

                    setTimeout(() => {
                        modalOverlay.classList.add('hidden');
                    }, 300);
                }

                // Bind semua tombol opener
                openButtons.forEach(btn => {
                    btn.addEventListener('click', openModal);
                });

                // Bind tombol penutup
                if (closeBtnHeader) closeBtnHeader.addEventListener('click', closeModal);
                if (closeBtnFooter) closeBtnFooter.addEventListener('click', closeModal);

                // Klik di luar konten modal = tutup
                if (modalOverlay) {
                    modalOverlay.addEventListener('click', function(event) {
                        if (event.target === modalOverlay) {
                            closeModal();
                        }
                    });
                }

                // Tombol ESC
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape' && !modalOverlay.classList.contains('hidden')) {
                        closeModal();
                    }
                });
            });
        </script>
    @endpush

</x-guest-layout>
