<x-guest-layout>
    <x-slot name="title">
        RSP | Analisis Hotspot
    </x-slot>

    @include('layouts.nav_public')

    {{-- Header --}}
    <div class="p-1">
        <div class="bg-white backdrop-blur-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-3">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">
                            Analisis Hotspot
                        </h1>
                        <p class="mt-1 text-sm text-gray-500">
                            Stunting Kecamatan Pangalengan
                        </p>
                    </div>

                    {{-- Tombol Modal --}}
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
            <div
                class="relative mx-auto border w-full max-w-md sm:max-w-lg md:max-w-xl shadow-lg rounded-xl bg-white transform transition-all duration-300 scale-95 opacity-0"
                id="infoModalContent"
            >
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
                            <strong>Wilayah (Poligon):</strong> Setiap batas berwarna pada peta merepresentasikan satu desa
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
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-blue-500 align-middle"></span>
                                    <strong>Biru (Tinggi/Gizi Lebih):</strong> (&gt; +2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-green-500 align-middle"></span>
                                    <strong>Hijau (Normal):</strong> (-2 SD s/d +2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-orange-500 align-middle"></span>
                                    <strong>Oranye (Pendek/Kurang Gizi):</strong> (-3 SD s/d &lt; -2 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-red-500 align-middle"></span>
                                    <strong>Merah (Sangat Pendek/Gizi Buruk):</strong> (&lt; -3 SD)
                                </li>
                                <li>
                                    <span class="inline-block w-3 h-3 rounded-full mr-2 bg-gray-400 align-middle"></span>
                                    <strong>Abu-abu (N/A):</strong> Data belum tersedia / belum dipublikasi.
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
        <link rel="stylesheet"
            href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
            crossorigin=""/>

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
                background: rgba(31, 41, 55, 0.9); /* gray-800 */
                color: #fff;
                border-radius: 9999px;
                padding: 4px 10px;
                border: none;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
                font-size: 0.75rem;   /* text-xs */
                font-weight: 600;     /* semibold */
                pointer-events: none; /* biar gak ganggu klik */
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
                integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
                crossorigin="">
        </script>

        {{-- Leaflet --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const map = L.map('map');

                L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
                {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.esri.com/en-us/home" target="_blank" rel="noopener noreferrer">Esri</a>'
                }
                ).addTo(map);

                // Ambil data dari DB + GeoJSON
                Promise.all([
                    fetch('{{ route('api.hotspot') }}').then(r => r.json()),
                    fetch('{{ asset('maps/PetaDesaKecPangalengan.geojson') }}').then(r => r.json())
                ])
                .then(([stats, geojson]) => {
                    console.log('API hotspot:', stats);

                    // Fungsi warna (sama seperti DataStunting)
                    function getColor(zs) {
                        if (zs === null || zs === undefined || isNaN(zs)) {
                            return '#9E9E9E'; // abu-abu N/A
                        }
                        if (zs < -3)  return '#D32F2F'; // merah tua (Sangat Pendek)
                        if (zs < -2)  return '#F57C00'; // oranye (Pendek)
                        if (zs <= 2)  return '#388E3C'; // hijau tua (Normal)
                        return '#1976D2';              // biru tua (Tinggi)
                    }

                    const layer = L.geoJSON(geojson, {
                        style: feature => {
                            const rawName  = feature.properties.NAME_4 || '';
                            const desaKey  = rawName.trim().toUpperCase();
                            const item     = stats[desaKey] || null;
                            const zs       = item ? item.avg_zs_tbu : null;

                            return {
                                color: '#FFF',
                                weight: 0.25,
                                fillColor: getColor(zs),
                                fillOpacity: 0.45
                            };
                        },
                        onEachFeature: (feature, layer) => {
                            const rawName = feature.properties.NAME_4 || '';
                            const desaKey = rawName.trim().toUpperCase();
                            const item    = stats[desaKey] || null;

                            layer.bindTooltip(rawName, {
                                direction: 'top',
                                permanent: false,
                                sticky: true,
                                offset: L.point(0, -12), 
                                className: 'desa-tooltip'
                            });

                            // Popup detail saat diklik
                            let html = `
                                <div class="space-y-3">
                                    <div class="border-b border-gray-200 pb-2">
                                        <h3 class="text-lg font-bold text-gray-900">${rawName}</h3>
                                    </div>
                            `;

                            if (item) {
                                html += `
                                    <div class="space-y-2">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm">Status Desa:</span>
                                            <span class="font-semibold text-gray-900 text-xs bg-blue-50 px-3 py-1 rounded-full">${item.kategori}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm">Rata-rata ZS TB/U:</span>
                                            <span class="font-bold text-sm" style="color:${getColor(item.avg_zs_tbu)}">${item.avg_zs_tbu}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm">Kasus Stunting:</span>
                                            <span class="font-semibold text-red-600 text-sm">${item.total_kasus_stunting}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 text-sm">Total Anak:</span>
                                            <span class="font-semibold text-gray-900 text-sm">${item.total_anak}</span>
                                        </div>
                                    </div>
                                `;
                            } else {
                                html += `
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-sm text-gray-600 text-center">
                                            <svg class="w-5 h-5 inline-block mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Data stunting belum tersedia / belum dipublikasi.
                                        </p>
                                    </div>
                                `;
                            }

                            html += `</div>`;

                            layer.bindPopup(html, {
                                maxWidth: 300,
                                className: 'custom-popup'
                            });
                        }
                    }).addTo(map);

                    // Atur posisi awal dan minimal zoom
                    const bounds = layer.getBounds();

                    // fitBounds dulu biar semua desa masuk layar
                    map.fitBounds(bounds, { padding: [20, 20] });

                    const minZoom = 12; 
                    if (map.getZoom() < minZoom) {
                        map.setZoom(minZoom);
                    }
                })
                .catch(err => {
                    console.error('Gagal memuat data hotspot:', err);
                });
            });
        </script>

        {{-- Modal --}}
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
    @endpush

</x-guest-layout>
